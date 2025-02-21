<script setup lang="js">
const props = defineProps({
    day: {
        type: Object,
        required: true
    },
    events: {
        type: Array,
        required: true
    }
})
</script>

<template>
    <ol v-if="events.length > 0" class="mt-2 cursor-pointer">
        <li v-for="event in events" :key="event.id">
            <a :href="event.href" class="group flex">
                <p class="flex-auto truncate font-medium text-gray-900 group-hover:text-indigo-600">
                    {{ event.title }}
                </p>
                <time :datetime="event.datetime" class="ml-3 hidden flex-none text-gray-500 group-hover:text-indigo-600 xl:block">
                    {{ new Date(event.start).toLocaleTimeString() }}
                </time>
            </a>
        </li>
        <li v-if="day.events.length > 2" class="text-gray-500">+ {{ day.events.length - 2 }} more</li>
    </ol>
</template>

<style scoped>

</style>
