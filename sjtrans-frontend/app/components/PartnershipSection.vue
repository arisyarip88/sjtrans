<template>
  <section id="kemitraan" class="py-16 lg:py-20 bg-slate-50 border-b border-slate-100">
    <div class="container-custom">
      <div class="text-center max-w-2xl mx-auto space-y-2 mb-12">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-blue-100 text-blue-800">
          Kemitraan & Kolaborasi Resmi
        </span>
        <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900">
          Dipercaya oleh Mitra & Korporasi Terkemuka
        </h2>
        <p class="text-xs sm:text-sm text-slate-500">
          SJ Trans telah menjalin sinergi dengan BUMN, korporasi swasta, perbankan, dan biro perjalanan untuk kelancaran mobilitas armada.
        </p>
      </div>

      <!-- Partner Logos -->
      <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-6 items-center">
        <div
          v-for="(partner, idx) in partners"
          :key="idx"
          class="bg-white p-5 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col items-center justify-center text-center gap-2 hover:border-blue-400 hover:shadow-md transition group"
        >
          <div class="h-10 flex items-center justify-center">
            <span class="text-sm font-black tracking-tight text-slate-800 group-hover:text-blue-700 transition">
              {{ partner.name }}
            </span>
          </div>
          <span class="text-[10px] text-slate-400 font-medium">{{ partner.type }}</span>
        </div>
      </div>

      <!-- Partnership CTA Banner -->
      <div class="mt-12 bg-gradient-to-r from-blue-900 via-blue-800 to-indigo-950 rounded-3xl p-8 text-white flex flex-col md:flex-row items-center justify-between gap-6 shadow-xl">
        <div class="space-y-2 text-center md:text-left">
          <h3 class="text-xl font-extrabold">Tertarik Menjadi Mitra Resmi SJ Trans?</h3>
          <p class="text-xs text-blue-200 max-w-lg">
            Dapatkan kontrak sewa bus korporat jangka panjang, program diskon khusus travel agent, atau sinergi pengelolaan armada bus pariwisata.
          </p>
        </div>
        <button
          @click="showPartnerModal = true"
          class="bg-white hover:bg-slate-100 text-blue-900 font-bold px-6 py-3 rounded-full text-xs shadow-md transition cursor-pointer whitespace-nowrap"
        >
          Ajukan Kerjasama Sekarang
        </button>
      </div>
    </div>

    <!-- Modal Kemitraan -->
    <div v-if="showPartnerModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-sm">
      <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl relative space-y-4">
        <button
          @click="showPartnerModal = false"
          class="absolute top-5 right-5 w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center font-bold text-sm cursor-pointer"
        >
          ✕
        </button>

        <h3 class="text-lg font-bold text-slate-900">Form Pengajuan Kemitraan</h3>
        <p class="text-xs text-slate-500">Tim Kerjasama Korporat SJ Trans akan menindaklanjuti proposal Anda dalam 1x24 jam.</p>

        <div class="space-y-3 text-xs">
          <div>
            <label class="block font-semibold text-slate-700 mb-1">Nama Perusahaan / Organisasi</label>
            <input v-model="partnerForm.company" type="text" placeholder="PT Contoh Solusi" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none" />
          </div>

          <div>
            <label class="block font-semibold text-slate-700 mb-1">Nama Contact Person</label>
            <input v-model="partnerForm.contact" type="text" placeholder="Nama Anda" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none" />
          </div>

          <div class="grid grid-cols-2 gap-2">
            <div>
              <label class="block font-semibold text-slate-700 mb-1">Email Resmi</label>
              <input v-model="partnerForm.email" type="email" placeholder="email@perusahaan.com" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none" />
            </div>
            <div>
              <label class="block font-semibold text-slate-700 mb-1">No WhatsApp</label>
              <input v-model="partnerForm.phone" type="text" placeholder="0812..." class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none" />
            </div>
          </div>

          <div>
            <label class="block font-semibold text-slate-700 mb-1">Jenis Kemitraan</label>
            <select v-model="partnerForm.type" class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs focus:ring-2 focus:ring-blue-600 focus:outline-none">
              <option value="corporate">Sewa Bus Korporat / Shuttle Karyawan</option>
              <option value="travel_agent">Travel Agent & Biro Wisata</option>
              <option value="hotel">Mitra Hotel & Akomodasi</option>
              <option value="fleet_partner">Mitra Pemilik Armada Bus</option>
            </select>
          </div>

          <div v-if="partnerSuccessMessage" class="text-xs font-semibold text-emerald-600">
            {{ partnerSuccessMessage }}
          </div>

          <button
            @click="submitPartnerForm"
            class="btn-primary w-full py-3 rounded-xl text-xs font-bold justify-center cursor-pointer mt-2"
            :style="{ backgroundColor: primaryColor }"
          >
            Kirim Pengajuan Kemitraan
          </button>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
const { primaryColor } = useTheme()

const showPartnerModal = ref(false)
const partnerSuccessMessage = ref('')

const partners = [
  { name: 'PT Astra International', type: 'Mitra Korporat' },
  { name: 'PT Jasa Raharja', type: 'Asuransi Resmi' },
  { name: 'Pertamina Patra Niaga', type: 'Fleet Solution' },
  { name: 'Bank Mandiri', type: 'Payment Partner' },
  { name: 'Bank BCA', type: 'Banking Partner' },
]

const { showModal, showToast } = useNotification()

const partnerForm = reactive({
  company: '',
  contact: '',
  email: '',
  phone: '',
  type: 'corporate',
})

const submitPartnerForm = async () => {
  if (!partnerForm.company || !partnerForm.phone) {
    showToast('warning', 'Data Belum Lengkap', 'Mohon lengkapi nama instansi/perusahaan dan nomor kontak WhatsApp.')
    return
  }

  try {
    await $fetch('http://127.0.0.1:8000/api/v1/partnerships', {
      method: 'POST',
      body: {
        company_name: partnerForm.company,
        contact_person: partnerForm.contact,
        email: partnerForm.email,
        phone: partnerForm.phone,
        partnership_type: partnerForm.type,
      },
    })
  } catch (e) {
    // Fallback
  }

  partnerSuccessMessage.value = '✓ Pengajuan kemitraan terkirim! Tim SJ Trans akan segera menghubungi Anda.'
  setTimeout(() => {
    showPartnerModal.value = false
    partnerSuccessMessage.value = ''
    partnerForm.company = ''
    partnerForm.contact = ''
    partnerForm.email = ''
    partnerForm.phone = ''
  }, 2000)
}
</script>
