<template>
  <div
    v-if="fileImageInput"
    class="flex justify-between h-[100px] border-t-[1px] border-black mt-2 dark:border-dark_text_strong"
  >
    <div class="relative flex items-center h-full max-w-[300px]">
      <img
        class="h-[90%] object-cover"
        :src="fileImageInput.preview"
        alt="image sent"
      />
      <div
        v-if="percentUploadImage"
        class="absolute top-[5%] left-0 h-[90%] w-full bg-[rgba(0,0,0,0.5)]"
      ></div>
      <ProgressLoader
        v-if="percentUploadImage"
        size="small"
        :percent="percentUploadImage"
      />
    </div>
    <button class="w-[50px] h-full" @click="handleClearTempInputImage">
      <font-awesome-icon icon="xmark" class="dark:text-dark_text_strong" />
    </button>
  </div>
</template>

<script setup>
import ProgressLoader from '~/components/ProgressLoader.vue'

// Props
const props = defineProps({
  fileImageInput: {
    type: Object,
    default: () => null,
  },
  percentUploadImage: {
    type: Number,
    default: () => null,
  },
})

// Emits
const emit = defineEmits(['clear-temp-input-image'])

// Methods
const handleClearTempInputImage = () => {
  emit('clear-temp-input-image')
}
</script>