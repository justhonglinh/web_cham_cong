import forms from '@tailwindcss/forms'
import type { Config } from 'tailwindcss'

export default {
  content: [
    './app/**/*.{js,ts,vue}',
    './app.vue',
  ],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Figtree', 'ui-sans-serif', 'system-ui'],
      },
    },
  },
  plugins: [forms],
} satisfies Config
