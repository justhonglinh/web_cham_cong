export default defineNuxtConfig({
  compatibilityDate: '2025-07-15',
  devtools: { enabled: true },

  srcDir: 'app/',

  modules: [
    '@nuxtjs/tailwindcss',
    '@pinia/nuxt',
    '@vueuse/nuxt',
  ],

  tailwindcss: {
    cssPath: '~/assets/css/main.css',
  },

  runtimeConfig: {
    public: {
      apiBase: process.env.NUXT_PUBLIC_API_BASE || 'http://localhost:8000',
    },
  },

  nitro: {
    devProxy: {
      '/api': {
        target: 'http://localhost:8000/api',
        changeOrigin: true,
        prependPath: false,
      },
      '/sanctum': {
        target: 'http://localhost:8000/sanctum',
        changeOrigin: true,
        prependPath: false,
      },
    },
  },

  app: {
    head: {
      title: 'Chấm Công',
      meta: [
        { charset: 'utf-8' },
        { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      ],
      link: [
        {
          rel: 'preconnect',
          href: 'https://fonts.bunny.net',
        },
        {
          rel: 'stylesheet',
          href: 'https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap',
        },
      ],
    },
  },
})
