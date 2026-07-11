<script setup lang="ts">
import * as z from 'zod'
import type { FormSubmitEvent } from '@nuxt/ui'

definePageMeta({ layout: 'auth' })

const authStore = useAuthStore()

const schema = z.object({
  email: z.email('Email không hợp lệ').min(1, 'Vui lòng nhập email'),
})
type Schema = z.output<typeof schema>

const state = reactive({ email: '' })
const loading = ref(false)
const errorMessage = ref('')
const sent = ref(false)

async function onSubmit(event: FormSubmitEvent<Schema>) {
  loading.value = true
  errorMessage.value = ''

  try {
    await authStore.forgotPassword(event.data.email)
    sent.value = true
  } catch (err: unknown) {
    const error = err as { data?: { message?: string } }
    errorMessage.value = error?.data?.message || 'Không thể gửi liên kết đặt lại mật khẩu. Vui lòng thử lại.'
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <UCard>
    <template v-if="!sent">
      <h2 class="text-xl font-semibold text-ink mb-1">Quên mật khẩu?</h2>
      <p class="text-sm text-muted mb-5">Nhập email tài khoản của bạn, chúng tôi sẽ gửi liên kết đặt lại mật khẩu.</p>

      <UAlert
        v-if="errorMessage"
        class="mb-5"
        color="error"
        variant="soft"
        icon="i-heroicons-exclamation-triangle"
        :description="errorMessage"
      />

      <UForm :schema="schema" :state="state" class="space-y-5" @submit="onSubmit">
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

        <UButton type="submit" block size="lg" :loading="loading" :disabled="loading">
          {{ loading ? 'Đang gửi...' : 'Gửi liên kết đặt lại' }}
        </UButton>
      </UForm>
    </template>

    <template v-else>
      <div class="flex flex-col items-center text-center gap-3 py-2">
        <div class="w-12 h-12 rounded-full bg-success-soft flex items-center justify-center">
          <UIcon name="i-heroicons-envelope" class="w-6 h-6 text-success" />
        </div>
        <h2 class="text-xl font-semibold text-ink">Kiểm tra email của bạn</h2>
        <p class="text-sm text-muted">
          Chúng tôi đã gửi liên kết đặt lại mật khẩu tới <strong class="text-body">{{ state.email }}</strong>.
          Liên kết có hiệu lực trong 60 phút.
        </p>
        <UButton variant="soft" color="neutral" class="mt-2" @click="sent = false">
          Gửi lại email khác
        </UButton>
      </div>
    </template>

    <div class="mt-6 text-center">
      <NuxtLink to="/login" class="inline-flex items-center gap-1 text-sm font-medium text-accent hover:text-accent-ink">
        <UIcon name="i-heroicons-arrow-left" class="w-4 h-4" />
        Quay lại đăng nhập
      </NuxtLink>
    </div>
  </UCard>
</template>
