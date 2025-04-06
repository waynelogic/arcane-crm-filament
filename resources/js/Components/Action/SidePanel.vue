<script setup lang="ts">
import {computed, PropType} from 'vue'
const props = defineProps({
    show: {
        type: Boolean,
        default: false
    },
    position: {
        type: String as PropType<'left' | 'right'>,
        default: 'right'
    },
    background: {
        type: String as PropType<'white' | 'black' | 'transparent'>,
        default: 'white'
    }
})

const emit = defineEmits(['close'])

const bgClasses = computed(() => {
    return {
        'bg-white': props.background === 'white',
        'bg-black': props.background === 'black',
        'bg-transparent': props.background === 'transparent'
    }
})
</script>

<template>
    <Teleport to="body">
        <transition enter-active-class="ease-out duration-300" leave-active-class="ease-in duration-200" enter-from-class="opacity-0" enter-to-class="opacity-100" leave-from-class="opacity-100" leave-to-class="opacity-0">
            <div v-if="show" @click="emit('close')" class="bg-gray-800/50 inset-0 fixed z-40"></div>
        </transition>
        <transition enter-active-class="ease-out duration-300" leave-active-class="ease-in duration-200" :enter-from-class="position === 'left' ? '-translate-x-full' : 'translate-x-full'" enter-to-class="translate-x-0" leave-from-class="translate-x-0" :leave-to-class="position === 'left' ? '-translate-x-full' : 'translate-x-full'">
            <div v-if="show" :class="[position === 'left' ? 'left-0' : 'right-0', 'h-full fixed top-0 z-50', bgClasses]">
                <slot/>
            </div>
        </transition>
    </Teleport>
</template>

<style scoped>

</style>
