<template>
  <div v-if="message">
    <div
      :id="message.id"
      :class="`flex items-end mt-3 
      ${isMyMessage(message) ? 'flex-row-reverse justify-start' : ''}`"
    >
      <Avatar
        v-if="!isMyMessage(message)"
        :is-have-avatar="!!user.avatar"
        :src-image="user.avatar"
        :first-char="user && user.name.charAt(0)"
        size="small"
      />
      <div
        :class="`max-w-[80%] md:max-w-[45%] rounded-[10px] peer flex flex-col 
        ${isMyMessage(message) ? 'items-end' : 'items-start'}`"
      >
        <div
          :class="`ml-2 rounded-[10px] max-w-full bg-[#e4e6eb] ${
            isMyMessage(message) ? getBgMessage : 'dark:bg-dark_bg_message'
          }`"
        >
          <div v-if="message !== 'text'" class="p-2">
            <p
              :class="`text-[1.1rem] truncate max-w-full ${
                isMyMessage(message)
                  ? 'text-white'
                  : 'text-[#333] dark:text-white'
              }`"
            >
              {{ message.content }}
            </p>
          </div>
        </div>
      </div>
      <div
        :class="`relative hidden peer-hover:block before:content-['']
        before:absolute before:w-[14px] before:h-full hover:block 
        before:bg-transparent 
        ${
          isMyMessage(message)
            ? 'right-2 before:left-[100%]'
            : 'left-2 before:right-[100%]'
        }`"
      >
        <button
          class="h-[32px] w-[32px] rounded-full flex items-center justify-center hover:bg-slate-200 dark:hover:bg-[rgba(255,255,255,0.1)]"
          @click="handleSetReplyMessage(message)"
        >
          <font-awesome-icon icon="reply" class="dark:text-dark_text_light" />
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useRouter, useNuxtApp } from '#app'
import Avatar from '~/components/Avatar.vue'
import AudioDisplay from '~/components/AudioDisplay.vue'
import { useAuthStore } from '@/stores/auth'


const { $t } = useNuxtApp()
const router = useRouter()


const props = defineProps({
  message: {
    type: Object,
    default: () => null,
  },
  conversation: {
    type: Object,
    default: () => null,
  },
})


const emit = defineEmits(['set-reply', 'show-image-detail'])
const user = computed(()=> useAuthStore().user)

const isMyMessage = (message) => {
  return user.value && message.user_id === user.value.id
}


const getBgMessage = computed(() => {
  return props.conversation
    ? `!bg-[${props.conversation.colorChat}]`
    : '!bg-[#0084ff]'
})

onMounted( ()=>{

})
</script>