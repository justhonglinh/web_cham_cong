import type { AttendanceRecord } from './attendance'
import type { LeaveRequest } from './leave'
import type { OvertimeRequest } from './overtime'

export interface WeeklyStat {
  day: string
  count: number
}

export interface ShiftTimeStat {
  name: string
  count: number
}

export interface DashboardData {
  employeesCount: number
  shiftsCount: number
  activeShifts: number
  oldShifts: number
  unusedShifts: number
  overtimesCount: number
  approvedOvertimeRequests: number
  pendingOvertimeRequests: number
  attendancesCount: number
  lateAttendances: number
  pendingLeaveRequests: number
  approvedLeaveRequests: number
  overtimeRequestsCount: number
  weeklyStats: WeeklyStat[]
  shiftTimeStats: ShiftTimeStat[]
  recentOvertimeRequests: OvertimeRequest[]
  recentAttendances: AttendanceRecord[]
  recentLeaveRequests: LeaveRequest[]
}
