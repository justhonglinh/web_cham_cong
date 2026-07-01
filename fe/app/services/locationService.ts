import { getAuthInstance } from '~/utils/api'
import type { Location, LocationInput } from '~/types/location'

export type { Location, LocationInput }

export const locationService = {
  getAll: async () => {
    return await getAuthInstance().get<{ data: Location[] } | Location[]>('/locations')
  },

  create: async (data: LocationInput) => {
    return await getAuthInstance().post<Location>('/locations', { ...data })
  },

  update: async (id: number, data: LocationInput) => {
    return await getAuthInstance().put<Location>(`/locations/${id}`, { ...data })
  },

  delete: async (id: number) => {
    return await getAuthInstance().delete(`/locations/${id}`)
  },

  toggle: async (id: number) => {
    return await getAuthInstance().patch(`/locations/${id}/toggle`, {})
  },
}
