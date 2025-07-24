<script setup>

import {Head, useForm} from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import {computed, onUnmounted, ref} from "vue";
import {useOrderStore} from "@/stores/order.js";


const props = defineProps({
    order: {
        type: Object
    },
    product: {
        type: Object
    },
    salePrice:{
        type: String
    }
})

const store = useOrderStore()
const err = ref('')
const minValue = store.min_value
const count = ref(minValue)
const form = useForm({
    quantity: '',
    product_id: '',
    order_id: '',
    total_sum: 0
})

const totalSum = computed(() => props.salePrice * count.value)

function addOrder(){
    ready.value = true
    try {
        if (count.value < minValue) {
            throw new Error('Должно быть больше минимального количества');
        }
        form.quantity = count.value
        form.product_id = props.product.id
        form.order_id = props.order.id
        form.total_sum = totalSum.value
        form.post(route('order.store'))
    } catch (error) {
        err.value = error.message; // Показывает ошибку пользователю
    }

}

const ready = ref(false)


onUnmounted(function (){
    store.$reset()
    if (ready.value === false){
        form.delete(route('order.product.delete', props.product.pivot.id))
    }
})

</script>

<template>
    <Head title="Order-Convertion" />
    <MainLayout></MainLayout>
    <div class="z-10 absolute w-full h-full bg-gradient-to-br from-green-950/95 via-green-900 to-green-950/90">
        <div class="z-20 max-w-4xl font-lobster mx-auto p-6 rounded-lg bg-green-950  shadow-md mt-36 text-white ">
            <div class="grid grid-cols-1 md:grid-cols-2   gap-8">
                <!-- Фото товара -->

                <!-- Описание и детали -->
                <div class="flex flex-col  justify-between">
                    <div class="">
                        <h1  class="text-2xl font-bold mb-2">Номер заказа: {{ order.id }}</h1>
                        <div  class="text-md font-bold mb-2">Название товара: {{ product.title }}</div>
                        <div class="flex  flex-col justify-between">
                            <div>
                                <label class="text-xl" for="count">Укажите точное количество товара: </label>
                                <input class="text-gray-800 font-medium rounded-full ring-2 ring-yellow-500" type="text" v-model="count" name="" id="count">
                                <div class="text-red-500 font-medium">{{err}}</div>
                                <div class="text-xl pb-2 pt-2 ">
                                    <span class="text-yellow-500">{{salePrice}}</span> руб. за шт.
                                </div>
                            </div>
                        </div>
                        <div class="py-2 text-white font-lobster">Итого: <div class="text-yellow-500">{{totalSum}}</div></div>
                        <!-- Цена за выбранное количество (можно динамически менять через JS/Vue) -->
                        <div class="flex flex-row justify-between">
                            <button @click.prevent="addOrder" class="w-full bg-green-900/60 hover:bg-green-900/70 text-white ring-2 ring-white font-semibold py-3 rounded transition">
                                Добавить в корзину
                            </button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
