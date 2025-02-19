<script setup>
import {useForm} from "@inertiajs/vue3";
import TextInput from "@/Components/Form/TextInput.vue";
import FormLabel from "@/Components/Form/FormLabel.vue";
import Button from "@/Components/Action/Button.vue";

const props = defineProps({
    person: Object
})

const form = useForm({
    name: props.person.name ?? null,
    email: props.person.email ?? null,
})

const submit = () => {

}
</script>

<template>
    <div class="grid grid-cols-1 gap-4 p-6">
        <form @submit.prevent="submit"></form>
        <FormLabel label="Имя" :message="form.errors.name">
            <TextInput v-model="form.name"/>
        </FormLabel>
        <FormLabel label="E-mail" :message="form.errors.email">
            <TextInput v-model="form.email"/>
        </FormLabel>

        <div class="flex justify-start">
            <Button>
                Сохранить
            </Button>
        </div>
    </div>
</template>

<style scoped>

</style>
