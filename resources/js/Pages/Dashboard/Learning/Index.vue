<script setup>
import { ref, computed } from 'vue'
import { useForm, router } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Head } from '@inertiajs/vue3'

const { t } = useI18n()

const props = defineProps({
    topics: {
        type: Object,
        default: () => ({ studied: [], current: [], planned: [] }),
    },
})

const showModal = ref(false)
const editingTopic = ref(null)

const form = useForm({
    title: '',
    category: 'it',
    status: 'current',
    period: '',
    progress: 0,
    notes: '',
    sort_order: 0,
})

const totalCount = computed(() => props.topics.studied.length + props.topics.current.length + props.topics.planned.length)
const overallProgress = computed(() => {
    if (!totalCount.value) return 0
    const score = props.topics.studied.length * 100
        + props.topics.current.reduce((acc, t) => acc + (t.progress ?? 0), 0)
    return Math.round(score / (totalCount.value * 100) * 100)
})

const categoryConfig = {
    it: { label: 'IT', bg: 'bg-indigo-100 dark:bg-indigo-900/40', text: 'text-indigo-700 dark:text-indigo-300' },
    ai: { label: 'AI', bg: 'bg-violet-100 dark:bg-violet-900/40', text: 'text-violet-700 dark:text-violet-300' },
    language: { label: t('learning.categories.language'), bg: 'bg-emerald-100 dark:bg-emerald-900/40', text: 'text-emerald-700 dark:text-emerald-300' },
}

const sections = computed(() => [
    {
        key: 'studied',
        label: t('learning.sections.studied'),
        topics: props.topics.studied,
        color: 'border-emerald-500',
        headerBg: 'bg-emerald-50 dark:bg-emerald-900/20',
        headerText: 'text-emerald-700 dark:text-emerald-300',
        dot: 'bg-emerald-500',
        emptyIcon: 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
    },
    {
        key: 'current',
        label: t('learning.sections.current'),
        topics: props.topics.current,
        color: 'border-indigo-500',
        headerBg: 'bg-indigo-50 dark:bg-indigo-900/20',
        headerText: 'text-indigo-700 dark:text-indigo-300',
        dot: 'bg-indigo-500',
        emptyIcon: 'M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25',
    },
    {
        key: 'planned',
        label: t('learning.sections.planned'),
        topics: props.topics.planned,
        color: 'border-amber-500',
        headerBg: 'bg-amber-50 dark:bg-amber-900/20',
        headerText: 'text-amber-700 dark:text-amber-300',
        dot: 'bg-amber-500',
        emptyIcon: 'M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z',
    },
])

function openCreate(defaultStatus = 'current') {
    editingTopic.value = null
    form.reset()
    form.status = defaultStatus
    form.sort_order = 0
    showModal.value = true
}

function openEdit(topic) {
    editingTopic.value = topic
    form.title = topic.title
    form.category = topic.category
    form.status = topic.status
    form.period = topic.period ?? ''
    form.progress = topic.progress
    form.notes = topic.notes ?? ''
    form.sort_order = topic.sort_order
    showModal.value = true
}

function closeModal() {
    showModal.value = false
    editingTopic.value = null
    form.reset()
    form.clearErrors()
}

function submitForm() {
    if (editingTopic.value) {
        form.put(route('dashboard.learning.update', editingTopic.value.id), {
            onSuccess: closeModal,
        })
    } else {
        form.post(route('dashboard.learning.store'), {
            onSuccess: closeModal,
        })
    }
}

function deleteTopic(topic) {
    if (!confirm(topic.title + '?')) return
    router.delete(route('dashboard.learning.destroy', topic.id))
}

function progressColor(val) {
    if (val >= 80) return 'bg-emerald-500'
    if (val >= 50) return 'bg-indigo-500'
    return 'bg-amber-500'
}
</script>

<template>
    <Head :title="t('learning.title')" />

    <AdminLayout :title="t('learning.title')">
        <!-- Header + overall progress -->
        <div class="mb-6 rounded-2xl bg-gradient-to-br from-indigo-600 to-violet-600 p-6 text-white shadow-lg">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-2xl font-bold">{{ t('learning.title') }}</h2>
                    <p class="mt-1 text-indigo-200">{{ t('learning.subtitle') }}</p>
                </div>
                <button
                    @click="openCreate()"
                    class="shrink-0 flex items-center gap-2 rounded-xl bg-white/20 px-4 py-2 text-sm font-semibold text-white backdrop-blur transition hover:bg-white/30"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    {{ t('learning.add_topic') }}
                </button>
            </div>

            <!-- Stats row -->
            <div class="mt-5 grid grid-cols-4 gap-3">
                <div class="rounded-xl bg-white/10 p-3 text-center backdrop-blur">
                    <p class="text-2xl font-bold">{{ totalCount }}</p>
                    <p class="text-xs text-indigo-200">{{ t('learning.stats.total') }}</p>
                </div>
                <div class="rounded-xl bg-white/10 p-3 text-center backdrop-blur">
                    <p class="text-2xl font-bold text-emerald-300">{{ topics.studied.length }}</p>
                    <p class="text-xs text-indigo-200">{{ t('learning.stats.completed') }}</p>
                </div>
                <div class="rounded-xl bg-white/10 p-3 text-center backdrop-blur">
                    <p class="text-2xl font-bold text-sky-300">{{ topics.current.length }}</p>
                    <p class="text-xs text-indigo-200">{{ t('learning.stats.in_progress') }}</p>
                </div>
                <div class="rounded-xl bg-white/10 p-3 text-center backdrop-blur">
                    <p class="text-2xl font-bold text-amber-300">{{ topics.planned.length }}</p>
                    <p class="text-xs text-indigo-200">{{ t('learning.stats.planned') }}</p>
                </div>
            </div>

            <!-- Overall progress bar -->
            <div class="mt-4">
                <div class="mb-1.5 flex items-center justify-between text-sm">
                    <span class="text-indigo-200">Общий прогресс</span>
                    <span class="font-bold">{{ overallProgress }}%</span>
                </div>
                <div class="h-2.5 w-full overflow-hidden rounded-full bg-white/20">
                    <div
                        class="h-full rounded-full bg-white transition-all duration-700"
                        :style="{ width: overallProgress + '%' }"
                    />
                </div>
            </div>
        </div>

        <!-- Three columns -->
        <div class="grid grid-cols-1 gap-5 lg:grid-cols-3">
            <div
                v-for="section in sections"
                :key="section.key"
                class="flex flex-col rounded-2xl border-t-4 bg-white shadow-sm dark:bg-slate-800"
                :class="section.color"
            >
                <!-- Section header -->
                <div class="flex items-center justify-between rounded-t-xl px-4 py-3" :class="section.headerBg">
                    <div class="flex items-center gap-2">
                        <span class="h-2.5 w-2.5 rounded-full" :class="section.dot" />
                        <span class="font-semibold" :class="section.headerText">{{ section.label }}</span>
                        <span class="ml-1 rounded-full bg-white/60 px-2 py-0.5 text-xs font-bold dark:bg-slate-700" :class="section.headerText">
                            {{ section.topics.length }}
                        </span>
                    </div>
                    <button
                        @click="openCreate(section.key)"
                        class="rounded-lg p-1 transition hover:bg-white/50 dark:hover:bg-slate-700"
                        :class="section.headerText"
                        :title="t('learning.add_topic')"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                    </button>
                </div>

                <!-- Topics list -->
                <div class="flex flex-1 flex-col gap-2 p-3">
                    <p v-if="!section.topics.length" class="flex flex-col items-center gap-2 py-8 text-center text-sm text-gray-400 dark:text-slate-500">
                        <svg class="h-8 w-8 opacity-40" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" :d="section.emptyIcon" />
                        </svg>
                        {{ t('learning.no_topics') }}
                    </p>

                    <div
                        v-for="topic in section.topics"
                        :key="topic.id"
                        class="group relative rounded-xl border border-gray-100 bg-gray-50 p-3 transition hover:border-gray-200 dark:border-slate-700 dark:bg-slate-700/50 dark:hover:border-slate-600"
                    >
                        <div class="flex items-start justify-between gap-2">
                            <div class="min-w-0 flex-1">
                                <div class="flex flex-wrap items-center gap-1.5">
                                    <!-- Category badge -->
                                    <span
                                        class="rounded-md px-1.5 py-0.5 text-xs font-semibold"
                                        :class="[categoryConfig[topic.category]?.bg, categoryConfig[topic.category]?.text]"
                                    >
                                        {{ categoryConfig[topic.category]?.label ?? topic.category }}
                                    </span>
                                    <!-- Period badge -->
                                    <span v-if="topic.period" class="text-xs text-gray-400 dark:text-slate-500">
                                        {{ topic.period }}
                                    </span>
                                </div>
                                <p class="mt-1.5 font-medium text-gray-900 dark:text-white">{{ topic.title }}</p>
                                <p v-if="topic.notes" class="mt-0.5 text-xs text-gray-500 dark:text-slate-400 line-clamp-2">{{ topic.notes }}</p>
                            </div>

                            <!-- Actions -->
                            <div class="flex shrink-0 gap-1 opacity-0 transition group-hover:opacity-100">
                                <button
                                    @click="openEdit(topic)"
                                    class="rounded-md p-1 text-gray-400 hover:bg-gray-200 hover:text-gray-700 dark:hover:bg-slate-600 dark:hover:text-white"
                                >
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                    </svg>
                                </button>
                                <button
                                    @click="deleteTopic(topic)"
                                    class="rounded-md p-1 text-gray-400 hover:bg-red-100 hover:text-red-600 dark:hover:bg-red-900/30 dark:hover:text-red-400"
                                >
                                    <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Progress bar for current topics -->
                        <div v-if="section.key === 'current'" class="mt-2.5">
                            <div class="mb-1 flex justify-between text-xs text-gray-500 dark:text-slate-400">
                                <span>{{ t('learning.fields.progress') }}</span>
                                <span class="font-semibold">{{ topic.progress }}%</span>
                            </div>
                            <div class="h-1.5 w-full overflow-hidden rounded-full bg-gray-200 dark:bg-slate-600">
                                <div
                                    class="h-full rounded-full transition-all duration-500"
                                    :class="progressColor(topic.progress)"
                                    :style="{ width: topic.progress + '%' }"
                                />
                            </div>
                        </div>

                        <!-- Completion checkmark for studied -->
                        <div v-if="section.key === 'studied'" class="mt-2 flex items-center gap-1 text-xs text-emerald-600 dark:text-emerald-400">
                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span>Изучено</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <Transition name="modal">
            <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center p-4">
                <div class="absolute inset-0 bg-black/60 backdrop-blur-sm" @click="closeModal" />

                <div class="relative w-full max-w-md rounded-2xl bg-white shadow-2xl dark:bg-slate-800">
                    <!-- Modal header -->
                    <div class="flex items-center justify-between border-b border-gray-100 px-6 py-4 dark:border-slate-700">
                        <h3 class="font-semibold text-gray-900 dark:text-white">
                            {{ editingTopic ? t('learning.edit_topic') : t('learning.add_topic') }}
                        </h3>
                        <button
                            @click="closeModal"
                            class="rounded-lg p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-slate-700"
                        >
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Modal body -->
                    <form @submit.prevent="submitForm" class="space-y-4 p-6">
                        <!-- Title -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-slate-300">
                                {{ t('learning.fields.title') }}
                            </label>
                            <input
                                v-model="form.title"
                                type="text"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white"
                                :class="{ 'border-red-400': form.errors.title }"
                            />
                            <p v-if="form.errors.title" class="mt-1 text-xs text-red-500">{{ form.errors.title }}</p>
                        </div>

                        <!-- Category + Status -->
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-slate-300">
                                    {{ t('learning.fields.category') }}
                                </label>
                                <select
                                    v-model="form.category"
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white"
                                >
                                    <option value="it">IT</option>
                                    <option value="ai">AI</option>
                                    <option value="language">{{ t('learning.categories.language') }}</option>
                                </select>
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-slate-300">
                                    {{ t('learning.fields.status') }}
                                </label>
                                <select
                                    v-model="form.status"
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white"
                                >
                                    <option value="studied">{{ t('learning.statuses.studied') }}</option>
                                    <option value="current">{{ t('learning.statuses.current') }}</option>
                                    <option value="planned">{{ t('learning.statuses.planned') }}</option>
                                </select>
                            </div>
                        </div>

                        <!-- Period + Progress -->
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-slate-300">
                                    {{ t('learning.fields.period') }}
                                </label>
                                <input
                                    v-model="form.period"
                                    type="text"
                                    placeholder="2026-05"
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white"
                                    :class="{ 'border-red-400': form.errors.period }"
                                />
                                <p v-if="form.errors.period" class="mt-1 text-xs text-red-500">{{ form.errors.period }}</p>
                            </div>
                            <div>
                                <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-slate-300">
                                    {{ t('learning.fields.progress') }}
                                </label>
                                <input
                                    v-model.number="form.progress"
                                    type="number"
                                    min="0"
                                    max="100"
                                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white"
                                    :class="{ 'border-red-400': form.errors.progress }"
                                />
                            </div>
                        </div>

                        <!-- Notes -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-slate-300">
                                {{ t('learning.fields.notes') }}
                            </label>
                            <textarea
                                v-model="form.notes"
                                rows="2"
                                class="w-full resize-none rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white"
                            />
                        </div>

                        <!-- Sort order -->
                        <div>
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-slate-300">
                                {{ t('learning.fields.sort_order') }}
                            </label>
                            <input
                                v-model.number="form.sort_order"
                                type="number"
                                min="0"
                                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white"
                            />
                        </div>

                        <!-- Actions -->
                        <div class="flex gap-3 pt-2">
                            <button
                                type="button"
                                @click="closeModal"
                                class="flex-1 rounded-xl border border-gray-200 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 dark:border-slate-600 dark:text-slate-300 dark:hover:bg-slate-700"
                            >
                                Отмена
                            </button>
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="flex-1 rounded-xl bg-indigo-600 py-2.5 text-sm font-medium text-white transition hover:bg-indigo-700 disabled:opacity-60"
                            >
                                {{ form.processing ? '...' : (editingTopic ? 'Сохранить' : 'Добавить') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </Transition>
    </AdminLayout>
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
