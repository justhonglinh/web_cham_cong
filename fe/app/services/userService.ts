import { getAuthInstance } from '~/utils/api'
import type { PaginatedResponse } from '~/composables/usePagination'
import type { User, CreateUserInput, UpdateUserInput } from '~/types/user'

export type { User, CreateUserInput, UpdateUserInput }

export const userService = {
  getAll: async (params?: { page?: number; per_page?: number; search?: string }) => {
    return await getAuthInstance().get<PaginatedResponse<User> | User[]>('/users', params)
  },

  create: async (data: CreateUserInput) => {
    const res = await getAuthInstance().post<{ data: User }>('/users', { ...data })
    return res.data
  },

  update: async (id: number, data: UpdateUserInput) => {
    const res = await getAuthInstance().put<{ data: User }>(`/users/${id}`, { ...data })
    return res.data
  },

  delete: async (id: number) => {
    return await getAuthInstance().delete(`/users/${id}`)
  },
}
