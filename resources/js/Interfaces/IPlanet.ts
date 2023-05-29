import { IRing } from './IRing'

export interface IPlanet {
  id: number
  name: string
  system_id: number
  body_id: number
  ascending_node: number
  atmosphere_type: null
  atmosphere_composition: string
  axial_tilt: number
  composition: string
  class: string
  distance_to_arrival: number
  eccentricity: number
  gravity: number
  is_landable: number
  mass: number
  mean_anomaly: number
  orbital_inclination: number
  orbital_period: number
  radius: number
  rotation_period: number
  semi_major_axis: number
  surface_pressure: number
  surface_temperature: number
  tidally_locked: number
  terraforming_state: string
  volcanism: string
  parents: string
  updated_at: string
  rings?: IRing[]
}
