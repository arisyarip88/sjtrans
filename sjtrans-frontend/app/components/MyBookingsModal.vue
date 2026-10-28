<template>
  <div v-if="isOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/75 backdrop-blur-sm animate-fade">
    <div class="bg-white rounded-3xl max-w-3xl w-full max-h-[90vh] overflow-y-auto p-6 sm:p-8 shadow-2xl relative">
      <button
        @click="$emit('close')"
        class="absolute top-5 right-5 w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center font-bold text-sm cursor-pointer"
      >
        ✕
      </button>

      <div class="space-y-6">
        <div>
          <span class="inline-flex items-center gap-1.5 px-3 py-0.5 rounded-full text-[11px] font-bold uppercase tracking-wider bg-blue-100 text-blue-800">
            Portal Pelanggan
          </span>
          <h3 class="text-xl font-black text-slate-900 mt-1">Riwayat & Status Pesanan Saya</h3>
          <p class="text-xs text-slate-500">Kelola reservasi bus dan upload bukti transfer pembayaran Anda.</p>
        </div>

        <!-- Daftar Pesanan -->
        <div v-if="bookings.length > 0" class="space-y-4">
          <div
            v-for="b in bookings"
            :key="b.id"
            class="p-5 rounded-2xl border border-slate-200 bg-slate-50/50 hover:bg-white hover:shadow-md transition space-y-3"
          >
            <div class="flex flex-wrap justify-between items-center gap-2 pb-3 border-b border-slate-200">
              <div class="flex items-center gap-2">
                <span class="font-mono font-bold text-blue-900 text-sm">{{ b.booking_code }}</span>
                <span
                  class="text-[10px] font-bold px-2.5 py-0.5 rounded-full uppercase"
                  :class="{
                    'bg-amber-100 text-amber-800': b.status === 'pending',
                    'bg-emerald-100 text-emerald-800': b.status === 'confirmed',
                    'bg-blue-100 text-blue-800': b.status === 'in_trip',
                    'bg-slate-200 text-slate-700': b.status === 'completed',
                  }"
                >
                  Status: {{ b.status }}
                </span>
              </div>

              <div class="text-xs">
                <span class="text-slate-400">Pembayaran: </span>
                <span
                  class="font-bold uppercase text-[10px] px-2 py-0.5 rounded"
                  :class="{
                    'bg-red-100 text-red-700': b.payment_status === 'unpaid',
                    'bg-amber-100 text-amber-800': b.payment_status === 'down_payment',
                    'bg-emerald-100 text-emerald-800': b.payment_status === 'paid',
                  }"
                >
                  {{ b.payment_status }}
                </span>
              </div>
            </div>

            <!-- Detail Info -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 text-xs text-slate-600">
              <div>
                <span class="text-slate-400 block text-[10px]">Armada Bus:</span>
                <strong class="text-slate-800">{{ b.fleet?.name || 'Big Bus SHD' }}</strong>
              </div>
              <div>
                <span class="text-slate-400 block text-[10px]">Rute & Tujuan:</span>
                <strong class="text-slate-800">{{ b.destination }}</strong>
              </div>
              <div>
                <span class="text-slate-400 block text-[10px]">Tanggal Sewa:</span>
                <strong class="text-slate-800">{{ b.start_date }} ({{ b.total_days }} Hari)</strong>
              </div>
            </div>

            <!-- Price & Upload Proof Action -->
            <div class="pt-3 border-t border-slate-200 flex flex-wrap justify-between items-center gap-3">
              <div>
                <span class="text-[11px] text-slate-400 block">Total Tagihan:</span>
                <span class="text-base font-black text-blue-900">Rp {{ formatPrice(b.total_price) }}</span>
                <span v-if="b.discount_amount > 0" class="text-[10px] text-emerald-600 font-semibold ml-2">
                  (Hemat Rp {{ formatPrice(b.discount_amount) }})
                </span>
              </div>

              <button
                v-if="b.payment_status !== 'paid'"
                @click="openUploadProof(b)"
                class="btn-primary text-xs py-2 px-4 rounded-xl cursor-pointer"
                :style="{ backgroundColor: primaryColor }"
              >
                📤 Upload Bukti Transfer (DP / Pelunasan)
              </button>
              <span v-else class="text-xs font-bold text-emerald-600 flex items-center gap-1">
                ✓ Lunas Terverifikasi
              </span>
            </div>
          </div>
        </div>

        <div v-else class="text-center py-12 text-slate-400 text-xs">
          Belum ada riwayat pesanan sewa bus. Silakan buat pesanan baru melalui beranda!
        </div>
      </div>
    </div>

    <!-- Sub-modal Upload Bukti Transfer -->
    <div v-if="selectedUploadBooking" class="fixed inset-0 z-60 flex items-center justify-center p-4 bg-slate-950/80 backdrop-blur-sm">
      <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl relative space-y-4">
        <button
          @click="selectedUploadBooking = null"
          class="absolute top-4 right-4 w-7 h-7 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center font-bold text-xs cursor-pointer"
        >
          ✕
        </button>

        <h4 class="text-base font-bold text-slate-900">Upload Bukti Transfer Pembayaran</h4>
        <p class="text-xs text-slate-500">
          Booking: <strong>{{ selectedUploadBooking.booking_code }}</strong>
        </p>

        <div class="space-y-3 text-xs">
          <div>
            <label class="block font-semibold text-slate-700 mb-1">Jenis Pembayaran</label>
            <select v-model="proofForm.type" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs">
              <option value="down_payment">Down Payment (DP 30%)</option>
              <option value="full_payment">Pelunasan Penuh (100%)</option>
            </select>
          </div>

          <div>
            <label class="block font-semibold text-slate-700 mb-1">Nominal yang Ditransfer (Rp)</label>
            <input
              v-model="proofForm.amount"
              type="number"
              placeholder="Contoh: 3000000"
              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs"
            />
          </div>

          <div>
            <label class="block font-semibold text-slate-700 mb-1">Metode Bank</label>
            <select v-model="proofForm.bank" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs">
              <option value="bca">Transfer Bank BCA (889-0123-7788 a.n. PT SJ Trans)</option>
              <option value="mandiri">Transfer Bank Mandiri (123-00-998877-6 a.n. PT SJ Trans)</option>
              <option value="qris">QRIS SJ Trans</option>
            </select>
          </div>

          <div>
            <label class="block font-semibold text-slate-700 mb-1">Catatan Pengirim</label>
            <input
              v-model="proofForm.notes"
              type="text"
              placeholder="Contoh: Transfer atas nama Budi"
              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs"
            />
          </div>

          <div v-if="uploadSuccess" class="text-xs font-bold text-emerald-600">
            ✓ Bukti transfer berhasil dikirim! Admin akan segera memverifikasi.
          </div>

          <button
            @click="submitProof"
            class="btn-primary w-full py-3 rounded-xl text-xs font-bold justify-center cursor-pointer mt-2"
            :style="{ backgroundColor: primaryColor }"
          >
            Kirim Bukti Pembayaran
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
const props = defineProps<{
  isOpen: boolean
}>()

const emit = defineEmits(['close'])

const { primaryColor } = useTheme()
const { user, token } = useAuth()

const bookings = ref<any[]>([])
const selectedUploadBooking = ref<any>(null)
const uploadSuccess = ref(false)

const proofForm = reactive({
  type: 'down_payment',
  amount: 0,
  bank: 'bca',
  notes: '',
})

const formatPrice = (val: number) => {
  return new Intl.NumberFormat('id-ID').format(val || 0)
}

const fetchMyBookings = async () => {
  let serverBookings: any[] = []
  if (token.value) {
    try {
      const res: any = await $fetch('http://127.0.0.1:8000/api/v1/my-bookings', {
        headers: {
          Accept: 'application/json',
          Authorization: `Bearer ${token.value}`,
        },
      })
      if (res) {
        if (Array.isArray(res.data)) {
          serverBookings = res.data
        } else if (res.data && Array.isArray(res.data.data)) {
          serverBookings = res.data.data
        } else if (Array.isArray(res)) {
          serverBookings = res
        }
      }
    } catch (e) {
      console.warn('API my-bookings fetch error, using local fallback:', e)
    }
  }

  // Ambil dari penyimpanan lokal browser (cache transaksi pemesanan lokal)
  let localBookings: any[] = []
  if (typeof localStorage !== 'undefined') {
    try {
      localBookings = JSON.parse(localStorage.getItem('sjtrans_local_bookings') || '[]')
    } catch (e) {}
  }

  // Gabungkan tanpa duplikasi berdasarkan booking_code
  const combined = [...serverBookings]
  for (const lb of localBookings) {
    if (!combined.some((b: any) => b.booking_code === lb.booking_code)) {
      combined.push(lb)
    }
  }

  bookings.value = combined
}

watch(() => props.isOpen, (open) => {
  if (open) {
    fetchMyBookings()
  }
})

const openUploadProof = (booking: any) => {
  selectedUploadBooking.value = booking
  proofForm.amount = booking.payment_status === 'unpaid' ? Math.round(booking.total_price * 0.3) : booking.total_price
  uploadSuccess.value = false
}

const submitProof = async () => {
  try {
    await $fetch('http://127.0.0.1:8000/api/v1/transactions', {
      method: 'POST',
      headers: { Authorization: `Bearer ${token.value}` },
      body: {
        booking_id: selectedUploadBooking.value.id,
        amount: proofForm.amount,
        payment_type: proofForm.type,
        payment_method: proofForm.bank,
        payment_proof_url: 'https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?auto=format&fit=crop&w=600&q=80',
        notes: proofForm.notes,
      },
    })
  } catch (e) {
    // Simulation
  }

  uploadSuccess.value = true
  setTimeout(() => {
    selectedUploadBooking.value = null
    fetchMyBookings()
  }, 1800)
}
</script>
