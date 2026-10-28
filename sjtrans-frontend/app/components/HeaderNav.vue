<template>
  <header class="sticky top-0 z-40 bg-white/95 backdrop-blur-md shadow-sm border-b border-slate-100 transition-all">
    <!-- Topbar: Kontak, Hotline, Language Switcher, Theme Switcher, Auth -->
    <div class="bg-slate-900 text-slate-200 text-xs py-2 px-4 border-b border-slate-800">
      <div class="container-custom flex flex-wrap justify-between items-center gap-3">
        <!-- Hotline & WhatsApp -->
        <div class="flex items-center gap-4 sm:gap-6">
          <a href="tel:1500888" class="flex items-center gap-2 hover:text-white transition">
            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
            <span>{{ t('hotline.title') }}</span>
            <strong class="text-white font-bold tracking-wide">1500-888</strong>
          </a>
          <a href="https://wa.me/6281288887788" target="_blank" class="hidden sm:flex items-center gap-1.5 text-emerald-400 hover:text-emerald-300 font-medium">
            <span>WhatsApp: 0812-8888-7788</span>
          </a>
        </div>

        <!-- Right Side: Language Switcher, Theme Switcher & Auth Status -->
        <div class="flex items-center gap-3 sm:gap-4">
          <!-- Bilingual Language Switcher (ID / EN) -->
          <div class="flex items-center bg-slate-800 rounded-full p-0.5 border border-slate-700">
            <button
              @click="setLocale('id')"
              class="px-2 py-0.5 rounded-full text-[11px] font-bold transition cursor-pointer flex items-center gap-1"
              :class="locale === 'id' ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-400 hover:text-white'"
              title="Bahasa Indonesia"
            >
              <span>🇮🇩</span>
              <span>ID</span>
            </button>
            <button
              @click="setLocale('en')"
              class="px-2 py-0.5 rounded-full text-[11px] font-bold transition cursor-pointer flex items-center gap-1"
              :class="locale === 'en' ? 'bg-blue-600 text-white shadow-xs' : 'text-slate-400 hover:text-white'"
              title="English"
            >
              <span>🇬🇧</span>
              <span>EN</span>
            </button>
          </div>

          <!-- Theme Switcher Dropdown (Tone Warna Biru & Presets) -->
          <div class="relative group">
            <button class="flex items-center gap-1.5 bg-slate-800 hover:bg-slate-700 text-slate-100 px-2.5 sm:px-3 py-1 rounded-full border border-slate-700 transition cursor-pointer">
              <span class="w-2.5 h-2.5 rounded-full" :style="{ backgroundColor: primaryColor }"></span>
              <span class="font-medium text-[11px] hidden sm:inline">{{ t('theme.color') }}</span>
              <svg class="w-3 h-3 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
            </button>

            <!-- Dropdown Menu -->
            <div class="absolute right-0 mt-1 w-56 bg-white text-slate-800 rounded-xl shadow-xl border border-slate-100 p-2 opacity-0 pointer-events-none group-hover:opacity-100 group-hover:pointer-events-auto transition-all duration-200 z-50">
              <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider px-2 py-1">{{ t('theme.choose') }}</div>
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
                <span class="text-slate-500">{{ t('theme.custom') }}</span>
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
          <div v-if="isLoggedIn && user" class="flex items-center gap-2 sm:gap-3">
            <span class="text-slate-400 hidden md:inline">
              {{ t('greeting') }}, <strong class="text-white">{{ user.name }}</strong>
              <span class="text-[10px] ml-1 px-1.5 py-0.5 rounded" :class="isSuperAdmin ? 'bg-purple-900/60 text-purple-300' : (isAdmin ? 'bg-blue-900/60 text-blue-300' : 'bg-slate-700 text-slate-300')">
                {{ user.role.display_name }}
              </span>
            </span>

            <!-- Dedicated Page Links based on Role -->
            <NuxtLink
              v-if="isAdmin"
              to="/admin"
              class="bg-blue-600 hover:bg-blue-500 text-white px-2.5 py-1 rounded text-[11px] font-bold transition flex items-center gap-1 shadow-sm"
            >
              <span>⚙️</span>
              <span>{{ t('admin_panel') }}</span>
            </NuxtLink>
            <NuxtLink
              v-else
              to="/customer"
              class="bg-slate-700 hover:bg-slate-600 text-white px-2.5 py-1 rounded text-[11px] font-bold transition flex items-center gap-1 shadow-sm"
            >
              <span>👤</span>
              <span>{{ t('customer_portal') }}</span>
            </NuxtLink>

            <button
              @click="handleLogout"
              class="text-red-400 hover:text-red-300 transition text-[11px] font-semibold cursor-pointer"
            >
              {{ t('logout') }}
            </button>
          </div>
          <div v-else class="flex items-center gap-2">
            <button
              @click="$emit('open-auth', 'login')"
              class="text-slate-200 hover:text-white font-semibold transition cursor-pointer"
            >
              {{ t('login') }}
            </button>
            <span class="text-slate-600">|</span>
            <button
              @click="$emit('open-auth', 'register')"
              class="text-blue-400 hover:text-blue-300 font-semibold transition cursor-pointer"
            >
              {{ t('register') }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Navigation Bar (Clean & Focused) -->
    <div class="container-custom py-3 flex justify-between items-center">
      <!-- Logo SJ Trans -->
      <NuxtLink to="/" class="flex items-center gap-3 group">
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
      </NuxtLink>

      <!-- Desktop Nav Menu (Ringkas & Efektif: Beranda, Armada Bus, Blog, Tentang Kami) -->
      <nav class="hidden lg:flex items-center gap-8 text-sm font-semibold text-slate-700">
        <NuxtLink
          to="/"
          class="hover:text-blue-700 transition relative py-1"
          :class="{ 'font-bold': route.path === '/' }"
          :style="route.path === '/' ? { color: primaryColor } : {}"
        >
          <span>{{ t('nav.home') }}</span>
          <span v-if="route.path === '/'" class="absolute bottom-0 left-0 right-0 h-0.5 rounded-full" :style="{ backgroundColor: primaryColor }"></span>
        </NuxtLink>

        <NuxtLink
          to="/armada"
          class="hover:text-blue-700 transition relative py-1 flex items-center gap-1.5"
          :class="{ 'font-bold': route.path === '/armada' }"
          :style="route.path === '/armada' ? { color: primaryColor } : {}"
        >
          <span>{{ t('nav.fleets') }}</span>
          <span class="bg-blue-100 text-blue-700 text-[10px] font-bold px-1.5 py-0.2 rounded-full uppercase">VIP</span>
          <span v-if="route.path === '/armada'" class="absolute bottom-0 left-0 right-0 h-0.5 rounded-full" :style="{ backgroundColor: primaryColor }"></span>
        </NuxtLink>

        <NuxtLink
          to="/blog"
          class="hover:text-blue-700 transition relative py-1"
          :class="{ 'font-bold': route.path.startsWith('/blog') }"
          :style="route.path.startsWith('/blog') ? { color: primaryColor } : {}"
        >
          <span>{{ t('nav.blog') }}</span>
          <span v-if="route.path.startsWith('/blog')" class="absolute bottom-0 left-0 right-0 h-0.5 rounded-full" :style="{ backgroundColor: primaryColor }"></span>
        </NuxtLink>

        <NuxtLink
          to="/tentang-kami"
          class="hover:text-blue-700 transition relative py-1"
          :class="{ 'font-bold': route.path === '/tentang-kami' }"
          :style="route.path === '/tentang-kami' ? { color: primaryColor } : {}"
        >
          <span>{{ t('nav.about') }}</span>
          <span v-if="route.path === '/tentang-kami'" class="absolute bottom-0 left-0 right-0 h-0.5 rounded-full" :style="{ backgroundColor: primaryColor }"></span>
        </NuxtLink>
      </nav>

      <!-- Action Buttons -->
      <div class="hidden sm:flex items-center gap-3">
        <a
          href="tel:1500888"
          class="btn-outline text-xs py-2 px-3.5 rounded-full flex items-center gap-1.5 font-bold"
          :style="{ borderColor: primaryColor, color: primaryColor }"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" /></svg>
          <span>{{ t('call_center') }}</span>
        </a>

        <button
          @click="handleBookBusClick"
          class="btn-primary text-xs py-2.5 px-5 rounded-full cursor-pointer shadow-md hover:shadow-lg transition flex items-center gap-1.5"
          :style="{ backgroundColor: primaryColor }"
        >
          <span>{{ t('book_bus') }}</span>
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
        </button>
      </div>

      <!-- Mobile Hamburger Button -->
      <div class="lg:hidden flex items-center gap-2">
        <button
          @click="mobileMenuOpen = !mobileMenuOpen"
          class="p-2 rounded-xl text-slate-700 hover:bg-slate-100 transition cursor-pointer"
          aria-label="Menu"
        >
          <svg v-if="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7" /></svg>
          <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
        </button>
      </div>
    </div>

    <!-- Mobile Drawer Menu -->
    <div v-if="mobileMenuOpen" class="lg:hidden bg-white border-t border-slate-100 py-4 px-6 space-y-3 shadow-xl animate-fade">
      <NuxtLink
        to="/"
        @click="mobileMenuOpen = false"
        class="block py-2 font-semibold text-slate-800 border-b border-slate-50 flex items-center justify-between"
        :class="{ 'text-blue-600 font-bold': route.path === '/' }"
      >
        <span>{{ t('nav.home') }}</span>
        <span class="text-xs text-slate-400">➔</span>
      </NuxtLink>

      <NuxtLink
        to="/armada"
        @click="mobileMenuOpen = false"
        class="block py-2 font-semibold text-slate-800 border-b border-slate-50 flex items-center justify-between"
        :class="{ 'text-blue-600 font-bold': route.path === '/armada' }"
      >
        <div class="flex items-center gap-2">
          <span>{{ t('nav.fleets') }}</span>
          <span class="bg-blue-100 text-blue-700 text-[10px] font-bold px-1.5 py-0.5 rounded">VIP</span>
        </div>
        <span class="text-xs text-slate-400">➔</span>
      </NuxtLink>

      <NuxtLink
        to="/blog"
        @click="mobileMenuOpen = false"
        class="block py-2 font-semibold text-slate-800 border-b border-slate-50 flex items-center justify-between"
        :class="{ 'text-blue-600 font-bold': route.path.startsWith('/blog') }"
      >
        <span>{{ t('nav.blog') }}</span>
        <span class="text-xs text-slate-400">➔</span>
      </NuxtLink>

      <NuxtLink
        to="/tentang-kami"
        @click="mobileMenuOpen = false"
        class="block py-2 font-semibold text-slate-800 border-b border-slate-50 flex items-center justify-between"
        :class="{ 'text-blue-600 font-bold': route.path === '/tentang-kami' }"
      >
        <span>{{ t('nav.about') }}</span>
        <span class="text-xs text-slate-400">➔</span>
      </NuxtLink>

      <!-- Quick Role Action inside Mobile Drawer -->
      <div v-if="isLoggedIn && user" class="pt-2 border-t border-slate-100">
        <NuxtLink
          v-if="isAdmin"
          to="/admin"
          @click="mobileMenuOpen = false"
          class="block py-2.5 px-4 rounded-xl bg-blue-50 text-blue-700 font-bold text-center text-xs mb-2"
        >
          ⚙️ {{ t('admin_panel') }}
        </NuxtLink>
        <NuxtLink
          v-else
          to="/customer"
          @click="mobileMenuOpen = false"
          class="block py-2.5 px-4 rounded-xl bg-slate-100 text-slate-800 font-bold text-center text-xs mb-2"
        >
          👤 {{ t('customer_portal') }}
        </NuxtLink>
      </div>

      <div class="pt-2 flex flex-col gap-2">
        <button
          @click="handleBookBusClick(); mobileMenuOpen = false"
          class="btn-primary w-full py-2.5 text-center justify-center shadow-md font-bold"
          :style="{ backgroundColor: primaryColor }"
        >
          {{ t('book_bus') }}
        </button>
      </div>
    </div>
  </header>
</template>

<script setup lang="ts">
const route = useRoute()
const router = useRouter()
const { currentPreset, primaryColor, presets, selectPreset, setCustomHex } = useTheme()
const { user, isLoggedIn, isAdmin, isSuperAdmin, logout } = useAuth()
const { locale, setLocale, t } = useI18n()

const emit = defineEmits(['open-auth', 'scroll-to-booking'])

const mobileMenuOpen = ref(false)

const handleBookBusClick = () => {
  if (route.path !== '/') {
    router.push('/#beranda')
  } else {
    emit('scroll-to-booking')
  }
}

const handleLogout = async () => {
  await logout()
  if (route.path.startsWith('/admin') || route.path.startsWith('/customer')) {
    router.push('/')
  }
}
</script>
