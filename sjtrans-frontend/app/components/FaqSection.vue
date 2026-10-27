<template>
  <section id="faq" class="py-16 lg:py-24 bg-white">
    <div class="container-custom max-w-4xl">
      <div class="text-center space-y-2 mb-12">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-blue-100 text-blue-800">
          Pusat Bantuan & Tanya Jawab
        </span>
        <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900">
          Pertanyaan yang Sering Diajukan (FAQ)
        </h2>
        <p class="text-xs sm:text-sm text-slate-500">
          Segala hal yang perlu Anda ketahui mengenai prosedur pemesanan, fasilitas bus, dan ketentuan sewa di SJ Trans.
        </p>
      </div>

      <!-- Accordion Items -->
      <div class="space-y-3">
        <div
          v-for="(faq, idx) in faqs"
          :key="idx"
          class="border border-slate-200 rounded-2xl overflow-hidden transition"
        >
          <button
            @click="toggleFaq(idx)"
            class="w-full p-5 text-left font-bold text-sm text-slate-900 flex justify-between items-center gap-4 bg-slate-50/50 hover:bg-slate-100/70 transition cursor-pointer"
          >
            <span>{{ faq.question }}</span>
            <span
              class="w-6 h-6 rounded-full flex items-center justify-center bg-white border border-slate-200 text-slate-600 font-black text-xs transition-transform duration-200"
              :class="{ 'rotate-180 bg-blue-50 text-blue-700': openIndex === idx }"
            >
              ▼
            </span>
          </button>

          <div
            v-if="openIndex === idx"
            class="p-5 text-xs text-slate-600 bg-white border-t border-slate-100 leading-relaxed space-y-2"
          >
            <p>{{ faq.answer }}</p>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
const openIndex = ref<number | null>(0)

const toggleFaq = (idx: number) => {
  openIndex.value = openIndex.value === idx ? null : idx
}

const faqs = [
  {
    question: 'Bagaimana alur pemesanan sewa bus pariwisata di SJ Trans?',
    answer: 'Pemesanan sangat praktis: 1) Gunakan kalkulator reservasi di beranda untuk menentukan tanggal, tujuan, dan armada; 2) Masukkan kode promo jika ada; 3) Lengkapi data kontak pemesan; 4) Lakukan pembayaran Down Payment (DP) 30% untuk mengunci jadwal armada; 5) Pelunasan dapat dilakukan maksimal H-3 sebelum keberangkatan.',
  },
  {
    question: 'Fasilitas apa saja yang sudah termasuk di dalam bus?',
    answer: 'Seluruh armada SJ Trans (Big Bus, Medium Bus, dan HiAce) telah dilengkapi AC ducting dingin merata, sound system karaoke, Smart TV LED, reclining seat empuk, port USB charger di setiap baris kursi, serta bagasi luas. Untuk tipe Big Bus SHD Scania juga tersedia toilet bersih dan dispenser air minum.',
  },
  {
    question: 'Apakah harga yang tertera sudah termasuk biaya tol, bensin, dan supir?',
    answer: 'Harga dasar sewa sudah mencakup unit armada prima dan jasa pengemudi profesional bersertifikat. Biaya bahan bakar (BBM), tol, retribusi parkir, penyeberangan kapal ferry (bila ke Bali/Sumatera), dan tip supir dapat disesuaikan dalam paket All-In atau mandiri sesuai kebutuhan pemesan.',
  },
  {
    question: 'Bagaimana jika kami ingin membatalkan atau mengubah jadwal sewa?',
    answer: 'Perubahan jadwal (reschedule) dapat dilakukan tanpa biaya tambahan maksimal H-7 sebelum jadwal keberangkatan, selama armada pengganti masih tersedia. Untuk pembatalan sepihak, silakan hubungi Customer Service kami untuk ketentuan refund sesuai kebijakan operasional.',
  },
  {
    question: 'Apakah seluruh penumpang dilindungi oleh asuransi perjalanan?',
    answer: 'Ya, keselamatan adalah prioritas utama kami. Seluruh penumpang terdaftar pada manifes perjalanan SJ Trans dilindungi asuransi resmi kecelakaan lalu lintas dari PT Jasa Raharja.',
  },
]
</script>
