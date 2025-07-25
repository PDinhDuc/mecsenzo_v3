// types/echo.d.ts
import type Echo from 'laravel-echo'

declare module '#app' {
  interface NuxtApp {
    $echo: Echo
  }
}

declare module '@vue/runtime-core' {
  interface ComponentCustomProperties {
    $echo: Echo
  }
}
