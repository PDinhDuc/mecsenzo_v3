<template>
  <div class="flex items-center">
    <div v-if="userTyping.length > 0">
      <div
        v-for="user in userTyping"
        :key="user.id"
        class="flex justify-center items-center text-[0.8rem]"
      >
        <avatar
          :is-have-avatar="!!user.avatar"
          :src-image="user.avatar"
          :first-char="user?.fullName.charAt(0)"
          size="small"
          class="mr-1 mt-1"
        />
        <div class="dot-typing animate-[dotTyping_0.8s_ease-in_infinite] ml-4"></div>
      </div>
    </div>
  </div>
</template>

<script setup>
const props = defineProps({
  userTyping: {
    type: Array,
    default: () => [],
  },
})
</script>

<style scoped>
/* Bạn có thể bổ sung animation nếu chưa có sẵn */
@keyframes dotTyping {
  0% {
    box-shadow: 0 0, 8px 0, 16px 0;
  }
  25% {
    box-shadow: 0 0, 8px 0, 16px 8px;
  }
  50% {
    box-shadow: 0 0, 8px 8px, 16px 8px;
  }
  75% {
    box-shadow: 0 0, 8px 0, 16px 8px;
  }
  100% {
    box-shadow: 0 0, 8px 0, 16px 0;
  }
}

.dot-typing {
  width: 4px;
  height: 4px;
  background-color: #888;
  border-radius: 50%;
  box-shadow: 0 0, 8px 0, 16px 0;
}
</style>