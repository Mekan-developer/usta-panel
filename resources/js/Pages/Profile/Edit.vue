<script setup>
import { useI18n } from 'vue-i18n'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Head } from '@inertiajs/vue3'
import DeleteUserForm from './Partials/DeleteUserForm.vue'
import UpdatePasswordForm from './Partials/UpdatePasswordForm.vue'
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm.vue'

const { t } = useI18n()

defineProps({
    mustVerifyEmail: { type: Boolean },
    status: { type: String },
})
</script>

<template>
    <Head :title="t('profile.title')" />

    <AdminLayout :title="t('profile.title')">
        <div class="mx-auto grid max-w-[1080px] grid-cols-[repeat(auto-fit,minmax(360px,1fr))] items-start gap-[22px]">
            <UpdateProfileInformationForm :must-verify-email="mustVerifyEmail" :status="status" />
            <UpdatePasswordForm />
            <DeleteUserForm />
        </div>
    </AdminLayout>
</template>
