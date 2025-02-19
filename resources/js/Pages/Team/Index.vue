<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import ResourceTable from "@/Components/Tables/ResourceTable.vue";
import TextColumn from "@/Components/Tables/TextColumn.vue";
import PersonForm from "@/Pages/Team/PersonForm.vue";
import SidePanel from "@/Components/Action/SidePanel.vue";
import {ref} from "vue";

const props = defineProps({
    people: Object
});

const currentItem = ref(null);
const open = (itemId = null) => {
    if (itemId) {
        currentItem.value = props.people.find((person) => person.id === itemId);
    }
}
</script>

<template>
    <AppLayout>
        <SidePanel :show="!!currentItem" @close="currentItem = null">
            <PersonForm :person="currentItem"/>
        </SidePanel>
        <ResourceTable :items="people" class="mb-4" @open="open">
            <TextColumn label="ID" value="id"/>
            <TextColumn label="Название" value="name"/>
            <TextColumn label="E-mail" value="email"/>
            <TextColumn label="Дата регистрации" :value="(item) => new Date(item.created_at).toLocaleDateString() "/>
        </ResourceTable>
    </AppLayout>
</template>

<style scoped>

</style>
