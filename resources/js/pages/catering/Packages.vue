<script setup lang="ts">
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Badge } from '@/components/ui/badge';
import { Checkbox } from '@/components/ui/checkbox';
import { Plus, Edit2, Save, Trash2, X, ChevronLeft, ChevronRight, ChevronsLeft, ChevronsRight, ChevronsUpDown } from 'lucide-vue-next';
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
import { ref, computed, h } from 'vue';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Catering Packages',
        href: '/catering/packages',
    }
];

interface Package {
    name: string;
    price: number;
    meals: number;
    includes: string[];
}

const packages = ref<Package[]>([
    {
        name: 'PACKAGE A',
        price: 280,
        meals: 5,
        includes: ['Rice & Drinks']
    },
    {
        name: 'PACKAGE B',
        price: 310,
        meals: 6,
        includes: ['Rice & Drinks']
    },
    {
        name: 'PACKAGE C',
        price: 350,
        meals: 7,
        includes: ['Rice & Drinks']
    },
    {
        name: 'PACKAGE D',
        price: 380,
        meals: 8,
        includes: ['Rice & Drinks']
    }
]);

const selectedPackages = ref<Set<string>>(new Set());
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

const columns: ColumnDef<Package>[] = [
    {
        id: 'select',
        header: () => h(Checkbox, {
            checked: selectedPackages.value.size === table.getRowModel().rows.length,
            'onUpdate:checked': (checked: boolean) => {
                if (checked) {
                    table.getRowModel().rows.forEach(row => selectedPackages.value.add(row.original.name));
                } else {
                    selectedPackages.value.clear();
                }
            }
        }),
        cell: ({ row }) => h(Checkbox, {
            checked: selectedPackages.value.has(row.original.name),
            'onUpdate:checked': (checked: boolean) => {
                if (checked) {
                    selectedPackages.value.add(row.original.name);
                } else {
                    selectedPackages.value.delete(row.original.name);
                }
            }
        }),
        enableSorting: false,
        enableHiding: false,
    },
    {
        accessorKey: 'name',
        header: 'Package Name',
        cell: ({ row, table }) => {
            const index = table.getRowModel().rows.findIndex((r: { id: string }) => r.id === row.id);
            return h('div', { class: 'font-medium' }, [
                !getEditingState(index).value?.isEditing
                    ? row.original.name
                    : h(Input, {
                        modelValue: row.original.name,
                        'onUpdate:modelValue': (val: string | number) => {
                            if (typeof val === 'string') {
                                row.original.name = val;
                            }
                        },
                        class: 'w-full'
                    })
            ]);
        },
    },
    {
        accessorKey: 'price',
        header: 'Price',
        cell: ({ row, table }) => {
            const index = table.getRowModel().rows.findIndex((r: { id: string }) => r.id === row.id);
            return h('div', { class: 'text-right' }, [
                !getEditingState(index).value?.isEditing
                    ? `₱${row.original.price.toLocaleString()}`
                    : h(Input, {
                        modelValue: row.original.price,
                        'onUpdate:modelValue': (val: string | number) => {
                            if (typeof val === 'number') {
                                row.original.price = val;
                            } else {
                                row.original.price = Number(val);
                            }
                        },
                        type: 'number',
                        class: 'w-32 text-right'
                    })
            ]);
        },
    },
    {
        accessorKey: 'meals',
        header: 'Meals',
        cell: ({ row, table }) => {
            const index = table.getRowModel().rows.findIndex((r: { id: string }) => r.id === row.id);
            return h('div', { class: 'text-center' }, [
                !getEditingState(index).value?.isEditing
                    ? `${row.original.meals} meals`
                    : h('div', { class: 'flex items-center justify-center gap-2' }, [
                        h(Input, {
                            modelValue: row.original.meals,
                            'onUpdate:modelValue': (val: string | number) => {
                                if (typeof val === 'number') {
                                    row.original.meals = val;
                                } else {
                                    row.original.meals = Number(val);
                                }
                            },
                            type: 'number',
                            class: 'w-20 text-center'
                        }),
                        h('span', 'meals')
                    ])
            ]);
        },
    },
    {
        accessorKey: 'includes',
        header: 'Inclusions',
        cell: ({ row, table }) => {
            const index = table.getRowModel().rows.findIndex((r: { id: string }) => r.id === row.id);
            return h('div', [
                !getEditingState(index).value?.isEditing
                    ? h('ul', { class: 'list-disc list-inside' },
                        row.original.includes.map(item =>
                            h('li', { class: 'text-sm text-muted-foreground' }, item)
                        )
                    )
                    : h('div', { class: 'space-y-2' }, [
                        h('ul', { class: 'space-y-1' },
                            row.original.includes.map((item, inclusionIndex) =>
                                h('li', { class: 'flex items-center gap-2' }, [
                                    h(Input, {
                                        modelValue: row.original.includes[inclusionIndex],
                                        'onUpdate:modelValue': (val: string | number) => {
                                            if (typeof val === 'string') {
                                                row.original.includes[inclusionIndex] = val;
                                            }
                                        },
                                        class: 'flex-1'
                                    }),
                                    h(Button, {
                                        variant: 'ghost',
                                        size: 'icon',
                                        onClick: () => removeInclusion(index, inclusionIndex)
                                    }, () => h(X, { class: 'h-4 w-4' }))
                                ])
                            )
                        ),
                        h('div', { class: 'flex gap-2' }, [
                            h(Input, {
                                modelValue: getEditingState(index).value.newInclusion,
                                'onUpdate:modelValue': (val: string | number) => {
                                    if (typeof val === 'string') {
                                        getEditingState(index).value.newInclusion = val;
                                    }
                                },
                                placeholder: 'Add new inclusion',
                                onKeyup: (e: KeyboardEvent) => e.key === 'Enter' && addInclusion(index)
                            }),
                            h(Button, {
                                variant: 'outline',
                                size: 'icon',
                                onClick: () => addInclusion(index)
                            }, () => h(Plus, { class: 'h-4 w-4' }))
                        ])
                    ])
            ]);
        },
    },
    {
        id: 'actions',
        header: '',
        cell: ({ row, table }) => {
            const index = table.getRowModel().rows.findIndex((r: { id: string }) => r.id === row.id);
            return h('div', { class: 'flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity' }, [
                h(Button, {
                    variant: 'ghost',
                    size: 'icon',
                    onClick: () => getEditingState(index).value?.isEditing ? savePackage(index) : startEditing(index)
                }, () => !getEditingState(index).value?.isEditing
                    ? h(Edit2, { class: 'h-4 w-4' })
                    : h(Save, { class: 'h-4 w-4' })
                ),
                h(Button, {
                    variant: 'ghost',
                    size: 'icon',
                    class: 'text-destructive',
                    onClick: () => confirmDelete(index)
                }, () => h(Trash2, { class: 'h-4 w-4' }))
            ]);
        },
        enableSorting: false,
        enableHiding: false,
    },
];

const searchQuery = ref('');
const sorting = ref<{ id: string; desc: boolean }[]>([]);
const pagination = ref({
    pageIndex: 0,
    pageSize: 10,
});

const fuzzyFilter: FilterFn<Package> = (row, columnId, filterValue) => {
    const searchValue = filterValue.toLowerCase();
    const value = row.getValue(columnId);
    if (typeof value === 'string') {
        return value.toLowerCase().includes(searchValue);
    }
    return false;
};

const table = useVueTable({
    data: packages,
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

const startEditing = (index: number) => {
    if (editingStates.value[index]) {
        editingStates.value[index].isEditing = true;
    }
};

const savePackage = (index: number) => {
    if (editingStates.value[index]) {
        editingStates.value[index].isEditing = false;
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

const packageToDelete = ref<number | null>(null);
const showDeleteDialog = ref(false);

const confirmDelete = (index: number) => {
    packageToDelete.value = index;
    showDeleteDialog.value = true;
};

const deletePackage = () => {
    if (packageToDelete.value !== null) {
        packages.value.splice(packageToDelete.value, 1);
        editingStates.value.splice(packageToDelete.value, 1);
        showDeleteDialog.value = false;
        packageToDelete.value = null;
    }
};

const confirmDeleteSelected = () => {
    if (selectedPackages.value.size > 0) {
        const indicesToDelete = packages.value
            .map((pkg: Package, index: number) => selectedPackages.value.has(pkg.name) ? index : -1)
            .filter((index: number) => index !== -1)
            .reverse(); // Delete from end to avoid index shifting

        indicesToDelete.forEach((index: number) => {
            packages.value.splice(index, 1);
            editingStates.value.splice(index, 1);
        });

        selectedPackages.value.clear();
    }
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">

        <Head title="Catering Packages" />

        <div class="flex h-full flex-1 flex-col gap-12 rounded-xl px-6 py-8 max-w-7xl mx-auto">
            <!-- Header -->
            <div class="text-center">
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Catering Packages</h1>
                <p class="text-lg text-muted-foreground">Manage your catering packages and their details</p>
            </div>

            <!-- Table Section -->
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <Input placeholder="Search packages..." v-model="searchQuery" class="max-w-sm" />
                    </div>
                    <div class="h-8 flex items-center justify-end">
                        <div v-if="selectedPackages.size > 0" class="flex items-center gap-2">
                            <span class="text-sm text-muted-foreground">
                                {{ selectedPackages.size }} selected
                            </span>
                            <Button variant="destructive" size="sm" @click="confirmDeleteSelected">
                                Delete Selected
                            </Button>
                        </div>
                    </div>
                </div>
                <div class="rounded-md border">
                    <Table>
                        <TableHeader>
                            <TableRow>
                                <TableHead v-for="column in table.getAllColumns()" :key="column.id" :class="{
                                    'w-[50px]': column.id === 'select',
                                    'w-[100px]': column.id === 'actions',
                                    'text-right': column.id === 'price',
                                    'text-center': column.id === 'meals'
                                }">
                                    <template v-if="column.id === 'select'">
                                        <Checkbox :checked="selectedPackages.size === table.getRowModel().rows.length"
                                            @update:checked="(checked: boolean) => {
                                                if (checked) {
                                                    table.getRowModel().rows.forEach(row => selectedPackages.add(row.original.name));
                                                } else {
                                                    selectedPackages.clear();
                                                }
                                            }" />
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
                                    'w-[100px]': column.id === 'actions'
                                }">
                                    <template v-if="typeof column.columnDef.cell === 'function'">
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
                <div class="flex items-center justify-between px-2">
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

            <!-- Delete Confirmation Dialog -->
            <Dialog v-model:open="showDeleteDialog">
                <DialogContent>
                    <DialogHeader>
                        <DialogTitle>Delete Package</DialogTitle>
                        <DialogDescription>
                            Are you sure you want to delete this package? This action cannot be undone.
                        </DialogDescription>
                    </DialogHeader>
                    <DialogFooter>
                        <Button variant="outline" @click="showDeleteDialog = false">Cancel</Button>
                        <Button variant="destructive" @click="deletePackage">Delete</Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>
        </div>
    </AppLayout>
</template>