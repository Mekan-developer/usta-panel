<script setup>
import { ref } from 'vue'
import { useI18n } from 'vue-i18n'
import PfIcon from './PfIcon.vue'

const props = defineProps({
    images:     { type: Array,   default: () => [] },
    thumbLabel: { type: String,  default: '' },
    isLocked:   { type: Boolean, default: false },
})

const emit = defineEmits(['lock-click'])

const { t } = useI18n()
const idx = ref(0)
const tsX = ref(null)

function prev(e) {
    e.stopPropagation()
    idx.value = (idx.value - 1 + props.images.length) % props.images.length
}

function next(e) {
    e.stopPropagation()
    idx.value = (idx.value + 1) % props.images.length
}

function onTouchStart(e) {
    tsX.value = e.touches[0].clientX
}

function onTouchEnd(e) {
    if (tsX.value === null || !props.images.length) return
    const d = tsX.value - e.changedTouches[0].clientX
    if (Math.abs(d) > 40) {
        idx.value = d > 0
            ? (idx.value + 1) % props.images.length
            : (idx.value - 1 + props.images.length) % props.images.length
    }
    tsX.value = null
}
</script>

<template>
    <!-- LOCKED -->
    <div v-if="isLocked" class="pf-proj-thumb">
        <button type="button" class="pf-lock-ov" @click="emit('lock-click')">
            <PfIcon name="lock" :size="22" />
            <span style="font-size: 0.77rem; font-weight: 600;">
                {{ t('portfolio_public.projects.locked') }}
            </span>
            <span style="font-size: 0.7rem; color: var(--pf-acc); font-weight: 600;">
                {{ t('portfolio_public.projects.unlock') }}
            </span>
        </button>
    </div>

    <!-- NO IMAGES: static placeholder, card-level @click handles opening modal -->
    <div v-else-if="!images.length" class="pf-proj-thumb">
        <span class="pf-thumb-lbl">{{ thumbLabel }}</span>
    </div>

    <!-- CAROUSEL with real images -->
    <div v-else class="pf-car" @touchstart="onTouchStart" @touchend="onTouchEnd">
        <div class="pf-car-track" :style="{ transform: `translateX(-${idx * 100}%)` }">
            <div v-for="(img, i) in images" :key="i" class="pf-car-slide">
                <img :src="img.url" :alt="`Screenshot ${i + 1}`" loading="lazy" />
            </div>
        </div>
        <template v-if="images.length > 1">
            <button type="button" class="pf-car-arr pf-car-prev" @click="prev">&#8249;</button>
            <button type="button" class="pf-car-arr pf-car-next" @click="next">&#8250;</button>
            <div class="pf-car-dots">
                <button
                    v-for="(_, i) in images"
                    :key="i"
                    type="button"
                    :class="['pf-car-dot', { 'is-active': i === idx }]"
                    @click.stop="idx = i" />
            </div>
        </template>
    </div>
</template>
