<script setup>
const props = defineProps({
    value: [String, Number, Date],
    badge: {
        type: Boolean,
        default: false
    }
})
</script>

<template>
    <span :class="[badge ? 'inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-blue-700/10 ring-inset' : 'text-gray-900 dark:text-gray-100']">{{ value }}</span>
</template>

<style scoped>

</style>
