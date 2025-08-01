<script setup>

import {Head, useForm} from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import {computed, onMounted, onUnmounted, ref} from "vue";
import {useOrderStore} from "@/stores/order.js";


const props = defineProps({

    product: {
        type: Object
    },
    salePrice:{
        type: Number,
        required: false
    }

})
const order = useOrderStore()

const err = ref('')
const minValue = order.min_value
const count = ref(minValue)
const form = useForm({
    quantity: '',
    product_id: '',
    total_sum: 0,
    sale_price: null
})

const totalSum = computed(() => props.salePrice * count.value)


onMounted(() => {
    console.log('salePrice:', props.salePrice)

    if (props.salePrice === undefined || props.salePrice === null) {
        window.history.back() // вернёт на предыдущую страницу
    }
})



const salePrice = props.salePrice

function addOrder(){
    ready.value = true
    try {
        if (count.value < minValue) {
            throw new Error('Должно быть больше минимального количества');
        }
        form.quantity = count.value
        form.product_id = props.product.id
        form.total_sum = totalSum.value
        form.sale_price = salePrice
        form.post(route('order.store'))
    } catch (error) {
        err.value = error.message; // Показывает ошибку пользователю
    }

}
function goBack() {
    window.history.back()
}
const ready = ref(false)

onUnmounted(() => order.$reset())
</script>

<template>
    <Head title="Order-Convertion" />
    <MainLayout></MainLayout>
    <div class="z-10 absolute w-full h-full bg-gradient-to-br from-green-950/95 via-green-900 to-green-950/90">
        <div class="z-20 max-w-4xl font-lobster mx-auto p-6 rounded-lg bg-green-950  shadow-md mt-36 text-white ">
            <div class="">
                <!-- Фото товара -->

                <!-- Описание и детали -->
                <div class="flex flex-col  ">
                    <div class="">
                        <div  class="text-md font-bold mb-2">{{ product.title }}</div>
                        <div class=" justify-center">
                            <div class="flex-row justify-center">
                                <div class="py-2 text-xl">Укажите точное количество товара: </div>
                                <input class="text-gray-800  font-bold w-2/5 py-2 rounded-full ring-2 ring-yellow-500" type="text" v-model="count" name="" id="count">
                                <div class="text-red-500 font-medium">{{err}}</div>
                                <div class="text-xl pb-2 pt-2 ">
                                    <span class="text-yellow-500">{{salePrice}}</span> руб. за шт.
                                </div>
                            </div>
                        </div>
                        <div class="py-2 text-white font-lobster">Итого: <span class="text-yellow-500">{{totalSum}}</span></div>
                        <!-- Цена за выбранное количество (можно динамически менять через JS/Vue) -->
                        <div class="flex flex-row font-comic justify-between">
                            <div>
                                <button @click.prevent="addOrder" class="w-full  bg-green-900/60 hover:bg-green-900/70 text-white ring-2 ring-white font-semibold px-2 py-3 rounded transition">
                                    Добавить в корзину
                                </button>
                            </div>
                            <div>
                                <button @click.prevent="goBack()" class="w-full bg-red-700  text-white ring-2 ring-white font-semibold px-2 py-3 rounded transition">
                                    Отмена
                                </button>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

</style>
