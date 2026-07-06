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
    <div class="flex h-screen overflow-hidden bg-page font-sans dark:bg-page-dark">

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
                'fixed inset-y-0 left-0 z-30 flex w-[248px] flex-col border-r border-edge bg-shell transition-transform duration-300 ease-in-out dark:border-edge-dark dark:bg-shell-dark lg:static lg:z-auto lg:translate-x-0',
                sidebarOpen ? 'translate-x-0' : '-translate-x-full',
            ]"
        >
            <!-- Logo -->
            <div class="flex shrink-0 items-center gap-2.5 px-5 pb-6 pt-[22px]">
                <div class="flex h-[34px] w-[34px] shrink-0 items-center justify-center rounded-[10px] bg-gradient-to-br from-[#8b7cf8] to-[#6c5ce7] shadow-[0_4px_12px_rgba(124,108,246,0.35)]">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none"><path d="M12 2L4 6v6c0 5 3.5 8.5 8 10 4.5-1.5 8-5 8-10V6l-8-4z" fill="white" fill-opacity="0.95" /></svg>
                </div>
                <span class="text-[15.5px] font-extrabold tracking-[-0.01em] text-ink dark:text-ink-dark">{{ appName }}</span>
            </div>

            <!-- Navigation links -->
            <nav class="flex flex-1 flex-col gap-0.5 overflow-y-auto px-3 py-2">
                <template v-for="item in navItems" :key="item.labelKey">
                    <!-- Item with submenu -->
                    <div v-if="item.children">
                        <button
                            @click="toggleSubmenu(item.labelKey)"
                            :class="[
                                'group flex w-full items-center justify-between gap-[11px] rounded-[10px] px-3 py-2.5 text-[13.5px] transition-colors duration-150',
                                isCurrentRoute(item.routePattern)
                                    ? 'bg-accent/10 font-semibold text-accent dark:bg-accent/[0.16]'
                                    : 'font-medium text-subtext hover:bg-field dark:text-subtext-dark dark:hover:bg-field-dark',
                            ]"
                        >
                            <div class="flex items-center gap-[11px]">
                                <svg class="h-[17px] w-[17px] shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" :d="item.iconPath" />
                                </svg>
                                {{ t(item.labelKey) }}
                            </div>
                            <svg
                                class="h-[14px] w-[14px] shrink-0 text-fadetext transition-transform duration-150 dark:text-fadetext-dark"
                                :class="openSubmenus.has(item.labelKey) ? 'rotate-180' : ''"
                                fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 9l6 6 6-6" />
                            </svg>
                        </button>
                        <div v-if="openSubmenus.has(item.labelKey)" class="mt-0.5 ml-4 space-y-0.5">
                            <Link
                                v-for="child in item.children"
                                :key="child.routeName"
                                :href="safeRoute(child.routeName)"
                                :class="[
                                    'flex items-center gap-2 rounded-[10px] px-3 py-2 text-[13.5px] transition-colors duration-150',
                                    isCurrentRoute(child.routePattern)
                                        ? 'bg-accent/10 font-semibold text-accent dark:bg-accent/[0.16]'
                                        : 'font-medium text-subtext hover:bg-field dark:text-subtext-dark dark:hover:bg-field-dark',
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
                            'group flex items-center gap-[11px] rounded-[10px] px-3 py-2.5 text-[13.5px] transition-colors duration-150',
                            isCurrentRoute(item.routePattern)
                                ? 'bg-accent/10 font-semibold text-accent dark:bg-accent/[0.16]'
                                : 'font-medium text-subtext hover:bg-field dark:text-subtext-dark dark:hover:bg-field-dark',
                        ]"
                        @click="sidebarOpen = false"
                    >
                        <svg class="h-[17px] w-[17px] shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
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
            <div class="flex shrink-0 items-center gap-2.5 border-t border-edge px-4 py-3.5 dark:border-edge-dark">
                <div class="h-9 w-9 shrink-0 overflow-hidden rounded-full border-2 border-accent/40">
                    <img
                        v-if="$page.props.auth.user?.avatar_url"
                        :src="$page.props.auth.user.avatar_url"
                        :alt="$page.props.auth.user.name"
                        class="h-full w-full object-cover"
                    />
                    <div v-else class="flex h-full w-full items-center justify-center bg-accent text-sm font-bold text-white">
                        {{ $page.props.auth.user?.name?.charAt(0)?.toUpperCase() }}
                    </div>
                </div>
                <div class="min-w-0 flex-1">
                    <p class="truncate text-[12.5px] font-semibold text-ink dark:text-ink-dark">{{ $page.props.auth.user?.name }}</p>
                    <p class="truncate text-[11px] text-fadetext dark:text-fadetext-dark">{{ $page.props.auth.user?.email }}</p>
                </div>
                <button
                    @click="logout"
                    class="flex h-[30px] w-[30px] shrink-0 items-center justify-center rounded-lg border border-edge text-fadetext transition-colors hover:bg-field dark:border-edge-dark dark:text-fadetext-dark dark:hover:bg-field-dark"
                    :title="t('layout.header.logout')"
                >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 17l5-5-5-5M21 12H9" />
                    </svg>
                </button>
            </div>
        </aside>

        <!-- Main area -->
        <div class="flex min-w-0 flex-1 flex-col overflow-hidden">

            <!-- Topbar -->
            <header class="sticky top-0 z-[5] flex h-16 shrink-0 items-center gap-4 border-b border-edge bg-shell px-4 dark:border-edge-dark dark:bg-shell-dark lg:px-7">

                <!-- Hamburger (mobile) -->
                <button
                    class="rounded-md p-2 text-subtext transition-colors hover:bg-field hover:text-ink dark:text-subtext-dark dark:hover:bg-field-dark dark:hover:text-ink-dark lg:hidden"
                    @click="sidebarOpen = !sidebarOpen"
                >
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>

                <!-- Page title -->
                <h1 class="flex-1 text-[16px] font-bold text-ink dark:text-ink-dark">{{ title }}</h1>

                <!-- Controls -->
                <div class="flex items-center gap-3.5">

                    <!-- Dark mode toggle -->
                    <button
                        @click="themeStore.toggle"
                        :class="[
                            'flex h-[34px] w-[34px] items-center justify-center rounded-[9px] border border-edge bg-field transition-colors dark:border-edge-dark dark:bg-field-dark',
                            themeStore.isDark ? 'text-[#f4c95d]' : 'text-[#6c5ce7]',
                        ]"
                        :title="t('layout.header.theme_toggle')"
                    >
                        <svg v-if="themeStore.isDark" width="17" height="17" viewBox="0 0 24 24" fill="none"><path d="M12 3v1M12 20v1M4.2 4.2l.7.7M18.4 18.4l.7.7M3 12h1M20 12h1M4.2 19.8l.7-.7M18.4 5.6l.7-.7" stroke="currentColor" stroke-width="2" stroke-linecap="round" /><circle cx="12" cy="12" r="4.3" stroke="currentColor" stroke-width="2" /></svg>
                        <svg v-else width="17" height="17" viewBox="0 0 24 24" fill="none"><path d="M20.5 14.5A8.5 8.5 0 019.5 3.5 8.5 8.5 0 1020.5 14.5z" stroke="currentColor" stroke-width="2" stroke-linejoin="round" /></svg>
                    </button>

                    <!-- Language switcher -->
                    <div class="flex items-center gap-0.5 rounded-[9px] border border-edge bg-field p-0.5 dark:border-edge-dark dark:bg-field-dark">
                        <button
                            v-for="lang in ['tk', 'ru']"
                            :key="lang"
                            @click="localeStore.setLocale(lang)"
                            :class="[
                                'rounded-[7px] px-2.5 py-[5px] text-[11.5px] font-bold uppercase tracking-wide transition-colors',
                                localeStore.locale === lang
                                    ? 'bg-accent text-white'
                                    : 'text-subtext hover:text-ink dark:text-subtext-dark dark:hover:text-ink-dark',
                            ]"
                        >
                            {{ lang }}
                        </button>
                    </div>

                    <div class="h-6 w-px bg-edge dark:bg-edge-dark"></div>

                    <!-- User dropdown -->
                    <div class="relative">
                        <button
                            @click="userMenuOpen = !userMenuOpen"
                            class="flex items-center gap-2.5 rounded-md px-1.5 py-1 text-[13px] font-semibold text-ink transition-colors hover:bg-field dark:text-ink-dark dark:hover:bg-field-dark"
                        >
                            <div class="h-[34px] w-[34px] overflow-hidden rounded-full">
                                <img
                                    v-if="$page.props.auth.user?.avatar_url"
                                    :src="$page.props.auth.user.avatar_url"
                                    :alt="$page.props.auth.user.name"
                                    class="h-full w-full object-cover"
                                />
                                <div v-else class="flex h-full w-full items-center justify-center bg-accent text-xs font-bold text-white">
                                    {{ $page.props.auth.user?.name?.charAt(0)?.toUpperCase() }}
                                </div>
                            </div>
                            <span class="hidden sm:block max-w-32 truncate">{{ $page.props.auth.user?.name }}</span>
                            <svg class="h-4 w-4 text-fadetext dark:text-fadetext-dark" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                            </svg>
                        </button>

                        <Transition name="dropdown">
                            <div
                                v-if="userMenuOpen"
                                class="absolute right-0 top-full z-50 mt-1 w-48 rounded-lg border border-edge bg-card py-1 shadow-lg dark:border-edge-dark dark:bg-card-dark"
                            >
                                <Link
                                    :href="route('profile.edit')"
                                    @click="userMenuOpen = false"
                                    class="flex items-center gap-2 px-4 py-2 text-sm text-subtext hover:bg-field dark:text-subtext-dark dark:hover:bg-field-dark"
                                >
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                                    </svg>
                                    {{ t('layout.header.profile') }}
                                </Link>
                                <button
                                    @click="logout"
                                    class="flex w-full items-center gap-2 px-4 py-2 text-sm text-red-500 hover:bg-red-500/10"
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
