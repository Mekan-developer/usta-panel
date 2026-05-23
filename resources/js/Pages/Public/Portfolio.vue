<script setup>
import { computed } from 'vue'
import { Head } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import { usePortfolioTheme } from '@/composables/usePortfolioTheme'

import PfNavBar from '@/Components/Portfolio/PfNavBar.vue'
import PfHero from '@/Components/Portfolio/PfHero.vue'
import PfSkills from '@/Components/Portfolio/PfSkills.vue'
import PfTimeline from '@/Components/Portfolio/PfTimeline.vue'
import PfProjects from '@/Components/Portfolio/PfProjects.vue'
import PfContact from '@/Components/Portfolio/PfContact.vue'

const props = defineProps({
    settings:    { type: Object,  required: true },
    avatar_url:  { type: String,  default: null },
    skills:      { type: Object,  required: true },
    experiences: { type: Array,   required: true },
    projects:    { type: Array,   required: true },
})

const { t } = useI18n()

usePortfolioTheme({
    theme: props.settings['default_theme'] || 'dark',
    accent: props.settings['accent'] || 'indigo',
})

const heroName = computed(() => props.settings['hero.name'] || 'Portfolio')
const year = new Date().getFullYear()
const copy = computed(() =>
    t('portfolio_public.footer.copy', { year, name: heroName.value })
)
</script>

<template>
    <Head :title="heroName" />

    <div class="pf-root">
        <PfNavBar />
        <PfHero :settings="settings" :avatar-url="avatar_url" />
        <PfSkills :skills="skills" />
        <PfTimeline :experiences="experiences" />
        <PfProjects :projects="projects" />
        <PfContact :settings="settings" />

        <footer class="pf-footer">
            <div class="pf-container">
                <div class="pf-foot-inner">
                    <span class="pf-foot-copy">{{ copy }}</span>
                </div>
            </div>
        </footer>
    </div>
</template>
