<script setup lang="ts">
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Textarea } from '@/components/ui/textarea';
import { Badge } from '@/components/ui/badge';
import { Checkbox } from '@/components/ui/checkbox';
import { Plus, Edit2, Save, Trash2, X, ChevronLeft, ChevronRight, ChevronsLeft, ChevronsRight, ChevronsUpDown, Download, Eye, EyeOff, MoreVertical, CheckCircle2, XCircle } from 'lucide-vue-next';
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
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';
import { jsPDF } from 'jspdf';
import autoTable from 'jspdf-autotable';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Manage Catering',
        href: '/catering',
    },
    {
        title: 'Packages',
        href: '/catering/packages',
    }
];

interface Package {
    name: string;
    price: number;
    meals: number;
    includes: string[];
    status: 'active' | 'inactive';
    createdAt: string;
}

const packages = ref<Package[]>([
    {
        name: 'PACKAGE A',
        price: 280,
        meals: 5,
        includes: ['Rice & Drinks'],
        status: 'active',
        createdAt: '2024-03-20'
    },
    {
        name: 'PACKAGE B',
        price: 310,
        meals: 6,
        includes: ['Rice & Drinks'],
        status: 'active',
        createdAt: '2024-03-20'
    },
    {
        name: 'PACKAGE C',
        price: 350,
        meals: 7,
        includes: ['Rice & Drinks'],
        status: 'active',
        createdAt: '2024-03-20'
    },
    {
        name: 'PACKAGE D',
        price: 380,
        meals: 8,
        includes: ['Rice & Drinks'],
        status: 'active',
        createdAt: '2024-03-20'
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
        size: 50,
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
        size: 250,
    },
    {
        accessorKey: 'price',
        header: 'Price',
        cell: ({ row, table }) => {
            const index = table.getRowModel().rows.findIndex((r: { id: string }) => r.id === row.id);
            return h('div', { class: 'font-medium pl-4' }, [
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
                        class: 'w-32'
                    })
            ]);
        },
        size: 120,
    },
    {
        accessorKey: 'meals',
        header: 'Meals',
        cell: ({ row, table }) => {
            const index = table.getRowModel().rows.findIndex((r: { id: string }) => r.id === row.id);
            return h('div', { class: 'font-medium' }, [
                !getEditingState(index).value?.isEditing
                    ? `${row.original.meals} meals`
                    : h('div', { class: 'flex items-center gap-2' }, [
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
                            class: 'w-20'
                        }),
                        h('span', 'meals')
                    ])
            ]);
        },
        size: 100,
    },
    {
        accessorKey: 'includes',
        header: 'Inclusions',
        cell: ({ row, table }) => {
            const index = table.getRowModel().rows.findIndex((r: { id: string }) => r.id === row.id);
            return h('div', { class: 'min-w-[120px]' }, [
                !getEditingState(index).value?.isEditing
                    ? h('ul', { class: 'list-disc list-inside space-y-0.5' },
                        row.original.includes.map(item =>
                            h('li', { class: 'text-sm text-muted-foreground' }, item)
                        )
                    )
                    : h('div', { class: 'space-y-1' }, [
                        h('ul', { class: 'space-y-0.5' },
                            row.original.includes.map((item, inclusionIndex) =>
                                h('li', { class: 'flex items-center gap-1' }, [
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
                        h('div', { class: 'flex gap-1' }, [
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
        size: 120,
    },
    {
        accessorKey: 'status',
        header: 'Status',
        cell: ({ row }) => h('div', { class: 'flex items-center gap-2' }, [
            h('div', { class: row.original.status === 'active' ? 'text-green-500' : 'text-red-500' }, [
                row.original.status === 'active' ? h(CheckCircle2, { class: 'h-4 w-4' }) : h(XCircle, { class: 'h-4 w-4' })
            ]),
            h('span', { class: 'capitalize' }, row.original.status)
        ]),
        size: 100,
    },
    {
        accessorKey: 'createdAt',
        header: 'Created At',
        cell: ({ row }) => h('span', {}, new Date(row.original.createdAt).toLocaleDateString()),
        size: 120,
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
                    onClick: () => startEditing(index)
                }, () => h(Edit2, { class: 'h-4 w-4' })),
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
        size: 100,
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

const editingPackage = ref<Package | null>(null);
const showEditDialog = ref(false);

const startEditing = (index: number) => {
    editingPackage.value = { ...packages.value[index] };
    showEditDialog.value = true;
};

const savePackage = () => {
    if (editingPackage.value) {
        const index = packages.value.findIndex(p => p.name === editingPackage.value?.name);
        if (index !== -1) {
            packages.value[index] = { ...editingPackage.value };
        }
        showEditDialog.value = false;
        editingPackage.value = null;
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

const showCreateDialog = ref(false);
const newPackage = ref<Partial<Package>>({
    name: '',
    price: 0,
    meals: 0,
    includes: [],
    status: 'active',
    createdAt: new Date().toISOString().split('T')[0]
});

const createPackage = () => {
    if (newPackage.value.name && newPackage.value.price && newPackage.value.meals) {
        packages.value.push({
            ...newPackage.value as Package,
            includes: newPackage.value.includes || ['Rice & Drinks']
        });
        editingStates.value.push({
            isEditing: false,
            newInclusion: ''
        });
        showCreateDialog.value = false;
        newPackage.value = {
            name: '',
            price: 0,
            meals: 0,
            includes: [],
            status: 'active',
            createdAt: new Date().toISOString().split('T')[0]
        };
    }
};

const togglePackageStatus = (index: number) => {
    if (packages.value[index]) {
        packages.value[index].status = packages.value[index].status === 'active' ? 'inactive' : 'active';
    }
};

const toggleBulkStatus = (status: 'active' | 'inactive') => {
    if (selectedPackages.value.size > 0) {
        packages.value.forEach((pkg, index) => {
            if (selectedPackages.value.has(pkg.name)) {
                packages.value[index].status = status;
            }
        });
    }
};

const exportToCSV = () => {
    const headers = ['Name', 'Price', 'Meals', 'Inclusions', 'Status', 'Created At'];
    const csvContent = [
        headers.join(','),
        ...packages.value.map(pkg => [
            pkg.name,
            pkg.price,
            pkg.meals,
            pkg.includes.join(';'),
            pkg.status,
            pkg.createdAt
        ].join(','))
    ].join('\n');

    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'catering-packages.csv';
    link.click();
};

const exportToPDF = () => {
    const doc = new jsPDF();

    // Add title
    doc.setFontSize(20);
    doc.text('Catering Packages', 14, 15);
    doc.setFontSize(10);
    doc.text('Generated on: ' + new Date().toLocaleDateString(), 14, 22);

    // Prepare data for the table
    const tableData = packages.value.map(pkg => [
        pkg.name,
        `₱${pkg.price.toLocaleString()}`,
        pkg.meals.toString(),
        pkg.includes.join(', '),
        pkg.status.charAt(0).toUpperCase() + pkg.status.slice(1),
        new Date(pkg.createdAt).toLocaleDateString()
    ]);

    // Add the table using autoTable
    autoTable(doc, {
        head: [['Name', 'Price', 'Meals', 'Inclusions', 'Status', 'Created At']],
        body: tableData,
        startY: 30,
        theme: 'grid',
        styles: {
            fontSize: 8,
            cellPadding: 2,
            overflow: 'linebreak'
        },
        headStyles: {
            fillColor: [41, 128, 185],
            textColor: 255,
            fontStyle: 'bold'
        },
        alternateRowStyles: {
            fillColor: [245, 245, 245]
        },
        columnStyles: {
            0: { cellWidth: 30 }, // Name
            1: { cellWidth: 25 }, // Price
            2: { cellWidth: 15 }, // Meals
            3: { cellWidth: 30 }, // Inclusions
            4: { cellWidth: 20 }, // Status
            5: { cellWidth: 25 }  // Created At
        }
    });

    // Save the PDF
    doc.save('catering-packages.pdf');
};

// Add a computed property to check if all selected packages are active
const allSelectedActive = computed(() => {
    return Array.from(selectedPackages.value).every(name => {
        const pkg = packages.value.find(p => p.name === name);
        return pkg?.status === 'active';
    });
});
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">

        <Head title="Catering Packages" />

        <div class="flex h-full flex-1 flex-col gap-12 rounded-xl px-6 py-8 max-w-7xl mx-auto">
            <!-- Header -->
            <div class="text-center">
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Catering Packages </h1>
                <p class="text-lg text-muted-foreground">Overview of packages and meal choices</p>
            </div>

            <!-- Table Section -->
            <div>
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <Input placeholder="Search packages..." v-model="searchQuery"
                                class="max-w-sm border-primary" />
                        </div>
                        <div class="flex items-center gap-2">
                            <Button v-if="selectedPackages.size > 0" variant="outline" size="sm"
                                @click="toggleBulkStatus(allSelectedActive ? 'inactive' : 'active')">
                                <component :is="allSelectedActive ? XCircle : CheckCircle2" class="h-4 w-4 mr-2" />
                                {{ allSelectedActive ? 'Disable' : 'Enable' }} Selected
                            </Button>
                            <Button v-if="selectedPackages.size > 0" variant="destructive" size="sm"
                                @click="confirmDeleteSelected">
                                Delete Selected
                            </Button>
                            <Button variant="default" size="sm" @click="showCreateDialog = true">
                                <Plus class="h-4 w-4 mr-2" />
                                New Package
                            </Button>
                            <DropdownMenu>
                                <DropdownMenuTrigger asChild>
                                    <Button variant="outline" size="sm">
                                        <MoreVertical class="h-4 w-4" />
                                    </Button>
                                </DropdownMenuTrigger>
                                <DropdownMenuContent align="end">
                                    <DropdownMenuItem @click="exportToCSV">
                                        <Download class="h-4 w-4 mr-2" />
                                        Export CSV
                                    </DropdownMenuItem>
                                    <DropdownMenuItem @click="exportToPDF">
                                        <Download class="h-4 w-4 mr-2" />
                                        Export PDF
                                    </DropdownMenuItem>
                                </DropdownMenuContent>
                            </DropdownMenu>
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
                                                    :checked="selectedPackages.size === table.getRowModel().rows.length"
                                                    @update:checked="(checked: boolean) => {
                                                        if (checked) {
                                                            table.getRowModel().rows.forEach(row => selectedPackages.add(row.original.name));
                                                        } else {
                                                            selectedPackages.clear();
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
                                                <Checkbox :checked="selectedPackages.has(row.original.name)"
                                                    @update:checked="(checked: boolean) => {
                                                        if (checked) {
                                                            selectedPackages.add(row.original.name);
                                                        } else {
                                                            selectedPackages.delete(row.original.name);
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

            <!-- Create Package Dialog -->
            <Dialog v-model:open="showCreateDialog">
                <DialogContent class="max-h-[90vh] overflow-y-auto">
                    <DialogHeader>
                        <DialogTitle>Create New Package</DialogTitle>
                        <DialogDescription>
                            Add a new catering package to your menu.
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
                    </div>
                    <DialogFooter>
                        <Button variant="outline" @click="showCreateDialog = false">Cancel</Button>
                        <Button @click="createPackage">Create Package</Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>

            <!-- Edit Package Dialog -->
            <Dialog v-model:open="showEditDialog">
                <DialogContent class="max-h-[90vh] overflow-y-auto">
                    <DialogHeader>
                        <DialogTitle>Edit Package</DialogTitle>
                        <DialogDescription>
                            Modify the package details.
                        </DialogDescription>
                    </DialogHeader>
                    <div class="space-y-4 py-4" v-if="editingPackage">
                        <div class="space-y-2">
                            <label class="text-sm font-medium">Package Name</label>
                            <Input v-model="editingPackage.name" placeholder="Enter package name" />
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium">Price</label>
                            <Input v-model="editingPackage.price" type="number" placeholder="Enter price" />
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium">Number of Meals</label>
                            <Input v-model="editingPackage.meals" type="number" placeholder="Enter number of meals" />
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium">Inclusions</label>
                            <div class="space-y-2">
                                <div v-for="(inclusion, index) in editingPackage.includes" :key="index"
                                    class="flex items-center gap-2">
                                    <Input v-model="editingPackage.includes[index]" placeholder="Enter inclusion" />
                                    <Button variant="ghost" size="icon"
                                        @click="editingPackage.includes.splice(index, 1)">
                                        <X class="h-4 w-4" />
                                    </Button>
                                </div>
                                <Button variant="outline" class="w-full" @click="editingPackage.includes.push('')">
                                    <Plus class="h-4 w-4 mr-2" />
                                    Add Inclusion
                                </Button>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium">Status</label>
                            <div class="flex items-center gap-4">
                                <div class="flex items-center gap-2">
                                    <input type="radio" v-model="editingPackage.status" value="active"
                                        id="status-active" />
                                    <label for="status-active">Active</label>
                                </div>
                                <div class="flex items-center gap-2">
                                    <input type="radio" v-model="editingPackage.status" value="inactive"
                                        id="status-inactive" />
                                    <label for="status-inactive">Inactive</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <DialogFooter>
                        <Button variant="outline" @click="showEditDialog = false">Cancel</Button>
                        <Button @click="savePackage">Save Changes</Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>

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

<style scoped>
.card {
    @apply transition-all duration-200;
}

.card:hover {
    @apply transform -translate-y-1;
}
</style>