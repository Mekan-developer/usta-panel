<script setup>
import { computed } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import { usePortfolioTheme } from '@/composables/usePortfolioTheme'

import PfNavBar from '@/Components/Portfolio/PfNavBar.vue'
import PfHero from '@/Components/Portfolio/PfHero.vue'
import PfSkills from '@/Components/Portfolio/PfSkills.vue'
import PfTimeline from '@/Components/Portfolio/PfTimeline.vue'
import PfProjects from '@/Components/Portfolio/PfProjects.vue'
import PfContact from '@/Components/Portfolio/PfContact.vue'
import PfIcon from '@/Components/Portfolio/PfIcon.vue'

const props = defineProps({
    settings:    { type: Object, required: true },
    skills:      { type: Object, required: true },
    experiences: { type: Array,  required: true },
    projects:    { type: Array,  required: true },
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
        <PfHero :settings="settings" />
        <PfSkills :skills="skills" />
        <PfTimeline :experiences="experiences" />
        <PfProjects :projects="projects" />
        <PfContact :settings="settings" />

        <footer class="pf-footer">
            <div class="pf-container">
                <div class="pf-foot-inner">
                    <span class="pf-foot-copy">{{ copy }}</span>
                    <Link :href="route('login')" class="pf-foot-admin">
                        <PfIcon name="gear" :size="14" />
                        <span>{{ t('portfolio_public.footer.admin') }}</span>
                    </Link>
                </div>
            </div>
        </footer>
    </div>
</template>
