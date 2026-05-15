<script setup>
import { computed } from 'vue'
import { useI18n } from 'vue-i18n'
import { useLocalized } from '@/composables/useLocalized'
import PfIcon from './PfIcon.vue'

const props = defineProps({
    settings: { type: Object, required: true },
})

const { t } = useI18n()
const { pick } = useLocalized()

const name = computed(() => props.settings['hero.name'] || 'Your Name')
const role = computed(() => pick(props.settings['hero.role'], ''))
const bio  = computed(() => pick(props.settings['hero.bio'], ''))

const socials = computed(() => [
    { name: 'github',   url: props.settings['social.github'] },
    { name: 'linkedin', url: props.settings['social.linkedin'] },
    { name: 'twitter',  url: props.settings['social.twitter'] },
    { name: 'mail',     url: props.settings['contact.email'] ? `mailto:${props.settings['contact.email']}` : null },
].filter((s) => !!s.url))
</script>

<template>
    <section id="hero" class="pf-hero">
        <div class="pf-container">
            <div class="pf-hero-grid">
                <div>
                    <div class="pf-hero-badge pf-fade-up">
                        <span class="pf-dot"></span>
                        {{ t('portfolio_public.hero.badge') }}
                    </div>
                    <h1 class="pf-hero-name pf-fade-up pf-d1">
                        {{ t('portfolio_public.hero.hi') }}<br />
                        <span class="pf-grad">{{ name }}</span>
                    </h1>
                    <p class="pf-hero-role pf-fade-up pf-d2">{{ role }}</p>
                    <p class="pf-hero-bio pf-fade-up pf-d2">{{ bio }}</p>
                    <div class="pf-hero-btns pf-fade-up pf-d3">
                        <a href="#projects" class="pf-btn-p">{{ t('portfolio_public.hero.cta_projects') }}</a>
                        <a href="#contact"  class="pf-btn-o">{{ t('portfolio_public.hero.cta_contact') }}</a>
                    </div>
                    <div class="pf-socials pf-fade-up pf-d4">
                        <a
                            v-for="s in socials"
                            :key="s.name"
                            :href="s.url"
                            class="pf-soc"
                            target="_blank"
                            rel="noopener noreferrer">
                            <PfIcon :name="s.name" :size="17" />
                        </a>
                    </div>
                </div>
                <div class="pf-photo-wrap pf-fade-up pf-d2">
                    <div class="pf-photo-glow"></div>
                    <div class="pf-photo-ring">
                        <div class="pf-photo-core">
                            <svg width="54" height="54" viewBox="0 0 54 54" fill="none">
                                <circle cx="27" cy="19" r="11" fill="currentColor" opacity="0.22" />
                                <ellipse cx="27" cy="44" rx="19" ry="11" fill="currentColor" opacity="0.1" />
                            </svg>
                            <span style="font-family: monospace; font-size: 0.67rem;">
                                {{ t('portfolio_public.hero.photo_placeholder') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
