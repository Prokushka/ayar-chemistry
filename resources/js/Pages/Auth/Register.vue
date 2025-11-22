<script setup>
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import MainLayout from "@/Layouts/MainLayout.vue";
import {useUserStore} from "@/stores/user.js";

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    phone: ''
});
const user = useUserStore()
const submit = () => {
    form.post(route('register'), {
        onFinish: function () {
            user.setUser(true)
            return form.reset('password','password_confirmation')
        },
    });
};
function passwordToggle(){
    let pass = document.getElementById('password')
    pass.type === 'text' ? pass.type = 'password' : pass.type = 'text'
}
function passwordConfirmToggle(){
    let pass = document.getElementById('password_confirmation')
    pass.type === 'text' ? pass.type = 'password' : pass.type = 'text'
}
</script>

<template>
    <MainLayout></MainLayout>
    <GuestLayout>
        <Head title="Register" />

        <form class="pb-12" @submit.prevent="submit">
            <div>
                <InputLabel for="name" value="Ваше имя" />

                <TextInput
                    id="name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.name"
                    required
                    autofocus
                    autocomplete="name"
                />

                <InputError class="mt-2" :message="form.errors.name" />
            </div>

            <div class="mt-4">
                <InputLabel for="email" value="E-mail" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>
            <div class="mt-4">
                <InputLabel for="phone" value="Телефон" />

                <TextInput
                    id="phone"
                    type="tel"
                    class="mt-1 block w-full"
                    v-model="form.phone"
                    required
                    autocomplete="tel"
                    aria-placeholder="+7 (___) ___-__-__"
                />

                <InputError class="mt-2" :message="form.errors.phone" />
            </div>

            <div class="mt-4 relative">
                <InputLabel for="password" value="Пароль" />

                <TextInput
                    id="password"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password"
                    required
                    autocomplete="new-password"
                />
                <div @click.prevent="passwordToggle" class="absolute right-3 text-xl text-gray-400 bottom-1.5 "><i class="ri-eye-line"></i></div>
                <InputError class="mt-2" :message="form.errors.password" />
            </div>

            <div class="mt-4 relative">
                <InputLabel
                    for="password_confirmation"
                    value="Повторите пароль"
                />

                <TextInput
                    id="password_confirmation"
                    type="password"
                    class="mt-1 block w-full"
                    v-model="form.password_confirmation"
                    required
                    autocomplete="new-password"
                />
                <div @click.prevent="passwordConfirmToggle" class="absolute right-3 text-xl text-gray-400 bottom-1.5 "><i class="ri-eye-line"></i></div>
                <InputError
                    class="mt-2"
                    :message="form.errors.password_confirmation"
                />
            </div>

            <div class="mt-4 flex items-center justify-end">
                <Link
                    :href="route('login')"
                    class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                >
                    Уже зарегистрированы?
                </Link>

                <PrimaryButton
                    class="ms-4"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Register
                </PrimaryButton>
            </div>
        </form>
    </GuestLayout>
</template>
