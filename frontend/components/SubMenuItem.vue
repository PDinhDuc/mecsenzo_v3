<template>
  <div
    v-if="type === 'button'"
    class="flex p-3 items-center border-b-[1px] cursor-pointer border-b-[#939496] hover:border-b-[#ff7200] group transition-all duration-300"
    @click.prevent="handleClickBtn"
  >
    <div
      class="w-[30px] text-[1.2rem] text-[#939496] group-hover:text-dark_primary transition-all duration-300 dark:text-dark_text_strong"
    >
      <font-awesome-icon :icon="icon" />
    </div>
    <div>
      <p
        class="text-[1.2rem] text-[#939496] group-hover:text-dark_primary transition-all duration-300 select-none dark:text-dark_text_strong"
      >
        {{ content }}
      </p>
    </div>
    <div
      v-if="isDarkMode"
      class="relative w-[68px] h-[30px] bg-[#939496] rounded-full ml-5 dark:bg-blue-400"
    >
      <div
        class="absolute top-[2px] left-1 w-[26px] h-[26px] bg-slate-600 rounded-full dark:left-[38px] dark:bg-white transform-all duration-300"
      ></div>
    </div>
  </div>

  <NuxtLink
    v-else-if="type === 'nuxt-link'"
    class="flex p-3 items-center border-b-[1px] cursor-pointer border-b-[#939496] hover:border-b-[#ff7200] group transition-all duration-300"
    :to="to"
    @click.native="handleCloseNotify"
  >
    <div
      class="w-[30px] text-[1.2rem] text-[#939496] group-hover:text-dark_primary transition-all duration-300 dark:text-dark_text_strong"
    >
      <font-awesome-icon :icon="icon" />
    </div>
    <div>
      <p
        class="text-[1.2rem] text-[#939496] group-hover:text-dark_primary transition-all duration-300 dark:text-dark_text_strong"
      >
        {{ content }}
      </p>
    </div>
    <div
      v-if="isDarkMode"
      class="relative w-[68px] h-[30px] bg-[#939496] rounded-full ml-5 dark:bg-blue-400"
    >
      <div
        class="absolute top-[2px] left-1 w-[26px] h-[26px] bg-slate-600 rounded-full dark:left-[38px] dark:bg-white transform-all duration-300"
      ></div>
    </div>
  </NuxtLink>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue'

const emit = defineEmits(['closeNotify'])

const props = defineProps({
  icon: {
    type: String,
    default: 'envelope',
  },
  content: {
    type: String,
    default: '',
  },
  handleClickSubMenuItem: {
    type: Function,
    default: () => () => {},
  },
  isDarkMode: {
    type: Boolean,
    default: false,
  },
  type: {
    type: String,
    default: 'button',
  },
  to: {
    type: Object,
    default: () => ({ path: '/' }),
  },
})

function handleCloseNotify() {
  emit('closeNotify')
}

function handleClickBtn() {
  handleCloseNotify()
  props.handleClickSubMenuItem()
}
</script>