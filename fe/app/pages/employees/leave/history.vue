<script setup lang="ts">
import { REQUEST_STATUS_BADGE, REQUEST_STATUS_LABEL, LEAVE_TYPE_LABEL } from '~/constants'
import { leaveService } from '~/services/leaveService'
import type { LeaveRequest as LeaveRecord } from '~/types/leave'
import { formatDate } from '~/utils/format'

definePageMeta({ layout: 'default' })

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
  return REQUEST_STATUS_BADGE[status] ?? 'badge-warning'
}

function statusLabel(status: string) {
  return REQUEST_STATUS_LABEL[status] ?? status
}

onMounted(fetchHistory)
</script>

<template>
  <div class="max-w-6xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Lịch sử nghỉ phép</h1>
        <p class="text-sm text-gray-500 mt-1">Xem và quản lý các yêu cầu nghỉ phép của bạn</p>
      </div>
      <div class="flex items-center gap-3">
        <button class="btn-secondary" :disabled="loading" @click="fetchHistory">
          <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
          </svg>
          Làm mới
        </button>
        <NuxtLink to="/employees/leave/request" class="btn-primary">
          <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          Đăng ký nghỉ
        </NuxtLink>
      </div>
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
      <div v-else-if="records.length > 0" class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Loại nghỉ</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Từ ngày</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Đến ngày</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Số ngày</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lý do</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
              <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hành động</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-100">
            <tr v-for="record in records" :key="record.id" class="hover:bg-gray-50 transition-colors">
              <td class="px-4 py-3 text-sm font-medium text-gray-900 whitespace-nowrap">{{ leaveTypeLabel(record.leave_type) }}</td>
              <td class="px-4 py-3 text-sm text-gray-600 whitespace-nowrap">{{ formatDate(record.start_date) }}</td>
              <td class="px-4 py-3 text-sm text-gray-600 whitespace-nowrap">{{ formatDate(record.end_date) }}</td>
              <td class="px-4 py-3 text-sm text-gray-600 whitespace-nowrap">
                <span class="font-medium">{{ record.days ?? '—' }}</span>
                <span v-if="record.days" class="text-gray-400 ml-1">ngày</span>
              </td>
              <td class="px-4 py-3 text-sm text-gray-600 max-w-xs">
                <span class="line-clamp-2">{{ record.reason || '—' }}</span>
              </td>
              <td class="px-4 py-3 text-sm whitespace-nowrap">
                <span :class="statusBadgeClass(record.status)">{{ statusLabel(record.status) }}</span>
              </td>
              <td class="px-4 py-3 text-sm whitespace-nowrap">
                <button
                  v-if="record.status === 'pending'"
                  class="btn-danger text-xs"
                  :disabled="cancelLoading[record.id]"
                  @click="cancelRequest(record)"
                >
                  {{ cancelLoading[record.id] ? 'Đang huỷ...' : 'Huỷ' }}
                </button>
                <span v-else class="text-gray-400 text-xs">—</span>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Empty -->
      <div v-else class="text-center py-16">
        <svg class="mx-auto h-14 w-14 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
        </svg>
        <p class="font-medium text-gray-500">Bạn chưa có yêu cầu nghỉ phép nào</p>
        <p class="text-sm text-gray-400 mt-1">Nhấn "Đăng ký nghỉ" để tạo yêu cầu mới</p>
        <NuxtLink to="/employees/leave/request" class="btn-primary mt-4 inline-flex">
          Đăng ký nghỉ ngay
        </NuxtLink>
      </div>
    </div>
  </div>
</template>
