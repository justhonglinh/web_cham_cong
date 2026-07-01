import { getAuthInstance } from '~/utils/api'
import type { Shift, ShiftInput } from '~/types/shift'

export type { Shift, ShiftInput }

export const shiftService = {
  getAll: async () => {
    return await getAuthInstance().get<{ data: Shift[] } | Shift[]>('/shift/management')
  },

  create: async (data: ShiftInput) => {
    const res = await getAuthInstance().post<{ data: Shift }>('/shift/management', { ...data })
    return res.data
  },

  update: async (id: number, data: ShiftInput) => {
    const res = await getAuthInstance().put<{ data: Shift }>(`/shift/management/${id}`, { ...data })
    return res.data
  },

  delete: async (id: number) => {
    return await getAuthInstance().delete(`/shift/management/${id}`)
  },
}
