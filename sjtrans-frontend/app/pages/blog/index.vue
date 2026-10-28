<template>
  <div class="pb-16 md:pb-12 bg-slate-50 min-h-screen">
    <!-- Header Banner with Solid Guaranteed Deep Navy Background -->
    <div
      class="hero-gradient py-14 px-4 relative overflow-hidden shadow-lg border-b border-blue-900/40"
      style="background: linear-gradient(135deg, #070F26 0%, #0D1E4A 50%, #1E3A8A 100%); color: #FFFFFF;"
    >
      <div class="absolute inset-0 opacity-15 bg-[radial-gradient(#38bdf8_1px,transparent_1px)] [background-size:20px_20px] pointer-events-none"></div>
      
      <div class="container-custom relative z-10">
        <!-- Breadcrumb -->
        <div class="flex items-center gap-2 text-xs font-medium text-sky-300 mb-3">
          <NuxtLink to="/" class="hover:text-white transition">Beranda</NuxtLink>
          <span class="text-slate-400">/</span>
          <span class="text-white font-bold">Blog</span>
        </div>

        <div class="max-w-3xl">
          <!-- Badge -->
          <div class="inline-flex items-center gap-2 bg-blue-500/25 border border-sky-400/40 text-sky-200 text-xs font-bold uppercase tracking-wider px-3.5 py-1 rounded-full mb-3 shadow-xs">
            <span class="w-2 h-2 rounded-full bg-sky-400 animate-pulse"></span>
            <span>Inspirasi Wisata & Tips Perjalanan</span>
          </div>

          <!-- Title -->
          <h1 class="text-3xl sm:text-4xl md:text-5xl font-black text-white tracking-tight leading-tight drop-shadow-sm">
            {{ t('blog.title') }}
          </h1>

          <!-- Subtitle -->
          <p class="text-sm sm:text-base text-slate-200 mt-3 leading-relaxed max-w-2xl">
            {{ t('blog.subtitle') }}
          </p>
        </div>
      </div>
    </div>

    <!-- Main Container -->
    <div class="container-custom py-8">
      <!-- Search & Category Filters -->
      <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-8">
        <!-- Categories -->
        <div class="flex items-center gap-2 overflow-x-auto pb-2 sm:pb-0 w-full sm:w-auto">
          <button
            v-for="cat in categories"
            :key="cat"
            @click="selectedCategory = cat"
            class="px-4 py-2 rounded-xl text-xs font-bold transition whitespace-nowrap cursor-pointer shadow-xs"
            :class="selectedCategory === cat
              ? 'text-white shadow-md'
              : 'bg-white text-slate-600 hover:bg-slate-100 border border-slate-200'"
            :style="selectedCategory === cat ? { backgroundColor: primaryColor } : {}"
          >
            {{ cat }}
          </button>
        </div>

        <!-- Search Bar -->
        <div class="relative w-full sm:w-72">
          <input
            v-model="searchQuery"
            type="text"
            :placeholder="t('blog.search_placeholder')"
            class="w-full bg-white border border-slate-200 rounded-xl pl-9 pr-4 py-2 text-xs text-slate-800 shadow-xs focus:outline-none focus:ring-2 focus:ring-blue-600"
          />
          <span class="absolute left-3 top-2.5 text-slate-400 text-xs">🔍</span>
        </div>
      </div>

      <!-- Articles Grid -->
      <div v-if="filteredPosts.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
        <article
          v-for="post in filteredPosts"
          :key="post.id"
          class="bg-white rounded-3xl border border-slate-200 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col group"
        >
          <!-- Thumbnail Image -->
          <NuxtLink :to="`/blog/${post.slug}`" class="relative h-48 sm:h-52 overflow-hidden bg-slate-100 block">
            <img
              :src="post.featured_image || 'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?auto=format&fit=crop&w=800&q=80'"
              :alt="post.title"
              class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
            />
            <div class="absolute top-4 left-4">
              <span class="bg-blue-600/90 backdrop-blur-md text-white text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full shadow-xs">
                {{ post.category?.name || 'Tips & Destinasi' }}
              </span>
            </div>
          </NuxtLink>

          <!-- Article Info & Body -->
          <div class="p-5 sm:p-6 flex-1 flex flex-col justify-between">
            <div>
              <div class="flex items-center gap-3 text-[11px] text-slate-400 mb-2 font-medium">
                <span>🗓️ {{ formatDate(post.published_at || post.created_at) }}</span>
                <span>•</span>
                <span>👁️ {{ post.views_count || 120 }} {{ t('blog.views') }}</span>
              </div>

              <NuxtLink :to="`/blog/${post.slug}`" class="block group-hover:text-blue-600 transition">
                <h2 class="text-base sm:text-lg font-bold text-slate-900 leading-snug line-clamp-2 mb-2">
                  {{ post.title }}
                </h2>
              </NuxtLink>

              <p class="text-xs text-slate-500 leading-relaxed line-clamp-3 mb-4">
                {{ post.excerpt || 'Pelajari informasi dan tips seputar rute wisata, kenyamanan armada bus, serta keselamatan perjalanan bersama SJ Trans.' }}
              </p>
            </div>

            <!-- Read More Button & Author -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
              <span class="text-[11px] font-semibold text-slate-600">
                ✍️ {{ post.author?.name || 'SJ Trans Editorial' }}
              </span>
              <NuxtLink
                :to="`/blog/${post.slug}`"
                class="text-xs font-bold transition flex items-center gap-1 group-hover:gap-2"
                :style="{ color: primaryColor }"
              >
                <span>{{ t('blog.read_more') }}</span>
                <span>➔</span>
              </NuxtLink>
            </div>
          </div>
        </article>
      </div>

      <!-- Empty State -->
      <div v-else class="text-center py-16 bg-white rounded-3xl border border-slate-200 p-8">
        <span class="text-4xl block mb-3">📰</span>
        <h3 class="text-lg font-bold text-slate-800 mb-1">Artikel Tidak Ditemukan</h3>
        <p class="text-xs text-slate-500 mb-4">Coba cari dengan kata kunci lain atau pilih kategori Semua.</p>
        <button
          @click="searchQuery = ''; selectedCategory = 'Semua'"
          class="btn-primary text-xs py-2 px-4 rounded-xl font-bold cursor-pointer"
          :style="{ backgroundColor: primaryColor }"
        >
          Reset Pencarian
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
const { primaryColor } = useTheme()
const { t } = useI18n()

const searchQuery = ref('')
const selectedCategory = ref('Semua')
const categories = ['Semua', 'Tips & Destinasi', 'Info Wisata', 'Panduan Armada']

const defaultPosts = [
  {
    id: 1,
    title: 'Panduan Lengkap Memilih Bus Pariwisata untuk Study Tour Sekolah',
    slug: 'panduan-memilih-bus-pariwisata-study-tour',
    excerpt: 'Ketahui faktor keselamatan, kapasitas kursi, dan fasilitas penting sebelum memesan bus pariwisata agar study tour siswa berjalan aman dan menyenangkan.',
    featured_image: 'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?auto=format&fit=crop&w=800&q=80',
    views_count: 1420,
    created_at: '2026-10-25T08:00:00Z',
    category: { name: 'Tips & Destinasi' },
    author: { name: 'Super Admin SJ Trans' },
  },
  {
    id: 2,
    title: 'Rekomendasi 7 Destinasi Wisata Terbaik di Bandung untuk Rombongan Bus',
    slug: 'rekomendasi-destinasi-wisata-bandung-rombongan-bus',
    excerpt: 'Daftar objek wisata ramah parkir bus besar di Bandung yang wajib dikunjungi bersama keluarga besar dan rekan sekantor.',
    featured_image: 'https://images.unsplash.com/photo-1570125909232-eb263c188f7e?auto=format&fit=crop&w=800&q=80',
    views_count: 980,
    created_at: '2026-10-26T09:00:00Z',
    category: { name: 'Info Wisata' },
    author: { name: 'Admin Operasional' },
  },
  {
    id: 3,
    title: '5 Keunggulan Mesin Scania K410IB Jetbus 5 untuk Perjalanan Jarak Jauh',
    slug: 'keunggulan-mesin-scania-k410ib-jetbus-5',
    excerpt: 'Mengapa suspensi udara dan sistem pengereman canggih Scania Opticruise menjadikannya raja jalanan bus pariwisata eksekutif.',
    featured_image: 'https://images.unsplash.com/photo-1494515843206-f3117d3f51b7?auto=format&fit=crop&w=800&q=80',
    views_count: 810,
    created_at: '2026-10-27T10:00:00Z',
    category: { name: 'Panduan Armada' },
    author: { name: 'Technical Fleet Team' },
  },
]

const posts = ref<any[]>(defaultPosts)

onMounted(async () => {
  try {
    const res: any = await $fetch('http://127.0.0.1:8000/api/v1/posts')
    if (res && res.data && res.data.length > 0) {
      const articleList = res.data.filter((p: any) => p.type !== 'page')
      if (articleList.length > 0) {
        posts.value = articleList
      }
    }
  } catch (err) {
    // Keep fallback posts
  }
})

const filteredPosts = computed(() => {
  return posts.value.filter(post => {
    const matchCat =
      selectedCategory.value === 'Semua' ||
      post.category?.name === selectedCategory.value

    const q = searchQuery.value.toLowerCase().trim()
    const matchSearch =
      !q ||
      post.title.toLowerCase().includes(q) ||
      (post.excerpt && post.excerpt.toLowerCase().includes(q))

    return matchCat && matchSearch
  })
})

const formatDate = (dateStr: string) => {
  if (!dateStr) return '28 Okt 2026'
  try {
    const d = new Date(dateStr)
    return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })
  } catch {
    return '28 Okt 2026'
  }
}
</script>
