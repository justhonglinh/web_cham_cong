<script setup lang="ts">
const props = withDefaults(defineProps<{ label?: string; to?: string }>(), {
  label: 'Quay lại',
})

const router = useRouter()

function goBack() {
  if (props.to) {
    router.push(props.to)
    return
  }
  router.back()
}
</script>

<template>
  <button
    type="button"
    class="inline-flex items-center gap-1 text-sm font-medium text-gray-500 hover:text-gray-700 transition-colors"
    @click="goBack"
  >
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
    </svg>
    {{ label }}
  </button>
</template>
