<script setup lang="ts">
const props = defineProps<{
    label?: string;
    message?: string;
}>();
</script>

<template>
    <label class="flex flex-col">
        <span v-if="label" class="block font-medium text-gray-700 mb-1">
            {{ label }}
        </span>
        <slot />
        <transition
            enter-active-class="transition ease-in-out"
            enter-from-class="opacity-0 translate-x-5"
            leave-active-class="transition ease-in-out"
            leave-to-class="opacity-0 translate-x-0"
        >
            <span v-if="message" class="text-sm text-red-600 mt-1">
                {{ message }}
            </span>
        </transition>
    </label>
</template>

<style scoped>

</style>
