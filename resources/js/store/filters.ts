import { defineStore } from 'pinia'

interface Filter {
  id: string
  value: string
  error?: boolean
}

export const useFilters = defineStore('filters', {
  state: () => {
    return {
      systems: [] as Filter[],
      stations: [] as Filter[],
    } as Record<string, Filter[]>
  },
  getters: {
    get: (state) => (key: string) => state[key],
    getById: (state) => (key: string, id: string) => state[key].find((f: Filter) => f.id === id),
    getByValue: (state) => (key: string, value: string) =>
      state[key].find((f: Filter) => f.value === value),
  },
  actions: {
    updateFilter(key: string, filter: Filter): void {
      const index = this[key].findIndex((f: Filter) => f.id === filter.id)
      //this.filters.splice(index, 1, filter)
      this[key][index] = { ...this[key][index], error: true }
    },
    addFilter(key: string, filter: Filter): void {
      this[key].push(filter)
    },
    removeFilter(key: string, id: string): void {
      const index = this[key].findIndex((filter: Filter) => filter.id === id)
      this[key].splice(index, 1)
    },
  },
  persist: {
    storage: localStorage,
  },
})
