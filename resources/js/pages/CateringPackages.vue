<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import CateringPackagesLayout from '../layouts/pages/CateringPackagesLayout.vue';
import HeadingSmall from '@/components/HeadingSmall.vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Catering Packages',
        href: '/catering/packages',
    }
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">

        <Head title="Catering Packages" />

        <CateringPackagesLayout>
            <div class="flex flex-col space-y-6">
                <HeadingSmall title="Packages" description="List of Packages that can be availed by the customer." />


                <p>Main Content</p>

            </div>
        </CateringPackagesLayout>

    </AppLayout>
</template>
