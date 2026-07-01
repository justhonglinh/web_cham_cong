<script setup lang="ts">
defineProps<{
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
</script>

<template>
  <div v-if="total > 0" class="px-6 py-4 border-t border-gray-100 flex flex-col sm:flex-row items-center justify-between gap-3">
    <p class="text-sm text-gray-500">
      Hiển thị <span class="font-medium text-gray-700">{{ summaryFrom }}–{{ summaryTo }}</span>
      trong tổng số <span class="font-medium text-gray-700">{{ total }}</span> bản ghi
    </p>

    <nav v-if="lastPage > 1" class="flex items-center gap-1">
      <!-- Prev -->
      <button
        class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-sm font-medium transition-colors"
        :class="currentPage === 1 ? 'text-gray-300 cursor-not-allowed' : 'text-gray-600 hover:bg-gray-100'"
        :disabled="currentPage === 1"
        @click="emit('goToPage', currentPage - 1)"
      >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
      </button>

      <!-- Page numbers -->
      <template v-for="(page, i) in visiblePages" :key="i">
        <span v-if="page === '...'" class="w-9 h-9 flex items-center justify-center text-gray-400 text-sm">…</span>
        <button
          v-else
          class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-sm font-medium transition-colors"
          :class="page === currentPage ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-100'"
          @click="emit('goToPage', page as number)"
        >
          {{ page }}
        </button>
      </template>

      <!-- Next -->
      <button
        class="inline-flex items-center justify-center w-9 h-9 rounded-lg text-sm font-medium transition-colors"
        :class="currentPage === lastPage ? 'text-gray-300 cursor-not-allowed' : 'text-gray-600 hover:bg-gray-100'"
        :disabled="currentPage === lastPage"
        @click="emit('goToPage', currentPage + 1)"
      >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
      </button>
    </nav>
  </div>
</template>
