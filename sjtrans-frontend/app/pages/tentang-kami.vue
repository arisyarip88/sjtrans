<template>
  <div class="pb-16 md:pb-12 bg-slate-50 min-h-screen">
    <!-- Header Banner with Solid Guaranteed Deep Navy Background -->
    <div
      class="hero-gradient py-14 px-4 relative overflow-hidden shadow-lg border-b border-blue-900/40"
      style="background: linear-gradient(135deg, #070F26 0%, #0D1E4A 50%, #1E3A8A 100%); color: #FFFFFF;"
    >
      <div class="absolute inset-0 opacity-15 bg-[radial-gradient(#38bdf8_1px,transparent_1px)] [background-size:20px_20px] pointer-events-none"></div>
      <div class="container-custom relative z-10">
        <div class="flex items-center gap-2 text-xs font-medium text-sky-300 mb-3">
          <NuxtLink to="/" class="hover:text-white transition">Beranda</NuxtLink>
          <span class="text-slate-400">/</span>
          <span class="text-white font-bold">{{ t('nav.about') }}</span>
        </div>

        <div class="max-w-3xl">
          <div class="inline-flex items-center gap-2 bg-blue-500/25 border border-sky-400/40 text-sky-200 text-xs font-bold uppercase tracking-wider px-3.5 py-1 rounded-full mb-3 shadow-xs">
            <span class="w-2 h-2 rounded-full bg-sky-400 animate-pulse"></span>
            <span>Profil Perusahaan</span>
          </div>
          <h1 class="text-3xl sm:text-4xl md:text-5xl font-black text-white tracking-tight leading-tight drop-shadow-sm">
            {{ t('about.title') }}
          </h1>
          <p class="text-sm sm:text-base text-slate-200 mt-3 leading-relaxed">
            {{ t('about.subtitle') }}
          </p>
        </div>
      </div>
    </div>

    <!-- Main Content Container -->
    <div class="container-custom py-10 space-y-12">
      <!-- Section 1: Sejarah & Filosofi -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center bg-white rounded-3xl p-6 sm:p-10 border border-slate-200 shadow-sm">
        <div>
          <span class="text-xs font-bold text-blue-600 uppercase tracking-wider block mb-2">Dedikasi & Komitmen</span>
          <h2 class="text-2xl sm:text-3xl font-black text-slate-900 leading-tight mb-4">
            Menghadirkan Pengalaman Perjalanan Wisata Tanpa Rasa Khawatir
          </h2>
          <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mb-4">
            Didirikan di Jakarta, <strong>PT Semesta Jaya Transport Indonesia (SJ Trans)</strong> lahir dari semangat untuk mendefinisikan ulang standar keselamatan dan kenyamanan transportasi pariwisata di Indonesia. Terinspirasi oleh standar layanan korporat kelas dunia, kami memadukan armada mutakhir dengan pengemudi terlatih dan sistem manajemen operasional berbasis teknologi.
          </p>
          <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
            Mulai dari rombongan studi tur sekolah, outing korporat multinasional, kementerian, hingga wisata keluarga, setiap kilometer bersama SJ Trans dirancang untuk memberikan ketenangan pikiran dan momen kebersamaan yang berkesan.
          </p>

          <div class="grid grid-cols-3 gap-4 mt-6 pt-6 border-t border-slate-100 text-center">
            <div>
              <span class="text-2xl sm:text-3xl font-black text-slate-900" :style="{ color: primaryColor }">50+</span>
              <span class="text-[11px] text-slate-500 font-semibold block mt-0.5">Unit Armada Aktif</span>
            </div>
            <div>
              <span class="text-2xl sm:text-3xl font-black text-slate-900" :style="{ color: primaryColor }">100K+</span>
              <span class="text-[11px] text-slate-500 font-semibold block mt-0.5">Penumpang Puas</span>
            </div>
            <div>
              <span class="text-2xl sm:text-3xl font-black text-slate-900" :style="{ color: primaryColor }">99.8%</span>
              <span class="text-[11px] text-slate-500 font-semibold block mt-0.5">Ketepatan Waktu</span>
            </div>
          </div>
        </div>

        <div class="relative rounded-2xl overflow-hidden shadow-lg h-72 sm:h-96 bg-slate-100">
          <img
            src="https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?auto=format&fit=crop&w=1000&q=80"
            alt="Armada SJ Trans"
            class="w-full h-full object-cover"
          />
          <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent flex items-end p-6">
            <span class="text-white text-xs font-semibold">
              Pool & Pusat Perawatan Armada Cibubur KM 26, Tol Jagorawi
            </span>
          </div>
        </div>
      </div>

      <!-- Section 2: Visi & Misi (Crystal Clear High Contrast Cards) -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Visi Kami Card -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm hover:shadow-md transition flex flex-col justify-between">
          <div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-700 flex items-center justify-center text-2xl mb-4 border border-blue-100">
              🎯
            </div>
            <span class="text-xs font-bold text-blue-600 uppercase tracking-wider block mb-1">Arah Masa Depan</span>
            <h3 class="text-xl font-black text-slate-900 mb-3">{{ t('about.vision') }}</h3>
            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
              Menjadi perusahaan penyedia jasa sewa bus pariwisata eksekutif terdepan dan paling terpercaya di Indonesia yang dikenal akan keunggulan keselamatan, inovasi armada, serta pelayanan prima tanpa kompromi.
            </p>
          </div>
          <div class="mt-6 pt-4 border-t border-slate-100 text-[11px] text-slate-500 font-semibold flex items-center gap-1.5">
            <span class="w-2 h-2 rounded-full bg-blue-600"></span>
            <span>Fokus: Keselamatan, Keandalan & Kepuasan Pelanggan</span>
          </div>
        </div>

        <!-- Misi Kami Card -->
        <div class="bg-white rounded-3xl p-6 sm:p-8 border border-slate-200 shadow-sm hover:shadow-md transition flex flex-col justify-between">
          <div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-emerald-700 flex items-center justify-center text-2xl mb-4 border border-emerald-100">
              🚀
            </div>
            <span class="text-xs font-bold text-emerald-600 uppercase tracking-wider block mb-1">Komitmen Layanan</span>
            <h3 class="text-xl font-black text-slate-900 mb-3">{{ t('about.mission') }}</h3>
            <ul class="space-y-2.5 text-xs sm:text-sm text-slate-600 leading-relaxed">
              <li class="flex items-start gap-2">
                <span class="text-emerald-600 font-bold">1.</span>
                <span>Menyediakan armada bus berusia muda dengan pemeliharaan mekanis preventif berstandar pabrikan resmi.</span>
              </li>
              <li class="flex items-start gap-2">
                <span class="text-emerald-600 font-bold">2.</span>
                <span>Membina kru pengemudi yang disiplin, bersertifikat, dan ramah keluarga.</span>
              </li>
              <li class="flex items-start gap-2">
                <span class="text-emerald-600 font-bold">3.</span>
                <span>Menghadirkan proses reservasi dan sistem harga transparan bebas biaya terselubung.</span>
              </li>
            </ul>
          </div>
          <div class="mt-6 pt-4 border-t border-slate-100 text-[11px] text-slate-500 font-semibold flex items-center gap-1.5">
            <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
            <span>Standar: Kemenhub RI & Sertifikasi Keselamatan Jasa Raharja</span>
          </div>
        </div>
      </div>

      <!-- Section 3: Legalitas & Kepatuhan -->
      <div class="bg-white rounded-3xl p-6 sm:p-10 border border-slate-200 shadow-sm">
        <div class="max-w-2xl mb-8">
          <span class="text-xs font-bold text-blue-600 uppercase tracking-wider block mb-1">Kepatuhan Hukum & Regulasi</span>
          <h3 class="text-2xl font-black text-slate-900 leading-tight">
            {{ t('about.legality') }}
          </h3>
          <p class="text-xs text-slate-500 mt-2">
            Perjalanan aman diawali dengan kepatuhan hukum yang menyeluruh. Seluruh armada SJ Trans beroperasi di bawah payung hukum legal.
          </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
          <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200">
            <span class="text-2xl block mb-2">📜</span>
            <h4 class="text-xs font-bold text-slate-900 mb-1">Izin Penyelenggaraan Wisata</h4>
            <span class="text-[11px] text-slate-500 block leading-tight">Kemenhub RI SK.AJ.101/1/14/DJDAT/2022</span>
          </div>

          <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200">
            <span class="text-2xl block mb-2">🛡️</span>
            <h4 class="text-xs font-bold text-slate-900 mb-1">Asuransi Penumpang Resmi</h4>
            <span class="text-[11px] text-slate-500 block leading-tight">Kerjasama Penuh PT Jasa Raharja (Persero)</span>
          </div>

          <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200">
            <span class="text-2xl block mb-2">🔧</span>
            <h4 class="text-xs font-bold text-slate-900 mb-1">Uji Kelayakan Kendaraan (KIR)</h4>
            <span class="text-[11px] text-slate-500 block leading-tight">Lulus Uji Berkala Dishub DKI Jakarta</span>
          </div>

          <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200">
            <span class="text-2xl block mb-2">🏢</span>
            <h4 class="text-xs font-bold text-slate-900 mb-1">Nomor Induk Berusaha (NIB)</h4>
            <span class="text-[11px] text-slate-500 block leading-tight">NIB: 1249000188921 KBLI 49221</span>
          </div>
        </div>
      </div>

      <!-- Section 4: Pool & Fasilitas Garasi (Guaranteed Deep Navy Gradient Background) -->
      <div
        class="rounded-3xl p-6 sm:p-10 shadow-xl flex flex-col md:flex-row items-center justify-between gap-8"
        style="background: linear-gradient(135deg, #070F26 0%, #0D1E4A 50%, #1E3A8A 100%); color: #FFFFFF;"
      >
        <div>
          <span class="text-sky-300 text-xs font-bold uppercase tracking-wider block mb-1">Kesiapan Infrastruktur</span>
          <h3 class="text-xl sm:text-2xl font-black text-white leading-tight mb-2">
            {{ t('about.fleet_garage') }}
          </h3>
          <p class="text-xs sm:text-sm text-slate-200 max-w-xl leading-relaxed">
            Berlokasi strategis di Kawasan Transportasi Cibubur KM 26, Tol Jagorawi dengan luas area 15.000 m², dilengkapi fasilitas cuci bus otomatis bertekanan tinggi, pit stop mekanik 24 jam, dan ruang istirahat kru driver ber-AC.
          </p>
        </div>
        <NuxtLink
          to="/armada"
          class="btn-primary py-3 px-6 rounded-full font-bold text-xs whitespace-nowrap shadow-md cursor-pointer shrink-0"
          :style="{ backgroundColor: primaryColor }"
        >
          Lihat Katalog Armada Bus ➔
        </NuxtLink>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
const { primaryColor } = useTheme()
const { t } = useI18n()
</script>
