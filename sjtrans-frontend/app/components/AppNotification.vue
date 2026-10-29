<template>
  <div class="relative">
    <!-- 1. FLOATING TOAST NOTIFICATIONS (Top Right) -->
    <div
      v-if="toasts.length > 0"
      class="fixed top-5 right-4 sm:right-6 z-[100] flex flex-col gap-3 max-w-sm w-full pointer-events-none"
    >
      <TransitionGroup
        enter-active-class="transition duration-300 ease-out"
        enter-from-class="transform translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-4"
        enter-to-class="transform translate-y-0 opacity-100 sm:translate-x-0"
        leave-active-class="transition duration-200 ease-in"
        leave-from-class="opacity-100 scale-100"
        leave-to-class="opacity-0 scale-95"
      >
        <div
          v-for="toast in toasts"
          :key="toast.id"
          class="pointer-events-auto bg-white/95 backdrop-blur-md rounded-2xl p-4 border shadow-xl flex items-start gap-3.5 transition-all"
          :class="{
            'border-emerald-200 shadow-emerald-500/10': toast.type === 'success',
            'border-rose-200 shadow-rose-500/10': toast.type === 'error',
            'border-amber-200 shadow-amber-500/10': toast.type === 'warning',
            'border-blue-200 shadow-blue-500/10': toast.type === 'info',
          }"
        >
          <!-- Toast Icon Badge -->
          <div
            class="w-9 h-9 rounded-xl shrink-0 flex items-center justify-center font-bold text-sm shadow-xs"
            :class="{
              'bg-emerald-100 text-emerald-700': toast.type === 'success',
              'bg-rose-100 text-rose-700': toast.type === 'error',
              'bg-amber-100 text-amber-700': toast.type === 'warning',
              'bg-blue-100 text-blue-700': toast.type === 'info',
            }"
          >
            <svg v-if="toast.type === 'success'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
            </svg>
            <svg v-else-if="toast.type === 'error'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
            </svg>
            <svg v-else-if="toast.type === 'warning'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>

          <!-- Toast Content -->
          <div class="flex-1 min-w-0 pr-1">
            <h4 class="text-xs font-black text-slate-900 tracking-tight">{{ toast.title }}</h4>
            <p class="text-[11px] text-slate-600 mt-0.5 leading-relaxed">{{ toast.message }}</p>
          </div>

          <!-- Dismiss Button -->
          <button
            @click="removeToast(toast.id)"
            class="text-slate-400 hover:text-slate-700 p-1 rounded-lg hover:bg-slate-100 transition cursor-pointer text-xs"
          >
            ✕
          </button>
        </div>
      </TransitionGroup>
    </div>

    <!-- 2. RICH MODAL NOTIFICATION DIALOG (Replaces Browser alert()) -->
    <div
      v-if="modal.isOpen"
      class="fixed inset-0 z-[110] flex items-center justify-center p-4 bg-slate-950/70 backdrop-blur-md animate-fade"
    >
      <div
        class="bg-white rounded-3xl max-w-sm w-full p-6 sm:p-7 shadow-2xl border border-slate-100 text-center relative transform transition-all scale-100"
      >
        <!-- Icon Badge with Glow Effect -->
        <div class="flex justify-center mb-4">
          <div
            class="w-16 h-16 rounded-3xl flex items-center justify-center shadow-lg transform transition-transform"
            :class="{
              'bg-emerald-500 text-white ring-8 ring-emerald-100 shadow-emerald-500/30': modal.type === 'success',
              'bg-rose-500 text-white ring-8 ring-rose-100 shadow-rose-500/30': modal.type === 'error',
              'bg-amber-500 text-white ring-8 ring-amber-100 shadow-amber-500/30': modal.type === 'warning',
              'bg-blue-600 text-white ring-8 ring-blue-100 shadow-blue-500/30': modal.type === 'info',
            }"
          >
            <!-- Success Checkmark -->
            <svg v-if="modal.type === 'success'" class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
            </svg>
            <!-- Error Cross -->
            <svg v-else-if="modal.type === 'error'" class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
            </svg>
            <!-- Warning Triangle -->
            <svg v-else-if="modal.type === 'warning'" class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <!-- Info Icon -->
            <svg v-else class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
        </div>

        <!-- Title -->
        <h3 class="text-lg font-black text-slate-900 tracking-tight leading-snug">
          {{ modal.title }}
        </h3>

        <!-- Description/Message -->
        <p class="text-xs text-slate-600 mt-2 mb-6 leading-relaxed">
          {{ modal.message }}
        </p>

        <!-- Action Buttons -->
        <div class="flex flex-col gap-2">
          <button
            @click="handleConfirm"
            class="w-full py-3 px-4 rounded-2xl font-bold text-xs text-white transition shadow-md cursor-pointer flex items-center justify-center gap-2"
            :class="{
              'bg-emerald-600 hover:bg-emerald-700 shadow-emerald-600/30': modal.type === 'success',
              'bg-rose-600 hover:bg-rose-700 shadow-rose-600/30': modal.type === 'error',
              'bg-amber-600 hover:bg-amber-700 shadow-amber-600/30': modal.type === 'warning',
              'bg-blue-600 hover:bg-blue-700 shadow-blue-600/30': modal.type === 'info',
            }"
          >
            <span>{{ modal.buttonText || 'Mengerti' }}</span>
          </button>

          <button
            v-if="modal.secondaryButtonText"
            @click="handleCancel"
            class="w-full py-2.5 px-4 rounded-2xl font-bold text-xs text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition cursor-pointer"
          >
            {{ modal.secondaryButtonText }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
const { toasts, modal, removeToast, closeModal } = useNotification()

const handleConfirm = () => {
  if (modal.value.onConfirm) {
    modal.value.onConfirm()
  }
  closeModal()
}

const handleCancel = () => {
  if (modal.value.onCancel) {
    modal.value.onCancel()
  }
  closeModal()
}
</script>
