<script setup lang="ts">
const props = defineProps<{
  currentPage: number
  lastPage: number
  total: number
  summaryFrom: number
  summaryTo: number
  visiblePages: (number | '...')[]
}>()

const emit = defineEmits<{
  goToPage: [page: number]
}>()

const itemsPerPage = computed(() => Math.max(1, Math.ceil(props.total / Math.max(1, props.lastPage))))

const page = computed({
  get: () => props.currentPage,
  set: (value: number) => emit('goToPage', value),
})
</script>

<template>
  <div v-if="total > 0" class="px-6 py-4 border-t border-border flex flex-col sm:flex-row items-center justify-between gap-3">
    <p class="text-sm text-muted">
      Hiển thị <span class="font-medium text-body">{{ summaryFrom }}–{{ summaryTo }}</span>
      trong tổng số <span class="font-medium text-body">{{ total }}</span> bản ghi
    </p>

    <UPagination
      v-if="lastPage > 1"
      v-model:page="page"
      :total="total"
      :items-per-page="itemsPerPage"
      :sibling-count="1"
      color="neutral"
      active-color="primary"
      show-edges
    />
  </div>
</template>
