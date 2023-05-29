import { defineStore } from 'pinia'

export const useSettings = defineStore('settings', {
  state: () => {
    return {
      _dark: true,
      _location: 'Sol',
    }
  },
  getters: {
    dark: (state) => state._dark,
    location: (state) => state._location,
  },
  actions: {
    toggleDark(dark: boolean) {
      return (this._dark = dark)
    },
    setLocation(system: string) {
      return (this._location = system)
    },
  },
  persist: {
    storage: localStorage,
  },
})
