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
                      'bg-amber-100 text-amber-800': order.payment_status === 'Menunggu Pembayaran' || order.payment_status === 'unpaid',
                      'bg-blue-100 text-blue-800': order.payment_status?.includes('DP') || order.payment_status === 'down_payment' || order.payment_status === 'dp_paid',
                      'bg-emerald-100 text-emerald-800': order.payment_status === 'Lunas' || order.payment_status === 'paid',
                    }"
                  >
                    ● {{ formatPaymentStatusLabel(order.payment_status) }}
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
            <label class="font-bold text-slate-700 block mb-1">Pilihan Pembayaran</label>
            <select
              v-model="uploadPaymentType"
              @change="handlePaymentTypeChange"
              class="w-full p-2.5 border border-slate-200 rounded-xl text-xs font-bold bg-white text-slate-800"
            >
              <option value="down_payment">DP 50% (Uang Muka) - Rp {{ (selectedOrderForUpload.total_price * 0.5).toLocaleString('id-ID') }}</option>
              <option value="full_payment">Pelunasan Penuh (100%) - Rp {{ selectedOrderForUpload.total_price.toLocaleString('id-ID') }}</option>
              <option value="remaining">Sisa Pelunasan (50%) - Rp {{ (selectedOrderForUpload.total_price * 0.5).toLocaleString('id-ID') }}</option>
            </select>
            <span class="text-[10px] text-slate-500 mt-1 block">
              {{ uploadPaymentType === 'down_payment' ? 'Bayar 50% sebagai tanda jadi. Sisa 50% dapat dilunasi saat keberangkatan.' : 'Pelunasan 100% langsung untuk reservasi bus.' }}
            </span>
          </div>

          <div>
            <label class="font-bold text-slate-700 block mb-1">Bank Pengirim Anda</label>
            <input v-model="uploadBank" type="text" placeholder="Contoh: BCA / Mandiri / BNI" class="w-full p-2.5 border border-slate-200 rounded-xl text-xs" />
          </div>

          <div>
            <label class="font-bold text-slate-700 block mb-1">Nominal yang Ditransfer</label>
            <input v-model="uploadNominal" type="text" class="w-full p-2.5 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 bg-slate-50" />
          </div>

          <div>
            <label class="font-bold text-slate-700 block mb-1">Foto Bukti Transfer (Resi/Struk)</label>
            <input
              type="file"
              accept="image/*,.pdf"
              @change="handleFileChange"
              class="w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 cursor-pointer"
            />
            <div v-if="uploadedFileName" class="mt-1.5 text-[11px] font-semibold text-emerald-600 flex items-center gap-1">
              <span>✓ Berkas:</span>
              <span class="font-mono text-slate-700 truncate">{{ uploadedFileName }}</span>
            </div>
          </div>
        </div>

        <button
          @click="submitProof"
          :disabled="isUploadingProof"
          class="btn-primary w-full py-2.5 rounded-xl font-bold text-xs text-center justify-center cursor-pointer shadow-md disabled:opacity-50"
          :style="{ backgroundColor: primaryColor }"
        >
          <span v-if="isUploadingProof" class="flex items-center justify-center gap-2">
            <svg class="animate-spin h-3.5 w-3.5 text-white" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Mengunggah Bukti Pembayaran...
          </span>
          <span v-else>Kirim Bukti Pembayaran</span>
        </button>
      </div>
    </div>

    <!-- E-Tiket / Invoice Modal Preview (KAI Boarding Pass Format) -->
    <div
      v-if="selectedOrderForInvoice"
      class="fixed inset-0 z-50 flex items-center justify-center p-3 sm:p-4 bg-black/75 backdrop-blur-xs animate-fade overflow-y-auto"
    >
      <div class="bg-white max-w-xl w-full p-5 sm:p-7 shadow-2xl border-2 border-slate-900 rounded-none sm:rounded-2xl max-h-[92vh] overflow-y-auto relative font-sans text-slate-900 print:max-h-none print:shadow-none print:border-none print:m-0 print:p-0">
        <button
          @click="selectedOrderForInvoice = null"
          class="absolute top-4 right-4 text-slate-400 hover:text-slate-900 text-sm font-bold print:hidden cursor-pointer"
        >
          ✕
        </button>

        <!-- KAI-STYLE OFFICIAL BOARDING PASS -->
        <div id="kai-printable-ticket" class="space-y-4">
          <!-- 1. Header Resmi -->
          <div class="border-b-2 border-slate-900 pb-3 flex flex-col sm:flex-row justify-between items-start gap-2">
            <div>
              <div class="text-base font-black tracking-wider uppercase text-slate-900 font-mono">
                PT SEMESTA JAYA TRANSPORT
              </div>
              <div class="text-[10px] text-slate-600 font-medium tracking-tight">
                IZIN RESMI KEMENHUB: SK.00492/AJ.301/DJPD/2026 | ASURANSI JASA RAHARJA
              </div>
            </div>
            <div class="sm:text-right">
              <span class="inline-block border-2 border-slate-900 px-2.5 py-0.5 text-xs font-black uppercase tracking-wider bg-slate-100 font-mono">
                BOARDING PASS
              </span>
              <div class="text-[9px] text-slate-500 mt-0.5">BUKTI RESERVASI & PEMBAYARAN SAH</div>
            </div>
          </div>

          <!-- 2. Barcode & Kode Booking Prominen (Seperti Tiket KAI) -->
          <div class="bg-slate-50 border-2 border-slate-900 p-3.5 flex flex-col sm:flex-row justify-between items-center gap-3">
            <div>
              <span class="text-[10px] font-bold text-slate-500 uppercase block tracking-wider">KODE BOOKING / BOOKING CODE</span>
              <span class="text-2xl font-black tracking-widest text-slate-900 font-mono">{{ selectedOrderForInvoice.booking_code }}</span>
            </div>
            <!-- Barcode Simulation -->
            <div class="text-center">
              <div class="font-mono text-xl tracking-[0.25em] font-black select-none text-slate-900 leading-none">
                |||| | ||||| ||| |||| | ||||| || ||||
              </div>
              <span class="text-[9px] font-mono text-slate-500 tracking-widest block mt-1">VERIFIED OFFICIAL TICKET</span>
            </div>
          </div>

          <!-- 3. Rincian Jadwal & Perjalanan -->
          <div class="border-2 border-slate-900 divide-y-2 divide-slate-900 text-xs font-mono">
            <!-- Row 1: Penumpang & Kontak -->
            <div class="grid grid-cols-1 sm:grid-cols-2 divide-y sm:divide-y-0 sm:divide-x-2 divide-slate-900">
              <div class="p-3">
                <span class="text-[9px] font-sans text-slate-500 uppercase font-bold block">NAMA PEMESAN / PENUMPANG:</span>
                <span class="font-bold text-slate-900 text-sm tracking-wide">{{ user?.name || selectedOrderForInvoice.customer_name || 'Pelanggan SJ Trans' }}</span>
              </div>
              <div class="p-3">
                <span class="text-[9px] font-sans text-slate-500 uppercase font-bold block">NO. KONTAK / IDENTITAS:</span>
                <span class="font-bold text-slate-900 tracking-wide">{{ user?.phone_number || selectedOrderForInvoice.customer_phone || '0812-3456-7890' }}</span>
              </div>
            </div>

            <!-- Row 2: Rute & Waktu -->
            <div class="grid grid-cols-1 sm:grid-cols-2 divide-y sm:divide-y-0 sm:divide-x-2 divide-slate-900 bg-slate-50/50">
              <div class="p-3">
                <span class="text-[9px] font-sans text-slate-500 uppercase font-bold block">KEBERANGKATAN (ASAL):</span>
                <span class="font-bold text-slate-900 text-sm block">{{ selectedOrderForInvoice.origin }}</span>
                <span class="text-[10px] text-slate-600 block mt-0.5">Tgl: <strong>{{ selectedOrderForInvoice.start_date }}</strong></span>
              </div>
              <div class="p-3">
                <span class="text-[9px] font-sans text-slate-500 uppercase font-bold block">TUJUAN (DESTINASI):</span>
                <span class="font-bold text-slate-900 text-sm block">{{ selectedOrderForInvoice.destination }}</span>
                <span class="text-[10px] text-slate-600 block mt-0.5">S/D: <strong>{{ selectedOrderForInvoice.end_date }}</strong> ({{ selectedOrderForInvoice.days }} Hari)</span>
              </div>
            </div>

            <!-- Row 3: Armada & Kapasitas -->
            <div class="grid grid-cols-1 sm:grid-cols-2 divide-y sm:divide-y-0 sm:divide-x-2 divide-slate-900">
              <div class="p-3">
                <span class="text-[9px] font-sans text-slate-500 uppercase font-bold block">ARMADA BUS & LAYANAN:</span>
                <span class="font-bold text-slate-900 text-sm block">{{ selectedOrderForInvoice.fleet_name }}</span>
                <span class="text-[10px] text-slate-600 font-sans block mt-0.5">Kapasitas: {{ selectedOrderForInvoice.seats }} Kursi | {{ selectedOrderForInvoice.units }} Unit</span>
              </div>
              <div class="p-3">
                <span class="text-[9px] font-sans text-slate-500 uppercase font-bold block">STATUS PEMBAYARAN:</span>
                <span
                  class="font-black text-sm block"
                  :class="selectedOrderForInvoice.payment_status === 'Lunas' ? 'text-emerald-700' : 'text-blue-700'"
                >
                  {{ selectedOrderForInvoice.payment_status === 'Lunas' ? 'LUNAS (VERIFIED PAID)' : (selectedOrderForInvoice.payment_status?.includes('DP') ? 'DP 50% DITERIMA (VERIFIED DP)' : selectedOrderForInvoice.payment_status) }}
                </span>
                <div class="text-xs font-mono mt-1 space-y-0.5">
                  <div class="text-slate-900 font-bold">Total: Rp {{ selectedOrderForInvoice.total_price.toLocaleString() }}</div>
                  <div v-if="selectedOrderForInvoice.payment_status?.includes('DP')" class="text-emerald-700 text-[11px] font-bold">
                    Terbayar (DP 50%): Rp {{ (selectedOrderForInvoice.total_price * 0.5).toLocaleString() }}
                  </div>
                  <div v-if="selectedOrderForInvoice.payment_status?.includes('DP')" class="text-amber-700 text-[11px] font-bold">
                    Sisa Pelunasan: Rp {{ (selectedOrderForInvoice.total_price * 0.5).toLocaleString() }} saat berangkat
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- 4. Garis Perforasi / Potongan Kertas -->
          <div class="relative py-2">
            <div class="border-b-2 border-dashed border-slate-400"></div>
            <div class="absolute inset-x-0 -top-1 flex justify-between text-[9px] text-slate-400 font-mono uppercase px-2">
              <span>GUNTING DISINI</span>
              <span>SJ TRANS TICKET PERFORATION</span>
            </div>
          </div>

          <!-- 5. Ketentuan Tiket ala KAI -->
          <div class="text-[9px] text-slate-600 font-sans leading-relaxed space-y-1 bg-slate-50 p-3 rounded-lg border border-slate-200">
            <div class="font-bold text-slate-900 uppercase">KETENTUAN PENTING PERJALANAN:</div>
            <p>1. Boarding Pass ini merupakan tanda bukti sah penyewaan armada bus pariwisata PT Semesta Jaya Transport.</p>
            <p>2. Tunjukkan lembar cetak atau salinan digital tiket ini kepada kru pengemudi saat penjemputan di lokasi.</p>
            <p>3. Seluruh penumpang terdaftar terlindungi oleh asuransi kecelakaan penumpang PT Jasa Raharja (Persero).</p>
            <p>4. Kru pengemudi dan armada bus akan tiba di titik penjemputan minimal 30 menit sebelum jadwal keberangkatan.</p>
            <p>5. Hotline Bantuan Operasional & Call Center 24 Jam: 1500-888 / WhatsApp Resmi SJ Trans.</p>
          </div>

          <!-- 6. Tombol Aksi (Hidden saat Print) -->
          <div class="flex gap-2 pt-2 print:hidden">
            <button
              @click="printTicket"
              class="flex-1 py-3 px-4 bg-slate-900 hover:bg-slate-800 text-white font-mono font-bold text-xs uppercase tracking-wider transition rounded-xl cursor-pointer shadow-md flex items-center justify-center gap-2"
            >
              <span>🖨️</span>
              <span>Cetak Tiket Boarding Pass</span>
            </button>
            <button
              @click="selectedOrderForInvoice = null"
              class="py-3 px-5 border-2 border-slate-300 hover:border-slate-800 text-slate-700 font-mono font-bold text-xs uppercase transition rounded-xl cursor-pointer"
            >
              Tutup
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
const router = useRouter()
const { primaryColor } = useTheme()
const { user, isLoggedIn, token, login, logout } = useAuth()
const { locale, setLocale, t } = useI18n()
const { showModal, showToast } = useNotification()

const customerTab = ref<'orders' | 'profile'>('orders')
const selectedOrderForUpload = ref<any>(null)
const selectedOrderForInvoice = ref<any>(null)
const uploadPaymentType = ref<'down_payment' | 'full_payment' | 'remaining'>('down_payment')
const uploadBank = ref('')
const uploadNominal = ref('')
const uploadedFileName = ref('')
const isUploadingProof = ref(false)
const isLoadingBookings = ref(false)

const formatPaymentStatusLabel = (status: string) => {
  if (status === 'paid' || status === 'Lunas') return 'Lunas'
  if (status === 'down_payment' || status === 'dp_paid' || status === 'DP Terbayar (30%)' || status === 'DP Terbayar (50%)' || status?.includes('DP')) return 'DP Terbayar (50%)'
  return 'Menunggu Pembayaran'
}

const handlePaymentTypeChange = () => {
  if (!selectedOrderForUpload.value) return
  const total = selectedOrderForUpload.value.total_price || 0
  if (uploadPaymentType.value === 'down_payment' || uploadPaymentType.value === 'remaining') {
    uploadNominal.value = `Rp ${(total * 0.5).toLocaleString('id-ID')}`
  } else {
    uploadNominal.value = `Rp ${total.toLocaleString('id-ID')}`
  }
}

const handleFileChange = (e: any) => {
  const file = e.target.files?.[0]
  if (file) {
    uploadedFileName.value = file.name
  } else {
    uploadedFileName.value = ''
  }
}

const myBookings = ref<any[]>([])

// Fetch riwayat pemesanan secara dinamis dari API Laravel & Local Storage
const fetchUserBookings = async () => {
  isLoadingBookings.value = true
  const list: any[] = []

  // 1. Ambil dari API Laravel jika ada token auth
  if (token.value) {
    try {
      const res: any = await $fetch('http://127.0.0.1:8000/api/v1/my-bookings', {
        headers: {
          Accept: 'application/json',
          Authorization: `Bearer ${token.value}`,
        },
      })
      const backendData = res.data?.data || res.data || []
      if (Array.isArray(backendData)) {
        backendData.forEach((b: any) => {
          list.push({
            id: b.id,
            booking_code: b.booking_code,
            customer_name: b.customer_name || user.value?.name,
            customer_phone: b.customer_phone || user.value?.phone_number,
            customer_email: b.customer_email || user.value?.email,
            fleet_name: b.fleet?.name || b.fleet_name || 'Armada SJ Trans',
            seats: b.fleet?.capacity || b.seats || 50,
            units: b.total_units || 1,
            origin: b.pickup_location || b.origin || 'Jakarta',
            destination: b.destination || 'Bandung',
            start_date: b.start_date,
            end_date: b.end_date,
            days: b.total_days || 1,
            total_price: b.total_price || 0,
            payment_status: b.payment_status === 'paid' || b.payment_status === 'Lunas' ? 'Lunas' : (b.payment_status === 'dp_paid' ? 'DP Terbayar (30%)' : 'Menunggu Pembayaran'),
            status: b.status || 'pending',
          })
        })
      }
    } catch (e) {
      console.warn('API my-bookings fetch skipped or failed:', e)
    }
  }

  // 2. Gabungkan dengan pesanan yang tersimpan di Local Storage
  if (typeof localStorage !== 'undefined') {
    try {
      const localList = JSON.parse(localStorage.getItem('sjtrans_local_bookings') || '[]')
      if (Array.isArray(localList)) {
        localList.forEach((lb: any) => {
          const exists = list.some(item => item.booking_code === lb.booking_code)
          const isUserMatch = !user.value?.email ||
            (lb.customer_email && lb.customer_email.toLowerCase() === user.value.email.toLowerCase()) ||
            (lb.customer_name && lb.customer_name.toLowerCase() === user.value.name?.toLowerCase()) ||
            lb.user_id === user.value?.id ||
            !lb.customer_email

          if (!exists && isUserMatch) {
            list.push({
              id: lb.id || Date.now() + Math.random(),
              booking_code: lb.booking_code,
              customer_name: lb.customer_name || user.value?.name || 'Pelanggan SJ Trans',
              customer_phone: lb.customer_phone || user.value?.phone_number || '0812-3456-7890',
              customer_email: lb.customer_email || user.value?.email,
              fleet_name: lb.fleet?.name || lb.fleet_name || 'Armada Bus SJ Trans',
              seats: lb.fleet?.capacity || lb.seats || 50,
              units: lb.total_units || lb.units || 1,
              origin: lb.pickup_location || lb.origin || 'Jakarta',
              destination: lb.destination || 'Bandung',
              start_date: lb.start_date,
              end_date: lb.end_date,
              days: lb.total_days || lb.days || 1,
              total_price: lb.total_price || 0,
              payment_status: lb.payment_status === 'paid' || lb.payment_status === 'Lunas' ? 'Lunas' : (lb.payment_status === 'dp_paid' || lb.payment_status === 'DP Terbayar (30%)' ? 'DP Terbayar (30%)' : 'Menunggu Pembayaran'),
              status: lb.status || 'pending',
            })
          }
        })
      }
    } catch (e) {
      console.error('Failed to parse local bookings:', e)
    }
  }

  // 3. Fallback demo data khusus untuk user demo Budi jika belum ada pesanan sama sekali
  if (list.length === 0 && user.value?.username === 'budi') {
    list.push(
      {
        id: 1,
        booking_code: 'SJT-20261028-001',
        customer_name: 'Budi Santoso',
        customer_phone: '0812-3456-7890',
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
        status: 'pending',
      },
      {
        id: 2,
        booking_code: 'SJT-20261015-082',
        customer_name: 'Budi Santoso',
        customer_phone: '0812-3456-7890',
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
        status: 'completed',
      }
    )
  }

  myBookings.value = list
  isLoadingBookings.value = false
}

onMounted(() => {
  fetchUserBookings()
})

watch(() => user.value?.id, () => {
  fetchUserBookings()
})

const quickCustomerLogin = async () => {
  await login('budi', 'password123')
  fetchUserBookings()
}

const handleLogout = async () => {
  await logout()
  router.push('/')
}

const openUploadModal = (order: any) => {
  selectedOrderForUpload.value = order
  uploadPaymentType.value = order.payment_status?.includes('DP') ? 'remaining' : 'down_payment'
  uploadBank.value = ''
  uploadedFileName.value = ''
  handlePaymentTypeChange()
}

const openInvoiceModal = (order: any) => {
  selectedOrderForInvoice.value = order
}

const printTicket = () => {
  if (typeof window !== 'undefined') {
    window.print()
  }
}

const submitProof = () => {
  if (!selectedOrderForUpload.value) return

  // 1. Validasi: jika bukti transfer atau nama bank kosong, tampilkan modal NOTIFIKASI GAGAL
  if (!uploadBank.value.trim() || !uploadedFileName.value) {
    showModal({
      type: 'error',
      title: 'Bukti Pembayaran Gagal Diunggah',
      message: 'Mohon masukkan nama bank pengirim dan lampirkan foto struk/resi transfer yang valid sebelum mengirim.',
      buttonText: 'Coba Lagi',
    })
    return
  }

  isUploadingProof.value = true

  // Simulasi proses upload dengan feedback responsif
  setTimeout(() => {
    isUploadingProof.value = false
    const order = selectedOrderForUpload.value
    const isDP = uploadPaymentType.value === 'down_payment'
    const isRemainingOrFull = uploadPaymentType.value === 'full_payment' || uploadPaymentType.value === 'remaining'
    const newStatus = isRemainingOrFull ? 'Lunas' : 'DP Terbayar (50%)'
    order.payment_status = newStatus
    const bookingCode = order.booking_code

    // Sinkronisasi status ke localStorage agar permanen
    if (typeof localStorage !== 'undefined') {
      try {
        const localList = JSON.parse(localStorage.getItem('sjtrans_local_bookings') || '[]')
        const target = localList.find((b: any) => b.booking_code === bookingCode)
        if (target) {
          target.payment_status = newStatus
          localStorage.setItem('sjtrans_local_bookings', JSON.stringify(localList))
        }
      } catch (e) {}
    }

    selectedOrderForUpload.value = null

    // 2. Tampilkan modal NOTIFIKASI BERHASIL berpenampilan modern
    showModal({
      type: 'success',
      title: 'Bukti Pembayaran Anda Berhasil Diunggah!',
      message: isDP
        ? `Terima kasih! Bukti transfer DP 50% untuk kode booking ${bookingCode} telah diterima dan diverifikasi. Sisa pelunasan 50% dapat dibayarkan sebelum atau saat jadwal keberangkatan bus.`
        : `Terima kasih! Bukti transfer pelunasan untuk kode booking ${bookingCode} telah diterima dan diverifikasi. Status pesanan Anda kini Lunas dan tiket resmi telah diterbitkan.`,
      buttonText: 'Lihat Tiket Resmi',
      onConfirm: () => {
        openInvoiceModal(order)
      },
      secondaryButtonText: 'Tutup',
    })

    showToast('success', 'Pembayaran Terverifikasi', `Status pemesanan ${bookingCode} telah diperbarui menjadi ${newStatus}.`)
  }, 700)
}
</script>

<style scoped>
@media print {
  /* Sembunyikan elemen latar belakang dan tampilkan boarding pass bersih */
  :deep(header), :deep(footer), :deep(nav), :deep(aside) {
    display: none !important;
  }
  #kai-printable-ticket {
    visibility: visible !important;
    position: relative !important;
    left: 0 !important;
    top: 0 !important;
    width: 100% !important;
    background: #ffffff !important;
    color: #000000 !important;
  }
}
</style>
