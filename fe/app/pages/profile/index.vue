<script setup lang="ts">
import { authService } from '~/services/authService'
import { toast } from 'vue-sonner'
import type { User as AuthUser } from '~/types/auth'
import { ROLE_LABEL } from '~/constants'

definePageMeta({ layout: 'default' })

const authStore = useAuthStore()

// ---- State ----
const loadingUser = ref(false)
const userInfo = ref<AuthUser | null>(null)

// Profile form
const profileName = ref('')
const profileLoading = ref(false)

// Password form
const currentPassword = ref('')
const newPassword = ref('')
const passwordConfirmation = ref('')
const passwordLoading = ref(false)

// ---- Fetch user info ----
async function fetchUser() {
  loadingUser.value = true
  try {
    const data = await authService.getUser()
    userInfo.value = data.user
    profileName.value = data.user.name
  } catch {
    userInfo.value = authStore.user ? { ...authStore.user } : null
    profileName.value = authStore.user?.name ?? ''
  } finally {
    loadingUser.value = false
  }
}

// ---- Update profile ----
async function updateProfile() {
  if (!profileName.value.trim()) return
  profileLoading.value = true
  try {
    const data = await authService.updateProfile({ name: profileName.value.trim() })
    userInfo.value = data.user
    if (authStore.user) authStore.user.name = data.user.name
    toast.success('Cập nhật hồ sơ thành công.')
  } catch {
    // toast error hiển thị tự động
  } finally {
    profileLoading.value = false
  }
}

// ---- Change password ----
async function changePassword() {
  if (!currentPassword.value || !newPassword.value || !passwordConfirmation.value) return
  if (newPassword.value !== passwordConfirmation.value) return
  if (newPassword.value.length < 8) return
  passwordLoading.value = true
  try {
    await authService.updatePassword({
      current_password: currentPassword.value,
      password: newPassword.value,
      password_confirmation: passwordConfirmation.value,
    })
    currentPassword.value = ''
    newPassword.value = ''
    passwordConfirmation.value = ''
    toast.success('Đổi mật khẩu thành công.')
  } catch {
    // toast error hiển thị tự động
  } finally {
    passwordLoading.value = false
  }
}

const roleLabel = computed(() => {
  if (!userInfo.value) return ''
  return ROLE_LABEL[userInfo.value.role] ?? userInfo.value.role
})

onMounted(fetchUser)
</script>

<template>
  <div class="max-w-3xl mx-auto space-y-6">
    <!-- Page Header -->
    <div>
      <h1 class="text-2xl font-bold text-ink">Hồ sơ cá nhân</h1>
      <p class="text-muted text-sm mt-1">Xem và cập nhật thông tin tài khoản của bạn.</p>
    </div>

    <!-- User Info Card -->
    <UCard>
      <div v-if="loadingUser" class="flex items-center gap-3 text-faint">
        <UIcon name="i-heroicons-arrow-path" class="animate-spin h-5 w-5" />
        <span class="text-sm">Đang tải thông tin...</span>
      </div>
      <div v-else-if="userInfo" class="flex items-center gap-5">
        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-2xl font-bold shrink-0">
          {{ userInfo.name.charAt(0).toUpperCase() }}
        </div>
        <div>
          <h2 class="text-xl font-semibold text-ink">{{ userInfo.name }}</h2>
          <p class="text-muted text-sm">{{ userInfo.email }}</p>
          <StatusChip color="info" class="mt-1">{{ roleLabel }}</StatusChip>
        </div>
      </div>
    </UCard>

    <!-- Update Profile Card -->
    <UCard>
      <h3 class="text-lg font-semibold text-ink mb-5">Cập nhật thông tin</h3>

      <form @submit.prevent="updateProfile" class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-body mb-1">Họ và tên</label>
          <UInput v-model="profileName" type="text" class="w-full" placeholder="Nhập họ và tên" :disabled="profileLoading" />
        </div>
        <div>
          <label class="block text-sm font-medium text-body mb-1">Email</label>
          <UInput :value="userInfo?.email" type="email" class="w-full bg-neutral-soft cursor-not-allowed" disabled />
          <p class="text-xs text-faint mt-1">Email không thể thay đổi.</p>
        </div>
        <div class="flex justify-end">
          <UButton type="submit" :disabled="profileLoading" :loading="profileLoading">
            {{ profileLoading ? 'Đang lưu...' : 'Lưu thay đổi' }}
          </UButton>
        </div>
      </form>
    </UCard>

    <!-- Change Password Card -->
    <UCard>
      <h3 class="text-lg font-semibold text-ink mb-5">Đổi mật khẩu</h3>

      <form @submit.prevent="changePassword" class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-body mb-1">Mật khẩu hiện tại</label>
          <UInput
            v-model="currentPassword"
            type="password"
            class="w-full"
            placeholder="••••••••"
            :disabled="passwordLoading"
            autocomplete="current-password"
          />
        </div>
        <div>
          <label class="block text-sm font-medium text-body mb-1">Mật khẩu mới</label>
          <UInput
            v-model="newPassword"
            type="password"
            class="w-full"
            placeholder="••••••••"
            :disabled="passwordLoading"
            autocomplete="new-password"
          />
          <p class="text-xs text-faint mt-1">Ít nhất 8 ký tự.</p>
        </div>
        <div>
          <label class="block text-sm font-medium text-body mb-1">Xác nhận mật khẩu mới</label>
          <UInput
            v-model="passwordConfirmation"
            type="password"
            class="w-full"
            placeholder="••••••••"
            :disabled="passwordLoading"
            autocomplete="new-password"
          />
        </div>
        <div class="flex justify-end">
          <UButton type="submit" :disabled="passwordLoading" :loading="passwordLoading">
            {{ passwordLoading ? 'Đang đổi...' : 'Đổi mật khẩu' }}
          </UButton>
        </div>
      </form>
    </UCard>
  </div>
</template>
