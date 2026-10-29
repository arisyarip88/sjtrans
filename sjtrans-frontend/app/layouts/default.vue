<template>
  <div class="min-h-screen flex flex-col bg-slate-50 text-slate-900 selection:bg-blue-600 selection:text-white">
    <!-- 1. Header Navigasi Publik (Concise + Bilingual + Theme Color) -->
    <HeaderNav
      @open-auth="openAuthModal"
      @scroll-to-booking="handleScrollToBooking"
    />

    <!-- 2. Main Page Content (NuxtPage injected via slot) -->
    <main class="flex-1">
      <slot />
    </main>

    <!-- 3. Footer Lengkap (Kontak, Alamat, Legalitas, Maps, Sosmed, Kemitraan) -->
    <FooterSection />

    <!-- 4. Interactive Floating Live Chat Widget (Integrated with Bantuan & FAQ) -->
    <LiveChatWidget ref="liveChatRef" />

    <!-- 5. Mobile Bottom Navigation Bar -->
    <MobileBottomNav
      @open-chat="openLiveChat"
      @open-auth="openAuthModal"
    />

    <!-- 6. Global Auth Modal -->
    <AuthModal
      :is-open="showAuthModal"
      :default-tab="authModalTab"
      @close="showAuthModal = false"
      @auth-success="handleAuthSuccess"
    />

    <!-- 7. Global Rich UI Notifications (Toasts & Custom Alert Modals) -->
    <AppNotification />
  </div>
</template>

<script setup lang="ts">
const router = useRouter()
const { user, isAdmin } = useAuth()

const showAuthModal = ref(false)
const authModalTab = ref<'login' | 'register'>('login')
const liveChatRef = ref<any>(null)

const openAuthModal = (tab: 'login' | 'register' = 'login') => {
  authModalTab.value = tab
  showAuthModal.value = true
}

const openLiveChat = () => {
  if (liveChatRef.value) {
    liveChatRef.value.isOpen = true
  }
}

const handleAuthSuccess = () => {
  showAuthModal.value = false
  if (isAdmin.value) {
    router.push('/admin')
  } else {
    router.push('/customer')
  }
}

const handleScrollToBooking = () => {
  if (typeof document !== 'undefined') {
    const el = document.getElementById('beranda')
    if (el) {
      el.scrollIntoView({ behavior: 'smooth' })
    }
  }
}
</script>
