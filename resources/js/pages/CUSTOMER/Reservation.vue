<script setup lang="ts">
import { ref } from 'vue';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { Button } from '@/components/ui/button';
import { Tabs, TabsContent, TabsList, TabsTrigger } from '@/components/ui/tabs';
import { Calendar, Package, Utensils, Plus, CreditCard } from 'lucide-vue-next';

// Step tracking
const currentStep = ref(1);
const totalSteps = 4;

// Navigation functions
const nextStep = () => {
    if (currentStep.value < totalSteps) {
        currentStep.value++;
    }
};

const prevStep = () => {
    if (currentStep.value > 1) {
        currentStep.value--;
    }
};

// Form data
const formData = ref({
    // Customer Details
    firstName: '',
    lastName: '',
    email: '',
    phone: '',
    eventDate: '',
    eventTime: '',
    venue: '',
    numberOfGuests: 0,

    // Package Selection
    selectedPackage: null,

    // Menu Selection
    selectedMenus: [],

    // Add-ons
    selectedAddons: [],

    // Payment
    paymentMethod: '',
    depositAmount: 0
});
</script>

<template>
    <div class="container mx-auto py-8">
        <!-- Header -->
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-bold">Make a Reservation</h1>
            <p class="mt-2 text-muted-foreground">Book your catering service in a few simple steps</p>
        </div>

        <!-- Progress Steps -->
        <div class="mb-8">
            <div class="flex justify-between">
                <div v-for="step in totalSteps" :key="step" class="flex items-center">
                    <div class="flex items-center">
                        <div :class="[
                            'flex h-8 w-8 items-center justify-center rounded-full',
                            currentStep >= step ? 'bg-primary text-primary-foreground' : 'bg-muted'
                        ]">
                            {{ step }}
                        </div>
                    </div>
                    <div v-if="step < totalSteps" class="h-0.5 w-24 bg-muted"></div>
                </div>
            </div>
        </div>

        <!-- Step Content -->
        <Card>
            <CardContent class="p-6">
                <!-- Step 1: Customer Details -->
                <div v-if="currentStep === 1">
                    <div class="mb-6 flex items-center gap-2">
                        <Calendar class="h-5 w-5 text-primary" />
                        <h2 class="text-xl font-semibold">Customer Details</h2>
                    </div>
                    <!-- Customer Details Form will go here -->
                    <div class="space-y-4">
                        <p class="text-muted-foreground">Customer details form coming soon...</p>
                    </div>
                </div>

                <!-- Step 2: Package Selection -->
                <div v-if="currentStep === 2">
                    <div class="mb-6 flex items-center gap-2">
                        <Package class="h-5 w-5 text-primary" />
                        <h2 class="text-xl font-semibold">Select Package</h2>
                    </div>
                    <!-- Package Selection will go here -->
                    <div class="space-y-4">
                        <p class="text-muted-foreground">Package selection coming soon...</p>
                    </div>
                </div>

                <!-- Step 3: Menu Selection -->
                <div v-if="currentStep === 3">
                    <div class="mb-6 flex items-center gap-2">
                        <Utensils class="h-5 w-5 text-primary" />
                        <h2 class="text-xl font-semibold">Select Menus</h2>
                    </div>
                    <!-- Menu Selection will go here -->
                    <div class="space-y-4">
                        <p class="text-muted-foreground">Menu selection coming soon...</p>
                    </div>
                </div>

                <!-- Step 4: Payment -->
                <div v-if="currentStep === 4">
                    <div class="mb-6 flex items-center gap-2">
                        <CreditCard class="h-5 w-5 text-primary" />
                        <h2 class="text-xl font-semibold">Payment</h2>
                    </div>
                    <!-- Payment Form will go here -->
                    <div class="space-y-4">
                        <p class="text-muted-foreground">Payment form coming soon...</p>
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="mt-8 flex justify-between">
                    <Button v-if="currentStep > 1" variant="outline" @click="prevStep">
                        Previous
                    </Button>
                    <Button v-if="currentStep < totalSteps" @click="nextStep">
                        Next
                    </Button>
                    <Button v-if="currentStep === totalSteps" @click="nextStep">
                        Submit Reservation
                    </Button>
                </div>
            </CardContent>
        </Card>
    </div>
</template>

<style scoped>
.container {
    max-width: 1200px;
    margin: 0 auto;
}
</style>