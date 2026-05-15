<script setup>
import { ref } from 'vue'
import { useI18n } from 'vue-i18n'
import { useLocalized } from '@/composables/useLocalized'
import { usePortfolioUnlock } from '@/composables/usePortfolioUnlock'
import PfProjectCarousel from './PfProjectCarousel.vue'
import PfProjectModal from './PfProjectModal.vue'
import PfPasswordModal from './PfPasswordModal.vue'

const props = defineProps({
    projects: { type: Array, required: true },
})

const { t } = useI18n()
const { pick } = useLocalized()

const { projects, isChecking, errorMessage, unlock } = usePortfolioUnlock(props.projects)

const showPasswordModal = ref(false)
const selectedProject = ref(null)

function openPasswordModal() {
    showPasswordModal.value = true
}

function handleCardClick(proj) {
    if (!proj.locked) selectedProject.value = proj
}

async function submitPassword(password, onFail) {
    const ok = await unlock(password)
    if (ok) {
        showPasswordModal.value = false
        return
    }
    onFail?.()
}

function localizedTitle(project) {
    return pick(project.title_translations, project.title)
}

function localizedDesc(project) {
    return pick(project.description_translations, project.description)
}

function projectUrl(project) {
    return project.web_url
        || project.play_store_url
        || project.app_store_url
        || project.desktop_url
        || null
}
</script>

<template>
    <section id="projects" class="pf-section pf-projects">
        <div class="pf-container">
            <div class="pf-sec-label">{{ t('portfolio_public.projects.label') }}</div>
            <h2 class="pf-sec-title">{{ t('portfolio_public.projects.title') }}</h2>

            <div v-if="!projects.length" class="pf-tl-desc">
                {{ t('portfolio_public.projects.empty') }}
            </div>

            <div v-else class="pf-proj-grid">
                <div
                    v-for="proj in projects"
                    :key="proj.id"
                    class="pf-proj-card"
                    :style="{ cursor: proj.locked ? 'default' : 'pointer' }"
                    @click="handleCardClick(proj)">

                    <PfProjectCarousel
                        :images="proj.images ?? []"
                        :thumb-label="proj.thumb_label || `[ ${localizedTitle(proj)} ]`"
                        :is-locked="proj.locked"
                        @lock-click="openPasswordModal" />

                    <div class="pf-proj-body">
                        <span
                            class="pf-proj-badge"
                            :class="proj.is_private ? 'pf-badge-priv' : 'pf-badge-pub'">
                            {{ proj.is_private ? t('portfolio_public.projects.private') : t('portfolio_public.projects.public') }}
                        </span>

                        <template v-if="!proj.locked">
                            <div class="pf-proj-title">{{ localizedTitle(proj) }}</div>
                            <div class="pf-proj-desc">{{ localizedDesc(proj) }}</div>
                            <div v-if="proj.tech_stack?.length" class="pf-tech-list">
                                <span
                                    v-for="tech in proj.tech_stack"
                                    :key="tech"
                                    class="pf-tech-pill">{{ tech }}</span>
                            </div>
                            <button
                                v-if="projectUrl(proj)"
                                type="button"
                                class="pf-proj-link"
                                style="background: none; border: none; padding: 0; cursor: pointer;"
                                @click.stop="selectedProject = proj">
                                {{ t('portfolio_public.projects.view') }}
                            </button>
                        </template>
                        <template v-else>
                            <div class="pf-proj-title">— — —</div>
                            <div class="pf-proj-desc">
                                {{ t('portfolio_public.projects.locked') }}
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>

        <PfPasswordModal
            v-if="showPasswordModal"
            :is-checking="isChecking"
            :error-message="errorMessage"
            @close="showPasswordModal = false"
            @submit="submitPassword" />

        <PfProjectModal
            v-if="selectedProject"
            :project="selectedProject"
            @close="selectedProject = null" />
    </section>
</template>
