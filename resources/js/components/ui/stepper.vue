<script setup lang="ts">
import { computed, useSlots } from 'vue';

interface Props {
    currentStep?: number;
}

const props = withDefaults(defineProps<Props>(), {
    currentStep: 1
});

const slots = useSlots();
const steps = computed(() => {
    return slots.default?.() || [];
});
</script>

<template>
    <div class="w-full">
        <div class="flex items-center justify-between">
            <slot />
        </div>
    </div>
</template>

<script lang="ts">
import { defineComponent, h } from 'vue';

export const Step = defineComponent({
    name: 'Step',
    setup(_, { slots }) {
        return () => h('div', { class: 'flex items-center' }, slots.default?.());
    }
});

export const StepIndicator = defineComponent({
    name: 'StepIndicator',
    setup(_, { slots }) {
        return () => h('div', { class: 'flex items-center' }, slots.default?.());
    }
});

export const StepNumber = defineComponent({
    name: 'StepNumber',
    setup(_, { slots }) {
        return () => h('div', { class: 'flex h-8 w-8 items-center justify-center rounded-full border-2 border-primary bg-primary text-primary-foreground' }, slots.default?.());
    }
});

export const StepStatus = defineComponent({
    name: 'StepStatus',
    setup() {
        return () => h('div', { class: 'sr-only' }, 'Current step');
    }
});

export const StepTitle = defineComponent({
    name: 'StepTitle',
    setup(_, { slots }) {
        return () => h('div', { class: 'text-sm font-medium' }, slots.default?.());
    }
});

export const StepDescription = defineComponent({
    name: 'StepDescription',
    setup(_, { slots }) {
        return () => h('div', { class: 'text-sm text-muted-foreground' }, slots.default?.());
    }
});

export const StepSeparator = defineComponent({
    name: 'StepSeparator',
    setup() {
        return () => h('div', { class: 'h-0.5 w-24 bg-muted' });
    }
});

export const StepContent = defineComponent({
    name: 'StepContent',
    setup(_, { slots }) {
        return () => h('div', { class: 'w-full' }, slots.default?.());
    }
});
</script>