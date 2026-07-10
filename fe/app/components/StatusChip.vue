<script setup lang="ts">
import type { BadgeColor } from '~/constants'

const props = defineProps<{ color: BadgeColor }>()

const styles: Record<BadgeColor, string> = {
  success: 'bg-success-soft text-[#067647]',
  warning: 'bg-warning-soft text-[#b54708]',
  error: 'bg-danger-soft text-[#b42318]',
  info: 'bg-accent-soft text-accent-ink',
  primary: 'bg-accent-soft text-accent-ink',
  secondary: 'bg-neutral-soft text-muted',
  neutral: 'bg-neutral-soft text-muted',
}

const dotStyles: Record<BadgeColor, string> = {
  success: 'bg-success',
  warning: 'bg-warning',
  error: 'bg-danger',
  info: 'bg-accent',
  primary: 'bg-accent',
  secondary: 'bg-faint',
  neutral: 'bg-faint',
}

const classes = computed(() => styles[props.color] ?? styles.neutral)
const dotClass = computed(() => dotStyles[props.color] ?? dotStyles.neutral)
</script>

<template>
  <span class="status-chip" :class="classes">
    <span class="dot" :class="dotClass" />
    <slot />
  </span>
</template>
