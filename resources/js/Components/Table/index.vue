<template>
  <div class="relative overflow-x-auto shadow-md sm:rounded-sm">
    <table class="w-full text-sm text-left text-neutral-500 dark:text-neutral-400">
      <thead
        class="text-xs uppercase text-neutral-700 bg-neutral-50 dark:bg-neutral-700 dark:text-neutral-400">
        <tr>
          <th
            @click="emitSort(head.key)"
            class="px-6 py-3"
            scope="col"
            v-for="head in th"
            :key="head.name">
            <span :class="`mr-2 cursor-pointer ${sort === head.key ? 'text-anzac-400' : ''}`">{{
              head.name
            }}</span>
            <span
              v-if="sort === head.key"
              :class="`mdi text-lg ${order === 'asc' ? 'mdi-menu-up' : 'mdi-menu-down'}`" />
            <span v-else class="text-lg mdi mdi-menu-swap" />
          </th>
        </tr>
      </thead>
      <slot />
    </table>
  </div>
</template>

<script lang="ts" setup>
import { toRefs } from 'vue'

interface Th {
  name: string
  key: string
}

interface Props {
  th: Array<Th>
  sort?: string
  order?: string
}

const props = defineProps<Props>()
const { sort, order } = toRefs(props)

const emit = defineEmits(['onSort'])

const emitSort = (column: string) => {
  emit('onSort', { column })
}
</script>
