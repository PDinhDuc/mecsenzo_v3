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
            v-else-if="user.status === 'pending' && user.user_id === user.id"
            color="#f74242"
            :handle-click="() => handleActionRequestFriend(user.friendship_id, 'accept')"
          >
            {{ t('addFriendTab.searchTab.accept') }}
          </Button>
          <Button
            v-else-if="user.status == null"
            color="#01c851"
            :handle-click="() => handleActionRequestFriend(user.id, 'invitation')"
          >
            {{ t('addFriendTab.searchTab.invite') }}
          </Button>
          <Button
            v-else
            color="#01c851"
            :handle-click="() => handleActionRequestFriend(user.friendship_id, 'cancel')"
          >
            {{ t('addFriendTab.searchTab.cancel') }}
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
import { useFriendStore } from '@/stores/friend'

const { t } = useI18n()

const searchKey = ref('')
const usersSearch = computed(()=>useFriendStore().searchFriendResult)

const handleSearch = useDebounceFn(async () => {
  await useFriendStore().searchFriend(searchKey.value)
  console.log(usersSearch.value);
}, 300)

async function handleActionRequestFriend(id, action) {
  await useFriendStore().handleActionRequestFriend(id, action)
  await useFriendStore().searchFriend(searchKey.value)
}
watch(searchKey,handleSearch)
</script>