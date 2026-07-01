<script setup lang="ts">
import { ATTENDANCE_STATUS_BADGE, ATTENDANCE_STATUS_LABEL } from '~/constants'
import { attendanceService } from '~/services/attendanceService'
import type { AttendanceRecord as TodayAttendance } from '~/types/attendance'
import { formatTime } from '~/utils/format'

definePageMeta({ layout: 'default' })

const todayRecord = ref<TodayAttendance | null>(null)
const loadingToday = ref(false)
const submitting = ref(false)
const successMessage = ref('')
const errorMessage = ref('')

// Live clock
const now = ref(new Date())
let clockInterval: ReturnType<typeof setInterval>

const currentTime = computed(() =>
  now.value.toLocaleTimeString('vi-VN', { hour: '2-digit', minute: '2-digit', second: '2-digit' }),
)
const currentDate = computed(() => {
  const d = now.value
  const days = ['Chủ nhật', 'Thứ hai', 'Thứ ba', 'Thứ tư', 'Thứ năm', 'Thứ sáu', 'Thứ bảy']
  const months = [
    'tháng 1', 'tháng 2', 'tháng 3', 'tháng 4', 'tháng 5', 'tháng 6',
    'tháng 7', 'tháng 8', 'tháng 9', 'tháng 10', 'tháng 11', 'tháng 12',
  ]
  return `${days[d.getDay()]}, ${d.getDate()} ${months[d.getMonth()]} ${d.getFullYear()}`
})


function statusLabel(status: string) {
  return ATTENDANCE_STATUS_LABEL[status] ?? status
}

function statusClass(status: string) {
  return ATTENDANCE_STATUS_BADGE[status] ?? 'badge-info'
}

async function fetchTodayAttendance() {
  loadingToday.value = true
  try {
    todayRecord.value = await attendanceService.getToday()
  } catch {
    todayRecord.value = null
  } finally {
    loadingToday.value = false
  }
}

async function checkIn() {
  submitting.value = true
  successMessage.value = ''
  errorMessage.value = ''
  try {
    todayRecord.value = await attendanceService.checkIn()
    successMessage.value = 'Chấm công thành công!'
    await fetchTodayAttendance()
  } catch (err: unknown) {
    const error = err as { data?: { message?: string } }
    errorMessage.value = error?.data?.message ?? 'Chấm công thất bại. Vui lòng thử lại.'
  } finally {
    submitting.value = false
  }
}

onMounted(() => {
  clockInterval = setInterval(() => { now.value = new Date() }, 1000)
  fetchTodayAttendance()
})
onUnmounted(() => clearInterval(clockInterval))
</script>

<template>
  <div class="max-w-2xl mx-auto space-y-6">
    <!-- Header -->
    <div>
      <h1 class="text-2xl font-bold text-gray-900">Chấm Công</h1>
      <p class="text-gray-500 text-sm mt-1">Điểm danh cho ngày hôm nay.</p>
    </div>

    <!-- Clock Card -->
    <div class="card p-8 text-center bg-gradient-to-br from-blue-600 via-purple-600 to-pink-600 text-white relative overflow-hidden">
      <div class="absolute -top-8 -right-8 w-40 h-40 bg-white/10 rounded-full blur-2xl" />
      <div class="absolute -bottom-6 -left-6 w-32 h-32 bg-white/10 rounded-full blur-2xl" />
      <div class="relative z-10">
        <p class="text-blue-100 text-sm mb-2">{{ currentDate }}</p>
        <p class="text-6xl font-bold tabular-nums tracking-tight mb-1">{{ currentTime }}</p>
        <p class="text-blue-100 text-sm">Giờ hiện tại</p>
      </div>
    </div>

    <!-- Success / Error Messages -->
    <div v-if="successMessage" class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 rounded-xl px-5 py-4 text-sm font-medium">
      <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
      </svg>
      {{ successMessage }}
    </div>
    <div v-if="errorMessage" class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 rounded-xl px-5 py-4 text-sm font-medium">
      <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
          d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
      </svg>
      {{ errorMessage }}
    </div>

    <!-- Check-In Action Card -->
    <div class="card p-8 text-center">
      <div class="mb-6">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-blue-50 mb-4">
          <svg class="w-10 h-10 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </div>
        <h2 class="text-xl font-semibold text-gray-800">Sẵn sàng chấm công?</h2>
        <p class="text-gray-400 text-sm mt-1">Nhấn nút bên dưới để ghi nhận thời gian của bạn.</p>
      </div>

      <button
        class="btn-primary px-10 py-3 text-base justify-center"
        :disabled="submitting"
        @click="checkIn"
      >
        <svg v-if="submitting" class="animate-spin -ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
        </svg>
        <svg v-else class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
        </svg>
        {{ submitting ? 'Đang xử lý...' : 'Chấm công ngay' }}
      </button>
    </div>

    <!-- Today's Status Card -->
    <div class="card overflow-hidden">
      <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="text-base font-semibold text-gray-800">Trạng thái hôm nay</h3>
        <button @click="fetchTodayAttendance" class="text-gray-400 hover:text-gray-600 transition-colors" title="Làm mới">
          <svg class="w-4 h-4" :class="{ 'animate-spin': loadingToday }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
          </svg>
        </button>
      </div>

      <div class="p-6">
        <!-- Loading skeleton -->
        <div v-if="loadingToday" class="animate-pulse space-y-3">
          <div class="h-4 bg-gray-200 rounded w-1/3" />
          <div class="h-4 bg-gray-200 rounded w-1/2" />
        </div>

        <!-- No record -->
        <div v-else-if="!todayRecord" class="text-center py-4">
          <p class="text-gray-400 text-sm">Chưa có dữ liệu chấm công hôm nay.</p>
        </div>

        <!-- Record details -->
        <div v-else class="grid grid-cols-2 gap-4">
          <div class="bg-blue-50 rounded-xl p-4 text-center">
            <p class="text-xs text-blue-500 font-medium mb-1 uppercase tracking-wide">Giờ vào</p>
            <p class="text-2xl font-bold text-blue-700">{{ formatTime(todayRecord.check_in) }}</p>
          </div>
          <div class="bg-purple-50 rounded-xl p-4 text-center">
            <p class="text-xs text-purple-500 font-medium mb-1 uppercase tracking-wide">Giờ ra</p>
            <p class="text-2xl font-bold text-purple-700">{{ formatTime(todayRecord.check_out) }}</p>
          </div>
          <div class="col-span-2 flex items-center justify-between bg-gray-50 rounded-xl p-4">
            <div>
              <p class="text-xs text-gray-500 font-medium mb-0.5">Ca làm việc</p>
              <p class="text-sm font-semibold text-gray-700">{{ todayRecord.shift_name ?? 'Chưa xác định' }}</p>
            </div>
            <span :class="statusClass(todayRecord.status)">{{ statusLabel(todayRecord.status) }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
