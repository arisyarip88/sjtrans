<template>
  <div v-if="isOpen" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-sm animate-fade">
    <div class="bg-white rounded-3xl max-w-md w-full p-6 sm:p-8 shadow-2xl relative">
      <!-- Close Button -->
      <button
        @click="$emit('close')"
        class="absolute top-5 right-5 w-8 h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 flex items-center justify-center font-bold text-sm cursor-pointer"
      >
        ✕
      </button>

      <!-- Tabs Header -->
      <div class="flex border-b border-slate-100 mb-6">
        <button
          @click="activeTab = 'login'"
          class="flex-1 pb-3 text-center font-bold text-sm transition cursor-pointer border-b-2"
          :class="activeTab === 'login' ? 'border-blue-600 text-blue-700' : 'border-transparent text-slate-400 hover:text-slate-600'"
        >
          Masuk Akun
        </button>
        <button
          @click="activeTab = 'register'"
          class="flex-1 pb-3 text-center font-bold text-sm transition cursor-pointer border-b-2"
          :class="activeTab === 'register' ? 'border-blue-600 text-blue-700' : 'border-transparent text-slate-400 hover:text-slate-600'"
        >
          Daftar Customer
        </button>
      </div>

      <!-- TAB 1: LOGIN (Bisa menggunakan Username ATAU Email) -->
      <div v-if="activeTab === 'login'" class="space-y-4">
        <div>
          <h3 class="text-xl font-black text-slate-900">Selamat Datang Kembali</h3>
          <p class="text-xs text-slate-500 mt-0.5">Masuk menggunakan Username ATAU Email yang terdaftar.</p>
        </div>

        <form @submit.prevent="handleLogin" class="space-y-3.5 text-xs">
          <div>
            <label class="block font-semibold text-slate-700 mb-1">Username atau Email</label>
            <input
              v-model="loginForm.login"
              type="text"
              required
              placeholder="Contoh: superadmin atau admin@sjtrans.co.id"
              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 font-medium focus:ring-2 focus:ring-blue-600 focus:outline-none"
            />
          </div>

          <div>
            <label class="block font-semibold text-slate-700 mb-1">Password</label>
            <input
              v-model="loginForm.password"
              type="password"
              required
              placeholder="••••••••"
              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3.5 py-2.5 text-xs text-slate-800 font-medium focus:ring-2 focus:ring-blue-600 focus:outline-none"
            />
          </div>

          <div v-if="errorMessage" class="p-2.5 rounded-xl bg-red-50 border border-red-200 text-red-600 font-medium text-xs">
            {{ errorMessage }}
          </div>

          <button
            type="submit"
            :disabled="isLoading"
            class="btn-primary w-full py-3 rounded-xl text-xs font-bold justify-center cursor-pointer shadow-md"
            :style="{ backgroundColor: primaryColor }"
          >
            <span v-if="isLoading">Memproses Masuk...</span>
            <span v-else>Masuk ke Akun SJ Trans</span>
          </button>
        </form>

        <!-- Quick Demo Accounts (Untuk kemudahan pengujian 3 Role) -->
        <div class="pt-4 border-t border-slate-100 space-y-2">
          <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider text-center">Akun Demo Pengujian:</div>
          <div class="grid grid-cols-3 gap-2 text-[10px]">
            <button
              @click="fillDemo('superadmin', 'password123')"
              class="p-2 rounded-xl bg-purple-50 hover:bg-purple-100 text-purple-800 font-bold border border-purple-200 text-center transition cursor-pointer"
            >
              Super Admin
            </button>
            <button
              @click="fillDemo('admin', 'password123')"
              class="p-2 rounded-xl bg-blue-50 hover:bg-blue-100 text-blue-800 font-bold border border-blue-200 text-center transition cursor-pointer"
            >
              Admin Staff
            </button>
            <button
              @click="fillDemo('budi', 'password123')"
              class="p-2 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-800 font-bold border border-emerald-200 text-center transition cursor-pointer"
            >
              Customer
            </button>
          </div>
        </div>
      </div>

      <!-- TAB 2: REGISTER (Username, Email, No Telpon, Password) -->
      <div v-else class="space-y-4">
        <div>
          <h3 class="text-xl font-black text-slate-900">Buat Akun Pelanggan</h3>
          <p class="text-xs text-slate-500 mt-0.5">Daftarkan akun untuk kemudahan pemesanan & kupon promo.</p>
        </div>

        <form @submit.prevent="handleRegister" class="space-y-3 text-xs">
          <div>
            <label class="block font-semibold text-slate-700 mb-1">Nama Lengkap</label>
            <input
              v-model="regForm.name"
              type="text"
              required
              placeholder="Contoh: Rian Anggara"
              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 focus:ring-2 focus:ring-blue-600 focus:outline-none"
            />
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
            <div>
              <label class="block font-semibold text-slate-700 mb-1">Username</label>
              <input
                v-model="regForm.username"
                type="text"
                required
                placeholder="rian88"
                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 focus:ring-2 focus:ring-blue-600 focus:outline-none"
              />
            </div>
            <div>
              <label class="block font-semibold text-slate-700 mb-1">No. WhatsApp / Telepon</label>
              <input
                v-model="regForm.phone_number"
                type="tel"
                required
                placeholder="08123456789"
                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 focus:ring-2 focus:ring-blue-600 focus:outline-none"
              />
            </div>
          </div>

          <div>
            <label class="block font-semibold text-slate-700 mb-1">Alamat Email</label>
            <input
              v-model="regForm.email"
              type="email"
              required
              placeholder="rian@example.com"
              class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 focus:ring-2 focus:ring-blue-600 focus:outline-none"
            />
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
            <div>
              <label class="block font-semibold text-slate-700 mb-1">Password</label>
              <input
                v-model="regForm.password"
                type="password"
                required
                minlength="8"
                placeholder="Minimal 8 karakter"
                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 focus:ring-2 focus:ring-blue-600 focus:outline-none"
              />
            </div>
            <div>
              <label class="block font-semibold text-slate-700 mb-1">Ulangi Password</label>
              <input
                v-model="regForm.password_confirmation"
                type="password"
                required
                placeholder="Ulangi password"
                class="w-full bg-slate-50 border border-slate-200 rounded-xl px-3 py-2 text-xs text-slate-800 focus:ring-2 focus:ring-blue-600 focus:outline-none"
              />
            </div>
          </div>

          <div v-if="errorMessage" class="p-2.5 rounded-xl bg-red-50 border border-red-200 text-red-600 font-medium text-xs">
            {{ errorMessage }}
          </div>

          <div v-if="successMessage" class="p-2.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 font-medium text-xs">
            {{ successMessage }}
          </div>

          <button
            type="submit"
            :disabled="isLoading"
            class="btn-primary w-full py-3 rounded-xl text-xs font-bold justify-center cursor-pointer shadow-md mt-2"
            :style="{ backgroundColor: primaryColor }"
          >
            <span v-if="isLoading">Mendaftarkan...</span>
            <span v-else>Daftar Akun Pelanggan (Customer)</span>
          </button>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
const props = defineProps<{
  isOpen: boolean
  defaultTab?: 'login' | 'register'
}>()

const emit = defineEmits(['close', 'auth-success'])

const { primaryColor } = useTheme()
const { login, register, isLoading } = useAuth()

const activeTab = ref<'login' | 'register'>(props.defaultTab || 'login')
const errorMessage = ref('')
const successMessage = ref('')

watch(() => props.defaultTab, (val) => {
  if (val) activeTab.value = val
})

const loginForm = reactive({
  login: '',
  password: '',
})

const regForm = reactive({
  name: '',
  username: '',
  email: '',
  phone_number: '',
  password: '',
  password_confirmation: '',
})

const fillDemo = (loginVal: string, passVal: string) => {
  loginForm.login = loginVal
  loginForm.password = passVal
  errorMessage.value = ''
}

const handleLogin = async () => {
  errorMessage.value = ''
  const result = await login(loginForm.login, loginForm.password)
  if (result.success) {
    emit('auth-success')
    emit('close')
  } else {
    errorMessage.value = result.message
  }
}

const handleRegister = async () => {
  errorMessage.value = ''
  successMessage.value = ''

  if (regForm.password !== regForm.password_confirmation) {
    errorMessage.value = 'Konfirmasi password tidak cocok.'
    return
  }

  const result = await register({
    name: regForm.name,
    username: regForm.username,
    email: regForm.email,
    phone_number: regForm.phone_number,
    password: regForm.password,
    password_confirmation: regForm.password_confirmation,
  })

  if (result.success) {
    successMessage.value = result.message
    setTimeout(() => {
      emit('auth-success')
      emit('close')
    }, 1500)
  } else {
    errorMessage.value = result.message
  }
}
</script>
