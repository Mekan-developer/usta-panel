<script setup>
import { useI18n } from 'vue-i18n'
import { useLocalized } from '@/composables/useLocalized'
import PfIcon from './PfIcon.vue'

defineProps({
    experiences: { type: Array, required: true },
})

const { t } = useI18n()
const { pick } = useLocalized()
</script>

<template>
    <section id="timeline" class="pf-section">
        <div class="pf-container">
            <div class="pf-sec-label">{{ t('portfolio_public.timeline.label') }}</div>
            <h2 class="pf-sec-title">{{ t('portfolio_public.timeline.title') }}</h2>

            <div class="pf-tl-wrap">
                <div
                    v-for="(exp, i) in experiences"
                    :key="exp.id"
                    class="pf-tl-row">
                    <div class="pf-tl-side-l">
                        <div v-if="i % 2 === 0" style="display: flex; justify-content: flex-end;">
                            <div class="pf-tl-card">
                                <div class="pf-tl-co">{{ exp.company }}</div>
                                <div class="pf-tl-role">{{ pick(exp.role) }}</div>
                                <div class="pf-tl-dur">
                                    <PfIcon name="clock" :size="12" />
                                    {{ exp.duration }}
                                </div>
                                <div class="pf-tl-desc">{{ pick(exp.description) }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="pf-tl-mid">
                        <div class="pf-tl-dot"></div>
                    </div>

                    <div class="pf-tl-side-r">
                        <div v-if="i % 2 !== 0" class="pf-tl-card">
                            <div class="pf-tl-co">{{ exp.company }}</div>
                            <div class="pf-tl-role">{{ pick(exp.role) }}</div>
                            <div class="pf-tl-dur">
                                <PfIcon name="clock" :size="12" />
                                {{ exp.duration }}
                            </div>
                            <div class="pf-tl-desc">{{ pick(exp.description) }}</div>
                        </div>
                        <div v-if="i % 2 === 0" class="pf-show-mob">
                            <div class="pf-tl-card">
                                <div class="pf-tl-co">{{ exp.company }}</div>
                                <div class="pf-tl-role">{{ pick(exp.role) }}</div>
                                <div class="pf-tl-dur">
                                    <PfIcon name="clock" :size="12" />
                                    {{ exp.duration }}
                                </div>
                                <div class="pf-tl-desc">{{ pick(exp.description) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
