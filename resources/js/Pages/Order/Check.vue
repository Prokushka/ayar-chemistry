<script setup>
import {Head, Link, useForm} from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";



    const props = defineProps({
        order: {
            type: Object
        }
    })
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
        title: "Действительно хотите удалить товар?",
        text: "Вы не сможете его вернуть!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Да, хочу",
        cancelButtonText: "Отмена",
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            form.delete(route('order.product.delete', id))
            swalWithBootstrapButtons.fire({
                title: "Товар удалён!",
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

function orderComplete(id){
        form.id = id
    form.post(route('order.create', id))
}
</script>

<template>
    <Head title="Order-Check" />


    <div v-if="order.products"  class="  z-0 text-white z-50  text-center">
        <MainLayout/>
        <div  class="container place-self-center  py-24 rounded-md   md:w-11/12   xs:w-full   items-center justify-items-center  ">

            <h1 class="text-4xl  font-bold mb-6">Ваш заказ №{{order.id}}</h1>
            <button v-if="order.products.length > 0 && order.status === 'Новый'" @click.prevent="orderComplete(order.id)" class="place-self-center bg-yellow-600 text-white font-comic font-semibold text-center py-2 px-4
                ring-2 ring-white rounded-lg my-12  ">Сформировать Заказ
            </button>
            <div class="flex flex-col items-center justify-items-center space-y-8 w-full ">


                <div v-for="product in order.products" class="flex relative min-h-[200px]  flex-row xs:w-11/12 bg-green-950 rounded-md ring-1 ring-yellow-500 items-center lg:w-3/5 ">
                    <div v-if="order.status === 'Новый'" @click.prevent="deleteOrder(product.pivot.id)" class="absolute -top-3 -right-3" >
                        <button class="bg-red-600 py-0.5 px-1 rounded-lg border hover:border-red-500 text-lg hover:text-red-500 hover:bg-gray-200
                         border-white transform duration-500 ease-out"> <i class="ri-close-fill"></i>
                        </button>
                    </div>
                    <div class=" w-[50%]">
                        <img class="w-2/3  " :src="`/storage/${product.image_url}`" alt="">
                    </div>
                    <div class=" w-[50%] flex-col space-y-5 justify-between pr-4 ">
                        <div class="text-white  md:text-lg lg:text-xl xl:text-2xl line-clamp-3 text-unwrap text-top font-comic font-medium">
                            {{product.title}}
                        </div>
                        <div class=" text-white xs:text-[15px] md:text-md lg:text-lg xl:text-xl  text-bottom font-comic font-medium">
                           <div>
                               Количество: <span class="text-yellow-500">{{product.pivot.quantity}}</span>
                           </div>
                            <div>
                                Стоимость: <span class="text-yellow-500">{{product.pivot.sale_price}}</span>
                            </div>
                            <div>
                                Итого: <span class="text-yellow-500">{{product.pivot.quantity * product.pivot.sale_price}}</span> руб.
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

</template>

<style scoped>

</style>
