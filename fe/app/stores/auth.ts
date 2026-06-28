import { defineStore } from 'pinia'

export interface User {
  id: number
  name: string
  email: string
  role: 'manager' | 'employee'
  avatar?: string
}

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

  async function fetchUser() {
    if (!token.value) return null
    try {
      const data = await $fetch<{ user: User }>('/api/user', {
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

  async function login(email: string, password: string) {
    const data = await $fetch<{ token: string; user: User }>('/api/login', {
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
      await $fetch('/api/logout', {
        method: 'POST',
        headers: { Authorization: `Bearer ${token.value}` },
      })
    } finally {
      token.value = null
      user.value = null
    }
  }

  return { user, token, isAuthenticated, isManager, isEmployee, fetchUser, login, logout }
})
