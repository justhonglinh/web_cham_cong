<script setup lang="ts">
definePageMeta({ layout: 'auth' })

const authStore = useAuthStore()

if (authStore.isAuthenticated) {
  navigateTo(authStore.isManager ? '/dashboard' : '/employees/dashboard')
}

const name = ref('')
const email = ref('')
const password = ref('')
const passwordConfirmation = ref('')
const loading = ref(false)
const errorMessage = ref('')
const showPassword = ref(false)
const showPasswordConfirmation = ref(false)

async function handleRegister() {
  if (!name.value || !email.value || !password.value || !passwordConfirmation.value) {
    errorMessage.value = 'Vui lòng nhập đầy đủ thông tin.'
    return
  }

  if (password.value !== passwordConfirmation.value) {
    errorMessage.value = 'Xác nhận mật khẩu không khớp.'
    return
  }

  loading.value = true
  errorMessage.value = ''

  try {
    await authStore.register(name.value, email.value, password.value, passwordConfirmation.value)
    await navigateTo('/dashboard')
  } catch (err: unknown) {
    const error = err as { data?: { message?: string; errors?: Record<string, string[]> }; statusCode?: number }
    if (error?.data?.errors) {
      const firstError = Object.values(error.data.errors)[0]
      errorMessage.value = Array.isArray(firstError) ? firstError[0] : String(firstError)
    } else if (error?.data?.message) {
      errorMessage.value = error.data.message
    } else {
      errorMessage.value = 'Đăng ký thất bại. Vui lòng thử lại.'
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
        <h2 class="text-base font-semibold text-ink mb-5">Tạo tài khoản</h2>

        <UAlert
          v-if="errorMessage"
          class="mb-5"
          color="error"
          variant="soft"
          icon="i-heroicons-exclamation-triangle"
          :description="errorMessage"
        />

        <form class="space-y-5" @submit.prevent="handleRegister">
          <!-- Name -->
          <div>
            <label for="name" class="block text-sm font-medium text-body mb-1">Họ và tên</label>
            <UInput
              id="name"
              v-model="name"
              type="text"
              autocomplete="name"
              placeholder="Nguyễn Văn A"
              class="w-full"
              :disabled="loading"
              required
            />
          </div>

          <!-- Email -->
          <div>
            <label for="email" class="block text-sm font-medium text-body mb-1">Email</label>
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
            <label for="password" class="block text-sm font-medium text-body mb-1">Mật khẩu</label>
            <UInput
              id="password"
              v-model="password"
              :type="showPassword ? 'text' : 'password'"
              autocomplete="new-password"
              placeholder="Tối thiểu 8 ký tự"
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

          <!-- Password Confirmation -->
          <div>
            <label for="password-confirmation" class="block text-sm font-medium text-body mb-1">Xác nhận mật khẩu</label>
            <UInput
              id="password-confirmation"
              v-model="passwordConfirmation"
              :type="showPasswordConfirmation ? 'text' : 'password'"
              autocomplete="new-password"
              placeholder="Nhập lại mật khẩu"
              class="w-full"
              :disabled="loading"
              required
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
          </div>

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
        </form>

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
