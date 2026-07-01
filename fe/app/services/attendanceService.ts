import { getAuthInstance } from '~/utils/api'
import type { AttendanceRecord, PaginatedAttendance, UpdateAttendanceInput } from '~/types/attendance'

export type { AttendanceRecord, PaginatedAttendance, UpdateAttendanceInput }

export const attendanceService = {
  // Manager
  getManagement: async (params?: { month?: number; year?: number }) => {
    return await getAuthInstance().get<{ data: AttendanceRecord[] } | AttendanceRecord[]>('/attendance/management', params)
  },

  updateManagement: async (id: number, data: UpdateAttendanceInput) => {
    return await getAuthInstance().put<AttendanceRecord>(`/attendance/management/${id}`, { ...data })
  },

  // Employee
  getToday: async () => {
    return await getAuthInstance().get<{ attendance: AttendanceRecord | null }>('/employees/attendance/today')
  },

  checkIn: async (data?: Record<string, unknown>) => {
    return await getAuthInstance().post<AttendanceRecord>('/employees/attendance', { ...data })
  },

  getHistory: async (params?: { page?: number; per_page?: number; limit?: number }) => {
    return await getAuthInstance().get<PaginatedAttendance | AttendanceRecord[]>('/employees/attendance/history', params)
  },
}
