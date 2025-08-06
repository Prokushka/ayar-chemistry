<script setup xmlns="http://www.w3.org/1999/html">

import {Link, router, usePage} from "@inertiajs/vue3";
import {computed, onMounted, ref, useTemplateRef} from "vue";
import {animate} from "animejs";
import {useLayoutStore} from "@/stores/layout.js";
import {useUserStore} from "@/stores/user.js";
import Categories from "@/Components/Categories.vue";

const layout = useLayoutStore()

const openMenu = ref(false);

const visionMenu = ref(true)
const visionCat = ref(true)
const showMenu = ref(false)
function mobileMenu(event){
    let el = event.target

    el.classList.toggle('ri-menu-line' )
    let anime = animate(el.parentElement,{
        duration: 350, //350
        alternate: true,
        autoplay: false,
        scale: {
            from: '1.0',
            to: '1.5'
        },
        y:{
            from: '0px',
            to: '10dvh'
        },
        x: {
            from: '0px',
            to: '45dvw',
            ease: 'outCubic',
        },
        rotate: {
            from: '0', // обязательно надо указывать, если хочешь чтобы анимация возвращалась
            to: '360',
            ease: 'inOutQuad'
        },
    })
    el.classList.toggle('ri-close-line' )
    if (openMenu.value === false){
        openMenu.value = true
        showMenu.value = true
        visionCat.value = false
        anime.play()

    }
    else {
        openMenu.value = false
        showMenu.value = false
        visionCat.value = true
        anime.reverse()
    }



}

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
            to: '-57dvw',
            ease: 'outCubic',
        },
        rotate: {
            from: '0', // обязательно надо указывать, если хочешь чтобы анимация возвращалась
            to: '360',
            ease: 'inOutQuad'
        },
    })
    el.classList.toggle('ri-close-line' )
    if (openMenu.value === false){
        openMenu.value = true
        visionMenu.value = false
        anime.play()
    }
    else {
        openMenu.value = false
        visionMenu.value = true
        anime.reverse()
    }


}
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
const categories = computed(() => usePage().props.categories)

function createCategoryLayout(){
    const list = useTemplateRef('categoryList')

}
onMounted(() => createCategoryLayout())
function categoryShow(slug){
    router.visit(route('product.category.show', slug))
}
</script>

<template>
    <div :class="{'backdrop-blur-xl': openMenu, 'bg-green-950': !visionMenu}"   v-if="openMenu" class=" z-50  h-screen w-screen flex justify-center  items-center">
        <div v-if="openMenu && showMenu" class=" flex font-bold flex-col text-yellow-400 font-medium  justify-items-center place-content-center text-center  space-y-4 z-50 text-3xl z-60 absolute translate-y-0 " >
            <Link

                :href="route('main')"
                class="rounded-md px-3 py-2"
            >
                На главную
            </Link>
            <Link
                :href="route('product.index')"
                class="rounded-md px-3 py-2  "
            >
                Товары
            </Link>
            <template v-if="$page.props.auth.user" >
            <Link
                v-if="$page.props.auth.user"
                :href="route('order.index')"
                class="rounded-md px-3 py-2  "
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
                <Link
                    :href="route('login')"
                    class="rounded-md px-3 py-2 "
                >
                    Вход
                </Link>
                <Link

                    :href="route('register')"
                    class="rounded-md px-3 py-2  "
                >
                    Регистрация
                </Link>

            </template>


        </div>
        <div v-if="!visionMenu" class="z-60" >
            <Categories
                v-for="category in categories"
                :key="category.id"
                :category="category"
            />
        </div><!--break-words whitespace-normal-->
    </div>
    <div class="w-full bg-green-950 py-2">
        <div  v-if="layout.isMobile && visionMenu" class="z-50 absolute top-1 left-0 rounded-md z-50 text-white text-3xl  transform bg-green-950 p-2 ">
            <i @click.prevent="mobileMenu($event)" class="ri-menu-line "></i>
        </div>
        <div class=" w-full flex justify-center   ">
            <div class="xs:w-2/3 lg:w-1/3 relative">
                <i class="ri-search-2-line  text-yellow-600 text-xl absolute top-2 right-2   "></i>
                <input @keyup.enter.prevent="searchProduct" v-model="search" class="rounded-md ring-2 mb-1 ring-yellow-600 text-gray-800  text-xl w-full placeholder:text-gray-500 placeholder:text-center" placeholder="Поиск" type="text" >

            </div>

        </div>
        <div id="cat-list" :class="{'bg-green-950' : visionCat}" v-if="layout.isMobile && visionCat" class="z-50 absolute top-0 right-1 rounded-md z-50 text-white text-3xl mt-1 transform  p-2 ">
            <i @click.prevent="getCategories($event)" class="ri-equalizer-3-fill "></i>
        </div>
    </div>

    <div class="   ">

    <slot/>
    </div>
</template>

<style scoped>

</style>
