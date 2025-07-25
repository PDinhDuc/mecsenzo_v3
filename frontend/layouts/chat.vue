<template>
  <div>
    <Header />
    <div class="flex max-w-[1200px] m-auto mt-[100px] h-[80vh]">
      <SidebarConversation @open-modal-add-space="openModalAddConversation" />
      <slot />
      <div
        v-if="isShow"
        class="absolute bottom-0 right-0 top-0 left-0 z-[1000]"
      >
        <ModalConversation
          :conversation="showModalChatRoom.conversation"
          @closeModal="closeModalConversation"
          @set-percent-upload="setPercentUploadAvatar"
          @clear-percent-upload="setPercentUploadAvatar(null)"
        />
      </div>
    </div>
    <div
      v-if="percentUploadAvatar"
      class="absolute top-0 left-0 bottom-0 right-0 w-[100vw] h-[100vh] overflow-hidden z-[1000] bg-[rgba(0,0,0,0.5)]"
    >
      <ProgressLoader size="large" :percent="percentUploadAvatar" />
    </div>
  </div>
</template>

<script setup>
  import {ref} from 'vue'
  import { useChatStore} from '@/stores/chat'

  const isShow = ref(false)
  const percentUploadAvatar = ref(null);

  const closeModalConversation = function() {
    isShow.value = false;
  }

  const openModalAddConversation = function() {
    useChatStore.conversation = null
    isShow.value = true
  }

  const setPercentUploadAvatar = function(percent) {
    percentUploadAvatar = percent
  }

</script>
