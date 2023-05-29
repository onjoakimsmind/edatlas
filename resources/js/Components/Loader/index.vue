<template>
  <img :src="EDLoader" :style="`height: ${size}px`" alt="loading" />
</template>
<script type="ts" setup>
import EDLoader from "../../../images/EDloader1.svg"
const props = defineProps({
  size: {
    type: Number,
    default: 48,
  },
})
</script>
