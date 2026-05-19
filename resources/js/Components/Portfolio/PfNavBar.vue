<script setup>
import { onMounted, onBeforeUnmount, ref } from 'vue'
import { useI18n } from 'vue-i18n'
import { useLocaleStore } from '@/stores/useLocaleStore'
import { useThemeStore } from '@/stores/useThemeStore'
import PfIcon from './PfIcon.vue'

const { t } = useI18n()
const localeStore = useLocaleStore()
const themeStore = useThemeStore()

const scrolled = ref(false)
const handler = () => { scrolled.value = window.scrollY > 20 }

onMounted(() => {
    window.addEventListener('scroll', handler, { passive: true })
    handler()
})
onBeforeUnmount(() => window.removeEventListener('scroll', handler))

const langs = ['en', 'ru', 'tk']
const links = [
    { id: 'skills',   key: 'portfolio_public.nav.skills' },
    { id: 'timeline', key: 'portfolio_public.nav.timeline' },
    { id: 'projects', key: 'portfolio_public.nav.projects' },
    { id: 'contact',  key: 'portfolio_public.nav.contact' },
]
</script>

<template>
    <nav class="pf-nav" :class="{ 'is-scrolled': scrolled }">
        <div class="pf-container">
            <div class="pf-nav-inner">
                <a href="#hero" class="pf-logo">mekan<em>.</em>dev</a>

                <ul class="pf-nav-links">
                    <li v-for="link in links" :key="link.id">
                        <a :href="`#${link.id}`">{{ t(link.key) }}</a>
                    </li>
                </ul>

                <div class="pf-nav-right">
                    <div class="pf-lang-toggle">
                        <button
                            v-for="lang in langs"
                            :key="lang"
                            type="button"
                            :class="{ 'is-active': localeStore.locale === lang }"
                            @click="localeStore.setLocale(lang)">
                            {{ lang.toUpperCase() }}
                        </button>
                    </div>
                    <button
                        type="button"
                        class="pf-icon-btn"
                        :aria-label="themeStore.isDark ? 'Light mode' : 'Dark mode'"
                        @click="themeStore.toggle">
                        <PfIcon :name="themeStore.isDark ? 'sun' : 'moon'" :size="15" />
                    </button>
                </div>
            </div>
        </div>
    </nav>
</template>
