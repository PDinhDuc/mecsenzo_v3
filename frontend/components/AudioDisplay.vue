<template>
  <div
    v-if="url"
    :class="`container-result ${isColorWhite ? 'container-result--white' : ''}`"
  >
    <button class="btn-controller dark:text-white" @click="handleTogglePlayPreview">
      <font-awesome-icon
        v-if="isPreviewPause"
        icon="play"
        :class="isColorWhite ? 'text-white' : ''"
      />
      <font-awesome-icon
        v-else
        icon="pause"
        :class="isColorWhite ? 'text-white' : ''"
      />
    </button>

    <div class="process-audio">
      <div ref="processCurrent" class="process-current"></div>
    </div>

    <div
      :class="`time-audio ${
        isColorWhite ? 'text-white' : 'text-[#888] dark:text-white'
      } ${currentTimePreview && totalTimePreview ? 'time-audio--show' : ''}`"
    >
      {{ Math.ceil(currentTimePreview) }}:{{ Math.ceil(totalTimePreview) }}
    </div>

    <audio
      ref="audioEl"
      class="audio-el"
      :src="url"
      controls
      :title="fileName"
      @timeupdate="onTimeUpdate"
    />
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { watch } from 'vue'

const props = defineProps({
  url: {
    type: String,
    default: null,
  },
  fileName: {
    type: String,
    default: '',
  },
  isColorWhite: {
    type: Boolean,
    default: false,
  },
})

const isPreviewPause = ref(true)
const currentTimePreview = ref(0)
const totalTimePreview = ref(0)

const audioEl = ref(null)
const processCurrent = ref(null)

const handleTogglePlayPreview = () => {
  isPreviewPause.value = !isPreviewPause.value
  const audio = audioEl.value

  if (!audio) return

  if (audio.paused) {
    audio.play()
  } else {
    audio.pause()
  }
}

const onTimeUpdate = () => {
  const audio = audioEl.value
  if (!audio) return

  currentTimePreview.value = audio.currentTime
  totalTimePreview.value = audio.duration

  if (processCurrent.value && totalTimePreview.value > 0) {
    const percent = Math.ceil((currentTimePreview.value / totalTimePreview.value) * 100)
    processCurrent.value.style.width = `${percent}%`
  }
}
</script>

<style scoped>
.container-result {
  position: relative !important;
  left: 70px;
  top: 5px;
  display: flex;
  align-items: center;
}

.audio-el {
  opacity: 0;
  max-width: 200px;
  left: 100px;
  width: 0;
  height: 0;
}

.process-audio {
  height: 1px;
  width: 150px;
  margin-left: 8px;
  background-color: rgb(156, 163, 175);
}

.process-current {
  background-color: #0fcdce;
  position: relative;
  top: -1px;
  height: 4px;
  width: 0;
}

.time-audio {
  font-size: 0.9rem;
  margin-left: 8px;
  opacity: 0;
}

.time-audio--show {
  opacity: 1;
}

.container-result--white .process-current {
  color: #fff;
  background-color: #fff;
}

@media screen and (max-width: 678px) {
  .process-audio {
    width: 100px;
  }
}
</style>
