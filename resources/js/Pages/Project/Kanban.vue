<script setup lang="js">
import KanbanItem from "@/Pages/Project/KanbanItem.vue";

const props = defineProps({
    items: {
        type: [Array, Object],
        required: true
    },
    columns: {
        type: Array,
        required: true
    }
})
</script>

<template>
    <div class="flex overflow-x-scroll">
        <div
            v-for="column in columns"
            :key="column.name"
            class="flex-1 rounded-lg px-3 py-3 min-w-80 "
        >
            <p class="text-gray-700 font-semibold font-sans tracking-wide text-sm">{{column.title}}</p>
            <KanbanItem
                v-for="(task) in items.filter(item => item.status === column.id)"
                :key="task.id"
                :task="task"
                class="mt-3 cursor-move"
            ></KanbanItem>
        </div>
    </div>
</template>

<style scoped>

</style>
