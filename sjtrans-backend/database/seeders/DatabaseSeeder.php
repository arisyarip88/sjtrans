<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\BusFleet;
use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\Discount;
use App\Models\Faq;
use App\Models\Partnership;
use App\Models\Post;
use App\Models\Role;
use App\Models\SiteSetting;
use App\Models\Testimonial;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Roles
        $roleSuperAdmin = Role::create([
            'name' => 'super_admin',
            'display_name' => 'Super Administrator',
            'description' => 'Akses penuh ke semua konfigurasi sistem, kelola admin, dan laporan.',
        ]);

        $roleAdmin = Role::create([
            'name' => 'admin',
            'display_name' => 'Administrator',
            'description' => 'Mengelola operasional sewa bus, armada, artikel, dan verifikasi reservasi.',
        ]);

        $roleCustomer = Role::create([
            'name' => 'customer',
            'display_name' => 'Pelanggan',
            'description' => 'Pengguna terdaftar yang dapat memesan bus dan melihat riwayat sewa.',
        ]);

        // 2. Users
        $superAdmin = User::create([
            'role_id' => $roleSuperAdmin->id,
            'name' => 'Super Admin SJ Trans',
            'username' => 'superadmin',
            'email' => 'admin@sjtrans.co.id',
            'phone_number' => '081122334455',
            'password' => Hash::make('password123'),
            'avatar' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=200&q=80',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $admin = User::create([
            'role_id' => $roleAdmin->id,
            'name' => 'Admin Operasional',
            'username' => 'admin',
            'email' => 'staff@sjtrans.co.id',
            'phone_number' => '081199887766',
            'password' => Hash::make('password123'),
            'avatar' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=200&q=80',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $customer = User::create([
            'role_id' => $roleCustomer->id,
            'name' => 'Budi Santoso',
            'username' => 'budi',
            'email' => 'budi@gmail.com',
            'phone_number' => '081234567890',
            'password' => Hash::make('password123'),
            'avatar' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=200&q=80',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // 3. Categories
        $catBigBus = Category::create([
            'name' => 'Big Bus',
            'slug' => 'big-bus',
            'type' => 'fleet',
            'description' => 'Bus kapasitas besar 45-59 kursi untuk rombongan wisata, study tour, dan gathering.',
        ]);

        $catMediumBus = Category::create([
            'name' => 'Medium Bus',
            'slug' => 'medium-bus',
            'type' => 'fleet',
            'description' => 'Bus medium 29-35 kursi, fleksibel untuk destinasi dalam dan luar kota.',
        ]);

        $catHiace = Category::create([
            'name' => 'Microbus & Van',
            'slug' => 'microbus-van',
            'type' => 'fleet',
            'description' => 'Toyota HiAce Premio Luxury & Commuter 14-15 kursi untuk kenyamanan keluarga & VIP.',
        ]);

        $catBlogWisata = Category::create([
            'name' => 'Tips & Destinasi',
            'slug' => 'tips-destinasi',
            'type' => 'post',
            'description' => 'Artikel rekomendasi rute wisata dan panduan perjalanan aman.',
        ]);

        // 4. Bus Fleets (Terinspirasi TRAC Astra)
        $fleet1 = BusFleet::create([
            'category_id' => $catBigBus->id,
            'name' => 'Big Bus SHD Scania K410IB Jetbus 5',
            'slug' => 'big-bus-shd-scania-k410ib-jetbus-5',
            'capacity' => 50,
            'seat_layout' => '2-2',
            'price_per_day' => 3800000,
            'facilities' => [
                'Full AC Ducting',
                'Audio Karaoke & Wireless Mic',
                'Android Smart TV LED 43 Inch',
                'Reclining Seats Ergonomis',
                'USB Fast Charging di Setiap Kursi',
                'Toilet Bersih On-Board',
                'Dispenser Air Panas & Dingin',
                'Wifi Kecepatan Tinggi',
                'Bagasi Bawah Luas & Tembus',
            ],
            'specifications' => [
                'Chassis' => 'Scania K410IB Opticruise',
                'Suspension' => 'Electronically Controlled Air Suspension',
                'Body' => 'Jetbus 5 Super High Deck by Adiputro',
                'Safety' => 'ABS, EBS, Retarder, Emergency Exits, Fire Extinguisher',
            ],
            'thumbnail_url' => 'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?auto=format&fit=crop&w=800&q=80',
            'gallery_urls' => [
                'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?auto=format&fit=crop&w=1200&q=80',
                'https://images.unsplash.com/photo-1570125909232-eb263c188f7e?auto=format&fit=crop&w=1200&q=80',
                'https://images.unsplash.com/photo-1494515843206-f3117d3f51b7?auto=format&fit=crop&w=1200&q=80',
            ],
            'description' => 'Armada flagship SJ Trans dengan sasis Scania K410IB suspensi udara yang luar biasa empuk. Dilengkapi toilet, dispenser, dan karaoke untuk perjalanan jarak jauh yang sangat santai.',
            'status' => 'available',
        ]);

        $fleet2 = BusFleet::create([
            'category_id' => $catBigBus->id,
            'name' => 'Big Bus Mercedes-Benz OH 1626 Pariwisata',
            'slug' => 'big-bus-mercedes-benz-oh-1626',
            'capacity' => 59,
            'seat_layout' => '2-3',
            'price_per_day' => 3200000,
            'facilities' => [
                'Full AC Dingin Maksimal',
                'Audio System & 2 TV LED',
                'Reclining Seat dengan Armrest',
                'USB Charging Port',
                'Bagasi Luas untuk Koper Rombongan',
                'Peralatan Medis P3K & APAR',
            ],
            'specifications' => [
                'Chassis' => 'Mercedes-Benz OH 1626 NG',
                'Suspension' => 'Built-in Air Suspension',
                'Body' => 'Avante H8 by Tentrem',
            ],
            'thumbnail_url' => 'https://images.unsplash.com/photo-1570125909232-eb263c188f7e?auto=format&fit=crop&w=800&q=80',
            'gallery_urls' => [
                'https://images.unsplash.com/photo-1570125909232-eb263c188f7e?auto=format&fit=crop&w=1200&q=80',
            ],
            'description' => 'Solusi hemat dan berkapasitas besar hingga 59 penumpang. Sangat diminati untuk kegiatan study tour sekolah, ziarah, dan outing perusahaan skala besar.',
            'status' => 'available',
        ]);

        $fleet3 = BusFleet::create([
            'category_id' => $catMediumBus->id,
            'name' => 'Medium Bus Jetbus 3+ MD Executive',
            'slug' => 'medium-bus-jetbus-3-md-executive',
            'capacity' => 31,
            'seat_layout' => '2-2',
            'price_per_day' => 2200000,
            'facilities' => [
                'AC Sejuk Merata',
                'Audio Karaoke & Smart TV',
                'Reclining Seat Lebar',
                'Port USB di Setiap Baris',
                'Bagasi Samping & Belakang',
            ],
            'specifications' => [
                'Chassis' => 'Mitsubishi Canter / Hino GB',
                'Body' => 'Jetbus 3+ MD Adiputro',
            ],
            'thumbnail_url' => 'https://images.unsplash.com/photo-1557223562-6c77ef16210f?auto=format&fit=crop&w=800&q=80',
            'gallery_urls' => [
                'https://images.unsplash.com/photo-1557223562-6c77ef16210f?auto=format&fit=crop&w=1200&q=80',
            ],
            'description' => 'Bus medium yang gesit bermanuver di rute perbukitan seperti Puncak, Lembang, dan Guci. Muat 31 penumpang dengan kenyamanan kursi berkelas eksekutif.',
            'status' => 'available',
        ]);

        $fleet4 = BusFleet::create([
            'category_id' => $catHiace->id,
            'name' => 'Toyota HiAce Premio Luxury VIP',
            'slug' => 'toyota-hiace-premio-luxury-vip',
            'capacity' => 14,
            'seat_layout' => '2-1 Captain Seats',
            'price_per_day' => 1600000,
            'facilities' => [
                'Captain Seats Kulit Mewah',
                'Smart Android TV & Audio Bass',
                'AC Individual Tiap Penumpang',
                'USB Port & Cup Holder',
                'Gorden Privasi',
                'Kabin Kedap Suara',
            ],
            'specifications' => [
                'Engine' => '2.8L 1GD-FTV Turbo Diesel',
                'Interior' => 'Custom Luxury VIP by BAV',
            ],
            'thumbnail_url' => 'https://images.unsplash.com/photo-1519003722824-194d4455a60c?auto=format&fit=crop&w=800&q=80',
            'gallery_urls' => [
                'https://images.unsplash.com/photo-1519003722824-194d4455a60c?auto=format&fit=crop&w=1200&q=80',
            ],
            'description' => 'Pilihan ideal untuk penjemputan tamu VIP, rombongan direksi, atau liburan keluarga berkelas dengan kenyamanan setara business class.',
            'status' => 'available',
        ]);

        // 5. Discounts
        $disc1 = Discount::create([
            'code' => 'SJTPROMO10',
            'title' => 'Promo Diskon 10% Sewa Bus Pariwisata',
            'discount_type' => 'percentage',
            'discount_value' => 10.00,
            'min_transaction' => 2000000,
            'max_discount' => 500000,
            'start_date' => Carbon::now()->subDays(5),
            'end_date' => Carbon::now()->addDays(60),
            'quota' => 100,
            'used_count' => 12,
            'is_active' => true,
        ]);

        $disc2 = Discount::create([
            'code' => 'LIBURANSERU',
            'title' => 'Potongan Langsung Rp 350.000 Musim Liburan',
            'discount_type' => 'fixed',
            'discount_value' => 350000,
            'min_transaction' => 3000000,
            'start_date' => Carbon::now()->subDays(10),
            'end_date' => Carbon::now()->addDays(90),
            'quota' => 50,
            'used_count' => 8,
            'is_active' => true,
        ]);

        // 6. Sample Bookings & Transactions
        $booking1 = Booking::create([
            'booking_code' => 'SJT-202610-001',
            'user_id' => $customer->id,
            'fleet_id' => $fleet1->id,
            'discount_id' => $disc1->id,
            'customer_name' => 'Budi Santoso',
            'customer_email' => 'budi@gmail.com',
            'customer_phone' => '081234567890',
            'pickup_location' => 'Kantor PT Sumber Makmur, Jl. Sudirman Kav 25, Jakarta Selatan',
            'destination' => 'Yogyakarta & Candi Borobudur',
            'start_date' => Carbon::now()->addDays(5),
            'end_date' => Carbon::now()->addDays(8),
            'total_days' => 3,
            'total_passengers' => 45,
            'total_units' => 1,
            'subtotal' => 11400000, // 3 x 3.800.000
            'discount_code' => 'SJTPROMO10',
            'discount_amount' => 500000,
            'total_price' => 10900000,
            'special_notes' => 'Tolong sediakan air mineral galon cadangan dan driver yang ramah.',
            'status' => 'confirmed',
            'payment_status' => 'down_payment',
        ]);

        Transaction::create([
            'booking_id' => $booking1->id,
            'invoice_number' => 'INV-SJT-202610-0001',
            'amount' => 5000000,
            'payment_type' => 'down_payment',
            'payment_method' => 'bank_transfer',
            'payment_proof_url' => 'https://images.unsplash.com/photo-1554224155-8d04cb21cd6c?auto=format&fit=crop&w=600&q=80',
            'status' => 'verified',
            'verified_by' => $admin->id,
            'verified_at' => Carbon::now()->subHours(2),
            'notes' => 'DP 50% diterima melalui rekening BCA PT SJ Trans. Pemesanan terkonfirmasi.',
        ]);

        $booking2 = Booking::create([
            'booking_code' => 'SJT-202610-002',
            'user_id' => null,
            'fleet_id' => $fleet3->id,
            'discount_id' => null,
            'customer_name' => 'Dewi Lestari',
            'customer_email' => 'dewi.lestari@perusahaan.co.id',
            'customer_phone' => '081399887711',
            'pickup_location' => 'Kelapa Gading, Jakarta Utara',
            'destination' => 'Ciwidey, Bandung Selatan',
            'start_date' => Carbon::now()->addDays(12),
            'end_date' => Carbon::now()->addDays(13),
            'total_days' => 1,
            'total_passengers' => 28,
            'total_units' => 1,
            'subtotal' => 2200000,
            'discount_code' => null,
            'discount_amount' => 0,
            'total_price' => 2200000,
            'special_notes' => 'Acara gathering divisi sales, mohon driver paham jalan alternatif.',
            'status' => 'pending',
            'payment_status' => 'unpaid',
        ]);

        // 7. Testimonials
        Testimonial::create([
            'user_id' => $customer->id,
            'fleet_id' => $fleet1->id,
            'customer_name' => 'Bambang Pratama',
            'customer_role_or_company' => 'HR Manager PT Astra Sedaya Finance',
            'rating' => 5,
            'comment' => 'Pelayanan SJ Trans sangat memuaskan! Bus Scania SHD-nya sangat bersih, AC dingin, dan suspensinya membuat perjalanan rombongan ke Jogja tanpa mabuk darat. Pengemudinya sangat profesional.',
            'photo_url' => 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=200&q=80',
            'is_approved' => true,
            'is_featured' => true,
        ]);

        Testimonial::create([
            'user_id' => null,
            'fleet_id' => $fleet4->id,
            'customer_name' => 'Siti Nurhaliza',
            'customer_role_or_company' => 'Koordinator Study Tour SMA 34 Jakarta',
            'rating' => 5,
            'comment' => 'Sewa HiAce Premio untuk rombongan dewan guru sangat nyaman. Kursi captain seat empuk, karaoke berfungsi sangat baik, anak-anak dan guru sangat senang sepanjang jalan.',
            'photo_url' => 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?auto=format&fit=crop&w=200&q=80',
            'is_approved' => true,
            'is_featured' => true,
        ]);

        Testimonial::create([
            'user_id' => null,
            'fleet_id' => $fleet3->id,
            'customer_name' => 'Hendra Kusuma',
            'customer_role_or_company' => 'Family Gathering Rombongan Bandung',
            'rating' => 5,
            'comment' => 'Proses booking lewat website sangat cepat dan transparan! Dapat potongan kode promo SJTPROMO10, admin cepat tanggap dan ramah saat verifikasi pembayaran.',
            'photo_url' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=200&q=80',
            'is_approved' => true,
            'is_featured' => true,
        ]);

        // 8. Partnerships
        Partnership::create([
            'company_name' => 'PT Astra International Tbk',
            'contact_person' => 'Divisi Kerjasama Korporat',
            'email' => 'partner@astra.co.id',
            'phone' => '021-65300000',
            'logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/a2/Astra_International_logo.svg/320px-Astra_International_logo.svg.png',
            'website_url' => 'https://www.astra.co.id',
            'partnership_type' => 'corporate',
            'status' => 'active',
        ]);

        Partnership::create([
            'company_name' => 'PT Jasa Raharja (Persero)',
            'contact_person' => 'Bagian Asuransi Penumpang',
            'email' => 'info@jasaraharja.co.id',
            'phone' => '1500-020',
            'logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/27/Logo_Jasa_Raharja.png/320px-Logo_Jasa_Raharja.png',
            'website_url' => 'https://jasaraharja.co.id',
            'partnership_type' => 'sponsor',
            'status' => 'active',
        ]);

        Partnership::create([
            'company_name' => 'PT Pertamina Patra Niaga',
            'contact_person' => 'Fleet Solution Partnership',
            'email' => 'fleet@pertamina.com',
            'phone' => '135',
            'logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/3/36/Pertamina_Logo.svg/320px-Pertamina_Logo.svg.png',
            'website_url' => 'https://pertamina.com',
            'partnership_type' => 'corporate',
            'status' => 'active',
        ]);

        Partnership::create([
            'company_name' => 'Bank Mandiri (Persero) Tbk',
            'contact_person' => 'Merchant Payment Gateway',
            'email' => 'mandiricare@bankmandiri.co.id',
            'phone' => '14000',
            'logo_url' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/ad/Bank_Mandiri_logo_2016.svg/320px-Bank_Mandiri_logo_2016.svg.png',
            'website_url' => 'https://bankmandiri.co.id',
            'partnership_type' => 'hotel',
            'status' => 'active',
        ]);

        // 9. Site Settings (Footer, Contacts, Sosmed, Location, Theme)
        $settings = [
            // Theme settings (Tone Biru SJ Trans)
            ['key' => 'theme_primary_color', 'value' => '#1E40AF', 'group' => 'theme'],
            ['key' => 'theme_primary_hover', 'value' => '#1D4ED8', 'group' => 'theme'],
            ['key' => 'theme_accent_color', 'value' => '#0284C7', 'group' => 'theme'],
            ['key' => 'theme_active_preset', 'value' => 'royal_blue', 'group' => 'theme'],

            // Company & Identity
            ['key' => 'company_name', 'value' => 'SJ Trans (PT Semesta Jaya Transport)', 'group' => 'general'],
            ['key' => 'company_tagline', 'value' => 'Sewa Bus Pariwisata Nyaman, Aman, & Terpercaya', 'group' => 'general'],
            ['key' => 'company_description', 'value' => 'SJ Trans adalah perusahaan penyedia jasa transportasi sewa bus pariwisata profesional terkemuka dengan armada Scania, Mercedes-Benz, dan HiAce terbaru. Berpengalaman melayani ribuan perjalanan wisata dan korporat di Indonesia.', 'group' => 'general'],

            // Contact info
            ['key' => 'company_address', 'value' => 'Jl. TB Simatupang No. 88, Ciracas, Pasar Rebo, Jakarta Timur, DKI Jakarta 13830', 'group' => 'contact'],
            ['key' => 'pool_address', 'value' => 'Pool Utama SJ Trans: KM 26 Tol Jagorawi, Cibubur, Jakarta Timur', 'group' => 'contact'],
            ['key' => 'phone_primary', 'value' => '021-8899-7700', 'group' => 'contact'],
            ['key' => 'phone_hotline', 'value' => '1500-888', 'group' => 'contact'],
            ['key' => 'whatsapp_number', 'value' => '0812-8888-7788', 'group' => 'contact'],
            ['key' => 'email_info', 'value' => 'info@sjtrans.co.id', 'group' => 'contact'],
            ['key' => 'email_booking', 'value' => 'booking@sjtrans.co.id', 'group' => 'contact'],

            // Maps & Location
            ['key' => 'maps_embed_url', 'value' => 'https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126907.08620868837!2d106.77412435552308!3d-6.284279549339345!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e69f3a67710c851%3A0xb3514dc29ff4be6e!2sJakarta%20Timur%2C%20Kota%20Jakarta%20Timur%2C%20Daerah%20Khusus%20Ibukota%20Jakarta!5e0!3m2!1sid!2sid!4v1700000000000!5m2!1sid!2sid', 'group' => 'contact'],
            ['key' => 'maps_location_url', 'value' => 'https://maps.google.com/?q=SJ+Trans+Jakarta', 'group' => 'contact'],

            // Social Media
            ['key' => 'social_instagram', 'value' => 'https://instagram.com/sjtrans_official', 'group' => 'social'],
            ['key' => 'social_facebook', 'value' => 'https://facebook.com/sjtrans.id', 'group' => 'social'],
            ['key' => 'social_tiktok', 'value' => 'https://tiktok.com/@sjtrans_id', 'group' => 'social'],
            ['key' => 'social_youtube', 'value' => 'https://youtube.com/@sjtranschannel', 'group' => 'social'],
            ['key' => 'social_linkedin', 'value' => 'https://linkedin.com/company/sjtrans-indonesia', 'group' => 'social'],
        ];

        foreach ($settings as $setting) {
            SiteSetting::create($setting);
        }

        // 10. FAQs
        $faqs = [
            [
                'question' => 'Bagaimana cara melakukan pemesanan sewa bus di SJ Trans?',
                'answer' => 'Pemesanan sangat mudah! Anda bisa menggunakan formulir reservasi cepat di beranda, memilih tanggal sewa, tujuan, serta armada yang diinginkan. Anda juga bisa langsung chat tim customer service kami atau menghubungi hotline 1500-888.',
                'category' => 'Pemesanan',
                'order_position' => 1,
            ],
            [
                'question' => 'Berapa besaran Down Payment (DP) yang harus dibayarkan?',
                'answer' => 'Untuk mengunci jadwal armada, Anda cukup melakukan pembayaran Down Payment sebesar 30% dari total nilai sewa. Pelunasan dapat dilakukan maksimal H-3 sebelum tanggal keberangkatan.',
                'category' => 'Pembayaran',
                'order_position' => 2,
            ],
            [
                'question' => 'Apakah harga sewa sudah termasuk bahan bakar, supir, dan tol?',
                'answer' => 'Harga tertera sudah mencakup unit bus prima dan pengemudi profesional bersertifikat. Biaya bahan bakar, tol, parkir, dan akomodasi supir (bila menginap) dapat disesuaikan dalam paket All-In atau mandiri sesuai kebutuhan Anda.',
                'category' => 'Fasilitas & Harga',
                'order_position' => 3,
            ],
            [
                'question' => 'Apakah perjalanan penumpang dilindungi asuransi?',
                'answer' => 'Tentu. Seluruh armada dan penumpang SJ Trans telah dilindungi oleh asuransi resmi perjalanan Jasa Raharja dan proteksi asuransi mitra terpercaya.',
                'category' => 'Keamanan',
                'order_position' => 4,
            ],
        ];

        foreach ($faqs as $faq) {
            Faq::create($faq);
        }

        // 11. Posts (Blog Artikel)
        Post::create([
            'author_id' => $superAdmin->id,
            'category_id' => $catBlogWisata->id,
            'title' => 'Panduan Lengkap Memilih Bus Pariwisata untuk Study Tour Sekolah',
            'slug' => 'panduan-memilih-bus-pariwisata-study-tour',
            'type' => 'post',
            'excerpt' => 'Ketahui faktor keselamatan, kapasitas kursi, dan fasilitas penting sebelum memesan bus pariwisata agar study tour siswa berjalan aman dan menyenangkan.',
            'content' => '<h3>1. Pastikan Legalitas dan Perizinan Perusahaan Otobus</h3><p>Keselamatan adalah prioritas utama. Selalu cek apakah PO bus memiliki izin trayek pariwisata resmi dan uji KIR kendaraan yang masih aktif.</p><h3>2. Cek Fasilitas Sesuai Kebutuhan Peserta</h3><p>Untuk perjalanan di atas 8 jam, pilihlah bus dengan fasilitas toilet, reclining seat berjarak lega, dan dispenser air minum.</p>',
            'featured_image' => 'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?auto=format&fit=crop&w=800&q=80',
            'status' => 'published',
            'views_count' => 1420,
            'meta_title' => 'Panduan Memilih Bus Pariwisata Study Tour - SJ Trans',
            'meta_description' => 'Tips terbaik memilih armada bus pariwisata aman untuk study tour sekolah di Jakarta.',
            'published_at' => Carbon::now()->subDays(3),
        ]);

        Post::create([
            'author_id' => $admin->id,
            'category_id' => $catBlogWisata->id,
            'title' => 'Rekomendasi 7 Destinasi Wisata Terbaik di Bandung untuk Rombongan Bus',
            'slug' => 'rekomendasi-destinasi-wisata-bandung-rombongan-bus',
            'type' => 'post',
            'excerpt' => 'Daftar objek wisata ramah parkir bus besar di Bandung yang wajib dikunjungi bersama keluarga besar dan rekan sekantor.',
            'content' => '<p>Bandung selalu menjadi magnet liburan favorit warga Jakarta. Namun, memilih tempat wisata yang memiliki akses jalan lebar dan area parkir luas untuk Big Bus sangat krusial.</p>',
            'featured_image' => 'https://images.unsplash.com/photo-1570125909232-eb263c188f7e?auto=format&fit=crop&w=800&q=80',
            'status' => 'published',
            'views_count' => 980,
            'meta_title' => '7 Tempat Wisata Bandung untuk Rombongan Bus - SJ Trans',
            'meta_description' => 'Objek wisata Bandung dengan akses bus pariwisata mudah dan nyaman.',
            'published_at' => Carbon::now()->subDays(1),
        ]);

        // 12. Static Pages
        Post::create([
            'author_id' => $superAdmin->id,
            'category_id' => null,
            'title' => 'Tentang Kami - SJ Trans Indonesia',
            'slug' => 'tentang-kami',
            'type' => 'page',
            'excerpt' => 'Profil PT Semesta Jaya Transport (SJ Trans).',
            'content' => '<h2>Profil Perusahaan SJ Trans</h2><p>Didirikan dengan dedikasi tinggi untuk menghadirkan standar baru dalam industri transportasi pariwisata Indonesia, SJ Trans senantiasa mengedepankan keamanan, kebersihan armada, dan keramahan pelayanan para kru profesional kami.</p>',
            'featured_image' => 'https://images.unsplash.com/photo-1494515843206-f3117d3f51b7?auto=format&fit=crop&w=800&q=80',
            'status' => 'published',
            'views_count' => 540,
            'published_at' => Carbon::now()->subDays(10),
        ]);
    }
}
