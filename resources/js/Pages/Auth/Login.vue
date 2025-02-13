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

    <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
        {{ status }}
    </div>

    <div class="container px-4 h-screen flex flex-col justify-center">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
            <div class="flex flex-col items-center justify-center gap-5">
                <img class="invert h-20 drop-shadow-lg" src="/images/arcane_fav.png" alt="">
                <h1 class="text-3xl md:text-6xl font-bold text-white drop-shadow-lg">Arcane CRM</h1>
            </div>
            <div>
                <div class="bg-white border border-gray-400  shadow-big rounded-2xl p-5 lg:p-7 lg:max-w-lg">
                    <h2 class="text-center font-medium text-2xl mb-8">Вход в приложение</h2>
                    <form @submit.prevent="submit" class="flex flex-col gap-4">
                        <FormLabel label="E-mail" :message="form.errors.email">
                            <TextInput
                                id="email"
                                type="email"
                                class="mt-1 block w-full"
                                v-model="form.email"
                                placeholder="Ваш E-mail"
                                required
                                autofocus
                                autocomplete="username"
                            />
                        </FormLabel>
                        <FormLabel label="Пароль" :message="form.errors.password">
                            <TextInput
                                id="password"
                                type="password"
                                class="mt-1 block w-full"
                                placeholder="Ваш пароль"
                                v-model="form.password"
                                required
                                autocomplete="current-password"
                            />
                        </FormLabel>
                        <div class="block">
                            <label class="flex items-center select-none">
                                <Checkbox name="remember" v-model:checked="form.remember" />
                                <span class="ms-2 text-sm text-gray-600">
                                    Не выходить из системы
                                </span>
                            </label>
                        </div>

                        <div class="flex flex-col gap-4 items-center justify-end">
                            <Button type="submit" color="primary" class="w-full" size="lg" :disabled="form.processing" rounded="full">
                                Войти
                            </Button>
                            <Link
                                v-if="canResetPassword"
                                :href="route('password.request')"
                                class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 select-none"
                            >
                                Забыли пароль?
                            </Link>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
