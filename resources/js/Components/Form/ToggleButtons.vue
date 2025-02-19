<script setup lang="ts">
const props = defineProps({
    name: {
        type: String,
        required: true
    },
    modelValue: {
        type: [String, Boolean, Number]
    },
    options: {
        type: Array
    }
})

const emit = defineEmits(['update:modelValue'])
</script>

<template>
    <div class="flex gap-2">
        <template v-for="option in options">
            <label class="border border-gray-400 rounded-lg py-2 px-4 flex items-center gap-2 cursor-pointer has-[:checked]:bg-primary-600 has-[:checked]:text-white">
                <input
                    type="radio"
                    class="sr-only"
                    :name="name"
                    :checked="option.id === modelValue"
                    :value="option.value"
                    @change="$emit('update:modelValue', option.value)"
                >
                {{ option.title }}
            </label>
        </template>
    </div>
</template>

<style scoped>

</style>
