<template>
  <div
    ref="container"
    class="absolute flex items-center justify-center top-[50%] left-[50%] translate-x-[-50%] translate-y-[-50%]"
  >
    <p v-if="size === 'large'" class="absolute text-[1.2rem] text-white">
      {{ percent.toFixed(0) }}%
    </p>

    <svg class="flex justify-center items-center w-full h-full">
      <circle
        ref="circleAll"
        :cx="sizeWidth[size] / 2"
        :cy="sizeWidth[size] / 2"
        :r="sizeWidth[size] / 5"
      />
      <circle
        ref="circlePercent"
        :cx="sizeWidth[size] / 2"
        :cy="sizeWidth[size] / 2"
        :r="sizeWidth[size] / 5"
      />
    </svg>
  </div>
</template>

<script setup>
import { ref, onMounted, watch, computed } from 'vue'

const props = defineProps({
  size: {
    type: String,
    default: 'medium',
  },
  percent: {
    type: Number,
    default: 0,
  },
})

const container = ref(null)
const circleAll = ref(null)
const circlePercent = ref(null)

const sizeWidth = {
  tiny: 50,
  small: 100,
  medium: 200,
  large: 400,
}

const sizeStroke = {
  tiny: 2,
  small: 4,
  medium: 6,
  large: 10,
}

const totalLengthCircle = ref(null)

onMounted(() => {
  const radius = Number(circleAll.value.getAttribute('r'))
  totalLengthCircle.value = 2 * Math.PI * radius

  container.value.style.width = `${sizeWidth[props.size]}px`
  container.value.style.height = `${sizeWidth[props.size]}px`

  circleAll.value.style.stroke = '#e2e8f0'
  circlePercent.value.style.stroke = '#33b5e7'
  circleAll.value.style.strokeWidth = sizeStroke[props.size]

  circlePercent.value.style.strokeDasharray = totalLengthCircle.value
  circlePercent.value.style.strokeDashoffset =
    totalLengthCircle.value - (props.percent / 100) * totalLengthCircle.value
  circlePercent.value.style.strokeWidth = sizeStroke[props.size]
})

watch(
  () => props.percent,
  (newVal) => {
    const percentFixed = newVal.toFixed(0)
    if (totalLengthCircle.value && circlePercent.value) {
      circlePercent.value.style.strokeDashoffset =
        totalLengthCircle.value - (percentFixed / 100) * totalLengthCircle.value
    }
  }
)
</script>

<style scoped>
circle {
  fill: none;
}
</style>