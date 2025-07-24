<script setup>

import {Head, Link, useForm} from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import {computed, onMounted, ref} from "vue";

import FooterComponent from "@/Components/FooterComponent.vue";



const props = defineProps({
    orders: {
        type: Object
    }
})

onMounted(() => console.log(props?.orders))

const form = useForm({
    id: 0
})

function deleteOrder(id) {
    form.id = id
const swalWithBootstrapButtons = Swal.mixin({
        customClass: {
            confirmButton: "bg-green-900",
            cancelButton: "bg-red-600"
        },
        buttonsStyling: true
    });
    swalWithBootstrapButtons.fire({
        title: "Действительно хотите удалить заказ?",
        text: "Вы не сможете его вернуть!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Да, хочу",
        cancelButtonText: "Отмена",
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            form.delete(route('order.delete', id))
            swalWithBootstrapButtons.fire({
                title: "Заказ удалён!",
                text: "Закажите что-нибудь другое!",
                icon: "success"
            });
        } else if (
            /* Read more about handling dismissals below */
            result.dismiss === Swal.DismissReason.cancel
        ) {
            swalWithBootstrapButtons.fire({
                title: "Отменено",
                icon: "error"
            });
        }
    });

}

</script>

<template>
    <Head title="Welcome" />


    <div class="z-10  text-white w-full h-full ">
        <MainLayout></MainLayout>
        <div v-if="orders.length > 0" class="container place-self-center  py-10 rounded-md   md:w-11/12 lg:w-2/5  xs:w-full   items-center justify-items-center  ">

            <h1 class="xs:text-4xl lg:text-5xl  font-bold my-24">Список заказов</h1>
            <div  class="container shadow-xl shadow-green-90/70 ring-2 ring-white bg-green-950/70 mx-auto">
                <div class="overflow-x-auto rounded-lg shadow">
                    <table class="w-full bg-green-950">
                        <thead>
                        <tr class=" text-left text-sm font-semibold ">
                            <th class="px-4 ring-white text-center ring-2 py-3">Номер</th>

                            <th class="px-4 ring-white text-center ring-2 py-3">Статус</th>
                            <th class="px-4 ring-white text-center ring-2 py-3">Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                        <!-- Пример строки заказа -->
                        <tr v-if="orders" v-for="order in orders" class="border-b ">
                            <td class="px-4 py-3 text-center font-mono">{{order.id}}</td>
                            <td class="px-4 text-center   py-3">
                                <span class="inline-block px-2 py-1  text-xs rounded bg-orange-500 text-white">{{ order.status }}</span>
                            </td>
                            <td class="px-4 flex justify-center py-3">

                                <Link v-if="order && order.id" :href="route('order.check',{id: order.id})">
                                    <button class="bg-yellow-500 py-2 px-3 rounded-lg border border-yellow-500 text-lg hover:text-yellow-500 hover:bg-gray-200
                         hover:border-white transform duration-500 ease-out"> <i class="ri-eye-fill"></i>
                                    </button>
                                </Link>

                                <div  @click.prevent="deleteOrder(order.id)" class="mx-3" v-if="order && order.id" >
                                    <button class="bg-red-600 py-2 px-3 rounded-lg border hover:border-red-500 text-lg hover:text-red-500 hover:bg-gray-200
                         border-white transform duration-500 ease-out"> <i class="ri-delete-bin-line"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
        <div v-else class="flex-col flex space-y-12 xs:text-2xl md:text-4xl lg:text-5xl font-lobster text-center py-44 font-medium">
            <span>У вас нет заказов</span>
            <div class="lg:w-2/5 xs:w-2/3 place-self-center md:w-3/4 h-1 rounded-md bg-white"></div>
            <span>Самое время их сделать!</span>

        </div>
    </div>

</template>

<style scoped>

</style>
