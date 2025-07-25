<template>
  <div id="container-recorder" class="h-[100px]">
    <audio-recorder :pause-recording="callback" :after-recording="callback" />
    <AudioDisplay :url="url" :file-name="fileName" />
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import AudioDisplay from './AudioDisplay.vue'

const emit = defineEmits(['change-data-audio'])

const url = ref(null)
const index = ref(0) // Nếu bạn truyền index từ bên ngoài, dùng defineProps()

const fileName = computed(() => {
  const i = index.value + 1
  const padded = i < 10 ? `00${i}` : i < 100 ? `0${i}` : `${i}`
  return `utterance-${padded}.mp3`
})

function callback(data) {
  if (data && data.url) {
    emit('change-data-audio', { ...data, fileName: fileName.value })
    url.value = data.url
  }
}
</script>

<style scoped>
#container-recorder {
  display: flex;
  align-items: center;
}

.ar {
  position: relative !important;
  height: 100px;
  width: fit-content !important;
  box-shadow: none !important;
  background: transparent !important;
}

.ar-content {
  display: block;
}

.ar-recorder {
  position: absolute !important;
  align-items: flex-start;
  top: 40px;
  left: 12px;
}

.ar-icon__sm.ar-recorder__stop {
  position: absolute !important;
  z-index: 1000;
  left: 40px;
  top: 1px;
}

.ar-icon.ar-icon__lg,
.ar-icon__sm.ar-recorder__stop {
  display: flex;
  justify-content: center;
  align-items: center;
  width: 30px;
  height: 30px;
}

svg {
  width: 20px;
  height: 20px;
}

.ar-player-actions,
.ar-player-bar,
.ar-records__record,
.ar-recorder__duration,
.ar-recorder__records-limit,
.ar-recorder__time-limit,
.ar-player,
.ar-records__record.ar-records__record--selected {
  width: 0 !important;
  height: 0 !important;
  overflow: hidden !important;
  opacity: 0 !important;
}
</style>