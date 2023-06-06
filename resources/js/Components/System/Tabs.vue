<template>
  <div
    class="text-sm font-medium text-center border-b text-neutral-500 border-neutral-200 dark:text-neutral-400 dark:border-neutral-700">
    <ul class="flex flex-wrap -mb-px">
      <li class="mr-2">
        <a :href="route('systems.show', { name })" class="link active" aria-current="page"
          >Overview</a
        >
      </li>
      <li v-if="features.stations" class="mr-2">
        <a :href="route('systems.show.stations', { name })" class="link">Stations</a>
      </li>
      <li v-if="features.bodies" class="mr-2">
        <a :href="route('systems.show.bodies', { name })" class="link">Bodies</a>
      </li>
      <li v-if="features.map" class="mr-2">
        <a :href="route('systems.show.map', { name })" class="link">Map</a>
      </li>
    </ul>
  </div>
</template>
<script lang="ts" setup>
import { onMounted, reactive, inject } from 'vue'

import { growthBookKey } from '@/Utils'

const growthBookInjectable = inject(growthBookKey);

const props = defineProps({
  name: {
    type: String,
    required: true,
  },
})

const features = reactive({
  stations: false,
  bodies: false,
  map: false,
})

onMounted(() => {  
  growthBookInjectable?.init().then((growthBook) => {
    if (!growthBook) {
      console.error("GrowthBook failed to initialize");
      return;
    }
    const systemMap = growthBook.getFeatureValue("system_map_view", false);
    if (typeof systemMap !== "undefined") {
      features.map = systemMap;
    }

    const bodies = growthBook.getFeatureValue("system_bodies_view", false);
    if (typeof bodies !== "undefined") {
      features.bodies = bodies;
    }

    const stations = growthBook.getFeatureValue("system_stations_view", false);
    if (typeof stations !== "undefined") {
      features.stations = stations;
    }
  });
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