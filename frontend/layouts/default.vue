<template>
  <div>
    <Header />
    <NuxtPage />
  </div>
</template>

<script>
import Header from '../components/Header.vue'
export default {
  name: 'Default',
  components: { Header },
}
</script>
