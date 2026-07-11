<script setup lang="ts">
import type { TableColumn } from '@nuxt/ui'
import { ATTENDANCE_STATUS_BADGE, ATTENDANCE_STATUS_LABEL } from '~/constants'
import { attendanceService } from '~/services/attendanceService'
import type { AttendanceRecord } from '~/types/attendance'
import { formatDate, formatTime } from '~/utils/format'

definePageMeta({ layout: 'default' })

const columns: TableColumn<AttendanceRecord>[] = [
  { accessorKey: 'date', header: 'Ngày' },
  { accessorKey: 'check_in', header: 'Giờ vào' },
  { accessorKey: 'check_out', header: 'Giờ ra' },
  { accessorKey: 'shift_name', header: 'Ca làm' },
  { accessorKey: 'status', header: 'Trạng thái' },
]

const authStore = useAuthStore()

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

function statusColor(status: string) {
  return ATTENDANCE_STATUS_BADGE[status] ?? 'info'
}

async function fetchRecentAttendance() {
  loadingAttendance.value = true
  try {
    const data = await attendanceService.getHistory({ limit: 5, per_page: 5, page: 1 })
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

type Tone = 'accent' | 'warning' | 'success'

const toneClasses: Record<Tone, { bg: string; text: string }> = {
  accent: { bg: 'bg-accent-soft', text: 'text-accent' },
  warning: { bg: 'bg-warning-soft', text: 'text-warning' },
  success: { bg: 'bg-success-soft', text: 'text-success' },
}

const quickActions: { title: string; description: string; icon: string; link: string; tone: Tone }[] = [
  { title: 'Chấm công', description: 'Điểm danh hôm nay', icon: 'i-heroicons-clock', link: '/employees/attendance', tone: 'accent' },
  { title: 'Lịch sử', description: 'Xem lịch sử chấm công', icon: 'i-heroicons-clipboard-document-list', link: '/employees/attendance/history', tone: 'accent' },
  { title: 'Tăng ca', description: 'Đăng ký tăng ca', icon: 'i-heroicons-bolt', link: '/overtime/employee', tone: 'warning' },
  { title: 'Nghỉ phép', description: 'Quản lý nghỉ phép', icon: 'i-heroicons-calendar', link: '/employees/leave/history', tone: 'success' },
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
          <UIcon name="i-heroicons-clock" class="w-4 h-4" />
          Chúc bạn một ngày làm việc hiệu quả!
        </div>
      </div>
    </div>

    <!-- Quick Actions -->
    <div>
      <h2 class="text-lg font-semibold text-ink mb-3">Truy cập nhanh</h2>
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        <NuxtLink
          v-for="action in quickActions"
          :key="action.title"
          :to="action.link"
          class="bg-white rounded-2xl shadow-lg border border-border p-5 flex flex-col items-center text-center gap-3 hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200 cursor-pointer group"
        >
          <div :class="['w-12 h-12 rounded-xl flex items-center justify-center', toneClasses[action.tone].bg]">
            <UIcon :name="action.icon" :class="['w-6 h-6', toneClasses[action.tone].text]" />
          </div>
          <div>
            <p class="font-semibold text-ink text-sm group-hover:text-accent transition-colors">{{ action.title }}</p>
            <p class="text-xs text-faint mt-0.5">{{ action.description }}</p>
          </div>
        </NuxtLink>
      </div>
    </div>

    <!-- Recent Attendance -->
    <UCard :ui="{ body: 'p-0' }">
      <div class="flex items-center justify-between px-6 py-4 border-b border-border">
        <h2 class="text-lg font-semibold text-ink">Chấm công gần đây</h2>
        <NuxtLink to="/employees/attendance/history" class="text-sm text-accent hover:underline font-medium">
          Xem tất cả
        </NuxtLink>
      </div>

      <!-- Loading -->
      <div v-if="loadingAttendance" class="p-6 space-y-3">
        <div v-for="i in 3" :key="i" class="animate-pulse flex gap-4 items-center">
          <div class="h-4 bg-neutral-soft rounded w-24" />
          <div class="h-4 bg-neutral-soft rounded w-16" />
          <div class="h-4 bg-neutral-soft rounded w-16" />
          <div class="h-4 bg-neutral-soft rounded flex-1" />
          <div class="h-5 bg-neutral-soft rounded-full w-16" />
        </div>
      </div>

      <!-- Empty -->
      <div v-else-if="recentAttendance.length === 0" class="p-12 text-center">
        <UIcon name="i-heroicons-clipboard-document-list" class="w-12 h-12 text-faint mx-auto mb-3" />
        <p class="text-faint text-sm">Chưa có dữ liệu chấm công.</p>
      </div>

      <!-- Table -->
      <UTable v-else :data="recentAttendance" :columns="columns">
        <template #date-cell="{ row }">
          <span class="font-medium">{{ formatDate(row.original.date) }}</span>
        </template>
        <template #check_in-cell="{ row }">
          {{ formatTime(row.original.check_in) }}
        </template>
        <template #check_out-cell="{ row }">
          {{ formatTime(row.original.check_out) }}
        </template>
        <template #shift_name-cell="{ row }">
          {{ row.original.shift_name ?? '—' }}
        </template>
        <template #status-cell="{ row }">
          <StatusChip :color="statusColor(row.original.status)">{{ statusLabel(row.original.status) }}</StatusChip>
        </template>
      </UTable>
    </UCard>
  </div>
</template>
