export interface UserRole {
  id: number
  name: 'super_admin' | 'admin' | 'customer'
  display_name: string
}

export interface UserProfile {
  id: number
  name: string
  username: string
  email: string
  phone_number: string
  avatar?: string
  role: UserRole
}

export const useAuth = () => {
  const token = useState<string | null>('auth_token', () => null)
  const user = useState<UserProfile | null>('auth_user', () => null)
  const isLoading = useState<boolean>('auth_loading', () => false)

  const isSuperAdmin = computed(() => user.value?.role?.name === 'super_admin')
  const isAdmin = computed(() => user.value?.role?.name === 'admin' || user.value?.role?.name === 'super_admin')
  const isCustomer = computed(() => user.value?.role?.name === 'customer')
  const isLoggedIn = computed(() => !!token.value && !!user.value)

  const initAuth = () => {
    if (typeof localStorage !== 'undefined') {
      const savedToken = localStorage.getItem('sjtrans_auth_token')
      const savedUser = localStorage.getItem('sjtrans_auth_user')
      if (savedToken && savedUser) {
        try {
          token.value = savedToken
          user.value = JSON.parse(savedUser)
        } catch (e) {
          logout()
        }
      }
    }
  }

  const setAuthData = (newToken: string, newUser: UserProfile) => {
    token.value = newToken
    user.value = newUser
    if (typeof localStorage !== 'undefined') {
      localStorage.setItem('sjtrans_auth_token', newToken)
      localStorage.setItem('sjtrans_auth_user', JSON.stringify(newUser))
    }
  }

  const login = async (loginInput: string, password: string):Promise<{ success: boolean; message: string }> => {
    isLoading.value = true
    try {
      const res: any = await $fetch('http://127.0.0.1:8000/api/v1/auth/login', {
        method: 'POST',
        body: { login: loginInput, password },
      })

      if (res && res.token && res.user) {
        setAuthData(res.token, res.user)
        return { success: true, message: res.message || 'Login berhasil!' }
      }
      return { success: false, message: 'Respon server tidak valid' }
    } catch (err: any) {
      const msg = err.data?.message || err.data?.errors?.login?.[0] || 'Login gagal. Periksa username/email & password.'
      return { success: false, message: msg }
    } finally {
      isLoading.value = false
    }
  }

  const register = async (formData: {
    name: string
    username: string
    email: string
    phone_number: string
    password: string
    password_confirmation: string
  }): Promise<{ success: boolean; message: string }> => {
    isLoading.value = true
    try {
      const res: any = await $fetch('http://127.0.0.1:8000/api/v1/auth/register', {
        method: 'POST',
        body: formData,
      })

      if (res && res.token && res.user) {
        setAuthData(res.token, res.user)
        return { success: true, message: res.message || 'Pendaftaran berhasil!' }
      }
      return { success: false, message: 'Respon server tidak valid' }
    } catch (err: any) {
      const errorMsg = err.data?.message || Object.values(err.data?.errors || {})?.[0]?.[0] || 'Pendaftaran gagal.'
      return { success: false, message: errorMsg }
    } finally {
      isLoading.value = false
    }
  }

  const logout = async () => {
    if (token.value) {
      try {
        await $fetch('http://127.0.0.1:8000/api/v1/auth/logout', {
          method: 'POST',
          headers: { Authorization: `Bearer ${token.value}` },
        })
      } catch (e) {
        // Abaikan error saat logout
      }
    }

    token.value = null
    user.value = null
    if (typeof localStorage !== 'undefined') {
      localStorage.removeItem('sjtrans_auth_token')
      localStorage.removeItem('sjtrans_auth_user')
    }
  }

  return {
    token,
    user,
    isLoading,
    isSuperAdmin,
    isAdmin,
    isCustomer,
    isLoggedIn,
    initAuth,
    login,
    register,
    logout,
    setAuthData,
  }
}
