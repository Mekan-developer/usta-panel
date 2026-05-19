<script setup>
import { ref, watch, onMounted } from 'vue'
import { Link, router, usePage } from '@inertiajs/vue3'
import { useI18n } from 'vue-i18n'
import { useThemeStore } from '@/stores/useThemeStore'
import { useLocaleStore } from '@/stores/useLocaleStore'
import { useNotificationStore } from '@/stores/useNotificationStore'

defineProps({
    title: {
        type: String,
        default: '',
    },
})

const { t, locale } = useI18n()
const themeStore = useThemeStore()
const localeStore = useLocaleStore()
const notificationStore = useNotificationStore()
const page = usePage()
const appName = page.props.app_name

const sidebarOpen = ref(false)
const userMenuOpen = ref(false)

watch(() => localeStore.locale, (lang) => {
    locale.value = lang
}, { immediate: true })

watch(() => page.props.notification, (notification) => {
    if (notification?.message) {
        notificationStore.add(notification)
    }
}, { immediate: true })

const navItems = [
    {
        labelKey: 'layout.nav.dashboard',
        routeName: 'dashboard.index',
        routePattern: 'dashboard.index',
        iconPath: 'M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z',
    },
    {
        labelKey: 'layout.nav.servers',
        routeName: 'dashboard.servers.index',
        routePattern: 'dashboard.servers.*',
        iconPath: 'M21.75 17.25v.75a3 3 0 01-3 3h-15a3 3 0 01-3-3v-.75m19.5 0a3.75 3.75 0 00.75-2.25V5.25a3 3 0 00-3-3h-15a3 3 0 00-3 3v9.75a3.75 3.75 0 00.75 2.25m19.5 0h-19.5',
    },
    {
        labelKey: 'layout.nav.portfolio',
        routePattern: 'dashboard.portfolio.*',
        iconPath: 'M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0M12 12.75h.008v.008H12v-.008z',
        children: [
            { labelKey: 'layout.nav.portfolio_projects', routeName: 'dashboard.portfolio.index', routePattern: 'dashboard.portfolio.index' },
            { labelKey: 'layout.nav.portfolio_info', routeName: 'dashboard.portfolio.info', routePattern: 'dashboard.portfolio.info*' },
            { labelKey: 'layout.nav.portfolio_settings', routeName: 'dashboard.portfolio.settings.edit', routePattern: 'dashboard.portfolio.settings*' },
        ],
    },
    {
        labelKey: 'layout.nav.messages',
        routeName: 'dashboard.messages.index',
        routePattern: 'dashboard.messages.*',
        iconPath: 'M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75',
        badge: true,
    },
    {
        labelKey: 'layout.nav.learning',
        routeName: 'dashboard.learning.index',
        routePattern: 'dashboard.learning.*',
        iconPath: 'M4.26 10.147a60.438 60.438 0 00-.491 6.347A48.62 48.62 0 0112 20.904a48.62 48.62 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.636 50.636 0 00-2.658-.813A59.906 59.906 0 0112 3.493a59.903 59.903 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5',
    },
    {
        labelKey: 'layout.nav.profile',
        routeName: 'profile.edit',
        routePattern: 'profile.*',
        iconPath: 'M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z',
    },
]

const openSubmenus = ref(new Set())

onMounted(() => {
    for (const item of navItems) {
        if (item.children && isCurrentRoute(item.routePattern)) {
            openSubmenus.value.add(item.labelKey)
        }
    }
})

function toggleSubmenu(key) {
    const s = new Set(openSubmenus.value)
    if (s.has(key)) {
        s.delete(key)
    } else {
        s.add(key)
    }
    openSubmenus.value = s
}

function safeRoute(name) {
    try {
        return route(name)
    } catch {
        return '#'
    }
}

function isCurrentRoute(pattern) {
    try {
        return route().current(pattern)
    } catch {
        return false
    }
}

function logout() {
    router.post(route('logout'))
}
</script>

<template>
    <div class="flex h-screen overflow-hidden bg-gray-100 dark:bg-slate-900">

        <!-- Mobile overlay -->
        <Transition name="overlay">
            <div
                v-if="sidebarOpen"
                class="fixed inset-0 z-20 bg-black/60 lg:hidden"
                @click="sidebarOpen = false"
            />
        </Transition>

        <!-- Sidebar -->
        <aside
            :class="[
                'fixed inset-y-0 left-0 z-30 flex w-64 flex-col bg-slate-800 transition-transform duration-300 ease-in-out lg:static lg:z-auto lg:translate-x-0',
                sidebarOpen ? 'translate-x-0' : '-translate-x-full',
            ]"
        >
            <!-- Logo -->
            <div class="flex h-16 shrink-0 items-center border-b border-slate-700 px-6">
                <div class="flex items-center gap-2.5">
                    <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-indigo-600">
                        <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.42 15.17L17.25 21A2.652 2.652 0 0021 17.25l-5.877-5.877M11.42 15.17l2.496-3.03c.317-.384.74-.626 1.208-.766M11.42 15.17l-4.655 5.653a2.548 2.548 0 11-3.586-3.586l6.837-5.63m5.108-.233c.55-.164 1.163-.188 1.743-.14a4.5 4.5 0 004.486-6.336l-3.276 3.277a3.004 3.004 0 01-2.25-2.25l3.276-3.276a4.5 4.5 0 00-6.336 4.486c.091 1.076-.071 2.264-.904 2.95l-.102.085m-1.745 1.437L5.909 7.5H4.5L2.25 3.75l1.5-1.5L7.5 4.5v1.409l4.26 4.26m-1.745 1.437l1.745-1.437m6.615 8.206L15.75 15.75M4.867 19.125h.008v.008h-.008v-.008z" />
                        </svg>
                    </div>
                    <span class="text-lg font-bold text-white">{{ appName }}</span>
                </div>
            </div>

            <!-- Navigation links -->
            <nav class="flex-1 space-y-1 overflow-y-auto px-3 py-4">
                <template v-for="item in navItems" :key="item.labelKey">
                    <!-- Item with submenu -->
                    <div v-if="item.children">
                        <button
                            @click="toggleSubmenu(item.labelKey)"
                            :class="[
                                'group flex w-full items-center justify-between gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-all duration-150',
                                isCurrentRoute(item.routePattern)
                                    ? 'bg-indigo-600/20 text-indigo-300'
                                    : 'text-slate-300 hover:bg-slate-700 hover:text-white',
                            ]"
                        >
                            <div class="flex items-center gap-3">
                                <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" :d="item.iconPath" />
                                </svg>
                                {{ t(item.labelKey) }}
                            </div>
                            <svg
                                class="h-4 w-4 shrink-0 transition-transform duration-200"
                                :class="openSubmenus.has(item.labelKey) ? 'rotate-180' : ''"
                                fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                            </svg>
                        </button>
                        <div v-if="openSubmenus.has(item.labelKey)" class="mt-1 ml-4 space-y-0.5">
                            <Link
                                v-for="child in item.children"
                                :key="child.routeName"
                                :href="safeRoute(child.routeName)"
                                :class="[
                                    'flex items-center gap-2 rounded-lg px-3 py-2 text-sm font-medium transition-all duration-150',
                                    isCurrentRoute(child.routePattern)
                                        ? 'bg-indigo-600 text-white shadow-sm'
                                        : 'text-slate-400 hover:bg-slate-700 hover:text-white',
                                ]"
                                @click="sidebarOpen = false"
                            >
                                <span class="h-1.5 w-1.5 rounded-full bg-current opacity-60" />
                                {{ t(child.labelKey) }}
                            </Link>
                        </div>
                    </div>

                    <!-- Regular item -->
                    <Link
                        v-else
                        :href="safeRoute(item.routeName)"
                        :class="[
                            'group flex items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition-all duration-150',
                            isCurrentRoute(item.routePattern)
                                ? 'bg-indigo-600 text-white shadow-sm'
                                : 'text-slate-300 hover:bg-slate-700 hover:text-white',
                        ]"
                        @click="sidebarOpen = false"
                    >
                        <svg class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" :d="item.iconPath" />
                        </svg>
                        <span class="flex-1">{{ t(item.labelKey) }}</span>
                        <span
                            v-if="item.badge && $page.props.unread_messages_count > 0"
                            class="ml-auto flex h-5 min-w-5 items-center justify-center rounded-full bg-red-500 px-1.5 text-xs font-bold text-white"
                        >
                            {{ $page.props.unread_messages_count }}
                        </span>
                    </Link>
                </template>
            </nav>

            <!-- Bottom user area -->
            <div class="shrink-0 border-t border-slate-700 p-4">
                <div class="flex items-center gap-3">
                    <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-indigo-600 text-sm font-bold text-white">
                        {{ $page.props.auth.user?.name?.charAt(0)?.toUpperCase() }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-medium text-white">{{ $page.props.auth.user?.name }}</p>
                        <p class="truncate text-xs text-slate-400">{{ $page.props.auth.user?.email }}</p>
                    </div>
                    <button
                        @click="logout"
                        class="shrink-0 rounded-md p-1.5 text-slate-400 transition-colors hover:bg-slate-700 hover:text-white"
                        :title="t('layout.header.logout')"
                    >
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                        </svg>
                    </button>
                </div>
            </div>
        </aside>

        <!-- Main area -->
        <div class="flex min-w-0 flex-1 flex-col overflow-hidden">

            <!-- Topbar -->
            <header class="flex h-16 shrink-0 items-center gap-4 border-b border-gray-200 bg-white px-4 dark:border-slate-700 dark:bg-slate-800 lg:px-6">

                <!-- Hamburger (mobile) -->
                <button
                    class="rounded-md p-2 text-gray-500 transition-colors hover:bg-gray-100 hover:text-gray-700 dark:text-slate-400 dark:hover:bg-slate-700 dark:hover:text-white lg:hidden"
                    @click="sidebarOpen = !sidebarOpen"
                >
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>

                <!-- Page title -->
                <h1 class="flex-1 text-lg font-semibold text-gray-900 dark:text-white">{{ title }}</h1>

                <!-- Controls -->
                <div class="flex items-center gap-2">

                    <!-- Dark mode toggle -->
                    <button
                        @click="themeStore.toggle"
                        class="rounded-md p-2 text-gray-500 transition-colors hover:bg-gray-100 hover:text-gray-700 dark:text-slate-400 dark:hover:bg-slate-700 dark:hover:text-white"
                        :title="t('layout.header.theme_toggle')"
                    >
                        <svg v-if="themeStore.isDark" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                        </svg>
                        <svg v-else class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" />
                        </svg>
                    </button>

                    <!-- Language switcher -->
                    <div class="flex items-center gap-0.5 rounded-md border border-gray-200 p-0.5 dark:border-slate-700">
                        <button
                            v-for="lang in ['ru', 'tk']"
                            :key="lang"
                            @click="localeStore.setLocale(lang)"
                            :class="[
                                'rounded px-2.5 py-1 text-xs font-semibold uppercase transition-colors',
                                localeStore.locale === lang
                                    ? 'bg-indigo-600 text-white'
                                    : 'text-gray-500 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white',
                            ]"
                        >
                            {{ lang }}
                        </button>
                    </div>

                    <!-- User dropdown -->
                    <div class="relative">
                        <button
                            @click="userMenuOpen = !userMenuOpen"
                            class="flex items-center gap-2 rounded-md px-2 py-1.5 text-sm text-gray-700 transition-colors hover:bg-gray-100 dark:text-slate-300 dark:hover:bg-slate-700"
                        >
                            <div class="flex h-7 w-7 items-center justify-center rounded-full bg-indigo-600 text-xs font-bold text-white">
                                {{ $page.props.auth.user?.name?.charAt(0)?.toUpperCase() }}
                            </div>
                            <span class="hidden sm:block max-w-32 truncate">{{ $page.props.auth.user?.name }}</span>
                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                            </svg>
                        </button>

                        <Transition name="dropdown">
                            <div
                                v-if="userMenuOpen"
                                class="absolute right-0 top-full z-50 mt-1 w-48 rounded-lg border border-gray-200 bg-white py-1 shadow-lg dark:border-slate-700 dark:bg-slate-800"
                            >
                                <Link
                                    :href="route('profile.edit')"
                                    @click="userMenuOpen = false"
                                    class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 dark:text-slate-300 dark:hover:bg-slate-700"
                                >
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                    </svg>
                                    {{ t('layout.header.profile') }}
                                </Link>
                                <button
                                    @click="logout"
                                    class="flex w-full items-center gap-2 px-4 py-2 text-sm text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/20"
                                >
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                                    </svg>
                                    {{ t('layout.header.logout') }}
                                </button>
                            </div>
                        </Transition>
                    </div>
                </div>
            </header>

            <!-- Page content -->
            <main class="flex-1 overflow-y-auto p-4 lg:p-6">
                <slot />
            </main>
        </div>

        <!-- Click-outside backdrop for user menu -->
        <div
            v-if="userMenuOpen"
            class="fixed inset-0 z-40"
            @click="userMenuOpen = false"
        />

        <!-- Toast notifications -->
        <div class="pointer-events-none fixed right-4 top-4 z-[9999] flex flex-col gap-2">
            <TransitionGroup name="toast">
                <div
                    v-for="notification in notificationStore.notifications"
                    :key="notification.id"
                    class="pointer-events-auto flex min-w-80 max-w-sm items-start gap-3 rounded-lg px-4 py-3 text-white shadow-lg"
                    :class="{
                        'bg-green-500': notification.type === 'success',
                        'bg-red-500': notification.type === 'error',
                        'bg-yellow-500 text-gray-900': notification.type === 'warning',
                        'bg-blue-500': notification.type === 'info',
                    }"
                >
                    <p class="flex-1 text-sm font-medium">{{ notification.message }}</p>
                    <button
                        @click="notificationStore.remove(notification.id)"
                        class="shrink-0 rounded opacity-75 transition-opacity hover:opacity-100"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </TransitionGroup>
        </div>
    </div>
</template>

<style scoped>
.overlay-enter-active,
.overlay-leave-active {
    transition: opacity 0.2s ease;
}
.overlay-enter-from,
.overlay-leave-to {
    opacity: 0;
}

.dropdown-enter-active,
.dropdown-leave-active {
    transition: opacity 0.15s ease, transform 0.15s ease;
}
.dropdown-enter-from,
.dropdown-leave-to {
    opacity: 0;
    transform: translateY(-6px) scale(0.97);
}

.toast-enter-active,
.toast-leave-active {
    transition: opacity 0.3s ease, transform 0.3s ease;
}
.toast-enter-from,
.toast-leave-to {
    opacity: 0;
    transform: translateX(100%);
}
.toast-move {
    transition: transform 0.3s ease;
}
</style>
