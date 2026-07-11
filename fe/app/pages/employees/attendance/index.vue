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
  return ATTENDANCE_STATUS_BADGE[status] ?? 'info'
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

function getCurrentPosition(): Promise<GeolocationPosition | null> {
  return new Promise((resolve) => {
    if (!navigator.geolocation) {
      resolve(null)
      return
    }
    navigator.geolocation.getCurrentPosition(
      (position) => resolve(position),
      () => resolve(null),
      { enableHighAccuracy: true, timeout: 8000 },
    )
  })
}

async function checkIn() {
  submitting.value = true
  successMessage.value = ''
  errorMessage.value = ''
  try {
    const position = await getCurrentPosition()
    const payload = position
      ? { latitude: position.coords.latitude, longitude: position.coords.longitude }
      : {}
    todayRecord.value = await attendanceService.checkIn(payload)
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
  <div class="max-w-5xl mx-auto space-y-6">
    <BackButton to="/employees/dashboard" />

    <!-- Header -->
    <div>
      <h1 class="text-2xl font-bold text-ink">Chấm Công</h1>
      <p class="text-muted text-sm mt-1">Điểm danh cho ngày hôm nay.</p>
    </div>

    <!-- Clock Card -->
    <UCard class="text-center bg-gradient-to-br from-blue-600 via-purple-600 to-pink-600 text-white relative overflow-hidden">
      <div class="absolute -top-8 -right-8 w-40 h-40 bg-white/10 rounded-full blur-2xl" />
      <div class="absolute -bottom-6 -left-6 w-32 h-32 bg-white/10 rounded-full blur-2xl" />
      <div class="relative z-10">
        <p class="text-blue-100 text-sm mb-2">{{ currentDate }}</p>
        <p class="text-6xl font-bold tabular-nums tracking-tight mb-1">{{ currentTime }}</p>
        <p class="text-blue-100 text-sm">Giờ hiện tại</p>
      </div>
    </UCard>

    <!-- Success / Error Messages -->
    <UAlert
      v-if="successMessage"
      color="success"
      variant="soft"
      icon="i-heroicons-check-circle"
      :description="successMessage"
    />
    <UAlert
      v-if="errorMessage"
      color="error"
      variant="soft"
      icon="i-heroicons-exclamation-triangle"
      :description="errorMessage"
    />

    <!-- Check-In Action Card -->
    <UCard class="text-center">
      <div class="mb-6">
        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-accent-soft mb-4">
          <UIcon name="i-heroicons-clock" class="w-10 h-10 text-accent" />
        </div>
        <h2 class="text-xl font-semibold text-ink">Sẵn sàng chấm công?</h2>
        <p class="text-faint text-sm mt-1">Nhấn nút bên dưới để ghi nhận thời gian của bạn.</p>
        <p class="text-faint text-xs mt-1">Vui lòng cho phép truy cập vị trí nếu trình duyệt yêu cầu.</p>
      </div>

      <UButton
        class="px-10 py-3 text-base justify-center"
        :disabled="submitting"
        :loading="submitting"
        @click="checkIn"
      >
        {{ submitting ? 'Đang xử lý...' : 'Chấm công ngay' }}
      </UButton>
    </UCard>

    <!-- Today's Status Card -->
    <UCard :ui="{ body: 'p-0' }">
      <div class="px-6 py-4 border-b border-border flex items-center justify-between">
        <h3 class="text-base font-semibold text-ink">Trạng thái hôm nay</h3>
        <button @click="fetchTodayAttendance" class="text-faint hover:text-body transition-colors" title="Làm mới">
          <UIcon name="i-heroicons-arrow-path" class="w-4 h-4" :class="{ 'animate-spin': loadingToday }" />
        </button>
      </div>

      <div class="p-6">
        <!-- Loading skeleton -->
        <div v-if="loadingToday" class="animate-pulse space-y-3">
          <div class="h-4 bg-neutral-soft rounded w-1/3" />
          <div class="h-4 bg-neutral-soft rounded w-1/2" />
        </div>

        <!-- No record -->
        <div v-else-if="!todayRecord" class="text-center py-4">
          <p class="text-faint text-sm">Chưa có dữ liệu chấm công hôm nay.</p>
        </div>

        <!-- Record details -->
        <div v-else class="grid grid-cols-2 gap-4">
          <div class="bg-accent-soft rounded-xl p-4 text-center">
            <p class="text-xs text-accent font-medium mb-1 uppercase tracking-wide">Giờ vào</p>
            <p class="text-2xl font-bold text-accent-ink">{{ formatTime(todayRecord.check_in) }}</p>
          </div>
          <div class="bg-purple-50 rounded-xl p-4 text-center">
            <p class="text-xs text-purple-500 font-medium mb-1 uppercase tracking-wide">Giờ ra</p>
            <p class="text-2xl font-bold text-purple-700">{{ formatTime(todayRecord.check_out) }}</p>
          </div>
          <div class="col-span-2 flex items-center justify-between bg-neutral-soft rounded-xl p-4">
            <div>
              <p class="text-xs text-muted font-medium mb-0.5">Ca làm việc</p>
              <p class="text-sm font-semibold text-body">{{ todayRecord.shift_name ?? 'Chưa xác định' }}</p>
            </div>
            <StatusChip :color="statusClass(todayRecord.status)">{{ statusLabel(todayRecord.status) }}</StatusChip>
          </div>
        </div>
      </div>
    </UCard>
  </div>
</template>
