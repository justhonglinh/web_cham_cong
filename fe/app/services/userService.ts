import { getAuthInstance } from '~/utils/api'
import type { User, CreateUserInput, UpdateUserInput } from '~/types/user'

export type { User, CreateUserInput, UpdateUserInput }

export const userService = {
  getAll: async () => {
    return await getAuthInstance().get<{ data: User[] } | User[]>('/users')
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
