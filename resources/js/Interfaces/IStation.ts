import { IFaction } from "./IFaction";

export interface IStation {
  id: number,
  market_id: number,
  system_id: number,
  name: string,
  type: string,
  distance_to_arrival: number,
  allegiance: string,
  government: string,
  economy: string,
  state: string,
  landing_pads: string,
  faction_id: number,
  faction: IFaction,
  updated_at: string,
}