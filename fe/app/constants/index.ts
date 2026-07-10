export type BadgeColor = 'success' | 'warning' | 'error' | 'info' | 'primary' | 'secondary' | 'neutral'

// ---- Roles ----
export const ROLE = {
  MANAGER: 'manager',
  EMPLOYEE: 'employee',
} as const

export const ROLE_LABEL: Record<string, string> = {
  manager: 'Quản lý',
  employee: 'Nhân viên',
}

export const ROLE_BADGE: Record<string, BadgeColor> = {
  manager: 'info',
  employee: 'success',
}

// ---- Request status (leave / overtime) ----
export const REQUEST_STATUS = {
  PENDING: 'pending',
  APPROVED: 'approved',
  REJECTED: 'rejected',
} as const

export const REQUEST_STATUS_LABEL: Record<string, string> = {
  pending: 'Chờ duyệt',
  approved: 'Đã duyệt',
  rejected: 'Đã từ chối',
}

export const REQUEST_STATUS_BADGE: Record<string, BadgeColor> = {
  pending: 'warning',
  approved: 'success',
  rejected: 'error',
}

// ---- Attendance status ----
export const ATTENDANCE_STATUS = {
  PRESENT: 'present',
  LATE: 'late',
  ABSENT: 'absent',
} as const

export const ATTENDANCE_STATUS_LABEL: Record<string, string> = {
  present: 'Có mặt',
  late: 'Đi trễ',
  absent: 'Vắng mặt',
  leave: 'Nghỉ phép',
}

export const ATTENDANCE_STATUS_BADGE: Record<string, BadgeColor> = {
  present: 'success',
  late: 'warning',
  absent: 'error',
  leave: 'info',
}

// ---- Shift status ----
export const SHIFT_STATUS = {
  ACTIVE: 'active',
  INACTIVE: 'inactive',
} as const

export const SHIFT_STATUS_LABEL: Record<string, string> = {
  active: 'Đang dùng',
  inactive: 'Ngừng dùng',
}

export const SHIFT_STATUS_BADGE: Record<string, BadgeColor> = {
  active: 'success',
  inactive: 'error',
}

// ---- Leave types ----
export const LEAVE_TYPE = {
  ANNUAL: 'annual',
  SICK: 'sick',
  FAMILY: 'family',
  OTHER: 'other',
} as const

export const LEAVE_TYPE_LABEL: Record<string, string> = {
  annual: 'Nghỉ phép năm',
  sick: 'Nghỉ bệnh',
  family: 'Nghỉ gia đình',
  other: 'Khác',
}

// ---- Work summary status ----
export const WORK_SUMMARY_STATUS_LABEL: Record<string, string> = {
  complete: 'Hoàn thành',
  completed: 'Hoàn thành',
  pending: 'Chờ xác nhận',
  processing: 'Đang xử lý',
}

export const WORK_SUMMARY_STATUS_BADGE: Record<string, BadgeColor> = {
  complete: 'success',
  completed: 'success',
  pending: 'warning',
  processing: 'info',
}

// ---- Location status ----
export const LOCATION_STATUS_LABEL: Record<string, string> = {
  active: 'Đang hoạt động',
  inactive: 'Tạm dừng',
}

export const LOCATION_STATUS_BADGE: Record<string, BadgeColor> = {
  active: 'success',
  inactive: 'warning',
}
