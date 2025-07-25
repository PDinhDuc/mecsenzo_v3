<template>
  <div
    v-if="replyMessage"
    :class="`flex justify-between border-t-[1px] border-black mt-2 dark:text-dark_text_strong dark:border-dark_text_strong
      ${replyMessage.type === 'image' ? 'h-[100px] mb-2' : 'h-[50px]'}`"
  >
    <div class="w-[50%]">
      <p class="text-[0.9rem] truncate max-w-[200px] md:max-w-[300px]">
        {{ $t('chatSide.reply') }}
        <span class="font-semibold dark:text-dark_text_strong">{{
          replyMessage.user.fullName
        }}</span>
      </p>
      <p
        v-if="replyMessage.type === 'text'"
        class="text-[0.9rem] text-[#9e9fa2] truncate max-w-[200px] md:max-w-[300px]"
      >
        {{ replyMessage.content }}
      </p>
      <p
        v-else-if="replyMessage.type === 'audio'"
        class="text-[0.9rem] text-[#9e9fa2] truncate max-w-[200px] md:max-w-[300px]"
      >
        {{ $t('chatSide.replyAudio') }}
      </p>
      <div
        v-else-if="replyMessage.type === 'image'"
        class="relative flex items-center h-full max-w-[300px]"
      >
        <img
          class="h-[60%] object-cover"
          :src="replyMessage.content"
          alt="image sent"
        />
      </div>
    </div>
    <button class="w-[50px] h-full" @click="clearReplyMessage">
      <font-awesome-icon icon="xmark" />
    </button>
  </div>
</template>

<script setup>
import { useNuxtApp } from '#app'

// Nuxt app context for i18n
const { $t } = useNuxtApp()

// Props
const props = defineProps({
  replyMessage: {
    type: Object,
    default: () => null,
  },
})

// Emits
const emit = defineEmits(['clear-reply-message'])

// Methods
const clearReplyMessage = () => {
  emit('clear-reply-message')
}
</script>