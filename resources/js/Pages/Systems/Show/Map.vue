<template>
  <Full :title="`System - ${name} - Map`">
    <div class="w-1/2 mx-auto">
      <ul
        class="hidden text-sm font-medium text-center divide-x rounded-b-lg shadow text-neutral-500 divide-neutral-200 sm:flex dark:divide-neutral-600 dark:text-neutral-400">
        <li class="w-full">
          <a
            :href="route('systems.show', name)"
            class="inline-block w-full p-4 bg-white hover:text-neutral-600 hover:bg-neutral-50 focus:outline-none dark:hover:text-white dark:bg-neutral-700 dark:hover:bg-neutral-600"
            >Overview</a
          >
        </li>
        <li class="w-full">
          <a
            :href="route('systems.show.stations', name)"
            class="inline-block w-full p-4 bg-white hover:text-neutral-600 hover:bg-neutral-50 focus:outline-none dark:hover:text-white dark:bg-neutral-700 dark:hover:bg-neutral-600"
            >Station</a
          >
        </li>
        <li class="w-full">
          <a
            :href="route('systems.show.bodies', name)"
            class="inline-block w-full p-4 bg-white hover:text-neutral-600 hover:bg-neutral-50 focus:outline-none dark:hover:text-white dark:bg-neutral-700 dark:hover:bg-neutral-600"
            >Bodies</a
          >
        </li>
        <li class="w-full">
          <a
            :href="route('systems.show.map', name)"
            class="inline-block w-full p-4 bg-white rounded-br-lg hover:text-neutral-600 hover:bg-neutral-50 focus:outline-none dark:hover:text-white dark:bg-neutral-700 dark:hover:bg-neutral-600"
            >Map</a
          >
        </li>
      </ul>
    </div>
  </Full>
</template>
<script lang="ts" setup>
import Full from '@/Layouts/Full.vue'
import { onMounted, ref, Ref } from 'vue'

import System from '@/Apis/System'

interface Props {
  name: string
}

const props: Props = defineProps({ name: { type: String, required: true } })

const system: Ref<any> = ref(null)

onMounted(async () => {
  const response = await System.get(props.name)
  console.log(response.data)
  system.value = response.data
})
</script>
