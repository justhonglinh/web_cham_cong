<script setup lang="ts">
import * as z from 'zod'
import type { FormSubmitEvent } from '@nuxt/ui'

definePageMeta({ layout: 'auth' })

const authStore = useAuthStore()

const schema = z.object({
  email: z.email('Email không hợp lệ').min(1, 'Vui lòng nhập email'),
  password: z.string().min(1, 'Vui lòng nhập mật khẩu'),
})
type Schema = z.output<typeof schema>

const state = reactive({ email: '', password: '' })
const rememberMe = ref(false)
const loading = ref(false)
const errorMessage = ref('')
const showPassword = ref(false)

// Redirect if already authenticated
if (authStore.isAuthenticated) {
  navigateTo(authStore.isManager ? '/dashboard' : '/employees/dashboard')
}

async function onSubmit(event: FormSubmitEvent<Schema>) {
  loading.value = true
  errorMessage.value = ''

  try {
    const user = await authStore.login(event.data.email, event.data.password)
    if (user.role === 'manager') {
      await navigateTo('/dashboard')
    } else {
      await navigateTo('/employees/dashboard')
    }
  } catch (err: unknown) {
    const error = err as { data?: { message?: string }; statusCode?: number }
    if (error?.data?.message) {
      errorMessage.value = error.data.message
    } else if (error?.statusCode === 401) {
      errorMessage.value = 'Email hoặc mật khẩu không đúng.'
    } else {
      errorMessage.value = 'Đăng nhập thất bại. Vui lòng thử lại.'
    }
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <UCard>
    <h2 class="text-xl font-semibold text-ink mb-1">Đăng nhập</h2>
    <p class="text-sm text-muted mb-5">Chào mừng quay lại! Vui lòng nhập thông tin tài khoản.</p>

    <UAlert
      v-if="errorMessage"
      class="mb-5"
      color="error"
      variant="soft"
      icon="i-heroicons-exclamation-triangle"
      :description="errorMessage"
    />

    <UForm :schema="schema" :state="state" class="space-y-5" @submit="onSubmit">
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
          autocomplete="current-password"
          placeholder="••••••••"
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

      <!-- Remember Me + Forgot password -->
      <div class="flex items-center justify-between">
        <UCheckbox v-model="rememberMe" label="Nhớ mật khẩu" />
        <NuxtLink
          to="/forgot-password"
          class="text-sm font-medium text-accent hover:text-accent-ink"
        >
          Quên mật khẩu?
        </NuxtLink>
      </div>

      <!-- Submit Button -->
      <UButton
        type="submit"
        block
        size="lg"
        :loading="loading"
        :disabled="loading"
      >
        {{ loading ? 'Đang đăng nhập...' : 'Đăng nhập' }}
      </UButton>
    </UForm>

    <div class="mt-6 text-center">
      <p class="text-sm text-body">
        Chưa có tài khoản?
        <NuxtLink to="/register" class="font-medium text-accent hover:text-accent-ink ml-1">
          Đăng ký ngay
        </NuxtLink>
      </p>
    </div>
  </UCard>
</template>
