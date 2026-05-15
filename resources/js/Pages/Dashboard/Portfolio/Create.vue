<script setup>
import { ref, computed } from 'vue'
import { useI18n } from 'vue-i18n'
import { useForm, Link, Head } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { useNotificationStore } from '@/stores/useNotificationStore'

const { t } = useI18n()
const notifications = useNotificationStore()

const props = defineProps({
    types: { type: Array, default: () => [] },
})

const form = useForm({
    title: '',
    description: '',
    type: 'web',
    web_url: '',
    play_store_url: '',
    app_store_url: '',
    desktop_url: '',
    tech_stack: [],
    is_active: true,
    is_private: false,
    thumb_label: '',
    sort_order: 0,
    images: [],
})

const techInput = ref('')
const previewUrls = ref([])

function addTech() {
    const tags = techInput.value.split(',').map(s => s.trim()).filter(Boolean)
    tags.forEach(tag => {
        if (!form.tech_stack.includes(tag)) form.tech_stack.push(tag)
    })
    techInput.value = ''
}

function removeTech(tag) {
    form.tech_stack = form.tech_stack.filter(t => t !== tag)
}

function onImagesChange(event) {
    const files = Array.from(event.target.files)
    form.images = files
    previewUrls.value = files.map(f => URL.createObjectURL(f))
}

function removePreview(index) {
    previewUrls.value.splice(index, 1)
    const newFiles = [...form.images]
    newFiles.splice(index, 1)
    form.images = newFiles
}

function submit() {
    form.post(route('dashboard.portfolio.store'), {
        forceFormData: true,
        onError: (errors) => {
            Object.values(errors).forEach(msg => notifications.error(msg))
        },
    })
}

const showMobileUrls = computed(() => ['mobile_android', 'mobile_ios', 'mobile_both'].includes(form.type))
const showDesktopUrl = computed(() => form.type === 'desktop')
const showWebUrl = computed(() => form.type === 'web')
</script>

<template>
    <Head :title="t('portfolio.create.title')" />

    <AdminLayout :title="t('portfolio.create.title')">
        <div class="mx-auto max-w-2xl">
            <div class="relative rounded-xl bg-white p-6 shadow-sm dark:bg-slate-800">
                <!-- Loading overlay -->
                <div
                    v-if="form.processing"
                    class="absolute inset-0 z-10 flex flex-col items-center justify-center gap-3 rounded-xl bg-white/80 dark:bg-slate-800/80 backdrop-blur-sm"
                >
                    <svg class="h-8 w-8 animate-spin text-indigo-600" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                    </svg>
                    <p class="text-sm font-medium text-indigo-600 dark:text-indigo-400">{{ t('portfolio.actions.saving') }}</p>
                </div>

                <form @submit.prevent="submit">
                <fieldset :disabled="form.processing" class="space-y-5 disabled:opacity-60 disabled:pointer-events-none">
                    <!-- General error banner -->
                    <div v-if="Object.keys(form.errors).length" class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 dark:border-red-800 dark:bg-red-900/20">
                        <p class="mb-1 text-sm font-medium text-red-700 dark:text-red-400">{{ t('portfolio.errors.title') }}</p>
                        <ul class="list-inside list-disc space-y-0.5 text-xs text-red-600 dark:text-red-400">
                            <li v-for="(msg, field) in form.errors" :key="field">{{ msg }}</li>
                        </ul>
                    </div>

                    <!-- Title -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-slate-300">
                            {{ t('portfolio.fields.title') }}
                        </label>
                        <input
                            v-model="form.title"
                            type="text"
                            required
                            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white dark:placeholder-slate-500"
                        />
                        <p v-if="form.errors.title" class="mt-1 text-xs text-red-500">{{ form.errors.title }}</p>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-slate-300">
                            {{ t('portfolio.fields.description') }}
                        </label>
                        <textarea
                            v-model="form.description"
                            rows="3"
                            required
                            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white dark:placeholder-slate-500"
                        />
                        <p v-if="form.errors.description" class="mt-1 text-xs text-red-500">{{ form.errors.description }}</p>
                    </div>

                    <!-- Type -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-slate-300">
                            {{ t('portfolio.fields.type') }}
                        </label>
                        <select
                            v-model="form.type"
                            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white"
                        >
                            <option v-for="tp in types" :key="tp" :value="tp">
                                {{ t(`portfolio.types.${tp}`) }}
                            </option>
                        </select>
                    </div>

                    <!-- Conditional URL fields -->
                    <div v-if="showWebUrl">
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-slate-300">
                            {{ t('portfolio.fields.web_url') }}
                        </label>
                        <input
                            v-model="form.web_url"
                            type="url"
                            placeholder="https://"
                            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white dark:placeholder-slate-500"
                        />
                        <p v-if="form.errors.web_url" class="mt-1 text-xs text-red-500">{{ form.errors.web_url }}</p>
                    </div>

                    <div v-if="showMobileUrls" class="grid grid-cols-2 gap-4">
                        <div v-if="form.type !== 'mobile_ios'">
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-slate-300">
                                {{ t('portfolio.fields.play_store_url') }}
                            </label>
                            <input
                                v-model="form.play_store_url"
                                type="url"
                                placeholder="https://play.google.com/..."
                                class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white dark:placeholder-slate-500"
                            />
                            <p v-if="form.errors.play_store_url" class="mt-1 text-xs text-red-500">{{ form.errors.play_store_url }}</p>
                        </div>
                        <div v-if="form.type !== 'mobile_android'">
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-slate-300">
                                {{ t('portfolio.fields.app_store_url') }}
                            </label>
                            <input
                                v-model="form.app_store_url"
                                type="url"
                                placeholder="https://apps.apple.com/..."
                                class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white dark:placeholder-slate-500"
                            />
                            <p v-if="form.errors.app_store_url" class="mt-1 text-xs text-red-500">{{ form.errors.app_store_url }}</p>
                        </div>
                    </div>

                    <div v-if="showDesktopUrl">
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-slate-300">
                            {{ t('portfolio.fields.desktop_url') }}
                        </label>
                        <input
                            v-model="form.desktop_url"
                            type="url"
                            placeholder="https://"
                            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white dark:placeholder-slate-500"
                        />
                        <p v-if="form.errors.desktop_url" class="mt-1 text-xs text-red-500">{{ form.errors.desktop_url }}</p>
                    </div>

                    <!-- Images upload -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-slate-300">
                            {{ t('portfolio.fields.images') }}
                        </label>

                        <!-- Preview grid -->
                        <div v-if="previewUrls.length" class="mb-3 grid grid-cols-3 gap-2 sm:grid-cols-4">
                            <div v-for="(url, i) in previewUrls" :key="i" class="group relative aspect-video overflow-hidden rounded-lg bg-gray-100 dark:bg-slate-700">
                                <img :src="url" class="h-full w-full object-cover" />
                                <button
                                    type="button"
                                    @click="removePreview(i)"
                                    class="absolute right-1 top-1 flex h-5 w-5 items-center justify-center rounded-full bg-red-500 text-xs text-white opacity-0 transition-opacity group-hover:opacity-100"
                                >×</button>
                            </div>
                        </div>

                        <label class="flex cursor-pointer flex-col items-center gap-2 rounded-lg border-2 border-dashed border-gray-300 px-4 py-6 text-center text-sm text-gray-500 transition-colors hover:border-indigo-400 hover:text-indigo-500 dark:border-slate-600 dark:text-slate-400 dark:hover:border-indigo-500">
                            <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                            </svg>
                            <span>{{ t('portfolio.images.select') }}</span>
                            <span class="text-xs text-gray-400 dark:text-slate-500">{{ t('portfolio.images.hint') }}</span>
                            <input type="file" multiple accept="image/*" class="hidden" @change="onImagesChange" />
                        </label>
                        <p v-if="form.errors.images" class="mt-1 text-xs text-red-500">{{ form.errors.images }}</p>
                    </div>

                    <!-- Tech stack -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-slate-300">
                            {{ t('portfolio.fields.tech_stack') }}
                        </label>
                        <div class="flex gap-2">
                            <input
                                v-model="techInput"
                                type="text"
                                placeholder="Laravel, Vue, Tailwind..."
                                @keydown.enter.prevent="addTech"
                                class="flex-1 rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white dark:placeholder-slate-500"
                            />
                            <button type="button" @click="addTech" class="rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-600 hover:bg-gray-50 dark:border-slate-600 dark:text-slate-400 dark:hover:bg-slate-700">
                                +
                            </button>
                        </div>
                        <div v-if="form.tech_stack.length" class="mt-2 flex flex-wrap gap-1.5">
                            <span
                                v-for="tech in form.tech_stack"
                                :key="tech"
                                class="inline-flex items-center gap-1 rounded-md bg-indigo-100 px-2 py-0.5 text-xs font-medium text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-400"
                            >
                                {{ tech }}
                                <button type="button" @click="removeTech(tech)" class="hover:text-indigo-900 dark:hover:text-indigo-200">×</button>
                            </span>
                        </div>
                    </div>

                    <!-- Thumb label -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-slate-300">
                            {{ t('portfolio.fields.thumb_label') }}
                        </label>
                        <input
                            v-model="form.thumb_label"
                            type="text"
                            maxlength="100"
                            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white dark:placeholder-slate-500"
                        />
                        <p v-if="form.errors.thumb_label" class="mt-1 text-xs text-red-500">{{ form.errors.thumb_label }}</p>
                    </div>

                    <!-- Sort + Active + Private -->
                    <div class="flex flex-wrap items-center gap-6">
                        <div class="w-28">
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-slate-300">
                                {{ t('portfolio.fields.sort_order') }}
                            </label>
                            <input
                                v-model.number="form.sort_order"
                                type="number"
                                min="0"
                                class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white"
                            />
                            <p v-if="form.errors.sort_order" class="mt-1 text-xs text-red-500">{{ form.errors.sort_order }}</p>
                        </div>
                        <label class="flex cursor-pointer items-center gap-2 pt-5">
                            <input v-model="form.is_active" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                            <span class="text-sm font-medium text-gray-700 dark:text-slate-300">{{ t('portfolio.fields.is_active') }}</span>
                        </label>
                        <label class="flex cursor-pointer items-center gap-2 pt-5">
                            <input v-model="form.is_private" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500" />
                            <span class="text-sm font-medium text-gray-700 dark:text-slate-300">{{ t('portfolio.fields.is_private') }}</span>
                        </label>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end gap-3 border-t border-gray-100 pt-4 dark:border-slate-700">
                        <Link
                            :href="route('dashboard.portfolio.index')"
                            class="rounded-lg px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100 dark:text-slate-400 dark:hover:bg-slate-700"
                        >
                            {{ t('portfolio.actions.cancel') }}
                        </Link>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-60"
                        >
                            {{ form.processing ? '...' : t('portfolio.actions.save') }}
                        </button>
                    </div>
                </fieldset>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
