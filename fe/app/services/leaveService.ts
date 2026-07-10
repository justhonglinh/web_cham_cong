import { getAuthInstance } from '~/utils/api'
import type { PaginatedResponse } from '~/composables/usePagination'
import type { LeaveRequest, CreateLeaveInput } from '~/types/leave'

export type { LeaveRequest, CreateLeaveInput }

export const leaveService = {
  // Manager
  getAll: async (params?: { page?: number; per_page?: number }) => {
    return await getAuthInstance().get<PaginatedResponse<LeaveRequest> | LeaveRequest[]>('/leave-requests/management', params)
  },

  updateStatus: async (id: number, status: 'approved' | 'rejected') => {
    return await getAuthInstance().patch(`/leave-requests/${id}/status`, { status })
  },

  // Employee
  create: async (data: CreateLeaveInput) => {
    const res = await getAuthInstance().post<{ data: LeaveRequest }>('/employees/leave', { ...data })
    return res.data
  },

  getHistory: async () => {
    return await getAuthInstance().get<{ data: LeaveRequest[] } | LeaveRequest[]>('/employees/leave/history')
  },

  cancel: async (id: number) => {
    return await getAuthInstance().delete(`/employees/leave/${id}`)
  },
}
