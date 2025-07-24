<script setup>

import {Link, router, useForm} from "@inertiajs/vue3";

import PhonePartLayout from "@/Components/PhonePartLayout.vue";
import {useLayoutStore} from "@/stores/layout.js";
import {useUserStore} from "@/stores/user.js";


const layout = useLayoutStore()
const user = useUserStore()

const logout = () => {

    router.visit(route('logout'), {
        onFinish: () => user.setUser(false)

    });
};
</script>

<template>

    <div class="fixed z-50      ">
        <PhonePartLayout></PhonePartLayout>
        <header v-if="!layout.isMobile" class="flex fixed z-50 flex-row  w-full  h-auto   font-rubick text-xl  items-center py-3 lg:grid-cols-3">
            <nav class="mx-3 text-white font-medium flex flex-1 pt-2 justify-between pr-3">
                <div class="pt-2">
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
                </template>
                </div>
            </nav>
        </header>

            <slot />


    </div>

</template>

<style scoped>

</style>
