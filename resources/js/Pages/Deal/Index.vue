<script setup lang="js">
import {ref} from "vue";
import {money} from "@/Utils/money";
import AppLayout from "@/Layouts/AppLayout.vue";
import TextColumn from "@/Components/Tables/TextColumn.vue";
import ResourceTable from "@/Components/Tables/ResourceTable.vue";
import DealForm from "@/Pages/Deal/DealForm.vue";
import Modal from "@/Components/Action/Modal.vue";
import Button from "@/Components/Action/Button.vue";

const props = defineProps({
    deals: Object,
    dealStatuses: Array
})

const currentItem = ref({});
const show = ref(false);
const open = (itemId = null) => {
    if (itemId) {
        currentItem.value = props.deals?.find((deal) => deal.id === itemId);
    } else {
        currentItem.value = {};
    }

    show.value = true
}
</script>

<template>
    <AppLayout title="Сделки">
        <Modal :show="show" @close="show = false">
            <DealForm :deal="currentItem" :dealStatuses="dealStatuses"/>
        </Modal>
        <template #actions>
            <Button color="primary" class="mb-4" @click="open()">
                Новая
            </Button>
        </template>
        <ResourceTable :items="deals" class="mb-4" @open="open">
            <TextColumn label="ID" value="id"/>
            <TextColumn label="Название" value="title"/>
            <TextColumn label="Сумма сделки" :value="(item) => money(item.total_price)"/>
            <TextColumn label="Статус" badge :value="(item) => dealStatuses.find((status) => status.id === item.status).title"/>
            <TextColumn label="Дата создания" :value="(item) => new Date(item.created_at).toLocaleDateString() "/>
        </ResourceTable>
    </AppLayout>
</template>

<style scoped>

</style>
