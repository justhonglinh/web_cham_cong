<script setup lang="ts">
const props = withDefaults(defineProps<{
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

const isOpen = computed({
  get: () => props.modelValue,
  set: (value: boolean) => emit('update:modelValue', value),
})
</script>

<template>
  <UModal
    v-model:open="isOpen"
    :title="title"
    :description="subtitle"
    :ui="{ content: `${maxWidth} ${scrollable ? 'max-h-[90vh]' : ''}` }"
  >
    <template #body>
      <div class="space-y-4" :class="scrollable ? 'overflow-y-auto' : ''">
        <slot />
      </div>
    </template>

    <template #footer>
      <slot name="footer" />
    </template>
  </UModal>
</template>
