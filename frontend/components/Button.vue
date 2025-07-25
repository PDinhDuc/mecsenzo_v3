<template>
  <button
    v-if="type === 'button'"
    :type="type"
    :class="`outline-none min-w-[120px] text-[1.2rem] rounded-[20px] select-none ${renderClassSize} ${renderClassColor}`"
    @click="handleClick"
  >
    <slot />
  </button>

  <NuxtLink v-else-if="type === 'link'" :to="to">
    <slot />
  </NuxtLink>

  <input
    v-else-if="type === 'submit'"
    type="submit"
    :value="nameButton"
    :class="`outline-none min-w-[120px] text-[1.2rem] rounded-[20px] cursor-pointer ${renderClassSize} ${renderClassColor}`"
  />
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  type: {
    type: String,
    default: 'button',
  },
  size: {
    type: String,
    default: 'medium',
  },
  color: {
    type: String,
    default: 'primary', // dùng tên từ colorClassMap
  },
  variant: {
    type: String,
    default: 'contained',
  },
  handleClick: {
    type: Function,
    default: () => () => {},
  },
  nameButton: {
    type: String,
    default: 'Submit',
  },
  to: {
    type: [String, Object],
    default: '/',
  },
})

// Map tên màu sang class Tailwind tĩnh
const colorClassMap = {
  primary: {
    contained: 'text-white bg-[#ff7200] border border-[#ff7200] hover:opacity-70',
    outlined: 'text-[#ff7200] bg-white border border-[#ff7200] hover:text-white hover:bg-[#ff7200]',
  },
  danger: {
    contained: 'text-white bg-[#f74242] border border-[#f74242] hover:opacity-70',
    outlined: 'text-[#f74242] bg-white border border-[#f74242] hover:text-white hover:bg-[#f74242]',
  },
  success: {
    contained: 'text-white bg-[#01c851] border border-[#01c851] hover:opacity-70',
    outlined: 'text-[#01c851] bg-white border border-[#01c851] hover:text-white hover:bg-[#01c851]',
  },
  info: {
    contained: 'text-white bg-[#33b5e7] border border-[#33b5e7] hover:opacity-70',
    outlined: 'text-[#33b5e7] bg-white border border-[#33b5e7] hover:text-white hover:bg-[#33b5e7]',
  },
}

const renderClassColor = computed(() => {
  const color = colorClassMap[props.color] || colorClassMap.primary
  return color[props.variant] || color.contained
})

const renderClassSize = computed(() => {
  const sizeMap = {
    small: ['4px', '8px'],
    medium: ['6px', '12px'],
    large: ['8px', '16px'],
  }
  const [py, px] = sizeMap[props.size] || sizeMap.medium
  return `py-[${py}] px-[${px}]`
})
</script>
