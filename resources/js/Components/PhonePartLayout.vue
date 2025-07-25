<script setup xmlns="http://www.w3.org/1999/html">

import {Link} from "@inertiajs/vue3";
import {ref} from "vue";
import {animate} from "animejs";
import {useLayoutStore} from "@/stores/layout.js";

const layout = useLayoutStore()

const openMenu = ref(false);
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
        anime.play()

    }
    else {
        openMenu.value = false
        anime.reverse()
    }



}
</script>

<template>
    <div :class="{'backdrop-blur-xl': openMenu}"  v-if="openMenu" class=" z-50  h-screen w-screen flex justify-center  items-center">
        <div class="flex font-bold flex-col text-yellow-400 font-medium  justify-items-center place-content-center text-center  space-y-4 z-50 text-3xl z-60 absolute translate-y-0 " >
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
                    :href="route('logout')"
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
    </div>
    <div v-if="layout.isMobile" class="z-50 absolute top-0 left-0 rounded-md z-50 text-white text-3xl transform bg-green-950 p-2 ">
        <i @click.prevent="mobileMenu($event)" class="ri-menu-line "></i>
    </div>
    <slot/>
</template>

<style scoped>

</style>
