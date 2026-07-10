<script setup lang="ts">
import type { TableColumn } from '@nuxt/ui'
import { ATTENDANCE_STATUS_BADGE, ATTENDANCE_STATUS_LABEL } from '~/constants'
import { attendanceService } from '~/services/attendanceService'
import type { AttendanceRecord } from '~/types/attendance'
import { formatDate, formatTime } from '~/utils/format'

definePageMeta({ layout: 'default' })

const columns: TableColumn<AttendanceRecord>[] = [
  { id: 'stt', header: 'STT' },
  { accessorKey: 'date', header: 'Ngày' },
  { accessorKey: 'check_in', header: 'Giờ vào' },
  { accessorKey: 'check_out', header: 'Giờ ra' },
  { accessorKey: 'shift_name', header: 'Ca làm' },
  { accessorKey: 'status', header: 'Trạng thái' },
]

const records = ref<AttendanceRecord[]>([])
const loading = ref(false)
const { currentPage, lastPage, total, perPage, setFromResponse, goToPage: goToPageFn, visiblePages, summaryFrom, summaryTo } = usePagination(10)


function statusLabel(status: string) {
  return ATTENDANCE_STATUS_LABEL[status] ?? status
}

function statusClass(status: string) {
  return ATTENDANCE_STATUS_BADGE[status] ?? 'info'
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
        <h1 class="text-2xl font-bold text-ink">Lịch sử chấm công</h1>
        <p class="text-muted text-sm mt-1">
          Danh sách các lần chấm công của bạn.
        </p>
      </div>
      <UButton color="neutral" variant="soft" :disabled="loading" :loading="loading" icon="i-heroicons-arrow-path" @click="fetchHistory(currentPage)">
        Làm mới
      </UButton>
    </div>

    <!-- Table Card -->
    <UCard :ui="{ body: 'p-0' }">
      <!-- Loading Skeleton -->
      <div v-if="loading" class="p-6 space-y-4">
        <div v-for="i in 6" :key="i" class="animate-pulse flex gap-4 items-center py-2">
          <div class="h-4 bg-neutral-soft rounded w-28" />
          <div class="h-4 bg-neutral-soft rounded w-16" />
          <div class="h-4 bg-neutral-soft rounded w-16" />
          <div class="h-4 bg-neutral-soft rounded flex-1" />
          <div class="h-5 bg-neutral-soft rounded-full w-20" />
        </div>
      </div>

      <!-- Empty State -->
      <div v-else-if="records.length === 0" class="py-16 text-center">
        <UIcon name="i-heroicons-inbox" class="w-14 h-14 text-border-strong mx-auto mb-4" />
        <p class="text-faint font-medium">Không có dữ liệu</p>
        <p class="text-faint text-sm mt-1">Chưa có bản ghi chấm công nào.</p>
      </div>

      <!-- Data Table -->
      <UTable v-else :data="records" :columns="columns">
        <template #stt-cell="{ row }">
          <span class="text-faint">{{ summaryFrom + row.index }}</span>
        </template>
        <template #date-cell="{ row }">
          <span class="font-medium text-ink">{{ formatDate(row.original.date) }}</span>
        </template>
        <template #check_in-cell="{ row }">
          <span
            class="text-sm font-mono"
            :class="row.original.check_in ? 'text-success font-semibold' : 'text-faint'"
          >
            {{ formatTime(row.original.check_in) }}
          </span>
        </template>
        <template #check_out-cell="{ row }">
          <span
            class="text-sm font-mono"
            :class="row.original.check_out ? 'text-accent font-semibold' : 'text-faint'"
          >
            {{ formatTime(row.original.check_out) }}
          </span>
        </template>
        <template #shift_name-cell="{ row }">
          {{ row.original.shift_name ?? '—' }}
        </template>
        <template #status-cell="{ row }">
          <StatusChip :color="statusClass(row.original.status)">{{ statusLabel(row.original.status) }}</StatusChip>
        </template>
      </UTable>

      <!-- Pagination -->
      <PaginationBar
        v-if="!loading"
        :current-page="currentPage" :last-page="lastPage"
        :total="total" :summary-from="summaryFrom" :summary-to="summaryTo"
        :visible-pages="visiblePages"
        @go-to-page="goToPage"
      />
    </UCard>
  </div>
</template>
