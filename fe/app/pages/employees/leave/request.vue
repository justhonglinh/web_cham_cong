<script setup lang="ts">
import * as z from 'zod'
import type { FormSubmitEvent } from '@nuxt/ui'
import { leaveService } from '~/services/leaveService'

definePageMeta({ layout: 'default' })

const toast = useAppToast()
const router = useRouter()

const schema = z.object({
  leave_type: z.string().min(1, 'Vui lòng chọn loại nghỉ phép'),
  start_date: z.string().min(1, 'Vui lòng chọn ngày bắt đầu'),
  end_date: z.string().min(1, 'Vui lòng chọn ngày kết thúc'),
  reason: z.string().optional(),
}).refine((data) => !data.start_date || !data.end_date || data.end_date >= data.start_date, {
  message: 'Ngày kết thúc phải sau ngày bắt đầu',
  path: ['end_date'],
})
type Schema = z.output<typeof schema>

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

async function onSubmit(event: FormSubmitEvent<Schema>) {
  loading.value = true
  error.value = ''
  try {
    await leaveService.create({ ...event.data })
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

        <UForm :schema="schema" :state="form" class="space-y-5" @submit="onSubmit">
          <!-- Leave Type -->
          <UFormField label="Loại nghỉ phép" name="leave_type" required>
            <USelect
              v-model="form.leave_type"
              :items="leaveTypes"
              placeholder="-- Chọn loại nghỉ phép --"
              class="w-full"
              :disabled="loading"
            />
          </UFormField>

          <!-- Date Range -->
          <div class="grid grid-cols-2 gap-4">
            <UFormField label="Từ ngày" name="start_date" required>
              <UInput
                v-model="form.start_date"
                type="date"
                class="w-full"
                :disabled="loading"
              />
            </UFormField>
            <UFormField label="Đến ngày" name="end_date" required>
              <UInput
                v-model="form.end_date"
                type="date"
                class="w-full"
                :min="form.start_date"
                :disabled="loading"
              />
            </UFormField>
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
          <UFormField label="Lý do" name="reason">
            <UTextarea
              v-model="form.reason"
              class="w-full"
              :rows="4"
              placeholder="Nhập lý do nghỉ phép (không bắt buộc)..."
              :disabled="loading"
            />
          </UFormField>

          <!-- Actions -->
          <div class="flex items-center justify-end gap-3 pt-2">
            <UButton type="button" color="neutral" variant="soft" :disabled="loading" @click="resetForm">
              Xoá trắng
            </UButton>
            <UButton type="submit" :disabled="loading" :loading="loading">
              {{ loading ? 'Đang gửi...' : 'Gửi yêu cầu' }}
            </UButton>
          </div>
        </UForm>
      </div>
    </UCard>
  </div>
</template>
