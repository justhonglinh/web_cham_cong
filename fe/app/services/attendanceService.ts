import { getAuthInstance } from '~/utils/api'
import type { AttendanceRecord, PaginatedAttendance, UpdateAttendanceInput } from '~/types/attendance'

export type { AttendanceRecord, PaginatedAttendance, UpdateAttendanceInput }

export const attendanceService = {
  // Manager
  getManagement: async (params?: { month?: number; year?: number; page?: number; per_page?: number }) => {
    return await getAuthInstance().get<PaginatedAttendance | AttendanceRecord[]>('/attendance/management', params)
  },

  updateManagement: async (id: number, data: UpdateAttendanceInput) => {
    const res = await getAuthInstance().put<{ data: AttendanceRecord }>(`/attendance/management/${id}`, { ...data })
    return res.data
  },

  // Employee
  getToday: async () => {
    const res = await getAuthInstance().get<{ data: AttendanceRecord | null }>('/employees/attendance/today')
    return res.data
  },

  checkIn: async (data?: Record<string, unknown>) => {
    const res = await getAuthInstance().post<{ data: AttendanceRecord }>('/employees/attendance', { ...data })
    return res.data
  },

  getHistory: async (params?: { page?: number; per_page?: number; limit?: number }) => {
    return await getAuthInstance().get<PaginatedAttendance | AttendanceRecord[]>('/employees/attendance/history', params)
  },
}
