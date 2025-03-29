<script lang="ts" setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import {
    Stepper,
    StepperDescription,
    StepperIndicator,
    StepperItem,
    StepperSeparator,
    StepperTitle,
    StepperTrigger
} from '@/components/ui/stepper';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Calendar, Package, Utensils, CreditCard, Check, ArrowLeft, X } from 'lucide-vue-next';
import { useToast } from '@/components/ui/toast/use-toast';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Customer Reservation',
        href: '/customer/reservation',
    }
];

// Step tracking
const currentStep = ref(1);
const totalSteps = 5;

// Form validation
const errors = ref<Record<string, string>>({});

// Add type definitions
interface Menu {
    id: number;
    name: string;
    price: number;
    description: string;
}

interface MenuType {
    id: number;
    name: string;
    icon: string;
    menus: Menu[];
    isOpen: boolean;
}

interface SelectedMenu extends Menu {
    type: string;
}

interface AddonPackage {
    id: number;
    name: string;
    description: string;
    serving_size: number;
    price: number;
    status: string;
    menu_limit: number;
    predefinedMenuType?: string;
    menus: {
        id: number;
        name: string;
        price: number;
        is_fixed: boolean;
        can_change: boolean;
        is_menu_type?: boolean;
        menu_type_id?: number;
    }[];
}

interface AddonCategory {
    id: number;
    name: string;
    description: string;
    status: string;
    packages: AddonPackage[];
}

// Form data
const formData = ref({
    // Customer Details
    firstName: 'test',
    lastName: 'test',
    email: 'test@test.com',
    phone: '09123456789',
    address: 'test',
    city: 'asadsadasdsad',
    state: 'asdasdasdasdasd',
    eventDate: '2025-04-12',
    eventStartTime: '10:00',
    eventDuration: '4',
    venue: 'testvenue',
    numberOfGuests: 200,
    eventType: 'testevent',
    dietaryRestrictions: 'testdietary',

    // Package Selection
    selectedPackage: null as number | null,

    // Menu Selection
    selectedMenus: [] as number[],

    // Add-ons
    selectedAddons: [] as number[],
    selectedAddonMenus: [] as number[],

    // Payment
    paymentMethod: '',
    depositAmount: 0
});

// Add duration options
const durationOptions = [
    { value: '2', label: '2 hours' },
    { value: '3', label: '3 hours' },
    { value: '4', label: '4 hours' },
    { value: '5', label: '5 hours' },
    { value: '6', label: '6 hours' },
];

// Add guest count options
const guestCountOptions = [
    { value: 50, label: '50 guests' },
    { value: 75, label: '75 guests' },
    { value: 100, label: '100 guests' },
    { value: 125, label: '125 guests' },
    { value: 150, label: '150 guests' },
    { value: 175, label: '175 guests' },
    { value: 200, label: '200 guests' }
];

// Package data
const packages = [
    {
        id: 1,
        name: 'Basic Package',
        price: 280,
        minGuests: 50,
        pricePerPerson: '280',
        description: 'Perfect for medium-sized gatherings',
        features: [
            'Minimum 50 guests',
            '5 menu selections with drinks',
            'Basic setup and service',
            'Standard tableware and linens'
        ],
        icon: '🍽️',
        menuLimit: 4,
        predefinedMenus: {
            required: true,
            menuTypeId: 1,
            minSelections: 1
        }
    },
    {
        id: 2,
        name: 'Standard Package',
        price: 350,
        minGuests: 50,
        pricePerPerson: '350',
        description: 'Ideal for medium to large events',
        features: [
            'Minimum 50 guests',
            '7 menu selections with drinks',
            'Premium setup and service',
            'Premium tableware and linens'
        ],
        icon: '✨',
        menuLimit: 5,
        predefinedMenus: {
            required: true,
            menuTypeId: 1,
            minSelections: 1
        }
    },
    {
        id: 3,
        name: 'Premium Package',
        price: 450,
        minGuests: 50,
        pricePerPerson: '450',
        description: 'Perfect for large celebrations',
        features: [
            'Minimum 50 guests',
            '10 menu selections with drinks',
            'Luxury setup and service',
            'Premium tableware and linens',
            'Additional service staff'
        ],
        icon: '🌟',
        menuLimit: 6,
        predefinedMenus: {
            required: true,
            menuTypeId: 1,
            minSelections: 1
        }
    },
    {
        id: 4,
        name: 'Grand Package',
        price: 600,
        minGuests: 50,
        pricePerPerson: '600',
        description: 'The ultimate catering experience',
        features: [
            'Minimum 50 guests',
            'Unlimited menu selections with drinks',
            'Luxury setup and service',
            'Premium tableware and linens',
            'Dedicated service staff',
            'Custom menu planning'
        ],
        icon: '👑',
        menuLimit: 7,
        predefinedMenus: {
            required: true,
            menuTypeId: 1,
            minSelections: 1
        }
    }
];

// Add computed property for package base prices
const packageBasePrices = computed(() => {
    return packages.map(pkg => ({
        id: pkg.id,
        basePrice: pkg.price * pkg.minGuests
    }));
});

// Update add-on categories data with new menu limits and predefined items
const addonCategories = ref<AddonCategory[]>([
    {
        id: 1,
        name: 'Lechon',
        description: 'Traditional Filipino roasted pig packages',
        status: 'active',
        packages: [
            {
                id: 1,
                name: 'Package A',
                description: 'Premium lechon package with customizable options',
                serving_size: 15,
                price: 2800,
                status: 'active',
                menu_limit: 3,
                menus: [
                    { id: 1, name: 'Lechon Belly', price: 2800, is_fixed: true, can_change: false },
                    { id: 2, name: 'Drinks', price: 0, is_fixed: false, can_change: true, is_menu_type: true, menu_type_id: 1 },
                    { id: 3, name: 'Beef', price: 0, is_fixed: false, can_change: true, is_menu_type: true, menu_type_id: 2 },
                    { id: 4, name: 'Pork', price: 0, is_fixed: false, can_change: true, is_menu_type: true, menu_type_id: 3 },
                    { id: 5, name: 'Chicken', price: 0, is_fixed: false, can_change: true, is_menu_type: true, menu_type_id: 4 },
                    { id: 6, name: 'Fish & Seafoods', price: 0, is_fixed: false, can_change: true, is_menu_type: true, menu_type_id: 5 },
                    { id: 7, name: 'Vegetables', price: 0, is_fixed: false, can_change: true, is_menu_type: true, menu_type_id: 6 },
                    { id: 8, name: 'Pasta', price: 0, is_fixed: false, can_change: true, is_menu_type: true, menu_type_id: 7 },
                    { id: 9, name: 'Noodles', price: 0, is_fixed: false, can_change: true, is_menu_type: true, menu_type_id: 8 },
                    { id: 10, name: 'Dessert', price: 0, is_fixed: false, can_change: true, is_menu_type: true, menu_type_id: 9 }
                ]
            },
            {
                id: 2,
                name: 'Lechon Belly',
                description: 'Crispy roasted pork belly',
                serving_size: 15,
                price: 2500,
                status: 'active',
                menu_limit: 4,
                menus: [
                    { id: 6, name: 'Roasted Pork Belly', price: 2500, is_fixed: true, can_change: false },
                    { id: 7, name: 'Lechon Sauce', price: 0, is_fixed: true, can_change: false },
                    { id: 8, name: 'Steamed Rice', price: 0, is_fixed: false, can_change: true },
                    { id: 9, name: 'Fresh Vegetables', price: 0, is_fixed: false, can_change: true }
                ]
            },
            {
                id: 3,
                name: 'Lechon Kawali',
                description: 'Crispy fried pork belly',
                serving_size: 15,
                price: 2000,
                status: 'active',
                menu_limit: 3,
                menus: [
                    { id: 10, name: 'Crispy Fried Pork Belly', price: 2000, is_fixed: true, can_change: false },
                    { id: 11, name: 'Lechon Sauce', price: 0, is_fixed: true, can_change: false },
                    { id: 12, name: 'Steamed Rice', price: 0, is_fixed: false, can_change: true }
                ]
            }
        ]
    },
    {
        id: 2,
        name: 'Seafood',
        description: 'Fresh seafood packages',
        status: 'active',
        packages: [
            {
                id: 4,
                name: 'Seafood Platter',
                description: 'Assorted fresh seafood selection',
                serving_size: 20,
                price: 4000,
                status: 'active',
                menu_limit: 5,
                menus: [
                    { id: 13, name: 'Grilled Fish', price: 1500, is_fixed: true, can_change: false },
                    { id: 14, name: 'Garlic Shrimp', price: 1500, is_fixed: true, can_change: false },
                    { id: 15, name: 'Crispy Squid', price: 1000, is_fixed: true, can_change: false },
                    { id: 16, name: 'Steamed Rice', price: 0, is_fixed: false, can_change: true },
                    { id: 17, name: 'Fresh Vegetables', price: 0, is_fixed: false, can_change: true }
                ]
            },
            {
                id: 5,
                name: 'Crab Feast',
                description: 'Fresh crab selection',
                serving_size: 15,
                price: 3500,
                status: 'active',
                menu_limit: 4,
                menus: [
                    { id: 18, name: 'Steamed Crabs', price: 2000, is_fixed: true, can_change: false },
                    { id: 19, name: 'Butter Garlic Sauce', price: 0, is_fixed: true, can_change: false },
                    { id: 20, name: 'Steamed Rice', price: 0, is_fixed: false, can_change: true },
                    { id: 21, name: 'Fresh Vegetables', price: 0, is_fixed: false, can_change: true }
                ]
            },
            {
                id: 6,
                name: 'Shrimp Special',
                description: 'Premium shrimp selection',
                serving_size: 15,
                price: 3000,
                status: 'active',
                menu_limit: 3,
                menus: [
                    { id: 22, name: 'Garlic Butter Shrimp', price: 1500, is_fixed: true, can_change: false },
                    { id: 23, name: 'Sweet & Sour Shrimp', price: 1500, is_fixed: true, can_change: false },
                    { id: 24, name: 'Steamed Rice', price: 0, is_fixed: false, can_change: true }
                ]
            }
        ]
    },
    {
        id: 3,
        name: 'Desserts',
        description: 'Sweet treats and desserts',
        status: 'active',
        packages: [
            {
                id: 7,
                name: 'Dessert Buffet',
                description: 'Assorted desserts and sweets',
                serving_size: 20,
                price: 2500,
                status: 'active',
                menu_limit: 5,
                menus: [
                    { id: 25, name: 'Assorted Cakes', price: 1000, is_fixed: true, can_change: false },
                    { id: 26, name: 'Fresh Fruits', price: 800, is_fixed: true, can_change: false },
                    { id: 27, name: 'Ice Cream', price: 700, is_fixed: true, can_change: false },
                    { id: 28, name: 'Cookies', price: 500, is_fixed: false, can_change: true },
                    { id: 29, name: 'Candy Bar', price: 500, is_fixed: false, can_change: true }
                ]
            },
            {
                id: 8,
                name: 'Chocolate Lovers',
                description: 'Chocolate-themed desserts',
                serving_size: 15,
                price: 2000,
                status: 'active',
                menu_limit: 4,
                menus: [
                    { id: 30, name: 'Chocolate Cake', price: 800, is_fixed: true, can_change: false },
                    { id: 31, name: 'Chocolate Truffles', price: 700, is_fixed: true, can_change: false },
                    { id: 32, name: 'Hot Chocolate', price: 500, is_fixed: true, can_change: false },
                    { id: 33, name: 'Chocolate Cookies', price: 0, is_fixed: false, can_change: true }
                ]
            },
            {
                id: 9,
                name: 'Filipino Desserts',
                description: 'Traditional Filipino sweets',
                serving_size: 15,
                price: 1800,
                status: 'active',
                menu_limit: 3,
                menus: [
                    { id: 34, name: 'Leche Flan', price: 600, is_fixed: true, can_change: false },
                    { id: 35, name: 'Bibingka', price: 600, is_fixed: true, can_change: false },
                    { id: 36, name: 'Puto Bumbong', price: 600, is_fixed: false, can_change: true }
                ]
            }
        ]
    },
    {
        id: 4,
        name: 'Drinks',
        description: 'Beverages and refreshments',
        status: 'active',
        packages: [
            {
                id: 10,
                name: 'Drinks Package',
                description: 'Assorted beverages package',
                serving_size: 20,
                price: 2000,
                status: 'active',
                menu_limit: 5,
                menus: [
                    { id: 37, name: 'Soft Drinks', price: 800, is_fixed: true, can_change: false },
                    { id: 38, name: 'Fresh Fruit Juices', price: 700, is_fixed: true, can_change: false },
                    { id: 39, name: 'Hot Coffee & Tea', price: 500, is_fixed: true, can_change: false },
                    { id: 40, name: 'Water', price: 0, is_fixed: false, can_change: true },
                    { id: 41, name: 'Hot Chocolate', price: 0, is_fixed: false, can_change: true }
                ]
            },
            {
                id: 11,
                name: 'Premium Drinks',
                description: 'Premium beverage selection',
                serving_size: 15,
                price: 2500,
                status: 'active',
                menu_limit: 4,
                menus: [
                    { id: 42, name: 'Fresh Smoothies', price: 1000, is_fixed: true, can_change: false },
                    { id: 43, name: 'Premium Coffee', price: 800, is_fixed: true, can_change: false },
                    { id: 44, name: 'Herbal Tea', price: 700, is_fixed: true, can_change: false },
                    { id: 45, name: 'Sparkling Water', price: 0, is_fixed: false, can_change: true }
                ]
            },
            {
                id: 12,
                name: 'Mocktail Package',
                description: 'Non-alcoholic cocktail selection',
                serving_size: 15,
                price: 2200,
                status: 'active',
                menu_limit: 3,
                menus: [
                    { id: 46, name: 'Virgin Mojito', price: 800, is_fixed: true, can_change: false },
                    { id: 47, name: 'Strawberry Daiquiri', price: 800, is_fixed: true, can_change: false },
                    { id: 48, name: 'Pina Colada', price: 600, is_fixed: false, can_change: true }
                ]
            }
        ]
    }
]);

// Add selected category and package states
const selectedCategory = ref<number | null>(null);
const selectedPackage = ref<number | null>(null);

// Update menu data with types
const menuTypes = ref<MenuType[]>([
    {
        id: 1,
        name: 'Drinks',
        icon: '🥤',
        menus: [
            { id: 33, name: 'Fresh Fruit Juice', price: 80, description: 'Assorted fresh fruit juices' },
            { id: 34, name: 'Iced Tea', price: 60, description: 'Refreshing iced tea' },
            { id: 35, name: 'Lemonade', price: 70, description: 'Fresh squeezed lemonade' },
            { id: 36, name: 'Hot Coffee', price: 80, description: 'Premium coffee with creamer' },
            { id: 37, name: 'Hot Tea', price: 60, description: 'Assorted tea bags' }
        ],
        isOpen: false
    },
    {
        id: 2,
        name: 'Beef',
        icon: '🥩',
        menus: [
            { id: 1, name: 'Beef Tenderloin', price: 200, description: 'Premium beef tenderloin with red wine sauce' },
            { id: 2, name: 'Beef Brisket', price: 180, description: 'Slow-cooked beef brisket with gravy' },
            { id: 3, name: 'Beef Steak', price: 190, description: 'Grilled beef steak with mushroom sauce' },
            { id: 4, name: 'Beef Kare-Kare', price: 170, description: 'Traditional Filipino beef stew with peanut sauce' }
        ],
        isOpen: false
    },
    {
        id: 3,
        name: 'Pork',
        icon: '🐷',
        menus: [
            { id: 5, name: 'Pork Belly', price: 160, description: 'Crispy pork belly with special sauce' },
            { id: 6, name: 'Pork Chop', price: 150, description: 'Grilled pork chop with apple sauce' },
            { id: 7, name: 'Pork Adobo', price: 140, description: 'Classic Filipino pork adobo' },
            { id: 8, name: 'Pork Lechon', price: 180, description: 'Roasted pork with crispy skin' }
        ],
        isOpen: false
    },
    {
        id: 4,
        name: 'Chicken',
        icon: '🍗',
        menus: [
            { id: 9, name: 'Roasted Chicken', price: 150, description: 'Herb-roasted whole chicken' },
            { id: 10, name: 'Chicken Curry', price: 140, description: 'Creamy chicken curry' },
            { id: 11, name: 'Chicken Adobo', price: 130, description: 'Traditional Filipino chicken adobo' },
            { id: 12, name: 'Chicken BBQ', price: 140, description: 'Grilled chicken with special sauce' }
        ],
        isOpen: false
    },
    {
        id: 5,
        name: 'Fish & Seafoods',
        icon: '🐟',
        menus: [
            { id: 13, name: 'Grilled Fish', price: 180, description: 'Fresh grilled fish with lemon butter sauce' },
            { id: 14, name: 'Shrimp Scampi', price: 200, description: 'Garlic butter shrimp' },
            { id: 15, name: 'Fish Fillet', price: 160, description: 'Crispy fish fillet with tartar sauce' },
            { id: 16, name: 'Seafood Paella', price: 190, description: 'Spanish-style seafood rice' }
        ],
        isOpen: false
    },
    {
        id: 6,
        name: 'Vegetables',
        icon: '🥬',
        menus: [
            { id: 17, name: 'Vegetable Stir Fry', price: 120, description: 'Fresh vegetables in Asian sauce' },
            { id: 18, name: 'Roasted Vegetables', price: 110, description: 'Seasonal roasted vegetables' },
            { id: 19, name: 'Vegetable Curry', price: 130, description: 'Coconut vegetable curry' },
            { id: 20, name: 'Fresh Garden Salad', price: 100, description: 'Mixed greens with vinaigrette' }
        ],
        isOpen: false
    },
    {
        id: 7,
        name: 'Pasta',
        icon: '🍝',
        menus: [
            { id: 21, name: 'Spaghetti Bolognese', price: 140, description: 'Classic meat sauce pasta' },
            { id: 22, name: 'Carbonara', price: 130, description: 'Creamy carbonara pasta' },
            { id: 23, name: 'Pesto Pasta', price: 120, description: 'Fresh basil pesto pasta' },
            { id: 24, name: 'Seafood Pasta', price: 160, description: 'Creamy seafood pasta' }
        ],
        isOpen: false
    },
    {
        id: 8,
        name: 'Noodles',
        icon: '🍜',
        menus: [
            { id: 25, name: 'Pancit Canton', price: 120, description: 'Filipino stir-fried noodles' },
            { id: 26, name: 'Pad Thai', price: 130, description: 'Thai-style stir-fried noodles' },
            { id: 27, name: 'Beef Noodle Soup', price: 140, description: 'Hearty beef noodle soup' },
            { id: 28, name: 'Chicken Noodle Soup', price: 130, description: 'Comforting chicken noodle soup' }
        ],
        isOpen: false
    },
    {
        id: 9,
        name: 'Dessert',
        icon: '🍰',
        menus: [
            { id: 29, name: 'Chocolate Cake', price: 100, description: 'Rich chocolate cake' },
            { id: 30, name: 'Fruit Platter', price: 120, description: 'Fresh fruit assortment' },
            { id: 31, name: 'Ice Cream', price: 80, description: 'Vanilla ice cream with toppings' },
            { id: 32, name: 'Leche Flan', price: 90, description: 'Classic Filipino custard' }
        ],
        isOpen: false
    }
]);

// Add toast setup after other refs
const { toast } = useToast();

// Add ref for menu section
const menuSection = ref<HTMLElement | null>(null);

// Add ref for guests section
const guestsSection = ref<HTMLElement | null>(null);

// Add validation functions for each step
const validateStep1 = () => {
    errors.value = {};
    let isValid = true;

    // Personal Information Validation
    if (!formData.value.firstName.trim()) {
        errors.value.firstName = 'First name is required';
        isValid = false;
    } else if (formData.value.firstName.length < 2) {
        errors.value.firstName = 'First name must be at least 2 characters';
        isValid = false;
    }

    if (!formData.value.lastName.trim()) {
        errors.value.lastName = 'Last name is required';
        isValid = false;
    } else if (formData.value.lastName.length < 2) {
        errors.value.lastName = 'Last name must be at least 2 characters';
        isValid = false;
    }

    if (!formData.value.email.trim()) {
        errors.value.email = 'Email is required';
        isValid = false;
    } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(formData.value.email)) {
        errors.value.email = 'Please enter a valid email address';
        isValid = false;
    }

    if (!formData.value.phone.trim()) {
        errors.value.phone = 'Phone number is required';
        isValid = false;
    } else if (!/^09\d{9}$/.test(formData.value.phone)) {
        errors.value.phone = 'Please enter a valid Philippine mobile number (09XXXXXXXXX)';
        isValid = false;
    }

    if (!formData.value.address.trim()) {
        errors.value.address = 'Address is required';
        isValid = false;
    }

    // Add city validation
    if (!formData.value.city.trim()) {
        errors.value.city = 'City is required';
        isValid = false;
    }

    // Add state validation
    if (!formData.value.state.trim()) {
        errors.value.state = 'State/Province is required';
        isValid = false;
    }

    // Event Information Validation
    const today = new Date();
    const selectedDate = new Date(formData.value.eventDate);
    const minDate = new Date(today.setDate(today.getDate() + 7)); // Minimum 7 days notice

    if (!formData.value.eventDate) {
        errors.value.eventDate = 'Event date is required';
        isValid = false;
    } else if (selectedDate < minDate) {
        errors.value.eventDate = 'Event must be at least 7 days from today';
        isValid = false;
    }

    if (!formData.value.eventStartTime) {
        errors.value.eventStartTime = 'Event start time is required';
        isValid = false;
    }

    if (!formData.value.eventDuration) {
        errors.value.eventDuration = 'Event duration is required';
        isValid = false;
    }

    if (!formData.value.venue.trim()) {
        errors.value.venue = 'Venue is required';
        isValid = false;
    }

    if (!formData.value.numberOfGuests || formData.value.numberOfGuests < 50) {
        errors.value.numberOfGuests = 'Minimum number of guests is 50';
        isValid = false;
    }

    if (!formData.value.eventType.trim()) {
        errors.value.eventType = 'Event type is required';
        isValid = false;
    }

    return isValid;
};

// Update hasRequiredPredefinedMenus computed property
const hasRequiredPredefinedMenus = computed(() => {
    if (!formData.value.selectedPackage) return false;

    const selectedPkg = packages.find(pkg => pkg.id === formData.value.selectedPackage);
    if (!selectedPkg?.predefinedMenus) return true;

    const requiredMenuTypeId = selectedPkg.predefinedMenus.menuTypeId;
    const requiredMinSelections = selectedPkg.predefinedMenus.minSelections;

    const selectedMenusFromType = formData.value.selectedMenus.filter(menuId => {
        const menuType = menuTypes.value.find(type =>
            type.menus.some(menu => menu.id === menuId)
        );
        return menuType?.id === requiredMenuTypeId;
    });

    // Must have exactly one drink selected
    return selectedMenusFromType.length === 1;
});

// Update toggleMenuSelection method
const toggleMenuSelection = (menuId: number) => {
    const index = formData.value.selectedMenus.indexOf(menuId);
    const selectedPkg = packages.find(pkg => pkg.id === formData.value.selectedPackage);

    if (!selectedPkg) return;

    // Get the menu type for the selected menu
    const menuType = menuTypes.value.find(type =>
        type.menus.some(menu => menu.id === menuId)
    );

    if (index === -1) {
        // Adding new menu
        if (menuType?.id === 1) { // Drinks category
            // Remove any existing drink selections
            formData.value.selectedMenus = formData.value.selectedMenus.filter(id => {
                const type = menuTypes.value.find(t =>
                    t.menus.some(m => m.id === id)
                );
                return type?.id !== 1;
            });
            formData.value.selectedMenus.push(menuId);
        } else {
            // For regular menu items
            // Check if a drink is already selected
            const hasDrink = formData.value.selectedMenus.some(id => {
                const type = menuTypes.value.find(t =>
                    t.menus.some(m => m.id === id)
                );
                return type?.id === 1; // Drinks category
            });

            if (!hasDrink) {
                toast({
                    title: "Drink Selection Required",
                    description: "Please select a drink first before choosing other menu items.",
                    variant: "destructive",
                });
                return;
            }

            const regularMenuCount = formData.value.selectedMenus.filter(id => {
                const type = menuTypes.value.find(t =>
                    t.menus.some(m => m.id === id)
                );
                return type?.id !== 1; // Not a drink
            }).length;

            if (regularMenuCount >= selectedPkg.menuLimit) {
                toast({
                    title: "Menu Limit Reached",
                    description: `You can only select up to ${selectedPkg.menuLimit} regular menu items.`,
                    variant: "destructive",
                });
                return;
            }
            formData.value.selectedMenus.push(menuId);
        }
    } else {
        // Removing menu
        formData.value.selectedMenus.splice(index, 1);
    }
};

// Update menuSelectionStatus computed property
const menuSelectionStatus = computed(() => {
    if (!formData.value.selectedPackage) return null;

    const selectedPkg = packages.find(pkg => pkg.id === formData.value.selectedPackage);
    if (!selectedPkg) return null;

    const regularMenuCount = formData.value.selectedMenus.filter(menuId => {
        const menuType = menuTypes.value.find(type =>
            type.menus.some(menu => menu.id === menuId)
        );
        return menuType?.id !== 1; // Not a drink
    }).length;

    const drinksSelected = formData.value.selectedMenus.filter(menuId => {
        const menuType = menuTypes.value.find(type =>
            type.menus.some(menu => menu.id === menuId)
        );
        return menuType?.id === 1; // Drinks category
    }).length;

    if (drinksSelected === 0) {
        return {
            text: "Please select a drink first",
            color: "text-red-600"
        };
    }

    if (regularMenuCount === 0) {
        return {
            text: `Please select ${selectedPkg.menuLimit} regular menu items`,
            color: "text-blue-600"
        };
    }

    if (regularMenuCount < selectedPkg.menuLimit) {
        return {
            text: `${regularMenuCount} of ${selectedPkg.menuLimit} regular menu items selected`,
            color: "text-blue-600"
        };
    }

    return {
        text: "Menu selection complete",
        color: "text-green-600"
    };
});

// Update validateStep2 function
const validateStep2 = () => {
    errors.value = {};
    let isValid = true;

    if (!formData.value.selectedPackage) {
        errors.value.selectedPackage = 'Please select a package';
        isValid = false;
    }

    // Check for drink selection
    const hasDrink = formData.value.selectedMenus.some(menuId => {
        const menuType = menuTypes.value.find(type =>
            type.menus.some(menu => menu.id === menuId)
        );
        return menuType?.id === 1; // Drinks category
    });

    if (!hasDrink) {
        errors.value.selectedMenus = 'Please select a drink';
        isValid = false;
    }

    // Check regular menu selections
    const regularMenuCount = formData.value.selectedMenus.filter(menuId => {
        const menuType = menuTypes.value.find(type =>
            type.menus.some(menu => menu.id === menuId)
        );
        return menuType?.id !== 1; // Not a drink
    }).length;

    const selectedPkg = packages.find(pkg => pkg.id === formData.value.selectedPackage);
    if (selectedPkg && regularMenuCount < selectedPkg.menuLimit) {
        errors.value.selectedMenus = `Please select ${selectedPkg.menuLimit} regular menu items`;
        isValid = false;
    }

    return isValid;
};

const validateStep3 = () => {
    errors.value = {};
    let isValid = true;

    // Validate all required fields from previous steps
    if (!validateStep1() || !validateStep2()) {
        isValid = false;
    }

    return isValid;
};

const validateStep4 = () => {
    errors.value = {};
    let isValid = true;

    if (!formData.value.paymentMethod) {
        errors.value.paymentMethod = 'Please select a payment method';
        isValid = false;
    }

    if (formData.value.depositAmount <= 0) {
        errors.value.depositAmount = 'Please enter a valid deposit amount';
        isValid = false;
    }

    return isValid;
};

// Update navigation functions with validation
const nextStep = () => {
    let isValid = true;

    switch (currentStep.value) {
        case 1:
            isValid = validateStep1();
            break;
        case 2:
            isValid = validateStep2();
            break;
        case 3:
            isValid = validateStep3();
            break;
    }

    if (isValid && currentStep.value < totalSteps) {
        currentStep.value++;
    }
};

const prevStep = () => {
    if (currentStep.value > 1) {
        currentStep.value--;
    }
};

// Update submit function with validation
const submitReservation = () => {
    if (!validateStep4()) {
        return;
    }

    // TODO: Implement reservation submission
    console.log('Submitting reservation:', formData.value);
};

// Update canProceed computed property
const canProceed = computed(() => {
    switch (currentStep.value) {
        case 1:
            return validateStep1();
        case 2:
            return validateStep2();
        case 3:
            return validateStep3();
        case 4:
            return validateStep4();
        default:
            return false;
    }
});

const buttonText = computed(() => {
    if (currentStep.value === totalSteps) {
        return 'Submit Reservation';
    }
    return 'Next';
});

// Update total price computation to include add-ons
const totalPrice = computed(() => {
    if (!formData.value.selectedPackage || !formData.value.numberOfGuests) return 0;

    const selectedPkg = packages.find(pkg => pkg.id === formData.value.selectedPackage);
    if (!selectedPkg) return 0;

    const basePrice = selectedPkg.price * formData.value.numberOfGuests;

    // Add add-on package prices
    const addonTotal = formData.value.selectedAddons.reduce((total, addonId) => {
        const addon = addonCategories.value.find(pkg => pkg.id === addonId);
        return total + (addon?.packages.find(p => p.id === addonId)?.price || 0);
    }, 0);

    return basePrice + addonTotal;
});

// Add computed property for selected package menu limit
const selectedPackageMenuLimit = computed(() => {
    if (!formData.value.selectedPackage) return 0;
    const selectedPkg = packages.find(pkg => pkg.id === formData.value.selectedPackage);
    return selectedPkg?.menuLimit || 0;
});

// Update remainingMenuSelections computed property
const remainingMenuSelections = computed(() => {
    if (!formData.value.selectedPackage) return 0;
    const selectedPkg = packages.find(pkg => pkg.id === formData.value.selectedPackage);
    if (!selectedPkg) return 0;

    // Count only regular menu items (excluding drinks)
    const regularMenuCount = formData.value.selectedMenus.filter(menuId => {
        const menuType = menuTypes.value.find(type =>
            type.menus.some(menu => menu.id === menuId)
        );
        return menuType?.id !== 1; // Not a drink
    }).length;

    return selectedPkg.menuLimit - regularMenuCount;
});

// Add method to toggle menu type
const toggleMenuType = (typeId: number) => {
    const type = menuTypes.value.find(t => t.id === typeId);
    if (type) {
        type.isOpen = !type.isOpen;
    }
};

// Update computed property for selected menus with details
const selectedMenusWithDetails = computed(() => {
    return formData.value.selectedMenus.map(menuId => {
        for (const type of menuTypes.value) {
            const menu = type.menus.find(m => m.id === menuId);
            if (menu) return { ...menu, type: type.name } as SelectedMenu;
        }
        return null;
    }).filter((menu): menu is SelectedMenu => menu !== null);
});

// Update package selection to include scrolling
const selectPackage = (packageId: number) => {
    formData.value.selectedPackage = formData.value.selectedPackage === packageId ? null : packageId;
    if (formData.value.selectedPackage) {
        // Wait for DOM update then scroll
        setTimeout(() => {
            guestsSection.value?.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }, 100);
    }
};

// Update toggleAddon method
const toggleAddon = (packageId: number) => {
    const index = formData.value.selectedAddons.indexOf(packageId);
    if (index === -1) {
        formData.value.selectedAddons.push(packageId);
    } else {
        formData.value.selectedAddons.splice(index, 1);
    }
};

// Add computed properties for selected package details
const selectedPackageDetails = computed(() => {
    if (!selectedCategory.value || !selectedPackage.value) return null;

    const category = addonCategories.value.find(c => c.id === selectedCategory.value);
    if (!category) return null;

    return category.packages.find(p => p.id === selectedPackage.value);
});

// Add computed property for remaining menu selections
const remainingAddonMenuSelections = computed(() => {
    if (!selectedPackageDetails.value) return 0;
    const predefinedCount = selectedPackageDetails.value.menus.filter(m => m.is_fixed).length;
    return selectedPackageDetails.value.menu_limit - predefinedCount;
});

// Add method to toggle additional menu selection
const toggleAdditionalMenu = (menuId: number) => {
    if (!selectedPackageDetails.value) return;

    const index = formData.value.selectedAddonMenus.indexOf(menuId);
    if (index === -1) {
        if (formData.value.selectedAddonMenus.length < remainingAddonMenuSelections.value) {
            formData.value.selectedAddonMenus.push(menuId);
        } else {
            toast({
                title: "Menu Limit Reached",
                description: `You can only select ${remainingAddonMenuSelections.value} additional menu items.`,
                variant: "destructive",
            });
        }
    } else {
        formData.value.selectedAddonMenus.splice(index, 1);
    }
};

// Add computed property for filtered packages
const filteredPackages = computed(() => {
    if (!selectedCategory.value) {
        return addonCategories.value.flatMap(category => category.packages);
    }
    return addonCategories.value.find(category => category.id === selectedCategory.value)?.packages || [];
});

// Add computed property for add-on button text
const addonButtonText = computed(() => {
    return selectedPackage.value && formData.value.selectedAddons.includes(selectedPackage.value) ? 'Remove Package' : 'Add Package';
});
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">

        <Head title="Customer Reservation" />

        <div class="container mx-auto py-8">
            <!-- Header -->
            <div class="mb-8 text-center">
                <h1 class="text-3xl font-bold">Make a Reservation</h1>
                <p class="mt-2 text-muted-foreground">Book your catering service in a few simple steps</p>
            </div>

            <!-- Stepper -->
            <Stepper :current-step="currentStep" class="mb-8">
                <div class="flex items-center justify-between">
                    <StepperItem :step="1" class="flex-1">
                        <div class="flex items-center">
                            <div
                                class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full border-2 border-primary bg-primary text-primary-foreground">
                                1
                            </div>
                            <div class="ml-4 flex flex-col">
                                <StepperTitle>Customer Details</StepperTitle>
                                <StepperDescription>Enter your information and event details</StepperDescription>
                            </div>
                            <div class="mx-4 h-px flex-1 bg-border"></div>
                        </div>
                    </StepperItem>

                    <StepperItem :step="2" class="flex-1">
                        <div class="flex items-center">
                            <div
                                class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full border-2 border-primary bg-primary text-primary-foreground">
                                2
                            </div>
                            <div class="ml-4 flex flex-col">
                                <StepperTitle>Select Package & Menus</StepperTitle>
                                <StepperDescription>Choose your catering package and menu items</StepperDescription>
                            </div>
                            <div class="mx-4 h-px flex-1 bg-border"></div>
                        </div>
                    </StepperItem>

                    <StepperItem :step="3" class="flex-1">
                        <div class="flex items-center">
                            <div
                                class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full border-2 border-primary bg-primary text-primary-foreground">
                                3
                            </div>
                            <div class="ml-4 flex flex-col">
                                <StepperTitle>Select Add-on Packages</StepperTitle>
                                <StepperDescription>Enhance your event with additional services</StepperDescription>
                            </div>
                            <div class="mx-4 h-px flex-1 bg-border"></div>
                        </div>
                    </StepperItem>

                    <StepperItem :step="4" class="flex-1">
                        <div class="flex items-center">
                            <div
                                class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full border-2 border-primary bg-primary text-primary-foreground">
                                4
                            </div>
                            <div class="ml-4 flex flex-col">
                                <StepperTitle>Review Details</StepperTitle>
                                <StepperDescription>Confirm your reservation details</StepperDescription>
                            </div>
                            <div class="mx-4 h-px flex-1 bg-border"></div>
                        </div>
                    </StepperItem>

                    <StepperItem :step="5" class="flex-1">
                        <div class="flex items-center">
                            <div
                                class="flex h-8 w-8 shrink-0 items-center justify-center rounded-full border-2 border-primary bg-primary text-primary-foreground">
                                5
                            </div>
                            <div class="ml-4 flex flex-col">
                                <StepperTitle>Payment</StepperTitle>
                                <StepperDescription>Complete your reservation</StepperDescription>
                            </div>
                        </div>
                    </StepperItem>
                </div>
            </Stepper>

            <!-- Step Content -->
            <Card>
                <CardContent class="p-6">
                    <!-- Step 1: Customer Details -->
                    <div v-if="currentStep === 1">
                        <div class="mb-6 flex items-center gap-2">
                            <Calendar class="h-5 w-5 text-primary" />
                            <h2 class="text-xl font-semibold">Customer Details</h2>
                        </div>
                        <div class="grid gap-6 md:grid-cols-2">
                            <!-- Personal Information -->
                            <div class="space-y-4">
                                <div class="space-y-2">
                                    <Label for="firstName">First Name</Label>
                                    <Input id="firstName" v-model="formData.firstName"
                                        placeholder="Enter your first name"
                                        :class="{ 'border-red-500': errors.firstName }" />
                                    <p v-if="errors.firstName" class="text-sm text-red-500">{{ errors.firstName }}</p>
                                </div>
                                <div class="space-y-2">
                                    <Label for="lastName">Last Name</Label>
                                    <Input id="lastName" v-model="formData.lastName" placeholder="Enter your last name"
                                        :class="{ 'border-red-500': errors.lastName }" />
                                    <p v-if="errors.lastName" class="text-sm text-red-500">{{ errors.lastName }}</p>
                                </div>
                                <div class="space-y-2">
                                    <Label for="email">Email</Label>
                                    <Input id="email" v-model="formData.email" type="email"
                                        placeholder="Enter your email" :class="{ 'border-red-500': errors.email }" />
                                    <p v-if="errors.email" class="text-sm text-red-500">{{ errors.email }}</p>
                                </div>
                                <div class="space-y-2">
                                    <Label for="phone">Phone</Label>
                                    <Input id="phone" v-model="formData.phone" type="tel"
                                        placeholder="Enter your phone number"
                                        :class="{ 'border-red-500': errors.phone }" />
                                    <p v-if="errors.phone" class="text-sm text-red-500">{{ errors.phone }}</p>
                                </div>
                                <div class="space-y-2">
                                    <Label for="address">Address</Label>
                                    <Input id="address" v-model="formData.address" placeholder="Enter your address"
                                        :class="{ 'border-red-500': errors.address }" />
                                    <p v-if="errors.address" class="text-sm text-red-500">{{ errors.address }}</p>
                                </div>
                                <div class="space-y-2">
                                    <Label for="city">City</Label>
                                    <Input id="city" v-model="formData.city" placeholder="Enter your city"
                                        :class="{ 'border-red-500': errors.city }" />
                                    <p v-if="errors.city" class="text-sm text-red-500">{{ errors.city }}</p>
                                </div>
                                <div class="space-y-2">
                                    <Label for="state">State/Province</Label>
                                    <Input id="state" v-model="formData.state" placeholder="Enter your state/province"
                                        :class="{ 'border-red-500': errors.state }" />
                                    <p v-if="errors.state" class="text-sm text-red-500">{{ errors.state }}</p>
                                </div>
                            </div>

                            <!-- Event Information -->
                            <div class="space-y-4">
                                <div class="space-y-2">
                                    <Label for="numberOfGuests">Number of Guests</Label>
                                    <select id="numberOfGuests" v-model="formData.numberOfGuests"
                                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                        :class="{ 'border-red-500': errors.numberOfGuests }">
                                        <option v-for="option in guestCountOptions" :key="option.value"
                                            :value="option.value">
                                            {{ option.label }}
                                        </option>
                                    </select>
                                    <p v-if="errors.numberOfGuests" class="text-sm text-red-500">{{
                                        errors.numberOfGuests }}</p>
                                </div>
                                <div class="space-y-2">
                                    <Label for="eventDate">Event Date</Label>
                                    <Input id="eventDate" v-model="formData.eventDate" type="date"
                                        :class="{ 'border-red-500': errors.eventDate }" />
                                    <p v-if="errors.eventDate" class="text-sm text-red-500">{{ errors.eventDate }}</p>
                                </div>
                                <div class="space-y-2">
                                    <Label for="eventStartTime">Event Start Time</Label>
                                    <Input id="eventStartTime" v-model="formData.eventStartTime" type="time"
                                        :class="{ 'border-red-500': errors.eventStartTime }" />
                                    <p v-if="errors.eventStartTime" class="text-sm text-red-500">{{
                                        errors.eventStartTime }}</p>
                                </div>
                                <div class="space-y-2">
                                    <Label for="eventDuration">Event Duration</Label>
                                    <select id="eventDuration" v-model="formData.eventDuration"
                                        class="flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50"
                                        :class="{ 'border-red-500': errors.eventDuration }">
                                        <option v-for="option in durationOptions" :key="option.value"
                                            :value="option.value">
                                            {{ option.label }}
                                        </option>
                                    </select>
                                    <p v-if="errors.eventDuration" class="text-sm text-red-500">{{ errors.eventDuration
                                        }}</p>
                                </div>
                                <div class="space-y-2">
                                    <Label for="venue">Venue</Label>
                                    <Input id="venue" v-model="formData.venue" placeholder="Enter venue address"
                                        :class="{ 'border-red-500': errors.venue }" />
                                    <p v-if="errors.venue" class="text-sm text-red-500">{{ errors.venue }}</p>
                                </div>
                                <div class="space-y-2">
                                    <Label for="eventType">Event Type</Label>
                                    <Input id="eventType" v-model="formData.eventType"
                                        placeholder="e.g., Wedding, Corporate, Birthday"
                                        :class="{ 'border-red-500': errors.eventType }" />
                                    <p v-if="errors.eventType" class="text-sm text-red-500">{{ errors.eventType }}</p>
                                </div>
                                <div class="space-y-2">
                                    <Label for="dietaryRestrictions">Dietary Restrictions</Label>
                                    <Input id="dietaryRestrictions" v-model="formData.dietaryRestrictions"
                                        placeholder="e.g., Vegetarian, Halal, etc." />
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Package & Menu Selection -->
                    <div v-if="currentStep === 2">
                        <div class="mb-6 flex items-center gap-2">
                            <Package class="h-5 w-5 text-primary" />
                            <h2 class="text-xl font-semibold">Select Package & Menus</h2>
                        </div>
                        <div class="space-y-6">
                            <!-- Package Selection -->
                            <div class="grid gap-6 md:grid-cols-2">
                                <Card v-for="pkg in packages" :key="pkg.id" :class="[
                                    'relative cursor-pointer transition-all hover:shadow-lg',
                                    formData.selectedPackage === pkg.id ? 'border-primary ring-1 ring-primary' : ''
                                ]" @click="selectPackage(pkg.id)">
                                    <CardContent class="p-6">
                                        <div class="flex items-start justify-between">
                                            <div>
                                                <div class="text-2xl mb-2">{{ pkg.icon }}</div>
                                                <h3 class="text-lg font-semibold">{{ pkg.name }}</h3>
                                                <p class="mt-1 text-sm text-muted-foreground">{{ pkg.description }}</p>
                                            </div>
                                            <div class="text-right">
                                                <div class="text-2xl font-bold">₱{{ pkg.price }}</div>
                                                <div class="text-sm text-muted-foreground">₱{{ pkg.pricePerPerson
                                                }}/person</div>
                                                <div class="mt-1 text-sm font-medium">
                                                    Base price: ₱{{packageBasePrices.find(bp => bp.id ===
                                                        pkg.id)?.basePrice}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="mt-4 space-y-2">
                                            <div v-for="feature in pkg.features" :key="feature"
                                                class="flex items-center gap-2">
                                                <Check class="h-4 w-4 text-primary" />
                                                <span class="text-sm">{{ feature }}</span>
                                            </div>
                                        </div>
                                        <div v-if="formData.selectedPackage === pkg.id"
                                            class="absolute -right-2 -top-2 flex h-5 w-5 items-center justify-center rounded-full bg-primary text-primary-foreground">
                                            <Check class="h-3 w-3" />
                                        </div>
                                    </CardContent>
                                </Card>
                            </div>

                            <!-- Package Selection Message -->
                            <div v-if="!formData.selectedPackage" class="text-center text-muted-foreground">
                                Please select a package to continue
                            </div>

                            <!-- Number of Guests and Total Price -->
                            <div v-if="formData.selectedPackage" class="space-y-4 pt-8" ref="guestsSection">
                                <!-- Number of Guests -->
                                <div class="flex items-center gap-4">
                                    <Label for="numberOfGuests" class="whitespace-nowrap">Number of Guests:</Label>
                                    <select id="numberOfGuests" v-model="formData.numberOfGuests"
                                        class="flex h-10 w-32 rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-50">
                                        <option v-for="option in guestCountOptions" :key="option.value"
                                            :value="option.value">
                                            {{ option.label }}
                                        </option>
                                    </select>
                                    <p class="text-sm text-muted-foreground">
                                        Select from available guest counts
                                    </p>
                                </div>

                                <!-- Total Price Display -->
                                <div class="rounded-lg bg-primary/5 p-4">
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <h3 class="text-lg font-semibold">Total Price</h3>
                                            <p class="text-sm text-muted-foreground">
                                                {{ formData.numberOfGuests }} guests × ₱{{packages.find(pkg => pkg.id
                                                    === formData.selectedPackage)?.price}}/person
                                            </p>
                                        </div>
                                        <div class="text-2xl font-bold">₱{{ totalPrice }}</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Menu Selection -->
                            <div v-if="formData.selectedPackage" class="mt-8" ref="menuSection">
                                <div class="flex items-center justify-between">
                                    <h3 class="text-lg font-semibold">Select Menu Items</h3>
                                    <p :class="['text-sm font-medium', menuSelectionStatus?.color]">
                                        {{ menuSelectionStatus?.text }}
                                    </p>
                                </div>

                                <!-- Menu Types Dropdown -->
                                <div class="mt-4">
                                    <div v-for="type in menuTypes" :key="type.id" class="mb-4">
                                        <button @click="toggleMenuType(type.id)"
                                            class="flex w-full items-center justify-between rounded-lg border p-4 hover:bg-accent cursor-pointer transition-colors">
                                            <div class="flex items-center gap-3">
                                                <span class="text-xl">{{ type.icon }}</span>
                                                <span class="font-medium">{{ type.name }}</span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <span class="text-sm text-muted-foreground">
                                                    {{type.menus.filter(menu =>
                                                        formData.selectedMenus.includes(menu.id)).length}} selected
                                                </span>
                                                <svg class="h-5 w-5 transform transition-transform duration-200"
                                                    :class="{ 'rotate-180': type.isOpen }"
                                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                                                    fill="currentColor">
                                                    <path fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd" />
                                                </svg>
                                            </div>
                                        </button>

                                        <!-- Menu Items -->
                                        <div v-if="type.isOpen" class="mt-2 grid gap-4 md:grid-cols-2">
                                            <div v-for="menu in type.menus" :key="menu.id"
                                                class="relative flex cursor-pointer items-start justify-between rounded-lg border p-4 transition-all hover:border-primary"
                                                :class="{
                                                    'border-primary bg-primary/5': formData.selectedMenus.includes(menu.id),
                                                    'opacity-50 cursor-not-allowed': remainingMenuSelections <= 0 && !formData.selectedMenus.includes(menu.id)
                                                }" @click="toggleMenuSelection(menu.id)">
                                                <div class="space-y-1">
                                                    <div class="flex items-center gap-2">
                                                        <input type="checkbox" :id="'menu-' + menu.id"
                                                            :checked="formData.selectedMenus.includes(menu.id)"
                                                            :disabled="remainingMenuSelections <= 0 && !formData.selectedMenus.includes(menu.id)"
                                                            class="h-4 w-4 rounded border-gray-300 text-primary focus:ring-primary disabled:opacity-50" />
                                                        <label :for="'menu-' + menu.id"
                                                            class="font-medium cursor-pointer"
                                                            :class="{ 'cursor-not-allowed': remainingMenuSelections <= 0 && !formData.selectedMenus.includes(menu.id) }">
                                                            {{ menu.name }}
                                                        </label>
                                                    </div>
                                                    <p class="text-sm text-muted-foreground">{{ menu.description }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Selected Menus Summary -->
                                <div v-if="formData.selectedMenus.length > 0" class="mt-6 rounded-lg bg-primary/5 p-4">
                                    <div class="flex items-center justify-between mb-4">
                                        <h4 class="font-medium">Selected Menus</h4>
                                        <span class="text-sm text-muted-foreground">
                                            {{ formData.selectedMenus.length }} items selected
                                        </span>
                                    </div>

                                    <!-- Group menus by category -->
                                    <div class="space-y-4">
                                        <div v-for="type in menuTypes" :key="type.id">
                                            <!-- Only show category if it has selected items -->
                                            <div v-if="type.menus.some(menu => formData.selectedMenus.includes(menu.id))"
                                                class="space-y-2">
                                                <div class="flex items-center gap-2">
                                                    <span class="text-xl">{{ type.icon }}</span>
                                                    <h5 class="font-medium text-sm text-muted-foreground">{{ type.name
                                                    }}</h5>
                                                </div>
                                                <div class="ml-6 space-y-1">
                                                    <div v-for="menu in type.menus.filter(m => formData.selectedMenus.includes(m.id))"
                                                        :key="menu.id" class="flex items-center py-1">
                                                        <span class="text-sm">{{ menu.name }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Add-ons Selection -->
                    <div v-if="currentStep === 3">
                        <div class="mb-6 flex items-center gap-2">
                            <Package class="h-5 w-5 text-primary" />
                            <h2 class="text-xl font-semibold">Select Add-on Packages</h2>
                        </div>
                        <div class="space-y-6">
                            <!-- Category Filters -->
                            <div class="flex flex-wrap gap-2">
                                <Button v-for="category in addonCategories" :key="category.id" variant="outline"
                                    :class="{ 'bg-primary text-primary-foreground': selectedCategory === category.id }"
                                    @click="selectedCategory = selectedCategory === category.id ? null : category.id">
                                    {{ category.name }}
                                </Button>
                            </div>

                            <!-- Packages Grid -->
                            <div class="grid gap-6 md:grid-cols-2">
                                <Card v-for="pkg in filteredPackages" :key="pkg.id"
                                    class="relative cursor-pointer transition-all hover:shadow-lg"
                                    :class="{ 'border-primary ring-1 ring-primary': formData.selectedAddons.includes(pkg.id) }"
                                    @click="selectedPackage = pkg.id">
                                    <CardContent class="p-6">
                                        <div class="flex items-start justify-between">
                                            <div class="space-y-1">
                                                <h3 class="text-lg font-semibold">{{ pkg.name }}</h3>
                                                <p class="text-sm text-muted-foreground">{{ pkg.description }}</p>
                                            </div>
                                            <div class="text-right">
                                                <div class="text-2xl font-bold">₱{{ pkg.price }}</div>
                                                <div class="text-sm text-muted-foreground">for {{ pkg.serving_size }}
                                                    persons</div>
                                            </div>
                                        </div>
                                        <div class="mt-4 space-y-2">
                                            <div class="text-sm text-muted-foreground">
                                                {{ pkg.menu_limit }} menu items
                                            </div>
                                        </div>
                                    </CardContent>
                                </Card>
                            </div>

                            <!-- Package Details Modal -->
                            <div v-if="selectedPackage"
                                class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
                                <Card class="w-[800px] h-[600px] mx-4 flex flex-col">
                                    <CardContent class="p-6 flex flex-col h-full overflow-hidden">
                                        <div class="flex items-center justify-between mb-6">
                                            <h3 class="text-xl font-semibold">{{ selectedPackageDetails?.name }}</h3>
                                            <Button variant="ghost" @click="selectedPackage = null">
                                                <X class="h-4 w-4" />
                                            </Button>
                                        </div>

                                        <!-- Package Info -->
                                        <div class="mb-6">
                                            <p class="text-sm text-muted-foreground">{{
                                                selectedPackageDetails?.description }}</p>
                                            <div class="mt-2 flex items-center justify-between">
                                                <div class="text-2xl font-bold">₱{{ selectedPackageDetails?.price }}
                                                </div>
                                                <div class="text-sm text-muted-foreground">for {{
                                                    selectedPackageDetails?.serving_size }} persons</div>
                                            </div>
                                        </div>

                                        <!-- Scrollable Content -->
                                        <div class="flex-1 overflow-y-auto pr-4">
                                            <!-- Menu Selection -->
                                            <div class="space-y-6">
                                                <!-- Predefined Menu Type -->
                                                <div v-if="selectedPackageDetails?.predefinedMenuType">
                                                    <h4 class="font-medium mb-4">Select {{
                                                        selectedPackageDetails.predefinedMenuType }} First</h4>
                                                    <div class="grid gap-4 md:grid-cols-2">
                                                        <div v-for="menu in selectedPackageDetails.menus.filter(m => m.is_fixed)"
                                                            :key="menu.id"
                                                            class="flex items-center justify-between p-4 rounded-lg border cursor-pointer transition-all hover:border-primary"
                                                            :class="{ 'border-primary bg-primary/5': formData.selectedAddonMenus.includes(menu.id) }"
                                                            @click="toggleAdditionalMenu(menu.id)">
                                                            <div class="space-y-1">
                                                                <div class="font-medium">{{ menu.name }}</div>
                                                            </div>
                                                            <div class="text-right">
                                                                <div class="font-medium">₱{{ menu.price }}</div>
                                                                <div class="text-sm text-muted-foreground">
                                                                    {{ formData.selectedAddonMenus.includes(menu.id) ?
                                                                        'Selected' : 'Click to select' }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Additional Menu Selection -->
                                                <div v-if="remainingAddonMenuSelections > 0">
                                                    <h4 class="font-medium mb-4">Select {{ remainingAddonMenuSelections
                                                    }} Additional Items</h4>
                                                    <div class="space-y-6">
                                                        <!-- Menu Types -->
                                                        <div v-for="menu in selectedPackageDetails?.menus.filter(m => !m.is_fixed && m.is_menu_type)"
                                                            :key="menu.id" class="space-y-4">
                                                            <div class="flex items-center justify-between p-4 rounded-lg border cursor-pointer transition-all hover:border-primary"
                                                                :class="{ 'border-primary bg-primary/5': formData.selectedAddonMenus.includes(menu.id) }"
                                                                @click="toggleAdditionalMenu(menu.id)">
                                                                <div class="space-y-1">
                                                                    <div class="font-medium">{{ menu.name }}</div>
                                                                </div>
                                                                <div class="text-right">
                                                                    <div class="text-sm text-muted-foreground">
                                                                        {{ formData.selectedAddonMenus.includes(menu.id)
                                                                            ? 'Selected' : 'Click to select' }}
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Show menus under selected type -->
                                                            <div v-if="formData.selectedAddonMenus.includes(menu.id)"
                                                                class="ml-6">
                                                                <div class="grid gap-4 md:grid-cols-2">
                                                                    <div v-for="typeMenu in menuTypes.find(t => t.id === menu.menu_type_id)?.menus"
                                                                        :key="typeMenu.id"
                                                                        class="flex items-center justify-between p-4 rounded-lg border cursor-pointer transition-all hover:border-primary"
                                                                        :class="{ 'border-primary bg-primary/5': formData.selectedAddonMenus.includes(typeMenu.id) }"
                                                                        @click="toggleAdditionalMenu(typeMenu.id)">
                                                                        <div class="space-y-1">
                                                                            <div class="font-medium">{{ typeMenu.name }}
                                                                            </div>
                                                                            <div class="text-sm text-muted-foreground">
                                                                                {{ typeMenu.description }}</div>
                                                                        </div>
                                                                        <div class="text-right">
                                                                            <div class="font-medium">₱{{ typeMenu.price
                                                                            }}</div>
                                                                            <div class="text-sm text-muted-foreground">
                                                                                {{
                                                                                    formData.selectedAddonMenus.includes(typeMenu.id)
                                                                                        ? 'Selected' : 'Click to select' }}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Fixed Footer -->
                                        <div class="mt-6 pt-6 border-t">
                                            <div class="flex items-center justify-between">
                                                <div class="text-sm text-muted-foreground">
                                                    {{ formData.selectedAddonMenus.length }}/{{
                                                        selectedPackageDetails?.menu_limit }} items selected
                                                </div>
                                                <Button @click="toggleAddon(selectedPackage)"
                                                    :class="{ 'bg-primary': formData.selectedAddons.includes(selectedPackage) }">
                                                    {{ addonButtonText }}
                                                </Button>
                                            </div>
                                        </div>
                                    </CardContent>
                                </Card>
                            </div>

                            <!-- Total Price with Add-ons -->
                            <div class="mt-6 rounded-lg bg-primary/5 p-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-lg font-semibold">Total Price with Add-ons</h3>
                                        <p class="text-sm text-muted-foreground">
                                            Base price + {{ formData.selectedAddons.length }} add-on packages
                                        </p>
                                    </div>
                                    <div class="text-2xl font-bold">₱{{ totalPrice }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 4: Review Details -->
                    <div v-if="currentStep === 4">
                        <div class="mb-6 flex items-center gap-2">
                            <Calendar class="h-5 w-5 text-primary" />
                            <h2 class="text-xl font-semibold">Review Details</h2>
                        </div>
                        <div class="space-y-6">
                            <!-- Customer Information -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-semibold">Customer Information</h3>
                                <div class="grid gap-4 md:grid-cols-2">
                                    <div>
                                        <p class="text-sm text-muted-foreground">Name</p>
                                        <p class="font-medium">{{ formData.firstName }} {{ formData.lastName }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-muted-foreground">Contact</p>
                                        <p class="font-medium">{{ formData.email }}</p>
                                        <p class="font-medium">{{ formData.phone }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-muted-foreground">Address</p>
                                        <p class="font-medium">{{ formData.address }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-muted-foreground">City</p>
                                        <p class="font-medium">{{ formData.city }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-muted-foreground">State/Province</p>
                                        <p class="font-medium">{{ formData.state }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Event Details -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-semibold">Event Details</h3>
                                <div class="grid gap-4 md:grid-cols-2">
                                    <div>
                                        <p class="text-sm text-muted-foreground">Date & Time</p>
                                        <p class="font-medium">{{ formData.eventDate }} at {{ formData.eventStartTime }}
                                        </p>
                                        <p class="font-medium">{{ formData.eventDuration }} hours duration</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-muted-foreground">Venue & Guests</p>
                                        <p class="font-medium">{{ formData.venue }}</p>
                                        <p class="font-medium">{{ formData.numberOfGuests }} guests</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-muted-foreground">Event Type</p>
                                        <p class="font-medium">{{ formData.eventType }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Package & Menu -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-semibold">Package & Menu</h3>
                                <div class="grid gap-4 md:grid-cols-2">
                                    <div>
                                        <p class="text-sm text-muted-foreground">Selected Package</p>
                                        <p class="font-medium">{{packages.find(pkg => pkg.id ===
                                            formData.selectedPackage)?.name}}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-muted-foreground">Selected Menus</p>
                                        <div class="space-y-1">
                                            <div v-for="menu in selectedMenusWithDetails" :key="menu.id"
                                                class="text-sm">
                                                {{ menu.name }} ({{ menu.type }})
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Add-ons -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-semibold">Selected Add-ons</h3>
                                <div class="grid gap-4 md:grid-cols-2">
                                    <div v-for="category in addonCategories" :key="category.id">
                                        <p class="text-sm text-muted-foreground">{{ category.name }}</p>
                                        <div class="space-y-1">
                                            <div v-for="addon in category.packages.filter(a => formData.selectedAddons.includes(a.id))"
                                                :key="addon.id" class="text-sm">
                                                {{ addon.name }} (₱{{ addon.price }})
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Total Price -->
                            <div class="rounded-lg bg-primary/5 p-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-lg font-semibold">Total Price</h3>
                                        <p class="text-sm text-muted-foreground">
                                            Package + {{ formData.selectedAddons.length }} add-ons
                                        </p>
                                    </div>
                                    <div class="text-2xl font-bold">₱{{ totalPrice }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 5: Payment -->
                    <div v-if="currentStep === 5">
                        <div class="mb-6 flex items-center gap-2">
                            <CreditCard class="h-5 w-5 text-primary" />
                            <h2 class="text-xl font-semibold">Payment</h2>
                        </div>
                        <div class="space-y-6">
                            <!-- Payment Method -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-semibold">Select Payment Method</h3>
                                <div class="grid gap-4 md:grid-cols-2">
                                    <div v-for="method in ['Cash', 'Bank Transfer', 'GCash']" :key="method"
                                        class="relative flex cursor-pointer items-start justify-between rounded-lg border p-4 transition-all hover:border-primary"
                                        :class="{ 'border-primary bg-primary/5': formData.paymentMethod === method }"
                                        @click="formData.paymentMethod = method">
                                        <div class="flex items-center gap-2">
                                            <input type="radio" :id="'payment-' + method" :value="method"
                                                v-model="formData.paymentMethod" class="h-4 w-4 text-primary" />
                                            <label :for="'payment-' + method" class="font-medium cursor-pointer">
                                                {{ method }}
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Deposit Amount -->
                            <div class="space-y-4">
                                <h3 class="text-lg font-semibold">Deposit Amount</h3>
                                <div class="grid gap-4 md:grid-cols-2">
                                    <div>
                                        <Label for="depositAmount">Amount (₱)</Label>
                                        <Input id="depositAmount" v-model="formData.depositAmount" type="number"
                                            :class="{ 'border-red-500': errors.depositAmount }" />
                                        <p v-if="errors.depositAmount" class="text-sm text-red-500">{{
                                            errors.depositAmount }}</p>
                                    </div>
                                    <div>
                                        <p class="text-sm text-muted-foreground">Recommended deposit: 50% of total price
                                        </p>
                                        <p class="text-lg font-semibold">₱{{ totalPrice * 0.5 }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Total Price -->
                            <div class="rounded-lg bg-primary/5 p-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-lg font-semibold">Total Price</h3>
                                        <p class="text-sm text-muted-foreground">
                                            Package + {{ formData.selectedAddons.length }} add-ons
                                        </p>
                                    </div>
                                    <div class="text-2xl font-bold">₱{{ totalPrice }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="mt-8 flex justify-between">
                        <Button v-if="currentStep > 1" variant="outline" @click="prevStep">
                            Previous
                        </Button>
                        <div class="ml-auto">
                            <Button v-if="currentStep < totalSteps" @click="nextStep" :disabled="!canProceed">
                                {{ buttonText }}
                            </Button>
                            <Button v-if="currentStep === totalSteps" @click="submitReservation">
                                Submit Reservation
                            </Button>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>

<style scoped>
.container {
    max-width: 1200px;
    margin: 0 auto;
}
</style>
