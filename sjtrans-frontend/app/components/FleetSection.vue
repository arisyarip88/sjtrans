<template>
  <section id="armada" class="py-16 lg:py-24 bg-slate-50">
    <div class="container-custom">
      <!-- Section Header -->
      <div class="text-center max-w-3xl mx-auto space-y-3 mb-12">
        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-blue-100 text-blue-800">
          Katalog Armada SJ Trans
        </div>
        <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight">
          Pilihan Bus Pariwisata Terbaik & Terlengkap
        </h2>
        <p class="text-slate-600 text-sm sm:text-base">
          Seluruh unit selalu dalam kondisi higienis dan terawat dengan jadwal servis berkala di bengkel resmi serta pengemudi berpengalaman.
        </p>

        <!-- Category Filter Tabs -->
        <div class="flex flex-wrap justify-center gap-2 pt-4">
          <button
            v-for="cat in categories"
            :key="cat.id"
            @click="activeCategory = cat.id"
            class="px-5 py-2 rounded-full text-xs font-bold transition-all cursor-pointer"
            :class="activeCategory === cat.id
              ? 'text-white shadow-md'
              : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-100'"
            :style="activeCategory === cat.id ? { backgroundColor: primaryColor } : {}"
          >
            {{ cat.name }}
          </button>
        </div>
      </div>

      <!-- Fleet Grid Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <div
          v-for="fleet in filteredFleets"
          :key="fleet.id"
          class="card-elevated bg-white rounded-3xl overflow-hidden flex flex-col group border border-slate-200/80"
        >
          <!-- Image with Badge Overlay -->
          <div class="relative h-56 sm:h-64 overflow-hidden bg-slate-900">
            <img
              :src="fleet.thumbnail_url"
              :alt="fleet.name"
              class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
            />
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-transparent to-transparent"></div>

            <div class="absolute top-4 left-4 flex gap-2">
              <span class="bg-blue-600 text-white font-black text-xs px-3 py-1 rounded-full shadow-md">
                {{ fleet.capacity }} Kursi
              </span>
              <span class="bg-slate-900/80 backdrop-blur-md text-white font-semibold text-xs px-2.5 py-1 rounded-full border border-white/20">
                Seat {{ fleet.seat_layout }}
              </span>
            </div>

            <div class="absolute bottom-3 left-4 right-4 flex justify-between items-end text-white">
              <div>
                <span class="text-[11px] text-slate-300 block">Tarif Mulai Dari</span>
                <span class="text-xl font-extrabold text-white">Rp {{ formatPrice(fleet.price_per_day) }}</span>
                <span class="text-xs text-slate-300"> / hari</span>
              </div>
            </div>
          </div>

          <!-- Body Info -->
          <div class="p-6 flex-1 flex flex-col justify-between space-y-4">
            <div>
              <h3 class="text-lg font-bold text-slate-900 group-hover:text-blue-700 transition">
                {{ fleet.name }}
              </h3>
              <p class="text-xs text-slate-500 mt-1 line-clamp-2 leading-relaxed">
                {{ fleet.description }}
              </p>

              <!-- Facilities Pills -->
              <div class="mt-4 flex flex-wrap gap-1.5">
                <span
                  v-for="(fac, idx) in (fleet.facilities || []).slice(0, 4)"
                  :key="idx"
                  class="badge-pill bg-slate-100 text-slate-700 text-[11px]"
                >
                  ✓ {{ fac }}
                </span>
                <span v-if="(fleet.facilities || []).length > 4" class="badge-pill bg-blue-50 text-blue-700 text-[10px]">
                  +{{ (fleet.facilities || []).length - 4 }} Fasilitas Lainnya
                </span>
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="pt-4 border-t border-slate-100 flex items-center gap-3">
              <button
                @click="openFleetDetail(fleet)"
                class="flex-1 py-2.5 px-3 rounded-xl border border-slate-200 hover:border-blue-600 text-slate-700 hover:text-blue-700 font-bold text-xs transition cursor-pointer text-center"
              >
                Detail Spesifikasi
              </button>
              <button
                @click="selectFleetForBooking(fleet)"
                class="btn-primary py-2.5 px-4 rounded-xl text-xs font-bold cursor-pointer"
                :style="{ backgroundColor: primaryColor }"
              >
                Pesan Unit
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Detail Armada -->
    <div v-if="selectedDetailFleet" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-sm">
      <div class="bg-white rounded-3xl max-w-2xl w-full max-h-[90vh] overflow-y-auto p-6 sm:p-8 shadow-2xl relative">
        <button
          @click="selectedDetailFleet = null"
          class="absolute top-5 right-5 w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center font-bold text-sm cursor-pointer"
        >
          ✕
        </button>

        <div class="space-y-5">
          <div class="relative h-64 rounded-2xl overflow-hidden bg-slate-900">
            <img :src="selectedDetailFleet.thumbnail_url" class="w-full h-full object-cover" />
            <div class="absolute bottom-3 left-4 bg-blue-900/90 text-white text-xs px-3 py-1 rounded-full font-bold">
              Kapasitas: {{ selectedDetailFleet.capacity }} Kursi (Layout {{ selectedDetailFleet.seat_layout }})
            </div>
          </div>

          <div>
            <h3 class="text-2xl font-black text-slate-900">{{ selectedDetailFleet.name }}</h3>
            <div class="text-blue-700 font-extrabold text-xl mt-1">
              Rp {{ formatPrice(selectedDetailFleet.price_per_day) }} <span class="text-xs text-slate-400 font-normal">/ hari sewa</span>
            </div>
            <p class="text-xs text-slate-600 mt-2 leading-relaxed">{{ selectedDetailFleet.description }}</p>
          </div>

          <!-- Fasilitas Lengkap -->
          <div>
            <h4 class="font-bold text-xs uppercase tracking-wider text-slate-400 mb-2">Fasilitas On-Board</h4>
            <div class="grid grid-cols-2 gap-2 text-xs">
              <div
                v-for="(fac, idx) in selectedDetailFleet.facilities"
                :key="idx"
                class="flex items-center gap-2 p-2 rounded-xl bg-slate-50 border border-slate-100 text-slate-700 font-medium"
              >
                <span class="text-blue-600 font-bold">✓</span>
                <span>{{ fac }}</span>
              </div>
            </div>
          </div>

          <!-- Spesifikasi Mesin/Sasis -->
          <div v-if="selectedDetailFleet.specifications">
            <h4 class="font-bold text-xs uppercase tracking-wider text-slate-400 mb-2">Spesifikasi Kendaraan</h4>
            <div class="bg-slate-50 rounded-2xl p-4 border border-slate-100 text-xs space-y-1.5">
              <div v-for="(val, key) in selectedDetailFleet.specifications" :key="key" class="flex justify-between">
                <span class="text-slate-500 font-medium">{{ key }}:</span>
                <span class="text-slate-900 font-bold">{{ val }}</span>
              </div>
            </div>
          </div>

          <div class="pt-4 border-t border-slate-100 flex gap-3">
            <button
              @click="selectedDetailFleet = null"
              class="flex-1 py-3 rounded-xl border border-slate-200 font-bold text-xs text-slate-600 cursor-pointer"
            >
              Tutup
            </button>
            <button
              @click="selectFleetForBooking(selectedDetailFleet); selectedDetailFleet = null"
              class="btn-primary flex-1 py-3 rounded-xl text-xs font-bold justify-center cursor-pointer"
              :style="{ backgroundColor: primaryColor }"
            >
              Pesan Sekarang
            </button>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
const { primaryColor } = useTheme()
const { fallbackFleets } = useApi()

const emit = defineEmits(['select-fleet'])

const fleets = ref(fallbackFleets)
const activeCategory = ref('all')
const selectedDetailFleet = ref<any>(null)

const categories = [
  { id: 'all', name: 'Semua Armada' },
  { id: 'big-bus', name: 'Big Bus (50-59 Seat)' },
  { id: 'medium-bus', name: 'Medium Bus (31 Seat)' },
  { id: 'microbus-van', name: 'HiAce VIP (14 Seat)' },
]

const filteredFleets = computed(() => {
  if (activeCategory.value === 'all') return fleets.value
  if (activeCategory.value === 'big-bus') return fleets.value.filter(f => f.capacity >= 45)
  if (activeCategory.value === 'medium-bus') return fleets.value.filter(f => f.capacity >= 25 && f.capacity < 45)
  if (activeCategory.value === 'microbus-van') return fleets.value.filter(f => f.capacity < 25)
  return fleets.value
})

const formatPrice = (val: number) => {
  return new Intl.NumberFormat('id-ID').format(val || 0)
}

const openFleetDetail = (fleet: any) => {
  selectedDetailFleet.value = fleet
}

const selectFleetForBooking = (fleet: any) => {
  emit('select-fleet', fleet)
}
</script>
