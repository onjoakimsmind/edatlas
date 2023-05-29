import { IRing } from '@/Interfaces/IRing'
export interface IStar {
  id: number
  system_id: number
  body_id: number
  name: string
  class: string
  absolute_magnitude: number
  age: number
  ascending_node: number
  distance_to_arrival: number
  eccentricity: number
  is_main_star: number
  is_scoopable: number
  luminosity: string
  mean_anomaly: number
  orbital_inclination: number
  orbital_period: number
  radius: number
  rotation_period: number
  semi_major_axis: number
  stellar_mass: number
  surface_temperature: number
  type: string
  periapsis: number
  parents: string
  updated_at: string
  rings?: IRing[]
}
