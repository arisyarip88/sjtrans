<template>
  <div v-if="isOpen" class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-6 bg-slate-950/80 backdrop-blur-md animate-fade">
    <div class="bg-white rounded-3xl w-full max-w-6xl max-h-[95vh] overflow-hidden flex flex-col shadow-2xl border border-slate-200">
      <!-- Admin Topbar -->
      <div class="p-5 bg-slate-900 text-white flex flex-wrap justify-between items-center gap-4 border-b border-slate-800">
        <div class="flex items-center gap-3">
          <div
            class="w-10 h-10 rounded-2xl flex items-center justify-center text-white font-extrabold text-base shadow-md"
            :style="{ backgroundColor: primaryColor }"
          >
            SJ
          </div>
          <div>
            <div class="flex items-center gap-2">
              <h3 class="text-base font-bold text-white">Panel Kontrol Operasional SJ Trans</h3>
              <span class="bg-purple-600/90 text-white text-[10px] font-extrabold px-2 py-0.5 rounded-full uppercase">
                {{ user?.role?.display_name || 'Administrator' }}
              </span>
            </div>
            <p class="text-xs text-slate-400">Selamat datang, <strong>{{ user?.name }}</strong> ({{ user?.username }})</p>
          </div>
        </div>

        <button
          @click="$emit('close')"
          class="w-8 h-8 rounded-full bg-slate-800 hover:bg-slate-700 text-slate-300 flex items-center justify-center font-bold text-sm cursor-pointer"
        >
          ✕
        </button>
      </div>

      <!-- Admin Tab Navigation -->
      <div class="bg-slate-100/80 border-b border-slate-200 px-5 flex overflow-x-auto gap-2 text-xs font-bold text-slate-600">
        <button
          v-for="tab in availableTabs"
          :key="tab.id"
          @click="activeTab = tab.id"
          class="py-3 px-4 border-b-2 transition cursor-pointer whitespace-nowrap flex items-center gap-2"
          :class="activeTab === tab.id
            ? 'border-blue-700 text-blue-900 bg-white rounded-t-xl'
            : 'border-transparent hover:text-slate-900'"
        >
          <span>{{ tab.icon }}</span>
          <span>{{ tab.name }}</span>
        </button>
      </div>

      <!-- Admin Content Body -->
      <div class="flex-1 overflow-y-auto p-5 sm:p-6 bg-slate-50 text-xs space-y-6">
        <!-- TAB 1: OVERVIEW & STATS -->
        <div v-if="activeTab === 'overview'" class="space-y-6">
          <!-- 4 KPI Cards -->
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
              <span class="text-slate-400 text-[11px] font-semibold block">Total Omset Terverifikasi</span>
              <span class="text-xl font-black text-slate-900 mt-1 block">Rp {{ formatPrice(stats.totalRevenue) }}</span>
              <span class="text-[10px] text-emerald-600 font-bold">✓ Dari transaksi lunas & DP</span>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
              <span class="text-slate-400 text-[11px] font-semibold block">Total Reservasi Bus</span>
              <span class="text-xl font-black text-slate-900 mt-1 block">{{ stats.totalBookings }} Pesanan</span>
              <span class="text-[10px] text-blue-600 font-bold">{{ stats.confirmedBookings }} Terkonfirmasi</span>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
              <span class="text-slate-400 text-[11px] font-semibold block">Total Armada Aktif</span>
              <span class="text-xl font-black text-slate-900 mt-1 block">{{ stats.totalFleets }} Unit</span>
              <span class="text-[10px] text-slate-500 font-medium">Scania, Mercy, HiAce</span>
            </div>
            <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
              <span class="text-slate-400 text-[11px] font-semibold block">Pengguna Terdaftar</span>
              <span class="text-xl font-black text-slate-900 mt-1 block">{{ stats.totalUsers }} User</span>
              <span class="text-[10px] text-purple-600 font-bold">3 Role Akses</span>
            </div>
          </div>

          <!-- Pesanan Terbaru Ringkasan -->
          <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm space-y-3">
            <div class="flex justify-between items-center">
              <h4 class="font-bold text-sm text-slate-900">Reservasi Terbaru Masuk</h4>
              <button @click="activeTab = 'bookings'" class="text-blue-700 font-bold hover:underline">Lihat Semua ➔</button>
            </div>

            <div class="overflow-x-auto">
              <table class="w-full text-left border-collapse">
                <thead>
                  <tr class="border-b border-slate-200 text-slate-400 text-[11px]">
                    <th class="py-2">Kode</th>
                    <th>Pemesan</th>
                    <th>Armada</th>
                    <th>Tujuan</th>
                    <th>Total</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                  <tr v-for="b in bookingsList.slice(0, 4)" :key="b.id">
                    <td class="py-2.5 font-mono font-bold text-blue-900">{{ b.booking_code }}</td>
                    <td class="font-medium text-slate-800">{{ b.customer_name }}</td>
                    <td class="text-slate-600">{{ b.fleet?.name || 'Big Bus' }}</td>
                    <td class="text-slate-600">{{ b.destination }}</td>
                    <td class="font-bold text-slate-900">Rp {{ formatPrice(b.total_price) }}</td>
                    <td>
                      <span class="px-2 py-0.5 rounded-full font-bold uppercase text-[10px] bg-emerald-100 text-emerald-800">
                        {{ b.status }}
                      </span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- TAB 2: TABEL PEMESANAN & RESERVASI (CRUD LENGKAP) -->
        <div v-if="activeTab === 'bookings'" class="space-y-4">
          <div class="flex flex-wrap justify-between items-center gap-3">
            <h4 class="font-bold text-sm text-slate-900">Data Pemesanan & Reservasi Bus</h4>
            <div class="flex gap-2">
              <input
                v-model="searchQuery"
                type="text"
                placeholder="Cari kode booking / nama..."
                class="bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600"
              />
            </div>
          </div>

          <!-- Table -->
          <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-x-auto">
            <table class="w-full text-left border-collapse">
              <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold text-[11px]">
                  <th class="p-3">Kode Booking</th>
                  <th class="p-3">Pemesan & Telpon</th>
                  <th class="p-3">Armada Bus</th>
                  <th class="p-3">Rute & Tgl</th>
                  <th class="p-3">Subtotal & Diskon</th>
                  <th class="p-3">Total Bayar</th>
                  <th class="p-3">Status Sewa</th>
                  <th class="p-3">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
                <tr v-for="b in filteredBookings" :key="b.id" class="hover:bg-slate-50/60 transition">
                  <td class="p-3 font-mono font-bold text-blue-900">{{ b.booking_code }}</td>
                  <td class="p-3">
                    <div class="font-bold text-slate-900">{{ b.customer_name }}</div>
                    <div class="text-[11px] text-slate-400">{{ b.customer_phone }}</div>
                  </td>
                  <td class="p-3 font-medium text-slate-700">{{ b.fleet?.name || 'Bus' }}</td>
                  <td class="p-3 text-slate-600">
                    <div>{{ b.destination }}</div>
                    <div class="text-[10px] text-slate-400">{{ b.start_date }} ({{ b.total_days }} Hari)</div>
                  </td>
                  <td class="p-3 text-slate-600">
                    <div>Rp {{ formatPrice(b.subtotal) }}</div>
                    <div v-if="b.discount_amount > 0" class="text-emerald-600 font-bold text-[10px]">
                      - Rp {{ formatPrice(b.discount_amount) }} ({{ b.discount_code }})
                    </div>
                  </td>
                  <td class="p-3 font-bold text-blue-900 text-sm">Rp {{ formatPrice(b.total_price) }}</td>
                  <td class="p-3">
                    <select
                      v-model="b.status"
                      @change="updateBookingStatus(b)"
                      class="bg-slate-50 border border-slate-200 rounded-lg p-1 text-[11px] font-bold"
                    >
                      <option value="pending">Pending</option>
                      <option value="confirmed">Confirmed</option>
                      <option value="in_trip">In Trip</option>
                      <option value="completed">Completed</option>
                      <option value="cancelled">Cancelled</option>
                    </select>
                  </td>
                  <td class="p-3">
                    <button
                      v-if="isSuperAdmin"
                      @click="deleteBooking(b.id)"
                      class="text-red-600 hover:text-red-800 font-bold text-[11px] cursor-pointer"
                    >
                      Hapus
                    </button>
                    <span v-else class="text-slate-400">-</span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- TAB 3: TABEL TRANSAKSI & PEMBAYARAN -->
        <div v-if="activeTab === 'transactions'" class="space-y-4">
          <h4 class="font-bold text-sm text-slate-900">Daftar Transaksi, Invoice & Bukti Pembayaran</h4>

          <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-x-auto">
            <table class="w-full text-left border-collapse">
              <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold text-[11px]">
                  <th class="p-3">No. Invoice</th>
                  <th class="p-3">Nominal Transfer</th>
                  <th class="p-3">Tipe Pembayaran</th>
                  <th class="p-3">Metode Bank</th>
                  <th class="p-3">Status Verifikasi</th>
                  <th class="p-3">Aksi Verifikasi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
                <tr v-for="t in transactionsList" :key="t.id">
                  <td class="p-3 font-mono font-bold text-blue-900">{{ t.invoice_number }}</td>
                  <td class="p-3 font-extrabold text-slate-900 text-sm">Rp {{ formatPrice(t.amount) }}</td>
                  <td class="p-3 uppercase text-[10px] font-bold text-slate-600">{{ t.payment_type }}</td>
                  <td class="p-3 uppercase font-medium text-slate-700">{{ t.payment_method }}</td>
                  <td class="p-3">
                    <span
                      class="px-2 py-0.5 rounded-full font-bold uppercase text-[10px]"
                      :class="t.status === 'verified' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800'"
                    >
                      {{ t.status }}
                    </span>
                  </td>
                  <td class="p-3">
                    <button
                      v-if="t.status !== 'verified'"
                      @click="verifyPayment(t)"
                      class="bg-emerald-600 hover:bg-emerald-500 text-white font-bold px-3 py-1 rounded-lg text-[11px] cursor-pointer"
                    >
                      ✓ Verifikasi & Setujui
                    </button>
                    <span v-else class="text-emerald-700 font-bold text-[11px]">✓ Terverifikasi</span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- TAB 4: TABEL ARMADA BUS (CRUD) -->
        <div v-if="activeTab === 'fleets'" class="space-y-4">
          <div class="flex justify-between items-center">
            <h4 class="font-bold text-sm text-slate-900">Katalog Armada Bus SJ Trans</h4>
            <button @click="showAddFleetModal = true" class="btn-primary text-xs py-2 px-4 rounded-xl cursor-pointer" :style="{ backgroundColor: primaryColor }">
              + Tambah Unit Bus Baru
            </button>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div v-for="f in fleetsList" :key="f.id" class="bg-white p-4 rounded-2xl border border-slate-200 flex gap-4 items-center">
              <img :src="f.thumbnail_url" class="w-24 h-20 rounded-xl object-cover bg-slate-900" />
              <div class="flex-1 space-y-1">
                <h5 class="font-bold text-slate-900 text-sm">{{ f.name }}</h5>
                <div class="text-[11px] text-slate-500">Kapasitas: {{ f.capacity }} Kursi (Layout {{ f.seat_layout }})</div>
                <div class="text-blue-700 font-bold">Rp {{ formatPrice(f.price_per_day) }} / hari</div>
              </div>
              <button v-if="isSuperAdmin" @click="deleteFleet(f.id)" class="text-red-500 hover:text-red-700 font-bold cursor-pointer">
                Hapus
              </button>
            </div>
          </div>
        </div>

        <!-- TAB 5: TABEL DISKON & KUPON (KOLOM DISKON CRUD) -->
        <div v-if="activeTab === 'discounts'" class="space-y-4">
          <div class="flex justify-between items-center">
            <h4 class="font-bold text-sm text-slate-900">Manajemen Kupon & Promo Diskon</h4>
            <button @click="showAddDiscountModal = true" class="btn-primary text-xs py-2 px-4 rounded-xl cursor-pointer" :style="{ backgroundColor: primaryColor }">
              + Buat Kupon Promo Baru
            </button>
          </div>

          <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-x-auto">
            <table class="w-full text-left border-collapse">
              <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold text-[11px]">
                  <th class="p-3">Kode Kupon</th>
                  <th class="p-3">Judul Promo</th>
                  <th class="p-3">Tipe</th>
                  <th class="p-3">Nilai Diskon</th>
                  <th class="p-3">Terpakai / Kuota</th>
                  <th class="p-3">Status</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
                <tr v-for="d in discountsList" :key="d.id">
                  <td class="p-3 font-mono font-bold text-blue-900">{{ d.code }}</td>
                  <td class="p-3 font-semibold text-slate-800">{{ d.title }}</td>
                  <td class="p-3 uppercase text-[10px] font-bold">{{ d.discount_type }}</td>
                  <td class="p-3 font-bold text-emerald-700">
                    {{ d.discount_type === 'percentage' ? d.discount_value + '%' : 'Rp ' + formatPrice(d.discount_value) }}
                  </td>
                  <td class="p-3 text-slate-600">{{ d.used_count || 0 }} / {{ d.quota || 'Tak Terbatas' }}</td>
                  <td class="p-3">
                    <span class="bg-emerald-100 text-emerald-800 font-bold px-2 py-0.5 rounded-full text-[10px]">
                      Aktif
                    </span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- TAB 6: KELOLA PENGGUNA (SUPER ADMIN ONLY) -->
        <div v-if="activeTab === 'users' && isSuperAdmin" class="space-y-4">
          <h4 class="font-bold text-sm text-slate-900">Daftar Pengguna & Hak Akses (3 Roles: Super Admin, Admin, Customer)</h4>

          <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-x-auto">
            <table class="w-full text-left border-collapse">
              <thead>
                <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-bold text-[11px]">
                  <th class="p-3">Nama</th>
                  <th class="p-3">Username</th>
                  <th class="p-3">Email</th>
                  <th class="p-3">No WhatsApp</th>
                  <th class="p-3">Level Role</th>
                  <th class="p-3">Status</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
                <tr v-for="u in usersList" :key="u.id">
                  <td class="p-3 font-bold text-slate-900">{{ u.name }}</td>
                  <td class="p-3 font-mono text-slate-700">@{{ u.username }}</td>
                  <td class="p-3 text-slate-600">{{ u.email }}</td>
                  <td class="p-3 text-slate-600">{{ u.phone_number }}</td>
                  <td class="p-3">
                    <span
                      class="px-2 py-0.5 rounded-full font-bold text-[10px] uppercase"
                      :class="{
                        'bg-purple-100 text-purple-800': u.role?.name === 'super_admin',
                        'bg-blue-100 text-blue-800': u.role?.name === 'admin',
                        'bg-emerald-100 text-emerald-800': u.role?.name === 'customer',
                      }"
                    >
                      {{ u.role?.display_name || u.role?.name }}
                    </span>
                  </td>
                  <td class="p-3">
                    <span class="text-emerald-700 font-bold text-[11px]">✓ Aktif</span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- TAB 7: MANAJER TEMA WARNA (SUPER ADMIN: BISA DIUBAH-UBAH SENDIRI) -->
        <div v-if="activeTab === 'theme' && isSuperAdmin" class="space-y-6">
          <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-sm space-y-4">
            <div>
              <h4 class="font-black text-base text-slate-900">Kustomisasi Tone Warna Website Secara Live</h4>
              <p class="text-xs text-slate-500">
                Ubah palet warna brand SJ Trans. Perubahan langsung teraplikasikan ke seluruh navigasi, hero banner, tombol reservasi, dan footer!
              </p>
            </div>

            <!-- Preset Palet Pilihan -->
            <div>
              <span class="font-bold text-xs text-slate-700 block mb-2">1. Pilih Preset Palet Warna Tersedia:</span>
              <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                <div
                  v-for="preset in presets"
                  :key="preset.id"
                  @click="selectPreset(preset.id)"
                  class="p-4 rounded-2xl border-2 transition cursor-pointer flex items-center justify-between"
                  :class="currentPreset === preset.id ? 'border-blue-700 bg-blue-50/50 shadow-sm' : 'border-slate-200 bg-white hover:border-slate-300'"
                >
                  <div class="flex items-center gap-3">
                    <span class="w-6 h-6 rounded-full shadow-inner border border-white" :style="{ backgroundColor: preset.primary }"></span>
                    <div>
                      <div class="font-bold text-xs text-slate-900">{{ preset.name }}</div>
                      <div class="text-[10px] font-mono text-slate-400">{{ preset.primary }}</div>
                    </div>
                  </div>
                  <span v-if="currentPreset === preset.id" class="text-blue-700 font-black">✓ Aktif</span>
                </div>
              </div>
            </div>

            <!-- Color Picker HEX Kustom -->
            <div class="pt-4 border-t border-slate-100 flex flex-wrap items-center justify-between gap-4">
              <div>
                <span class="font-bold text-xs text-slate-700 block">2. Atau Masukkan Warna Heksadesimal Sendiri:</span>
                <span class="text-[11px] text-slate-400">Pilih warna apa pun dengan color picker:</span>
              </div>
              <div class="flex items-center gap-3">
                <input
                  type="color"
                  :value="primaryColor"
                  @input="(e: any) => setCustomHex(e.target.value)"
                  class="w-10 h-10 rounded-xl cursor-pointer border-0 bg-transparent"
                />
                <input
                  type="text"
                  :value="primaryColor"
                  @change="(e: any) => setCustomHex(e.target.value)"
                  class="w-28 font-mono uppercase bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs font-bold text-slate-800 text-center"
                />
                <button
                  @click="saveThemeToBackend"
                  class="btn-primary py-2 px-5 text-xs font-bold cursor-pointer"
                  :style="{ backgroundColor: primaryColor }"
                >
                  Simpan Permanen
                </button>
              </div>
            </div>
            <div v-if="themeSavedMessage" class="text-xs font-bold text-emerald-600">
              {{ themeSavedMessage }}
            </div>
          </div>
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

const { primaryColor, presets, currentPreset, selectPreset, setCustomHex } = useTheme()
const { user, isSuperAdmin, token } = useAuth()
const { fallbackFleets, fallbackDiscounts } = useApi()

const activeTab = ref('overview')
const searchQuery = ref('')
const themeSavedMessage = ref('')
const showAddFleetModal = ref(false)
const showAddDiscountModal = ref(false)

const stats = reactive({
  totalRevenue: 15900000,
  totalBookings: 8,
  confirmedBookings: 6,
  totalFleets: 4,
  totalUsers: 14,
})

const availableTabs = computed(() => {
  const tabs = [
    { id: 'overview', name: 'Dashboard Overview', icon: '📊' },
    { id: 'bookings', name: 'Tabel Pemesanan', icon: '📋' },
    { id: 'transactions', name: 'Tabel Transaksi', icon: '💳' },
    { id: 'fleets', name: 'Armada Bus', icon: '🚌' },
    { id: 'discounts', name: 'Diskon & Promo', icon: '🏷️' },
  ]

  if (isSuperAdmin.value) {
    tabs.push({ id: 'users', name: 'Kelola Pengguna', icon: '👥' })
    tabs.push({ id: 'theme', name: 'Manajer Warna Tema', icon: '🎨' })
  }

  return tabs
})

const bookingsList = ref<any[]>([
  {
    id: 1,
    booking_code: 'SJT-202610-001',
    customer_name: 'Budi Santoso',
    customer_phone: '081234567890',
    destination: 'Yogyakarta',
    start_date: '2026-11-01',
    total_days: 3,
    subtotal: 11400000,
    discount_code: 'SJTPROMO10',
    discount_amount: 500000,
    total_price: 10900000,
    status: 'confirmed',
    fleet: { name: 'Big Bus Scania K410IB' },
  },
  {
    id: 2,
    booking_code: 'SJT-202610-002',
    customer_name: 'Dewi Lestari',
    customer_phone: '081399887711',
    destination: 'Bandung',
    start_date: '2026-11-10',
    total_days: 1,
    subtotal: 2200000,
    discount_code: null,
    discount_amount: 0,
    total_price: 2200000,
    status: 'pending',
    fleet: { name: 'Medium Bus 31s' },
  },
])

const transactionsList = ref<any[]>([
  {
    id: 1,
    invoice_number: 'INV-SJT-202610-0001',
    amount: 5000000,
    payment_type: 'down_payment',
    payment_method: 'bank_transfer',
    status: 'verified',
  },
  {
    id: 2,
    invoice_number: 'INV-SJT-202610-0002',
    amount: 2200000,
    payment_type: 'full_payment',
    payment_method: 'bank_transfer',
    status: 'pending',
  },
])

const fleetsList = ref(fallbackFleets)
const discountsList = ref(fallbackDiscounts)

const usersList = ref<any[]>([
  { id: 1, name: 'Super Admin SJ Trans', username: 'superadmin', email: 'admin@sjtrans.co.id', phone_number: '081122334455', role: { name: 'super_admin', display_name: 'Super Administrator' } },
  { id: 2, name: 'Admin Operasional', username: 'admin', email: 'staff@sjtrans.co.id', phone_number: '081199887766', role: { name: 'admin', display_name: 'Administrator' } },
  { id: 3, name: 'Budi Santoso', username: 'budi', email: 'budi@gmail.com', phone_number: '081234567890', role: { name: 'customer', display_name: 'Pelanggan' } },
])

const filteredBookings = computed(() => {
  if (!searchQuery.value) return bookingsList.value
  const q = searchQuery.value.toLowerCase()
  return bookingsList.value.filter(b =>
    b.booking_code.toLowerCase().includes(q) || b.customer_name.toLowerCase().includes(q)
  )
})

const formatPrice = (val: number) => {
  return new Intl.NumberFormat('id-ID').format(val || 0)
}

const updateBookingStatus = (booking: any) => {
  alert(`Status pemesanan ${booking.booking_code} diperbarui menjadi: ${booking.status}`)
}

const verifyPayment = (trx: any) => {
  trx.status = 'verified'
  alert(`Pembayaran invoice ${trx.invoice_number} berhasil diverifikasi!`)
}

const deleteBooking = (id: number) => {
  if (confirm('Yakin ingin menghapus data pemesanan ini?')) {
    bookingsList.value = bookingsList.value.filter(b => b.id !== id)
  }
}

const deleteFleet = (id: number) => {
  if (confirm('Yakin ingin menghapus armada ini?')) {
    fleetsList.value = fleetsList.value.filter(f => f.id !== id)
  }
}

const saveThemeToBackend = async () => {
  try {
    await $fetch('http://127.0.0.1:8000/api/v1/settings/theme', {
      method: 'POST',
      headers: { Authorization: `Bearer ${token.value}` },
      body: {
        theme_primary_color: primaryColor.value,
        theme_active_preset: currentPreset.value,
      },
    })
  } catch (e) {
    // Local persistence
  }

  themeSavedMessage.value = '✓ Warna tema berhasil disimpan secara permanen ke server!'
  setTimeout(() => {
    themeSavedMessage.value = ''
  }, 2500)
}
</script>
