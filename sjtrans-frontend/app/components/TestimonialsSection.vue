<template>
  <section id="testimoni" class="py-16 lg:py-24 bg-white border-b border-slate-100">
    <div class="container-custom">
      <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-4 mb-12">
        <div>
          <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-blue-100 text-blue-800">
            Ulasan Pelanggan
          </span>
          <h2 class="text-2xl sm:text-3xl lg:text-4xl font-extrabold text-slate-900 mt-2">
            Apa Kata Mereka Tentang SJ Trans?
          </h2>
        </div>
        <button
          @click="showReviewModal = true"
          class="btn-outline text-xs py-2 px-5 rounded-full cursor-pointer"
          :style="{ borderColor: primaryColor, color: primaryColor }"
        >
          ✍️ Tulis Pengalaman & Ulasan
        </button>
      </div>

      <!-- Testimonial Grid -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div
          v-for="(item, idx) in testimonials"
          :key="idx"
          class="p-6 rounded-3xl bg-slate-50 border border-slate-100 hover:shadow-lg transition-all flex flex-col justify-between space-y-4"
        >
          <div class="space-y-3">
            <!-- 5 Star Rating -->
            <div class="flex items-center gap-1 text-amber-400 text-sm">
              <span v-for="star in item.rating" :key="star">★</span>
              <span class="text-xs font-bold text-slate-700 ml-1.5">{{ item.rating }}.0</span>
            </div>

            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed italic">
              "{{ item.comment }}"
            </p>
          </div>

          <div class="pt-4 border-t border-slate-200/60 flex items-center gap-3">
            <img :src="item.photo_url" class="w-10 h-10 rounded-full object-cover shadow-sm border border-white" />
            <div>
              <h4 class="text-xs font-bold text-slate-900">{{ item.customer_name }}</h4>
              <p class="text-[11px] text-slate-500">{{ item.customer_role_or_company }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal Tulis Ulasan -->
    <div v-if="showReviewModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-sm">
      <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl relative space-y-4">
        <button
          @click="showReviewModal = false"
          class="absolute top-5 right-5 w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center font-bold text-sm cursor-pointer"
        >
          ✕
        </button>

        <h3 class="text-lg font-bold text-slate-900">Beri Ulasan Perjalanan</h3>
        <p class="text-xs text-slate-500">Bagikan pengalaman berharga Anda menyewa bus pariwisata di SJ Trans.</p>

        <div class="space-y-3 text-xs">
          <div>
            <label class="block font-semibold text-slate-700 mb-1">Nama Lengkap</label>
            <input v-model="reviewForm.name" type="text" placeholder="Nama Anda" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none" />
          </div>

          <div>
            <label class="block font-semibold text-slate-700 mb-1">Perusahaan / Nama Rombongan</label>
            <input v-model="reviewForm.company" type="text" placeholder="Contoh: PT ABC / Panitia Wisata Keluarga" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none" />
          </div>

          <div>
            <label class="block font-semibold text-slate-700 mb-1">Bintang Rating (1 - 5)</label>
            <div class="flex gap-2 text-xl cursor-pointer">
              <span
                v-for="s in 5"
                :key="s"
                @click="reviewForm.rating = s"
                :class="s <= reviewForm.rating ? 'text-amber-400' : 'text-slate-300'"
              >
                ★
              </span>
            </div>
          </div>

          <div>
            <label class="block font-semibold text-slate-700 mb-1">Komentar / Ulasan</label>
            <textarea v-model="reviewForm.comment" rows="3" placeholder="Ceritakan kenyamanan bus, pelayanan supir, dan ketepatan waktu..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none"></textarea>
          </div>

          <div v-if="reviewStatusMessage" class="text-xs font-semibold text-emerald-600">
            {{ reviewStatusMessage }}
          </div>

          <button
            @click="submitReview"
            class="btn-primary w-full py-3 rounded-xl text-xs font-bold justify-center cursor-pointer mt-2"
            :style="{ backgroundColor: primaryColor }"
          >
            Kirim Ulasan Sekarang
          </button>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
const { primaryColor } = useTheme()

const showReviewModal = ref(false)
const reviewStatusMessage = ref('')

const reviewForm = reactive({
  name: '',
  company: '',
  rating: 5,
  comment: '',
})

const testimonials = ref([
  {
    rating: 5,
    comment: 'Pelayanan SJ Trans sangat memuaskan! Bus Scania SHD-nya sangat bersih, AC dingin, dan suspensinya membuat perjalanan rombongan ke Jogja tanpa mabuk darat. Pengemudinya sangat profesional.',
    customer_name: 'Bambang Pratama',
    customer_role_or_company: 'HR Manager PT Astra Sedaya Finance',
    photo_url: 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=200&q=80',
  },
  {
    rating: 5,
    comment: 'Sewa HiAce Premio untuk rombongan dewan guru sangat nyaman. Kursi captain seat empuk, karaoke berfungsi sangat baik, anak-anak dan guru sangat senang sepanjang jalan.',
    customer_name: 'Siti Nurhaliza',
    customer_role_or_company: 'Koordinator Study Tour SMA 34 Jakarta',
    photo_url: 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=200&q=80',
  },
  {
    rating: 5,
    comment: 'Proses booking lewat website sangat cepat dan transparan! Dapat potongan kode promo SJTPROMO10, admin cepat tanggap dan ramah saat verifikasi pembayaran.',
    customer_name: 'Hendra Kusuma',
    customer_role_or_company: 'Family Gathering Rombongan Bandung',
    photo_url: 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=200&q=80',
  },
])

const submitReview = async () => {
  if (!reviewForm.name || !reviewForm.comment) {
    alert('Mohon isi nama dan komentar ulasan Anda.')
    return
  }

  try {
    await $fetch('http://127.0.0.1:8000/api/v1/testimonials', {
      method: 'POST',
      body: {
        customer_name: reviewForm.name,
        customer_role_or_company: reviewForm.company,
        rating: reviewForm.rating,
        comment: reviewForm.comment,
      },
    })
  } catch (e) {
    // Fallback locally
  }

  reviewStatusMessage.value = '✓ Terima kasih! Ulasan Anda telah kami terima dan akan tampil setelah verifikasi.'
  setTimeout(() => {
    showReviewModal.value = false
    reviewStatusMessage.value = ''
    reviewForm.name = ''
    reviewForm.company = ''
    reviewForm.comment = ''
  }, 2000)
}
</script>
