<script setup lang="ts">
import { REQUEST_STATUS_BADGE, REQUEST_STATUS_LABEL, LEAVE_TYPE_LABEL } from '~/constants'
import { leaveService } from '~/services/leaveService'
import type { LeaveRequest } from '~/types/leave'
import { formatDate } from '~/utils/format'

definePageMeta({ layout: 'default' })

const toast = useToast()

const requests = ref<LeaveRequest[]>([])
const loading = ref(false)
const error = ref('')
const actionLoading = ref<Record<number, boolean>>({})

const { currentPage, lastPage, total, perPage, setTotal, paginateArray, goToPage, visiblePages, summaryFrom, summaryTo } = usePagination(15)

const pagedRequests = computed(() => paginateArray(requests.value))
watch(requests, (val) => setTotal(val.length))

async function fetchRequests() {
  loading.value = true
  error.value = ''
  try {
    const res = await leaveService.getAll()
    requests.value = Array.isArray(res) ? res : (res as any).data ?? []
    setTotal(requests.value.length)
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
    await fetchRequests()
  } catch {
  } finally {
    delete actionLoading.value[request.id]
  }
}


function leaveTypeLabel(type: string) {
  return LEAVE_TYPE_LABEL[type] ?? type
}

function statusBadgeClass(status: string) {
  return REQUEST_STATUS_BADGE[status] ?? 'badge-warning'
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
        <h1 class="text-2xl font-bold text-gray-900">Quản lý nghỉ phép</h1>
        <p class="text-sm text-gray-500 mt-1">
          Phê duyệt và quản lý yêu cầu nghỉ phép nhân viên
          <span v-if="pendingCount > 0" class="ml-2 badge-warning">{{ pendingCount }} chờ duyệt</span>
        </p>
      </div>
      <button class="btn-secondary" :disabled="loading" @click="fetchRequests">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
        </svg>
        Làm mới
      </button>
    </div>

    <!-- Card -->
    <div class="card p-6">
      <!-- Error -->
      <div v-if="error" class="mb-4 bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 text-sm">
        {{ error }}
      </div>

      <!-- Loading -->
      <div v-if="loading" class="flex justify-center py-16">
        <svg class="animate-spin h-10 w-10 text-blue-600" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
        </svg>
      </div>

      <!-- Table -->
      <div v-else-if="requests.length > 0" class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nhân viên</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Loại nghỉ</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Từ ngày</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Đến ngày</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lý do</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hành động</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-100">
            <tr
              v-for="req in pagedRequests"
              :key="req.id"
              class="hover:bg-gray-50 transition-colors"
              :class="req.status === 'pending' ? 'bg-amber-50/30' : ''"
            >
              <td class="px-4 py-3 text-sm font-medium text-gray-900 whitespace-nowrap">{{ req.employee_name }}</td>
              <td class="px-4 py-3 text-sm text-gray-600 whitespace-nowrap">{{ leaveTypeLabel(req.leave_type) }}</td>
              <td class="px-4 py-3 text-sm text-gray-600 whitespace-nowrap">{{ formatDate(req.start_date) }}</td>
              <td class="px-4 py-3 text-sm text-gray-600 whitespace-nowrap">{{ formatDate(req.end_date) }}</td>
              <td class="px-4 py-3 text-sm text-gray-600 max-w-xs">
                <span class="line-clamp-2">{{ req.reason || '—' }}</span>
              </td>
              <td class="px-4 py-3 text-sm whitespace-nowrap">
                <span :class="statusBadgeClass(req.status)">{{ statusLabel(req.status) }}</span>
              </td>
              <td class="px-4 py-3 text-sm whitespace-nowrap space-x-2">
                <template v-if="req.status === 'pending'">
                  <button
                    class="btn-primary text-xs"
                    :disabled="actionLoading[req.id]"
                    @click="updateStatus(req, 'approved')"
                  >
                    {{ actionLoading[req.id] ? '...' : 'Duyệt' }}
                  </button>
                  <button
                    class="btn-danger text-xs"
                    :disabled="actionLoading[req.id]"
                    @click="updateStatus(req, 'rejected')"
                  >
                    {{ actionLoading[req.id] ? '...' : 'Từ chối' }}
                  </button>
                </template>
                <span v-else class="text-gray-400 text-xs">—</span>
              </td>
            </tr>
          </tbody>
        </table>
      <!-- Pagination -->
      <PaginationBar
        v-if="!loading && requests.length > 0"
        :current-page="currentPage" :last-page="lastPage"
        :total="total" :summary-from="summaryFrom" :summary-to="summaryTo"
        :visible-pages="visiblePages"
        @go-to-page="goToPage"
      />
      </div>

      <!-- Empty -->
      <div v-else class="text-center py-16 text-gray-400">
        <svg class="mx-auto h-14 w-14 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
        </svg>
        <p class="font-medium text-gray-500">Chưa có yêu cầu nghỉ phép nào</p>
      </div>
    </div>
  </div>
</template>
