<script setup lang="ts">
import { SHIFT_STATUS_BADGE, SHIFT_STATUS_LABEL } from '~/constants'
import type { Shift, ShiftInput as ShiftForm } from '~/types/shift'
import { formatTime } from '~/utils/format'

definePageMeta({ layout: 'default' })

const api = useApi()

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
    const res = await api.get<{ data: Shift[] } | Shift[]>('/shift/management')
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
      const updated = await api.put<Shift>(`/shift/management/${editingId.value}`, payload, { success: 'Cập nhật ca làm việc thành công.' })
      const idx = shifts.value.findIndex(s => s.id === editingId.value)
      if (idx !== -1) shifts.value[idx] = updated
    } else {
      const created = await api.post<Shift>('/shift/management', payload, { success: 'Thêm ca làm việc thành công.' })
      shifts.value.unshift(created)
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
    await api.del(`/shift/management/${deletingId.value}`, { success: 'Xóa ca làm việc thành công.' })
    shifts.value = shifts.value.filter(s => s.id !== deletingId.value)
    showDeleteConfirm.value = false
  } catch (e: any) {
    error.value = e?.data?.message || 'Xóa thất bại. Vui lòng thử lại.'
    showDeleteConfirm.value = false
  } finally {
    deleting.value = false
  }
}


function statusBadge(status: string) {
  return SHIFT_STATUS_BADGE[status] ?? 'badge-danger'
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
        <h1 class="text-2xl font-bold text-gray-900">Quản lý ca làm việc</h1>
        <p class="text-sm text-gray-500 mt-0.5">Tạo và quản lý các ca làm việc trong hệ thống</p>
      </div>
      <button class="btn-primary gap-2" @click="openAdd">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Thêm ca
      </button>
    </div>

    <!-- Error Banner -->
    <div v-if="error" class="card p-4 border-l-4 border-red-500 flex items-center justify-between">
      <p class="text-sm text-red-700">{{ error }}</p>
      <button class="text-red-500 hover:text-red-700 ml-4" @click="error = null">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
      <div class="card p-4 flex items-center gap-3">
        <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center shrink-0">
          <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
        <div>
          <p class="text-xs text-gray-500">Tổng ca</p>
          <p class="text-xl font-bold text-gray-900">{{ shifts.length }}</p>
        </div>
      </div>
      <div class="card p-4 flex items-center gap-3">
        <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center shrink-0">
          <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
        <div>
          <p class="text-xs text-gray-500">Đang hoạt động</p>
          <p class="text-xl font-bold text-gray-900">{{ shifts.filter(s => s.status === 'active').length }}</p>
        </div>
      </div>
      <div class="card p-4 flex items-center gap-3">
        <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center shrink-0">
          <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636" />
          </svg>
        </div>
        <div>
          <p class="text-xs text-gray-500">Ngừng dùng</p>
          <p class="text-xl font-bold text-gray-900">{{ shifts.filter(s => s.status !== 'active').length }}</p>
        </div>
      </div>
    </div>

    <!-- Table -->
    <div class="card overflow-hidden">
      <div v-if="loading" class="flex items-center justify-center py-16">
        <div class="h-8 w-8 animate-spin rounded-full border-4 border-blue-600 border-t-transparent"></div>
        <span class="ml-3 text-gray-500">Đang tải...</span>
      </div>

      <div v-else-if="shifts.length === 0" class="text-center py-16">
        <svg class="mx-auto w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
        </svg>
        <p class="text-gray-500 mb-3">Chưa có ca làm việc nào</p>
        <button class="btn-primary" @click="openAdd">Thêm ca đầu tiên</button>
      </div>

      <div v-else class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-100">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Tên ca</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Giờ bắt đầu</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Giờ kết thúc</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Thời lượng</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Trạng thái</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Số lần dùng</th>
              <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">Hành động</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-50">
            <tr v-for="shift in shifts" :key="shift.id" class="hover:bg-gray-50 transition-colors">
              <td class="px-6 py-4">
                <div class="flex items-center gap-2">
                  <div class="w-8 h-8 bg-indigo-100 rounded-lg flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </div>
                  <span class="text-sm font-medium text-gray-900">{{ shift.name }}</span>
                </div>
              </td>
              <td class="px-6 py-4">
                <span class="text-sm font-semibold text-green-700 bg-green-50 px-2 py-0.5 rounded-md">
                  {{ formatTime(shift.start_time) }}
                </span>
              </td>
              <td class="px-6 py-4">
                <span class="text-sm font-semibold text-blue-700 bg-blue-50 px-2 py-0.5 rounded-md">
                  {{ formatTime(shift.end_time) }}
                </span>
              </td>
              <td class="px-6 py-4 text-sm text-gray-600">
                {{ calcDuration(shift.start_time, shift.end_time) }}
              </td>
              <td class="px-6 py-4">
                <span :class="statusBadge(shift.status)">{{ statusLabel(shift.status) }}</span>
              </td>
              <td class="px-6 py-4">
                <span class="text-sm text-gray-700 font-medium">{{ shift.usage_count ?? 0 }}</span>
                <span class="text-xs text-gray-400 ml-1">lần</span>
              </td>
              <td class="px-6 py-4 text-right">
                <div class="flex items-center justify-end gap-2">
                  <button
                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors"
                    @click="openEdit(shift)"
                  >
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Sửa
                  </button>
                  <button
                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors"
                    @click="confirmDelete(shift)"
                  >
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Xóa
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Add/Edit Modal -->
    <Teleport to="body">
      <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md">
          <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-900">
              {{ editingId ? 'Chỉnh sửa ca làm việc' : 'Thêm ca làm việc mới' }}
            </h2>
            <button class="text-gray-400 hover:text-gray-600 transition-colors" @click="closeModal">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>

          <div class="px-6 py-4 space-y-4">
            <div v-if="saveError" class="bg-red-50 border border-red-200 rounded-lg px-3 py-2 text-sm text-red-700">
              {{ saveError }}
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Tên ca <span class="text-red-500">*</span></label>
              <input v-model="form.name" type="text" class="input-field" placeholder="VD: Ca sáng, Ca chiều..." />
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

            <div v-if="form.start_time && form.end_time" class="bg-blue-50 rounded-lg px-3 py-2 text-sm text-blue-700 flex items-center gap-2">
              <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              Thời lượng: {{ calcDuration(form.start_time, form.end_time) }}
            </div>
          </div>

          <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-100">
            <button class="btn-secondary" :disabled="saving" @click="closeModal">Hủy</button>
            <button class="btn-primary" :disabled="saving" @click="saveShift">
              <svg v-if="saving" class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z" />
              </svg>
              {{ editingId ? 'Lưu thay đổi' : 'Thêm ca' }}
            </button>
          </div>
        </div>
      </div>
    </Teleport>

    <!-- Delete Confirm Modal -->
    <Teleport to="body">
      <div v-if="showDeleteConfirm" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6">
          <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center shrink-0">
              <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
              </svg>
            </div>
            <div>
              <h3 class="font-semibold text-gray-900">Xác nhận xóa</h3>
              <p class="text-sm text-gray-500">Hành động này không thể hoàn tác.</p>
            </div>
          </div>
          <p class="text-sm text-gray-700 mb-6">
            Bạn có chắc muốn xóa ca làm việc <strong>{{ deletingName }}</strong>?
            Dữ liệu liên quan có thể bị ảnh hưởng.
          </p>
          <div class="flex gap-3 justify-end">
            <button class="btn-secondary" :disabled="deleting" @click="showDeleteConfirm = false">Hủy</button>
            <button class="btn-danger" :disabled="deleting" @click="deleteShift">
              <svg v-if="deleting" class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z" />
              </svg>
              Xóa ca làm việc
            </button>
          </div>
        </div>
      </div>
    </Teleport>
  </div>
</template>
