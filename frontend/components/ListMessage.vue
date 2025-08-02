<template>
  <div
    v-if="listMessage"
    id="container-msg"
    ref="containerMsg"
    class="flex-1 overflow-y-auto p-2 overflow-hidden scroll-smooth"
    @scroll="onScrollContainerMessage"
  >
    <div class="h-[90%] flex flex-col-reverse justify-end">
      <div
        v-for="(message, index) in listMessage"
        :key="index"
        class="flex flex-col-reverse"
      >
        <Message
          :message="message"
          :conversation="conversation"
          @set-reply="handleSetReplyMessage"
          @show-image-detail="handleShowImageDetail"
        />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'
import Message from '~/components/Message.vue'

// Props
const props = defineProps({
  listMessage: {
    type: Object,
    default: () => null,
  },
  conversation: {
    type: Object,
    default: ()=> null
  },
  footerData: {
    type: Object,
    default: ()=> null
  }
})

// Emits
const emit = defineEmits([
  'list-msg:load-more-message',
  'list-msg:set-reply',
  'list-msg:show-image-detail',
])

// Reactive state
const isScrollToBottom = ref(true)
const containerMsg = ref(null)

// Watch for listMessageData changes to scroll to bottom
watch(() => props.listMessage, () => {
  nextTick(() => {
    scrollTopBottomContainerChat()
  })
}, { deep: true })

// Methods
const onScrollContainerMessage = (e) => {
  if (e.target.scrollTop === 0) {
    emit('list-msg:load-more-message')
    isScrollToBottom.value = false
  }
}

const scrollTopBottomContainerChat = ()=> {
  if (isScrollToBottom.value) {
    const containerMessage = containerMsg.value
    containerMessage.scrollTo({
      top: containerMessage.scrollHeight,
      behavior: 'smooth',
    })
  }
}

const handleSetReplyMessage = (replyMessage) => {
  emit('list-msg:set-reply', replyMessage)
}

const handleShowImageDetail = (srcImage) => {
  emit('list-msg:show-image-detail', srcImage)
}

onMounted( ()=>{
  nextTick(() => {
    scrollTopBottomContainerChat()
  })
})
</script>

<style></style>