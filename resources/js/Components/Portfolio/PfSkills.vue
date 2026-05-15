<script setup>
import { ref, computed, onMounted, onBeforeUnmount, watch, nextTick } from 'vue'
import { useI18n } from 'vue-i18n'

const props = defineProps({
    skills: { type: Object, required: true }, // { frontend: [...], backend: [...], tools: [...] }
})

const { t } = useI18n()

const tabs = ['frontend', 'backend', 'tools']
const active = ref(tabs.find((tab) => (props.skills[tab] ?? []).length) ?? 'frontend')
const animated = ref(false)
const root = ref(null)

let observer

onMounted(() => {
    observer = new IntersectionObserver(
        ([entry]) => {
            if (entry.isIntersecting) {
                animated.value = true
            }
        },
        { threshold: 0.15 }
    )
    if (root.value) observer.observe(root.value)
})

onBeforeUnmount(() => observer?.disconnect())

watch(active, async () => {
    animated.value = false
    await nextTick()
    setTimeout(() => { animated.value = true }, 50)
})

const items = computed(() => props.skills[active.value] ?? [])
</script>

<template>
    <section id="skills" class="pf-section pf-skills" ref="root">
        <div class="pf-container">
            <div class="pf-sec-label">{{ t('portfolio_public.skills.label') }}</div>
            <h2 class="pf-sec-title">{{ t('portfolio_public.skills.title') }}</h2>

            <div class="pf-skill-tabs">
                <button
                    v-for="tab in tabs"
                    :key="tab"
                    type="button"
                    class="pf-sk-tab"
                    :class="{ 'is-active': active === tab }"
                    @click="active = tab">
                    {{ t(`portfolio_public.skills.tabs.${tab}`) }}
                </button>
            </div>

            <div class="pf-skills-grid">
                <div
                    v-for="sk in items"
                    :key="sk.id"
                    class="pf-sk-card">
                    <div class="pf-sk-abbr">{{ sk.abbr }}</div>
                    <div class="pf-sk-name">{{ sk.name }}</div>
                    <div class="pf-bar-bg">
                        <div
                            class="pf-bar-fill"
                            :style="{ width: animated ? `${sk.percent}%` : '0%' }">
                        </div>
                    </div>
                    <div class="pf-sk-pct">{{ sk.percent }}%</div>
                </div>
            </div>
        </div>
    </section>
</template>
