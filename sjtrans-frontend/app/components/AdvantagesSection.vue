<template>
  <section id="keunggulan" class="py-16 lg:py-24 bg-white border-y border-slate-100">
    <div class="container-custom">
      <div class="text-center max-w-3xl mx-auto space-y-3 mb-16">
        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-blue-100 text-blue-800">
          Standar Layanan Unggul
        </div>
        <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight">
          Mengapa Memilih SJ Trans untuk Perjalanan Anda?
        </h2>
        <p class="text-slate-600 text-sm sm:text-base">
          Kami berkomitmen memberikan ketenangan dan kenyamanan optimal bagi seluruh rombongan dengan standar mutu tinggi.
        </p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        <!-- Keunggulan 1 -->
        <div class="p-6 rounded-3xl bg-slate-50 border border-slate-100 hover:border-blue-200 transition-all hover:shadow-lg space-y-4">
          <div
            class="w-12 h-12 rounded-2xl flex items-center justify-center text-white font-bold text-xl shadow-md"
            :style="{ backgroundColor: primaryColor }"
          >
            🛡️
          </div>
          <h3 class="text-base font-bold text-slate-900">Pengemudi Profesional & Terlatih</h3>
          <p class="text-xs text-slate-500 leading-relaxed">
            Kru pengemudi kami telah lulus sertifikasi Defensive Driving, berpengalaman di berbagai medan jalan, ramah, dan bebas dari rokok.
          </p>
        </div>

        <!-- Keunggulan 2 -->
        <div class="p-6 rounded-3xl bg-slate-50 border border-slate-100 hover:border-blue-200 transition-all hover:shadow-lg space-y-4">
          <div
            class="w-12 h-12 rounded-2xl flex items-center justify-center text-white font-bold text-xl shadow-md"
            :style="{ backgroundColor: primaryColor }"
          >
            🚌
          </div>
          <h3 class="text-base font-bold text-slate-900">Armada Baru & Suspensi Udara</h3>
          <p class="text-xs text-slate-500 leading-relaxed">
            Unit bus kami didukung teknologi Air Suspension modern yang meminimalisir guncangan jalan sehingga perjalanan jauh bebas mabuk darat.
          </p>
        </div>

        <!-- Keunggulan 3 -->
        <div class="p-6 rounded-3xl bg-slate-50 border border-slate-100 hover:border-blue-200 transition-all hover:shadow-lg space-y-4">
          <div
            class="w-12 h-12 rounded-2xl flex items-center justify-center text-white font-bold text-xl shadow-md"
            :style="{ backgroundColor: primaryColor }"
          >
            🎵
          </div>
          <h3 class="text-base font-bold text-slate-900">Hiburan Audio & Karaoke On-Board</h3>
          <p class="text-xs text-slate-500 leading-relaxed">
            Dilengkapi Smart Android TV LED, audio system stereo, wireless karaoke mic, USB fast charging di setiap kursi, dan wifi berkecepatan tinggi.
          </p>
        </div>

        <!-- Keunggulan 4 -->
        <div class="p-6 rounded-3xl bg-slate-50 border border-slate-100 hover:border-blue-200 transition-all hover:shadow-lg space-y-4">
          <div
            class="w-12 h-12 rounded-2xl flex items-center justify-center text-white font-bold text-xl shadow-md"
            :style="{ backgroundColor: primaryColor }"
          >
            ✅
          </div>
          <h3 class="text-base font-bold text-slate-900">Proteksi Asuransi Resmi</h3>
          <p class="text-xs text-slate-500 leading-relaxed">
            Seluruh penumpang terdaftar dan dilindungi secara hukum oleh asuransi resmi perjalanan Jasa Raharja demi keamanan dan keselamatan mutlak.
          </p>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
const { primaryColor } = useTheme()
</script>
