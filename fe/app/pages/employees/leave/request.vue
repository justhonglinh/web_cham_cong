<script setup lang="ts">
definePageMeta({ layout: 'default' })

const api = useApi()
const router = useRouter()

const form = reactive({
  leave_type: '',
  start_date: '',
  end_date: '',
  reason: '',
})

const loading = ref(false)
const error = ref('')
const success = ref(false)

const leaveTypes = [
  { value: 'annual', label: 'Nghỉ phép năm' },
  { value: 'sick', label: 'Nghỉ bệnh' },
  { value: 'family', label: 'Nghỉ gia đình' },
  { value: 'other', label: 'Khác' },
]

function validateForm() {
  if (!form.leave_type) return 'Vui lòng chọn loại nghỉ phép.'
  if (!form.start_date) return 'Vui lòng chọn ngày bắt đầu.'
  if (!form.end_date) return 'Vui lòng chọn ngày kết thúc.'
  if (form.end_date < form.start_date) return 'Ngày kết thúc phải sau ngày bắt đầu.'
  return ''
}

async function handleSubmit() {
  error.value = validateForm()
  if (error.value) return

  loading.value = true
  try {
    await api.post('/employees/leave', { ...form })
    success.value = true
    setTimeout(() => {
      router.push('/employees/leave/history')
    }, 1500)
  } catch (err: unknown) {
    const e = err as { data?: { message?: string } }
    error.value = e?.data?.message || 'Gửi yêu cầu thất bại. Vui lòng thử lại.'
  } finally {
    loading.value = false
  }
}

function resetForm() {
  form.leave_type = ''
  form.start_date = ''
  form.end_date = ''
  form.reason = ''
  error.value = ''
}
</script>

<template>
  <div class="max-w-2xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
      <NuxtLink
        to="/employees/leave/history"
        class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700 transition-colors"
      >
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Lịch sử nghỉ phép
      </NuxtLink>
    </div>

    <div>
      <h1 class="text-2xl font-bold text-gray-900">Đăng ký nghỉ phép</h1>
      <p class="text-sm text-gray-500 mt-1">Điền thông tin yêu cầu nghỉ phép bên dưới</p>
    </div>

    <!-- Success Alert -->
    <div v-if="success" class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-700 rounded-lg px-4 py-4">
      <svg class="w-6 h-6 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
      </svg>
      <div>
        <p class="font-medium">Đăng ký thành công!</p>
        <p class="text-sm mt-0.5">Yêu cầu của bạn đang chờ phê duyệt. Đang chuyển hướng...</p>
      </div>
    </div>

    <!-- Form Card -->
    <div v-else class="card p-6 space-y-5">
      <!-- Error -->
      <div v-if="error" class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 text-sm">
        <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
        </svg>
        <span>{{ error }}</span>
      </div>

      <form @submit.prevent="handleSubmit" class="space-y-5">
        <!-- Leave Type -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">
            Loại nghỉ phép <span class="text-red-500">*</span>
          </label>
          <select v-model="form.leave_type" class="input-field" :disabled="loading">
            <option value="">-- Chọn loại nghỉ phép --</option>
            <option v-for="lt in leaveTypes" :key="lt.value" :value="lt.value">{{ lt.label }}</option>
          </select>
        </div>

        <!-- Date Range -->
        <div class="grid grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
              Từ ngày <span class="text-red-500">*</span>
            </label>
            <input
              v-model="form.start_date"
              type="date"
              class="input-field"
              :disabled="loading"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">
              Đến ngày <span class="text-red-500">*</span>
            </label>
            <input
              v-model="form.end_date"
              type="date"
              class="input-field"
              :min="form.start_date"
              :disabled="loading"
            />
          </div>
        </div>

        <!-- Duration info -->
        <div v-if="form.start_date && form.end_date && form.end_date >= form.start_date" class="flex items-center gap-2 text-sm text-blue-600 bg-blue-50 border border-blue-100 rounded-lg px-3 py-2">
          <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          <span>
            Thời gian nghỉ:
            <strong>
              {{
                Math.round((new Date(form.end_date).getTime() - new Date(form.start_date).getTime()) / (1000 * 60 * 60 * 24) + 1)
              }}
            </strong> ngày
          </span>
        </div>

        <!-- Reason -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Lý do</label>
          <textarea
            v-model="form.reason"
            class="input-field resize-none"
            rows="4"
            placeholder="Nhập lý do nghỉ phép (không bắt buộc)..."
            :disabled="loading"
          />
        </div>

        <!-- Actions -->
        <div class="flex items-center justify-end gap-3 pt-2">
          <button type="button" class="btn-secondary" :disabled="loading" @click="resetForm">
            Xoá trắng
          </button>
          <button type="submit" class="btn-primary" :disabled="loading">
            <svg v-if="loading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
            </svg>
            {{ loading ? 'Đang gửi...' : 'Gửi yêu cầu' }}
          </button>
        </div>
      </form>
    </div>
  </div>
</template>
