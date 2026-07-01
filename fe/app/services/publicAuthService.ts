import type { AuthResponse } from '~/types/auth'

export const publicAuthService = {
  login: async (email: string, password: string) => {
    const config = useRuntimeConfig()
    return await $fetch<AuthResponse>(`${config.public.apiBase}/api/login`, {
      method: 'POST',
      body: { email, password },
    })
  },

  register: async (name: string, email: string, password: string, password_confirmation: string) => {
    const config = useRuntimeConfig()
    return await $fetch<AuthResponse>(`${config.public.apiBase}/api/register`, {
      method: 'POST',
      body: { name, email, password, password_confirmation },
    })
  },

  logout: async (token: string) => {
    const config = useRuntimeConfig()
    return await $fetch<{ message: string }>(`${config.public.apiBase}/api/logout`, {
      method: 'POST',
      headers: { Authorization: `Bearer ${token}` },
    })
  },
}
