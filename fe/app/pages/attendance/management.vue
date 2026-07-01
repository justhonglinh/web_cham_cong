<script setup lang="ts">
import { ATTENDANCE_STATUS_BADGE, ATTENDANCE_STATUS_LABEL } from '~/constants'
import type { AttendanceRecord } from '~/types/attendance'
import { formatDate, formatTime } from '~/utils/format'

definePageMeta({ layout: 'default' })

const api = useApi()

const loading = ref(true)
const saving = ref(false)
const error = ref<string | null>(null)
const saveError = ref<string | null>(null)

const records = ref<AttendanceRecord[]>([])

const now = new Date()
const filterMonth = ref(String(now.getMonth() + 1).padStart(2, '0'))
const filterYear = ref(String(now.getFullYear()))

const showModal = ref(false)
const editingRecord = ref<AttendanceRecord | null>(null)
const editForm = ref({ check_in: '', check_out: '' })

const { currentPage, lastPage, total, perPage, setTotal, paginateArray, goToPage, visiblePages, summaryFrom, summaryTo } = usePagination(20)

const pagedRecords = computed(() => paginateArray(records.value))
watch([filterMonth, filterYear], () => { currentPage.value = 1 })

async function fetchAttendance() {
  loading.value = true
  error.value = null
  try {
    const res = await api.get<{ data: AttendanceRecord[] } | AttendanceRecord[]>(
      '/attendance/management',
      { month: filterMonth.value, year: filterYear.value }
    )
    records.value = Array.isArray(res) ? res : (res as any).data ?? []
    setTotal(records.value.length)
  } catch (e: any) {
    error.value = e?.data?.message || 'Không thể tải dữ liệu chấm công.'
  } finally {
    loading.value = false
  }
}

function openEdit(record: AttendanceRecord) {
  editingRecord.value = record
  editForm.value = {
    check_in: record.check_in?.slice(0, 5) ?? '',
    check_out: record.check_out?.slice(0, 5) ?? '',
  }
  saveError.value = null
  showModal.value = true
}

function closeModal() {
  showModal.value = false
  editingRecord.value = null
  saveError.value = null
}

async function saveAttendance() {
  if (!editingRecord.value) return
  saving.value = true
  saveError.value = null
  try {
    const updated = await api.put<AttendanceRecord>(
      `/attendance/management/${editingRecord.value.id}`,
      {
        check_in: editForm.value.check_in || null,
        check_out: editForm.value.check_out || null,
      },
      { success: 'Cập nhật chấm công thành công.' }
    )
    const idx = records.value.findIndex(r => r.id === editingRecord.value!.id)
    if (idx !== -1) records.value[idx] = updated
    closeModal()
  } catch (e: any) {
    saveError.value = e?.data?.message || 'Lưu thất bại. Vui lòng thử lại.'
  } finally {
    saving.value = false
  }
}

function statusBadge(status: string) {
  return ATTENDANCE_STATUS_BADGE[status] ?? 'badge-danger'
}

function statusLabel(status: string) {
  return ATTENDANCE_STATUS_LABEL[status] ?? status
}


const years = computed(() => {
  const y = now.getFullYear()
  return [y - 1, y, y + 1].map(String)
})

const months = [
  { value: '01', label: 'Tháng 1' },
  { value: '02', label: 'Tháng 2' },
  { value: '03', label: 'Tháng 3' },
  { value: '04', label: 'Tháng 4' },
  { value: '05', label: 'Tháng 5' },
  { value: '06', label: 'Tháng 6' },
  { value: '07', label: 'Tháng 7' },
  { value: '08', label: 'Tháng 8' },
  { value: '09', label: 'Tháng 9' },
  { value: '10', label: 'Tháng 10' },
  { value: '11', label: 'Tháng 11' },
  { value: '12', label: 'Tháng 12' },
]

onMounted(fetchAttendance)
</script>

<template>
  <div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div>
      <h1 class="text-2xl font-bold text-gray-900">Quản lý chấm công</h1>
      <p class="text-sm text-gray-500 mt-0.5">Xem và chỉnh sửa dữ liệu chấm công của nhân viên</p>
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

    <!-- Filters -->
    <div class="card p-4">
      <div class="flex flex-wrap items-end gap-3">
        <div>
          <label class="block text-xs font-medium text-gray-500 mb-1">Tháng</label>
          <select v-model="filterMonth" class="input-field w-32">
            <option v-for="m in months" :key="m.value" :value="m.value">{{ m.label }}</option>
          </select>
        </div>
        <div>
          <label class="block text-xs font-medium text-gray-500 mb-1">Năm</label>
          <select v-model="filterYear" class="input-field w-28">
            <option v-for="y in years" :key="y" :value="y">{{ y }}</option>
          </select>
        </div>
        <button class="btn-primary" @click="fetchAttendance">
          <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2a1 1 0 01-.293.707L13 13.414V19a1 1 0 01-.553.894l-4 2A1 1 0 017 21v-7.586L3.293 6.707A1 1 0 013 6V4z" />
          </svg>
          Lọc
        </button>
      </div>
    </div>

    <!-- Table -->
    <div class="card overflow-hidden">
      <div v-if="loading" class="flex items-center justify-center py-16">
        <div class="h-8 w-8 animate-spin rounded-full border-4 border-blue-600 border-t-transparent"></div>
        <span class="ml-3 text-gray-500">Đang tải...</span>
      </div>

      <div v-else-if="records.length === 0" class="text-center py-16">
        <svg class="mx-auto w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
        </svg>
        <p class="text-gray-500">Không có dữ liệu chấm công cho tháng này</p>
      </div>

      <div v-else class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-100">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Nhân viên</th>
              <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Ngày</th>
              <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Giờ vào</th>
              <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Giờ ra</th>
              <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Ca làm</th>
              <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Trạng thái</th>
              <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">Hành động</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-50">
            <tr v-for="record in pagedRecords" :key="record.id" class="hover:bg-gray-50 transition-colors">
              <td class="px-5 py-3">
                <div class="flex items-center gap-2">
                  <div class="w-7 h-7 rounded-full bg-purple-100 flex items-center justify-center text-purple-700 font-semibold text-xs shrink-0">
                    {{ record.employee_name.charAt(0).toUpperCase() }}
                  </div>
                  <span class="text-sm font-medium text-gray-900">{{ record.employee_name }}</span>
                </div>
              </td>
              <td class="px-5 py-3 text-sm text-gray-600">{{ formatDate(record.date) }}</td>
              <td class="px-5 py-3">
                <span class="text-sm font-medium" :class="record.check_in ? 'text-green-700' : 'text-gray-400'">
                  {{ formatTime(record.check_in) }}
                </span>
              </td>
              <td class="px-5 py-3">
                <span class="text-sm font-medium" :class="record.check_out ? 'text-blue-700' : 'text-gray-400'">
                  {{ formatTime(record.check_out) }}
                </span>
              </td>
              <td class="px-5 py-3 text-sm text-gray-600">{{ record.shift_name || '—' }}</td>
              <td class="px-5 py-3">
                <span :class="statusBadge(record.status)">{{ statusLabel(record.status) }}</span>
              </td>
              <td class="px-5 py-3 text-right">
                <button
                  class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors"
                  @click="openEdit(record)"
                >
                  <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                  </svg>
                  Sửa
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <PaginationBar
        v-if="!loading"
        :current-page="currentPage" :last-page="lastPage"
        :total="total" :summary-from="summaryFrom" :summary-to="summaryTo"
        :visible-pages="visiblePages"
        @go-to-page="goToPage"
      />
    </div>

    <!-- Edit Modal -->
    <Teleport to="body">
      <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md">
          <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <div>
              <h2 class="text-lg font-semibold text-gray-900">Chỉnh sửa chấm công</h2>
              <p v-if="editingRecord" class="text-xs text-gray-500 mt-0.5">
                {{ editingRecord.employee_name }} &bull; {{ formatDate(editingRecord.date) }}
              </p>
            </div>
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
              <label class="block text-sm font-medium text-gray-700 mb-1">Giờ vào</label>
              <input v-model="editForm.check_in" type="time" class="input-field" />
              <p class="text-xs text-gray-400 mt-1">Để trống nếu chưa có giờ vào</p>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Giờ ra</label>
              <input v-model="editForm.check_out" type="time" class="input-field" />
              <p class="text-xs text-gray-400 mt-1">Để trống nếu chưa có giờ ra</p>
            </div>
          </div>

          <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-100">
            <button class="btn-secondary" :disabled="saving" @click="closeModal">Hủy</button>
            <button class="btn-primary" :disabled="saving" @click="saveAttendance">
              <svg v-if="saving" class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z" />
              </svg>
              Lưu thay đổi
            </button>
          </div>
        </div>
      </div>
    </Teleport>
  </div>
</template>
