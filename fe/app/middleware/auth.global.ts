export default defineNuxtRouteMiddleware(async (to) => {
  const authStore = useAuthStore()
  const publicRoutes = ['/login', '/register', '/forgot-password', '/reset-password']

  if (publicRoutes.includes(to.path)) {
    if (authStore.token && !authStore.user) {
      await authStore.fetchUser()
    }
    if (authStore.isAuthenticated) {
      return navigateTo(authStore.isManager ? '/dashboard' : '/employees/dashboard')
    }
    return
  }

  if (!authStore.token) {
    return navigateTo('/login')
  }

  if (!authStore.user) {
    const user = await authStore.fetchUser()
    if (!user) {
      return navigateTo('/login')
    }
  }

  // Role-based access control
  const managerRoutes = ['/dashboard', '/employees/management', '/attendance/management', '/overtime/management', '/shift/management', '/leave-requests/management', '/work-summary/management', '/locations']
  const employeeRoutes = ['/employees/dashboard', '/employees/attendance', '/overtime/employee', '/employees/leave']

  const isManagerRoute = managerRoutes.some(r => to.path.startsWith(r))
  const isEmployeeRoute = employeeRoutes.some(r => to.path.startsWith(r))

  if (isManagerRoute && !authStore.isManager) {
    return navigateTo('/employees/dashboard')
  }

  if (isEmployeeRoute && !authStore.isEmployee) {
    return navigateTo('/dashboard')
  }
})
