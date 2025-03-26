<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { ref, reactive } from 'vue';
import { PlusCircle } from 'lucide-vue-next';
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
        title: 'Dishes',
        href: '/catering/dishes',
    }
];

interface Dish {
    name: string;
    category: string;
    description?: string;
}

// Convert the meal categories data into a flat list of dishes
const dishes = reactive<Dish[]>([
    { name: 'BEEF STEAK TAGALOG', category: 'Beef' },
    { name: 'BEEF MENUDO', category: 'Beef' },
    { name: 'BEEF WITH BROCCOLI', category: 'Beef' },
    { name: 'BEEF CALDERETA', category: 'Beef' },
    { name: 'SIZZLING BEEF BULALO IN CREAM SAUCE', category: 'Beef' },
    { name: 'BEEF WITH MUSHROOM', category: 'Beef' },
    { name: 'LENGUA ESTOFADO', category: 'Beef' },
    { name: 'PORK MENUDO', category: 'Pork' },
    { name: 'SPICY PORK RIBS', category: 'Pork' },
    { name: 'PORK BINAGOONGAN', category: 'Pork' },
    // ... other dishes
]);
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">

        <Head title="Manage Dishes" />

        <div class="flex h-full flex-1 flex-col gap-6 p-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-semibold">Dishes</h1>
                    <p class="text-sm text-muted-foreground">Manage all available dishes</p>
                </div>
                <Button>
                    <PlusCircle class="h-4 w-4 mr-2" />
                    New Dish
                </Button>
            </div>

            <Card>
                <CardHeader>
                    <CardTitle>All Dishes</CardTitle>
                </CardHeader>
                <CardContent>
                    <div class="flex items-center gap-3 mb-4">
                        <Input placeholder="Search dishes..." class="max-w-sm" />
                    </div>
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead>Dish Name</TableHead>
                                <TableHead>Category</TableHead>
                                <TableHead>Description</TableHead>
                                <TableHead class="w-[100px]">Actions</TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                            <TableRow v-for="dish in dishes" :key="dish.name">
                                <TableCell class="font-medium">{{ dish.name }}</TableCell>
                                <TableCell>{{ dish.category }}</TableCell>
                                <TableCell class="text-muted-foreground">
                                    {{ dish.description || 'No description' }}
                                </TableCell>
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