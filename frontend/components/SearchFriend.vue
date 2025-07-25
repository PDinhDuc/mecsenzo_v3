<template>
  <div class="w-[420px]">
    <div class="relative h-[43px] rounded-full">
      <input
        v-model="searchKey"
        autofocus
        type="text"
        class="appearance-none outline-none pl-[20px] py-[8px] pr-[calc(120px+12px)] w-full h-full rounded-full text-[1.1rem] border border-[#ff7200] focus:shadow-md focus:shadow-[#f69443]"
        :placeholder="t('addFriendTab.searchTab.inputPlaceholder')"
      />
      <Button class="absolute right-0 top-0" :handle-click="handleSearch">
        <font-awesome-icon icon="magnifying-glass" />
      </Button>
    </div>

    <div class="max-h-[350px] overflow-auto px-3 mt-[40px]">
      <div
        v-for="user in usersSearch"
        :key="user.id"
        class="flex h-[50px] w-full justify-between items-center mb-[40px]"
      >
        <div class="flex items-center">
          <Avatar
            :is-have-avatar="!!user.avatar"
            :src-image="user.avatar"
            :first-char="user.name.charAt(0)"
          />
          <p class="select-none text-[1.2rem] font-medium ml-3 dark:text-white">
            {{ user.name }}
          </p>
        </div>
        <div>
          <div
            v-if="user.status === 'accepted'"
            class="flex items-center justify-center min-w-[120px] px-[12px] py-[6px] text-[1.2rem] text-white rounded-[20px] bg-[#33b5e7] border border-[#33b5e7] select-none"
          >
            {{ t('addFriendTab.searchTab.friend') }}
          </div>
          <Button
            v-else-if="user.status === 'pending'"
            color="#f74242"
            :handle-click="() => handleCancelInvitation(user.email)"
          >
            {{ t('addFriendTab.searchTab.cancel') }}
          </Button>
          <!-- <Button
            v-else-if="isPendingInvitationReceived(user)"
            color="#01c851"
            :handle-click="() => handleAcceptInvitation(user.email)"
          >
            {{ t('addFriendTab.searchTab.accept') }}
          </Button> -->
          <Button
            v-else
            color="#01c851"
            :handle-click="() => handleSendInvitation(user.email)"
          >
            {{ t('addFriendTab.searchTab.invite') }}
          </Button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useI18n } from 'vue-i18n'
import Button from './Button.vue'
import Avatar from './Avatar.vue'
import { useDebounceFn } from '@vueuse/core'
import { useUserStore } from '@/stores/user'

const getCurrentEmail = useState('currentUserEmail')

const { t } = useI18n()

const searchKey = ref('')
const usersSearch = computed(()=>useUserStore().searchUserResult)
const pendingInvitationSent = ref([])
const pendingInvitationReceived = ref([])
const friendOfCurrentUser = ref([])

function isPendingInvitationSent(user) {
  return pendingInvitationSent.value.some((i) => i.receiverEmail === user.email)
}

function isPendingInvitationReceived(user) {
  return pendingInvitationReceived.value.some((i) => i.senderEmail === user.email)
}

function isFriend(user) {
  return friendOfCurrentUser.value.includes(user.email)
}

const handleSearch = useDebounceFn(async () => {
  await useUserStore().searchUser(searchKey.value)
}, 300)

async function handleCancelInvitation(receiver) {
  const invitation = await getPendingInvitationBySenderReceiver(getCurrentEmail.value, receiver)
  await deleteInvitation(invitation.id)
  pendingInvitationSent.value = pendingInvitationSent.value.filter(
    (invite) => invite.id !== invitation.id
  )
}

async function handleAcceptInvitation(sender) {
  const invitation = await getPendingInvitationBySenderReceiver(sender, getCurrentEmail.value)
  await acceptInvitation(invitation)

  const currentUser = await getUserByEmail(getCurrentEmail.value)
  const senderUser = await getUserByEmail(sender)

  await addNewFriend(currentUser, sender)
  await addNewFriend(senderUser, currentUser.email)

  friendOfCurrentUser.value.push(sender)

  createNotify(
  console.log('create notification')
  )

  await createConversation({
    type: 'individual',
    member: [getCurrentEmail.value, sender],
    seen: [],
    isTyping: [],
    colorChat: '#0084ff',
    messages: [],
    thumb: null,
    name: '',
    accountHost: null,
    lastMessage: null,
  })
}

async function handleSendInvitation(receiver) {
  const newInvitation = await createInvitation(getCurrentEmail.value, receiver)
  const fullInvitation = await getInvitationById(newInvitation.id)
  pendingInvitationSent.value.push(fullInvitation)

  const currentUser = await getUserByEmail(getCurrentEmail.value)

  await createNotify(
    receiver,
    currentUser.fullName,
    'inviteFriend',
    routers.ADD_FRIEND_PAGE
  )
}
watch(searchKey, (newValue)=>{
  handleSearch(newValue)
  console.log(usersSearch.value);
  
})
</script>