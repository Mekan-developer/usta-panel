<script setup>
import { useI18n } from 'vue-i18n'
import { Link, router, Head } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const { t } = useI18n()

defineProps({
    projects: {
        type: Array,
        default: () => [],
    },
})

const typeBadgeClass = {
    web: 'bg-indigo-100 text-indigo-700 dark:bg-indigo-900/40 dark:text-indigo-400',
    mobile_android: 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400',
    mobile_ios: 'bg-sky-100 text-sky-700 dark:bg-sky-900/40 dark:text-sky-400',
    mobile_both: 'bg-teal-100 text-teal-700 dark:bg-teal-900/40 dark:text-teal-400',
    desktop: 'bg-violet-100 text-violet-700 dark:bg-violet-900/40 dark:text-violet-400',
}

function confirmDelete(project) {
    if (confirm(t('portfolio.confirm_delete'))) {
        router.delete(route('dashboard.portfolio.destroy', project.id))
    }
}
</script>

<template>
    <Head :title="t('portfolio.title')" />

    <AdminLayout :title="t('portfolio.title')">
        <div class="mb-4 flex items-center justify-between">
            <p class="text-sm text-gray-500 dark:text-slate-400">{{ projects.length }} {{ t('portfolio.title').toLowerCase() }}</p>
            <div class="flex items-center gap-2">
                <Link
                    :href="route('dashboard.cv.index')"
                    class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-300 dark:hover:bg-slate-600"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                    {{ t('portfolio.cv.title') }}
                </Link>
                <Link
                    :href="route('dashboard.portfolio.create')"
                    class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    {{ t('portfolio.add') }}
                </Link>
            </div>
        </div>

        <p v-if="!projects.length" class="rounded-xl bg-white py-16 text-center text-sm text-gray-500 shadow-sm dark:bg-slate-800 dark:text-slate-400">
            {{ t('portfolio.empty') }}
        </p>

        <div v-else class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-3">
            <div
                v-for="project in projects"
                :key="project.id"
                class="overflow-hidden rounded-xl bg-white shadow-sm dark:bg-slate-800"
                :class="{ 'opacity-60': !project.is_active }"
            >
                <!-- Thumbnail / Images -->
                <div class="relative aspect-video w-full overflow-hidden bg-gray-100 dark:bg-slate-700">
                    <template v-if="project.images?.length">
                        <img
                            v-if="!project.images[0].is_processing"
                            :src="`/storage/${project.images[0].path}`"
                            :alt="project.title"
                            class="h-full w-full object-cover"
                        />
                        <div v-else class="flex h-full items-center justify-center">
                            <span class="text-xs text-gray-400 dark:text-slate-500 animate-pulse">{{ t('portfolio.images.processing') }}</span>
                        </div>
                        <span v-if="project.images.length > 1" class="absolute right-2 top-2 rounded-full bg-black/60 px-2 py-0.5 text-xs text-white">
                            +{{ project.images.length - 1 }}
                        </span>
                    </template>
                    <div v-else class="flex h-full items-center justify-center">
                        <svg class="h-10 w-10 text-gray-300 dark:text-slate-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                        </svg>
                    </div>
                </div>

                <div class="p-4">
                    <div class="flex items-start justify-between gap-2">
                        <h3 class="truncate font-semibold text-gray-900 dark:text-white">{{ project.title }}</h3>
                        <span :class="['shrink-0 rounded-full px-2 py-0.5 text-xs font-medium', typeBadgeClass[project.type] ?? 'bg-gray-100 text-gray-600 dark:bg-slate-700 dark:text-slate-400']">
                            {{ t(`portfolio.types.${project.type}`) }}
                        </span>
                    </div>

                    <p class="mt-1 line-clamp-2 text-xs text-gray-500 dark:text-slate-400">{{ project.description }}</p>

                    <!-- Tech stack -->
                    <div v-if="project.tech_stack?.length" class="mt-2 flex flex-wrap gap-1">
                        <span
                            v-for="tech in project.tech_stack"
                            :key="tech"
                            class="rounded-md bg-gray-100 px-1.5 py-0.5 text-xs text-gray-600 dark:bg-slate-700 dark:text-slate-400"
                        >
                            {{ tech }}
                        </span>
                    </div>

                    <!-- Actions -->
                    <div class="mt-3 flex items-center justify-between border-t border-gray-100 pt-3 dark:border-slate-700">
                        <span
                            :class="[
                                'text-xs font-medium',
                                project.is_active ? 'text-emerald-600 dark:text-emerald-400' : 'text-gray-400 dark:text-slate-500',
                            ]"
                        >
                            {{ project.is_active ? t('portfolio.status.active') : t('portfolio.status.hidden') }}
                        </span>
                        <div class="flex items-center gap-2">
                            <Link
                                :href="route('dashboard.portfolio.edit', project.id)"
                                class="rounded-md p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-700 dark:hover:bg-slate-700 dark:hover:text-white"
                            >
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                </svg>
                            </Link>
                            <button
                                @click="confirmDelete(project)"
                                class="rounded-md p-1.5 text-gray-400 hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-900/20 dark:hover:text-red-400"
                            >
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
