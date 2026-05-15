<script setup>
import { ref, computed } from 'vue'
import { useI18n } from 'vue-i18n'
import PfIcon from './PfIcon.vue'

const props = defineProps({
    isChecking: { type: Boolean, default: false },
    errorMessage: { type: String, default: '' },
})

const emit = defineEmits(['close', 'submit'])

const { t } = useI18n()
const password = ref('')

const errText = computed(() => props.errorMessage || (showLocalError.value ? t('portfolio_public.password.wrong') : ''))
const showLocalError = ref(false)

function submit() {
    if (!password.value || props.isChecking) return
    showLocalError.value = false
    emit('submit', password.value, () => {
        // failure callback
        showLocalError.value = true
        password.value = ''
    })
}
</script>

<template>
    <div class="pf-modal-bg" @click="emit('close')">
        <div class="pf-modal" @click.stop>
            <button type="button" class="pf-modal-x" @click="emit('close')" aria-label="Close">
                <PfIcon name="close" :size="14" />
            </button>
            <div style="font-size: 1.6rem; margin-bottom: 10px;">
                <PfIcon name="lock" :size="28" />
            </div>
            <div class="pf-modal-h">{{ t('portfolio_public.password.title') }}</div>
            <div class="pf-modal-s">{{ t('portfolio_public.password.sub') }}</div>
            <form @submit.prevent="submit">
                <input
                    v-model="password"
                    type="password"
                    class="pf-fi"
                    :class="{ 'pf-fi-error': !!errText }"
                    :placeholder="t('portfolio_public.password.placeholder')"
                    autofocus
                    :disabled="isChecking" />
                <div v-if="errText" class="pf-modal-err">{{ errText }}</div>
                <button
                    type="submit"
                    class="pf-btn-p"
                    style="width: 100%; margin-top: 14px;"
                    :disabled="isChecking || !password">
                    {{ isChecking ? t('portfolio_public.password.processing') : t('portfolio_public.password.btn') }}
                </button>
            </form>
        </div>
    </div>
</template>
