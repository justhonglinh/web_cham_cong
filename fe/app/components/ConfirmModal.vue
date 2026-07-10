<script setup lang="ts">
const props = withDefaults(defineProps<{
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

const isOpen = computed({
  get: () => props.modelValue,
  set: (value: boolean) => emit('update:modelValue', value),
})
</script>

<template>
  <UModal v-model:open="isOpen" :ui="{ content: 'max-w-sm' }">
    <template #header>
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 bg-danger-soft rounded-full flex items-center justify-center shrink-0">
          <UIcon name="i-heroicons-exclamation-triangle" class="w-5 h-5 text-danger" />
        </div>
        <div>
          <h3 class="font-semibold text-ink">{{ title }}</h3>
          <p class="text-sm text-muted">{{ subtitle }}</p>
        </div>
      </div>
    </template>

    <template #body>
      <p class="text-sm text-body">
        <slot />
      </p>
    </template>

    <template #footer>
      <div class="flex gap-3 justify-end w-full">
        <UButton color="neutral" variant="soft" :disabled="loading" @click="isOpen = false">{{ cancelText }}</UButton>
        <UButton color="error" :loading="loading" @click="emit('confirm')">{{ confirmText }}</UButton>
      </div>
    </template>
  </UModal>
</template>
