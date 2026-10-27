export interface ThemePreset {
  id: string
  name: string
  primary: string
  hover: string
  accent: string
}

export const THEME_PRESETS: ThemePreset[] = [
  {
    id: 'royal_blue',
    name: 'Royal Blue (TRAC Astra)',
    primary: '#1E40AF',
    hover: '#1D4ED8',
    accent: '#0284C7',
  },
  {
    id: 'navy_corporate',
    name: 'Navy Executive',
    primary: '#0F172A',
    hover: '#1E293B',
    accent: '#2563EB',
  },
  {
    id: 'ocean_sapphire',
    name: 'Ocean Sapphire',
    primary: '#0369A1',
    hover: '#0284C7',
    accent: '#38BDF8',
  },
  {
    id: 'midnight_indigo',
    name: 'Midnight Indigo',
    primary: '#1E1B4B',
    hover: '#312E81',
    accent: '#6366F1',
  },
  {
    id: 'emerald_express',
    name: 'Emerald Tour',
    primary: '#065F46',
    hover: '#047857',
    accent: '#10B981',
  },
]

export const useTheme = () => {
  const currentPreset = useState<string>('theme_preset', () => 'royal_blue')
  const primaryColor = useState<string>('theme_primary', () => '#1E40AF')
  const hoverColor = useState<string>('theme_hover', () => '#1D4ED8')
  const accentColor = useState<string>('theme_accent', () => '#0284C7')

  const applyColorsToDOM = (primary: string, hover: string, accent: string) => {
    if (typeof document !== 'undefined') {
      const root = document.documentElement
      root.style.setProperty('--color-primary', primary)
      root.style.setProperty('--color-primary-hover', hover)
      root.style.setProperty('--color-accent', accent)
    }
  }

  const selectPreset = (presetId: string) => {
    const preset = THEME_PRESETS.find(p => p.id === presetId)
    if (preset) {
      currentPreset.value = preset.id
      primaryColor.value = preset.primary
      hoverColor.value = preset.hover
      accentColor.value = preset.accent
      applyColorsToDOM(preset.primary, preset.hover, preset.accent)

      if (typeof localStorage !== 'undefined') {
        localStorage.setItem('sjtrans_theme_preset', preset.id)
        localStorage.setItem('sjtrans_theme_primary', preset.primary)
      }
    }
  }

  const setCustomHex = (hex: string) => {
    currentPreset.value = 'custom'
    primaryColor.value = hex
    hoverColor.value = hex
    accentColor.value = '#0284C7'
    applyColorsToDOM(hex, hex, '#0284C7')

    if (typeof localStorage !== 'undefined') {
      localStorage.setItem('sjtrans_theme_preset', 'custom')
      localStorage.setItem('sjtrans_theme_primary', hex)
    }
  }

  const initTheme = () => {
    if (typeof localStorage !== 'undefined') {
      const savedPreset = localStorage.getItem('sjtrans_theme_preset')
      const savedHex = localStorage.getItem('sjtrans_theme_primary')
      if (savedPreset && savedPreset !== 'custom') {
        selectPreset(savedPreset)
      } else if (savedHex) {
        setCustomHex(savedHex)
      } else {
        applyColorsToDOM(primaryColor.value, hoverColor.value, accentColor.value)
      }
    }
  }

  return {
    currentPreset,
    primaryColor,
    hoverColor,
    accentColor,
    presets: THEME_PRESETS,
    selectPreset,
    setCustomHex,
    initTheme,
  }
}
