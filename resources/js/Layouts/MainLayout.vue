<script setup>

import {Link, router} from "@inertiajs/vue3";

import PhonePartLayout from "@/Components/PhonePartLayout.vue";
import {useLayoutStore} from "@/stores/layout.js";
import {useUserStore} from "@/stores/user.js";
import {computed, onMounted, ref, watch} from 'vue'
import { usePage } from '@inertiajs/vue3'
import {animate} from "animejs";
import Categories from "@/Components/Categories.vue";

const layout = useLayoutStore()
const user = useUserStore()

const logout = () => {

    router.post(route('logout'), {}, {
        onSuccess: function (){
            user.setUser(false)
        }
    });
};
const search = ref('')
function searchProduct(){

    router.visit(route('product.search', {
        query_string: search.value
    }))
}

const openCategoryMenu = ref(false)
function getCategories(event){
    let el = event.target
    el.classList.toggle('ri-equalizer-3-fill' )

    let anime = animate(el.parentElement,{
        duration: 350, //350
        alternate: true,
        autoplay: false,
        scale: {
            from: '1.0',
            to: '1.5'
        },
        x: {
            from: '0px',
            to: '275px',
            ease: 'outCubic',
        },
        rotate: {
            from: '0', // обязательно надо указывать, если хочешь чтобы анимация возвращалась
            to: '360',
            ease: 'inOutQuad'
        },
    })
    el.classList.toggle('ri-close-line' )
    if (openCategoryMenu.value === false){
        openCategoryMenu.value = true
        anime.play()
    }
    else {
        openCategoryMenu.value = false
        anime.reverse()
    }


}
function categoryShow(slug){
    router.visit(route('product.category.show', slug))
}
const page = usePage()
const categories = computed(() => page.props.categories)
const breadcrumbs = computed(() => page.props.breadcrumbs ?? [])

onMounted(() => console.log(categories.value))
</script>

<template>

    <div class="fixed w-full z-50 ">

        <PhonePartLayout v-if="layout.isMobile"></PhonePartLayout>



        <header v-if="!layout.isMobile" class="flex fixed z-50 flex-row  w-full  h-auto   font-rubick text-xl  bg-green-950 items-center py-3 lg:grid-cols-3">
            <nav class="mx-3 text-white font-medium flex flex-1  justify-between pr-3">
                <div class=" pt-2">
                    <Link

                        :href="route('main')"
                        class="rounded-md px-3 py-2 text-white "
                    >
                        На главную
                    </Link>
                    <Link

                        :href="route('product.index')"
                        class="rounded-md px-3 py-2 "
                    >
                        Товары
                    </Link>
                </div>
                <div class="w-2/5  ">
                    <div class="w-full relative">
                        <div class="relative">
                            <i class="ri-search-2-line  text-yellow-600 text-xl absolute top-2 right-2   "></i>
                            <input @keyup.enter.prevent="searchProduct" v-model="search" class="rounded-md ring-2 mb-1 ring-yellow-600 text-gray-800  text-xl w-full placeholder:text-gray-500 placeholder:text-center" placeholder="Поиск" type="text" >

                        </div>
                        <div id="cat-list"  class="z-50 absolute -top-1.5 -right-14 rig rounded-md z-50 text-white text-3xl mt-1 transform bg-green-950 p-2 ">
                            <i @click.prevent="getCategories" class="ri-equalizer-3-fill  "></i>
                        </div>
                    </div>

                </div>
                <div>

                <template v-if="$page.props.auth.user">

                        <Link
                            v-if="$page.props.auth.user"
                            :href="route('order.index')"
                            class="rounded-md px-3 py-2 "
                        >
                            Мои заказы
                        </Link>
                        <Link
                            @click.prevent="logout"
                            method="post"
                            class="rounded-md px-3 py-2"
                        >
                            Выйти
                        </Link>


                </template>
                <template v-else>
                    <div class="pt-2">
                    <Link
                        :href="route('login')"
                        class="rounded-md px-3 py-2"
                    >
                        Вход
                    </Link>

                    <Link

                        :href="route('register')"
                        class="rounded-md px-3 py-2 "
                    >
                        Регистрация
                    </Link>
                    </div>
                </template>
                </div>
            </nav>
            <div id="right-cat-menu" v-if="openCategoryMenu" class="absolute text-white font-rubick font-bold rounded-l-xl h-screen bg-green-950 right-0 top-0 w-1/5 ">
                <div class="text-4xl  text-center pt-12">Категории</div>
                <div class="pt-12  space-y-4 ">

                    <Categories
                        v-for="category in categories"
                        :key="category.id"
                        :category="category"
                    />
                </div>
            </div>
        </header>

            <slot />




    </div>
    <nav
        v-if="breadcrumbs.length > 1"
        aria-label="Breadcrumb"
        class="z-20 absolute top-[73px] left-0 md:pt-3 lg:pt-6 px-4"
    >
        <ol class="flex flex-wrap text-white text-sm md:text-base font-rubick font-semibold space-x-1 md:space-x-2">
            <li
                v-for="(crumb, i) in breadcrumbs"
                :key="crumb.title"
                class="flex items-center"
            >
                <Link :href="crumb.url" class="hover:underline">
                    {{ crumb.title }}
                </Link>
                <span
                    v-if="i < breadcrumbs.length - 1"
                    class="px-1 text-yellow-400 select-none"
                    aria-hidden="true"
                >
        &rsaquo;
      </span>
            </li>
        </ol>
    </nav>

</template>

<style scoped>

</style>
