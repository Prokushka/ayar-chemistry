<script setup>
import {Head, Link, useForm} from '@inertiajs/vue3';
import {computed, onBeforeUnmount, onMounted, ref} from "vue";
import {animate, createTimeline, onScroll} from "animejs";
import MainLayout from "@/Layouts/MainLayout.vue";
import {useLayoutStore} from "@/stores/layout.js";
import FooterComponent from "@/Components/FooterComponent.vue";
import {useUserStore} from "@/stores/user.js";


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
const cleaningLogo = ref(false)
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
const userStore = useUserStore()

const isAuth = computed(() => userStore.isAuth)


</script>

<template>
    <Head title="Welcome" />
    <MainLayout/>

        <div class=" bg-gradient-to-br from-green-950/95 via-green-900 to-green-950/90 xs:space-y-[155px] lg:space-y-[240px] overflow-hidden relative">
            <div  class="absolute overflow-hidden z-10 -right-[100px] -top-[20dvh] size-[66dvh] bg-green-950/90 animate-up-size rounded-[100%]"></div>
<!--    start        -->
            <div class="relative z-10 flex items-center justify-center text-white">
                <div class="flex flex-col items-center">
                    <img class="py-[2%] w-5/6 lg:w-2/5" src="/stickers/main_text.png" />
                    <p class="animate-move-y mt-12 -translate-y-2 leading-relaxed lg:text-[28px] font-comic w-11/12 md:w-3/4 text-center text-gray-100 tracking-normal">
                        Наша оптовая компания предлагает широкий ассортимент бытовой химии по выгодным ценам.
                        Мы гарантируем качественные товары, быструю доставку и индивидуальный подход к каждому клиенту.
                        Выбирайте надёжного партнёра для стабильных поставок и экономьте с нами!
                    </p>
                </div>
            </div>
<!--    classic containers        -->
            <div class="relative z-10 flex flex-col items-center justify-center space-y-12  text-white font-lobster ">
                <div id="text2" :class="{'animate-text-up': firstAnimation}" class="xs:text-[40px] font-rubick font-semibold xs:text-4xl w-11/12 text-center md:text-5xl lg:text-7xl xs:pb-12 xs:pb-16 md:pb-24 lg:pb-32 items-center lg:text-center">
                    Почему стоит <p v-if="layout.isMobile" >брать у наc?</p><span v-if="!layout.isMobile" >брать у наc?</span>
                </div>
                <img :class="{'animate-roll-right ': firstAnimation}" id="cleaning_logo" @click.prevent="rotateFirstLogo($event)" src="/stickers/cleaning_logo.png" class="absolute xs:hidden lg:block overflow-hidden xs:opacity-25 lg:opacity-75 z-30 xs:-left-[38%] lg:-left-[32%]  xs:top-[15%] md:top-[20%]  rounded-[100%]">
                <img id="cleaning_more_logo" src="/stickers/more_cleaning_logo.png" :class="{'animate-roll-left': secondAnimation}" @click.prevent="rotateSecondLogo($event)" class=" overflow-hidden absolute xs:hidden lg:block xs:opacity-25 z-50 lg:opacity-75 xs:top-[50%] lg:top-[35%] xs:-right-[48%] lg:-right-[34%] bottom-[35%]  rounded-[100%]">
                <div class="grid grid-rows-3 gap-y-10 justify-items-center  ">
                    <div id="container-1" :class="{'animate-slide-left': container1}" class="bg-green-950 xs:w-11/12 md:w-5/6 lg:w-1/3 xs:p-4 xs:p-5 xs:text-center lg:p-10 rounded-lg ">
                        <p class="xs:text-2xl  lg:text-4xl text-center mb-7 font-semibold font-lobster">Доступные цены и выгодные условия поставки</p>
                        <span class="xs:text-md lg:text-xl">
                            Крупные оптовые компании могут предлагать конкурентоспособные цены
                            за счёт масштабов закупок и собственной сырьевой базы,
                            а также предоставлять гибкие условия сотрудничества для клиентов
                        </span>
                    </div>
                    <div id="container-2" :class="{'animate-slide-right': container2}" class="bg-green-950 xs:w-11/12 md:w-5/6 lg:w-1/3 w-1/3 xs:p-4 xs:p-5 xs:text-center lg:p-10 rounded-lg ">
                        <p class="xs:text-2xl  lg:text-4xl text-center mb-7 font-semibold font-lobster">Гарантия качества продукции</p>
                        <span class="xs:text-md lg:text-xl">
                            Компании, работающие напрямую с производителями или имеющие собственные лаборатории контроля,
                            могут обеспечить строгое соблюдение стандартов, безопасность и стабильное качество товаров, что особенно важно для оптовых покупателей
                        </span>
                    </div>
                    <div id="container-3" :class="{'animate-slide-left': container3}" class="bg-green-950 xs:w-11/12 md:w-5/6 lg:w-1/3 w-1/3 xs:p-4 xs:p-5 xs:text-center lg:p-10 rounded-lg ">
                        <p class="xs:text-2xl  lg:text-4xl text-center mb-7 font-semibold font-lobster">Широкий ассортимент и логистические возможности</p>
                        <span class="xs:text-md lg:text-xl">
                            Оптовые продавцы бытовой химии обычно предлагают продукцию сразу нескольких производителей,
                            что позволяет удовлетворить разнообразные потребности клиентов,
                            а развитая логистическая сеть обеспечивает быструю и надёжную доставку даже в удалённые регионы

                        </span>
                    </div>
                </div>
            </div>

            <div class="flex-row justify-center items-center" v-if="products.length > 3" >
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
                                <span class="text-white text-2xl font-semibold mr-3">{{products[0].size}} </span>
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
                                <span class="text-white text-2xl font-semibold mr-3">{{products[1].size}} </span>
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
                               от <span class="text-yellow-500 text-[1.2em]"> {{ products[2].min_price }}</span> ₽
                            </span>
                            <div >
                                <span class="text-white text-2xl font-semibold mr-3">{{products[2].size}} </span>
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
                                <span class="text-white text-2xl font-semibold mr-3">{{products[3].size}} </span>
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
                text-white lg:text-4xl font-rubick font-semibold p-6 mt-12 xs:text-xl md:text-3xl w-auto ring-2 text-center  place-self-center ring-yellow-500 rounded-full">
                    <Link :href="route('product.index')" >
                        Посмотреть все товары
                    </Link>
                </div>

            </div>
            <div id="text1" class="z-40 bg-green-950 text-white font-lobster py-10 space-y-6  ">

                <div  :class="{'animate-slide-right': doverie}" class=" lg:pl-24 xs:text-center  w-full  xs:text-center lg:text-start
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
                                <span class="text-yellow-500 font-lobster text-5xl text-right">,,</span>
                            </div> </div>
                        <div class="swiper-slide flex justify-center space-x-6"><div class="bg-green-900/65 ring-2 ring-green-950 rounded-lg p-6 max-w-sm w-full space-y-3 flex flex-col">
                            <div class="flex items-center">
                                <img width="50" class="rounded-full" src="/stickers/man_client.jpeg" alt="">
                                <p class="ml-3 font-lobster text-2xl">Ринат Гимадиев</p>
                            </div>
                            <p class="font-rubick text-lg">
                                Заказывали бытовую химию крупным оптом для торговой сети, всё пришло в срок, качество продукции отличное, менеджеры всегда на связи.
                            </p>
                            <span class="text-yellow-500 font-lobster text-5xl text-right">,,</span>
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
                                <span class="text-yellow-500 font-lobster text-5xl text-right">,,</span>
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
                                <span class="text-yellow-500 font-lobster text-5xl text-right">,,</span>
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
                                <span class="text-yellow-500 font-lobster text-5xl text-right">,,</span>
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
                                <span class="text-yellow-500 font-lobster text-5xl text-right">,,</span>
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
                                <span class="text-yellow-500 font-lobster text-5xl text-right">,,</span>
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
                                <span class="text-yellow-500 font-lobster text-5xl text-right">,,</span>
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
                                <span class="text-yellow-500 font-lobster text-5xl text-right">,,</span>
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

            <!--   Ynd Map         -->
            <div class="relative z-40 flex items-center justify-center  text-white font-lobster ">

                <div id="mapPage" class=" absolute z-0  w-[30dvw]   h-[30dvw] rounded-[100%] top-0 -left-[30dvw] bg-green-950 "></div>
                <div class="flex flex-col items-center z-0 space-y-12 ">
                    <span class="text-white xs:-bottom-3/4 lg:-bottom-[36%] text-center lg:-right-[45%] xs:text-3xl xs:w-11/12 md:w-full md:text-5xl lg:text-7xl font-rubick font-semibold  py-10 ">
                        Как до нас добраться?
                    </span>
                    <div id="map" v-if="!layout.isMobile" style="width: 800px; height: 500px"></div>
                    <div id="map" class="items-center" v-else  style=" width: 80dvw; height: 80dvw"></div>
                </div>
            </div>
            <!-- REGISTER           -->
            <div  class="pb-8 relative z-40 flex items-center justify-center  text-white font-lobster ">

                <div v-if="isAuth == null || isAuth === false" class="flex flex-col items-center space-y-12 text-gray-800 ">
                    <span class="text-white xs:text-3xl w-11/12 md:text-5xl lg:text-7xl text-center font-lobster  py-10 "><span class="text-yellow-600">Зарегистрируйтесь </span> - и мы с вами свяжемся</span>
                    <div class="items-center text-white text-lobster xs:text-2xl xs:text-3xl md:text-4xl text-center flex flex-col space-y-8 text-center xs:p-8 xs:p-10 md:p-16 ">
                        <Link :href="route('register')">


                            <button class="button-yellow"> Регистрация </button>
                        </Link>
                        <Link :href="route('login')">
                            <button class="button-yellow" >Вход</button>
                        </Link>
                    </div>

                </div>
            </div>
            <!--    footer        -->
            <FooterComponent></FooterComponent>
        </div>
</template>
