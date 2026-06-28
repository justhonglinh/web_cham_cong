<script setup lang="ts">
definePageMeta({ layout: 'default' })

const api = useApi()

interface AttendanceRecord {
  id: number
  date: string
  check_in: string | null
  check_out: string | null
  shift_name: string | null
  status: 'present' | 'late' | 'absent' | string
}

interface PaginatedResponse {
  data: AttendanceRecord[]
  current_page: number
  last_page: number
  per_page: number
  total: number
}

const records = ref<AttendanceRecord[]>([])
const loading = ref(false)
const currentPage = ref(1)
const lastPage = ref(1)
const total = ref(0)
const perPage = ref(10)

function formatDate(dateStr: string) {
  if (!dateStr) return '—'
  const d = new Date(dateStr)
  if (isNaN(d.getTime())) return dateStr
  const dd = String(d.getDate()).padStart(2, '0')
  const mm = String(d.getMonth() + 1).padStart(2, '0')
  const yyyy = d.getFullYear()
  return `${dd}/${mm}/${yyyy}`
}

function formatTime(timeStr: string | null) {
  if (!timeStr) return '--:--'
  const parts = timeStr.split(':')
  if (parts.length >= 2) return `${parts[0]}:${parts[1]}`
  const d = new Date(timeStr)
  return isNaN(d.getTime()) ? '--:--' : d.toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit' })
}

function statusLabel(status: string) {
  const map: Record<string, string> = { present: 'Có mặt', late: 'Muộn', absent: 'Vắng' }
  return map[status] ?? status
}

function statusClass(status: string) {
  if (status === 'present') return 'badge-success'
  if (status === 'late') return 'badge-warning'
  if (status === 'absent') return 'badge-danger'
  return 'badge-info'
}

async function fetchHistory(page = 1) {
  loading.value = true
  try {
    const data = await api.get<PaginatedResponse | AttendanceRecord[]>(
      '/employees/attendance/history',
      { page, per_page: perPage.value },
    )
    if (Array.isArray(data)) {
      records.value = data
      currentPage.value = 1
      lastPage.value = 1
      total.value = data.length
    } else {
      const paginated = data as PaginatedResponse
      records.value = paginated.data ?? []
      currentPage.value = paginated.current_page ?? 1
      lastPage.value = paginated.last_page ?? 1
      total.value = paginated.total ?? 0
      perPage.value = paginated.per_page ?? perPage.value
    }
  } catch {
    records.value = []
  } finally {
    loading.value = false
  }
}

function goToPage(page: number) {
  if (page < 1 || page > lastPage.value) return
  currentPage.value = page
  fetchHistory(page)
}

// Visible page numbers (max 5 pages around current)
const visiblePages = computed(() => {
  const total = lastPage.value
  const cur = currentPage.value
  if (total <= 7) return Array.from({ length: total }, (_, i) => i + 1)

  const pages: (number | '...')[] = []
  const start = Math.max(2, cur - 2)
  const end = Math.min(total - 1, cur + 2)

  pages.push(1)
  if (start > 2) pages.push('...')
  for (let p = start; p <= end; p++) pages.push(p)
  if (end < total - 1) pages.push('...')
  pages.push(total)
  return pages
})

const summaryFrom = computed(() => (currentPage.value - 1) * perPage.value + 1)
const summaryTo = computed(() => Math.min(currentPage.value * perPage.value, total.value))

onMounted(() => fetchHistory(1))
</script>

<template>
  <div class="max-w-5xl mx-auto space-y-6">
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

      <!-- Pagination Footer -->
      <div v-if="!loading && records.length > 0" class="px-6 py-4 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-3">
        <p class="text-sm text-gray-500">
          Hiển thị <span class="font-medium text-gray-700">{{ summaryFrom }}–{{ summaryTo }}</span>
          trong tổng số <span class="font-medium text-gray-700">{{ total }}</span> bản ghi
        </p>

        <nav class="flex items-center gap-1">
          <!-- Prev -->
          <button
            class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-sm font-medium transition-colors"
            :class="currentPage === 1
              ? 'text-gray-300 cursor-not-allowed'
              : 'text-gray-600 hover:bg-gray-100'"
            :disabled="currentPage === 1"
            @click="goToPage(currentPage - 1)"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
          </button>

          <!-- Page numbers -->
          <template v-for="(page, i) in visiblePages" :key="i">
            <span v-if="page === '...'" class="w-9 h-9 flex items-center justify-center text-gray-400 text-sm">…</span>
            <button
              v-else
              class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-sm font-medium transition-colors"
              :class="page === currentPage
                ? 'bg-blue-600 text-white'
                : 'text-gray-600 hover:bg-gray-100'"
              @click="goToPage(page as number)"
            >
              {{ page }}
            </button>
          </template>

          <!-- Next -->
          <button
            class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-sm font-medium transition-colors"
            :class="currentPage === lastPage
              ? 'text-gray-300 cursor-not-allowed'
              : 'text-gray-600 hover:bg-gray-100'"
            :disabled="currentPage === lastPage"
            @click="goToPage(currentPage + 1)"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </button>
        </nav>
      </div>
    </div>
  </div>
</template>
