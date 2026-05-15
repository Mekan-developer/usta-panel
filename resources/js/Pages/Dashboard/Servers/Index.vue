<script setup>
import { ref } from 'vue'
import { useI18n } from 'vue-i18n'
import { Link, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Head } from '@inertiajs/vue3'

const { t } = useI18n()

const checking = ref(false)
const checkingId = ref(null)

function checkAll() {
    checking.value = true
    router.post(route('dashboard.servers.check-all'), {}, {
        onFinish: () => { checking.value = false },
    })
}

function checkOne(server) {
    checkingId.value = server.id
    router.post(route('dashboard.servers.check', server.id), {}, {
        onFinish: () => { checkingId.value = null },
    })
}

defineProps({
    servers: {
        type: Array,
        default: () => [],
    },
})

function statusBadgeClass(status) {
    return {
        online: 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400',
        offline: 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-400',
        unknown: 'bg-gray-100 text-gray-600 dark:bg-slate-700 dark:text-slate-400',
    }[status] ?? 'bg-gray-100 text-gray-600 dark:bg-slate-700 dark:text-slate-400'
}

function statusDotClass(status) {
    return {
        online: 'bg-emerald-500',
        offline: 'bg-red-500',
        unknown: 'bg-gray-400',
    }[status] ?? 'bg-gray-400'
}

function formatUptime(seconds) {
    if (!seconds) return '—'
    const d = Math.floor(seconds / 86400)
    const h = Math.floor((seconds % 86400) / 3600)
    if (d > 0) return `${d}${t('common.days_short')} ${h}${t('common.hours_short')}`
    return `${h}${t('common.hours_short')}`
}

function formatBytes(mb) {
    if (!mb) return '—'
    return mb >= 1024 ? `${(mb / 1024).toFixed(1)} GB` : `${mb} MB`
}

function progressClass(pct) {
    if (pct >= 90) return 'bg-red-500'
    if (pct >= 70) return 'bg-amber-500'
    return 'bg-emerald-500'
}

function confirmDelete(server) {
    if (confirm(t('servers.confirm_delete'))) {
        router.delete(route('dashboard.servers.destroy', server.id))
    }
}
</script>

<template>
    <Head :title="t('servers.title')" />

    <AdminLayout :title="t('servers.title')">
        <div class="mb-4 flex items-center justify-between">
            <p class="text-sm text-gray-500 dark:text-slate-400">{{ servers.length }} {{ t('servers.title').toLowerCase() }}</p>
            <div class="flex items-center gap-2">
                <button
                    v-if="servers.length"
                    @click="checkAll"
                    :disabled="checking"
                    class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 disabled:opacity-60 dark:border-slate-600 dark:bg-slate-700 dark:text-slate-300 dark:hover:bg-slate-600"
                >
                    <svg class="h-4 w-4" :class="{ 'animate-spin': checking }" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                    {{ t('servers.check_all') }}
                </button>
                <Link
                    :href="route('dashboard.servers.create')"
                    class="inline-flex items-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-700"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    {{ t('servers.add') }}
                </Link>
            </div>
        </div>

        <p v-if="!servers.length" class="rounded-xl bg-white py-16 text-center text-sm text-gray-500 shadow-sm dark:bg-slate-800 dark:text-slate-400">
            {{ t('servers.empty') }}
        </p>

        <div v-else class="grid grid-cols-1 gap-4 lg:grid-cols-2 xl:grid-cols-3">
            <div
                v-for="server in servers"
                :key="server.id"
                class="rounded-xl bg-white p-5 shadow-sm dark:bg-slate-800"
            >
                <!-- Header -->
                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0">
                        <h3 class="truncate font-semibold text-gray-900 dark:text-white">{{ server.name }}</h3>
                        <p class="truncate text-xs text-gray-400 dark:text-slate-500">{{ server.url }}</p>
                    </div>
                    <span :class="['inline-flex shrink-0 items-center gap-1 rounded-full px-2 py-0.5 text-xs font-medium', statusBadgeClass(server.status)]">
                        <span :class="['h-1.5 w-1.5 rounded-full', statusDotClass(server.status)]" />
                        {{ t(`servers.status.${server.status}`) }}
                    </span>
                </div>

                <!-- Metrics -->
                <div v-if="server.last_metrics" class="mt-4 space-y-3">
                    <!-- CPU -->
                    <div>
                        <div class="mb-1 flex justify-between text-xs text-gray-500 dark:text-slate-400">
                            <span>{{ t('servers.metrics.cpu') }}</span>
                            <span>{{ server.last_metrics.cpu_usage ?? 0 }}%</span>
                        </div>
                        <div class="h-1.5 w-full rounded-full bg-gray-100 dark:bg-slate-700">
                            <div
                                :class="['h-1.5 rounded-full transition-all', progressClass(server.last_metrics.cpu_usage)]"
                                :style="{ width: `${Math.min(server.last_metrics.cpu_usage ?? 0, 100)}%` }"
                            />
                        </div>
                    </div>
                    <!-- RAM -->
                    <div>
                        <div class="mb-1 flex justify-between text-xs text-gray-500 dark:text-slate-400">
                            <span>{{ t('servers.metrics.ram') }}</span>
                            <span>{{ formatBytes(server.last_metrics.ram_used) }} / {{ formatBytes(server.last_metrics.ram_total) }}</span>
                        </div>
                        <div class="h-1.5 w-full rounded-full bg-gray-100 dark:bg-slate-700">
                            <div
                                :class="['h-1.5 rounded-full transition-all', progressClass(Math.round((server.last_metrics.ram_used / server.last_metrics.ram_total) * 100))]"
                                :style="{ width: `${Math.min(Math.round((server.last_metrics.ram_used / server.last_metrics.ram_total) * 100), 100)}%` }"
                            />
                        </div>
                    </div>
                    <!-- Disk -->
                    <div class="flex justify-between text-xs text-gray-500 dark:text-slate-400">
                        <span>{{ t('servers.metrics.disk') }}</span>
                        <span>{{ server.last_metrics.disk_used ?? '—' }} / {{ server.last_metrics.disk_total ?? '—' }} GB</span>
                    </div>
                    <!-- Uptime -->
                    <div class="flex justify-between text-xs text-gray-500 dark:text-slate-400">
                        <span>{{ t('servers.metrics.uptime') }}</span>
                        <span>{{ formatUptime(server.last_metrics.uptime_seconds) }}</span>
                    </div>
                    <!-- Services -->
                    <div class="mt-3 border-t border-gray-100 pt-3 dark:border-slate-700">
                        <p class="mb-1.5 text-xs font-medium text-gray-500 dark:text-slate-400">{{ t('servers.metrics.services') }}</p>
                        <div class="flex flex-wrap gap-1.5">
                            <!-- Web server — inferred from online status -->
                            <span class="inline-flex items-center gap-1 rounded-md px-2 py-0.5 text-xs font-medium bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400">
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500" />
                                web
                            </span>
                            <!-- Background services from endpoint -->
                            <span
                                v-for="(active, name) in (server.last_metrics.services ?? {})"
                                :key="name"
                                :class="[
                                    'inline-flex items-center gap-1 rounded-md px-2 py-0.5 text-xs font-medium',
                                    active
                                        ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/40 dark:text-emerald-400'
                                        : 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-400',
                                ]"
                            >
                                <span :class="['h-1.5 w-1.5 rounded-full', active ? 'bg-emerald-500' : 'bg-red-500']" />
                                {{ name }}
                            </span>
                        </div>
                    </div>
                </div>

                <div v-else class="mt-3 text-xs text-gray-400 dark:text-slate-500">
                    {{ t('servers.metrics.last_checked') }}: {{ t('servers.metrics.never') }}
                </div>

                <!-- Actions -->
                <div class="mt-4 flex items-center justify-between border-t border-gray-100 pt-3 dark:border-slate-700">
                    <span v-if="server.last_checked_at" class="text-xs text-gray-400 dark:text-slate-500">
                        {{ new Date(server.last_checked_at).toLocaleString('ru') }}
                    </span>
                    <span v-else class="text-xs text-gray-400 dark:text-slate-500">—</span>
                    <div class="flex items-center gap-2">
                        <!-- Check now -->
                        <button
                            @click="checkOne(server)"
                            :disabled="checkingId === server.id"
                            class="rounded-md p-1.5 text-gray-400 hover:bg-emerald-50 hover:text-emerald-600 disabled:opacity-50 dark:hover:bg-emerald-900/20 dark:hover:text-emerald-400"
                            :title="t('servers.check_now')"
                        >
                            <svg class="h-4 w-4" :class="{ 'animate-spin': checkingId === server.id }" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                            </svg>
                        </button>
                        <!-- Edit -->
                        <Link
                            :href="route('dashboard.servers.edit', server.id)"
                            class="rounded-md p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-700 dark:hover:bg-slate-700 dark:hover:text-white"
                        >
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                            </svg>
                        </Link>
                        <!-- Delete -->
                        <button
                            @click="confirmDelete(server)"
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
    </AdminLayout>
</template>
