<template>
  <section id="beranda" class="relative overflow-hidden pt-8 pb-16 lg:py-20 bg-gradient-to-br from-slate-950 via-blue-950 to-blue-900 text-white">
    <!-- Background bus graphic overlay -->
    <div class="absolute inset-0 opacity-15 bg-[radial-gradient(#38bdf8_1px,transparent_1px)] [background-size:24px_24px]"></div>
    
    <div class="container-custom relative z-10">
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
        <!-- Left Hero Copy -->
        <div class="lg:col-span-6 space-y-6">
          <div class="inline-flex items-center gap-2 bg-blue-500/20 border border-blue-400/30 px-3.5 py-1.5 rounded-full text-xs font-semibold text-blue-200">
            <span class="w-2 h-2 rounded-full bg-blue-400 animate-ping"></span>
            <span>Standar Layanan Bus Pariwisata Eksekutif</span>
          </div>

          <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold leading-[1.15] tracking-tight">
            Sewa Bus Pariwisata <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-300 via-sky-200 to-white">Mulai Rp 1 Jutaan</span> di SJ Trans
          </h1>

          <p class="text-slate-300 text-sm sm:text-base leading-relaxed max-w-xl">
            Transportasi perjalanan paling nyaman & berkelas. Pilihan armada Toyota HiAce, Medium Bus Jetbus 3+, hingga Big Bus Scania SHD kapasitas 14 hingga 59 penumpang dengan proteksi asuransi resmi.
          </p>

          <!-- 4 Trust Badges -->
          <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 pt-2">
            <div class="bg-white/10 backdrop-blur-md rounded-xl p-3 border border-white/10 text-center">
              <div class="text-lg font-black text-sky-300">100%</div>
              <div class="text-[11px] text-slate-300 font-medium">Armada Prima</div>
            </div>
            <div class="bg-white/10 backdrop-blur-md rounded-xl p-3 border border-white/10 text-center">
              <div class="text-lg font-black text-sky-300">Air Susp.</div>
              <div class="text-[11px] text-slate-300 font-medium">Bebas Mabuk</div>
            </div>
            <div class="bg-white/10 backdrop-blur-md rounded-xl p-3 border border-white/10 text-center">
              <div class="text-lg font-black text-sky-300">Jasa Raharja</div>
              <div class="text-[11px] text-slate-300 font-medium">Asuransi Resmi</div>
            </div>
            <div class="bg-white/10 backdrop-blur-md rounded-xl p-3 border border-white/10 text-center">
              <div class="text-lg font-black text-sky-300">24 Jam</div>
              <div class="text-[11px] text-slate-300 font-medium">Layanan Darurat</div>
            </div>
          </div>
        </div>

        <!-- Right Hero: Interactive Quick Booking & Calculator Widget -->
        <div class="lg:col-span-6">
          <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-2xl text-slate-800 border border-slate-100 relative">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
              <div>
                <h2 class="text-lg sm:text-xl font-bold text-slate-900">Hitung & Pesan Bus Cepat</h2>
                <p class="text-xs text-slate-500">Estimasi tarif transparan tanpa biaya tersembunyi</p>
              </div>
              <span class="bg-blue-50 text-blue-700 text-xs font-bold px-3 py-1 rounded-full border border-blue-100">Instan</span>
            </div>

            <!-- Booking Form -->
            <div class="mt-5 space-y-4 text-xs">
              <!-- Asal & Tujuan -->
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                  <label class="block font-semibold text-slate-700 mb-1">Kota Penjemputan</label>
                  <select v-model="form.origin" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs text-slate-800 font-medium focus:ring-2 focus:ring-blue-600 focus:outline-none">
                    <option value="Jakarta">Jakarta (Jabodetabek)</option>
                    <option value="Bandung">Bandung & Sekitarnya</option>
                    <option value="Bogor">Bogor / Sentul</option>
                    <option value="Yogyakarta">Yogyakarta</option>
                    <option value="Surabaya">Surabaya</option>
                  </select>
                </div>
                <div>
                  <label class="block font-semibold text-slate-700 mb-1">Kota Tujuan Wisata</label>
                  <input
                    v-model="form.destination"
                    type="text"
                    placeholder="Contoh: Bandung, Jogja, Bali, dll."
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs text-slate-800 font-medium focus:ring-2 focus:ring-blue-600 focus:outline-none"
                  />
                </div>
              </div>

              <!-- Tanggal Berangkat & Selesai -->
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                  <label class="block font-semibold text-slate-700 mb-1">Tanggal Berangkat</label>
                  <input
                    v-model="form.startDate"
                    type="date"
                    :min="todayString"
                    @change="calculateDuration"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs text-slate-800 font-medium focus:ring-2 focus:ring-blue-600 focus:outline-none"
                  />
                </div>
                <div>
                  <label class="block font-semibold text-slate-700 mb-1">Tanggal Selesai</label>
                  <input
                    v-model="form.endDate"
                    type="date"
                    :min="form.startDate || todayString"
                    @change="calculateDuration"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs text-slate-800 font-medium focus:ring-2 focus:ring-blue-600 focus:outline-none"
                  />
                </div>
              </div>

              <!-- Pilihan Armada & Jumlah Penumpang -->
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                  <label class="block font-semibold text-slate-700 mb-1">Pilihan Armada Bus</label>
                  <select v-model="selectedFleetIndex" @change="recalculatePrice" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs text-slate-800 font-medium focus:ring-2 focus:ring-blue-600 focus:outline-none">
                    <option v-for="(fleet, idx) in fleets" :key="fleet.id" :value="idx">
                      {{ fleet.name }} ({{ fleet.capacity }} Seat) - Rp {{ formatPrice(fleet.price_per_day) }}/hari
                    </option>
                  </select>
                </div>
                <div>
                  <label class="block font-semibold text-slate-700 mb-1">Jumlah Unit Bus</label>
                  <div class="flex items-center gap-2">
                    <button @click="changeUnits(-1)" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 font-bold text-slate-700 text-sm">-</button>
                    <span class="font-bold text-sm text-slate-900 w-8 text-center">{{ form.units }}</span>
                    <button @click="changeUnits(1)" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 font-bold text-slate-700 text-sm">+</button>
                    <span class="text-[11px] text-slate-400 ml-1">({{ durationDays }} Hari)</span>
                  </div>
                </div>
              </div>

              <!-- KOLOM DISKON & KUPON PROMO -->
              <div class="bg-blue-50/70 border border-blue-100 rounded-2xl p-3.5 space-y-2">
                <div class="flex items-center justify-between">
                  <label class="block font-bold text-blue-900 text-xs">Punya Kode Promo / Kupon Diskon?</label>
                  <span class="text-[10px] text-blue-600 cursor-pointer hover:underline" @click="applyDemoCode">Gunakan Demo: SJTPROMO10</span>
                </div>
                <div class="flex gap-2">
                  <input
                    v-model="discountCodeInput"
                    type="text"
                    placeholder="Masukkan kode (contoh: SJTPROMO10)"
                    class="flex-1 uppercase font-mono bg-white border border-blue-200 rounded-xl px-3 py-2 text-xs font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500"
                  />
                  <button
                    @click="applyDiscount"
                    class="bg-blue-800 hover:bg-blue-900 text-white font-bold px-4 py-2 rounded-xl text-xs transition cursor-pointer"
                  >
                    Terapkan
                  </button>
                </div>

                <div v-if="discountMessage" class="text-[11px] font-semibold" :class="discountValid ? 'text-emerald-700' : 'text-red-600'">
                  {{ discountMessage }}
                </div>
              </div>

              <!-- Kalkulator Rincian Biaya -->
              <div class="pt-3 border-t border-slate-100 space-y-1.5">
                <div class="flex justify-between text-slate-500">
                  <span>Subtotal Sewa ({{ durationDays }} hari x {{ form.units }} unit):</span>
                  <span class="font-semibold text-slate-800">Rp {{ formatPrice(subtotal) }}</span>
                </div>
                <div v-if="discountAmount > 0" class="flex justify-between text-emerald-600 font-semibold">
                  <span>Potongan Promo ({{ activeDiscountCode }}):</span>
                  <span>- Rp {{ formatPrice(discountAmount) }}</span>
                </div>
                <div class="flex justify-between items-baseline pt-2 border-t border-slate-200">
                  <span class="text-sm font-bold text-slate-900">Total Biaya Sewa:</span>
                  <span class="text-xl sm:text-2xl font-black text-blue-800">Rp {{ formatPrice(totalPrice) }}</span>
                </div>
              </div>

              <!-- Submit Button -->
              <button
                @click="submitBookingForm"
                class="btn-primary w-full py-3.5 text-sm font-bold tracking-wide shadow-lg justify-center cursor-pointer mt-2"
                :style="{ backgroundColor: primaryColor }"
              >
                <span>Lanjut Pengisian Data & Reservasi</span>
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
const { primaryColor } = useTheme()
const { fallbackFleets } = useApi()

const emit = defineEmits(['start-booking'])

const fleets = ref(fallbackFleets)
const selectedFleetIndex = ref(0)

const today = new Date()
const todayString = today.toISOString().split('T')[0]

const nextWeek = new Date()
nextWeek.setDate(today.getDate() + 3)
const nextWeekString = nextWeek.toISOString().split('T')[0]

const form = reactive({
  origin: 'Jakarta',
  destination: 'Bandung & Lembang',
  startDate: todayString,
  endDate: nextWeekString,
  units: 1,
})

const durationDays = ref(3)
const discountCodeInput = ref('')
const activeDiscountCode = ref('')
const discountAmount = ref(0)
const discountValid = ref(false)
const discountMessage = ref('')

const calculateDuration = () => {
  if (form.startDate && form.endDate) {
    const start = new Date(form.startDate)
    const end = new Date(form.endDate)
    const diffTime = Math.abs(end.getTime() - start.getTime())
    const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1
    durationDays.value = Math.max(1, diffDays)
  }
}

const changeUnits = (delta: number) => {
  form.units = Math.max(1, form.units + delta)
}

const subtotal = computed(() => {
  const fleet = fleets.value[selectedFleetIndex.value]
  if (!fleet) return 0
  return (fleet.price_per_day || 3800000) * durationDays.value * form.units
})

const totalPrice = computed(() => {
  return Math.max(0, subtotal.value - discountAmount.value)
})

const recalculatePrice = () => {
  if (activeDiscountCode.value) {
    applyDiscount()
  }
}

const applyDemoCode = () => {
  discountCodeInput.value = 'SJTPROMO10'
  applyDiscount()
}

const applyDiscount = async () => {
  const code = discountCodeInput.value.trim().toUpperCase()
  if (!code) {
    discountMessage.value = 'Masukkan kode promo terlebih dahulu.'
    discountValid.value = false
    return
  }

  try {
    const res: any = await $fetch('http://127.0.0.1:8000/api/v1/discounts/validate', {
      method: 'POST',
      body: { code, subtotal: subtotal.value },
    })

    if (res.valid && res.discount) {
      activeDiscountCode.value = res.discount.code
      discountAmount.value = res.discount.discount_amount
      discountValid.value = true
      discountMessage.value = `✓ Kupon ${code} berhasil! Hemat Rp ${formatPrice(res.discount.discount_amount)}`
    }
  } catch (err: any) {
    // Client-side fallback calculation for seamless experience
    if (code === 'SJTPROMO10') {
      const deduction = Math.min(500000, subtotal.value * 0.1)
      activeDiscountCode.value = 'SJTPROMO10'
      discountAmount.value = deduction
      discountValid.value = true
      discountMessage.value = `✓ Kupon SJTPROMO10 aktif! Hemat Rp ${formatPrice(deduction)}`
    } else if (code === 'LIBURANSERU') {
      const deduction = 350000
      activeDiscountCode.value = 'LIBURANSERU'
      discountAmount.value = deduction
      discountValid.value = true
      discountMessage.value = `✓ Kupon LIBURANSERU aktif! Hemat Rp ${formatPrice(deduction)}`
    } else {
      discountValid.value = false
      discountMessage.value = err.data?.message || 'Kode promo tidak valid atau kadaluarsa.'
      discountAmount.value = 0
      activeDiscountCode.value = ''
    }
  }
}

const formatPrice = (val: number) => {
  return new Intl.NumberFormat('id-ID').format(val || 0)
}

const submitBookingForm = () => {
  const chosenFleet = fleets.value[selectedFleetIndex.value]
  emit('start-booking', {
    fleet: chosenFleet,
    origin: form.origin,
    destination: form.destination,
    startDate: form.startDate,
    endDate: form.endDate,
    totalDays: durationDays.value,
    totalUnits: form.units,
    subtotal: subtotal.value,
    discountCode: activeDiscountCode.value,
    discountAmount: discountAmount.value,
    totalPrice: totalPrice.value,
  })
}

onMounted(() => {
  calculateDuration()
})
</script>
