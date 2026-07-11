<script setup lang="ts">
import * as z from 'zod'
import type { FormSubmitEvent } from '@nuxt/ui'
import { authService } from '~/services/authService'
import type { User as AuthUser } from '~/types/auth'
import { ROLE_LABEL } from '~/constants'

definePageMeta({ layout: 'default' })

const authStore = useAuthStore()
const toast = useAppToast()

// ---- State ----
const loadingUser = ref(false)
const userInfo = ref<AuthUser | null>(null)

// ---- Profile form ----
const profileSchema = z.object({
  profileName: z.string().min(1, 'Vui lòng nhập họ và tên'),
})
type ProfileSchema = z.output<typeof profileSchema>

const profileState = reactive({ profileName: '' })
const profileFormRef = useTemplateRef('profileFormRef')
const profileLoading = ref(false)

// ---- Password form ----
const passwordSchema = z.object({
  currentPassword: z.string().min(1, 'Vui lòng nhập mật khẩu hiện tại'),
  newPassword: z.string().min(8, 'Mật khẩu mới tối thiểu 8 ký tự'),
  passwordConfirmation: z.string().min(1, 'Vui lòng xác nhận mật khẩu mới'),
}).refine((data) => data.newPassword === data.passwordConfirmation, {
  message: 'Xác nhận mật khẩu không khớp',
  path: ['passwordConfirmation'],
})
type PasswordSchema = z.output<typeof passwordSchema>

const passwordState = reactive({ currentPassword: '', newPassword: '', passwordConfirmation: '' })
const passwordFormRef = useTemplateRef('passwordFormRef')
const passwordLoading = ref(false)

// ---- Fetch user info ----
async function fetchUser() {
  loadingUser.value = true
  try {
    const data = await authService.getUser()
    userInfo.value = data.user
    profileState.profileName = data.user.name
  } catch {
    userInfo.value = authStore.user ? { ...authStore.user } : null
    profileState.profileName = authStore.user?.name ?? ''
  } finally {
    loadingUser.value = false
  }
}

// ---- Update profile ----
async function onSubmitProfile(event: FormSubmitEvent<ProfileSchema>) {
  profileLoading.value = true
  try {
    const data = await authService.updateProfile({ name: event.data.profileName.trim() })
    userInfo.value = data.user
    if (authStore.user) authStore.user.name = data.user.name
    profileState.profileName = data.user.name
    toast.success('Cập nhật hồ sơ thành công.')
  } catch (err: unknown) {
    const error = err as { data?: { message?: string; errors?: Record<string, string[]> } }
    if (error?.data?.errors) {
      const fieldMap: Record<string, string> = { name: 'profileName' }
      profileFormRef.value?.setErrors(
        Object.entries(error.data.errors).map(([name, messages]) => ({
          name: fieldMap[name] ?? name,
          message: messages[0],
        }))
      )
    } else {
      toast.error(error?.data?.message || 'Cập nhật hồ sơ thất bại. Vui lòng thử lại.')
    }
  } finally {
    profileLoading.value = false
  }
}

// ---- Change password ----
async function onSubmitPassword(event: FormSubmitEvent<PasswordSchema>) {
  passwordLoading.value = true
  try {
    await authService.updatePassword({
      current_password: event.data.currentPassword,
      password: event.data.newPassword,
      password_confirmation: event.data.passwordConfirmation,
    })
    passwordState.currentPassword = ''
    passwordState.newPassword = ''
    passwordState.passwordConfirmation = ''
    toast.success('Đổi mật khẩu thành công.')
  } catch (err: unknown) {
    const error = err as { data?: { message?: string; errors?: Record<string, string[]> } }
    if (error?.data?.errors) {
      const fieldMap: Record<string, string> = {
        current_password: 'currentPassword',
        password: 'newPassword',
        password_confirmation: 'passwordConfirmation',
      }
      passwordFormRef.value?.setErrors(
        Object.entries(error.data.errors).map(([name, messages]) => ({
          name: fieldMap[name] ?? name,
          message: messages[0],
        }))
      )
    } else {
      toast.error(error?.data?.message || 'Đổi mật khẩu thất bại. Vui lòng thử lại.')
    }
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

      <UForm ref="profileFormRef" :schema="profileSchema" :state="profileState" class="space-y-4" @submit="onSubmitProfile">
        <UFormField name="profileName" label="Họ và tên" required>
          <UInput v-model="profileState.profileName" type="text" class="w-full" placeholder="Nhập họ và tên" :disabled="profileLoading" />
        </UFormField>
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
      </UForm>
    </UCard>

    <!-- Change Password Card -->
    <UCard>
      <h3 class="text-lg font-semibold text-ink mb-5">Đổi mật khẩu</h3>

      <UForm ref="passwordFormRef" :schema="passwordSchema" :state="passwordState" class="space-y-4" @submit="onSubmitPassword">
        <UFormField name="currentPassword" label="Mật khẩu hiện tại" required>
          <UInput
            v-model="passwordState.currentPassword"
            type="password"
            class="w-full"
            placeholder="••••••••"
            :disabled="passwordLoading"
            autocomplete="current-password"
          />
        </UFormField>
        <UFormField name="newPassword" label="Mật khẩu mới" required help="Ít nhất 8 ký tự">
          <UInput
            v-model="passwordState.newPassword"
            type="password"
            class="w-full"
            placeholder="••••••••"
            :disabled="passwordLoading"
            autocomplete="new-password"
          />
        </UFormField>
        <UFormField name="passwordConfirmation" label="Xác nhận mật khẩu mới" required>
          <UInput
            v-model="passwordState.passwordConfirmation"
            type="password"
            class="w-full"
            placeholder="••••••••"
            :disabled="passwordLoading"
            autocomplete="new-password"
          />
        </UFormField>
        <div class="flex justify-end">
          <UButton type="submit" :disabled="passwordLoading" :loading="passwordLoading">
            {{ passwordLoading ? 'Đang đổi...' : 'Đổi mật khẩu' }}
          </UButton>
        </div>
      </UForm>
    </UCard>
  </div>
</template>
