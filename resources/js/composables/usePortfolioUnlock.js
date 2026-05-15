import { ref } from 'vue'

const STORAGE_KEY = 'pf_unlocked_session'

/**
 * Хранит факт разблокировки приватных проектов на текущей сессии,
 * и обращается к POST /portfolio/unlock для проверки пароля.
 */
export function usePortfolioUnlock(initialProjects = []) {
    const projects = ref([...initialProjects])
    const isUnlocked = ref(loadFlag())
    const isChecking = ref(false)
    const errorMessage = ref('')

    function loadFlag() {
        try {
            return sessionStorage.getItem(STORAGE_KEY) === '1'
        } catch {
            return false
        }
    }

    function persistFlag(value) {
        try {
            if (value) {
                sessionStorage.setItem(STORAGE_KEY, '1')
            } else {
                sessionStorage.removeItem(STORAGE_KEY)
            }
        } catch {
            /* ignore */
        }
    }

    function mergeUnlocked(unlockedList) {
        const byId = new Map(projects.value.map((p) => [p.id, p]))
        for (const item of unlockedList) {
            byId.set(item.id, { ...item, locked: false })
        }
        projects.value = [...byId.values()].sort((a, b) => a.id - b.id)
    }

    async function unlock(password) {
        errorMessage.value = ''
        isChecking.value = true
        try {
            const { data } = await window.axios.post(route('portfolio.unlock'), { password })
            if (data?.ok) {
                mergeUnlocked(data.projects ?? [])
                isUnlocked.value = true
                persistFlag(true)
                return true
            }
            errorMessage.value = data?.message ?? ''
            return false
        } catch (e) {
            errorMessage.value = e?.response?.data?.message ?? ''
            return false
        } finally {
            isChecking.value = false
        }
    }

    function reset() {
        isUnlocked.value = false
        persistFlag(false)
    }

    return {
        projects,
        isUnlocked,
        isChecking,
        errorMessage,
        unlock,
        reset,
    }
}
