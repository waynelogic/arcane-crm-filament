<script setup>
import { ref } from 'vue'
import {Head} from "@inertiajs/vue3";
import {PhBell, PhList, PhMagnifyingGlass} from "@phosphor-icons/vue";
import Navigation from "@/Layouts/AppLayout/Navigation.vue";
import SidePanel from "@/Components/Action/SidePanel.vue";

const props = defineProps({
    title: {
        type: String,
        default: null
    }
})
const sidebarOpen = ref(false)
</script>

<template>
    <SidePanel :show="sidebarOpen" @close="sidebarOpen = false" position="left" :background="'transparent'">
        <div class="p-3 h-full">
            <Navigation mobile/>
        </div>
    </SidePanel>
    <div class="relative isolate flex min-h-svh w-full bg-white max-lg:flex-col lg:bg-zinc-100 dark:bg-zinc-900 dark:lg:bg-zinc-950">
        <div class="fixed inset-y-0 left-0 w-64 max-lg:hidden">
            <Navigation/>
        </div>
        <header class="flex items-center px-4 lg:hidden">
            <div class="sticky w-full top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-gray-200 bg-white px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8">
                <button type="button" class="-m-2.5 p-2.5 text-gray-700 lg:hidden" @click="sidebarOpen = true">
                    <span class="sr-only">Open sidebar</span>
                    <PhList class="h-6 w-6" aria-hidden="true" />
                </button>

                <!-- Separator -->
                <div class="h-6 w-px bg-gray-200 lg:hidden" aria-hidden="true" />

                <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6">
                    <form class="relative flex flex-1" action="#" method="GET">
                        <label for="search-field" class="sr-only">Поиск</label>
                        <PhMagnifyingGlass class="pointer-events-none absolute inset-y-0 left-0 h-full w-5 text-gray-400" aria-hidden="true" />
                        <input id="search-field" class="block h-full w-full border-0 py-0 pl-8 pr-0 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm" placeholder="Поиск..." type="search" name="search" />
                    </form>
                    <div class="flex items-center gap-x-4 lg:gap-x-6">
                        <button type="button" class="-m-2.5 p-2.5 text-gray-400 hover:text-gray-500">
                            <span class="sr-only">View notifications</span>
                            <PhBell class="h-6 w-6" aria-hidden="true" />
                        </button>

                        <!-- Separator -->
                        <div class="hidden lg:block lg:h-6 lg:w-px lg:bg-gray-200" aria-hidden="true" />
                    </div>
                </div>
            </div>
        </header>
        <!-- Page -->
        <main class="flex flex-1 flex-col pb-3 lg:min-w-0 lg:pt-3 lg:pr-3 lg:pl-64">
            <div class="grow p-6 lg:rounded-lg lg:bg-white lg:p-0 lg:ring-1 lg:shadow-xs lg:ring-zinc-950/5 dark:lg:bg-zinc-900 dark:lg:ring-white/10">
                <div class="mx-auto max-w-6xl px-6 h-full flex flex-col">
                    <div class="lg:flex lg:items-center lg:justify-between mb-4 pt-6 px-4">
                        <div class="min-w-0 flex-1">
                            <h2 v-if="title" class="text-2xl/7 font-bold text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                                {{ title }}
                            </h2>
                        </div>
                        <div class="mt-5 flex lg:mt-0 lg:ml-4">
                            <slot name="actions"/>
                        </div>
                    </div>
                    <slot/>
                </div>
            </div>
        </main>
    </div>


<!--    <div class="relative isolate flex min-h-svh w-full bg-white max-lg:flex-col lg:bg-zinc-100 dark:bg-zinc-900 dark:lg:bg-zinc-950">-->
<!--        <SidePanel :show="sidebarOpen" @close="sidebarOpen = false" position="left">-->
<!--            <Navigation/>-->
<!--        </SidePanel>-->
<!--        <div class="fixed inset-y-0 left-0 w-64 max-lg:hidden">-->
<!--            <Navigation bordered/>-->
<!--        </div>-->

<!--        <div class="flex flex-1 flex-col pb-2 lg:min-w-0 lg:pt-2 lg:pr-2 lg:pl-64">-->
<!--            <div class="grow lg:rounded-lg lg:bg-white lg:ring-1 lg:shadow-xs lg:ring-zinc-950/5 dark:lg:bg-zinc-900 dark:lg:ring-white/10">-->

<!--                <div class="sticky top-0 z-40 flex h-16 shrink-0 items-center gap-x-4 border-b border-gray-200 bg-white px-4 shadow-sm sm:gap-x-6 sm:px-6 lg:px-8">-->
<!--                    <button type="button" class="-m-2.5 p-2.5 text-gray-700 lg:hidden" @click="sidebarOpen = true">-->
<!--                        <span class="sr-only">Open sidebar</span>-->
<!--                        <PhList class="h-6 w-6" aria-hidden="true" />-->
<!--                    </button>-->

<!--                    &lt;!&ndash; Separator &ndash;&gt;-->
<!--                    <div class="h-6 w-px bg-gray-200 lg:hidden" aria-hidden="true" />-->

<!--                    <div class="flex flex-1 gap-x-4 self-stretch lg:gap-x-6">-->
<!--                        <form class="relative flex flex-1" action="#" method="GET">-->
<!--                            <label for="search-field" class="sr-only">Поиск</label>-->
<!--                            <PhMagnifyingGlass class="pointer-events-none absolute inset-y-0 left-0 h-full w-5 text-gray-400" aria-hidden="true" />-->
<!--                            <input id="search-field" class="block h-full w-full border-0 py-0 pl-8 pr-0 text-gray-900 placeholder:text-gray-400 focus:ring-0 sm:text-sm" placeholder="Поиск..." type="search" name="search" />-->
<!--                        </form>-->
<!--                        <div class="flex items-center gap-x-4 lg:gap-x-6">-->
<!--                            <button type="button" class="-m-2.5 p-2.5 text-gray-400 hover:text-gray-500">-->
<!--                                <span class="sr-only">View notifications</span>-->
<!--                                <PhBell class="h-6 w-6" aria-hidden="true" />-->
<!--                            </button>-->

<!--                            &lt;!&ndash; Separator &ndash;&gt;-->
<!--                            <div class="hidden lg:block lg:h-6 lg:w-px lg:bg-gray-200" aria-hidden="true" />-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->

<!--                <main class="relative size-full">-->
<!--                    <div class="relative">-->
<!--                        <div class="lg:flex lg:items-center lg:justify-between mb-4">-->
<!--                            <div class="min-w-0 flex-1">-->
<!--                                <h2 v-if="title" class="text-2xl/7 font-bold text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">-->
<!--                                    {{ title }}-->
<!--                                </h2>-->
<!--                            </div>-->
<!--                            <div class="mt-5 flex lg:mt-0 lg:ml-4">-->
<!--                                <slot name="actions"/>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                        <slot/>-->
<!--                    </div>-->
<!--                </main>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
</template>

<style scoped>

</style>
