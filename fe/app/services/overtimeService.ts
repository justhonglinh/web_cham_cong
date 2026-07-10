import { getAuthInstance } from '~/utils/api'
import type { PaginatedResponse } from '~/composables/usePagination'
import type { OvertimeShift, OvertimeRequest, OvertimeShiftInput } from '~/types/overtime'

export type { OvertimeShift, OvertimeRequest, OvertimeShiftInput }

export const overtimeService = {
  // Manager
  getShifts: async () => {
    return await getAuthInstance().get<{ data: OvertimeShift[] } | OvertimeShift[]>('/overtime/management')
  },

  getRequests: async (params?: { page?: number; per_page?: number }) => {
    return await getAuthInstance().get<PaginatedResponse<OvertimeRequest> | OvertimeRequest[]>('/overtime/management/requests', params)
  },

  createShift: async (data: OvertimeShiftInput) => {
    const res = await getAuthInstance().post<{ data: OvertimeShift }>('/overtime/management', { ...data })
    return res.data
  },

  updateShift: async (id: number, data: OvertimeShiftInput) => {
    const res = await getAuthInstance().put<{ data: OvertimeShift }>(`/overtime/management/${id}`, { ...data })
    return res.data
  },

  deleteShift: async (id: number) => {
    return await getAuthInstance().delete(`/overtime/management/${id}`)
  },

  updateRequestStatus: async (id: number, status: 'approved' | 'rejected') => {
    const res = await getAuthInstance().patch<{ data: OvertimeRequest }>(`/overtime-requests/${id}/status`, { status })
    return res.data
  },

  // Employee
  getAvailableShifts: async () => {
    return await getAuthInstance().get<{ data: OvertimeShift[] } | OvertimeShift[]>('/overtime/employee')
  },

  register: async (shiftId: number) => {
    return await getAuthInstance().post(`/overtime/register/${shiftId}`, {})
  },

  unregister: async (shiftId: number) => {
    return await getAuthInstance().delete(`/overtime/unregister/${shiftId}`)
  },
}
