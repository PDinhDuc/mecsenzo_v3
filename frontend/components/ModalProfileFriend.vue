<template>
  <div class="fixed top-0 left-0 bottom-0 right-0 overflow-hidden">
    <div class="w-full h-full bg-[rgba(0,0,0,0.5)]" @click="closeModal"></div>
    <div
      class="absolute top-[50%] left-[50%] translate-x-[-50%] translate-y-[-50%] w-[380px] min-h-[560px] sm:w-[480px] bg-white shadow-xl rounded-[25px] p-[32px] py-5 px-10"
    >
      <div class="py-2 border-b-2 border-b-[#212332]">
        <h3 class="text-[1.4rem] sm:text-[1.6rem] font-bold">
          {{ $t('profileModal.heading') }}
        </h3>
      </div>
      <div class="flex flex-col items-center justify-center">
        <div
          class="grid grid-cols-[20%_70%_10%] w-full mt-2 items-center mb-[12px]"
        >
          <p
            class="text-[1rem] sm:text-[1.2rem] font-semibold text-dark_primary"
          >
            {{ $t('profileModal.fullname') }}
          </p>
          <p
            class="appearance-none outline-none w-full px-3 text-[1rem] sm:text-[1.2rem] py-2 border-b-[2px] border-b-[rgb(100,116,139)] focus:border-b-[#ff7200]"
          >
            {{ user.fullName }}
          </p>
        </div>
        <div
          class="grid grid-cols-[20%_70%_10%] w-full mt-2 items-center mb-[12px]"
        >
          <p
            class="text-[1rem] sm:text-[1.2rem] font-semibold text-dark_primary"
          >
            {{ $t('profileModal.age') }}
          </p>
          <p
            class="appearance-none outline-none w-full px-3 text-[1rem] sm:text-[1.2rem] py-2 border-b-[2px] border-b-[rgb(100,116,139)] focus:border-b-[#ff7200]"
          >
            {{ user.age }}
          </p>
        </div>
        <div
          class="grid grid-cols-[20%_70%_10%] w-full mt-2 items-center mb-[12px]"
        >
          <p
            class="text-[1rem] sm:text-[1.2rem] font-semibold text-dark_primary"
          >
            {{ $t('profileModal.address') }}
          </p>
          <p
            class="appearance-none outline-none w-full px-3 text-[1rem] sm:text-[1.2rem] py-2 border-b-[2px] border-b-[rgb(100,116,139)] focus:border-b-[#ff7200]"
          >
            {{ user.address }}
          </p>
        </div>
        <Avatar
          :is-have-avatar="user && !!user.avatar"
          :src-image="user && user.avatar"
          :first-char="user && user.fullName.charAt(0)"
          size="large"
        />
      </div>
      <div class="w-full">
        <div
          class="mt-8 mx-auto w-full sm:w-[80%] flex justify-between items-end h-[50px]"
        >
          <Button
            color="#ff7200"
            size="large"
            :handle-click="handleRedirectChat"
          >
            {{ $t('addFriendTab.invitationTab.chat') }}
          </Button>
          <Button
            color="#ff7200"
            variant="outline"
            size="large"
            :handle-click="closeModal"
          >
            {{ $t('profileModal.close') }}
          </Button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useNuxtApp } from '#app'
import Avatar from '~/components/Avatar.vue'
import Button from '~/components/Button.vue'

// Nuxt app context for i18n
const { $t } = useNuxtApp()

// Props
const props = defineProps({
  user: {
    type: Object,
    default: () => ({}),
  },
})

// Emits
const emit = defineEmits(['redirect-chat', 'close-modal'])

// Methods
const handleRedirectChat = () => {
  emit('redirect-chat', props.user)
}

const closeModal = () => {
  emit('close-modal')
}
</script>