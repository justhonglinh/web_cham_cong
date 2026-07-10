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
  <UButton
    type="button"
    variant="link"
    color="neutral"
    icon="i-heroicons-arrow-left"
    class="px-0"
    @click="goBack"
  >
    {{ label }}
  </UButton>
</template>
