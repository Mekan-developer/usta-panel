<script setup>
import { ref } from 'vue'
import { useI18n } from 'vue-i18n'
import { useForm, Link, Head } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const { t } = useI18n()

const props = defineProps({
    cvUrl: { type: String, default: null },
})

const form = useForm({ cv: null })
const fileInput = ref(null)
const fileName = ref('')

function onFileChange(event) {
    const file = event.target.files[0]
    if (!file) return
    form.cv = file
    fileName.value = file.name
}

function submit() {
    form.post(route('dashboard.cv.store'), { forceFormData: true })
}

function deleteCv() {
    form.delete(route('dashboard.cv.destroy'))
}
</script>

<template>
    <Head :title="t('portfolio.cv.title')" />

    <AdminLayout :title="t('portfolio.cv.title')">
        <div class="mx-auto max-w-xl space-y-6">

            <!-- Back -->
            <Link
                :href="route('dashboard.portfolio.index')"
                class="inline-flex items-center gap-1.5 text-sm text-gray-500 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white"
            >
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                </svg>
                {{ t('portfolio.title') }}
            </Link>

            <!-- Current CV card -->
            <div class="rounded-xl bg-white p-6 shadow-sm dark:bg-slate-800">
                <h2 class="mb-4 text-sm font-semibold text-gray-900 dark:text-white">{{ t('portfolio.cv.current') }}</h2>

                <div v-if="cvUrl" class="flex items-center gap-4">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-lg bg-indigo-100 dark:bg-indigo-900/40">
                        <svg class="h-6 w-6 text-indigo-600 dark:text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-900 dark:text-white">CV</p>
                        <a :href="cvUrl" target="_blank" class="text-xs text-indigo-600 hover:underline dark:text-indigo-400">
                            {{ t('portfolio.cv.download') }} ↗
                        </a>
                    </div>
                    <button
                        @click="deleteCv"
                        :disabled="form.processing"
                        class="rounded-lg px-3 py-1.5 text-sm font-medium text-red-600 hover:bg-red-50 disabled:opacity-50 dark:text-red-400 dark:hover:bg-red-900/20"
                    >
                        {{ t('portfolio.cv.delete') }}
                    </button>
                </div>

                <p v-else class="text-sm text-gray-400 dark:text-slate-500">{{ t('portfolio.cv.none') }}</p>
            </div>

            <!-- Upload card -->
            <div class="rounded-xl bg-white p-6 shadow-sm dark:bg-slate-800">
                <h2 class="mb-4 text-sm font-semibold text-gray-900 dark:text-white">{{ t('portfolio.cv.upload') }}</h2>

                <form @submit.prevent="submit" class="space-y-4">
                    <label class="flex cursor-pointer flex-col items-center gap-2 rounded-lg border-2 border-dashed border-gray-300 px-4 py-8 text-center text-sm text-gray-500 transition-colors hover:border-indigo-400 hover:text-indigo-500 dark:border-slate-600 dark:text-slate-400 dark:hover:border-indigo-500">
                        <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                        </svg>
                        <span class="font-medium">{{ fileName || t('portfolio.cv.upload') }}</span>
                        <span class="text-xs text-gray-400 dark:text-slate-500">{{ t('portfolio.cv.hint') }}</span>
                        <input ref="fileInput" type="file" accept=".pdf,.doc,.docx" class="hidden" @change="onFileChange" />
                    </label>
                    <p v-if="form.errors.cv" class="text-xs text-red-500">{{ form.errors.cv }}</p>

                    <div class="flex justify-end">
                        <button
                            type="submit"
                            :disabled="!form.cv || form.processing"
                            class="rounded-lg bg-indigo-600 px-5 py-2 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-50"
                        >
                            {{ form.processing ? t('portfolio.cv.uploading') : t('portfolio.cv.upload') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
