interface History {
  influence: number
  date: string
}

export interface IFaction {
  id: number
  name: string
  allegiance: string
  government: string
  state: string
  influence: number
  happiness: string
  active_states: string | null
  pending_states: string | null
  recovering_states: string | null
  history?: History[]
  pivot?: {
    system_id: number
    faction_id: number
  }
  updated_at: string
}
