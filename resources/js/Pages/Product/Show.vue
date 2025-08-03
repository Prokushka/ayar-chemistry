<script setup>

import {Head, useForm} from "@inertiajs/vue3";
import {computed, onMounted, ref, watch} from "vue";
import MainLayout from "@/Layouts/MainLayout.vue";
import {router} from "@inertiajs/vue3";
import {useOrderStore} from "@/stores/order.js";
import {useUserStore} from "@/stores/user.js";

const props = defineProps({
    product:{
        type: Object
    }
})

const quantity = ref(1)

const order = useOrderStore()

watch(quantity, async (newQuantity) => {
    switch (Number(newQuantity)){
        case 250:
            procent.value = 0.93;
            break;
        case 1:
            procent.value = 1;
            break;
        case 10:
            procent.value = 0.98
            break;
        case 25:
            procent.value = 0.96;
            break;
        case 100:
            procent.value = 0.95;
            break;

    }
})
const procent = ref(1)
const totalPrice = computed(() => parseInt(procent.value * props?.product?.sale_price ))

const form = useForm({
    id: props?.product?.id,
    total_price: null
})
const user = useUserStore()

function convertion(){
    if (user.isAuth === false){
       return  router.visit(route('login'))
    }else {
        order.setOrder(procent.value, props.product.sale_price, totalPrice.value, quantity.value)
        form.total_price = totalPrice.value
        form.post(route('order.convertion'))
    }

}




</script>

<template >
    <Head title="Product-Show" />
    <MainLayout></MainLayout>
    <div class="z-10 absolute w-full h-full bg-gradient-to-br from-green-950/95 via-green-900 to-green-950/90">
        <div class="z-20 max-w-4xl mx-auto xs:p-6 rounded-lg  bg-green-950   shadow-md text-white ">
            <div class="grid grid-cols-1 md:grid-cols-2  gap-8">
                <!-- Фото товара -->
                <div class="py-12 mt-36">
                    <img v-if="product && product.image_url" :src="`/storage/${product.image_url}`"
                         alt="Фото товара"
                         class="w-full xs:h-96 object-cover rounded-lg " />
                </div>
                <!-- Описание и детали -->
                <div class="flex flex-col  justify-between">
                    <div class="">
                        <h1 v-if="product && product.title"  class="text-2xl font-bold mb-2">{{ product.title }}</h1>
                        <div v-if="product && product.sku" class="text-white">Артикул: <span class="font-mono">{{ product.sku }}</span></div>
                        <p v-if="product && product.description" class=" my-6">
                            {{product.description}}
                        </p>
                        <!-- Dropdown с ценами -->
                        <div class="flex  flex-col justify-between">
                            <div>
                                <label for="quantity" class="block text-sm font-medium mb-1">Выберите количество:</label>
                                <select v-model="quantity" id="quantity" class="w-11/12 border rounded text-gray-800 px-3 py-2 mb-4  focus:ring-2 border-yellow-600 border-4 ring-yellow-600 ">
                                    <option class="" value="1">от 1</option>
                                    <option value="10">от 10</option>
                                    <option value="25">от 25</option>
                                    <option value="100">от 100</option>
                                    <option value="250">от 250</option>
                                </select>
                                <div class="text-xl pb-2 pt-1 font-bold ">
                                    {{totalPrice}}₽ за шт.
                                </div>
                            </div>


                        </div>
                    </div>
                    <div>
                        <div class="text-xl font-semibold text-white py-1 mb-4">В наличии: <span class="text-yellow-500">{{product?.quantity}} </span> шт.</div>

                        <button @click.prevent="convertion" class="w-full bg-green-900/60 hover:bg-green-900/70 text-white ring-2 ring-white font-semibold py-3 rounded transition">
                            Добавить в корзину
                        </button>
                    </div>



                </div>
            </div>
        </div>
    </div>


</template>

<style scoped>

</style>
