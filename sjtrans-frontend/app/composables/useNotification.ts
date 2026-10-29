export type NotificationType = 'success' | 'error' | 'warning' | 'info'

export interface ToastItem {
  id: string
  type: NotificationType
  title: string
  message: string
  duration?: number
}

export interface ModalNotification {
  isOpen: boolean
  type: NotificationType
  title: string
  message: string
  buttonText?: string
  secondaryButtonText?: string
  onConfirm?: () => void
  onCancel?: () => void
}

export const useNotification = () => {
  const toasts = useState<ToastItem[]>('app_toasts', () => [])
  const modal = useState<ModalNotification>('app_notification_modal', () => ({
    isOpen: false,
    type: 'success',
    title: '',
    message: '',
    buttonText: 'Mengerti',
  }))

  const showToast = (type: NotificationType, title: string, message: string, duration: number = 4000) => {
    const id = Math.random().toString(36).substring(2, 9)
    toasts.value.push({ id, type, title, message, duration })

    if (duration > 0) {
      setTimeout(() => {
        removeToast(id)
      }, duration)
    }
  }

  const removeToast = (id: string) => {
    toasts.value = toasts.value.filter(t => t.id !== id)
  }

  const showModal = (options: {
    type?: NotificationType
    title: string
    message: string
    buttonText?: string
    secondaryButtonText?: string
    onConfirm?: () => void
    onCancel?: () => void
  }) => {
    modal.value = {
      isOpen: true,
      type: options.type || 'success',
      title: options.title,
      message: options.message,
      buttonText: options.buttonText || 'Mengerti',
      secondaryButtonText: options.secondaryButtonText,
      onConfirm: options.onConfirm,
      onCancel: options.onCancel,
    }
  }

  const closeModal = () => {
    modal.value.isOpen = false
  }

  return {
    toasts,
    modal,
    showToast,
    removeToast,
    showModal,
    closeModal,
    success: (title: string, message: string) => showToast('success', title, message),
    error: (title: string, message: string) => showToast('error', title, message),
    warning: (title: string, message: string) => showToast('warning', title, message),
    info: (title: string, message: string) => showToast('info', title, message),
  }
}
