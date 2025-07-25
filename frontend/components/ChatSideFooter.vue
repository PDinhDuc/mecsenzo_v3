<template>
  <div class="h-[10%] w-full flex items-center">
    <div class="flex justify-center items-center">
      <div
        class="relative h-[40px] w-[40px] rounded-full mr-2 flex items-center justify-center hover:bg-slate-200 hover:bg-[rgba(255,255,255,0.1)]"
      >
        <input
          id="input-image"
          ref="inputImage"
          type="file"
          class="opacity-0 z-[-1] absolute"
          @change="handleSetFileImageInput"
        />
        <label for="input-image" class="cursor-pointer">
          <font-awesome-icon
            icon="image"
            class="text-[1.2rem]"
            :style="{ color: colorBtn }"
          />
        </label>
      </div>
      <ButtonIcon
        :color="colorBtn"
        class="mr-1"
        @btn-icon-click="handleShowPreviewChatVoice"
      >
        <font-awesome-icon icon="microphone" class="text-[1.2rem]" />
      </ButtonIcon>
    </div>
    <form class="flex-1 flex items-center" @submit.prevent="handleSendMessage">
      <div class="relative flex-1 h-full">
        <input
          ref="inputMessage"
          v-focus
          type="text"
          class="w-full px-3 py-2 pr-[60px] appearance-none outline-none rounded-full bg-slate-200 dark:bg-dark_bg_message dark:text-white"
          :disabled="isDisableInputMessage"
          :placeholder="t('chatSide.inputPlaceholder')"
          @focus="handleFocusInputMessage"
          @blur="handleBlurInputMessage"
          @input="updateInputMessage"
        />
        <div
          class="absolute right-0 top-0 h-full w-[50px] flex justify-center items-center cursor-pointer noSelect"
          @click="toggleEmojiPicker"
        >
          <img
            src="~/assets/images/icon.png"
            alt="emoji"
            class="w-[28px] object-fill"
          />
          <div
            v-if="isClient && isShowIconPicker"
            class="absolute w-[50px] h-[50px]"
          >
            <ClientOnly>
              <Picker
                class="absolute right-[-50px] md:right-[0px] translate-y-[calc(-100%)] h-[330px] z-[999]"
                :showPreview="false"
                :showSkinTones="false"
                :perLine="9"
                :emojiSize="24"
                :theme="'light'"
                @emoji-select="handleSelectEmoji"
              />
            </ClientOnly>
          </div>
        </div>
      </div>
      <ButtonIcon type="submit" :color="colorBtn" class="ml-1">
        <font-awesome-icon icon="paper-plane" class="text-[1.2rem]" />
      </ButtonIcon>
    </form>
  </div>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue'
import { useNuxtApp } from '#app'
import ButtonIcon from '~/components/ButtonIcon.vue'
// import { ClientOnly } from '#components'
// import { Picker } from 'emoji-mart-vue'
// import 'emoji-mart-vue/css/emoji-mart.css'
import { useI18n } from 'vue-i18n'
import { useLocalePath } from '#i18n'

const { t } = useI18n()
const localePath = useLocalePath()


// Props
const props = defineProps({
  colorBtn: {
    type: String,
    default: '#0084ff',
  },
  isDisableInputMessage: {
    type: Boolean,
    default: false,
  },
})

// Emits
const emit = defineEmits([
  'set-file-image-input',
  'show-preview-chat-voice',
  'send-message',
  'focus-input-message',
  'blur-input-message',
  'select-emoji',
  'update:input-message',
])

// Reactive state
const isShowIconPicker = ref(false)
const inputImage = ref(null)
const inputMessage = ref(null)


// Client-side check
const isClient = ref(false)

// Methods
const toggleEmojiPicker = () => {
  isShowIconPicker.value = !isShowIconPicker.value
}

const handleSetFileImageInput = (e) => {
  const fileImage = e.target.files[0]
  emit('set-file-image-input', fileImage)
  if (inputMessage.value) inputMessage.value.value = ''
}

const handleShowPreviewChatVoice = () => {
  emit('show-preview-chat-voice')
}

const handleSendMessage = () => {
  if (inputMessage.value) inputMessage.value.value = ''
  inputMessage.value.focus()
  emit('send-message')
}

const handleFocusInputMessage = () => {
  emit('focus-input-message')
}

const handleBlurInputMessage = () => {
  emit('blur-input-message')
}

// const handleSelectEmoji = (emoji) => {
//   if (inputMessage.value) {
//     inputMessage.value.value += emoji.native
//     inputMessage.value.focus()
//   }
//   emit('select-emoji', emoji)
// }

const updateInputMessage = (e) => {
  emit('update:input-message', e.target.value)
}

// Lifecycle hooks and watchers

</script>

<style></style>