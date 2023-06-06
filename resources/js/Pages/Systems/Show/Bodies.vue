<template>
  <Default :title="`System - ${name}`">
    <div v-if="system">
      <span class="text-3xl font-bold uppercase text-anzac-500">System</span>
      <div
        class="text-sm font-medium text-center border-b text-neutral-500 border-neutral-200 dark:text-neutral-400 dark:border-neutral-700">
        <ul class="flex flex-wrap -mb-px">
          <li class="mr-2">
            <a :href="route('systems.show', { name })" class="link" aria-current="page">Overview</a>
          </li>
          <li class="mr-2">
            <a :href="route('systems.show.stations', { name })" class="link">Stations</a>
          </li>
          <li class="mr-2">
            <a :href="route('systems.show.bodies', { name })" class="link active">Bodies</a>
          </li>
          <li class="mr-2">
            <a :href="route('systems.show.map', { name })" class="link">Map</a>
          </li>
        </ul>
      </div>
      <div
        class="flex w-full bg-white border rounded-sm shadow border-neutral-200 dark:bg-neutral-800 dark:border-neutral-700">
        <div class="flex flex-col w-full p-4 leading-normal">
          <div class="flex items-center justify-between">
            <h5 class="mb-2 text-3xl font-bold tracking-tight text-anzac-500 dark:text-anzac-500">
              {{ name }}
            </h5>
          </div>
          <div class="flex w-full mt-6 text-neutral-400">
            <div class="flex w-1/2">
              <div class="flex flex-col w-full">
                <Info>
                  <template #key>
                    <span>Economy</span>
                  </template>
                  <template #value>
                    <span>{{ system.economy }}</span> /
                    <span>{{ system.second_economy }}</span>
                  </template>
                </Info>
                <Info>
                  <template #key>
                    <span>Government</span>
                  </template>
                  <template #value>
                    <span>{{ system.government }}</span>
                  </template>
                </Info>
                <Info>
                  <template #key>
                    <span>Allegiance</span>
                  </template>
                  <template #value>
                    <span>{{ system.allegiance }}</span>
                  </template>
                </Info>
              </div>
            </div>
            <div class="flex w-1/2">
              <div class="flex flex-col w-full">
                <Info>
                  <template #key>
                    <span>Star type</span>
                  </template>
                  <template #value>
                    <span>
                      Class {{ primaryStar?.type }} star
                      <span
                        v-if="primaryStar?.is_scoopable"
                        class="ml-2 bg-neutral-100 text-neutral-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded dark:bg-neutral-700 dark:text-neutral-300"
                        >Scoopable</span
                      >
                    </span>
                  </template>
                </Info>
                <Info>
                  <template #key>
                    <span>Coordinates</span>
                  </template>
                  <template #value>
                    <span>{{ system.x }} / {{ system.y }} / {{ system.z }}</span>
                  </template>
                </Info>
                <Info>
                  <template #key>
                    <span>Permit Required</span>
                  </template>
                  <template #value>
                    <span v-if="system.permit" class="text-red-500">Yes</span>
                    <span v-else>No</span>
                  </template>
                </Info>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div
        class="flex w-full mt-6 bg-white border rounded-sm shadow border-neutral-200 dark:bg-neutral-800 dark:border-neutral-700">
        <div class="flex flex-col w-full p-4 leading-normal">
          <div class="flex items-center">
            <h5 class="mb-2 text-xl font-bold tracking-tight text-anzac-500 dark:text-anzac-500">
              {{ name }}
              <span class="text-sm  text-anzac-300">
              - Class {{ primaryStar?.type }} star
              </span>
            </h5>
          </div>
          <div class="w-full ">
            <div class="w-1/2 text-neutral-800 dark:text-neutral-400">
            <Info>
              <template #key>
                <span>Permit Required</span>
              </template>
              <template #value>
                <span v-if="system.permit" class="text-red-500">Yes</span>
                <span v-else>No</span>
              </template>
            </Info>
          </div>
          </div>
        </div>
      </div>
    </div>
  </Default>
</template>
<script setup lang="ts">
import { onMounted, ref, Ref, computed } from 'vue'
import { initTooltips } from 'flowbite'
import Default from '@/Layouts/Default.vue'
import Info from '@/Components/System/Info.vue'

import System from '@/Apis/System'

import { toLocaleString, DateFormat } from '@/Utils'

import { IStar } from '@/Interfaces'

interface Props {
  name: string
}

const props: Props = defineProps({ name: { type: String, required: true } })

const system: Ref<any> = ref(null)

onMounted(async () => {
  initTooltips()
  const response = await System.get(props.name)
  console.log(response.data)
  system.value = response.data
})

const primaryStar = computed(() => {
  return system.value.stars?.find((star: IStar) => star.is_main_star)
})
</script>
<style lang="scss" scoped>
.link {
  @apply inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-neutral-600 hover:border-neutral-300 dark:hover:text-neutral-300;

  &.active {
    @apply text-anzac-600 border-anzac-600 dark:text-anzac-500 dark:border-anzac-500;
  }
}
</style>
