<script setup lang="ts">
import type { TableColumn } from '@nuxt/ui'
import { locationService } from '~/services/locationService'
import type { Location } from '~/types/location'

definePageMeta({ layout: 'default' })

const columns: TableColumn<Location>[] = [
  { accessorKey: 'name', header: 'Tên vị trí' },
  { accessorKey: 'address', header: 'Địa chỉ' },
  { id: 'coords', header: 'Toạ độ' },
  { accessorKey: 'radius', header: 'Bán kính (m)' },
  { accessorKey: 'is_active', header: 'Trạng thái' },
  { id: 'actions', header: 'Hành động' },
]

const toast = useAppToast()

const locations = ref<Location[]>([])
const loading = ref(false)
const error = ref('')
const actionLoading = ref<Record<number, boolean>>({})

// Modal
const showModal = ref(false)
const editingLocation = ref<Location | null>(null)
const modalLoading = ref(false)
const modalError = ref('')

const form = reactive({
  name: '',
  address: '',
  latitude: '' as string | number,
  longitude: '' as string | number,
  radius: 100,
  description: '',
})

async function fetchLocations() {
  loading.value = true
  error.value = ''
  try {
    const res = await locationService.getAll()
    locations.value = Array.isArray(res) ? res : (res as any).data ?? []
  } catch {
    error.value = 'Không thể tải danh sách vị trí. Vui lòng thử lại.'
  } finally {
    loading.value = false
  }
}

function openAddModal() {
  editingLocation.value = null
  form.name = ''
  form.address = ''
  form.latitude = ''
  form.longitude = ''
  form.radius = 100
  form.description = ''
  modalError.value = ''
  showModal.value = true
}

function openEditModal(loc: Location) {
  editingLocation.value = loc
  form.name = loc.name
  form.address = loc.address
  form.latitude = loc.latitude
  form.longitude = loc.longitude
  form.radius = loc.radius
  form.description = loc.description ?? ''
  modalError.value = ''
  showModal.value = true
}

function validateForm() {
  if (!form.name.trim()) return 'Vui lòng nhập tên vị trí.'
  if (!form.address.trim()) return 'Vui lòng nhập địa chỉ.'
  if (form.latitude === '' || isNaN(Number(form.latitude))) return 'Vui lòng nhập vĩ độ hợp lệ.'
  if (form.longitude === '' || isNaN(Number(form.longitude))) return 'Vui lòng nhập kinh độ hợp lệ.'
  if (!form.radius || Number(form.radius) <= 0) return 'Bán kính phải lớn hơn 0.'
  return ''
}

async function submitModal() {
  modalError.value = validateForm()
  if (modalError.value) return

  modalLoading.value = true
  modalError.value = ''

  const payload = {
    name: form.name,
    address: form.address,
    latitude: Number(form.latitude),
    longitude: Number(form.longitude),
    radius: Number(form.radius),
    description: form.description,
  }

  try {
    if (editingLocation.value) {
      await locationService.update(editingLocation.value.id, payload)
      toast.success('Cập nhật vị trí thành công.')
    } else {
      await locationService.create(payload)
      toast.success('Thêm vị trí thành công.')
    }
    showModal.value = false
    await fetchLocations()
  } catch (err: unknown) {
    const e = err as { data?: { message?: string } }
    modalError.value = e?.data?.message || 'Lưu thất bại. Vui lòng thử lại.'
  } finally {
    modalLoading.value = false
  }
}

async function deleteLocation(loc: Location) {
  if (!confirm(`Bạn có chắc muốn xoá vị trí "${loc.name}"?`)) return
  actionLoading.value[loc.id] = true
  try {
    await locationService.delete(loc.id)
    toast.success('Xóa vị trí thành công.')
    await fetchLocations()
  } catch {
  } finally {
    delete actionLoading.value[loc.id]
  }
}

async function toggleActive(loc: Location) {
  actionLoading.value[loc.id] = true
  try {
    await locationService.toggle(loc.id)
    toast.success('Cập nhật trạng thái thành công.')
    await fetchLocations()
  } catch {
  } finally {
    delete actionLoading.value[loc.id]
  }
}

onMounted(fetchLocations)
</script>

<template>
  <div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-ink">Quản lý vị trí chấm công</h1>
        <p class="text-sm text-muted mt-1">Thiết lập các vị trí cho phép nhân viên chấm công</p>
      </div>
      <UButton icon="i-heroicons-plus" @click="openAddModal">
        Thêm vị trí
      </UButton>
    </div>

    <!-- Card -->
    <UCard>
      <!-- Error -->
      <div v-if="error" class="mb-4 bg-danger-soft border border-border-strong text-danger rounded-lg px-4 py-3 text-sm">
        {{ error }}
      </div>

      <!-- Loading -->
      <div v-if="loading" class="flex justify-center py-16">
        <UIcon name="i-heroicons-arrow-path" class="animate-spin w-10 h-10 text-accent" />
      </div>

      <!-- Table -->
      <div v-else-if="locations.length > 0">
        <UTable :data="locations" :columns="columns">
          <template #name-cell="{ row }">
            <p class="text-sm font-medium text-ink">{{ row.original.name }}</p>
            <p v-if="row.original.description" class="text-xs text-faint mt-0.5 line-clamp-1">{{ row.original.description }}</p>
          </template>
          <template #address-cell="{ row }">
            <span class="line-clamp-2 max-w-xs block">{{ row.original.address }}</span>
          </template>
          <template #coords-cell="{ row }">
            <div class="text-xs text-muted whitespace-nowrap">
              <div>Vĩ: {{ Number(row.original.latitude).toFixed(6) }}</div>
              <div>Kinh: {{ Number(row.original.longitude).toFixed(6) }}</div>
            </div>
          </template>
          <template #is_active-cell="{ row }">
            <StatusChip :color="row.original.is_active ? 'success' : 'warning'">
              {{ row.original.is_active ? 'Đang hoạt động' : 'Tạm dừng' }}
            </StatusChip>
          </template>
          <template #actions-cell="{ row }">
            <div class="flex items-center gap-2">
              <UButton
                color="neutral"
                variant="soft"
                size="xs"
                :loading="actionLoading[row.original.id]"
                :disabled="actionLoading[row.original.id]"
                @click="toggleActive(row.original)"
              >
                {{ row.original.is_active ? 'Tạm dừng' : 'Kích hoạt' }}
              </UButton>
              <UButton color="neutral" variant="soft" size="xs" @click="openEditModal(row.original)">Sửa</UButton>
              <UButton
                color="error"
                size="xs"
                :disabled="actionLoading[row.original.id]"
                @click="deleteLocation(row.original)"
              >
                Xoá
              </UButton>
            </div>
          </template>
        </UTable>
      </div>

      <!-- Empty -->
      <div v-else class="text-center py-16 text-faint">
        <UIcon name="i-heroicons-inbox" class="mx-auto w-14 h-14 mb-4" />
        <p class="font-medium text-muted">Chưa có vị trí chấm công nào</p>
        <p class="text-sm mt-1">Nhấn "Thêm vị trí" để tạo vị trí đầu tiên</p>
        <UButton class="mt-4" @click="openAddModal">Thêm vị trí ngay</UButton>
      </div>
    </UCard>

    <!-- Add/Edit Modal -->
    <BaseModal
      v-model="showModal"
      :title="editingLocation ? 'Chỉnh sửa vị trí' : 'Thêm vị trí mới'"
      max-width="max-w-lg"
      scrollable
    >
      <div v-if="modalError" class="bg-danger-soft border border-border-strong text-danger rounded-lg px-4 py-3 text-sm">
        {{ modalError }}
      </div>

      <!-- Name -->
      <div>
        <label class="block text-sm font-medium text-body mb-1">
          Tên vị trí <span class="text-danger">*</span>
        </label>
        <UInput v-model="form.name" type="text" class="w-full" placeholder="VD: Văn phòng chính" />
      </div>

      <!-- Address -->
      <div>
        <label class="block text-sm font-medium text-body mb-1">
          Địa chỉ <span class="text-danger">*</span>
        </label>
        <UInput v-model="form.address" type="text" class="w-full" placeholder="Số nhà, đường, quận, thành phố..." />
      </div>

      <!-- Lat / Lng -->
      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium text-body mb-1">
            Vĩ độ (Latitude) <span class="text-danger">*</span>
          </label>
          <UInput
            v-model="form.latitude"
            type="number"
            step="0.000001"
            class="w-full"
            placeholder="VD: 10.762622"
          />
        </div>
        <div>
          <label class="block text-sm font-medium text-body mb-1">
            Kinh độ (Longitude) <span class="text-danger">*</span>
          </label>
          <UInput
            v-model="form.longitude"
            type="number"
            step="0.000001"
            class="w-full"
            placeholder="VD: 106.660172"
          />
        </div>
      </div>

      <!-- Radius -->
      <div>
        <label class="block text-sm font-medium text-body mb-1">
          Bán kính cho phép (mét) <span class="text-danger">*</span>
        </label>
        <UInput
          v-model.number="form.radius"
          type="number"
          min="1"
          class="w-full"
          placeholder="VD: 100"
        />
        <p class="text-xs text-faint mt-1">Nhân viên phải ở trong bán kính này mới được chấm công</p>
      </div>

      <!-- Description -->
      <div>
        <label class="block text-sm font-medium text-body mb-1">Mô tả</label>
        <UTextarea
          v-model="form.description"
          class="w-full"
          :rows="3"
          placeholder="Mô tả thêm về vị trí (không bắt buộc)..."
        />
      </div>

      <template #footer>
        <UButton color="neutral" variant="soft" :disabled="modalLoading" @click="showModal = false">Huỷ</UButton>
        <UButton :loading="modalLoading" :disabled="modalLoading" @click="submitModal">
          {{ modalLoading ? 'Đang lưu...' : 'Lưu vị trí' }}
        </UButton>
      </template>
    </BaseModal>
  </div>
</template>
