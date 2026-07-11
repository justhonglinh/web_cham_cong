<template>
  <div class="min-h-screen flex">
    <!-- Left branding panel -->
    <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden flex-col justify-between p-12 brand-panel">
      <div class="absolute -top-24 -right-24 w-96 h-96 bg-white/10 rounded-full blur-3xl" />
      <div class="absolute -bottom-32 -left-20 w-96 h-96 bg-white/10 rounded-full blur-3xl" />
      <div class="absolute top-1/3 left-1/4 w-64 h-64 bg-white/5 rounded-full blur-3xl" />

      <div class="relative z-10 flex items-center gap-3">
        <div class="w-10 h-10 bg-white/15 backdrop-blur rounded-xl flex items-center justify-center ring-1 ring-white/20">
          <UIcon name="i-heroicons-users" class="w-5.5 h-5.5 text-white" />
        </div>
        <span class="text-lg font-semibold tracking-tight text-white">Chấm Công</span>
      </div>

      <div class="relative z-10 max-w-md">
        <h1 class="text-3xl font-bold leading-tight mb-4 text-white">
          Quản lý chấm công &amp; nhân sự đơn giản, chính xác
        </h1>
        <p class="text-white/75 text-base leading-relaxed">
          Theo dõi giờ làm, ca trực, tăng ca và nghỉ phép của toàn bộ nhân viên chỉ trong một nền tảng duy nhất.
        </p>

        <div class="mt-8 flex flex-col gap-3">
          <div v-for="item in highlights" :key="item" class="flex items-center gap-2.5 text-white/90 text-sm">
            <div class="w-5 h-5 rounded-full bg-white/15 flex items-center justify-center shrink-0">
              <UIcon name="i-heroicons-check" class="w-3.5 h-3.5" />
            </div>
            {{ item }}
          </div>
        </div>
      </div>

      <p class="relative z-10 text-sm text-white/50">&copy; {{ new Date().getFullYear() }} Hệ thống chấm công.</p>
    </div>

    <!-- Right form panel -->
    <div class="flex-1 flex items-center justify-center px-4 py-12 sm:px-6 lg:px-16 bg-canvas">
      <div class="w-full max-w-sm">
        <!-- Logo shown only when the branding panel is hidden -->
        <div class="flex lg:hidden flex-col items-center gap-3 mb-8">
          <div class="w-11 h-11 bg-accent rounded-xl shadow-[0_8px_20px_rgba(47,95,246,0.28)] flex items-center justify-center">
            <UIcon name="i-heroicons-users" class="w-5.5 h-5.5 text-white" />
          </div>
          <span class="text-xl font-semibold text-ink tracking-tight">Chấm Công</span>
        </div>

        <slot />

        <p class="lg:hidden text-center text-xs text-faint mt-6">
          &copy; {{ new Date().getFullYear() }} Hệ thống chấm công.
        </p>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
const highlights = [
  'Chấm công theo vị trí GPS',
  'Duyệt tăng ca & nghỉ phép nhanh chóng',
  'Báo cáo bảng công tự động hàng tháng',
]
</script>

<style scoped>
.brand-panel {
  background:
    radial-gradient(600px 400px at 15% 15%, rgba(255, 255, 255, 0.08) 0%, transparent 60%),
    linear-gradient(135deg, var(--color-accent) 0%, #6d28d9 100%);
}
</style>
