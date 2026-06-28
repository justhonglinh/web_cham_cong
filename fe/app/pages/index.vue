<script setup lang="ts">
const authStore = useAuthStore()
if (authStore.isAuthenticated) {
  navigateTo(authStore.isManager ? '/dashboard' : '/employees/dashboard')
} else {
  navigateTo('/login')
}
</script>

<template>
  <div></div>
</template>
