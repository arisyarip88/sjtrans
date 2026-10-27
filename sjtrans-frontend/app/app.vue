<template>
  <div class="min-h-screen flex flex-col bg-slate-50 text-slate-900 selection:bg-blue-600 selection:text-white">
    <!-- 1. Header & Navigasi -->
    <HeaderNav
      @open-auth="openAuthModal"
      @open-admin="showAdminModal = true"
      @open-my-bookings="showMyBookingsModal = true"
      @scroll-to-booking="scrollToBookingSection"
    />

    <!-- 2. Main Content Sections -->
    <main class="flex-1">
      <!-- Hero Section & Instant Rental Calculator dengan Kolom Diskon -->
      <HeroBookingWidget
        ref="heroSectionRef"
        @start-booking="handleStartBooking"
      />

      <!-- Pilihan Armada Bus (HiAce, Medium Bus, Big Bus) -->
      <FleetSection
        @select-fleet="handleFleetSelected"
      />

      <!-- 4 Pilar Keunggulan SJ Trans -->
      <AdvantagesSection />

      <!-- Kupon & Promo Diskon -->
      <PromoSection />

      <!-- Testimoni Pelanggan -->
      <TestimonialsSection />

      <!-- Kemitraan & Partnership Korporat -->
      <PartnershipSection />

      <!-- Bantuan & FAQ Interaktif -->
      <FaqSection />
    </main>

    <!-- 3. Footer Lengkap (Nama, Alamat, No Telpon, Maps Lokasi, Email, Sosmed, Partnership) -->
    <FooterSection />

    <!-- 4. Interactive Floating Live Chat Widget -->
    <LiveChatWidget />

    <!-- 5. Modals & Dialogs -->
    <AuthModal
      :is-open="showAuthModal"
      :default-tab="authModalTab"
      @close="showAuthModal = false"
      @auth-success="handleAuthSuccess"
    />

    <BookingModal
      :is-open="showBookingModal"
      :initial-data="currentBookingData"
      @close="showBookingModal = false"
      @booking-confirmed="handleBookingConfirmed"
      @open-my-bookings="showMyBookingsModal = true"
    />

    <MyBookingsModal
      :is-open="showMyBookingsModal"
      @close="showMyBookingsModal = false"
    />

    <AdminDashboardModal
      :is-open="showAdminModal"
      @close="showAdminModal = false"
    />
  </div>
</template>

<script setup lang="ts">
const { initTheme } = useTheme()
const { initAuth, user, isAdmin } = useAuth()

const showAuthModal = ref(false)
const authModalTab = ref<'login' | 'register'>('login')
const showBookingModal = ref(false)
const showMyBookingsModal = ref(false)
const showAdminModal = ref(false)
const currentBookingData = ref<any>(null)

const openAuthModal = (tab: 'login' | 'register' = 'login') => {
  authModalTab.value = tab
  showAuthModal.value = true
}

const handleAuthSuccess = () => {
  if (isAdmin.value) {
    showAdminModal.value = true
  }
}

const scrollToBookingSection = () => {
  if (typeof document !== 'undefined') {
    const el = document.getElementById('beranda')
    if (el) {
      el.scrollIntoView({ behavior: 'smooth' })
    }
  }
}

const handleStartBooking = (bookingData: any) => {
  currentBookingData.value = bookingData
  showBookingModal.value = true
}

const handleFleetSelected = (fleet: any) => {
  currentBookingData.value = {
    fleet,
    origin: 'Jakarta',
    destination: 'Bandung & Sekitarnya',
    startDate: new Date().toISOString().split('T')[0],
    endDate: new Date(Date.now() + 3 * 86400000).toISOString().split('T')[0],
    totalDays: 3,
    totalUnits: 1,
    subtotal: (fleet.price_per_day || 3800000) * 3,
    discountCode: null,
    discountAmount: 0,
    totalPrice: (fleet.price_per_day || 3800000) * 3,
  }
  showBookingModal.value = true
}

const handleBookingConfirmed = (booking: any) => {
  // Handled inside BookingModal
}

onMounted(() => {
  initTheme()
  initAuth()
})
</script>
