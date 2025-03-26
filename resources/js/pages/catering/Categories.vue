<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { ref, reactive } from 'vue';
import { PlusCircle, GripVertical } from 'lucide-vue-next';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Manage Catering',
        href: '/catering',
    },
    {
        title: 'Categories',
        href: '/catering/categories',
    }
];

interface MealCategory {
    title: string;
    items: string[];
}

const categories = reactive<MealCategory[]>([
    {
        title: 'Beef',
        items: [
            'BEEF STEAK TAGALOG',
            'BEEF MENUDO',
            'BEEF WITH BROCCOLI',
            'BEEF CALDERETA',
            'SIZZLING BEEF BULALO IN CREAM SAUCE',
            'BEEF WITH MUSHROOM',
            'LENGUA ESTOFADO'
        ]
    },
    {
        title: 'Pork',
        items: [
            'PORK MENUDO',
            'SPICY PORK RIBS',
            'PORK BINAGOONGAN',
            'SWEET AND SOUR PORK',
            'LUMPIA SHANGHAI',
            'PORK STEAK'
        ]
    },
    // ... other categories
]);
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">

        <Head title="Manage Categories" />

        <div class="flex h-full flex-1 flex-col gap-6 p-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold">Meal Categories</h1>
                    <p class="text-sm text-muted-foreground">Manage and organize meal categories</p>
                </div>
                <Button>
                    <PlusCircle class="h-4 w-4 mr-2" />
                    New Category
                </Button>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>All Categories</CardTitle>
                </CardHeader>
                <CardContent>
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead class="w-[40px]"></TableHead>
                                <TableHead>Category Name</TableHead>
                                <TableHead>Number of Dishes</TableHead>
                                <TableHead class="w-[100px]">Actions</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="category in categories" :key="category.title">
                                <TableCell>
                                    <Button variant="ghost" size="icon">
                                        <GripVertical class="h-4 w-4" />
                                    </Button>
                                </TableCell>
                                <TableCell class="font-medium">{{ category.title }}</TableCell>
                                <TableCell>{{ category.items.length }} dishes</TableCell>
                                <TableCell>
                                    <Button variant="ghost" size="sm">Edit</Button>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>