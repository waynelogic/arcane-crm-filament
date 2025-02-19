<script setup lang="ts">
import FormLabel from "@/Components/Form/FormLabel.vue";
import {useForm} from "@inertiajs/vue3";
import TextInput from "@/Components/Form/TextInput.vue";
import Button from "@/Components/Action/Button.vue";
import {computed} from "vue";
import TextArea from "@/Components/Form/TextArea.vue";
import ToggleButtons from "@/Components/Form/ToggleButtons.vue";
import {PhPlusCircle} from "@phosphor-icons/vue";

const props = defineProps({
    deal: {
        type: Object,
        required: true
    },
    dealStatuses: {
        type: Array,
        required: true
    }
})

const isNew = computed(() => props.deal?.id ?? false)

const form = useForm({
    title: props.deal?.title ?? '',
    status: props.deal?.status ?? props.dealStatuses[0].id,
    description: props.deal?.description ?? '',
    total_price: props.deal?.total_price ?? 0
})
</script>

<template>
    <form action="" class="grid grid-cols-1 gap-4 p-4">
        <h3 class="text-xl font-bold">
            {{ deal.id ? 'Сделка #' + deal.id : 'Новая заявка' }}
        </h3>
        <ToggleButtons v-if="isNew" name="status" :options="dealStatuses" v-model="form.status"/>
        <FormLabel label="Название" :message="form.errors.title">
            <TextInput v-model="form.title" :disabled="isNew"/>
        </FormLabel>

        <FormLabel label="Описание" :message="form.errors.description">
            <TextArea v-model="form.description" :disabled="isNew"/>
        </FormLabel>

        <FormLabel v-if="isNew" label="Цена" :message="form.errors.total_price">
            <TextInput v-model="form.total_price" type="number" :disabled="isNew"/>
        </FormLabel>
        <FormLabel class="flex flex-col" label="Товары">
            <select class="rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                <option value="" disabled selected>Выберите товар</option>
            </select>
            <button class="mt-4 w-full border border-dashed rounded-md border-gray-400 p-2 flex items-center justify-center">
                <PhPlusCircle class="size-6"/>
            </button>
        </FormLabel>

        <div class="flex justify-start">
            <Button v-if="!isNew" :disabled="form.processing" color="primary" type="submit">
                Отправить
            </Button>
        </div>

    </form>
</template>

<style scoped>

</style>
