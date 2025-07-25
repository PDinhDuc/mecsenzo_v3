<template>
  <div
    v-if="isShowPreviewChatVoice"
    class="flex flex-row justify-between border-t-[1px] mt-2 w-full relative dark:!border-dark_text_strong"
  >
    <Recorder @change-data-audio="handleChangeDataChatVoice" />
    <button
      class="absolute top-0 right-0 w-[50px] h-full"
      @click="closePreview"
    >
      <font-awesome-icon icon="xmark" class="dark:text-dark_text_strong" />
    </button>
  </div>
</template>

<script setup>
import Recorder from './Recorder.vue'
import { defineProps, defineEmits } from 'vue'

const props = defineProps({
  isShowPreviewChatVoice: Boolean,
})

const emit = defineEmits(['close-preview', 'set-data-chat-voice'])

function closePreview() {
  emit('close-preview')
}

function handleChangeDataChatVoice(payload) {
  emit('set-data-chat-voice', payload)
}
</script>

<style scoped></style>