<script setup>
import {Link, usePage} from "@inertiajs/vue3";
import {
    PhCalendar,
    PhCaretDown, PhCaretUp,
    PhChartPie,
    PhFileDoc,
    PhFolder,
    PhHouse,
    PhUsers,
    PhWrench
} from "@phosphor-icons/vue";
import Dropdown from "@/Components/Action/Dropdown.vue";
import Button from "@/Components/Action/Button.vue";
import DropdownLink from "@/Components/Action/DropdownLink.vue";

const props = defineProps({
    mobile: {
        type: Boolean,
        default: false
    }
})

const page = usePage();
class NavItem {
    constructor(title, link, icon) {
        this.title = title;
        this.link = link;
        this.icon = icon;
    }
    get isActive() {
        return page.url.startsWith(this.link);
    }
}

const navItems = [
    new NavItem('Главная', '/dashboard', PhHouse),
    new NavItem('Проекты', '/projects', PhFolder),
    new NavItem('Календарь', '/events', PhCalendar),
    new NavItem('Сделки', '/deals', PhFileDoc),
    new NavItem('Команда', '/users', PhUsers),
]

const outLinks = [
    { id: 1, name: 'AnyDesk', href: 'https://anydesk.com/ru', initial: 'A', color: '#ee1414' },
    { id: 2, name: 'Portal 1C', href: 'https://portal.1c.ru/', initial: 'P', color: '#fed210' },
]
</script>

<template>
    <div :class="[mobile && 'rounded-2xl bg-white  overflow-hidden', 'flex grow flex-col gap-y-5 overflow-y-auto h-full']">
        <div class="flex h-16 shrink-0 items-center border-b border-gray-200 px-6">
            <Dropdown class="w-full">
                <template #trigger>
                    <button class="flex items-center w-full" color="white">
                        <img class="size-8 rounded-full" :src="$page.props.auth.company.logo" alt="">
                        <span class="ml-2">{{ $page.props.auth.company.name }}</span>
                        <PhCaretDown class="ml-auto"/>
                    </button>
                </template>
                <template #content>
                    <DropdownLink :href="route('logout')" method="post" as="button">
                        Альбус
                    </DropdownLink>
                </template>
            </Dropdown>
        </div>
        <nav class="flex flex-1 flex-col px-6 pb-4">
            <ul role="list" class="flex flex-1 flex-col gap-y-7">
                <li>
                    <ul role="list" class="-mx-2 space-y-1 text-zinc-950">
                        <li v-for="item in navItems" :key="item.title" class="relative">
                            <span v-if="item.isActive" class="absolute -left-4 w-0.5 h-1/2 top-1/2 -translate-y-1/2 bg-current"></span>
                            <Link :href="item.link" :class="[item.isActive ? 'bg-zinc-100' : 'text-zinc-500 hover:opacity-100 hover:text-zinc-700', 'group flex items-center gap-x-3 rounded-lg p-2 text-sm font-semibold leading-6 duration-100']">
                                <component :weight="item.isActive ? 'fill' : 'duotone'" :is="item.icon" class="size-5 shrink-0" aria-hidden="true" />
                                {{ item.title }}
                            </Link>
                        </li>
                    </ul>
                </li>
                <li>
                    <div class="text-xs font-semibold leading-6 text-gray-400">Полезные ссылки</div>
                    <ul role="list" class="-mx-2 mt-2 space-y-1">
                        <li v-for="(link, index) in outLinks" :key="index">
                            <Link
                                :style="{ '--color': link.color }"
                                :href="link.href" class="text-gray-700 hover:bg-gray-50 hover:text-gray-950 group flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6">
                                <span class="border-gray-200 text-gray-400 group-hover:border-[--color] group-hover:text-[--color] flex h-6 w-6 shrink-0 items-center justify-center rounded-lg border bg-white text-[0.625rem] font-medium">
                                    {{ link.initial }}</span>
                                <span class="truncate">{{ link.name }}</span>
                            </Link>
                        </li>
                    </ul>
                </li>
                <li class="mt-auto">
                    <a href="#"
                       class="group -mx-2 flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6 text-gray-700 hover:bg-gray-50 hover:text-primary-600">
                        <PhWrench class="h-6 w-6 shrink-0 text-gray-400 group-hover:text-primary-600"
                                aria-hidden="true" />
                        Настройки
                    </a>
                </li>
            </ul>
        </nav>
        <div class="mt-auto border-t border-gray-200 px-6">
            <Dropdown>
                <template #trigger>
                    <button class="flex items-center py-4">
                        <img class="size-10 shrink-0 rounded-lg mr-3" :src="$page.props.auth.user.avatar" :alt="$page.props.auth.user.name">
                        <div class="text-start flex flex-col mr-3">
                            <span class="line-clamp-1 text-sm/5 font-medium text-zinc-950 dark:text-white">
                                {{ $page.props.auth.user.name }}
                            </span>
                            <span class="block line-clamp-1 text-xs/5 font-normal text-zinc-500 dark:text-zinc-400">
                                {{ $page.props.auth.user.email }}
                            </span>
                        </div>
                        <div class="ml-auto">
                            <PhCaretUp class="size-5 shrink-0 " />
                        </div>
                    </button>
                </template>
                <template #content>
                    <DropdownLink
                        :href="route('profile.edit')"
                    >
                        Профиль
                    </DropdownLink>
                    <DropdownLink
                        :href="route('logout')"
                        method="post"
                        as="button"
                    >
                        Выход
                    </DropdownLink>
                </template>
            </Dropdown>
        </div>
    </div>
</template>

<style scoped>

</style>
