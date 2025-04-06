<script setup lang="ts">
import AppLayout from "@/Layouts/AppLayout.vue";
import Calendar from "@/Pages/Events/Calendar.vue";

const props = defineProps({
    events: Object
})
</script>

<template>
    <AppLayout title="События">
        <Calendar :events="events"/>
    </AppLayout>
</template>

<style scoped>

</style>
