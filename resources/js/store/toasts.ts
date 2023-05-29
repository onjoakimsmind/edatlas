import { defineStore } from 'pinia'
import { v4 } from 'uuid'

interface Toast {
  id?: string
  type: string
  title: string
  message: string
  duration?: number
}

export const useToasts = defineStore('toasts', {
  state: () => {
    return {
      _toasts: [] as Toast[],
    } as Record<string, Toast[]>
  },
  getters: {
    get: (state) => state._toasts,
  },
  actions: {
    addToast(toast: Toast): void {
      if (toast.duration === undefined) toast.duration = 5000
      if (toast.id === undefined) toast.id = v4()
      this._toasts.push(toast)
    },
    removeToast(id: string): void {
      const index = this._toasts.findIndex((toast: Toast) => {
        return toast.id === id
      })
      this._toasts.splice(index, 1)
    },
  },
  persist: {
    storage: localStorage,
  },
})
