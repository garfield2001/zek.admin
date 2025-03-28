<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { ref, onMounted, computed, h } from 'vue';
import {
    Tabs,
    TabsContent,
    TabsList,
    TabsTrigger,
} from '@/components/ui/tabs';
import {
    Package, Utensils, DollarSign, Calendar,
    Settings, BarChart, TrendingUp, ChevronRight,
    ArrowRight, Plus, Edit, Edit2, Trash2,
    X, ChevronLeft, ChevronsLeft, ChevronsRight, ChevronsUpDown,
    Image as ImageIcon, ExternalLink, Search
} from 'lucide-vue-next';
import { Link } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import { toast } from 'sonner';

import Menus from './catering/Menus.vue';



const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Catering Overview',
        href: '/catering',
    }
];

// Data from backend
const stats = ref({
    totalPackages: 0,
    totalMenuItems: 0,
    totalRevenue: 0,
    upcomingEvents: 0,
    trends: {
        packages: '+0 from last month',
        menuItems: '+0 from last month',
        revenue: '+0% from last month',
        events: '+0 from last month'
    }
});

// Define types for backend data
interface MenuType {
    id: number;
    name: string;
    description?: string;
    dishes: Dish[];
}

interface Dish {
    id: number;
    name: string;
    description: string;
    price: number;
    is_available: boolean;
    menu_type_id: number;
    menu_type: MenuType;
}

interface BackendData {
    initialPackages: any[];
    initialMenuTypes: MenuType[];
    initialDishes: Dish[];
    initialMinimumPersons: number;
}

// Menu management data with proper typing
const menuData = ref({
    menuTypes: [] as MenuType[],
    dishes: [] as Dish[],
});

// Form data for modals
const newDish = ref({
    name: '',
    description: '',
    price: 0,
    menu_type_id: '',
    is_available: true,
});

// Fetch data on component mount
onMounted(() => {
    router.get('/catering', {}, {
        preserveState: true,
        onSuccess: (page) => {
            const data = page.props as unknown as BackendData;
            stats.value.totalPackages = data.initialPackages?.length || 0;
            stats.value.totalMenuItems = data.initialDishes?.length || 0;

            // Update menu data
            menuData.value.menuTypes = data.initialMenuTypes || [];
            menuData.value.dishes = data.initialDishes || [];
        }
    });
});




</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">

        <Head title="Catering Overview" />

        <div class="flex h-full flex-1 flex-col gap-6 p-6">
            <!-- Welcome Section -->
            <div class="flex items-center justify-between">
                <div class="space-y-1">
                    <h1 class="text-3xl font-bold tracking-tight">Catering Overview</h1>
                    <p class="text-muted-foreground">Manage your catering packages and menu items</p>
                </div>
                <Button variant="outline"
                    class="gap-2 transition-all duration-200 hover:bg-primary hover:text-primary-foreground" asChild>
                    <Link href="/dashboard">
                    View Full Dashboard
                    <ChevronRight class="h-4 w-4" />
                    </Link>
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
                        <TabsTrigger value="menu"
                            class="text-sm font-medium data-[state=active]:bg-primary/10 data-[state=active]:text-primary rounded-md px-8 transition-all duration-200 hover:bg-primary/5 hover:text-primary/80 relative overflow-hidden group">
                            <span class="relative z-10">Menu Management</span>
                            <div
                                class="absolute inset-0 bg-primary/5 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                            </div>
                        </TabsTrigger>
                        <TabsTrigger value="packages"
                            class="text-sm font-medium data-[state=active]:bg-primary/10 data-[state=active]:text-primary rounded-md px-8 transition-all duration-200 hover:bg-primary/5 hover:text-primary/80 relative overflow-hidden group">
                            <span class="relative z-10">Package Management</span>
                            <div
                                class="absolute inset-0 bg-primary/5 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                            </div>
                        </TabsTrigger>
                    </TabsList>
                </div>

                <!-- Overview Tab -->
                <TabsContent value="overview" class="mt-6">
                    <!-- Quick Stats -->
                    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                        <Card
                            class="relative overflow-hidden border-none bg-gradient-to-br from-primary/10 to-primary/5 transition-all duration-300 hover:shadow-lg hover:scale-[1.02] group">
                            <div
                                class="absolute inset-0 bg-gradient-to-br from-primary/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none">
                            </div>
                            <div class="absolute right-4 top-4 opacity-10 pointer-events-none">
                                <Package class="h-20 w-20 text-primary" />
                            </div>
                            <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2 relative z-10">
                                <CardTitle class="text-sm font-medium text-primary">Total Packages</CardTitle>
                            </CardHeader>
                            <CardContent class="relative z-10">
                                <div class="text-3xl font-bold">{{ stats.totalPackages }}</div>
                                <div class="flex items-center gap-1 text-sm text-muted-foreground">
                                    <TrendingUp class="h-4 w-4 text-green-500" />
                                    <span>{{ stats.trends.packages }}</span>
                                </div>
                                <div class="mt-4 flex items-center justify-between">
                                    <p class="text-xs text-muted-foreground">Available packages</p>
                                    <Link href="/catering/packages"
                                        class="text-xs text-primary hover:underline flex items-center gap-1 transition-colors duration-200">
                                    View Details
                                    <ChevronRight
                                        class="h-3 w-3 transition-transform duration-200 group-hover:translate-x-1" />
                                    </Link>
                                </div>
                            </CardContent>
                        </Card>

                        <Card
                            class="relative overflow-hidden border-none bg-gradient-to-br from-green-500/10 to-green-500/5 transition-all duration-300 hover:shadow-lg hover:scale-[1.02] group">
                            <div
                                class="absolute inset-0 bg-gradient-to-br from-green-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none">
                            </div>
                            <div class="absolute right-4 top-4 opacity-10 pointer-events-none">
                                <Utensils class="h-20 w-20 text-green-500" />
                            </div>
                            <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2 relative z-10">
                                <CardTitle class="text-sm font-medium text-green-500">Menu Items</CardTitle>
                            </CardHeader>
                            <CardContent class="relative z-10">
                                <div class="text-3xl font-bold">{{ stats.totalMenuItems }}</div>
                                <div class="flex items-center gap-1 text-sm text-muted-foreground">
                                    <TrendingUp class="h-4 w-4 text-green-500" />
                                    <span>{{ stats.trends.menuItems }}</span>
                                </div>
                                <div class="mt-4 flex items-center justify-between">
                                    <p class="text-xs text-muted-foreground">Total menu items</p>
                                    <Link href="/catering/menu"
                                        class="text-xs text-green-500 hover:underline flex items-center gap-1 transition-colors duration-200">
                                    View Details
                                    <ChevronRight
                                        class="h-3 w-3 transition-transform duration-200 group-hover:translate-x-1" />
                                    </Link>
                                </div>
                            </CardContent>
                        </Card>

                        <Card
                            class="relative overflow-hidden border-none bg-gradient-to-br from-blue-500/10 to-blue-500/5 transition-all duration-300 hover:shadow-lg hover:scale-[1.02] group">
                            <div
                                class="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none">
                            </div>
                            <div class="absolute right-4 top-4 opacity-10 pointer-events-none">
                                <DollarSign class="h-20 w-20 text-blue-500" />
                            </div>
                            <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2 relative z-10">
                                <CardTitle class="text-sm font-medium text-blue-500">Catering Revenue</CardTitle>
                            </CardHeader>
                            <CardContent class="relative z-10">
                                <div class="text-3xl font-bold">₱{{ stats.totalRevenue.toLocaleString() }}</div>
                                <div class="flex items-center gap-1 text-sm text-muted-foreground">
                                    <TrendingUp class="h-4 w-4 text-green-500" />
                                    <span>{{ stats.trends.revenue }}</span>
                                </div>
                                <div class="mt-4 flex items-center justify-between">
                                    <p class="text-xs text-muted-foreground">This month</p>
                                    <Link href="/catering/revenue"
                                        class="text-xs text-blue-500 hover:underline flex items-center gap-1 transition-colors duration-200">
                                    View Details
                                    <ChevronRight
                                        class="h-3 w-3 transition-transform duration-200 group-hover:translate-x-1" />
                                    </Link>
                                </div>
                            </CardContent>
                        </Card>

                        <Card
                            class="relative overflow-hidden border-none bg-gradient-to-br from-purple-500/10 to-purple-500/5 transition-all duration-300 hover:shadow-lg hover:scale-[1.02] group">
                            <div
                                class="absolute inset-0 bg-gradient-to-br from-purple-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none">
                            </div>
                            <div class="absolute right-4 top-4 opacity-10 pointer-events-none">
                                <Calendar class="h-20 w-20 text-purple-500" />
                            </div>
                            <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2 relative z-10">
                                <CardTitle class="text-sm font-medium text-purple-500">Upcoming Events</CardTitle>
                            </CardHeader>
                            <CardContent class="relative z-10">
                                <div class="text-3xl font-bold">{{ stats.upcomingEvents }}</div>
                                <div class="flex items-center gap-1 text-sm text-muted-foreground">
                                    <TrendingUp class="h-4 w-4 text-green-500" />
                                    <span>{{ stats.trends.events }}</span>
                                </div>
                                <div class="mt-4 flex items-center justify-between">
                                    <p class="text-xs text-muted-foreground">Next 30 days</p>
                                    <Link href="/catering/events"
                                        class="text-xs text-purple-500 hover:underline flex items-center gap-1 transition-colors duration-200">
                                    View Details
                                    <ChevronRight
                                        class="h-3 w-3 transition-transform duration-200 group-hover:translate-x-1" />
                                    </Link>
                                </div>
                            </CardContent>
                        </Card>
                    </div>

                    <!-- Quick Actions -->
                    <div class="grid gap-6 lg:grid-cols-2 mt-6">
                        <Card class="transition-all duration-300 hover:shadow-lg">
                            <CardHeader class="flex flex-row items-center justify-between">
                                <CardTitle>Quick Actions</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <div class="grid gap-2">
                                    <Button variant="outline" class="w-full justify-start h-auto py-2.5" asChild>
                                        <Link href="/catering/packages/new" class="flex w-full items-center gap-3">
                                        <div class="rounded-full bg-primary/10 p-2">
                                            <Package class="h-4 w-4 text-primary" />
                                        </div>
                                        <div class="flex flex-col items-start">
                                            <span class="font-medium">Add New Package</span>
                                            <span class="text-xs text-muted-foreground">Create a new catering
                                                package</span>
                                        </div>
                                        </Link>
                                    </Button>
                                    <Button variant="outline" class="w-full justify-start h-auto py-2.5" asChild>
                                        <Link href="/catering/menu/new" class="flex w-full items-center gap-3">
                                        <div class="rounded-full bg-green-500/10 p-2">
                                            <Utensils class="h-4 w-4 text-green-500" />
                                        </div>
                                        <div class="flex flex-col items-start">
                                            <span class="font-medium">Add Menu Item</span>
                                            <span class="text-xs text-muted-foreground">Add a new menu item</span>
                                        </div>
                                        </Link>
                                    </Button>
                                    <Button variant="outline" class="w-full justify-start h-auto py-2.5" asChild>
                                        <Link href="/catering/events" class="flex w-full items-center gap-3">
                                        <div class="rounded-full bg-purple-500/10 p-2">
                                            <Calendar class="h-4 w-4 text-purple-500" />
                                        </div>
                                        <div class="flex flex-col items-start">
                                            <span class="font-medium">View Upcoming Events</span>
                                            <span class="text-xs text-muted-foreground">Check your upcoming catering
                                                events</span>
                                        </div>
                                        </Link>
                                    </Button>
                                </div>
                            </CardContent>
                        </Card>

                        <Card class="transition-all duration-300 hover:shadow-lg">
                            <CardHeader class="flex flex-row items-center justify-between">
                                <CardTitle>Management Tools</CardTitle>
                            </CardHeader>
                            <CardContent>
                                <div class="grid gap-2">
                                    <Button variant="outline" class="w-full justify-start h-auto py-2.5" asChild>
                                        <Link href="/catering/settings" class="flex w-full items-center gap-3">
                                        <div class="rounded-full bg-blue-500/10 p-2">
                                            <Settings class="h-4 w-4 text-blue-500" />
                                        </div>
                                        <div class="flex flex-col items-start">
                                            <span class="font-medium">Catering Settings</span>
                                            <span class="text-xs text-muted-foreground">Configure your catering
                                                preferences</span>
                                        </div>
                                        </Link>
                                    </Button>
                                    <Button variant="outline" class="w-full justify-start h-auto py-2.5" asChild>
                                        <Link href="/dashboard" class="flex w-full items-center gap-3">
                                        <div class="rounded-full bg-primary/10 p-2">
                                            <BarChart class="h-4 w-4 text-primary" />
                                        </div>
                                        <div class="flex flex-col items-start">
                                            <span class="font-medium">View Full Reports</span>
                                            <span class="text-xs text-muted-foreground">Access detailed analytics and
                                                reports</span>
                                        </div>
                                        </Link>
                                    </Button>
                                </div>
                            </CardContent>
                        </Card>
                    </div>
                </TabsContent>

                <!-- Menu Management Tab -->
                <TabsContent value="menu" class="mt-6">
                    <Menus />
                </TabsContent>

                <!-- Package Management Tab -->
                <TabsContent value="packages" class="mt-6">
                    <!-- Content will be added here -->
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