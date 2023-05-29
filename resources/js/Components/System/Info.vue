<template>
  <div class="flex w-full mt-1 mr-8">
    <div class="w-1/2 text-sm uppercase">
      <slot name="key" />
    </div>
    <div class="w-1/2 ml-8 text-sm text-neutral-300">
      <slot name="value" />
    </div>
  </div>
</template>
