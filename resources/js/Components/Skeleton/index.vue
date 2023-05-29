<template>
  <div role="status" class="w-full h-2">
    <div class="line"></div>
    <span class="sr-only">Loading...</span>
  </div>
</template>
<script lang="ts" setup></script>
<style lang="scss" scoped>
.line {
  @apply float-left w-full h-4 mt-4 rounded-md bg-gradient-to-r from-neutral-700 via-neutral-600 to-neutral-700;

  //background-image: linear-gradient(90deg, $base-color 0px, $shine-color 40px, $base-color 80px);
  background-size: 600px;
  animation: shine-lines 2s infinite;
}

@keyframes shine-lines {
  0% {
    background-position: 100%;
  }
  100% {
    background-position: -100%;
  }
}
</style>
