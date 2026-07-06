<script setup>
import { useI18n } from 'vue-i18n'
import { useForm } from '@inertiajs/vue3'
import { computed, ref } from 'vue'
import PasswordInput from '@/Components/PasswordInput.vue'
import { useProfileFieldClass } from '@/composables/useProfileFieldClass'

const { t } = useI18n()
const { fieldCls } = useProfileFieldClass()

const passwordInput = ref(null)
const currentPasswordInput = ref(null)

const form = useForm({
    current_password: '',
    password: '',
    password_confirmation: '',
})

const strength = computed(() => {
    const len = form.password.length
    if (len === 0) return null
    if (len < 6) return { pct: 30, color: '#ef4444', label: t('profile.password.strength.weak') }
    if (len < 10) return { pct: 65, color: '#f4b93d', label: t('profile.password.strength.medium') }
    return { pct: 100, color: '#22c55e', label: t('profile.password.strength.strong') }
})

const mismatch = computed(() =>
    form.password_confirmation.length > 0 && form.password !== form.password_confirmation
)

function updatePassword() {
    if (mismatch.value) {
        return
    }

    form.put(route('password.update'), {
        preserveScroll: true,
        onSuccess: () => form.reset(),
        onError: () => {
            if (form.errors.password) {
                form.reset('password', 'password_confirmation')
                passwordInput.value.focus()
            }
            if (form.errors.current_password) {
                form.reset('current_password')
                currentPasswordInput.value.focus()
            }
        },
    })
}
</script>

<template>
    <section class="rounded-2xl border border-edge bg-card pb-6 pt-[26px] px-[28px] shadow-[0_1px_2px_rgba(15,19,32,0.04)] dark:border-edge-dark dark:bg-card-dark dark:shadow-[0_1px_0_rgba(255,255,255,0.02)]">
        <div>
            <h2 class="text-[15.5px] font-bold text-ink dark:text-ink-dark">{{ t('profile.password.title') }}</h2>
            <p class="mt-1 text-[12.5px] leading-[1.5] text-subtext dark:text-subtext-dark">{{ t('profile.password.description') }}</p>
        </div>

        <form @submit.prevent="updatePassword">
            <div class="mt-[18px]">
                <label for="current_password" class="text-[12.5px] font-semibold text-subtext dark:text-subtext-dark">
                    {{ t('profile.password.current') }}
                </label>
                <PasswordInput
                    id="current_password"
                    ref="currentPasswordInput"
                    v-model="form.current_password"
                    autocomplete="current-password"
                    :class="fieldCls(!!form.errors.current_password)"
                />
                <p v-if="form.errors.current_password" class="mt-1.5 text-xs text-red-500">{{ form.errors.current_password }}</p>
            </div>

            <div class="mt-[18px]">
                <label for="password" class="text-[12.5px] font-semibold text-subtext dark:text-subtext-dark">
                    {{ t('profile.password.new') }}
                </label>
                <PasswordInput
                    id="password"
                    ref="passwordInput"
                    v-model="form.password"
                    autocomplete="new-password"
                    :class="fieldCls(!!form.errors.password)"
                />
                <div v-if="strength" class="mt-2 flex items-center gap-2.5">
                    <div class="h-1 flex-1 overflow-hidden rounded-full bg-fieldedge dark:bg-fieldedge-dark">
                        <div
                            class="h-full rounded-full transition-all duration-200"
                            :style="{ width: strength.pct + '%', backgroundColor: strength.color }"
                        />
                    </div>
                    <span class="min-w-[52px] text-[11px] font-semibold" :style="{ color: strength.color }">{{ strength.label }}</span>
                </div>
                <p v-if="form.errors.password" class="mt-1.5 text-xs text-red-500">{{ form.errors.password }}</p>
            </div>

            <div class="mt-[18px]">
                <label for="password_confirmation" class="text-[12.5px] font-semibold text-subtext dark:text-subtext-dark">
                    {{ t('profile.password.confirm') }}
                </label>
                <PasswordInput
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    autocomplete="new-password"
                    :class="fieldCls(!!form.errors.password_confirmation || mismatch)"
                />
                <p v-if="mismatch" class="mt-1.5 text-[11.5px] text-red-500">{{ t('profile.password.mismatch') }}</p>
                <p v-else-if="form.errors.password_confirmation" class="mt-1.5 text-xs text-red-500">{{ form.errors.password_confirmation }}</p>
            </div>

            <div class="mt-6 flex items-center justify-end gap-3">
                <Transition
                    enter-active-class="transition ease-in-out duration-200"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out duration-200"
                    leave-to-class="opacity-0"
                >
                    <span v-if="form.recentlySuccessful" class="flex items-center gap-1 text-[12px] font-semibold text-green-500">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none"><path d="M20 6L9 17l-5-5" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" /></svg>
                        {{ t('profile.password.saved') }}
                    </span>
                </Transition>
                <button
                    type="submit"
                    :disabled="form.processing || mismatch"
                    class="rounded-[9px] bg-accent px-5 py-2.5 text-[13px] font-bold text-white shadow-[0_4px_12px_rgba(124,108,246,0.3)] transition-opacity disabled:cursor-not-allowed disabled:opacity-60"
                >
                    {{ t('profile.password.save') }}
                </button>
            </div>
        </form>
    </section>
</template>
