<script setup lang="ts">
import { ATTENDANCE_STATUS_BADGE, ATTENDANCE_STATUS_LABEL } from '~/constants'
import type { AttendanceRecord } from '~/types/attendance'
import { formatDate, formatTime } from '~/utils/format'

definePageMeta({ layout: 'default' })

const authStore = useAuthStore()
const api = useApi()

const recentAttendance = ref<AttendanceRecord[]>([])
const loadingAttendance = ref(false)

// Vietnamese date helpers
const today = computed(() => {
  const now = new Date()
  const days = ['Chủ nhật', 'Thứ hai', 'Thứ ba', 'Thứ tư', 'Thứ năm', 'Thứ sáu', 'Thứ bảy']
  const months = [
    'tháng 1', 'tháng 2', 'tháng 3', 'tháng 4', 'tháng 5', 'tháng 6',
    'tháng 7', 'tháng 8', 'tháng 9', 'tháng 10', 'tháng 11', 'tháng 12',
  ]
  return `${days[now.getDay()]}, ${now.getDate()} ${months[now.getMonth()]} ${now.getFullYear()}`
})


function statusLabel(status: string) {
  return ATTENDANCE_STATUS_LABEL[status] ?? status
}

function statusClass(status: string) {
  return ATTENDANCE_STATUS_BADGE[status] ?? 'badge-info'
}

async function fetchRecentAttendance() {
  loadingAttendance.value = true
  try {
    const data = await api.get<{ data: AttendanceRecord[] } | AttendanceRecord[]>(
      '/employees/attendance/history',
      { limit: 5, per_page: 5, page: 1 },
    )
    if (Array.isArray(data)) {
      recentAttendance.value = data.slice(0, 5)
    } else {
      recentAttendance.value = (data.data ?? []).slice(0, 5)
    }
  } catch {
    recentAttendance.value = []
  } finally {
    loadingAttendance.value = false
  }
}

const quickActions = [
  {
    title: 'Chấm công',
    description: 'Điểm danh hôm nay',
    icon: 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
    link: '/employees/attendance',
    color: 'from-blue-500 to-blue-600',
    bg: 'bg-blue-50',
    text: 'text-blue-700',
  },
  {
    title: 'Lịch sử',
    description: 'Xem lịch sử chấm công',
    icon: 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',
    link: '/employees/attendance/history',
    color: 'from-purple-500 to-purple-600',
    bg: 'bg-purple-50',
    text: 'text-purple-700',
  },
  {
    title: 'Tăng ca',
    description: 'Đăng ký tăng ca',
    icon: 'M13 10V3L4 14h7v7l9-11h-7z',
    link: '/overtime/employee',
    color: 'from-orange-500 to-orange-600',
    bg: 'bg-orange-50',
    text: 'text-orange-700',
  },
  {
    title: 'Nghỉ phép',
    description: 'Quản lý nghỉ phép',
    icon: 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
    link: '/employees/leave/history',
    color: 'from-green-500 to-green-600',
    bg: 'bg-green-50',
    text: 'text-green-700',
  },
]

onMounted(fetchRecentAttendance)
</script>

<template>
  <div class="max-w-5xl mx-auto space-y-6">
    <!-- Hero Section -->
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-blue-600 via-purple-600 to-pink-600 p-8 text-white shadow-xl">
      <!-- Decorative circles -->
      <div class="absolute -top-10 -right-10 w-48 h-48 bg-white/10 rounded-full blur-2xl" />
      <div class="absolute -bottom-8 -left-8 w-36 h-36 bg-white/10 rounded-full blur-2xl" />

      <div class="relative z-10">
        <p class="text-blue-100 text-sm font-medium uppercase tracking-wider mb-1">Xin chào</p>
        <h1 class="text-3xl font-bold mb-1">{{ authStore.user?.name ?? 'Nhân viên' }} 👋</h1>
        <p class="text-blue-100 text-base">{{ today }}</p>

        <div class="mt-6 inline-flex items-center gap-2 bg-white/20 backdrop-blur-sm rounded-xl px-4 py-2 text-sm font-medium">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          Chúc bạn một ngày làm việc hiệu quả!
        </div>
      </div>
    </div>

    <!-- Quick Actions -->
    <div>
      <h2 class="text-lg font-semibold text-gray-800 mb-3">Truy cập nhanh</h2>
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <NuxtLink
          v-for="action in quickActions"
          :key="action.title"
          :to="action.link"
          class="card p-5 flex flex-col items-center text-center gap-3 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200 cursor-pointer group"
        >
          <div :class="['w-12 h-12 rounded-xl flex items-center justify-center bg-gradient-to-br', action.color]">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="action.icon" />
            </svg>
          </div>
          <div>
            <p class="font-semibold text-gray-800 text-sm group-hover:text-blue-600 transition-colors">{{ action.title }}</p>
            <p class="text-xs text-gray-400 mt-0.5">{{ action.description }}</p>
          </div>
        </NuxtLink>
      </div>
    </div>

    <!-- Recent Attendance -->
    <div class="card overflow-hidden">
      <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
        <h2 class="text-lg font-semibold text-gray-800">Chấm công gần đây</h2>
        <NuxtLink to="/employees/attendance/history" class="text-sm text-blue-600 hover:underline font-medium">
          Xem tất cả
        </NuxtLink>
      </div>

      <!-- Loading -->
      <div v-if="loadingAttendance" class="p-6 space-y-3">
        <div v-for="i in 3" :key="i" class="animate-pulse flex gap-4 items-center">
          <div class="h-4 bg-gray-200 rounded w-24" />
          <div class="h-4 bg-gray-200 rounded w-16" />
          <div class="h-4 bg-gray-200 rounded w-16" />
          <div class="h-4 bg-gray-200 rounded flex-1" />
          <div class="h-5 bg-gray-200 rounded-full w-16" />
        </div>
      </div>

      <!-- Empty -->
      <div v-else-if="recentAttendance.length === 0" class="p-12 text-center">
        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
            d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
        </svg>
        <p class="text-gray-400 text-sm">Chưa có dữ liệu chấm công.</p>
      </div>

      <!-- Table -->
      <div v-else class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-100">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ngày</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Giờ vào</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Giờ ra</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ca làm</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Trạng thái</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-50">
            <tr v-for="record in recentAttendance" :key="record.id" class="hover:bg-gray-50 transition-colors">
              <td class="px-6 py-4 text-sm text-gray-700 font-medium whitespace-nowrap">{{ formatDate(record.date) }}</td>
              <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap">{{ formatTime(record.check_in) }}</td>
              <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap">{{ formatTime(record.check_out) }}</td>
              <td class="px-6 py-4 text-sm text-gray-600 whitespace-nowrap">{{ record.shift_name ?? '—' }}</td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="statusClass(record.status)">{{ statusLabel(record.status) }}</span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>
