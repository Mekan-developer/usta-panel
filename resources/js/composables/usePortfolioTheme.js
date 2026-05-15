import { computed, onMounted, onBeforeUnmount, watch } from 'vue'
import { useThemeStore } from '@/stores/useThemeStore'

const PF_THEME_KEY = 'pf_theme'
const PF_ACCENT_KEY = 'pf_accent'
const VALID_ACCENTS = ['indigo', 'violet', 'teal', 'amber']

/**
 * Включает оформление публичного портфолио:
 *  – пишет data-portfolio="1" на <html> на время монтирования
 *  – синхронизирует data-pf-theme с глобальным useThemeStore (Tailwind dark)
 *  – управляет accent-цветом
 *
 * `defaults` — { theme: 'dark'|'light', accent: 'indigo'|... } из props сервера.
 */
export function usePortfolioTheme(defaults = {}) {
    const themeStore = useThemeStore()
    const html = () => document.documentElement

    const accent = computed({
        get() {
            const saved = localStorage.getItem(PF_ACCENT_KEY)
            if (saved && VALID_ACCENTS.includes(saved)) {
                return saved
            }
            return defaults.accent && VALID_ACCENTS.includes(defaults.accent)
                ? defaults.accent
                : 'indigo'
        },
        set(value) {
            if (!VALID_ACCENTS.includes(value)) return
            localStorage.setItem(PF_ACCENT_KEY, value)
            html().setAttribute('data-pf-accent', value)
        },
    })

    const pfTheme = computed(() => (themeStore.isDark ? 'dark' : 'light'))

    function apply() {
        const el = html()
        el.setAttribute('data-portfolio', '1')
        el.setAttribute('data-pf-theme', pfTheme.value)
        el.setAttribute('data-pf-accent', accent.value)
    }

    function detach() {
        const el = html()
        el.removeAttribute('data-portfolio')
        el.removeAttribute('data-pf-theme')
        el.removeAttribute('data-pf-accent')
    }

    onMounted(() => {
        // Первая инициализация темы из props, если в localStorage пусто
        if (!localStorage.getItem('theme') && defaults.theme === 'light') {
            themeStore.isDark = false
        }
        // Сохранённый pf_theme (если был) тоже учитываем
        const savedPfTheme = localStorage.getItem(PF_THEME_KEY)
        if (savedPfTheme === 'light' && themeStore.isDark) {
            themeStore.isDark = false
        }
        if (savedPfTheme === 'dark' && !themeStore.isDark) {
            themeStore.isDark = true
        }
        apply()
    })

    watch(pfTheme, (val) => {
        html().setAttribute('data-pf-theme', val)
        localStorage.setItem(PF_THEME_KEY, val)
    })

    onBeforeUnmount(detach)

    function toggleTheme() {
        themeStore.toggle()
    }

    return {
        accent,
        pfTheme,
        toggleTheme,
        accents: VALID_ACCENTS,
    }
}
