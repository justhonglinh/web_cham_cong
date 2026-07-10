<script setup lang="ts">
import type { TableColumn } from '@nuxt/ui'
import { WORK_SUMMARY_STATUS_BADGE, WORK_SUMMARY_STATUS_LABEL } from '~/constants'
import { workSummaryService } from '~/services/workSummaryService'
import type { WorkSummary } from '~/types/workSummary'

definePageMeta({ layout: 'default' })

const columns: TableColumn<WorkSummary>[] = [
  { accessorKey: 'employee_name', header: 'Nhân viên' },
  { accessorKey: 'month', header: 'Tháng' },
  { accessorKey: 'total_work_days', header: 'Tổng ngày làm', meta: { class: { th: 'text-right', td: 'text-right' } } },
  { accessorKey: 'total_work_hours', header: 'Tổng giờ làm', meta: { class: { th: 'text-right', td: 'text-right' } } },
  { accessorKey: 'leave_days', header: 'Ngày nghỉ', meta: { class: { th: 'text-right', td: 'text-right' } } },
  { accessorKey: 'overtime_hours', header: 'Tăng ca (giờ)', meta: { class: { th: 'text-right', td: 'text-right' } } },
  { accessorKey: 'status', header: 'Trạng thái' },
]

const summaries = ref<WorkSummary[]>([])
const loading = ref(false)
const error = ref('')
const exportLoading = ref(false)

const currentDate = new Date()
const filterMonth = ref(currentDate.getMonth() + 1)
const filterYear = ref(currentDate.getFullYear())
const searchName = ref('')

const months = [
  { value: 1, label: 'Tháng 1' }, { value: 2, label: 'Tháng 2' },
  { value: 3, label: 'Tháng 3' }, { value: 4, label: 'Tháng 4' },
  { value: 5, label: 'Tháng 5' }, { value: 6, label: 'Tháng 6' },
  { value: 7, label: 'Tháng 7' }, { value: 8, label: 'Tháng 8' },
  { value: 9, label: 'Tháng 9' }, { value: 10, label: 'Tháng 10' },
  { value: 11, label: 'Tháng 11' }, { value: 12, label: 'Tháng 12' },
]

const years = computed(() => {
  const y = currentDate.getFullYear()
  return [y - 2, y - 1, y, y + 1]
})

const { currentPage, lastPage, total, perPage, setFromResponse, goToPage: goToPageFn, visiblePages, summaryFrom, summaryTo } = usePagination(20)

function goToPage(page: number) {
  goToPageFn(page, fetchSummaries)
}

async function fetchSummaries(page = 1) {
  loading.value = true
  error.value = ''
  try {
    const res = await workSummaryService.getAll({
      month: filterMonth.value,
      year: filterYear.value,
      page,
      per_page: perPage.value,
      search: searchName.value.trim() || undefined,
    })
    summaries.value = setFromResponse(res)
  } catch {
    error.value = 'Không thể tải báo cáo tổng hợp. Vui lòng thử lại.'
  } finally {
    loading.value = false
  }
}

async function exportExcel() {
  exportLoading.value = true
  try {
    const blob = await workSummaryService.export({ month: filterMonth.value, year: filterYear.value })
    const link = document.createElement('a')
    link.href = URL.createObjectURL(blob)
    link.download = `bao-cao-cong-${filterMonth.value}-${filterYear.value}.xlsx`
    link.click()
    URL.revokeObjectURL(link.href)
  } catch {
    alert('Xuất file thất bại. Vui lòng thử lại.')
  } finally {
    exportLoading.value = false
  }
}

function statusBadgeClass(status: string) {
  return WORK_SUMMARY_STATUS_BADGE[status] ?? 'info'
}

function statusLabel(status: string) {
  return WORK_SUMMARY_STATUS_LABEL[status] ?? status
}

watch([filterMonth, filterYear], () => fetchSummaries(1))
onMounted(() => fetchSummaries(1))
</script>

<template>
  <div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-ink">Báo cáo tổng hợp công việc</h1>
        <p class="text-sm text-muted mt-1">Xem tổng hợp công việc của tất cả nhân viên</p>
      </div>
      <UButton
        :loading="exportLoading"
        :disabled="exportLoading || loading"
        @click="exportExcel"
      >
        <UIcon v-if="!exportLoading" name="i-heroicons-arrow-down-tray" class="w-4 h-4" />
        {{ exportLoading ? 'Đang xuất...' : 'Xuất Excel' }}
      </UButton>
    </div>

    <!-- Filters -->
    <UCard>
      <div class="flex flex-wrap gap-4 items-end">
        <div>
          <label class="block text-xs font-medium text-body mb-1">Tháng</label>
          <USelect v-model="filterMonth" :items="months" value-key="value" label-key="label" class="w-36" />
        </div>
        <div>
          <label class="block text-xs font-medium text-body mb-1">Năm</label>
          <USelect v-model="filterYear" :items="years" class="w-28" />
        </div>
        <div class="flex-1 min-w-48">
          <label class="block text-xs font-medium text-body mb-1">Tìm theo tên nhân viên</label>
          <UInput
            v-model="searchName"
            type="text"
            icon="i-heroicons-magnifying-glass"
            class="w-full"
            placeholder="Nhập tên nhân viên..."
          />
        </div>
        <UButton color="neutral" variant="soft" :loading="loading" :disabled="loading" @click="fetchSummaries(1)">
          {{ loading ? 'Đang tải...' : 'Tìm kiếm' }}
        </UButton>
      </div>
    </UCard>

    <!-- Card -->
    <UCard>
      <!-- Error -->
      <UAlert
        v-if="error"
        class="mb-4"
        color="error"
        variant="soft"
        icon="i-heroicons-exclamation-triangle"
        :description="error"
      />

      <!-- Loading -->
      <div v-if="loading" class="flex justify-center py-16">
        <UIcon name="i-heroicons-arrow-path" class="animate-spin w-10 h-10 text-accent" />
      </div>

      <!-- Table -->
      <div v-else-if="summaries.length > 0">
        <div class="mb-3 text-sm text-muted">
          Hiển thị <strong>{{ summaries.length }}</strong> nhân viên —
          Tháng {{ filterMonth }}/{{ filterYear }}
        </div>
        <UTable :data="summaries" :columns="columns">
          <template #month-cell="{ row }">
            {{ row.original.month }}/{{ row.original.year }}
          </template>
          <template #total_work_days-cell="{ row }">
            <span class="font-medium">{{ row.original.total_work_days ?? 0 }}</span>
          </template>
          <template #total_work_hours-cell="{ row }">
            <span class="font-medium">{{ row.original.total_work_hours ?? 0 }}h</span>
          </template>
          <template #leave_days-cell="{ row }">
            <span :class="(row.original.leave_days ?? 0) > 0 ? 'text-warning font-medium' : ''">
              {{ row.original.leave_days ?? 0 }}
            </span>
          </template>
          <template #overtime_hours-cell="{ row }">
            <span :class="(row.original.overtime_hours ?? 0) > 0 ? 'text-accent font-medium' : ''">
              {{ row.original.overtime_hours ?? 0 }}h
            </span>
          </template>
          <template #status-cell="{ row }">
            <StatusChip :color="statusBadgeClass(row.original.status)">{{ statusLabel(row.original.status) }}</StatusChip>
          </template>
          <template #body-bottom>
            <tr class="bg-neutral-soft border-t-2 border-border-strong">
              <td class="px-4 py-3 text-sm font-semibold text-body" colspan="2">Tổng cộng</td>
              <td class="px-4 py-3 text-sm font-semibold text-ink text-right">
                {{ summaries.reduce((acc, s) => acc + (s.total_work_days ?? 0), 0) }}
              </td>
              <td class="px-4 py-3 text-sm font-semibold text-ink text-right">
                {{ summaries.reduce((acc, s) => acc + (s.total_work_hours ?? 0), 0) }}h
              </td>
              <td class="px-4 py-3 text-sm font-semibold text-warning text-right">
                {{ summaries.reduce((acc, s) => acc + (s.leave_days ?? 0), 0) }}
              </td>
              <td class="px-4 py-3 text-sm font-semibold text-accent text-right">
                {{ summaries.reduce((acc, s) => acc + (s.overtime_hours ?? 0), 0) }}h
              </td>
              <td class="px-4 py-3"></td>
            </tr>
          </template>
        </UTable>

        <!-- Pagination -->
        <PaginationBar
          :current-page="currentPage" :last-page="lastPage"
          :total="total" :summary-from="summaryFrom" :summary-to="summaryTo"
          :visible-pages="visiblePages"
          @go-to-page="goToPage"
        />
      </div>

      <!-- Empty -->
      <div v-else class="text-center py-16 text-faint">
        <UIcon name="i-heroicons-inbox" class="mx-auto h-14 w-14 mb-4" />
        <p class="font-medium text-muted">Không có dữ liệu cho tháng {{ filterMonth }}/{{ filterYear }}</p>
        <p class="text-sm mt-1">Thử chọn tháng hoặc năm khác</p>
      </div>
    </UCard>
  </div>
</template>
