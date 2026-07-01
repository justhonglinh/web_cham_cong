<script setup lang="ts">
withDefaults(defineProps<{
  modelValue: boolean
  title?: string
  subtitle?: string
  confirmText?: string
  cancelText?: string
  loading?: boolean
}>(), {
  title: 'Xác nhận xóa',
  subtitle: 'Hành động này không thể hoàn tác.',
  confirmText: 'Xóa',
  cancelText: 'Hủy',
  loading: false,
})

const emit = defineEmits<{
  'update:modelValue': [value: boolean]
  confirm: []
}>()
</script>

<template>
  <Teleport to="body">
    <div v-if="modelValue" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
      <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6">
        <div class="flex items-center gap-3 mb-4">
          <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
          </div>
          <div>
            <h3 class="font-semibold text-gray-900">{{ title }}</h3>
            <p class="text-sm text-gray-500">{{ subtitle }}</p>
          </div>
        </div>
        <p class="text-sm text-gray-700 mb-6">
          <slot />
        </p>
        <div class="flex gap-3 justify-end">
          <button class="btn-secondary" :disabled="loading" @click="emit('update:modelValue', false)">{{ cancelText }}</button>
          <button class="btn-danger" :disabled="loading" @click="emit('confirm')">
            <svg v-if="loading" class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z" />
            </svg>
            {{ confirmText }}
          </button>
        </div>
      </div>
    </div>
  </Teleport>
</template>
