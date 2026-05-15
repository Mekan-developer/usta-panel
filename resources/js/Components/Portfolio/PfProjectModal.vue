<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { useI18n } from 'vue-i18n'
import { useLocalized } from '@/composables/useLocalized'
import PfIcon from './PfIcon.vue'

const props = defineProps({
    project: { type: Object, required: true },
})

const emit = defineEmits(['close'])

const { t } = useI18n()
const { pick } = useLocalized()

const idx = ref(0)
const tsX = ref(null)

const images = computed(() => props.project.images ?? [])
const title  = computed(() => pick(props.project.title_translations, props.project.title))
const desc   = computed(() => pick(props.project.description_translations, props.project.description))
const url    = computed(() =>
    props.project.web_url
    || props.project.play_store_url
    || props.project.app_store_url
    || props.project.desktop_url
    || null
)

function prev() {
    if (!images.value.length) return
    idx.value = (idx.value - 1 + images.value.length) % images.value.length
}

function next() {
    if (!images.value.length) return
    idx.value = (idx.value + 1) % images.value.length
}

function onTouchStart(e) { tsX.value = e.touches[0].clientX }
function onTouchEnd(e) {
    if (tsX.value === null || !images.value.length) return
    const d = tsX.value - e.changedTouches[0].clientX
    if (Math.abs(d) > 40) d > 0 ? next() : prev()
    tsX.value = null
}

function onKey(e) {
    if (e.key === 'Escape') emit('close')
}

onMounted(() => {
    document.addEventListener('keydown', onKey)
    document.body.style.overflow = 'hidden'
})
onUnmounted(() => {
    document.removeEventListener('keydown', onKey)
    document.body.style.overflow = ''
})
</script>

<template>
    <div class="pf-pdm-bg" @click.self="emit('close')">
        <div class="pf-pdm">
            <!-- sticky close -->
            <div class="pf-pdm-close-row">
                <button type="button" class="pf-pdm-close-btn" @click="emit('close')">
                    <PfIcon name="close" :size="14" />
                </button>
            </div>

            <!-- image / placeholder area -->
            <div class="pf-pdm-img-wrap">
                <template v-if="images.length">
                    <div class="pf-pdm-main" @touchstart="onTouchStart" @touchend="onTouchEnd">
                        <div class="pf-pdm-track" :style="{ transform: `translateX(-${idx * 100}%)` }">
                            <div v-for="(img, i) in images" :key="i" class="pf-pdm-slide">
                                <img :src="img.url" :alt="`Screenshot ${i + 1}`" style="width:100%;height:100%;object-fit:cover;" />
                            </div>
                        </div>
                        <template v-if="images.length > 1">
                            <button type="button" class="pf-pdm-arr pf-pdm-arr-l" @click.stop="prev">&#8249;</button>
                            <button type="button" class="pf-pdm-arr pf-pdm-arr-r" @click.stop="next">&#8250;</button>
                        </template>
                    </div>
                    <div v-if="images.length > 1" class="pf-pdm-thumbs">
                        <button
                            v-for="(img, i) in images"
                            :key="i"
                            type="button"
                            :class="['pf-pdm-tn', { 'is-active': i === idx }]"
                            @click="idx = i">
                            <img :src="img.url" :alt="`thumb ${i + 1}`" style="width:100%;height:100%;object-fit:cover;" />
                        </button>
                    </div>
                </template>
                <div v-else class="pf-pdm-placeholder">
                    <span class="pf-thumb-lbl" style="font-size: 0.78rem;">
                        {{ project.thumb_label || `[ ${title} ]` }}
                    </span>
                </div>
            </div>

            <!-- content -->
            <div class="pf-pdm-body">
                <span class="pf-proj-badge" :class="project.is_private ? 'pf-badge-priv' : 'pf-badge-pub'">
                    {{ project.is_private ? t('portfolio_public.projects.private') : t('portfolio_public.projects.public') }}
                </span>
                <h3 class="pf-pdm-title">{{ title }}</h3>
                <p class="pf-pdm-desc">{{ desc }}</p>
                <div v-if="project.tech_stack?.length" class="pf-tech-list" style="margin-bottom: 22px;">
                    <span v-for="tech in project.tech_stack" :key="tech" class="pf-tech-pill">{{ tech }}</span>
                </div>
                <a
                    v-if="url"
                    :href="url"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="pf-pdm-open"
                    @click.stop>
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/>
                        <polyline points="15 3 21 3 21 9"/>
                        <line x1="10" y1="14" x2="21" y2="3"/>
                    </svg>
                    {{ t('portfolio_public.projects.open_app') }}
                </a>
            </div>
        </div>
    </div>
</template>
