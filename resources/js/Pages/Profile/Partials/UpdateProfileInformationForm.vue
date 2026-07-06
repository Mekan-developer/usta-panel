<script setup>
import { ref } from 'vue'
import { useI18n } from 'vue-i18n'
import { Link, useForm, usePage, router } from '@inertiajs/vue3'
import { useProfileFieldClass } from '@/composables/useProfileFieldClass'

const { t } = useI18n()
const { fieldCls } = useProfileFieldClass()

defineProps({
    mustVerifyEmail: { type: Boolean },
    status: { type: String },
})

const user = usePage().props.auth.user

const form = useForm({
    name: user.name,
    email: user.email,
})

const avatarInput = ref(null)
const avatarPreview = ref(user.avatar_url)
const avatarUploading = ref(false)
const avatarError = ref(null)

function triggerFileInput() {
    avatarInput.value.click()
}

function onAvatarChange(event) {
    const file = event.target.files[0]
    if (!file) {
        return
    }

    avatarPreview.value = URL.createObjectURL(file)
    avatarError.value = null
    avatarUploading.value = true

    const formData = new FormData()
    formData.append('avatar', file)

    router.post(route('profile.avatar'), formData, {
        onSuccess: () => {
            avatarPreview.value = usePage().props.auth.user.avatar_url
        },
        onError: (errors) => {
            avatarError.value = errors.avatar ?? null
            avatarPreview.value = user.avatar_url
        },
        onFinish: () => {
            avatarUploading.value = false
            avatarInput.value.value = ''
        },
    })
}
</script>

<template>
    <section class="rounded-2xl border border-edge bg-card pb-6 pt-[26px] px-[28px] shadow-[0_1px_2px_rgba(15,19,32,0.04)] dark:border-edge-dark dark:bg-card-dark dark:shadow-[0_1px_0_rgba(255,255,255,0.02)]">
        <div>
            <h2 class="text-[15.5px] font-bold text-ink dark:text-ink-dark">{{ t('profile.info.title') }}</h2>
            <p class="mt-1 text-[12.5px] leading-[1.5] text-subtext dark:text-subtext-dark">{{ t('profile.info.description') }}</p>
        </div>

        <!-- Avatar upload -->
        <div class="mt-[22px] flex items-center gap-[18px]">
            <div
                class="group relative h-16 w-16 shrink-0 cursor-pointer overflow-hidden rounded-full border-2 border-accent p-0.5"
                @click="triggerFileInput"
            >
                <img
                    v-if="avatarPreview"
                    :src="avatarPreview"
                    alt="Avatar"
                    class="h-full w-full rounded-full object-cover"
                />
                <div
                    v-else
                    class="flex h-full w-full items-center justify-center rounded-full bg-accent text-xl font-bold text-white"
                >
                    {{ user.name?.charAt(0)?.toUpperCase() }}
                </div>
                <!-- Hover overlay -->
                <div class="absolute inset-0.5 flex items-center justify-center rounded-full bg-[#0f1320]/45 opacity-0 transition-opacity group-hover:opacity-100">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none"><path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z" stroke="white" stroke-width="2" stroke-linejoin="round" /><circle cx="12" cy="13" r="3.5" stroke="white" stroke-width="2" /></svg>
                </div>
                <!-- Upload spinner overlay -->
                <div
                    v-if="avatarUploading"
                    class="absolute inset-0.5 flex items-center justify-center rounded-full bg-black/40"
                >
                    <svg class="h-5 w-5 animate-spin text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                    </svg>
                </div>
            </div>

            <div>
                <input
                    ref="avatarInput"
                    type="file"
                    accept="image/*"
                    class="hidden"
                    @change="onAvatarChange"
                />
                <button
                    type="button"
                    :disabled="avatarUploading"
                    @click="triggerFileInput"
                    class="rounded-lg border border-fieldedge bg-field px-3.5 py-2 text-[12.5px] font-semibold text-ink transition-colors hover:brightness-95 disabled:cursor-not-allowed disabled:opacity-60 dark:border-fieldedge-dark dark:bg-field-dark dark:text-ink-dark"
                >
                    {{ avatarUploading ? t('profile.info.uploading') : t('profile.info.choose_photo') }}
                </button>
                <p v-if="avatarError" class="mt-1.5 text-xs text-red-500">{{ avatarError }}</p>
                <p class="mt-1.5 text-[11px] text-fadetext dark:text-fadetext-dark">{{ t('profile.info.photo_hint') }}</p>
            </div>
        </div>

        <form @submit.prevent="form.patch(route('profile.update'))">
            <div class="mt-[18px]">
                <label for="name" class="text-[12.5px] font-semibold text-subtext dark:text-subtext-dark">
                    {{ t('profile.info.name') }}
                </label>
                <input
                    id="name"
                    v-model="form.name"
                    type="text"
                    required
                    autofocus
                    autocomplete="name"
                    :class="fieldCls(!!form.errors.name)"
                />
                <p v-if="form.errors.name" class="mt-1.5 text-xs text-red-500">{{ form.errors.name }}</p>
            </div>

            <div class="mt-[18px]">
                <label for="email" class="text-[12.5px] font-semibold text-subtext dark:text-subtext-dark">
                    {{ t('profile.info.email') }}
                </label>
                <input
                    id="email"
                    v-model="form.email"
                    type="email"
                    required
                    autocomplete="username"
                    :class="fieldCls(!!form.errors.email)"
                />
                <p v-if="form.errors.email" class="mt-1.5 text-xs text-red-500">{{ form.errors.email }}</p>
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null" class="mt-[18px]">
                <p class="text-sm text-amber-600 dark:text-warning">
                    {{ t('profile.info.unverified_email') }}
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="font-medium underline hover:no-underline"
                    >
                        {{ t('profile.info.resend_verification') }}
                    </Link>
                </p>
                <p v-show="status === 'verification-link-sent'" class="mt-1 text-sm font-medium text-green-500">
                    {{ t('profile.info.verification_sent') }}
                </p>
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
                        {{ t('profile.info.saved') }}
                    </span>
                </Transition>
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="rounded-[9px] bg-accent px-5 py-2.5 text-[13px] font-bold text-white shadow-[0_4px_12px_rgba(124,108,246,0.3)] transition-opacity disabled:cursor-not-allowed disabled:opacity-60"
                >
                    {{ t('profile.info.save') }}
                </button>
            </div>
        </form>
    </section>
</template>
