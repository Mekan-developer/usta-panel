<script setup>
import { useI18n } from 'vue-i18n'
import { useForm, Link, router, Head } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const { t } = useI18n()

const props = defineProps({
    server: { type: Object, required: true },
})

const form = useForm({
    name: props.server.name,
    url: props.server.url,
    token: '',
})

function submit() {
    form.put(route('dashboard.servers.update', props.server.id))
}

function confirmDelete() {
    if (confirm(t('servers.confirm_delete'))) {
        router.delete(route('dashboard.servers.destroy', props.server.id))
    }
}
</script>

<template>
    <Head :title="t('servers.edit.title')" />

    <AdminLayout :title="t('servers.edit.title')">
        <div class="mx-auto max-w-xl">
            <div class="rounded-xl bg-white p-6 shadow-sm dark:bg-slate-800">
                <form @submit.prevent="submit" class="space-y-5">
                    <!-- Name -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-slate-300">
                            {{ t('servers.fields.name') }}
                        </label>
                        <input
                            v-model="form.name"
                            type="text"
                            required
                            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white dark:placeholder-slate-500"
                        />
                        <p v-if="form.errors.name" class="mt-1 text-xs text-red-500">{{ form.errors.name }}</p>
                    </div>

                    <!-- URL -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-slate-300">
                            {{ t('servers.fields.url') }}
                        </label>
                        <input
                            v-model="form.url"
                            type="url"
                            required
                            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white dark:placeholder-slate-500"
                        />
                        <p v-if="form.errors.url" class="mt-1 text-xs text-red-500">{{ form.errors.url }}</p>
                    </div>

                    <!-- Token -->
                    <div>
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-slate-300">
                            {{ t('servers.fields.token') }}
                        </label>
                        <input
                            v-model="form.token"
                            type="password"
                            :placeholder="t('servers.fields.token_placeholder')"
                            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white dark:placeholder-slate-500"
                        />
                        <p v-if="form.errors.token" class="mt-1 text-xs text-red-500">{{ form.errors.token }}</p>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-between border-t border-gray-100 pt-4 dark:border-slate-700">
                        <button
                            type="button"
                            @click="confirmDelete"
                            class="rounded-lg px-4 py-2 text-sm font-medium text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/20"
                        >
                            {{ t('servers.actions.delete') }}
                        </button>
                        <div class="flex items-center gap-3">
                            <Link
                                :href="route('dashboard.servers.index')"
                                class="rounded-lg px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100 dark:text-slate-400 dark:hover:bg-slate-700"
                            >
                                {{ t('servers.actions.cancel') }}
                            </Link>
                            <button
                                type="submit"
                                :disabled="form.processing"
                                class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-60"
                            >
                                {{ t('servers.actions.save') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
