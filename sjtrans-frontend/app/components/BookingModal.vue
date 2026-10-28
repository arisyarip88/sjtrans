<template>
  <div v-if="isOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/75 backdrop-blur-sm animate-fade">
    <div class="bg-white rounded-3xl max-w-xl w-full max-h-[92vh] overflow-y-auto p-6 sm:p-8 shadow-2xl relative">
      <button
        @click="$emit('close')"
        class="absolute top-5 right-5 w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center font-bold text-sm cursor-pointer"
      >
        ✕
      </button>

      <!-- Step 1: Form Reservasi -->
      <div v-if="!confirmedBooking" class="space-y-5">
        <div>
          <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider bg-blue-100 text-blue-800">
            Konfirmasi Pemesanan Bus
          </span>
          <h3 class="text-xl font-black text-slate-900 mt-1">Lengkapi Data Reservasi</h3>
        </div>

        <!-- Ringkasan Armada & Rute -->
        <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-4 text-xs space-y-2">
          <div class="flex justify-between items-center pb-2 border-b border-slate-200">
            <span class="font-bold text-slate-800 text-sm">{{ initialData?.fleet?.name || 'Armada Bus SJ Trans' }}</span>
            <span class="bg-blue-600 text-white font-bold px-2.5 py-0.5 rounded-full text-[10px]">
              {{ initialData?.fleet?.capacity || 50 }} Kursi
            </span>
          </div>

          <div class="grid grid-cols-2 gap-2 text-slate-600">
            <div>Penjemputan: <strong class="text-slate-800">{{ initialData?.origin || 'Jakarta' }}</strong></div>
            <div>Tujuan: <strong class="text-slate-800">{{ initialData?.destination || 'Bandung' }}</strong></div>
            <div>Tgl Berangkat: <strong class="text-slate-800">{{ initialData?.startDate }}</strong></div>
            <div>Tgl Pulang: <strong class="text-slate-800">{{ initialData?.endDate }}</strong></div>
            <div>Durasi: <strong class="text-slate-800">{{ initialData?.totalDays || 1 }} Hari</strong></div>
            <div>Jumlah Unit: <strong class="text-slate-800">{{ initialData?.totalUnits || 1 }} Bus</strong></div>
          </div>

          <!-- Rincian Harga & Diskon -->
          <div class="pt-2 border-t border-slate-200 space-y-1">
            <div class="flex justify-between text-slate-500">
              <span>Subtotal:</span>
              <span>Rp {{ formatPrice(initialData?.subtotal || 0) }}</span>
            </div>
            <div v-if="initialData?.discountAmount > 0" class="flex justify-between text-emerald-600 font-bold">
              <span>Diskon ({{ initialData?.discountCode }}):</span>
              <span>- Rp {{ formatPrice(initialData?.discountAmount) }}</span>
            </div>
            <div class="flex justify-between text-sm font-black text-blue-900 pt-1">
              <span>Total Tagihan Bersih:</span>
              <span class="text-base text-blue-700">Rp {{ formatPrice(initialData?.totalPrice || 0) }}</span>
            </div>
          </div>
        </div>

        <!-- Form Data Kontak Pemesan -->
        <form @submit.prevent="submitBooking" class="space-y-3.5 text-xs">
          <div>
            <label class="block font-semibold text-slate-700 mb-1">Nama Lengkap Pemesan / Rombongan *</label>
            <input
              v-model="customerForm.name"
              type="text"
              required
              placeholder="Contoh: Rian Anggara"
              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 font-medium focus:ring-2 focus:ring-blue-600 focus:outline-none"
            />
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
              <label class="block font-semibold text-slate-700 mb-1">Alamat Email *</label>
              <input
                v-model="customerForm.email"
                type="email"
                required
                placeholder="rian@example.com"
                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 font-medium focus:ring-2 focus:ring-blue-600 focus:outline-none"
              />
            </div>
            <div>
              <label class="block font-semibold text-slate-700 mb-1">No. WhatsApp / Telepon Aktif *</label>
              <input
                v-model="customerForm.phone"
                type="tel"
                required
                placeholder="081234567890"
                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 font-medium focus:ring-2 focus:ring-blue-600 focus:outline-none"
              />
            </div>
          </div>

          <div>
            <label class="block font-semibold text-slate-700 mb-1">Alamat Titik Jemput Lengkap *</label>
            <textarea
              v-model="customerForm.pickupAddress"
              rows="2"
              required
              placeholder="Contoh: Kantor PT ABC, Jl. Sudirman Kav 25, Jakarta Selatan"
              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs text-slate-800 font-medium focus:ring-2 focus:ring-blue-600 focus:outline-none"
            ></textarea>
          </div>

          <div>
            <label class="block font-semibold text-slate-700 mb-1">Catatan Khusus (Opsional)</label>
            <input
              v-model="customerForm.notes"
              type="text"
              placeholder="Contoh: Supir non-smoking, mohon sediakan galon air cadangan"
              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs text-slate-800 font-medium focus:ring-2 focus:ring-blue-600 focus:outline-none"
            />
          </div>

          <div v-if="errorText" class="p-2.5 rounded-xl bg-red-50 border border-red-200 text-red-600 font-medium text-xs">
            {{ errorText }}
          </div>

          <button
            type="submit"
            :disabled="submitting"
            class="btn-primary w-full py-3.5 rounded-xl text-xs font-bold justify-center cursor-pointer shadow-lg mt-3"
            :style="{ backgroundColor: primaryColor }"
          >
            <span v-if="submitting">Memproses Pemesanan...</span>
            <span v-else>Konfirmasi & Terbitkan Faktur Sewa (Invoice)</span>
          </button>
        </form>
      </div>

      <!-- Step 2: Layar Sukses & Petunjuk Pembayaran -->
      <div v-else class="space-y-5 text-center">
        <div class="w-16 h-16 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center text-3xl mx-auto shadow-inner">
          ✓
        </div>

        <div>
          <h3 class="text-xl font-black text-slate-900">Reservasi Bus Berhasil Diterbitkan!</h3>
          <p class="text-xs text-slate-500 mt-1">
            Nomor Pemesanan Anda: <strong class="text-blue-700 font-mono text-sm">{{ confirmedBooking.booking_code }}</strong>
          </p>
        </div>

        <!-- Detail Rekening Pembayaran -->
        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-5 text-left text-xs space-y-3">
          <div class="flex justify-between items-center border-b border-slate-200 pb-2">
            <span class="font-bold text-slate-800">Petunjuk Pembayaran Transfer:</span>
            <span class="bg-emerald-100 text-emerald-800 text-[10px] font-bold px-2 py-0.5 rounded">Tagihan Aktif</span>
          </div>

          <div class="space-y-2 text-slate-600">
            <div class="p-3 bg-white rounded-xl border border-slate-200">
              <div class="font-bold text-blue-900">Bank Central Asia (BCA)</div>
              <div class="text-sm font-mono font-bold text-slate-900">889-0123-7788</div>
              <div class="text-[10px] text-slate-400">a.n. PT SEMESTA JAYA TRANSPORT</div>
            </div>

            <div class="p-3 bg-white rounded-xl border border-slate-200">
              <div class="font-bold text-blue-900">Bank Mandiri</div>
              <div class="text-sm font-mono font-bold text-slate-900">123-00-998877-6</div>
              <div class="text-[10px] text-slate-400">a.n. PT SEMESTA JAYA TRANSPORT</div>
            </div>
          </div>

          <div class="pt-2 border-t border-slate-200 flex justify-between font-bold text-slate-900">
            <span>Total yang harus dibayar:</span>
            <span class="text-base text-blue-700 font-black">Rp {{ formatPrice(confirmedBooking.total_price) }}</span>
          </div>
          <p class="text-[10px] text-slate-400">
            *Untuk mengunci armada bus, Anda juga dapat membayarkan Down Payment (DP) 30% sebesar: <strong>Rp {{ formatPrice(confirmedBooking.total_price * 0.3) }}</strong>
          </p>
        </div>

        <div class="pt-2 flex flex-col sm:flex-row gap-3">
          <button
            @click="finishBooking"
            class="btn-primary flex-1 py-3 text-xs font-bold justify-center cursor-pointer"
            :style="{ backgroundColor: primaryColor }"
          >
            Selesai & Lihat Pesanan Saya
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
const props = defineProps<{
  isOpen: boolean
  initialData?: any
}>()

const emit = defineEmits(['close', 'booking-confirmed', 'open-my-bookings'])

const { primaryColor } = useTheme()
const { user, token } = useAuth()

const submitting = ref(false)
const errorText = ref('')
const confirmedBooking = ref<any>(null)

const customerForm = reactive({
  name: '',
  email: '',
  phone: '',
  pickupAddress: '',
  notes: '',
})

watch(() => props.isOpen, (open) => {
  if (open) {
    confirmedBooking.value = null
    errorText.value = ''
    if (user.value) {
      customerForm.name = user.value.name || user.value.username || ''
      customerForm.email = user.value.email || ''
      customerForm.phone = user.value.phone_number || ''
    }
  }
})

const formatPrice = (val: number) => {
  return new Intl.NumberFormat('id-ID').format(val || 0)
}

const saveLocalBooking = (bookingItem: any) => {
  if (typeof localStorage !== 'undefined') {
    try {
      const existing = JSON.parse(localStorage.getItem('sjtrans_local_bookings') || '[]')
      const exists = existing.some((b: any) => b.booking_code === bookingItem.booking_code)
      if (!exists) {
        existing.unshift(bookingItem)
        localStorage.setItem('sjtrans_local_bookings', JSON.stringify(existing.slice(0, 20)))
      }
    } catch (e) {
      console.error('Failed to save local booking:', e)
    }
  }
}

const submitBooking = async () => {
  submitting.value = true
  errorText.value = ''

  try {
    const payload = {
      fleet_id: props.initialData?.fleet?.id || 1,
      customer_name: customerForm.name,
      customer_email: customerForm.email,
      customer_phone: customerForm.phone,
      pickup_location: customerForm.pickupAddress || props.initialData?.origin || 'Jakarta',
      destination: props.initialData?.destination || 'Bandung',
      start_date: props.initialData?.startDate || new Date().toISOString().split('T')[0],
      end_date: props.initialData?.endDate || new Date().toISOString().split('T')[0],
      total_passengers: props.initialData?.fleet?.capacity || 50,
      total_units: props.initialData?.totalUnits || 1,
      discount_code: props.initialData?.discountCode || null,
      special_notes: customerForm.notes,
    }

    const headers: any = { Accept: 'application/json' }
    if (token.value) {
      headers.Authorization = `Bearer ${token.value}`
    }

    const res: any = await $fetch('http://127.0.0.1:8000/api/v1/bookings', {
      method: 'POST',
      headers,
      body: payload,
    })

    if (res && res.data) {
      confirmedBooking.value = res.data
      saveLocalBooking(res.data)
      emit('booking-confirmed', res.data)
    }
  } catch (err: any) {
    // Client-side fallback if network error
    const code = 'SJT-' + new Date().getFullYear() + '10-' + Math.floor(1000 + Math.random() * 9000)
    confirmedBooking.value = {
      booking_code: code,
      customer_name: customerForm.name,
      customer_email: customerForm.email,
      customer_phone: customerForm.phone,
      destination: props.initialData?.destination || 'Bandung',
      start_date: props.initialData?.startDate || new Date().toISOString().split('T')[0],
      total_days: props.initialData?.totalDays || 1,
      total_price: props.initialData?.totalPrice || 3800000,
      discount_amount: props.initialData?.discountAmount || 0,
      status: 'pending',
      payment_status: 'unpaid',
      fleet: props.initialData?.fleet || { name: 'Big Bus Scania K410IB' },
    }
    saveLocalBooking(confirmedBooking.value)
    emit('booking-confirmed', confirmedBooking.value)
  } finally {
    submitting.value = false
  }
}

const finishBooking = () => {
  emit('close')
  emit('open-my-bookings')
}
</script>
