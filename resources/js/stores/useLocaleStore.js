import { defineStore } from 'pinia'
import { ref, watch } from 'vue'

export const useLocaleStore = defineStore('locale', () => {
    const locale = ref(localStorage.getItem('locale') || 'ru')

    function setLocale(lang) {
        locale.value = lang
        document.cookie = `locale=${lang};path=/;max-age=31536000;SameSite=Lax`
    }

    watch(locale, (value) => {
        localStorage.setItem('locale', value)
    })

    // Sync cookie on initial load
    setLocale(locale.value)

    return { locale, setLocale }
})
