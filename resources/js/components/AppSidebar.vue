<script setup lang="ts">
/* import NavFooter from '@/components/NavFooter.vue'; */
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { NavGroup, type NavItem } from '@/types';
import { Link } from '@inertiajs/vue3';
import { CalendarCheck, ChartBarStacked, ChartBarStackedIcon, CircleGauge, ClipboardMinus, Package, Package2, PackageCheck, Soup } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

const mainNavGroups: NavGroup[] = [
    {
        title: 'Main',
        navItems: [
            {
                title: 'Dashboard',
                href: '/dashboard',
                icon: CircleGauge,
            },
            {
                title: 'Reservations',
                href: '/reservations',
                icon: CalendarCheck,
            },

            {
                title: 'Manage Catering',
                href: '/catering',
                icon: Soup,
            },
            {
                title: 'Manage Add-Ons',
                href: '',
                icon: ChartBarStacked,
            },
        ],
    },
    {
        title: 'Others',
        navItems: [
            {
                title: 'Reports',
                href: '/reports',
                icon: ClipboardMinus,
            },
        ],
    },
];


/* const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: CircleGauge,
    },
]; */


/* const footerNavItems: NavItem[] = [
    {
        title: 'Github Repo',
        href: 'https://github.com/laravel/vue-starter-kit',
        icon: Folder,
    },
    {
        title: 'Documentation',
        href: 'https://laravel.com/docs/starter-kits',
        icon: BookOpen,
    },
]; */
</script>

<template>
    <Sidebar collapsible="icon" variant="floating">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('show.dashboard.page')">
                        <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <!-- <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent> -->
        <SidebarContent>
            <NavMain :navGroups="mainNavGroups" />
        </SidebarContent>

        <SidebarFooter>
            <!-- <NavFooter :items="footerNavItems" /> -->
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
