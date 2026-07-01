<script setup lang="ts">
import { REQUEST_STATUS_BADGE, REQUEST_STATUS_LABEL } from '~/constants'
import type { OvertimeShift, OvertimeRequest } from '~/types/overtime'
import { formatDate, formatTime } from '~/utils/format'

definePageMeta({ layout: 'default' })

const api = useApi()

// --- Shifts state ---
const shifts = ref<OvertimeShift[]>([])
const shiftsLoading = ref(false)
const shiftsError = ref('')

// --- Requests state ---
const requests = ref<OvertimeRequest[]>([])
const requestsLoading = ref(false)
const requestsError = ref('')

// --- Modal state ---
const showModal = ref(false)
const editingShift = ref<OvertimeShift | null>(null)
const modalLoading = ref(false)
const modalError = ref('')

const form = reactive({
  name: '',
  start_time: '',
  end_time: '',
  date: '',
  max_registrations: 10,
})

// --- Action loading map ---
const actionLoading = ref<Record<number, boolean>>({})

// --- Fetch shifts ---
async function fetchShifts() {
  shiftsLoading.value = true
  shiftsError.value = ''
  try {
    const data = await api.get<{ data: OvertimeShift[] }>('/overtime/management')
    shifts.value = data.data ?? []
  } catch {
    shiftsError.value = 'Không thể tải danh sách ca tăng ca.'
  } finally {
    shiftsLoading.value = false
  }
}

// --- Fetch requests ---
async function fetchRequests() {
  requestsLoading.value = true
  requestsError.value = ''
  try {
    const data = await api.get<{ data: OvertimeRequest[] }>('/overtime/management/requests')
    requests.value = data.data ?? []
  } catch {
    requestsError.value = 'Không thể tải danh sách yêu cầu tăng ca.'
  } finally {
    requestsLoading.value = false
  }
}

// --- Open add modal ---
function openAddModal() {
  editingShift.value = null
  form.name = ''
  form.start_time = ''
  form.end_time = ''
  form.date = ''
  form.max_registrations = 10
  modalError.value = ''
  showModal.value = true
}

// --- Open edit modal ---
function openEditModal(shift: OvertimeShift) {
  editingShift.value = shift
  form.name = shift.name
  form.start_time = shift.start_time
  form.end_time = shift.end_time
  form.date = shift.date
  form.max_registrations = shift.max_registrations
  modalError.value = ''
  showModal.value = true
}

// --- Submit modal ---
async function submitModal() {
  if (!form.name || !form.start_time || !form.end_time || !form.date) {
    modalError.value = 'Vui lòng điền đầy đủ thông tin.'
    return
  }
  modalLoading.value = true
  modalError.value = ''
  try {
    if (editingShift.value) {
      await api.put(`/overtime/management/${editingShift.value.id}`, { ...form }, { success: 'Cập nhật ca tăng ca thành công.' })
    } else {
      await api.post('/overtime/management', { ...form }, { success: 'Thêm ca tăng ca thành công.' })
    }
    showModal.value = false
    await fetchShifts()
  } catch {
    modalError.value = 'Lưu thất bại. Vui lòng thử lại.'
  } finally {
    modalLoading.value = false
  }
}

// --- Delete shift ---
async function deleteShift(shift: OvertimeShift) {
  if (!confirm(`Bạn có chắc muốn xoá ca "${shift.name}"?`)) return
  actionLoading.value[shift.id] = true
  try {
    await api.del(`/overtime/management/${shift.id}`, { success: 'Xóa ca tăng ca thành công.' })
    await fetchShifts()
  } catch {
  } finally {
    delete actionLoading.value[shift.id]
  }
}

// --- Approve / Reject request ---
async function updateRequestStatus(request: OvertimeRequest, status: 'approved' | 'rejected') {
  actionLoading.value[request.id] = true
  try {
    const label = status === 'approved' ? 'Đã duyệt yêu cầu tăng ca.' : 'Đã từ chối yêu cầu tăng ca.'
    await api.patch(`/overtime-requests/${request.id}/status`, { status }, { success: label })
    await fetchRequests()
  } catch {
  } finally {
    delete actionLoading.value[request.id]
  }
}


function statusBadgeClass(status: string) {
  return REQUEST_STATUS_BADGE[status] ?? 'badge-warning'
}

function statusLabel(status: string) {
  return REQUEST_STATUS_LABEL[status] ?? status
}

onMounted(() => {
  fetchShifts()
  fetchRequests()
})
</script>

<template>
  <div class="max-w-7xl mx-auto space-y-8">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Quản lý tăng ca</h1>
        <p class="text-sm text-gray-500 mt-1">Quản lý ca tăng ca và phê duyệt yêu cầu nhân viên</p>
      </div>
    </div>

    <!-- Section 1: Overtime Shifts -->
    <div class="card p-6">
      <div class="flex items-center justify-between mb-5">
        <h2 class="text-lg font-semibold text-gray-800">Danh sách ca tăng ca</h2>
        <button class="btn-primary" @click="openAddModal">
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          Thêm ca tăng ca
        </button>
      </div>

      <!-- Error -->
      <div v-if="shiftsError" class="mb-4 bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 text-sm">
        {{ shiftsError }}
      </div>

      <!-- Loading -->
      <div v-if="shiftsLoading" class="flex justify-center py-12">
        <svg class="animate-spin h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
        </svg>
      </div>

      <!-- Table -->
      <div v-else-if="shifts.length > 0" class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tên ca</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Giờ bắt đầu</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Giờ kết thúc</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Số đăng ký</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hành động</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-100">
            <tr v-for="shift in shifts" :key="shift.id" class="hover:bg-gray-50 transition-colors">
              <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ shift.name }}</td>
              <td class="px-4 py-3 text-sm text-gray-600">{{ formatTime(shift.start_time) }}</td>
              <td class="px-4 py-3 text-sm text-gray-600">{{ formatTime(shift.end_time) }}</td>
              <td class="px-4 py-3 text-sm text-gray-600">{{ formatDate(shift.date) }}</td>
              <td class="px-4 py-3 text-sm text-gray-600">
                {{ shift.registration_count ?? 0 }} / {{ shift.max_registrations }}
              </td>
              <td class="px-4 py-3 text-sm space-x-2 whitespace-nowrap">
                <button class="btn-secondary text-xs" @click="openEditModal(shift)">Sửa</button>
                <button
                  class="btn-danger text-xs"
                  :disabled="actionLoading[shift.id]"
                  @click="deleteShift(shift)"
                >
                  {{ actionLoading[shift.id] ? 'Đang xoá...' : 'Xoá' }}
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Empty -->
      <div v-else class="text-center py-12 text-gray-400">
        <svg class="mx-auto h-12 w-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
        <p class="text-sm">Chưa có ca tăng ca nào</p>
      </div>
    </div>

    <!-- Section 2: Overtime Requests -->
    <div class="card p-6">
      <div class="flex items-center justify-between mb-5">
        <h2 class="text-lg font-semibold text-gray-800">Yêu cầu tăng ca từ nhân viên</h2>
        <button class="btn-secondary text-sm" @click="fetchRequests">
          <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
          </svg>
          Làm mới
        </button>
      </div>

      <!-- Error -->
      <div v-if="requestsError" class="mb-4 bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 text-sm">
        {{ requestsError }}
      </div>

      <!-- Loading -->
      <div v-if="requestsLoading" class="flex justify-center py-12">
        <svg class="animate-spin h-8 w-8 text-blue-600" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
        </svg>
      </div>

      <!-- Table -->
      <div v-else-if="requests.length > 0" class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nhân viên</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ca tăng ca</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hành động</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-100">
            <tr v-for="req in requests" :key="req.id" class="hover:bg-gray-50 transition-colors">
              <td class="px-4 py-3 text-sm font-medium text-gray-900">{{ req.employee_name }}</td>
              <td class="px-4 py-3 text-sm text-gray-600">{{ req.shift_name }}</td>
              <td class="px-4 py-3 text-sm text-gray-600">{{ formatDate(req.shift_date) }}</td>
              <td class="px-4 py-3 text-sm">
                <span :class="statusBadgeClass(req.status)">{{ statusLabel(req.status) }}</span>
              </td>
              <td class="px-4 py-3 text-sm space-x-2 whitespace-nowrap">
                <template v-if="req.status === 'pending'">
                  <button
                    class="btn-primary text-xs"
                    :disabled="actionLoading[req.id]"
                    @click="updateRequestStatus(req, 'approved')"
                  >
                    {{ actionLoading[req.id] ? '...' : 'Duyệt' }}
                  </button>
                  <button
                    class="btn-danger text-xs"
                    :disabled="actionLoading[req.id]"
                    @click="updateRequestStatus(req, 'rejected')"
                  >
                    {{ actionLoading[req.id] ? '...' : 'Từ chối' }}
                  </button>
                </template>
                <span v-else class="text-gray-400 text-xs">—</span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Empty -->
      <div v-else class="text-center py-12 text-gray-400">
        <svg class="mx-auto h-12 w-12 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
        </svg>
        <p class="text-sm">Chưa có yêu cầu tăng ca nào</p>
      </div>
    </div>

    <!-- Add/Edit Modal -->
    <Teleport to="body">
      <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md">
          <div class="flex items-center justify-between px-6 py-4 border-b">
            <h3 class="text-lg font-semibold text-gray-900">
              {{ editingShift ? 'Chỉnh sửa ca tăng ca' : 'Thêm ca tăng ca mới' }}
            </h3>
            <button class="text-gray-400 hover:text-gray-600 transition-colors" @click="showModal = false">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>

          <div class="p-6 space-y-4">
            <div v-if="modalError" class="bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 text-sm">
              {{ modalError }}
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Tên ca <span class="text-red-500">*</span></label>
              <input v-model="form.name" type="text" class="input-field" placeholder="VD: Ca tăng ca chiều" />
            </div>

            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Giờ bắt đầu <span class="text-red-500">*</span></label>
                <input v-model="form.start_time" type="time" class="input-field" />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Giờ kết thúc <span class="text-red-500">*</span></label>
                <input v-model="form.end_time" type="time" class="input-field" />
              </div>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Ngày <span class="text-red-500">*</span></label>
              <input v-model="form.date" type="date" class="input-field" />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Số đăng ký tối đa</label>
              <input v-model.number="form.max_registrations" type="number" min="1" class="input-field" />
            </div>
          </div>

          <div class="flex justify-end gap-3 px-6 py-4 border-t bg-gray-50 rounded-b-xl">
            <button class="btn-secondary" :disabled="modalLoading" @click="showModal = false">Huỷ</button>
            <button class="btn-primary" :disabled="modalLoading" @click="submitModal">
              <svg v-if="modalLoading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
              </svg>
              {{ modalLoading ? 'Đang lưu...' : 'Lưu' }}
            </button>
          </div>
        </div>
      </div>
    </Teleport>
  </div>
</template>
