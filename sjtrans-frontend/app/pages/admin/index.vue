<template>
  <div class="min-h-screen bg-slate-100 flex flex-col pb-16 md:pb-0">
    <!-- Auth Guard Check: If not logged in or not admin -->
    <div v-if="!isLoggedIn || !isAdmin" class="min-h-screen flex items-center justify-center p-4">
      <div class="max-w-md w-full bg-white rounded-3xl p-8 border border-slate-200 shadow-xl text-center">
        <div class="w-16 h-16 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center text-3xl mx-auto mb-4">
          🔒
        </div>
        <h2 class="text-xl font-black text-slate-900 mb-2">Akses Terbatas Admin</h2>
        <p class="text-xs text-slate-500 mb-6 leading-relaxed">
          Halaman ini khusus untuk staf Admin Operasional dan Super Admin SJ Trans. Silakan masuk dengan akun yang memiliki hak akses.
        </p>

        <!-- Manual Login Form -->
        <form @submit.prevent="handleManualLogin" class="space-y-3.5 text-left mb-5">
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Username atau Email Admin</label>
            <input
              v-model="manualLoginForm.login"
              type="text"
              required
              placeholder="Contoh: superadmin atau admin@sjtrans.co.id"
              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 font-medium focus:ring-2 focus:ring-blue-600 focus:outline-none"
            />
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-700 mb-1">Password</label>
            <input
              v-model="manualLoginForm.password"
              type="password"
              required
              placeholder="Masukkan password admin"
              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 font-medium focus:ring-2 focus:ring-blue-600 focus:outline-none"
            />
          </div>

          <div v-if="loginErrorMessage" class="p-2.5 rounded-xl bg-red-50 border border-red-200 text-red-600 font-semibold text-xs">
            {{ loginErrorMessage }}
          </div>

          <button
            type="submit"
            :disabled="isLoggingIn"
            class="w-full py-3 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs transition shadow-md cursor-pointer disabled:opacity-50"
          >
            <span v-if="isLoggingIn">Memverifikasi Akun...</span>
            <span v-else>Masuk ke Panel Admin</span>
          </button>
        </form>

        <div class="relative flex py-2 items-center mb-4">
          <div class="flex-grow border-t border-slate-200"></div>
          <span class="flex-shrink mx-3 text-[10px] font-bold text-slate-400 uppercase tracking-wider">Atau 1-Click Login Cepat</span>
          <div class="flex-grow border-t border-slate-200"></div>
        </div>

        <!-- Demo 1-Click Login Helper -->
        <div class="bg-slate-50 rounded-2xl p-4 border border-slate-200 mb-6 text-left space-y-2.5">
          <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Pilih Akun Demo Admin:</div>
          <button
            @click="quickLogin('superadmin', 'password123')"
            class="w-full py-2.5 px-4 rounded-xl bg-purple-600 hover:bg-purple-700 text-white hover:text-white text-xs font-bold transition flex items-center justify-between cursor-pointer shadow-sm"
          >
            <span class="text-white">👑 Login sebagai Super Admin</span>
            <span class="text-[10px] bg-purple-800 text-white px-2 py-0.5 rounded font-black">Penuh</span>
          </button>
          <button
            @click="quickLogin('admin', 'password123')"
            class="w-full py-2.5 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white hover:text-white text-xs font-bold transition flex items-center justify-between cursor-pointer shadow-sm"
          >
            <span class="text-white">⚙️ Login sebagai Admin Operasional</span>
            <span class="text-[10px] bg-blue-800 text-white px-2 py-0.5 rounded font-black">Operasional</span>
          </button>
        </div>

        <NuxtLink to="/" class="text-xs font-bold text-slate-500 hover:text-blue-700 transition inline-block mt-2">
          ← Kembali ke Website Utama
        </NuxtLink>
      </div>
    </div>

    <!-- ADMIN & SUPER ADMIN DEDICATED PANEL LAYOUT -->
    <div v-else class="min-h-screen flex flex-col">
      <!-- Admin Top Navigation Bar -->
      <header
        class="text-white px-4 sm:px-6 py-3 flex items-center justify-between shadow-md transition-all duration-300"
        :class="isSuperAdmin ? 'bg-gradient-to-r from-purple-950 via-slate-900 to-purple-950 border-b border-purple-800/40' : 'bg-gradient-to-r from-slate-900 via-blue-950 to-slate-900 border-b border-blue-900/40'"
      >
        <div class="flex items-center gap-3">
          <!-- Mobile Sidebar Toggle -->
          <button
            @click="mobileSidebarOpen = !mobileSidebarOpen"
            class="lg:hidden p-1.5 rounded-xl bg-white/10 hover:bg-white/20 text-white transition cursor-pointer"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" /></svg>
          </button>

          <NuxtLink to="/" class="flex items-center gap-2 group">
            <div
              class="w-8 h-8 rounded-xl flex items-center justify-center font-black text-sm text-white shadow-sm"
              :class="isSuperAdmin ? 'bg-purple-600' : 'bg-blue-600'"
            >
              SJ
            </div>
            <span class="text-sm font-black tracking-tight text-white hidden sm:inline">SJTRANS <span class="text-slate-400 font-normal">| Admin Center</span></span>
          </NuxtLink>

          <!-- Distinct Role Badge -->
          <div
            class="px-2.5 py-0.5 rounded-full text-[11px] font-black uppercase tracking-wider flex items-center gap-1 shadow-xs"
            :class="isSuperAdmin ? 'bg-purple-500/30 text-purple-200 border border-purple-400/50' : 'bg-blue-500/30 text-blue-200 border border-blue-400/50'"
          >
            <span>{{ isSuperAdmin ? '👑' : '⚙️' }}</span>
            <span>{{ isSuperAdmin ? t('admin.super_admin_badge') : t('admin.admin_badge') }}</span>
          </div>
        </div>

        <!-- Right Side: Locale Switcher, Back to Site, User Info -->
        <div class="flex items-center gap-3">
          <!-- Bilingual Switcher in Admin -->
          <div class="flex items-center bg-black/40 rounded-full p-0.5 border border-white/10">
            <button
              @click="setLocale('id')"
              class="px-2 py-0.5 rounded-full text-[10px] font-bold transition cursor-pointer"
              :class="locale === 'id' ? 'bg-blue-600 text-white' : 'text-slate-300 hover:text-white hover:bg-white/10'"
            >
              🇮🇩 ID
            </button>
            <button
              @click="setLocale('en')"
              class="px-2 py-0.5 rounded-full text-[10px] font-bold transition cursor-pointer"
              :class="locale === 'en' ? 'bg-blue-600 text-white' : 'text-slate-300 hover:text-white hover:bg-white/10'"
            >
              🇬🇧 EN
            </button>
          </div>

          <NuxtLink
            to="/"
            class="text-xs text-white bg-white/15 hover:bg-white/30 border border-white/20 px-3 py-1.5 rounded-full font-bold transition hover:text-white hidden sm:inline-flex items-center gap-1.5 shadow-xs"
          >
            <span>🌐</span>
            <span>{{ t('admin.back_to_site') }}</span>
          </NuxtLink>

          <!-- User Profile & Logout -->
          <div class="flex items-center gap-2">
            <span class="text-xs font-bold text-slate-200 hidden md:inline">{{ user?.name }}</span>
            <button
              @click="handleLogout"
              class="bg-red-600 hover:bg-red-700 text-white hover:text-white px-3 py-1.5 rounded-xl text-xs font-bold transition cursor-pointer shadow-xs"
            >
              {{ t('logout') }}
            </button>
          </div>
        </div>
      </header>

      <!-- Admin Body: Sidebar + Main Content -->
      <div class="flex-1 flex flex-col lg:flex-row relative">
        <!-- Sidebar Navigation (Desktop + Mobile Drawer) -->
        <aside
          class="lg:w-64 bg-white border-r border-slate-200 flex flex-col shrink-0 transition-all duration-300"
          :class="mobileSidebarOpen ? 'fixed inset-y-0 left-0 z-50 w-64 shadow-2xl block' : 'hidden lg:flex'"
        >
          <!-- Mobile Sidebar Header Close -->
          <div class="lg:hidden p-4 border-b border-slate-100 flex items-center justify-between bg-slate-900 text-white">
            <span class="text-xs font-bold">Menu Panel Admin</span>
            <button @click="mobileSidebarOpen = false" class="text-white text-sm font-bold p-1 cursor-pointer">✕</button>
          </div>

          <!-- Template Information Box -->
          <div class="p-4 border-b border-slate-100">
            <div
              class="p-3 rounded-2xl border"
              :class="isSuperAdmin ? 'bg-purple-50/80 border-purple-100 text-purple-950' : 'bg-blue-50/80 border-blue-100 text-blue-950'"
            >
              <div class="text-[10px] font-black uppercase tracking-wider opacity-75">Tipe Template Aktif:</div>
              <div class="text-xs font-extrabold mt-0.5">
                {{ isSuperAdmin ? 'Template Super Admin' : 'Template Admin Operasional' }}
              </div>
              <p class="text-[10px] text-slate-600 mt-1 leading-snug">
                {{ isSuperAdmin ? 'Akses penuh kontrol pengguna, penghapusan permanen data & konfigurasi tema.' : 'Fokus pemrosesan pemesanan, verifikasi transaksi & kesiapan armada.' }}
              </p>
            </div>
          </div>

          <!-- Sidebar Navigation Items (High-Contrast Solid Hover, Clean Professional Typography) -->
          <nav class="flex-1 p-3 space-y-1.5 overflow-y-auto text-xs font-bold">
            <!-- 1. Overview Stats -->
            <button
              @click="activeTab = 'stats'; mobileSidebarOpen = false"
              class="w-full text-left px-3.5 py-2.5 rounded-xl transition flex items-center justify-between cursor-pointer font-bold"
              :class="activeTab === 'stats'
                ? (isSuperAdmin ? 'bg-purple-600 text-white shadow-sm hover:bg-purple-700 hover:text-white' : 'bg-blue-600 text-white shadow-sm hover:bg-blue-700 hover:text-white')
                : (isSuperAdmin ? 'text-slate-700 hover:text-purple-700 hover:bg-purple-50' : 'text-slate-700 hover:text-blue-700 hover:bg-blue-50')"
            >
              <span>{{ t('admin.dashboard') }}</span>
            </button>

            <!-- 2. Pemesanan -->
            <button
              @click="activeTab = 'bookings'; mobileSidebarOpen = false"
              class="w-full text-left px-3.5 py-2.5 rounded-xl transition flex items-center justify-between cursor-pointer font-bold"
              :class="activeTab === 'bookings'
                ? (isSuperAdmin ? 'bg-purple-600 text-white shadow-sm hover:bg-purple-700 hover:text-white' : 'bg-blue-600 text-white shadow-sm hover:bg-blue-700 hover:text-white')
                : (isSuperAdmin ? 'text-slate-700 hover:text-purple-700 hover:bg-purple-50' : 'text-slate-700 hover:text-blue-700 hover:bg-blue-50')"
            >
              <span>{{ t('admin.bookings') }}</span>
              <span class="text-[10px] px-2 py-0.5 rounded-full font-black" :class="activeTab === 'bookings' ? 'bg-white/25 text-white' : 'bg-slate-200 text-slate-700'">
                {{ bookings.length }}
              </span>
            </button>

            <!-- 3. Transaksi & Verifikasi -->
            <button
              @click="activeTab = 'transactions'; mobileSidebarOpen = false"
              class="w-full text-left px-3.5 py-2.5 rounded-xl transition flex items-center justify-between cursor-pointer font-bold"
              :class="activeTab === 'transactions'
                ? (isSuperAdmin ? 'bg-purple-600 text-white shadow-sm hover:bg-purple-700 hover:text-white' : 'bg-blue-600 text-white shadow-sm hover:bg-blue-700 hover:text-white')
                : (isSuperAdmin ? 'text-slate-700 hover:text-purple-700 hover:bg-purple-50' : 'text-slate-700 hover:text-blue-700 hover:bg-blue-50')"
            >
              <span>{{ t('admin.transactions') }}</span>
              <span class="text-[10px] px-2 py-0.5 rounded-full font-black" :class="activeTab === 'transactions' ? 'bg-white/25 text-white' : 'bg-slate-200 text-slate-700'">
                {{ transactions.length }}
              </span>
            </button>

            <!-- 4. Armada Bus -->
            <button
              @click="activeTab = 'fleets'; mobileSidebarOpen = false"
              class="w-full text-left px-3.5 py-2.5 rounded-xl transition flex items-center justify-between cursor-pointer font-bold"
              :class="activeTab === 'fleets'
                ? (isSuperAdmin ? 'bg-purple-600 text-white shadow-sm hover:bg-purple-700 hover:text-white' : 'bg-blue-600 text-white shadow-sm hover:bg-blue-700 hover:text-white')
                : (isSuperAdmin ? 'text-slate-700 hover:text-purple-700 hover:bg-purple-50' : 'text-slate-700 hover:text-blue-700 hover:bg-blue-50')"
            >
              <span>{{ t('admin.fleets') }}</span>
              <span class="text-[10px] px-2 py-0.5 rounded-full font-black" :class="activeTab === 'fleets' ? 'bg-white/25 text-white' : 'bg-slate-200 text-slate-700'">
                {{ fleets.length }}
              </span>
            </button>

            <!-- 5. Promo & Kupon -->
            <button
              @click="activeTab = 'discounts'; mobileSidebarOpen = false"
              class="w-full text-left px-3.5 py-2.5 rounded-xl transition flex items-center justify-between cursor-pointer font-bold"
              :class="activeTab === 'discounts'
                ? (isSuperAdmin ? 'bg-purple-600 text-white shadow-sm hover:bg-purple-700 hover:text-white' : 'bg-blue-600 text-white shadow-sm hover:bg-blue-700 hover:text-white')
                : (isSuperAdmin ? 'text-slate-700 hover:text-purple-700 hover:bg-purple-50' : 'text-slate-700 hover:text-blue-700 hover:bg-blue-50')"
            >
              <span>{{ t('admin.discounts') }}</span>
            </button>

            <!-- 6. SUPER ADMIN EXCLUSIVE: User Management -->
            <div v-if="isSuperAdmin" class="pt-3">
              <div class="text-[10px] font-black text-purple-700 uppercase tracking-wider px-3.5 py-1">
                Khusus Super Admin
              </div>
              <button
                @click="activeTab = 'users'; mobileSidebarOpen = false"
                class="w-full text-left px-3.5 py-2.5 rounded-xl transition flex items-center justify-between cursor-pointer font-bold mt-1"
                :class="activeTab === 'users'
                  ? 'bg-purple-600 text-white font-extrabold shadow-sm hover:bg-purple-700 hover:text-white'
                  : 'text-purple-900 bg-purple-50/80 hover:bg-purple-600 hover:text-white'"
              >
                <span>{{ t('admin.users') }}</span>
                <span class="text-[10px] px-2 py-0.5 rounded-full font-bold" :class="activeTab === 'users' ? 'bg-white/25 text-white' : 'bg-purple-200 text-purple-900'">
                  {{ users.length }}
                </span>
              </button>
            </div>

            <!-- Lock Info for Operational Admin -->
            <div v-else class="p-3.5 mt-4 bg-slate-50 rounded-xl border border-dashed border-slate-200 text-[11px] text-slate-500">
              <span class="font-bold block text-slate-700 mb-0.5">Menu Terkunci:</span>
              <span>Kelola Akun Pengguna & Hapus Data Permanen membutuhkan hak akses Super Admin.</span>
            </div>
          </nav>

          <!-- Sidebar Footer Status -->
          <div class="p-3 border-t border-slate-100 text-center text-[10px] text-slate-400 font-semibold">
            SJ Trans Admin OS v2.0 • Online
          </div>
        </aside>

        <!-- Backdrop for mobile drawer -->
        <div
          v-if="mobileSidebarOpen"
          @click="mobileSidebarOpen = false"
          class="lg:hidden fixed inset-0 bg-black/40 z-40 backdrop-blur-xs"
        ></div>

        <!-- Main Content Area -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-y-auto">
          <!-- TAB 1: OVERVIEW & STATS -->
          <section v-if="activeTab === 'stats'" class="space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
              <div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900">
                  {{ isSuperAdmin ? 'Ringkasan Eksekutif & Metrik Sistem' : 'Dashboard Operasional Harian' }}
                </h1>
                <p class="text-xs text-slate-500">Status armada, omset pemesanan, dan transaksi real-time.</p>
              </div>
              <span class="text-xs text-slate-500 font-bold bg-white px-3.5 py-1.5 rounded-xl border border-slate-200 shadow-2xs self-start sm:self-auto">
                🕒 Terakhir diperbarui: {{ currentTime }}
              </span>
            </div>

            <!-- Stats KPI Cards -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
              <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs">
                <span class="text-xs font-bold text-slate-400 block mb-1">Total Pemesanan</span>
                <span class="text-2xl sm:text-3xl font-black text-slate-900">{{ stats.total_bookings }}</span>
                <span class="text-[10px] text-emerald-600 font-bold block mt-1">↑ 12% bulan ini</span>
              </div>

              <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs">
                <span class="text-xs font-bold text-slate-400 block mb-1">Verifikasi Tertunda</span>
                <span class="text-2xl sm:text-3xl font-black text-amber-500">{{ stats.pending_transactions }}</span>
                <span class="text-[10px] text-amber-600 font-bold block mt-1">Menunggu approval</span>
              </div>

              <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs">
                <span class="text-xs font-bold text-slate-400 block mb-1">Armada Bus Siap</span>
                <span class="text-2xl sm:text-3xl font-black text-blue-600">{{ fleets.length }} Unit</span>
                <span class="text-[10px] text-blue-600 font-bold block mt-1">100% Lulus Uji KIR</span>
              </div>

              <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs">
                <span class="text-xs font-bold text-slate-400 block mb-1">Estimasi Omset</span>
                <span class="text-xl sm:text-2xl font-black text-slate-900">Rp {{ (stats.total_revenue / 1000000).toFixed(1) }} Jt</span>
                <span class="text-[10px] text-emerald-600 font-bold block mt-1">Status Lunas & DP</span>
              </div>
            </div>

            <!-- Role-Specific Action Banner -->
            <div
              v-if="isSuperAdmin"
              class="bg-gradient-to-r from-purple-900 to-indigo-950 text-white p-6 sm:p-8 rounded-3xl shadow-md flex flex-col sm:flex-row items-center justify-between gap-4"
            >
              <div>
                <span class="text-purple-300 text-[10px] font-bold uppercase tracking-wider block mb-1">Panel Kendali Penuh</span>
                <h3 class="text-base sm:text-lg font-black text-white">Kelola Akses User & Audit Keamanan Sistem</h3>
                <p class="text-xs text-purple-200 mt-1 max-w-xl">Anda memiliki wewenang membuat akun staf, mengubah hak akses role, serta menghapus data permanen.</p>
              </div>
              <button
                @click="activeTab = 'users'"
                class="bg-white hover:bg-purple-100 text-purple-950 hover:text-purple-900 px-5 py-2.5 rounded-xl font-bold text-xs whitespace-nowrap shadow-md transition cursor-pointer"
              >
                Buka Kelola Pengguna ➔
              </button>
            </div>
          </section>

          <!-- TAB 2: MANAJEMEN PEMESANAN -->
          <section v-if="activeTab === 'bookings'" class="space-y-4">
            <div class="flex items-center justify-between">
              <div>
                <h2 class="text-lg font-black text-slate-900">{{ t('admin.bookings') }}</h2>
                <p class="text-xs text-slate-500">Daftar reservasi sewa bus pelanggan masuk.</p>
              </div>
            </div>

            <!-- Responsive Bookings Table -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs overflow-hidden">
              <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                  <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 font-bold uppercase text-[10px]">
                    <tr>
                      <th class="p-3.5">Kode Booking</th>
                      <th class="p-3.5">Pelanggan</th>
                      <th class="p-3.5">Armada & Rute</th>
                      <th class="p-3.5">Tanggal</th>
                      <th class="p-3.5">Total Biaya</th>
                      <th class="p-3.5">Status</th>
                      <th class="p-3.5 text-right">Aksi</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-slate-100 text-slate-700">
                    <tr v-for="b in bookings" :key="b.id" class="hover:bg-slate-50 transition">
                      <td class="p-3.5 font-mono font-bold text-blue-600">{{ b.booking_code }}</td>
                      <td class="p-3.5">
                        <div class="font-bold text-slate-900">{{ b.customer_name }}</div>
                        <div class="text-[10px] text-slate-400">{{ b.customer_phone }}</div>
                      </td>
                      <td class="p-3.5">
                        <div class="font-semibold text-slate-800">{{ b.fleet_name }} ({{ b.units }} Unit)</div>
                        <div class="text-[10px] text-slate-400">{{ b.origin }} ➔ {{ b.destination }}</div>
                      </td>
                      <td class="p-3.5 text-[11px] whitespace-nowrap">{{ b.start_date }} s/d {{ b.end_date }}</td>
                      <td class="p-3.5 font-bold text-slate-900 whitespace-nowrap">Rp {{ b.total_price.toLocaleString() }}</td>
                      <td class="p-3.5">
                        <span
                          class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase"
                          :class="{
                            'bg-amber-100 text-amber-800': b.status === 'pending',
                            'bg-blue-100 text-blue-800': b.status === 'confirmed',
                            'bg-emerald-100 text-emerald-800': b.status === 'completed',
                            'bg-red-100 text-red-800': b.status === 'cancelled',
                          }"
                        >
                          {{ b.status }}
                        </span>
                      </td>
                      <td class="p-3.5 text-right">
                        <div class="flex items-center justify-end gap-1.5">
                          <button
                            v-if="b.status === 'pending'"
                            @click="updateBookingStatus(b.id, 'confirmed')"
                            class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white hover:text-white rounded-lg text-xs font-bold transition shadow-2xs cursor-pointer"
                          >
                            Konfirmasi
                          </button>
                          <button
                            v-if="b.status === 'confirmed'"
                            @click="updateBookingStatus(b.id, 'completed')"
                            class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white hover:text-white rounded-lg text-xs font-bold transition shadow-2xs cursor-pointer"
                          >
                            Selesai
                          </button>
                          <!-- Permanent Delete only for Super Admin -->
                          <button
                            v-if="isSuperAdmin"
                            @click="deleteBooking(b.id)"
                            class="px-3 py-1.5 bg-red-50 hover:bg-red-600 text-red-700 hover:text-white border border-red-200 rounded-lg text-xs font-bold transition shadow-2xs cursor-pointer"
                            title="Hapus Permanen (Super Admin Only)"
                          >
                            Hapus
                          </button>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </section>

          <!-- TAB 3: VERIFIKASI TRANSAKSI -->
          <section v-if="activeTab === 'transactions'" class="space-y-4">
            <div class="flex items-center justify-between">
              <div>
                <h2 class="text-lg font-black text-slate-900">{{ t('admin.transactions') }}</h2>
                <p class="text-xs text-slate-500">Pemeriksaan bukti transfer bank BCA & Mandiri.</p>
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div
                v-for="tx in transactions"
                :key="tx.id"
                class="bg-white rounded-2xl border border-slate-200 p-5 shadow-2xs flex flex-col justify-between"
              >
                <div>
                  <div class="flex items-center justify-between mb-2">
                    <span class="font-mono text-xs font-bold text-slate-700">{{ tx.invoice_number }}</span>
                    <span
                      class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase"
                      :class="tx.status === 'verified' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800'"
                    >
                      {{ tx.status }}
                    </span>
                  </div>

                  <div class="text-base font-black text-slate-900 mb-1">
                    Rp {{ tx.amount.toLocaleString() }}
                  </div>
                  <div class="text-xs text-slate-600">Metode: <strong>{{ tx.payment_method }}</strong></div>
                  <div class="text-[11px] text-slate-400 mt-1">Pengirim: {{ tx.sender_name }} ({{ tx.sender_bank }})</div>

                  <!-- Proof Image Mock -->
                  <div class="mt-3 p-3 bg-slate-50 rounded-xl border border-slate-200 text-xs flex items-center justify-between">
                    <span class="text-slate-600 font-medium">Bukti Transfer:</span>
                    <a :href="tx.proof_url" target="_blank" class="text-blue-600 font-bold hover:text-blue-800 hover:underline">
                      Lihat Bukti Foto ➔
                    </a>
                  </div>
                </div>

                <div class="pt-4 mt-4 border-t border-slate-100 flex items-center justify-end gap-2">
                  <button
                    v-if="tx.status === 'pending'"
                    @click="verifyTransaction(tx.id, 'rejected')"
                    class="px-3.5 py-1.5 rounded-xl bg-red-50 hover:bg-red-600 text-red-700 hover:text-white border border-red-200 text-xs font-bold transition shadow-2xs cursor-pointer"
                  >
                    Tolak
                  </button>
                  <button
                    v-if="tx.status === 'pending'"
                    @click="verifyTransaction(tx.id, 'verified')"
                    class="px-4 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white hover:text-white text-xs font-bold transition shadow-2xs cursor-pointer"
                  >
                    Verifikasi Lunas ✓
                  </button>
                  <span v-else class="text-xs text-emerald-600 font-bold bg-emerald-50 px-3 py-1 rounded-lg">
                    ✓ Terverifikasi Lunas
                  </span>
                </div>
              </div>
            </div>
          </section>

          <!-- TAB 4: KELOLA ARMADA BUS -->
          <section v-if="activeTab === 'fleets'" class="space-y-4">
            <div class="flex items-center justify-between">
              <div>
                <h2 class="text-lg font-black text-slate-900">{{ t('admin.fleets') }}</h2>
                <p class="text-xs text-slate-500">Katalog unit bus aktif dan tarif harian.</p>
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
              <div
                v-for="fl in fleets"
                :key="fl.id"
                class="bg-white rounded-2xl border border-slate-200 p-4 shadow-2xs flex flex-col justify-between"
              >
                <div>
                  <div class="h-36 rounded-xl overflow-hidden mb-3 bg-slate-100">
                    <img :src="fl.image_url" :alt="fl.name" class="w-full h-full object-cover" />
                  </div>
                  <h4 class="font-bold text-slate-900 text-sm mb-1">{{ fl.name }}</h4>
                  <div class="text-xs text-slate-500 mb-2">Kapasitas: {{ fl.seats }} Kursi Penumpang</div>
                  <div class="text-sm font-black text-blue-700">Rp {{ fl.price_per_day.toLocaleString() }} / hari</div>
                </div>

                <div class="pt-3 mt-3 border-t border-slate-100 flex items-center justify-between">
                  <span class="text-[10px] text-emerald-700 font-bold bg-emerald-50 px-2.5 py-1 rounded-lg">Tersedia</span>
                  <!-- Super admin exclusive delete button -->
                  <button
                    v-if="isSuperAdmin"
                    @click="deleteFleet(fl.id)"
                    class="px-3 py-1.5 rounded-xl bg-red-50 hover:bg-red-600 text-red-700 hover:text-white border border-red-200 text-xs font-bold transition shadow-2xs cursor-pointer"
                  >
                    Hapus Armada
                  </button>
                </div>
              </div>
            </div>
          </section>

          <!-- TAB 5: KELOLA PROMO & KUPON -->
          <section v-if="activeTab === 'discounts'" class="space-y-4">
            <div class="flex items-center justify-between">
              <div>
                <h2 class="text-lg font-black text-slate-900">{{ t('admin.discounts') }}</h2>
                <p class="text-xs text-slate-500">Kupon potongan harga promosi penyewaan bus.</p>
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div
                v-for="d in discounts"
                :key="d.id"
                class="bg-white rounded-2xl border border-slate-200 p-5 shadow-2xs flex items-center justify-between"
              >
                <div>
                  <div class="font-mono text-base font-black text-blue-700 tracking-wide">{{ d.code }}</div>
                  <div class="text-xs font-bold text-slate-800 mt-0.5">{{ d.name }}</div>
                  <div class="text-[11px] text-slate-500">Nilai: <strong>{{ d.discount_type === 'percentage' ? `${d.value}%` : `Rp ${d.value.toLocaleString()}` }}</strong></div>
                </div>

                <div class="text-right">
                  <span class="bg-emerald-100 text-emerald-800 text-[10px] font-bold px-2 py-0.5 rounded-full uppercase">Aktif</span>
                  <div v-if="isSuperAdmin" class="mt-3">
                    <button
                      @click="deleteDiscount(d.id)"
                      class="px-3 py-1.5 rounded-xl bg-red-50 hover:bg-red-600 text-red-700 hover:text-white border border-red-200 text-xs font-bold transition shadow-2xs cursor-pointer"
                    >
                      Hapus Kupon
                    </button>
                  </div>
                </div>
              </div>
            </div>
          </section>

          <!-- TAB 6: SUPER ADMIN EXCLUSIVE - MANAJEMEN PENGGUNA -->
          <section v-if="activeTab === 'users' && isSuperAdmin" class="space-y-4 animate-fade">
            <div class="flex items-center justify-between bg-purple-50 p-4 rounded-2xl border border-purple-100">
              <div>
                <span class="bg-purple-600 text-white text-[10px] font-black uppercase px-2 py-0.5 rounded">Wewenang Super Admin</span>
                <h2 class="text-lg font-black text-purple-950 mt-1">{{ t('admin.users') }}</h2>
                <p class="text-xs text-purple-800">Kelola akun, ubah role pengguna, dan aktifkan/nonaktifkan akun.</p>
              </div>
            </div>

            <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs overflow-hidden">
              <div class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                  <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 font-bold uppercase text-[10px]">
                    <tr>
                      <th class="p-3.5">Nama & Username</th>
                      <th class="p-3.5">Email & Kontak</th>
                      <th class="p-3.5">Hak Akses Role</th>
                      <th class="p-3.5">Status</th>
                      <th class="p-3.5 text-right">Aksi Super Admin</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-slate-100 text-slate-700">
                    <tr v-for="u in users" :key="u.id" class="hover:bg-purple-50/40 transition">
                      <td class="p-3.5">
                        <div class="font-bold text-slate-900">{{ u.name }}</div>
                        <div class="text-[10px] font-mono text-slate-400">@{{ u.username }}</div>
                      </td>
                      <td class="p-3.5">
                        <div class="text-slate-800 font-medium">{{ u.email }}</div>
                        <div class="text-[10px] text-slate-400">{{ u.phone_number }}</div>
                      </td>
                      <td class="p-3.5">
                        <span
                          class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase"
                          :class="{
                            'bg-purple-100 text-purple-800 border border-purple-200': u.role?.name === 'super_admin',
                            'bg-blue-100 text-blue-800': u.role?.name === 'admin',
                            'bg-slate-100 text-slate-700': u.role?.name === 'customer',
                          }"
                        >
                          {{ u.role?.display_name || u.role?.name }}
                        </span>
                      </td>
                      <td class="p-3.5">
                        <span class="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded">Aktif</span>
                      </td>
                      <td class="p-3.5 text-right">
                        <button
                          v-if="u.role?.name !== 'super_admin'"
                          @click="toggleUserRole(u)"
                          class="px-3 py-1.5 bg-purple-50 hover:bg-purple-600 text-purple-800 hover:text-white border border-purple-200 font-bold text-xs rounded-xl transition shadow-2xs cursor-pointer"
                        >
                          Ubah Hak Akses Role
                        </button>
                        <span v-else class="text-[10px] text-slate-400 italic font-semibold">Utama</span>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </section>
        </main>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
const router = useRouter()
const { user, isLoggedIn, isAdmin, isSuperAdmin, login, logout } = useAuth()
const { locale, setLocale, t } = useI18n()
const { showToast } = useNotification()

const activeTab = ref('stats')
const mobileSidebarOpen = ref(false)
const currentTime = ref(new Date().toLocaleTimeString('id-ID'))

const stats = ref({
  total_bookings: 18,
  pending_transactions: 3,
  total_revenue: 68500000,
})

const bookings = ref<any[]>([
  {
    id: 1,
    booking_code: 'SJT-20261028-001',
    customer_name: 'Budi Santoso',
    customer_phone: '081234567890',
    fleet_name: 'Big Bus Scania K410IB',
    units: 1,
    origin: 'Jakarta',
    destination: 'Bandung & Sekitarnya',
    start_date: '2026-11-01',
    end_date: '2026-11-03',
    total_price: 12600000,
    status: 'pending',
  },
  {
    id: 2,
    booking_code: 'SJT-20261027-004',
    customer_name: 'PT Astra International Tbk',
    customer_phone: '08119888221',
    fleet_name: 'Mercedes-Benz OH 1626',
    units: 2,
    origin: 'Jakarta',
    destination: 'Yogyakarta',
    start_date: '2026-11-10',
    end_date: '2026-11-14',
    total_price: 30400000,
    status: 'confirmed',
  },
  {
    id: 3,
    booking_code: 'SJT-20261025-009',
    customer_name: 'SMA Negeri 8 Jakarta',
    customer_phone: '08138877665',
    fleet_name: 'Medium Bus Jetbus 3+',
    units: 3,
    origin: 'Jakarta',
    destination: 'Puncak Bogor',
    start_date: '2026-10-29',
    end_date: '2026-10-30',
    total_price: 15000000,
    status: 'completed',
  },
])

const transactions = ref<any[]>([
  {
    id: 1,
    invoice_number: 'INV-20261028-001',
    amount: 3780000,
    payment_method: 'Transfer Bank BCA (DP 30%)',
    sender_name: 'Budi Santoso',
    sender_bank: 'BCA 5410192837',
    proof_url: 'https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?auto=format&fit=crop&w=800&q=80',
    status: 'pending',
  },
  {
    id: 2,
    invoice_number: 'INV-20261027-004',
    amount: 30400000,
    payment_method: 'Transfer Bank Mandiri (Pelunasan 100%)',
    sender_name: 'PT Astra Finance',
    sender_bank: 'Mandiri 1370001928',
    proof_url: 'https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?auto=format&fit=crop&w=800&q=80',
    status: 'verified',
  },
])

const fleets = ref<any[]>([
  {
    id: 1,
    name: 'Big Bus SHD Scania K410IB Jetbus 5',
    seats: 50,
    price_per_day: 4200000,
    image_url: 'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?auto=format&fit=crop&w=800&q=80',
  },
  {
    id: 2,
    name: 'Big Bus Mercedes-Benz OH 1626',
    seats: 59,
    price_per_day: 3800000,
    image_url: 'https://images.unsplash.com/photo-1570125909232-eb263c188f7e?auto=format&fit=crop&w=800&q=80',
  },
  {
    id: 3,
    name: 'Medium Bus Jetbus 3+ MD Executive',
    seats: 31,
    price_per_day: 2500000,
    image_url: 'https://images.unsplash.com/photo-1494515843206-f3117d3f51b7?auto=format&fit=crop&w=800&q=80',
  },
  {
    id: 4,
    name: 'Toyota HiAce Premio Luxury VIP',
    seats: 14,
    price_per_day: 1500000,
    image_url: 'https://images.unsplash.com/photo-1568605117036-5fe5e7bab0b7?auto=format&fit=crop&w=800&q=80',
  },
])

const discounts = ref<any[]>([
  { id: 1, code: 'SJTPROMO10', name: 'Promo Rombongan Wisata 10%', discount_type: 'percentage', value: 10 },
  { id: 2, code: 'LIBURANSERU', name: 'Potongan Langsung Liburan Seru', discount_type: 'nominal', value: 350000 },
])

const users = ref<any[]>([
  {
    id: 1,
    name: 'Super Admin SJ Trans',
    username: 'superadmin',
    email: 'superadmin@sjtrans.co.id',
    phone_number: '081288887788',
    role: { id: 1, name: 'super_admin', display_name: 'Super Admin' },
  },
  {
    id: 2,
    name: 'Admin Operasional',
    username: 'admin',
    email: 'admin@sjtrans.co.id',
    phone_number: '081288997700',
    role: { id: 2, name: 'admin', display_name: 'Admin Operasional' },
  },
  {
    id: 3,
    name: 'Budi Santoso',
    username: 'budi',
    email: 'budi@gmail.com',
    phone_number: '081234567890',
    role: { id: 3, name: 'customer', display_name: 'Customer' },
  },
])

const manualLoginForm = ref({
  login: '',
  password: '',
})
const loginErrorMessage = ref('')
const isLoggingIn = ref(false)

const handleManualLogin = async () => {
  loginErrorMessage.value = ''
  if (!manualLoginForm.value.login || !manualLoginForm.value.password) {
    loginErrorMessage.value = 'Mohon masukkan username/email dan password.'
    return
  }
  isLoggingIn.value = true
  const res = await login(manualLoginForm.value.login, manualLoginForm.value.password)
  isLoggingIn.value = false
  if (!res.success) {
    loginErrorMessage.value = res.message || 'Login gagal. Periksa username/email dan password Anda.'
  }
}

const quickLogin = async (username: string, pass: string) => {
  loginErrorMessage.value = ''
  await login(username, pass)
}

const handleLogout = async () => {
  await logout()
  router.push('/')
}

const updateBookingStatus = (id: number, newStatus: string) => {
  const item = bookings.value.find(b => b.id === id)
  if (item) {
    item.status = newStatus
  }
}

const deleteBooking = (id: number) => {
  if (confirm('Apakah Anda yakin ingin menghapus pemesanan ini secara permanen?')) {
    bookings.value = bookings.value.filter(b => b.id !== id)
  }
}

const verifyTransaction = (id: number, status: string) => {
  const tx = transactions.value.find(t => t.id === id)
  if (tx) {
    tx.status = status
  }
}

const deleteFleet = (id: number) => {
  if (confirm('Hapus armada ini dari daftar aktif?')) {
    fleets.value = fleets.value.filter(f => f.id !== id)
  }
}

const deleteDiscount = (id: number) => {
  if (confirm('Hapus kupon promo ini?')) {
    discounts.value = discounts.value.filter(d => d.id !== id)
  }
}

const toggleUserRole = (targetUser: any) => {
  if (targetUser.role.name === 'customer') {
    targetUser.role = { id: 2, name: 'admin', display_name: 'Admin Operasional' }
    showToast('success', 'Hak Akses Diperbarui', `Hak akses ${targetUser.name} diubah menjadi Admin Operasional!`)
  } else if (targetUser.role.name === 'admin') {
    targetUser.role = { id: 3, name: 'customer', display_name: 'Customer' }
    showToast('info', 'Hak Akses Diperbarui', `Hak akses ${targetUser.name} diubah menjadi Customer!`)
  }
}
</script>
