<script setup lang="ts">
definePageMeta({ layout: 'default' })

const api = useApi()

interface LeaveRequest {
  id: number
  employee_name: string
  leave_type: string
  start_date: string
  end_date: string
  reason: string
  status: 'pending' | 'approved' | 'rejected'
}

const requests = ref<LeaveRequest[]>([])
const loading = ref(false)
const error = ref('')
const actionLoading = ref<Record<number, boolean>>({})

async function fetchRequests() {
  loading.value = true
  error.value = ''
  try {
    const data = await api.get<{ data: LeaveRequest[] }>('/leave-requests/management')
    requests.value = data.data ?? []
  } catch {
    error.value = 'Không thể tải danh sách yêu cầu nghỉ phép.'
  } finally {
    loading.value = false
  }
}

async function updateStatus(request: LeaveRequest, status: 'approved' | 'rejected') {
  actionLoading.value[request.id] = true
  try {
    await api.patch(`/leave-requests/${request.id}/status`, { status })
    await fetchRequests()
  } catch {
    alert('Cập nhật trạng thái thất bại. Vui lòng thử lại.')
  } finally {
    delete actionLoading.value[request.id]
  }
}

function formatDate(d: string) {
  if (!d) return ''
  const [y, m, day] = d.split('-')
  return `${day}/${m}/${y}`
}

function leaveTypeLabel(type: string) {
  const map: Record<string, string> = {
    annual: 'Nghỉ phép năm',
    sick: 'Nghỉ bệnh',
    family: 'Nghỉ gia đình',
    other: 'Khác',
  }
  return map[type] ?? type
}

function statusBadgeClass(status: string) {
  if (status === 'approved') return 'badge-success'
  if (status === 'rejected') return 'badge-danger'
  return 'badge-warning'
}

function statusLabel(status: string) {
  if (status === 'approved') return 'Đã duyệt'
  if (status === 'rejected') return 'Đã từ chối'
  return 'Chờ duyệt'
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
              v-for="req in requests"
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
