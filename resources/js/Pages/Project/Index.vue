<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import Kanban from "@/Pages/Project/Kanban.vue";

const props = defineProps({
    columns: {
        type: Object,
        required: true
    },
    tasks: {
        type: Object,
        required: true
    }
})
</script>

<template>
    <AppLayout>
        <Kanban :columns="columns" :items="tasks"/>
    </AppLayout>
</template>

<style scoped>

</style>
