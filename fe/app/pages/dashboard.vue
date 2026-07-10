<script setup lang="ts">
import {
  ATTENDANCE_STATUS_BADGE, ATTENDANCE_STATUS_LABEL,
  REQUEST_STATUS_BADGE, REQUEST_STATUS_LABEL,
} from '~/constants'
import { dashboardService } from '~/services/dashboardService'
import type { DashboardData, WeeklyStat, ShiftTimeStat } from '~/types/dashboard'
import { formatDate, formatTime } from '~/utils/format'

definePageMeta({ layout: 'default' })

const loading = ref(true)
const error = ref<string | null>(null)
const data = ref<DashboardData | null>(null)

async function fetchDashboard() {
  loading.value = true
  error.value = null
  try {
    const res = await dashboardService.getManagerDashboard()
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
  return ATTENDANCE_STATUS_BADGE[status] ?? REQUEST_STATUS_BADGE[status] ?? 'info'
}

function statusLabel(status: string) {
  return ATTENDANCE_STATUS_LABEL[status] ?? REQUEST_STATUS_LABEL[status] ?? status
}


onMounted(fetchDashboard)
</script>

<template>
  <div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-end justify-between gap-4">
      <div>
        <h1 class="text-2xl font-semibold text-ink tracking-tight">Trang chủ</h1>
        <p class="text-sm text-muted mt-1">Tổng quan hoạt động chấm công của toàn hệ thống</p>
      </div>
      <UButton color="neutral" variant="soft" icon="i-heroicons-arrow-path" :loading="loading" @click="fetchDashboard">
        Làm mới
      </UButton>
    </div>

    <!-- Loading -->
    <div v-if="loading" class="flex items-center justify-center py-20">
      <UIcon name="i-heroicons-arrow-path" class="w-8 h-8 text-accent animate-spin" />
      <span class="ml-3 text-muted">Đang tải dữ liệu...</span>
    </div>

    <!-- Error -->
    <UAlert
      v-else-if="error"
      color="error"
      variant="soft"
      title="Lỗi tải dữ liệu"
      :description="error"
    >
      <template #actions>
        <UButton size="sm" @click="fetchDashboard">Thử lại</UButton>
      </template>
    </UAlert>

    <template v-else-if="data">
      <!-- Stat Cards -->
      <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        <UCard>
          <div class="flex items-center justify-between mb-2.5">
            <p class="text-[11px] font-semibold text-faint uppercase tracking-wide">Tổng nhân viên</p>
            <div class="w-7 h-7 rounded-lg bg-accent-soft flex items-center justify-center">
              <UIcon name="i-heroicons-users" class="w-3.5 h-3.5 text-accent" />
            </div>
          </div>
          <p class="text-[26px] font-semibold text-ink tabular-nums tracking-tight">{{ data.employeesCount }}</p>
        </UCard>

        <UCard>
          <div class="flex items-center justify-between mb-2.5">
            <p class="text-[11px] font-semibold text-faint uppercase tracking-wide">Ca làm việc</p>
            <div class="w-7 h-7 rounded-lg bg-accent-soft flex items-center justify-center">
              <UIcon name="i-heroicons-calendar-days" class="w-3.5 h-3.5 text-accent" />
            </div>
          </div>
          <p class="text-[26px] font-semibold text-ink tabular-nums tracking-tight">{{ data.shiftsCount }}</p>
          <p class="text-xs text-faint mt-1">{{ data.activeShifts }} đang dùng</p>
        </UCard>

        <UCard>
          <div class="flex items-center justify-between mb-2.5">
            <p class="text-[11px] font-semibold text-faint uppercase tracking-wide">Ca tăng ca</p>
            <div class="w-7 h-7 rounded-lg bg-warning-soft flex items-center justify-center">
              <UIcon name="i-heroicons-bolt" class="w-3.5 h-3.5 text-warning" />
            </div>
          </div>
          <p class="text-[26px] font-semibold text-ink tabular-nums tracking-tight">{{ data.overtimesCount }}</p>
          <p class="text-xs text-faint mt-1">{{ data.approvedOvertimeRequests }} đã duyệt</p>
        </UCard>

        <UCard>
          <div class="flex items-center justify-between mb-2.5">
            <p class="text-[11px] font-semibold text-faint uppercase tracking-wide">Chấm công hôm nay</p>
            <div class="w-7 h-7 rounded-lg bg-success-soft flex items-center justify-center">
              <UIcon name="i-heroicons-clipboard-document-check" class="w-3.5 h-3.5 text-success" />
            </div>
          </div>
          <p class="text-[26px] font-semibold text-ink tabular-nums tracking-tight">{{ data.attendancesCount }}</p>
          <p class="text-xs text-faint mt-1">{{ data.lateAttendances }} đi trễ</p>
        </UCard>
      </div>

      <!-- Alert Cards -->
      <div v-if="data.pendingOvertimeRequests > 0 || data.pendingLeaveRequests > 0 || data.lateAttendances > 0" class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <UCard v-if="data.pendingOvertimeRequests > 0">
          <div class="flex items-center gap-3">
            <div class="w-9 h-9 bg-warning-soft rounded-lg flex items-center justify-center shrink-0">
              <UIcon name="i-heroicons-bolt" class="w-4.5 h-4.5 text-warning" />
            </div>
            <div>
              <p class="font-semibold text-ink text-sm">Tăng ca chờ duyệt</p>
              <p class="text-xs text-muted mt-0.5">{{ data.pendingOvertimeRequests }} yêu cầu cần xem xét</p>
            </div>
          </div>
        </UCard>

        <UCard v-if="data.pendingLeaveRequests > 0">
          <div class="flex items-center gap-3">
            <div class="w-9 h-9 bg-warning-soft rounded-lg flex items-center justify-center shrink-0">
              <UIcon name="i-heroicons-document-text" class="w-4.5 h-4.5 text-warning" />
            </div>
            <div>
              <p class="font-semibold text-ink text-sm">Nghỉ phép chờ duyệt</p>
              <p class="text-xs text-muted mt-0.5">{{ data.pendingLeaveRequests }} yêu cầu cần xem xét</p>
            </div>
          </div>
        </UCard>

        <UCard v-if="data.lateAttendances > 0">
          <div class="flex items-center gap-3">
            <div class="w-9 h-9 bg-danger-soft rounded-lg flex items-center justify-center shrink-0">
              <UIcon name="i-heroicons-exclamation-triangle" class="w-4.5 h-4.5 text-danger" />
            </div>
            <div>
              <p class="font-semibold text-ink text-sm">Nhân viên đi trễ</p>
              <p class="text-xs text-muted mt-0.5">{{ data.lateAttendances }} người đi trễ hôm nay</p>
            </div>
          </div>
        </UCard>
      </div>

      <!-- Weekly Bar Chart + Recent Overtime -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <!-- Weekly Attendance Chart -->
        <UCard>
          <h2 class="text-sm font-semibold text-ink mb-4">Chấm công tuần này</h2>
          <div v-if="data.weeklyStats && data.weeklyStats.length" class="flex items-end gap-3 h-36">
            <div
              v-for="stat in data.weeklyStats"
              :key="stat.day"
              class="flex-1 flex flex-col items-center gap-1.5"
            >
              <span class="text-xs font-semibold text-body tabular-nums">{{ stat.count }}</span>
              <div
                class="w-full bg-accent-soft rounded-t-[4px] rounded-b-[2px] transition-all duration-500"
                :style="{ height: `${Math.max((stat.count / maxWeeklyCount) * 110, 4)}px` }"
              ></div>
              <span class="text-[11px] font-medium text-faint">{{ stat.day }}</span>
            </div>
          </div>
          <div v-else class="flex items-center justify-center h-36 text-faint text-sm">
            Không có dữ liệu tuần này
          </div>
        </UCard>

        <!-- Recent Overtime Requests -->
        <UCard :ui="{ body: 'p-0' }">
          <div class="flex items-center justify-between px-5 pt-4 pb-3">
            <h2 class="text-sm font-semibold text-ink">Tăng ca gần đây</h2>
            <NuxtLink to="/overtime/management" class="text-xs font-medium text-accent hover:text-accent-ink">Xem tất cả</NuxtLink>
          </div>
          <div v-if="data.recentOvertimeRequests && data.recentOvertimeRequests.length">
            <div
              v-for="req in data.recentOvertimeRequests"
              :key="req.id"
              class="flex items-center justify-between gap-3 px-5 py-2.5 border-t border-border"
            >
              <div class="min-w-0">
                <p class="text-sm font-medium text-ink truncate">{{ req.employee_name }}</p>
                <p class="text-xs text-faint">{{ req.date }} &bull; {{ req.hours }}h</p>
              </div>
              <StatusChip :color="statusBadge(req.status)">{{ statusLabel(req.status) }}</StatusChip>
            </div>
          </div>
          <div v-else class="text-center py-8 text-faint text-sm border-t border-border">Không có yêu cầu tăng ca</div>
        </UCard>
      </div>

      <!-- Recent Attendances + Recent Leave Requests -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        <!-- Recent Attendances -->
        <UCard :ui="{ body: 'p-0' }">
          <div class="flex items-center justify-between px-5 pt-4 pb-3">
            <h2 class="text-sm font-semibold text-ink">Chấm công gần đây</h2>
            <NuxtLink to="/attendance/management" class="text-xs font-medium text-accent hover:text-accent-ink">Xem tất cả</NuxtLink>
          </div>
          <div v-if="data.recentAttendances && data.recentAttendances.length">
            <div
              v-for="att in data.recentAttendances"
              :key="att.id"
              class="flex items-center justify-between gap-3 px-5 py-2.5 border-t border-border"
            >
              <div class="min-w-0">
                <p class="text-sm font-medium text-ink truncate">{{ att.employee_name }}</p>
                <p class="text-xs text-faint">
                  {{ formatDate(att.date) }} &bull; Vào: {{ formatTime(att.check_in) }} / Ra: {{ formatTime(att.check_out) }}
                </p>
              </div>
              <StatusChip :color="statusBadge(att.status)">{{ statusLabel(att.status) }}</StatusChip>
            </div>
          </div>
          <div v-else class="text-center py-8 text-faint text-sm border-t border-border">Không có dữ liệu chấm công</div>
        </UCard>

        <!-- Recent Leave Requests -->
        <UCard :ui="{ body: 'p-0' }">
          <div class="flex items-center justify-between px-5 pt-4 pb-3">
            <h2 class="text-sm font-semibold text-ink">Nghỉ phép gần đây</h2>
            <NuxtLink to="/leave-requests/management" class="text-xs font-medium text-accent hover:text-accent-ink">Xem tất cả</NuxtLink>
          </div>
          <div v-if="data.recentLeaveRequests && data.recentLeaveRequests.length">
            <div
              v-for="leave in data.recentLeaveRequests"
              :key="leave.id"
              class="flex items-center justify-between gap-3 px-5 py-2.5 border-t border-border"
            >
              <div class="min-w-0">
                <p class="text-sm font-medium text-ink truncate">{{ leave.employee_name }}</p>
                <p class="text-xs text-faint">{{ leave.start_date }} → {{ leave.end_date }}</p>
                <p class="text-xs text-faint truncate max-w-[200px]">{{ leave.reason }}</p>
              </div>
              <StatusChip :color="statusBadge(leave.status)">{{ statusLabel(leave.status) }}</StatusChip>
            </div>
          </div>
          <div v-else class="text-center py-8 text-faint text-sm border-t border-border">Không có yêu cầu nghỉ phép</div>
        </UCard>
      </div>
    </template>
  </div>
</template>
