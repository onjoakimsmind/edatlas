<template>
  <div
    :id="id"
    class="flex-col w-full max-w-xs mb-4 bg-white rounded-lg shadow text-neutral-500 dark:text-neutral-400 dark:bg-neutral-900"
    role="alert">
    <div class="flex items-center p-4">
      <div
        class="inline-flex items-center justify-center flex-shrink-0 w-8 h-8 text-red-500 bg-red-100 rounded-lg dark:bg-red-800 dark:text-red-200">
        <svg
          aria-hidden="true"
          class="w-5 h-5"
          fill="currentColor"
          viewBox="0 0 20 20"
          xmlns="http://www.w3.org/2000/svg">
          <path
            fill-rule="evenodd"
            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
            clip-rule="evenodd"></path>
        </svg>
        <span class="sr-only">Error icon</span>
      </div>
      <div class="flex-col">
        <div class="ml-3 mr-6 text-sm font-bold">{{ title }}</div>
        <div class="ml-3 mr-6 text-sm font-normal">{{ message }}</div>
      </div>
      <button
        v-if="dismissable"
        @click="dismiss(id)"
        type="button"
        class="ml-auto -mx-1.5 -my-1.5 bg-white text-neutral-400 hover:text-neutral-900 rounded-lg focus:ring-2 focus:ring-neutral-300 p-1.5 hover:bg-neutral-100 inline-flex h-8 w-8 dark:text-neutral-500 dark:hover:text-white dark:bg-neutral-800 dark:hover:bg-neutral-700"
        aria-label="Close">
        <span class="sr-only">Close</span>
        <svg
          aria-hidden="true"
          class="w-5 h-5"
          fill="currentColor"
          viewBox="0 0 20 20"
          xmlns="http://www.w3.org/2000/svg">
          <path
            fill-rule="evenodd"
            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
            clip-rule="evenodd"></path>
        </svg>
      </button>
    </div>
    <div style="width: 100%" class="h-1 rounded-b-lg progress bg-neutral-400 dark:bg-neutral-600" />
  </div>
</template>
<script lang="ts" setup>
import { onMounted, ref } from 'vue'

import { useToasts } from '@/store'

const store = useToasts()

const props = defineProps({
  id: { type: String, default: 0 },
  title: { type: String, required: true },
  message: { type: String, required: true },
  type: { type: String, required: true },
  dismissable: { type: Boolean, default: true },
  duration: { type: Number, default: 5000 },
})

onMounted(() => {
  setTimeout(() => {
    document.getElementById(props.id)?.remove()
    store.removeToast(props.id)
  }, props.duration)
})

const dismiss = (id: string) => {
  document.getElementById(id)?.remove()
  store.removeToast(props.id)
}
</script>
<style lang="scss" scoped>
.progress {
  animation: progressBar 4.9s ease-in-out;
  animation-fill-mode: both;
}

@keyframes progressBar {
  0% {
    width: 100%;
  }
  100% {
    width: 0;
  }
}
</style>
