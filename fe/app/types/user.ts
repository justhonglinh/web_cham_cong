export interface User {
  id: number
  name: string
  email: string
  phone?: string
  role: 'manager' | 'employee'
  status?: 'active' | 'inactive'
}

export interface CreateUserInput {
  name: string
  email: string
  phone?: string
  role: 'manager' | 'employee'
  password: string
}

export interface UpdateUserInput {
  name?: string
  email?: string
  phone?: string
  password?: string
}
