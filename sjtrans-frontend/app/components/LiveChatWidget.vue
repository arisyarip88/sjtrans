<template>
  <div class="fixed bottom-6 right-6 z-50">
    <!-- Floating Trigger Button -->
    <button
      v-if="!isOpen"
      @click="toggleChat"
      class="group flex items-center gap-2.5 px-4 py-3 rounded-full shadow-2xl text-white font-bold text-xs transition-all transform hover:scale-105 cursor-pointer border-2 border-white/40"
      :style="{ backgroundColor: primaryColor }"
    >
      <span class="relative flex h-3 w-3">
        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
        <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
      </span>
      <span>Live Chat CS</span>
      <span class="bg-white/20 text-white rounded-full p-1 text-[10px]">💬</span>
    </button>

    <!-- Chat Box Window -->
    <div
      v-else
      class="w-80 sm:w-96 bg-white rounded-3xl shadow-2xl border border-slate-200 overflow-hidden flex flex-col h-[480px] animate-fade"
    >
      <!-- Chat Header -->
      <div class="p-4 text-white flex items-center justify-between shadow-md" :style="{ backgroundColor: primaryColor }">
        <div class="flex items-center gap-3">
          <div class="relative">
            <div class="w-9 h-9 rounded-full bg-white/20 flex items-center justify-center font-bold text-sm">
              CS
            </div>
            <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-emerald-400 border-2 border-white rounded-full"></span>
          </div>
          <div>
            <h4 class="text-xs font-bold leading-tight">Customer Support SJ Trans</h4>
            <span class="text-[10px] text-blue-100">Online • Siap Melayani Reservasi</span>
          </div>
        </div>

        <button @click="isOpen = false" class="text-white/80 hover:text-white font-bold text-sm p-1 cursor-pointer">
          ✕
        </button>
      </div>

      <!-- Quick WhatsApp Connect Banner -->
      <a
        href="https://wa.me/6281288887788?text=Halo%20SJ%20Trans,%20saya%20ingin%20tanya%20sewa%20bus%20pariwisata"
        target="_blank"
        class="bg-emerald-50 hover:bg-emerald-100 border-b border-emerald-100 px-4 py-2 flex items-center justify-between text-[11px] text-emerald-800 font-semibold transition"
      >
        <span class="flex items-center gap-1.5">
          <span>💬</span> Chat Cepat via WhatsApp
        </span>
        <span class="text-xs">➔</span>
      </a>

      <!-- Chat Messages Scroll Area -->
      <div ref="chatContainer" class="flex-1 p-4 overflow-y-auto space-y-3 bg-slate-50 text-xs">
        <div
          v-for="(msg, idx) in messages"
          :key="idx"
          class="flex flex-col"
          :class="msg.sender_type === 'customer' ? 'items-end' : 'items-start'"
        >
          <div
            class="max-w-[85%] rounded-2xl px-3.5 py-2.5 shadow-sm leading-relaxed"
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
          placeholder="Ketik pertanyaan Anda di sini..."
          class="flex-1 bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-600"
        />
        <button
          @click="sendChatMessage"
          class="w-8 h-8 rounded-xl flex items-center justify-center text-white cursor-pointer transition shadow-sm"
          :style="{ backgroundColor: primaryColor }"
        >
          ➤
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
const { primaryColor } = useTheme()
const { user } = useAuth()

const isOpen = ref(false)
const inputMessage = ref('')
const isTyping = ref(false)
const chatContainer = ref<HTMLElement | null>(null)

const messages = ref<any[]>([
  {
    sender_type: 'bot',
    message: 'Halo! Selamat datang di Layanan Pelanggan SJ Trans. Ada yang bisa kami bantu seputar reservasi bus pariwisata hari ini?',
  },
])

const toggleChat = () => {
  isOpen.value = !isOpen.value
  scrollToBottom()
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

  // Simulate or call backend
  isTyping.value = true
  setTimeout(() => {
    isTyping.value = false
    let reply = 'Terima kasih atas pesan Anda! Untuk rute sewa bus pariwisata, armada kami siap melayani seluruh pulau Jawa, Bali, dan Sumatera. Anda juga bisa langsung klik tombol "Reservasi Bus" di atas atau WhatsApp di 0812-8888-7788.'
    if (text.toLowerCase().includes('diskon') || text.toLowerCase().includes('promo')) {
      reply = 'Kabar gembira! Gunakan kode kupon SJTPROMO10 untuk mendapatkan potongan 10% atau LIBURANSERU untuk potongan langsung Rp 350.000!'
    } else if (text.toLowerCase().includes('harga') || text.toLowerCase().includes('biaya')) {
      reply = 'Tarif sewa HiAce mulai Rp 1,3 Juta/hari, Medium Bus 31s mulai Rp 2,2 Juta/hari, dan Big Bus 50-59s mulai Rp 3,2 Juta/hari. Sudah termasuk armada prima dan driver profesional.'
    }

    messages.value.push({
      sender_type: 'admin',
      message: reply,
    })
    scrollToBottom()
  }, 1200)
}
</script>
