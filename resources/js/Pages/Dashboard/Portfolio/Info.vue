<script setup>
import { ref } from 'vue'
import { useI18n } from 'vue-i18n'
import { useForm, router, Head } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'

const { t } = useI18n()

const props = defineProps({
    hero: { type: Object, required: true },
    contacts: { type: Object, required: true },
    socials: { type: Object, required: true },
    skills: { type: Array, default: () => [] },
    experiences: { type: Array, default: () => [] },
})

// ─── Hero form ───────────────────────────────────────────────────────────────
const heroLang = ref('ru')

const heroForm = useForm({
    name: props.hero.name,
    role: { tk: props.hero.role.tk, ru: props.hero.role.ru, en: props.hero.role.en },
    bio: { tk: props.hero.bio.tk, ru: props.hero.bio.ru, en: props.hero.bio.en },
    email: props.contacts.email,
    phone: props.contacts.phone,
    location: { tk: props.contacts.location.tk, ru: props.contacts.location.ru, en: props.contacts.location.en },
    github: props.socials.github,
    linkedin: props.socials.linkedin,
    twitter: props.socials.twitter,
})

function submitHero() {
    heroForm.put(route('dashboard.portfolio.info.update-hero'))
}

// ─── Skill CRUD ───────────────────────────────────────────────────────────────
const showSkillModal = ref(false)
const editingSkillId = ref(null)

const skillForm = useForm({
    name: '',
    abbr: '',
    category: 'frontend',
    percent: 80,
    sort_order: 0,
})

const skillCategories = ['frontend', 'backend', 'tools']

function openAddSkill() {
    skillForm.reset()
    skillForm.category = 'frontend'
    skillForm.percent = 80
    skillForm.sort_order = 0
    editingSkillId.value = null
    showSkillModal.value = true
}

function openEditSkill(skill) {
    skillForm.name = skill.name
    skillForm.abbr = skill.abbr
    skillForm.category = typeof skill.category === 'object' ? skill.category.value ?? skill.category : skill.category
    skillForm.percent = skill.percent
    skillForm.sort_order = skill.sort_order
    editingSkillId.value = skill.id
    showSkillModal.value = true
}

function submitSkill() {
    if (editingSkillId.value) {
        skillForm.put(route('dashboard.portfolio.info.skills.update', editingSkillId.value), {
            onSuccess: () => { showSkillModal.value = false },
        })
    } else {
        skillForm.post(route('dashboard.portfolio.info.skills.store'), {
            onSuccess: () => { showSkillModal.value = false },
        })
    }
}

function deleteSkill(skill) {
    if (!confirm(t('portfolio.info.confirm_delete'))) { return }
    router.delete(route('dashboard.portfolio.info.skills.destroy', skill.id))
}

// ─── Experience CRUD ─────────────────────────────────────────────────────────
const showExpModal = ref(false)
const editingExpId = ref(null)
const expLang = ref('ru')

const expForm = useForm({
    company: '',
    duration: '',
    role: { tk: '', ru: '', en: '' },
    description: { tk: '', ru: '', en: '' },
    sort_order: 0,
})

function openAddExp() {
    expForm.reset()
    expForm.sort_order = 0
    expForm.role = { tk: '', ru: '', en: '' }
    expForm.description = { tk: '', ru: '', en: '' }
    editingExpId.value = null
    expLang.value = 'ru'
    showExpModal.value = true
}

function openEditExp(exp) {
    expForm.company = exp.company
    expForm.duration = exp.duration
    expForm.role = { tk: exp.role?.tk ?? '', ru: exp.role?.ru ?? '', en: exp.role?.en ?? '' }
    expForm.description = { tk: exp.description?.tk ?? '', ru: exp.description?.ru ?? '', en: exp.description?.en ?? '' }
    expForm.sort_order = exp.sort_order
    editingExpId.value = exp.id
    expLang.value = 'ru'
    showExpModal.value = true
}

function submitExp() {
    if (editingExpId.value) {
        expForm.put(route('dashboard.portfolio.info.experiences.update', editingExpId.value), {
            onSuccess: () => { showExpModal.value = false },
        })
    } else {
        expForm.post(route('dashboard.portfolio.info.experiences.store'), {
            onSuccess: () => { showExpModal.value = false },
        })
    }
}

function deleteExp(exp) {
    if (!confirm(t('portfolio.info.confirm_delete'))) { return }
    router.delete(route('dashboard.portfolio.info.experiences.destroy', exp.id))
}

// ─── Helpers ─────────────────────────────────────────────────────────────────
function skillCategoryLabel(cat) {
    const val = typeof cat === 'object' ? cat.value ?? cat : cat
    return t(`portfolio.info.skill.categories.${val}`)
}

function expRoleLabel(exp) {
    return exp.role?.ru || exp.role?.tk || exp.role?.en || '—'
}
</script>

<template>
    <Head :title="t('portfolio.info.title')" />

    <AdminLayout :title="t('portfolio.info.title')">
        <div class="space-y-6">

            <!-- ── Hero & Contacts form ─────────────────────────────────── -->
            <div class="rounded-xl bg-white p-6 shadow-sm dark:bg-slate-800">
                <div class="mb-5 flex items-center justify-between">
                    <h2 class="text-base font-semibold text-gray-900 dark:text-white">
                        {{ t('portfolio.info.section_hero') }}
                    </h2>
                    <!-- Lang tabs -->
                    <div class="flex gap-0.5 rounded-md border border-gray-200 p-0.5 dark:border-slate-700">
                        <button
                            v-for="lang in ['tk', 'ru', 'en']"
                            :key="lang"
                            type="button"
                            @click="heroLang = lang"
                            :class="[
                                'rounded px-2.5 py-1 text-xs font-semibold uppercase transition-colors',
                                heroLang === lang
                                    ? 'bg-indigo-600 text-white'
                                    : 'text-gray-500 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white',
                            ]"
                        >{{ lang }}</button>
                    </div>
                </div>

                <form @submit.prevent="submitHero" class="space-y-4">
                    <!-- Name -->
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-slate-300">
                            {{ t('portfolio.info.fields.name') }}
                        </label>
                        <input
                            v-model="heroForm.name"
                            type="text"
                            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white"
                        />
                        <p v-if="heroForm.errors.name" class="mt-1 text-xs text-red-500">{{ heroForm.errors.name }}</p>
                    </div>

                    <!-- Role -->
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-slate-300">
                            {{ t('portfolio.info.fields.role') }} [{{ heroLang.toUpperCase() }}]
                        </label>
                        <input
                            v-model="heroForm.role[heroLang]"
                            type="text"
                            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white"
                        />
                        <p v-if="heroForm.errors['role.tk'] || heroForm.errors['role.ru']" class="mt-1 text-xs text-red-500">
                            {{ heroForm.errors['role.tk'] || heroForm.errors['role.ru'] }}
                        </p>
                    </div>

                    <!-- Bio -->
                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-slate-300">
                            {{ t('portfolio.info.fields.bio') }} [{{ heroLang.toUpperCase() }}]
                        </label>
                        <textarea
                            v-model="heroForm.bio[heroLang]"
                            rows="3"
                            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white"
                        />
                    </div>

                    <hr class="border-gray-100 dark:border-slate-700" />
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-slate-500">
                        {{ t('portfolio.info.section_contacts') }}
                    </p>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-slate-300">{{ t('portfolio.info.fields.email') }}</label>
                            <input v-model="heroForm.email" type="email" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white" />
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-slate-300">{{ t('portfolio.info.fields.phone') }}</label>
                            <input v-model="heroForm.phone" type="text" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white" />
                        </div>
                    </div>

                    <div>
                        <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-slate-300">
                            {{ t('portfolio.info.fields.location') }} [{{ heroLang.toUpperCase() }}]
                        </label>
                        <input
                            v-model="heroForm.location[heroLang]"
                            type="text"
                            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white"
                        />
                    </div>

                    <hr class="border-gray-100 dark:border-slate-700" />
                    <p class="text-xs font-semibold uppercase tracking-wide text-gray-400 dark:text-slate-500">
                        {{ t('portfolio.info.section_socials') }}
                    </p>

                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-slate-300">{{ t('portfolio.info.fields.github') }}</label>
                            <input v-model="heroForm.github" type="url" placeholder="https://github.com/..." class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white dark:placeholder-slate-500" />
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-slate-300">{{ t('portfolio.info.fields.linkedin') }}</label>
                            <input v-model="heroForm.linkedin" type="url" placeholder="https://linkedin.com/..." class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white dark:placeholder-slate-500" />
                        </div>
                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-slate-300">{{ t('portfolio.info.fields.twitter') }}</label>
                            <input v-model="heroForm.twitter" type="url" placeholder="https://twitter.com/..." class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white dark:placeholder-slate-500" />
                        </div>
                    </div>

                    <div class="flex justify-end border-t border-gray-100 pt-4 dark:border-slate-700">
                        <button
                            type="submit"
                            :disabled="heroForm.processing"
                            class="rounded-lg bg-indigo-600 px-5 py-2 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-60"
                        >
                            {{ heroForm.processing ? '...' : t('portfolio.info.save') }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- ── Skills ──────────────────────────────────────────────── -->
            <div class="rounded-xl bg-white p-6 shadow-sm dark:bg-slate-800">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-base font-semibold text-gray-900 dark:text-white">
                        {{ t('portfolio.info.section_skills') }}
                        <span class="ml-1.5 text-sm font-normal text-gray-400 dark:text-slate-500">({{ skills.length }})</span>
                    </h2>
                    <button
                        type="button"
                        @click="openAddSkill"
                        class="inline-flex items-center gap-1.5 rounded-lg bg-indigo-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-indigo-700"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        {{ t('portfolio.info.add_skill') }}
                    </button>
                </div>

                <p v-if="!skills.length" class="py-6 text-center text-sm text-gray-400 dark:text-slate-500">—</p>

                <div v-else class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b border-gray-100 text-xs uppercase text-gray-400 dark:border-slate-700 dark:text-slate-500">
                                <th class="pb-2 pr-4">{{ t('portfolio.info.skill.name') }}</th>
                                <th class="pb-2 pr-4">{{ t('portfolio.info.skill.abbr') }}</th>
                                <th class="pb-2 pr-4">{{ t('portfolio.info.skill.category') }}</th>
                                <th class="pb-2 pr-4">{{ t('portfolio.info.skill.percent') }}</th>
                                <th class="pb-2 pr-4">{{ t('portfolio.info.skill.sort_order') }}</th>
                                <th class="pb-2" />
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50 dark:divide-slate-700/50">
                            <tr v-for="skill in skills" :key="skill.id" class="group">
                                <td class="py-2.5 pr-4 font-medium text-gray-900 dark:text-white">{{ skill.name }}</td>
                                <td class="py-2.5 pr-4 text-gray-500 dark:text-slate-400">{{ skill.abbr }}</td>
                                <td class="py-2.5 pr-4 text-gray-500 dark:text-slate-400">{{ skillCategoryLabel(skill.category) }}</td>
                                <td class="py-2.5 pr-4">
                                    <div class="flex items-center gap-2">
                                        <div class="h-1.5 w-20 rounded-full bg-gray-100 dark:bg-slate-700">
                                            <div
                                                class="h-1.5 rounded-full bg-indigo-500"
                                                :style="{ width: `${skill.percent}%` }"
                                            />
                                        </div>
                                        <span class="text-xs text-gray-400 dark:text-slate-500">{{ skill.percent }}%</span>
                                    </div>
                                </td>
                                <td class="py-2.5 pr-4 text-gray-400 dark:text-slate-500">{{ skill.sort_order }}</td>
                                <td class="py-2.5">
                                    <div class="flex items-center gap-1 opacity-0 group-hover:opacity-100">
                                        <button
                                            type="button"
                                            @click="openEditSkill(skill)"
                                            class="rounded-md p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-700 dark:hover:bg-slate-700 dark:hover:text-white"
                                        >
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                                            </svg>
                                        </button>
                                        <button
                                            type="button"
                                            @click="deleteSkill(skill)"
                                            class="rounded-md p-1.5 text-gray-400 hover:bg-red-50 hover:text-red-600 dark:hover:bg-red-900/20 dark:hover:text-red-400"
                                        >
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- ── Experiences ──────────────────────────────────────────── -->
            <div class="rounded-xl bg-white p-6 shadow-sm dark:bg-slate-800">
                <div class="mb-4 flex items-center justify-between">
                    <h2 class="text-base font-semibold text-gray-900 dark:text-white">
                        {{ t('portfolio.info.section_experiences') }}
                        <span class="ml-1.5 text-sm font-normal text-gray-400 dark:text-slate-500">({{ experiences.length }})</span>
                    </h2>
                    <button
                        type="button"
                        @click="openAddExp"
                        class="inline-flex items-center gap-1.5 rounded-lg bg-indigo-600 px-3 py-1.5 text-sm font-medium text-white hover:bg-indigo-700"
                    >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        {{ t('portfolio.info.add_experience') }}
                    </button>
                </div>

                <p v-if="!experiences.length" class="py-6 text-center text-sm text-gray-400 dark:text-slate-500">—</p>

                <div v-else class="space-y-2">
                    <div
                        v-for="exp in experiences"
                        :key="exp.id"
                        class="group flex items-center justify-between rounded-lg border border-gray-100 px-4 py-3 dark:border-slate-700"
                    >
                        <div class="min-w-0">
                            <p class="truncate font-medium text-gray-900 dark:text-white">{{ exp.company }}</p>
                            <p class="text-xs text-gray-400 dark:text-slate-500">
                                {{ exp.duration }} · {{ expRoleLabel(exp) }}
                            </p>
                        </div>
                        <div class="ml-4 flex shrink-0 items-center gap-1 opacity-0 group-hover:opacity-100">
                            <button
                                type="button"
                                @click="openEditExp(exp)"
                                class="rounded-md p-1.5 text-gray-400 hover:bg-gray-100 hover:text-gray-700 dark:hover:bg-slate-700 dark:hover:text-white"
                            >
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931z" />
                                </svg>
                            </button>
                            <button
                                type="button"
                                @click="deleteExp(exp)"
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

        <!-- ── Skill Modal ──────────────────────────────────────────────── -->
        <Teleport to="body">
            <div
                v-if="showSkillModal"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4"
                @click.self="showSkillModal = false"
            >
                <div class="w-full max-w-md rounded-xl bg-white p-6 shadow-xl dark:bg-slate-800">
                    <h3 class="mb-4 text-base font-semibold text-gray-900 dark:text-white">
                        {{ editingSkillId ? t('portfolio.info.edit_skill') : t('portfolio.info.add_skill') }}
                    </h3>

                    <form @submit.prevent="submitSkill" class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-slate-300">{{ t('portfolio.info.skill.name') }}</label>
                                <input v-model="skillForm.name" type="text" required class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white" />
                                <p v-if="skillForm.errors.name" class="mt-1 text-xs text-red-500">{{ skillForm.errors.name }}</p>
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-slate-300">{{ t('portfolio.info.skill.abbr') }}</label>
                                <input v-model="skillForm.abbr" type="text" maxlength="4" required class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white" />
                                <p v-if="skillForm.errors.abbr" class="mt-1 text-xs text-red-500">{{ skillForm.errors.abbr }}</p>
                            </div>
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-slate-300">{{ t('portfolio.info.skill.category') }}</label>
                            <select v-model="skillForm.category" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white">
                                <option v-for="cat in skillCategories" :key="cat" :value="cat">{{ t(`portfolio.info.skill.categories.${cat}`) }}</option>
                            </select>
                            <p v-if="skillForm.errors.category" class="mt-1 text-xs text-red-500">{{ skillForm.errors.category }}</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-slate-300">{{ t('portfolio.info.skill.percent') }}</label>
                                <input v-model.number="skillForm.percent" type="number" min="1" max="100" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white" />
                                <p v-if="skillForm.errors.percent" class="mt-1 text-xs text-red-500">{{ skillForm.errors.percent }}</p>
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-slate-300">{{ t('portfolio.info.skill.sort_order') }}</label>
                                <input v-model.number="skillForm.sort_order" type="number" min="0" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-3 border-t border-gray-100 pt-4 dark:border-slate-700">
                            <button type="button" @click="showSkillModal = false" class="rounded-lg px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100 dark:text-slate-400 dark:hover:bg-slate-700">
                                {{ t('portfolio.actions.cancel') }}
                            </button>
                            <button type="submit" :disabled="skillForm.processing" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-60">
                                {{ skillForm.processing ? '...' : t('portfolio.info.save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </Teleport>

        <!-- ── Experience Modal ─────────────────────────────────────────── -->
        <Teleport to="body">
            <div
                v-if="showExpModal"
                class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 p-4"
                @click.self="showExpModal = false"
            >
                <div class="w-full max-w-lg rounded-xl bg-white p-6 shadow-xl dark:bg-slate-800">
                    <div class="mb-4 flex items-center justify-between">
                        <h3 class="text-base font-semibold text-gray-900 dark:text-white">
                            {{ editingExpId ? t('portfolio.info.edit_experience') : t('portfolio.info.add_experience') }}
                        </h3>
                        <div class="flex gap-0.5 rounded-md border border-gray-200 p-0.5 dark:border-slate-700">
                            <button
                                v-for="lang in ['tk', 'ru', 'en']"
                                :key="lang"
                                type="button"
                                @click="expLang = lang"
                                :class="[
                                    'rounded px-2.5 py-1 text-xs font-semibold uppercase transition-colors',
                                    expLang === lang ? 'bg-indigo-600 text-white' : 'text-gray-500 hover:text-gray-900 dark:text-slate-400 dark:hover:text-white',
                                ]"
                            >{{ lang }}</button>
                        </div>
                    </div>

                    <form @submit.prevent="submitExp" class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-slate-300">{{ t('portfolio.info.experience.company') }}</label>
                                <input v-model="expForm.company" type="text" required class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white" />
                                <p v-if="expForm.errors.company" class="mt-1 text-xs text-red-500">{{ expForm.errors.company }}</p>
                            </div>
                            <div>
                                <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-slate-300">{{ t('portfolio.info.experience.duration') }}</label>
                                <input v-model="expForm.duration" type="text" placeholder="2022 — 2024" required class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 placeholder-gray-400 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white dark:placeholder-slate-500" />
                                <p v-if="expForm.errors.duration" class="mt-1 text-xs text-red-500">{{ expForm.errors.duration }}</p>
                            </div>
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-slate-300">
                                {{ t('portfolio.info.experience.role') }} [{{ expLang.toUpperCase() }}]
                            </label>
                            <input v-model="expForm.role[expLang]" type="text" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white" />
                            <p v-if="expForm.errors['role.tk'] || expForm.errors['role.ru']" class="mt-1 text-xs text-red-500">
                                {{ expForm.errors['role.tk'] || expForm.errors['role.ru'] }}
                            </p>
                        </div>

                        <div>
                            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-slate-300">
                                {{ t('portfolio.info.experience.description') }} [{{ expLang.toUpperCase() }}]
                            </label>
                            <textarea v-model="expForm.description[expLang]" rows="3" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white" />
                            <p v-if="expForm.errors['description.tk'] || expForm.errors['description.ru']" class="mt-1 text-xs text-red-500">
                                {{ expForm.errors['description.tk'] || expForm.errors['description.ru'] }}
                            </p>
                        </div>

                        <div class="w-28">
                            <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-slate-300">{{ t('portfolio.info.experience.sort_order') }}</label>
                            <input v-model.number="expForm.sort_order" type="number" min="0" class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2 text-sm text-gray-900 focus:border-indigo-500 focus:outline-none focus:ring-1 focus:ring-indigo-500 dark:border-slate-600 dark:bg-slate-700 dark:text-white" />
                        </div>

                        <div class="flex items-center justify-end gap-3 border-t border-gray-100 pt-4 dark:border-slate-700">
                            <button type="button" @click="showExpModal = false" class="rounded-lg px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100 dark:text-slate-400 dark:hover:bg-slate-700">
                                {{ t('portfolio.actions.cancel') }}
                            </button>
                            <button type="submit" :disabled="expForm.processing" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 disabled:opacity-60">
                                {{ expForm.processing ? '...' : t('portfolio.info.save') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </Teleport>
    </AdminLayout>
</template>
