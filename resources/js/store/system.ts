import { defineStore } from 'pinia'

import { ISystem } from '@/Interfaces'

export const useSystem = defineStore('system', {
  state: () => {
    return {
      system: {} as ISystem,
    } as { system: ISystem }
  },
  getters: {
    get: (state) => state.system,
  },
  actions: {
    setSystem(system: ISystem): void {
      this.system = system
    },
  },
  persist: {
    storage: localStorage,
  },
})
