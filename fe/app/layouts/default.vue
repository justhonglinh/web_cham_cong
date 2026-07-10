<script setup lang="ts">
const authStore = useAuthStore()
const router = useRouter()
const mobileMenuOpen = ref(false)

async function handleLogout() {
  await authStore.logout()
  router.push('/login')
}

const userMenuItems = computed(() => [
  [
    { label: 'Hồ sơ', icon: 'i-heroicons-user-circle', to: '/profile' },
  ],
  [
    { label: 'Đăng xuất', icon: 'i-heroicons-arrow-right-on-rectangle', onSelect: handleLogout },
  ],
])

const employeeMenuItems = computed(() => [
  [
    { label: authStore.user?.name, description: authStore.user?.email, type: 'label' as const },
  ],
  [
    { label: 'Dashboard', icon: 'i-heroicons-chart-bar', to: '/employees/dashboard' },
    { label: 'Chấm công', icon: 'i-heroicons-clock', to: '/employees/attendance' },
    { label: 'Lịch sử chấm công', icon: 'i-heroicons-clock', to: '/employees/attendance/history' },
    { label: 'Tăng ca', icon: 'i-heroicons-bolt', to: '/overtime/employee' },
    { label: 'Nghỉ phép', icon: 'i-heroicons-calendar', to: '/employees/leave/history' },
    { label: 'Hồ sơ', icon: 'i-heroicons-user-circle', to: '/profile' },
  ],
  [
    { label: 'Đăng xuất', icon: 'i-heroicons-arrow-right-on-rectangle', onSelect: handleLogout },
  ],
])

const navGroups = [
  {
    label: 'Tổng quan',
    items: [
      { label: 'Trang chủ', to: '/dashboard', icon: 'i-heroicons-home' },
    ],
  },
  {
    label: 'Nhân sự',
    items: [
      { label: 'Nhân viên', to: '/employees/management', icon: 'i-heroicons-users' },
      { label: 'Chấm công', to: '/attendance/management', icon: 'i-heroicons-finger-print' },
      { label: 'Ca làm việc', to: '/shift/management', icon: 'i-heroicons-calendar-days' },
      { label: 'Tăng ca', to: '/overtime/management', icon: 'i-heroicons-bolt' },
      { label: 'Nghỉ phép', to: '/leave-requests/management', icon: 'i-heroicons-document-text' },
    ],
  },
  {
    label: 'Báo cáo',
    items: [
      { label: 'Bảng công', to: '/work-summary/management', icon: 'i-heroicons-chart-bar' },
      { label: 'Địa điểm', to: '/locations', icon: 'i-heroicons-map-pin' },
    ],
  },
]

function initials(name?: string) {
  if (!name) return '?'
  const parts = name.trim().split(/\s+/)
  return parts.length === 1 ? parts[0]!.slice(0, 2).toUpperCase() : (parts[0]![0]! + parts[parts.length - 1]![0]!).toUpperCase()
}
</script>

<template>
  <div class="min-h-screen bg-canvas">
    <!-- Manager: sidebar layout -->
    <div v-if="authStore.isManager" class="flex min-h-screen">
      <!-- Mobile top bar -->
      <div class="lg:hidden fixed inset-x-0 top-0 z-40 flex items-center justify-between h-14 px-4 bg-white border-b border-border">
        <NuxtLink to="/dashboard" class="flex items-center gap-2">
          <div class="w-7 h-7 bg-accent rounded-lg flex items-center justify-center">
            <UIcon name="i-heroicons-users" class="w-4 h-4 text-white" />
          </div>
          <span class="font-semibold text-ink text-sm">Chấm Công</span>
        </NuxtLink>
        <UButton
          color="neutral"
          variant="ghost"
          :icon="mobileMenuOpen ? 'i-heroicons-x-mark' : 'i-heroicons-bars-3'"
          @click="mobileMenuOpen = !mobileMenuOpen"
        />
      </div>

      <!-- Sidebar -->
      <aside
        class="sidebar-panel"
        :class="mobileMenuOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'"
      >
        <NuxtLink to="/dashboard" class="brand">
          <div class="brand-mark">
            <UIcon name="i-heroicons-users" class="w-4 h-4" />
          </div>
          <div>
            <p class="text-sm font-semibold text-ink leading-tight">Chấm Công</p>
            <p class="text-xs text-faint leading-tight">Quản trị viên</p>
          </div>
        </NuxtLink>

        <nav class="flex-1 overflow-y-auto">
          <template v-for="group in navGroups" :key="group.label">
            <p class="nav-group-label">{{ group.label }}</p>
            <NuxtLink
              v-for="item in group.items"
              :key="item.to"
              :to="item.to"
              class="sidebar-link"
              active-class="sidebar-link-active"
              @click="mobileMenuOpen = false"
            >
              <UIcon :name="item.icon" class="w-4 h-4 shrink-0" />
              {{ item.label }}
            </NuxtLink>
          </template>
        </nav>

        <div class="pt-3 border-t border-border">
          <UDropdownMenu :items="userMenuItems" :ui="{ content: 'w-52' }">
            <button class="user-card">
              <span class="avatar">{{ initials(authStore.user?.name) }}</span>
              <span class="min-w-0 text-left">
                <span class="block text-sm font-semibold text-ink truncate">{{ authStore.user?.name }}</span>
                <span class="block text-xs text-faint truncate">{{ authStore.user?.email }}</span>
              </span>
            </button>
          </UDropdownMenu>
        </div>
      </aside>

      <div
        v-if="mobileMenuOpen"
        class="fixed inset-0 z-30 bg-black/30 lg:hidden"
        @click="mobileMenuOpen = false"
      />

      <main class="flex-1 min-w-0 pt-14 lg:pt-8 p-5 sm:p-8">
        <slot />
      </main>
    </div>

    <!-- Employee: simple top bar layout -->
    <div v-else-if="authStore.isEmployee">
      <nav class="bg-white border-b border-border">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
          <div class="flex justify-between h-16">
            <div class="flex items-center">
              <NuxtLink to="/employees/dashboard" class="flex items-center gap-2">
                <div class="w-8 h-8 bg-accent rounded-lg flex items-center justify-center">
                  <UIcon name="i-heroicons-users" class="w-5 h-5 text-white" />
                </div>
                <span class="text-ink font-semibold hidden sm:block">Employee Portal</span>
              </NuxtLink>
            </div>

            <div class="flex items-center space-x-4">
              <UDropdownMenu :items="employeeMenuItems" :ui="{ content: 'w-56' }">
                <UButton color="neutral" variant="ghost" trailing-icon="i-heroicons-chevron-down">
                  <span class="hidden sm:block">{{ authStore.user?.name }}</span>
                </UButton>
              </UDropdownMenu>
            </div>
          </div>
        </div>
      </nav>

      <main class="p-6">
        <slot />
      </main>
    </div>
  </div>
</template>

<style scoped>
@reference "~/assets/css/main.css";

.sidebar-panel {
  @apply fixed lg:sticky top-0 left-0 z-40 h-screen w-64 shrink-0 bg-white border-r border-border flex flex-col p-3.5 transition-transform duration-200;
}
.brand {
  @apply flex items-center gap-2.5 px-2 pb-4 mb-2 border-b border-border;
}
.brand-mark {
  @apply w-7 h-7 rounded-lg bg-accent text-white flex items-center justify-center shrink-0;
}
.nav-group-label {
  @apply text-[11px] font-semibold uppercase tracking-wide text-faint px-2.5 pt-3.5 pb-1.5;
}
.sidebar-link {
  @apply flex items-center gap-2.5 px-2.5 py-2 rounded-lg text-[13.5px] font-medium text-body hover:bg-neutral-soft hover:text-ink transition-colors;
}
.sidebar-link-active {
  @apply bg-accent-soft text-accent-ink font-semibold;
}
.user-card {
  @apply w-full flex items-center gap-2.5 p-2 rounded-lg hover:bg-neutral-soft transition-colors;
}
.avatar {
  @apply w-8 h-8 rounded-full bg-accent-soft text-accent-ink flex items-center justify-center text-xs font-bold shrink-0;
}
</style>
