<template>
  <div class="pb-16 md:pb-12 bg-slate-50 min-h-screen">
    <!-- Breadcrumb & Top Bar with Solid Deep Navy Background -->
    <div
      class="hero-gradient py-10 px-4 border-b border-blue-900/40 shadow-md"
      style="background: linear-gradient(135deg, #070F26 0%, #0D1E4A 50%, #1E3A8A 100%); color: #FFFFFF;"
    >
      <div class="container-custom">
        <div class="flex items-center gap-2 text-xs font-medium text-sky-300 mb-2">
          <NuxtLink to="/" class="hover:text-white transition">Beranda</NuxtLink>
          <span class="text-slate-400">/</span>
          <NuxtLink to="/blog" class="hover:text-white transition">Blog</NuxtLink>
          <span class="text-slate-400">/</span>
          <span class="text-white font-bold truncate max-w-xs">{{ post?.title || 'Detail Artikel' }}</span>
        </div>
        <NuxtLink to="/blog" class="inline-flex items-center gap-1.5 text-xs text-sky-300 hover:text-white font-semibold transition">
          <span>←</span>
          <span>{{ t('blog.back') }}</span>
        </NuxtLink>
      </div>
    </div>

    <!-- Article Content -->
    <div class="container-custom py-8 max-w-4xl">
      <article class="bg-white rounded-3xl border border-slate-200 shadow-sm overflow-hidden p-6 sm:p-10">
        <!-- Badge & Meta -->
        <div class="flex flex-wrap items-center gap-3 mb-4">
          <span class="bg-blue-100 text-blue-800 text-xs font-bold uppercase tracking-wider px-3 py-1 rounded-full">
            {{ post?.category?.name || 'Tips & Destinasi' }}
          </span>
          <span class="text-xs text-slate-400">
            🗓️ {{ formatDate(post?.published_at || post?.created_at) }}
          </span>
          <span class="text-xs text-slate-400">
            👁️ {{ post?.views_count || 350 }} {{ t('blog.views') }}
          </span>
        </div>

        <!-- Title -->
        <h1 class="text-2xl sm:text-3xl md:text-4xl font-black text-slate-900 leading-tight mb-6">
          {{ post?.title }}
        </h1>

        <!-- Author Byline -->
        <div class="flex items-center gap-3 pb-6 border-b border-slate-100 mb-6">
          <div class="w-10 h-10 rounded-full bg-blue-600 text-white flex items-center justify-center font-bold text-sm shadow-sm">
            SJ
          </div>
          <div>
            <div class="text-xs font-bold text-slate-900">{{ post?.author?.name || 'Super Admin SJ Trans' }}</div>
            <div class="text-[11px] text-slate-400">Tim Riset Operasional & Pariwisata Indonesia</div>
          </div>
        </div>

        <!-- Featured Image -->
        <div class="h-64 sm:h-96 rounded-2xl overflow-hidden mb-8 bg-slate-100">
          <img
            :src="post?.featured_image || 'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?auto=format&fit=crop&w=1200&q=80'"
            :alt="post?.title"
            class="w-full h-full object-cover"
          />
        </div>

        <!-- Article Rich Content Body -->
        <div class="prose prose-slate max-w-none text-slate-700 leading-relaxed text-sm sm:text-base space-y-4">
          <div v-if="post?.content" v-html="post.content"></div>
          <div v-else class="space-y-4">
            <p>
              Merencanakan agenda perjalanan wisata rombongan, baik untuk study tour sekolah, family gathering, maupun perjalanan dinas instansi, memerlukan persiapan matang terutama pada aspek armada transportasi.
            </p>
            <h3 class="text-lg font-bold text-slate-900 pt-2">
              1. Legalitas Trayek Pariwisata dan Uji Berkala (KIR)
            </h3>
            <p>
              Pastikan perusahaan otobus (PO) memiliki izin resmi dari Kementerian Perhubungan Republik Indonesia. Armada SJ Trans selalu diperiksa kelayakan jalannya secara berkala, memastikan sistem pengereman ABS, ban, dan suspensi udara beroperasi optimal.
            </p>
            <h3 class="text-lg font-bold text-slate-900 pt-2">
              2. Standar Kru Pengemudi yang Berpengalaman
            </h3>
            <p>
              Pengemudi bus pariwisata tidak hanya harus mahir mengemudi, melainkan juga menguasai etika keselamatan, pemahaman rute antar provinsi, dan ramah terhadap kebutuhan penumpang. Di SJ Trans, seluruh supir wajib lulus sertifikasi berkala dan pemeriksaan kesehatan sebelum bertugas.
            </p>
            <h3 class="text-lg font-bold text-slate-900 pt-2">
              3. Fasilitas Lengkap untuk Kenyamanan Sepanjang Jalan
            </h3>
            <p>
              Untuk perjalanan di atas 5 jam, fasilitas pendingin udara Full AC Ducting, reclining seat ergonomis, port USB charging per kursi, serta hiburan Smart LED TV dan audio karaoke menjadi penunjang kenyamanan yang tak tergantikan.
            </p>
          </div>
        </div>

        <!-- Booking CTA Card inside Article -->
        <div class="mt-10 p-6 sm:p-8 rounded-3xl bg-gradient-to-r from-blue-900 to-slate-900 text-white flex flex-col sm:flex-row items-center justify-between gap-6 shadow-xl">
          <div class="text-center sm:text-left">
            <span class="text-blue-300 text-xs font-bold uppercase tracking-wider block mb-1">Siap Berangkat?</span>
            <h3 class="text-xl font-black">Rencanakan Perjalanan Wisata Anda Sekarang</h3>
            <p class="text-xs text-slate-300 mt-1">Dapatkan penawaran harga spesial dan diskon hingga Rp 350.000!</p>
          </div>
          <NuxtLink
            to="/armada"
            class="btn-primary py-3 px-6 rounded-full font-bold text-xs whitespace-nowrap shadow-md cursor-pointer"
            :style="{ backgroundColor: primaryColor }"
          >
            Lihat Pilihan Armada Bus ➔
          </NuxtLink>
        </div>
      </article>
    </div>
  </div>
</template>

<script setup lang="ts">
const route = useRoute()
const { primaryColor } = useTheme()
const { t } = useI18n()

const slug = computed(() => route.params.slug as string)
const post = ref<any>(null)

const samplePosts: Record<string, any> = {
  'panduan-memilih-bus-pariwisata-study-tour': {
    title: 'Panduan Lengkap Memilih Bus Pariwisata untuk Study Tour Sekolah',
    published_at: '2026-10-25T08:00:00Z',
    views_count: 1420,
    featured_image: 'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?auto=format&fit=crop&w=1200&q=80',
    category: { name: 'Tips & Destinasi' },
    author: { name: 'Super Admin SJ Trans' },
  },
  'rekomendasi-destinasi-wisata-bandung-rombongan-bus': {
    title: 'Rekomendasi 7 Destinasi Wisata Terbaik di Bandung untuk Rombongan Bus',
    published_at: '2026-10-26T09:00:00Z',
    views_count: 980,
    featured_image: 'https://images.unsplash.com/photo-1570125909232-eb263c188f7e?auto=format&fit=crop&w=1200&q=80',
    category: { name: 'Info Wisata' },
    author: { name: 'Admin Operasional' },
  },
  'keunggulan-mesin-scania-k410ib-jetbus-5': {
    title: '5 Keunggulan Mesin Scania K410IB Jetbus 5 untuk Perjalanan Jarak Jauh',
    published_at: '2026-10-27T10:00:00Z',
    views_count: 810,
    featured_image: 'https://images.unsplash.com/photo-1494515843206-f3117d3f51b7?auto=format&fit=crop&w=1200&q=80',
    category: { name: 'Panduan Armada' },
    author: { name: 'Technical Fleet Team' },
  },
}

onMounted(async () => {
  post.value = samplePosts[slug.value] || {
    title: 'Tips & Panduan Perjalanan Bersama SJ Trans',
    published_at: new Date().toISOString(),
    views_count: 500,
    featured_image: 'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?auto=format&fit=crop&w=1200&q=80',
    category: { name: 'Info Wisata' },
    author: { name: 'SJ Trans Editorial' },
  }

  try {
    const res: any = await $fetch(`http://127.0.0.1:8000/api/v1/posts/${slug.value}`)
    if (res && res.data) {
      post.value = res.data
    }
  } catch (err) {
    // Fallback active
  }
})

const formatDate = (dateStr: string) => {
  if (!dateStr) return '28 Okt 2026'
  try {
    const d = new Date(dateStr)
    return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' })
  } catch {
    return '28 Okt 2026'
  }
}
</script>
