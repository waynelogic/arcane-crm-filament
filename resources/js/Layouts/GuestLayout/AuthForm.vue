<script setup lang="ts">

</script>

<template>
    <div class="container px-4 min-h-screen flex flex-col justify-center py-12">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
            <div class="flex flex-col items-center justify-center gap-5">
                <img class="invert h-20 drop-shadow-lg" src="/images/arcane_fav.png" alt="">
                <h1 class="text-3xl md:text-6xl font-bold text-white drop-shadow-lg">Arcane CRM</h1>
            </div>
            <div>
                <div class="bg-white border border-gray-400  shadow-big rounded-2xl p-5 lg:p-7 lg:max-w-lg">
                    <slot/>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
