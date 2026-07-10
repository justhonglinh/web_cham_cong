<script setup lang="ts">
import type { TableColumn } from '@nuxt/ui'
import { REQUEST_STATUS_BADGE, REQUEST_STATUS_LABEL } from '~/constants'
import { overtimeService } from '~/services/overtimeService'
import type { OvertimeShift, OvertimeRequest } from '~/types/overtime'
import { formatDate, formatTime } from '~/utils/format'

definePageMeta({ layout: 'default' })

const shiftColumns: TableColumn<OvertimeShift>[] = [
  { accessorKey: 'name', header: 'Tên ca' },
  { accessorKey: 'start_time', header: 'Giờ bắt đầu' },
  { accessorKey: 'end_time', header: 'Giờ kết thúc' },
  { accessorKey: 'date', header: 'Ngày' },
  { accessorKey: 'registration_count', header: 'Số đăng ký' },
  { id: 'actions', header: 'Hành động' },
]

const requestColumns: TableColumn<OvertimeRequest>[] = [
  { accessorKey: 'employee_name', header: 'Nhân viên' },
  { accessorKey: 'shift_name', header: 'Ca tăng ca' },
  { accessorKey: 'shift_date', header: 'Ngày' },
  { accessorKey: 'status', header: 'Trạng thái' },
  { id: 'actions', header: 'Hành động' },
]

const toast = useAppToast()

// --- Shifts state ---
const shifts = ref<OvertimeShift[]>([])
const shiftsLoading = ref(false)
const shiftsError = ref('')

// --- Requests state ---
const requests = ref<OvertimeRequest[]>([])
const requestsLoading = ref(false)
const requestsError = ref('')

const {
  currentPage: requestsPage, lastPage: requestsLastPage, total: requestsTotal,
  perPage: requestsPerPage, setFromResponse: setRequestsFromResponse,
  goToPage: goToRequestsPageFn, visiblePages: requestsVisiblePages,
  summaryFrom: requestsSummaryFrom, summaryTo: requestsSummaryTo,
} = usePagination(20)

function goToRequestsPage(page: number) {
  goToRequestsPageFn(page, fetchRequests)
}

// --- Modal state ---
const showModal = ref(false)
const editingShift = ref<OvertimeShift | null>(null)
const modalLoading = ref(false)
const modalError = ref('')

const form = reactive({
  name: '',
  start_time: '',
  end_time: '',
  date: '',
  max_registrations: 10,
})

// --- Action loading map ---
const actionLoading = ref<Record<number, boolean>>({})

// --- Fetch shifts ---
async function fetchShifts() {
  shiftsLoading.value = true
  shiftsError.value = ''
  try {
    const res = await overtimeService.getShifts()
    shifts.value = Array.isArray(res) ? res : (res as any).data ?? []
  } catch {
    shiftsError.value = 'Không thể tải danh sách ca tăng ca.'
  } finally {
    shiftsLoading.value = false
  }
}

// --- Fetch requests ---
async function fetchRequests(page = 1) {
  requestsLoading.value = true
  requestsError.value = ''
  try {
    const res = await overtimeService.getRequests({ page, per_page: requestsPerPage.value })
    requests.value = setRequestsFromResponse(res)
  } catch {
    requestsError.value = 'Không thể tải danh sách yêu cầu tăng ca.'
  } finally {
    requestsLoading.value = false
  }
}

// --- Open add modal ---
function openAddModal() {
  editingShift.value = null
  form.name = ''
  form.start_time = ''
  form.end_time = ''
  form.date = ''
  form.max_registrations = 10
  modalError.value = ''
  showModal.value = true
}

// --- Open edit modal ---
function openEditModal(shift: OvertimeShift) {
  editingShift.value = shift
  form.name = shift.name
  form.start_time = shift.start_time
  form.end_time = shift.end_time
  form.date = shift.date
  form.max_registrations = shift.max_registrations
  modalError.value = ''
  showModal.value = true
}

// --- Submit modal ---
async function submitModal() {
  if (!form.name || !form.start_time || !form.end_time || !form.date) {
    modalError.value = 'Vui lòng điền đầy đủ thông tin.'
    return
  }
  modalLoading.value = true
  modalError.value = ''
  try {
    if (editingShift.value) {
      await overtimeService.updateShift(editingShift.value.id, { ...form })
      toast.success('Cập nhật ca tăng ca thành công.')
    } else {
      await overtimeService.createShift({ ...form })
      toast.success('Thêm ca tăng ca thành công.')
    }
    showModal.value = false
    await fetchShifts()
  } catch {
    modalError.value = 'Lưu thất bại. Vui lòng thử lại.'
  } finally {
    modalLoading.value = false
  }
}

// --- Delete shift ---
async function deleteShift(shift: OvertimeShift) {
  if (!confirm(`Bạn có chắc muốn xoá ca "${shift.name}"?`)) return
  actionLoading.value[shift.id] = true
  try {
    await overtimeService.deleteShift(shift.id)
    toast.success('Xóa ca tăng ca thành công.')
    await fetchShifts()
  } catch {
  } finally {
    delete actionLoading.value[shift.id]
  }
}

// --- Approve / Reject request ---
async function updateRequestStatus(request: OvertimeRequest, status: 'approved' | 'rejected') {
  actionLoading.value[request.id] = true
  try {
    const label = status === 'approved' ? 'Đã duyệt yêu cầu tăng ca.' : 'Đã từ chối yêu cầu tăng ca.'
    await overtimeService.updateRequestStatus(request.id, status)
    toast.success(label)
    await fetchRequests(requestsPage.value)
  } catch {
  } finally {
    delete actionLoading.value[request.id]
  }
}


function statusBadgeClass(status: string) {
  return REQUEST_STATUS_BADGE[status] ?? 'warning'
}

function statusLabel(status: string) {
  return REQUEST_STATUS_LABEL[status] ?? status
}

onMounted(() => {
  fetchShifts()
  fetchRequests()
})
</script>

<template>
  <div class="max-w-7xl mx-auto space-y-8">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
      <div>
        <h1 class="text-2xl font-bold text-ink">Quản lý tăng ca</h1>
        <p class="text-sm text-muted mt-1">Quản lý ca tăng ca và phê duyệt yêu cầu nhân viên</p>
      </div>
    </div>

    <!-- Section 1: Overtime Shifts -->
    <UCard>
      <div class="flex items-center justify-between mb-5">
        <h2 class="text-lg font-semibold text-ink">Danh sách ca tăng ca</h2>
        <UButton icon="i-heroicons-plus" @click="openAddModal">
          Thêm ca tăng ca
        </UButton>
      </div>

      <!-- Error -->
      <div v-if="shiftsError" class="mb-4 bg-danger-soft text-danger rounded-lg px-4 py-3 text-sm">
        {{ shiftsError }}
      </div>

      <!-- Loading -->
      <div v-if="shiftsLoading" class="flex justify-center py-12">
        <UIcon name="i-heroicons-arrow-path" class="animate-spin h-8 w-8 text-accent" />
      </div>

      <!-- Table -->
      <div v-else-if="shifts.length > 0">
        <UTable :data="shifts" :columns="shiftColumns">
          <template #start_time-cell="{ row }">
            {{ formatTime(row.original.start_time) }}
          </template>
          <template #end_time-cell="{ row }">
            {{ formatTime(row.original.end_time) }}
          </template>
          <template #date-cell="{ row }">
            {{ formatDate(row.original.date) }}
          </template>
          <template #registration_count-cell="{ row }">
            {{ row.original.registration_count ?? 0 }} / {{ row.original.max_registrations }}
          </template>
          <template #actions-cell="{ row }">
            <div class="flex items-center gap-2">
              <UButton color="neutral" variant="soft" size="xs" @click="openEditModal(row.original)">Sửa</UButton>
              <UButton
                color="error"
                size="xs"
                :loading="actionLoading[row.original.id]"
                @click="deleteShift(row.original)"
              >
                Xoá
              </UButton>
            </div>
          </template>
        </UTable>
      </div>

      <!-- Empty -->
      <div v-else class="text-center py-12 text-faint">
        <UIcon name="i-heroicons-inbox" class="mx-auto h-12 w-12 mb-3" />
        <p class="text-sm">Chưa có ca tăng ca nào</p>
      </div>
    </UCard>

    <!-- Section 2: Overtime Requests -->
    <UCard>
      <div class="flex items-center justify-between mb-5">
        <h2 class="text-lg font-semibold text-ink">Yêu cầu tăng ca từ nhân viên</h2>
        <UButton color="neutral" variant="soft" size="sm" icon="i-heroicons-arrow-path" @click="fetchRequests(requestsPage)">
          Làm mới
        </UButton>
      </div>

      <!-- Error -->
      <div v-if="requestsError" class="mb-4 bg-danger-soft text-danger rounded-lg px-4 py-3 text-sm">
        {{ requestsError }}
      </div>

      <!-- Loading -->
      <div v-if="requestsLoading" class="flex justify-center py-12">
        <UIcon name="i-heroicons-arrow-path" class="animate-spin h-8 w-8 text-accent" />
      </div>

      <!-- Table -->
      <div v-else-if="requests.length > 0">
        <UTable :data="requests" :columns="requestColumns">
          <template #shift_date-cell="{ row }">
            {{ formatDate(row.original.shift_date) }}
          </template>
          <template #status-cell="{ row }">
            <StatusChip :color="statusBadgeClass(row.original.status)">{{ statusLabel(row.original.status) }}</StatusChip>
          </template>
          <template #actions-cell="{ row }">
            <div v-if="row.original.status === 'pending'" class="flex items-center gap-2">
              <UButton
                size="xs"
                icon="i-heroicons-check"
                :loading="actionLoading[row.original.id]"
                @click="updateRequestStatus(row.original, 'approved')"
              >
                Duyệt
              </UButton>
              <UButton
                color="error"
                size="xs"
                icon="i-heroicons-x-mark"
                :loading="actionLoading[row.original.id]"
                @click="updateRequestStatus(row.original, 'rejected')"
              >
                Từ chối
              </UButton>
            </div>
            <span v-else class="text-faint text-xs">—</span>
          </template>
        </UTable>

        <!-- Pagination -->
        <PaginationBar
          :current-page="requestsPage" :last-page="requestsLastPage"
          :total="requestsTotal" :summary-from="requestsSummaryFrom" :summary-to="requestsSummaryTo"
          :visible-pages="requestsVisiblePages"
          @go-to-page="goToRequestsPage"
        />
      </div>

      <!-- Empty -->
      <div v-else class="text-center py-12 text-faint">
        <UIcon name="i-heroicons-inbox" class="mx-auto h-12 w-12 mb-3" />
        <p class="text-sm">Chưa có yêu cầu tăng ca nào</p>
      </div>
    </UCard>

    <!-- Add/Edit Modal -->
    <BaseModal
      v-model="showModal"
      :title="editingShift ? 'Chỉnh sửa ca tăng ca' : 'Thêm ca tăng ca mới'"
    >
      <div v-if="modalError" class="bg-danger-soft text-danger rounded-lg px-4 py-3 text-sm">
        {{ modalError }}
      </div>

      <div>
        <label class="block text-sm font-medium text-body mb-1">Tên ca <span class="text-danger">*</span></label>
        <UInput v-model="form.name" type="text" class="w-full" placeholder="VD: Ca tăng ca chiều" />
      </div>

      <div class="grid grid-cols-2 gap-4">
        <div>
          <label class="block text-sm font-medium text-body mb-1">Giờ bắt đầu <span class="text-danger">*</span></label>
          <UInput v-model="form.start_time" type="time" class="w-full" />
        </div>
        <div>
          <label class="block text-sm font-medium text-body mb-1">Giờ kết thúc <span class="text-danger">*</span></label>
          <UInput v-model="form.end_time" type="time" class="w-full" />
        </div>
      </div>

      <div>
        <label class="block text-sm font-medium text-body mb-1">Ngày <span class="text-danger">*</span></label>
        <UInput v-model="form.date" type="date" class="w-full" />
      </div>

      <div>
        <label class="block text-sm font-medium text-body mb-1">Số đăng ký tối đa</label>
        <UInput v-model.number="form.max_registrations" type="number" min="1" class="w-full" />
      </div>

      <template #footer>
        <UButton color="neutral" variant="soft" :disabled="modalLoading" @click="showModal = false">Huỷ</UButton>
        <UButton :loading="modalLoading" @click="submitModal">Lưu</UButton>
      </template>
    </BaseModal>
  </div>
</template>
