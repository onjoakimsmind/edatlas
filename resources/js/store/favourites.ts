import { defineStore } from 'pinia'

interface Favorite {
  id: number
  name: string
}

export const useFavourites = defineStore('favourites', {
  state: () => {
    return {
      systems: [] as Favorite[],
      stations: [] as Favorite[],
    } as Record<string, Favorite[]>
  },
  getters: {
    getSystems(state): Favorite[] {
      return state.systems
    },
  },
  actions: {
    toggleSystem(system: Favorite) {
      const index = this.systems.findIndex((s) => s.id === system.id)
      if (index === -1) {
        this.systems.push(system)
      } else {
        this.systems.splice(index, 1)
      }
    },
  },
  persist: {
    storage: localStorage,
  },
})
