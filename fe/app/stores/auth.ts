import { defineStore } from 'pinia'
import type { User } from '~/types/auth'

export type { User }

export const useAuthStore = defineStore('auth', () => {
  const user = ref<User | null>(null)
  const token = useCookie<string | null>('auth_token', {
    maxAge: 60 * 60 * 24 * 7,
    secure: false,
    sameSite: 'lax',
  })

  const isAuthenticated = computed(() => !!token.value && !!user.value)
  const isManager = computed(() => user.value?.role === 'manager')
  const isEmployee = computed(() => user.value?.role === 'employee')

  const config = useRuntimeConfig()

  async function fetchUser() {
    if (!token.value) return null
    try {
      const data = await $fetch<{ user: User }>(`${config.public.apiBase}/api/user`, {
        headers: { Authorization: `Bearer ${token.value}` },
      })
      user.value = data.user
      return data.user
    } catch {
      token.value = null
      user.value = null
      return null
    }
  }

  async function register(name: string, email: string, password: string, passwordConfirmation: string) {
    const data = await $fetch<{ token: string; user: User }>(`${config.public.apiBase}/api/register`, {
      method: 'POST',
      body: { name, email, password, password_confirmation: passwordConfirmation },
    })
    token.value = data.token
    user.value = data.user
    return data.user
  }

  async function login(email: string, password: string) {
    const data = await $fetch<{ token: string; user: User }>(`${config.public.apiBase}/api/login`, {
      method: 'POST',
      body: { email, password },
    })
    token.value = data.token
    user.value = data.user
    return data.user
  }

  async function logout() {
    if (!token.value) return
    try {
      await $fetch(`${config.public.apiBase}/api/logout`, {
        method: 'POST',
        headers: { Authorization: `Bearer ${token.value}` },
      })
    } finally {
      token.value = null
      user.value = null
    }
  }

  async function forgotPassword(email: string) {
    const data = await $fetch<{ message: string }>(`${config.public.apiBase}/api/forgot-password`, {
      method: 'POST',
      body: { email },
    })
    return data.message
  }

  async function resetPassword(payload: { token: string; email: string; password: string; passwordConfirmation: string }) {
    const data = await $fetch<{ message: string }>(`${config.public.apiBase}/api/reset-password`, {
      method: 'POST',
      body: {
        token: payload.token,
        email: payload.email,
        password: payload.password,
        password_confirmation: payload.passwordConfirmation,
      },
    })
    return data.message
  }

  return { user, token, isAuthenticated, isManager, isEmployee, fetchUser, register, login, logout, forgotPassword, resetPassword }
})
