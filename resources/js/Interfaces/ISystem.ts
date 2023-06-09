import { IFaction } from './IFaction'
import { IStar } from './IStar'
import { IPlanet } from './IPlanet'
import { IStation } from './IStation'

export interface ISystem {
  id: number
  address: number
  name: string
  population: number
  distance: number
  x: number
  y: number
  z: number
  economy: string
  second_economy: string
  government: string
  security: string
  allegiance: string
  powers: JSON
  pps: string
  permit: boolean
  faction: IFaction
  factions: IFaction[]
  stations: IStation[]
  stars?: IStar[]
  planets?: IPlanet[]
  updated_at: string
}
