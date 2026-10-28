<template>
  <div class="min-h-screen flex flex-col bg-slate-50 text-slate-900 selection:bg-blue-600 selection:text-white">
    <NuxtLayout>
      <NuxtPage />
    </NuxtLayout>
  </div>
</template>

<script setup lang="ts">
const { initTheme } = useTheme()
const { initAuth } = useAuth()
const { initLocale } = useI18n()

onMounted(() => {
  initTheme()
  initAuth()
  initLocale()
})
</script>
