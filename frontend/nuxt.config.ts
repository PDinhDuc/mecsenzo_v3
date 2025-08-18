export default defineNuxtConfig({
  modules: ['@nuxtjs/tailwindcss', '@pinia/nuxt', '@nuxtjs/i18n','@vesp/nuxt-fontawesome'],
  css: ['@/assets/css/base.css', '@/assets/css/tailwind.css'],
  fontawesome: {
    component: 'fa',
    icons: {
      solid: ['bell','earth-asia','pen-to-square','eye','angles-right','angles-left',
         'user', 'plus','chart-line', 'moon', 'arrow-right-from-bracket','paper-plane',
         'microphone', 'image'
        ],
      brands: ['twitter'],
    },
  },
  runtimeConfig: {
    public: {
      apiBase: process.env.NUXT_PUBLIC_API_BASE || 'http://127.0.0.1:8000',
      pusherKey: 'local',
      wsHost: '127.0.0.1',
      wsPort: 6001
    }
  },


  i18n: {
    langDir: 'locales',
    lazy: true,
    defaultLocale: 'en',
    locales: [
      { code: 'en', file: 'en.js', name: 'English' },
      { code: 'vi', file: 'vi.js', name: 'Tiếng Việt' },
    ]
  },

  compatibilityDate: '2025-07-15',
  devtools: { enabled: process.env.NODE_ENV !== 'production' },
  ssr: false,

  // Performance-focused tweaks
  nitro: {
    compressPublicAssets: true,
  },

  vite: {
    build: {
      cssCodeSplit: true,
    },
    esbuild: {
      drop: process.env.NODE_ENV === 'production' ? ['console', 'debugger'] : [],
    },
  },
})