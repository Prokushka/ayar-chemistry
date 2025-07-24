<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
import {animate, createTimeline} from "animejs";
import {onBeforeUnmount, onMounted, ref} from "vue";
import PhonePartLayout from "@/Components/PhonePartLayout.vue";
import MainLayout from "@/Layouts/MainLayout.vue";

function animatePriskalka() {
    createTimeline({
            duration: 1000
    }
    ).add('#priskalka', {
        rotate: {
            from: 0,
            to: -30
        }
    },300).add('#priskalka',{
        rotate:{
            from: -30,
            to: 30
        },
        scale:{
            from: 1.0,
            to: 1.5
        }
    }, 700)
}

onMounted(() => animatePriskalka())

const isMobile = ref(false)

function updateViewport() {
    isMobile.value = window.innerWidth < 1024
}

onMounted(() => {
    window.addEventListener('resize', updateViewport)
    updateViewport()
})

onBeforeUnmount(() => {
    window.removeEventListener('resize', updateViewport)
})
</script>

<template>

    <div
        class="flex overflow-hidden min-h-screen relative  flex-col items-center bg-gradient-to-r from-green-950/95 via-green-900 to-green-950/90 pt-6 sm:justify-center sm:pt-0"
    >
        <div v-if="!isMobile" class="  absolute  z-10  w-[50dvw]   h-[50dvw] rounded-[100%] -top-[18dvw] -left-[20dvw] bg-green-950  animate-up-size"></div>
        <div v-if="!isMobile" class=" absolute  z-10  w-[50dvw]   h-[50dvw] rounded-[100%] -bottom-[18dvw] -right-[20dvw] bg-green-950  animate-up-size"></div>
        <img src="/stickers/main_text.png" class="z-20" width="350" alt="">
        <img v-if="!isMobile" id="priskalka" src="/stickers/priskalka.png"  class="absolute opacity- 65 z-20 -left-[20dvh] rotate-30 -bottom-[20dvh]">
        <div
            class="mt-6 w-full overflow-hidden  bg-gray-300 px-6 py-4 z-30 shadow-md sm:max-w-md sm:rounded-lg"
        >
            <slot />
        </div>
    </div>
</template>
