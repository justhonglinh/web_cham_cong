<script setup lang="ts">
import * as z from 'zod'
import type { FormSubmitEvent } from '@nuxt/ui'

definePageMeta({ layout: 'auth' })

const route = useRoute()
const authStore = useAuthStore()
const toast = useAppToast()

const token = String(route.query.token ?? '')
const email = String(route.query.email ?? '')

const schema = z.object({
  password: z.string().min(8, 'Mật khẩu tối thiểu 8 ký tự'),
  passwordConfirmation: z.string().min(1, 'Vui lòng xác nhận mật khẩu'),
}).refine((data) => data.password === data.passwordConfirmation, {
  message: 'Xác nhận mật khẩu không khớp',
  path: ['passwordConfirmation'],
})
type Schema = z.output<typeof schema>

const state = reactive({ password: '', passwordConfirmation: '' })
const loading = ref(false)
const errorMessage = ref('')
const showPassword = ref(false)
const showPasswordConfirmation = ref(false)

async function onSubmit(event: FormSubmitEvent<Schema>) {
  loading.value = true
  errorMessage.value = ''

  try {
    await authStore.resetPassword({
      token,
      email,
      password: event.data.password,
      passwordConfirmation: event.data.passwordConfirmation,
    })
    toast.success('Đặt lại mật khẩu thành công. Vui lòng đăng nhập.')
    await navigateTo('/login')
  } catch (err: unknown) {
    const error = err as { data?: { message?: string; errors?: Record<string, string[]> } }
    if (error?.data?.errors) {
      const firstError = Object.values(error.data.errors)[0]
      errorMessage.value = Array.isArray(firstError) ? firstError[0] : String(firstError)
    } else {
      errorMessage.value = error?.data?.message || 'Đặt lại mật khẩu thất bại. Vui lòng thử lại.'
    }
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <UCard>
    <template v-if="!token || !email">
      <div class="flex flex-col items-center text-center gap-3 py-2">
        <div class="w-12 h-12 rounded-full bg-danger-soft flex items-center justify-center">
          <UIcon name="i-heroicons-exclamation-triangle" class="w-6 h-6 text-danger" />
        </div>
        <h2 class="text-xl font-semibold text-ink">Liên kết không hợp lệ</h2>
        <p class="text-sm text-muted">
          Liên kết đặt lại mật khẩu bị thiếu thông tin hoặc không hợp lệ. Vui lòng yêu cầu gửi lại liên kết mới.
        </p>
        <UButton to="/forgot-password" class="mt-2">Yêu cầu liên kết mới</UButton>
      </div>
    </template>

    <template v-else>
      <h2 class="text-xl font-semibold text-ink mb-1">Đặt lại mật khẩu</h2>
      <p class="text-sm text-muted mb-5">Nhập mật khẩu mới cho tài khoản <strong class="text-body">{{ email }}</strong>.</p>

      <UAlert
        v-if="errorMessage"
        class="mb-5"
        color="error"
        variant="soft"
        icon="i-heroicons-exclamation-triangle"
        :description="errorMessage"
      />

      <UForm :schema="schema" :state="state" class="space-y-5" @submit="onSubmit">
        <UFormField label="Mật khẩu mới" name="password" required>
          <UInput
            v-model="state.password"
            :type="showPassword ? 'text' : 'password'"
            autocomplete="new-password"
            placeholder="Tối thiểu 8 ký tự"
            class="w-full"
            :disabled="loading"
          >
            <template #trailing>
              <UButton
                color="neutral"
                variant="link"
                size="sm"
                :icon="showPassword ? 'i-heroicons-eye-slash' : 'i-heroicons-eye'"
                :padded="false"
                @click="showPassword = !showPassword"
              />
            </template>
          </UInput>
        </UFormField>

        <UFormField label="Xác nhận mật khẩu mới" name="passwordConfirmation" required>
          <UInput
            v-model="state.passwordConfirmation"
            :type="showPasswordConfirmation ? 'text' : 'password'"
            autocomplete="new-password"
            placeholder="Nhập lại mật khẩu mới"
            class="w-full"
            :disabled="loading"
          >
            <template #trailing>
              <UButton
                color="neutral"
                variant="link"
                size="sm"
                :icon="showPasswordConfirmation ? 'i-heroicons-eye-slash' : 'i-heroicons-eye'"
                :padded="false"
                @click="showPasswordConfirmation = !showPasswordConfirmation"
              />
            </template>
          </UInput>
        </UFormField>

        <UButton type="submit" block size="lg" :loading="loading" :disabled="loading">
          {{ loading ? 'Đang đặt lại...' : 'Đặt lại mật khẩu' }}
        </UButton>
      </UForm>
    </template>

    <div class="mt-6 text-center">
      <NuxtLink to="/login" class="inline-flex items-center gap-1 text-sm font-medium text-accent hover:text-accent-ink">
        <UIcon name="i-heroicons-arrow-left" class="w-4 h-4" />
        Quay lại đăng nhập
      </NuxtLink>
    </div>
  </UCard>
</template>
