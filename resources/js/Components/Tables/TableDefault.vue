<script setup>
import {ref, watch, computed, useSlots} from 'vue';

// Props
const props = defineProps({
    items: {
        type: [Array, Object],
        required: true
    },
    modelValue: {
        type: Array,
        required: true,
        default: () => []
    },
    itemKey: {
        type: String,
        required: true
    }
});

// Emits для v-model
const emit = defineEmits(['update:modelValue']);

// Локальная копия массива данных
const internalItems = ref([...props.modelValue]);

// Синхронизация с v-model
const items = computed({
    get() {
        return internalItems.value;
    },
    set(newValue) {
        internalItems.value = newValue;
        emit('update:modelValue', newValue); // Обновление родительского массива
    }
});

// При изменении внешнего массива обновляем локальную копию
watch(
    () => props.modelValue,
    (newVal) => {
        internalItems.value = newVal;
    },
    { immediate: true }
);

// Получение уникального ключа для элемента
const getKey = (item) => {
    return item[props.itemKey];
};

const slots = useSlots();
const internalColumns = computed(() => {
    if (slots.item) {
        // Вызываем функцию слота для получения его содержимого
        const itemsSlotContent = slots.item();

        // Выводим содержимое слота
        console.log('Содержимое слота items:', itemsSlotContent);
    } else {
        console.log('Слот items не определен');
    }
});
</script>

<template>
    {{ initialColumns }}
    <table class="min-w-full table-fixed divide-y divide-gray-300">
        <thead>
        <tr>
            <th v scope="col">Person</th>
            <th scope="col">Most interest in</th>
            <th scope="col">Age</th>
        </tr>
        </thead>
        <tbody class="divide-y divide-gray-200 bg-white">
            <tr>
                <th scope="row">Chris</th>
                <td>HTML tables</td>
                <td>22</td>
            </tr>
            <tr>
                <th scope="row">Dennis</th>
                <td>Web accessibility</td>
                <td>45</td>
            </tr>
            <tr>
                <th scope="row">Sarah</th>
                <td>JavaScript frameworks</td>
                <td>29</td>
            </tr>
            <tr>
                <th scope="row">Karen</th>
                <td>Web performance</td>
                <td>36</td>
            </tr>
            </tbody>
            <tfoot>
            <tr>
                <th scope="row" colspan="2">Average age</th>
                <td>33</td>
            </tr>
        </tfoot>
    </table>

    <div class="table-container">
        <!-- Если передан массив данных -->
        <div v-if="items.length > 0" class="table-row" v-for="(item, index) in items" :key="getKey(item)">
            <!-- Слот для кастомного отображения элемента -->
            <slot name="item" :element="item" :index="index"></slot>
        </div>

        <!-- Сообщение, если данных нет -->
        <div v-else class="no-data">Нет данных</div>
    </div>
</template>

<style scoped>

</style>
