<script >
import GuestLayout from "@/Layouts/GuestLayout.vue";
import MainLayout from "@/Layouts/MainLayout.vue";
import {Head, Link} from "@inertiajs/vue3";
import {route} from "ziggy-js";
import {router} from "@inertiajs/vue3";
export default {
    components: {Link, Head, MainLayout},
    props: {
        products: Object,
        categories: Object
    },

    data(){
        return{
            categoryModel: null,
        }
    },

}
</script>

<template >
    <Head title="Product" />
    <MainLayout/>

    <div class=" text-white z-20   items-center">

        <div class="xs:text-5xl md:text-6xl lg:text-7xl font-lobster text-center py-28 font-medium">
            Наши товары
        </div>

        <!--
   <div class="w-full justify-end flex  ">

            <select  v-model="categoryModel" id="quantity" class="xs:w-1/3 mr-8 rounded-md focus:ring-4 focus:ring-yellow-600  flex text-white  mb-4  h-6 bg-green-950 ">

                <option  class="text-sm" v-for="category in categories" :value="category.title">{{category.title}}</option>
            </select>
        </div>-->

        <div v-if="products.length > 0"  class="flex justify-center place-self-center bg-green-950/70 py-10 rounded-md shadow-xl shadow-green-90/70 ring-2 ring-green-950 w-11/12  grid xs:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-x-4 gap-y-6 items-center justify-items-center  ">
            <div v-for="product in products" class="rounded-md shadow-md shadow-xl overflow-hidden shadow-green-950 w-11/12  relative  h-[420px]  p-4   relative  bg-green-950 flex place-content-center justify-between items-center flex-col">
                <div v-if="!product.is_active" class="absolute bg-yellow-700 rounded-lg text-white transform text-sm  h-5 w-[118px] z-30  text-center font-bold font-rubick top-[220px] right-4">
                    Нет в Наличии</div>
                <div v-if="product.event_color" :style="`background-color: ${product.event_color};`" class="absolute bg-red-700 text-white transform rotate-45 h-6 w-[180px] z-30 text-center font-semibold font-rubick top-8 -right-10">
                    {{ product?.event }}</div>
                <img
                    :src="`/storage/${product.image_url}`"
                    class="h-56 h-56 object-contain mb-2 rounded"
                /><p class="text-white  text-balance font-lobster font-semibold line-clamp-3   text-center  text-2xl">
                <Link :href="route('product.show', product.slug)">
                    {{product.title}}
                </Link>
                </p>

                <div class="text-white relative text-xl text-center w-full line-clamp-3 font-lobster flex justify-between  pt-3" >
                    <div class="w-full absolute top-1 bg-yellow-400 h-0.5 "></div>
                            <span class="text-xl  font-bold place-content-center">
                               от <span class="text-yellow-500 text-[1.2em]"> {{ product.min_price }}</span> ₽
                            </span>
                    <div >
                        <span class="text-white text-2xl font-semibold mr-3">{{product.size}} </span>
                        <Link :href="route('product.show', product.slug)">
                            <button class="bg-yellow-500 py-2 px-3 rounded-lg border border-yellow-500 text-lg
                                hover:text-yellow-500 hover:bg-gray-200  hover:border-white transform duration-500 ease-out">
                                <i class="ri-luggage-cart-fill"></i>
                            </button>

                        </Link>
                    </div>

                </div>

            </div>

        </div>
        <div  v-else class="xs:text-2xl md:text-4xl lg:text-5xl font-lato text-center py-28 font-medium">
           Ничего не найдено по запросу
        </div>

    </div>

</template>

<style scoped>

</style>
