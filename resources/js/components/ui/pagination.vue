<script setup lang="ts">
import { computed } from 'vue';
import { Button } from '@/components/ui/button';
import { ChevronLeft, ChevronRight } from 'lucide-vue-next';

const props = defineProps<{
    modelValue: number;
    totalPages: number;
}>();

const emit = defineEmits<{
    (e: 'update:modelValue', value: number): void;
}>();

const currentPage = computed({
    get: () => props.modelValue,
    set: (value) => emit('update:modelValue', value)
});

const pages = computed(() => {
    const pages = [];
    const maxVisiblePages = 5;
    let start = Math.max(1, currentPage.value - Math.floor(maxVisiblePages / 2));
    let end = Math.min(props.totalPages, start + maxVisiblePages - 1);

    if (end - start + 1 < maxVisiblePages) {
        start = Math.max(1, end - maxVisiblePages + 1);
    }

    for (let i = start; i <= end; i++) {
        pages.push(i);
    }

    return pages;
});
</script>

<template>
    <div class="flex items-center gap-2">
        <Button variant="outline" size="icon" :disabled="currentPage === 1" @click="currentPage = currentPage - 1">
            <ChevronLeft class="h-4 w-4" />
        </Button>
        <div class="flex items-center gap-1">
            <Button v-for="page in pages" :key="page" variant="outline" size="sm"
                :class="{ 'bg-primary text-primary-foreground': page === currentPage }" @click="currentPage = page">
                {{ page }}
            </Button>
        </div>
        <Button variant="outline" size="icon" :disabled="currentPage === totalPages"
            @click="currentPage = currentPage + 1">
            <ChevronRight class="h-4 w-4" />
        </Button>
    </div>
</template>