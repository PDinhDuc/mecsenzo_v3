<template>
  <div
    :class="`flex-1 flex justify-center items-center ml-0 md:ml-4 text-gray-500 bg-white shadow-2xl rounded-[20px] 
    p-[36px] md:pl-[36px] overflow-x-hidden dark:bg-dark_bg_light
    ${showSidebarConversation ? 'pl-[100px]' : 'pl-[36px]'}`"
  >
    {{ t('chatSide.noConversation') }}
    <div
      :class="`noSelect cursor-pointer absolute w-[50px] 
      h-[50px] top-[30%] left-[0] flex sm:hidden
      justify-center items-center bg-white rounded-full 
      text-dark_primary shadow-xl hover:bg-dark_primary hover:text-white
      transition-all
      ${showSidebarConversation ? 'translate-x-[70px]' : 'translate-x-[-20px]'}`"
      @click="toggleSidebarMobile"
    >
      <font-awesome-icon v-if="!showSidebarConversation" icon="angles-right" class="ml-3" />
      <font-awesome-icon v-else icon="angles-left" class="ml-3" />
    </div>
  </div>
</template>

<script setup>
import { useHead } from '@unhead/vue'


const { t } = useI18n()


definePageMeta({
  layout: 'chat',
})


useHead({
  title: 'Mecsenzo',
})


const showSidebarConversation = useState('sidebarConversation', () => false)


const toggleSidebarMobile = () => {
  showSidebarConversation.value = !showSidebarConversation.value
}
</script>