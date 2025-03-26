<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { CalendarCheck, Package2, Soup, Users } from 'lucide-vue-next';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

// Mock data - Replace with actual data from your backend
const stats = {
    totalReservations: 156,
    activeReservations: 23,
    totalPackages: 12,
    totalDishes: 45,
    upcomingEvents: [
        { date: '2024-03-28', client: 'John Doe', package: 'Wedding Package', guests: 100 },
        { date: '2024-03-30', client: 'Jane Smith', package: 'Corporate Event', guests: 50 },
        { date: '2024-04-02', client: 'Mike Johnson', package: 'Birthday Celebration', guests: 75 },
    ],
};
</script>

<template>

    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl px-4 py-6">
            <h2 class="text-xl font-semibold tracking-tight">Dashboard Overview</h2>

            <!-- Stats Grid -->
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Total Reservations</CardTitle>
                        <CalendarCheck class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats.totalReservations }}</div>
                        <p class="text-xs text-muted-foreground">
                            {{ stats.activeReservations }} active reservations
                        </p>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Catering Packages</CardTitle>
                        <Package2 class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats.totalPackages }}</div>
                        <p class="text-xs text-muted-foreground">
                            Available packages
                        </p>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Total Dishes</CardTitle>
                        <Soup class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">{{ stats.totalDishes }}</div>
                        <p class="text-xs text-muted-foreground">
                            Menu items
                        </p>
                    </CardContent>
                </Card>

                <Card>
                    <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                        <CardTitle class="text-sm font-medium">Total Guests</CardTitle>
                        <Users class="h-4 w-4 text-muted-foreground" />
                    </CardHeader>
                    <CardContent>
                        <div class="text-2xl font-bold">2,450</div>
                        <p class="text-xs text-muted-foreground">
                            This month
                        </p>
                    </CardContent>
                </Card>
            </div>

            <!-- Upcoming Events -->
            <Card class="col-span-4">
                <CardHeader>
                    <CardTitle>Upcoming Events</CardTitle>
                </CardHeader>
                <CardContent>
                    <div class="space-y-4">
                        <div v-for="event in stats.upcomingEvents" :key="event.date"
                            class="flex items-center justify-between rounded-lg border p-4">
                            <div class="space-y-1">
                                <p class="text-sm font-medium">{{ event.client }}</p>
                                <p class="text-sm text-muted-foreground">{{ event.package }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-medium">{{ event.date }}</p>
                                <p class="text-sm text-muted-foreground">{{ event.guests }} guests</p>
                            </div>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>
