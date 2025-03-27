<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { Card, CardContent } from '@/components/ui/card';
import { Utensils, UtensilsCrossed, Plus, X, Edit2, Save, PlusCircle, Trash2 } from 'lucide-vue-next';
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
import { Link } from '@inertiajs/vue3';

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

interface MealCategory {
    title: string;
    items: string[];
}

const minimumPersons = ref(50);
const isEditingMinimum = ref(false);

const defaultPackage = {
    name: 'NEW PACKAGE',
    price: 0,
    meals: 5,
    includes: ['Rice & Drinks']
};

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

const mealCategories = reactive<MealCategory[]>([
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
    {
        title: 'Chicken',
        items: [
            'CHICKEN AFRITADA',
            'BUTTERED CHICKEN',
            'GARLIC CHICKEN',
            'FRIED CHICKEN',
            'CHICKEN CURRY',
            'CHICKEN TERIYAKI',
            'CHICKEN BUFFALO',
            'CHICKEN CORDON BLEU',
            'CHICKEN ALA KING'
        ]
    },
    {
        title: 'Fish & Seafoods',
        items: [
            'MIXED SEAFOODS',
            'BUTTERED GARLIC SHRIMP',
            'SWEET AND SOUR FISH',
            'FISH FILLET WITH TAOSI SAUCE',
            'BREADED FISH FILLET WITH TARTAR SAUCE'
        ]
    },
    {
        title: 'Vegetables',
        items: [
            'CHOPSUEY WITH QUAIL EGG',
            'VEGETABLE WITH SEAFOODS',
            'BUTTERED MIX VEGETABLES',
            'PINAKBET',
            'STIR FRY VEGETABLE'
        ]
    },
    {
        title: 'Noodles',
        items: [
            'SOTANGHON GUISADO',
            'PANCIT CANTON',
            'BAM-E'
        ]
    },
    {
        title: 'Dessert',
        items: [
            'FRESH MIX FRUITS',
            'MANGO SAGO',
            'BUKO SALAD',
            'BUKO PANDAN',
            'MACARONI SALAD'
        ]
    },
    {
        title: 'Pasta',
        items: [
            'SPAGHETTI WITH BECHAMEL SAUCE',
            'SEAFOOD MARINARA',
            'CREAMY CARBONARA',
            'BAKED MACARONI',
            'BAKED LASAGNA',
            'BAKED SPAGHETTI'
        ]
    }
]);

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

const saveMinimumPersons = () => {
    isEditingMinimum.value = false;
    // Here you would typically make an API call to save the changes
};

// Dialog States
const showAddDialog = ref(false);
const showAddDishDialog = ref(false);
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
    if (packageToDelete.value !== null) {
        packages.value.splice(packageToDelete.value, 1);
        editingStates.value.splice(packageToDelete.value, 1);
        showDeleteDialog.value = false;
        packageToDelete.value = null;
    }
};

// Dish Management
const selectedCategory = ref('');
const newDish = ref('');
const dishToDelete = ref<{ category: string; dish: string } | null>(null);
const dishToEdit = ref<{ category: string; dish: string } | null>(null);
const editingDish = ref('');
const newCategory = ref('');

const resetNewDish = () => {
    selectedCategory.value = '';
    newDish.value = '';
    newCategory.value = '';
};

const cancelAddDish = () => {
    showAddDishDialog.value = false;
    resetNewDish();
};

const addNewDish = () => {
    if (selectedCategory.value && newDish.value.trim()) {
        const category = mealCategories.find(cat => cat.title === selectedCategory.value);
        if (category && !category.items.includes(newDish.value.trim().toUpperCase())) {
            category.items.push(newDish.value.trim().toUpperCase());
            showAddDishDialog.value = false;
            resetNewDish();
        }
    }
};

const startEditingDish = (categoryTitle: string, dish: string) => {
    dishToEdit.value = { category: categoryTitle, dish };
    editingDish.value = dish;
};

const saveDish = () => {
    if (dishToEdit.value && editingDish.value.trim()) {
        const category = mealCategories.find(cat => cat.title === dishToEdit.value?.category);
        if (category) {
            const index = category.items.indexOf(dishToEdit.value.dish);
            if (index !== -1) {
                category.items[index] = editingDish.value.trim().toUpperCase();
                dishToEdit.value = null;
                editingDish.value = '';
            }
        }
    }
};

const confirmDeleteDish = (categoryTitle: string, dish: string) => {
    dishToDelete.value = { category: categoryTitle, dish };
    showDeleteDialog.value = true;
};

const deleteDish = () => {
    if (dishToDelete.value) {
        const category = mealCategories.find(cat => cat.title === dishToDelete.value?.category);
        if (category) {
            const index = category.items.indexOf(dishToDelete.value.dish);
            if (index !== -1) {
                category.items.splice(index, 1);
                showDeleteDialog.value = false;
                dishToDelete.value = null;
            }
        }
    }
};

const addNewCategory = () => {
    const categoryName = newCategory.value.trim().toUpperCase();
    if (categoryName && !mealCategories.some(cat => cat.title === categoryName)) {
        mealCategories.push({
            title: categoryName,
            items: []
        });
        selectedCategory.value = categoryName;
        newCategory.value = '';
    }
};
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">

        <Head title="Manage Catering" />

        <div class="flex h-full flex-1 flex-col gap-12 rounded-xl px-6 py-8 max-w-7xl mx-auto bg-gray-50/50">
            <!-- Header -->
            <div class="text-center">
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Catering Packages </h1>
                <p class="text-lg text-muted-foreground">Overview of packages and meal choices</p>
            </div>

            <!-- Packages Section -->
            <div class="bg-white rounded-xl p-8 shadow-md border border-gray-200">
                <div class="flex items-center justify-between gap-3 mb-6">
                    <div class="flex items-center gap-3">
                        <UtensilsCrossed class="h-7 w-7 text-primary" />
                        <h2 class="text-2xl font-bold text-gray-900">Available Packages</h2>
                    </div>
                    <div class="flex items-center gap-3">
                        <Button variant="outline" @click="addNewPackage">
                            <PlusCircle class="h-4 w-4 mr-2" />
                            Add Package
                        </Button>
                        <Button variant="outline" asChild>
                            <Link href="/catering/packages">Manage All Packages</Link>
                        </Button>
                    </div>
                </div>
                <div class="text-center mb-6 p-4 bg-primary/5 rounded-lg flex items-center justify-center gap-3">
                    <div class="text-lg font-semibold text-primary" v-if="!isEditingMinimum">
                        MINIMUM OF {{ minimumPersons }} PERSONS
                        <Button variant="ghost" size="icon" class="ml-2" @click="isEditingMinimum = true">
                            <Edit2 class="h-4 w-4" />
                        </Button>
                    </div>
                    <div v-else class="flex items-center gap-2">
                        <span class="text-lg font-semibold text-primary">MINIMUM OF</span>
                        <Input v-model="minimumPersons" type="number" class="w-24 text-center" />
                        <span class="text-lg font-semibold text-primary">PERSONS</span>
                        <Button variant="ghost" size="icon" @click="saveMinimumPersons">
                            <Save class="h-4 w-4" />
                        </Button>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <Card v-for="(pkg, index) in packages" :key="index"
                        class="overflow-hidden transition-all duration-200 hover:shadow-lg">
                        <div class="bg-primary/10 p-4 flex justify-between items-center">
                            <div v-if="!getEditingState(index).value?.isEditing" class="text-lg font-bold text-primary">
                                {{ pkg.name }}
                            </div>
                            <Input v-else v-model="pkg.name" class="w-full" />
                            <div class="flex items-center gap-2">
                                <Button variant="ghost" size="icon"
                                    @click="getEditingState(index).value?.isEditing ? savePackage(index) : startEditing(index)">
                                    <Edit2 v-if="!getEditingState(index).value?.isEditing" class="h-4 w-4" />
                                    <Save v-else class="h-4 w-4" />
                                </Button>
                                <Button variant="ghost" size="icon" class="text-destructive"
                                    @click="confirmDelete(index)">
                                    <Trash2 class="h-4 w-4" />
                                </Button>
                            </div>
                        </div>
                        <CardContent class="p-6">
                            <div class="flex items-center gap-2 mb-2">
                                <div v-if="!getEditingState(index).value?.isEditing"
                                    class="text-3xl font-bold text-gray-900">
                                    ₱{{ pkg.price }}
                                </div>
                                <Input v-else v-model="pkg.price" type="number" class="w-32"
                                    :placeholder="String(pkg.price)" />
                            </div>
                            <div class="flex items-center gap-2 mb-2 text-primary">
                                <Utensils class="h-5 w-5" />
                                <div v-if="!getEditingState(index).value?.isEditing" class="font-semibold">
                                    Choice of {{ pkg.meals }} Meals
                                </div>
                                <div v-else class="flex items-center gap-2">
                                    <span class="font-semibold">Choice of</span>
                                    <Input v-model="pkg.meals" type="number" class="w-20"
                                        :placeholder="String(pkg.meals)" />
                                    <span class="font-semibold">Meals</span>
                                </div>
                            </div>
                            <div class="text-sm text-muted-foreground mb-4">
                                Select any {{ pkg.meals }} meals from our menu
                            </div>
                            <div class="h-px w-full bg-gray-200 my-4"></div>
                            <div class="font-medium text-sm mb-2">Package Includes:</div>
                            <ul class="space-y-3">
                                <li v-for="(item, inclusionIndex) in pkg.includes" :key="item"
                                    class="flex items-center justify-between text-gray-600">
                                    <div class="flex items-center">
                                        <span class="h-1.5 w-1.5 rounded-full bg-primary mr-2"></span>
                                        <span v-if="!getEditingState(index).value?.isEditing">{{ item }}</span>
                                        <Input v-else v-model="pkg.includes[inclusionIndex]" class="w-full" />
                                    </div>
                                    <Button v-if="getEditingState(index).value?.isEditing" variant="ghost" size="icon"
                                        @click="removeInclusion(index, inclusionIndex)">
                                        <X class="h-4 w-4" />
                                    </Button>
                                </li>
                                <li v-if="getEditingState(index).value?.isEditing" class="flex items-center gap-2">
                                    <Input v-model="getEditingState(index).value.newInclusion"
                                        placeholder="Add new inclusion" @keyup.enter="addInclusion(index)" />
                                    <Button variant="ghost" size="icon" @click="addInclusion(index)">
                                        <Plus class="h-4 w-4" />
                                    </Button>
                                </li>
                            </ul>
                        </CardContent>
                    </Card>
                </div>
            </div>

            <!-- Section Divider -->
            <div class="relative py-2">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-200"></div>
                </div>
                <div class="relative flex justify-center">
                    <span class="bg-gray-50/50 px-6 py-1 text-sm font-medium text-gray-500">MENU CONFIGURATION</span>
                </div>
            </div>

            <!-- Meal Choices Section -->
            <div class="bg-white rounded-xl p-8 shadow-md border border-gray-200">
                <div class="flex items-center justify-between gap-3 mb-8">
                    <div class="flex items-center gap-3">
                        <Utensils class="h-7 w-7 text-primary" />
                        <h2 class="text-2xl font-bold text-gray-900">Meal Choices</h2>
                    </div>
                    <div class="flex items-center gap-3">
                        <Button variant="outline" @click="showAddDishDialog = true">
                            <PlusCircle class="h-4 w-4 mr-2" />
                            Add Dish
                        </Button>
                        <Button variant="outline" asChild>
                            <Link href="/catering/dishes">Manage Dishes</Link>
                        </Button>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <Card v-for="category in mealCategories" :key="category.title" class="overflow-hidden">
                        <div class="bg-primary/10 p-4">
                            <div class="text-lg font-bold text-primary">
                                {{ category.title }}
                            </div>
                        </div>
                        <CardContent class="p-6">
                            <ul class="space-y-3">
                                <li v-for="item in category.items" :key="item"
                                    class="flex items-center justify-between text-gray-600 hover:text-primary transition-colors">
                                    <div class="flex items-start flex-1">
                                        <span class="h-1.5 w-1.5 rounded-full bg-primary mr-2 mt-2"></span>
                                        <span>{{ item }}</span>
                                    </div>
                                </li>
                            </ul>
                        </CardContent>
                    </Card>
                </div>
            </div>

            <!-- Add Dish Dialog -->
            <Dialog v-model:open="showAddDishDialog">
                <DialogContent>
                    <DialogHeader>
                        <DialogTitle>Add New Dish</DialogTitle>
                        <DialogDescription>
                            Add a new dish to an existing category or create a new one.
                        </DialogDescription>
                    </DialogHeader>
                    <div class="space-y-4 py-4">
                        <div class="space-y-2">
                            <label class="text-sm font-medium">Select Category</label>
                            <div class="flex gap-2">
                                <select v-model="selectedCategory"
                                    class="flex-1 h-9 rounded-md border border-input bg-background px-3 py-1 text-sm shadow-sm transition-colors placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-1 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50"
                                    :class="{ 'border-red-500': showAddDishDialog && !selectedCategory }">
                                    <option value="">Select a category</option>
                                    <option v-for="category in mealCategories" :key="category.title"
                                        :value="category.title">
                                        {{ category.title }}
                                    </option>
                                </select>
                                <div class="relative">
                                    <Button variant="outline" @click="selectedCategory = ''">
                                        <Plus class="h-4 w-4 mr-2" />
                                        New
                                    </Button>
                                </div>
                            </div>
                        </div>

                        <div v-if="!selectedCategory" class="space-y-2">
                            <label class="text-sm font-medium">New Category Name</label>
                            <div class="flex gap-2">
                                <Input v-model="newCategory" placeholder="Enter new category name" />
                                <Button variant="outline" @click="addNewCategory"
                                    :disabled="!newCategory.trim() || mealCategories.some(cat => cat.title === newCategory.trim().toUpperCase())">
                                    Add
                                </Button>
                            </div>
                            <p v-if="newCategory.trim() && mealCategories.some(cat => cat.title === newCategory.trim().toUpperCase())"
                                class="text-sm text-red-500">
                                This category already exists
                            </p>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-medium">Dish Name</label>
                            <Input v-model="newDish" placeholder="Enter dish name" :disabled="!selectedCategory"
                                :class="{ 'border-red-500': showAddDishDialog && selectedCategory && !newDish.trim() }"
                                @keyup.enter="addNewDish" />
                            <p v-if="showAddDishDialog && selectedCategory && newDish.trim() &&
                                mealCategories.some(cat => cat.title === selectedCategory && cat.items.includes(newDish.trim().toUpperCase()))"
                                class="text-sm text-red-500">
                                This dish already exists in the selected category
                            </p>
                        </div>
                    </div>
                    <DialogFooter>
                        <Button variant="outline" @click="cancelAddDish">Cancel</Button>
                        <Button @click="addNewDish"
                            :disabled="!selectedCategory || !newDish.trim() ||
                                mealCategories.some(cat => cat.title === selectedCategory && cat.items.includes(newDish.trim().toUpperCase()))">
                            Add Dish
                        </Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>

            <!-- Add Package Dialog -->
            <Dialog v-model:open="showAddDialog">
                <DialogContent>
                    <DialogHeader>
                        <DialogTitle>Add New Package</DialogTitle>
                        <DialogDescription>
                            Create a new catering package. You can edit additional details after creating.
                        </DialogDescription>
                    </DialogHeader>
                    <div class="space-y-4 py-4">
                        <div class="space-y-2">
                            <label class="text-sm font-medium">Package Name</label>
                            <Input v-model="newPackage.name" placeholder="Enter package name"
                                :class="{ 'border-red-500': showAddDialog && !newPackage.name.trim() }" />
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium">Price</label>
                            <Input v-model="newPackage.price" type="number" placeholder="Enter price"
                                :class="{ 'border-red-500': showAddDialog && newPackage.price <= 0 }" />
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium">Number of Meals</label>
                            <Input v-model="newPackage.meals" type="number" placeholder="Enter number of meals"
                                :class="{ 'border-red-500': showAddDialog && newPackage.meals <= 0 }" />
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium">Inclusions</label>
                            <ul class="space-y-2 mb-2">
                                <li v-for="(inclusion, index) in newPackage.includes" :key="index"
                                    class="flex items-center justify-between bg-muted/50 px-3 py-1.5 rounded-md">
                                    <span>{{ inclusion }}</span>
                                    <Button variant="ghost" size="icon" @click="removeNewInclusion(index)">
                                        <X class="h-4 w-4" />
                                    </Button>
                                </li>
                            </ul>
                            <div class="flex gap-2">
                                <Input v-model="newInclusion" placeholder="Add new inclusion"
                                    @keyup.enter="addNewInclusion" />
                                <Button variant="outline" @click="addNewInclusion">
                                    <Plus class="h-4 w-4" />
                                </Button>
                            </div>
                        </div>
                    </div>
                    <DialogFooter>
                        <Button variant="outline" @click="cancelAddPackage">Cancel</Button>
                        <Button @click="confirmAddPackage"
                            :disabled="!newPackage.name.trim() || newPackage.price <= 0 || newPackage.meals <= 0">
                            Add Package
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
                        <Button variant="destructive" @click="deleteDish()">
                            Delete
                        </Button>
                    </DialogFooter>
                </DialogContent>
            </Dialog>
        </div>
    </AppLayout>
</template>

<style scoped>
.card {
    @apply transition-all duration-200 shadow border border-gray-200;
}

.card:hover {
    @apply transform -translate-y-1 shadow-md;
}
</style>