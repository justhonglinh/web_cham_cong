import { getAuthInstance } from '~/utils/api'
import type { Location, LocationInput } from '~/types/location'

export type { Location, LocationInput }

export const locationService = {
  getAll: async () => {
    return await getAuthInstance().get<{ data: Location[] } | Location[]>('/locations')
  },

  create: async (data: LocationInput) => {
    const res = await getAuthInstance().post<{ data: Location }>('/locations', { ...data })
    return res.data
  },

  update: async (id: number, data: LocationInput) => {
    const res = await getAuthInstance().put<{ data: Location }>(`/locations/${id}`, { ...data })
    return res.data
  },

  delete: async (id: number) => {
    return await getAuthInstance().delete(`/locations/${id}`)
  },

  toggle: async (id: number) => {
    return await getAuthInstance().patch(`/locations/${id}/toggle`, {})
  },
}
