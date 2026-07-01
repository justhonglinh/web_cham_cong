<script setup lang="ts">
import { WORK_SUMMARY_STATUS_BADGE, WORK_SUMMARY_STATUS_LABEL } from '~/constants'
import type { WorkSummary } from '~/types/workSummary'

definePageMeta({ layout: 'default' })

const api = useApi()
const authStore = useAuthStore()

const summaries = ref<WorkSummary[]>([])
const loading = ref(false)
const error = ref('')
const exportLoading = ref(false)

const currentDate = new Date()
const filterMonth = ref(currentDate.getMonth() + 1)
const filterYear = ref(currentDate.getFullYear())
const searchName = ref('')

const months = [
  { value: 1, label: 'Tháng 1' }, { value: 2, label: 'Tháng 2' },
  { value: 3, label: 'Tháng 3' }, { value: 4, label: 'Tháng 4' },
  { value: 5, label: 'Tháng 5' }, { value: 6, label: 'Tháng 6' },
  { value: 7, label: 'Tháng 7' }, { value: 8, label: 'Tháng 8' },
  { value: 9, label: 'Tháng 9' }, { value: 10, label: 'Tháng 10' },
  { value: 11, label: 'Tháng 11' }, { value: 12, label: 'Tháng 12' },
]

const years = computed(() => {
  const y = currentDate.getFullYear()
  return [y - 2, y - 1, y, y + 1]
})

const filteredSummaries = computed(() => {
  if (!searchName.value.trim()) return summaries.value
  const q = searchName.value.trim().toLowerCase()
  return summaries.value.filter(s => s.employee_name.toLowerCase().includes(q))
})

async function fetchSummaries() {
  loading.value = true
  error.value = ''
  try {
    const data = await api.get<{ data: WorkSummary[] }>('/work-summary/management', {
      month: filterMonth.value,
      year: filterYear.value,
    })
    summaries.value = data.data ?? []
  } catch {
    error.value = 'Không thể tải báo cáo tổng hợp. Vui lòng thử lại.'
  } finally {
    loading.value = false
  }
}

async function exportExcel() {
  exportLoading.value = true
  try {
    const url = `/api/work-summary/export?month=${filterMonth.value}&year=${filterYear.value}`
    const response = await fetch(url, {
      headers: { Authorization: `Bearer ${authStore.token}` },
    })
    if (!response.ok) throw new Error('Export failed')
    const blob = await response.blob()
    const link = document.createElement('a')
    link.href = URL.createObjectURL(blob)
    link.download = `bao-cao-cong-${filterMonth.value}-${filterYear.value}.xlsx`
    link.click()
    URL.revokeObjectURL(link.href)
  } catch {
    alert('Xuất file thất bại. Vui lòng thử lại.')
  } finally {
    exportLoading.value = false
  }
}

function statusBadgeClass(status: string) {
  return WORK_SUMMARY_STATUS_BADGE[status] ?? 'badge-info'
}

function statusLabel(status: string) {
  return WORK_SUMMARY_STATUS_LABEL[status] ?? status
}

watch([filterMonth, filterYear], fetchSummaries)
onMounted(fetchSummaries)
</script>

<template>
  <div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Báo cáo tổng hợp công việc</h1>
        <p class="text-sm text-gray-500 mt-1">Xem tổng hợp công việc của tất cả nhân viên</p>
      </div>
      <button
        class="btn-primary"
        :disabled="exportLoading || loading"
        @click="exportExcel"
      >
        <svg v-if="exportLoading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
        </svg>
        <svg v-else class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
        </svg>
        {{ exportLoading ? 'Đang xuất...' : 'Xuất Excel' }}
      </button>
    </div>

    <!-- Filters -->
    <div class="card p-4">
      <div class="flex flex-wrap gap-4 items-end">
        <div>
          <label class="block text-xs font-medium text-gray-600 mb-1">Tháng</label>
          <select v-model.number="filterMonth" class="input-field w-36">
            <option v-for="m in months" :key="m.value" :value="m.value">{{ m.label }}</option>
          </select>
        </div>
        <div>
          <label class="block text-xs font-medium text-gray-600 mb-1">Năm</label>
          <select v-model.number="filterYear" class="input-field w-28">
            <option v-for="y in years" :key="y" :value="y">{{ y }}</option>
          </select>
        </div>
        <div class="flex-1 min-w-48">
          <label class="block text-xs font-medium text-gray-600 mb-1">Tìm theo tên nhân viên</label>
          <div class="relative">
            <input
              v-model="searchName"
              type="text"
              class="input-field pl-9"
              placeholder="Nhập tên nhân viên..."
            />
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </div>
        </div>
        <button class="btn-secondary" :disabled="loading" @click="fetchSummaries">
          <svg v-if="loading" class="animate-spin h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
          </svg>
          {{ loading ? 'Đang tải...' : 'Tìm kiếm' }}
        </button>
      </div>
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
      <div v-else-if="filteredSummaries.length > 0" class="overflow-x-auto">
        <div class="mb-3 text-sm text-gray-500">
          Hiển thị <strong>{{ filteredSummaries.length }}</strong> nhân viên —
          Tháng {{ filterMonth }}/{{ filterYear }}
        </div>
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nhân viên</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tháng</th>
              <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Tổng ngày làm</th>
              <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Tổng giờ làm</th>
              <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày nghỉ</th>
              <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Tăng ca (giờ)</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-100">
            <tr v-for="s in filteredSummaries" :key="s.id" class="hover:bg-gray-50 transition-colors">
              <td class="px-4 py-3 text-sm font-medium text-gray-900 whitespace-nowrap">{{ s.employee_name }}</td>
              <td class="px-4 py-3 text-sm text-gray-600 whitespace-nowrap">{{ s.month }}/{{ s.year }}</td>
              <td class="px-4 py-3 text-sm text-gray-700 text-right font-medium">{{ s.total_work_days ?? 0 }}</td>
              <td class="px-4 py-3 text-sm text-gray-700 text-right font-medium">{{ s.total_work_hours ?? 0 }}h</td>
              <td class="px-4 py-3 text-sm text-right">
                <span :class="(s.leave_days ?? 0) > 0 ? 'text-amber-600 font-medium' : 'text-gray-600'">
                  {{ s.leave_days ?? 0 }}
                </span>
              </td>
              <td class="px-4 py-3 text-sm text-right">
                <span :class="(s.overtime_hours ?? 0) > 0 ? 'text-blue-600 font-medium' : 'text-gray-600'">
                  {{ s.overtime_hours ?? 0 }}h
                </span>
              </td>
              <td class="px-4 py-3 text-sm whitespace-nowrap">
                <span :class="statusBadgeClass(s.status)">{{ statusLabel(s.status) }}</span>
              </td>
            </tr>
          </tbody>
          <!-- Totals row -->
          <tfoot class="bg-gray-50 border-t-2 border-gray-200">
            <tr>
              <td class="px-4 py-3 text-sm font-semibold text-gray-700" colspan="2">Tổng cộng</td>
              <td class="px-4 py-3 text-sm font-semibold text-gray-900 text-right">
                {{ filteredSummaries.reduce((acc, s) => acc + (s.total_work_days ?? 0), 0) }}
              </td>
              <td class="px-4 py-3 text-sm font-semibold text-gray-900 text-right">
                {{ filteredSummaries.reduce((acc, s) => acc + (s.total_work_hours ?? 0), 0) }}h
              </td>
              <td class="px-4 py-3 text-sm font-semibold text-amber-600 text-right">
                {{ filteredSummaries.reduce((acc, s) => acc + (s.leave_days ?? 0), 0) }}
              </td>
              <td class="px-4 py-3 text-sm font-semibold text-blue-600 text-right">
                {{ filteredSummaries.reduce((acc, s) => acc + (s.overtime_hours ?? 0), 0) }}h
              </td>
              <td class="px-4 py-3"></td>
            </tr>
          </tfoot>
        </table>
      </div>

      <!-- Empty -->
      <div v-else class="text-center py-16 text-gray-400">
        <svg class="mx-auto h-14 w-14 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        <p class="font-medium text-gray-500">Không có dữ liệu cho tháng {{ filterMonth }}/{{ filterYear }}</p>
        <p class="text-sm mt-1">Thử chọn tháng hoặc năm khác</p>
      </div>
    </div>
  </div>
</template>
