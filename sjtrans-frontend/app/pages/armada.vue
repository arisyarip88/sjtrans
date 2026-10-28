<template>
  <div class="pb-16 md:pb-12 bg-slate-50 min-h-screen">
    <!-- Breadcrumb & Header Banner -->
    <div
      class="hero-gradient py-14 px-4 relative overflow-hidden shadow-lg border-b border-blue-900/40"
      style="background: linear-gradient(135deg, #070F26 0%, #0D1E4A 50%, #1E3A8A 100%); color: #FFFFFF;"
    >
      <div class="absolute inset-0 opacity-15 bg-[radial-gradient(#38bdf8_1px,transparent_1px)] [background-size:20px_20px] pointer-events-none"></div>
      <div class="container-custom relative z-10">
        <div class="flex items-center gap-2 text-xs font-medium text-sky-300 mb-3">
          <NuxtLink to="/" class="hover:text-white transition">Beranda</NuxtLink>
          <span class="text-slate-400">/</span>
          <span class="text-white font-bold">{{ t('nav.fleets') }}</span>
        </div>

        <div class="max-w-3xl">
          <div class="inline-flex items-center gap-2 bg-blue-500/25 border border-sky-400/40 text-sky-200 text-xs font-bold uppercase tracking-wider px-3.5 py-1 rounded-full mb-3 shadow-xs">
            <span class="w-2 h-2 rounded-full bg-sky-400 animate-pulse"></span>
            <span>Armada Bus Pariwisata Berkelas</span>
          </div>
          <h1 class="text-3xl sm:text-4xl md:text-5xl font-black text-white tracking-tight leading-tight drop-shadow-sm">
            {{ t('fleet.title') }}
          </h1>
          <p class="text-sm sm:text-base text-slate-200 mt-3 leading-relaxed">
            {{ t('fleet.subtitle') }} Seluruh bus menjalani uji kelayakan berkala (KIR), asuransi Jasa Raharja resmi, dan sanitasi menyeluruh sebelum keberangkatan.
          </p>
        </div>
      </div>
    </div>

    <!-- Main Content Container -->
    <div class="container-custom py-8">
      <!-- Filter Categories -->
      <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
        <div class="flex items-center gap-2 overflow-x-auto pb-2 sm:pb-0 w-full sm:w-auto">
          <button
            v-for="cat in categories"
            :key="cat.id"
            @click="activeCategory = cat.id"
            class="px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap cursor-pointer shadow-xs"
            :class="activeCategory === cat.id
              ? 'text-white shadow-md'
              : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200'"
            :style="activeCategory === cat.id ? { backgroundColor: primaryColor } : {}"
          >
            {{ cat.name }}
          </button>
        </div>

        <span class="text-xs text-slate-500 font-medium">
          Menampilkan <strong class="text-slate-800">{{ filteredFleets.length }}</strong> varian armada pilihan
        </span>
      </div>

      <!-- Fleets Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
        <div
          v-for="bus in filteredFleets"
          :key="bus.id"
          class="bg-white rounded-3xl border border-slate-200 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col group"
        >
          <!-- Fleet Image & Badges -->
          <div class="relative h-56 sm:h-64 overflow-hidden bg-slate-100">
            <img
              :src="bus.image_url"
              :alt="bus.name"
              class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
            />
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/20 to-transparent"></div>

            <!-- Category & Seat Badges -->
            <div class="absolute top-4 left-4 flex gap-2">
              <span class="bg-black/60 backdrop-blur-md text-white text-[11px] font-bold px-2.5 py-1 rounded-full border border-white/20">
                {{ bus.type_label }}
              </span>
              <span class="bg-emerald-600/90 text-white text-[11px] font-bold px-2.5 py-1 rounded-full">
                {{ bus.seats }} {{ t('fleet.seats') }}
              </span>
            </div>

            <!-- Engine Specs Badge -->
            <div class="absolute bottom-4 left-4 right-4">
              <span class="text-[11px] text-blue-200 font-semibold uppercase tracking-wider block">
                Chassis & Karoseri
              </span>
              <h3 class="text-lg font-black text-white leading-tight">
                {{ bus.name }}
              </h3>
            </div>
          </div>

          <!-- Card Content Body -->
          <div class="p-5 sm:p-6 flex-1 flex flex-col justify-between">
            <div>
              <p class="text-xs text-slate-600 leading-relaxed line-clamp-2 mb-4">
                {{ bus.description }}
              </p>

              <!-- Facilities Pill Badges -->
              <div class="mb-5">
                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block mb-2">
                  {{ t('fleet.specs') }}
                </span>
                <div class="flex flex-wrap gap-1.5">
                  <span
                    v-for="(fac, fIdx) in bus.facilities.slice(0, 5)"
                    :key="fIdx"
                    class="bg-slate-100 text-slate-700 text-[10px] font-semibold px-2.5 py-1 rounded-lg border border-slate-200"
                  >
                    ✓ {{ fac }}
                  </span>
                  <span v-if="bus.facilities.length > 5" class="bg-blue-50 text-blue-700 text-[10px] font-bold px-2 py-1 rounded-lg">
                    +{{ bus.facilities.length - 5 }} lainnya
                  </span>
                </div>
              </div>
            </div>

            <!-- Price & Action Buttons -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-between gap-3">
              <div>
                <span class="text-[10px] text-slate-400 font-semibold block uppercase">
                  {{ t('fleet.starting_from') }}
                </span>
                <div class="flex items-baseline gap-1">
                  <span class="text-lg sm:text-xl font-black text-slate-900" :style="{ color: primaryColor }">
                    Rp {{ (bus.price_per_day / 1000).toLocaleString() }}K
                  </span>
                  <span class="text-[11px] text-slate-500 font-medium">{{ t('fleet.per_day') }}</span>
                </div>
              </div>

              <div class="flex items-center gap-2">
                <button
                  @click="openDetailModal(bus)"
                  class="btn-outline text-xs py-2 px-3 rounded-xl font-bold cursor-pointer transition"
                >
                  Detail
                </button>
                <button
                  @click="handleSelectBus(bus)"
                  class="btn-primary text-xs py-2 px-4 rounded-xl font-bold cursor-pointer shadow-md transition"
                  :style="{ backgroundColor: primaryColor }"
                >
                  {{ t('book_now') }}
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Detail Spesifikasi Armada Modal -->
    <div
      v-if="selectedBus"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm animate-fade"
    >
      <div class="bg-white rounded-3xl max-w-2xl w-full max-h-[90vh] overflow-y-auto shadow-2xl border border-slate-200 p-6 sm:p-8 relative">
        <button
          @click="selectedBus = null"
          class="absolute top-4 right-4 w-9 h-9 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold flex items-center justify-center transition cursor-pointer"
        >
          ✕
        </button>

        <div class="flex items-center gap-2 mb-2">
          <span class="bg-blue-100 text-blue-800 text-[10px] font-black uppercase px-2 py-0.5 rounded">
            {{ selectedBus.type_label }}
          </span>
          <span class="text-xs font-bold text-emerald-600">
            {{ selectedBus.seats }} Kursi Penumpang
          </span>
        </div>

        <h2 class="text-2xl font-black text-slate-900 mb-4">
          {{ selectedBus.name }}
        </h2>

        <div class="h-64 sm:h-72 rounded-2xl overflow-hidden mb-6 bg-slate-100">
          <img :src="selectedBus.image_url" :alt="selectedBus.name" class="w-full h-full object-cover" />
        </div>

        <div class="space-y-4 text-xs text-slate-700 mb-6">
          <p class="leading-relaxed text-sm">
            {{ selectedBus.description }}
          </p>

          <div class="bg-slate-50 rounded-2xl p-4 border border-slate-200">
            <h4 class="font-bold text-slate-900 mb-2 uppercase text-[11px] tracking-wider">
              Daftar Fasilitas Lengkap:
            </h4>
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
              <div
                v-for="(fac, idx) in selectedBus.facilities"
                :key="idx"
                class="flex items-center gap-1.5 text-slate-700"
              >
                <span class="text-emerald-500 font-bold">✓</span>
                <span>{{ fac }}</span>
              </div>
            </div>
          </div>

          <div class="grid grid-cols-2 gap-3">
            <div class="bg-blue-50/60 p-3 rounded-xl border border-blue-100">
              <span class="text-[10px] text-blue-600 font-bold block uppercase">Standar Keamanan</span>
              <span class="text-xs font-semibold text-slate-800">Seatbelt di semua kursi, APAR, Palu Darurat, P3K</span>
            </div>
            <div class="bg-blue-50/60 p-3 rounded-xl border border-blue-100">
              <span class="text-[10px] text-blue-600 font-bold block uppercase">Kenyamanan & Hiburan</span>
              <span class="text-xs font-semibold text-slate-800">Android TV, Karaoke, Port USB Fast Charging</span>
            </div>
          </div>
        </div>

        <div class="flex items-center justify-between pt-4 border-t border-slate-100">
          <div>
            <span class="text-[10px] text-slate-400 font-semibold block uppercase">Tarif Sewa Harian</span>
            <span class="text-2xl font-black text-slate-900" :style="{ color: primaryColor }">
              Rp {{ selectedBus.price_per_day.toLocaleString() }}
            </span>
          </div>

          <button
            @click="handleSelectBus(selectedBus); selectedBus = null"
            class="btn-primary py-3 px-6 rounded-xl font-bold cursor-pointer shadow-lg"
            :style="{ backgroundColor: primaryColor }"
          >
            Sewa Bus Ini Sekarang
          </button>
        </div>
      </div>
    </div>

    <!-- Booking Modal -->
    <BookingModal
      :is-open="showBookingModal"
      :initial-data="bookingData"
      @close="showBookingModal = false"
    />
  </div>
</template>

<script setup lang="ts">
const { primaryColor } = useTheme()
const { t } = useI18n()

const activeCategory = ref('all')
const selectedBus = ref<any>(null)
const showBookingModal = ref(false)
const bookingData = ref<any>(null)

const categories = [
  { id: 'all', name: 'Semua Armada' },
  { id: 'big_bus', name: 'Big Bus (50-59 Seat)' },
  { id: 'medium_bus', name: 'Medium Bus (31 Seat)' },
  { id: 'hiace', name: 'HiAce VIP (14 Seat)' },
]

const fleets = [
  {
    id: 1,
    name: 'Big Bus SHD Scania K410IB Jetbus 5',
    category: 'big_bus',
    type_label: 'Big Bus SHD Executive',
    seats: 50,
    price_per_day: 4200000,
    description: 'Armada flagship bertenaga mesin Scania Opticruise dengan suspensi udara lembut dan desain interior Jetbus 5 termewah.',
    image_url: 'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?auto=format&fit=crop&w=800&q=80',
    facilities: ['Full AC Ducting Dingin', 'Reclining Seat 2-2', 'Smart TV LED 43 Inch', 'Audio Karaoke Wireless', 'Port USB Setiap Kursi', 'Toilet Eksekutif', 'Coolbox Minuman', 'Bagasi Luas Tembus'],
  },
  {
    id: 2,
    name: 'Big Bus Mercedes-Benz OH 1626 Pariwisata',
    category: 'big_bus',
    type_label: 'Big Bus Pariwisata',
    seats: 59,
    price_per_day: 3800000,
    description: 'Pilihan terfavorit untuk outing kantor, gathering instansi dan rombongan keluarga besar dengan kapasitas maksimal 59 penumpang.',
    image_url: 'https://images.unsplash.com/photo-1570125909232-eb263c188f7e?auto=format&fit=crop&w=800&q=80',
    facilities: ['Full AC Ducting', 'Konfigurasi Kursi 2-3', '2 Unit LED TV', 'Audio Sound System', 'Port USB Charger', 'Lampu Baca LED', 'Kotak P3K & APAR'],
  },
  {
    id: 3,
    name: 'Medium Bus Jetbus 3+ MD Executive',
    category: 'medium_bus',
    type_label: 'Medium Bus Executive',
    seats: 31,
    price_per_day: 2500000,
    description: 'Lincah menjangkau destinasi wisata dengan jalanan berliku seperti Puncak, Lembang, dan Dieng namun tetap lapang dan nyaman.',
    image_url: 'https://images.unsplash.com/photo-1494515843206-f3117d3f51b7?auto=format&fit=crop&w=800&q=80',
    facilities: ['Full AC Dingin Merata', 'Reclining Seat 2-2', 'TV LED 32 Inch', 'Audio Karaoke', 'Port Charger HP', 'Mic Pemandu Wisata', 'Bagasi Samping & Belakang'],
  },
  {
    id: 4,
    name: 'Toyota HiAce Premio Luxury VIP',
    category: 'hiace',
    type_label: 'Luxury VIP Van',
    seats: 14,
    price_per_day: 1500000,
    description: 'Kendaraan van eksekutif privat dengan kursi captain seat empuk, privasi maksimal, sangat cocok untuk rombongan VIP dan delegasi.',
    image_url: 'https://images.unsplash.com/photo-1568605117036-5fe5e7bab0b7?auto=format&fit=crop&w=800&q=80',
    facilities: ['Captain Seat Ergonomis', 'Double Blower AC', 'Audio Bluetooth', 'Port Type-C & USB Fast Charging', 'Kaca Film Privasi V-Kool', 'Cup Holder'],
  },
]

const filteredFleets = computed(() => {
  if (activeCategory.value === 'all') return fleets
  return fleets.filter(f => f.category === activeCategory.value)
})

const openDetailModal = (bus: any) => {
  selectedBus.value = bus
}

const handleSelectBus = (bus: any) => {
  bookingData.value = {
    fleet: bus,
    origin: 'Jakarta',
    destination: 'Bandung & Sekitarnya',
    startDate: new Date().toISOString().split('T')[0],
    endDate: new Date(Date.now() + 3 * 86400000).toISOString().split('T')[0],
    totalDays: 3,
    totalUnits: 1,
    subtotal: (bus.price_per_day || 3800000) * 3,
    discountCode: null,
    discountAmount: 0,
    totalPrice: (bus.price_per_day || 3800000) * 3,
  }
  showBookingModal.value = true
}
</script>
