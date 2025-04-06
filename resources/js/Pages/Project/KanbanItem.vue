<script setup lang="ts">
import {computed} from "vue";

const props = defineProps({
    task: {
        type: Object,
        default: () => ({})
    }
})
</script>

<template>
    <div class="bg-white shadow rounded px-3 pt-3 pb-5 border border-neutral-300">
        <div class="flex justify-between">
            <p class="text-gray-700 font-semibold font-sans tracking-wide text-sm">{{task.title}}</p>
        </div>
        <div class="flex mt-4 justify-between items-center">
            <span class="text-sm text-gray-600">{{ new Date(task.created_at).toLocaleDateString() }}</span>
<!--            <badge v-if="task.type" :color="badgeColor">{{task.type}}</badge>-->
        </div>
    </div>
</template>

<style scoped>

</style>
