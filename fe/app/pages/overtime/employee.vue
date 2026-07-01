<script setup lang="ts">
import { overtimeService } from '~/services/overtimeService'
import type { OvertimeShift } from '~/types/overtime'
import { formatDate, formatTime } from '~/utils/format'

definePageMeta({ layout: 'default' })

const shifts = ref<OvertimeShift[]>([])
const loading = ref(false)
const error = ref('')
const actionLoading = ref<Record<number, boolean>>({})
const successMessage = ref('')

async function fetchShifts() {
  loading.value = true
  error.value = ''
  try {
    const res = await overtimeService.getAvailableShifts()
    shifts.value = Array.isArray(res) ? res : (res as any).data ?? []
  } catch {
    error.value = 'Không thể tải danh sách ca tăng ca. Vui lòng thử lại.'
  } finally {
    loading.value = false
  }
}

async function registerShift(shift: OvertimeShift) {
  actionLoading.value[shift.id] = true
  successMessage.value = ''
  try {
    await overtimeService.register(shift.id)
    successMessage.value = `Đăng ký ca "${shift.name}" thành công!`
    await fetchShifts()
  } catch {
    alert('Đăng ký thất bại. Vui lòng thử lại.')
  } finally {
    delete actionLoading.value[shift.id]
  }
}

async function unregisterShift(shift: OvertimeShift) {
  if (!confirm(`Bạn có chắc muốn huỷ đăng ký ca "${shift.name}"?`)) return
  actionLoading.value[shift.id] = true
  successMessage.value = ''
  try {
    await overtimeService.unregister(shift.id)
    successMessage.value = `Đã huỷ đăng ký ca "${shift.name}".`
    await fetchShifts()
  } catch {
    alert('Huỷ đăng ký thất bại. Vui lòng thử lại.')
  } finally {
    delete actionLoading.value[shift.id]
  }
}


function remainingSlots(shift: OvertimeShift) {
  return Math.max(0, shift.max_registrations - (shift.registration_count ?? 0))
}

onMounted(fetchShifts)
</script>

<template>
  <div class="max-w-5xl mx-auto space-y-6">
    <BackButton to="/employees/dashboard" />

    <!-- Header -->
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Đăng ký tăng ca</h1>
        <p class="text-sm text-gray-500 mt-1">Chọn ca tăng ca phù hợp để đăng ký</p>
      </div>
      <button class="btn-secondary" @click="fetchShifts" :disabled="loading">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
        </svg>
        Làm mới
      </button>
    </div>

    <!-- Success Alert -->
    <div v-if="successMessage" class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-3 text-sm">
      <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
      </svg>
      <span>{{ successMessage }}</span>
    </div>

    <!-- Error Alert -->
    <div v-if="error" class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 text-sm">
      <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
      </svg>
      <span>{{ error }}</span>
    </div>

    <!-- Loading -->
    <div v-if="loading" class="flex justify-center py-16">
      <svg class="animate-spin h-10 w-10 text-blue-600" fill="none" viewBox="0 0 24 24">
        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
      </svg>
    </div>

    <!-- Shift Cards -->
    <div v-else-if="shifts.length > 0" class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
      <div
        v-for="shift in shifts"
        :key="shift.id"
        class="card p-5 flex flex-col gap-4 border-2 transition-colors"
        :class="shift.is_registered ? 'border-blue-200 bg-blue-50/30' : 'border-transparent'"
      >
        <!-- Card Header -->
        <div class="flex items-start justify-between">
          <h3 class="text-base font-semibold text-gray-900">{{ shift.name }}</h3>
          <span v-if="shift.is_registered" class="badge-info text-xs shrink-0 ml-2">Đã đăng ký</span>
        </div>

        <!-- Info -->
        <div class="space-y-2 text-sm text-gray-600">
          <div class="flex items-center gap-2">
            <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <span>{{ formatTime(shift.start_time) }} – {{ formatTime(shift.end_time) }}</span>
          </div>
          <div class="flex items-center gap-2">
            <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
            <span>{{ formatDate(shift.date) }}</span>
          </div>
          <div class="flex items-center gap-2">
            <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <span>
              Còn
              <strong
                :class="remainingSlots(shift) === 0 ? 'text-red-600' : 'text-green-600'"
              >{{ remainingSlots(shift) }}</strong>
              / {{ shift.max_registrations }} chỗ
            </span>
          </div>
        </div>

        <!-- Action Button -->
        <div class="mt-auto pt-2">
          <button
            v-if="shift.is_registered"
            class="btn-danger w-full justify-center text-sm"
            :disabled="actionLoading[shift.id]"
            @click="unregisterShift(shift)"
          >
            <svg v-if="actionLoading[shift.id]" class="animate-spin -ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
            </svg>
            {{ actionLoading[shift.id] ? 'Đang huỷ...' : 'Huỷ đăng ký' }}
          </button>
          <button
            v-else
            class="btn-primary w-full justify-center text-sm"
            :disabled="actionLoading[shift.id] || remainingSlots(shift) === 0"
            @click="registerShift(shift)"
          >
            <svg v-if="actionLoading[shift.id]" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
            </svg>
            {{ actionLoading[shift.id] ? 'Đang đăng ký...' : remainingSlots(shift) === 0 ? 'Hết chỗ' : 'Đăng ký' }}
          </button>
        </div>
      </div>
    </div>

    <!-- Empty -->
    <div v-else class="card p-12 text-center text-gray-400">
      <svg class="mx-auto h-14 w-14 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
      </svg>
      <p class="font-medium text-gray-500">Hiện chưa có ca tăng ca nào</p>
      <p class="text-sm mt-1">Quay lại sau để xem ca tăng ca mới nhất</p>
    </div>
  </div>
</template>
