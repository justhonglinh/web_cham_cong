<script setup lang="ts">
definePageMeta({ layout: 'default' })

const api = useApi()

interface WeeklyStat {
  day: string
  count: number
}

interface ShiftTimeStat {
  name: string
  count: number
}

interface OvertimeRequest {
  id: number
  employee_name: string
  date: string
  hours: number
  reason: string
  status: 'pending' | 'approved' | 'rejected'
}

interface Attendance {
  id: number
  employee_name: string
  date: string
  check_in: string | null
  check_out: string | null
  status: 'present' | 'late' | 'absent'
}

interface LeaveRequest {
  id: number
  employee_name: string
  start_date: string
  end_date: string
  reason: string
  status: 'pending' | 'approved' | 'rejected'
}

interface DashboardData {
  employeesCount: number
  shiftsCount: number
  activeShifts: number
  oldShifts: number
  unusedShifts: number
  overtimesCount: number
  approvedOvertimeRequests: number
  pendingOvertimeRequests: number
  attendancesCount: number
  lateAttendances: number
  pendingLeaveRequests: number
  approvedLeaveRequests: number
  overtimeRequestsCount: number
  weeklyStats: WeeklyStat[]
  shiftTimeStats: ShiftTimeStat[]
  recentOvertimeRequests: OvertimeRequest[]
  recentAttendances: Attendance[]
  recentLeaveRequests: LeaveRequest[]
}

const loading = ref(true)
const error = ref<string | null>(null)
const data = ref<DashboardData | null>(null)

async function fetchDashboard() {
  loading.value = true
  error.value = null
  try {
    const res = await api.get<DashboardData>('/dashboard')
    data.value = res
  } catch (e: any) {
    error.value = e?.data?.message || 'Không thể tải dữ liệu dashboard.'
  } finally {
    loading.value = false
  }
}

const maxWeeklyCount = computed(() => {
  if (!data.value?.weeklyStats?.length) return 1
  return Math.max(...data.value.weeklyStats.map(s => s.count), 1)
})

function statusBadge(status: string) {
  if (status === 'present') return 'badge-success'
  if (status === 'late') return 'badge-warning'
  if (status === 'absent') return 'badge-danger'
  if (status === 'approved') return 'badge-success'
  if (status === 'pending') return 'badge-warning'
  if (status === 'rejected') return 'badge-danger'
  return 'badge-info'
}

function statusLabel(status: string) {
  const map: Record<string, string> = {
    present: 'Có mặt',
    late: 'Đi trễ',
    absent: 'Vắng mặt',
    approved: 'Đã duyệt',
    pending: 'Chờ duyệt',
    rejected: 'Từ chối',
  }
  return map[status] ?? status
}

function formatTime(t: string | null) {
  if (!t) return '--:--'
  return t.slice(0, 5)
}

onMounted(fetchDashboard)
</script>

<template>
  <div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="rounded-2xl bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 p-8 text-white shadow-xl">
      <h1 class="text-3xl font-bold">Dashboard</h1>
      <p class="mt-1 text-blue-100 text-lg">Tổng quan hệ thống quản lý chấm công</p>
    </div>

    <!-- Loading -->
    <div v-if="loading" class="flex items-center justify-center py-20">
      <div class="h-10 w-10 animate-spin rounded-full border-4 border-blue-600 border-t-transparent"></div>
      <span class="ml-3 text-gray-500">Đang tải dữ liệu...</span>
    </div>

    <!-- Error -->
    <div v-else-if="error" class="card p-6 border-l-4 border-red-500 flex items-start gap-3">
      <svg class="w-5 h-5 text-red-500 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
      </svg>
      <div>
        <p class="font-semibold text-red-700">Lỗi tải dữ liệu</p>
        <p class="text-sm text-red-600">{{ error }}</p>
        <button class="mt-2 btn-primary text-sm" @click="fetchDashboard">Thử lại</button>
      </div>
    </div>

    <template v-else-if="data">
      <!-- Stat Cards -->
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="card p-5">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Tổng nhân viên</p>
              <p class="mt-1 text-3xl font-bold text-gray-900">{{ data.employeesCount }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
              <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
            </div>
          </div>
        </div>

        <div class="card p-5">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Ca làm việc</p>
              <p class="mt-1 text-3xl font-bold text-gray-900">{{ data.shiftsCount }}</p>
              <p class="text-xs text-gray-400 mt-1">{{ data.activeShifts }} đang dùng</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
              <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
          </div>
        </div>

        <div class="card p-5">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Ca tăng ca</p>
              <p class="mt-1 text-3xl font-bold text-gray-900">{{ data.overtimesCount }}</p>
              <p class="text-xs text-gray-400 mt-1">{{ data.approvedOvertimeRequests }} đã duyệt</p>
            </div>
            <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
              <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
              </svg>
            </div>
          </div>
        </div>

        <div class="card p-5">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-xs font-medium text-gray-500 uppercase tracking-wide">Chấm công hôm nay</p>
              <p class="mt-1 text-3xl font-bold text-gray-900">{{ data.attendancesCount }}</p>
              <p class="text-xs text-gray-400 mt-1">{{ data.lateAttendances }} đi trễ</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
              <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
              </svg>
            </div>
          </div>
        </div>
      </div>

      <!-- Alert Cards -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div v-if="data.pendingOvertimeRequests > 0" class="card p-4 border-l-4 border-orange-400 flex items-center gap-3">
          <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
          </div>
          <div>
            <p class="font-semibold text-gray-800">Tăng ca chờ duyệt</p>
            <p class="text-sm text-gray-500">{{ data.pendingOvertimeRequests }} yêu cầu cần xem xét</p>
          </div>
        </div>

        <div v-if="data.pendingLeaveRequests > 0" class="card p-4 border-l-4 border-yellow-400 flex items-center gap-3">
          <div class="w-10 h-10 bg-yellow-100 rounded-lg flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
          </div>
          <div>
            <p class="font-semibold text-gray-800">Nghỉ phép chờ duyệt</p>
            <p class="text-sm text-gray-500">{{ data.pendingLeaveRequests }} yêu cầu cần xem xét</p>
          </div>
        </div>

        <div v-if="data.lateAttendances > 0" class="card p-4 border-l-4 border-red-400 flex items-center gap-3">
          <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <div>
            <p class="font-semibold text-gray-800">Nhân viên đi trễ</p>
            <p class="text-sm text-gray-500">{{ data.lateAttendances }} người đi trễ hôm nay</p>
          </div>
        </div>
      </div>

      <!-- Weekly Bar Chart + Recent Overtime -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Weekly Attendance Chart -->
        <div class="card p-6">
          <h2 class="text-lg font-semibold text-gray-800 mb-4">Chấm công tuần này</h2>
          <div v-if="data.weeklyStats && data.weeklyStats.length" class="flex items-end gap-3 h-40">
            <div
              v-for="stat in data.weeklyStats"
              :key="stat.day"
              class="flex-1 flex flex-col items-center gap-1"
            >
              <span class="text-xs font-medium text-gray-600">{{ stat.count }}</span>
              <div
                class="w-full bg-blue-500 rounded-t-md transition-all duration-500"
                :style="{ height: `${Math.max((stat.count / maxWeeklyCount) * 120, 4)}px` }"
              ></div>
              <span class="text-xs text-gray-400">{{ stat.day }}</span>
            </div>
          </div>
          <div v-else class="flex items-center justify-center h-40 text-gray-400 text-sm">
            Không có dữ liệu tuần này
          </div>
        </div>

        <!-- Recent Overtime Requests -->
        <div class="card p-6">
          <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-800">Tăng ca gần đây</h2>
            <NuxtLink to="/overtime/management" class="text-sm text-blue-600 hover:underline">Xem tất cả</NuxtLink>
          </div>
          <div v-if="data.recentOvertimeRequests && data.recentOvertimeRequests.length" class="space-y-3">
            <div
              v-for="req in data.recentOvertimeRequests"
              :key="req.id"
              class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0"
            >
              <div>
                <p class="text-sm font-medium text-gray-800">{{ req.employee_name }}</p>
                <p class="text-xs text-gray-400">{{ req.date }} &bull; {{ req.hours }}h</p>
              </div>
              <span :class="statusBadge(req.status)">{{ statusLabel(req.status) }}</span>
            </div>
          </div>
          <div v-else class="text-center py-8 text-gray-400 text-sm">Không có yêu cầu tăng ca</div>
        </div>
      </div>

      <!-- Recent Attendances + Recent Leave Requests -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Attendances -->
        <div class="card p-6">
          <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-800">Chấm công gần đây</h2>
            <NuxtLink to="/attendance/management" class="text-sm text-blue-600 hover:underline">Xem tất cả</NuxtLink>
          </div>
          <div v-if="data.recentAttendances && data.recentAttendances.length" class="space-y-3">
            <div
              v-for="att in data.recentAttendances"
              :key="att.id"
              class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0"
            >
              <div>
                <p class="text-sm font-medium text-gray-800">{{ att.employee_name }}</p>
                <p class="text-xs text-gray-400">
                  {{ att.date }} &bull; Vào: {{ formatTime(att.check_in) }} / Ra: {{ formatTime(att.check_out) }}
                </p>
              </div>
              <span :class="statusBadge(att.status)">{{ statusLabel(att.status) }}</span>
            </div>
          </div>
          <div v-else class="text-center py-8 text-gray-400 text-sm">Không có dữ liệu chấm công</div>
        </div>

        <!-- Recent Leave Requests -->
        <div class="card p-6">
          <div class="flex items-center justify-between mb-4">
            <h2 class="text-lg font-semibold text-gray-800">Nghỉ phép gần đây</h2>
            <NuxtLink to="/leave-requests/management" class="text-sm text-blue-600 hover:underline">Xem tất cả</NuxtLink>
          </div>
          <div v-if="data.recentLeaveRequests && data.recentLeaveRequests.length" class="space-y-3">
            <div
              v-for="leave in data.recentLeaveRequests"
              :key="leave.id"
              class="flex items-center justify-between py-2 border-b border-gray-50 last:border-0"
            >
              <div>
                <p class="text-sm font-medium text-gray-800">{{ leave.employee_name }}</p>
                <p class="text-xs text-gray-400">{{ leave.start_date }} → {{ leave.end_date }}</p>
                <p class="text-xs text-gray-400 truncate max-w-[200px]">{{ leave.reason }}</p>
              </div>
              <span :class="statusBadge(leave.status)">{{ statusLabel(leave.status) }}</span>
            </div>
          </div>
          <div v-else class="text-center py-8 text-gray-400 text-sm">Không có yêu cầu nghỉ phép</div>
        </div>
      </div>
    </template>
  </div>
</template>
