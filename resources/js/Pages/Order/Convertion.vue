<script setup>

import {Head, Link, useForm} from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import {computed, onMounted, onUnmounted, ref, watch} from "vue";
import {useOrderStore} from "@/stores/order.js";


const props = defineProps({

    products: {
        type: Object
    },
    salePrice: {
        type: Object
    },
    min_quantity: {
        type: Object
    },

})
const order = useOrderStore()

function createObject() {
    let obj = {}
    for (let product of props.products) {
        obj[product.id] = 0
    }
    return obj
}

const allQuantity = ref(
    createObject()
)

function plus(i){
    allQuantity.value[i] += 1
}
function minus(i){
    if (allQuantity.value[i] !== 0){
        allQuantity.value[i] -= 1
    }

}
onMounted(() => console.log(allQuantity.value))

const err = ref('')
const summary = ref(0)
watch(
    () => allQuantity.value,
    () => {
        const sum = Object.values(allQuantity.value).reduce((acc, item) => acc + item, 0);
        summary.value = sum
    },
    {
       deep: true
    }

);
const remained = computed(() => props.min_quantity[0].from_quantity - summary.value > 0 ? props.min_quantity[0].from_quantity - summary.value : 0)

onMounted(() => console.log(props.min_quantity))
const form = useForm({
    quantity: '',
    position: [],
    total_sum: 0,
    sale_price: null
})
const quantity = ref(props.min_quantity[0].from_quantity)
const totalSum = computed(() => props.salePrice * summary.value)


function addOrder(){
    ready.value = true
    try {
        if (remained.value !== 0) {
            throw new Error('Вы не набрали необходимое количество товара.');
        }
        form.quantity = summary.value
        form.position = allQuantity.value
        form.total_sum = totalSum.value
        form.sale_price = props.salePrice
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
    <div class="w-full flex justify-center pt-16 lg:pt-36">
        <div class=" flex flex-col md:w-11/12 lg:w-3/4 bg-green-950  justify-between space-y-12">
            <div class="grid  text-white md:grid-cols-3 lg:grid-cols-4 xs:grid-cols-2">
                <div v-for="(product, i) in products" :key="product.id" :class="{'col-span-2 md:col-span-3 lg:col-span-4 h-[28rem] ': i === 0}" class="flex flex-col justify-between w-full justify-items-center h-64 font-rubick font-semibold border border-green-900 space-y-4">
                    <img
                        :src="`/storage/${product?.image_url}`"
                        :alt="product?.image_url"
                        :class="{'w-64 h-64 ': i === 0}" class="h-32 h-32 place-self-center object-contain mb-2 rounded"
                    />
                    <Link :href="route('product.show', product.slug)">
                        <p :class="{'text-2xl': i === 0, 'text-xs': i !== 0}" class=" text-center font-rubick font-semibold line-clamp-2 text-balance">{{product.title}}</p>

                    </Link>
                        <div class="flex flex-row w-full px-2 pb-4 space-x-2 md:space-x-4 justify-center">
                            <div @click.prevent="plus(product.id)" class="py-0.5  px-1 text-xl rounded-lg border-white border text-center bg-green-900/70">
                                <i class="ri-arrow-up-wide-line"></i>
                            </div>
                            <div  >
                                <input v-model="allQuantity[product.id]" class="w-20 h-9 font-semibold text-gray-800 font-rubick focus:border-indigo-500 border rounded-md" type="number">
                            </div>
                            <div @click.prevent="minus(product.id)" class="py-0.5  px-1 text-xl  rounded-lg border-white border  text-center bg-red-700">
                                <i class="ri-arrow-down-wide-line"></i>
                            </div>
                        </div>
                </div>
            </div>
            <div>
                <div class="py-4 text-center text-white  font-rubick font-semibold">
                   Вы набрали {{summary}}. Осталось: {{remained}} шт.
                </div>
                <div class="text-white text-center">
                    Выходит на сумму: {{totalSum}}
                </div>
                <div class="text-red-500 text-sm font-medium py-2">{{err}}</div>
                <div class="flex px-2 flex-row font-comic justify-center space-x-12 pb-4">
                    <div>
                        <button @click.prevent="addOrder" class="w-full  bg-green-900/60 hover:bg-green-900/40 text-white ring-2 ring-white font-semibold px-2 py-3 rounded transition">
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
<!--    <div class="z-10 absolute w-full h-full bg-gradient-to-br from-green-950/95 via-green-900 to-green-950/90">
        <div class="z-20 max-w-4xl font-lobster mx-auto p-6 rounded-lg bg-green-950  shadow-md mt-36 text-white ">
            <div class="">
                &lt;!&ndash; Фото товара &ndash;&gt;

                &lt;!&ndash; Описание и детали &ndash;&gt;
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
    </div>-->
</template>

<style scoped>

</style>
