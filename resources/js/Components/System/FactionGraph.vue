<template>
    <div class="w-full mt-2">
      <canvas ref="history" class="block w-full" id="history" />
    </div>
</template>
<script lang="ts" setup>
import { onMounted, ref } from 'vue'
import Chart from 'chart.js/auto'
import 'chartjs-adapter-moment';
import zoomPlugin from 'chartjs-plugin-zoom';

import { SystemsApi } from '@/Apis'

import { IFaction } from '@/Interfaces'

const props = defineProps({
  system: {
    type: String,
    required: true,
  },
})

Chart.register(zoomPlugin);

const history = ref(null)
const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']

onMounted(async () => {
  const response = await SystemsApi.factions(props.system)
  const allDates = response[0].history.map((row :any) => {
        const date = new Date(row.updated_at)
        return `${months[date.getMonth()]} ${date.getDate()}`
      }).filter((item: any, i: any, ar: any) => ar.indexOf(item) === i)
  const dataset = response.map((faction: IFaction) => {
        return {
          label: faction.name,
          data: faction.history?.map((row: any) => {
            const date = new Date(row.updated_at)
            const key = date.getTime()
            return {
              x: key,
              y: row.influence * 100
            }
          }),
        }
      });
      
  new Chart(history.value!, {
    type: 'line',
    data: {
      datasets: dataset,
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        zoom: {
          pan: {
            enabled: true,
            mode: 'x',
            threshold: 10,
          },
          zoom: {
            mode: 'x',
          },
        },
      },
      scales: {
        x: {
          type: 'time',
          time: {
            unit: 'day',
            displayFormats: {
              month: 'MMM DD',
            },
          },
          ticks: {
            source: 'auto',
          },
        },
        y: {
          min: 0,
          max: 100,
          ticks: {
            callback: (value: any) => `${value}%`,
          },
        },
      },
    }
  })
})
</script>
