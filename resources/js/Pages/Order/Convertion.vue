<script setup>

import {Head, useForm} from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import {computed, onMounted, onUnmounted, ref} from "vue";
import {useOrderStore} from "@/stores/order.js";


const props = defineProps({

    product: {
        type: Object
    },


})
const order = useOrderStore()

const err = ref('')


const form = useForm({
    quantity: '',
    product_id: '',
    total_sum: 0,
    sale_price: null
})
const quantity = ref(props.product.min_quantity)
const totalSum = computed(() => props.product.salePrice * quantity.value)








function addOrder(){
    ready.value = true
    try {
        if (quantity.value < props.product.min_quantity) {
            throw new Error('Должно быть больше минимального количества');
        }
        form.quantity = quantity.value
        form.product_id = props.product.id
        form.total_sum = totalSum.value
        form.sale_price = props.product.salePrice
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
                        <div  class="text-5xl mb-16 text-center font-bold mb-2">{{ product.title }}</div>
                        <div class=" justify-center">
                            <div class="flex-col flex justify-center">
                                <div class="py-2 text-xl">Укажите точное количество товара: </div>
                                <input class="text-gray-800  font-bold w-2/5 py-2 rounded-full ring-2 ring-yellow-500" type="text" v-model="quantity" name="" id="count">
                                <div class="text-red-500 font-medium py-1">{{err}}</div>
                                <div class="text-xl pb-2 pt-2 ">
                                    <span class="text-yellow-400">{{product.salePrice}}</span> ₽ за шт.
                                </div>
                            </div>
                        </div>
                        <div class="py-2 text-white font-lobster">Итого: <span class="text-yellow-400">{{totalSum}}</span></div>

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
