<template>
  <div
    v-if="usersSeen.length > sizeSeenMessage"
    class="relative flex justify-end text-gray-500 py-2 right-[-4px]"
  >
    <avatar
      v-for="index in sizeSeenMessage"
      :key="index"
      :is-have-avatar="!!usersSeen[index - 1].avatar"
      :src-image="usersSeen[index - 1].avatar"
      :first-char="usersSeen[index - 1]?.fullName.charAt(0)"
      size="tiny"
      class="mr-1"
    />
    <div
      class="bg-gray-500 text-white text-[0.6rem] w-[16px] h-[16px] rounded-full flex justify-center items-center select-none"
    >
      {{ `+${usersSeen.length - sizeSeenMessage}` }}
    </div>
  </div>

  <div v-else class="relative flex justify-end text-gray-500 py-2 right-[-4px]">
    <avatar
      v-for="user in usersSeen"
      :key="user.id"
      :is-have-avatar="!!user.avatar"
      :src-image="user.avatar"
      :first-char="user?.fullName.charAt(0)"
      size="tiny"
      class="mr-1"
    />
  </div>
</template>

<script setup>

const props = defineProps({
  usersSeen: {
    type: Array,
    default: () => [],
  },
})

const sizeSeenMessage = 5
</script>

<style scoped></style>