export interface Location {
  id: number
  name: string
  address: string
  latitude: number
  longitude: number
  radius: number
  description?: string
  is_active: boolean
}

export interface LocationInput {
  name: string
  address: string
  latitude: number
  longitude: number
  radius: number
  description?: string
}
