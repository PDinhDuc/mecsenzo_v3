<template>
  <div
    class="absolute w-full h-[85%] bg-white bottom-[10px] md:bottom-0 left-0 z-[1000] px-[20px] dark:bg-dark_bg_light"
  >
    <div class="container-conversation h-[90%] my-5 overflow-y-auto overflow-x-hidden">
      <div>
        <nuxt-link
          v-for="conversation in resultSearch"
          :id="conversation.id"
          :key="conversation.id"
          :to="{
            path: `/`,
            params: { id: conversation.id },
            name: `id___${locale}`,
          }"
          class="h-[54px] mb-3 flex items-center cursor-pointer hover:bg-slate-200 hover:bg-[rgba(255,255,255,0.1)]"
        >
          <div class="relative">
            <avatar
              v-if="conversation.type === 'individual'"
              :is-have-avatar="!!conversation.partnerUser.avatar"
              :src-image="conversation.partnerUser.avatar"
              :first-char="conversation.partnerUser.fullName.charAt(0)"
            />
            <avatar
              v-else
              :is-have-avatar="!!conversation.thumb"
              :src-image="conversation.thumb"
              :first-char="conversation.name.charAt(0)"
            />
            <div
              v-if="conversation.type === 'individual'"
              :class="`absolute w-[12px] h-[12px] rounded-full bottom-0 right-0 
              ${getClassIsOnline(conversation.partnerUser)}`"
            ></div>
          </div>
          <div class="conversation-content ml-4">
            <p
              :class="`select-none truncate text-ellipsis max-w-[180px] md:max-w-[120px] lg:max-w-[180px] h-[1.4rem] dark:text-dark_text_strong
              ${getClassNameNotSeen(conversation)}`"
            >
              {{
                conversation.type === 'individual'
                  ? conversation.partnerUser.fullName
                  : conversation.name
              }}
            </p>
            <p
              :class="`select-none truncate text-ellipsis text-[0.9rem] max-w-[180px] 
              md:max-w-[120px] lg:max-w-[180px] h-[1.4rem] dark:text-dark_text_light
              ${getClassNewMsgNotSeen(conversation)}`"
            >
              <span v-if="getLastMessage(conversation).type === 'text'">
                {{ getLastMessage(conversation).content }}
              </span>
              <span v-else-if="getLastMessage(conversation).type === 'audio'">
                {{ $t('sidebarConversation.lastMsgAudio') }}
              </span>
              <span v-else-if="getLastMessage(conversation).type === 'image'">
                {{ $t('sidebarConversation.lastMsgImage') }}
              </span>
            </p>
          </div>
        </nuxt-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue'
import { useI18n } from 'vue-i18n'

// Nếu bạn dùng Pinia thì thay bằng: import { useAccountStore } from '~/stores/account'
const getKeySearch = useState('searchSidebarConversationKey')
const getCurrentEmail = useState('currentUserEmail')

const resultSearchAll = ref([])
const resultSearch = ref([])

const { locale, t } = useI18n()

// Methods
const getClassNameNotSeen = (conversation) =>
  conversation.seen.includes(getCurrentEmail.value) ? '' : 'font-medium'

const getClassNewMsgNotSeen = (conversation) =>
  conversation.seen.includes(getCurrentEmail.value)
    ? 'text-gray-400'
    : 'text-dark_primary font-medium'

const getClassIsOnline = (partnerUser) =>
  partnerUser.isActive ? 'bg-success' : 'bg-gray-300'

const getLastMessage = (conversation) =>
  conversation.lastMessage ? conversation.lastMessage : ''

// Watch search key
watch(getKeySearch, (newValue) => {
  if (resultSearchAll.value) {
    resultSearch.value = filterByName(resultSearchAll.value, newValue)
  }
})

// Fetch conversation data
onMounted(async () => {
  const conversationOfCurrentUser = await getConversationOfUser(getCurrentEmail.value)

  if (conversationOfCurrentUser) {
    const individualChat = conversationOfCurrentUser.filter((c) => c.type === 'individual')
    const groupChat = conversationOfCurrentUser.filter((c) => c.type === 'group')

    await mergeUseIndividualConversation(individualChat, getCurrentEmail.value)

    resultSearchAll.value = [...individualChat, ...groupChat]
    resultSearch.value = resultSearchAll.value
  }
})
</script>