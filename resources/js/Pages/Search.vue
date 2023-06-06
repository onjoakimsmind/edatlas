<template>
  <Default :title="`Search - ${q}`">
    <div class="relative mt-3 mb-6">
      <form @submit.prevent="submit">
        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
          <svg
            class="w-5 h-5 text-neutral-500"
            aria-hidden="true"
            fill="currentColor"
            viewBox="0 0 20 20"
            xmlns="http://www.w3.org/2000/svg">
            <path
              fill-rule="evenodd"
              d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
              clip-rule="evenodd"></path>
          </svg>
          <span class="sr-only">Search icon</span>
        </div>
        <input
          type="text"
          ref="searchInput"
          v-model="searchInput.q"
          id="search-navbar"
          class="block w-full p-2 pl-10 text-sm border rounded-sm text-neutral-900 border-neutral-300 bg-neutral-50 focus:ring-anzac-500 focus:border-anzac-500 dark:bg-neutral-700 dark:border-neutral-600 dark:placeholder-neutral-400 dark:text-white dark:focus:ring-anzac-500 dark:focus:border-anzac-500"
          placeholder="Search..." />
      </form>
    </div>
    <div v-if="systems.length">
      <h5 class="text-xl font-bold dark:text-white">Systems</h5>
      <div
        class="flex w-full bg-white border rounded-sm shadow border-neutral-200 dark:bg-neutral-700 dark:border-neutral-800">
        <div class="flex flex-wrap w-full p-4 leading-normal">
          <div class="w-1/3" v-for="system in systems">
            <div class="text-anzac-500">
              <a :href="route('systems.show', { name: system.name })">{{ system.name }}</a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div v-if="stations.length" class="mt-6">
      <h5 class="text-xl font-bold dark:text-white">Stations</h5>
      <div
        class="flex w-full bg-white border rounded-sm shadow border-neutral-200 dark:bg-neutral-700 dark:border-neutral-800">
        <div class="flex flex-wrap w-full p-4 leading-normal">
          <div class="w-1/3" v-for="station in stations">
            <div class="flex hover:text-neutral-600">
              <a :href="route('systems.show.stations', { name: station.system.name })" class="flex">
                <div class="mr-4 text-neutral-400">
                  {{ station.name }}
                </div>
                <div class="text-anzac-500">
                  {{ station.system.name }}
                </div>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div v-if="commodities.length" class="mt-6">
      <h5 class="text-xl font-bold dark:text-white">Commodities</h5>
      <div
        class="flex w-full bg-white border rounded-sm shadow border-neutral-200 dark:bg-neutral-700 dark:border-neutral-800">
        <div class="flex flex-wrap w-full p-4 leading-normal">
          <div class="w-1/3" v-for="commodity in commodities">
            <div class="text-anzac-500">
              {{ commodity.name }}
            </div>
          </div>
        </div>
      </div>
    </div>
    <div v-if="factions.length" class="mt-6">
      <div
        class="flex w-full bg-white border rounded-sm shadow border-neutral-200 dark:bg-neutral-700 dark:border-neutral-800">
        <div class="flex flex-wrap w-full p-4 leading-normal">
          <div class="w-1/3" v-for="faction in factions">
            <div class="text-anzac-500">
              {{ faction.name }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </Default>
</template>
<script lang="ts" setup>
import Default from '@/Layouts/Default.vue'
import { onMounted, ref, Ref, reactive } from 'vue'

import Search from '@/Apis/Search'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  q: {
    type: String,
    required: true,
  },
})

interface Station {
  name: string
  market_id: number
  system_id: number
  system: System
}

interface System {
  address: number
  name: string
}

interface Faction {
  name: string
  id: number
}

interface Commodity {
  name: string
}

const systems: Ref<System[]> = ref([])
const stations: Ref<Station[]> = ref([])
const factions: Ref<Faction[]> = ref([])
const commodities: Ref<Commodity[]> = ref([])

const searchInput = reactive({
  q: props.q,
})

onMounted(async () => {
  const response = await Search.search(props.q)
  systems.value = response.data.systems
  stations.value = response.data.stations
  factions.value = response.data.factions
  commodities.value = response.data.commodities
})

const submit = async () => {
  route('search', { q: searchInput.q })
}
</script>
