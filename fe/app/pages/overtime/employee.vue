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
        <h1 class="text-2xl font-bold text-ink">Đăng ký tăng ca</h1>
        <p class="text-sm text-muted mt-1">Chọn ca tăng ca phù hợp để đăng ký</p>
      </div>
      <UButton color="neutral" variant="soft" :loading="loading" @click="fetchShifts">
        <UIcon name="i-heroicons-arrow-path" class="w-4 h-4" />
        Làm mới
      </UButton>
    </div>

    <!-- Success Alert -->
    <UAlert v-if="successMessage" color="success" variant="soft" icon="i-heroicons-check-circle" :description="successMessage" />

    <!-- Error Alert -->
    <UAlert v-if="error" color="error" variant="soft" icon="i-heroicons-exclamation-triangle" :description="error" />

    <!-- Loading -->
    <div v-if="loading" class="flex justify-center py-16">
      <UIcon name="i-heroicons-arrow-path" class="animate-spin h-10 w-10 text-accent" />
    </div>

    <!-- Shift Cards -->
    <div v-else-if="shifts.length > 0" class="grid gap-5 sm:grid-cols-2 lg:grid-cols-3">
      <UCard
        v-for="shift in shifts"
        :key="shift.id"
        class="flex flex-col gap-4 border-2 transition-colors"
        :class="shift.is_registered ? 'border-accent-soft bg-accent-soft/30' : 'border-transparent'"
      >
        <!-- Card Header -->
        <div class="flex items-start justify-between">
          <h3 class="text-base font-semibold text-ink">{{ shift.name }}</h3>
          <StatusChip v-if="shift.is_registered" color="info" class="text-xs shrink-0 ml-2">Đã đăng ký</StatusChip>
        </div>

        <!-- Info -->
        <div class="space-y-2 text-sm text-body">
          <div class="flex items-center gap-2">
            <UIcon name="i-heroicons-clock" class="w-4 h-4 text-faint shrink-0" />
            <span>{{ formatTime(shift.start_time) }} – {{ formatTime(shift.end_time) }}</span>
          </div>
          <div class="flex items-center gap-2">
            <UIcon name="i-heroicons-calendar" class="w-4 h-4 text-faint shrink-0" />
            <span>{{ formatDate(shift.date) }}</span>
          </div>
          <div class="flex items-center gap-2">
            <UIcon name="i-heroicons-user" class="w-4 h-4 text-faint shrink-0" />
            <span>
              Còn
              <strong
                :class="remainingSlots(shift) === 0 ? 'text-danger' : 'text-success'"
              >{{ remainingSlots(shift) }}</strong>
              / {{ shift.max_registrations }} chỗ
            </span>
          </div>
        </div>

        <!-- Action Button -->
        <div class="mt-auto pt-2">
          <UButton
            v-if="shift.is_registered"
            color="error"
            class="w-full justify-center text-sm"
            :loading="actionLoading[shift.id]"
            :disabled="actionLoading[shift.id]"
            @click="unregisterShift(shift)"
          >
            {{ actionLoading[shift.id] ? 'Đang huỷ...' : 'Huỷ đăng ký' }}
          </UButton>
          <UButton
            v-else
            class="w-full justify-center text-sm"
            :loading="actionLoading[shift.id]"
            :disabled="actionLoading[shift.id] || remainingSlots(shift) === 0"
            @click="registerShift(shift)"
          >
            {{ actionLoading[shift.id] ? 'Đang đăng ký...' : remainingSlots(shift) === 0 ? 'Hết chỗ' : 'Đăng ký' }}
          </UButton>
        </div>
      </UCard>
    </div>

    <!-- Empty -->
    <UCard v-else class="text-center text-faint">
      <UIcon name="i-heroicons-calendar" class="mx-auto h-14 w-14 mb-4" />
      <p class="font-medium text-muted">Hiện chưa có ca tăng ca nào</p>
      <p class="text-sm mt-1">Quay lại sau để xem ca tăng ca mới nhất</p>
    </UCard>
  </div>
</template>
