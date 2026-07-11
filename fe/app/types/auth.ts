export interface User {
  id: number
  name: string
  email: string
  role: 'manager' | 'employee'
  avatar?: string
}

export interface UpdateProfileInput {
  name: string
}

export interface UpdatePasswordInput {
  current_password: string
  password: string
  password_confirmation: string
}
