<script setup lang="ts">
withDefaults(defineProps<{
  modelValue: boolean
  title: string
  subtitle?: string
  maxWidth?: string
  scrollable?: boolean
}>(), {
  maxWidth: 'max-w-md',
  scrollable: false,
})

const emit = defineEmits<{
  'update:modelValue': [value: boolean]
}>()
</script>

<template>
  <Teleport to="body">
    <div v-if="modelValue" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
      <div
        class="bg-white rounded-2xl shadow-2xl w-full"
        :class="[maxWidth, scrollable ? 'max-h-[90vh] flex flex-col' : '']"
      >
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 shrink-0">
          <div>
            <h2 class="text-lg font-semibold text-gray-900">{{ title }}</h2>
            <p v-if="subtitle" class="text-xs text-gray-500 mt-0.5">{{ subtitle }}</p>
          </div>
          <button class="text-gray-400 hover:text-gray-600 transition-colors" @click="emit('update:modelValue', false)">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>

        <div class="px-6 py-4 space-y-4" :class="scrollable ? 'overflow-y-auto' : ''">
          <slot />
        </div>

        <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-100 shrink-0">
          <slot name="footer" />
        </div>
      </div>
    </div>
  </Teleport>
</template>
