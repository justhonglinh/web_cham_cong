import { getAuthInstance } from '~/utils/api'
import type { OvertimeShift, OvertimeRequest, OvertimeShiftInput } from '~/types/overtime'

export type { OvertimeShift, OvertimeRequest, OvertimeShiftInput }

export const overtimeService = {
  // Manager
  getShifts: async () => {
    return await getAuthInstance().get<{ data: OvertimeShift[] } | OvertimeShift[]>('/overtime/management')
  },

  getRequests: async () => {
    return await getAuthInstance().get<{ data: OvertimeRequest[] } | OvertimeRequest[]>('/overtime/management/requests')
  },

  createShift: async (data: OvertimeShiftInput) => {
    return await getAuthInstance().post<OvertimeShift>('/overtime/management', { ...data })
  },

  updateShift: async (id: number, data: OvertimeShiftInput) => {
    return await getAuthInstance().put<OvertimeShift>(`/overtime/management/${id}`, { ...data })
  },

  deleteShift: async (id: number) => {
    return await getAuthInstance().delete(`/overtime/management/${id}`)
  },

  updateRequestStatus: async (id: number, status: 'approved' | 'rejected') => {
    return await getAuthInstance().patch(`/overtime-requests/${id}/status`, { status })
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
