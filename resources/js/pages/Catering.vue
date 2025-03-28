<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { ref, onMounted, computed, h } from 'vue';
import HeadingSmall from '@/components/HeadingSmall.vue';
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
    Image as ImageIcon, ExternalLink, Search, AlertTriangle
} from 'lucide-vue-next';
import { Link } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import { toast } from 'sonner';
import { Input } from '@/components/ui/input';
import { Checkbox } from '@/components/ui/checkbox';
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
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Badge } from '@/components/ui/badge';

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
    todayEvents: 0,
    todayGuests: 0,
    activeOrders: 0,
    pendingOrders: 0,
    todayRevenue: 0,
    revenueTrend: '+0% from last month',
    lowStockItems: 0,
    criticalItems: 0,
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
    menus: Menu[];
}

interface Menu {
    id: number;
    name: string;
    description?: string;
    price: number;
    is_available: boolean;
    menu_type_id: number;
    menu_type: MenuType;
    category: string;
    image_url?: string;
    createdAt: string;
    quantity?: number;
}

interface Package {
    id: number;
    name: string;
    description?: string;
    price: number;
    meal_limit: number;
    minimum_guests: number;
    maximum_guests: number;
    status: 'active' | 'inactive';
    inclusions: string[];
    image_url?: string;
    createdAt: string;
}

interface BackendData {
    initialPackages: any[];
    initialMenuTypes: MenuType[];
    initialMenus: Menu[];
    initialMinimumPersons: number;
}

// Menu management data with proper typing
const menuData = ref({
    menuTypes: [] as MenuType[],
    menus: [] as Menu[],
});

// Form data for modals
const newMenu = ref<Partial<Menu>>({
    id: 0,
    name: '',
    description: '',
    price: 0,
    is_available: true,
    menu_type_id: 0,
    category: '',
    image_url: '',
    createdAt: new Date().toISOString().split('T')[0]
});

// Add these after the existing data/refs
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

const menus = ref<Menu[]>([
    {
        id: 1,
        name: 'BEEF STEAK TAGALOG',
        category: 'Beef',
        description: 'Classic Filipino beef steak',
        image_url: 'https://images.unsplash.com/photo-1504674900247-0877df9cc836',
        createdAt: '2024-03-20',
        price: 250,
        is_available: true,
        menu_type_id: 1,
        menu_type: {
            id: 1,
            name: 'Main Course',
            menus: []
        }
    },
    {
        id: 2,
        name: 'BEEF MENUDO',
        category: 'Beef',
        description: 'Traditional Filipino beef stew',
        image_url: 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd',
        createdAt: '2024-03-20',
        price: 200,
        is_available: true,
        menu_type_id: 1,
        menu_type: {
            id: 1,
            name: 'Main Course',
            menus: []
        }
    },
    {
        id: 3,
        name: 'BEEF WITH BROCCOLI',
        category: 'Beef',
        description: 'Stir-fried beef with broccoli',
        image_url: 'https://images.unsplash.com/photo-1467003909585-2f8a72700288',
        createdAt: '2024-03-20',
        price: 220,
        is_available: true,
        menu_type_id: 1,
        menu_type: {
            id: 1,
            name: 'Main Course',
            menus: []
        }
    }
]);

const selectedMenus = ref<Set<string>>(new Set<string>());
const searchQuery = ref('');
const selectedCategory = ref<string>('');
const sorting = ref<{ id: string; desc: boolean }[]>([]);
const pagination = ref({
    pageIndex: 0,
    pageSize: 10,
});

// Add these after the existing computed properties
const categoryStats = computed(() => {
    const stats = new Map<string, number>();
    categories.value.forEach(category => {
        stats.set(category, menus.value.filter(menu => menu.category === category).length);
    });
    return stats;
});

const addNewCategory = () => {
    if (newCategory.value.trim() && !categories.value.includes(newCategory.value.trim())) {
        categories.value.push(newCategory.value.trim());
        newCategory.value = '';
        showAddCategoryInput.value = false;
    }
};

const fuzzyFilter: FilterFn<Menu> = (row, columnId, filterValue) => {
    const searchValue = filterValue.toLowerCase();
    const value = row.getValue(columnId);

    if (selectedCategory.value && row.original.category !== selectedCategory.value) {
        return false;
    }

    if (typeof value === 'string') {
        return value.toLowerCase().includes(searchValue);
    }
    return false;
};

const columns: ColumnDef<Menu>[] = [
    {
        id: 'select',
        header: ({ table }) => h('div', { class: 'px-1' }, [
            h(Checkbox, {
                checked: selectedMenus.value.size === table.getRowModel().rows.length,
                'onUpdate:checked': (checked: boolean) => {
                    if (checked) {
                        table.getRowModel().rows.forEach(row => selectedMenus.value.add(row.original.name));
                    } else {
                        selectedMenus.value.clear();
                    }
                }
            })
        ]),
        cell: ({ row }) => h('div', { class: 'px-1' }, [
            h(Checkbox, {
                checked: selectedMenus.value.has(row.original.name),
                'onUpdate:checked': (checked: boolean) => {
                    if (checked) {
                        selectedMenus.value.add(row.original.name);
                    } else {
                        selectedMenus.value.delete(row.original.name);
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
        header: 'Menu Name',
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
                        onClick: () => previewMenuImage(row.original.image_url!)
                    }),
                    h(Search, {
                        class: 'h-4 w-4 text-muted-foreground cursor-pointer hover:text-primary transition-colors',
                        onClick: () => previewMenuImage(row.original.image_url!)
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
    data: menus,
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
const showImagePreview = ref(false);
const previewImage = ref<string>('');
const editingMenu = ref<Menu | null>(null);
const menuToDelete = ref<Menu | null>(null);

const validateImageUrl = (url: string) => {
    try {
        new URL(url);
        return true;
    } catch {
        return false;
    }
};

const previewMenuImage = (imageUrl: string) => {
    if (imageUrl) {
        previewImage.value = imageUrl;
        showImagePreview.value = true;
    }
};

const startEditing = (menu: Menu) => {
    editingMenu.value = { ...menu };
    showEditDialog.value = true;
};

const confirmDelete = (menu: Menu) => {
    menuToDelete.value = menu;
    showDeleteDialog.value = true;
};

const createMenu = () => {
    if (newMenu.value.name && newMenu.value.category) {
        const newId = Math.max(...menus.value.map(m => m.id)) + 1;
        menus.value.push({
            ...newMenu.value,
            id: newId,
            price: newMenu.value.price || 0,
            is_available: true,
            menu_type_id: 1,
            menu_type: {
                id: 1,
                name: 'Main Course',
                menus: []
            },
            createdAt: new Date().toISOString().split('T')[0]
        } as Menu);
        showCreateDialog.value = false;
        newMenu.value = {
            id: 0,
            name: '',
            category: '',
            description: '',
            image_url: '',
            price: 0,
            is_available: true,
            menu_type_id: 0,
            createdAt: new Date().toISOString().split('T')[0]
        };
    }
};

const saveMenu = () => {
    if (editingMenu.value) {
        const index = menus.value.findIndex(m => m.name === editingMenu.value?.name);
        if (index !== -1) {
            menus.value[index] = { ...editingMenu.value };
        }
        showEditDialog.value = false;
        editingMenu.value = null;
    }
};

const deleteMenu = () => {
    if (menuToDelete.value) {
        const index = menus.value.findIndex(m => m.name === menuToDelete.value?.name);
        if (index !== -1) {
            menus.value.splice(index, 1);
        }
        showDeleteDialog.value = false;
        menuToDelete.value = null;
    }
};

const confirmDeleteSelected = () => {
    if (selectedMenus.value.size > 0) {
        const indicesToDelete = menus.value
            .map((menu, index) => selectedMenus.value.has(menu.name) ? index : -1)
            .filter(index => index !== -1)
            .reverse();

        indicesToDelete.forEach(index => {
            menus.value.splice(index, 1);
        });

        selectedMenus.value.clear();
    }
};

// Add after the menus data
const packages = ref<Package[]>([
    {
        id: 1,
        name: 'BASIC PACKAGE',
        description: 'Perfect for small gatherings',
        price: 350,
        meal_limit: 3,
        minimum_guests: 30,
        maximum_guests: 50,
        status: 'active',
        inclusions: ['Setup', 'Cleanup', 'Basic Decoration'],
        image_url: 'https://images.unsplash.com/photo-1555244162-803834f70033',
        createdAt: '2024-03-20'
    },
    {
        id: 2,
        name: 'PREMIUM PACKAGE',
        description: 'Ideal for medium-sized events',
        price: 450,
        meal_limit: 4,
        minimum_guests: 50,
        maximum_guests: 100,
        status: 'active',
        inclusions: ['Setup', 'Cleanup', 'Premium Decoration', 'Waitstaff'],
        image_url: 'https://images.unsplash.com/photo-1561910733-7a3a1220f11d',
        createdAt: '2024-03-20'
    },
    {
        id: 3,
        name: 'DELUXE PACKAGE',
        description: 'Perfect for large celebrations',
        price: 550,
        meal_limit: 5,
        minimum_guests: 100,
        maximum_guests: 200,
        status: 'active',
        inclusions: ['Setup', 'Cleanup', 'Luxury Decoration', 'Waitstaff', 'Event Coordinator'],
        image_url: 'https://images.unsplash.com/photo-1464366400600-7168b8af9bc3',
        createdAt: '2024-03-20'
    },
]);

// Package management states
const selectedPackages = ref<Set<string>>(new Set<string>());
const packageSearchQuery = ref('');
const packageSorting = ref<{ id: string; desc: boolean }[]>([]);
const packagePagination = ref({
    pageIndex: 0,
    pageSize: 10,
});

// Package management dialogs
const showCreatePackageDialog = ref(false);
const showEditPackageDialog = ref(false);
const showDeletePackageDialog = ref(false);
const showPackageImagePreview = ref(false);
const packagePreviewImage = ref<string>('');
const editingPackage = ref<Package | null>(null);
const packageToDelete = ref<Package | null>(null);

const newPackage = ref<Partial<Package>>({
    id: 0,
    name: '',
    description: '',
    price: 0,
    meal_limit: 3,
    minimum_guests: 30,
    maximum_guests: 50,
    status: 'active',
    inclusions: [],
    image_url: '',
    createdAt: new Date().toISOString().split('T')[0]
});

// Package table columns
const packageColumns: ColumnDef<Package>[] = [
    {
        accessorKey: 'name',
        header: 'Package Name',
        cell: ({ row }) => h('div', { class: 'font-medium' }, row.original.name),
        size: 200,
    },
    {
        accessorKey: 'price',
        header: 'Price per Head',
        cell: ({ row }) => h('div', { class: 'font-medium' }, `₱${row.original.price.toLocaleString()}`),
        size: 120,
    },
    {
        accessorKey: 'meal_limit',
        header: 'Meal Limit',
        cell: ({ row }) => h('div', { class: 'text-muted-foreground' }, `${row.original.meal_limit} meals`),
        size: 100,
    },
    {
        accessorKey: 'minimum_guests',
        header: 'Min. Guests',
        cell: ({ row }) => h('div', { class: 'text-muted-foreground' }, row.original.minimum_guests),
        size: 120,
    },
    {
        accessorKey: 'maximum_guests',
        header: 'Max. Guests',
        cell: ({ row }) => h('div', { class: 'text-muted-foreground' }, row.original.maximum_guests),
        size: 120,
    },
    {
        accessorKey: 'status',
        header: 'Status',
        cell: ({ row }) => h(Badge, {
            variant: row.original.status === 'active' ? 'default' : 'secondary',
            class: row.original.status === 'active' ? 'bg-green-500/10 text-green-500' : 'bg-muted text-muted-foreground'
        }, () => row.original.status),
        size: 100,
    },
    {
        id: 'actions',
        header: '',
        cell: ({ row }) => h('div', { class: 'flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity' }, [
            h(Button, {
                variant: 'ghost',
                size: 'icon',
                onClick: () => startEditingPackage(row.original)
            }, () => h(Edit2, { class: 'h-4 w-4' })),
            h(Button, {
                variant: 'ghost',
                size: 'icon',
                class: 'text-destructive',
                onClick: () => confirmDeletePackage(row.original)
            }, () => h(Trash2, { class: 'h-4 w-4' }))
        ]),
        enableSorting: false,
        enableHiding: false,
        size: 100,
    },
];

// Package table instance
const packageTable = useVueTable({
    data: packages,
    columns: packageColumns,
    getCoreRowModel: getCoreRowModel(),
    getPaginationRowModel: getPaginationRowModel(),
    getFilteredRowModel: getFilteredRowModel(),
    getSortedRowModel: getSortedRowModel(),
    state: {
        sorting: packageSorting.value,
        pagination: packagePagination.value,
        globalFilter: packageSearchQuery.value,
    },
    onSortingChange: (updater) => {
        packageSorting.value = typeof updater === 'function' ? updater(packageSorting.value) : updater;
    },
    onPaginationChange: (updater) => {
        packagePagination.value = typeof updater === 'function' ? updater(packagePagination.value) : updater;
    },
    onGlobalFilterChange: (updater) => {
        packageSearchQuery.value = typeof updater === 'function' ? updater(packageSearchQuery.value) : updater;
    },
});

// Package management functions
const previewPackageImage = (imageUrl: string) => {
    if (imageUrl) {
        packagePreviewImage.value = imageUrl;
        showPackageImagePreview.value = true;
    }
};

const startEditingPackage = (pkg: Package) => {
    editingPackage.value = { ...pkg };
    showEditPackageDialog.value = true;
};

const confirmDeletePackage = (pkg: Package) => {
    packageToDelete.value = pkg;
    showDeletePackageDialog.value = true;
};

const createPackage = () => {
    if (newPackage.value.name) {
        const newId = Math.max(...packages.value.map(p => p.id)) + 1;
        packages.value.push({
            ...newPackage.value,
            id: newId,
            is_available: true,
            included_menus: [],
            createdAt: new Date().toISOString().split('T')[0]
        } as Package);
        showCreatePackageDialog.value = false;
        newPackage.value = {
            id: 0,
            name: '',
            description: '',
            price: 0,
            meal_limit: 3,
            minimum_guests: 30,
            maximum_guests: 50,
            status: 'active',
            inclusions: [],
            image_url: '',
            createdAt: new Date().toISOString().split('T')[0]
        };
    }
};

const savePackage = () => {
    if (editingPackage.value) {
        const index = packages.value.findIndex(p => p.name === editingPackage.value?.name);
        if (index !== -1) {
            packages.value[index] = { ...editingPackage.value };
        }
        showEditPackageDialog.value = false;
        editingPackage.value = null;
    }
};

const deletePackage = () => {
    if (packageToDelete.value) {
        const index = packages.value.findIndex(p => p.name === packageToDelete.value?.name);
        if (index !== -1) {
            packages.value.splice(index, 1);
        }
        showDeletePackageDialog.value = false;
        packageToDelete.value = null;
    }
};

const confirmDeleteSelectedPackages = () => {
    if (selectedPackages.value.size > 0) {
        const indicesToDelete = packages.value
            .map((pkg, index) => selectedPackages.value.has(pkg.name) ? index : -1)
            .filter(index => index !== -1)
            .reverse();

        indicesToDelete.forEach(index => {
            packages.value.splice(index, 1);
        });

        selectedPackages.value.clear();
    }
};

// Add these after the existing interfaces
interface AddonCategory {
    id: number;
    name: string;
    description?: string;
    status: 'active' | 'inactive';
}

interface AddonPackage {
    id: number;
    category_id: number;
    name: string;
    description?: string;
    serving_size: number;
    price?: number;
    status: 'active' | 'inactive';
    image_url?: string;
    createdAt: string;
}

interface AddonPackageMenu {
    id: number;
    package_id: number;
    menu_id: number;
    is_fixed: boolean;
    can_change: boolean;
    price?: number;
    quantity?: number;
    menu?: Menu;
}

// Add after the packages ref
const addonCategories = ref<AddonCategory[]>([
    {
        id: 1,
        name: 'Bellychon Package',
        description: 'Special lechon belly packages with sides',
        status: 'active'
    },
    {
        id: 2,
        name: 'Food Tray Menu',
        description: 'Individual food trays perfect for events',
        status: 'active'
    }
]);

const addonPackages = ref<AddonPackage[]>([
    {
        id: 1,
        category_id: 1,
        name: 'Package A',
        description: 'Lechon Belly 3kgs with sides',
        serving_size: 15,
        price: 4000,
        status: 'active',
        image_url: 'https://images.unsplash.com/photo-1555244162-803834f70033',
        createdAt: '2024-03-20'
    },
    {
        id: 2,
        category_id: 1,
        name: 'Package B',
        description: 'Lechon Belly 3kgs with additional sides',
        serving_size: 15,
        price: 5000,
        status: 'active',
        image_url: 'https://images.unsplash.com/photo-1504674900247-0877df9cc836',
        createdAt: '2024-03-20'
    },
    {
        id: 3,
        category_id: 2,
        name: 'Beef Trays',
        description: 'Selection of beef dishes',
        serving_size: 15,
        status: 'active',
        image_url: 'https://images.unsplash.com/photo-1464366400600-7168b8af9bc3',
        createdAt: '2024-03-20'
    },
    {
        id: 4,
        category_id: 2,
        name: 'Seafood Trays',
        description: 'Selection of seafood specialties',
        serving_size: 15,
        status: 'active',
        image_url: 'https://images.unsplash.com/photo-1464366400600-7168b8af9bc3',
        createdAt: '2024-03-20'
    },
]);

const addonPackageMenus = ref<AddonPackageMenu[]>([
    // Package A menus
    {
        id: 1,
        package_id: 1,
        menu_id: 1,
        is_fixed: true,
        can_change: false,
        quantity: 3,
        menu: {
            id: 1,
            name: 'LECHON BELLY',
            description: 'Crispy lechon belly',
            price: 0,
            is_available: true,
            menu_type_id: 1,
            category: 'Pork',
            menu_type: { id: 1, name: 'Main Course', menus: [] },
            createdAt: '2024-03-20'
        }
    },
    {
        id: 2,
        package_id: 1,
        menu_id: 2,
        is_fixed: false,
        can_change: true,
        menu: {
            id: 2,
            name: 'GARLIC CHICKEN',
            description: 'Savory garlic chicken',
            price: 0,
            is_available: true,
            menu_type_id: 1,
            category: 'Chicken',
            menu_type: { id: 1, name: 'Main Course', menus: [] },
            createdAt: '2024-03-20'
        }
    },
    // Beef Tray menus
    {
        id: 3,
        package_id: 3,
        menu_id: 3,
        is_fixed: false,
        can_change: true,
        price: 1100,
        menu: {
            id: 3,
            name: 'BEEF CALDERETA',
            description: 'Traditional Filipino beef stew',
            price: 1100,
            is_available: true,
            menu_type_id: 1,
            category: 'Beef',
            menu_type: { id: 1, name: 'Main Course', menus: [] },
            createdAt: '2024-03-20'
        }
    },
]);

// Add-ons management states
const selectedAddonCategory = ref<number | null>(null);
const addonSearchQuery = ref('');
const showCreateAddonDialog = ref(false);
const showEditAddonDialog = ref(false);
const showDeleteAddonDialog = ref(false);
const showMenuSelectionDialog = ref(false);
const editingAddonPackage = ref<AddonPackage | null>(null);
const selectedMenusForPackage = ref<Set<number>>(new Set());

const filteredAddonPackages = computed(() => {
    let packages = addonPackages.value;

    if (selectedAddonCategory.value) {
        packages = packages.filter(p => p.category_id === selectedAddonCategory.value);
    }

    if (addonSearchQuery.value) {
        const query = addonSearchQuery.value.toLowerCase();
        packages = packages.filter(p =>
            p.name.toLowerCase().includes(query) ||
            p.description?.toLowerCase().includes(query)
        );
    }

    return packages;
});

const getPackageMenus = (packageId: number) => {
    return addonPackageMenus.value.filter(pm => pm.package_id === packageId);
};

const getCategoryName = (categoryId: number) => {
    return addonCategories.value.find(c => c.id === categoryId)?.name || '';
};

// Add this computed property after the other computed properties
const getPackageCount = (categoryId: number) => {
    return addonPackages.value.filter(p => p.category_id === categoryId).length;
};

// Fetch data on component mount
onMounted(() => {
    router.get('/catering', {}, {
        preserveState: true,
        onSuccess: (page) => {
            const data = page.props as unknown as BackendData;
            stats.value.totalPackages = data.initialPackages?.length || 0;
            stats.value.totalMenuItems = data.initialMenus?.length || 0;

            // Update menu data
            menuData.value.menuTypes = data.initialMenuTypes || [];
            menuData.value.menus = data.initialMenus || [];
        }
    });
});

// Add these methods after the existing methods
const addMenuItemsToPackage = () => {
    if (editingAddonPackage.value) {
        selectedMenusForPackage.value.forEach(menuId => {
            const menu = menus.value.find(m => m.id === menuId);
            if (menu) {
                addonPackageMenus.value.push({
                    id: Math.max(...addonPackageMenus.value.map(pm => pm.id)) + 1,
                    package_id: editingAddonPackage.value!.id,
                    menu_id: menu.id,
                    is_fixed: false,
                    can_change: true,
                    price: menu.price,
                    quantity: menu.quantity || 1,
                    menu: menu
                });
            }
        });
        showMenuSelectionDialog.value = false;
        selectedMenusForPackage.value.clear();
    }
};

const saveAddonPackage = () => {
    if (editingAddonPackage.value) {
        const index = addonPackages.value.findIndex(p => p.id === editingAddonPackage.value?.id);
        if (index !== -1) {
            addonPackages.value[index] = { ...editingAddonPackage.value };
        }
        showEditAddonDialog.value = false;
        editingAddonPackage.value = null;
    }
};

const deleteAddonPackage = () => {
    if (editingAddonPackage.value) {
        // Remove the package
        const index = addonPackages.value.findIndex(p => p.id === editingAddonPackage.value?.id);
        if (index !== -1) {
            addonPackages.value.splice(index, 1);
        }

        // Remove associated menu items
        addonPackageMenus.value = addonPackageMenus.value.filter(
            pm => pm.package_id !== editingAddonPackage.value?.id
        );

        showDeleteAddonDialog.value = false;
        editingAddonPackage.value = null;
    }
};

const addonDescription = computed(() => {
    return selectedAddonCategory.value
        ? 'View and manage packages in this category'
        : 'Browse all available packages'
})

// Add after the stats ref
const upcomingEvents = ref([
    {
        id: 1,
        title: 'Wedding Reception',
        date: '2024-04-15',
        time: '6:00 PM',
        guests: 150,
        package: 'Premium Package'
    },
    {
        id: 2,
        title: 'Corporate Event',
        date: '2024-04-20',
        time: '12:00 PM',
        guests: 75,
        package: 'Business Package'
    }
]);

const recentOrders = ref([
    {
        id: 'ORD-001',
        date: '2024-03-28',
        status: 'pending',
        items: 5,
        total: 25000
    },
    {
        id: 'ORD-002',
        date: '2024-03-27',
        status: 'completed',
        items: 3,
        total: 15000
    }
]);

const importantNotes = ref([
    {
        id: 1,
        title: 'Inventory Alert',
        description: 'Low stock on premium ingredients'
    },
    {
        id: 2,
        title: 'Staff Schedule',
        description: 'Additional staff needed for upcoming events'
    }
]);
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
                            <span class="relative z-10">Menus</span>
                            <div
                                class="absolute inset-0 bg-primary/5 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                            </div>
                        </TabsTrigger>
                        <TabsTrigger value="packages"
                            class="text-sm font-medium data-[state=active]:bg-primary/10 data-[state=active]:text-primary rounded-md px-8 transition-all duration-200 hover:bg-primary/5 hover:text-primary/80 relative overflow-hidden group">
                            <span class="relative z-10">Packages</span>
                            <div
                                class="absolute inset-0 bg-primary/5 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                            </div>
                        </TabsTrigger>
                        <TabsTrigger value="add-ons"
                            class="text-sm font-medium data-[state=active]:bg-primary/10 data-[state=active]:text-primary rounded-md px-8 transition-all duration-200 hover:bg-primary/5 hover:text-primary/80 relative overflow-hidden group">
                            <span class="relative z-10">Add-Ons</span>
                            <div
                                class="absolute inset-0 bg-primary/5 opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                            </div>
                        </TabsTrigger>
                    </TabsList>
                </div>

                <!-- Overview Tab -->
                <TabsContent value="overview" class="mt-6">
                    <!-- Main Overview Grid -->
                    <div class="grid gap-6 lg:grid-cols-3">
                        <!-- Upcoming Events -->
                        <Card class="lg:col-span-2">
                            <CardHeader>
                                <CardTitle class="flex items-center gap-2">
                                    <Calendar class="h-5 w-5 text-primary" />
                                    Upcoming Events
                                </CardTitle>
                            </CardHeader>
                            <CardContent>
                                <div class="space-y-4">
                                    <div v-for="event in upcomingEvents" :key="event.id"
                                        class="flex items-center justify-between p-4 rounded-lg border hover:bg-muted/50 transition-colors">
                                        <div class="space-y-1">
                                            <h4 class="font-medium">{{ event.title }}</h4>
                                            <p class="text-sm text-muted-foreground">{{ event.date }} at {{ event.time
                                                }}</p>
                                            <div class="flex items-center gap-2 text-sm">
                                                <Badge variant="outline">{{ event.guests }} guests</Badge>
                                                <Badge variant="outline">{{ event.package }}</Badge>
                                            </div>
                                        </div>
                                        <Button variant="ghost" size="sm" class="gap-2">
                                            View Details
                                            <ChevronRight class="h-4 w-4" />
                                        </Button>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>

                        <!-- Quick Actions -->
                        <Card>
                            <CardHeader>
                                <CardTitle class="flex items-center gap-2">
                                    <Settings class="h-5 w-5 text-primary" />
                                    Quick Actions
                                </CardTitle>
                            </CardHeader>
                            <CardContent>
                                <div class="space-y-2">
                                    <Button variant="outline" class="w-full justify-start h-auto py-2.5" asChild>
                                        <Link href="/catering/events/new" class="flex w-full items-center gap-3">
                                        <div class="rounded-full bg-primary/10 p-2">
                                            <Calendar class="h-4 w-4 text-primary" />
                                        </div>
                                        <div class="flex flex-col items-start">
                                            <span class="font-medium">New Event</span>
                                            <span class="text-xs text-muted-foreground">Schedule a new catering
                                                event</span>
                                        </div>
                                        </Link>
                                    </Button>
                                    <Button variant="outline" class="w-full justify-start h-auto py-2.5" asChild>
                                        <Link href="/catering/orders/new" class="flex w-full items-center gap-3">
                                        <div class="rounded-full bg-green-500/10 p-2">
                                            <Utensils class="h-4 w-4 text-green-500" />
                                        </div>
                                        <div class="flex flex-col items-start">
                                            <span class="font-medium">New Order</span>
                                            <span class="text-xs text-muted-foreground">Create a new catering
                                                order</span>
                                        </div>
                                        </Link>
                                    </Button>
                                    <Button variant="outline" class="w-full justify-start h-auto py-2.5" asChild>
                                        <Link href="/catering/menu" class="flex w-full items-center gap-3">
                                        <div class="rounded-full bg-blue-500/10 p-2">
                                            <Package class="h-4 w-4 text-blue-500" />
                                        </div>
                                        <div class="flex flex-col items-start">
                                            <span class="font-medium">Manage Menu</span>
                                            <span class="text-xs text-muted-foreground">Update menu items and
                                                packages</span>
                                        </div>
                                        </Link>
                                    </Button>
                                </div>
                            </CardContent>
                        </Card>

                        <!-- Recent Orders -->
                        <Card class="lg:col-span-2">
                            <CardHeader>
                                <CardTitle class="flex items-center gap-2">
                                    <Utensils class="h-5 w-5 text-primary" />
                                    Recent Orders
                                </CardTitle>
                            </CardHeader>
                            <CardContent>
                                <div class="space-y-4">
                                    <div v-for="order in recentOrders" :key="order.id"
                                        class="flex items-center justify-between p-4 rounded-lg border hover:bg-muted/50 transition-colors">
                                        <div class="space-y-1">
                                            <h4 class="font-medium">Order #{{ order.id }}</h4>
                                            <p class="text-sm text-muted-foreground">{{ order.date }}</p>
                                            <div class="flex items-center gap-2 text-sm">
                                                <Badge :variant="order.status === 'pending' ? 'secondary' : 'default'">
                                                    {{ order.status }}
                                                </Badge>
                                                <span class="text-muted-foreground">{{ order.items }} items</span>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="font-medium">₱{{ order.total.toLocaleString() }}</p>
                                            <Button variant="ghost" size="sm" class="gap-2">
                                                View Details
                                                <ChevronRight class="h-4 w-4" />
                                            </Button>
                                        </div>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>

                        <!-- Important Notes -->
                        <Card>
                            <CardHeader>
                                <CardTitle class="flex items-center gap-2">
                                    <AlertTriangle class="h-5 w-5 text-primary" />
                                    Important Notes
                                </CardTitle>
                            </CardHeader>
                            <CardContent>
                                <div class="space-y-4">
                                    <div v-for="note in importantNotes" :key="note.id"
                                        class="p-4 rounded-lg border hover:bg-muted/50 transition-colors">
                                        <div class="flex items-start gap-3">
                                            <div class="rounded-full bg-yellow-500/10 p-2">
                                                <AlertTriangle class="h-4 w-4 text-yellow-500" />
                                            </div>
                                            <div>
                                                <h4 class="font-medium">{{ note.title }}</h4>
                                                <p class="text-sm text-muted-foreground">{{ note.description }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </CardContent>
                        </Card>
                    </div>
                </TabsContent>

                <!-- Menu Management Tab -->
                <TabsContent value="menu" class="mt-6">
                    <div class="flex h-full flex-1 flex-col gap-12 rounded-xl px-6 py-8 max-w-7xl mx-auto">
                        <!-- Header -->
                        <div class="text-center">
                            <h1 class="text-4xl font-bold text-gray-900 mb-2">Menus</h1>
                            <p class="text-lg text-muted-foreground">Manage all available menus</p>
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
                                    <p class="text-xs text-muted-foreground">menus</p>
                                </CardContent>
                            </Card>
                        </div>

                        <!-- Table Section -->
                        <div>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <Input placeholder="Search menus..." v-model="searchQuery"
                                            class="max-w-sm border-primary" />
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <Button v-if="selectedMenus.size > 0" variant="destructive" size="sm"
                                            @click="confirmDeleteSelected">
                                            Delete Selected
                                        </Button>
                                        <Button variant="default" size="sm" @click="showCreateDialog = true">
                                            <Plus class="h-4 w-4 mr-2" />
                                            New Menu
                                        </Button>
                                    </div>
                                </div>
                                <div class="rounded-md border">
                                    <div class="relative w-full overflow-auto">
                                        <Table>
                                            <TableHeader>
                                                <TableRow class="bg-muted/50">
                                                    <TableHead v-for="column in table.getAllColumns()" :key="column.id"
                                                        :class="{
                                                            'w-[50px]': column.id === 'select',
                                                            'w-[100px]': column.id === 'actions',
                                                            'bg-muted/50': true
                                                        }">
                                                        <template v-if="column.id === 'select'">
                                                            <Checkbox
                                                                :checked="selectedMenus.size === table.getRowModel().rows.length"
                                                                @update:checked="(checked: boolean) => {
                                                                    if (checked) {
                                                                        table.getRowModel().rows.forEach(row =>
                                                                            selectedMenus.add(row.original.name));
                                                                    } else {
                                                                        selectedMenus.clear();
                                                                    }
                                                                }" class="border-primary" />
                                                        </template>
                                                        <template v-else>
                                                            <div class="flex items-center gap-2">
                                                                {{ column.columnDef.header }}
                                                                <Button v-if="column.getCanSort()" variant="ghost"
                                                                    size="icon" class="h-8 w-8"
                                                                    @click="column.getToggleSortingHandler()">
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
                                                    <TableCell v-for="column in table.getAllColumns()" :key="column.id"
                                                        :class="{
                                                            'w-[50px]': column.id === 'select',
                                                            'w-[100px]': column.id === 'actions',
                                                            'text-left': true,
                                                            'px-4': true
                                                        }">
                                                        <template v-if="column.id === 'select'">
                                                            <Checkbox :checked="selectedMenus.has(row.original.name)"
                                                                @update:checked="(checked: boolean) => {
                                                                    if (checked) {
                                                                        selectedMenus.add(row.original.name);
                                                                    } else {
                                                                        selectedMenus.delete(row.original.name);
                                                                    }
                                                                }" class="border-primary" />
                                                        </template>
                                                        <template
                                                            v-else-if="typeof column.columnDef.cell === 'function'">
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
                                            <option v-for="pageSize in [10, 20, 30, 40, 50]" :key="pageSize"
                                                :value="pageSize">
                                                {{ pageSize }}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="flex w-[100px] items-center justify-center text-sm font-medium">
                                        Page {{ table.getState().pagination.pageIndex + 1 }} of
                                        {{ table.getPageCount() }}
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <Button variant="outline" class="hidden h-8 w-8 p-0 lg:flex"
                                            :disabled="!table.getCanPreviousPage()" @click="table.setPageIndex(0)">
                                            <span class="sr-only">Go to first page</span>
                                            <ChevronsLeft class="h-4 w-4" />
                                        </Button>
                                        <Button variant="outline" class="h-8 w-8 p-0"
                                            :disabled="!table.getCanPreviousPage()" @click="table.previousPage()">
                                            <span class="sr-only">Go to previous page</span>
                                            <ChevronLeft class="h-4 w-4" />
                                        </Button>
                                        <Button variant="outline" class="h-8 w-8 p-0"
                                            :disabled="!table.getCanNextPage()" @click="table.nextPage()">
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

                        <!-- Create Menu Dialog -->
                        <Dialog v-model:open="showCreateDialog">
                            <DialogContent class="max-h-[90vh] overflow-y-auto">
                                <DialogHeader>
                                    <DialogTitle>Create New Menu</DialogTitle>
                                    <DialogDescription>
                                        Add a new menu item to your catering options.
                                    </DialogDescription>
                                </DialogHeader>
                                <div class="space-y-4 py-4">
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium">Menu Name</label>
                                        <Input v-model="newMenu.name" placeholder="Enter menu name" />
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium">Category</label>
                                        <div class="flex gap-2">
                                            <Select v-model="newMenu.category" class="flex-1">
                                                <SelectTrigger>
                                                    <SelectValue placeholder="Select category" />
                                                </SelectTrigger>
                                                <SelectContent>
                                                    <SelectItem v-for="category in categories" :key="category"
                                                        :value="category">
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
                                            <Input v-model="newCategory" placeholder="New category name"
                                                class="flex-1" />
                                            <Button variant="default" size="sm" @click="addNewCategory">Add</Button>
                                            <Button variant="ghost" size="icon" @click="showAddCategoryInput = false">
                                                <X class="h-4 w-4" />
                                            </Button>
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium">Description</label>
                                        <Input v-model="newMenu.description" placeholder="Enter description" />
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium">Image URL</label>
                                        <div class="flex gap-2">
                                            <Input v-model="newMenu.image_url" placeholder="Enter image URL"
                                                :class="{ 'border-red-500': newMenu.image_url && !validateImageUrl(newMenu.image_url) }" />
                                            <Button variant="outline" size="icon"
                                                @click="() => newMenu.image_url && previewMenuImage(newMenu.image_url)"
                                                :disabled="!newMenu.image_url || !validateImageUrl(newMenu.image_url)">
                                                <Search class="h-4 w-4" />
                                            </Button>
                                        </div>
                                        <p v-if="newMenu.image_url && !validateImageUrl(newMenu.image_url)"
                                            class="text-sm text-red-500">
                                            Please enter a valid image URL
                                        </p>
                                        <div v-if="newMenu.image_url && validateImageUrl(newMenu.image_url)"
                                            class="mt-2">
                                            <img :src="newMenu.image_url" class="h-32 w-32 object-cover rounded-lg"
                                                :alt="newMenu.name || 'New menu'" />
                                        </div>
                                        <p class="text-sm text-muted-foreground mt-1">
                                            Tip: Use high-quality images from services like Unsplash or your own hosted
                                            images.
                                        </p>
                                    </div>
                                </div>
                                <DialogFooter>
                                    <Button variant="outline" @click="showCreateDialog = false">Cancel</Button>
                                    <Button @click="createMenu"
                                        :disabled="!newMenu.name?.trim() || !newMenu.category?.trim()">
                                        Create Menu
                                    </Button>
                                </DialogFooter>
                            </DialogContent>
                        </Dialog>

                        <!-- Edit Menu Dialog -->
                        <Dialog v-model:open="showEditDialog">
                            <DialogContent class="max-h-[90vh] overflow-y-auto">
                                <DialogHeader>
                                    <DialogTitle>Edit Menu</DialogTitle>
                                    <DialogDescription>
                                        Modify the menu details.
                                    </DialogDescription>
                                </DialogHeader>
                                <div class="space-y-4 py-4" v-if="editingMenu">
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium">Menu Name</label>
                                        <Input v-model="editingMenu.name" placeholder="Enter menu name" />
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium">Category</label>
                                        <div class="flex gap-2">
                                            <Select v-model="editingMenu.category" class="flex-1">
                                                <SelectTrigger>
                                                    <SelectValue placeholder="Select category" />
                                                </SelectTrigger>
                                                <SelectContent>
                                                    <SelectItem v-for="category in categories" :key="category"
                                                        :value="category">
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
                                            <Input v-model="newCategory" placeholder="New category name"
                                                class="flex-1" />
                                            <Button variant="default" size="sm" @click="addNewCategory">Add</Button>
                                            <Button variant="ghost" size="icon" @click="showAddCategoryInput = false">
                                                <X class="h-4 w-4" />
                                            </Button>
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium">Description</label>
                                        <Input v-model="editingMenu.description" placeholder="Enter description" />
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium">Image URL</label>
                                        <div class="flex gap-2">
                                            <Input v-model="editingMenu.image_url" placeholder="Enter image URL"
                                                :class="{ 'border-red-500': editingMenu.image_url && !validateImageUrl(editingMenu.image_url) }" />
                                            <Button variant="outline" size="icon"
                                                @click="() => editingMenu?.image_url && previewMenuImage(editingMenu?.image_url)"
                                                :disabled="!editingMenu.image_url || !validateImageUrl(editingMenu.image_url)">
                                                <Search class="h-4 w-4" />
                                            </Button>
                                        </div>
                                        <p v-if="editingMenu.image_url && !validateImageUrl(editingMenu.image_url)"
                                            class="text-sm text-red-500">
                                            Please enter a valid image URL
                                        </p>
                                        <div v-if="editingMenu.image_url && validateImageUrl(editingMenu.image_url)"
                                            class="mt-2">
                                            <img :src="editingMenu.image_url" class="h-32 w-32 object-cover rounded-lg"
                                                :alt="editingMenu.name" />
                                        </div>
                                        <p class="text-sm text-muted-foreground mt-1">
                                            Tip: Use high-quality images from services like Unsplash or your own hosted
                                            images.
                                        </p>
                                    </div>
                                </div>
                                <DialogFooter>
                                    <Button variant="outline" @click="showEditDialog = false">Cancel</Button>
                                    <Button @click="saveMenu"
                                        :disabled="!editingMenu?.name?.trim() || !editingMenu?.category?.trim()">
                                        Save Changes
                                    </Button>
                                </DialogFooter>
                            </DialogContent>
                        </Dialog>

                        <!-- Delete Menu Dialog -->
                        <Dialog v-model:open="showDeleteDialog">
                            <DialogContent>
                                <DialogHeader>
                                    <DialogTitle>Delete Menu</DialogTitle>
                                    <DialogDescription>
                                        Are you sure you want to delete this menu? This action cannot be undone.
                                    </DialogDescription>
                                </DialogHeader>
                                <DialogFooter>
                                    <Button variant="outline" @click="showDeleteDialog = false">Cancel</Button>
                                    <Button variant="destructive" @click="deleteMenu">Delete</Button>
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
                </TabsContent>

                <!-- Package Management Tab -->
                <TabsContent value="packages" class="mt-6">
                    <div class="flex h-full flex-1 flex-col gap-12 rounded-xl px-6 py-8 max-w-7xl mx-auto">
                        <!-- Header -->
                        <div class="text-center">
                            <h1 class="text-4xl font-bold text-gray-900 mb-2">Packages</h1>
                            <p class="text-lg text-muted-foreground">Manage all available catering packages</p>
                        </div>

                        <!-- Table Section -->
                        <div>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <Input placeholder="Search packages..." v-model="packageSearchQuery"
                                            class="max-w-sm border-primary" />
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <Button v-if="selectedPackages.size > 0" variant="destructive" size="sm"
                                            @click="confirmDeleteSelectedPackages">
                                            Delete Selected
                                        </Button>
                                        <Button variant="default" size="sm" @click="showCreatePackageDialog = true">
                                            <Plus class="h-4 w-4 mr-2" />
                                            New Package
                                        </Button>
                                    </div>
                                </div>
                                <div class="rounded-md border">
                                    <div class="relative w-full overflow-auto">
                                        <Table>
                                            <TableHeader>
                                                <TableRow class="bg-muted/50">
                                                    <TableHead v-for="column in packageTable.getAllColumns()"
                                                        :key="column.id" :class="{
                                                            'w-[50px]': column.id === 'select',
                                                            'w-[100px]': column.id === 'actions',
                                                            'bg-muted/50': true
                                                        }">
                                                        <template v-if="column.id === 'select'">
                                                            <Checkbox
                                                                :checked="selectedPackages.size === packageTable.getRowModel().rows.length"
                                                                @update:checked="(checked: boolean) => {
                                                                    if (checked) {
                                                                        packageTable.getRowModel().rows.forEach(row =>
                                                                            selectedPackages.add(row.original.name));
                                                                    } else {
                                                                        selectedPackages.clear();
                                                                    }
                                                                }" class="border-primary" />
                                                        </template>
                                                        <template v-else>
                                                            <div class="flex items-center gap-2">
                                                                {{ column.columnDef.header }}
                                                                <Button v-if="column.getCanSort()" variant="ghost"
                                                                    size="icon" class="h-8 w-8"
                                                                    @click="column.getToggleSortingHandler()">
                                                                    <ChevronsUpDown class="h-4 w-4" />
                                                                </Button>
                                                            </div>
                                                        </template>
                                                    </TableHead>
                                                </TableRow>
                                            </TableHeader>
                                            <TableBody>
                                                <TableRow v-for="row in packageTable.getRowModel().rows" :key="row.id"
                                                    class="group hover:bg-muted/50 transition-colors">
                                                    <TableCell v-for="column in packageTable.getAllColumns()"
                                                        :key="column.id" :class="{
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
                                                        <template
                                                            v-else-if="typeof column.columnDef.cell === 'function'">
                                                            <component :is="column.columnDef.cell({
                                                                row,
                                                                table: packageTable,
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
                                    {{ packageTable.getFilteredRowModel().rows.length }} row(s) total
                                </div>
                                <div class="flex items-center space-x-6 lg:space-x-8">
                                    <div class="flex items-center space-x-2">
                                        <p class="text-sm font-medium">Rows per page</p>
                                        <select :value="packageTable.getState().pagination.pageSize"
                                            @change="e => packageTable.setPageSize(Number((e.target as HTMLSelectElement).value))"
                                            class="h-8 w-[70px] rounded-md border border-input bg-background px-2 py-2 text-sm">
                                            <option v-for="pageSize in [10, 20, 30, 40, 50]" :key="pageSize"
                                                :value="pageSize">
                                                {{ pageSize }}
                                            </option>
                                        </select>
                                    </div>
                                    <div class="flex w-[100px] items-center justify-center text-sm font-medium">
                                        Page {{ packageTable.getState().pagination.pageIndex + 1 }} of
                                        {{ packageTable.getPageCount() }}
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <Button variant="outline" class="hidden h-8 w-8 p-0 lg:flex"
                                            :disabled="!packageTable.getCanPreviousPage()"
                                            @click="packageTable.setPageIndex(0)">
                                            <span class="sr-only">Go to first page</span>
                                            <ChevronsLeft class="h-4 w-4" />
                                        </Button>
                                        <Button variant="outline" class="h-8 w-8 p-0"
                                            :disabled="!packageTable.getCanPreviousPage()"
                                            @click="packageTable.previousPage()">
                                            <span class="sr-only">Go to previous page</span>
                                            <ChevronLeft class="h-4 w-4" />
                                        </Button>
                                        <Button variant="outline" class="h-8 w-8 p-0"
                                            :disabled="!packageTable.getCanNextPage()" @click="packageTable.nextPage()">
                                            <span class="sr-only">Go to next page</span>
                                            <ChevronRight class="h-4 w-4" />
                                        </Button>
                                        <Button variant="outline" class="hidden h-8 w-8 p-0 lg:flex"
                                            :disabled="!packageTable.getCanNextPage()"
                                            @click="packageTable.setPageIndex(packageTable.getPageCount() - 1)">
                                            <span class="sr-only">Go to last page</span>
                                            <ChevronsRight class="h-4 w-4" />
                                        </Button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Create Package Dialog -->
                        <Dialog v-model:open="showCreatePackageDialog">
                            <DialogContent class="max-h-[90vh] overflow-y-auto">
                                <DialogHeader>
                                    <DialogTitle>Create New Package</DialogTitle>
                                    <DialogDescription>
                                        Add a new catering package.
                                    </DialogDescription>
                                </DialogHeader>
                                <div class="space-y-4 py-4">
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium">Package Name</label>
                                        <Input v-model="newPackage.name" placeholder="Enter package name" />
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium">Description</label>
                                        <Input v-model="newPackage.description" placeholder="Enter description" />
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="space-y-2">
                                            <label class="text-sm font-medium">Price per Head</label>
                                            <Input v-model="newPackage.price" type="number" min="0"
                                                placeholder="Enter price" />
                                        </div>
                                        <div class="space-y-2">
                                            <label class="text-sm font-medium">Meal Limit</label>
                                            <Input v-model="newPackage.meal_limit" type="number" min="1"
                                                placeholder="Enter meal limit" />
                                        </div>
                                        <div class="space-y-2">
                                            <label class="text-sm font-medium">Minimum Guests</label>
                                            <Input v-model="newPackage.minimum_guests" type="number" min="1"
                                                placeholder="Enter minimum guests" />
                                        </div>
                                        <div class="space-y-2">
                                            <label class="text-sm font-medium">Maximum Guests</label>
                                            <Input v-model="newPackage.maximum_guests" type="number"
                                                :min="newPackage.minimum_guests || 1"
                                                placeholder="Enter maximum guests" />
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium">Status</label>
                                        <Select v-model="newPackage.status">
                                            <SelectTrigger>
                                                <SelectValue placeholder="Select status" />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectItem value="active">Active</SelectItem>
                                                <SelectItem value="inactive">Inactive</SelectItem>
                                            </SelectContent>
                                        </Select>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium">Image URL</label>
                                        <div class="flex gap-2">
                                            <Input v-model="newPackage.image_url" placeholder="Enter image URL"
                                                :class="{ 'border-red-500': newPackage.image_url && !validateImageUrl(newPackage.image_url) }" />
                                            <Button variant="outline" size="icon"
                                                @click="() => newPackage.image_url && previewPackageImage(newPackage.image_url)"
                                                :disabled="!newPackage.image_url || !validateImageUrl(newPackage.image_url)">
                                                <Search class="h-4 w-4" />
                                            </Button>
                                        </div>
                                        <p v-if="newPackage.image_url && !validateImageUrl(newPackage.image_url)"
                                            class="text-sm text-red-500">
                                            Please enter a valid image URL
                                        </p>
                                        <div v-if="newPackage.image_url && validateImageUrl(newPackage.image_url)"
                                            class="mt-2">
                                            <img :src="newPackage.image_url" class="h-32 w-32 object-cover rounded-lg"
                                                :alt="newPackage.name || 'New package'" />
                                        </div>
                                        <p class="text-sm text-muted-foreground mt-1">
                                            Tip: Use high-quality images from services like Unsplash or your own hosted
                                            images.
                                        </p>
                                    </div>
                                </div>
                                <DialogFooter>
                                    <Button variant="outline" @click="showCreatePackageDialog = false">Cancel</Button>
                                    <Button @click="createPackage" :disabled="!newPackage.name?.trim()">
                                        Create Package
                                    </Button>
                                </DialogFooter>
                            </DialogContent>
                        </Dialog>

                        <!-- Edit Package Dialog -->
                        <Dialog v-model:open="showEditPackageDialog">
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
                                        <label class="text-sm font-medium">Description</label>
                                        <Input v-model="editingPackage.description" placeholder="Enter description" />
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="space-y-2">
                                            <label class="text-sm font-medium">Price per Head</label>
                                            <Input v-model="editingPackage.price" type="number" min="0"
                                                placeholder="Enter price" />
                                        </div>
                                        <div class="space-y-2">
                                            <label class="text-sm font-medium">Meal Limit</label>
                                            <Input v-model="editingPackage.meal_limit" type="number" min="1"
                                                placeholder="Enter meal limit" />
                                        </div>
                                        <div class="space-y-2">
                                            <label class="text-sm font-medium">Minimum Guests</label>
                                            <Input v-model="editingPackage.minimum_guests" type="number" min="1"
                                                placeholder="Enter minimum guests" />
                                        </div>
                                        <div class="space-y-2">
                                            <label class="text-sm font-medium">Maximum Guests</label>
                                            <Input v-model="editingPackage.maximum_guests" type="number"
                                                :min="editingPackage.minimum_guests || 1"
                                                placeholder="Enter maximum guests" />
                                        </div>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium">Status</label>
                                        <Select v-model="editingPackage.status">
                                            <SelectTrigger>
                                                <SelectValue placeholder="Select status" />
                                            </SelectTrigger>
                                            <SelectContent>
                                                <SelectItem value="active">Active</SelectItem>
                                                <SelectItem value="inactive">Inactive</SelectItem>
                                            </SelectContent>
                                        </Select>
                                    </div>
                                    <div class="space-y-2">
                                        <label class="text-sm font-medium">Image URL</label>
                                        <div class="flex gap-2">
                                            <Input v-model="editingPackage.image_url" placeholder="Enter image URL"
                                                :class="{ 'border-red-500': editingPackage.image_url && !validateImageUrl(editingPackage.image_url) }" />
                                            <Button variant="outline" size="icon"
                                                @click="() => editingPackage?.image_url && previewPackageImage(editingPackage?.image_url)"
                                                :disabled="!editingPackage.image_url || !validateImageUrl(editingPackage.image_url)">
                                                <Search class="h-4 w-4" />
                                            </Button>
                                        </div>
                                        <p v-if="editingPackage.image_url && !validateImageUrl(editingPackage.image_url)"
                                            class="text-sm text-red-500">
                                            Please enter a valid image URL
                                        </p>
                                        <div v-if="editingPackage.image_url && validateImageUrl(editingPackage.image_url)"
                                            class="mt-2">
                                            <img :src="editingPackage.image_url"
                                                class="h-32 w-32 object-cover rounded-lg" :alt="editingPackage.name" />
                                        </div>
                                        <p class="text-sm text-muted-foreground mt-1">
                                            Tip: Use high-quality images from services like Unsplash or your own hosted
                                            images.
                                        </p>
                                    </div>
                                </div>
                                <DialogFooter>
                                    <Button variant="outline" @click="showEditPackageDialog = false">Cancel</Button>
                                    <Button @click="savePackage" :disabled="!editingPackage?.name?.trim()">
                                        Save Changes
                                    </Button>
                                </DialogFooter>
                            </DialogContent>
                        </Dialog>

                        <!-- Delete Package Dialog -->
                        <Dialog v-model:open="showDeletePackageDialog">
                            <DialogContent>
                                <DialogHeader>
                                    <DialogTitle>Delete Package</DialogTitle>
                                    <DialogDescription>
                                        Are you sure you want to delete this package? This action cannot be undone.
                                    </DialogDescription>
                                </DialogHeader>
                                <DialogFooter>
                                    <Button variant="outline" @click="showDeletePackageDialog = false">Cancel</Button>
                                    <Button variant="destructive" @click="deletePackage">Delete</Button>
                                </DialogFooter>
                            </DialogContent>
                        </Dialog>

                        <!-- Image Preview Dialog -->
                        <Dialog v-model:open="showPackageImagePreview">
                            <DialogContent class="sm:max-w-xl">
                                <DialogHeader>
                                    <DialogTitle>Image Preview</DialogTitle>
                                </DialogHeader>
                                <div class="relative aspect-video w-full overflow-hidden rounded-lg">
                                    <img :src="packagePreviewImage" class="w-full h-full object-contain"
                                        :alt="packagePreviewImage" />
                                </div>
                                <DialogFooter>
                                    <Button variant="outline" @click="showPackageImagePreview = false">Close</Button>
                                    <Button variant="default" asChild>
                                        <a :href="packagePreviewImage" target="_blank" rel="noopener noreferrer">
                                            <ExternalLink class="h-4 w-4 mr-2" />
                                            Open Original
                                        </a>
                                    </Button>
                                </DialogFooter>
                            </DialogContent>
                        </Dialog>
                    </div>
                </TabsContent>

                <!-- Add-Ons Tab Content -->
                <TabsContent value="add-ons" class="mt-6">
                    <div class="flex h-full flex-1 flex-col gap-12 rounded-xl px-6 py-8 max-w-7xl mx-auto">
                        <!-- Header -->
                        <div class="text-center">
                            <h1 class="text-4xl font-bold text-gray-900 mb-2">Add-Ons</h1>
                            <p class="text-lg text-muted-foreground">Manage special packages and food trays</p>
                        </div>

                        <!-- Categories Section -->
                        <div class="space-y-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="text-2xl font-semibold tracking-tight">Categories</h2>
                                    <p class="text-muted-foreground">Select a category to view its packages</p>
                                </div>
                                <Button variant="outline" class="gap-2">
                                    <Plus class="h-4 w-4" />
                                    Add Category
                                </Button>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <Card v-for="category in addonCategories" :key="category.id"
                                    class="cursor-pointer transition-all duration-300 hover:shadow-lg group relative overflow-hidden"
                                    :class="{ 'ring-2 ring-primary': selectedAddonCategory === category.id }"
                                    @click="selectedAddonCategory = selectedAddonCategory === category.id ? null : category.id">
                                    <!-- Background Pattern -->
                                    <div
                                        class="absolute inset-0 bg-gradient-to-br from-primary/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                    </div>

                                    <CardHeader>
                                        <CardTitle class="flex items-center justify-between">
                                            <div class="flex items-center gap-2">
                                                <div class="rounded-full bg-primary/10 p-2">
                                                    <Package class="h-5 w-5 text-primary" />
                                                </div>
                                                {{ category.name }}
                                            </div>
                                            <Badge :variant="category.status === 'active' ? 'default' : 'secondary'"
                                                class="transition-colors duration-200 group-hover:bg-primary/10">
                                                {{ category.status }}
                                            </Badge>
                                        </CardTitle>
                                    </CardHeader>
                                    <CardContent>
                                        <p class="text-muted-foreground">{{ category.description }}</p>
                                        <div class="mt-4 flex items-center justify-between text-sm">
                                            <div class="flex items-center gap-2">
                                                <div class="rounded-full bg-muted p-1.5">
                                                    <Utensils class="h-4 w-4 text-muted-foreground" />
                                                </div>
                                                <span class="text-muted-foreground">
                                                    {{ getPackageCount(category.id) }} packages
                                                </span>
                                            </div>
                                            <Button variant="ghost" size="sm" class="gap-2">
                                                View Details
                                                <ChevronRight class="h-4 w-4" />
                                            </Button>
                                        </div>
                                    </CardContent>
                                </Card>
                            </div>
                        </div>

                        <!-- Divider -->
                        <!-- <div class="relative">
                            <div class="absolute inset-0 flex items-center" aria-hidden="true">
                                <div class="w-full border-t border-muted"></div>
                            </div>
                            <div class="relative flex justify-center">
                                <span class="bg-background px-4 text-sm text-muted-foreground">Packages</span>
                            </div>
                        </div> -->

                        <!-- Packages Section -->
                        <div class="space-y-6 mt-8">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h2 class="text-2xl font-semibold tracking-tight">
                                        {{ selectedAddonCategory ? getCategoryName(selectedAddonCategory) : 'All' }}
                                        Packages
                                    </h2>
                                    <p class="text-muted-foreground">
                                        {{ addonDescription }}
                                    </p>
                                </div>
                                <Button @click="showCreateAddonDialog = true" class="gap-2">
                                    <Plus class="h-4 w-4" />
                                    Add Package
                                </Button>
                            </div>

                            <!-- Search and Filters -->
                            <div class="flex items-center gap-4">
                                <div class="relative flex-1 max-w-sm">
                                    <Search
                                        class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                                    <Input v-model="addonSearchQuery" placeholder="Search packages..." class="pl-9" />
                                </div>
                                <Button variant="outline" size="icon" @click="addonSearchQuery = ''"
                                    v-if="addonSearchQuery">
                                    <X class="h-4 w-4" />
                                </Button>
                            </div>

                            <!-- Packages Grid -->
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                <Card v-for="pkg in filteredAddonPackages" :key="pkg.id"
                                    class="group relative overflow-hidden hover:shadow-lg transition-all duration-300">
                                    <!-- Package Image -->
                                    <div class="relative h-48 overflow-hidden">
                                        <img :src="pkg.image_url || 'default-image-url'" :alt="pkg.name"
                                            class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105" />
                                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent" />
                                        <Badge class="absolute top-4 right-4"
                                            :variant="pkg.status === 'active' ? 'default' : 'secondary'">
                                            {{ pkg.status }}
                                        </Badge>
                                    </div>

                                    <CardHeader class="relative z-10">
                                        <CardTitle class="flex items-center justify-between">
                                            <div class="flex items-center gap-2">
                                                <div class="rounded-full bg-primary/10 p-2">
                                                    <Package class="h-4 w-4 text-primary" />
                                                </div>
                                                {{ pkg.name }}
                                            </div>
                                            <Badge variant="outline" class="bg-background/80 backdrop-blur-sm">
                                                {{ getCategoryName(pkg.category_id) }}
                                            </Badge>
                                        </CardTitle>
                                    </CardHeader>

                                    <CardContent class="space-y-4">
                                        <p class="text-muted-foreground">{{ pkg.description }}</p>

                                        <!-- Package Details -->
                                        <div class="space-y-2">
                                            <div class="flex items-center justify-between text-sm">
                                                <span class="text-muted-foreground">Serving Size</span>
                                                <span class="font-medium">{{ pkg.serving_size }} pax</span>
                                            </div>
                                            <div class="flex items-center justify-between text-sm" v-if="pkg.price">
                                                <span class="text-muted-foreground">Price</span>
                                                <span class="font-medium">₱{{ pkg.price.toLocaleString() }}</span>
                                            </div>
                                        </div>

                                        <!-- Menu Items -->
                                        <div class="space-y-2">
                                            <h4 class="text-sm font-medium flex items-center gap-2">
                                                <div class="rounded-full bg-muted p-1.5">
                                                    <Utensils class="h-4 w-4 text-muted-foreground" />
                                                </div>
                                                Included Items
                                            </h4>
                                            <div class="space-y-1">
                                                <div v-for="packageMenu in getPackageMenus(pkg.id)"
                                                    :key="packageMenu.id"
                                                    class="flex items-center justify-between text-sm">
                                                    <div class="flex items-center gap-2">
                                                        <Badge variant="outline" :class="{
                                                            'border-primary text-primary': packageMenu.is_fixed,
                                                            'border-muted text-muted-foreground': !packageMenu.is_fixed
                                                        }">
                                                            {{ packageMenu.is_fixed ? 'Fixed' : 'Changeable' }}
                                                        </Badge>
                                                        <span>{{ packageMenu.menu?.name }}</span>
                                                    </div>
                                                    <div class="flex items-center gap-2">
                                                        <span v-if="packageMenu.quantity" class="text-muted-foreground">
                                                            x{{ packageMenu.quantity }}
                                                        </span>
                                                        <span v-if="packageMenu.price" class="font-medium">
                                                            ₱{{ packageMenu.price.toLocaleString() }}
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </CardContent>

                                    <!-- Action Buttons -->
                                    <div
                                        class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <div class="flex items-center gap-1">
                                            <Button variant="outline" size="icon" class="bg-white/90 hover:bg-white"
                                                @click="showMenuSelectionDialog = true; editingAddonPackage = pkg">
                                                <Plus class="h-4 w-4" />
                                            </Button>
                                            <Button variant="outline" size="icon" class="bg-white/90 hover:bg-white"
                                                @click="showEditAddonDialog = true; editingAddonPackage = pkg">
                                                <Edit2 class="h-4 w-4" />
                                            </Button>
                                            <Button variant="outline" size="icon"
                                                class="bg-white/90 hover:bg-white text-destructive hover:text-destructive"
                                                @click="showDeleteAddonDialog = true; editingAddonPackage = pkg">
                                                <Trash2 class="h-4 w-4" />
                                            </Button>
                                        </div>
                                    </div>
                                </Card>
                            </div>
                        </div>
                    </div>
                </TabsContent>
            </Tabs>
        </div>
    </AppLayout>

    <!-- Add Menu Items Dialog -->
    <Dialog v-model:open="showMenuSelectionDialog">
        <DialogContent class="max-h-[90vh] overflow-y-auto">
            <DialogHeader>
                <DialogTitle>Add Menu Items</DialogTitle>
                <DialogDescription>
                    Add menu items to {{ editingAddonPackage?.name }}
                </DialogDescription>
            </DialogHeader>
            <div class="space-y-4 py-4">
                <div class="space-y-2">
                    <label class="text-sm font-medium">Select Menu Items</label>
                    <div class="space-y-2">
                        <div v-for="menu in menus" :key="menu.id"
                            class="flex items-center justify-between p-2 border rounded-lg">
                            <div class="flex items-center gap-2">
                                <Checkbox :checked="selectedMenusForPackage.has(menu.id)" @update:checked="(checked) => {
                                    if (checked) selectedMenusForPackage.add(menu.id);
                                    else selectedMenusForPackage.delete(menu.id);
                                }" />
                                <span>{{ menu.name }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <Input type="number" v-model="menu.quantity" min="1" class="w-20" placeholder="Qty" />
                                <Input type="number" v-model="menu.price" min="0" class="w-32" placeholder="Price" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <DialogFooter>
                <Button variant="outline" @click="showMenuSelectionDialog = false">Cancel</Button>
                <Button @click="addMenuItemsToPackage">Add Items</Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>

    <!-- Edit Package Dialog -->
    <Dialog v-model:open="showEditAddonDialog">
        <DialogContent class="max-h-[90vh] overflow-y-auto">
            <DialogHeader>
                <DialogTitle>Edit Package</DialogTitle>
                <DialogDescription>
                    Modify package details for {{ editingAddonPackage?.name }}
                </DialogDescription>
            </DialogHeader>
            <div class="space-y-4 py-4" v-if="editingAddonPackage">
                <div class="space-y-2">
                    <label class="text-sm font-medium">Package Name</label>
                    <Input v-model="editingAddonPackage.name" placeholder="Enter package name" />
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-medium">Description</label>
                    <Input v-model="editingAddonPackage.description" placeholder="Enter description" />
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-sm font-medium">Serving Size</label>
                        <Input v-model="editingAddonPackage.serving_size" type="number" min="1"
                            placeholder="Enter serving size" />
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-medium">Price</label>
                        <Input v-model="editingAddonPackage.price" type="number" min="0" placeholder="Enter price" />
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-medium">Status</label>
                    <Select v-model="editingAddonPackage.status">
                        <SelectTrigger>
                            <SelectValue placeholder="Select status" />
                        </SelectTrigger>
                        <SelectContent>
                            <SelectItem value="active">Active</SelectItem>
                            <SelectItem value="inactive">Inactive</SelectItem>
                        </SelectContent>
                    </Select>
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-medium">Image URL</label>
                    <div class="flex gap-2">
                        <Input v-model="editingAddonPackage.image_url" placeholder="Enter image URL" />
                        <Button variant="outline" size="icon"
                            @click="() => editingAddonPackage?.image_url && previewPackageImage(editingAddonPackage?.image_url)">
                            <Search class="h-4 w-4" />
                        </Button>
                    </div>
                </div>
            </div>
            <DialogFooter>
                <Button variant="outline" @click="showEditAddonDialog = false">Cancel</Button>
                <Button @click="saveAddonPackage">Save Changes</Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>

    <!-- Delete Package Dialog -->
    <Dialog v-model:open="showDeleteAddonDialog">
        <DialogContent>
            <DialogHeader>
                <DialogTitle>Delete Package</DialogTitle>
                <DialogDescription>
                    Are you sure you want to delete {{ editingAddonPackage?.name }}? This action cannot be undone.
                </DialogDescription>
            </DialogHeader>
            <DialogFooter>
                <Button variant="outline" @click="showDeleteAddonDialog = false">Cancel</Button>
                <Button variant="destructive" @click="deleteAddonPackage">Delete</Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
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