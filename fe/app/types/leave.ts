export interface LeaveRequest {
  id: number
  employee_name?: string
  leave_type: string
  start_date: string
  end_date: string
  days?: number
  reason: string
  status: 'pending' | 'approved' | 'rejected'
}

export interface CreateLeaveInput {
  leave_type: string
  start_date: string
  end_date: string
  reason: string
}
