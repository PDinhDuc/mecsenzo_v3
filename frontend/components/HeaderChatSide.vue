<template>
  <div class="flex justify-between items-center">
    <div class="h-[50px] w-full flex items-center justify-between">
      <div class="flex items-center">
        <div class="relative z-[10]">
          <Avatar
            v-if="conversationInfor"
            :is-have-avatar="!!conversationInfor?.avatar"
            :src-image="conversationInfor?.avatar"
            :first-char="conversationInfor?.name && conversationInfor?.name.charAt(0)"
          />
          <div
            v-if="infoConversation.type === 'private'"
            :class="`absolute w-[12px] h-[12px] rounded-full bottom-0 right-0
              ${conversationInfor.is_online ? 'bg-success' : 'bg-gray-300'}`"
          ></div>
        </div>
        <div class="conversation-content ml-4">
          <p
            :class="`select-none font-semibold truncate max-w-[80px] sm:max-w-[300px] dark:text-white
              ${isShowSidebarConversation ? 'hidden' : ''}`"
          >
            {{ conversationInfor?.name }}
          </p>
          <p
            v-if="infoConversation.type === 'private'"
            class="select-none truncate text-[0.9rem] max-w-[180px] h-[1.4rem] text-gray-500"
          >
            {{ conversationInfor.is_online ? t('chatSide.active') : t('chatSide.offline') }}
          </p>
        </div>
      </div>
      <div class="flex items-center">
        <ButtonIcon
          v-if="infoConversation.type === 'group'"
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
          v-if="infoConversation.type === 'group'"
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
import ButtonIcon from '~/components/ButtonIcon.vue'
import Avatar from '~/components/Avatar.vue'
import { useAuthStore } from '@/stores/auth'
import { useI18n } from 'vue-i18n'
import realtimeUserActive from '@/composables/realtimeUserActive'

const updateUserStatus = (userId, isOnline) => {
  if (props.infoConversation.type !== 'private') return

  const user = conversationInfor.value
  if (user && user.id === userId) {
    user.is_online = isOnline
  }
}
const {listen , leave} = realtimeUserActive(updateUserStatus)
const { t } = useI18n()

const props = defineProps({
  infoConversation: {
    type: Object,
    required: true,
  },
  isShowSidebarConversation: {
    type: Boolean,
    default: false,
  },
})

const emit = defineEmits([
  'header-chat-side:show-add-member',
  'header-chat-side:show-modal-conversation',
  'header-chat-side:leave-room',
  'header-chat-side:create-video-call',
])


const getColorBtnIcon = computed(() => props.infoConversation.colorChat)
const conversationInfor = ref(null)

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

watch(
  () => props.infoConversation,
  (newInfo) => {
    if (!newInfo || !newInfo.users) {
      conversationInfor.value = null
      return
    }

    if (newInfo.type === 'private') {
      conversationInfor.value =
        newInfo.users.find((user) => user.id !== useAuthStore().user.id) || null
    } else {
      conversationInfor.value = newInfo
    }
  },
  { immediate: true, deep: true }
)
onMounted(()=>{
  listen()
})
onBeforeUnmount(()=>{
  leave()
})
</script>

<style></style>