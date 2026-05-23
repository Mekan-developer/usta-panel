<script setup>
import { ref } from 'vue'
import { useI18n } from 'vue-i18n'
import { Link, useForm, usePage, router } from '@inertiajs/vue3'
import { useInputClass } from '@/composables/useInputClass'

const { t } = useI18n()
const { inputCls } = useInputClass()

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
    <section>
        <div class="mb-5">
            <h2 class="text-base font-semibold text-gray-900 dark:text-white">{{ t('profile.info.title') }}</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-slate-400">{{ t('profile.info.description') }}</p>
        </div>

        <!-- Avatar upload -->
        <div class="mb-6 flex items-center gap-5">
            <div class="relative">
                <div
                    v-if="avatarPreview"
                    class="h-20 w-20 overflow-hidden rounded-full ring-2 ring-indigo-500 ring-offset-2 dark:ring-offset-slate-800"
                >
                    <img :src="avatarPreview" alt="Avatar" class="h-full w-full object-cover" />
                </div>
                <div
                    v-else
                    class="flex h-20 w-20 items-center justify-center rounded-full bg-indigo-600 text-2xl font-bold text-white ring-2 ring-indigo-500 ring-offset-2 dark:ring-offset-slate-800"
                >
                    {{ user.name?.charAt(0)?.toUpperCase() }}
                </div>
                <!-- Upload spinner overlay -->
                <div
                    v-if="avatarUploading"
                    class="absolute inset-0 flex items-center justify-center rounded-full bg-black/40"
                >
                    <svg class="h-6 w-6 animate-spin text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                    </svg>
                </div>
            </div>

            <div class="flex flex-col gap-2">
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
                    class="rounded-lg border border-gray-300 px-3 py-1.5 text-sm font-medium text-gray-700 transition-colors hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-60 dark:border-slate-600 dark:text-slate-300 dark:hover:bg-slate-700"
                >
                    {{ avatarUploading ? t('profile.info.uploading') : t('profile.info.choose_photo') }}
                </button>
                <p v-if="avatarError" class="text-xs text-red-500">{{ avatarError }}</p>
                <p class="text-xs text-gray-400 dark:text-slate-500">{{ t('profile.info.photo_hint') }}</p>
            </div>
        </div>

        <form @submit.prevent="form.patch(route('profile.update'))" class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-slate-300">
                    {{ t('profile.info.name') }}
                </label>
                <input
                    id="name"
                    v-model="form.name"
                    type="text"
                    required
                    autofocus
                    autocomplete="name"
                    :class="inputCls(!!form.errors.name)"
                />
                <p v-if="form.errors.name" class="mt-1.5 text-xs text-red-500">{{ form.errors.name }}</p>
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-slate-300">
                    {{ t('profile.info.email') }}
                </label>
                <input
                    id="email"
                    v-model="form.email"
                    type="email"
                    required
                    autocomplete="username"
                    :class="inputCls(!!form.errors.email)"
                />
                <p v-if="form.errors.email" class="mt-1.5 text-xs text-red-500">{{ form.errors.email }}</p>
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <p class="mt-1 text-sm text-amber-600 dark:text-amber-400">
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
                <p v-show="status === 'verification-link-sent'" class="mt-1 text-sm font-medium text-green-600 dark:text-green-400">
                    {{ t('profile.info.verification_sent') }}
                </p>
            </div>

            <div class="flex items-center gap-4 pt-1">
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-sm transition-colors hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-60 dark:focus:ring-offset-slate-800"
                >
                    {{ t('profile.info.save') }}
                </button>
                <Transition
                    enter-active-class="transition ease-in-out duration-200"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out duration-200"
                    leave-to-class="opacity-0"
                >
                    <span v-if="form.recentlySuccessful" class="text-sm text-green-600 dark:text-green-400">
                        {{ t('profile.info.saved') }}
                    </span>
                </Transition>
            </div>
        </form>
    </section>
</template>
