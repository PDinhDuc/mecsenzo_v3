<template>
  <div
    class="relative m-auto mt-[100px] w-[400px] md:w-[500px] shadow-xl p-10 rounded-[25px] bg-white animate-[fadeUp_0.5s]"
  >
    <div class="flex justify-center mb-4">
      <h3
        class="text-dark_primary text-[1.6rem] md:text-[1.5rem] font-bold tracking-wider uppercase select-none"
      >
        {{ t('auth.register') }}
      </h3>
    </div>
    <form @submit.prevent="onSubmit">
      <div class="mb-6">
        <input
          name="fullName"
          type="text"
          rules="required|max:30"
          v-model="fullName"
          class="form-control block w-full px-4 py-2 text-xl font-normal text-gray-700 bg-white bg-clip-padding border-b-[2px] border-solid border-gray-300 transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-dark_primary focus:outline-none"
          :placeholder="t('auth.fullNamePlaceholder')"
        />
      </div>
      <div class="mb-6">
        <input
          name="email"
          type="text"
          rules="required|emailRule"
          v-model="email"
          class="form-control block w-full px-4 py-2 text-xl font-normal text-gray-700 bg-white bg-clip-padding border-b-[2px] border-solid border-gray-300 transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-dark_primary focus:outline-none"
          placeholder="Email"
        />
      </div>
      <div class="mb-6">
        <input
          name="password"
          type="password"
          rules="required"
          v-model="password"
          class="form-control block w-full px-4 py-2 text-xl font-normal text-gray-700 bg-white bg-clip-padding border-b-[2px] border-solid border-gray-300 transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-dark_primary focus:outline-none"
          :placeholder="t('auth.password')"
        />
      </div>
      <div class="mb-6">
        <input
          name="rePassword"
          type="password"
          :rules="{ required: true, confirmed: '@password' }"
          v-model="rePassword"
          class="form-control block w-full px-4 py-2 text-xl font-normal text-gray-700 bg-white bg-clip-padding border-b-[2px] border-solid border-gray-300 transition ease-in-out m-0 focus:text-gray-700 focus:bg-white focus:border-dark_primary focus:outline-none"
          :placeholder="t('auth.rePasswordPlaceholder')"
        />
      </div>
      <div class="text-center lg:text-left">
        <button
          type="submit"
          class="w-full inline-block px-7 py-3 bg-dark_primary text-white font-medium text-sm leading-snug uppercase rounded shadow-md hover:opacity-75 hover:shadow-lg focus:opacity-75 focus:shadow-lg focus:outline-none focus:ring-0 active:shadow-lg transition duration-150 ease-in-out"
        >
          {{ t('auth.register') }}
        </button>
        <p class="text-sm font-semibold mt-2 pt-1 mb-0">
          {{ t('auth.haveAccount') }}
          <NuxtLink
            :to="localePath({ name: 'login' })"
            class="text-blue-600 hover:text-blue-700 focus:text-blue-700 transition duration-200 ease-in-out"
          >
            {{ t('auth.login') }}
          </NuxtLink>
        </p>
      </div>
    </form>
  </div>
</template>

<script setup>

import { ref } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router'
import { useI18n } from 'vue-i18n'
import { useLocalePath } from '#i18n'

definePageMeta({
  layout: 'auth',
})

const { t } = useI18n()
const localePath = useLocalePath()
const auth = useAuthStore()
const router = useRouter()

const fullName = ref('')
const email = ref('')
const password = ref('')
const error = ref(null)


const onSubmit = async () => {
  error.value = null
  try {
    await auth.register(fullName.value, email.value, password.value)
    router.push('/')
  } catch (err) {
    error.value = err?.statusMessage || 'Đăng ký thất bại'
  }
}
</script>