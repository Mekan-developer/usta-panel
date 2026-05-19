<script setup>
import { computed, ref } from 'vue'
import { useI18n } from 'vue-i18n'
import { useForm } from '@inertiajs/vue3'
import { useLocalized } from '@/composables/useLocalized'
import PfIcon from './PfIcon.vue'

const props = defineProps({
    settings: { type: Object, required: true },
})

const { t, locale } = useI18n()
const { pick } = useLocalized()

const sent = ref(false)

const form = useForm({
    name: '',
    email: '',
    message: '',
    locale: locale.value,
})

function submit() {
    form.locale = locale.value
    form.post(route('portfolio.contact'), {
        onSuccess: () => {
            sent.value = true
            form.reset()
            setTimeout(() => { sent.value = false }, 3500)
        },
    })
}

const contacts = computed(() => [
    {
        icon: 'mail',
        value: props.settings['contact.email'] || '',
        label: t('portfolio_public.contact.email_label'),
    },
    {
        icon: 'phone',
        value: props.settings['contact.phone'] || '',
        label: t('portfolio_public.contact.phone_label'),
    },
    {
        icon: 'pin',
        value: pick(props.settings['contact.location'], ''),
        label: t('portfolio_public.contact.location_label'),
    },
].filter((c) => !!c.value))

const socials = computed(() => [
    { name: 'github',   url: props.settings['social.github'] },
    { name: 'linkedin', url: props.settings['social.linkedin'] },
    { name: 'twitter',  url: props.settings['social.twitter'] },
].filter((s) => !!s.url))
</script>

<template>
    <section id="contact" class="pf-section">
        <div class="pf-container">
            <div class="pf-sec-label">{{ t('portfolio_public.contact.label') }}</div>
            <h2 class="pf-sec-title">{{ t('portfolio_public.contact.title') }}</h2>

            <div class="pf-contact-grid">
                <div>
                    <div class="pf-ci-title">{{ t('portfolio_public.contact.title') }}</div>
                    <p class="pf-ci-desc">{{ t('portfolio_public.contact.desc') }}</p>

                    <div class="pf-ci-list">
                        <div v-for="c in contacts" :key="c.label" class="pf-ci-row">
                            <div class="pf-ci-icon">
                                <PfIcon :name="c.icon" :size="16" />
                            </div>
                            <div>
                                <div class="pf-ci-val">{{ c.value }}</div>
                                <div class="pf-ci-lbl">{{ c.label }}</div>
                            </div>
                        </div>
                    </div>

                    <div v-if="socials.length" class="pf-socials">
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

                <form @submit.prevent="submit">
                    <div class="pf-fg">
                        <label class="pf-fl">{{ t('portfolio_public.contact.name') }}</label>
                        <input v-model="form.name" class="pf-fi" required />
                        <span v-if="form.errors.name" style="color:var(--acc);font-size:0.8rem">{{ form.errors.name }}</span>
                    </div>
                    <div class="pf-fg">
                        <label class="pf-fl">{{ t('portfolio_public.contact.email') }}</label>
                        <input v-model="form.email" type="email" class="pf-fi" required />
                        <span v-if="form.errors.email" style="color:var(--acc);font-size:0.8rem">{{ form.errors.email }}</span>
                    </div>
                    <div class="pf-fg">
                        <label class="pf-fl">{{ t('portfolio_public.contact.message') }}</label>
                        <textarea v-model="form.message" class="pf-fi" required></textarea>
                        <span v-if="form.errors.message" style="color:var(--acc);font-size:0.8rem">{{ form.errors.message }}</span>
                    </div>
                    <button type="submit" :disabled="form.processing" class="pf-btn-p" style="width: 100%; font-size: 0.92rem;">
                        {{ sent ? t('portfolio_public.contact.sent') : form.processing ? '...' : t('portfolio_public.contact.send') }}
                    </button>
                </form>
            </div>
        </div>
    </section>
</template>
