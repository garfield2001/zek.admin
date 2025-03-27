<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Utensils, UtensilsCrossed, Plus, X, Edit2, Save, PlusCircle, Trash2, Settings, BarChart, Users, Filter, ShoppingCart, Calendar, CalendarDays, DollarSign, Package, TrendingUp, CheckCircle, Sun, Heart, ChevronRight, LineChart, Clock, Sliders, History } from 'lucide-vue-next';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { ref, reactive, computed } from 'vue';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';
import {
    Tabs,
    TabsContent,
    TabsList,
    TabsTrigger,
} from '@/components/ui/tabs';
import { Link, router } from '@inertiajs/vue3';
import { Badge } from '@/components/ui/badge';
import { Switch } from '@/components/ui/switch';

interface Props {
    initialPackages: Package[];
    initialMealCategories: MenuCategory[];
    initialMinimumPersons: number;
}

const props = defineProps<Props>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Manage Catering',
        href: '/catering',
    }
];

interface Package {
    name: string;
    price: number;
    meals: number;
    includes: string[];
}

interface MenuCategory {
    id: string;
    title: string;
    items: string[];
}

const minimumPersons = ref(props.initialMinimumPersons);
const isEditingMinimum = ref(false);

const defaultPackage = {
    name: 'NEW PACKAGE',
    price: 0,
    meals: 5,
    includes: ['Rice & Drinks']
};

const packages = ref<Package[]>(props.initialPackages);
const menuCategories = reactive<MenuCategory[]>(props.initialMealCategories);

const editingStates = ref(packages.value.map(() => ({
    isEditing: false,
    newInclusion: '',
})));

const getEditingState = (index: number) => {
    return computed({
        get: () => editingStates.value[index],
        set: (value) => {
            if (editingStates.value[index]) {
                editingStates.value[index] = { ...editingStates.value[index], ...value };
            }
        }
    });
};

const newPackage = reactive<Package>({ ...defaultPackage });
const newInclusion = ref('');
const packageToDelete = ref<number | null>(null);

const startEditing = (index: number) => {
    if (editingStates.value[index]) {
        editingStates.value[index].isEditing = true;
    }
};

const savePackage = (index: number) => {
    if (editingStates.value[index]) {
        const pkg = packages.value[index];
        router.put(`/catering/packages/${index + 1}`, {
            name: pkg.name,
            price: pkg.price,
            meals: pkg.meals,
            includes: pkg.includes
        }, {
            preserveScroll: true,
            onSuccess: () => {
                editingStates.value[index].isEditing = false;
            }
        });
    }
};

const addInclusion = (packageIndex: number) => {
    const state = editingStates.value[packageIndex];
    if (state?.newInclusion.trim() && packages.value[packageIndex]) {
        packages.value[packageIndex].includes.push(state.newInclusion.trim());
        if (state) {
            state.newInclusion = '';
        }
    }
};

const removeInclusion = (packageIndex: number, inclusionIndex: number) => {
    if (packages.value[packageIndex]) {
        packages.value[packageIndex].includes.splice(inclusionIndex, 1);
    }
};

const saveMinimumPersons = () => {
    router.put('/catering/settings/minimum-guests', {
        value: minimumPersons.value
    }, {
        preserveScroll: true,
        onSuccess: () => {
            isEditingMinimum.value = false;
        }
    });
};

// Dialog States
const showAddDialog = ref(false);
const showAddMenuDialog = ref(false);
const showDeleteDialog = ref(false);

const addNewInclusion = () => {
    const inclusion = newInclusion.value.trim();
    if (inclusion && !newPackage.includes.includes(inclusion)) {
        newPackage.includes.push(inclusion);
        newInclusion.value = '';
    }
};

const removeNewInclusion = (index: number) => {
    newPackage.includes.splice(index, 1);
};

const resetNewPackage = () => {
    Object.assign(newPackage, defaultPackage);
    newInclusion.value = '';
};

const addNewPackage = () => {
    showAddDialog.value = true;
};

const cancelAddPackage = () => {
    showAddDialog.value = false;
    resetNewPackage();
};

const confirmAddPackage = () => {
    if (newPackage.name.trim() && newPackage.price > 0 && newPackage.meals > 0) {
        packages.value.push({ ...newPackage });
        editingStates.value.push({
            isEditing: false,
            newInclusion: '',
        });
        showAddDialog.value = false;
        resetNewPackage();
    }
};

const confirmDelete = (index: number) => {
    packageToDelete.value = index;
    showDeleteDialog.value = true;
};

const deletePackage = () => {
    const index = packageToDelete.value;
    if (index !== null) {
        router.delete(`/catering/packages/${index + 1}`, {
            preserveScroll: true,
            onSuccess: () => {
                packages.value.splice(index, 1);
                editingStates.value.splice(index, 1);
                showDeleteDialog.value = false;
                packageToDelete.value = null;
            }
        });
    }
};

// Menu Management
const selectedCategory = ref('');
const newMenuItem = ref('');
const newCategory = ref('');
const menuType = ref<'category' | 'menu'>('menu');

const resetMenuForm = () => {
    selectedCategory.value = '';
    newMenuItem.value = '';
    newCategory.value = '';
    menuType.value = 'menu';
};

const cancelAddMenu = () => {
    showAddMenuDialog.value = false;
    resetMenuForm();
};

const addNewMenu = () => {
    if (menuType.value === 'category' && newCategory.value.trim()) {
        const categoryName = newCategory.value.trim().toUpperCase();
        if (!menuCategories.some(cat => cat.title === categoryName)) {
            menuCategories.push({
                id: categoryName,
                title: categoryName,
                items: []
            });
            resetMenuForm();
            showAddMenuDialog.value = false;
        }
    } else if (menuType.value === 'menu' && selectedCategory.value && newMenuItem.value.trim()) {
        const category = menuCategories.find(cat => cat.title === selectedCategory.value);
        if (category && !category.items.includes(newMenuItem.value.trim().toUpperCase())) {
            category.items.push(newMenuItem.value.trim().toUpperCase());
            resetMenuForm();
            showAddMenuDialog.value = false;
        }
    }
};

// Menu Item Management
const menuItemToDelete = ref<{ category: string; item: string } | null>(null);
const menuItemToEdit = ref<{ category: string; item: string } | null>(null);
const editingMenuItem = ref('');

const startEditingMenuItem = (categoryTitle: string, item: string) => {
    menuItemToEdit.value = { category: categoryTitle, item };
    editingMenuItem.value = item;
};

const saveMenuItem = () => {
    if (menuItemToEdit.value && editingMenuItem.value.trim()) {
        const category = menuCategories.find(cat => cat.title === menuItemToEdit.value?.category);
        if (category) {
            const index = category.items.indexOf(menuItemToEdit.value.item);
            if (index !== -1) {
                category.items[index] = editingMenuItem.value.trim().toUpperCase();
                menuItemToEdit.value = null;
                editingMenuItem.value = '';
            }
        }
    }
};

const confirmDeleteMenuItem = (categoryTitle: string, item: string) => {
    menuItemToDelete.value = { category: categoryTitle, item };
    showDeleteDialog.value = true;
};

const deleteMenuItem = () => {
    if (menuItemToDelete.value) {
        const category = menuCategories.find(cat => cat.title === menuItemToDelete.value?.category);
        if (category) {
            const index = category.items.indexOf(menuItemToDelete.value.item);
            if (index !== -1) {
                category.items.splice(index, 1);
                showDeleteDialog.value = false;
                menuItemToDelete.value = null;
            }
        }
    }
};

const addNewCategory = () => {
    const categoryName = newCategory.value.trim().toUpperCase();
    if (categoryName && !menuCategories.some(cat => cat.title === categoryName)) {
        menuCategories.push({
            id: categoryName,
            title: categoryName,
            items: []
        });
        selectedCategory.value = categoryName;
        newCategory.value = '';
    }
};

const seasonalDates = computed(() => ['Dec 31', 'Aug 31', 'Jun 30']);
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">

        <Head title="Manage Catering" />

        <div class="flex h-full flex-1 flex-col gap-6 p-6">
            <!-- Header Section -->
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight">Catering Management</h1>
                    <p class="text-muted-foreground">Manage your catering packages and menu items</p>
                </div>
                <div class="flex items-center gap-3">
                    <Button variant="outline" asChild>
                        <Link href="/catering/settings">
                        <Settings class="h-4 w-4 mr-2" />
                        Settings
                        </Link>
                    </Button>
                    <Button variant="outline" asChild>
                        <Link href="/catering/reports">
                        <BarChart class="h-4 w-4 mr-2" />
                        Reports
                        </Link>
                    </Button>
                </div>
            </div>

            <!-- Navigation Tabs -->
            <Tabs defaultValue="overview" class="w-full">
                <TabsList class="w-full justify-start h-10">
                    <TabsTrigger value="overview" class="text-sm font-medium">Overview</TabsTrigger>
                    <TabsTrigger value="menu" class="text-sm font-medium">Menu Management</TabsTrigger>
                    <TabsTrigger value="packages" class="text-sm font-medium">Package Management</TabsTrigger>
                </TabsList>

                <!-- Overview Tab -->
                <TabsContent value="overview" class="mt-6">
                    <!-- Stats Grid -->
                    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-4">
                        <Card
                            class="relative overflow-hidden border-none bg-gradient-to-br from-primary/10 to-primary/5">
                            <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                                <CardTitle class="text-sm font-medium text-primary">Package Performance</CardTitle>
                                <Package class="h-4 w-4 text-primary" />
                            </CardHeader>
                            <CardContent>
                                <div class="text-3xl font-bold">Wedding Package</div>
                                <div class="flex items-center gap-1 text-sm text-muted-foreground">
                                    <TrendingUp class="h-4 w-4 text-green-500" />
                                    <span>45% of bookings</span>
                                </div>
                                <p class="mt-2 text-xs text-muted-foreground">
                                    Most popular package
                                </p>
                            </CardContent>
                        </Card>

                        <Card
                            class="relative overflow-hidden border-none bg-gradient-to-br from-green-500/10 to-green-500/5">
                            <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                                <CardTitle class="text-sm font-medium text-green-500">Average Order Value</CardTitle>
                                <DollarSign class="h-4 w-4 text-green-500" />
                            </CardHeader>
                            <CardContent>
                                <div class="text-3xl font-bold">₱{{(packages.reduce((acc, pkg) => acc + pkg.price, 0) /
                                    packages.length).toFixed(2)}}</div>
                                <div class="flex items-center gap-1 text-sm text-muted-foreground">
                                    <TrendingUp class="h-4 w-4 text-green-500" />
                                    <span>Per package</span>
                                </div>
                                <p class="mt-2 text-xs text-muted-foreground">
                                    Current average
                                </p>
                            </CardContent>
                        </Card>

                        <Card
                            class="relative overflow-hidden border-none bg-gradient-to-br from-blue-500/10 to-blue-500/5">
                            <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                                <CardTitle class="text-sm font-medium text-blue-500">Price Range</CardTitle>
                                <DollarSign class="h-4 w-4 text-blue-500" />
                            </CardHeader>
                            <CardContent>
                                <div class="text-3xl font-bold">₱{{Math.min(...packages.map(pkg =>
                                    pkg.price)).toLocaleString()}} - ₱{{Math.max(...packages.map(pkg =>
                                        pkg.price)).toLocaleString()}}</div>
                                <div class="flex items-center gap-1 text-sm text-muted-foreground">
                                    <TrendingUp class="h-4 w-4 text-green-500" />
                                    <span>Across all packages</span>
                                </div>
                                <p class="mt-2 text-xs text-muted-foreground">
                                    Min - Max range
                                </p>
                            </CardContent>
                        </Card>

                        <Card
                            class="relative overflow-hidden border-none bg-gradient-to-br from-purple-500/10 to-purple-500/5">
                            <CardHeader class="flex flex-row items-center justify-between space-y-0 pb-2">
                                <CardTitle class="text-sm font-medium text-purple-500">Meal Distribution</CardTitle>
                                <Utensils class="h-4 w-4 text-purple-500" />
                            </CardHeader>
                            <CardContent>
                                <div class="text-3xl font-bold">{{Math.min(...packages.map(pkg => pkg.meals))}} -
                                    {{Math.max(...packages.map(pkg => pkg.meals))}}</div>
                                <div class="flex items-center gap-1 text-sm text-muted-foreground">
                                    <TrendingUp class="h-4 w-4 text-green-500" />
                                    <span>Meals per package</span>
                                </div>
                                <p class="mt-2 text-xs text-muted-foreground">
                                    Available range
                                </p>
                            </CardContent>
                        </Card>
                    </div>

                    <!-- Additional Management Sections -->
                    <div class="grid gap-6 lg:grid-cols-2 mt-6">
                        <!-- Menu Category Overview -->
                        <Card class="col-span-1">
                            <CardHeader class="flex flex-row items-center justify-between">
                                <CardTitle>Menu Categories</CardTitle>
                                <Button variant="outline" size="sm" class="gap-2">
                                    <Filter class="h-4 w-4" />
                                    Manage
                                </Button>
                            </CardHeader>
                            <CardContent>
                                <div class="space-y-4">
                                    <div v-for="category in menuCategories" :key="category.id"
                                        class="flex items-center justify-between rounded-lg border p-4">
                                        <div class="space-y-1">
                                            <div class="flex items-center gap-2">
                                                <UtensilsCrossed class="h-4 w-4 text-primary" />
                                                <span class="font-medium">{{ category.title }}</span>
                                            </div>
                                            <p class="text-sm text-muted-foreground">{{ category.items.length }} items
                                            </p>
                                        </div>
                                        <Button variant="ghost" size="sm">
                                            <Edit2 class="h-4 w-4" />
                                        </Button>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>

                        <!-- Package Inclusions Summary -->
                        <Card class="col-span-1">
                            <CardHeader class="flex flex-row items-center justify-between">
                                <CardTitle>Package Inclusions</CardTitle>
                                <Button variant="outline" size="sm" class="gap-2">
                                    <Package class="h-4 w-4" />
                                    Manage
                                </Button>
                            </CardHeader>
                            <CardContent>
                                <div class="space-y-4">
                                    <div v-for="(inclusion, index) in ['Service Staff', 'Basic Setup', 'Tableware', 'Basic Decoration']"
                                        :key="index" class="flex items-center justify-between rounded-lg border p-4">
                                        <div class="space-y-1">
                                            <div class="flex items-center gap-2">
                                                <CheckCircle class="h-4 w-4 text-green-500" />
                                                <span class="font-medium">{{ inclusion }}</span>
                                            </div>
                                            <p class="text-sm text-muted-foreground">Available in {{
                                                Math.floor(Math.random() * 3) + 2 }} packages</p>
                                        </div>
                                        <Button variant="ghost" size="sm">
                                            <Edit2 class="h-4 w-4" />
                                        </Button>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>

                        <!-- Seasonal Packages -->
                        <Card class="col-span-1">
                            <CardHeader class="flex flex-row items-center justify-between">
                                <CardTitle>Seasonal Packages</CardTitle>
                                <Button variant="outline" size="sm" class="gap-2">
                                    <Calendar class="h-4 w-4" />
                                    Manage
                                </Button>
                            </CardHeader>
                            <CardContent>
                                <div class="space-y-4">
                                    <div v-for="(season, index) in ['Holiday Special', 'Summer Package', 'Wedding Season']"
                                        :key="index" class="flex items-center justify-between rounded-lg border p-4">
                                        <div class="space-y-1">
                                            <div class="flex items-center gap-2">
                                                <Sun class="h-4 w-4 text-yellow-500" />
                                                <span class="font-medium">{{ season }}</span>
                                            </div>
                                            <p class="text-sm text-muted-foreground">Active until {{
                                                seasonalDates[index] }}</p>
                                        </div>
                                        <Switch />
                                    </div>
                                </div>
                            </CardContent>
                        </Card>

                        <!-- Package Comparison -->
                        <Card class="col-span-1">
                            <CardHeader class="flex flex-row items-center justify-between">
                                <CardTitle>Package Comparison</CardTitle>
                                <Button variant="outline" size="sm" class="gap-2">
                                    <BarChart class="h-4 w-4" />
                                    Compare
                                </Button>
                            </CardHeader>
                            <CardContent>
                                <div class="space-y-4">
                                    <div v-for="(pkg, index) in packages.slice(0, 2)" :key="index"
                                        class="flex items-center justify-between rounded-lg border p-4">
                                        <div class="space-y-1">
                                            <div class="flex items-center gap-2">
                                                <Package class="h-4 w-4 text-primary" />
                                                <span class="font-medium">{{ pkg.name }}</span>
                                            </div>
                                            <p class="text-sm text-muted-foreground">₱{{ pkg.price.toLocaleString() }} -
                                                {{ pkg.meals }} meals</p>
                                        </div>
                                        <Button variant="ghost" size="sm">
                                            <BarChart class="h-4 w-4" />
                                        </Button>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>

                        <!-- Customer Preferences -->
                        <Card class="col-span-1">
                            <CardHeader class="flex flex-row items-center justify-between">
                                <CardTitle>Customer Preferences</CardTitle>
                                <Button variant="outline" size="sm" class="gap-2">
                                    <Users class="h-4 w-4" />
                                    View All
                                </Button>
                            </CardHeader>
                            <CardContent>
                                <div class="space-y-4">
                                    <div v-for="(pref, index) in ['Most Selected Items', 'Popular Combinations', 'Customization Trends']"
                                        :key="index" class="flex items-center justify-between rounded-lg border p-4">
                                        <div class="space-y-1">
                                            <div class="flex items-center gap-2">
                                                <Heart class="h-4 w-4 text-red-500" />
                                                <span class="font-medium">{{ pref }}</span>
                                            </div>
                                            <p class="text-sm text-muted-foreground">View detailed analytics</p>
                                        </div>
                                        <Button variant="ghost" size="sm">
                                            <ChevronRight class="h-4 w-4" />
                                        </Button>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>

                        <!-- Package Performance Details -->
                        <Card class="col-span-1">
                            <CardHeader class="flex flex-row items-center justify-between">
                                <CardTitle>Performance Details</CardTitle>
                                <Button variant="outline" size="sm" class="gap-2">
                                    <TrendingUp class="h-4 w-4" />
                                    View All
                                </Button>
                            </CardHeader>
                            <CardContent>
                                <div class="space-y-4">
                                    <div v-for="(metric, index) in ['Booking Trends', 'Revenue Analysis', 'Customer Satisfaction']"
                                        :key="index" class="flex items-center justify-between rounded-lg border p-4">
                                        <div class="space-y-1">
                                            <div class="flex items-center gap-2">
                                                <LineChart class="h-4 w-4 text-blue-500" />
                                                <span class="font-medium">{{ metric }}</span>
                                            </div>
                                            <p class="text-sm text-muted-foreground">View detailed reports</p>
                                        </div>
                                        <Button variant="ghost" size="sm">
                                            <ChevronRight class="h-4 w-4" />
                                        </Button>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>

                        <!-- Quick Settings -->
                        <Card class="col-span-1">
                            <CardHeader class="flex flex-row items-center justify-between">
                                <CardTitle>Quick Settings</CardTitle>
                                <Button variant="outline" size="sm" class="gap-2">
                                    <Settings class="h-4 w-4" />
                                    All Settings
                                </Button>
                            </CardHeader>
                            <CardContent>
                                <div class="space-y-4">
                                    <div v-for="(setting, index) in ['Minimum Order', 'Package Availability', 'Special Offers']"
                                        :key="index" class="flex items-center justify-between rounded-lg border p-4">
                                        <div class="space-y-1">
                                            <div class="flex items-center gap-2">
                                                <Sliders class="h-4 w-4 text-primary" />
                                                <span class="font-medium">{{ setting }}</span>
                                            </div>
                                            <p class="text-sm text-muted-foreground">Configure settings</p>
                                        </div>
                                        <Button variant="ghost" size="sm">
                                            <Edit2 class="h-4 w-4" />
                                        </Button>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>

                        <!-- Recent Changes -->
                        <Card class="col-span-1">
                            <CardHeader class="flex flex-row items-center justify-between">
                                <CardTitle>Recent Changes</CardTitle>
                                <Button variant="outline" size="sm" class="gap-2">
                                    <History class="h-4 w-4" />
                                    View All
                                </Button>
                            </CardHeader>
                            <CardContent>
                                <div class="space-y-4">
                                    <div v-for="(change, index) in ['Package Updated', 'New Menu Item', 'Price Adjusted']"
                                        :key="index" class="flex items-center justify-between rounded-lg border p-4">
                                        <div class="space-y-1">
                                            <div class="flex items-center gap-2">
                                                <Clock class="h-4 w-4 text-primary" />
                                                <span class="font-medium">{{ change }}</span>
                                            </div>
                                            <p class="text-sm text-muted-foreground">2 hours ago</p>
                                        </div>
                                        <Button variant="ghost" size="sm">
                                            <ChevronRight class="h-4 w-4" />
                                        </Button>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>
                    </div>
                </TabsContent>

                <!-- Menu Management Tab -->
                <TabsContent value="menu" class="mt-6">
                    <Card>
                        <CardHeader class="flex flex-row items-center justify-between">
                            <CardTitle>Menu Categories</CardTitle>
                            <Button variant="outline" size="sm" class="gap-2" @click="showAddMenuDialog = true">
                                <Plus class="h-4 w-4" />
                                Add Category
                            </Button>
                        </CardHeader>
                        <CardContent>
                            <div class="space-y-4">
                                <div v-for="category in menuCategories" :key="category.title"
                                    class="rounded-lg border p-4 transition-colors hover:bg-muted/50">
                                    <div class="flex items-center justify-between mb-2">
                                        <h3 class="text-sm font-medium">{{ category.title }}</h3>
                                        <Button variant="ghost" size="sm" @click="addNewCategory">
                                            <PlusCircle class="h-4 w-4" />
                                        </Button>
                                    </div>
                                    <div class="space-y-2">
                                        <div v-for="(item, itemIndex) in category.items" :key="itemIndex"
                                            class="flex items-center justify-between text-sm">
                                            <span class="text-muted-foreground">{{ item }}</span>
                                            <div class="flex items-center gap-1">
                                                <Button variant="ghost" size="sm"
                                                    @click="startEditingMenuItem(category.title, item)">
                                                    <Edit2 class="h-3 w-3" />
                                                </Button>
                                                <Button variant="ghost" size="sm"
                                                    @click="confirmDeleteMenuItem(category.title, item)">
                                                    <Trash2 class="h-3 w-3" />
                                                </Button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </TabsContent>

                <!-- Package Management Tab -->
                <TabsContent value="packages" class="mt-6">
                    <Card>
                        <CardHeader class="flex flex-row items-center justify-between">
                            <CardTitle>Packages</CardTitle>
                            <Button variant="outline" size="sm" class="gap-2" @click="addNewPackage">
                                <Plus class="h-4 w-4" />
                                Add Package
                            </Button>
                        </CardHeader>
                        <CardContent>
                            <div class="space-y-4">
                                <div v-for="(pkg, index) in packages" :key="index"
                                    class="flex items-center justify-between rounded-lg border p-4 transition-colors hover:bg-muted/50">
                                    <div class="space-y-1">
                                        <p class="text-sm font-medium">{{ pkg.name }}</p>
                                        <p class="text-sm text-muted-foreground">₱{{ pkg.price }} - {{ pkg.meals }}
                                            meals</p>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <Button variant="ghost" size="sm" @click="startEditing(index)">
                                            <Edit2 class="h-4 w-4" />
                                        </Button>
                                        <Button variant="ghost" size="sm" @click="confirmDelete(index)">
                                            <Trash2 class="h-4 w-4" />
                                        </Button>
                                    </div>
                                </div>
                            </div>
                        </CardContent>
                    </Card>
                </TabsContent>
            </Tabs>
        </div>

        <!-- Add Package Dialog -->
        <Dialog v-model:open="showAddDialog">
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>Add New Package</DialogTitle>
                    <DialogDescription>
                        Create a new catering package with details and inclusions.
                    </DialogDescription>
                </DialogHeader>
                <div class="space-y-4 py-4">
                    <div class="space-y-2">
                        <label class="text-sm font-medium">Package Name</label>
                        <Input v-model="newPackage.name" placeholder="Enter package name" />
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-medium">Price</label>
                        <Input v-model="newPackage.price" type="number" placeholder="Enter price" />
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-medium">Number of Meals</label>
                        <Input v-model="newPackage.meals" type="number" placeholder="Enter number of meals" />
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-medium">Inclusions</label>
                        <div class="flex gap-2">
                            <Input v-model="newInclusion" placeholder="Add inclusion" />
                            <Button variant="outline" @click="addNewInclusion">
                                <Plus class="h-4 w-4" />
                            </Button>
                        </div>
                        <div class="flex flex-wrap gap-2 mt-2">
                            <Badge v-for="(inclusion, index) in newPackage.includes" :key="index" variant="secondary">
                                {{ inclusion }}
                                <X class="h-3 w-3 ml-1 cursor-pointer" @click="removeNewInclusion(index)" />
                            </Badge>
                        </div>
                    </div>
                </div>
                <DialogFooter>
                    <Button variant="outline" @click="cancelAddPackage">Cancel</Button>
                    <Button @click="confirmAddPackage">Add Package</Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>

        <!-- Add Menu Dialog -->
        <Dialog v-model:open="showAddMenuDialog">
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>Add Menu Item</DialogTitle>
                    <DialogDescription>
                        Add a new menu category or item to your menu.
                    </DialogDescription>
                </DialogHeader>
                <div class="space-y-4 py-4">
                    <div class="space-y-2">
                        <label class="text-sm font-medium">Type</label>
                        <div class="flex gap-4">
                            <Button variant="outline" :class="{ 'bg-primary/10': menuType === 'category' }"
                                @click="menuType = 'category'">
                                Category
                            </Button>
                            <Button variant="outline" :class="{ 'bg-primary/10': menuType === 'menu' }"
                                @click="menuType = 'menu'">
                                Menu Item
                            </Button>
                        </div>
                    </div>
                    <div v-if="menuType === 'category'" class="space-y-2">
                        <label class="text-sm font-medium">Category Name</label>
                        <Input v-model="newCategory" placeholder="Enter category name" />
                    </div>
                    <div v-else class="space-y-2">
                        <label class="text-sm font-medium">Category</label>
                        <select v-model="selectedCategory" class="w-full rounded-md border p-2">
                            <option value="">Select a category</option>
                            <option v-for="category in menuCategories" :key="category.title" :value="category.title">
                                {{ category.title }}
                            </option>
                        </select>
                    </div>
                    <div v-if="menuType === 'menu'" class="space-y-2">
                        <label class="text-sm font-medium">Menu Item</label>
                        <Input v-model="newMenuItem" placeholder="Enter menu item name" />
                    </div>
                </div>
                <DialogFooter>
                    <Button variant="outline" @click="cancelAddMenu">Cancel</Button>
                    <Button @click="addNewMenu">Add</Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>

        <!-- Delete Confirmation Dialog -->
        <Dialog v-model:open="showDeleteDialog">
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>Delete Item</DialogTitle>
                    <DialogDescription>
                        Are you sure you want to delete this item? This action cannot be undone.
                    </DialogDescription>
                </DialogHeader>
                <DialogFooter>
                    <Button variant="outline" @click="showDeleteDialog = false">Cancel</Button>
                    <Button variant="destructive" @click="deleteMenuItem()">
                        Delete
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>

<style scoped>
.card {
    @apply transition-all duration-200;
}

.card:hover {
    @apply shadow-md;
}
</style>