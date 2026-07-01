import { getAuthInstance } from '~/utils/api'
import type { Shift, ShiftInput } from '~/types/shift'

export type { Shift, ShiftInput }

export const shiftService = {
  getAll: async () => {
    return await getAuthInstance().get<{ data: Shift[] } | Shift[]>('/shift/management')
  },

  create: async (data: ShiftInput) => {
    return await getAuthInstance().post<Shift>('/shift/management', { ...data })
  },

  update: async (id: number, data: ShiftInput) => {
    return await getAuthInstance().put<Shift>(`/shift/management/${id}`, { ...data })
  },

  delete: async (id: number) => {
    return await getAuthInstance().delete(`/shift/management/${id}`)
  },
}
