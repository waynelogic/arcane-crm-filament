<script setup lang="ts">
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/Form/InputError.vue';
import InputLabel from '@/Components/Form/InputLabel.vue';
import PrimaryButton from '@/Components/Auth/PrimaryButton.vue';
import TextInput from '@/Components/Form/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthForm from "@/Layouts/GuestLayout/AuthForm.vue";
import FormLabel from "@/Components/Form/FormLabel.vue";
import Button from "@/Components/Action/Button.vue";

defineOptions({
    layout: GuestLayout,
})

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    inn: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => {
            form.reset('password', 'password_confirmation');
        },
    });
};
</script>

<template>
        <Head title="Register" />

        <AuthForm>
            <h2 class="text-center font-medium text-2xl mb-8">Регистрация</h2>
            <form @submit.prevent="submit" class="flex flex-col gap-4">
                <FormLabel label="ФИО" :message="form.errors.name">
                    <TextInput
                        id="name"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.name"
                        required
                        autofocus
                        autocomplete="name"
                    />
                </FormLabel>
                <FormLabel label="E-mail" :message="form.errors.email">
                    <TextInput
                        id="email"
                        type="email"
                        class="mt-1 block w-full"
                        v-model="form.email"
                        required
                        autocomplete="username"
                    />
                </FormLabel>
                <FormLabel label="ИНН" :message="form.errors.inn">
                    <TextInput
                        id="inn"
                        type="text"
                        class="mt-1 block w-full"
                        v-model="form.inn"
                        required
                        maxlength="12"
                        autocomplete="inn"
                    />
                </FormLabel>
                <FormLabel label="Пароль" :message="form.errors.password">
                    <TextInput
                        id="password"
                        type="password"
                        class="mt-1 block w-full"
                        v-model="form.password"
                        required
                        autocomplete="new-password"
                    />
                </FormLabel>

                <FormLabel label="Повторите пароль" :message="form.errors.password_confirmation">
                    <TextInput
                        id="password_confirmation"
                        type="password"
                        class="mt-1 block w-full"
                        v-model="form.password_confirmation"
                        required
                        autocomplete="new-password"
                    />
                </FormLabel>

                <div class="flex flex-col gap-4 items-center justify-end">
                    <Button color="primary"
                        rounded="full"
                        class="w-full"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        Регистрация
                    </Button>
                    <Link
                        :href="route('login')"
                        class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                        Уже зарегистрированы?
                    </Link>

                </div>
            </form>
        </AuthForm>
</template>
