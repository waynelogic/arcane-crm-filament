<script setup lang="js">
import {PhCaretLeft, PhCaretRight} from "@phosphor-icons/vue";
import {computed, ref} from "vue";
import Day from "@/Pages/Events/Day.vue";

const props = defineProps({
    events: {
        type: Object,
        required: true
    }
})

const currentDate = new Date();
const currentYear = ref(currentDate.getFullYear());
const currentMonth = ref(currentDate.getMonth());
const currentMonthObject = computed(() => new Date(currentYear.value, currentMonth.value));

const arDays = computed(() => {
    const firstDay = new Date(currentYear.value, currentMonth.value, 1);
    const lastDay = new Date(currentYear.value, currentMonth.value + 1, 0);
    const startDay = firstDay.getDay() - 1;
    const totalDays = lastDay.getDate() - 1;

    // Создаем массив дней
    const calendarDays = [];

    // Добавляем последние дни предыдущего месяца
    if (startDay > 0) {
        const prevMonthLastDay = new Date(currentYear.value, currentMonth.value, 0);
        const prevMonthTotalDays = prevMonthLastDay.getDate();
        for (let i = prevMonthTotalDays - startDay + 1; i <= prevMonthTotalDays; i++) {
            calendarDays.push({
                number: i,
                isCurrentMonth: false,
                events: [],
            });
        }
    }

    // Добавляем дни текущего месяца
    for (let i = 1; i <= totalDays; i++) {
        calendarDays.push({
            number: i,
            isCurrentMonth: true,
            events: [],
        });
    }

    // Добавляем первые дни следующего месяца
    const remainingDays = 7 - (calendarDays.length % 7);
    if (remainingDays < 7) {
        for (let i = 1; i <= remainingDays; i++) {
            calendarDays.push({
                number: i,
                isCurrentMonth: false,
                events: [],
            });
        }
    }

    return calendarDays;
});

const prevMonth = () => {
    if (currentMonth.value === 0) {
        currentMonth.value = 11;
        currentYear.value--;
    } else {
        currentMonth.value--;
    }
};

const nextMonth = () => {
    if (currentMonth.value === 11) {
        currentMonth.value = 0;
        currentYear.value++;
    } else {
        currentMonth.value++;
    }
};

const days = [
    { date: '2021-12-27', events: [] },
    { date: '2021-12-28', events: [] },
    { date: '2021-12-29', events: [] },
    { date: '2021-12-30', events: [] },
    { date: '2021-12-31', events: [] },
    { date: '2022-01-01', isCurrentMonth: true, events: [] },
    { date: '2022-01-02', isCurrentMonth: true, events: [] },
    {
        date: '2022-01-03',
        isCurrentMonth: true,
        events: [
            { id: 1, name: 'Design review', time: '10AM', datetime: '2022-01-03T10:00', href: '#' },
            { id: 2, name: 'Sales meeting', time: '2PM', datetime: '2022-01-03T14:00', href: '#' },
        ],
    },
    { date: '2022-01-04', isCurrentMonth: true, events: [] },
    { date: '2022-01-05', isCurrentMonth: true, events: [] },
    { date: '2022-01-06', isCurrentMonth: true, events: [] },
    {
        date: '2022-01-07',
        isCurrentMonth: true,
        events: [{ id: 3, name: 'Date night', time: '6PM', datetime: '2022-01-08T18:00', href: '#' }],
    },
    { date: '2022-01-08', isCurrentMonth: true, events: [] },
    { date: '2022-01-09', isCurrentMonth: true, events: [] },
    { date: '2022-01-10', isCurrentMonth: true, events: [] },
    { date: '2022-01-11', isCurrentMonth: true, events: [] },
    {
        date: '2022-01-12',
        isCurrentMonth: true,
        isToday: true,
        events: [{ id: 6, name: "Sam's birthday party", time: '2PM', datetime: '2022-01-25T14:00', href: '#' }],
    },
    { date: '2022-01-13', isCurrentMonth: true, events: [] },
    { date: '2022-01-14', isCurrentMonth: true, events: [] },
    { date: '2022-01-15', isCurrentMonth: true, events: [] },
    { date: '2022-01-16', isCurrentMonth: true, events: [] },
    { date: '2022-01-17', isCurrentMonth: true, events: [] },
    { date: '2022-01-18', isCurrentMonth: true, events: [] },
    { date: '2022-01-19', isCurrentMonth: true, events: [] },
    { date: '2022-01-20', isCurrentMonth: true, events: [] },
    { date: '2022-01-21', isCurrentMonth: true, events: [] },
    {
        date: '2022-01-22',
        isCurrentMonth: true,
        isSelected: true,
        events: [
            { id: 4, name: 'Maple syrup museum', time: '3PM', datetime: '2022-01-22T15:00', href: '#' },
            { id: 5, name: 'Hockey game', time: '7PM', datetime: '2022-01-22T19:00', href: '#' },
        ],
    },
    { date: '2022-01-23', isCurrentMonth: true, events: [] },
    { date: '2022-01-24', isCurrentMonth: true, events: [] },
    { date: '2022-01-25', isCurrentMonth: true, events: [] },
    { date: '2022-01-26', isCurrentMonth: true, events: [] },
    { date: '2022-01-27', isCurrentMonth: true, events: [] },
    { date: '2022-01-28', isCurrentMonth: true, events: [] },
    { date: '2022-01-29', isCurrentMonth: true, events: [] },
    { date: '2022-01-30', isCurrentMonth: true, events: [] },
    { date: '2022-01-31', isCurrentMonth: true, events: [] },
    { date: '2022-02-01', events: [] },
    { date: '2022-02-02', events: [] },
    { date: '2022-02-03', events: [] },
    {
        date: '2022-02-04',
        events: [{ id: 7, name: 'Cinema with friends', time: '9PM', datetime: '2022-02-04T21:00', href: '#' }],
    },
    { date: '2022-02-05', events: [] },
    { date: '2022-02-06', events: [] },
]
const selectedDay = days.find((day) => day.isSelected)
</script>

<template>
    {{ events}}
    <div class="lg:flex lg:h-full lg:flex-col">
        <header class="flex items-center justify-between border-b border-gray-200 px-6 py-4 lg:flex-none">
            <h1 class="text-base font-semibold leading-6 text-gray-900">
                <time :datetime="currentYear + '-' + ('0' + (currentMonth + 1)).slice(-2)">
                    {{ currentMonth + 1 }}. {{ currentYear }}
                </time>
            </h1>
            <div class="flex items-center">
                <div class="relative flex items-center rounded-md bg-white shadow-sm md:items-stretch">
                    <button @click="prevMonth" type="button" class="flex h-9 w-12 items-center justify-center rounded-l-md border-y border-l border-gray-300 pr-1 text-gray-400 hover:text-gray-500 focus:relative md:w-9 md:pr-0 md:hover:bg-gray-50">
                        <span class="sr-only">Previous month</span>
                        <PhCaretLeft class="h-5 w-5" aria-hidden="true" />
                    </button>
                    <button type="button" class="hidden border-y border-gray-300 px-3.5 text-sm font-semibold text-gray-900 hover:bg-gray-50 focus:relative md:block">Today</button>
                    <span class="relative -mx-px h-5 w-px bg-gray-300 md:hidden" />
                    <button @click="nextMonth" type="button" class="flex h-9 w-12 items-center justify-center rounded-r-md border-y border-r border-gray-300 pl-1 text-gray-400 hover:text-gray-500 focus:relative md:w-9 md:pl-0 md:hover:bg-gray-50">
                        <span class="sr-only">Next month</span>
                        <PhCaretRight class="h-5 w-5" aria-hidden="true" />
                    </button>
                </div>
                <div class="hidden md:ml-4 md:flex md:items-center">
                    <div class="ml-6 h-6 w-px bg-gray-300" />
                    <button type="button" class="ml-6 rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-500">
                        Add event
                    </button>
                </div>
            </div>
        </header>
        <div class="shadow ring-1 ring-black ring-opacity-5 lg:flex lg:flex-auto lg:flex-col">
            <div
                class="grid grid-cols-7 gap-px border-b border-gray-300 bg-gray-200 text-center text-xs font-semibold leading-6 text-gray-700 lg:flex-none">
                <div class="bg-white py-2">M<span class="sr-only sm:not-sr-only">on</span></div>
                <div class="bg-white py-2">T<span class="sr-only sm:not-sr-only">ue</span></div>
                <div class="bg-white py-2">W<span class="sr-only sm:not-sr-only">ed</span></div>
                <div class="bg-white py-2">T<span class="sr-only sm:not-sr-only">hu</span></div>
                <div class="bg-white py-2">F<span class="sr-only sm:not-sr-only">ri</span></div>
                <div class="bg-white py-2">S<span class="sr-only sm:not-sr-only">at</span></div>
                <div class="bg-white py-2">S<span class="sr-only sm:not-sr-only">un</span></div>
            </div>
            <div class="flex bg-gray-200 text-xs leading-6 text-gray-700 lg:flex-auto">
                <div class="hidden w-full lg:grid lg:grid-cols-7 lg:grid-rows-6 lg:gap-px">
                    <div v-for="day in arDays" :key="day.date"
                         :class="[day.isCurrentMonth ? 'bg-white' : 'bg-gray-50 text-gray-500', 'relative px-3 py-2']">
                        <time :datetime="day.date" :class="day.number === currentDate.getDate() ? 'flex h-6 w-6 items-center justify-center rounded-full bg-primary-600 font-semibold text-white' : undefined">
                            {{ day.number }}
                        </time>
                        <ol v-if="day.events.length > 0" class="mt-2">
                            <Day :events="day.events" :day="day"/>
                            <li v-for="event in day.events.slice(0, 2)" :key="event.id">
                                <a :href="event.href" class="group flex">
                                    <p class="flex-auto truncate font-medium text-gray-900 group-hover:text-indigo-600">
                                        {{ event.name }}
                                    </p>
                                    <time :datetime="event.datetime" class="ml-3 hidden flex-none text-gray-500 group-hover:text-indigo-600 xl:block">
                                        {{ event.time }}
                                    </time>
                                </a>
                            </li>
                            <li v-if="day.events.length > 2" class="text-gray-500">+ {{ day.events.length - 2 }} more</li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="flex bg-gray-200 text-xs leading-6 text-gray-700 lg:flex-auto">
                <div class="hidden w-full lg:grid lg:grid-cols-7 lg:grid-rows-6 lg:gap-px">
                    <div v-for="day in days" :key="day.date"
                         :class="[day.isCurrentMonth ? 'bg-white' : 'bg-gray-50 text-gray-500', 'relative px-3 py-2']">
                        <time :datetime="day.date"
                              :class="day.isToday ? 'flex h-6 w-6 items-center justify-center rounded-full bg-indigo-600 font-semibold text-white' : undefined">{{
                                day.date.split('-').pop().replace(/^0/, '') }}</time>
                        <ol v-if="day.events.length > 0" class="mt-2">
                            <li v-for="event in day.events.slice(0, 2)" :key="event.id">
                                <a :href="event.href" class="group flex">
                                    <p class="flex-auto truncate font-medium text-gray-900 group-hover:text-indigo-600">
                                        {{ event.name }}
                                    </p>
                                    <time :datetime="event.datetime"
                                          class="ml-3 hidden flex-none text-gray-500 group-hover:text-indigo-600 xl:block">{{
                                            event.time }}</time>
                                </a>
                            </li>
                            <li v-if="day.events.length > 2" class="text-gray-500">+ {{ day.events.length - 2 }} more
                            </li>
                        </ol>
                    </div>
                </div>
                <div class="isolate grid w-full grid-cols-7 grid-rows-6 gap-px lg:hidden">
                    <button v-for="day in days" :key="day.date" type="button"
                            :class="[day.isCurrentMonth ? 'bg-white' : 'bg-gray-50', (day.isSelected || day.isToday) && 'font-semibold', day.isSelected && 'text-white', !day.isSelected && day.isToday && 'text-indigo-600', !day.isSelected && day.isCurrentMonth && !day.isToday && 'text-gray-900', !day.isSelected && !day.isCurrentMonth && !day.isToday && 'text-gray-500', 'flex h-14 flex-col px-3 py-2 hover:bg-gray-100 focus:z-10']">
                        <time :datetime="day.date"
                              :class="[day.isSelected && 'flex h-6 w-6 items-center justify-center rounded-full', day.isSelected && day.isToday && 'bg-indigo-600', day.isSelected && !day.isToday && 'bg-gray-900', 'ml-auto']">{{
                                day.date.split('-').pop().replace(/^0/, '') }}</time>
                        <span class="sr-only">{{ day.events.length }} events</span>
                        <span v-if="day.events.length > 0" class="-mx-0.5 mt-auto flex flex-wrap-reverse">
							<span v-for="event in day.events" :key="event.id"
                                  class="mx-0.5 mb-1 h-1.5 w-1.5 rounded-full bg-gray-400" />
						</span>
                    </button>
                </div>
            </div>
        </div>
<!--        <div v-if="selectedDay?.events.length > 0" class="px-4 py-10 sm:px-6 lg:hidden">-->
<!--            <ol-->
<!--                class="divide-y divide-gray-100 overflow-hidden rounded-lg bg-white text-sm shadow ring-1 ring-black ring-opacity-5">-->
<!--                <li v-for="event in selectedDay.events" :key="event.id"-->
<!--                    class="group flex p-4 pr-6 focus-within:bg-gray-50 hover:bg-gray-50">-->
<!--                    <div class="flex-auto">-->
<!--                        <p class="font-semibold text-gray-900">{{ event.name }}</p>-->
<!--                        <time :datetime="event.datetime" class="mt-2 flex items-center text-gray-700">-->
<!--                            <ClockIcon class="mr-2 h-5 w-5 text-gray-400" aria-hidden="true" />-->
<!--                            {{ event.time }}-->
<!--                        </time>-->
<!--                    </div>-->
<!--                    <a :href="event.href"-->
<!--                       class="ml-6 flex-none self-center rounded-md bg-white px-3 py-2 font-semibold text-gray-900 opacity-0 shadow-sm ring-1 ring-inset ring-gray-300 hover:ring-gray-400 focus:opacity-100 group-hover:opacity-100">Edit<span-->
<!--                        class="sr-only">, {{ event.name }}</span></a>-->
<!--                </li>-->
<!--            </ol>-->
<!--        </div>-->
    </div>
</template>

<style scoped>

</style>
