<template>
  <div class="flex justify-center items-center">
    <div
      class="w-16 h-16 border-4 border-t-transparent border-blue-500 dark:border-[#ff7200] dark:border-t-transparent rounded-full animate-spin"
    ></div>
  </div>
</template>


<style scoped>
@media screen and (max-width: 768px) {
  .container-sidebar {
    position: absolute;
    width: 90px;
    height: 80vh;
    border-radius: 5px;
  }
  .container-sidebar:hover {
    width: 300px;
  }
}
</style>
