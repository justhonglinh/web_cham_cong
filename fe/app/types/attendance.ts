export interface AttendanceRecord {
  id: number
  employee_name?: string
  employee_id?: number
  date: string
  check_in: string | null
  check_out: string | null
  shift_name: string | null
  status: 'present' | 'late' | 'absent' | string
}

export interface PaginatedAttendance {
  data: AttendanceRecord[]
  current_page: number
  last_page: number
  per_page: number
  total: number
}

export interface UpdateAttendanceInput {
  check_in?: string | null
  check_out?: string | null
}
