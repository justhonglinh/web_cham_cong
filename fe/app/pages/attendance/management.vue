<script setup lang="ts">
import * as z from 'zod'
import type { FormSubmitEvent, TableColumn } from '@nuxt/ui'
import { ATTENDANCE_STATUS_BADGE, ATTENDANCE_STATUS_LABEL } from '~/constants'
import { attendanceService } from '~/services/attendanceService'
import type { AttendanceRecord } from '~/types/attendance'
import { formatDate, formatTime } from '~/utils/format'

definePageMeta({ layout: 'default' })

const columns: TableColumn<AttendanceRecord>[] = [
  { accessorKey: 'employee_name', header: 'Nhân viên' },
  { accessorKey: 'date', header: 'Ngày' },
  { accessorKey: 'check_in', header: 'Giờ vào' },
  { accessorKey: 'check_out', header: 'Giờ ra' },
  { accessorKey: 'shift_name', header: 'Ca làm' },
  { accessorKey: 'status', header: 'Trạng thái' },
  { id: 'actions', header: '', meta: { class: { th: 'text-right', td: 'text-right' } } },
]

const toast = useAppToast()

const loading = ref(true)
const saving = ref(false)
const error = ref<string | null>(null)
const saveError = ref<string | null>(null)

const records = ref<AttendanceRecord[]>([])

const now = new Date()
const filterMonth = ref(String(now.getMonth() + 1).padStart(2, '0'))
const filterYear = ref(String(now.getFullYear()))

const showModal = ref(false)
const editingRecord = ref<AttendanceRecord | null>(null)
const editForm = ref({ check_in: '', check_out: '' })

const schema = z.object({
  check_in: z.string().optional(),
  check_out: z.string().optional(),
})
type Schema = z.output<typeof schema>

const formRef = useTemplateRef('formRef')

const { currentPage, lastPage, total, perPage, setFromResponse, goToPage: goToPageFn, visiblePages, summaryFrom, summaryTo } = usePagination(20)

function goToPage(page: number) {
  goToPageFn(page, fetchAttendance)
}

async function fetchAttendance(page = 1) {
  loading.value = true
  error.value = null
  try {
    const res = await attendanceService.getManagement({ month: Number(filterMonth.value), year: Number(filterYear.value), page, per_page: perPage.value })
    records.value = setFromResponse(res)
  } catch (e: any) {
    error.value = e?.data?.message || 'Không thể tải dữ liệu chấm công.'
  } finally {
    loading.value = false
  }
}

function openEdit(record: AttendanceRecord) {
  editingRecord.value = record
  editForm.value = {
    check_in: record.check_in?.slice(0, 5) ?? '',
    check_out: record.check_out?.slice(0, 5) ?? '',
  }
  saveError.value = null
  showModal.value = true
}

function closeModal() {
  showModal.value = false
  editingRecord.value = null
  saveError.value = null
}

async function onSubmit(event: FormSubmitEvent<Schema>) {
  if (!editingRecord.value) return
  saving.value = true
  saveError.value = null
  try {
    const updated = await attendanceService.updateManagement(editingRecord.value.id, {
      check_in: event.data.check_in || null,
      check_out: event.data.check_out || null,
    })
    const idx = records.value.findIndex(r => r.id === editingRecord.value!.id)
    if (idx !== -1) records.value[idx] = updated
    toast.success('Cập nhật chấm công thành công.')
    closeModal()
  } catch (e: any) {
    saveError.value = e?.data?.message || 'Lưu thất bại. Vui lòng thử lại.'
  } finally {
    saving.value = false
  }
}

function statusBadge(status: string) {
  return ATTENDANCE_STATUS_BADGE[status] ?? 'error'
}

function statusLabel(status: string) {
  return ATTENDANCE_STATUS_LABEL[status] ?? status
}


const years = computed(() => {
  const y = now.getFullYear()
  return [y - 1, y, y + 1].map(String)
})

const months = Array.from({ length: 12 }, (_, i) => ({
  value: String(i + 1).padStart(2, '0'),
  label: `Tháng ${i + 1}`,
}))

onMounted(fetchAttendance)
</script>

<template>
  <div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div>
      <h1 class="text-2xl font-bold text-ink">Quản lý chấm công</h1>
      <p class="text-sm text-muted mt-0.5">Xem và chỉnh sửa dữ liệu chấm công của nhân viên</p>
    </div>

    <!-- Error Banner -->
    <UAlert
      v-if="error"
      color="error"
      variant="soft"
      :description="error"
      close
      @update:open="error = null"
    />

    <!-- Filters -->
    <UCard>
      <div class="flex flex-wrap items-end gap-3">
        <div>
          <label class="block text-xs font-medium text-muted mb-1">Tháng</label>
          <USelect v-model="filterMonth" :items="months" value-key="value" class="w-32" />
        </div>
        <div>
          <label class="block text-xs font-medium text-muted mb-1">Năm</label>
          <USelect v-model="filterYear" :items="years" class="w-28" />
        </div>
        <UButton icon="i-heroicons-funnel" @click="fetchAttendance(1)">
          Lọc
        </UButton>
      </div>
    </UCard>

    <!-- Table -->
    <UCard :ui="{ body: 'p-0 sm:p-0' }" class="overflow-hidden">
      <UTable
        :data="records"
        :columns="columns"
        :loading="loading"
        empty="Không có dữ liệu chấm công cho tháng này"
      >
        <template #employee_name-cell="{ row }">
          <div class="flex items-center gap-2">
            <div class="w-7 h-7 rounded-full bg-neutral-soft flex items-center justify-center text-body font-semibold text-xs shrink-0">
              {{ (row.original.employee_name ?? '?').charAt(0).toUpperCase() }}
            </div>
            <span class="text-sm font-medium text-ink">{{ row.original.employee_name }}</span>
          </div>
        </template>
        <template #date-cell="{ row }">
          <span class="text-body">{{ formatDate(row.original.date) }}</span>
        </template>
        <template #check_in-cell="{ row }">
          <span class="text-sm font-medium" :class="row.original.check_in ? 'text-success' : 'text-faint'">
            {{ formatTime(row.original.check_in) }}
          </span>
        </template>
        <template #check_out-cell="{ row }">
          <span class="text-sm font-medium" :class="row.original.check_out ? 'text-accent' : 'text-faint'">
            {{ formatTime(row.original.check_out) }}
          </span>
        </template>
        <template #shift_name-cell="{ row }">
          <span class="text-body">{{ row.original.shift_name || '—' }}</span>
        </template>
        <template #status-cell="{ row }">
          <StatusChip :color="statusBadge(row.original.status)">{{ statusLabel(row.original.status) }}</StatusChip>
        </template>
        <template #actions-cell="{ row }">
          <UButton
            color="primary"
            variant="soft"
            size="xs"
            icon="i-heroicons-pencil-square"
            @click="openEdit(row.original)"
          >
            Sửa
          </UButton>
        </template>
      </UTable>

      <!-- Pagination -->
      <PaginationBar
        v-if="!loading"
        :current-page="currentPage" :last-page="lastPage"
        :total="total" :summary-from="summaryFrom" :summary-to="summaryTo"
        :visible-pages="visiblePages"
        @go-to-page="goToPage"
      />
    </UCard>

    <!-- Edit Modal -->
    <BaseModal
      :model-value="showModal"
      title="Chỉnh sửa chấm công"
      :subtitle="editingRecord ? `${editingRecord.employee_name} • ${formatDate(editingRecord.date)}` : undefined"
      @update:model-value="closeModal"
    >
      <UForm ref="formRef" :schema="schema" :state="editForm" class="space-y-4" @submit="onSubmit">
        <UAlert
          v-if="saveError"
          color="error"
          variant="soft"
          icon="i-heroicons-exclamation-triangle"
          :description="saveError"
        />

        <UFormField label="Giờ vào" name="check_in" help="Để trống nếu chưa có giờ vào">
          <UInput v-model="editForm.check_in" type="time" class="w-full" />
        </UFormField>

        <UFormField label="Giờ ra" name="check_out" help="Để trống nếu chưa có giờ ra">
          <UInput v-model="editForm.check_out" type="time" class="w-full" />
        </UFormField>
      </UForm>

      <template #footer>
        <UButton color="neutral" variant="soft" :disabled="saving" @click="closeModal">Hủy</UButton>
        <UButton :loading="saving" @click="formRef?.submit()">Lưu thay đổi</UButton>
      </template>
    </BaseModal>
  </div>
</template>
