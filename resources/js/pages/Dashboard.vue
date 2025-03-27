<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { CalendarCheck, DollarSign, Users, TrendingUp, ArrowRight, LineChart } from 'lucide-vue-next';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Line } from 'vue-chartjs';
import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend
} from 'chart.js';
import {
    Tabs,
    TabsContent,
    TabsList,
    TabsTrigger,
} from '@/components/ui/tabs';

ChartJS.register(
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Legend
);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

// Mock data - Replace with actual data from your backend
const stats = {
    totalReservations: 156, // All-time total
    activeReservations: 23, // Current month's active reservations
    monthlyRevenue: 45600, // Current month's revenue
    monthlyGuests: 2450, // Current month's total guests
    upcomingEvents: [
        { date: '2024-03-28', client: 'John Doe', package: 'Wedding Package', guests: 100 },
        { date: '2024-03-30', client: 'Jane Smith', package: 'Corporate Event', guests: 50 },
        { date: '2024-04-02', client: 'Mike Johnson', package: 'Birthday Celebration', guests: 75 },
    ],
    revenueTrend: (() => {
        const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
        const currentDate = new Date();
        const currentMonth = currentDate.getMonth();
        const trend = [];

        // Get last 6 months including current month
        for (let i = 5; i >= 0; i--) {
            const monthIndex = (currentMonth - i + 12) % 12;
            trend.push({
                month: months[monthIndex],
                revenue: Math.floor(Math.random() * (52000 - 35000) + 35000) // Mock data - replace with actual data
            });
        }

        return trend;
    })()
};

// Chart data
const chartData = {
    labels: stats.revenueTrend.map(item => item.month),
    datasets: [
        {
            label: 'Monthly Revenue',
            data: stats.revenueTrend.map(item => item.revenue),
            borderColor: '#22c55e',
            backgroundColor: 'rgba(34, 197, 94, 0.1)',
            tension: 0.4,
            fill: true,
        }
    ]
};

// Chart options
const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            display: false
        }
    },
    scales: {
        y: {
            beginAtZero: true,
            grid: {
                display: true,
                color: 'rgba(0, 0, 0, 0.05)'
            },
            ticks: {
                callback: (value: number) => `$${value.toLocaleString()}`
            }
        },
        x: {
            grid: {
                display: false
            }
        }
    }
};
</script>

<template>

    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6">
            <!-- Welcome Section -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight">Welcome back!</h1>
                    <p class="text-muted-foreground">Here's what's happening with your catering business today.</p>
                </div>
                <Button variant="outline" class="gap-2">
                    View Reports
                    <ArrowRight class="h-4 w-4" />
                </Button>
            </div>

            <!-- Navigation Tabs -->
            <Tabs defaultValue="overview" class="w-full">
                <TabsList class="w-full justify-start h-10">
                    <TabsTrigger value="overview" class="text-sm font-medium">Overview</TabsTrigger>
                    <TabsTrigger value="analytics" class="text-sm font-medium">Analytics</TabsTrigger>
                    <TabsTrigger value="performance" class="text-sm font-medium">Performance</TabsTrigger>
                </TabsList>

                <!-- Overview Tab -->
                <TabsContent value="overview" class="mt-6">
                    <!-- Stats Grid -->
                    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                        <Card
                            class="relative overflow-hidden border-none bg-gradient-to-br from-primary/10 to-primary/5">
                            <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                                <CardTitle class="text-sm font-medium text-primary">Total Reservations</CardTitle>
                                <CalendarCheck class="h-4 w-4 text-primary" />
                            </CardHeader>
                            <CardContent>
                                <div class="text-3xl font-bold">{{ stats.totalReservations }}</div>
                                <div class="flex items-center gap-1 text-sm text-muted-foreground">
                                    <TrendingUp class="h-4 w-4 text-green-500" />
                                    <span>+12% from last month</span>
                                </div>
                                <p class="mt-2 text-xs text-muted-foreground">
                                    All-time total
                                </p>
                            </CardContent>
                        </Card>

                        <Card
                            class="relative overflow-hidden border-none bg-gradient-to-br from-green-500/10 to-green-500/5">
                            <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                                <CardTitle class="text-sm font-medium text-green-500">Monthly Revenue</CardTitle>
                                <DollarSign class="h-4 w-4 text-green-500" />
                            </CardHeader>
                            <CardContent>
                                <div class="text-3xl font-bold">${{ stats.monthlyRevenue.toLocaleString() }}</div>
                                <div class="flex items-center gap-1 text-sm text-muted-foreground">
                                    <TrendingUp class="h-4 w-4 text-green-500" />
                                    <span>+15% from last month</span>
                                </div>
                                <p class="mt-2 text-xs text-muted-foreground">
                                    This month's earnings
                                </p>
                            </CardContent>
                        </Card>

                        <Card
                            class="relative overflow-hidden border-none bg-gradient-to-br from-blue-500/10 to-blue-500/5">
                            <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                                <CardTitle class="text-sm font-medium text-blue-500">Active Reservations</CardTitle>
                                <CalendarCheck class="h-4 w-4 text-blue-500" />
                            </CardHeader>
                            <CardContent>
                                <div class="text-3xl font-bold">{{ stats.activeReservations }}</div>
                                <div class="flex items-center gap-1 text-sm text-muted-foreground">
                                    <TrendingUp class="h-4 w-4 text-green-500" />
                                    <span>+8% from last month</span>
                                </div>
                                <p class="mt-2 text-xs text-muted-foreground">
                                    This month's bookings
                                </p>
                            </CardContent>
                        </Card>

                        <Card
                            class="relative overflow-hidden border-none bg-gradient-to-br from-purple-500/10 to-purple-500/5">
                            <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                                <CardTitle class="text-sm font-medium text-purple-500">Monthly Guests</CardTitle>
                                <Users class="h-4 w-4 text-purple-500" />
                            </CardHeader>
                            <CardContent>
                                <div class="text-3xl font-bold">{{ stats.monthlyGuests }}</div>
                                <div class="flex items-center gap-1 text-sm text-muted-foreground">
                                    <TrendingUp class="h-4 w-4 text-green-500" />
                                    <span>+8% from last month</span>
                                </div>
                                <p class="mt-2 text-xs text-muted-foreground">
                                    This month's total
                                </p>
                            </CardContent>
                        </Card>
                    </div>

                    <!-- Main Content Grid -->
                    <div class="grid gap-6 lg:grid-cols-2 mt-6">
                        <!-- Upcoming Events -->
                        <Card class="col-span-1">
                            <CardHeader class="flex flex-row items-center justify-between">
                                <CardTitle>Upcoming Events</CardTitle>
                                <Button variant="ghost" size="sm" class="gap-2">
                                    View All
                                    <ArrowRight class="h-4 w-4" />
                                </Button>
                            </CardHeader>
                            <CardContent>
                                <div class="space-y-4">
                                    <div v-for="event in stats.upcomingEvents" :key="event.date"
                                        class="flex items-center justify-between rounded-lg border p-4 transition-colors hover:bg-muted/50">
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

                        <!-- Revenue Trend -->
                        <Card class="col-span-1">
                            <CardHeader class="flex flex-row items-center justify-between">
                                <CardTitle>Revenue Trend</CardTitle>
                                <Button variant="ghost" size="sm" class="gap-2">
                                    View Details
                                    <ArrowRight class="h-4 w-4" />
                                </Button>
                            </CardHeader>
                            <CardContent>
                                <div class="h-[300px] w-full">
                                    <Line :data="chartData" :options="chartOptions" />
                                </div>
                            </CardContent>
                        </Card>
                    </div>
                </TabsContent>

                <!-- Analytics Tab -->
                <TabsContent value="analytics" class="mt-6">
                    <div class="grid gap-6 lg:grid-cols-2">
                        <!-- Package Performance -->
                        <Card class="col-span-1">
                            <CardHeader>
                                <CardTitle>Package Performance</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <div class="text-center text-muted-foreground py-8">
                                    Package performance analytics coming soon
                                </div>
                            </CardContent>
                        </Card>

                        <!-- Customer Insights -->
                        <Card class="col-span-1">
                            <CardHeader>
                                <CardTitle>Customer Insights</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <div class="text-center text-muted-foreground py-8">
                                    Customer insights analytics coming soon
                                </div>
                            </CardContent>
                        </Card>
                    </div>
                </TabsContent>

                <!-- Performance Tab -->
                <TabsContent value="performance" class="mt-6">
                    <div class="grid gap-6 lg:grid-cols-2">
                        <!-- Business Metrics -->
                        <Card class="col-span-1">
                            <CardHeader>
                                <CardTitle>Business Metrics</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <div class="text-center text-muted-foreground py-8">
                                    Business performance metrics coming soon
                                </div>
                            </CardContent>
                        </Card>

                        <!-- Growth Trends -->
                        <Card class="col-span-1">
                            <CardHeader>
                                <CardTitle>Growth Trends</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <div class="text-center text-muted-foreground py-8">
                                    Growth trend analysis coming soon
                                </div>
                            </CardContent>
                        </Card>
                    </div>
                </TabsContent>
            </Tabs>
        </div>
    </AppLayout>
</template>
