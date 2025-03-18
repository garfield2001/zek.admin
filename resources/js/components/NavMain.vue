<script setup lang="ts">
import { SidebarGroup, SidebarGroupLabel, SidebarMenu, SidebarMenuButton, SidebarMenuItem, useSidebar } from '@/components/ui/sidebar';
import { type NavGroup, type SharedData } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';

defineProps<{
    navGroups: NavGroup[];
}>();

const page = usePage<SharedData>();
const state = useSidebar();



/* For dropdown

import { ref } from 'vue';
const openGroups = ref<{ [key: string]: boolean }>({});

// Toggle function
const toggleDropdown = (groupTitle: string) => {
    openGroups.value[groupTitle] = !openGroups.value[groupTitle];
};

// Check if dropdown is open
const isOpen = (groupTitle: string) => {
    return openGroups.value[groupTitle];
}; */







</script>

<template>
    <SidebarGroup class="px-2 py-0" v-for="navGroup in navGroups" :key="navGroup.title">
        <SidebarGroupLabel>{{ navGroup.title }}</SidebarGroupLabel>
        <SidebarMenu :class="state.state.value === 'collapsed' ? '' : 'px-2'">
            <SidebarMenuItem v-for="navItem in navGroup.navItems" :key="navItem.title">
                <SidebarMenuButton as-child :is-active="navItem.href === page.url">
                    <Link :href="navItem.href">
                    <component :is="navItem.icon" />
                    <span>{{ navItem.title }}</span>
                    </Link>
                </SidebarMenuButton>
            </SidebarMenuItem>
        </SidebarMenu>
    </SidebarGroup>

    <!-- with dropdown
        <div v-for="navGroup in navGroups" :key="navGroup.title" class="px-2 py-0">
        Dropdown Toggle
        <SidebarGroupLabel @click="toggleDropdown(navGroup.title)"
            class="cursor-pointer flex justify-between items-center">
            <span>{{ navGroup.title }}</span>
            <span>{{ isOpen(navGroup.title) ? '▲' : '▼' }}</span> Indicator
        </SidebarGroupLabel>

        Dropdown Items
        <SidebarMenu v-show="isOpen(navGroup.title)">
            <SidebarMenuItem v-for="navItem in navGroup.navItems" :key="navItem.title">
                <SidebarMenuButton as-child :is-active="navItem.href === page.url">
                    <Link :href="navItem.href">
                    <component :is="navItem.icon" />
                    <span>{{ navItem.title }}</span>
                    </Link>
                </SidebarMenuButton>
            </SidebarMenuItem>
        </SidebarMenu>
    </div> -->


</template>
