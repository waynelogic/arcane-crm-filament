<script setup lang="js">
import AppLayout from "@/Layouts/AppLayout.vue";
import Kanban from "@/Pages/Project/Kanban.vue";

const props = defineProps({
    columns: {
        type: [Object, Array],
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
        <Kanban class="grow" :columns="columns" :items="tasks"/>
    </AppLayout>
</template>

<style scoped>

</style>
