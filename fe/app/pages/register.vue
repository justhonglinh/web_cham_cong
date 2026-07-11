<script setup lang="ts">
import * as z from 'zod'
import type { FormSubmitEvent } from '@nuxt/ui'

definePageMeta({ layout: 'auth' })

const authStore = useAuthStore()

if (authStore.isAuthenticated) {
  navigateTo(authStore.isManager ? '/dashboard' : '/employees/dashboard')
}

const schema = z.object({
  name: z.string().min(1, 'Vui lòng nhập họ và tên'),
  email: z.email('Email không hợp lệ').min(1, 'Vui lòng nhập email'),
  password: z.string().min(8, 'Mật khẩu tối thiểu 8 ký tự'),
  passwordConfirmation: z.string().min(1, 'Vui lòng xác nhận mật khẩu'),
}).refine((data) => data.password === data.passwordConfirmation, {
  message: 'Xác nhận mật khẩu không khớp',
  path: ['passwordConfirmation'],
})
type Schema = z.output<typeof schema>

const state = reactive({
  name: '',
  email: '',
  password: '',
  passwordConfirmation: '',
})

const formRef = useTemplateRef('formRef')
const loading = ref(false)
const errorMessage = ref('')
const showPassword = ref(false)
const showPasswordConfirmation = ref(false)

async function onSubmit(event: FormSubmitEvent<Schema>) {
  loading.value = true
  errorMessage.value = ''

  try {
    await authStore.register(
      event.data.name,
      event.data.email,
      event.data.password,
      event.data.passwordConfirmation
    )
    await navigateTo('/dashboard')
  } catch (err: unknown) {
    const error = err as { data?: { message?: string; errors?: Record<string, string[]> }; statusCode?: number }
    if (error?.data?.errors) {
      formRef.value?.setErrors(
        Object.entries(error.data.errors).map(([name, messages]) => ({ name, message: messages[0] }))
      )
    } else {
      errorMessage.value = error?.data?.message || 'Đăng ký thất bại. Vui lòng thử lại.'
    }
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <UCard>
    <h2 class="text-xl font-semibold text-ink mb-1">Tạo tài khoản</h2>
    <p class="text-sm text-muted mb-5">Bắt đầu quản lý chấm công cho doanh nghiệp của bạn.</p>

    <UAlert
      v-if="errorMessage"
      class="mb-5"
      color="error"
      variant="soft"
      icon="i-heroicons-exclamation-triangle"
      :description="errorMessage"
    />

    <UForm ref="formRef" :schema="schema" :state="state" class="space-y-5" @submit="onSubmit">
      <!-- Name -->
      <UFormField label="Họ và tên" name="name" required>
        <UInput
          v-model="state.name"
          type="text"
          autocomplete="name"
          placeholder="Nguyễn Văn A"
          class="w-full"
          :disabled="loading"
        />
      </UFormField>

      <!-- Email -->
      <UFormField label="Email" name="email" required>
        <UInput
          v-model="state.email"
          type="text"
          autocomplete="email"
          placeholder="example@company.com"
          class="w-full"
          :disabled="loading"
        />
      </UFormField>

      <!-- Password -->
      <UFormField label="Mật khẩu" name="password" required>
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

      <!-- Password Confirmation -->
      <UFormField label="Xác nhận mật khẩu" name="passwordConfirmation" required>
        <UInput
          v-model="state.passwordConfirmation"
          :type="showPasswordConfirmation ? 'text' : 'password'"
          autocomplete="new-password"
          placeholder="Nhập lại mật khẩu"
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

      <!-- Submit -->
      <UButton
        type="submit"
        block
        size="lg"
        :loading="loading"
        :disabled="loading"
      >
        {{ loading ? 'Đang tạo tài khoản...' : 'Tạo tài khoản' }}
      </UButton>
    </UForm>

    <!-- Link to Login -->
    <div class="mt-6 text-center">
      <p class="text-sm text-body">
        Đã có tài khoản?
        <NuxtLink to="/login" class="font-medium text-accent hover:text-accent-ink ml-1">
          Đăng nhập ngay
        </NuxtLink>
      </p>
    </div>
  </UCard>
</template>
