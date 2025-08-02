<template>
  <header
    class="w-full h-[68px] bg-white fixed top-0 shadow-xl z-[1000] dark:bg-dark_bg_nav dark:shadow-gray-500 dark:shadow-md"
  >
    <div
      class="flex items-center max-w-[1200px] m-auto h-full justify-between px-4 xl:px-0"
    >
      <NuxtLink
        :to="{ path: '/' }"
        class="flex items-center"
        @click="handleCloseNotifyAndMenu"
      >
        <img
          src="~/assets/images/logo.png"
          alt="logo"
          class="w-[68px] h-full"
        />
        <h1
          class="text-dark_primary text-[1.2rem] md:text-[1.5rem] font-bold tracking-wider"
        >
          MECSENZO
        </h1>
      </NuxtLink>
      <div class="h-full">
        <div class="relative flex items-center h-full">
          <div
            class="noSelect relative flex items-center justify-center w-[40px] h-[40px] mr-3 sm:mr-6 rounded-full cursor-pointer bg-slate-100 hover:bg-slate-200 dark:text-white dark:bg-[rgba(255,255,255,0.3)] dark:hover:bg-[rgba(255,255,255,0.5)]"
            @click="handleToggleNotify"
          >
            <div
              v-if="unreadCount > 0"
              class="absolute top-[-4px] right-[-4px] w-[16px] h-[16px] bg-red-500 rounded-full text-white text-[0.8rem] flex items-center justify-center"
            >
              {{
                unreadCount
              }}
            </div>
            <font-awesome-icon icon="bell" class="text-[1.2rem]" />
          </div>
          <div
            v-if="isShowNotify"
            class="notify-container absolute w-[350px] max-h-[70vh] overflow-y-auto px-4 py-4 bg-white shadow-xl top-[110%] right-[calc(100%-40px)] rounded-[20px] after:content-[''] after:w-full after:h-[20px] after:bg-slate-500 after:absolute after:top-[-20px] after:left-0 after:bg-transparent z-[100] origin-top transition-all duration-150 ease-in-out animate-[leftIn_0.3s_ease-in-out] md:animate-[scaleDown_0.15s_ease-in-out] dark:bg-dark_bg_nav"
            @scroll="handleScroll"
          >
            <div v-for="(notify, index) in notifications" :key="index">
              <NotifyItem
                :sender="notify.data.from_user_name"
                :type="notify.data.type"
                :timestamp="notify.created_at"
                :link="{
                  path: `#`,
                }"
                @closeNotify="handleCloseNotify"
              />
            </div>
          </div>
          <div
            class="noSelect mr-3 sm:mr-6 flex flex-col justify-center items-center w-[40px] h-[40px] bg-slate-100 hover:bg-slate-200 rounded-full cursor-pointer dark:text-white dark:bg-[rgba(255,255,255,0.3)] dark:hover:bg-[rgba(255,255,255,0.5)]"
            @click="handleToggleChooseLang"
          >
            <font-awesome-icon icon="earth-asia" class="text-[1.2rem]" />
          </div>
          <div
            v-if="isShowChooseLang"
            class="notify-container absolute w-[300px] max-h-[70vh] overflow-y-auto px-4 py-4 bg-white shadow-xl top-[110%] right-[calc(100%-100px)] rounded-[20px] after:content-[''] after:w-full after:h-[20px] after:bg-slate-500 after:absolute after:top-[-20px] after:left-0 after:bg-transparent z-[100] origin-top transition-all duration-150 ease-in-out animate-[leftIn_0.3s_ease-in-out] md:animate-[scaleDown_0.15s_ease-in-out] dark:bg-dark_bg_nav"
          >
            <div
              v-for="locale in availableLocales"
              :key="locale.code"
              class="flex justify-between items-center py-3 px-1 cursor-pointer border-b-[1px] border-b-[#939496]"
            >
              <NuxtLink
                to="#"
                class="flex-1 dark:text-dark_text_strong"
                @click="handleToggleChooseLang(locale.code)"
              >
                {{ locale.name }}
              </NuxtLink>
              <div
                v-if="$i18n.locale === locale.code"
                class="flex justify-center items-center w-[20px] h-[20px] bg-[#33b5e7] rounded-full"
              >
                <div class="w-[9px] h-[9px] bg-white rounded-full"></div>
              </div>
            </div>
          </div>
          <div
            ref="mainMenu"
            class="main-menu flex justify-center items-center md:cursor-pointer shadow-xl md:shadow-none"
          >
            <div v-if="isShowLoaderUser" class="h-full">
              <LoaderUser />
            </div>
            <div
              v-if="user"
              class="w-full flex justify-center items-center py-3 md:py-0 dark:bg-dark_bg_nav dark:text-dark_text_strong"
            >
              <Avatar
                :is-have-avatar="!!user.avatar"
                :src-image="user.avatar"
                :first-char="user && user.name.charAt(0)"
              />
              <p
                class="text-dark_bg leading-[1.4rem] text-[1.2rem] h-[1.4rem] ml-4 select-none dark:text-white"
              >
                {{ user && user.name }}
              </p>
              <font-awesome-icon
                icon="caret-down"
                class="hidden text-dark_bg text-[1.2rem] ml-2 md:block dark:text-white"
              />
            </div>
            <div class="sub-menu absolute w-[300px] px-4 py-4 bg-white shadow-xl
             top-[110%] right-0 rounded-[20px] after:content-[''] after:w-full after:h-[20px]
              after:bg-slate-500 after:absolute after:top-[-20px] after:left-0 after:bg-transparent
               z-[100] origin-top transition-all duration-150 ease-in-out dark:bg-dark_bg_nav">
              <SubMenuItem
                icon="user"
                :content="t('nav.profile')"
                type="button"
                :handle-click-sub-menu-item="handleShowProfile"
                @closeNotify="handleCloseNotifyAndMenu"
              />
              <SubMenuItem
                icon="plus"
                :content="t('nav.addFriend')"
                :to="localePath('add-friend')"
                type="nuxt-link"
                @closeNotify="handleCloseNotifyAndMenu"
              />
              <SubMenuItem
                icon="chart-line"
                :content="t('nav.statistic')"
                :to="localePath('/statistic')"
                type="nuxt-link"
                @closeNotify="handleCloseNotifyAndMenu"
              />
              <SubMenuItem
                icon="moon"
                :content="t('nav.darkMode')"
                :is-dark-mode="true"
                type="button"
                :handle-click-sub-menu-item="handleChangeModeDarkTheme"
                @closeNotify="handleCloseNotifyAndMenu"
              />
              <SubMenuItem
                icon="arrow-right-from-bracket"
                :content="t('nav.logout')"
                type="button"
                :handle-click-sub-menu-item="handleLogout"
                @closeNotify="handleCloseNotifyAndMenu"
              />
            </div>
          </div>
          <div
            ref="btnHamburger"
            class="btn-hamburger noSelect flex faces
            flex-col justify-center items-center w-[40px] h-[40px] bg-slate-200 rounded-full md:hidden cursor-pointer"
            @click="handleToggleMenu"
          >
            <span></span>
            <span></span>
            <span></span>
          </div>
        </div>
      </div>
    </div>
    <div
      v-if="isShowModalProfile"
      class="absolute w-[100vw] h-[100vh] top-0 left-0 z-[100]"
    >
      <ModalProfile
        @closeModal="closeModalProfile"
        @update:user="handleUpdateUser"
        @set-percent-upload="setPercentUploadAvatar"
        @clear-percent-upload="setPercentUploadAvatar(null)"
      />
      <div
        v-if="percentUploadAvatar"
        class="absolute top-0 left-0 bottom-0 right-0 w-[100vw] h-[100vh] overflow-hidden z-[1000] bg-[rgba(0,0,0,0.5)]"
      >
        <ProgressLoader size="large" :percent="percentUploadAvatar" />
      </div>
    </div>
    <VideoCallPending />
  </header>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useNuxtApp, useRouter } from '#app'
import Avatar from '~/components/Avatar.vue'
import SubMenuItem from '~/components/SubMenuItem.vue'
import ProgressLoader from '~/components/ProgressLoader.vue'
import ModalProfile from '~/components/ModalProfile.vue'
import NotifyItem from '~/components/NotifyItem.vue'
import LoaderUser from '~/components/LoaderUser.vue'
import { useI18n } from 'vue-i18n'
// import { useLocalePath, useSwitchLocalePath } from '#i18n'
import { useAuthStore } from '@/stores/auth'
import { useNotifications } from '~/composables/useNotifications'

const { notifications, unreadCount, listen, markAllAsRead } = useNotifications()
const { locale, setLocale, locales } = useI18n()

// Nuxt app context
const { $i18n  } = useNuxtApp()
const router = useRouter()

const { t } = useI18n()
const localePath = useLocalePath()

// Reactive state
const user = ref(null)
const isShowModalProfile = ref(false)
const isShowNotify = ref(false)
const isShowChooseLang = ref(false)
const percentUploadAvatar = ref(null)
const isShowLoaderUser = ref(true)
const mainMenu = ref(null)
const btnHamburger = ref(null)
const currentLoadNotify = ref(1)

const availableLocales = computed(() => {
  return locales.value
})

const splitBackslashNotifyLink = (link) => {
  return link.substr(1)
}

// Methods
const setUser = async () => {
  user.value = useAuthStore().user
  isShowLoaderUser.value = false
}

const handleLogout = () => {
  useAuthStore().logout()
  navigateTo('/login')
}

const handleToggleMenu = () => {
  handleCloseNotify()
  handleCloseChooseLang()
  if (mainMenu.value && btnHamburger.value) {
    mainMenu.value.classList.toggle('main-menu--show')
    btnHamburger.value.classList.toggle('btn-hamburger--active')
  }
}

const handleCloseMenu = () => {
  if (mainMenu.value && btnHamburger.value) {
    mainMenu.value.classList.remove('main-menu--show')
    btnHamburger.value.classList.remove('btn-hamburger--active')
  }
}

const handleShowProfile = () => {
  isShowModalProfile.value = true
}

const closeModalProfile = () => {
  isShowModalProfile.value = false
}

const handleUpdateUser = async (payload) => {
  await setUser()
  if (payload) {
    user.value.fullName = payload.newUser.fullName
  }
}

const handleToggleNotify = async () => {
  handleCloseMenu()
  handleCloseChooseLang()
  isShowNotify.value = !isShowNotify.value
  console.log(notifications);
}

const handleCloseNotify = () => {
  isShowNotify.value = false
}

const handleCloseNotifyAndMenu = () => {
  handleCloseMenu()
  handleCloseNotify()
}


const handleScroll = (e) => {
  if (
    Math.ceil(e.target.scrollTop) + e.target.clientHeight >= e.target.scrollHeight
  ) {
    console.log('scroll');
    
  }
}

const handleToggleChooseLang = async (code) => {
  handleCloseMenu()
  handleCloseNotify()
  isShowChooseLang.value = !isShowChooseLang.value

  if ($i18n.locale !== code) {
    await setLocale(code)

    // Optional: Nếu bạn muốn chuyển URL sang đúng route theo locale
    const path = localePath({ path: router.currentRoute.value.fullPath, locale: code })
    router.push(path)
  }
}


const handleCloseChooseLang = () => {
  isShowChooseLang.value = false
}

const handleChangeModeDarkTheme = () => {
  const htmlEl = document.querySelector('html')
  htmlEl.classList.toggle('dark')
  const currentTheme = localStorage.getItem('theme') || 'light'
  localStorage.setItem('theme', currentTheme === 'light' ? 'dark' : 'light')
}

const setPercentUploadAvatar = (percent) => {
  percentUploadAvatarCDR.value = percent
}

// Lifecycle hooks
onMounted(async () => {
  await setUser()
  const currentTheme = localStorage.getItem('theme') || 'light'
  if (currentTheme === 'dark') {
    document.querySelector('html').classList.add('dark')
  }
})
</script>

<style scoped>
.sub-menu {
  transform: scaleY(0);
}
.main-menu:hover .sub-menu {
  transform: scaleY(1);
}

.btn-hamburger > span {
  width: 24px;
  height: 3px;
  background-color: #000;
  border-radius: 4px;
  transition: all 0.3s ease-in-out;
}

.btn-hamburger > span:nth-child(2) {
  margin: 3px 0;
}

.btn-hamburger--active > span:nth-child(2) {
  opacity: 0;
}
.btn-hamburger--active > span:nth-child(1) {
  transform: rotate(45deg) translate(4px, 4px);
}

.btn-hamburger--active > span:nth-child(3) {
  transform: rotate(-45deg) translate(4px, -4px);
}

@media screen and (max-width: 768px) {
  .btn-hamburger {
    display: flex;
  }
  .main-menu {
    position: absolute;
    top: 110%;
    width: 100vw;
    background-color: #fff;
    border-top-left-radius: 20px;
    border-top-right-radius: 20px;
    border-bottom: solid 1px #939496;
    right: -16px;
    transform: translateX(100%);
    transition: all 0.3s ease-in-out;
  }
  .sub-menu {
    transform: scaleY(1);
    width: 100%;
    height: 80vh;
    border-top-left-radius: 0;
    border-top-right-radius: 0;
    top: 100%;
  }
  .sub-menu::after {
    display: none;
  }
  .main-menu--show {
    transform: translateX(0);
  }

  .notify-container {
    width: 100vw;
    height: 100vh;
    right: -16px;
  }
}
</style>