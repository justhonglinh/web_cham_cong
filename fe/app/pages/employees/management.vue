<script setup lang="ts">
import { userService } from '~/services/userService'
import type { User, CreateUserInput as UserForm } from '~/types/user'
import { ROLE_BADGE, ROLE_LABEL } from '~/constants'

definePageMeta({ layout: 'default' })

const toast = useToast()

const loading = ref(true)
const saving = ref(false)
const deleting = ref(false)
const error = ref<string | null>(null)
const saveError = ref<string | null>(null)

const users = ref<User[]>([])
const search = ref('')

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

const { currentPage, lastPage, total, perPage, setTotal, paginateArray, goToPage, visiblePages, summaryFrom, summaryTo } = usePagination(20)

const filteredUsers = computed(() => {
  const q = search.value.trim().toLowerCase()
  if (!q) return users.value
  return users.value.filter(u =>
    u.name.toLowerCase().includes(q) ||
    u.email.toLowerCase().includes(q) ||
    (u.phone ?? '').toLowerCase().includes(q)
  )
})

const pagedUsers = computed(() => paginateArray(filteredUsers.value))

watch(search, () => { currentPage.value = 1 })
watch(filteredUsers, (val) => setTotal(val.length))

async function fetchUsers() {
  loading.value = true
  error.value = null
  try {
    const res = await userService.getAll()
    users.value = Array.isArray(res) ? res : (res as any).data ?? []
    setTotal(users.value.length)
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
      const created = await userService.create(payload as any)
      users.value.unshift(created)
      toast.success('Thêm nhân viên thành công.')
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
    users.value = users.value.filter(u => u.id !== deletingId.value)
    showDeleteConfirm.value = false
    toast.success('Xóa nhân viên thành công.')
  } catch (e: any) {
    toast.error(e?.data?.message || 'Xóa thất bại. Vui lòng thử lại.')
    showDeleteConfirm.value = false
  } finally {
    deleting.value = false
  }
}

function roleBadge(role: string) {
  return ROLE_BADGE[role] ?? 'badge-success'
}

function roleLabel(role: string) {
  return ROLE_LABEL[role] ?? role
}

onMounted(fetchUsers)
</script>

<template>
  <div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Quản lý nhân viên</h1>
        <p class="text-sm text-gray-500 mt-0.5">Danh sách toàn bộ nhân viên trong hệ thống</p>
      </div>
      <button class="btn-primary gap-2" @click="openAdd">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Thêm nhân viên
      </button>
    </div>

    <!-- Error Banner -->
    <div v-if="error" class="card p-4 border-l-4 border-red-500 flex items-center justify-between">
      <p class="text-sm text-red-700">{{ error }}</p>
      <button class="text-red-500 hover:text-red-700 ml-4" @click="error = null">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>

    <!-- Toolbar -->
    <div class="card p-4">
      <div class="relative max-w-sm">
        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
        <input
          v-model="search"
          type="text"
          placeholder="Tìm theo tên, email, số điện thoại..."
          class="input-field pl-9"
        />
      </div>
    </div>

    <!-- Table -->
    <div class="card overflow-hidden">
      <div v-if="loading" class="flex items-center justify-center py-16">
        <div class="h-8 w-8 animate-spin rounded-full border-4 border-blue-600 border-t-transparent"></div>
        <span class="ml-3 text-gray-500">Đang tải...</span>
      </div>

      <div v-else-if="filteredUsers.length === 0" class="text-center py-16">
        <svg class="mx-auto w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
        </svg>
        <p class="text-gray-500">Không tìm thấy nhân viên nào</p>
      </div>

      <div v-else class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-100">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Tên</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Email</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Số điện thoại</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Vai trò</th>
              <th class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wide">Trạng thái</th>
              <th class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wide">Hành động</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-50">
            <tr v-for="user in pagedUsers" :key="user.id" class="hover:bg-gray-50 transition-colors">
              <td class="px-6 py-4">
                <div class="flex items-center gap-3">
                  <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-semibold text-sm shrink-0">
                    {{ user.name.charAt(0).toUpperCase() }}
                  </div>
                  <span class="text-sm font-medium text-gray-900">{{ user.name }}</span>
                </div>
              </td>
              <td class="px-6 py-4 text-sm text-gray-600">{{ user.email }}</td>
              <td class="px-6 py-4 text-sm text-gray-600">{{ user.phone || '—' }}</td>
              <td class="px-6 py-4">
                <span :class="roleBadge(user.role)">{{ roleLabel(user.role) }}</span>
              </td>
              <td class="px-6 py-4">
                <span :class="user.status === 'inactive' ? 'badge-danger' : 'badge-success'">
                  {{ user.status === 'inactive' ? 'Ngừng hoạt động' : 'Hoạt động' }}
                </span>
              </td>
              <td class="px-6 py-4 text-right">
                <div class="flex items-center justify-end gap-2">
                  <button
                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors"
                    @click="openEdit(user)"
                  >
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Sửa
                  </button>
                  <button
                    class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors"
                    @click="confirmDelete(user)"
                  >
                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    Xóa
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <PaginationBar
        v-if="!loading"
        :current-page="currentPage" :last-page="lastPage"
        :total="total" :summary-from="summaryFrom" :summary-to="summaryTo"
        :visible-pages="visiblePages"
        @go-to-page="goToPage"
      />
    </div>

    <!-- Add/Edit Modal -->
    <Teleport to="body">
      <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md">
          <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-900">
              {{ editingId ? 'Chỉnh sửa nhân viên' : 'Thêm nhân viên mới' }}
            </h2>
            <button class="text-gray-400 hover:text-gray-600 transition-colors" @click="closeModal">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>

          <div class="px-6 py-4 space-y-4">
            <div v-if="saveError" class="bg-red-50 border border-red-200 rounded-lg px-3 py-2 text-sm text-red-700">
              {{ saveError }}
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Họ và tên <span class="text-red-500">*</span></label>
              <input v-model="form.name" type="text" class="input-field" placeholder="Nguyễn Văn A" />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
              <input v-model="form.email" type="email" class="input-field" placeholder="nhanvien@example.com" />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Số điện thoại</label>
              <input v-model="form.phone" type="tel" class="input-field" placeholder="0901234567" />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Vai trò <span class="text-red-500">*</span></label>
              <select v-model="form.role" class="input-field">
                <option value="employee">Nhân viên</option>
                <option value="manager">Quản lý</option>
              </select>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">
                Mật khẩu <span v-if="!editingId" class="text-red-500">*</span>
                <span v-else class="text-gray-400 font-normal">(để trống nếu không đổi)</span>
              </label>
              <input v-model="form.password" type="password" class="input-field" placeholder="••••••••" />
            </div>
          </div>

          <div class="flex items-center justify-end gap-3 px-6 py-4 border-t border-gray-100">
            <button class="btn-secondary" :disabled="saving" @click="closeModal">Hủy</button>
            <button class="btn-primary" :disabled="saving" @click="saveUser">
              <svg v-if="saving" class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z" />
              </svg>
              {{ editingId ? 'Lưu thay đổi' : 'Thêm nhân viên' }}
            </button>
          </div>
        </div>
      </div>
    </Teleport>

    <!-- Delete Confirm Modal -->
    <Teleport to="body">
      <div v-if="showDeleteConfirm" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm p-6">
          <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center shrink-0">
              <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
              </svg>
            </div>
            <div>
              <h3 class="font-semibold text-gray-900">Xác nhận xóa</h3>
              <p class="text-sm text-gray-500">Hành động này không thể hoàn tác.</p>
            </div>
          </div>
          <p class="text-sm text-gray-700 mb-6">
            Bạn có chắc muốn xóa nhân viên <strong>{{ deletingName }}</strong>?
          </p>
          <div class="flex gap-3 justify-end">
            <button class="btn-secondary" :disabled="deleting" @click="showDeleteConfirm = false">Hủy</button>
            <button class="btn-danger" :disabled="deleting" @click="deleteUser">
              <svg v-if="deleting" class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z" />
              </svg>
              Xóa nhân viên
            </button>
          </div>
        </div>
      </div>
    </Teleport>
  </div>
</template>
