<template>
  <div>
    <div class="fixed top-0 left-0 bottom-0 right-0 overflow-hidden z-[1000]">
      <div
        class="w-full h-full bg-[rgba(0,0,0,0.5)] flex justify-center items-center"
      >
        <div v-if="infoVideoCall" class="w-[60%] h-[60%]">
          <div class="flex justify-center items-center h-[90%]">
            <div class="flex flex-col items-center">
              <div>
                <Avatar
                  :is-have-avatar="!!infoVideoCall.avatar"
                  :src-image="infoVideoCall.avatar"
                  :first-char="
                    infoVideoCall.name && infoVideoCall.name.charAt(0)
                  "
                  size="large"
                />
              </div>
              <div class="flex flex-col items-center">
                <p class="select-none text-[1.2rem] text-white font-medium">
                  {{ infoVideoCall.name }}
                </p>
                <p
                  v-if="currentMessageVideoCall.status === 'pending'"
                  class="text-white text-[1.2rem] mt-4 font-light"
                >
                  {{ $t('modalCallVideo.calling') }}
                </p>
                <p v-else class="text-white text-[1.2rem] mt-4 font-light">
                  {{ $t('modalCallVideo.busy') }}
                </p>
              </div>
            </div>
          </div>
          <div>
            <div class="m-auto w-[80%] md:w-[60%] flex justify-center">
              <button
                v-if="currentMessageVideoCall.status === 'pending'"
                class="w-[68px] h-[68px] rounded-full bg-danger text-[1.2rem] text-white"
                @click="handleCancelVideoCall"
              >
                <font-awesome-icon icon="xmark" />
              </button>
              <button
                v-else
                class="w-[68px] h-[68px] rounded-full bg-danger text-[1.2rem] text-white"
                @click="handleCloseModalVideoCall"
              >
                -
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useNuxtApp } from '#app'
import Avatar from '~/components/Avatar.vue'

// Nuxt app context for i18n
const { $t } = useNuxtApp()

// Props
const props = defineProps({
  infoVideoCall: {
    type: Object,
    default: () => null,
  },
  currentMessageVideoCall: {
    type: Object,
    default: () => null,
  },
})

// Emits
const emit = defineEmits(['cancel-video-call', 'close-video-call'])

// Methods
const handleCancelVideoCall = () => {
  handleCloseModalVideoCall()
  emit('cancel-video-call')
}

const handleCloseModalVideoCall = () => {
  emit('close-video-call')
}
</script>