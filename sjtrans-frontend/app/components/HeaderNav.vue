<template>
  <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md shadow-sm border-b border-slate-100 transition-all">
    <!-- Topbar: Kontak, Hotline, Theme Switcher, Auth -->
    <div class="bg-slate-900 text-slate-200 text-xs py-2 px-4 border-b border-slate-800">
      <div class="container-custom flex flex-wrap justify-between items-center gap-3">
        <!-- Hotline & WhatsApp -->
        <div class="flex items-center gap-6">
          <a href="tel:1500888" class="flex items-center gap-2 hover:text-white transition">
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
            <span>Hotline 24/7:</span>
            <strong class="text-white font-bold tracking-wide">1500-888</strong>
          </a>
          <a href="https://wa.me/6281288887788" target="_blank" class="hidden sm:flex items-center gap-1.5 text-emerald-400 hover:text-emerald-300 font-medium">
            <span>WhatsApp: 0812-8888-7788</span>
          </a>
        </div>

        <!-- Right Side: Theme Switcher & Auth Status -->
        <div class="flex items-center gap-4">
          <!-- Theme Switcher Dropdown (Tone Warna Biru & Presets) -->
          <div class="relative group">
            <button class="flex items-center gap-1.5 bg-slate-800 hover:bg-slate-700 text-slate-100 px-3 py-1 rounded-full border border-slate-700 transition cursor-pointer">
              <span class="w-2.5 h-2.5 rounded-full" :style="{ backgroundColor: primaryColor }"></span>
              <span class="font-medium text-[11px]">Tema Warna</span>
              <svg class="w-3 h-3 ml-0.5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
            </button>

            <!-- Dropdown Menu -->
            <div class="absolute right-0 mt-1 w-56 bg-white text-slate-800 rounded-xl shadow-xl border border-slate-100 p-2 opacity-0 pointer-events-none group-hover:opacity-100 group-hover:pointer-events-auto transition-all duration-200 z-50">
              <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider px-2 py-1">Pilih Tone Warna</div>
              <div
                v-for="preset in presets"
                :key="preset.id"
                @click="selectPreset(preset.id)"
                class="flex items-center justify-between px-2.5 py-1.5 rounded-lg text-xs cursor-pointer hover:bg-slate-50 transition"
                :class="{ 'bg-blue-50 font-bold text-blue-800': currentPreset === preset.id }"
              >
                <div class="flex items-center gap-2">
                  <span class="w-3.5 h-3.5 rounded-full border border-white shadow-sm" :style="{ backgroundColor: preset.primary }"></span>
                  <span>{{ preset.name }}</span>
                </div>
                <span v-if="currentPreset === preset.id" class="text-blue-600 font-bold">✓</span>
              </div>

              <!-- Custom Color Picker Input -->
              <div class="mt-2 pt-2 border-t border-slate-100 px-2 flex items-center justify-between text-xs">
                <span class="text-slate-500">Warna Kustom:</span>
                <input
                  type="color"
                  :value="primaryColor"
                  @input="(e: any) => setCustomHex(e.target.value)"
                  class="w-6 h-6 rounded cursor-pointer border-0 bg-transparent"
                  title="Pilih warna heksadesimal sesuka hati"
                />
              </div>
            </div>
          </div>

          <!-- Auth Account Status -->
          <div v-if="isLoggedIn && user" class="flex items-center gap-3">
            <span class="text-slate-400 hidden md:inline">Halo, <strong class="text-white">{{ user.name }}</strong> ({{ user.role.display_name }})</span>
            <button
              v-if="isAdmin"
              @click="$emit('open-admin')"
              class="bg-blue-600 hover:bg-blue-500 text-white px-2.5 py-1 rounded text-[11px] font-bold transition cursor-pointer"
            >
              Panel Admin
            </button>
            <button
              v-else
              @click="$emit('open-my-bookings')"
              class="bg-slate-700 hover:bg-slate-600 text-white px-2.5 py-1 rounded text-[11px] font-bold transition cursor-pointer"
            >
              Pesanan Saya
            </button>
            <button
              @click="logout"
              class="text-red-400 hover:text-red-300 transition text-[11px] font-semibold cursor-pointer"
            >
              Keluar
            </button>
          </div>
          <div v-else class="flex items-center gap-2">
            <button
              @click="$emit('open-auth', 'login')"
              class="text-slate-200 hover:text-white font-semibold transition cursor-pointer"
            >
              Masuk
            </button>
            <span class="text-slate-600">|</span>
            <button
              @click="$emit('open-auth', 'register')"
              class="text-blue-400 hover:text-blue-300 font-semibold transition cursor-pointer"
            >
              Daftar
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Navigation Bar -->
    <div class="container-custom py-3.5 flex justify-between items-center">
      <!-- Logo SJ Trans (Blue & Chrome transport emblem) -->
      <a href="#" class="flex items-center gap-3 group">
        <div
          class="w-11 h-11 rounded-2xl flex items-center justify-center text-white font-extrabold text-xl shadow-md transition transform group-hover:scale-105"
          :style="{ backgroundColor: primaryColor }"
        >
          SJ
        </div>
        <div class="flex flex-col">
          <div class="flex items-center gap-1.5">
            <span class="text-2xl font-black tracking-tight text-slate-900">SJ<span :style="{ color: primaryColor }">TRANS</span></span>
            <span class="bg-blue-100 text-blue-800 text-[10px] font-black uppercase px-1.5 py-0.5 rounded">Bus Tour</span>
          </div>
          <span class="text-[11px] font-medium text-slate-500 tracking-wide">Semesta Jaya Transport Indonesia</span>
        </div>
      </a>

      <!-- Desktop Nav Menu -->
      <nav class="hidden lg:flex items-center gap-7 text-sm font-semibold text-slate-700">
        <a href="#beranda" class="hover:text-blue-700 transition" :style="{ color: currentNav === 'beranda' ? primaryColor : '' }">Beranda</a>
        <a href="#armada" class="hover:text-blue-700 transition">Pilihan Armada</a>
        <a href="#keunggulan" class="hover:text-blue-700 transition">Keunggulan</a>
        <a href="#promo" class="hover:text-blue-700 transition flex items-center gap-1.5">
          <span>Promo</span>
          <span class="bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.2 rounded-full uppercase animate-pulse">Hemat</span>
        </a>
        <a href="#testimoni" class="hover:text-blue-700 transition">Testimoni</a>
        <a href="#kemitraan" class="hover:text-blue-700 transition">Kemitraan</a>
        <a href="#faq" class="hover:text-blue-700 transition">Bantuan & FAQ</a>
      </nav>

      <!-- Action Buttons -->
      <div class="hidden sm:flex items-center gap-3">
        <a
          href="tel:1500888"
          class="btn-outline text-xs py-2 px-4 rounded-full flex items-center gap-2"
          :style="{ borderColor: primaryColor, color: primaryColor }"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
          <span>Call Center</span>
        </a>

        <button
          @click="$emit('scroll-to-booking')"
          class="btn-primary text-xs py-2.5 px-6 rounded-full cursor-pointer"
          :style="{ backgroundColor: primaryColor }"
        >
          <span>Reservasi Bus</span>
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
        </button>
      </div>

      <!-- Mobile Hamburger Button -->
      <button
        @click="mobileMenuOpen = !mobileMenuOpen"
        class="lg:hidden p-2 rounded-xl text-slate-700 hover:bg-slate-100 transition"
      >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" /></svg>
      </button>
    </div>

    <!-- Mobile Drawer Menu -->
    <div v-if="mobileMenuOpen" class="lg:hidden bg-white border-t border-slate-100 py-4 px-6 space-y-3 shadow-lg">
      <a @click="mobileMenuOpen = false" href="#beranda" class="block py-2 font-semibold text-slate-800">Beranda</a>
      <a @click="mobileMenuOpen = false" href="#armada" class="block py-2 font-semibold text-slate-800">Pilihan Armada</a>
      <a @click="mobileMenuOpen = false" href="#keunggulan" class="block py-2 font-semibold text-slate-800">Keunggulan</a>
      <a @click="mobileMenuOpen = false" href="#promo" class="block py-2 font-semibold text-slate-800">Promo Diskon</a>
      <a @click="mobileMenuOpen = false" href="#testimoni" class="block py-2 font-semibold text-slate-800">Testimoni</a>
      <a @click="mobileMenuOpen = false" href="#kemitraan" class="block py-2 font-semibold text-slate-800">Kemitraan</a>
      <a @click="mobileMenuOpen = false" href="#faq" class="block py-2 font-semibold text-slate-800">Bantuan & FAQ</a>
      <div class="pt-2 border-t border-slate-100 flex flex-col gap-2">
        <button
          @click="$emit('scroll-to-booking'); mobileMenuOpen = false"
          class="btn-primary w-full py-2.5 text-center justify-center"
          :style="{ backgroundColor: primaryColor }"
        >
          Reservasi Sekarang
        </button>
      </div>
    </div>
  </header>
</template>

<script setup lang="ts">
const { currentPreset, primaryColor, presets, selectPreset, setCustomHex } = useTheme()
const { user, isLoggedIn, isAdmin, isSuperAdmin, logout } = useAuth()

defineEmits(['open-auth', 'open-admin', 'open-my-bookings', 'scroll-to-booking'])

const currentNav = ref('beranda')
const mobileMenuOpen = ref(false)
</script>
