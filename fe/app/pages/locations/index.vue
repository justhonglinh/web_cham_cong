<script setup lang="ts">
definePageMeta({ layout: 'default' })

const api = useApi()

interface Location {
  id: number
  name: string
  address: string
  latitude: number
  longitude: number
  radius: number
  description: string
  is_active: boolean
}

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
    const data = await api.get<{ data: Location[] }>('/locations')
    locations.value = data.data ?? []
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
      await api.put(`/locations/${editingLocation.value.id}`, payload)
    } else {
      await api.post('/locations', payload)
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
    await api.del(`/locations/${loc.id}`)
    await fetchLocations()
  } catch {
    alert('Xoá thất bại. Vui lòng thử lại.')
  } finally {
    delete actionLoading.value[loc.id]
  }
}

async function toggleActive(loc: Location) {
  actionLoading.value[loc.id] = true
  try {
    await api.patch(`/locations/${loc.id}/toggle`, {})
    await fetchLocations()
  } catch {
    alert('Cập nhật trạng thái thất bại. Vui lòng thử lại.')
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
        <h1 class="text-2xl font-bold text-gray-900">Quản lý vị trí chấm công</h1>
        <p class="text-sm text-gray-500 mt-1">Thiết lập các vị trí cho phép nhân viên chấm công</p>
      </div>
      <button class="btn-primary" @click="openAddModal">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Thêm vị trí
      </button>
    </div>

    <!-- Card -->
    <div class="card p-6">
      <!-- Error -->
      <div v-if="error" class="mb-4 bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 text-sm">
        {{ error }}
      </div>

      <!-- Loading -->
      <div v-if="loading" class="flex justify-center py-16">
        <svg class="animate-spin h-10 w-10 text-blue-600" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
        </svg>
      </div>

      <!-- Table -->
      <div v-else-if="locations.length > 0" class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên vị trí</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Địa chỉ</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Toạ độ</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bán kính (m)</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hành động</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-100">
            <tr v-for="loc in locations" :key="loc.id" class="hover:bg-gray-50 transition-colors">
              <td class="px-4 py-3">
                <p class="text-sm font-medium text-gray-900">{{ loc.name }}</p>
                <p v-if="loc.description" class="text-xs text-gray-400 mt-0.5 line-clamp-1">{{ loc.description }}</p>
              </td>
              <td class="px-4 py-3 text-sm text-gray-600 max-w-xs">
                <span class="line-clamp-2">{{ loc.address }}</span>
              </td>
              <td class="px-4 py-3 text-xs text-gray-500 whitespace-nowrap">
                <div>Vĩ: {{ Number(loc.latitude).toFixed(6) }}</div>
                <div>Kinh: {{ Number(loc.longitude).toFixed(6) }}</div>
              </td>
              <td class="px-4 py-3 text-sm text-gray-700 font-medium">{{ loc.radius }}</td>
              <td class="px-4 py-3 text-sm">
                <span :class="loc.is_active ? 'badge-success' : 'badge-danger'">
                  {{ loc.is_active ? 'Đang hoạt động' : 'Tạm dừng' }}
                </span>
              </td>
              <td class="px-4 py-3 text-sm whitespace-nowrap space-x-2">
                <button
                  class="btn-secondary text-xs"
                  :disabled="actionLoading[loc.id]"
                  @click="toggleActive(loc)"
                >
                  {{ actionLoading[loc.id] ? '...' : loc.is_active ? 'Tạm dừng' : 'Kích hoạt' }}
                </button>
                <button class="btn-secondary text-xs" @click="openEditModal(loc)">Sửa</button>
                <button
                  class="btn-danger text-xs"
                  :disabled="actionLoading[loc.id]"
                  @click="deleteLocation(loc)"
                >
                  Xoá
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Empty -->
      <div v-else class="text-center py-16 text-gray-400">
        <svg class="mx-auto h-14 w-14 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg>
        <p class="font-medium text-gray-500">Chưa có vị trí chấm công nào</p>
        <p class="text-sm mt-1">Nhấn "Thêm vị trí" để tạo vị trí đầu tiên</p>
        <button class="btn-primary mt-4" @click="openAddModal">Thêm vị trí ngay</button>
      </div>
    </div>

    <!-- Add/Edit Modal -->
    <Teleport to="body">
      <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-lg max-h-[90vh] flex flex-col">
          <!-- Modal Header -->
          <div class="flex items-center justify-between px-6 py-4 border-b shrink-0">
            <h3 class="text-lg font-semibold text-gray-900">
              {{ editingLocation ? 'Chỉnh sửa vị trí' : 'Thêm vị trí mới' }}
            </h3>
            <button class="text-gray-400 hover:text-gray-600 transition-colors" @click="showModal = false">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>

          <!-- Modal Body -->
          <div class="p-6 space-y-4 overflow-y-auto">
            <div v-if="modalError" class="bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 text-sm">
              {{ modalError }}
            </div>

            <!-- Name -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">
                Tên vị trí <span class="text-red-500">*</span>
              </label>
              <input v-model="form.name" type="text" class="input-field" placeholder="VD: Văn phòng chính" />
            </div>

            <!-- Address -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">
                Địa chỉ <span class="text-red-500">*</span>
              </label>
              <input v-model="form.address" type="text" class="input-field" placeholder="Số nhà, đường, quận, thành phố..." />
            </div>

            <!-- Lat / Lng -->
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                  Vĩ độ (Latitude) <span class="text-red-500">*</span>
                </label>
                <input
                  v-model="form.latitude"
                  type="number"
                  step="0.000001"
                  class="input-field"
                  placeholder="VD: 10.762622"
                />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                  Kinh độ (Longitude) <span class="text-red-500">*</span>
                </label>
                <input
                  v-model="form.longitude"
                  type="number"
                  step="0.000001"
                  class="input-field"
                  placeholder="VD: 106.660172"
                />
              </div>
            </div>

            <!-- Radius -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">
                Bán kính cho phép (mét) <span class="text-red-500">*</span>
              </label>
              <input
                v-model.number="form.radius"
                type="number"
                min="1"
                class="input-field"
                placeholder="VD: 100"
              />
              <p class="text-xs text-gray-400 mt-1">Nhân viên phải ở trong bán kính này mới được chấm công</p>
            </div>

            <!-- Description -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Mô tả</label>
              <textarea
                v-model="form.description"
                class="input-field resize-none"
                rows="3"
                placeholder="Mô tả thêm về vị trí (không bắt buộc)..."
              />
            </div>
          </div>

          <!-- Modal Footer -->
          <div class="flex justify-end gap-3 px-6 py-4 border-t bg-gray-50 rounded-b-xl shrink-0">
            <button class="btn-secondary" :disabled="modalLoading" @click="showModal = false">Huỷ</button>
            <button class="btn-primary" :disabled="modalLoading" @click="submitModal">
              <svg v-if="modalLoading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
              </svg>
              {{ modalLoading ? 'Đang lưu...' : 'Lưu vị trí' }}
            </button>
          </div>
        </div>
      </div>
    </Teleport>
  </div>
</template>
