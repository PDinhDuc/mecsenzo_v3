<template>
  <div class="w-full h-[450px] px-[50px]">
    <div class="relative w-[180px] cursor-pointer">
      <select
        v-model="filter"
        name=""
        class="appearance-none outline-none w-full px-[30px] py-[8px] rounded-full text-[1.1rem] border border-[#ff7200] shadow-xl cursor-pointer bg-white"
      >
        <option value="sent">
          {{ t('addFriendTab.invitationTab.sent') }}
        </option>
        <option value="pending">
          {{ t('addFriendTab.invitationTab.pending') }}
        </option>
        <option value="accepted">
          {{ t('addFriendTab.invitationTab.approved') }}
        </option>
      </select>
      <div class="absolute right-4 top-[25%] pointer-events-none">
        <font-awesome-icon icon="caret-down" class="text-dark_bg text-[1.2rem] ml-2" />
      </div>
    </div>
    <div
      v-if="isShowLoader"
      class="h-[200px] w-full flex justify-center items-center"
    >
      <LoaderSideConversation />
    </div>
    <div
      class="grid grid-cols-1 lg:grid-cols-2 gap-5 w-full my-6 overflow-y-auto max-h-[400px]"
    >
      <div
        v-for="invitation in getInvitations"
        :key="invitation.id"
        class="relative flex w-full justify-between items-center border-[2px] p-3 h-[86px]"
      >
        <div v-if="invitation" class="flex items-center">
          <Avatar
            :is-have-avatar="!!invitation.user.avatar"
            :src-image="invitation.user.avatar"
            :first-char="invitation.user.name.charAt(0)"
          />
          <p class="select-none text-[1.2rem] font-medium ml-3 dark:text-white">
            {{ invitation.user.name }}
          </p>
        </div>
        <div v-if="filter === 'accepted'">
          <ButtonIcon class="group" color="#333">
            <font-awesome-icon icon="ellipsis-vertical" class="dark:text-dark_text_light" />
            <div
              class="hidden absolute top-[100%] right-0 group-hover:flex flex-col w-[150px] shadow-xl z-[1000] bg-white after:content-[''] after:w-full after:absolute after:h-[30px] after:left-0 after:top-[-30px] after:bg-transparent"
            >
              <button
                class="text-[0.9rem] py-2 px-3 border-b-[1px] hover:bg-slate-300"
                @click="handleRedirectToConversation(invitation.user)"
              >
                {{ t('addFriendTab.invitationTab.chat') }}
              </button>
              <button
                class="text-[0.9rem] py-2 px-3 border-b-[1px] hover:bg-slate-300"
                @click="handleShowInfoFriend(invitation.user)"
              >
                {{ t('addFriendTab.invitationTab.info') }}
              </button>
              <button
                class="text-[0.9rem] py-2 px-3 border-b-[1px] hover:bg-slate-300"
                @click="handleUnfriend(invitation)"
              >
                {{ t('addFriendTab.invitationTab.unfriend') }}
              </button>
            </div>
          </ButtonIcon>
        </div>
        <div v-else-if="filter === 'sent'">
          <Button
            color="#f74242"
            :handle-click="() => handleActionRequestFriend(invitation.friendships.id, 'cancel')"
          >
            {{ t('addFriendTab.invitationTab.cancel') }}
          </Button>
        </div>
        <div v-else-if="filter === 'pending'">
          <Button
            color="#01c851"
            :handle-click="() => handleActionRequestFriend(invitation.friendships.id, 'accept')"
          >
            {{ t('addFriendTab.invitationTab.accept') }}
          </Button>
        </div>
      </div>
    </div>
    <ModalProfileFriend
      v-if="profileFriend"
      :user="profileFriend"
      @redirect-chat="handleRedirectToConversation"
      @close-modal="handleCloseModalProfile"
    />
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter, useNuxtApp } from '#app'
import Avatar from '~/components/Avatar.vue'
import LoaderSideConversation from '~/components/LoaderSideConversation.vue'
import ButtonIcon from '~/components/ButtonIcon.vue'
import Button from '~/components/Button.vue'
import ModalProfileFriend from '~/components/ModalProfileFriend.vue'
import { useI18n } from 'vue-i18n'



// Nuxt app context for i18n and router
const { t } = useI18n()
const router = useRouter()

// Reactive state
const filter = ref('pending')
const friends = computed(()=>useFriendStore().friendUser)
const invitationsReceived = computed(()=>useFriendStore().friendUserReceiver)
const invitationsSent = computed(()=>useFriendStore().friendUserSent)
const currentUser = ref(null)
const profileFriend = ref(null)
const isShowLoader = ref(true)


const getInvitations = computed(() => {
  if (filter.value === 'pending') {
    return invitationsReceived.value
  } else if (filter.value === 'sent') {
    return invitationsSent.value
  } else {
    return friends.value
  }
})

// Lifecycle hook
onMounted(async () => {
  await useFriendStore().fetchFriendOfUser(filter.value)
  console.log(invitationsSent.value);
  isShowLoader.value = false
})

// Methods
const handleActionRequestFriend = async (id, action) =>{
  await useFriendStore().handleActionRequestFriend(id,action)
  await useFriendStore().fetchFriendOfUser(filter.value)
}

const handleUnfriend = async (invitation) => {

}

const handleRedirectToConversation = async (partnerUser) => {
  const conversationsOfCurrentUser = await getIndividualConversationByMember(
    currentUser.value.email
  )

  const conversation = conversationsOfCurrentUser.filter((conversation) =>
    conversation.member.includes(partnerUser.email)
  )[0]

  router.push({
    name: `id___${t('locale')}`,
    params: { id: conversation.id },
  })
}

const handleShowInfoFriend = (user) => {
  profileFriend.value = user
}

const handleCloseModalProfile = () => {
  profileFriend.value = null
}

watch(filter, async (newValue)=>{
  await useFriendStore().fetchFriendOfUser(newValue)
})
</script>