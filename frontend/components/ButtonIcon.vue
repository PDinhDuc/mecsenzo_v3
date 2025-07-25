<template>
  <button
    ref="btn"
    :type="type"
    class="p-2 w-[40px] h-[40px] rounded-full text-[1.2rem] flex justify-center items-center hover:bg-slate-200 hover:bg-[rgba(225,255,255,0.1)]"
    @click="emit('btn-icon-click')"
  >
    <slot />
  </button>
</template>

<script setup>
import { onMounted, ref, watch } from 'vue'

// Props
const props = defineProps({
  color: {
    type: String,
    default: '#0084ff',
  },
  type: {
    type: String,
    default: 'button',
  },
})

// Emits
const emit = defineEmits(['btn-icon-click'])

// Refs
const btn = ref(null)

// Set color when mounted
onMounted(() => {
  if (btn.value) {
    btn.value.style.color = props.color
  }
})

// Watch color changes
watch(
  () => props.color,
  (newColor) => {
    if (btn.value) {
      btn.value.style.color = newColor
    }
  }
)
</script>
