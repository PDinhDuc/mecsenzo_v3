<template>
  <div class="min-h-[600px] w-full rounded-[25px] shadow-2xl">
    <div
      :class="`grid-cols-2 relative grid w-full h-[68px] bg-dark_primary rounded-[25px]`"
    >
      <div
        v-for="(tabItem, index) in tabItems"
        :key="index"
        :ref="el => tabRefs[index] = el"
        class="flex items-center justify-center w-full h-full text-white text-[1.2rem] font-semibold cursor-pointer select-none"
        @click="setCurrentTab(index)"
        >
        {{ tabItem.title }}
      </div>
      <div
        :class="`${getClassMaker} absolute top-0 h-full bg-[rgba(255,255,255,0.3)] 
        rounded-[25px] transition-all duration-300 ease-in-out`"
        :style="{ left: markerLeft + 'px', width: markerWidth + 'px' }"
      ></div>
    </div>

    <div class="mt-[50px] flex justify-center">
      <keep-alive>
        <component :is="tabItems[currentTab].component" />
      </keep-alive>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, defineProps } from 'vue'

const props = defineProps({
  tabItems: {
    type: Array,
    default: () => [],
  },
})

const currentTab = ref(0)
const tabRefs = ref([])
const markerLeft = ref(0)
const markerWidth = ref(0)
const currentComponent = computed(() => props.tabItems[currentTab.value].component)



const setCurrentTab = async (index) => {
  currentTab.value = index
  // currentComponent.value = props.tabItems[index].component

  await nextTick()

  const el = tabRefs.value[index]
  if (el) {
    markerLeft.value = el.offsetLeft
    markerWidth.value = el.offsetWidth
  }
}

const getClassMaker = computed(() => {
  return `left-[${markerLeft.value}px] w-[${markerWidth.value}px]`
})

onMounted(()=>{
  setCurrentTab(0)
}
)
</script>