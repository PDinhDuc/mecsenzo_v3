<template>
  <div
    :class="`flex-1 flex flex-col ml-0 md:ml-4 bg-white shadow-2xl rounded-[20px]  
    p-[36px] sm:pl-[100px]  md:pl-[36px] overflow-x-hidden dark:bg-dark_bg_light
    ${showSidebarConversation ? 'pl-[100px]' : 'pl-[36px]'}`"
  >
    <div v-if="isShowLoader" class="h-[50px]">
      <LoaderUser />
    </div>
    <HeaderChatSide
      v-else
      :info-conversation="conversationInfo"
      :is-show-sidebar-conversation="showSidebarConversation"
      @header-chat-side:show-add-member="handleShowModalAddMember"
      @header-chat-side:show-modal-conversation="showModalConversation"
      @header-chat-side:leave-room="handleShowPopupLeaveRoom"
      @header-chat-side:create-video-call="handleCreateVideoCall"
    />
    <Separation />
    <LoaderMessage v-if="isShowLoader"/>
    <ListMessage
    v-else
      :list-message="messages"
      @list-msg:load-more-message="handleLoadMoreMessage"
      @list-msg:set-reply="handleSetReplyMessage"
      @list-msg:show-image-detail="handleShowImageDetail"
    />
    <PreviewReply
      v-if="replyMessage"
      :reply-message="replyMessage"
      @clear-reply-message="clearReplyMessage"
    />
    <PreviewImageInput
      :file-image-input="fileImageInput"
      :percent-upload-image="percentUploadImage"
      @clear-temp-input-image="handleClearTempInputImage"
    />
    <PreviewVoiceChat
      :is-show-preview-chat-voice="isShowPreviewChatVoice"
      @close-preview="handleClosePreviewChatVoice"
      @set-data-chat-voice="handleSetDataChatVoice"
    />
    <ChatSideFooter
      :color-btn="conversationRealtime ? conversationRealtime.colorChat : '#0084ff'"
      :is-disable-input-message="isDisableInputMessage"
      @set-file-image-input="handleSetFileImageInput"
      @show-preview-chat-voice="handleShowPreviewChatVoice"
      @send-message="handleSendMessage"
      @focus-input-message="handleFocusInputMessage"
      @blur-input-message="handleBlurInputMessage"
      @select-emoji="handleSelectEmoji"
      @update:input-message="updateInputMessage"
    />
    <div
      :class="`noSelect cursor-pointer absolute w-[50px] 
      h-[50px] top-[30%] left-[0] flex sm:hidden
      justify-center items-center bg-white rounded-full 
      text-dark_primary shadow-xl hover:bg-dark_primary hover:text-white
      transition-all
      ${showSidebarConversation ? 'translate-x-[70px]' : 'translate-x-[-20px]'}`"
      @click="handleToggleSidebarMobile"
    >
      <font-awesome-icon v-if="!showSidebarConversation" icon="angles-right" class="ml-3" />
      <font-awesome-icon v-else icon="angles-left" class="ml-3" />
    </div>
    <ModalAddMember
      v-if="conversationRealtime && conversationRealtime.type === 'group' && isShowModalAddMember"
      :conversation="conversationRealtime"
      @closeModal="handleCloseModalAddMember"
    />
    <PopupConfirm
      v-if="conversationRealtime && conversationRealtime.type === 'group' && isShowPopupLeaveRoom"
      :content="t('popupConfirm.leaveRoomContent')"
      :name-btn-action="t('popupConfirm.leaveRoom')"
      @close-popup="handleClosePopupLeaveRoom"
      @confirm-popup="handleLeaveRoom"
    />
    <ShowImageMessage
      v-if="srcImageShow"
      :src="srcImageShow"
      @close-show-image-message="handleCloseShowImageMessage"
    />
    <ModalCallVideo
      v-if="isShowModalCallVideo"
      :info-video-call="infoVideoCall"
      :current-message-video-call="currentMessageVideoCall"
      @cancel-video-call="handleCancelVideoCall"
      @close-video-call="handleCloseVideoCall"
    />
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted, watch } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import Separation from '~/components/Separation.vue'
import ListMessage from '~/components/ListMessage.vue'
import HeaderChatSide from '~/components/HeaderChatSide.vue'
import PreviewReply from '~/components/PreviewReply.vue'
import PreviewImageInput from '~/components/PreviewImageInput.vue'
import ModalCallVideo from '~/components/ModalCallVideo.vue'
import LoaderUser from '~/components/LoaderUser.vue'
import PreviewVoiceChat from '~/components/PreviewVoiceChat.vue'
import ChatSideFooter from '~/components/ChatSideFooter.vue'
import LoaderMessage from './LoaderMessage.vue'
import { useI18n } from 'vue-i18n'
import { useLocalePath } from '#i18n'
import { useConversationStore } from '~/stores/conversation'
import useChat from '@/composables/useChat'

const { t } = useI18n()
const localePath = useLocalePath()

const route = useRoute()
const router = useRouter()

const conversationId = computed(() => parseInt(route.params.id))
const {
  messages,
  error,
  isListening,
  listen,
  stop,
  loadMessages,
  sendMessage,
  hasMore,
  isShowLoader,
} = useChat(conversationId.value)

// Reactive state
const currentConversation = ref(null)
const listMessage = ref(null)
const lastDocMessage = ref(null)
const unsubscribeSnapMessage = ref(null)
const unsubscribeLoadMoreMsg = ref(null)
const inputMessage = ref('')
const replyMessage = ref(null)
const conversationRealtime = ref(null)
const unsubscribeGetConversationRealtime = ref(null)
const isShowModalAddMember = ref(false)
const isShowPopupLeaveRoom = ref(false)
const fileImageInput = ref(null)
const percentUploadImage = ref(null)
const srcImageShow = ref(null)
const isShowPreviewChatVoice = ref(false)
const dataChatVoice = ref(null)
const isDisableInputMessage = ref(false)
const currentMessageVideoCall = ref(null)
const isShowModalCallVideo = ref(false)
const infoVideoCall = ref(null)
const unsubscribeCurrentMessageVideoCall = ref(null)
const flagAutoCancelVideoCall = ref(true)
const showSidebarConversation = ref(false)

const partnerUser = computed(() => {
  return messages.value.filter(mess => mess.user_id !== useAuthStore().user.id)
})

const currentUser = computed(() => {
  return currentMembers.value.filter(user => user.email === currentEmail.value)[0]
})

const conversationInfo = computed(() => {
  return useConversationStore().conversationInfor
})

const statusPartner = computed(() => partnerUser.value?.isActive || false)

const usersSeen = computed(() => {
  if (
    conversationRealtime.value &&
    conversationRealtime.value.lastMessage &&
    conversationRealtime.value.lastMessage.user.email === currentEmail.value
  ) {
    return currentMembers.value.filter(
      member => member.email !== currentEmail.value && conversationRealtime.value.seen.includes(member.email)
    )
  }
  return []
})

const usersTyping = computed(() => {
  if (conversationRealtime.value && conversationRealtime.value.isTyping) {
    const emailTyping = conversationRealtime.value.isTyping.filter(email => email !== currentEmail.value)
    return currentMembers.value.filter(user => emailTyping.includes(user.email) && user.isActive)
  }
  return []
})

const listMessageFooterData = computed(() => {
  return {
    userTyping: usersTyping.value,
    usersSeen: usersSeen.value,
  }
})

const listMessageData = computed(() => ({
  footerData: listMessageFooterData.value,
  listMessage: messages.value,
  conversation: conversationRealtime.value,
}))

const headerChatSideData = computed(() => ({
  avatar: conversationInfo.value.avatar,
  name: conversationInfo.value.name,
  conversation: conversationRealtime.value,
  statusPartner: statusPartner.value,
}))

const titlePage = computed(() => {
  if (conversationRealtime.value) {
    return conversationRealtime.value.type === 'individual'
      ? conversationRealtime.value.partnerUser.fullName
      : conversationRealtime.value.name
  }
  return 'Mecsenzo'
})

watch(currentMessageVideoCall, async (newValue) => {
  if (newValue.status === 'accept') {
    flagAutoCancelVideoCall.value = false
    router.push({
      name: `video-chat-id___${$i18n.locale}`,
      params: { id: currentMessageVideoCall.value.id },
    })
  } else if (newValue.status === 'cancel') {
    isShowModalCallVideo.value = false
    await updateUser({ ...currentUser.value, isFreeVideoCall: true })
  }
})

watch(listMessage, (newValue)=>{
  console.log('listMess when realtime');
  console.log(newValue);
}, { deep: true })

// Methods
const updateInputMessage = (value) => {
  inputMessage.value = value
}

const handleSelectEmoji = (emoji) => {
  inputMessage.value += emoji.data
}

const handleToggleSidebarMobile = () => {
  useSidebarConversationStore().toggleSidebar()
}

const handleDocsMessage = (listMessageDocs) => {
  const listMessage = listMessageDocs.map(doc => ({
    id: doc.id,
    ...doc.data(),
  }))
  const lastDocMessage = listMessageDocs[listMessageDocs.length - 1]
  return { listMessage, lastDocMessage }
}



const handleSetReplyMessage = (message) => {
  replyMessage.value = message
}

const clearReplyMessage = () => {
  replyMessage.value = null
}

const updateConversationWhenSendMessage = async (newMessage) => {
  await updateConversation({
    ...conversationRealtime.value,
    lastMessage: newMessage,
    timeEnd: serverTimestamp(),
    seen: [currentEmail.value],
  })
}

// const sendMessage = async (content, type) => {
//   const userSendMessage = currentUser.value
//   const newMessage = await saveMessage(
//     conversationRealtime.value.id,
//     userSendMessage,
//     content,
//     replyMessage.value,
//     type
//   )
//   await updateConversationWhenSendMessage(newMessage)
// }

const saveMessageImage = async (url) => {
  await sendMessage(url, 'image')
  handleClearTempInputImage()
  percentUploadImage.value = null
  replyMessage.value = null
}

const saveMessageVoice = async (url) => {
  await sendMessage(url, 'audio')
  handleClosePreviewChatVoice()
  replyMessage.value = null
}

const setPercentLoaderImage = (percent) => {
  percentUploadImage.value = percent
}

const handleSendMessage = async () => {
  if (fileImageInput.value) {
    console.log('send img');   
  } else if (dataChatVoice.value) {
    console.log('chatvoice');  
  } else if (inputMessage.value) {
    await sendMessage(inputMessage.value)
    inputMessage.value = ''
    replyMessage.value = null
  }
}

const loadMoreMessage = (listMessageDocs) => {
  const { listMessage: newMessages, lastDocMessage: lastDoc } = handleDocsMessage(listMessageDocs) 
  if (lastDoc && lastDoc.id !== lastDocMessage.value?.id) {
    lastDocMessage.value = lastDoc
    listMessage.value = [...listMessage.value, ...newMessages]
  }
}

const handleLoadMoreMessage = () => {
  console.log('handle load more');
  
}



const handleFocusInputMessage = async () => {
  if (conversationRealtime.value) {
    if (!conversationRealtime.value.seen.includes(currentEmail.value)) {
      await updateConversation({
        ...conversationRealtime.value,
        seen: [...conversationRealtime.value.seen, currentEmail.value],
      })
    }
    await updateConversation({
      ...conversationRealtime.value,
      isTyping: [...conversationRealtime.value.isTyping, currentEmail.value],
    })
  }
}

const handleBlurInputMessage = async () => {
  if (conversationRealtime.value && conversationRealtime.value.isTyping) {
    const newIsTyping = conversationRealtime.value.isTyping.filter(email => email !== currentEmail.value)
    await updateConversation({
      ...conversationRealtime.value,
      isTyping: newIsTyping,
    })
  }
}

const showModalConversation = () => {
  useModalChatRoomStore().openModal()
  useModalChatRoomStore().setConversation(conversationRealtime.value)
}

const handleCloseModalAddMember = () => {
  isShowModalAddMember.value = false
}

const handleShowModalAddMember = () => {
  isShowModalAddMember.value = true
}

const handleClosePopupLeaveRoom = () => {
  isShowPopupLeaveRoom.value = false
}

const handleShowPopupLeaveRoom = () => {
  isShowPopupLeaveRoom.value = true
}

const handleLeaveRoom = async () => {
  handleClosePopupLeaveRoom()
  await updateConversation({
    ...conversationRealtime.value,
    member: conversationRealtime.value.member.filter(email => email !== currentEmail.value),
  })
  router.push({ name: `index___${$i18n.locale}` })
}

const handleSetFileImageInput = (fileImage) => {
  fileImageInput.value = createTempUrlForImageFile(fileImage)
  isDisableInputMessage.value = true
  if (fileImageInput.value) inputMessage.value = ''
  if (isShowPreviewChatVoice.value) {
    isShowPreviewChatVoice.value = null
    dataChatVoice.value = null
  }
}

const handleClearTempInputImage = () => {
  fileImageInput.value = null
  isDisableInputMessage.value = false
}

const handleShowImageDetail = (src) => {
  srcImageShow.value = src
}

const handleCloseShowImageMessage = () => {
  srcImageShow.value = null
}

const handleShowPreviewChatVoice = () => {
  isShowPreviewChatVoice.value = true
  isDisableInputMessage.value = true
  inputMessage.value = ''
  if (fileImageInput.value) fileImageInput.value = null
}

const handleClosePreviewChatVoice = () => {
  isShowPreviewChatVoice.value = false
  dataChatVoice.value = null
  inputMessage.value = ''
  isDisableInputMessage.value = false
}

const handleSetDataChatVoice = (payload) => {
  dataChatVoice.value = payload
}

const handleCreateVideoCall = async () => {
  flagAutoCancelVideoCall.value = true
  const newMessage = await saveMessageVideoCall(
    conversationRealtime.value.id,
    currentUser.value,
    roomVideoCall
  )
  await updateConversationWhenSendMessage(newMessage)
  isShowModalCallVideo.value = true
  currentMessageVideoCall.value = newMessage

  if (conversationRealtime.value.type === 'individual') {
    const partnerUserQuery = await getUserByEmail(partnerUser.value.email)
    const currentUserData = currentUser.value
    infoVideoCall.value = {
      avatar: partnerUserQuery.avatar,
      name: partnerUserQuery.fullName,
    }

    if (partnerUserQuery.isFreeVideoCall === false) {
      currentMessageVideoCall.value = { ...currentMessageVideoCall.value, status: 'cancel' }
      await handleCancelVideoCall()
    } else {
      await updateUser({ ...partnerUserQuery, isFreeVideoCall: false })
      await updateUser({ ...currentUserData, isFreeVideoCall: false })
      setTimeout(async () => {
        if (flagAutoCancelVideoCall.value) {
          await handleCancelVideoCall()
          handleCloseVideoCall()
          await updateUser({ ...partnerUserQuery, isFreeVideoCall: true })
          await updateUser({ ...currentUserData, isFreeVideoCall: true })
          if (unsubscribeCurrentMessageVideoCall.value) {
            unsubscribeCurrentMessageVideoCall.value()
          }
        }
      }, constant.MILISECONDS_CANCEL_VIDEO_CALL)
      unsubscribeCurrentMessageVideoCall.value = getMessageRealtime(
        currentMessageVideoCall.value.id,
        setCurrentMessageVideoCall
      )
    }
  } else {
    const emailMemberOfConversation = conversationRealtime.value.member
    const userOfConversation = await getUsersByEmails(emailMemberOfConversation)
    userOfConversation.forEach(async user => {
      await updateUser({ ...user, isFreeVideoCall: false })
    })
    router.push({
      name: `video-chat-id___${$i18n.locale}`,
      params: { id: currentMessageVideoCall.value.id },
    })
    infoVideoCall.value = {
      avatar: conversationRealtime.value.thumb,
      name: conversationRealtime.value.name,
    }
  }
}

const handleCancelVideoCall = async () => {
  const newLastMessage = { ...currentMessageVideoCall.value, status: 'cancel' }
  await updateMessageVideoCall(newLastMessage)
  await updateConversationWhenSendMessage(newLastMessage)
}

const handleCloseVideoCall = () => {
  isShowModalCallVideo.value = false
  flagAutoCancelVideoCall.value = false
}

const setCurrentMessageVideoCall = (message) => {
  currentMessageVideoCall.value = message
}

onMounted(async ()=>{
  await useConversationStore().getConversationInfor(conversationId.value)
})

onUnmounted(() => {
  if (unsubscribeSnapMessage.value) unsubscribeSnapMessage.value()
  if (unsubscribeGetConversationRealtime.value) unsubscribeGetConversationRealtime.value()
})

// Head configuration for Nuxt 3
definePageMeta({
  title: titlePage,
})
</script>