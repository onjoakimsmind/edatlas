<template>
  <nav class="bg-white border-neutral-200 dark:bg-neutral-800">
    <div class="flex flex-wrap items-center justify-between max-w-screen-xl p-4 mx-auto">
      <a href="https://flowbite.com/" class="flex items-center">
        <img :src="Icon" class="h-8 mr-3" alt="Flowbite Logo" />
        <span class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white"
          >ED Star Atlas</span
        >
      </a>
      <div class="flex items-center md:order-3">
        <div v-if="$page.props.auth.user">
          <button
            type="button"
            class="flex mr-3 text-sm bg-gray-800 rounded-full md:mr-0 focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600"
            id="user-menu-button"
            aria-expanded="false"
            data-dropdown-toggle="user-dropdown"
            data-dropdown-trigger="hover"
            data-dropdown-placement="bottom">
            <span class="sr-only">Open user menu</span>
            <img
              class="w-8 h-8 rounded-full"
              src="/docs/images/people/profile-picture-3.jpg"
              alt="user photo" />
          </button>
          <!-- Dropdown menu -->
          <div
            class="z-50 hidden w-48 my-4 text-base list-none bg-white divide-y divide-gray-100 rounded-lg shadow dark:bg-gray-700 dark:divide-gray-600"
            id="user-dropdown">
            <div class="px-4 py-3">
              <span class="block text-sm text-gray-900 dark:text-white">{{
                $page.props.auth.user.username
              }}</span>
            </div>
            <ul class="py-2" aria-labelledby="user-menu-button">
              <li>
                <a
                  href="#"
                  class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white"
                  >Dashboard</a
                >
              </li>
              <li>
                <a
                  href="#"
                  class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white"
                  >Settings</a
                >
              </li>
              <li>
                <Link
                  :href="route('logout')"
                  method="post"
                  as="button"
                  class="block w-full px-4 py-2 text-sm text-left text-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-gray-200 dark:hover:text-white"
                  >Sign out</Link
                >
              </li>
            </ul>
          </div>
        </div>
        <div v-else>
          <Link
            :href="route('login')"
            class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-gray-800 border border-transparent rounded-md shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600"
            >Sign In</Link
          >
        </div>
        <button
          data-collapse-toggle="mobile-menu-2"
          type="button"
          class="inline-flex items-center p-2 ml-1 text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
          aria-controls="mobile-menu-2"
          aria-expanded="false">
          <span class="sr-only">Open main menu</span>
          <svg
            class="w-6 h-6"
            aria-hidden="true"
            fill="currentColor"
            viewBox="0 0 20 20"
            xmlns="http://www.w3.org/2000/svg">
            <path
              fill-rule="evenodd"
              d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
              clip-rule="evenodd"></path>
          </svg>
        </button>
      </div>
      <div class="flex md:order-1">
        <button
          type="button"
          data-collapse-toggle="navbar-search"
          aria-controls="navbar-search"
          aria-expanded="false"
          class="md:hidden text-neutral-500 dark:text-neutral-400 hover:bg-neutral-100 dark:hover:bg-neutral-700 focus:outline-none focus:ring-4 focus:ring-neutral-200 dark:focus:ring-neutral-700 rounded-sm text-sm p-2.5 mr-1">
          <svg
            class="w-5 h-5"
            aria-hidden="true"
            fill="currentColor"
            viewBox="0 0 20 20"
            xmlns="http://www.w3.org/2000/svg">
            <path
              fill-rule="evenodd"
              d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
              clip-rule="evenodd"></path>
          </svg>
          <span class="sr-only">Search</span>
        </button>
        <div class="relative hidden md:block">
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
              id="search-navbar"
              v-model="form.searchQuery"
              class="block w-full p-2 pl-10 text-sm border rounded-sm text-neutral-900 border-neutral-300 bg-neutral-50 focus:ring-anzac-500 focus:border-anzac-500 dark:bg-neutral-700 dark:border-neutral-600 dark:placeholder-neutral-400 dark:text-white dark:focus:ring-anzac-500 dark:focus:border-anzac-500"
              placeholder="Search..." />
          </form>
        </div>
        <button
          data-collapse-toggle="navbar-search"
          type="button"
          class="inline-flex items-center p-2 text-sm rounded-sm text-neutral-500 md:hidden hover:bg-neutral-100 focus:outline-none focus:ring-2 focus:ring-neutral-200 dark:text-neutral-400 dark:hover:bg-neutral-700 dark:focus:ring-neutral-600"
          aria-controls="navbar-search"
          aria-expanded="false">
          <span class="sr-only">Open menu</span>
          <svg
            class="w-6 h-6"
            aria-hidden="true"
            fill="currentColor"
            viewBox="0 0 20 20"
            xmlns="http://www.w3.org/2000/svg">
            <path
              fill-rule="evenodd"
              d="M3 5a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 10a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 15a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z"
              clip-rule="evenodd"></path>
          </svg>
        </button>
      </div>
      <div
        class="items-center justify-between hidden w-full md:flex md:w-auto md:order-2"
        id="navbar-search">
        <div class="relative mt-3 md:hidden">
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
            </div>
            <input
              type="text"
              id="search-navbar"
              v-model="form.searchQuery"
              class="block w-full p-2 pl-10 text-sm border rounded-sm text-neutral-900 border-neutral-300 bg-neutral-50 focus:ring-anzac-500 focus:border-anzac-500 dark:bg-neutral-700 dark:border-neutral-600 dark:placeholder-neutral-400 dark:text-white dark:focus:ring-anzac-500 dark:focus:border-anzac-500"
              placeholder="Search..." />
          </form>
        </div>
        <ul
          class="flex p-4 mt-5 font-medium border rounded-sm flex-colh-full border-neutral-100 md:p-0 bg-neutral-50 md:flex-row md:space-x-8 md:mt-0 md:border-0 md:bg-white dark:bg-neutral-750 md:dark:bg-neutral-800 dark:border-neutral-700">
          <li>
            <a
              href="#"
              class="block py-2 pl-3 pr-4 text-white rounded bg-anzac-700 md:bg-transparent md:text-anzac-700 md:p-0 md:dark:text-anzac-500"
              aria-current="page"
              >Home</a
            >
          </li>
          <li>
            <a
              href="#"
              class="block py-2 pl-3 pr-4 rounded text-neutral-900 hover:bg-neutral-100 md:hover:bg-transparent md:hover:text-anzac-700 md:p-0 md:dark:hover:text-anzac-500 dark:text-white dark:hover:bg-neutral-700 dark:hover:text-white md:dark:hover:bg-transparent dark:border-neutral-700"
              >About</a
            >
          </li>
          <li>
            <button
              id="dropdownNavbarLink"
              data-dropdown-toggle="dropdownNavbar"
              data-dropdown-trigger="hover"
              class="flex items-center justify-between w-full py-2 pl-3 pr-4 border-b text-neutral-700 border-neutral-100 hover:bg-neutral-50 md:hover:bg-transparent md:border-0 md:hover:text-anzac-700 md:p-0 md:w-auto dark:text-white md:dark:hover:text-anzac-500 dark:focus:text-white dark:border-neutral-700 dark:hover:bg-neutral-700 md:dark:hover:bg-transparent">
              Data
              <svg
                class="w-5 h-5 ml-1"
                aria-hidden="true"
                fill="currentColor"
                viewBox="0 0 20 20"
                xmlns="http://www.w3.org/2000/svg">
                <path
                  fill-rule="evenodd"
                  d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                  clip-rule="evenodd"></path>
              </svg>
            </button>
            <!-- Dropdown menu -->
            <div
              id="dropdownNavbar"
              class="z-10 hidden w-1/3 font-normal bg-white divide-y rounded-sm shadow divide-neutral-100 dark:bg-neutral-700 dark:divide-neutral-600">
              <div class="flex p-6">
                <div class="w-1/3">Items here</div>
                <div class="w-2/3">Items here</div>
              </div>
            </div>
          </li>
          <li>
            <label class="relative inline-flex items-center cursor-pointer">
              <input
                @change="toggleDark"
                type="checkbox"
                value=""
                class="sr-only peer"
                :checked="store.dark" />
              <div
                class="w-11 h-6 bg-neutral-200 rounded-full peer peer-focus:ring-0 peer-focus:ring-anzac-300 dark:peer-focus:ring-anzac-800 dark:bg-neutral-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-neutral-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-neutral-600 peer-checked:bg-anzac-600"></div>
              <span class="mt-1 ml-3 text-sm font-medium text-neutral-900 dark:text-neutral-300"
                >Dark Mode</span
              >
            </label>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script lang="ts" setup>
import { onMounted, reactive, ref } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import { initDropdowns } from 'flowbite'
import Icon from '../../../images/Icon.svg'
import { useSettings } from '@/store/settings'
import { configCatClient } from '@/plugins'

const store = useSettings()

const form = reactive({
  searchQuery: null,
})

onMounted(() => {
  initDropdowns()
})

const toggleDark = () => {
  const isDark = store.dark
  store.toggleDark(!isDark)
}

const submit = () => {
  router.get(`/search/${form.searchQuery}`)
}
</script>
