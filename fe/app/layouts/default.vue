<script setup lang="ts">
const authStore = useAuthStore()
const router = useRouter()
const mobileMenuOpen = ref(false)
const dropdownRef = ref<HTMLElement | null>(null)

onClickOutside(dropdownRef, () => {
  mobileMenuOpen.value = false
})

async function handleLogout() {
  await authStore.logout()
  router.push('/login')
}
</script>

<template>
  <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
    <!-- Manager Navigation -->
    <nav v-if="authStore.isManager" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex">
            <div class="shrink-0 flex items-center">
              <NuxtLink to="/dashboard" class="flex items-center space-x-2">
                <div class="w-8 h-8 bg-blue-600 rounded-lg flex items-center justify-center">
                  <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                  </svg>
                </div>
                <span class="font-bold text-gray-900 hidden sm:block">Chấm Công</span>
              </NuxtLink>
            </div>
            <div class="hidden space-x-1 sm:-my-px sm:ms-10 sm:flex items-center">
              <NuxtLink to="/dashboard" class="nav-link" active-class="nav-link-active">Trang Chủ</NuxtLink>
              <NuxtLink to="/employees/management" class="nav-link" active-class="nav-link-active">Nhân Viên</NuxtLink>
              <NuxtLink to="/attendance/management" class="nav-link" active-class="nav-link-active">Chấm Công</NuxtLink>
              <NuxtLink to="/work-summary/management" class="nav-link" active-class="nav-link-active">Báo cáo</NuxtLink>
              <NuxtLink to="/overtime/management" class="nav-link" active-class="nav-link-active">Tăng Ca</NuxtLink>
              <NuxtLink to="/shift/management" class="nav-link" active-class="nav-link-active">Ca làm việc</NuxtLink>
              <NuxtLink to="/leave-requests/management" class="nav-link" active-class="nav-link-active">Nghỉ phép</NuxtLink>
            </div>
          </div>

          <div class="hidden sm:flex sm:items-center sm:ms-6">
            <div class="relative" ref="dropdownRef">
              <button
                @click="mobileMenuOpen = !mobileMenuOpen"
                class="inline-flex items-center px-3 py-2 text-sm font-medium rounded-md text-gray-500 hover:text-gray-700 hover:bg-gray-50 transition-colors"
              >
                <span>{{ authStore.user?.name }}</span>
                <svg class="ml-1 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
              </button>
              <div v-if="mobileMenuOpen" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-100 py-1 z-50">
                <NuxtLink to="/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50" @click="mobileMenuOpen = false">Hồ sơ</NuxtLink>
                <button @click="handleLogout" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">Đăng xuất</button>
              </div>
            </div>
          </div>

          <div class="-me-2 flex items-center sm:hidden">
            <button @click="mobileMenuOpen = !mobileMenuOpen" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:bg-gray-100">
              <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                <path v-if="!mobileMenuOpen" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
        </div>
      </div>

      <div v-if="mobileMenuOpen" class="sm:hidden border-t border-gray-100">
        <div class="pt-2 pb-3 space-y-1 px-4">
          <NuxtLink to="/dashboard" class="mobile-nav-link" active-class="mobile-nav-link-active" @click="mobileMenuOpen = false">Trang Chủ</NuxtLink>
          <NuxtLink to="/employees/management" class="mobile-nav-link" active-class="mobile-nav-link-active" @click="mobileMenuOpen = false">Nhân Viên</NuxtLink>
          <NuxtLink to="/attendance/management" class="mobile-nav-link" active-class="mobile-nav-link-active" @click="mobileMenuOpen = false">Chấm Công</NuxtLink>
          <NuxtLink to="/overtime/management" class="mobile-nav-link" active-class="mobile-nav-link-active" @click="mobileMenuOpen = false">Tăng Ca</NuxtLink>
          <NuxtLink to="/leave-requests/management" class="mobile-nav-link" active-class="mobile-nav-link-active" @click="mobileMenuOpen = false">Nghỉ phép</NuxtLink>
        </div>
        <div class="pt-4 pb-3 border-t border-gray-200 px-4">
          <p class="font-medium text-gray-900">{{ authStore.user?.name }}</p>
          <p class="text-sm text-gray-500">{{ authStore.user?.email }}</p>
          <div class="mt-3 space-y-1">
            <NuxtLink to="/profile" class="mobile-nav-link" @click="mobileMenuOpen = false">Hồ sơ</NuxtLink>
            <button @click="handleLogout" class="block w-full text-left py-2 text-sm text-red-600">Đăng xuất</button>
          </div>
        </div>
      </div>
    </nav>

    <!-- Employee Navigation -->
    <nav v-else-if="authStore.isEmployee" class="bg-gradient-to-r from-blue-600 via-purple-600 to-pink-600 shadow-lg">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex items-center">
            <NuxtLink to="/employees/dashboard" class="flex items-center space-x-2">
              <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                </svg>
              </div>
              <span class="text-white font-bold text-lg hidden sm:block">Employee Portal</span>
            </NuxtLink>
          </div>

          <div class="flex items-center space-x-4">
            <div class="relative">
              <button
                @click="mobileMenuOpen = !mobileMenuOpen"
                class="inline-flex items-center px-3 py-2 text-sm font-medium text-white/90 hover:text-white hover:bg-white/10 rounded-lg transition-colors"
              >
                <span class="hidden sm:block">{{ authStore.user?.name }}</span>
                <svg class="ml-1 h-4 w-4 fill-current" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
              </button>
              <div v-if="mobileMenuOpen" class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-100 py-1 z-50">
                <div class="px-4 py-2 border-b">
                  <p class="font-medium text-gray-900 text-sm">{{ authStore.user?.name }}</p>
                  <p class="text-xs text-gray-500">{{ authStore.user?.email }}</p>
                </div>
                <NuxtLink to="/employees/dashboard" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50" @click="mobileMenuOpen = false">Dashboard</NuxtLink>
                <NuxtLink to="/employees/attendance" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50" @click="mobileMenuOpen = false">Chấm công</NuxtLink>
                <NuxtLink to="/employees/attendance/history" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50" @click="mobileMenuOpen = false">Lịch sử chấm công</NuxtLink>
                <NuxtLink to="/overtime/employee" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50" @click="mobileMenuOpen = false">Tăng ca</NuxtLink>
                <NuxtLink to="/employees/leave/history" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50" @click="mobileMenuOpen = false">Nghỉ phép</NuxtLink>
                <NuxtLink to="/profile" class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50" @click="mobileMenuOpen = false">Hồ sơ</NuxtLink>
                <button @click="handleLogout" class="flex items-center w-full px-4 py-2 text-sm text-red-600 hover:bg-red-50">Đăng xuất</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </nav>

    <main class="p-6">
      <slot />
    </main>
  </div>
</template>

<style scoped>
.nav-link {
  @apply inline-flex items-center px-3 py-2 text-sm font-medium text-gray-500 hover:text-gray-700 hover:bg-gray-50 rounded-md transition-colors;
}
.nav-link-active {
  @apply text-blue-600 bg-blue-50 border-b-2 border-blue-600;
}
.mobile-nav-link {
  @apply block py-2 text-sm text-gray-700 hover:text-gray-900;
}
.mobile-nav-link-active {
  @apply text-blue-600 font-medium;
}
</style>
