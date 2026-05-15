<script setup>
import { useI18n } from 'vue-i18n'
import { Link } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Head } from '@inertiajs/vue3'

const { t } = useI18n()

const props = defineProps({
    stats: {
        type: Object,
        default: () => ({ total_servers: 0, online_servers: 0, offline_servers: 0, total_projects: 0 }),
    },
    recentServers: {
        type: Array,
        default: () => [],
    },
})

const statCards = [
    {
        key: 'total_servers',
        labelKey: 'dashboard.stats.total_servers',
        iconPath: 'M21.75 17.25v.75a3 3 0 01-3 3h-15a3 3 0 01-3-3v-.75m19.5 0a3.75 3.75 0 00.75-2.25V5.25a3 3 0 00-3-3h-15a3 3 0 00-3 3v9.75a3.75 3.75 0 00.75 2.25m19.5 0h-19.5',
        iconBg: 'bg-indigo-100 dark:bg-indigo-900/40',
        iconColor: 'text-indigo-600 dark:text-indigo-400',
    },
    {
        key: 'online_servers',
        labelKey: 'dashboard.stats.online_servers',
        iconPath: 'M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
        iconBg: 'bg-emerald-100 dark:bg-emerald-900/40',
        iconColor: 'text-emerald-600 dark:text-emerald-400',
    },
    {
        key: 'offline_servers',
        labelKey: 'dashboard.stats.offline_servers',
        iconPath: 'M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z',
        iconBg: 'bg-red-100 dark:bg-red-900/40',
        iconColor: 'text-red-600 dark:text-red-400',
    },
    {
        key: 'total_projects',
        labelKey: 'dashboard.stats.total_projects',
        iconPath: 'M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0M12 12.75h.008v.008H12v-.008z',
        iconBg: 'bg-sky-100 dark:bg-sky-900/40',
        iconColor: 'text-sky-600 dark:text-sky-400',
    },
]

function statusColor(status) {
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
</script>

<template>
    <Head :title="t('dashboard.title')" />

    <AdminLayout :title="t('dashboard.title')">
        <!-- Stat cards -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div
                v-for="card in statCards"
                :key="card.key"
                class="flex items-center gap-4 rounded-xl bg-white p-5 shadow-sm dark:bg-slate-800"
            >
                <div :class="['flex h-12 w-12 shrink-0 items-center justify-center rounded-xl', card.iconBg]">
                    <svg :class="['h-6 w-6', card.iconColor]" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" :d="card.iconPath" />
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ props.stats[card.key] ?? 0 }}</p>
                    <p class="text-sm text-gray-500 dark:text-slate-400">{{ t(card.labelKey) }}</p>
                </div>
            </div>
        </div>

        <!-- Recent servers -->
        <div class="mt-6">
            <div class="mb-3 flex items-center justify-between">
                <h2 class="text-base font-semibold text-gray-900 dark:text-white">{{ t('dashboard.recent_servers') }}</h2>
                <Link :href="route('dashboard.servers.index')" class="text-sm text-indigo-600 hover:underline dark:text-indigo-400">
                    {{ t('servers.title') }} →
                </Link>
            </div>

            <p v-if="!recentServers.length" class="text-sm text-gray-500 dark:text-slate-400">
                {{ t('dashboard.no_servers') }}
            </p>

            <div v-else class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-3">
                <div
                    v-for="server in recentServers"
                    :key="server.id"
                    class="rounded-xl bg-white p-4 shadow-sm dark:bg-slate-800"
                >
                    <div class="flex items-center justify-between">
                        <span class="truncate text-sm font-medium text-gray-900 dark:text-white">{{ server.name }}</span>
                        <span class="flex items-center gap-1.5">
                            <span :class="['inline-block h-2 w-2 rounded-full', statusColor(server.status)]" />
                            <span class="text-xs text-gray-500 dark:text-slate-400 capitalize">{{ t(`servers.status.${server.status}`) }}</span>
                        </span>
                    </div>
                    <div v-if="server.last_metrics" class="mt-3 grid grid-cols-3 gap-2 text-xs text-gray-500 dark:text-slate-400">
                        <div>
                            <p class="font-medium text-gray-700 dark:text-slate-300">{{ t('servers.metrics.cpu') }}</p>
                            <p>{{ server.last_metrics.cpu_usage ?? '—' }}%</p>
                        </div>
                        <div>
                            <p class="font-medium text-gray-700 dark:text-slate-300">{{ t('servers.metrics.ram') }}</p>
                            <p>{{ server.last_metrics.ram_used ?? '—' }} MB</p>
                        </div>
                        <div>
                            <p class="font-medium text-gray-700 dark:text-slate-300">{{ t('servers.metrics.uptime') }}</p>
                            <p>{{ formatUptime(server.last_metrics.uptime_seconds) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
