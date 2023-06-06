<template>
  <Default :title="`System - ${name}`">
    <div v-if="system && !loading">
      <span class="text-3xl font-bold uppercase text-anzac-500">System</span>
        <Tabs :name="name" />
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
              <div v-if="$page.props.auth.user && features.edit">
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
                <Info class="mt-12" v-if="system.faction">
                  <template #key>
                    <span>Controlling Faction</span>
                  </template>
                  <template #value>
                    <a :href="route('factions.show', { name: system.faction.name })">{{ system.faction.name }}</a>
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
                <Info v-if="system.powers">
                  <template #key>
                    <span>Powerplay</span>
                  </template>
                  <template #value>
                    <span v-for="power in JSON.parse(system.powers.toString())" :key="power">
                      <a href="">{{ power }}</a>
                    </span>
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
      <div v-if="system.stations.length && features.stations">
      <div class="mt-12">
        <span class="text-xl font-bold uppercase text-neutral-400">Stations</span>
      </div>
      <div>
        <div v-if="system.stations.length % 4 === 0" class="grid grid-cols-3 gap-4">
          <div v-for="station in system.stations">
            {{ station.name }}
          </div>
        </div>
        <div v-else-if="system.stations.length % 3 === 0" class="grid grid-cols-4 gap-2">
          <div v-for="station in 12" :key="station" class="station">
            <a :href="route('systems.show.station', { name: system.name, station: system.stations[station].name })" class="flex flex-col items-center justify-center">
            <span class="flex items-center"><img v-if="system.stations[station].type" :src="stationType(system.stations[station].type)" style="height: 12px;" class="mr-2">{{ system.stations[station].name }}</span>
            <span class="text-xs text-anzac-500">{{ system.stations[station].type }} ({{ system.stations[station].distance_to_arrival }} ls)</span>
            <span v-if="system.stations[station].faction" class="text-xs">{{ system.stations[station].faction.name }}</span>
            </a>
          </div>
        </div>
      </div>
      </div>
      <div v-if="system.factions.length && features.factions">
        <div class="mt-12">
          <span class="text-xl font-bold uppercase text-neutral-400">Minor factions</span>
        </div>
        <div>
          <Table :th="th">
            <tbody>
              <tr v-for="faction in system.factions" class="tr">
                <td scope="row" class="px-1 py-2 text-neutral-900 font-xs whitespace-nowrap dark:text-white ">
                  <a :href="route('factions.show', { name: faction.name })">{{ faction.name }}</a>
                </td>
                <td class="px-1 py-2">
                  {{ faction.government }}
                </td>
                <td class="px-1 py-2">
                  {{ faction.allegiance }}
                </td>
                <td class="px-1 py-2">
                  {{ Math.round(faction.influence * 100) }}
                </td>
              </tr>
            </tbody>
          </Table>
        </div>
        <div class="flex-col w-full mt-4 mb-12 bg-white border rounded-sm shadow border-neutral-200 dark:bg-neutral-800 dark:border-neutral-700">
          <FactionGraph :system="system.name" />
        </div>
      </div>
    </div>
  </Default>
</template>
<script setup lang="ts">
import { onMounted, ref, Ref, computed, reactive, inject } from 'vue'
import { initTooltips } from 'flowbite'
import { storeToRefs } from 'pinia'

import Default from '@/Layouts/Default.vue'
import Info from '@/Components/System/Info.vue'
import FactionGraph from '@/Components/System/FactionGraph.vue'
import Table from '@/Components/Table/index.vue'
import Tabs from '@/Components/System/Tabs.vue'

import AsteroidBase from '@/../images/icons/Asteroid_Station_Icon.svg'
import Coriolis from '@/../images/icons/Coriolis.svg'
import Ocellus from '@/../images/icons/Ocellus.svg'
import Orbis from '@/../images/icons/Orbis.svg'
import Outpost from '@/../images/icons/Outpost.svg'
import MegaShip from '@/../images/icons/Mega-Ship_Icon.svg'
import Settlement from '@/../images/icons/settlement_pm.svg'
import SurfacePort from '@/../images/icons/surface_port.svg'


import { SystemsApi } from '@/Apis'

import { ISystem } from '@/Interfaces'

import { toLocaleString, DateFormat, growthBookKey } from '@/Utils'

import { useFavourites, useSettings } from '@/store'

interface Props {
  name: string
}

const th = [
  { key: 'name', name: 'Name' },
  { key: 'government', name: 'Government' },
  { key: 'allegiance', name: 'Allegiance' },
  { key: 'influence', name: 'InFluence' },
]

const favourite = useFavourites()
const settings = useSettings()
const { systems } = storeToRefs(favourite)

const props: Props = defineProps({ name: { type: String, required: true } })

const system: Ref<ISystem> = ref({} as ISystem)
const loading = ref(true)

const features = reactive({
  stations: false,
  edit: false,
  factions: false,
})

const growthBookInjectable = inject(growthBookKey);

onMounted(() => {
  initTooltips()
  fetchSystem()
  
  growthBookInjectable?.init().then((growthBook) => {
    if (!growthBook) {
      console.error("GrowthBook failed to initialize");
      return;
    }
  const stations = growthBook.getFeatureValue("system_stations", false);
  if (typeof stations !== "undefined") {
    features.stations = stations;
  }

  const factions = growthBook.getFeatureValue("system_factions", false);
  if (typeof factions !== "undefined") {
    features.factions = factions;
  }

  const editBtn = growthBook.getFeatureValue("system_edit_btn", false);
  if (typeof editBtn !== "undefined") {
    features.edit = editBtn;
  }
});
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

const stationType = (stationType: string | null | undefined): string => {
  switch (stationType) {
    case 'Orbis Starport ':
      return Coriolis
    case 'Ocellus Starport':
      return Ocellus
    case 'Orbis Starport':
      return Orbis
    case 'Outpost':
      return Outpost
    case 'Settlement':
      return Settlement
    case 'Planetary Port':
    case 'Planetary Outpost':
    case 'Civilian Outpost':
      return SurfacePort
    case 'Asteroid Base':
      return AsteroidBase
    case 'Mega Ship':
      return MegaShip
    default:
      return ''
  }
}

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

.station {
  @apply flex flex-col items-center justify-center px-8 py-4 rounded-sm bg-neutral-700 text-neutral-300;
  &:hover {
    @apply bg-neutral-800;
  }
}

.tr {
  @apply bg-white dark:bg-neutral-800;

  &:hover {
    @apply bg-neutral-50 dark:bg-neutral-900;
  }

  & > td:first-child {
    @apply text-anzac-500;
    &:hover {
      @apply text-anzac-600 underline;
    }
  }

  &:nth-child(odd) {
    @apply bg-neutral-50 dark:bg-neutral-850;
    &:hover {
      @apply bg-neutral-50 dark:bg-neutral-900;
    }
  }
}
</style>
