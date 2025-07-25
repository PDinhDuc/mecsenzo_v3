<template>
  <div class="flex justify-between items-center">
    <div class="h-[50px] w-full flex items-center justify-between">
      <div class="flex items-center">
        <div class="relative z-[10]">
          <Avatar
            :is-have-avatar="!!infoConversation.avatar"
            :src-image="infoConversation.avatar"
            :first-char="infoConversation.name && infoConversation.name.charAt(0)"
          />
          <div
            v-if="infoConversation.conversation.type === 'individual'"
            :class="`absolute w-[12px] h-[12px] rounded-full bottom-0 right-0
              ${infoConversation.statusPartner ? 'bg-success' : 'bg-gray-300'}`"
          ></div>
        </div>
        <div class="conversation-content ml-4">
          <p
            :class="`select-none font-semibold truncate max-w-[80px] sm:max-w-[300px] dark:text-white
              ${isShowSidebarConversation ? 'hidden' : ''}`"
          >
            {{ infoConversation.name }}
          </p>
          <p
            v-if="infoConversation.conversation.type === 'individual'"
            class="select-none truncate text-[0.9rem] max-w-[180px] h-[1.4rem] text-gray-500"
          >
            {{ infoConversation.statusPartner ? $t('chatSide.active') : $t('chatSide.offline') }}
          </p>
        </div>
      </div>
      <div class="flex items-center">
        <ButtonIcon
          v-if="infoConversation.conversation.type === 'group'"
          :color="getColorBtnIcon"
          @btn-icon-click="handleShowModalAddMember"
        >
          <font-awesome-icon icon="user-plus" />
        </ButtonIcon>
        <ButtonIcon
          :color="getColorBtnIcon"
          @btn-icon-click="handleCreateVideoCall"
        >
          <font-awesome-icon icon="video" />
        </ButtonIcon>
        <ButtonIcon
          :color="getColorBtnIcon"
          @btn-icon-click="showModalConversation"
        >
          <font-awesome-icon icon="circle-info" />
        </ButtonIcon>
        <ButtonIcon
          v-if="infoConversation.conversation.type === 'group'"
          :color="getColorBtnIcon"
          @btn-icon-click="handleShowPopupLeaveRoom"
        >
          <font-awesome-icon icon="arrow-right-from-bracket" />
        </ButtonIcon>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useNuxtApp } from '#app'
import ButtonIcon from '~/components/ButtonIcon.vue'
import Avatar from '~/components/Avatar.vue'

// Nuxt app context for i18n
const { $t } = useNuxtApp()

// Props
const props = defineProps({
  infoConversation: {
    type: Object,
    default: () => null,
  },
  isShowSidebarConversation: {
    type: Boolean,
    default: false,
  },
})

// Emits
const emit = defineEmits([
  'header-chat-side:show-add-member',
  'header-chat-side:show-modal-conversation',
  'header-chat-side:leave-room',
  'header-chat-side:create-video-call',
])

// Computed properties
const getColorBtnIcon = computed(() => props.infoConversation.conversation.colorChat)

// Methods
const handleShowModalAddMember = () => {
  emit('header-chat-side:show-add-member')
}

const showModalConversation = () => {
  emit('header-chat-side:show-modal-conversation')
}

const handleShowPopupLeaveRoom = () => {
  emit('header-chat-side:leave-room')
}

const handleCreateVideoCall = () => {
  emit('header-chat-side:create-video-call')
}
</script>

<style></style>