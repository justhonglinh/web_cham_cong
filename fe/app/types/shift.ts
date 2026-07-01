export interface Shift {
  id: number
  name: string
  start_time: string
  end_time: string
  status: 'active' | 'inactive'
  usage_count: number
}

export interface ShiftInput {
  name: string
  start_time: string
  end_time: string
}
