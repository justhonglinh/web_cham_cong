<script setup lang="ts">
import type { TableColumn } from '@nuxt/ui'
import { SHIFT_STATUS_BADGE, SHIFT_STATUS_LABEL } from '~/constants'
import { shiftService } from '~/services/shiftService'
import type { Shift, ShiftInput as ShiftForm } from '~/types/shift'
import { formatTime } from '~/utils/format'

definePageMeta({ layout: 'default' })

const columns: TableColumn<Shift>[] = [
  { accessorKey: 'name', header: 'Tên ca' },
  { accessorKey: 'start_time', header: 'Giờ bắt đầu' },
  { accessorKey: 'end_time', header: 'Giờ kết thúc' },
  { id: 'duration', header: 'Thời lượng' },
  { accessorKey: 'status', header: 'Trạng thái' },
  { accessorKey: 'usage_count', header: 'Số lần dùng' },
  { id: 'actions', header: '', meta: { class: { th: 'text-right', td: 'text-right' } } },
]

const toast = useAppToast()

const loading = ref(true)
const saving = ref(false)
const deleting = ref(false)
const error = ref<string | null>(null)
const saveError = ref<string | null>(null)

const shifts = ref<Shift[]>([])

const showModal = ref(false)
const editingId = ref<number | null>(null)
const form = ref<ShiftForm>({ name: '', start_time: '', end_time: '' })

const showDeleteConfirm = ref(false)
const deletingId = ref<number | null>(null)
const deletingName = ref('')

async function fetchShifts() {
  loading.value = true
  error.value = null
  try {
    const res = await shiftService.getAll()
    shifts.value = Array.isArray(res) ? res : (res as any).data ?? []
  } catch (e: any) {
    error.value = e?.data?.message || 'Không thể tải danh sách ca làm việc.'
  } finally {
    loading.value = false
  }
}

function openAdd() {
  editingId.value = null
  form.value = { name: '', start_time: '', end_time: '' }
  saveError.value = null
  showModal.value = true
}

function openEdit(shift: Shift) {
  editingId.value = shift.id
  form.value = {
    name: shift.name,
    start_time: shift.start_time?.slice(0, 5) ?? '',
    end_time: shift.end_time?.slice(0, 5) ?? '',
  }
  saveError.value = null
  showModal.value = true
}

function closeModal() {
  showModal.value = false
  saveError.value = null
}

async function saveShift() {
  if (!form.value.name.trim()) {
    saveError.value = 'Vui lòng nhập tên ca làm việc.'
    return
  }
  if (!form.value.start_time || !form.value.end_time) {
    saveError.value = 'Vui lòng nhập giờ bắt đầu và giờ kết thúc.'
    return
  }
  saving.value = true
  saveError.value = null
  try {
    const payload = {
      name: form.value.name,
      start_time: form.value.start_time,
      end_time: form.value.end_time,
    }
    if (editingId.value) {
      const updated = await shiftService.update(editingId.value, payload)
      const idx = shifts.value.findIndex(s => s.id === editingId.value)
      if (idx !== -1) shifts.value[idx] = updated
      toast.success('Cập nhật ca làm việc thành công.')
    } else {
      const created = await shiftService.create(payload)
      shifts.value.unshift(created)
      toast.success('Thêm ca làm việc thành công.')
    }
    closeModal()
  } catch (e: any) {
    saveError.value = e?.data?.message || 'Lưu thất bại. Vui lòng thử lại.'
  } finally {
    saving.value = false
  }
}

function confirmDelete(shift: Shift) {
  deletingId.value = shift.id
  deletingName.value = shift.name
  showDeleteConfirm.value = true
}

async function deleteShift() {
  if (!deletingId.value) return
  deleting.value = true
  try {
    await shiftService.delete(deletingId.value)
    shifts.value = shifts.value.filter(s => s.id !== deletingId.value)
    showDeleteConfirm.value = false
    toast.success('Xóa ca làm việc thành công.')
  } catch (e: any) {
    error.value = e?.data?.message || 'Xóa thất bại. Vui lòng thử lại.'
    showDeleteConfirm.value = false
  } finally {
    deleting.value = false
  }
}


function statusBadge(status: string) {
  return SHIFT_STATUS_BADGE[status] ?? 'error'
}

function statusLabel(status: string) {
  return SHIFT_STATUS_LABEL[status] ?? status
}

function calcDuration(start: string, end: string) {
  if (!start || !end) return '—'
  const [sh, sm] = start.split(':').map(Number)
  const [eh, em] = end.split(':').map(Number)
  let mins = (eh * 60 + em) - (sh * 60 + sm)
  if (mins < 0) mins += 24 * 60
  const h = Math.floor(mins / 60)
  const m = mins % 60
  return m > 0 ? `${h}h${m}p` : `${h}h`
}

onMounted(fetchShifts)
</script>

<template>
  <div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
      <div>
        <h1 class="text-2xl font-bold text-ink">Quản lý ca làm việc</h1>
        <p class="text-sm text-muted mt-0.5">Tạo và quản lý các ca làm việc trong hệ thống</p>
      </div>
      <UButton icon="i-heroicons-plus" @click="openAdd">
        Thêm ca
      </UButton>
    </div>

    <!-- Error Banner -->
    <UAlert
      v-if="error"
      color="error"
      variant="soft"
      :description="error"
      close
      @update:open="error = null"
    />

    <!-- Summary Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
      <UCard>
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 bg-accent-soft rounded-xl flex items-center justify-center shrink-0">
            <UIcon name="i-heroicons-clock" class="w-5 h-5 text-accent" />
          </div>
          <div>
            <p class="text-xs text-muted">Tổng ca</p>
            <p class="text-xl font-bold text-ink">{{ shifts.length }}</p>
          </div>
        </div>
      </UCard>
      <UCard>
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 bg-success-soft rounded-xl flex items-center justify-center shrink-0">
            <UIcon name="i-heroicons-check-circle" class="w-5 h-5 text-success" />
          </div>
          <div>
            <p class="text-xs text-muted">Đang hoạt động</p>
            <p class="text-xl font-bold text-ink">{{ shifts.filter(s => s.status === 'active').length }}</p>
          </div>
        </div>
      </UCard>
      <UCard>
        <div class="flex items-center gap-3">
          <div class="w-10 h-10 bg-neutral-soft rounded-xl flex items-center justify-center shrink-0">
            <UIcon name="i-heroicons-x-circle" class="w-5 h-5 text-muted" />
          </div>
          <div>
            <p class="text-xs text-muted">Ngừng dùng</p>
            <p class="text-xl font-bold text-ink">{{ shifts.filter(s => s.status !== 'active').length }}</p>
          </div>
        </div>
      </UCard>
    </div>

    <!-- Table -->
    <UCard :ui="{ body: 'p-0' }">
      <div v-if="loading" class="flex items-center justify-center py-16">
        <div class="h-8 w-8 animate-spin rounded-full border-4 border-accent border-t-transparent"></div>
        <span class="ml-3 text-muted">Đang tải...</span>
      </div>

      <div v-else-if="shifts.length === 0" class="text-center py-16">
        <UIcon name="i-heroicons-clock" class="mx-auto w-12 h-12 text-faint mb-3" />
        <p class="text-muted mb-3">Chưa có ca làm việc nào</p>
        <UButton @click="openAdd">Thêm ca đầu tiên</UButton>
      </div>

      <UTable v-else :data="shifts" :columns="columns">
        <template #name-cell="{ row }">
          <div class="flex items-center gap-2">
            <div class="w-8 h-8 bg-accent-soft rounded-lg flex items-center justify-center shrink-0">
              <UIcon name="i-heroicons-clock" class="w-4 h-4 text-accent" />
            </div>
            <span class="text-sm font-medium text-ink">{{ row.original.name }}</span>
          </div>
        </template>
        <template #start_time-cell="{ row }">
          <span class="text-sm font-semibold text-success bg-success-soft px-2 py-0.5 rounded-md">
            {{ formatTime(row.original.start_time) }}
          </span>
        </template>
        <template #end_time-cell="{ row }">
          <span class="text-sm font-semibold text-accent-ink bg-accent-soft px-2 py-0.5 rounded-md">
            {{ formatTime(row.original.end_time) }}
          </span>
        </template>
        <template #duration-cell="{ row }">
          <span class="text-body">{{ calcDuration(row.original.start_time, row.original.end_time) }}</span>
        </template>
        <template #status-cell="{ row }">
          <StatusChip :color="statusBadge(row.original.status)">{{ statusLabel(row.original.status) }}</StatusChip>
        </template>
        <template #usage_count-cell="{ row }">
          <span class="text-sm text-body font-medium">{{ row.original.usage_count ?? 0 }}</span>
          <span class="text-xs text-faint ml-1">lần</span>
        </template>
        <template #actions-cell="{ row }">
          <div class="flex items-center justify-end gap-2">
            <UButton
              color="neutral"
              variant="soft"
              size="xs"
              icon="i-heroicons-pencil-square"
              @click="openEdit(row.original)"
            >
              Sửa
            </UButton>
            <UButton
              color="error"
              variant="soft"
              size="xs"
              icon="i-heroicons-trash"
              @click="confirmDelete(row.original)"
            >
              Xóa
            </UButton>
          </div>
        </template>
      </UTable>
    </UCard>

    <!-- Add/Edit Modal -->
    <BaseModal
      :model-value="showModal"
      :title="editingId ? 'Chỉnh sửa ca làm việc' : 'Thêm ca làm việc mới'"
      @update:model-value="closeModal"
    >
      <div v-if="saveError" class="bg-danger-soft rounded-lg px-3 py-2 text-sm text-danger">
        {{ saveError }}
      </div>

      <div>
        <label class="block text-sm font-medium text-body mb-1">Tên ca <span class="text-danger">*</span></label>
        <UInput v-model="form.name" type="text" class="w-full" placeholder="VD: Ca sáng, Ca chiều..." />
      </div>

      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium text-body mb-1">Giờ bắt đầu <span class="text-danger">*</span></label>
          <UInput v-model="form.start_time" type="time" class="w-full" />
        </div>
        <div>
          <label class="block text-sm font-medium text-body mb-1">Giờ kết thúc <span class="text-danger">*</span></label>
          <UInput v-model="form.end_time" type="time" class="w-full" />
        </div>
      </div>

      <div v-if="form.start_time && form.end_time" class="bg-accent-soft rounded-lg px-3 py-2 text-sm text-accent-ink flex items-center gap-2">
        <UIcon name="i-heroicons-information-circle" class="w-4 h-4 shrink-0" />
        Thời lượng: {{ calcDuration(form.start_time, form.end_time) }}
      </div>

      <template #footer>
        <UButton color="neutral" variant="soft" :disabled="saving" @click="closeModal">Hủy</UButton>
        <UButton :loading="saving" @click="saveShift">
          {{ editingId ? 'Lưu thay đổi' : 'Thêm ca' }}
        </UButton>
      </template>
    </BaseModal>

    <!-- Delete Confirm Modal -->
    <ConfirmModal
      v-model="showDeleteConfirm"
      confirm-text="Xóa ca làm việc"
      :loading="deleting"
      @confirm="deleteShift"
    >
      Bạn có chắc muốn xóa ca làm việc <strong>{{ deletingName }}</strong>?
      Dữ liệu liên quan có thể bị ảnh hưởng.
    </ConfirmModal>
  </div>
</template>
