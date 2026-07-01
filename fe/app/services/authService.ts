import { getAuthInstance } from '~/utils/api'
import type { UpdateProfileInput, UpdatePasswordInput } from '~/types/auth'
import type { AuthUser } from '~/types'

export const authService = {
  getUser: async () => {
    return await getAuthInstance().get<{ user: AuthUser }>('/user')
  },

  updateProfile: async (data: UpdateProfileInput) => {
    return await getAuthInstance().patch<{ message: string; user: AuthUser }>('/profile', { ...data })
  },

  updatePassword: async (data: UpdatePasswordInput) => {
    return await getAuthInstance().put<{ message: string }>('/password', { ...data })
  },
}
