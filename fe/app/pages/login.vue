<script setup lang="ts">
definePageMeta({ layout: 'auth' })

const authStore = useAuthStore()
const router = useRouter()

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
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50 px-4">
    <div class="w-full max-w-md">
      <!-- Logo / Header -->
      <div class="text-center mb-8">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-600 to-purple-600 rounded-2xl shadow-lg mb-4">
          <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
          </svg>
        </div>
        <h1 class="text-3xl font-bold text-gray-900">Chấm Công</h1>
        <p class="text-gray-500 mt-1 text-sm">Hệ thống quản lý chấm công</p>
      </div>

      <!-- Card -->
      <div class="card p-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-6">Đăng nhập</h2>

        <!-- Error Alert -->
        <div v-if="errorMessage" class="mb-5 flex items-start gap-3 bg-red-50 border border-red-200 text-red-700 rounded-lg px-4 py-3 text-sm">
          <svg class="w-5 h-5 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z" />
          </svg>
          <span>{{ errorMessage }}</span>
        </div>

        <form @submit.prevent="handleLogin" class="space-y-5">
          <!-- Email -->
          <div>
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
              Email
            </label>
            <input
              id="email"
              v-model="email"
              type="email"
              autocomplete="email"
              placeholder="example@company.com"
              class="input-field"
              :disabled="loading"
              required
            />
          </div>

          <!-- Password -->
          <div>
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
              Mật khẩu
            </label>
            <div class="relative">
              <input
                id="password"
                v-model="password"
                :type="showPassword ? 'text' : 'password'"
                autocomplete="current-password"
                placeholder="••••••••"
                class="input-field pr-10"
                :disabled="loading"
                required
              />
              <button
                type="button"
                class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-400 hover:text-gray-600"
                @click="showPassword = !showPassword"
              >
                <svg v-if="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0zm-3-9C7.477 3 3 8.477 3 12s4.477 9 9 9 9-4.477 9-9S16.523 3 12 3z" />
                </svg>
                <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18" />
                </svg>
              </button>
            </div>
          </div>

          <!-- Remember Me -->
          <div class="flex items-center">
            <input
              id="remember-me"
              v-model="rememberMe"
              type="checkbox"
              class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
            />
            <label for="remember-me" class="ml-2 block text-sm text-gray-700 cursor-pointer">
              Nhớ mật khẩu
            </label>
          </div>

          <!-- Submit Button -->
          <button
            type="submit"
            class="btn-primary w-full justify-center py-2.5 text-base"
            :disabled="loading"
          >
            <svg v-if="loading" class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
            </svg>
            {{ loading ? 'Đang đăng nhập...' : 'Đăng nhập' }}
          </button>
        </form>
      </div>

      <p class="text-center text-xs text-gray-400 mt-6">
        &copy; {{ new Date().getFullYear() }} Hệ thống chấm công. All rights reserved.
      </p>
    </div>
  </div>
</template>
