<template>
  <footer class="bg-slate-950 text-slate-300 pt-16 pb-12 border-t border-slate-800">
    <div class="container-custom space-y-12">
      <!-- 4 Columns Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">
        <!-- Kolom 1: Profil SJ Trans & Legalitas -->
        <div class="space-y-4">
          <div class="flex items-center gap-3">
            <div
              class="w-10 h-10 rounded-2xl flex items-center justify-center text-white font-extrabold text-lg shadow-md"
              :style="{ backgroundColor: primaryColor }"
            >
              SJ
            </div>
            <div>
              <span class="text-xl font-black text-white tracking-tight">SJ<span :style="{ color: primaryColor }">TRANS</span></span>
              <p class="text-[10px] text-slate-400">PT Semesta Jaya Transport</p>
            </div>
          </div>

          <p class="text-xs text-slate-400 leading-relaxed">
            {{ t('footer.desc') }}
          </p>

          <!-- Izin Kemenhub & Legalitas -->
          <div class="bg-slate-900 border border-slate-800 rounded-2xl p-3 text-[11px] text-slate-400 space-y-1">
            <div class="font-bold text-slate-200">Legalitas Resmi:</div>
            <div>Izin Kemenhub RI: <span class="text-blue-400">SK.551/AJ.205/DJP/2022</span></div>
            <div>Asuransi Jasa Raharja: <span class="text-blue-400">JR-08871/2023</span></div>
          </div>
        </div>

        <!-- Kolom 2: Kontak & Alamat Lengkap -->
        <div class="space-y-4">
          <h4 class="text-sm font-bold text-white uppercase tracking-wider border-b border-slate-800 pb-2">
            Kontak & Alamat
          </h4>

          <div class="space-y-3 text-xs text-slate-400">
            <!-- Kantor Pusat -->
            <div>
              <div class="font-semibold text-slate-200">Kantor Pusat Operasional:</div>
              <p class="mt-0.5">Gedung Cyber 2 Lantai 15, Jl. HR Rasuna Said, Jakarta Selatan 12950</p>
            </div>

            <!-- Pool Armada -->
            <div>
              <div class="font-semibold text-slate-200">Pool Utama Bus:</div>
              <p class="mt-0.5">KM 26 Tol Jagorawi, Cibubur, Jakarta Timur 13720</p>
            </div>

            <!-- No Telpon & Hotline -->
            <div class="pt-2 border-t border-slate-800 space-y-1">
              <div>
                <span class="text-slate-500">Hotline 24 Jam:</span>
                <a href="tel:1500888" class="text-white font-bold ml-1 hover:text-blue-400 transition">1500-888</a>
              </div>
              <div>
                <span class="text-slate-500">WhatsApp Resmi:</span>
                <a href="https://wa.me/6281288887788" target="_blank" class="text-emerald-400 font-bold ml-1 hover:underline">0812-8888-7788</a>
              </div>
              <div>
                <span class="text-slate-500">Email Informasi:</span>
                <a href="mailto:info@sjtrans.co.id" class="text-blue-400 ml-1 hover:underline">info@sjtrans.co.id</a>
              </div>
            </div>
          </div>
        </div>

        <!-- Kolom 3: Lokasi & Peta Google Maps Interaktif -->
        <div class="space-y-4">
          <h4 class="text-sm font-bold text-white uppercase tracking-wider border-b border-slate-800 pb-2">
            Lokasi Peta (Google Maps)
          </h4>

          <div class="rounded-2xl overflow-hidden border border-slate-800 h-36 bg-slate-900 relative shadow-inner">
            <iframe
              src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126907.08620868837!2d106.77412435552308!3d-6.284279549339345!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f3a67710c851%3A0xb3514dc29ff4be6e!2sJakarta%20Timur!5e0!3m2!1sid!2sid!4v1700000000000!5m2!1sid!2sid"
              width="100%"
              height="100%"
              style="border:0;"
              loading="lazy"
              referrerpolicy="no-referrer-when-downgrade"
            ></iframe>
          </div>

          <a
            href="https://maps.google.com/?q=Jakarta+Timur"
            target="_blank"
            class="inline-flex items-center gap-2 text-xs font-semibold text-blue-400 hover:text-blue-300 transition"
          >
            <span>Buka Petunjuk Arah Google Maps</span>
            <span>↗</span>
          </a>

          <!-- Media Sosial Resmi -->
          <div class="pt-2">
            <div class="text-xs font-bold text-slate-300 mb-2">Media Sosial Resmi:</div>
            <div class="flex items-center gap-2">
              <a href="https://instagram.com" target="_blank" class="w-8 h-8 rounded-full bg-slate-800 hover:bg-pink-600 text-white flex items-center justify-center text-xs transition" title="Instagram">
                IG
              </a>
              <a href="https://facebook.com" target="_blank" class="w-8 h-8 rounded-full bg-slate-800 hover:bg-blue-600 text-white flex items-center justify-center text-xs transition" title="Facebook">
                FB
              </a>
              <a href="https://tiktok.com" target="_blank" class="w-8 h-8 rounded-full bg-slate-800 hover:bg-slate-700 text-white flex items-center justify-center text-xs transition" title="TikTok">
                TT
              </a>
              <a href="https://youtube.com" target="_blank" class="w-8 h-8 rounded-full bg-slate-800 hover:bg-red-600 text-white flex items-center justify-center text-xs transition" title="YouTube">
                YT
              </a>
              <a href="https://linkedin.com" target="_blank" class="w-8 h-8 rounded-full bg-slate-800 hover:bg-blue-700 text-white flex items-center justify-center text-xs transition" title="LinkedIn">
                IN
              </a>
            </div>
          </div>
        </div>

        <!-- Kolom 4: Navigasi Halaman Utama -->
        <div class="space-y-4">
          <h4 class="text-sm font-bold text-white uppercase tracking-wider border-b border-slate-800 pb-2">
            {{ t('footer.quick_links') }}
          </h4>

          <div class="space-y-2 text-xs text-slate-400">
            <NuxtLink to="/" class="block hover:text-white transition">➔ {{ t('nav.home') }}</NuxtLink>
            <NuxtLink to="/armada" class="block hover:text-white transition">➔ {{ t('nav.fleets') }}</NuxtLink>
            <NuxtLink to="/blog" class="block hover:text-white transition">➔ {{ t('nav.blog') }}</NuxtLink>
            <NuxtLink to="/tentang-kami" class="block hover:text-white transition">➔ {{ t('nav.about') }}</NuxtLink>
            <NuxtLink to="/customer" class="block hover:text-white transition">➔ {{ t('customer.title') }}</NuxtLink>
            <NuxtLink to="/admin" class="block hover:text-white transition">➔ {{ t('admin_panel') }}</NuxtLink>
          </div>

          <div class="pt-2 border-t border-slate-800">
            <div class="text-xs font-bold text-slate-300 mb-1.5">Kemitraan Resmi:</div>
            <p class="text-[11px] text-slate-400 leading-relaxed">
              PT Astra International Tbk, Jasa Raharja, Pertamina Patra Niaga, Bank BCA & Mandiri.
            </p>
          </div>
        </div>
      </div>

      <!-- Bottom Bar: Copyright -->
      <div class="pt-8 border-t border-slate-800/80 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-slate-500">
        <p>© 2026 PT Semesta Jaya Transport (SJ Trans). {{ t('footer.rights') }}</p>
        <div class="flex items-center gap-6">
          <NuxtLink to="/tentang-kami" class="hover:text-slate-300 transition">Tentang Kami</NuxtLink>
          <NuxtLink to="/armada" class="hover:text-slate-300 transition">Armada Bus</NuxtLink>
          <NuxtLink to="/blog" class="hover:text-slate-300 transition">Artikel Blog</NuxtLink>
        </div>
      </div>
    </div>
  </footer>
</template>

<script setup lang="ts">
const { primaryColor } = useTheme()
const { t } = useI18n()
</script>
