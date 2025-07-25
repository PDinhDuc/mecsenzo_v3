<template>
  <div
    v-if="footerListMessageData"
    class="relative flex text-gray-500 py-2 right-[-4px] justify-between"
  >
    <ShowTyping :user-typing="footerListMessageData.userTyping" />
    <ShowSeenMessage :users-seen="footerListMessageData.usersSeen" />
  </div>
</template>

<script setup>
import ShowTyping from '~/components/ShowTyping.vue'
import ShowSeenMessage from '~/components/ShowSeenMessage.vue'

// Props
const props = defineProps({
  footerListMessageData: {
    type: Object,
    default: () => ({}),
  },
})
</script>