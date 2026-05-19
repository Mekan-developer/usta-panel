<script setup>
import { ref } from 'vue'
import { router } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import { Head } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const { t } = useI18n()

const props = defineProps({
    messages: { type: Array, default: () => [] },
})

const expanded = ref(null)

function toggle(id) {
    expanded.value = expanded.value === id ? null : id
}

function deleteMessage(message) {
    if (!confirm(t('messages.confirm_delete'))) return
    router.delete(route('dashboard.messages.destroy', message.id))
}

function formatDate(iso) {
    return new Date(iso).toLocaleString('ru-RU', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    })
}
</script>

<template>
    <Head :title="t('messages.title')" />

    <AdminLayout :title="t('messages.title')">
        <div v-if="!messages.length" class="flex flex-col items-center justify-center rounded-2xl bg-white py-20 text-center shadow-sm dark:bg-slate-800">
            <svg class="mb-4 h-12 w-12 text-gray-300 dark:text-slate-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
            </svg>
            <p class="text-sm text-gray-400 dark:text-slate-500">{{ t('messages.empty') }}</p>
        </div>

        <div v-else class="space-y-3">
            <div
                v-for="msg in messages"
                :key="msg.id"
                class="overflow-hidden rounded-2xl bg-white shadow-sm transition dark:bg-slate-800"
                :class="{ 'ring-2 ring-indigo-500/40': !msg.is_read }"
            >
                <!-- Header row -->
                <button
                    type="button"
                    class="flex w-full items-center gap-4 px-5 py-4 text-left transition hover:bg-gray-50 dark:hover:bg-slate-700/50"
                    @click="toggle(msg.id)"
                >
                    <!-- Avatar -->
                    <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-indigo-100 text-sm font-bold text-indigo-600 dark:bg-indigo-900/40 dark:text-indigo-300">
                        {{ msg.name.charAt(0).toUpperCase() }}
                    </div>

                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2">
                            <span class="font-semibold text-gray-900 dark:text-white">{{ msg.name }}</span>
                            <span
                                v-if="!msg.is_read"
                                class="rounded-full bg-indigo-100 px-2 py-0.5 text-xs font-semibold text-indigo-600 dark:bg-indigo-900/40 dark:text-indigo-300"
                            >{{ t('messages.unread') }}</span>
                        </div>
                        <div class="flex items-center gap-3 text-xs text-gray-400 dark:text-slate-500">
                            <span>{{ msg.email }}</span>
                            <span>·</span>
                            <span>{{ formatDate(msg.created_at) }}</span>
                        </div>
                    </div>

                    <svg
                        class="h-4 w-4 shrink-0 text-gray-400 transition-transform duration-200"
                        :class="expanded === msg.id ? 'rotate-180' : ''"
                        fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                    >
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                    </svg>
                </button>

                <!-- Expanded body -->
                <Transition name="expand">
                    <div v-if="expanded === msg.id" class="border-t border-gray-100 px-5 py-4 dark:border-slate-700">
                        <p class="whitespace-pre-wrap text-sm leading-relaxed text-gray-700 dark:text-slate-300">{{ msg.message }}</p>

                        <div class="mt-4 flex gap-3">
                            <a
                                :href="`mailto:${msg.email}?subject=Re: меcсage from ${msg.name}`"
                                class="flex items-center gap-1.5 rounded-lg bg-indigo-600 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-indigo-700"
                            >
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                                </svg>
                                {{ t('messages.reply') }}
                            </a>
                            <button
                                type="button"
                                class="flex items-center gap-1.5 rounded-lg border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-600 transition hover:bg-red-50 dark:border-red-800 dark:text-red-400 dark:hover:bg-red-900/20"
                                @click="deleteMessage(msg)"
                            >
                                <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                                {{ t('messages.delete') }}
                            </button>
                        </div>
                    </div>
                </Transition>
            </div>
        </div>
    </AdminLayout>
</template>

<style scoped>
.expand-enter-active,
.expand-leave-active {
    transition: opacity 0.15s ease;
}
.expand-enter-from,
.expand-leave-to {
    opacity: 0;
}
</style>
