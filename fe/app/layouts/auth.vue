<template>
  <div class="min-h-screen flex flex-col justify-center items-center bg-gray-100 py-12 px-4">
    <div class="mb-8 text-center">
      <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-600 rounded-2xl shadow-lg mb-4">
        <svg class="w-9 h-9 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
        </svg>
      </div>
      <h1 class="text-3xl font-bold text-gray-900">Chấm Công</h1>
      <p class="text-gray-500 text-sm mt-1">Hệ thống quản lý chấm công</p>
    </div>

    <div class="w-full max-w-md bg-white rounded-2xl shadow-xl p-8">
      <slot />
    </div>
  </div>
</template>
