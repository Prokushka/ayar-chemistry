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
    },
    priceTiers:{
        type: Object
    },
    event:{
        type: Object
    }
})

const selected = ref(0)

const order = useOrderStore()

const realPrice = computed(() => parseInt(props?.priceTiers[selected.value]?.price))
const form = useForm({
    id: props?.product?.id,
    total_price: null
})
const user = useUserStore()



function convertion(){
    if (user.isAuth === false){
       return  router.visit(route('login'))
    }else {
        //order.setOrder(procent.value, props.product.sale_price, realPrice.value, select.value)
        form.total_price = realPrice.value
        form.get(route('order.convertion'))
    }

}




</script>

<template >
    <Head title="Product-Show" />
    <MainLayout></MainLayout>
    <div class="z-10 py-36 ">
        <div class="z-20 max-w-4xl mx-auto p-6  rounded-lg  bg-green-950   shadow-md text-white ">
            <div class="grid grid-cols-1 md:grid-cols-2  gap-8">
                <!-- Фото товара -->
                <div class="py-12 lg:mt-0 relative overflow-hidden">

                    <div v-if="event.color" :style="`background-color: ${event.color};`" class="absolute bg-red-700 text-white transform rotate-45 h-6 w-[180px] z-30 text-center font-semibold font-rubick top-8 -right-10">
                        <p class="text-base">{{ event.title }}</p></div>
                    <img v-if="product && product.image_url" :src="`/storage/${product.image_url}`"
                         alt="Фото товара"
                         class="w-full h-96 object-contain rounded-lg " />
                </div>
                <!-- Описание и детали -->
                <div class="flex flex-col  justify-between">
                    <div class="">
                        <h1 v-if="product && product.title"  class="text-3xl text-center font-bold mb-2">{{ product.title }}</h1>
                        <p v-if="product && product.description" class=" my-6">
                           <span class="font-bold">Описание: </span>{{product.description}}
                        </p>
                        <!-- Dropdown с ценами -->
                        <div class="flex  flex-col h-full justify-between">
                                <div>
                                    <label for="select" class="block text-sm font-medium mb-1">Выберите количество:</label>
                                    <select  v-model="selected" id="quantity" class="w-11/12 max-w-full overflow-hidden truncate text-gray-700 font-semibold font-rubick outline-none border rounded text-gray-800 px-3 py-2 mb-4  focus:ring-2 border-yellow-600 border-4 ring-yellow-600 ">
                                        <option v-for="(price, i) in priceTiers" :value="i">от {{ price.from_quantity }} шт.</option>
                                    </select>
                                </div>





                        </div>
                    </div>
                    <div class="space-y-4">

                        <div class="flex flex-row justify-end w-full">

                            <div class="text-xl pb-2 pt-1 font-bold ">
                                {{realPrice}}₽ за шт.
                            </div>
                        </div><!--<div class="text-xl font-semibold text-white py-1 mb-4">В наличии: <span class="text-yellow-500">{{product?.quantity}} </span> шт.</div>-->

                        <button v-if="product.is_active" @click.prevent="convertion" class="w-full bg-green-900/60 hover:bg-green-900/70 text-white ring-2 ring-white font-semibold py-3 rounded transition">
                            Добавить в корзину
                        </button>
                        <div class="w-full py-3 font-rubick text-center text-2xl font-bold text-white" v-else>
                            Нет в наличии
                        </div>
                    </div>



                </div>
            </div>
        </div>
    </div>


</template>

<style scoped>

</style>
