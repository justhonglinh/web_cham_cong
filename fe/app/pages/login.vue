<script setup lang="ts">
definePageMeta({ layout: 'auth' })

const authStore = useAuthStore()

const email = ref('')
const password = ref('')
const rememberMe = ref(false)
const loading = ref(false)
const errorMessage = ref('')
const showPassword = ref(false)

// Redirect if already authenticated
if (authStore.isAuthenticated) {
  navigateTo(authStore.isManager ? '/dashboard' : '/employees/dashboard')
}

async function handleLogin() {
  if (!email.value || !password.value) {
    errorMessage.value = 'Vui lòng nhập đầy đủ email và mật khẩu.'
    return
  }

  loading.value = true
  errorMessage.value = ''

  try {
    const user = await authStore.login(email.value, password.value)
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
  <div class="min-h-screen flex items-center justify-center px-4 auth-bg">
    <div class="w-full max-w-sm">
      <!-- Logo / Header -->
      <div class="flex flex-col items-center gap-3 mb-7">
        <div class="w-11 h-11 bg-accent rounded-xl shadow-[0_8px_20px_rgba(47,95,246,0.28)] flex items-center justify-center">
          <UIcon name="i-heroicons-users" class="w-5.5 h-5.5 text-white" />
        </div>
        <div class="text-center">
          <h1 class="text-xl font-semibold text-ink tracking-tight">Chấm Công</h1>
          <p class="text-faint mt-0.5 text-sm">Hệ thống quản lý chấm công</p>
        </div>
      </div>

      <!-- Card -->
      <UCard>
        <h2 class="text-base font-semibold text-ink mb-5">Đăng nhập</h2>

        <UAlert
          v-if="errorMessage"
          class="mb-5"
          color="error"
          variant="soft"
          icon="i-heroicons-exclamation-triangle"
          :description="errorMessage"
        />

        <form class="space-y-5" @submit.prevent="handleLogin">
          <!-- Email -->
          <div>
            <label for="email" class="block text-sm font-medium text-body mb-1">
              Email
            </label>
            <UInput
              id="email"
              v-model="email"
              type="email"
              autocomplete="email"
              placeholder="example@company.com"
              class="w-full"
              :disabled="loading"
              required
            />
          </div>

          <!-- Password -->
          <div>
            <label for="password" class="block text-sm font-medium text-body mb-1">
              Mật khẩu
            </label>
            <UInput
              id="password"
              v-model="password"
              :type="showPassword ? 'text' : 'password'"
              autocomplete="current-password"
              placeholder="••••••••"
              class="w-full"
              :disabled="loading"
              required
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
          </div>

          <!-- Remember Me + Register link -->
          <div class="flex items-center justify-between">
            <UCheckbox v-model="rememberMe" label="Nhớ mật khẩu" />
            <NuxtLink
              to="/register"
              class="inline-flex items-center gap-1 text-sm font-medium text-accent hover:text-accent-ink"
            >
              <UIcon name="i-heroicons-user-plus" class="w-4 h-4" />
              Tạo tài khoản
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
        </form>
      </UCard>

      <p class="text-center text-xs text-faint mt-6">
        &copy; {{ new Date().getFullYear() }} Hệ thống chấm công. All rights reserved.
      </p>
    </div>
  </div>
</template>

<style scoped>
.auth-bg {
  background:
    radial-gradient(600px 400px at 12% 8%, #eef2ff 0%, transparent 60%),
    radial-gradient(500px 380px at 90% 90%, #ecfdf3 0%, transparent 55%),
    var(--color-canvas);
}
</style>
