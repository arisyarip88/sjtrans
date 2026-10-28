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
            <span>{{ t('hero.badge') }}</span>
          </div>

          <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold leading-[1.15] tracking-tight">
            {{ locale === 'en' ? 'Executive Tour Bus Rental' : 'Sewa Bus Pariwisata' }}
            <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-300 via-sky-200 to-white">
              {{ locale === 'en' ? 'Starting from Rp 1 Million' : 'Mulai Rp 1 Jutaan' }}
            </span>
            {{ locale === 'en' ? 'with SJ Trans' : 'di SJ Trans' }}
          </h1>

          <p class="text-slate-300 text-sm sm:text-base leading-relaxed max-w-xl">
            {{ t('hero.desc') }}
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
              <div class="text-[11px] text-slate-300 font-medium">Layanan Siaga</div>
            </div>
          </div>
        </div>

        <!-- Right Hero: Interactive Quick Booking & Calculator Widget -->
        <div class="lg:col-span-6">
          <div class="bg-white rounded-3xl p-6 sm:p-8 shadow-2xl text-slate-800 border border-slate-100 relative">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
              <div>
                <h2 class="text-lg sm:text-xl font-bold text-slate-900">{{ t('calc.title') }}</h2>
                <p class="text-xs text-slate-500">Estimasi tarif transparan tanpa biaya tersembunyi</p>
              </div>
              <span class="bg-blue-50 text-blue-700 text-xs font-bold px-3 py-1 rounded-full border border-blue-100">Instan</span>
            </div>

            <!-- Booking Form -->
            <div class="mt-5 space-y-4 text-xs">
              <!-- Asal & Tujuan -->
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                  <label class="block font-semibold text-slate-700 mb-1">{{ t('calc.origin') }}</label>
                  <select v-model="form.origin" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600">
                    <option value="Jakarta">Jakarta (Seluruh Wilayah)</option>
                    <option value="Bogor">Bogor & Sekitarnya</option>
                    <option value="Depok">Depok & Sekitarnya</option>
                    <option value="Tangerang">Tangerang & BSD</option>
                    <option value="Bekasi">Bekasi & Cikarang</option>
                  </select>
                </div>
                <div>
                  <label class="block font-semibold text-slate-700 mb-1">{{ t('calc.destination') }}</label>
                  <select v-model="form.destination" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600">
                    <option value="Bandung & Lembang">Bandung & Lembang</option>
                    <option value="Puncak & Bogor">Puncak & Bogor</option>
                    <option value="Yogyakarta & Solo">Yogyakarta & Solo</option>
                    <option value="Semarang & Dieng">Semarang & Dieng</option>
                    <option value="Surabaya & Malang Bromo">Surabaya & Malang Bromo</option>
                    <option value="Bali Tour">Bali Tour</option>
                    <option value="Anyer & Carita">Pantai Anyer & Carita</option>
                  </select>
                </div>
              </div>

              <!-- Tanggal Sewa -->
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                  <label class="block font-semibold text-slate-700 mb-1">{{ t('calc.start_date') }}</label>
                  <input
                    v-model="form.startDate"
                    @change="calculateDuration"
                    type="date"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600"
                  />
                </div>
                <div>
                  <label class="block font-semibold text-slate-700 mb-1">{{ t('calc.end_date') }}</label>
                  <input
                    v-model="form.endDate"
                    @change="calculateDuration"
                    type="date"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600"
                  />
                </div>
              </div>

              <!-- Jenis Armada & Unit -->
              <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div class="sm:col-span-2">
                  <label class="block font-semibold text-slate-700 mb-1">{{ t('calc.fleet_type') }}</label>
                  <select
                    v-model="selectedFleetIndex"
                    @change="recalculatePrice"
                    class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600"
                  >
                    <option
                      v-for="(fl, idx) in fleets"
                      :key="fl.id"
                      :value="idx"
                    >
                      {{ fl.name }} ({{ fl.seats }} Seat) - Rp {{ formatPrice(fl.price_per_day) }}/hr
                    </option>
                  </select>
                </div>
                <div>
                  <label class="block font-semibold text-slate-700 mb-1">{{ t('calc.bus_units') }}</label>
                  <div class="flex items-center border border-slate-200 rounded-xl bg-slate-50 overflow-hidden">
                    <button
                      type="button"
                      @click="changeUnits(-1)"
                      class="px-3 py-2.5 text-slate-600 hover:bg-slate-200 font-bold transition cursor-pointer"
                    >
                      -
                    </button>
                    <span class="flex-1 text-center font-bold text-slate-800">{{ form.units }}</span>
                    <button
                      type="button"
                      @click="changeUnits(1)"
                      class="px-3 py-2.5 text-slate-600 hover:bg-slate-200 font-bold transition cursor-pointer"
                    >
                      +
                    </button>
                  </div>
                </div>
              </div>

              <!-- Input Kode Kupon / Diskon -->
              <div class="pt-2">
                <div class="flex justify-between items-center mb-1">
                  <label class="font-semibold text-slate-700">{{ t('calc.discount_code') }}</label>
                  <button
                    type="button"
                    @click="applyDemoCode"
                    class="text-[11px] text-blue-600 hover:underline cursor-pointer font-medium"
                  >
                    Coba: SJTPROMO10
                  </button>
                </div>
                <div class="flex gap-2">
                  <input
                    v-model="discountCodeInput"
                    type="text"
                    placeholder="Contoh: SJTPROMO10 atau LIBURANSERU"
                    class="flex-1 bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs uppercase text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600"
                  />
                  <button
                    type="button"
                    @click="applyDiscount"
                    class="px-4 py-2 rounded-xl text-xs font-bold text-white transition cursor-pointer shadow-xs"
                    :style="{ backgroundColor: primaryColor }"
                  >
                    {{ t('calc.apply_coupon') }}
                  </button>
                </div>

                <!-- Feedback Diskon -->
                <div v-if="discountMessage" class="mt-1.5 text-[11px] font-medium" :class="discountValid ? 'text-emerald-600' : 'text-red-500'">
                  {{ discountMessage }}
                </div>
              </div>

              <!-- Price Breakdown Box -->
              <div class="bg-blue-50/60 rounded-2xl p-4 border border-blue-100/80 space-y-2 mt-2">
                <div class="flex justify-between text-slate-600">
                  <span>Durasi Sewa:</span>
                  <span class="font-bold text-slate-800">{{ durationDays }} Hari</span>
                </div>
                <div class="flex justify-between text-slate-600">
                  <span>Subtotal ({{ form.units }} Unit x {{ durationDays }} Hari):</span>
                  <span class="font-bold text-slate-800">Rp {{ formatPrice(subtotal) }}</span>
                </div>
                <div v-if="discountAmount > 0" class="flex justify-between text-emerald-600 font-medium">
                  <span>Potongan Kupon Promo ({{ activeDiscountCode }}):</span>
                  <span>- Rp {{ formatPrice(discountAmount) }}</span>
                </div>
                <div class="border-t border-blue-200/60 pt-2 flex justify-between items-baseline">
                  <span class="font-bold text-slate-900 text-sm">{{ t('calc.est_price') }}</span>
                  <div class="text-right">
                    <span class="text-xl sm:text-2xl font-black text-slate-900" :style="{ color: primaryColor }">
                      Rp {{ formatPrice(totalPrice) }}
                    </span>
                    <span class="block text-[10px] text-slate-400 font-normal">Sudah termasuk supir & armada</span>
                  </div>
                </div>
              </div>

              <!-- Submit Button -->
              <button
                type="button"
                @click="submitBookingForm"
                class="btn-primary w-full py-3.5 rounded-2xl font-bold text-sm text-center justify-center shadow-lg transition-all duration-200 transform hover:-translate-y-0.5 cursor-pointer"
                :style="{ backgroundColor: primaryColor }"
              >
                <span>{{ t('calc.button') }}</span>
                <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
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
const { locale, t } = useI18n()

const emit = defineEmits(['start-booking'])

const selectedFleetIndex = ref(0)
const fleets = ref<any[]>([
  { id: 1, name: 'Big Bus SHD Scania K410IB', seats: 50, price_per_day: 4200000 },
  { id: 2, name: 'Big Bus Mercedes-Benz OH 1626', seats: 59, price_per_day: 3800000 },
  { id: 3, name: 'Medium Bus Jetbus 3+ MD', seats: 31, price_per_day: 2500000 },
  { id: 4, name: 'Toyota HiAce Premio Luxury VIP', seats: 14, price_per_day: 1500000 },
])

const today = new Date()
const nextWeek = new Date(today.getTime() + 3 * 24 * 60 * 60 * 1000)
const todayString = today.toISOString().split('T')[0]
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
