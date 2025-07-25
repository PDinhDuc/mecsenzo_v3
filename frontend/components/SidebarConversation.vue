<template>
  <div
    :class="`relative container-sidebar w-[30%] h-full py-[36px] px-[20px] 
    bg-white shadow-lg sm:shadow-2xl rounded-[20px] z-[50] 
    dark:bg-dark_bg_light
    ${
      showSidebarConversation ? 'translate-x-0' : 'translate-x-[-100px]'
    } sm:translate-x-0`"
  >
    <div class="relative w-full h-[48px]">
      <input
        type="text"
        class="appearance-none outline-none w-full h-full p-[20px] rounded-full bg-gray-200 focus:bg-white focus:shadow-lg transition-all duration-150 ease-in-out opacity-0 group-hover:opacity-100 text-[0.9rem] md:text-[1rem]"
        :placeholder="t('sidebarConversation.inputPlaceholder')"
        @input="handleChangeSearchKey"
      />
      <button
        class="absolute top-0 right-0 h-full w-[48px] flex justify-center items-center rounded-full bg-slate-200 md:bg-transparent transition-all"
      >
        <font-awesome-icon icon="magnifying-glass" />
      </button>
    </div>
    <div
      class="container-conversation relative h-[36%] my-5 overflow-y-auto overflow-x-hidden"
      @scroll="handleScroll($event, handleLoadMoreIndividual)"
    >
      <div v-if="conversationIndividual">
        <NuxtLink
          v-for="conversation in conversationIndividual"
          :id="conversation.id"
          :key="conversation.id"
          :to="localePath(`/conversation/${conversation.id}`)"
          :class="`h-[54px] mb-3 flex items-center cursor-pointer hover:bg-slate-200 hover:bg-[rgba(255,255,255,0.1)]
                  ${getClassBgCurrentConversation(conversation.id)}`"
          @click="handleSeenConversation(conversation)"
        > 
          <div class="relative">
            <Avatar
              :is-have-avatar="conversation.users[0].avatar"
              :src-image="conversation.users[0].avatar ?? conversation.users[0].avatar"
              :first-char="conversation.users[0].name.charAt(0)"
            />
            <div
              :class="`absolute w-[12px] h-[12px] rounded-full bottom-0 right-0 
              ${getClassIsOnline(conversation.partnerUser)}`"
            ></div>
          </div>
          <div class="conversation-content ml-4">
            <p
              :class="`select-none truncate text-ellipsis max-w-[180px] 
              md:max-w-[120px] lg:max-w-[180px] h-[1.4rem] dark:text-dark_text_strong
              ${getClassNameNotSeen(conversation)}`"
            >
              {{ conversation.users[0].name }}
            </p>
            <p
              :class="`select-none truncate text-ellipsis text-[0.9rem] max-w-[180px] 
              md:max-w-[120px] lg:max-w-[180px] h-[1.4rem] dark:text-dark_text_light
              ${getClassNewMsgNotSeen(conversation)}`"
            >
              <span >
                {{ conversation.messages[0]?.content }}
              </span>
              <!-- <span v-else-if="getLastMessage(conversation).type === 'audio'">
                {{ t('sidebarConversation.lastMsgAudio') }}
              </span>
              <span v-else-if="getLastMessage(conversation).type === 'image'">
                {{ t('sidebarConversation.lastMsgImage') }}
              </span>
              <span
                v-else-if="getLastMessage(conversation).type === 'videoCall'"
              >
                {{ t('sidebarConversation.lastMsgVideoCall') }}
              </span> -->
            </p>
          </div>
        </NuxtLink>
      </div>
      <div
        v-if="isShowLoaderIndividualConversation"
        class="h-[100px] w-full bg-transparent absolute top-0 left-0 flex justify-center items-center"
      >
        <LoaderSideConversation />
      </div>
    </div>
    <Separation />
    <div class="w-full h-[20px] flex justify-between items-center px-3">
      <p class="heading-space text-[1.1rem] hidden dark:text-white">
        {{ t('sidebarConversation.spaces') }}
      </p>
      <button
        class="btn-add-space w-[36px] h-[36px] text-[1.1rem] text-success rounded-full bg-slate-200 md:bg-transparent hover:bg-slate-200 transition-colors"
        @click="showModalAddSpace"
      >
        <font-awesome-icon icon="plus" />
      </button>
    </div>
    <div
      class="container-conversation relative h-[36%] my-5 overflow-y-auto overflow-x-hidden"
      @scroll="handleScroll($event, handleLoadMoreSpaces)"
    >
      <NuxtLink
        v-for="conversation in conversationSpace"
        :id="conversation.id"
        :key="conversation.id"
        :to="localePath(`/conversation/${conversation.id}`)"
        :class="`h-[54px] mb-3 flex items-center cursor-pointer hover:bg-slate-200 hover:bg-[rgba(255,255,255,0.1)]
        ${getClassBgCurrentConversation(conversation.id)}`"
        @click="handleSeenConversation(conversation)"
      >
        <Avatar
          :is-have-avatar="!!conversation.thumb"
          :src-image="conversation.thumb"
          :first-char="conversation.name.charAt(0)"
        />
        <div class="conversation-content ml-4">
          <p
            :class="`select-none truncate 
            text-ellipsis max-w-[180px] 
            md:max-w-[120px] lg:max-w-[180px] h-[1.4rem] dark:text-dark_text_strong
            ${getClassNameNotSeen(conversation)}`"
          >
            {{ conversation.name }}
          </p>
          <p
            v-if="getLastMessage(conversation)"
            :class="`select-none truncate text-ellipsis text-[0.9rem] 
            max-w-[180px] md:max-w-[120px] lg:max-w-[180px] dark:text-dark_text_light
            h-[1.4rem] ${getClassNewMsgNotSeen(conversation)}`"
          >
            <span v-if="getLastMessage(conversation).type === 'text'">
              {{ getLastMessage(conversation).content }}
            </span>
            <span v-else-if="getLastMessage(conversation).type === 'audio'">
              {{ t('sidebarConversation.lastMsgAudio') }}
            </span>
            <span v-else-if="getLastMessage(conversation).type === 'image'">
              {{ t('sidebarConversation.lastMsgImage') }}
            </span>
          </p>
        </div>
      </NuxtLink>
      <div
        v-if="isShowLoaderGroupConversation"
        class="h-[100px] w-full bg-transparent absolute top-0 left-0 flex justify-center items-center"
      >
        <LoaderSideConversation />
      </div>
    </div>
    <ClientOnly>
      <AsyncResultSearchConversation v-if="keySearch.value" />
    </ClientOnly>
  </div>
</template>

<script setup>
import { defineAsyncComponent, onMounted, ref } from 'vue'
import Avatar from './Avatar.vue'
import LoaderSideConversation from './LoaderSideConversation.vue'
import Separation from './Separation.vue'
import { useI18n } from 'vue-i18n'
import { useLocalePath } from '#i18n'
import { useAuthStore } from '@/stores/auth'
import { useChatStore } from '@/stores/chat'

const AsyncResultSearchConversation = defineAsyncComponent({
  loader: () => import('~/components/ResultSearchConversation.vue'),
  loadingComponent: LoaderSideConversation,
  delay: 200,
  timeout: 3000,
})

const { t } = useI18n()
const localePath = useLocalePath()


// State management using useState (or Pinia)
const currentEmail = computed(() => useAuthStore.user.email )
const currentConversation = computed(()=>{
  const currentConversation = useChatStore.conversation
  return currentConversation ? currentConversation?.pivot?.conversation_id : null
})

const showSidebarConversation = ref(false)
const keySearch = ref('')
const conversationSpace = ref([])
const conversationIndividual = ref([])
const isShowLoaderIndividualConversation = ref(true)
const isShowLoaderGroupConversation = ref(true)

// Computed properties
const getLastMessage = (conversation) => {
  return 'text'
}

const getClassNameNotSeen = (conversation) => {
  return false ? '' : 'font-medium'
}

const getClassNewMsgNotSeen = (conversation) => {
  return false
    ? 'text-gray-400'
    : 'text-dark_primary font-medium'
}

const getClassIsOnline = (partnerUser) => {
  return partnerUser?.isActive ? 'bg-success' : 'bg-gray-300'
}

const getCurrentConversationId = () => {
  return currentConversation.value ? currentConversation.value.id : null
}

const getClassBgCurrentConversation = (idConversation) => {
  return idConversation === getCurrentConversationId() ? 'bg-[#eaf3ff]' : ''
}

// Methods

const handleSeenConversation = async (conversation)=>{
  await useChatStore().fetchMessages(conversation.id)
}

const showModalAddSpace = () => {
  // Emitting an event in Nuxt 3 can be handled via a custom event bus or parent component
  // For simplicity, assuming a parent component handles this
  // emit('open-modal-add-space')
  console.log('Open modal add space')
}

const setConversationIndividualAndSpace = async () => {
  await useChatStore().fetchConversations()
  useChatStore().conversations.map((conversation)=>{
    if(conversation.type === 'private'){
      conversationIndividual.value = [...conversationIndividual.value, conversation]
    }else{
      conversationSpace.value = [...conversationSpace.value, conversation]
    }
  })
  isShowLoaderIndividualConversation.value = false
  isShowLoaderGroupConversation.value = false
}

const handleScroll = (e, handleLoadMore) => {
  if (
    Math.ceil(e.target.scrollTop) + e.target.clientHeight >=
    e.target.scrollHeight
  ) {
    handleLoadMore()
  }
}

const handleChangeSearchKey = (e) => {
  keySearch.value = e.target.value
}

const handleLoadMoreSpaces = ()=>{
  console.log('handle load more space')
}

const handleLoadMoreIndividual = ()=>{
  console.log('handle load more conversation individual');
}

onMounted(async () => {
  await setConversationIndividualAndSpace()
  
})
</script>

<style scoped>
.container-conversation > :last-child {
  margin-bottom: 0;
}

@media screen and (max-width: 767px) {
  .container-sidebar {
    position: absolute;
    width: 90px;
    height: 80vh;
    border-radius: 5px;
  }
  .container-sidebar:hover {
    width: 300px;
  }
  .container-sidebar:hover input {
    opacity: 1;
  }
  .container-sidebar:hover .heading-space {
    display: block;
  }
  .btn-add-space {
    position: absolute;
    left: 25px;
  }
  .container-sidebar:hover .btn-add-space {
    position: relative;
    left: 0;
  }
  .container-conversation {
    overflow-y: hidden;
  }
  .container-sidebar:hover .container-conversation {
    overflow-y: auto;
  }
  .conversation-content {
    display: none;
    max-width: 180px !important;
  }
  .container-sidebar:hover .conversation-content {
    display: block;
  }
}

@media screen and (min-width: 767px) {
  .container-sidebar input {
    opacity: 1;
  }

  .heading-space {
    display: block;
  }
}
</style>