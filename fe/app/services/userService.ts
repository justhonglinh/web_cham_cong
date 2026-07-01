import { getAuthInstance } from '~/utils/api'
import type { User, CreateUserInput, UpdateUserInput } from '~/types/user'

export type { User, CreateUserInput, UpdateUserInput }

export const userService = {
  getAll: async () => {
    return await getAuthInstance().get<{ data: User[] } | User[]>('/users')
  },

  create: async (data: CreateUserInput) => {
    return await getAuthInstance().post<User>('/users', { ...data })
  },

  update: async (id: number, data: UpdateUserInput) => {
    return await getAuthInstance().put<User>(`/users/${id}`, { ...data })
  },

  delete: async (id: number) => {
    return await getAuthInstance().delete(`/users/${id}`)
  },
}
