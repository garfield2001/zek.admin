<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, Link } from '@inertiajs/vue3';
import { CalendarCheck, PhilippinePeso, Users, TrendingUp, ArrowRight, LineChart, ChevronRight, Plus, Calendar, FileText, UserPlus, Settings, RefreshCw, Star } from 'lucide-vue-next';
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
    Legend,
    Filler
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
    Legend,
    Filler
);

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

// test data
const stats = {
    totalReservations: 156,
    activeReservations: 23,
    monthlyRevenue: 45600,
    monthlyGuests: 2450,
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


        // https://stackoverflow.com/questions/10420352/getting-the-month-name-from-a-date-object
        // Get the current month (0-11)
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
    interaction: {
        mode: 'index' as const,
        intersect: false,
    },
    plugins: {
        legend: {
            display: false
        },
        tooltip: {
            backgroundColor: 'rgba(0, 0, 0, 0.8)',
            padding: 12,
            titleColor: '#fff',
            bodyColor: '#fff',
            borderColor: 'rgba(255, 255, 255, 0.1)',
            borderWidth: 1,
            displayColors: false,
            callbacks: {
                label: (context: any) => `₱${context.parsed.y.toLocaleString()}`
            }
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
                callback: function (this: any, tickValue: number | string) {
                    return `₱${Number(tickValue).toLocaleString()}`;
                }
            }
        },
        x: {
            grid: {
                display: false
            }
        }
    }
};

// Add recent activities data
const recentActivities = [
    {
        id: 1,
        type: 'reservation',
        title: 'New Reservation',
        description: 'John Doe booked Wedding Package for 100 guests',
        time: '2 hours ago',
        icon: CalendarCheck
    },
    {
        id: 2,
        type: 'payment',
        title: 'Payment Received',
        description: 'Received ₱25,000 from Jane Smith',
        time: '4 hours ago',
        icon: PhilippinePeso
    },
    {
        id: 3,
        type: 'customer',
        title: 'New Customer',
        description: 'Mike Johnson registered as a new customer',
        time: '6 hours ago',
        icon: UserPlus
    },
    {
        id: 4,
        type: 'event',
        title: 'Event Completed',
        description: 'Successfully completed Corporate Event for ABC Corp',
        time: '1 day ago',
        icon: Calendar
    }
];

// Add quick actions data
const quickActions = [
    {
        title: 'New Reservation',
        description: 'Create a new catering reservation',
        icon: Plus,
        href: '/reservations/new',
        color: 'text-primary'
    },
    {
        title: 'View Calendar',
        description: 'Check upcoming events and availability',
        icon: Calendar,
        href: '/calendar',
        color: 'text-green-500'
    },
    {
        title: 'Generate Report',
        description: 'Create financial or performance reports',
        icon: FileText,
        href: '/reports',
        color: 'text-blue-500'
    },
    {
        title: 'Manage Staff',
        description: 'View and manage staff schedules',
        icon: Users,
        href: '/staff',
        color: 'text-purple-500'
    }
];

// Analytics data
const analyticsData = {
    packagePerformance: [
        { name: 'Wedding Package', bookings: 45, revenue: 675000, rating: 4.8, guests: 2250 },
        { name: 'Corporate Events', bookings: 38, revenue: 456000, rating: 4.6, guests: 1900 },
        { name: 'Birthday Celebrations', bookings: 32, revenue: 256000, rating: 4.7, guests: 1600 },
        { name: 'Special Occasions', bookings: 28, revenue: 224000, rating: 4.5, guests: 1400 },
        { name: 'Holiday Packages', bookings: 25, revenue: 375000, rating: 4.9, guests: 1250 }
    ],
    customerInsights: {
        peakDays: [
            { day: 'Saturday', bookings: 65, revenue: 975000 },
            { day: 'Sunday', bookings: 45, revenue: 675000 },
            { day: 'Friday', bookings: 35, revenue: 525000 },
            { day: 'Thursday', bookings: 25, revenue: 375000 },
            { day: 'Wednesday', bookings: 20, revenue: 300000 }
        ],
        popularTimes: [
            { time: '12:00 PM', bookings: 40, revenue: 600000 },
            { time: '6:00 PM', bookings: 35, revenue: 525000 },
            { time: '11:00 AM', bookings: 30, revenue: 450000 },
            { time: '5:00 PM', bookings: 25, revenue: 375000 },
            { time: '1:00 PM', bookings: 20, revenue: 300000 }
        ],
        eventTypes: [
            { type: 'Weddings', percentage: 35, revenue: 5250000 },
            { type: 'Corporate', percentage: 25, revenue: 3750000 },
            { type: 'Birthdays', percentage: 20, revenue: 3000000 },
            { type: 'Special Events', percentage: 15, revenue: 2250000 },
            { type: 'Others', percentage: 5, revenue: 750000 }
        ],
        customerRetention: {
            newCustomers: 120,
            returningCustomers: 85,
            retentionRate: 71
        }
    },
    seasonalTrends: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        data: [65, 75, 85, 95, 90, 80, 70, 75, 85, 95, 100, 90],
        revenue: [975000, 1125000, 1275000, 1425000, 1350000, 1200000, 1050000, 1125000, 1275000, 1425000, 1500000, 1350000]
    },
    financialMetrics: {
        averageOrderValue: 15000,
        profitMargin: 32,
        costPerGuest: 500,
        revenueGrowth: 15,
        customerLifetimeValue: 45000
    }
};
</script>

<template>

    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-6 p-6">
            <!-- Welcome Section -->
            <div class="flex items-center justify-between">
                <div class="space-y-1">
                    <h1 class="text-3xl font-bold tracking-tight">Welcome back!</h1>
                    <p class="text-muted-foreground">Here's what's happening with your catering business today.</p>
                </div>
                <Button variant="outline"
                    class="gap-2 transition-all duration-200 hover:bg-primary hover:text-primary-foreground">
                    View Reports
                    <ArrowRight class="h-4 w-4" />
                </Button>
            </div>

            <!-- Navigation Tabs -->
            <Tabs defaultValue="overview" class="w-full">
                <div class="mb-8 rounded-lg border bg-card/50 backdrop-blur-sm p-1.5">
                    <TabsList class="w-full justify-start h-12 bg-transparent">
                        <TabsTrigger value="overview"
                            class="text-sm font-medium data-[state=active]:bg-primary/10 data-[state=active]:text-primary rounded-md px-8 transition-all duration-200 hover:bg-primary/5 hover:text-primary/80 relative overflow-hidden group">
                            <span class="relative z-10">Overview</span>
                            <div
                                class="absolute inset-0 bg-primary/5 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                            </div>
                        </TabsTrigger>
                        <TabsTrigger value="analytics"
                            class="text-sm font-medium data-[state=active]:bg-primary/10 data-[state=active]:text-primary rounded-md px-8 transition-all duration-200 hover:bg-primary/5 hover:text-primary/80 relative overflow-hidden group">
                            <span class="relative z-10">Analytics</span>
                            <div
                                class="absolute inset-0 bg-primary/5 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                            </div>
                        </TabsTrigger>
                    </TabsList>
                </div>

                <!-- Overview Tab -->
                <TabsContent value="overview" class="mt-6">
                    <!-- Stats Grid -->
                    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                        <!-- Total Reservations Card -->
                        <Card
                            class="relative overflow-hidden border-none bg-gradient-to-br from-primary/10 to-primary/5 transition-all duration-300 hover:shadow-lg hover:scale-[1.02] group">
                            <div
                                class="absolute inset-0 bg-gradient-to-br from-primary/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none">
                            </div>
                            <div class="absolute right-4 top-4 opacity-10 pointer-events-none">
                                <CalendarCheck class="h-20 w-20 text-amber-500" />
                            </div>
                            <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2 relative z-10">
                                <CardTitle class="text-sm font-medium text-primary">Total Reservations</CardTitle>
                            </CardHeader>
                            <CardContent class="relative z-10">
                                <div class="text-3xl font-bold">{{ stats.totalReservations }}</div>
                                <div class="flex items-center gap-1 text-sm text-muted-foreground">
                                    <TrendingUp class="h-4 w-4 text-green-500" />
                                    <span>+12% from last month</span>
                                </div>
                                <div class="mt-4 flex items-center justify-between">
                                    <p class="text-xs text-muted-foreground">All-time total</p>
                                    <Link href="/reservations"
                                        class="text-xs text-primary hover:underline flex items-center gap-1 transition-colors duration-200">
                                    View Details
                                    <ChevronRight
                                        class="h-3 w-3 transition-transform duration-200 group-hover:translate-x-1" />
                                    </Link>
                                </div>
                            </CardContent>
                        </Card>

                        <!-- Monthly Revenue Card -->
                        <Card
                            class="relative overflow-hidden border-none bg-gradient-to-br from-green-500/10 to-green-500/5 transition-all duration-300 hover:shadow-lg hover:scale-[1.02] group">
                            <div
                                class="absolute inset-0 bg-gradient-to-br from-green-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none">
                            </div>
                            <div class="absolute right-4 top-4 opacity-10 pointer-events-none">
                                <PhilippinePeso class="h-20 w-20 text-green-500" />
                            </div>
                            <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2 relative z-10">
                                <CardTitle class="text-sm font-medium text-green-500">Monthly Revenue</CardTitle>
                            </CardHeader>
                            <CardContent class="relative z-10">
                                <div class="text-3xl font-bold">₱{{ stats.monthlyRevenue.toLocaleString() }}</div>
                                <div class="flex items-center gap-1 text-sm text-muted-foreground">
                                    <TrendingUp class="h-4 w-4 text-green-500" />
                                    <span>+15% from last month</span>
                                </div>
                                <div class="mt-4 flex items-center justify-between">
                                    <p class="text-xs text-muted-foreground">This month's earnings</p>
                                    <Link href="/reports/revenue"
                                        class="text-xs text-green-500 hover:underline flex items-center gap-1 transition-colors duration-200">
                                    View Details
                                    <ChevronRight
                                        class="h-3 w-3 transition-transform duration-200 group-hover:translate-x-1" />
                                    </Link>
                                </div>
                            </CardContent>
                        </Card>

                        <!-- Active Reservations Card -->
                        <Card
                            class="relative overflow-hidden border-none bg-gradient-to-br from-blue-500/10 to-blue-500/5 transition-all duration-300 hover:shadow-lg hover:scale-[1.02] group">
                            <div
                                class="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none">
                            </div>
                            <div class="absolute right-4 top-4 opacity-10 pointer-events-none">
                                <CalendarCheck class="h-20 w-20 text-blue-500" />
                            </div>
                            <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2 relative z-10">
                                <CardTitle class="text-sm font-medium text-blue-500">Active Reservations</CardTitle>
                            </CardHeader>
                            <CardContent class="relative z-10">
                                <div class="text-3xl font-bold">{{ stats.activeReservations }}</div>
                                <div class="flex items-center gap-1 text-sm text-muted-foreground">
                                    <TrendingUp class="h-4 w-4 text-green-500" />
                                    <span>+8% from last month</span>
                                </div>
                                <div class="mt-4 flex items-center justify-between">
                                    <p class="text-xs text-muted-foreground">This month's bookings</p>
                                    <Link href="/reservations/active"
                                        class="text-xs text-blue-500 hover:underline flex items-center gap-1 transition-colors duration-200">
                                    View Details
                                    <ChevronRight
                                        class="h-3 w-3 transition-transform duration-200 group-hover:translate-x-1" />
                                    </Link>
                                </div>
                            </CardContent>
                        </Card>

                        <!-- Monthly Guests Card -->
                        <Card
                            class="relative overflow-hidden border-none bg-gradient-to-br from-purple-500/10 to-purple-500/5 transition-all duration-300 hover:shadow-lg hover:scale-[1.02] group">
                            <div
                                class="absolute inset-0 bg-gradient-to-br from-purple-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none">
                            </div>
                            <div class="absolute right-4 top-4 opacity-10 pointer-events-none">
                                <Users class="h-20 w-20 text-purple-500" />
                            </div>
                            <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2 relative z-10">
                                <CardTitle class="text-sm font-medium text-purple-500">Monthly Guests</CardTitle>
                            </CardHeader>
                            <CardContent class="relative z-10">
                                <div class="text-3xl font-bold">{{ stats.monthlyGuests }}</div>
                                <div class="flex items-center gap-1 text-sm text-muted-foreground">
                                    <TrendingUp class="h-4 w-4 text-green-500" />
                                    <span>+8% from last month</span>
                                </div>
                                <div class="mt-4 flex items-center justify-between">
                                    <p class="text-xs text-muted-foreground">This month's total</p>
                                    <Link href="/reports/guests"
                                        class="text-xs text-purple-500 hover:underline flex items-center gap-1 transition-colors duration-200">
                                    View Details
                                    <ChevronRight
                                        class="h-3 w-3 transition-transform duration-200 group-hover:translate-x-1" />
                                    </Link>
                                </div>
                            </CardContent>
                        </Card>
                    </div>

                    <!-- Main Content Grid -->
                    <div class="grid gap-6 lg:grid-cols-2 mt-6">
                        <!-- Quick Actions -->
                        <Card class="col-span-1">
                            <CardHeader class="flex flex-row items-center justify-between">
                                <CardTitle>Quick Actions</CardTitle>
                                <Button variant="ghost" size="sm" class="gap-2">
                                    <RefreshCw class="h-4 w-4" />
                                    Refresh
                                </Button>
                            </CardHeader>
                            <CardContent>
                                <div class="grid gap-2">
                                    <Button v-for="action in quickActions" :key="action.title" variant="outline"
                                        class="w-full justify-start h-auto py-2.5">
                                        <Link :href="action.href" class="flex w-full items-center gap-3">
                                        <div class="rounded-full bg-primary/10 p-2">
                                            <component :is="action.icon" :class="[action.color, 'h-4 w-4']" />
                                        </div>
                                        <div class="flex flex-col items-start">
                                            <span class="font-medium">{{ action.title }}</span>
                                            <span class="text-xs text-muted-foreground">{{ action.description }}</span>
                                        </div>
                                        </Link>
                                    </Button>
                                </div>
                            </CardContent>
                        </Card>

                        <!-- Recent Activity -->
                        <Card class="col-span-1 transition-all duration-300 hover:shadow-lg">
                            <CardHeader class="flex flex-row items-center justify-between">
                                <CardTitle>Recent Activity</CardTitle>
                                <Link href="/activity"
                                    class="text-sm text-primary hover:underline flex items-center gap-1 transition-colors duration-200">
                                View All
                                <ChevronRight
                                    class="h-3 w-3 transition-transform duration-200 group-hover:translate-x-1" />
                                </Link>
                            </CardHeader>
                            <CardContent>
                                <div class="space-y-4">
                                    <div v-for="activity in recentActivities" :key="activity.id"
                                        class="flex items-start gap-4 rounded-lg border p-4 transition-all duration-200 hover:bg-muted/50 hover:border-primary/20 group relative overflow-hidden">
                                        <div
                                            class="absolute inset-0 bg-gradient-to-r from-primary/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        </div>
                                        <div
                                            class="rounded-full bg-primary/10 p-2 transition-transform duration-200 group-hover:scale-110 relative z-10">
                                            <component :is="activity.icon" class="h-4 w-4 text-primary" />
                                        </div>
                                        <div class="flex-1 space-y-1 relative z-10">
                                            <p class="text-sm font-medium">{{ activity.title }}</p>
                                            <p class="text-sm text-muted-foreground">{{ activity.description }}</p>
                                        </div>
                                        <p class="text-xs text-muted-foreground relative z-10">{{ activity.time }}</p>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>
                    </div>

                    <!-- Upcoming Events and Revenue Trend -->
                    <div class="grid gap-6 lg:grid-cols-2 mt-6">
                        <!-- Upcoming Events -->
                        <Card class="col-span-1 transition-all duration-300 hover:shadow-lg">
                            <CardHeader class="flex flex-row items-center justify-between">
                                <CardTitle>Upcoming Events</CardTitle>
                                <Link href="/events"
                                    class="text-sm text-primary hover:underline flex items-center gap-1 transition-colors duration-200">
                                View All
                                <ChevronRight
                                    class="h-3 w-3 transition-transform duration-200 group-hover:translate-x-1" />
                                </Link>
                            </CardHeader>
                            <CardContent>
                                <div class="space-y-4">
                                    <div v-for="event in stats.upcomingEvents" :key="event.date"
                                        class="flex items-center justify-between rounded-lg border p-4 transition-all duration-200 hover:bg-muted/50 hover:border-primary/20 group relative overflow-hidden">
                                        <div
                                            class="absolute inset-0 bg-gradient-to-r from-primary/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        </div>
                                        <div class="space-y-1 relative z-10">
                                            <p class="text-sm font-medium">{{ event.client }}</p>
                                            <p class="text-sm text-muted-foreground">{{ event.package }}</p>
                                        </div>
                                        <div class="text-right relative z-10">
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
                                <Link href="/reports/revenue"
                                    class="text-sm text-primary hover:underline flex items-center gap-1">
                                View Details
                                <ChevronRight class="h-3 w-3" />
                                </Link>
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
                <TabsContent value="analytics" class="mt-8">
                    <div class="grid gap-6 lg:grid-cols-2">
                        <!-- Package Performance -->
                        <Card>
                            <CardHeader>
                                <CardTitle>Package Performance</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <div class="space-y-4">
                                    <div v-for="pkg in analyticsData.packagePerformance" :key="pkg.name"
                                        class="flex items-center justify-between rounded-lg border p-4">
                                        <div class="space-y-1">
                                            <p class="font-medium">{{ pkg.name }}</p>
                                            <p class="text-sm text-muted-foreground">{{ pkg.bookings }} bookings • {{
                                                pkg.guests }} guests</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-medium">₱{{ pkg.revenue.toLocaleString() }}</p>
                                            <div class="flex items-center gap-1 text-sm">
                                                <Star class="h-3 w-3 fill-yellow-400 text-yellow-400" />
                                                <span>{{ pkg.rating }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>

                        <!-- Customer Insights -->
                        <Card>
                            <CardHeader>
                                <CardTitle>Customer Insights</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <div class="space-y-6">
                                    <!-- Customer Retention -->
                                    <div>
                                        <h4 class="text-sm font-medium mb-3">Customer Retention</h4>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div class="rounded-lg border p-4">
                                                <p class="text-sm text-muted-foreground">New Customers</p>
                                                <p class="text-2xl font-bold">{{
                                                    analyticsData.customerInsights.customerRetention.newCustomers }}</p>
                                            </div>
                                            <div class="rounded-lg border p-4">
                                                <p class="text-sm text-muted-foreground">Returning Customers</p>
                                                <p class="text-2xl font-bold">{{
                                                    analyticsData.customerInsights.customerRetention.returningCustomers
                                                    }}</p>
                                            </div>
                                            <div class="col-span-2 rounded-lg border p-4">
                                                <p class="text-sm text-muted-foreground">Retention Rate</p>
                                                <p class="text-2xl font-bold">{{
                                                    analyticsData.customerInsights.customerRetention.retentionRate }}%
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Peak Days -->
                                    <div>
                                        <h4 class="text-sm font-medium mb-3">Peak Booking Days</h4>
                                        <div class="space-y-2">
                                            <div v-for="day in analyticsData.customerInsights.peakDays" :key="day.day"
                                                class="flex items-center gap-2">
                                                <div class="w-20 text-sm">{{ day.day }}</div>
                                                <div class="flex-1 h-2 bg-muted rounded-full overflow-hidden">
                                                    <div class="h-full bg-primary"
                                                        :style="{ width: `${(day.bookings / 65) * 100}%` }"></div>
                                                </div>
                                                <div class="w-24 text-sm text-right">₱{{ (day.revenue / 1000).toFixed(1)
                                                    }}k</div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Popular Times -->
                                    <div>
                                        <h4 class="text-sm font-medium mb-3">Popular Booking Times</h4>
                                        <div class="space-y-2">
                                            <div v-for="time in analyticsData.customerInsights.popularTimes"
                                                :key="time.time" class="flex items-center gap-2">
                                                <div class="w-20 text-sm">{{ time.time }}</div>
                                                <div class="flex-1 h-2 bg-muted rounded-full overflow-hidden">
                                                    <div class="h-full bg-primary"
                                                        :style="{ width: `${(time.bookings / 40) * 100}%` }"></div>
                                                </div>
                                                <div class="w-24 text-sm text-right">₱{{ (time.revenue /
                                                    1000).toFixed(1) }}k</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>

                        <!-- Financial Metrics -->
                        <Card>
                            <CardHeader>
                                <CardTitle>Financial Metrics</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="rounded-lg border p-4">
                                        <p class="text-sm text-muted-foreground">Average Order Value</p>
                                        <p class="text-2xl font-bold">₱{{
                                            analyticsData.financialMetrics.averageOrderValue.toLocaleString() }}</p>
                                    </div>
                                    <div class="rounded-lg border p-4">
                                        <p class="text-sm text-muted-foreground">Profit Margin</p>
                                        <p class="text-2xl font-bold">{{ analyticsData.financialMetrics.profitMargin }}%
                                        </p>
                                    </div>
                                    <div class="rounded-lg border p-4">
                                        <p class="text-sm text-muted-foreground">Cost per Guest</p>
                                        <p class="text-2xl font-bold">₱{{
                                            analyticsData.financialMetrics.costPerGuest.toLocaleString() }}</p>
                                    </div>
                                    <div class="rounded-lg border p-4">
                                        <p class="text-sm text-muted-foreground">Revenue Growth</p>
                                        <p class="text-2xl font-bold text-green-500">+{{
                                            analyticsData.financialMetrics.revenueGrowth }}%</p>
                                    </div>
                                    <div class="col-span-2 rounded-lg border p-4">
                                        <p class="text-sm text-muted-foreground">Customer Lifetime Value</p>
                                        <p class="text-2xl font-bold">₱{{
                                            analyticsData.financialMetrics.customerLifetimeValue.toLocaleString() }}</p>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>

                        <!-- Seasonal Trends -->
                        <Card class="lg:col-span-2">
                            <CardHeader>
                                <CardTitle>Seasonal Trends</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <div class="h-[300px] w-full">
                                    <Line :data="{
                                        labels: analyticsData.seasonalTrends.labels,
                                        datasets: [
                                            {
                                                label: 'Booking Rate',
                                                data: analyticsData.seasonalTrends.data,
                                                borderColor: 'rgb(var(--primary))',
                                                backgroundColor: 'rgba(var(--primary), 0.1)',
                                                tension: 0.4,
                                                fill: true
                                            },
                                            {
                                                label: 'Revenue',
                                                data: analyticsData.seasonalTrends.revenue.map(v => v / 10000),
                                                borderColor: 'rgb(var(--green-500))',
                                                backgroundColor: 'rgba(var(--green-500), 0.1)',
                                                tension: 0.4,
                                                fill: true
                                            }
                                        ]
                                    }" :options="{
                                        responsive: true,
                                        maintainAspectRatio: false,
                                        interaction: {
                                            mode: 'index' as const,
                                            intersect: false,
                                        },
                                        plugins: {
                                            legend: {
                                                display: true,
                                                position: 'top' as const
                                            },
                                            tooltip: {
                                                callbacks: {
                                                    label: function (context: any) {
                                                        if (context.dataset.label === 'Booking Rate') {
                                                            return `${context.parsed.y}%`;
                                                        } else {
                                                            return `₱${(context.parsed.y * 10000).toLocaleString()}`;
                                                        }
                                                    }
                                                }
                                            }
                                        },
                                        scales: {
                                            y: {
                                                beginAtZero: true,
                                                grid: {
                                                    display: true,
                                                    color: 'rgba(0, 0, 0, 0.05)'
                                                }
                                            },
                                            x: {
                                                grid: {
                                                    display: false
                                                }
                                            }
                                        }
                                    }" />
                                </div>
                            </CardContent>
                        </Card>
                    </div>
                </TabsContent>
            </Tabs>
        </div>
    </AppLayout>
</template>

<style scoped>
.card {
    @apply transition-all duration-300;
}

.card:hover {
    @apply shadow-lg;
}

/* Add smooth transitions for all interactive elements */
button,
a {
    @apply transition-all duration-200;
}

/* Add subtle gradient overlay on hover */
.hover-gradient {
    @apply relative overflow-hidden;
}

.hover-gradient::after {
    content: '';
    @apply absolute inset-0 bg-gradient-to-br from-primary/5 to-transparent opacity-0 transition-opacity duration-300;
}

.hover-gradient:hover::after {
    @apply opacity-100;
}

/* Add scale effect to icons on hover */
.icon-hover {
    @apply transition-transform duration-200;
}

.icon-hover:hover {
    @apply scale-110;
}

/* Add slide effect to chevron icons */
.chevron-slide {
    @apply transition-transform duration-200;
}

.chevron-slide:hover {
    @apply translate-x-1;
}
</style>
