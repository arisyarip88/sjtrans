<template>
  <div class="min-h-screen bg-slate-100 flex flex-col pb-16 md:pb-12">
    <!-- Auth Guard Check -->
    <div v-if="!isLoggedIn" class="min-h-screen flex items-center justify-center p-4">
      <div class="max-w-md w-full bg-white rounded-3xl p-8 border border-slate-200 shadow-xl text-center">
        <div class="w-16 h-16 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center text-3xl mx-auto mb-4">
          👤
        </div>
        <h2 class="text-xl font-black text-slate-900 mb-2">{{ t('customer.title') }}</h2>
        <p class="text-xs text-slate-500 mb-6 leading-relaxed">
          Silakan masuk ke akun pelanggan Anda untuk melihat riwayat reservasi bus, upload bukti pembayaran transfer, dan mengunduh e-tiket resmi.
        </p>

        <!-- 1-Click Demo Customer Login -->
        <div class="bg-slate-50 rounded-2xl p-4 border border-slate-200 mb-6 text-left space-y-2">
          <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Akun Demo Pelanggan:</div>
          <button
            @click="quickCustomerLogin"
            class="w-full py-2.5 px-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition flex items-center justify-between cursor-pointer shadow-sm"
          >
            <span>👤 Masuk sebagai Pelanggan (Budi Santoso)</span>
            <span class="text-[10px] bg-blue-800 px-1.5 py-0.5 rounded">1-Klik</span>
          </button>
        </div>

        <NuxtLink to="/" class="text-xs font-semibold text-slate-500 hover:text-slate-800 transition">
          ← Kembali ke Website Utama
        </NuxtLink>
      </div>
    </div>

    <!-- CUSTOMER DEDICATED TEMPLATE -->
    <div v-else class="min-h-screen flex flex-col">
      <!-- Customer Header -->
      <header class="bg-white border-b border-slate-200 px-4 sm:px-8 py-3.5 flex items-center justify-between sticky top-0 z-30 shadow-xs">
        <div class="flex items-center gap-3">
          <NuxtLink to="/" class="flex items-center gap-2 group">
            <div class="w-9 h-9 rounded-xl bg-blue-600 flex items-center justify-center font-black text-sm text-white shadow-sm">
              SJ
            </div>
            <div>
              <span class="text-sm font-black tracking-tight text-slate-900">SJTRANS</span>
              <span class="text-xs text-slate-400 block -mt-1 font-medium">{{ t('customer.title') }}</span>
            </div>
          </NuxtLink>
        </div>

        <div class="flex items-center gap-3">
          <!-- Bilingual Switcher -->
          <div class="flex items-center bg-slate-100 rounded-full p-0.5 border border-slate-200">
            <button
              @click="setLocale('id')"
              class="px-2 py-0.5 rounded-full text-[10px] font-bold transition cursor-pointer"
              :class="locale === 'id' ? 'bg-blue-600 text-white shadow-2xs' : 'text-slate-500 hover:text-slate-900'"
            >
              🇮🇩 ID
            </button>
            <button
              @click="setLocale('en')"
              class="px-2 py-0.5 rounded-full text-[10px] font-bold transition cursor-pointer"
              :class="locale === 'en' ? 'bg-blue-600 text-white shadow-2xs' : 'text-slate-500 hover:text-slate-900'"
            >
              🇬🇧 EN
            </button>
          </div>

          <NuxtLink to="/" class="text-xs font-semibold text-slate-600 hover:text-blue-600 transition hidden sm:inline">
            Website Utama
          </NuxtLink>

          <button
            @click="handleLogout"
            class="text-xs font-bold text-red-600 hover:text-red-700 bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-xl transition cursor-pointer"
          >
            {{ t('logout') }}
          </button>
        </div>
      </header>

      <!-- Customer Portal Body Container -->
      <div class="container-custom py-8 flex-1">
        <!-- Customer Profile Welcome Banner -->
        <div class="bg-gradient-to-r from-blue-900 to-slate-900 text-white rounded-3xl p-6 sm:p-8 shadow-md mb-8 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
          <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-2xl bg-white/20 border border-white/30 flex items-center justify-center text-2xl font-black shadow-inner">
              {{ user?.name?.charAt(0) || 'B' }}
            </div>
            <div>
              <div class="flex items-center gap-2">
                <h1 class="text-xl sm:text-2xl font-black">{{ user?.name }}</h1>
                <span class="bg-emerald-500/20 text-emerald-300 border border-emerald-400/30 text-[10px] font-bold px-2 py-0.5 rounded-full">
                  Member Terverifikasi
                </span>
              </div>
              <p class="text-xs text-blue-200 mt-1">
                📧 {{ user?.email }} • 📱 {{ user?.phone_number || '0812-3456-7890' }}
              </p>
            </div>
          </div>

          <div class="flex items-center gap-3 self-stretch sm:self-auto">
            <NuxtLink
              to="/armada"
              class="btn-primary flex-1 sm:flex-none text-center justify-center text-xs py-2.5 px-5 rounded-xl font-bold shadow-md cursor-pointer"
              :style="{ backgroundColor: primaryColor }"
            >
              + {{ t('customer.new_booking') }}
            </NuxtLink>
          </div>
        </div>

        <!-- Navigation Tabs -->
        <div class="flex border-b border-slate-200 mb-6 bg-white rounded-2xl p-1 shadow-2xs text-xs font-bold w-full sm:w-auto self-start">
          <button
            @click="customerTab = 'orders'"
            class="flex-1 sm:flex-none px-5 py-2.5 rounded-xl transition cursor-pointer flex items-center justify-center gap-2"
            :class="customerTab === 'orders' ? 'bg-blue-600 text-white shadow-2xs' : 'text-slate-600 hover:bg-slate-50'"
          >
            <span>🎫</span>
            <span>{{ t('customer.my_orders') }}</span>
            <span class="bg-white/20 text-white text-[10px] px-1.5 py-0.2 rounded-full" v-if="customerTab === 'orders'">
              {{ myBookings.length }}
            </span>
          </button>
          <button
            @click="customerTab = 'profile'"
            class="flex-1 sm:flex-none px-5 py-2.5 rounded-xl transition cursor-pointer flex items-center justify-center gap-2"
            :class="customerTab === 'profile' ? 'bg-blue-600 text-white shadow-2xs' : 'text-slate-600 hover:bg-slate-50'"
          >
            <span>👤</span>
            <span>{{ t('customer.profile') }}</span>
          </button>
        </div>

        <!-- TAB 1: DAFTAR PESANAN SAYA -->
        <div v-if="customerTab === 'orders'" class="space-y-6">
          <div v-if="myBookings.length > 0" class="space-y-4">
            <div
              v-for="order in myBookings"
              :key="order.id"
              class="bg-white rounded-3xl border border-slate-200 p-6 shadow-2xs hover:shadow-md transition flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6"
            >
              <div class="space-y-2 flex-1">
                <div class="flex flex-wrap items-center gap-2">
                  <span class="font-mono text-xs font-bold text-blue-600 bg-blue-50 px-2.5 py-1 rounded-lg">
                    {{ order.booking_code }}
                  </span>
                  <span
                    class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase"
                    :class="{
                      'bg-amber-100 text-amber-800': order.payment_status === 'Menunggu Pembayaran',
                      'bg-blue-100 text-blue-800': order.payment_status === 'DP Terbayar (30%)',
                      'bg-emerald-100 text-emerald-800': order.payment_status === 'Lunas',
                    }"
                  >
                    ● {{ order.payment_status }}
                  </span>
                </div>

                <h3 class="text-lg font-black text-slate-900">
                  {{ order.fleet_name }}
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs text-slate-600">
                  <div>📍 Rute: <strong>{{ order.origin }} ➔ {{ order.destination }}</strong></div>
                  <div>🗓️ Tanggal: <strong>{{ order.start_date }} s/d {{ order.end_date }} ({{ order.days }} Hari)</strong></div>
                  <div>👥 Penumpang: <strong>{{ order.seats }} Kursi ({{ order.units }} Unit)</strong></div>
                  <div>💰 Total Biaya: <strong class="text-slate-900 font-black">Rp {{ order.total_price.toLocaleString() }}</strong></div>
                </div>
              </div>

              <!-- Action Buttons for Customer -->
              <div class="flex flex-wrap items-center gap-2 self-stretch lg:self-auto justify-end border-t lg:border-t-0 pt-4 lg:pt-0 border-slate-100">
                <button
                  v-if="order.payment_status !== 'Lunas'"
                  @click="openUploadModal(order)"
                  class="btn-primary text-xs py-2 px-4 rounded-xl font-bold cursor-pointer shadow-2xs transition"
                  :style="{ backgroundColor: primaryColor }"
                >
                  📤 {{ t('customer.upload_proof') }}
                </button>

                <button
                  @click="openInvoiceModal(order)"
                  class="btn-outline text-xs py-2 px-4 rounded-xl font-bold cursor-pointer transition flex items-center gap-1.5"
                  :style="{ borderColor: primaryColor, color: primaryColor }"
                >
                  <span>📄</span>
                  <span>{{ t('customer.download_ticket') }}</span>
                </button>
              </div>
            </div>
          </div>

          <div v-else class="text-center py-16 bg-white rounded-3xl border border-slate-200 p-8">
            <span class="text-4xl block mb-3">🎫</span>
            <h3 class="text-base font-bold text-slate-800 mb-1">Belum Ada Riwayat Pemesanan</h3>
            <p class="text-xs text-slate-500 mb-4">Anda belum melakukan penyewaan bus pariwisata saat ini.</p>
            <NuxtLink
              to="/armada"
              class="btn-primary text-xs py-2 px-5 rounded-xl font-bold cursor-pointer inline-block"
              :style="{ backgroundColor: primaryColor }"
            >
              Lihat Armada & Sewa Sekarang
            </NuxtLink>
          </div>
        </div>

        <!-- TAB 2: PROFIL AKUN -->
        <div v-if="customerTab === 'profile'" class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-10 shadow-2xs max-w-2xl">
          <h2 class="text-lg font-black text-slate-900 mb-4">Informasi Akun Pelanggan</h2>

          <div class="space-y-4 text-xs">
            <div>
              <label class="font-bold text-slate-600 block mb-1">Nama Lengkap</label>
              <input type="text" :value="user?.name" readonly class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-slate-800" />
            </div>
            <div>
              <label class="font-bold text-slate-600 block mb-1">Username</label>
              <input type="text" :value="user?.username" readonly class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-slate-800 font-mono" />
            </div>
            <div>
              <label class="font-bold text-slate-600 block mb-1">Alamat Email</label>
              <input type="text" :value="user?.email" readonly class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-slate-800" />
            </div>
            <div>
              <label class="font-bold text-slate-600 block mb-1">No. WhatsApp / Handphone</label>
              <input type="text" :value="user?.phone_number || '0812-3456-7890'" readonly class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-slate-800" />
            </div>
            <div>
              <label class="font-bold text-slate-600 block mb-1">Tipe Akun</label>
              <div class="bg-blue-50 border border-blue-200 text-blue-900 rounded-xl p-3 font-bold">
                Pelanggan Terdaftar SJ Trans
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Upload Bukti Bayar Modal -->
    <div
      v-if="selectedOrderForUpload"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs animate-fade"
    >
      <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-200 relative">
        <button @click="selectedOrderForUpload = null" class="absolute top-4 right-4 text-slate-400 hover:text-slate-700 text-sm font-bold">✕</button>
        <h3 class="text-base font-black text-slate-900 mb-1">Upload Bukti Transfer Bank</h3>
        <p class="text-xs text-slate-500 mb-4">Kode Booking: {{ selectedOrderForUpload.booking_code }}</p>

        <div class="space-y-3 text-xs mb-5">
          <div class="bg-slate-50 p-3 rounded-xl border border-slate-200">
            <span class="text-[10px] text-slate-400 uppercase font-bold block">Rekening Resmi SJ Trans</span>
            <div class="font-mono font-bold text-slate-900">BCA: 123-456-7890</div>
            <div class="font-mono font-bold text-slate-900">Mandiri: 137-000-888-999</div>
            <div class="text-[10px] text-slate-500 mt-1">a.n PT Semesta Jaya Transport Indonesia</div>
          </div>

          <div>
            <label class="font-bold text-slate-700 block mb-1">Bank Pengirim Anda</label>
            <input v-model="uploadBank" type="text" placeholder="Contoh: BCA / Mandiri / BNI" class="w-full p-2.5 border border-slate-200 rounded-xl text-xs" />
          </div>

          <div>
            <label class="font-bold text-slate-700 block mb-1">Nominal yang Ditransfer</label>
            <input v-model="uploadNominal" type="text" :placeholder="`Rp ${selectedOrderForUpload.total_price.toLocaleString()}`" class="w-full p-2.5 border border-slate-200 rounded-xl text-xs" />
          </div>

          <div>
            <label class="font-bold text-slate-700 block mb-1">Foto Bukti Transfer (Resi/Struk)</label>
            <input type="file" class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700" />
          </div>
        </div>

        <button
          @click="submitProof"
          class="btn-primary w-full py-2.5 rounded-xl font-bold text-xs text-center justify-center cursor-pointer shadow-md"
          :style="{ backgroundColor: primaryColor }"
        >
          Kirim Bukti Pembayaran
        </button>
      </div>
    </div>

    <!-- E-Tiket / Invoice Modal Preview -->
    <div
      v-if="selectedOrderForInvoice"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs animate-fade"
    >
      <div class="bg-white rounded-3xl max-w-lg w-full p-6 sm:p-8 shadow-2xl border border-slate-200 max-h-[90vh] overflow-y-auto relative">
        <button @click="selectedOrderForInvoice = null" class="absolute top-4 right-4 text-slate-400 hover:text-slate-700 text-sm font-bold">✕</button>

        <!-- Printable E-Tiket Header -->
        <div class="border-b-2 border-dashed border-slate-200 pb-4 mb-4">
          <div class="flex items-center justify-between">
            <div>
              <span class="text-xl font-black text-slate-900">SJ<span class="text-blue-600">TRANS</span></span>
              <span class="text-[10px] text-slate-400 block">PT Semesta Jaya Transport Indonesia</span>
            </div>
            <span class="bg-emerald-100 text-emerald-800 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase">
              E-TIKET RESMI
            </span>
          </div>
        </div>

        <div class="space-y-3 text-xs text-slate-700 mb-6">
          <div class="flex justify-between">
            <span class="text-slate-400">Kode Booking:</span>
            <span class="font-mono font-bold text-blue-600">{{ selectedOrderForInvoice.booking_code }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-slate-400">Nama Pemesan:</span>
            <span class="font-bold text-slate-900">{{ user?.name }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-slate-400">Armada Bus:</span>
            <span class="font-bold text-slate-900">{{ selectedOrderForInvoice.fleet_name }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-slate-400">Rute Perjalanan:</span>
            <span class="font-bold text-slate-900">{{ selectedOrderForInvoice.origin }} ➔ {{ selectedOrderForInvoice.destination }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-slate-400">Jadwal Sewa:</span>
            <span class="font-bold text-slate-900">{{ selectedOrderForInvoice.start_date }} s/d {{ selectedOrderForInvoice.end_date }}</span>
          </div>
          <div class="flex justify-between pt-2 border-t border-slate-100">
            <span class="text-slate-400">Total Tagihan:</span>
            <span class="text-base font-black text-slate-900">Rp {{ selectedOrderForInvoice.total_price.toLocaleString() }}</span>
          </div>
        </div>

        <div class="bg-slate-50 p-3 rounded-xl border border-slate-200 text-[10px] text-slate-500 mb-5 leading-relaxed">
          ℹ️ Tunjukkan E-Tiket ini kepada kru pengemudi atau staf operasional saat penjemputan di lokasi. Hotline darurat 24 Jam: 1500-888.
        </div>

        <div class="flex gap-2">
          <button
            onclick="window.print()"
            class="btn-primary flex-1 py-2.5 rounded-xl font-bold text-xs text-center justify-center cursor-pointer shadow-md"
            :style="{ backgroundColor: primaryColor }"
          >
            🖨️ Cetak / Simpan PDF
          </button>
          <button
            @click="selectedOrderForInvoice = null"
            class="btn-outline py-2.5 px-4 rounded-xl font-bold text-xs cursor-pointer"
          >
            Tutup
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
const router = useRouter()
const { primaryColor } = useTheme()
const { user, isLoggedIn, login, logout } = useAuth()
const { locale, setLocale, t } = useI18n()

const customerTab = ref<'orders' | 'profile'>('orders')
const selectedOrderForUpload = ref<any>(null)
const selectedOrderForInvoice = ref<any>(null)
const uploadBank = ref('')
const uploadNominal = ref('')

const myBookings = ref<any[]>([
  {
    id: 1,
    booking_code: 'SJT-20261028-001',
    fleet_name: 'Big Bus SHD Scania K410IB Jetbus 5',
    seats: 50,
    units: 1,
    origin: 'Jakarta (TB Simatupang)',
    destination: 'Bandung & Lembang',
    start_date: '2026-11-01',
    end_date: '2026-11-03',
    days: 3,
    total_price: 12600000,
    payment_status: 'DP Terbayar (30%)',
  },
  {
    id: 2,
    booking_code: 'SJT-20261015-082',
    fleet_name: 'Toyota HiAce Premio Luxury VIP',
    seats: 14,
    units: 1,
    origin: 'Jakarta (Kuningan)',
    destination: 'Puncak Bogor',
    start_date: '2026-10-18',
    end_date: '2026-10-19',
    days: 2,
    total_price: 3000000,
    payment_status: 'Lunas',
  },
])

const quickCustomerLogin = async () => {
  await login('budi', 'password123')
}

const handleLogout = async () => {
  await logout()
  router.push('/')
}

const openUploadModal = (order: any) => {
  selectedOrderForUpload.value = order
}

const openInvoiceModal = (order: any) => {
  selectedOrderForInvoice.value = order
}

const submitProof = () => {
  if (selectedOrderForUpload.value) {
    selectedOrderForUpload.value.payment_status = 'Lunas'
    alert('Bukti pembayaran berhasil diunggah! Status pesanan Anda kini Lunas dan tiket resmi telah diterbitkan.')
    selectedOrderForUpload.value = null
  }
}
</script>
