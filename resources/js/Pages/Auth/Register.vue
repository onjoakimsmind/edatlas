<script setup lang="ts">
import Default from '@/Layouts/Default.vue'
import { useForm } from '@inertiajs/vue3'

const form = useForm({
  username: '',
  email: '',
  password: '',
  password_confirmation: '',
})

const submit = () => {
  form.post(route('register'), {
    onFinish: () => form.reset('password', 'password_confirmation'),
  })
}
</script>

<template>
  <Default>
    <div
      class="flex w-full p-8 bg-white border rounded-sm shadow border-neutral-200 dark:bg-neutral-800 dark:border-neutral-700">
      <form @submit.prevent="submit" class="w-full">
        <div class="mb-6">
          <label for="email" class="block mb-2 text-sm font-medium text-neutral-900 dark:text-white"
            >Username</label
          >
          <input
            type="text"
            id="username"
            v-model="form.username"
            class="bg-neutral-50 border border-neutral-300 text-neutral-900 text-sm rounded-lg focus:ring-anzac-500 focus:border-anzac-500 block w-full p-2.5 dark:bg-neutral-700 dark:border-neutral-600 dark:placeholder-neutral-400 dark:text-white dark:focus:ring-anzac-500 dark:focus:border-anzac-500"
            placeholder="CMDR John Doe"
            required />
        </div>
        <div class="mb-6">
          <label for="email" class="block mb-2 text-sm font-medium text-neutral-900 dark:text-white"
            >Email address</label
          >
          <input
            type="email"
            id="email"
            v-model="form.email"
            class="bg-neutral-50 border border-neutral-300 text-neutral-900 text-sm rounded-lg focus:ring-anzac-500 focus:border-anzac-500 block w-full p-2.5 dark:bg-neutral-700 dark:border-neutral-600 dark:placeholder-neutral-400 dark:text-white dark:focus:ring-anzac-500 dark:focus:border-anzac-500"
            placeholder="john.doe@company.com"
            required />
        </div>
        <div class="mb-6">
          <label
            for="password"
            class="block mb-2 text-sm font-medium text-neutral-900 dark:text-white"
            >Password</label
          >
          <input
            type="password"
            id="password"
            v-model="form.password"
            class="bg-neutral-50 border border-neutral-300 text-neutral-900 text-sm rounded-lg focus:ring-anzac-500 focus:border-anzac-500 block w-full p-2.5 dark:bg-neutral-700 dark:border-neutral-600 dark:placeholder-neutral-400 dark:text-white dark:focus:ring-anzac-500 dark:focus:border-anzac-500"
            placeholder="•••••••••"
            required />
        </div>
        <div class="mb-6">
          <label
            for="confirm_password"
            class="block mb-2 text-sm font-medium text-neutral-900 dark:text-white"
            >Confirm password</label
          >
          <input
            type="password"
            id="confirm_password"
            v-model="form.password_confirmation"
            class="bg-neutral-50 border border-neutral-300 text-neutral-900 text-sm rounded-lg focus:ring-anzac-500 focus:border-anzac-500 block w-full p-2.5 dark:bg-neutral-700 dark:border-neutral-600 dark:placeholder-neutral-400 dark:text-white dark:focus:ring-anzac-500 dark:focus:border-anzac-500"
            placeholder="•••••••••"
            required />
        </div>
        <div class="flex items-start mb-6">
          <div class="flex items-center h-5">
            <input
              id="remember"
              type="checkbox"
              value=""
              class="w-4 h-4 border rounded border-neutral-300 bg-neutral-50 focus:ring-3 focus:ring-anzac-300 dark:bg-neutral-700 dark:border-neutral-600 dark:focus:ring-anzac-600 dark:ring-offset-neutral-800"
              required />
          </div>
          <label
            for="remember"
            class="ml-2 text-sm font-medium text-neutral-900 dark:text-neutral-300"
            >I agree with the
            <a href="#" class="text-anzac-600 hover:underline dark:text-anzac-500"
              >terms and conditions</a
            >.</label
          >
        </div>
        <button
          type="submit"
          class="text-white bg-anzac-700 hover:bg-anzac-800 focus:ring-4 focus:outline-none focus:ring-anzac-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-anzac-600 dark:hover:bg-anzac-700 dark:focus:ring-anzac-800">
          Sign Up
        </button>
      </form>
    </div>
  </Default>
</template>
