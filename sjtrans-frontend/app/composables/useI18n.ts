export type Locale = 'id' | 'en'

export interface I18nDictionary {
  [key: string]: {
    id: string
    en: string
  }
}

export const translations: I18nDictionary = {
  // Brand & Topbar
  'hotline.title': { id: 'Hotline 24/7:', en: '24/7 Hotline:' },
  'theme.color': { id: 'Tema Warna', en: 'Theme Color' },
  'theme.choose': { id: 'Pilih Tone Warna', en: 'Select Color Tone' },
  'theme.custom': { id: 'Warna Kustom:', en: 'Custom Color:' },
  'greeting': { id: 'Halo', en: 'Hello' },
  'login': { id: 'Masuk', en: 'Log In' },
  'register': { id: 'Daftar', en: 'Sign Up' },
  'logout': { id: 'Keluar', en: 'Sign Out' },
  'call_center': { id: 'Call Center', en: 'Call Center' },
  'book_bus': { id: 'Reservasi Bus', en: 'Book Bus' },
  'book_now': { id: 'Pesan Sekarang', en: 'Book Now' },
  'my_orders': { id: 'Pesanan Saya', en: 'My Bookings' },
  'admin_panel': { id: 'Panel Admin', en: 'Admin Panel' },
  'customer_portal': { id: 'Portal Pelanggan', en: 'Customer Portal' },

  // Navbar
  'nav.home': { id: 'Beranda', en: 'Home' },
  'nav.fleets': { id: 'Armada Bus', en: 'Bus Fleets' },
  'nav.blog': { id: 'Blog', en: 'Blog' },
  'nav.about': { id: 'Tentang Kami', en: 'About Us' },

  // Mobile Bottom Nav
  'mobile.home': { id: 'Beranda', en: 'Home' },
  'mobile.fleets': { id: 'Armada', en: 'Fleets' },
  'mobile.blog': { id: 'Blog', en: 'Blog' },
  'mobile.chat': { id: 'Chat CS', en: 'Live Chat' },
  'mobile.account': { id: 'Akun', en: 'Account' },

  // Hero & Calculator
  'hero.badge': { id: 'SEWA BUS PARIWISATA EKSEKUTIF JAKARTA & INDONESIA', en: 'EXECUTIVE TOUR BUS RENTAL JAKARTA & INDONESIA' },
  'hero.title_pre': { id: 'Perjalanan Nyaman & Aman Bersama', en: 'Comfortable & Safe Journeys with' },
  'hero.desc': {
    id: 'Layanan sewa bus pariwisata profesional berstandar korporat. Didukung armada Scania, Mercedes-Benz, dan HiAce terbaru dengan kru pengemudi terlatih.',
    en: 'Professional corporate-grade charter bus rental services. Supported by the latest Scania, Mercedes-Benz, and HiAce fleet with highly trained chauffeurs.',
  },
  'calc.title': { id: 'Kalkulator Sewa Instan & Cek Tarif', en: 'Instant Rental Calculator & Rates' },
  'calc.origin': { id: 'Kota Penjemputan', en: 'Pickup City' },
  'calc.destination': { id: 'Destinasi Tujuan', en: 'Destination' },
  'calc.start_date': { id: 'Tanggal Berangkat', en: 'Departure Date' },
  'calc.end_date': { id: 'Tanggal Selesai', en: 'Return Date' },
  'calc.fleet_type': { id: 'Pilihan Armada', en: 'Bus Fleet Type' },
  'calc.bus_units': { id: 'Jumlah Unit Bus', en: 'Number of Buses' },
  'calc.discount_code': { id: 'Punya Kode Promo / Kupon?', en: 'Have a Promo / Coupon Code?' },
  'calc.apply_coupon': { id: 'Terapkan', en: 'Apply' },
  'calc.est_price': { id: 'Estimasi Tarif Sewa:', en: 'Estimated Rental Price:' },
  'calc.button': { id: 'Lanjutkan Pemesanan', en: 'Continue Booking' },

  // Fleets Section
  'fleet.title': { id: 'Katalog Armada Bus Pariwisata', en: 'Charter Bus Fleet Catalog' },
  'fleet.subtitle': {
    id: 'Pilihan bus eksekutif modern dengan standar perawatan berkala dan asuransi perjalanan lengkap.',
    en: 'Modern executive bus collection with routine maintenance standards and comprehensive travel insurance.',
  },
  'fleet.filter_all': { id: 'Semua Armada', en: 'All Fleets' },
  'fleet.seats': { id: 'Kursi', en: 'Seats' },
  'fleet.starting_from': { id: 'Mulai dari', en: 'Starting from' },
  'fleet.per_day': { id: '/ hari', en: '/ day' },
  'fleet.specs': { id: 'Spesifikasi & Fasilitas', en: 'Specs & Amenities' },
  'fleet.view_detail': { id: 'Lihat Detail', en: 'View Details' },

  // Advantages
  'adv.title': { id: 'Mengapa Memilih SJ Trans?', en: 'Why Choose SJ Trans?' },
  'adv.subtitle': { id: '4 Pilar keunggulan layanan transportasi pariwisata terpercaya kami.', en: '4 Pillars of excellence in our trusted tour transportation service.' },
  'adv.p1_title': { id: 'Armada Baru & Terawat', en: 'New & Pristine Fleets' },
  'adv.p1_desc': { id: 'Unit bus berusia muda dengan inspeksi teknis ketat sebelum bertugas.', en: 'Young-aged bus units undergoing rigorous technical pre-trip inspections.' },
  'adv.p2_title': { id: 'Kru Pengemudi Andal', en: 'Certified Chauffeurs' },
  'adv.p2_desc': { id: 'Driver bersertifikat, ramah, memahami rute wisata, dan mengutamakan keselamatan.', en: 'Certified, friendly drivers who master tour routes and prioritize passenger safety.' },
  'adv.p3_title': { id: 'Tarif Transparan & Bersahabat', en: 'Transparent & Fair Rates' },
  'adv.p3_desc': { id: 'Tanpa biaya tersembunyi dengan berbagai penawaran diskon khusus rombongan.', en: 'Zero hidden fees with exclusive discount offers tailored for tour groups.' },
  'adv.p4_title': { id: 'Layanan Siaga 24/7', en: '24/7 Dedicated Support' },
  'adv.p4_desc': { id: 'Tim operasional siap memantau dan mendampingi kelancaran perjalanan Anda sepanjang waktu.', en: 'Operational dispatch team ready around the clock to ensure your trip runs smoothly.' },

  // Promo
  'promo.title': { id: 'Promo & Diskon Eksklusif', en: 'Exclusive Promos & Discounts' },
  'promo.subtitle': { id: 'Hemat biaya sewa bus dengan kode kupon resmi SJ Trans.', en: 'Save on charter costs with official SJ Trans coupon codes.' },
  'promo.copy': { id: 'Salin Kode', en: 'Copy Code' },
  'promo.copied': { id: 'Kode Tersalin!', en: 'Code Copied!' },

  // Testimonials
  'testi.title': { id: 'Pengalaman & Ulasan Pelanggan', en: 'Customer Reviews & Stories' },
  'testi.subtitle': { id: 'Kepercayaan ribuan perusahaan, instansi pemerintah, dan keluarga di Indonesia.', en: 'Trusted by thousands of corporations, government institutions, and families.' },
  'testi.send': { id: 'Tulis Ulasan Anda', en: 'Write a Review' },

  // Partnerships
  'partner.title': { id: 'Kemitraan Resmi & Korporat', en: 'Official Corporate Partnerships' },
  'partner.subtitle': { id: 'Bekerja sama dengan institusi terpercaya untuk jaminan perjalanan terbaik.', en: 'Partnering with leading institutions for peak travel assurance.' },

  // Live Chat & FAQ
  'chat.trigger': { id: 'Live Chat CS', en: 'Live Chat Support' },
  'chat.header_title': { id: 'Layanan Pelanggan SJ Trans', en: 'SJ Trans Customer Support' },
  'chat.header_status': { id: 'Online • Siap Melayani 24 Jam', en: 'Online • 24/7 Ready to Assist' },
  'chat.tab_chat': { id: '💬 Obrolan Langsung', en: '💬 Live Chat' },
  'chat.tab_faq': { id: '❓ Bantuan & FAQ', en: '❓ Help & FAQ' },
  'chat.wa_button': { id: 'Chat Cepat via WhatsApp', en: 'Fast WhatsApp Chat' },
  'chat.placeholder': { id: 'Ketik pertanyaan Anda di sini...', en: 'Type your question here...' },
  'chat.faq_search': { id: 'Cari pertanyaan bantuan...', en: 'Search help questions...' },

  // Blog Page
  'blog.title': { id: 'Blog & Info Wisata Transportasi', en: 'Blog & Travel Transportation News' },
  'blog.subtitle': { id: 'Panduan wisata, tips memilih bus pariwisata, dan kabar terbaru dari SJ Trans.', en: 'Travel guides, bus charter tips, and the latest updates from SJ Trans.' },
  'blog.search_placeholder': { id: 'Cari artikel atau topik wisata...', en: 'Search articles or travel topics...' },
  'blog.read_more': { id: 'Baca Selengkapnya', en: 'Read More' },
  'blog.views': { id: 'kali dibaca', en: 'views' },
  'blog.back': { id: 'Kembali ke Blog', en: 'Back to Blog' },

  // About Page
  'about.title': { id: 'Tentang SJ Trans Indonesia', en: 'About SJ Trans Indonesia' },
  'about.subtitle': { id: 'Standar baru kenyamanan, keselamatan, dan keandalan sewa bus pariwisata eksekutif.', en: 'A new benchmark in comfort, safety, and reliability for executive charter bus rentals.' },
  'about.company_name': { id: 'PT Semesta Jaya Transport Indonesia', en: 'PT Semesta Jaya Transport Indonesia' },
  'about.vision': { id: 'Visi Kami', en: 'Our Vision' },
  'about.mission': { id: 'Misi Kami', en: 'Our Mission' },
  'about.fleet_garage': { id: 'Fasilitas Garasi & Bengkel Perawatan', en: 'Fleet Garage & Maintenance Depot' },
  'about.legality': { id: 'Legalitas & Sertifikasi Resmi', en: 'Legality & Official Certification' },

  // Admin & Super Admin
  'admin.dashboard': { id: 'Dashboard Utama', en: 'Main Dashboard' },
  'admin.bookings': { id: 'Manajemen Pemesanan', en: 'Booking Management' },
  'admin.transactions': { id: 'Verifikasi Transaksi', en: 'Transaction Verification' },
  'admin.fleets': { id: 'Kelola Armada Bus', en: 'Bus Fleet Management' },
  'admin.discounts': { id: 'Kelola Promo & Kupon', en: 'Promos & Coupons' },
  'admin.posts': { id: 'CMS Blog & Artikel', en: 'CMS Blog & Articles' },
  'admin.users': { id: 'Manajemen Pengguna', en: 'User Management' },
  'admin.settings': { id: 'Pengaturan Sistem', en: 'System Settings' },
  'admin.back_to_site': { id: 'Ke Website Utama', en: 'Back to Website' },
  'admin.super_admin_badge': { id: 'SUPER ADMIN (Penuh)', en: 'SUPER ADMIN (Full Access)' },
  'admin.admin_badge': { id: 'ADMIN OPERASIONAL', en: 'OPERATIONAL ADMIN' },

  // Customer Portal
  'customer.title': { id: 'Portal Pelanggan SJ Trans', en: 'SJ Trans Customer Portal' },
  'customer.my_orders': { id: 'Riwayat Pesanan Saya', en: 'My Order History' },
  'customer.profile': { id: 'Profil Akun', en: 'Account Profile' },
  'customer.new_booking': { id: 'Sewa Bus Baru', en: 'Book New Bus' },
  'customer.upload_proof': { id: 'Upload Bukti Transfer', en: 'Upload Payment Proof' },
  'customer.download_ticket': { id: 'Cetak E-Tiket / Invoice', en: 'Print E-Ticket / Invoice' },

  // Footer
  'footer.desc': {
    id: 'PT Semesta Jaya Transport Indonesia adalah penyedia jasa sewa bus pariwisata profesional terkemuka dengan komitmen keselamatan, kenyamanan, dan ketepatan waktu prima.',
    en: 'PT Semesta Jaya Transport Indonesia is a leading executive charter bus provider dedicated to prime safety, passenger comfort, and reliable punctuality.',
  },
  'footer.address_office': { id: 'Kantor Pusat: Gedung Cyber 2 Lantai 15, Jl. HR Rasuna Said Blok X-5, Kuningan, Jakarta Selatan 12950', en: 'Headquarters: Cyber 2 Tower 15th Floor, Jl. HR Rasuna Said Block X-5, Kuningan, South Jakarta 12950' },
  'footer.address_pool': { id: 'Pool & Garasi: Kawasan Transportasi Cibubur KM 26, Tol Jagorawi, Jakarta Timur 13720', en: 'Bus Depot & Garage: Cibubur Transport Zone KM 26, Jagorawi Expressway, East Jakarta 13720' },
  'footer.quick_links': { id: 'Tautan Cepat', en: 'Quick Links' },
  'footer.fleets': { id: 'Kategori Armada', en: 'Fleet Categories' },
  'footer.rights': { id: 'Hak Cipta Dilindungi Undang-Undang.', en: 'All Rights Reserved.' },
}

export const useI18n = () => {
  const locale = useState<Locale>('app_locale', () => 'id')

  const initLocale = () => {
    if (typeof localStorage !== 'undefined') {
      const saved = localStorage.getItem('sjtrans_lang') as Locale
      if (saved === 'id' || saved === 'en') {
        locale.value = saved
      }
    }
  }

  const setLocale = (newLocale: Locale) => {
    locale.value = newLocale
    if (typeof localStorage !== 'undefined') {
      localStorage.setItem('sjtrans_lang', newLocale)
    }
  }

  const toggleLocale = () => {
    setLocale(locale.value === 'id' ? 'en' : 'id')
  }

  const t = (key: string, fallback?: string): string => {
    const item = translations[key]
    if (item && item[locale.value]) {
      return item[locale.value]
    }
    return fallback || key
  }

  return {
    locale,
    setLocale,
    toggleLocale,
    initLocale,
    t,
  }
}
