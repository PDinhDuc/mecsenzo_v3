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
      <Form v-slot="{ handleSubmit }" as="form" @submit="handleSubmit(handleEditProfile)">
        <div>
          <FormField
            name="fullName"
            :label-field="$t('profileModal.fullname')"
            :value-field="user && user.fullName"
            :rules="{ required: true, max: 30 }"
            @onChangeField="handleChangeFiled"
          />
          <FormField
            name="age"
            :label-field="$t('profileModal.age')"
            type-input="number"
            :value-field="user && user.age"
            @onChangeField="handleChangeFiled"
          />
          <FormField
            name="address"
            :label-field="$t('profileModal.address')"
            :value-field="user && user.address"
            @onChangeField="handleChangeFiled"
          />
          <FormField
            :label-field="$t('profileModal.avatar')"
            type-input="file"
            :value-field="avatar"
            @onChangeFile="handleChangeAvatar"
          />
        </div>
        <div class="flex items-center justify-center">
          <Avatar
            :is-have-avatar="!!avatar"
            :src-image="avatar"
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
              type="submit"
              :name-button="$t('profileModal.edit')"
            />
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
      </Form>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import FormField from '~/components/FormField.vue'
import Avatar from '~/components/Avatar.vue'
import Button from '~/components/Button.vue'

// Nuxt app context for i18n
const { $t } = useNuxtApp()
const accountStore = useAccountStore()

// Props
const props = defineProps({
  isShow: {
    type: Boolean,
    default: false,
  },
})

// Emits
const emit = defineEmits([
  'closeModal',
  'update:user',
  'set-percent-upload',
  'clear-percent-upload',
])

// Reactive state
const user = ref(null)
const avatar = ref(null)
const fileAvatar = ref(null)

// Computed properties
const currentEmail = computed(() => accountStore.getAccount)

// Lifecycle hook
onMounted(async () => {
  user.value = await getUserByEmail(currentEmail.value)
  avatar.value = user.value && user.value.avatar
})

// Methods
const handleChangeFiled = (newValue) => {
  const [fieldChange, value] = newValue
  user.value[fieldChange] = value
}

const closeModal = () => {
  emit('closeModal')
}

const handleChangeAvatar = (fileImage) => {
  avatar.value = fileImage.preview
  fileAvatar.value = fileImage
}

const updateInfoUser = () => {
  updateUser(user.value)
  localStorage.setItem('user', JSON.stringify(user.value))
}

const handleImageUpdateComplete = async (urlAvatar) => {
  updateInfoUser()
  await setAvatarUser(urlAvatar)
  closeModal()
  clearPercentUploadAvatar()
}

const handleEditProfile = () => {
  if (fileAvatar.value) {
    uploadImage(
      `user-avatar`,
      fileAvatar.value,
      handleImageUpdateComplete,
      setPercentUploadAvatar
    )
    emit('update:user', {
      newUser: user.value,
      urlAvatarTemp: avatar.value,
    })
  } else {
    updateInfoUser()
    closeModal()
    emit('update:user')
  }
}

const setPercentUploadAvatar = (percent) => {
  emit('set-percent-upload', percent)
}

const clearPercentUploadAvatar = () => {
  emit('clear-percent-upload')
}
</script>