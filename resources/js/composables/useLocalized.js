import { useLocaleStore } from '@/stores/useLocaleStore'

/**
 * Возвращает функцию-геттер, которая берёт значение из объекта переводов
 * { tk, ru, en } по текущему локале с fallback на ru → en → first.
 */
export function useLocalized() {
    const localeStore = useLocaleStore()

    const pick = (translations, fallback = '') => {
        if (!translations) return fallback
        if (typeof translations === 'string') return translations
        const loc = localeStore.locale
        if (translations[loc]) return translations[loc]
        if (translations.ru) return translations.ru
        if (translations.en) return translations.en
        const first = Object.values(translations).find(Boolean)
        return first ?? fallback
    }

    return { pick }
}
