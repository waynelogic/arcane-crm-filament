<script setup lang="ts">
import {Link} from "@inertiajs/vue3";
import {PropType} from "vue";
import {tv} from "tailwind-variants";
const props = defineProps({
    href: {
        type: String,
        default: null
    },
    color: {
        type: String as PropType<'white' | 'black' | 'primary' | 'secondary' | 'danger' | 'success' | 'warning'>,
        default: 'black'
    },
    size: {
        type: String as PropType<'xs' | 'sm' | 'md' | 'lg' | 'xl'>,
        default: 'md'
    },
    rounded: {
        type: String as PropType<'none' | 'sm' | 'md' | 'lg' | 'xl' | '2xl' | '3xl' | 'full'>,
        default: 'md'
    },
    type: {
        type: String as PropType<'button' | 'submit' | 'reset'>,
        default: 'button'
    }
})


const btnClasses = tv({
    base: 'inline-flex items-center justify-center font-semibold border border-transparent rounded-md shadow-sm select-none focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-75 disabled:cursor-not-allowed disabled:scale-95 duration-150',
    variants: {
        color: {
            white: 'text-gray-700 bg-white hover:bg-gray-50 border-gray-300',
            black: 'text-white bg-neutral-900 hover:bg-neutral-600',
            primary: 'text-white bg-primary-600 hover:bg-primary-700',
            secondary: 'text-gray-700 bg-gray-100 hover:bg-gray-200',
            danger: 'text-white bg-red-600 hover:bg-red-700',
            success: 'text-white bg-green-600 hover:bg-green-700',
            warning: 'text-white bg-yellow-600 hover:bg-yellow-700',
        },
        size: {
            xs: 'text-xs px-2 py-1',
            sm: 'text-sm px-3 py-2',
            md: 'text-sm px-4 py-2',
            lg: 'text-md px-4 py-2',
            xl: 'text-lg px-6 py-3',
        },
        rounded: {
            none: 'rounded-none',
            sm: 'rounded-sm',
            md: 'rounded-md',
            lg: 'rounded-lg',
            xl: 'rounded-xl',
            '2xl': 'rounded-2xl',
            '3xl': 'rounded-3xl',
            full: 'rounded-full',
        },
    }
})
</script>

<template>
    <component :is="href ? Link : 'button'" :href="href" :type="type" :class="btnClasses({color, size, rounded})">
        <slot />
    </component>
</template>

<style scoped>

</style>
