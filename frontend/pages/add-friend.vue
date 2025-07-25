<template>
  <div class="max-w-[1200px] h-[calc(100vh-100px)] m-auto relative top-[100px]">
    <TabSide v-if="tabItems.length" :tabItems="tabItems" />
  </div>
</template>

<script setup>
import TabSide from '~/components/TabSide.vue'
import Invitation from '~/components/Invitation.vue'
import SearchFriend from '~/components/SearchFriend.vue'
import { useI18n } from 'vue-i18n'

const { t } = useI18n()

useHead({
  title: computed(() => t('title.addFriend')),
})

const tabItems = computed(() => [
  {
    title: t('addFriendTab.invitation'),
    component: Invitation,
  },
  {
    title: t('addFriendTab.search'),
    component: SearchFriend,
  },
])
</script>