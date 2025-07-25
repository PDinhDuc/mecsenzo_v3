<template>
  <NuxtLink :to="link" @click="handleCloseNotify">
    <div class="py-3 px-1 cursor-pointer border-b-[1px] border-b-[#939496]">
      <p class="leading-[1.4rem] dark:text-dark_text_light">
        <span class="font-semibold dark:text-dark_text_strong">{{ sender }}</span>
        {{ t(getQueryTextNotifyContent) }}
      </p>
      <p class="mt-1 text-[0.9rem] italic text-gray-400">
        {{ getTimeNotify }}
      </p>
    </div>
  </NuxtLink>
</template>

<script setup>
import { computed } from 'vue'
import { useNuxtApp } from '#app'
import { useI18n } from 'vue-i18n'

// Nuxt app context for i18n
const { t } = useI18n()
const locale = useNuxtApp().$i18n.locale

const link = computed(() => ({
  path: 'add-friend',
  name: `add-friend___${locale.value}`,
}))

// Props
const props = defineProps({
  sender: {
    type: String,
    default: '',
  },
  type: {
    type: String,
    default: '',
  },
  timestamp: {
    type: Object,
    default: () => null,
  }
})

// Emits
const emit = defineEmits(['closeNotify'])

// Computed properties
const getQueryTextNotifyContent = computed(() => `notify.${props.type}`)

const getTimeNotify = computed(() => {
  const time = transformTimeStampToString(props.timestamp?.seconds)
  return `${time.value} ${t('time.' + time.type)} ${t('time.ago')}`
})

// Methods
const handleCloseNotify = () => {
  emit('closeNotify')
}
</script>