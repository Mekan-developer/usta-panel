<script setup>
import { useI18n } from 'vue-i18n'
import { useForm, Head, usePage } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const { t } = useI18n()
const page = usePage()

const props = defineProps({
    accent: { type: String, default: 'indigo' },
    theme: { type: String, default: 'dark' },
    hasPassword: { type: Boolean, default: false },
})

const form = useForm({
    password: '',
    password_confirmation: '',
    accent: props.accent,
    theme: props.theme,
})

const accents = [
    { value: 'indigo', color: 'bg-indigo-500', ring: 'ring-indigo-500' },
    { value: 'violet', color: 'bg-violet-500', ring: 'ring-violet-500' },
    { value: 'teal', color: 'bg-teal-500', ring: 'ring-teal-500' },
    { value: 'amber', color: 'bg-amber-500', ring: 'ring-amber-500' },
]

function submit() {
    form.put(route('dashboard.portfolio.settings.update'))
}
</script>

<template>
    <Head :title="t('portfolio.settings.title')" />

    <AdminLayout :title="t('portfolio.settings.title')">
        <form class="mx-auto max-w-2xl space-y-6" @submit.prevent="submit">
            <input type="email" autocomplete="username" :value="page.props.auth.user?.email" class="sr-only" tabindex="-1" aria-hidden="true" readonly />

            <!-- Password -->
            <div class="rounded-xl bg-white p-6 shadow-sm dark:bg-slate-800">
                <h2 class="mb-4 text-base font-semibold text-gray-900 dark:text-white">
                    {{ t('portfolio.settings.section_password') }}
                </h2>

                <div class="mb-3 flex items-center gap-2 text-sm">
                    <span
                        :class="[
                            'inline-flex h-2 w-2 rounded-full',
                            hasPassword ? 'bg-emerald-500' : 'bg-gray-300 dark:bg-slate-600',
                        ]"
                    />
                    <span class="text-gray-600 dark:text-slate-400">
                        {{ hasPassword ? t('portfolio.settings.password_set') : t('portfolio.settings.password_not_set') }}
                    </span>
                </div>

                <div class="space-y-3">
                    <div>
                        <input
                            v-model="form.password"
                            type="password"
                            autocomplete="new-password"
                            :placeholder="t('portfolio.settings.password_placeholder')"
                            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white dark:placeholder-slate-500"
                        />
                        <p v-if="form.errors.password" class="mt-1 text-xs text-red-500">{{ form.errors.password }}</p>
                    </div>
                    <div>
                        <input
                            v-model="form.password_confirmation"
                            type="password"
                            autocomplete="new-password"
                            :placeholder="t('portfolio.settings.password_confirm_placeholder')"
                            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white dark:placeholder-slate-500"
                        />
                    </div>
                    <p class="text-xs text-gray-400 dark:text-slate-500">{{ t('portfolio.settings.password_hint') }}</p>
                </div>
            </div>

            <!-- Appearance -->
            <div class="rounded-xl bg-white p-6 shadow-sm dark:bg-slate-800">
                <h2 class="mb-5 text-base font-semibold text-gray-900 dark:text-white">
                    {{ t('portfolio.settings.section_appearance') }}
                </h2>

                <!-- Theme -->
                <div class="mb-5">
                    <p class="mb-2 text-sm font-medium text-gray-700 dark:text-slate-300">
                        {{ t('portfolio.settings.theme_label') }}
                    </p>
                    <div class="flex gap-3">
                        <label
                            v-for="themeOpt in ['dark', 'light']"
                            :key="themeOpt"
                            class="flex cursor-pointer items-center gap-2"
                        >
                            <input
                                v-model="form.theme"
                                type="radio"
                                :value="themeOpt"
                                class="h-4 w-4 border-gray-300 text-indigo-600 focus:ring-indigo-500"
                            />
                            <span class="text-sm text-gray-700 dark:text-slate-300">
                                {{ t(`portfolio.settings.theme_${themeOpt}`) }}
                            </span>
                        </label>
                    </div>
                    <p v-if="form.errors.theme" class="mt-1 text-xs text-red-500">{{ form.errors.theme }}</p>
                </div>

                <!-- Accent -->
                <div>
                    <p class="mb-2 text-sm font-medium text-gray-700 dark:text-slate-300">
                        {{ t('portfolio.settings.accent_label') }}
                    </p>
                    <div class="flex gap-3">
                        <button
                            v-for="acc in accents"
                            :key="acc.value"
                            type="button"
                            @click="form.accent = acc.value"
                            :class="[
                                'h-8 w-8 rounded-full transition-all',
                                acc.color,
                                form.accent === acc.value ? `ring-2 ring-offset-2 ${acc.ring} dark:ring-offset-slate-800` : 'opacity-60 hover:opacity-100',
                            ]"
                            :title="t(`portfolio.settings.accents.${acc.value}`)"
                        />
                    </div>
                    <p class="mt-1.5 text-xs text-gray-400 dark:text-slate-500">
                        {{ t(`portfolio.settings.accents.${form.accent}`) }}
                    </p>
                    <p v-if="form.errors.accent" class="mt-1 text-xs text-red-500">{{ form.errors.accent }}</p>
                </div>
            </div>

            <!-- Save -->
            <div class="flex justify-end">
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="rounded-lg bg-indigo-600 px-5 py-2.5 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-60"
                >
                    {{ form.processing ? '...' : t('portfolio.settings.save') }}
                </button>
            </div>
        </form>
    </AdminLayout>
</template>
