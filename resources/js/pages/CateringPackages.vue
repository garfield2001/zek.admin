<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head, useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Plus, Pencil, Trash2, ChevronLeft, ChevronRight } from 'lucide-vue-next';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import Badge from '@/components/ui/badge.vue';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Checkbox } from '@/components/ui/checkbox';
import { ref, computed, h } from 'vue';
import {
    useVueTable,
    getCoreRowModel,
    getPaginationRowModel,
    type ColumnDef,
    type Row,
} from '@tanstack/vue-table';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Catering Packages',
        href: '/catering/packages',
    }
];

// Define the package type
interface Package {
    id: number;
    name: string;
    description: string;
    mealLimit: number;
    price: number;
    status: string;
    createdAt: string;
}

// Mock data - Replace with actual data from your backend
const packages = ref<Package[]>([
    {
        id: 1,
        name: 'Package A',
        description: 'Perfect for small gatherings',
        mealLimit: 5,
        price: 280,
        status: 'active',
        createdAt: '2024-03-26',
    },
    {
        id: 2,
        name: 'Package B',
        description: 'Ideal for medium-sized events',
        mealLimit: 8,
        price: 450,
        status: 'active',
        createdAt: '2024-03-26',
    },
    {
        id: 3,
        name: 'Package C',
        description: 'Best for large celebrations',
        mealLimit: 12,
        price: 680,
        status: 'active',
        createdAt: '2024-03-26',
    },
    {
        id: 4,
        name: 'Package D',
        description: 'Premium package for special occasions',
        mealLimit: 15,
        price: 950,
        status: 'active',
        createdAt: '2024-03-26',
    },
    {
        id: 5,
        name: 'Package E',
        description: 'Budget-friendly option for small events',
        mealLimit: 4,
        price: 200,
        status: 'active',
        createdAt: '2024-03-26',
    },
    {
        id: 6,
        name: 'Package F',
        description: 'Family gathering special',
        mealLimit: 6,
        price: 320,
        status: 'active',
        createdAt: '2024-03-26',
    },
    {
        id: 7,
        name: 'Package G',
        description: 'Corporate event package',
        mealLimit: 20,
        price: 1200,
        status: 'active',
        createdAt: '2024-03-26',
    },
    {
        id: 8,
        name: 'Package H',
        description: 'Wedding reception special',
        mealLimit: 25,
        price: 1500,
        status: 'active',
        createdAt: '2024-03-26',
    },
    {
        id: 9,
        name: 'Package I',
        description: 'Birthday celebration package',
        mealLimit: 10,
        price: 550,
        status: 'active',
        createdAt: '2024-03-26',
    },
    {
        id: 10,
        name: 'Package J',
        description: 'Holiday feast package',
        mealLimit: 15,
        price: 850,
        status: 'active',
        createdAt: '2024-03-26',
    },
    {
        id: 11,
        name: 'Package K',
        description: 'Graduation party special',
        mealLimit: 18,
        price: 1000,
        status: 'active',
        createdAt: '2024-03-26',
    },
    {
        id: 12,
        name: 'Package L',
        description: 'Anniversary celebration package',
        mealLimit: 12,
        price: 650,
        status: 'active',
        createdAt: '2024-03-26',
    },
    {
        id: 13,
        name: 'Package M',
        description: 'Team building package',
        mealLimit: 30,
        price: 1800,
        status: 'active',
        createdAt: '2024-03-26',
    },
    {
        id: 14,
        name: 'Package N',
        description: 'Conference catering package',
        mealLimit: 40,
        price: 2500,
        status: 'active',
        createdAt: '2024-03-26',
    },
    {
        id: 15,
        name: 'Package O',
        description: 'Sports event package',
        mealLimit: 35,
        price: 2200,
        status: 'active',
        createdAt: '2024-03-26',
    },
]);

// Selection state
const selectedPackages = ref<number[]>([]);

// Computed properties for selection
const isAllSelected = computed(() => {
    const currentPageRows = table.getRowModel().rows;
    return currentPageRows.length > 0 &&
        currentPageRows.every(row => selectedPackages.value.includes(row.original.id));
});

const isIndeterminate = computed(() => {
    const currentPageRows = table.getRowModel().rows;
    const selectedCurrentPage = currentPageRows.filter(row =>
        selectedPackages.value.includes(row.original.id)
    ).length;
    return selectedCurrentPage > 0 && selectedCurrentPage < currentPageRows.length;
});

// Selection handlers
const toggleSelectAll = () => {
    const currentPageRows = table.getRowModel().rows;
    if (isAllSelected.value) {
        // Remove only current page selections
        selectedPackages.value = selectedPackages.value.filter(id =>
            !currentPageRows.some(row => row.original.id === id)
        );
    } else {
        // Add current page selections
        currentPageRows.forEach(row => {
            if (!selectedPackages.value.includes(row.original.id)) {
                selectedPackages.value.push(row.original.id);
            }
        });
    }
};

const toggleSelect = (id: number) => {
    const index = selectedPackages.value.indexOf(id);
    if (index === -1) {
        selectedPackages.value.push(id);
    } else {
        selectedPackages.value.splice(index, 1);
    }
};

// Table columns definition
const columns: ColumnDef<Package>[] = [
    {
        id: 'select',
        header: () => h(Checkbox, {
            checked: isAllSelected.value,
            indeterminate: isIndeterminate.value,
            onClick: toggleSelectAll,
        }),
        cell: ({ row }) => h(Checkbox, {
            checked: selectedPackages.value.includes(row.original.id),
            onClick: () => toggleSelect(row.original.id),
        }),
        enableSorting: false,
        enableHiding: false,
    },
    {
        accessorKey: 'name',
        header: 'Package Name',
    },
    {
        accessorKey: 'description',
        header: 'Description',
    },
    {
        accessorKey: 'mealLimit',
        header: 'Meal Limit',
        cell: ({ row }) => `${row.original.mealLimit} meals`,
    },
    {
        accessorKey: 'price',
        header: 'Price',
        cell: ({ row }) => `₱${row.original.price.toLocaleString()}`,
    },
    {
        accessorKey: 'status',
        header: 'Status',
        cell: ({ row }) => h(Badge, {
            variant: row.original.status === 'active' ? 'default' : 'secondary',
        }, () => row.original.status),
    },
    {
        accessorKey: 'createdAt',
        header: 'Created At',
    },
    {
        id: 'actions',
        header: () => h('div', { class: 'text-right' }, 'Actions'),
        cell: ({ row }) => h('div', { class: 'flex justify-end gap-2' }, [
            h(Button, {
                variant: 'ghost',
                size: 'sm',
                onClick: () => handleEdit(row.original),
            }, () => h(Pencil, { class: 'h-4 w-4' })),
            h(Button, {
                variant: 'ghost',
                size: 'sm',
                onClick: () => handleDelete(row.original),
            }, () => h(Trash2, { class: 'h-4 w-4 text-red-500' })),
        ]),
        enableSorting: false,
        enableHiding: false,
    },
];

// Initialize table
const table = useVueTable<Package>({
    get data() {
        return packages.value;
    },
    columns,
    getCoreRowModel: getCoreRowModel(),
    getPaginationRowModel: getPaginationRowModel(),
    initialState: {
        pagination: {
            pageSize: 5,
        },
    },
});

// Edit package state
const editingPackage = ref<Package | null>(null);
const isEditDialogOpen = ref(false);

// Delete package state
const deletingPackage = ref<Package | null>(null);
const isDeleteDialogOpen = ref(false);

// Add package state
const isAddDialogOpen = ref(false);
const newPackage = ref<Omit<Package, 'id' | 'createdAt'>>({
    name: '',
    description: '',
    mealLimit: 5,
    price: 0,
    status: 'active',
});

// Handle edit
const handleEdit = (pkg: Package) => {
    editingPackage.value = { ...pkg };
    isEditDialogOpen.value = true;
};

// Handle save edit
const handleSaveEdit = () => {
    if (!editingPackage.value) return;

    const index = packages.value.findIndex(p => p.id === editingPackage.value?.id);
    if (index !== -1) {
        packages.value[index] = { ...editingPackage.value };
    }
    isEditDialogOpen.value = false;
    editingPackage.value = null;
};

// Handle delete
const handleDelete = (pkg: Package) => {
    deletingPackage.value = pkg;
    isDeleteDialogOpen.value = true;
};

// Confirm delete
const confirmDelete = () => {
    if (!deletingPackage.value) return;

    packages.value = packages.value.filter(p => p.id !== deletingPackage.value?.id);
    isDeleteDialogOpen.value = false;
    deletingPackage.value = null;
};

// Handle bulk delete
const handleBulkDelete = () => {
    if (selectedPackages.value.length === 0) return;

    packages.value = packages.value.filter(p => !selectedPackages.value.includes(p.id));
    selectedPackages.value = [];
};

// Handle add package
const handleAddPackage = () => {
    if (!newPackage.value.name || !newPackage.value.description || !newPackage.value.mealLimit || !newPackage.value.price) {
        return;
    }

    const newId = Math.max(...packages.value.map(p => p.id), 0) + 1;
    const newPackageData: Package = {
        ...newPackage.value,
        id: newId,
        createdAt: new Date().toISOString().split('T')[0],
    };

    packages.value = [...packages.value, newPackageData];

    // Reset form
    newPackage.value = {
        name: '',
        description: '',
        mealLimit: 5,
        price: 0,
        status: 'active',
    };
    isAddDialogOpen.value = false;
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">

        <Head title="Catering Packages" />

        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl px-4 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <h2 class="text-xl font-semibold tracking-tight">Catering Packages</h2>
                    <!-- Bulk Actions -->
                    <div v-if="selectedPackages.length > 0"
                        class="h-8 flex items-center gap-2 rounded-md border bg-muted px-3">
                        <span class="text-sm text-muted-foreground">
                            {{ selectedPackages.length }} selected
                        </span>
                        <Button variant="destructive" size="sm" @click="handleBulkDelete">
                            <Trash2 class="mr-2 h-4 w-4" />
                            Delete
                        </Button>
                    </div>
                    <div v-else class="h-8"></div>
                </div>
                <Button @click="isAddDialogOpen = true">
                    <Plus class="mr-2 h-4 w-4" />
                    Add Package
                </Button>
            </div>

            <div class="rounded-md border">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead v-for="header in table.getHeaderGroups()[0].headers" :key="header.id">
                                <component :is="header.column.columnDef.header"
                                    v-if="typeof header.column.columnDef.header === 'function'" :column="header.column"
                                    :header="header" :table="table" />
                                <template v-else>
                                    {{ header.column.columnDef.header }}
                                </template>
                            </TableHead>
                        </TableRow>
                    </TableHeader>
                    <TableBody>
                        <TableRow v-for="row in table.getRowModel().rows" :key="row.id">
                            <TableCell v-for="cell in row.getVisibleCells()" :key="cell.id">
                                <component :is="cell.column.columnDef.cell"
                                    v-if="typeof cell.column.columnDef.cell === 'function'" :cell="cell"
                                    :column="cell.column" :row="row" :table="table" :getValue="cell.getValue"
                                    :renderValue="cell.renderValue" />
                                <template v-else>
                                    {{ cell.getValue() }}
                                </template>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>

            <!-- Pagination -->
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <span class="text-sm text-muted-foreground">
                        Page {{ table.getState().pagination.pageIndex + 1 }} of {{ table.getPageCount() }}
                    </span>
                </div>
                <div class="flex items-center gap-2">
                    <Button variant="outline" size="sm" :disabled="!table.getCanPreviousPage()"
                        @click="table.previousPage()">
                        <ChevronLeft class="h-4 w-4" />
                    </Button>
                    <div class="flex items-center gap-1">
                        <Button v-for="page in table.getPageCount()" :key="page" variant="outline" size="sm"
                            :class="{ 'bg-primary text-primary-foreground': table.getState().pagination.pageIndex === page - 1 }"
                            @click="table.setPageIndex(page - 1)">
                            {{ page }}
                        </Button>
                    </div>
                    <Button variant="outline" size="sm" :disabled="!table.getCanNextPage()" @click="table.nextPage()">
                        <ChevronRight class="h-4 w-4" />
                    </Button>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-sm text-muted-foreground">Rows per page:</span>
                    <select class="rounded-md border p-1 text-sm" :value="table.getState().pagination.pageSize"
                        @change="(e: Event) => table.setPageSize(Number((e.target as HTMLSelectElement).value))">
                        <option v-for="size in [5, 10, 15, 20, 25, 30]" :key="size" :value="size">
                            {{ size }}
                        </option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Edit Dialog -->
        <Dialog v-model:open="isEditDialogOpen">
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>Edit Package</DialogTitle>
                    <DialogDescription>
                        Make changes to the package details below.
                    </DialogDescription>
                </DialogHeader>
                <div v-if="editingPackage" class="grid gap-4 py-4">
                    <div class="grid gap-2">
                        <label class="text-sm font-medium">Package Name</label>
                        <input v-model="editingPackage.name" class="rounded-md border p-2" />
                    </div>
                    <div class="grid gap-2">
                        <label class="text-sm font-medium">Description</label>
                        <input v-model="editingPackage.description" class="rounded-md border p-2" />
                    </div>
                    <div class="grid gap-2">
                        <label class="text-sm font-medium">Meal Limit</label>
                        <input v-model.number="editingPackage.mealLimit" type="number" class="rounded-md border p-2" />
                    </div>
                    <div class="grid gap-2">
                        <label class="text-sm font-medium">Price</label>
                        <input v-model.number="editingPackage.price" type="number" class="rounded-md border p-2" />
                    </div>
                </div>
                <DialogFooter>
                    <Button variant="outline" @click="isEditDialogOpen = false">Cancel</Button>
                    <Button @click="handleSaveEdit">Save changes</Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>

        <!-- Delete Dialog -->
        <Dialog v-model:open="isDeleteDialogOpen">
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>Delete Package</DialogTitle>
                    <DialogDescription>
                        Are you sure you want to delete "{{ deletingPackage?.name }}"? This action cannot be undone.
                    </DialogDescription>
                </DialogHeader>
                <DialogFooter>
                    <Button variant="outline" @click="isDeleteDialogOpen = false">Cancel</Button>
                    <Button variant="destructive" @click="confirmDelete">Delete</Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>

        <!-- Add Package Dialog -->
        <Dialog v-model:open="isAddDialogOpen">
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>Add New Package</DialogTitle>
                    <DialogDescription>
                        Fill in the details for the new package.
                    </DialogDescription>
                </DialogHeader>
                <div class="grid gap-4 py-4">
                    <div class="grid gap-2">
                        <label class="text-sm font-medium">Package Name</label>
                        <input v-model="newPackage.name" class="rounded-md border p-2"
                            placeholder="Enter package name" />
                    </div>
                    <div class="grid gap-2">
                        <label class="text-sm font-medium">Description</label>
                        <input v-model="newPackage.description" class="rounded-md border p-2"
                            placeholder="Enter package description" />
                    </div>
                    <div class="grid gap-2">
                        <label class="text-sm font-medium">Meal Limit</label>
                        <input v-model.number="newPackage.mealLimit" type="number" min="1"
                            class="rounded-md border p-2" />
                    </div>
                    <div class="grid gap-2">
                        <label class="text-sm font-medium">Price</label>
                        <input v-model.number="newPackage.price" type="number" min="0" class="rounded-md border p-2" />
                    </div>
                </div>
                <DialogFooter>
                    <Button variant="outline" @click="isAddDialogOpen = false">Cancel</Button>
                    <Button @click="handleAddPackage">Add Package</Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>
