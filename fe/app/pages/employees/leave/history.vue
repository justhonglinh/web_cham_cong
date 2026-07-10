<script setup lang="ts">
import type { TableColumn } from '@nuxt/ui'
import { REQUEST_STATUS_BADGE, REQUEST_STATUS_LABEL, LEAVE_TYPE_LABEL } from '~/constants'
import { leaveService } from '~/services/leaveService'
import type { LeaveRequest as LeaveRecord } from '~/types/leave'
import { formatDate } from '~/utils/format'

definePageMeta({ layout: 'default' })

const columns: TableColumn<LeaveRecord>[] = [
  { accessorKey: 'leave_type', header: 'Loại nghỉ' },
  { accessorKey: 'start_date', header: 'Từ ngày' },
  { accessorKey: 'end_date', header: 'Đến ngày' },
  { accessorKey: 'days', header: 'Số ngày' },
  { accessorKey: 'reason', header: 'Lý do' },
  { accessorKey: 'status', header: 'Trạng thái' },
  { id: 'actions', header: 'Hành động' },
]

const records = ref<LeaveRecord[]>([])
const loading = ref(false)
const error = ref('')
const cancelLoading = ref<Record<number, boolean>>({})

async function fetchHistory() {
  loading.value = true
  error.value = ''
  try {
    const res = await leaveService.getHistory()
    records.value = Array.isArray(res) ? res : (res as any).data ?? []
  } catch {
    error.value = 'Không thể tải lịch sử nghỉ phép. Vui lòng thử lại.'
  } finally {
    loading.value = false
  }
}

async function cancelRequest(record: LeaveRecord) {
  if (!confirm('Bạn có chắc muốn huỷ yêu cầu nghỉ phép này?')) return
  cancelLoading.value[record.id] = true
  try {
    await leaveService.cancel(record.id)
    await fetchHistory()
  } catch {
    alert('Huỷ yêu cầu thất bại. Vui lòng thử lại.')
  } finally {
    delete cancelLoading.value[record.id]
  }
}


function leaveTypeLabel(type: string) {
  return LEAVE_TYPE_LABEL[type] ?? type
}

function statusBadgeClass(status: string) {
  return REQUEST_STATUS_BADGE[status] ?? 'warning'
}

function statusLabel(status: string) {
  return REQUEST_STATUS_LABEL[status] ?? status
}

onMounted(fetchHistory)
</script>

<template>
  <div class="max-w-5xl mx-auto space-y-6">
    <BackButton to="/employees/dashboard" />

    <!-- Header -->
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-ink">Lịch sử nghỉ phép</h1>
        <p class="text-sm text-muted mt-1">Xem và quản lý các yêu cầu nghỉ phép của bạn</p>
      </div>
      <div class="flex items-center gap-3">
        <UButton color="neutral" variant="soft" :disabled="loading" :loading="loading" icon="i-heroicons-arrow-path" @click="fetchHistory">
          Làm mới
        </UButton>
        <UButton to="/employees/leave/request" icon="i-heroicons-plus">
          Đăng ký nghỉ
        </UButton>
      </div>
    </div>

    <!-- Card -->
    <UCard>
      <!-- Error -->
      <UAlert
        v-if="error"
        class="mb-4"
        color="error"
        variant="soft"
        icon="i-heroicons-exclamation-triangle"
        :description="error"
      />

      <!-- Loading -->
      <div v-if="loading" class="flex justify-center py-16">
        <UIcon name="i-heroicons-arrow-path" class="animate-spin h-10 w-10 text-accent" />
      </div>

      <!-- Table -->
      <div v-else-if="records.length > 0">
        <UTable :data="records" :columns="columns">
          <template #leave_type-cell="{ row }">
            <span class="font-medium">{{ leaveTypeLabel(row.original.leave_type) }}</span>
          </template>
          <template #start_date-cell="{ row }">
            {{ formatDate(row.original.start_date) }}
          </template>
          <template #end_date-cell="{ row }">
            {{ formatDate(row.original.end_date) }}
          </template>
          <template #days-cell="{ row }">
            <span class="font-medium">{{ row.original.days ?? '—' }}</span>
            <span v-if="row.original.days" class="text-faint ml-1">ngày</span>
          </template>
          <template #reason-cell="{ row }">
            <span class="line-clamp-2 max-w-xs block">{{ row.original.reason || '—' }}</span>
          </template>
          <template #status-cell="{ row }">
            <StatusChip :color="statusBadgeClass(row.original.status)">{{ statusLabel(row.original.status) }}</StatusChip>
          </template>
          <template #actions-cell="{ row }">
            <UButton
              v-if="row.original.status === 'pending'"
              color="error"
              size="xs"
              :disabled="cancelLoading[row.original.id]"
              :loading="cancelLoading[row.original.id]"
              @click="cancelRequest(row.original)"
            >
              {{ cancelLoading[row.original.id] ? 'Đang huỷ...' : 'Huỷ' }}
            </UButton>
            <span v-else class="text-faint text-xs">—</span>
          </template>
        </UTable>
      </div>

      <!-- Empty -->
      <div v-else class="text-center py-16">
        <UIcon name="i-heroicons-inbox" class="mx-auto h-14 w-14 text-faint mb-4" />
        <p class="font-medium text-muted">Bạn chưa có yêu cầu nghỉ phép nào</p>
        <p class="text-sm text-faint mt-1">Nhấn "Đăng ký nghỉ" để tạo yêu cầu mới</p>
        <UButton to="/employees/leave/request" class="mt-4 inline-flex">
          Đăng ký nghỉ ngay
        </UButton>
      </div>
    </UCard>
  </div>
</template>
