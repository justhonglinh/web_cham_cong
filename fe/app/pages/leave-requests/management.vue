<script setup lang="ts">
import type { TableColumn } from '@nuxt/ui'
import { REQUEST_STATUS_BADGE, REQUEST_STATUS_LABEL, LEAVE_TYPE_LABEL } from '~/constants'
import { leaveService } from '~/services/leaveService'
import type { LeaveRequest } from '~/types/leave'
import { formatDate } from '~/utils/format'

definePageMeta({ layout: 'default' })

const columns: TableColumn<LeaveRequest>[] = [
  { accessorKey: 'employee_name', header: 'Nhân viên' },
  { accessorKey: 'leave_type', header: 'Loại nghỉ' },
  { accessorKey: 'start_date', header: 'Từ ngày' },
  { accessorKey: 'end_date', header: 'Đến ngày' },
  { accessorKey: 'reason', header: 'Lý do' },
  { accessorKey: 'status', header: 'Trạng thái' },
  { id: 'actions', header: 'Hành động' },
]

const toast = useAppToast()

const requests = ref<LeaveRequest[]>([])
const loading = ref(false)
const error = ref('')
const actionLoading = ref<Record<number, boolean>>({})

const { currentPage, lastPage, total, perPage, setFromResponse, goToPage: goToPageFn, visiblePages, summaryFrom, summaryTo } = usePagination(20)

function goToPage(page: number) {
  goToPageFn(page, fetchRequests)
}

async function fetchRequests(page = 1) {
  loading.value = true
  error.value = ''
  try {
    const res = await leaveService.getAll({ page, per_page: perPage.value })
    requests.value = setFromResponse(res)
  } catch {
    error.value = 'Không thể tải danh sách yêu cầu nghỉ phép.'
  } finally {
    loading.value = false
  }
}

async function updateStatus(request: LeaveRequest, status: 'approved' | 'rejected') {
  actionLoading.value[request.id] = true
  try {
    const label = status === 'approved' ? 'Đã duyệt đơn nghỉ phép.' : 'Đã từ chối đơn nghỉ phép.'
    await leaveService.updateStatus(request.id, status)
    toast.success(label)
    await fetchRequests(currentPage.value)
  } catch {
  } finally {
    delete actionLoading.value[request.id]
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

const pendingCount = computed(() => requests.value.filter(r => r.status === 'pending').length)

onMounted(fetchRequests)
</script>

<template>
  <div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-ink">Quản lý nghỉ phép</h1>
        <p class="text-sm text-muted mt-1">
          Phê duyệt và quản lý yêu cầu nghỉ phép nhân viên
          <StatusChip v-if="pendingCount > 0" color="warning" class="ml-2">{{ pendingCount }} chờ duyệt</StatusChip>
        </p>
      </div>
      <UButton color="neutral" variant="soft" :loading="loading" :disabled="loading" @click="fetchRequests(currentPage)">
        <UIcon v-if="!loading" name="i-heroicons-arrow-path" class="w-4 h-4" />
        Làm mới
      </UButton>
    </div>

    <!-- Card -->
    <UCard>
      <!-- Error -->
      <div v-if="error" class="mb-4 bg-danger-soft border border-border-strong text-danger rounded-lg px-4 py-3 text-sm">
        {{ error }}
      </div>

      <!-- Loading -->
      <div v-if="loading" class="flex justify-center py-16">
        <UIcon name="i-heroicons-arrow-path" class="animate-spin w-10 h-10 text-accent" />
      </div>

      <!-- Table -->
      <div v-else-if="requests.length > 0">
        <UTable
          :data="requests"
          :columns="columns"
          :meta="{ class: { tr: (row) => row.original.status === 'pending' ? 'bg-warning-soft/40' : '' } }"
        >
          <template #reason-cell="{ row }">
            <span class="line-clamp-2 max-w-xs block">{{ row.original.reason || '—' }}</span>
          </template>
          <template #leave_type-cell="{ row }">
            {{ leaveTypeLabel(row.original.leave_type) }}
          </template>
          <template #start_date-cell="{ row }">
            {{ formatDate(row.original.start_date) }}
          </template>
          <template #end_date-cell="{ row }">
            {{ formatDate(row.original.end_date) }}
          </template>
          <template #status-cell="{ row }">
            <StatusChip :color="statusBadgeClass(row.original.status)">{{ statusLabel(row.original.status) }}</StatusChip>
          </template>
          <template #actions-cell="{ row }">
            <div v-if="row.original.status === 'pending'" class="flex items-center gap-2">
              <UButton
                size="xs"
                :loading="actionLoading[row.original.id]"
                :disabled="actionLoading[row.original.id]"
                @click="updateStatus(row.original, 'approved')"
              >
                Duyệt
              </UButton>
              <UButton
                color="error"
                size="xs"
                :loading="actionLoading[row.original.id]"
                :disabled="actionLoading[row.original.id]"
                @click="updateStatus(row.original, 'rejected')"
              >
                Từ chối
              </UButton>
            </div>
            <span v-else class="text-faint text-xs">—</span>
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
      </div>

      <!-- Empty -->
      <div v-else class="text-center py-16 text-faint">
        <UIcon name="i-heroicons-inbox" class="mx-auto h-14 w-14 mb-4" />
        <p class="font-medium text-muted">Chưa có yêu cầu nghỉ phép nào</p>
      </div>
    </UCard>
  </div>
</template>
