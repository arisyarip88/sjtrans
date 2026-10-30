<template>
  <div class="min-h-screen bg-slate-100 flex flex-col pb-16 md:pb-0">
    <!-- Auth Guard Check: If not logged in or not admin -->
    <div v-if="!isLoggedIn || !isAdmin" class="min-h-screen flex items-center justify-center p-4">
      <div class="max-w-md w-full bg-white rounded-3xl p-8 border border-slate-200 shadow-xl text-center">
        <div class="w-16 h-16 rounded-full bg-slate-100 text-slate-700 flex items-center justify-center mx-auto mb-4 border border-slate-200">
          <svg class="w-7 h-7 text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
          </svg>
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
            <span class="text-white">Login sebagai Super Admin</span>
            <span class="text-[10px] bg-purple-800 text-white px-2 py-0.5 rounded font-black">Penuh</span>
          </button>
          <button
            @click="quickLogin('admin', 'password123')"
            class="w-full py-2.5 px-4 rounded-xl bg-blue-600 hover:bg-blue-700 text-white hover:text-white text-xs font-bold transition flex items-center justify-between cursor-pointer shadow-sm"
          >
            <span class="text-white">Login sebagai Admin Operasional</span>
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
            class="px-2.5 py-0.5 rounded-full text-[11px] font-black uppercase tracking-wider flex items-center gap-1.5 shadow-xs"
            :class="isSuperAdmin ? 'bg-purple-500/30 text-purple-200 border border-purple-400/50' : 'bg-blue-500/30 text-blue-200 border border-blue-400/50'"
          >
            <span class="w-1.5 h-1.5 rounded-full" :class="isSuperAdmin ? 'bg-purple-300 animate-pulse' : 'bg-blue-300 animate-pulse'"></span>
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
              ID
            </button>
            <button
              @click="setLocale('en')"
              class="px-2 py-0.5 rounded-full text-[10px] font-bold transition cursor-pointer"
              :class="locale === 'en' ? 'bg-blue-600 text-white' : 'text-slate-300 hover:text-white hover:bg-white/10'"
            >
              EN
            </button>
          </div>

          <NuxtLink
            to="/"
            class="text-xs text-white bg-white/15 hover:bg-white/30 border border-white/20 px-3 py-1.5 rounded-full font-bold transition hover:text-white hidden sm:inline-flex items-center gap-1.5 shadow-xs"
          >
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
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

            <!-- 5.5 Kelola Blog & Artikel (Admin & Super Admin) -->
            <button
              @click="activeTab = 'posts'; mobileSidebarOpen = false"
              class="w-full text-left px-3.5 py-2.5 rounded-xl transition flex items-center justify-between cursor-pointer font-bold"
              :class="activeTab === 'posts'
                ? (isSuperAdmin ? 'bg-purple-600 text-white shadow-sm hover:bg-purple-700 hover:text-white' : 'bg-blue-600 text-white shadow-sm hover:bg-blue-700 hover:text-white')
                : (isSuperAdmin ? 'text-slate-700 hover:text-purple-700 hover:bg-purple-50' : 'text-slate-700 hover:text-blue-700 hover:bg-blue-50')"
            >
              <span>Kelola Blog & Artikel</span>
              <span class="text-[10px] px-2 py-0.5 rounded-full font-black" :class="activeTab === 'posts' ? 'bg-white/25 text-white' : 'bg-slate-200 text-slate-700'">
                {{ blogPosts.length }}
              </span>
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
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
              <div>
                <h1 class="text-xl sm:text-2xl font-black text-slate-900">
                  {{ isSuperAdmin ? 'Ringkasan Eksekutif & Metrik Sistem' : 'Dashboard Operasional Harian' }}
                </h1>
                <p class="text-xs text-slate-500">Status armada, omset pemesanan, dan transaksi real-time.</p>
              </div>
              <div class="flex items-center gap-2 self-start sm:self-auto">
                <div class="hidden sm:flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-bold">
                  <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                  <span>Sinkronisasi Aktif</span>
                </div>
                <button
                  @click="fetchAdminData(false)"
                  :disabled="isSyncing"
                  class="px-3.5 py-1.5 rounded-xl bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 hover:text-slate-900 text-xs font-bold transition flex items-center gap-2 shadow-2xs cursor-pointer disabled:opacity-50"
                >
                  <svg
                    class="w-3.5 h-3.5 text-blue-600"
                    :class="{ 'animate-spin': isSyncing }"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                  </svg>
                  <span>{{ isSyncing ? 'Sinkron...' : 'Segarkan Data' }}</span>
                </button>
              </div>
            </div>

            <!-- Stats KPI Cards -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
              <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs">
                <span class="text-xs font-bold text-slate-400 block mb-1">Total Pemesanan</span>
                <span class="text-2xl sm:text-3xl font-black text-slate-900">{{ stats.total_bookings }}</span>
                <span class="text-[10px] text-emerald-600 font-bold block mt-1">Tersinkron Customer</span>
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
                <span class="text-xl sm:text-2xl font-black text-slate-900">Rp {{ Number(stats.total_revenue || 0).toLocaleString('id-ID') }}</span>
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
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
              <div>
                <h2 class="text-lg font-black text-slate-900">{{ t('admin.bookings') }}</h2>
                <p class="text-xs text-slate-500">Daftar reservasi sewa bus pelanggan yang otomatis terintegrasi dari customer portal.</p>
              </div>
              <div class="flex items-center gap-2">
                <button
                  @click="fetchAdminData(false)"
                  :disabled="isSyncing"
                  class="px-3.5 py-1.5 rounded-xl bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 hover:text-slate-900 text-xs font-bold transition flex items-center gap-2 shadow-2xs cursor-pointer disabled:opacity-50"
                >
                  <svg
                    class="w-3.5 h-3.5 text-blue-600"
                    :class="{ 'animate-spin': isSyncing }"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                  </svg>
                  <span>{{ isSyncing ? 'Sinkron...' : 'Segarkan Data' }}</span>
                </button>
              </div>
            </div>

            <!-- Responsive Bookings Table -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs overflow-hidden">
              <div v-if="bookings.length === 0" class="p-12 text-center">
                <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                </div>
                <h3 class="text-sm font-bold text-slate-700">Belum Ada Pemesanan Masuk</h3>
                <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">Ketika pelanggan memesan tiket bus di website atau portal, data akan langsung muncul di tabel ini.</p>
              </div>
              <div v-else class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                  <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 font-bold uppercase text-[10px]">
                    <tr>
                      <th class="p-3.5">Kode Booking</th>
                      <th class="p-3.5">Pelanggan</th>
                      <th class="p-3.5">Armada & Rute</th>
                      <th class="p-3.5">Tanggal</th>
                      <th class="p-3.5">Total Biaya</th>
                      <th class="p-3.5">Status Pembayaran</th>
                      <th class="p-3.5">Status Reservasi</th>
                      <th class="p-3.5 text-right">Aksi</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-slate-100 text-slate-700">
                    <tr v-for="b in bookings" :key="b.id" class="hover:bg-slate-50 transition">
                      <td class="p-3.5 font-mono font-bold text-blue-600 whitespace-nowrap">{{ b.booking_code }}</td>
                      <td class="p-3.5">
                        <div class="font-bold text-slate-900">{{ b.customer_name }}</div>
                        <div class="text-[10px] text-slate-400">{{ b.customer_phone }}</div>
                        <div v-if="b.customer_email" class="text-[10px] text-slate-400">{{ b.customer_email }}</div>
                      </td>
                      <td class="p-3.5">
                        <div class="font-semibold text-slate-800">{{ b.fleet_name }} ({{ b.units }} Unit)</div>
                        <div class="text-[10px] text-slate-400">{{ b.origin }} ➔ {{ b.destination }}</div>
                      </td>
                      <td class="p-3.5 text-[11px] whitespace-nowrap">{{ (b.start_date || '').split('T')[0] }} s/d {{ (b.end_date || '').split('T')[0] }}</td>
                      <td class="p-3.5 font-bold text-slate-900 whitespace-nowrap">
                        <div>Rp {{ Number(b.total_price || 0).toLocaleString('id-ID') }}</div>
                        <div v-if="b.payment_status === 'down_payment' || b.payment_status === 'dp_paid' || b.payment_status?.includes('DP')" class="text-[10px] text-blue-600 font-bold mt-0.5">
                          DP 50%: Rp {{ (Number(b.total_price || 0) * 0.5).toLocaleString('id-ID') }}
                        </div>
                      </td>

                      <!-- Dropdown Status Pembayaran (Belum Bayar, DP 50%, Lunas 100%, Refund) -->
                      <td class="p-3.5 min-w-[160px]">
                        <select
                          v-model="b.payment_status"
                          class="w-full text-xs font-bold rounded-xl border px-2.5 py-1.5 focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer shadow-2xs transition"
                          :class="{
                            'bg-amber-50 text-amber-800 border-amber-300': b.payment_status === 'unpaid' || b.payment_status === 'Menunggu Pembayaran',
                            'bg-blue-50 text-blue-800 border-blue-300': b.payment_status === 'down_payment' || b.payment_status === 'dp_paid' || b.payment_status?.includes('DP'),
                            'bg-emerald-50 text-emerald-800 border-emerald-300': b.payment_status === 'paid' || b.payment_status === 'Lunas',
                            'bg-red-50 text-red-800 border-red-300': b.payment_status === 'refunded',
                          }"
                        >
                          <option value="unpaid">Belum Bayar (0%)</option>
                          <option value="down_payment">DP 50% (Uang Muka)</option>
                          <option value="paid">Lunas Penuh (100%)</option>
                          <option value="refunded">Dikembalikan (Refund)</option>
                        </select>
                      </td>

                      <!-- Dropdown Status Reservasi (Menunggu, Terkonfirmasi, Dalam Perjalanan, Selesai, Batal) -->
                      <td class="p-3.5 min-w-[160px]">
                        <select
                          v-model="b.status"
                          class="w-full text-xs font-bold rounded-xl border px-2.5 py-1.5 focus:outline-none focus:ring-2 focus:ring-blue-500 cursor-pointer shadow-2xs transition"
                          :class="{
                            'bg-amber-50 text-amber-800 border-amber-300': b.status === 'pending',
                            'bg-blue-50 text-blue-800 border-blue-300': b.status === 'confirmed',
                            'bg-indigo-50 text-indigo-800 border-indigo-300': b.status === 'in_trip',
                            'bg-emerald-50 text-emerald-800 border-emerald-300': b.status === 'completed',
                            'bg-red-50 text-red-800 border-red-300': b.status === 'cancelled',
                          }"
                        >
                          <option value="pending">Menunggu Konfirmasi</option>
                          <option value="confirmed">Terkonfirmasi (Siap)</option>
                          <option value="in_trip">Dalam Perjalanan</option>
                          <option value="completed">Perjalanan Selesai</option>
                          <option value="cancelled">Dibatalkan</option>
                        </select>
                      </td>

                      <!-- Tombol Konfirmasi & Simpan -->
                      <td class="p-3.5 text-right whitespace-nowrap">
                        <div class="flex items-center justify-end gap-1.5">
                          <button
                            @click="saveBookingStatus(b)"
                            :disabled="b.isSaving"
                            class="px-3.5 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white hover:text-white rounded-xl text-xs font-bold transition shadow-2xs cursor-pointer flex items-center gap-1.5 disabled:opacity-50"
                          >
                            <svg v-if="b.isSaving" class="animate-spin h-3 w-3 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            <span>{{ b.isSaving ? 'Menyimpan...' : 'Konfirmasi' }}</span>
                          </button>
                          <!-- Permanent Delete only for Super Admin -->
                          <button
                            v-if="isSuperAdmin"
                            @click="deleteBooking(b.id)"
                            class="px-2.5 py-1.5 bg-red-50 hover:bg-red-600 text-red-700 hover:text-white border border-red-200 rounded-xl text-xs font-bold transition shadow-2xs cursor-pointer"
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
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
              <div>
                <h2 class="text-lg font-black text-slate-900">{{ t('admin.transactions') }}</h2>
                <p class="text-xs text-slate-500">Pemeriksaan bukti transfer bank BCA & Mandiri.</p>
              </div>
              <div class="flex items-center gap-2">
                <button
                  @click="fetchAdminData(false)"
                  :disabled="isSyncing"
                  class="px-3.5 py-1.5 rounded-xl bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 hover:text-slate-900 text-xs font-bold transition flex items-center gap-2 shadow-2xs cursor-pointer disabled:opacity-50"
                >
                  <svg
                    class="w-3.5 h-3.5 text-blue-600"
                    :class="{ 'animate-spin': isSyncing }"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                  >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                  </svg>
                  <span>{{ isSyncing ? 'Sinkron...' : 'Segarkan Data' }}</span>
                </button>
              </div>
            </div>

            <div v-if="transactions.length === 0" class="p-12 text-center bg-white rounded-2xl border border-slate-200">
              <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z" /></svg>
              </div>
              <h3 class="text-sm font-bold text-slate-700">Tidak Ada Transaksi</h3>
              <p class="text-xs text-slate-400 mt-1">Belum ada bukti pembayaran yang diunggah oleh pelanggan.</p>
            </div>

            <div v-else class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                    Rp {{ Number(tx.amount || 0).toLocaleString('id-ID') }}
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

          <!-- TAB 5.5: KELOLA BLOG & ARTIKEL INFORMASI (Admin & Super Admin) -->
          <section v-if="activeTab === 'posts'" class="space-y-4 animate-fade">
            <!-- Header bar -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs">
              <div>
                <div class="inline-flex items-center gap-2 bg-blue-50 text-blue-700 text-[10px] font-black uppercase tracking-wider px-2.5 py-0.5 rounded-full mb-1">
                  <span>Modul Publikasi & Informasi</span>
                </div>
                <h2 class="text-lg font-black text-slate-900">Kelola Blog & Artikel SJ Trans</h2>
                <p class="text-xs text-slate-500">Publikasikan informasi rute, tips sewa bus pariwisata, atau hapus artikel yang sudah usang.</p>
              </div>
              <div class="flex items-center gap-2">
                <button
                  @click="fetchBlogPosts"
                  :disabled="isLoadingPosts"
                  class="px-3.5 py-2 rounded-xl bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 text-xs font-bold transition flex items-center gap-2 cursor-pointer disabled:opacity-50"
                >
                  <svg class="w-3.5 h-3.5 text-blue-600" :class="{ 'animate-spin': isLoadingPosts }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                  <span>Segarkan</span>
                </button>
                <button
                  @click="openCreatePostModal"
                  class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white hover:text-white text-xs font-bold transition shadow-sm flex items-center gap-1.5 cursor-pointer"
                >
                  <span>+</span>
                  <span>Tulis Artikel Baru</span>
                </button>
              </div>
            </div>

            <!-- Articles Table -->
            <div class="bg-white rounded-2xl border border-slate-200 shadow-2xs overflow-hidden">
              <div v-if="blogPosts.length === 0" class="p-12 text-center">
                <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-3">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" /></svg>
                </div>
                <h3 class="text-sm font-bold text-slate-700">Belum Ada Artikel Blog</h3>
                <p class="text-xs text-slate-400 mt-1 max-w-sm mx-auto">Klik tombol "Tulis Artikel Baru" di atas untuk menambahkan artikel informasi wisata atau panduan bagi calon penyewa bus.</p>
              </div>

              <div v-else class="overflow-x-auto">
                <table class="w-full text-left text-xs">
                  <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 font-bold uppercase text-[10px]">
                    <tr>
                      <th class="p-3.5">Artikel & Sampul</th>
                      <th class="p-3.5">Kategori</th>
                      <th class="p-3.5">Penulis</th>
                      <th class="p-3.5">Dibaca</th>
                      <th class="p-3.5">Status</th>
                      <th class="p-3.5 text-right">Aksi</th>
                    </tr>
                  </thead>
                  <tbody class="divide-y divide-slate-100 text-slate-700">
                    <tr v-for="post in blogPosts" :key="post.id" class="hover:bg-slate-50 transition">
                      <td class="p-3.5 max-w-sm">
                        <div class="flex items-start gap-3">
                          <img
                            :src="post.featured_image || 'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?auto=format&fit=crop&w=200&q=80'"
                            :alt="post.title"
                            class="w-14 h-11 object-cover rounded-lg shrink-0 border border-slate-200"
                          />
                          <div>
                            <div class="font-bold text-slate-900 line-clamp-1 text-xs">{{ post.title }}</div>
                            <div class="text-[11px] text-slate-500 line-clamp-1 mt-0.5">{{ post.excerpt }}</div>
                            <div class="text-[10px] text-slate-400 mt-1 font-mono">/blog/{{ post.slug }}</div>
                          </div>
                        </div>
                      </td>
                      <td class="p-3.5 whitespace-nowrap">
                        <span class="bg-blue-50 text-blue-700 border border-blue-200 px-2 py-0.5 rounded-lg text-[10px] font-bold">
                          {{ post.category?.name || 'Tips & Destinasi' }}
                        </span>
                      </td>
                      <td class="p-3.5 whitespace-nowrap">
                        <div class="font-semibold text-slate-800">{{ post.author?.name || 'Admin SJ Trans' }}</div>
                        <div class="text-[10px] text-slate-400">{{ (post.published_at || post.created_at || '').split('T')[0] }}</div>
                      </td>
                      <td class="p-3.5 font-bold text-slate-700 whitespace-nowrap">
                        {{ Number(post.views_count || 0).toLocaleString('id-ID') }}x
                      </td>
                      <td class="p-3.5 whitespace-nowrap">
                        <span
                          class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase"
                          :class="post.status === 'published' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800'"
                        >
                          {{ post.status || 'published' }}
                        </span>
                      </td>
                      <td class="p-3.5 text-right whitespace-nowrap">
                        <div class="flex items-center justify-end gap-1.5">
                          <NuxtLink
                            :to="`/blog/${post.slug}`"
                            target="_blank"
                            class="px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg text-xs font-bold transition shadow-2xs"
                          >
                            Lihat Web
                          </NuxtLink>
                          <button
                            @click="deleteBlogPost(post.id, post.title)"
                            :disabled="post.isDeleting"
                            class="px-2.5 py-1.5 bg-red-50 hover:bg-red-600 text-red-700 hover:text-white border border-red-200 rounded-lg text-xs font-bold transition shadow-2xs cursor-pointer disabled:opacity-50"
                            title="Hapus Artikel Blog"
                          >
                            <span v-if="post.isDeleting">Menghapus...</span>
                            <span v-else>Hapus</span>
                          </button>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
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

    <!-- MODAL TULIS ARTIKEL BARU (Admin & Super Admin) -->
    <div
      v-if="showCreatePostModal"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-xs animate-fade overflow-y-auto"
    >
      <div class="bg-white rounded-3xl max-w-2xl w-full p-6 sm:p-7 shadow-2xl border border-slate-200 relative my-8">
        <button
          @click="showCreatePostModal = false"
          class="absolute top-4 right-4 text-slate-400 hover:text-slate-700 text-sm font-bold cursor-pointer"
        >
          ✕
        </button>

        <div class="mb-5">
          <div class="inline-flex items-center gap-2 bg-blue-50 text-blue-700 text-[10px] font-black uppercase tracking-wider px-2.5 py-0.5 rounded-full mb-1">
            <span>Form Artikel Baru</span>
          </div>
          <h3 class="text-base font-black text-slate-900">Publikasikan Artikel Informasi Baru</h3>
          <p class="text-xs text-slate-500">Artikel akan langsung tayang pada halaman /blog website untuk dibaca oleh calon pelanggan.</p>
        </div>

        <form @submit.prevent="handleCreatePost" class="space-y-4 text-xs">
          <div>
            <label class="block font-bold text-slate-700 mb-1">Judul Artikel <span class="text-red-500">*</span></label>
            <input
              v-model="newPostForm.title"
              type="text"
              required
              placeholder="Contoh: Rekomendasi Rute Wisata Terbaik di Jawa Barat 2026"
              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 font-medium focus:ring-2 focus:ring-blue-600 focus:outline-none"
            />
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
              <label class="block font-bold text-slate-700 mb-1">Kategori Artikel</label>
              <select
                v-model="newPostForm.category_name"
                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 font-bold focus:ring-2 focus:ring-blue-600 focus:outline-none"
              >
                <option value="Tips & Destinasi">Tips & Destinasi Wisata</option>
                <option value="Panduan Sewa Bus">Panduan Sewa Bus</option>
                <option value="Info Layanan & Promo">Info Layanan & Promo</option>
                <option value="Berita SJ Trans">Berita SJ Trans</option>
              </select>
            </div>

            <div>
              <label class="block font-bold text-slate-700 mb-1">Status Publikasi</label>
              <select
                v-model="newPostForm.status"
                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 font-bold focus:ring-2 focus:ring-blue-600 focus:outline-none"
              >
                <option value="published">Langsung Tayang (Published)</option>
                <option value="draft">Simpan sebagai Draft</option>
              </select>
            </div>
          </div>

          <div>
            <label class="block font-bold text-slate-700 mb-1">URL Foto Sampul (Featured Image)</label>
            <input
              v-model="newPostForm.featured_image"
              type="url"
              placeholder="https://images.unsplash.com/photo-..."
              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs text-slate-800 focus:ring-2 focus:ring-blue-600 focus:outline-none font-mono"
            />
            <span class="text-[10px] text-slate-400 mt-1 block">Bisa gunakan link foto Unsplash bus/pariwisata atau biarkan default bila kosong.</span>
          </div>

          <div>
            <label class="block font-bold text-slate-700 mb-1">Ringkasan Singkat (Excerpt)</label>
            <textarea
              v-model="newPostForm.excerpt"
              rows="2"
              placeholder="Ringkasan 1-2 kalimat untuk preview kartu artikel..."
              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2 text-xs text-slate-800 focus:ring-2 focus:ring-blue-600 focus:outline-none resize-none"
            ></textarea>
          </div>

          <div>
            <label class="block font-bold text-slate-700 mb-1">Isi Konten Artikel <span class="text-red-500">*</span></label>
            <textarea
              v-model="newPostForm.content"
              required
              rows="6"
              placeholder="Tulis informasi rute atau panduan perjalanan bus pariwisata secara lengkap di sini..."
              class="w-full bg-slate-50 border border-slate-200 rounded-xl p-3 text-xs text-slate-800 focus:ring-2 focus:ring-blue-600 focus:outline-none"
            ></textarea>
          </div>

          <div class="flex items-center justify-end gap-2 pt-3 border-t border-slate-100">
            <button
              type="button"
              @click="showCreatePostModal = false"
              class="px-4 py-2.5 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-700 text-xs font-bold transition cursor-pointer"
            >
              Batal
            </button>
            <button
              type="submit"
              :disabled="isSubmittingPost"
              class="px-5 py-2.5 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold transition shadow-md cursor-pointer disabled:opacity-50 flex items-center gap-1.5"
            >
              <span v-if="isSubmittingPost">Menyimpan...</span>
              <span v-else>Publikasikan Artikel Sekarang</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
const router = useRouter()
const { user, isLoggedIn, isAdmin, isSuperAdmin, login, logout, token, initAuth } = useAuth()
const { locale, setLocale, t } = useI18n()
const { showToast } = useNotification()

const activeTab = ref('stats')
const mobileSidebarOpen = ref(false)
const currentTime = ref(new Date().toLocaleTimeString('id-ID'))
const isSyncing = ref(false)
let syncInterval: any = null

const stats = ref({
  total_bookings: 18,
  pending_transactions: 3,
  total_revenue: 68500000,
})

const blogPosts = ref<any[]>([])
const isLoadingPosts = ref(false)
const showCreatePostModal = ref(false)
const isSubmittingPost = ref(false)
const newPostForm = ref({
  title: '',
  category_name: 'Tips & Destinasi',
  featured_image: '',
  excerpt: '',
  content: '',
  status: 'published',
})

const bookings = ref<any[]>([
  {
    id: 1,
    booking_code: 'SJT-20261028-001',
    customer_name: 'Budi Santoso',
    customer_phone: '081234567890',
    customer_email: 'budi@gmail.com',
    fleet_name: 'Big Bus Scania K410IB',
    units: 1,
    origin: 'Jakarta',
    destination: 'Bandung & Sekitarnya',
    start_date: '2026-11-01',
    end_date: '2026-11-03',
    total_price: 12600000,
    status: 'pending',
    payment_status: 'down_payment',
  },
  {
    id: 2,
    booking_code: 'SJT-20261027-004',
    customer_name: 'PT Astra International Tbk',
    customer_phone: '08119888221',
    customer_email: 'procurement@astra.co.id',
    fleet_name: 'Mercedes-Benz OH 1626',
    units: 2,
    origin: 'Jakarta',
    destination: 'Yogyakarta',
    start_date: '2026-11-10',
    end_date: '2026-11-14',
    total_price: 30400000,
    status: 'confirmed',
    payment_status: 'paid',
  },
  {
    id: 3,
    booking_code: 'SJT-20261025-009',
    customer_name: 'SMA Negeri 8 Jakarta',
    customer_phone: '08138877665',
    customer_email: 'panitia@sman8jkt.sch.id',
    fleet_name: 'Medium Bus Jetbus 3+',
    units: 3,
    origin: 'Jakarta',
    destination: 'Puncak Bogor',
    start_date: '2026-10-29',
    end_date: '2026-10-30',
    total_price: 15000000,
    status: 'completed',
    payment_status: 'paid',
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
    email: 'admin@sjtrans.co.id',
    phone_number: '081122334455',
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

const getAuthHeaders = () => {
  const tkn = token.value || (typeof localStorage !== 'undefined' ? localStorage.getItem('sjtrans_auth_token') : null)
  return tkn ? { Authorization: `Bearer ${tkn}`, Accept: 'application/json' } : { Accept: 'application/json' }
}

const fetchAdminData = async (isBackground = false) => {
  if (isSyncing.value) return
  isSyncing.value = true

  try {
    // 1. Fetch live stats
    try {
      const statsRes: any = await $fetch('http://127.0.0.1:8000/api/v1/dashboard/stats', {
        headers: getAuthHeaders(),
      })
      if (statsRes?.metrics) {
        stats.value = {
          total_bookings: statsRes.metrics.total_bookings ?? stats.value.total_bookings,
          pending_transactions: transactions.value.filter(t => t.status === 'pending').length,
          total_revenue: statsRes.metrics.total_revenue ?? stats.value.total_revenue,
        }
      }
    } catch (e) {
      // Keep existing stats
    }

    // 2. Fetch live bookings from backend
    try {
      const bookingsRes: any = await $fetch('http://127.0.0.1:8000/api/v1/admin/bookings', {
        headers: getAuthHeaders(),
      })
      const rawList = bookingsRes?.data || bookingsRes || []
      const fetchedBookings: any[] = []

      if (Array.isArray(rawList)) {
        for (const b of rawList) {
          fetchedBookings.push({
            id: b.id,
            booking_code: b.booking_code,
            customer_name: b.customer_name,
            customer_phone: b.customer_phone,
            customer_email: b.customer_email,
            fleet_name: b.fleet?.name || b.fleet_name || 'Bus Pariwisata',
            units: b.units || 1,
            origin: b.origin || 'Jakarta',
            destination: b.destination,
            start_date: b.start_date,
            end_date: b.end_date,
            total_price: Number(b.total_price),
            status: b.status,
            payment_status: (b.payment_status === 'Lunas' || b.payment_status === 'paid') ? 'paid' : ((b.payment_status === 'dp_paid' || b.payment_status?.includes('DP') || b.payment_status === 'down_payment') ? 'down_payment' : (b.payment_status === 'refunded' ? 'refunded' : 'unpaid')),
          })
        }
      }

      // Merge with local bookings cache from localStorage so anything newly created appears instantly
      if (typeof localStorage !== 'undefined') {
        const localRaw = localStorage.getItem('sjtrans_local_bookings')
        if (localRaw) {
          try {
            const localList = JSON.parse(localRaw)
            if (Array.isArray(localList)) {
              for (const lb of localList) {
                const existing = fetchedBookings.find(item => item.booking_code === lb.booking_code)
                if (!existing) {
                  const lbPay = (lb.payment_status === 'Lunas' || lb.payment_status === 'paid') ? 'paid' : ((lb.payment_status === 'dp_paid' || lb.payment_status?.includes('DP') || lb.payment_status === 'down_payment') ? 'down_payment' : (lb.payment_status === 'refunded' ? 'refunded' : 'unpaid'))
                  fetchedBookings.unshift({
                    id: lb.id || Date.now(),
                    booking_code: lb.booking_code,
                    customer_name: lb.customer_name,
                    customer_phone: lb.customer_phone,
                    customer_email: lb.customer_email,
                    fleet_name: lb.fleet_name || 'Bus Pariwisata',
                    units: lb.units || 1,
                    origin: lb.origin || 'Jakarta',
                    destination: lb.destination,
                    start_date: lb.start_date,
                    end_date: lb.end_date,
                    total_price: Number(lb.total_price || 0),
                    status: lb.status || 'pending',
                    payment_status: lbPay,
                  })
                }
              }
            }
          } catch (e) {}
        }
      }

      if (fetchedBookings.length > 0) {
        bookings.value = fetchedBookings
        stats.value.total_bookings = fetchedBookings.length
      }
    } catch (e) {
      // Keep existing bookings
    }

    // 3. Fetch live transactions
    try {
      const txRes: any = await $fetch('http://127.0.0.1:8000/api/v1/admin/transactions', {
        headers: getAuthHeaders(),
      })
      const rawTxList = txRes?.data || txRes || []
      const fetchedTx: any[] = []

      if (Array.isArray(rawTxList)) {
        for (const tx of rawTxList) {
          fetchedTx.push({
            id: tx.id,
            invoice_number: tx.invoice_number,
            amount: Number(tx.amount),
            payment_method: tx.payment_method,
            sender_name: tx.sender_name || tx.booking?.customer_name || 'Pelanggan SJ Trans',
            sender_bank: tx.sender_bank || 'Transfer Bank',
            proof_url: tx.payment_proof_url || 'https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?auto=format&fit=crop&w=800&q=80',
            status: tx.status,
            booking_id: tx.booking_id,
          })
        }
      }

      if (fetchedTx.length > 0) {
        transactions.value = fetchedTx
        stats.value.pending_transactions = fetchedTx.filter(t => t.status === 'pending').length
      }
    } catch (e) {}

    // 4. Fetch fleets
    try {
      const fleetRes: any = await $fetch('http://127.0.0.1:8000/api/v1/fleets')
      const rawFleets = fleetRes?.data || fleetRes || []
      if (Array.isArray(rawFleets) && rawFleets.length > 0) {
        fleets.value = rawFleets.map((f: any) => ({
          id: f.id,
          name: f.name,
          seats: f.seats,
          price_per_day: Number(f.price_per_day),
          image_url: f.image_url || 'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?auto=format&fit=crop&w=800&q=80',
        }))
      }
    } catch (e) {}

    // 5. Fetch discounts
    try {
      const discRes: any = await $fetch('http://127.0.0.1:8000/api/v1/discounts')
      const rawDiscounts = discRes?.data || discRes || []
      if (Array.isArray(rawDiscounts) && rawDiscounts.length > 0) {
        discounts.value = rawDiscounts.map((d: any) => ({
          id: d.id,
          code: d.code,
          name: d.name,
          discount_type: d.discount_type,
          value: Number(d.value),
        }))
      }
    } catch (e) {}

    // 6. Fetch users (Super Admin only)
    if (isSuperAdmin.value) {
      try {
        const usersRes: any = await $fetch('http://127.0.0.1:8000/api/v1/admin/users', {
          headers: getAuthHeaders(),
        })
        const rawUsers = usersRes?.data || usersRes || []
        if (Array.isArray(rawUsers) && rawUsers.length > 0) {
          users.value = rawUsers
        }
      } catch (e) {}
    }

    // 7. Fetch blog posts
    await fetchBlogPosts()

    currentTime.value = new Date().toLocaleTimeString('id-ID')
    if (!isBackground) {
      showToast('success', 'Data Sinkron', 'Data pemesanan customer, armada & transaksi berhasil diperbarui!')
    }
  } catch (err) {
    console.warn('Sync error:', err)
  } finally {
    isSyncing.value = false
  }
}

onMounted(() => {
  initAuth()
  if (isAdmin.value) {
    fetchAdminData(true)
    syncInterval = setInterval(() => {
      if (isAdmin.value) {
        fetchAdminData(true)
      }
    }, 15000)
  }
})

onUnmounted(() => {
  if (syncInterval) {
    clearInterval(syncInterval)
    syncInterval = null
  }
})

watch(isAdmin, (newVal) => {
  if (newVal) {
    fetchAdminData(false)
    if (!syncInterval) {
      syncInterval = setInterval(() => {
        if (isAdmin.value) fetchAdminData(true)
      }, 15000)
    }
  } else if (syncInterval) {
    clearInterval(syncInterval)
    syncInterval = null
  }
})

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
  } else {
    fetchAdminData(false)
  }
}

const quickLogin = async (username: string, pass: string) => {
  loginErrorMessage.value = ''
  const res = await login(username, pass)
  if (res.success) {
    fetchAdminData(false)
  }
}

const handleLogout = async () => {
  if (syncInterval) {
    clearInterval(syncInterval)
    syncInterval = null
  }
  await logout()
  router.push('/')
}

const openCreatePostModal = () => {
  newPostForm.value = {
    title: '',
    category_name: 'Tips & Destinasi',
    featured_image: '',
    excerpt: '',
    content: '',
    status: 'published',
  }
  showCreatePostModal.value = true
}

const fetchBlogPosts = async () => {
  isLoadingPosts.value = true
  try {
    const res: any = await $fetch('http://127.0.0.1:8000/api/v1/posts', {
      headers: getAuthHeaders(),
    })
    const list = res?.data || res || []
    if (Array.isArray(list)) {
      blogPosts.value = list.filter((p: any) => p.type !== 'page')
    }
  } catch (e) {
    console.warn('Fetch blog posts error:', e)
  } finally {
    isLoadingPosts.value = false
  }
}

const handleCreatePost = async () => {
  if (!newPostForm.value.title.trim() || !newPostForm.value.content.trim()) {
    showToast('error', 'Gagal', 'Judul dan isi artikel wajib diisi!')
    return
  }

  isSubmittingPost.value = true
  try {
    const res: any = await $fetch('http://127.0.0.1:8000/api/v1/posts', {
      method: 'POST',
      headers: getAuthHeaders(),
      body: {
        title: newPostForm.value.title,
        content: newPostForm.value.content,
        excerpt: newPostForm.value.excerpt || undefined,
        featured_image: newPostForm.value.featured_image || 'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?auto=format&fit=crop&w=800&q=80',
        type: 'post',
        status: newPostForm.value.status,
      },
    })

    if (res?.data) {
      blogPosts.value.unshift(res.data)
    } else {
      await fetchBlogPosts()
    }

    showCreatePostModal.value = false
    showToast('success', 'Artikel Diterbitkan', `Artikel "${newPostForm.value.title}" berhasil dipublikasikan ke blog!`)
  } catch (err: any) {
    showToast('error', 'Gagal Menyimpan', err?.data?.message || 'Terjadi kesalahan saat mempublikasikan artikel.')
  } finally {
    isSubmittingPost.value = false
  }
}

const deleteBlogPost = async (id: number, title: string) => {
  if (!confirm(`Apakah Anda yakin ingin menghapus artikel "${title}"?`)) return

  const target = blogPosts.value.find(p => p.id === id)
  if (target) target.isDeleting = true

  try {
    await $fetch(`http://127.0.0.1:8000/api/v1/posts/${id}`, {
      method: 'DELETE',
      headers: getAuthHeaders(),
    })
    blogPosts.value = blogPosts.value.filter(p => p.id !== id)
    showToast('info', 'Artikel Dihapus', `Artikel "${title}" telah dihapus dari blog.`)
  } catch (err: any) {
    showToast('error', 'Gagal Menghapus', err?.data?.message || 'Gagal menghapus artikel.')
    if (target) target.isDeleting = false
  }
}

const saveBookingStatus = async (b: any) => {
  b.isSaving = true

  let payStatus = b.payment_status || 'unpaid'
  if (payStatus === 'Menunggu Pembayaran') payStatus = 'unpaid'
  if (payStatus === 'DP Terbayar (50%)' || payStatus === 'dp_paid' || payStatus.includes('DP')) payStatus = 'down_payment'
  if (payStatus === 'Lunas') payStatus = 'paid'

  try {
    await $fetch(`http://127.0.0.1:8000/api/v1/bookings/${b.id}/status`, {
      method: 'PUT',
      headers: getAuthHeaders(),
      body: {
        status: b.status,
        payment_status: payStatus,
      },
    })
  } catch (e: any) {
    console.warn('Backend status update warning:', e)
  }

  // Update in local storage sjtrans_local_bookings
  if (typeof localStorage !== 'undefined') {
    const localRaw = localStorage.getItem('sjtrans_local_bookings')
    if (localRaw) {
      try {
        const list = JSON.parse(localRaw)
        const loc = list.find((item: any) => item.id === b.id || item.booking_code === b.booking_code)
        if (loc) {
          loc.status = b.status
          loc.payment_status = b.payment_status
          localStorage.setItem('sjtrans_local_bookings', JSON.stringify(list))
        }
      } catch (e) {}
    }
  }

  b.isSaving = false
  const payLabel = b.payment_status === 'down_payment' ? 'DP 50% (Uang Muka)' : (b.payment_status === 'paid' ? 'Lunas 100%' : (b.payment_status === 'refunded' ? 'Dikembalikan' : 'Belum Bayar (0%)'))
  showToast('success', 'Status Berhasil Dikonfirmasi', `Pemesanan ${b.booking_code} dikonfirmasi: Status "${b.status}", Pembayaran "${payLabel}"`)
}

const deleteBooking = async (id: number) => {
  if (!confirm('Apakah Anda yakin ingin menghapus pemesanan ini secara permanen?')) return

  try {
    await $fetch(`http://127.0.0.1:8000/api/v1/bookings/${id}`, {
      method: 'DELETE',
      headers: getAuthHeaders(),
    })
  } catch (e) {}

  bookings.value = bookings.value.filter(b => b.id !== id)
  stats.value.total_bookings = bookings.value.length

  if (typeof localStorage !== 'undefined') {
    const localRaw = localStorage.getItem('sjtrans_local_bookings')
    if (localRaw) {
      try {
        let list = JSON.parse(localRaw)
        list = list.filter((b: any) => b.id !== id)
        localStorage.setItem('sjtrans_local_bookings', JSON.stringify(list))
      } catch (e) {}
    }
  }

  showToast('info', 'Pemesanan Dihapus', 'Data reservasi berhasil dihapus secara permanen.')
}

const verifyTransaction = async (id: number, status: 'verified' | 'rejected') => {
  try {
    await $fetch(`http://127.0.0.1:8000/api/v1/transactions/${id}/verify`, {
      method: 'PUT',
      headers: getAuthHeaders(),
      body: { status },
    })
  } catch (e) {}

  const tx = transactions.value.find(t => t.id === id)
  if (tx) {
    tx.status = status
  }

  if (status === 'verified') {
    const relatedBooking = bookings.value.find(b => b.id === tx?.booking_id || (tx && b.booking_code?.includes(tx.invoice_number?.split('-')?.[1] || '')))
    if (relatedBooking) {
      relatedBooking.status = 'confirmed'
      relatedBooking.payment_status = 'paid'
    }
  }

  stats.value.pending_transactions = transactions.value.filter(t => t.status === 'pending').length
  showToast(
    'success',
    'Verifikasi Transaksi',
    status === 'verified'
      ? 'Pembayaran berhasil diverifikasi Lunas! Tiket pelanggan otomatis terkonfirmasi.'
      : 'Bukti transfer pembayaran ditolak.'
  )
}

const deleteFleet = async (id: number) => {
  if (!confirm('Hapus armada ini dari daftar aktif?')) return

  try {
    await $fetch(`http://127.0.0.1:8000/api/v1/fleets/${id}`, {
      method: 'DELETE',
      headers: getAuthHeaders(),
    })
  } catch (e) {}

  fleets.value = fleets.value.filter(f => f.id !== id)
  showToast('info', 'Armada Dihapus', 'Armada bus telah dihapus dari katalog.')
}

const deleteDiscount = async (id: number) => {
  if (!confirm('Hapus kupon promo ini?')) return

  try {
    await $fetch(`http://127.0.0.1:8000/api/v1/discounts/${id}`, {
      method: 'DELETE',
      headers: getAuthHeaders(),
    })
  } catch (e) {}

  discounts.value = discounts.value.filter(d => d.id !== id)
  showToast('info', 'Kupon Dihapus', 'Kupon promo berhasil dihapus.')
}

const toggleUserRole = async (targetUser: any) => {
  const currentRole = targetUser.role?.name || 'customer'
  const newRoleName = currentRole === 'admin' ? 'customer' : 'admin'

  try {
    const res: any = await $fetch(`http://127.0.0.1:8000/api/v1/admin/users/${targetUser.id}/role`, {
      method: 'PUT',
      headers: getAuthHeaders(),
      body: { role_name: newRoleName },
    })

    if (res?.user?.role) {
      targetUser.role = res.user.role
    } else {
      targetUser.role = {
        id: newRoleName === 'admin' ? 2 : 3,
        name: newRoleName,
        display_name: newRoleName === 'admin' ? 'Admin Operasional' : 'Customer',
      }
    }
    showToast('success', 'Hak Akses Diperbarui', res.message || `Hak akses ${targetUser.name} diubah menjadi ${targetUser.role.display_name}!`)
  } catch (err: any) {
    targetUser.role = {
      id: newRoleName === 'admin' ? 2 : 3,
      name: newRoleName,
      display_name: newRoleName === 'admin' ? 'Admin Operasional' : 'Customer',
    }
    showToast('info', 'Hak Akses Diperbarui', `Hak akses ${targetUser.name} diubah menjadi ${targetUser.role.display_name}!`)
  }
}
</script>
