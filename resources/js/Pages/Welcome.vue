<script setup>
import {Head, Link, useForm} from '@inertiajs/vue3';
import {computed, onBeforeUnmount, onMounted, ref} from "vue";
import {animate, createTimeline, onScroll} from "animejs";
import MainLayout from "@/Layouts/MainLayout.vue";
import {useLayoutStore} from "@/stores/layout.js";
import FooterComponent from "@/Components/FooterComponent.vue";
import {useUserStore} from "@/stores/user.js";
import HeaderComponent from "@/Components/HeaderComponent.vue";


function animation(entries, toggle){
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                console.log('Элемент появился в viewport');
                toggle.value = true;
                observer.unobserve(entry.target);
            }
        });
    }, {
        root: null, // Наблюдаем относительно viewport
        rootMargin: '0px', // Дополнительная зона вокруг root
        threshold: 0.1 // Сработает при 10% видимости
    });
    observer.observe(entries);
}
const doverie = ref(false)
const firstAnimation = ref(false)
const secondAnimation = ref(false)
const container1 = ref(false)
const container2 = ref(false)
const container3 = ref(false)
const dovolny = ref(false)
onMounted( () => {
    const text1 = document.getElementById('text1');
    if (text1) {
        animation(text1, doverie)
    }

    const text2 = document.getElementById('text2');
    if (text2) {
        animation(text2, firstAnimation)
    }

// можно всё это через switch/case попробовать
    const cleaning_more_logo = document.getElementById('cleaning_more_logo');
    if (cleaning_more_logo) {
        animation(cleaning_more_logo, secondAnimation)
    }
    const firstContainer = document.getElementById('container-1');
    if (firstContainer) {
        animation(firstContainer, container1)
    }
    const secondContainer = document.getElementById('container-2');
    if (secondContainer) {
        animation(secondContainer, container2)
    }
    const thirdContainer = document.getElementById('container-3');
    if (thirdContainer) {
        animation(thirdContainer, container3)
    }
    const dovolnyElement = document.getElementById('dovolny');
    if (dovolnyElement) {
        animation(dovolnyElement, dovolny)
    }
},  )
//>>>>>>>>>>>>> ANIME.JS <<<<<<<<<<<<<<
function rotateSecondLogo(target) {
    let el = target.target
    if (el.classList.contains('animate-roll-left')){
        el.classList.remove('animate-roll-left')
    }
    if (target){
        const rule = createTimeline({
            defaults: {duration: 1500}
        }).add(el, {
            translateX: 500 ,
            rotate: 90,
            ease: 'out(4)',
            duration: 1500,
            direction: 'alternate'
        }).then(() =>  setTimeout( animate(el, { translateX: 0, rotate: 0,
            ease: 'out(4)',
            duration: 1500,
        }), 100))
    }
}
function rotateFirstLogo(target) {
   let el = target.target
    if (el.classList.contains('animate-roll-right')){
        el.classList.remove('animate-roll-right')
    }
    if (target){
        const rule = createTimeline({
            defaults: {duration: 1500}
        }).add(el, {
            translateX: -500 ,
            rotate: -90,
            ease: 'out(4)',
            duration: 1500,
            direction: 'alternate'
        }).then(() =>  setTimeout( animate(el, { translateX: 0, rotate: 0,
            ease: 'out(4)',
            duration: 1500,
        }), 100))

    }

}
animate('#container-1', { x: '350px',
    duration: 500,
    ease: 'inOutQuad',
    alternate: true,
    autoplay: onScroll({
        container: document.getElementById('container-1'),
        target: document.getElementById('container-1')
    })})
let mapInstance = null;

function waitForYmaps() {
    return new Promise((resolve, reject) => {
        if (window.ymaps && window.ymaps.ready) {
            window.ymaps.ready(() => resolve(window.ymaps));
        } else {
            // Если ymaps ещё не загрузился, ждём появления объекта
            const checkInterval = setInterval(() => {
                if (window.ymaps && window.ymaps.ready) {
                    clearInterval(checkInterval);
                    window.ymaps.ready(() => resolve(window.ymaps));
                }
            }, 100);
            setTimeout(() => {
                clearInterval(checkInterval);
                reject(new Error('ymaps не загрузился'));
            }, 10000);
        }
    });
}
  onMounted(async () => {
        try {
            const ymaps = await waitForYmaps();

            mapInstance = new ymaps.Map('map', {
                center: [55.769862, 49.208450],
                zoom: 12,
            });

            const placemark = new ymaps.Placemark([55.769862, 49.208450], {
                hintContent: 'Москва!',
                balloonContent: 'Столица России',
            });
            mapInstance.geoObjects.add(placemark);
            mapInstance.events.add('click', function (e) {
                const coords = e.get('coords');
                mapInstance.setCenter(coords);
            });
        } catch (error) {
            console.error('Ошибка инициализации Яндекс.Карт:', error);
        }
    });

// Чистим карту при размонтировании компонента
onBeforeUnmount(() => {
    if (mapInstance) {
        mapInstance.destroy();
        mapInstance = null;
    }
});

function scrollToBottom() {

    scrollTo('100%', '100%')
}

const props = defineProps({
    products:{
        type: Object
    },
    breadcrumbs: {
        type: Object
    }
})
onMounted(() => console.log(props?.breadcrumbs))
function mapPageAnimate(){


    animate('#mapPage',{
        x: [
            { to: '65dvw', ease: 'out', duration: 1000 },
        ],
        y: [
            { to: '25dvh', ease: 'in', duration: 0,  },
        ],
        scale: [
            { to: 0.5 },
            { to: 2.4, duration: 400, delay: 400 },
        ],
        autoplay: onScroll()
    })

}
onMounted(() => mapPageAnimate())
const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const layout = useLayoutStore()
function animatePopular(){

    if (!layout.isMobile) {


        for (let i = 1; ; i++) {
            let el = document.getElementById(`popular-${i}`)
            let yPos = '-100px';
            let xPos = 0;
            if (!el) {
                break;
            }
            animate(el, {
                translateY: [yPos, 0],
                translateX: [xPos, 0],
                ease: 'out',
                duration: (i * 350) + 200,
                autoplay: onScroll()
            })
        }
    }

}


onMounted(() =>  animatePopular())


onMounted(() => {
    new Swiper('.swiper', {
        // Optional parameters

        loop: true,
        speed: 400,
        spaceBetween: 30,

        autoplay:{
            delay: 4200,
            disabledOnInteraction: false
        },
        // If we need pagination
        pagination: {
            el: '.swiper-pagination',
            clickable: true
        },

        // Navigation arrows
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        breakpoints: {
            // when window width is >= 320px
            300: {
                slidesPerView: 1,
                spaceBetween: 10
            },
            // when window width is >= 480px
            480: {
                slidesPerView: 2,
                spaceBetween: 20
            },
            // when window width is >= 640px
            760: {
                slidesPerView: 3,
                spaceBetween: 25
            }
        }

        // And if we need scrollbar

    });
})

function navLink(i){
    const el = document.getElementById(`section-${i}`)
    el.scrollIntoView({
        behavior: 'smooth',
        block: 'start'
    })
}

const userStore = useUserStore()




</script>

<template>
    <Head title="Welcome" />
    <MainLayout/>
    <section>
        <header-component/>
        <section>
            <nav class="w-full xs:text-[0.7rem] lg:text-[1.2rem] flex text-white font-semibold lg:space-x-2 justify-center bg-green-950">
                <div   id="cursor-1"  class="xs:px-1 lg:px-2  cursor-pointer flex justify-center flex-row py-[1rem]">
                    <div>
                        <span @click.prevent="navLink(1)" class="hover:text-yellow-500">О нас </span>
                    </div>
                    <div class="ml-1.5 lg:ml-4 xs:h-5 lg:h-8 bg-white w-0.5 rounded-sm"></div>
                </div>
                <div id="cursor-2" class="xs:px-1 lg:px-2  cursor-pointer flex justify-center flex-row py-[1rem]">
                    <div>
                        <span @click.prevent="navLink(2)" class="hover:text-yellow-500">Популярные товары </span>
                    </div>
                      <div class="ml-1.5 lg:ml-4 xs:h-5 lg:h-8 bg-white w-0.5 rounded-sm"></div>
                </div>
                <div id="cursor-3" class="xs:px-1 lg:px-2  cursor-pointer flex justify-center flex-row py-[1rem]">
                    <div>
                        <span @click.prevent="navLink(3)" class="hover:text-yellow-500">Отзывы  </span>
                    </div>
                      <div class="ml-1.5 lg:ml-4 xs:h-5 lg:h-8 bg-white w-0.5 rounded-sm"></div>
                </div>
                <div id="cursor-4" class="xs:px-1 md:px-2  cursor-pointer mt-[1rem] ">
                    <div>
                        <span @click.prevent="navLink(4)" class="hover:text-yellow-500 ">Как добраться?</span>
                    </div>
                </div>
            </nav>
        </section>
    </section>
    <main>
        <div class=" xs:space-y-[155px] lg:space-y-[240px]  overflow-hidden relative">


            <!--    classic containers        -->
            <section id="section-1" class="relative z-10 flex flex-col items-center justify-center space-y-12 pt-[5rem]  text-white font-lobster ">
                <div id="text2" :class="{'animate-text-up': firstAnimation}" class="xs:text-[40px] font-rubick font-semibold xs:text-4xl w-11/12 text-center md:text-5xl lg:text-7xl xs:pb-12 xs:pb-16 md:pb-24 lg:pb-32 items-center lg:text-center">
                    Почему стоит <p v-if="layout.isMobile" >брать у наc?</p><span v-if="!layout.isMobile" >брать у наc?</span>
                </div>
                <img :class="{'animate-roll-right ': firstAnimation}" id="cleaning_logo" @click.prevent="rotateFirstLogo($event)" src="/stickers/cleaning_logo.png" class="absolute xs:hidden lg:block overflow-hidden xs:opacity-25 lg:opacity-75 z-30 xs:-left-[38%] lg:-left-[32%]  xs:top-[15%] md:top-[20%]  rounded-[100%]">
                <img id="cleaning_more_logo" src="/stickers/more_cleaning_logo.png" :class="{'animate-roll-left': secondAnimation}" @click.prevent="rotateSecondLogo($event)" class=" overflow-hidden absolute xs:hidden lg:block xs:opacity-25 z-50 lg:opacity-75 xs:top-[50%] lg:top-[35%] xs:-right-[48%] lg:-right-[34%] bottom-[35%]  rounded-[100%]">
                <div class="grid grid-rows-3 gap-y-10 justify-items-center">
                    <div id="container-1" :class="{'animate-slide-left': container1}" class="bg-green-950  xs:w-11/12 md:w-5/6 lg:w-1/3 xs:p-4 xs:p-5 xs:text-center lg:p-10 rounded-lg">
                        <div class="flex flex-row  ">
                            <div>
                                <p class="xs:text-xl lg:text-4xl text-center mb-7 font-semibold font-lobster">Честные цены и удобные условия работы </p>
                            </div>
                            <div>
                                <svg  xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-7">
                                    <path class="cursor-pointer" stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                </svg>
                            </div>
                        </div>

                        <span class="xs:text-[0.8rem] font-medium lg:text-xl font-rubick">
            Мы закупаем продукцию <strong class="text-yellow-500 ">напрямую</strong>  у производителей и держим собственные складские запасы,
            поэтому можем предлагать действительно выгодные цены и гибкие условия поставки.
            Для постоянных клиентов действуют персональные скидки и ускоренная доставка.
        </span>
                    </div>

                    <div id="container-2" :class="{'animate-slide-right': container2}" class="bg-green-950 xs:w-11/12 md:w-5/6 lg:w-1/3 xs:p-4 xs:p-5 xs:text-center lg:p-10 rounded-lg">
                        <div class="flex flex-row  ">
                            <div>
                                <p class="xs:text-xl lg:text-4xl text-center mb-7 font-semibold font-lobster">Качество, за которое отвечаем</p>
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path class="cursor-pointer" stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 0 0 .75-.75 2.25 2.25 0 0 0-.1-.664m-5.8 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25ZM6.75 12h.008v.008H6.75V12Zm0 3h.008v.008H6.75V15Zm0 3h.008v.008H6.75V18Z" />
                                </svg>
                            </div>
                        </div>
                        <span class="xs:text-[0.8rem] font-medium lg:text-xl font-rubick">
            Вся наша продукция проходит проверку на соответствие стандартам и требованиям безопасности.
            Мы работаем только с надёжными брендами и поставщиками, а каждую партию дополнительно контролируем перед отгрузкой.
            Мы уверены в том, что поставляем — и <strong class="text-yellow-500 ">отвечаем</strong> за результат.
        </span>
                    </div>

                    <div id="container-3" :class="{'animate-slide-left': container3}" class="bg-green-950 xs:w-11/12 md:w-5/6 lg:w-1/3 xs:p-4 xs:p-5 xs:text-center lg:p-10 rounded-lg">
                        <div class="flex flex-row ">
                            <div><p class="xs:text-xl lg:text-4xl text-center mb-7 font-semibold font-lobster">Ассортимент и доставка без границ</p></div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m20.893 13.393-1.135-1.135a2.252 2.252 0 0 1-.421-.585l-1.08-2.16a.414.414 0 0 0-.663-.107.827.827 0 0 1-.812.21l-1.273-.363a.89.89 0 0 0-.738 1.595l.587.39c.59.395.674 1.23.172 1.732l-.2.2c-.212.212-.33.498-.33.796v.41c0 .409-.11.809-.32 1.158l-1.315 2.191a2.11 2.11 0 0 1-1.81 1.025 1.055 1.055 0 0 1-1.055-1.055v-1.172c0-.92-.56-1.747-1.414-2.089l-.655-.261a2.25 2.25 0 0 1-1.383-2.46l.007-.042a2.25 2.25 0 0 1 .29-.787l.09-.15a2.25 2.25 0 0 1 2.37-1.048l1.178.236a1.125 1.125 0 0 0 1.302-.795l.208-.73a1.125 1.125 0 0 0-.578-1.315l-.665-.332-.091.091a2.25 2.25 0 0 1-1.591.659h-.18c-.249 0-.487.1-.662.274a.931.931 0 0 1-1.458-1.137l1.411-2.353a2.25 2.25 0 0 0 .286-.76m11.928 9.869A9 9 0 0 0 8.965 3.525m11.928 9.868A9 9 0 1 1 8.965 3.525" />
                                </svg>
                            </div>
                        </div>


                        <span class="xs:text-[0.8rem] font-medium lg:text-xl font-rubick">
            На наших складах — всё от бытовой химии до профессиональных средств для клининга.
            Мы <strong class="text-yellow-500 ">регулярно</strong> расширяем ассортимент и поддерживаем наличие ходовых позиций.
            Благодаря собственной логистике доставляем заказы быстро и точно в срок — по всему региону и за его пределы.
        </span>
                    </div>
                </div>
            </section>

            <section id="section-2" class="flex-col flex scroll-mt-20  justify-center items-center" v-if="products.length > 3" >
                <div class=" w-full text text-white font-rubick font-semibold xs:text-[32px] xs:text-4xl place-content-center text-center md:text-5xl lg:text-7xl xs:pb-28 xs:pb-32 md:pb-48 lg:pb-52  items-center lg:text-7xl">
                    Популярные товары
                </div>
                <div  class="flex justify-center place-self-center bg-green-950/70 py-10 rounded-md shadow-xl shadow-green-900/70 ring-2 ring-green-950 w-11/12 grid xs:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-x-4 gap-y-36 items-center justify-items-center  ">
                    <div id="popular-1"  class="rounded-md shadow-xl w-[95%] min-h-[340px]    px-10 pt-20 shadow-green-950 h-auto relative space-y-3 bg-green-950 flex justify-end items-center flex-col">

                        <img :src="`/storage/${ products[0].image_url}`" class="absolute w-48 -top-4 left-1/2   -translate-y-1/2 -translate-x-1/2 transform hover:-top-8 duration-500 ease-in-out   items-center" >

                        <p class=" text-balance text-white line-clamp-3   font-lobster font-semibold  text-center  text-3xl">
                            <Link :href="route('product.show', products[0].slug)"> {{ products[0].title }} </Link>
                        </p>

                        <div class="text-white text-xl relative text-center w-full line-clamp-3 font-lobster flex justify-between pb-4  pt-2" >
                            <div class="w-full bg-yellow-400 h-0.5 absolute top-0 "></div>
                            <span class="text-xl  font-bold place-content-center">
                               от <span class="text-yellow-500 text-[1.2em]"> {{ products[0].min_price }}</span> ₽
                            </span>
                            <div >
                                <span class="text-white text-lg font-rubick font-semibold mr-3">{{products[0].size}} </span>
                                <Link :href="route('product.show', products[0].slug)">
                                    <button class="bg-yellow-500 py-2 px-3 rounded-lg border border-yellow-500 text-lg
                                hover:text-yellow-500 hover:bg-gray-200  hover:border-white transform duration-500 ease-out">
                                        <i class="ri-luggage-cart-fill"></i>
                                    </button>

                                </Link>
                            </div>

                        </div>

                    </div>
                    <div id="popular-2"  class="rounded-md shadow-xl w-[95%] min-h-[340px]    px-10 pt-20 shadow-green-950 h-auto relative space-y-3 bg-green-950 flex justify-end items-center flex-col">

                        <img :src="`/storage/${ products[1].image_url}`" class="absolute w-48 -top-4 left-1/2   -translate-y-1/2 -translate-x-1/2 transform hover:-top-8 duration-500 ease-in-out   items-center" >

                        <p class=" text-balance text-white line-clamp-3   font-lobster font-semibold  text-center  text-3xl">
                            <Link :href="route('product.show', products[1].slug)"> {{ products[1].title }} </Link>
                        </p>

                        <div class="text-white text-xl relative text-center w-full line-clamp-3 font-lobster flex justify-between pb-4  pt-2" >
                            <div class="w-full bg-yellow-400 h-0.5 absolute top-0 "></div>
                            <span class="text-xl  font-bold place-content-center">
                               от <span class="text-yellow-500 text-[1.2em]"> {{ products[1].min_price }}</span> ₽
                            </span>
                            <div >
                                <span class="text-white text-lg font-rubick font-semibold mr-3">{{products[1].size}} </span>
                                <Link :href="route('product.show', products[1].slug)">
                                    <button class="bg-yellow-500 py-2 px-3 rounded-lg border border-yellow-500 text-lg
                                hover:text-yellow-500 hover:bg-gray-200  hover:border-white transform duration-500 ease-out">
                                        <i class="ri-luggage-cart-fill"></i>
                                    </button>

                                </Link>
                            </div>

                        </div>

                    </div>
                    <div id="popular-3"  class="rounded-md shadow-xl w-[95%] min-h-[340px]    px-10 pt-20 shadow-green-950 h-auto relative space-y-3 bg-green-950 flex justify-end items-center flex-col">

                        <img :src="`/storage/${ products[2].image_url}`" class="absolute w-48 -top-4 left-1/2   -translate-y-1/2 -translate-x-1/2 transform hover:-top-8 duration-500 ease-in-out   items-center" >

                        <p class=" text-balance text-white line-clamp-3   font-lobster font-semibold  text-center  text-3xl">
                            <Link :href="route('product.show', products[2].slug)"> {{ products[2].title }} </Link>
                        </p>

                        <div class="text-white text-xl relative text-center w-full line-clamp-3 font-lobster flex justify-between pb-4  pt-2" >
                            <div class="w-full bg-yellow-400 h-0.5 absolute top-0 "></div>
                            <span class="text-xl  font-bold place-content-center">
                               от <span class="text-yellow-500 text-[1.2em]"> {{ products[2].min_price }}</span>₽
                            </span>
                            <div>
                                <span class="text-white text-lg font-rubick font-semibold mr-3">{{products[2].size}}</span>
                                <Link :href="route('product.show', products[2].slug)">
                                    <button class="bg-yellow-500 py-2 px-3 rounded-lg border border-yellow-500 text-lg
                                hover:text-yellow-500 hover:bg-gray-200  hover:border-white transform duration-500 ease-out">
                                        <i class="ri-luggage-cart-fill"></i>
                                    </button>

                                </Link>
                            </div>

                        </div>

                    </div>
                    <div id="popular-4"  class="rounded-md shadow-xl w-[95%] min-h-[340px]    px-10 pt-20 shadow-green-950 h-auto relative space-y-3 bg-green-950 flex justify-end items-center flex-col">

                        <img :src="`/storage/${ products[3].image_url}`" class="absolute w-48 -top-4 left-1/2   -translate-y-1/2 -translate-x-1/2 transform hover:-top-8 duration-500 ease-in-out   items-center" >

                        <p class=" text-balance text-white line-clamp-3   font-lobster font-semibold  text-center  text-3xl">
                            <Link :href="route('product.show', products[3].slug)"> {{ products[3].title }} </Link>
                        </p>

                        <div class="text-white text-xl relative text-center w-full line-clamp-3 font-lobster flex justify-between pb-4  pt-2" >
                            <div class="w-full bg-yellow-400 h-0.5 absolute top-0 "></div>
                            <span class="text-xl  font-bold place-content-center">
                               от <span class="text-yellow-500 text-[1.2em]"> {{ products[3].min_price }}</span> ₽
                            </span>
                            <div >
                                <span class="text-white text-lg font-rubick font-semibold mr-3">{{products[3].size}} </span>
                                <Link :href="route('product.show', products[3].slug)">
                                    <button class="bg-yellow-500 py-2 px-3 rounded-lg border border-yellow-500 text-lg
                                hover:text-yellow-500 hover:bg-gray-200  hover:border-white transform duration-500 ease-out">
                                        <i class="ri-luggage-cart-fill"></i>
                                    </button>

                                </Link>
                            </div>

                        </div>

                    </div>

                </div>
                <div class="bg-green-950
                text-white lg:text-4xl font-rubick font-semibold p-6 mt-12 xs:text-xl
                 md:text-3xl w-auto hover:ring-white hover:text-yellow-600 ring-2 text-center  place-self-center ring-yellow-500 rounded-full">
                    <Link :href="route('product.index')" >
                        Посмотреть все товары
                    </Link>
                </div>

            </section>
            <section id="section-3"  class="z-40 scroll-mt-20 ">
                <div class="bg-green-950 text-white mt-10  font-lobster py-10 space-y-6 ">

                <div id="text1" :class="{'animate-slide-right': doverie}" class=" lg:pl-24 xs:text-center  w-full  xs:text-center lg:text-start
                 xs:text-3xl md:text-5xl lg:text-7xl font-lobster pt-2 ">Эти клиенты <p v-if="layout.isMobile">доверились нам</p><span  v-if="!layout.isMobile">доверились нам</span> </div>

                <div class="swiper">
                    <!-- Additional required wrapper -->
                    <div class="swiper-wrapper text-white py-12">
                        <!-- Слайд 1 -->
                        <div class="swiper-slide flex justify-center space-x-6">
                            <div class="bg-green-900/65 ring-2 ring-green-950 rounded-lg p-6 max-w-sm w-full space-y-3 flex flex-col">
                                <div class="flex items-center">
                                    <img width="50" class="rounded-full" src="/stickers/mannn.jpeg" alt="">
                                    <p class="ml-3 font-lobster text-2xl">Антон Гарифуллин</p>
                                </div>
                                <p class="font-rubick text-lg">
                                    Работаем с Ayar chemistry уже второй год — всегда широкий ассортимент, быстрая отгрузка и приятные цены. Очень довольны сотрудничеством!
                                </p>
                                <span class="text-yellow-500 font-lobster text-5xl text-right font-semibold">,,</span>
                            </div> </div>
                        <div class="swiper-slide flex justify-center space-x-6"><div class="bg-green-900/65 ring-2 ring-green-950 rounded-lg p-6 max-w-sm w-full space-y-3 flex flex-col">
                            <div class="flex items-center">
                                <img width="50" class="rounded-full" src="/stickers/man_client.jpeg" alt="">
                                <p class="ml-3 font-lobster text-2xl">Ринат Гимадиев</p>
                            </div>
                            <p class="font-rubick text-lg">
                                Заказывали бытовую химию крупным оптом для торговой сети, всё пришло в срок, качество продукции отличное, менеджеры всегда на связи.
                            </p>
                            <span class="text-yellow-500 font-lobster text-5xl text-right font-semibold">,,</span>
                        </div></div>
                        <!-- Слайд 2 -->
                        <div class="swiper-slide flex justify-center space-x-6">
                            <div class="bg-green-900/65 ring-2 ring-green-950 rounded-lg p-6 max-w-sm w-full space-y-3 flex flex-col">
                                <div class="flex items-center">
                                    <img width="50" class="rounded-full" src="/stickers/woman_ind.jpeg" alt="">
                                    <p class="ml-3 font-lobster text-2xl">Гузель Хасанова</p>
                                </div>
                                <p class="font-rubick text-lg">
                                    Приятно удивила гибкая система скидок и индивидуальный подход к клиенту. Ayar chemistry — надёжный поставщик!
                                </p>
                                <span class="text-yellow-500 font-lobster text-5xl text-right font-semibold">,,</span>
                            </div>  </div>
                        <div class="swiper-slide flex justify-center space-x-6">
                            <div class="bg-green-900/65 ring-2 ring-green-950 rounded-lg p-6 max-w-sm w-full space-y-3 flex flex-col">
                                <div class="flex items-center">
                                    <img width="50" class="rounded-full" src="/stickers/man.jpeg" alt="">
                                    <p class="ml-3 font-lobster text-2xl">Сергей Кузнецов</p>
                                </div>
                                <p class="font-rubick text-lg">
                                    Оформление заказа занимает минимум времени, документы всегда в порядке, доставка чётко по графику. Спасибо за профессионализм!
                                </p>
                                <span class="text-yellow-500 font-lobster text-5xl text-right font-semibold">,,</span>
                            </div>
                        </div>
                        <div class="swiper-slide flex justify-center space-x-6">
                            <div class="bg-green-900/65 ring-2 ring-green-950 rounded-lg p-6 max-w-sm w-full space-y-3 flex flex-col">
                                <div class="flex items-center">
                                    <img width="50" class="rounded-full" src="/stickers/true_another_people.jpeg" alt="">
                                    <p class="ml-3 font-lobster text-2xl">Руслан Минниханов</p>
                                </div>
                                <p class="font-rubick text-lg">
                                    Очень удобно, что можно заказать как крупный, так и мелкий опт. Всегда подбирают оптимальные условия для нашего бизнеса.
                                </p>
                                <span class="text-yellow-500 font-lobster text-5xl text-right font-semibold">,,</span>
                            </div>
                        </div>
                        <div class="swiper-slide flex justify-center space-x-6">
                            <div class="bg-green-900/65 ring-2 min-h-[282px] ring-green-950 rounded-lg p-6 max-w-sm w-full space-y-3 flex flex-col">
                                <div class="flex items-center">
                                    <img width="50" class="rounded-full" src="/stickers/woman_client.jpeg" alt="">
                                    <p class="ml-3 font-lobster text-2xl">Ирина Васильева</p>
                                </div>
                                <p class="font-rubick text-lg">
                                    Постоянно заказываем у Ayar chemistry — надёжный поставщик, всё чётко и без задержек.</p>
                                <span class="text-yellow-500 font-lobster text-5xl text-right font-semibold">,,</span>
                            </div>
                        </div>
                        <!-- Слайд 3 -->
                        <div class="swiper-slide flex justify-center space-x-6">
                            <div class="bg-green-900/65 ring-2 ring-green-950 rounded-lg p-6 max-w-sm w-full space-y-3 flex flex-col">
                                <div class="flex items-center">
                                    <img width="50" class="rounded-full" src="/stickers/another_woman.jpeg" alt="">
                                    <p class="ml-3 font-lobster text-2xl">Людмила Сиддикова</p>
                                </div>
                                <p class="font-rubick text-lg">
                                    Уже несколько раз заказывали у Ayar chemistry бытовую химию для клининговой компании — всё устраивает, рекомендуем!
                                </p>
                                <span class="text-yellow-500 font-lobster text-5xl text-right font-semibold">,,</span>
                            </div>
                        </div>
                        <div class="swiper-slide flex justify-center space-x-6">
                            <div class="bg-green-900/65 ring-2 ring-green-950 rounded-lg p-6 max-w-sm w-full space-y-3 flex flex-col">
                                <div class="flex items-center">
                                    <img width="50" class="rounded-full" src="/stickers/school_woman.jpeg" alt="">
                                    <p class="ml-3 font-lobster text-2xl">Лилия Галимова</p>
                                </div>
                                <p class="font-rubick text-lg">
                                    Приятно работать с компанией, которая ценит своих клиентов и всегда идёт навстречу по срокам и условиям оплаты.
                                </p>
                                <span class="text-yellow-500 font-lobster text-5xl text-right font-semibold">,,</span>
                            </div>
                        </div>
                        <div class="swiper-slide flex justify-center space-x-6">
                            <div class="bg-green-900/65 ring-2 ring-green-950 rounded-lg p-6 max-w-sm w-full space-y-3 flex flex-col">
                                <div class="flex items-center">
                                    <img width="50" class="rounded-full" src="/stickers/man_free.jpeg" alt="">
                                    <p class="ml-3 font-lobster text-2xl">Марат Файзуллин</p>
                                </div>
                                <p class="font-rubick text-lg">
                                    Надёжный партнёр для оптовых закупок: всегда свежие товары, быстрая логистика и отличное отношение к клиенту!
                                </p>
                                <span class="text-yellow-500 font-lobster text-5xl text-right font-semibold">,,</span>
                            </div>
                        </div>


                    </div>
                    <!-- If we need pagination -->
                    <div class=" font-bold swiper-pagination"></div>

                    <!-- If we need navigation buttons -->
                    <div class="text-green-950 font-bold swiper-button-prev"></div>
                    <div class=" text-green-950 font-bold swiper-button-next"></div>

                    <!-- If we need scrollbar -->
                    <div class="swiper-scrollbar"></div>
                </div>
                <div id="dovolny" :class="{'animate-slide-left': dovolny}" class="  w-full  xs:text-center  xs:text-3xl lg:text-end lg:pr-24 md:text-5xl pb-2 lg:text-7xl font-lobster ">
                    И остались <span class="text-yellow-500">довольны</span>
                </div>
                </div>
            </section>

            <!--   Ynd Map         -->
            <section id="section-4" class="relative scroll-mt-20 z-40 flex items-center justify-center  text-white font-lobster ">

                <div id="mapPage" class=" absolute z-0  w-[30dvw]   h-[30dvw] rounded-[100%] top-0 -left-[30dvw] bg-green-950 "></div>
                <div class="flex flex-col items-center z-0 space-y-12 ">
                    <span class="text-white xs:-bottom-3/4 lg:-bottom-[36%] text-center lg:-right-[45%] xs:text-3xl xs:w-11/12 md:w-full md:text-5xl lg:text-7xl font-rubick font-semibold  py-10 ">
                        Как до нас добраться?
                    </span>
                    <div id="map" v-if="!layout.isMobile" style="width: 800px; height: 500px"></div>
                    <div id="map" class="items-center" v-else  style=" width: 80dvw; height: 80dvw"></div>
                </div>
            </section>
        </div>
    </main>
    <footer class="mt-[20rem]">
        <FooterComponent></FooterComponent>
    </footer>

</template>
