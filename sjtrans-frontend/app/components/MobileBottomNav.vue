<template>
  <nav class="md:hidden fixed bottom-0 left-0 right-0 z-40 bg-white/95 backdrop-blur-md border-t border-slate-200 shadow-lg safe-bottom">
    <div class="grid grid-cols-5 h-16 items-center px-1">
      <!-- 1. Beranda -->
      <NuxtLink
        to="/"
        class="flex flex-col items-center justify-center py-1 transition group"
        :class="route.path === '/' ? 'text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-800'"
      >
        <span class="text-xl">🏠</span>
        <span class="text-[10px] mt-0.5 tracking-tight">{{ t('mobile.home') }}</span>
      </NuxtLink>

      <!-- 2. Armada -->
      <NuxtLink
        to="/armada"
        class="flex flex-col items-center justify-center py-1 transition group relative"
        :class="route.path === '/armada' ? 'text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-800'"
      >
        <span class="text-xl">🚌</span>
        <span class="text-[10px] mt-0.5 tracking-tight">{{ t('mobile.fleets') }}</span>
        <span class="absolute top-1 right-2 w-2 h-2 rounded-full bg-blue-600"></span>
      </NuxtLink>

      <!-- 3. Blog -->
      <NuxtLink
        to="/blog"
        class="flex flex-col items-center justify-center py-1 transition group"
        :class="route.path.startsWith('/blog') ? 'text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-800'"
      >
        <span class="text-xl">📰</span>
        <span class="text-[10px] mt-0.5 tracking-tight">{{ t('mobile.blog') }}</span>
      </NuxtLink>

      <!-- 4. Quick Live Chat / FAQ -->
      <button
        @click="$emit('open-chat')"
        class="flex flex-col items-center justify-center py-1 transition group text-slate-500 hover:text-slate-800 cursor-pointer"
      >
        <span class="text-xl">💬</span>
        <span class="text-[10px] mt-0.5 tracking-tight">{{ t('mobile.chat') }}</span>
      </button>

      <!-- 5. Akun / Portal -->
      <button
        v-if="!isLoggedIn"
        @click="$emit('open-auth')"
        class="flex flex-col items-center justify-center py-1 transition text-slate-500 hover:text-slate-800 cursor-pointer"
      >
        <span class="text-xl">👤</span>
        <span class="text-[10px] mt-0.5 tracking-tight">{{ t('login') }}</span>
      </button>

      <NuxtLink
        v-else-if="isAdmin"
        to="/admin"
        class="flex flex-col items-center justify-center py-1 transition group"
        :class="route.path.startsWith('/admin') ? 'text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-800'"
      >
        <span class="text-xl">⚙️</span>
        <span class="text-[10px] mt-0.5 tracking-tight">Admin</span>
      </NuxtLink>

      <NuxtLink
        v-else
        to="/customer"
        class="flex flex-col items-center justify-center py-1 transition group"
        :class="route.path.startsWith('/customer') ? 'text-blue-600 font-bold' : 'text-slate-500 hover:text-slate-800'"
      >
        <span class="text-xl">🎫</span>
        <span class="text-[10px] mt-0.5 tracking-tight">Pesanan</span>
      </NuxtLink>
    </div>
  </nav>
</template>

<script setup lang="ts">
const route = useRoute()
const { isLoggedIn, isAdmin } = useAuth()
const { t } = useI18n()

defineEmits(['open-chat', 'open-auth'])
</script>

<style scoped>
.safe-bottom {
  padding-bottom: env(safe-area-inset-bottom, 0px);
}
</style>
