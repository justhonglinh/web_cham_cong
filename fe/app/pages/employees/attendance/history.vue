<script setup lang="ts">
import { ATTENDANCE_STATUS_BADGE, ATTENDANCE_STATUS_LABEL } from '~/constants'
import { attendanceService } from '~/services/attendanceService'
import type { AttendanceRecord } from '~/types/attendance'
import { formatDate, formatTime } from '~/utils/format'

definePageMeta({ layout: 'default' })

const records = ref<AttendanceRecord[]>([])
const loading = ref(false)
const { currentPage, lastPage, total, perPage, setFromResponse, goToPage: goToPageFn, visiblePages, summaryFrom, summaryTo } = usePagination(10)


function statusLabel(status: string) {
  return ATTENDANCE_STATUS_LABEL[status] ?? status
}

function statusClass(status: string) {
  return ATTENDANCE_STATUS_BADGE[status] ?? 'badge-info'
}

async function fetchHistory(page = 1) {
  loading.value = true
  try {
    const data = await attendanceService.getHistory({ page, per_page: perPage.value })
    records.value = setFromResponse(data)
  } catch {
    records.value = []
  } finally {
    loading.value = false
  }
}

function goToPage(page: number) {
  goToPageFn(page, fetchHistory)
}

onMounted(() => fetchHistory(1))
</script>

<template>
  <div class="max-w-5xl mx-auto space-y-6">
    <BackButton to="/employees/dashboard" />

    <!-- Header -->
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Lịch sử chấm công</h1>
        <p class="text-gray-500 text-sm mt-1">
          Danh sách các lần chấm công của bạn.
        </p>
      </div>
      <button @click="fetchHistory(currentPage)" class="btn-secondary gap-2" :disabled="loading">
        <svg class="w-4 h-4" :class="{ 'animate-spin': loading }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
        </svg>
        Làm mới
      </button>
    </div>

    <!-- Table Card -->
    <div class="card overflow-hidden">
      <!-- Loading Skeleton -->
      <div v-if="loading" class="p-6 space-y-4">
        <div v-for="i in 6" :key="i" class="animate-pulse flex gap-4 items-center py-2">
          <div class="h-4 bg-gray-200 rounded w-28" />
          <div class="h-4 bg-gray-200 rounded w-16" />
          <div class="h-4 bg-gray-200 rounded w-16" />
          <div class="h-4 bg-gray-200 rounded flex-1" />
          <div class="h-5 bg-gray-200 rounded-full w-20" />
        </div>
      </div>

      <!-- Empty State -->
      <div v-else-if="records.length === 0" class="py-16 text-center">
        <svg class="w-14 h-14 text-gray-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
        </svg>
        <p class="text-gray-400 font-medium">Không có dữ liệu</p>
        <p class="text-gray-300 text-sm mt-1">Chưa có bản ghi chấm công nào.</p>
      </div>

      <!-- Data Table -->
      <div v-else class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-100">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">STT</th>
              <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ngày</th>
              <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Giờ vào</th>
              <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Giờ ra</th>
              <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ca làm</th>
              <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Trạng thái</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-50">
            <tr
              v-for="(record, index) in records"
              :key="record.id"
              class="hover:bg-gray-50 transition-colors"
            >
              <td class="px-6 py-4 text-sm text-gray-400 whitespace-nowrap">
                {{ summaryFrom + index }}
              </td>
              <td class="px-6 py-4 text-sm font-medium text-gray-800 whitespace-nowrap">
                {{ formatDate(record.date) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span
                  class="text-sm font-mono"
                  :class="record.check_in ? 'text-green-600 font-semibold' : 'text-gray-300'"
                >
                  {{ formatTime(record.check_in) }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span
                  class="text-sm font-mono"
                  :class="record.check_out ? 'text-blue-600 font-semibold' : 'text-gray-300'"
                >
                  {{ formatTime(record.check_out) }}
                </span>
              </td>
              <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap">
                {{ record.shift_name ?? '—' }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="statusClass(record.status)">{{ statusLabel(record.status) }}</span>
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
  </div>
</template>
