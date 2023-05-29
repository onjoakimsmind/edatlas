<template>
  <Default :title="`System - ${name}`">
    <div v-if="system && !loading">
      <span class="text-3xl font-bold uppercase text-anzac-500">System</span>
      <div
        class="text-sm font-medium text-center border-b text-neutral-500 border-neutral-200 dark:text-neutral-400 dark:border-neutral-700">
        <ul class="flex flex-wrap -mb-px">
          <li class="mr-2">
            <a :href="route('systems.show', { name })" class="link active" aria-current="page"
              >Overview</a
            >
          </li>
          <li class="mr-2">
            <a :href="route('systems.show.stations', { name })" class="link">Stations</a>
          </li>
          <li class="mr-2">
            <a :href="route('systems.show.bodies', { name })" class="link">Bodies</a>
          </li>
          <li class="mr-2">
            <a :href="route('systems.show.map', { name })" class="link">Map</a>
          </li>
        </ul>
      </div>
      <div
        class="flex w-full bg-white border rounded-sm shadow h-96 border-neutral-200 dark:bg-neutral-800 dark:border-neutral-700">
        <div
          class="w-32 h-full bg-right bg-cover"
          style="background-image: url('/storage/Sun_sol.webp')"></div>
        <div class="flex flex-col w-full p-4 leading-normal">
          <div class="flex items-center justify-between">
            <h5 class="mb-2 text-3xl font-bold tracking-tight text-anzac-500 dark:text-anzac-500">
              {{ name }}
            </h5>
            <div class="flex items-center">
              <div v-if="$page.props.auth.user">
                <a
                  :href="route('systems.show.edit', { name })"
                  type="button"
                  data-tooltip-target="tooltip-edit"
                  data-tooltip-placement="bottom"
                  class="relative flex items-center justify-center h-8 p-2 text-sm font-medium text-center text-white uppercase rounded-sm cursor-pointer bg-river-bed-700 hover:bg-river-bed-800 focus:ring-4 focus:outline-none focus:ring-river-bed-300 dark:bg-river-bed-600 dark:hover:bg-river-bed-700 dark:focus:ring-river-bed-800">
                  <span class="mdi mdi-pencil"></span>
                  <span class="sr-only">Edit</span>
                </a>
                <div
                  id="tooltip-edit"
                  role="tooltip"
                  class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white rounded-lg shadow-sm opacity-0 bg-neutral-900 tooltip dark:bg-neutral-700">
                  Edit
                  <div class="tooltip-arrow" data-popper-arrow></div>
                </div>
              </div>
              <span
                type="button"
                data-tooltip-target="tooltip-set-location"
                data-tooltip-placement="bottom"
                @click="setLocation"
                class="relative flex items-center justify-center h-8 p-2 ml-2 text-sm font-medium text-center text-white uppercase rounded-sm cursor-pointer bg-river-bed-700 hover:bg-river-bed-800 focus:ring-4 focus:outline-none focus:ring-river-bed-300 dark:bg-river-bed-600 dark:hover:bg-river-bed-700 dark:focus:ring-river-bed-800">
                <span class="mdi mdi-map-marker-outline"></span>
                <span class="sr-only">Set Current Location</span>
              </span>
              <div
                id="tooltip-set-location"
                role="tooltip"
                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white rounded-lg shadow-sm opacity-0 bg-neutral-900 tooltip dark:bg-neutral-700">
                Set Current Location
                <div class="tooltip-arrow" data-popper-arrow></div>
              </div>
              <span
                type="button"
                data-tooltip-target="tooltip-favorite"
                data-tooltip-placement="bottom"
                @click="toggleFavorite"
                class="relative flex items-center justify-center w-8 h-8 p-2 ml-2 text-sm font-medium text-center text-white rounded-sm cursor-pointer hover:bg-anzac-800 focus:ring-4 focus:outline-none focus:ring-anzac-300 dark:hover:bg-anzac-700 dark:focus:ring-anzac-800"
                :class="isFavorite">
                <span class="mdi mdi-star"></span>
                <span class="sr-only">Notifications</span>
              </span>
              <div
                id="tooltip-favorite"
                role="tooltip"
                class="absolute z-10 invisible inline-block px-3 py-2 text-sm font-medium text-white rounded-lg shadow-sm opacity-0 bg-neutral-900 tooltip dark:bg-neutral-700">
                Add to Favorites
                <div class="tooltip-arrow" data-popper-arrow></div>
              </div>
            </div>
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
                <Info>
                  <template #key>
                    <span>Population</span>
                  </template>
                  <template #value>
                    <span>{{ toLocaleString(system.population) }}</span>
                  </template>
                </Info>
                <Info class="mt-12">
                  <template #key>
                    <span>Controlling Faction</span>
                  </template>
                  <template #value>
                    <a href="">{{ system.faction.name }}</a>
                  </template>
                </Info>
                <Info>
                  <template #key>
                    <span>Security</span>
                  </template>
                  <template #value>
                    <a href="">{{ system.security }}</a>
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
                <Info>
                  <template #key>
                    <span>Powerplay</span>
                  </template>
                  <template #value>
                    <span v-for="power in JSON.parse(system.powers.toString())" :key="power">{{
                      power
                    }}</span>
                  </template>
                </Info>
              </div>
            </div>
          </div>
          <div class="flex flex-col items-end justify-end w-full h-32">
            <div class="flex items-end justify-end text-xs">
              <span class="mr-5 text-neutral-400">Updated</span>
              <span class="text-neutral-300">{{ DateFormat.fromNow(system.updated_at) }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>
    {{ $page.props }}
  </Default>
</template>
<script setup lang="ts">
import { onMounted, ref, Ref, computed, nextTick } from 'vue'
import { initTooltips } from 'flowbite'
import { storeToRefs } from 'pinia'

import Default from '@/Layouts/Default.vue'
import Info from '@/Components/System/Info.vue'

import { SystemsApi } from '@/Apis'

import { ISystem } from '@/Interfaces'

import { toLocaleString, DateFormat } from '@/Utils'

import { useFavourites, useSettings, useSystem } from '@/store'

interface Props {
  name: string
}

const favourite = useFavourites()
const settings = useSettings()
const { systems } = storeToRefs(favourite)

const props: Props = defineProps({ name: { type: String, required: true } })

const system: Ref<ISystem> = ref({} as ISystem)
const loading = ref(true)

onMounted(() => {
  initTooltips()
  fetchSystem()
})

const isFavorite = computed(() => {
  const boolean = systems.value.some((system) => {
    return system.name === props.name
  })

  return {
    'bg-blue-300': boolean,
    'bg-anzac-500': !boolean,
    'dark:bg-blue-500': boolean,
    'dark:bg-anza-500': !boolean,
  }
})

const primaryStar = computed(() => {
  return system.value.stars?.find((star) => star.is_main_star)
})

const fetchSystem = async () => {
  const response = await SystemsApi.get(props.name)
  system.value = response.data
  loading.value = false
}

const toggleFavorite = () => {
  favourite.toggleSystem({ id: system.value.id, name: system.value.name })
}

const setLocation = () => {
  settings.setLocation(system.value.name)
}
</script>
<style lang="scss" scoped>
.link {
  @apply inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-neutral-600 hover:border-neutral-300 dark:hover:text-neutral-300;

  &.active {
    @apply text-anzac-600 border-anzac-600 dark:text-anzac-500 dark:border-anzac-500;
  }
}
</style>
