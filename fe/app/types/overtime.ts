export interface OvertimeShift {
  id: number
  name: string
  start_time: string
  end_time: string
  date: string
  max_registrations: number
  registration_count: number
  is_registered?: boolean
}

export interface OvertimeRequest {
  id: number
  employee_name: string
  shift_name: string
  shift_date: string
  status: 'pending' | 'approved' | 'rejected'
}

export interface OvertimeShiftInput {
  name: string
  date: string
  start_time: string
  end_time: string
  max_registrations: number
}
