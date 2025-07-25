<template>
  <div :class="`rounded-full select-none ${getClassSize}`">
    <div v-if="props.isHaveAvatar" class="w-full h-full">
      <img
        :src="props.srcImage"
        alt=""
        class="w-full h-full object-cover rounded-full"
      />
    </div>
    <div
      v-else
      class="w-full h-full bg-dark_primary flex items-center justify-center rounded-full"
    >
      <p :class="`uppercase font-bold text-white ${getClassSizeText}`">
        {{ props.firstChar }}
      </p>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  isHaveAvatar: {
    type: Boolean,
    default: false,
  },
  firstChar: {
    type: String,
    default: '',
  },
  srcImage: {
    type: String,
    default: '',
  },
  size: {
    type: String,
    default: 'medium',
  },
})

const getClassSize = computed(() => {
  const sizeWith = {
    tiny: 16,
    small: 26,
    medium: 44,
    large: 100,
  }

  return `w-[${sizeWith[props.size]}px] h-[${sizeWith[props.size]}px]`
})

const getClassSizeText = computed(() => {
  const sizeText = {
    tiny: 0.6,
    small: 0.8,
    medium: 1.2,
    large: 2,
  }

  return `text-[${sizeText[props.size]}rem]`
})
</script>
