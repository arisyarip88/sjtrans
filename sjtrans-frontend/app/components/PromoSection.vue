<template>
  <section id="promo" class="py-16 lg:py-20 bg-gradient-to-b from-slate-50 to-white">
    <div class="container-custom">
      <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 mb-10">
        <div>
          <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-red-100 text-red-700">
            Kupon & Voucher Hemat
          </span>
          <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-2">
            Promo Diskon Sewa Bus SJ Trans
          </h2>
        </div>
        <p class="text-xs text-slate-500 max-w-md">
          Salin kode kupon di bawah ini dan masukkan pada kolom promo kalkulator pemesanan di bagian atas untuk mendapatkan potongan tarif langsung!
        </p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Promo 1 -->
        <div class="rounded-3xl border-2 border-dashed border-blue-300 bg-white p-6 shadow-sm hover:shadow-md transition flex flex-col justify-between space-y-4">
          <div class="space-y-2">
            <div class="flex justify-between items-center">
              <span class="bg-blue-100 text-blue-800 text-[11px] font-bold px-2.5 py-0.5 rounded-full">Kupon 10%</span>
              <span class="text-[11px] text-slate-400">Berlaku s/d Akhir Tahun</span>
            </div>
            <h3 class="text-lg font-bold text-slate-900">Promo Liburan Hemat 10%</h3>
            <p class="text-xs text-slate-500">
              Dapatkan potongan harga sewa bus sebesar 10% (maksimal Rp 500.000) dengan minimal transaksi Rp 2.000.000.
            </p>
          </div>

          <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
            <div class="bg-slate-100 px-3 py-1.5 rounded-xl font-mono font-bold text-slate-800 text-sm tracking-wider">
              SJTPROMO10
            </div>
            <button
              @click="copyCode('SJTPROMO10')"
              class="text-xs font-bold px-4 py-2 rounded-xl transition cursor-pointer"
              :class="copiedCode === 'SJTPROMO10' ? 'bg-emerald-600 text-white' : 'bg-blue-50 text-blue-700 hover:bg-blue-100'"
            >
              {{ copiedCode === 'SJTPROMO10' ? 'Tersalin ✓' : 'Salin Kode' }}
            </button>
          </div>
        </div>

        <!-- Promo 2 -->
        <div class="rounded-3xl border-2 border-dashed border-emerald-300 bg-white p-6 shadow-sm hover:shadow-md transition flex flex-col justify-between space-y-4">
          <div class="space-y-2">
            <div class="flex justify-between items-center">
              <span class="bg-emerald-100 text-emerald-800 text-[11px] font-bold px-2.5 py-0.5 rounded-full">Potongan Tunai</span>
              <span class="text-[11px] text-slate-400">Kuota Terbatas</span>
            </div>
            <h3 class="text-lg font-bold text-slate-900">Potongan Langsung Rp 350.000</h3>
            <p class="text-xs text-slate-500">
              Kupon diskon flat Rp 350.000 untuk perjalanan rombongan dengan minimal transaksi Rp 3.000.000.
            </p>
          </div>

          <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
            <div class="bg-slate-100 px-3 py-1.5 rounded-xl font-mono font-bold text-slate-800 text-sm tracking-wider">
              LIBURANSERU
            </div>
            <button
              @click="copyCode('LIBURANSERU')"
              class="text-xs font-bold px-4 py-2 rounded-xl transition cursor-pointer"
              :class="copiedCode === 'LIBURANSERU' ? 'bg-emerald-600 text-white' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100'"
            >
              {{ copiedCode === 'LIBURANSERU' ? 'Tersalin ✓' : 'Salin Kode' }}
            </button>
          </div>
        </div>

        <!-- Promo 3 -->
        <div class="rounded-3xl border-2 border-dashed border-amber-300 bg-white p-6 shadow-sm hover:shadow-md transition flex flex-col justify-between space-y-4">
          <div class="space-y-2">
            <div class="flex justify-between items-center">
              <span class="bg-amber-100 text-amber-800 text-[11px] font-bold px-2.5 py-0.5 rounded-full">Early Bird</span>
              <span class="text-[11px] text-slate-400">Booking H-30</span>
            </div>
            <h3 class="text-lg font-bold text-slate-900">Spesial Pesan Awal (H-30)</h3>
            <p class="text-xs text-slate-500">
              Pesan armada bus minimal 30 hari sebelum hari keberangkatan dan dapatkan potongan harga eksklusif hingga 15%.
            </p>
          </div>

          <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
            <div class="bg-slate-100 px-3 py-1.5 rounded-xl font-mono font-bold text-slate-800 text-sm tracking-wider">
              EARLYBIRD
            </div>
            <button
              @click="copyCode('EARLYBIRD')"
              class="text-xs font-bold px-4 py-2 rounded-xl transition cursor-pointer"
              :class="copiedCode === 'EARLYBIRD' ? 'bg-emerald-600 text-white' : 'bg-amber-50 text-amber-700 hover:bg-amber-100'"
            >
              {{ copiedCode === 'EARLYBIRD' ? 'Tersalin ✓' : 'Salin Kode' }}
            </button>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
const copiedCode = ref('')

const copyCode = (code: string) => {
  if (typeof navigator !== 'undefined' && navigator.clipboard) {
    navigator.clipboard.writeText(code)
  }
  copiedCode.value = code
  setTimeout(() => {
    copiedCode.value = ''
  }, 2500)
}
</script>
