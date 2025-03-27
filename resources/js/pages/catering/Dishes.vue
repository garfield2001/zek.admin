<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { ref, reactive, computed, h } from 'vue';
import { Checkbox } from '@/components/ui/checkbox';
import { PlusCircle, Edit2, Save, Trash2, X, ChevronLeft, ChevronRight, ChevronsLeft, ChevronsRight, ChevronsUpDown, MoreVertical, Plus, Image as ImageIcon, ExternalLink, Search } from 'lucide-vue-next';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import {
    useVueTable,
    getCoreRowModel,
    getPaginationRowModel,
    getFilteredRowModel,
    getSortedRowModel,
    type ColumnDef,
    type FilterFn,
} from '@tanstack/vue-table';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Badge } from '@/components/ui/badge';

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
    image_url?: string;
    createdAt: string;
}

// Add categories management
const categories = ref<string[]>([
    'Beef',
    'Pork',
    'Chicken',
    'Seafood',
    'Vegetables',
    'Dessert'
]);

const newCategory = ref('');
const showAddCategoryInput = ref(false);

const addNewCategory = () => {
    if (newCategory.value.trim() && !categories.value.includes(newCategory.value.trim())) {
        categories.value.push(newCategory.value.trim());
        newCategory.value = '';
        showAddCategoryInput.value = false;
    }
};

// Compute category statistics
const categoryStats = computed(() => {
    const stats = new Map<string, number>();
    categories.value.forEach(category => {
        stats.set(category, dishes.value.filter(dish => dish.category === category).length);
    });
    return stats;
});

const dishes = ref<Dish[]>([
    {
        name: 'BEEF STEAK TAGALOG',
        category: 'Beef',
        description: 'Classic Filipino beef steak',
        image_url: 'https://images.unsplash.com/photo-1504674900247-0877df9cc836',
        createdAt: '2024-03-20'
    },
    {
        name: 'BEEF MENUDO',
        category: 'Beef',
        description: 'Traditional Filipino beef stew',
        image_url: 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd',
        createdAt: '2024-03-20'
    },
    {
        name: 'BEEF WITH BROCCOLI',
        category: 'Beef',
        description: 'Stir-fried beef with broccoli',
        image_url: 'https://images.unsplash.com/photo-1467003909585-2f8a72700288',
        createdAt: '2024-03-20'
    }
]);

const selectedDishes = ref<Set<string>>(new Set());
const searchQuery = ref('');
const selectedCategory = ref<string>('');
const sorting = ref<{ id: string; desc: boolean }[]>([]);
const pagination = ref({
    pageIndex: 0,
    pageSize: 10,
});

// Enhanced filtering to include category filter
const fuzzyFilter: FilterFn<Dish> = (row, columnId, filterValue) => {
    const searchValue = filterValue.toLowerCase();
    const value = row.getValue(columnId);

    // Category filter
    if (selectedCategory.value && row.original.category !== selectedCategory.value) {
        return false;
    }

    if (typeof value === 'string') {
        return value.toLowerCase().includes(searchValue);
    }
    return false;
};

const columns: ColumnDef<Dish>[] = [
    {
        id: 'select',
        header: ({ table }) => h('div', { class: 'px-1' }, [
            h(Checkbox, {
                checked: selectedDishes.value.size === table.getRowModel().rows.length,
                'onUpdate:checked': (checked: boolean) => {
                    if (checked) {
                        table.getRowModel().rows.forEach(row => selectedDishes.value.add(row.original.name));
                    } else {
                        selectedDishes.value.clear();
                    }
                }
            })
        ]),
        cell: ({ row }) => h('div', { class: 'px-1' }, [
            h(Checkbox, {
                checked: selectedDishes.value.has(row.original.name),
                'onUpdate:checked': (checked: boolean) => {
                    if (checked) {
                        selectedDishes.value.add(row.original.name);
                    } else {
                        selectedDishes.value.delete(row.original.name);
                    }
                }
            })
        ]),
        enableSorting: false,
        enableHiding: false,
        size: 50,
    },
    {
        accessorKey: 'name',
        header: 'Dish Name',
        cell: ({ row }) => h('div', { class: 'font-medium' }, row.original.name),
        size: 250,
    },
    {
        accessorKey: 'category',
        header: 'Category',
        cell: ({ row }) => h(Badge, {
            variant: 'secondary',
            class: 'font-medium'
        }, () => row.original.category),
        size: 150,
    },
    {
        accessorKey: 'description',
        header: 'Description',
        cell: ({ row }) => h('div', { class: 'text-muted-foreground' }, row.original.description || 'No description'),
        size: 300,
    },
    {
        accessorKey: 'createdAt',
        header: 'Created At',
        cell: ({ row }) => h('span', {}, new Date(row.original.createdAt).toLocaleDateString()),
        size: 120,
    },
    {
        accessorKey: 'image_url',
        header: 'Image',
        cell: ({ row }) => h('div', { class: 'flex items-center gap-2' }, [
            row.original.image_url
                ? [
                    h('img', {
                        src: row.original.image_url,
                        class: 'h-12 w-12 object-cover rounded-md cursor-pointer hover:ring-2 hover:ring-primary transition-all',
                        alt: row.original.name,
                        onClick: () => previewDishImage(row.original.image_url!)
                    }),
                    h(Search, {
                        class: 'h-4 w-4 text-muted-foreground cursor-pointer hover:text-primary transition-colors',
                        onClick: () => previewDishImage(row.original.image_url!)
                    })
                ]
                : h('div', {
                    class: 'h-12 w-12 rounded-md bg-muted flex items-center justify-center'
                }, [
                    h(ImageIcon, { class: 'h-6 w-6 text-muted-foreground' })
                ])
        ]),
        size: 120,
    },
    {
        id: 'actions',
        header: '',
        cell: ({ row }) => h('div', { class: 'flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity' }, [
            h(Button, {
                variant: 'ghost',
                size: 'icon',
                onClick: () => startEditing(row.original)
            }, () => h(Edit2, { class: 'h-4 w-4' })),
            h(Button, {
                variant: 'ghost',
                size: 'icon',
                class: 'text-destructive',
                onClick: () => confirmDelete(row.original)
            }, () => h(Trash2, { class: 'h-4 w-4' }))
        ]),
        enableSorting: false,
        enableHiding: false,
        size: 100,
    },
];

const table = useVueTable({
    data: dishes,
    columns,
    getCoreRowModel: getCoreRowModel(),
    getPaginationRowModel: getPaginationRowModel(),
    getFilteredRowModel: getFilteredRowModel(),
    getSortedRowModel: getSortedRowModel(),
    state: {
        sorting: sorting.value,
        pagination: pagination.value,
        globalFilter: searchQuery.value,
    },
    onSortingChange: (updater) => {
        sorting.value = typeof updater === 'function' ? updater(sorting.value) : updater;
    },
    onPaginationChange: (updater) => {
        pagination.value = typeof updater === 'function' ? updater(pagination.value) : updater;
    },
    onGlobalFilterChange: (updater) => {
        searchQuery.value = typeof updater === 'function' ? updater(searchQuery.value) : updater;
    },
    globalFilterFn: fuzzyFilter,
});

const showCreateDialog = ref(false);
const showEditDialog = ref(false);
const showDeleteDialog = ref(false);
const editingDish = ref<Dish | null>(null);
const dishToDelete = ref<Dish | null>(null);

const newDish = ref<Partial<Dish>>({
    name: '',
    category: '',
    description: '',
    image_url: '',
    createdAt: new Date().toISOString().split('T')[0]
});

const showImagePreview = ref(false);
const previewImage = ref<string>('');

const previewDishImage = (imageUrl: string) => {
    if (imageUrl) {
        previewImage.value = imageUrl;
        showImagePreview.value = true;
    }
};

const validateImageUrl = (url: string) => {
    try {
        new URL(url);
        return true;
    } catch {
        return false;
    }
};

const startEditing = (dish: Dish) => {
    editingDish.value = { ...dish };
    showEditDialog.value = true;
};

const confirmDelete = (dish: Dish) => {
    dishToDelete.value = dish;
    showDeleteDialog.value = true;
};

const createDish = () => {
    if (newDish.value.name && newDish.value.category) {
        dishes.value.push({
            ...newDish.value as Dish,
            createdAt: new Date().toISOString().split('T')[0]
        });
        showCreateDialog.value = false;
        newDish.value = {
            name: '',
            category: '',
            description: '',
            image_url: '',
            createdAt: new Date().toISOString().split('T')[0]
        };
    }
};

const saveDish = () => {
    if (editingDish.value) {
        const index = dishes.value.findIndex(d => d.name === editingDish.value?.name);
        if (index !== -1) {
            dishes.value[index] = { ...editingDish.value };
        }
        showEditDialog.value = false;
        editingDish.value = null;
    }
};

const deleteDish = () => {
    if (dishToDelete.value) {
        const index = dishes.value.findIndex(d => d.name === dishToDelete.value?.name);
        if (index !== -1) {
            dishes.value.splice(index, 1);
        }
        showDeleteDialog.value = false;
        dishToDelete.value = null;
    }
};

const confirmDeleteSelected = () => {
    if (selectedDishes.value.size > 0) {
        const indicesToDelete = dishes.value
            .map((dish, index) => selectedDishes.value.has(dish.name) ? index : -1)
            .filter(index => index !== -1)
            .reverse();

        indicesToDelete.forEach(index => {
            dishes.value.splice(index, 1);
        });

        selectedDishes.value.clear();
    }
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">

        <Head title="Manage Dishes" />

        <div class="flex h-full flex-1 flex-col gap-12 rounded-xl px-6 py-8 max-w-7xl mx-auto">
            <!-- Header -->
            <div class="text-center">
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Dishes</h1>
                <p class="text-lg text-muted-foreground">Manage all available dishes</p>
            </div>

            <!-- Category Stats -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                <Card v-for="[category, count] in categoryStats" :key="category"
                    class="cursor-pointer hover:bg-muted/50 transition-colors"
                    :class="{ 'ring-2 ring-primary': selectedCategory === category }"
                    @click="selectedCategory = selectedCategory === category ? '' : category">
                    <CardHeader class="p-4">
                        <CardTitle class="text-sm font-medium">{{ category }}</CardTitle>
                    </CardHeader>
                    <CardContent class="p-4 pt-0">
                        <p class="text-2xl font-bold">{{ count }}</p>
                        <p class="text-xs text-muted-foreground">dishes</p>
                    </CardContent>
                </Card>
            </div>

            <!-- Table Section -->
            <div>
                <div class="space-y-4">
            <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <Input placeholder="Search dishes..." v-model="searchQuery"
                                class="max-w-sm border-primary" />
                </div>
                        <div class="flex items-center gap-2">
                            <Button v-if="selectedDishes.size > 0" variant="destructive" size="sm"
                                @click="confirmDeleteSelected">
                                Delete Selected
                            </Button>
                            <Button variant="default" size="sm" @click="showCreateDialog = true">
                                <Plus class="h-4 w-4 mr-2" />
                    New Dish
                </Button>
            </div>
                    </div>
                    <div class="rounded-md border">
                        <div class="relative w-full overflow-auto">
                    <Table>
                        <TableHeader>
                                    <TableRow class="bg-muted/50">
                                        <TableHead v-for="column in table.getAllColumns()" :key="column.id" :class="{
                                            'w-[50px]': column.id === 'select',
                                            'w-[100px]': column.id === 'actions',
                                            'bg-muted/50': true
                                        }">
                                            <template v-if="column.id === 'select'">
                                                <Checkbox
                                                    :checked="selectedDishes.size === table.getRowModel().rows.length"
                                                    @update:checked="(checked: boolean) => {
                                                        if (checked) {
                                                            table.getRowModel().rows.forEach(row => selectedDishes.add(row.original.name));
                                                        } else {
                                                            selectedDishes.clear();
                                                        }
                                                    }" class="border-primary" />
                                            </template>
                                            <template v-else>
                                                <div class="flex items-center gap-2">
                                                    {{ column.columnDef.header }}
                                                    <Button v-if="column.getCanSort()" variant="ghost" size="icon"
                                                        class="h-8 w-8" @click="column.getToggleSortingHandler()">
                                                        <ChevronsUpDown class="h-4 w-4" />
                                                    </Button>
                                                </div>
                                            </template>
                                        </TableHead>
                            </TableRow>
                        </TableHeader>
                        <TableBody>
                                    <TableRow v-for="row in table.getRowModel().rows" :key="row.id"
                                        class="group hover:bg-muted/50 transition-colors">
                                        <TableCell v-for="column in table.getAllColumns()" :key="column.id" :class="{
                                            'w-[50px]': column.id === 'select',
                                            'w-[100px]': column.id === 'actions',
                                            'text-left': true,
                                            'px-4': true
                                        }">
                                            <template v-if="column.id === 'select'">
                                                <Checkbox :checked="selectedDishes.has(row.original.name)"
                                                    @update:checked="(checked: boolean) => {
                                                        if (checked) {
                                                            selectedDishes.add(row.original.name);
                                                        } else {
                                                            selectedDishes.delete(row.original.name);
                                                        }
                                                    }" class="border-primary" />
                                            </template>
                                            <template v-else-if="typeof column.columnDef.cell === 'function'">
                                                <component :is="column.columnDef.cell({
                                                    row,
                                                    table,
                                                    column,
                                                    getValue: () => row.getValue(column.id),
                                                    renderValue: () => row.getValue(column.id),
                                                    cell: row.getVisibleCells().find(cell => cell.column.id === column.id) || row.getVisibleCells()[0]
                                                })" />
                                            </template>
                                            <template v-else>
                                                {{ column.columnDef.cell }}
                                            </template>
                                </TableCell>
                            </TableRow>
                        </TableBody>
                    </Table>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between mt-4">
                    <div class="flex-1 text-sm text-muted-foreground">
                        {{ table.getFilteredRowModel().rows.length }} row(s) total
                    </div>
                    <div class="flex items-center space-x-6 lg:space-x-8">
                        <div class="flex items-center space-x-2">
                            <p class="text-sm font-medium">Rows per page</p>
                            <select :value="table.getState().pagination.pageSize"
                                @change="e => table.setPageSize(Number((e.target as HTMLSelectElement).value))"
                                class="h-8 w-[70px] rounded-md border border-input bg-background px-2 py-2 text-sm">
                                <option v-for="pageSize in [10, 20, 30, 40, 50]" :key="pageSize" :value="pageSize">
                                    {{ pageSize }}
                                </option>
                            </select>
                        </div>
                        <div class="flex w-[100px] items-center justify-center text-sm font-medium">
                            Page {{ table.getState().pagination.pageIndex + 1 }} of {{ table.getPageCount() }}
                        </div>
                        <div class="flex items-center space-x-2">
                            <Button variant="outline" class="hidden h-8 w-8 p-0 lg:flex"
                                :disabled="!table.getCanPreviousPage()" @click="table.setPageIndex(0)">
                                <span class="sr-only">Go to first page</span>
                                <ChevronsLeft class="h-4 w-4" />
                            </Button>
                            <Button variant="outline" class="h-8 w-8 p-0" :disabled="!table.getCanPreviousPage()"
                                @click="table.previousPage()">
                                <span class="sr-only">Go to previous page</span>
                                <ChevronLeft class="h-4 w-4" />
                            </Button>
                            <Button variant="outline" class="h-8 w-8 p-0" :disabled="!table.getCanNextPage()"
                                @click="table.nextPage()">
                                <span class="sr-only">Go to next page</span>
                                <ChevronRight class="h-4 w-4" />
                            </Button>
                            <Button variant="outline" class="hidden h-8 w-8 p-0 lg:flex"
                                :disabled="!table.getCanNextPage()"
                                @click="table.setPageIndex(table.getPageCount() - 1)">
                                <span class="sr-only">Go to last page</span>
                                <ChevronsRight class="h-4 w-4" />
                            </Button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Create Dish Dialog -->
            <Dialog v-model:open="showCreateDialog">
                <DialogContent class="max-h-[90vh] overflow-y-auto">
                    <DialogHeader>
                        <DialogTitle>Create New Dish</DialogTitle>
                        <DialogDescription>
                            Add a new dish to your menu.
                        </DialogDescription>
                    </DialogHeader>
                    <div class="space-y-4 py-4">
                        <div class="space-y-2">
                            <label class="text-sm font-medium">Dish Name</label>
                            <Input v-model="newDish.name" placeholder="Enter dish name" />
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium">Category</label>
                            <div class="flex gap-2">
                                <Select v-model="newDish.category" class="flex-1">
                                    <SelectTrigger>
                                        <SelectValue placeholder="Select category" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem v-for="category in categories" :key="category" :value="category">
                                            {{ category }}
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                                <Button variant="outline" size="icon" @click="showAddCategoryInput = true"
                                    v-if="!showAddCategoryInput">
                                    <Plus class="h-4 w-4" />
                                </Button>
                            </div>
                            <div v-if="showAddCategoryInput" class="flex gap-2 mt-2">
                                <Input v-model="newCategory" placeholder="New category name" class="flex-1" />
                                <Button variant="default" size="sm" @click="addNewCategory">Add</Button>
                                <Button variant="ghost" size="icon" @click="showAddCategoryInput = false">
                                    <X class="h-4 w-4" />
                                </Button>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium">Description</label>
                            <Input v-model="newDish.description" placeholder="Enter description" />
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium">Image URL</label>
                            <div class="flex gap-2">
                                <Input v-model="newDish.image_url" placeholder="Enter image URL"
                                    :class="{ 'border-red-500': newDish.image_url && !validateImageUrl(newDish.image_url) }" />
                                <Button variant="outline" size="icon"
                                    @click="() => newDish.image_url && previewDishImage(newDish.image_url)"
                                    :disabled="!newDish.image_url || !validateImageUrl(newDish.image_url)">
                                    <Search class="h-4 w-4" />
                                </Button>
                            </div>
                            <p v-if="newDish.image_url && !validateImageUrl(newDish.image_url)"
                                class="text-sm text-red-500">
                                Please enter a valid image URL
                            </p>
                            <div v-if="newDish.image_url && validateImageUrl(newDish.image_url)" class="mt-2">
                                <img :src="newDish.image_url" class="h-32 w-32 object-cover rounded-lg"
                                    :alt="newDish.name || 'New dish'" />
                            </div>
                            <p class="text-sm text-muted-foreground mt-1">
                                Tip: Use high-quality images from services like Unsplash or your own hosted images.
                            </p>
                        </div>
                    </div>
                    <DialogFooter>
                        <Button variant="outline" @click="showCreateDialog = false">Cancel</Button>
                        <Button @click="createDish" :disabled="!newDish.name?.trim() || !newDish.category?.trim()">
                            Create Dish
                        </Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>

            <!-- Edit Dish Dialog -->
            <Dialog v-model:open="showEditDialog">
                <DialogContent class="max-h-[90vh] overflow-y-auto">
                    <DialogHeader>
                        <DialogTitle>Edit Dish</DialogTitle>
                        <DialogDescription>
                            Modify the dish details.
                        </DialogDescription>
                    </DialogHeader>
                    <div class="space-y-4 py-4" v-if="editingDish">
                        <div class="space-y-2">
                            <label class="text-sm font-medium">Dish Name</label>
                            <Input v-model="editingDish.name" placeholder="Enter dish name" />
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium">Category</label>
                            <div class="flex gap-2">
                                <Select v-model="editingDish.category" class="flex-1">
                                    <SelectTrigger>
                                        <SelectValue placeholder="Select category" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectItem v-for="category in categories" :key="category" :value="category">
                                            {{ category }}
                                        </SelectItem>
                                    </SelectContent>
                                </Select>
                                <Button variant="outline" size="icon" @click="showAddCategoryInput = true"
                                    v-if="!showAddCategoryInput">
                                    <Plus class="h-4 w-4" />
                                </Button>
                            </div>
                            <div v-if="showAddCategoryInput" class="flex gap-2 mt-2">
                                <Input v-model="newCategory" placeholder="New category name" class="flex-1" />
                                <Button variant="default" size="sm" @click="addNewCategory">Add</Button>
                                <Button variant="ghost" size="icon" @click="showAddCategoryInput = false">
                                    <X class="h-4 w-4" />
                                </Button>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium">Description</label>
                            <Input v-model="editingDish.description" placeholder="Enter description" />
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium">Image URL</label>
                            <div class="flex gap-2">
                                <Input v-model="editingDish.image_url" placeholder="Enter image URL"
                                    :class="{ 'border-red-500': editingDish.image_url && !validateImageUrl(editingDish.image_url) }" />
                                <Button variant="outline" size="icon"
                                    @click="() => editingDish.image_url && previewDishImage(editingDish.image_url)"
                                    :disabled="!editingDish.image_url || !validateImageUrl(editingDish.image_url)">
                                    <Search class="h-4 w-4" />
                                </Button>
                            </div>
                            <p v-if="editingDish.image_url && !validateImageUrl(editingDish.image_url)"
                                class="text-sm text-red-500">
                                Please enter a valid image URL
                            </p>
                            <div v-if="editingDish.image_url && validateImageUrl(editingDish.image_url)" class="mt-2">
                                <img :src="editingDish.image_url" class="h-32 w-32 object-cover rounded-lg"
                                    :alt="editingDish.name" />
                            </div>
                            <p class="text-sm text-muted-foreground mt-1">
                                Tip: Use high-quality images from services like Unsplash or your own hosted images.
                            </p>
                        </div>
                    </div>
                    <DialogFooter>
                        <Button variant="outline" @click="showEditDialog = false">Cancel</Button>
                        <Button @click="saveDish"
                            :disabled="!editingDish?.name?.trim() || !editingDish?.category?.trim()">
                            Save Changes
                        </Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>

            <!-- Delete Confirmation Dialog -->
            <Dialog v-model:open="showDeleteDialog">
                <DialogContent>
                    <DialogHeader>
                        <DialogTitle>Delete Dish</DialogTitle>
                        <DialogDescription>
                            Are you sure you want to delete this dish? This action cannot be undone.
                        </DialogDescription>
                    </DialogHeader>
                    <DialogFooter>
                        <Button variant="outline" @click="showDeleteDialog = false">Cancel</Button>
                        <Button variant="destructive" @click="deleteDish">Delete</Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>

            <!-- Image Preview Dialog -->
            <Dialog v-model:open="showImagePreview">
                <DialogContent class="sm:max-w-xl">
                    <DialogHeader>
                        <DialogTitle>Image Preview</DialogTitle>
                    </DialogHeader>
                    <div class="relative aspect-video w-full overflow-hidden rounded-lg">
                        <img :src="previewImage" class="w-full h-full object-contain" :alt="previewImage" />
                    </div>
                    <DialogFooter>
                        <Button variant="outline" @click="showImagePreview = false">Close</Button>
                        <Button variant="default" asChild>
                            <a :href="previewImage" target="_blank" rel="noopener noreferrer">
                                <ExternalLink class="h-4 w-4 mr-2" />
                                Open Original
                            </a>
                        </Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>
        </div>
    </AppLayout>
</template>