<template>
  <div class="grid grid-cols-[20%_70%_10%] w-full mt-2 items-center">
    <div>
      <p class="text-[1rem] sm:text-[1.2rem] font-semibold text-dark_primary">
        {{ labelField }}
      </p>
    </div>
    <div>
      <div v-if="typeInput !== 'file'">
        <input
          ref="inputField"
          :type="typeInput"
          :name="name"
          :value="field.value"
          class="appearance-none outline-none w-full px-3 text-[1rem] sm:text-[1.2rem] py-2 border-b-[2px] border-b-[rgb(100,116,139)] focus:border-b-[#ff7200]"
          :readonly="!isEdit"
          @input="field.handleInput($event.target.value)"
        />
        <p v-if="meta.touched && errorMessage" class="text-red-500 text-[0.9rem] italic mt-2">
          {{ errorMessage }}
        </p>
      </div>
      <div v-else>
        <input
          v-if="isEdit"
          ref="inputField"
          type="file"
          class="appearance-none outline-none w-[200px]"
          @change="handleChangeAvatar"
        />
      </div>
    </div>
    <div>
      <button
        v-if="!isEdit"
        id="btn-edit"
        type="button"
        class="h-full p-4"
        @click="handleEnableEdit"
      >
        <font-awesome-icon icon="pen-to-square" />
      </button>
      <button
        v-else
        id="btn-edit"
        type="button"
        class="h-full p-4"
        @click="handleDisableEdit"
      >
        <font-awesome-icon icon="eye" class="text-primary" />
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

// Props
const props = defineProps({
  name: {
    type: String,
    default: '',
  },
  labelField: {
    type: String,
    default: '',
  },
  typeInput: {
    type: String,
    default: 'text',
  },
  valueField: {
    type: String,
    default: '',
  },
  rule: {
    type: [String, null],
    default: null,
  },
})

// Emits
const emit = defineEmits(['onChangeField', 'onChangeFile'])

// Reactive state
const isEdit = ref(false)
const inputField = ref(null)

// VeeValidate field
const { field, errorMessage, meta } = useField(props.name, props.rule, {
  initialValue: props.valueField,
  validateOnValueUpdate: false,
})

// Watch for valueField changes to sync with VeeValidate field
watch(() => props.valueField, (newValue) => {
  field.value = newValue
})

// Methods
const handleEnableEdit = () => {
  if (props.typeInput !== 'file' && props.typeInput !== 'number') {
    const inputFieldEl = inputField.value
    if (inputFieldEl) {
      const end = inputFieldEl.value.length
      inputFieldEl.setSelectionRange(end, end)
      inputFieldEl.focus()
    }
  }
  isEdit.value = true
}

const handleDisableEdit = () => {
  isEdit.value = false
}

const handleChangeAvatar = (e) => {
  const fileImage = createTempUrlForImageFile(e.target.files[0])
  emit('onChangeFile', fileImage)
}

// Sync input changes with emit
watch(field.value, (newValue) => {
  emit('onChangeField', [props.name, newValue])
})
</script>

<style></style>