<script setup lang="ts">
import { leaveService } from '~/services/leaveService'

definePageMeta({ layout: 'default' })

const toast = useAppToast()
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
    await leaveService.create({ ...form })
    toast.success('Gửi đơn nghỉ phép thành công.')
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
      <UButton
        to="/employees/leave/history"
        color="neutral"
        variant="link"
        icon="i-heroicons-arrow-left"
        class="px-0"
      >
        Lịch sử nghỉ phép
      </UButton>
    </div>

    <div>
      <h1 class="text-2xl font-bold text-ink">Đăng ký nghỉ phép</h1>
      <p class="text-sm text-muted mt-1">Điền thông tin yêu cầu nghỉ phép bên dưới</p>
    </div>

    <!-- Success Alert -->
    <UAlert
      v-if="success"
      color="success"
      variant="soft"
      icon="i-heroicons-check-circle"
      title="Đăng ký thành công!"
      description="Yêu cầu của bạn đang chờ phê duyệt. Đang chuyển hướng..."
    />

    <!-- Form Card -->
    <UCard v-else>
      <div class="space-y-5">
        <!-- Error -->
        <UAlert
          v-if="error"
          color="error"
          variant="soft"
          icon="i-heroicons-exclamation-triangle"
          :description="error"
        />

        <form @submit.prevent="handleSubmit" class="space-y-5">
          <!-- Leave Type -->
          <div>
            <label class="block text-sm font-medium text-body mb-1">
              Loại nghỉ phép <span class="text-danger">*</span>
            </label>
            <USelect
              v-model="form.leave_type"
              :items="leaveTypes"
              placeholder="-- Chọn loại nghỉ phép --"
              class="w-full"
              :disabled="loading"
            />
          </div>

          <!-- Date Range -->
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-body mb-1">
                Từ ngày <span class="text-danger">*</span>
              </label>
              <UInput
                v-model="form.start_date"
                type="date"
                class="w-full"
                :disabled="loading"
              />
            </div>
            <div>
              <label class="block text-sm font-medium text-body mb-1">
                Đến ngày <span class="text-danger">*</span>
              </label>
              <UInput
                v-model="form.end_date"
                type="date"
                class="w-full"
                :min="form.start_date"
                :disabled="loading"
              />
            </div>
          </div>

          <!-- Duration info -->
          <div v-if="form.start_date && form.end_date && form.end_date >= form.start_date" class="flex items-center gap-2 text-sm text-accent bg-accent-soft border border-border rounded-lg px-3 py-2">
            <UIcon name="i-heroicons-calendar" class="w-4 h-4 shrink-0" />
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
            <label class="block text-sm font-medium text-body mb-1">Lý do</label>
            <UTextarea
              v-model="form.reason"
              class="w-full"
              :rows="4"
              placeholder="Nhập lý do nghỉ phép (không bắt buộc)..."
              :disabled="loading"
            />
          </div>

          <!-- Actions -->
          <div class="flex items-center justify-end gap-3 pt-2">
            <UButton type="button" color="neutral" variant="soft" :disabled="loading" @click="resetForm">
              Xoá trắng
            </UButton>
            <UButton type="submit" :disabled="loading" :loading="loading">
              {{ loading ? 'Đang gửi...' : 'Gửi yêu cầu' }}
            </UButton>
          </div>
        </form>
      </div>
    </UCard>
  </div>
</template>
