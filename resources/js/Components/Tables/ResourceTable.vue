<script setup>
import {ref, computed, useSlots} from 'vue'
import {PhEye} from "@phosphor-icons/vue";

const props = defineProps({
    items: {
        type: [Array, Object],
        required: true
    }
})
const emit = defineEmits(['open']);

const slots = useSlots();
const internalColumns = computed(() => {
    return slots.default ? slots.default().map((slot) => {
        const { label, value, ...newProps } = slot.props || {}
        return {
            component: slot.type,
            label,
            value: typeof value === 'function' ? value : (item) => item[value],
            props: newProps
        }
    }) : []
})

const selectedItems = ref([])
const indeterminate = computed(() => selectedItems.value.length > 0 && selectedItems.value.length < props.items.length)
</script>

<template>
    <div class="rounded-xl overflow-hidden border flow-root">
        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <div class="relative">
                    <div v-if="selectedItems.length > 0"
                         class="absolute left-14 top-0 flex h-12 items-center space-x-3 bg-white sm:left-12">
                        <button type="button"
                                class="inline-flex items-center rounded bg-white px-2 py-1 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-30 disabled:hover:bg-white">Bulk
                            edit</button>
                        <button type="button"
                                class="inline-flex items-center rounded bg-white px-2 py-1 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-30 disabled:hover:bg-white">Delete
                            all</button>
                    </div>
                    <table class="min-w-full table-fixed divide-y divide-gray-300">
                        <thead>
                            <tr>
                                <th scope="col" class="relative px-7 sm:w-12 sm:px-6">
                                    <input type="checkbox" class="absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-600" :checked="indeterminate || selectedItems.length === items.length" :indeterminate="indeterminate" @change="selectedItems = $event.target.checked ? items.map((p) => p.id) : []" />
                                </th>
                                <th scope="col" v-for="(column, index) in internalColumns" :key="index"
                                    :class="[index === 0 ? 'py-3.5 pr-3' : 'px-3 py-3.5', 'text-left text-sm font-semibold text-gray-900']">
                                    {{ column.label }}
                                </th>
                                <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-3">
                                    <span class="sr-only">Edit</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white">
                            <tr v-for="(item, index) in items" :key="index">
                                <td class="relative px-7 sm:w-12 sm:px-6">
                                    <div v-if="selectedItems.includes(item.id)" class="absolute inset-y-0 left-0 w-0.5 bg-primary-600"></div>
                                    <input type="checkbox" class="absolute left-4 top-1/2 -mt-2 h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-600" :value="item.id" v-model="selectedItems" />
                                </td>
                                <td @click="$emit('open', item.id)" v-for="(column, index) in internalColumns" :key="index"
                                    :class="[
                                        index === 0 ? 'py-4 pr-3' : 'px-3 py-4',
                                        selectedItems.includes(item.id) ? 'text-primary-600' : 'text-gray-900',
                                         'text-sm text-gray-500 cursor-pointer']">
                                    <component
                                        :is="column.component"
                                        :value="column.value(item)"
                                        v-bind="column.props"
                                    />
                                </td>
                                <td class="whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-3">
                                    <a href="#" class="flex items-center gap-2 text-primary-600 hover:text-primary-900">
                                        <PhEye class="size-4"/>
                                        Просмотр
                                    </a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
