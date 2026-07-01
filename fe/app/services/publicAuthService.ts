import type { AuthResponse } from '~/types/auth'

export const publicAuthService = {
  login: async (email: string, password: string) => {
    return await $fetch<AuthResponse>('/api/login', {
      method: 'POST',
      body: { email, password },
    })
  },

  register: async (name: string, email: string, password: string, password_confirmation: string) => {
    return await $fetch<AuthResponse>('/api/register', {
      method: 'POST',
      body: { name, email, password, password_confirmation },
    })
  },

  logout: async (token: string) => {
    return await $fetch<{ message: string }>('/api/logout', {
      method: 'POST',
      headers: { Authorization: `Bearer ${token}` },
    })
  },
}
