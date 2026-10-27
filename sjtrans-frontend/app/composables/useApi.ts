export const useApi = () => {
  const { token } = useAuth()
  const API_BASE = 'http://127.0.0.1:8000/api/v1'

  const apiFetch = async <T = any>(endpoint: string, options: any = {}): Promise<T> => {
    const headers: Record<string, string> = {
      'Accept': 'application/json',
      ...(options.headers || {}),
    }

    if (token.value) {
      headers['Authorization'] = `Bearer ${token.value}`
    }

    return await $fetch<T>(`${API_BASE}${endpoint}`, {
      ...options,
      headers,
    })
  }

  // Fallback / Initial sample data
  const fallbackFleets = [
    {
      id: 1,
      name: 'Big Bus SHD Scania K410IB Jetbus 5',
      slug: 'big-bus-shd-scania-k410ib-jetbus-5',
      capacity: 50,
      seat_layout: '2-2',
      price_per_day: 3800000,
      facilities: ['AC Ducting', 'Audio Karaoke & Mic', 'Smart TV LED 43"', 'Reclining Seat', 'Toilet Bersih', 'Dispenser', 'Wifi Fast', 'USB Charger'],
      specifications: { Chassis: 'Scania K410IB Opticruise', Suspension: 'Air Suspension', Body: 'Jetbus 5 by Adiputro' },
      thumbnail_url: 'https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?auto=format&fit=crop&w=800&q=80',
      description: 'Armada flagship SJ Trans dengan sasis Scania K410IB suspensi udara yang empuk. Dilengkapi toilet, dispenser, dan karaoke untuk kenyamanan jarak jauh.',
      status: 'available',
    },
    {
      id: 2,
      name: 'Big Bus Mercedes-Benz OH 1626 Pariwisata',
      slug: 'big-bus-mercedes-benz-oh-1626',
      capacity: 59,
      seat_layout: '2-3',
      price_per_day: 3200000,
      facilities: ['Full AC Dingin', 'Audio & 2 TV LED', 'Reclining Seat', 'USB Charger Port', 'Bagasi Ekstra Luas', 'P3K & APAR'],
      specifications: { Chassis: 'Mercedes-Benz OH 1626 NG', Suspension: 'Air Suspension', Body: 'Avante H8 Tentrem' },
      thumbnail_url: 'https://images.unsplash.com/photo-1570125909232-eb263c188f7e?auto=format&fit=crop&w=800&q=80',
      description: 'Pilihan hemat berkapasitas besar hingga 59 seat. Paling diminati untuk study tour sekolah dan gathering perusahaan skala besar.',
      status: 'available',
    },
    {
      id: 3,
      name: 'Medium Bus Jetbus 3+ MD Executive',
      slug: 'medium-bus-jetbus-3-md-executive',
      capacity: 31,
      seat_layout: '2-2',
      price_per_day: 2200000,
      facilities: ['AC Sejuk Merata', 'Audio Karaoke', 'Smart TV Android', 'Reclining Seat', 'USB Charger', 'Bagasi Samping'],
      specifications: { Chassis: 'Mitsubishi Canter / Hino', Body: 'Jetbus 3+ MD Adiputro' },
      thumbnail_url: 'https://images.unsplash.com/photo-1557223562-6c77ef16210f?auto=format&fit=crop&w=800&q=80',
      description: 'Bus medium lincah bermanuver di rute perbukitan Puncak, Lembang, dan Guci. Muat 31 penumpang dengan kenyamanan eksekutif.',
      status: 'available',
    },
    {
      id: 4,
      name: 'Toyota HiAce Premio Luxury VIP',
      slug: 'toyota-hiace-premio-luxury-vip',
      capacity: 14,
      seat_layout: '2-1 Captain Seats',
      price_per_day: 1600000,
      facilities: ['Captain Seats Kulit Mewah', 'Smart Android TV', 'Audio Bass Mantap', 'AC Individual Tiap Kursi', 'Gorden Privasi VIP'],
      specifications: { Engine: '2.8L 1GD-FTV Turbo Diesel', Interior: 'Custom VIP Luxury by BAV' },
      thumbnail_url: 'https://images.unsplash.com/photo-1519003722824-194d4455a60c?auto=format&fit=crop&w=800&q=80',
      description: 'Solusi transportasi VIP untuk rombongan direksi, penjemputan bandara, atau liburan keluarga berkelas business class.',
      status: 'available',
    },
  ]

  const fallbackDiscounts = [
    {
      id: 1,
      code: 'SJTPROMO10',
      title: 'Diskon 10% Sewa Bus Wisata',
      discount_type: 'percentage',
      discount_value: 10,
      max_discount: 500000,
      min_transaction: 2000000,
    },
    {
      id: 2,
      code: 'LIBURANSERU',
      title: 'Potongan Rp 350.000 Liburan Sekolah',
      discount_type: 'fixed',
      discount_value: 350000,
      min_transaction: 3000000,
    },
  ]

  return {
    API_BASE,
    apiFetch,
    fallbackFleets,
    fallbackDiscounts,
  }
}
