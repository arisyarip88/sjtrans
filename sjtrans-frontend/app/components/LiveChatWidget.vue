<template>
  <div class="fixed bottom-20 md:bottom-6 right-4 sm:right-6 z-50">
    <!-- Floating Trigger Button -->
    <button
      v-if="!isOpen"
      @click="toggleChat"
      class="group flex items-center gap-2.5 px-4 py-3 rounded-full shadow-2xl text-white font-bold text-xs transition-all transform hover:scale-105 cursor-pointer border-2 border-white/50"
      :style="{ backgroundColor: primaryColor }"
      aria-label="Buka Live Chat & FAQ"
    >
      <span class="relative flex h-3 w-3">
        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
        <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
      </span>
      <span>{{ t('chat.trigger') }}</span>
      <span class="bg-white/20 text-white rounded-full px-1.5 py-0.5 text-[10px] font-black">FAQ</span>
    </button>

    <!-- Chat Box Window -->
    <div
      v-else
      class="w-[calc(100vw-2rem)] sm:w-96 bg-white rounded-3xl shadow-2xl border border-slate-200 overflow-hidden flex flex-col h-[520px] max-h-[85vh] animate-fade"
    >
      <!-- Chat Header -->
      <div class="p-4 text-white flex items-center justify-between shadow-md" :style="{ backgroundColor: primaryColor }">
        <div class="flex items-center gap-3">
          <div class="relative">
            <div class="w-10 h-10 rounded-2xl bg-white/20 flex items-center justify-center font-bold text-base shadow-inner">
              SJ
            </div>
            <span class="absolute bottom-0 right-0 w-3 h-3 bg-emerald-400 border-2 border-white rounded-full"></span>
          </div>
          <div>
            <h4 class="text-xs font-bold leading-tight">{{ t('chat.header_title') }}</h4>
            <span class="text-[10px] text-blue-100 flex items-center gap-1 mt-0.5">
              <span class="w-1.5 h-1.5 rounded-full bg-emerald-300"></span>
              {{ t('chat.header_status') }}
            </span>
          </div>
        </div>

        <button
          @click="isOpen = false"
          class="text-white/80 hover:text-white font-bold text-sm p-1.5 hover:bg-white/10 rounded-full transition cursor-pointer"
        >
          ✕
        </button>
      </div>

      <!-- Tab Switcher: Chat CS vs Bantuan & FAQ -->
      <div class="flex border-b border-slate-200 bg-slate-50 text-xs font-bold">
        <button
          @click="activeTab = 'chat'"
          class="flex-1 py-2.5 px-3 text-center transition border-b-2 flex items-center justify-center gap-1.5 cursor-pointer"
          :class="activeTab === 'chat' ? 'text-blue-700 border-blue-600 bg-white' : 'text-slate-500 border-transparent hover:text-slate-800'"
        >
          <span>💬</span>
          <span>{{ t('chat.tab_chat') }}</span>
        </button>
        <button
          @click="activeTab = 'faq'"
          class="flex-1 py-2.5 px-3 text-center transition border-b-2 flex items-center justify-center gap-1.5 cursor-pointer"
          :class="activeTab === 'faq' ? 'text-blue-700 border-blue-600 bg-white' : 'text-slate-500 border-transparent hover:text-slate-800'"
        >
          <span>❓</span>
          <span>{{ t('chat.tab_faq') }}</span>
        </button>
      </div>

      <!-- Quick WhatsApp Connect Banner -->
      <a
        href="https://wa.me/6281288887788?text=Halo%20SJ%20Trans,%20saya%20ingin%20tanya%20sewa%20bus%20pariwisata"
        target="_blank"
        class="bg-emerald-50 hover:bg-emerald-100 border-b border-emerald-100 px-4 py-2 flex items-center justify-between text-[11px] text-emerald-800 font-semibold transition shrink-0"
      >
        <span class="flex items-center gap-1.5">
          <span>📱</span> {{ t('chat.wa_button') }}
        </span>
        <span class="text-xs">➔</span>
      </a>

      <!-- TAB 1: LIVE CHAT CS -->
      <div v-show="activeTab === 'chat'" class="flex-1 flex flex-col min-h-0">
        <!-- Messages Scroll Area -->
        <div ref="chatContainer" class="flex-1 p-4 overflow-y-auto space-y-3 bg-slate-50 text-xs">
          <div
            v-for="(msg, idx) in messages"
            :key="idx"
            class="flex flex-col"
            :class="msg.sender_type === 'customer' ? 'items-end' : 'items-start'"
          >
            <div
              class="max-w-[85%] rounded-2xl px-3.5 py-2.5 shadow-xs leading-relaxed"
              :class="msg.sender_type === 'customer'
                ? 'text-white rounded-br-none'
                : 'bg-white border border-slate-200 text-slate-800 rounded-bl-none'"
              :style="msg.sender_type === 'customer' ? { backgroundColor: primaryColor } : {}"
            >
              {{ msg.message }}
            </div>
            <span class="text-[9px] text-slate-400 mt-1 px-1">
              {{ msg.sender_type === 'customer' ? 'Anda' : (msg.sender_type === 'bot' ? 'SJ Assistant' : 'Admin CS') }}
            </span>
          </div>

          <div v-if="isTyping" class="flex items-center gap-1.5 text-[11px] text-slate-400 italic">
            <span class="w-1.5 h-1.5 rounded-full bg-slate-400 animate-bounce"></span>
            <span class="w-1.5 h-1.5 rounded-full bg-slate-400 animate-bounce [animation-delay:0.2s]"></span>
            <span class="w-1.5 h-1.5 rounded-full bg-slate-400 animate-bounce [animation-delay:0.4s]"></span>
            <span>CS sedang mengetik...</span>
          </div>
        </div>

        <!-- Chat Input Field -->
        <div class="p-3 bg-white border-t border-slate-100 flex items-center gap-2">
          <input
            v-model="inputMessage"
            @keyup.enter="sendChatMessage"
            type="text"
            :placeholder="t('chat.placeholder')"
            class="flex-1 bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600"
          />
          <button
            @click="sendChatMessage"
            class="w-8 h-8 rounded-xl flex items-center justify-center text-white cursor-pointer transition shadow-xs"
            :style="{ backgroundColor: primaryColor }"
          >
            ➤
          </button>
        </div>
      </div>

      <!-- TAB 2: INTEGRATED BANTUAN & FAQ -->
      <div v-show="activeTab === 'faq'" class="flex-1 flex flex-col min-h-0 bg-slate-50">
        <!-- FAQ Search Filter -->
        <div class="p-3 bg-white border-b border-slate-200">
          <div class="relative">
            <input
              v-model="faqSearchQuery"
              type="text"
              :placeholder="t('chat.faq_search')"
              class="w-full bg-slate-50 border border-slate-200 rounded-xl pl-8 pr-3 py-1.5 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600"
            />
            <span class="absolute left-2.5 top-2 text-slate-400 text-xs">🔍</span>
          </div>
        </div>

        <!-- FAQ Items Accordion -->
        <div class="flex-1 p-3 overflow-y-auto space-y-2">
          <div
            v-for="(item, idx) in filteredFaqs"
            :key="idx"
            class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-xs transition"
          >
            <button
              @click="toggleFaqItem(idx)"
              class="w-full p-3 text-left flex items-center justify-between text-xs font-bold text-slate-800 hover:bg-slate-50 transition cursor-pointer"
            >
              <span class="pr-2">{{ locale === 'en' ? item.q_en : item.q_id }}</span>
              <span class="text-slate-400 text-sm font-bold shrink-0">
                {{ openFaqIndex === idx ? '−' : '+' }}
              </span>
            </button>
            <div
              v-if="openFaqIndex === idx"
              class="px-3 pb-3 text-[11px] text-slate-600 leading-relaxed border-t border-slate-100 bg-slate-50/50 pt-2"
            >
              {{ locale === 'en' ? item.a_en : item.a_id }}
            </div>
          </div>

          <div v-if="filteredFaqs.length === 0" class="text-center py-6 text-slate-400 text-xs">
            Tidak ada pertanyaan yang sesuai dengan kata kunci.
          </div>
        </div>

        <!-- Direct Ask CS Banner -->
        <div class="p-2.5 bg-blue-50 border-t border-blue-100 text-center text-xs text-blue-900">
          <span>Butuh jawaban lain? </span>
          <button @click="activeTab = 'chat'" class="font-bold underline text-blue-700 cursor-pointer">
            Tanyakan langsung ke CS
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
const { primaryColor } = useTheme()
const { locale, t } = useI18n()

const isOpen = ref(false)
const activeTab = ref<'chat' | 'faq'>('chat')
const inputMessage = ref('')
const isTyping = ref(false)
const chatContainer = ref<HTMLElement | null>(null)
const faqSearchQuery = ref('')
const openFaqIndex = ref<number | null>(0)

const messages = ref<any[]>([
  {
    sender_type: 'bot',
    message: 'Halo! Selamat datang di Layanan Pelanggan SJ Trans. Ada yang bisa kami bantu seputar reservasi bus pariwisata hari ini?',
  },
])

const faqs = [
  {
    q_id: 'Bagaimana cara memesan sewa bus di SJ Trans?',
    q_en: 'How do I book a tour bus with SJ Trans?',
    a_id: 'Anda dapat menghitung estimasi tarif melalui form kalkulator di beranda atau katalog armada, masukkan tanggal & rute, masukkan kode diskon (jika ada), lalu klik tombol "Reservasi Bus". Tim operasional kami akan segera mengonfirmasi ketersediaan armada.',
    a_en: 'You can calculate an instant rate estimate via our home calculator or fleet catalog, choose dates & route, apply promo codes, and click "Book Bus". Our dispatch team will confirm unit availability immediately.',
  },
  {
    q_id: 'Apakah tarif sudah termasuk BBM, Tol, dan Supir?',
    q_en: 'Does the rental rate include Fuel, Toll, and Driver?',
    a_id: 'Tarif dasar sewa bus pariwisata SJ Trans sudah termasuk armada prima, bahan bakar (BBM), dan jasa kru pengemudi profesional. Biaya tol, parkir, retribusi wisata, serta tips/penginapan kru luar kota biasanya disesuaikan sesuai paket pesanan Anda.',
    a_en: 'Base rental rates include pristine fleet, fuel, and professional chauffeurs. Toll fees, parking, tourism entry, and driver lodging for out-of-town trips are arranged according to your package.',
  },
  {
    q_id: 'Bagaimana sistem pembayaran & transfer bank?',
    q_en: 'What are the payment and bank transfer methods?',
    a_id: 'Kami menerima pembayaran melalui transfer Bank BCA (No. Rekening 123-456-7890 a.n PT Semesta Jaya Transport) dan Bank Mandiri (No. Rekening 137-000-888-999). Down Payment (DP) minimal 30% untuk kunci jadwal, dan pelunasan maksimal H-3 sebelum jadwal penjemputan.',
    a_en: 'We accept bank transfers via BCA (Acc: 123-456-7890 a/n PT Semesta Jaya Transport) and Mandiri (Acc: 137-000-888-999). Minimum 30% deposit locks the schedule, with balance due 3 days before departure.',
  },
  {
    q_id: 'Bagaimana kebijakan pembatalan (cancellation & refund)?',
    q_en: 'What is the cancellation and refund policy?',
    a_id: 'Pembatalan lebih dari 7 hari sebelum jadwal keberangkatan dikenakan biaya administrasi 10% dari nilai DP. Pembatalan H-7 hingga H-3 dikenakan potongan 50% dari DP. Pembatalan H-2 atau mendadak maka DP hangus.',
    a_en: 'Cancellations more than 7 days prior incur a 10% administrative fee from DP. Cancellations between 7 to 3 days incur a 50% deduction. Cancellations within 48 hours forfeit the deposit.',
  },
  {
    q_id: 'Apa saja fasilitas yang tersedia di dalam bus?',
    q_en: 'What amenities are available on board the bus?',
    a_id: 'Seluruh armada kami dilengkapi Full AC Ducting, Reclining Seats berjarak lega, Smart TV LED, Sound System Audio Karaoke dengan Mic Wireless, Port USB Charger di setiap bangku, Coolbox, dan kotak P3K darurat.',
    a_en: 'All units feature Full AC Ducting, spacious Reclining Seats, Smart LED TVs, Audio Karaoke Sound Systems with wireless mics, USB charging ports at each seat, Coolbox, and emergency First Aid kits.',
  },
  {
    q_id: 'Berapa kode kupon diskon promo yang tersedia?',
    q_en: 'What promo discount coupons are currently active?',
    a_id: 'Gunakan kode kupon "SJTPROMO10" untuk potongan harga 10% atau kode "LIBURANSERU" untuk potongan langsung Rp 350.000 pada formulir pemesanan.',
    a_en: 'Use coupon code "SJTPROMO10" for 10% off, or "LIBURANSERU" for a direct Rp 350,000 deduction in your booking form.',
  },
]

const filteredFaqs = computed(() => {
  const query = faqSearchQuery.value.toLowerCase().trim()
  if (!query) return faqs
  return faqs.filter(item => {
    return (
      item.q_id.toLowerCase().includes(query) ||
      item.a_id.toLowerCase().includes(query) ||
      item.q_en.toLowerCase().includes(query) ||
      item.a_en.toLowerCase().includes(query)
    )
  })
})

const toggleFaqItem = (idx: number) => {
  openFaqIndex.value = openFaqIndex.value === idx ? null : idx
}

const toggleChat = () => {
  isOpen.value = !isOpen.value
  if (isOpen.value && activeTab.value === 'chat') {
    scrollToBottom()
  }
}

const scrollToBottom = () => {
  nextTick(() => {
    if (chatContainer.value) {
      chatContainer.value.scrollTop = chatContainer.value.scrollHeight
    }
  })
}

const sendChatMessage = async () => {
  const text = inputMessage.value.trim()
  if (!text) return

  messages.value.push({
    sender_type: 'customer',
    message: text,
  })

  inputMessage.value = ''
  scrollToBottom()

  isTyping.value = true
  setTimeout(() => {
    isTyping.value = false
    let reply = 'Terima kasih atas pesan Anda! Untuk rute sewa bus pariwisata, armada kami siap melayani seluruh pulau Jawa, Bali, dan Sumatera. Anda juga bisa membuka tab "❓ Bantuan & FAQ" di atas atau WhatsApp di 0812-8888-7788.'
    if (text.toLowerCase().includes('diskon') || text.toLowerCase().includes('promo')) {
      reply = 'Kabar gembira! Gunakan kode kupon SJTPROMO10 untuk mendapatkan potongan 10% atau LIBURANSERU untuk potongan langsung Rp 350.000!'
    } else if (text.toLowerCase().includes('harga') || text.toLowerCase().includes('biaya') || text.toLowerCase().includes('tarif')) {
      reply = 'Tarif sewa HiAce mulai Rp 1,3 Juta/hari, Medium Bus 31s mulai Rp 2,2 Juta/hari, dan Big Bus 50-59s mulai Rp 3,2 Juta/hari. Sudah termasuk armada prima dan driver profesional.'
    } else if (text.toLowerCase().includes('rekening') || text.toLowerCase().includes('bayar') || text.toLowerCase().includes('transfer')) {
      reply = 'Pembayaran resmi SJ Trans: Bank BCA No. Rekening 123-456-7890 dan Bank Mandiri No. Rekening 137-000-888-999 a.n PT Semesta Jaya Transport Indonesia.'
    }

    messages.value.push({
      sender_type: 'admin',
      message: reply,
    })
    scrollToBottom()
  }, 1000)
}
</script>
