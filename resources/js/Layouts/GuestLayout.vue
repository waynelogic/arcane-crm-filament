<script setup lang="ts">
import ApplicationLogo from '@/Components/Auth/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="relative min-h-screen isolate">
        <div class="">
            <img class="absolute size-full object-cover z-[-2]" src="/images/common/main-bg.jpg" alt="BG">
            <div class="absolute inset-0 bg-black/20 z-[-1]"></div>
        </div>
        <slot />
    </div>
<!--    <div-->
<!--        class="flex min-h-screen flex-col items-center bg-gray-100 pt-6 sm:justify-center sm:pt-0"-->
<!--    >-->
<!--        <div>-->
<!--            <Link href="/">-->
<!--                <ApplicationLogo class="h-20 w-20 fill-current text-gray-500" />-->
<!--            </Link>-->
<!--        </div>-->

<!--        <div-->
<!--            class="mt-6 w-full overflow-hidden bg-white px-6 py-4 shadow-md sm:max-w-md sm:rounded-lg"-->
<!--        >-->
<!--            <slot />-->
<!--        </div>-->
<!--    </div>-->
</template>
