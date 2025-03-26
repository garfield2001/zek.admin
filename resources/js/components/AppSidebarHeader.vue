<script setup lang="ts">
import Breadcrumbs from '@/components/Breadcrumbs.vue';
import { SidebarTrigger } from '@/components/ui/sidebar';
import type { BreadcrumbItemType } from '@/types';

const props = withDefaults(defineProps<{
    breadcrumbs?: BreadcrumbItemType[];
}>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <header
        class="fixed top-0 right-0 left-[var(--sidebar-width)] z-40 flex h-16 shrink-0 items-center gap-2 border-b bg-background border-sidebar-border/70 px-6 transition-[width,height,left] ease-linear group-has-[[data-collapsible=icon]]/sidebar-wrapper:h-12 group-has-[[data-collapsible=icon]]/sidebar-wrapper:left-[var(--sidebar-width-collapsed)] md:px-4">
        <div class="flex items-center gap-2">
            <SidebarTrigger class="-ml-1" />
            <template v-if="props.breadcrumbs.length > 0">
                <Breadcrumbs :breadcrumbs="props.breadcrumbs" />
            </template>
        </div>
    </header>
</template>
