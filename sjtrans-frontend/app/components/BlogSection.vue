<template>
  <section id="blog" class="py-16 sm:py-20 bg-slate-50 border-t border-slate-200">
    <div class="container-custom">
      <!-- Section Header -->
      <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-12">
        <div class="max-w-2xl">
          <span class="text-xs font-bold uppercase tracking-wider block mb-2" :style="{ color: primaryColor }">
            Inspirasi Wisata & Tips Perjalanan
          </span>
          <h2 class="text-2xl sm:text-3xl md:text-4xl font-black text-slate-900 tracking-tight leading-tight">
            {{ locale === 'en' ? 'Latest Travel & Charter Bus Articles' : 'Artikel Wisata & Panduan Sewa Bus Terkini' }}
          </h2>
          <p class="text-xs sm:text-sm text-slate-500 mt-2 leading-relaxed">
            {{ locale === 'en' ? 'Tour tips, destination recommendations, and safe group travel guides from SJ Trans.' : 'Tips memilih armada, rekomendasi rute wisata favorit, dan panduan perjalanan rombongan aman & nyaman.' }}
          </p>
        </div>

        <NuxtLink
          to="/blog"
          class="btn-outline text-xs py-2.5 px-5 rounded-full font-bold transition self-start sm:self-auto flex items-center gap-1.5 shrink-0 shadow-2xs"
          :style="{ borderColor: primaryColor, color: primaryColor }"
        >
          <span>{{ locale === 'en' ? 'View All Articles' : 'Lihat Semua Artikel' }}</span>
          <span>➔</span>
        </NuxtLink>
      </div>

      <!-- Articles Grid (3 Cards on Homepage) -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
        <article
          v-for="post in featuredPosts"
          :key="post.id"
          class="bg-white rounded-3xl border border-slate-200 shadow-2xs hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col group"
        >
          <!-- Thumbnail -->
          <NuxtLink :to="`/blog/${post.slug}`" class="relative h-48 sm:h-52 overflow-hidden bg-slate-100 block">
            <img
              :src="post.featured_image"
              :alt="post.title"
              class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105"
            />
            <div class="absolute top-4 left-4">
              <span class="bg-blue-600/90 backdrop-blur-md text-white text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-full shadow-xs">
                {{ post.category }}
              </span>
            </div>
          </NuxtLink>

          <!-- Body -->
          <div class="p-5 sm:p-6 flex-1 flex flex-col justify-between">
            <div>
              <div class="flex items-center gap-3 text-[11px] text-slate-400 mb-2 font-medium">
                <span>🗓️ {{ post.date }}</span>
                <span>•</span>
                <span>👁️ {{ post.views }} {{ t('blog.views') }}</span>
              </div>

              <NuxtLink :to="`/blog/${post.slug}`" class="block group-hover:text-blue-600 transition">
                <h3 class="text-base font-bold text-slate-900 leading-snug line-clamp-2 mb-2">
                  {{ post.title }}
                </h3>
              </NuxtLink>

              <p class="text-xs text-slate-500 leading-relaxed line-clamp-3 mb-4">
                {{ post.excerpt }}
              </p>
            </div>

            <!-- Footer Link -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
              <span class="text-[11px] font-semibold text-slate-600">
                ✍️ {{ post.author }}
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
    </div>
  </section>
</template>

<script setup lang="ts">
const { primaryColor } = useTheme()
const { locale, t } = useI18n()

const featuredPosts = [
  {
    id: 1,
    title: 'Panduan Lengkap Memilih Bus Pariwisata untuk Study Tour Sekolah',
    slug: 'panduan-memilih-bus-pariwisata-study-tour',
    category: 'Tips & Destinasi',
    excerpt: 'Ketahui faktor keselamatan, kapasitas kursi, dan fasilitas penting sebelum memesan bus pariwisata agar study tour siswa berjalan aman dan menyenangkan.',
    featured_image: 'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?auto=format&fit=crop&w=800&q=80',
    date: '25 Okt 2026',
    views: 1420,
    author: 'Super Admin SJ Trans',
  },
  {
    id: 2,
    title: 'Rekomendasi 7 Destinasi Wisata Terbaik di Bandung untuk Rombongan Bus',
    slug: 'rekomendasi-destinasi-wisata-bandung-rombongan-bus',
    category: 'Info Wisata',
    excerpt: 'Daftar objek wisata ramah parkir bus besar di Bandung yang wajib dikunjungi bersama keluarga besar dan rekan sekantor.',
    featured_image: 'https://images.unsplash.com/photo-1570125909232-eb263c188f7e?auto=format&fit=crop&w=800&q=80',
    date: '26 Okt 2026',
    views: 980,
    author: 'Admin Operasional',
  },
  {
    id: 3,
    title: '5 Keunggulan Mesin Scania K410IB Jetbus 5 untuk Perjalanan Jarak Jauh',
    slug: 'keunggulan-mesin-scania-k410ib-jetbus-5',
    category: 'Panduan Armada',
    excerpt: 'Mengapa suspensi udara dan sistem pengereman canggih Scania Opticruise menjadikannya raja jalanan bus pariwisata eksekutif.',
    featured_image: 'https://images.unsplash.com/photo-1494515843206-f3117d3f51b7?auto=format&fit=crop&w=800&q=80',
    date: '27 Okt 2026',
    views: 810,
    author: 'Technical Fleet Team',
  },
]
</script>
