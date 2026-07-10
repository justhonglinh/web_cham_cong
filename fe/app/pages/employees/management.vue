<script setup lang="ts">
import type { TableColumn } from '@nuxt/ui'
import { userService } from '~/services/userService'
import type { User, CreateUserInput as UserForm } from '~/types/user'
import { ROLE_BADGE, ROLE_LABEL } from '~/constants'

definePageMeta({ layout: 'default' })

const columns: TableColumn<User>[] = [
  { accessorKey: 'name', header: 'Tên' },
  { accessorKey: 'email', header: 'Email' },
  { accessorKey: 'phone', header: 'Số điện thoại' },
  { accessorKey: 'role', header: 'Vai trò' },
  { accessorKey: 'status', header: 'Trạng thái' },
  { id: 'actions', header: '', meta: { class: { th: 'text-right', td: 'text-right' } } },
]

const toast = useAppToast()

const loading = ref(true)
const saving = ref(false)
const deleting = ref(false)
const error = ref<string | null>(null)
const saveError = ref<string | null>(null)

const users = ref<User[]>([])
const search = ref('')
let searchDebounce: ReturnType<typeof setTimeout> | undefined

const showModal = ref(false)
const editingId = ref<number | null>(null)
const form = ref<UserForm>({
  name: '',
  email: '',
  phone: '',
  role: 'employee',
  password: '',
})

const showDeleteConfirm = ref(false)
const deletingId = ref<number | null>(null)
const deletingName = ref('')

const { currentPage, lastPage, total, perPage, setFromResponse, goToPage: goToPageFn, visiblePages, summaryFrom, summaryTo } = usePagination(20)

function goToPage(page: number) {
  goToPageFn(page, fetchUsers)
}

watch(search, () => {
  clearTimeout(searchDebounce)
  searchDebounce = setTimeout(() => fetchUsers(1), 300)
})

async function fetchUsers(page = 1) {
  loading.value = true
  error.value = null
  try {
    const res = await userService.getAll({ page, per_page: perPage.value, search: search.value.trim() || undefined })
    users.value = setFromResponse(res)
  } catch (e: any) {
    error.value = e?.data?.message || 'Không thể tải danh sách nhân viên.'
  } finally {
    loading.value = false
  }
}

function openAdd() {
  editingId.value = null
  form.value = { name: '', email: '', phone: '', role: 'employee', password: '' }
  saveError.value = null
  showModal.value = true
}

function openEdit(user: User) {
  editingId.value = user.id
  form.value = {
    name: user.name,
    email: user.email,
    phone: user.phone ?? '',
    role: user.role,
    password: '',
  }
  saveError.value = null
  showModal.value = true
}

function closeModal() {
  showModal.value = false
  saveError.value = null
}

async function saveUser() {
  if (!form.value.name.trim() || !form.value.email.trim()) {
    saveError.value = 'Vui lòng nhập đầy đủ họ tên và email.'
    return
  }
  if (!editingId.value && !form.value.password.trim()) {
    saveError.value = 'Vui lòng nhập mật khẩu cho nhân viên mới.'
    return
  }
  saving.value = true
  saveError.value = null
  try {
    const payload: Record<string, unknown> = {
      name: form.value.name,
      email: form.value.email,
      phone: form.value.phone,
      role: form.value.role,
    }
    if (form.value.password.trim()) {
      payload.password = form.value.password
    }

    if (editingId.value) {
      const updated = await userService.update(editingId.value, payload)
      const idx = users.value.findIndex(u => u.id === editingId.value)
      if (idx !== -1) users.value[idx] = updated
      toast.success('Cập nhật nhân viên thành công.')
    } else {
      await userService.create(payload as any)
      toast.success('Thêm nhân viên thành công.')
      await fetchUsers(1)
    }
    closeModal()
  } catch (e: any) {
    toast.error(e?.data?.message || 'Lưu thất bại. Vui lòng thử lại.')
    saveError.value = e?.data?.message || 'Lưu thất bại. Vui lòng thử lại.'
  } finally {
    saving.value = false
  }
}

function confirmDelete(user: User) {
  deletingId.value = user.id
  deletingName.value = user.name
  showDeleteConfirm.value = true
}

async function deleteUser() {
  if (!deletingId.value) return
  deleting.value = true
  try {
    await userService.delete(deletingId.value)
    showDeleteConfirm.value = false
    toast.success('Xóa nhân viên thành công.')
    await fetchUsers(currentPage.value)
  } catch (e: any) {
    toast.error(e?.data?.message || 'Xóa thất bại. Vui lòng thử lại.')
    showDeleteConfirm.value = false
  } finally {
    deleting.value = false
  }
}

function roleBadge(role: string) {
  return ROLE_BADGE[role] ?? 'success'
}

function roleLabel(role: string) {
  return ROLE_LABEL[role] ?? role
}

function initials(name: string): string {
  const parts = name.trim().split(/\s+/).filter(Boolean)
  if (parts.length === 0) return ''
  if (parts.length === 1) return parts[0].slice(0, 2).toUpperCase()
  return (parts[0].charAt(0) + parts[parts.length - 1].charAt(0)).toUpperCase()
}

onMounted(fetchUsers)
</script>

<template>
  <div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
      <div>
        <h1 class="text-2xl font-bold text-ink">Quản lý nhân viên</h1>
        <p class="text-sm text-muted mt-0.5">Danh sách toàn bộ nhân viên trong hệ thống</p>
      </div>
      <UButton icon="i-heroicons-plus" @click="openAdd">
        Thêm nhân viên
      </UButton>
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

    <!-- Toolbar -->
    <UCard>
      <div class="max-w-sm">
        <UInput
          v-model="search"
          type="text"
          placeholder="Tìm theo tên, email, số điện thoại..."
          icon="i-heroicons-magnifying-glass"
          class="w-full"
        />
      </div>
    </UCard>

    <!-- Table -->
    <UCard :ui="{ body: 'p-0 sm:p-0' }">
      <UTable
        :data="users"
        :columns="columns"
        :loading="loading"
        empty="Không tìm thấy nhân viên nào"
      >
        <template #name-cell="{ row }">
          <div class="flex items-center gap-2.5">
            <div class="w-8 h-8 rounded-full bg-neutral-soft flex items-center justify-center text-body font-semibold text-xs shrink-0">
              {{ initials(row.original.name) }}
            </div>
            <span class="text-sm font-medium text-ink">{{ row.original.name }}</span>
          </div>
        </template>
        <template #phone-cell="{ row }">
          <span class="text-body">{{ row.original.phone || '—' }}</span>
        </template>
        <template #role-cell="{ row }">
          <StatusChip :color="roleBadge(row.original.role)">{{ roleLabel(row.original.role) }}</StatusChip>
        </template>
        <template #status-cell="{ row }">
          <StatusChip :color="row.original.status === 'inactive' ? 'error' : 'success'">
            {{ row.original.status === 'inactive' ? 'Ngừng hoạt động' : 'Hoạt động' }}
          </StatusChip>
        </template>
        <template #actions-cell="{ row }">
          <div class="flex items-center justify-end gap-2">
            <UButton
              color="neutral"
              variant="soft"
              size="xs"
              icon="i-heroicons-pencil-square"
              @click="openEdit(row.original)"
            >
              Sửa
            </UButton>
            <UButton
              color="error"
              variant="soft"
              size="xs"
              icon="i-heroicons-trash"
              @click="confirmDelete(row.original)"
            >
              Xóa
            </UButton>
          </div>
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

    <!-- Add/Edit Modal -->
    <BaseModal
      :model-value="showModal"
      :title="editingId ? 'Chỉnh sửa nhân viên' : 'Thêm nhân viên mới'"
      @update:model-value="closeModal"
    >
      <div v-if="saveError" class="bg-danger-soft rounded-lg px-3 py-2 text-sm text-danger">
        {{ saveError }}
      </div>

      <div>
        <label class="block text-sm font-medium text-body mb-1">Họ và tên <span class="text-danger">*</span></label>
        <UInput v-model="form.name" type="text" class="w-full" placeholder="Nguyễn Văn A" />
      </div>

      <div>
        <label class="block text-sm font-medium text-body mb-1">Email <span class="text-danger">*</span></label>
        <UInput v-model="form.email" type="email" class="w-full" placeholder="nhanvien@example.com" />
      </div>

      <div>
        <label class="block text-sm font-medium text-body mb-1">Số điện thoại</label>
        <UInput v-model="form.phone" type="tel" class="w-full" placeholder="0901234567" />
      </div>

      <div>
        <label class="block text-sm font-medium text-body mb-1">Vai trò <span class="text-danger">*</span></label>
        <USelect
          v-model="form.role"
          :items="[{ label: 'Nhân viên', value: 'employee' }, { label: 'Quản lý', value: 'manager' }]"
          class="w-full"
        />
      </div>

      <div>
        <label class="block text-sm font-medium text-body mb-1">
          Mật khẩu <span v-if="!editingId" class="text-danger">*</span>
          <span v-else class="text-faint font-normal">(để trống nếu không đổi)</span>
        </label>
        <UInput v-model="form.password" type="password" class="w-full" placeholder="••••••••" />
      </div>

      <template #footer>
        <UButton color="neutral" variant="soft" :disabled="saving" @click="closeModal">Hủy</UButton>
        <UButton :loading="saving" @click="saveUser">
          {{ editingId ? 'Lưu thay đổi' : 'Thêm nhân viên' }}
        </UButton>
      </template>
    </BaseModal>

    <!-- Delete Confirm Modal -->
    <ConfirmModal
      v-model="showDeleteConfirm"
      confirm-text="Xóa nhân viên"
      :loading="deleting"
      @confirm="deleteUser"
    >
      Bạn có chắc muốn xóa nhân viên <strong>{{ deletingName }}</strong>?
    </ConfirmModal>
  </div>
</template>
