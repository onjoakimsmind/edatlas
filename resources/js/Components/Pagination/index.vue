<template>
  <div class="flex flex-col items-center">
    <span class="mb-6 text-sm text-neutral-700 dark:text-neutral-400">
      Showing <span class="font-semibold text-neutral-900 dark:text-white">{{ meta.from }}</span> to
      <span class="font-semibold text-neutral-900 dark:text-white">{{ meta.to }}</span> of
      <span class="font-semibold text-neutral-900 dark:text-white">{{ meta.total }}</span> Entries
    </span>
    <nav>
      <ul class="inline-flex items-center">
        <li>
          <Link
            :href="route('systems.index', { page: previous() })"
            class="block px-3 py-2 ml-0 leading-tight bg-white border rounded-l-sm text-neutral-500 border-neutral-300 hover:bg-neutral-100 hover:text-neutral-700 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-white">
            <span class="sr-only">Previous</span>
            <svg
              aria-hidden="true"
              class="w-5 h-5"
              fill="currentColor"
              viewBox="0 0 20 20"
              xmlns="http://www.w3.org/2000/svg">
              <path
                fill-rule="evenodd"
                d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                clip-rule="evenodd"></path>
            </svg>
          </Link>
        </li>
        <div v-if="meta.current_page > 5" class="inline-flex height-38">
          <li>
            <Link
              :href="route('systems.index', { page: 1 })"
              class="px-3 py-2 leading-tight border border-neutral-300 hover:bg-neutral-100 hover:text-neutral-700 dark:border-neutral-700 dark:hover:bg-neutral-700 dark:hover:text-white inActive">
              1
            </Link>
          </li>
          <li>
            <a
              class="px-3 py-2 leading-tight border border-neutral-300 hover:bg-neutral-100 hover:text-neutral-700 dark:border-neutral-700 dark:hover:bg-neutral-700 dark:hover:text-white inActive">
              ...
            </a>
          </li>
        </div>
        <div v-for="page in pages" :key="page" class="height-38">
          <li>
            <Link
              :href="route('systems.index', { page: page })"
              class="px-3 py-2 leading-tight border border-neutral-300 hover:bg-neutral-100 hover:text-neutral-700 dark:border-neutral-700 dark:hover:bg-neutral-700 dark:hover:text-white"
              :class="{
                active: isActive(page),
                inActive: !isActive(page),
              }">
              {{ page }}
            </Link>
          </li>
        </div>
        <div v-if="lastPage - meta.current_page > 4" class="inline-flex items-center height-38">
          <li>
            <a
              class="px-3 py-2 leading-tight border border-neutral-300 hover:bg-neutral-100 hover:text-neutral-700 dark:border-neutral-700 dark:hover:bg-neutral-700 dark:hover:text-white inActive">
              ...
            </a>
          </li>
          <li>
            <Link
              :href="route('systems.index', { page: lastPage })"
              class="px-3 py-2 leading-tight border border-neutral-300 hover:bg-neutral-100 hover:text-neutral-700 dark:border-neutral-700 dark:hover:bg-neutral-700 dark:hover:text-white inActive">
              {{ lastPage }}
            </Link>
          </li>
        </div>
        <li>
          <Link
            :href="
              route('systems.index', {
                page: next(),
              })
            "
            class="block px-3 py-2 leading-tight bg-white border rounded-r-sm text-neutral-500 border-neutral-300 hover:bg-neutral-100 hover:text-neutral-700 dark:bg-neutral-800 dark:border-neutral-700 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:hover:text-white">
            <span class="sr-only">Next</span>
            <svg
              aria-hidden="true"
              class="w-5 h-5"
              fill="currentColor"
              viewBox="0 0 20 20"
              xmlns="http://www.w3.org/2000/svg">
              <path
                fill-rule="evenodd"
                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                clip-rule="evenodd"></path>
            </svg>
          </Link>
        </li>
      </ul>
    </nav>
  </div>
</template>
<script lang="ts" setup>
import { ref, onMounted } from 'vue'
import { Link } from '@inertiajs/vue3'

const pages = ref<number[]>([])

const props = defineProps({
  meta: {
    type: Object,
    required: true,
  },
  lastPage: {
    type: Number,
    default: 1,
  },
})

const max = 4 //isMobile ? 2 : 4

onMounted(() => {
  pageGenerator()
})

const isActive = (page: number) => {
  return page === Number(props.meta.current_page)
}

const pageGenerator = () => {
  const tmpPages = []
  const maxSides = max
  const left = Number(props.meta.current_page) - maxSides
  const right =
    Number(props.meta.current_page) + maxSides > Number(props.meta.current_page)
      ? props.lastPage
      : Number(props.meta.current_page) + maxSides
  const minLeft = Number(props.meta.current_page) >= props.lastPage ? props.lastPage - 9 : left
  const maxRight = right >= 10 ? right + 1 : 10
  const maxCount =
    Number(props.meta.current_page) + maxSides < Number(props.meta.current_page)
      ? props.lastPage
      : Number(props.meta.current_page) + maxSides
  for (let i = Number(props.meta.current_page) - maxSides; i <= maxCount; i++) {
    if (i < 1) {
      continue
    }

    if (i > props.lastPage) {
      continue
    }

    if (i >= minLeft && i <= maxRight) {
      tmpPages.push(i)
    }
  }
  pages.value = tmpPages
}

const previous = () => {
  if (Number(props.meta.current_page) - 1 < 1) {
    return Number(props.meta.current_page)
  }
  return Number(props.meta.current_page) - 1
}

const next = () => {
  if (Number(props.meta.current_page) + 1 > props.lastPage) {
    return Number(props.meta.current_page)
  }
  return Number(props.meta.current_page) + 1
}
</script>

<style lang="scss" scoped>
.active {
  @apply bg-neutral-100 text-neutral-700 dark:bg-neutral-700 dark:text-anzac-500 hover:text-neutral-700 dark:hover:text-white;
}

.inActive {
  @apply bg-white text-neutral-500 dark:bg-neutral-800 dark:text-neutral-400 hover:text-neutral-700 dark:hover:text-white;
}

.height-38 {
  @apply h-full;
}
</style>
