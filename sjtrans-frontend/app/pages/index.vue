<template>
  <div class="pb-16 md:pb-0">
    <!-- Hero Section & Instant Rental Calculator -->
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

    <!-- Blog & Artikel Wisata Terkini (Ditampilkan di Homepage) -->
    <BlogSection />

    <!-- Testimoni Pelanggan -->
    <TestimonialsSection />

    <!-- Kemitraan & Partnership Korporat -->
    <PartnershipSection />

    <!-- Global Booking Modal Triggered from Home Page -->
    <BookingModal
      :is-open="showBookingModal"
      :initial-data="currentBookingData"
      @close="showBookingModal = false"
      @booking-confirmed="handleBookingConfirmed"
    />
  </div>
</template>

<script setup lang="ts">
const heroSectionRef = ref<any>(null)
const showBookingModal = ref(false)
const currentBookingData = ref<any>(null)

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

const handleBookingConfirmed = () => {
  showBookingModal.value = false
}
</script>
