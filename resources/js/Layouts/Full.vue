<template>
  <Head :title="title" />
  <Header />
  <div id="full" class="relative w-full">
    <slot />
    <div class="fixed bottom-4 right-4">
      <Toast
        v-if="toasts._toasts.value.length > 0"
        v-for="toast in toasts._toasts.value"
        :key="toast.id"
        :id="toast.id"
        :title="toast.title"
        :message="toast.message"
        :type="toast.type" />
    </div>
  </div>
</template>
<script lang="ts" setup>
import { watch, ref, Ref } from 'vue'
import { Head } from '@inertiajs/vue3'
import { storeToRefs } from 'pinia'

import Header from '@/Components/Header/index.vue'
import Toast from '@/Components/Toast/index.vue'
import { useSettings } from '@/store/settings'
import { useToasts } from '@/store/toasts'

interface Toast {
  id?: string
  type: string
  title: string
  message: string
  duration?: number
}

const store = useSettings()

const toastStore = useToasts()

const htmlDOM: HTMLElement = document.getElementsByTagName('html')[0]

const toasts = storeToRefs(toastStore)

watch(store, (newVal) => {
  if (newVal.dark) {
    htmlDOM.classList.add('dark')
  } else {
    htmlDOM.classList.remove('dark')
  }
})

const props = defineProps({
  title: {
    type: String,
    default: 'Systems',
  },
})
</script>
