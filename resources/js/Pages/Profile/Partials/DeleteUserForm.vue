<script setup>
import { useI18n } from 'vue-i18n'
import { useForm } from '@inertiajs/vue3'
import { nextTick, ref } from 'vue'
import PasswordInput from '@/Components/PasswordInput.vue'

const { t } = useI18n()

const confirmingDeletion = ref(false)
const deleteConfirmed = ref(false)
const passwordInput = ref(null)

const form = useForm({
    password: '',
})

function confirmDeletion() {
    confirmingDeletion.value = true
    deleteConfirmed.value = false
    nextTick(() => passwordInput.value?.focus())
}

function deleteUser() {
    if (!deleteConfirmed.value) {
        return
    }

    form.delete(route('profile.destroy'), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value?.focus(),
        onFinish: () => form.reset(),
    })
}

function closeModal() {
    confirmingDeletion.value = false
    deleteConfirmed.value = false
    form.clearErrors()
    form.reset()
}
</script>

<template>
    <section class="col-span-full rounded-2xl border border-red-500/25 bg-red-500/[0.04] pb-6 pt-[26px] px-[28px] dark:bg-red-500/[0.06]">
        <h2 class="text-[15.5px] font-bold text-red-500">{{ t('profile.delete.title') }}</h2>
        <p class="mt-1 max-w-[440px] text-[12.5px] leading-[1.5] text-subtext dark:text-subtext-dark">{{ t('profile.delete.description') }}</p>

        <div class="mt-6 flex justify-end">
            <button
                @click="confirmDeletion"
                class="rounded-[9px] bg-red-500 px-5 py-2.5 text-[13px] font-bold text-white shadow-[0_4px_12px_rgba(239,68,68,0.3)] transition-opacity hover:opacity-90"
            >
                {{ t('profile.delete.button') }}
            </button>
        </div>

        <!-- Confirmation modal -->
        <Transition name="modal">
            <div v-if="confirmingDeletion" class="fixed inset-0 z-50 flex items-center justify-center p-4 backdrop-blur-[3px]">
                <!-- Backdrop -->
                <div class="absolute inset-0 bg-[#0a0c14]/55" @click="closeModal" />

                <!-- Dialog -->
                <div class="relative w-full max-w-[380px] rounded-2xl border border-edge bg-card p-[26px] shadow-[0_20px_60px_rgba(0,0,0,0.4)] dark:border-edge-dark dark:bg-card-dark">
                    <div class="flex h-[42px] w-[42px] items-center justify-center rounded-[11px] bg-red-500/10 text-red-500">
                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none"><path d="M12 9v4M12 17h.01M10.3 3.9L1.8 18a2 2 0 001.7 3h17a2 2 0 001.7-3L13.7 3.9a2 2 0 00-3.4 0z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" /></svg>
                    </div>

                    <h3 class="mt-3.5 text-[15.5px] font-bold text-ink dark:text-ink-dark">
                        {{ t('profile.delete.confirm_title') }}
                    </h3>
                    <p class="mt-2 text-[12.5px] leading-[1.6] text-subtext dark:text-subtext-dark">
                        {{ t('profile.delete.confirm_description') }}
                    </p>

                    <div class="mt-4">
                        <PasswordInput
                            ref="passwordInput"
                            v-model="form.password"
                            :placeholder="t('profile.delete.password_placeholder')"
                            class="block w-full rounded-[9px] border border-fieldedge bg-field px-3.5 py-2.5 text-[13.5px] text-ink outline-none transition-colors focus:border-red-400 dark:border-fieldedge-dark dark:bg-field-dark dark:text-ink-dark"
                            @keyup.enter="deleteUser"
                        />
                        <p v-if="form.errors.password" class="mt-1.5 text-xs text-red-500">{{ form.errors.password }}</p>
                    </div>

                    <label class="mt-4 flex cursor-pointer items-center gap-2.5 text-[12px] text-ink dark:text-ink-dark">
                        <input
                            v-model="deleteConfirmed"
                            type="checkbox"
                            class="h-4 w-4 accent-red-500"
                        />
                        {{ t('profile.delete.confirm_checkbox') }}
                    </label>

                    <div class="mt-5 flex gap-2.5">
                        <button
                            @click="closeModal"
                            class="flex-1 rounded-[9px] border border-fieldedge py-2.5 text-[13px] font-semibold text-ink transition-colors hover:bg-field dark:border-fieldedge-dark dark:text-ink-dark dark:hover:bg-field-dark"
                        >
                            {{ t('profile.delete.cancel') }}
                        </button>
                        <button
                            @click="deleteUser"
                            :disabled="form.processing || !deleteConfirmed"
                            :class="[
                                'flex-1 rounded-[9px] py-2.5 text-[13px] font-bold text-white transition-colors',
                                deleteConfirmed ? 'bg-red-500' : 'cursor-not-allowed bg-red-500/40',
                            ]"
                        >
                            {{ t('profile.delete.confirm_button') }}
                        </button>
                    </div>
                </div>
            </div>
        </Transition>
    </section>
</template>

<style scoped>
.modal-enter-active,
.modal-leave-active {
    transition: opacity 0.2s ease;
}
.modal-enter-from,
.modal-leave-to {
    opacity: 0;
}
</style>
