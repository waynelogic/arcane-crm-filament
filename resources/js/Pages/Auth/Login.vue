<script setup lang="ts">
import Checkbox from '@/Components/Form/Checkbox.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/Form/InputError.vue';
import InputLabel from '@/Components/Form/InputLabel.vue';
import PrimaryButton from '@/Components/Auth/PrimaryButton.vue';
import TextInput from '@/Components/Form/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import FormLabel from "@/Components/Form/FormLabel.vue";
import Button from "@/Components/Action/Button.vue";
import AuthForm from "@/Layouts/GuestLayout/AuthForm.vue";

defineProps<{
    canResetPassword?: boolean;
    status?: string;
}>();

defineOptions({
    layout: GuestLayout,
})

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => {
            form.reset('password');
        },
    });
};
</script>

<template>
    <Head title="Вход" />
    
    <AuthForm>
        <h2 class="text-center font-medium text-2xl mb-8">Вход в приложение</h2>
        <form @submit.prevent="submit" class="flex flex-col gap-4">
            <FormLabel label="E-mail" :message="form.errors.email">
                <TextInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" placeholder="Ваш E-mail" required autofocus autocomplete="username"/>
            </FormLabel>
            <FormLabel label="Пароль" :message="form.errors.password">
                <TextInput id="password" type="password" class="mt-1 block w-full" placeholder="Ваш пароль" v-model="form.password" required autocomplete="current-password"/>
            </FormLabel>
            <div class="block">
                <label class="flex items-center select-none">
                    <Checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ms-2 text-sm text-gray-600">Не выходить из системы</span>
                </label>
            </div>

            <div class="flex flex-col gap-4 items-center justify-end">
                <Button type="submit" color="primary" class="w-full" size="lg" :disabled="form.processing" rounded="full">
                    Войти
                </Button>
                <Link v-if="canResetPassword" :href="route('password.request')" class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 select-none">Забыли пароль?</Link>
                <Link :href="route('register')" class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 select-none">Регистрация</Link>
            </div>
        </form>
    </AuthForm>
</template>
