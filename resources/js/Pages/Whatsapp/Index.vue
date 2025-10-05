<template>
    <div class="max-w-lg text-white mx-auto p-4">
        <h1 class="text-xl text-center font-bold mb-4">Рассылка XD XD XD XD :D :-D - Мустафа)))</h1>

        <form @submit.prevent="submit">

            <div class="mb-4">

                <textarea
                    v-model="form.message"
                    id="message"
                    class="w-full border text-gray-800 font-semibold rounded p-2"
                ></textarea>
                <div v-if="form.errors.message" class="text-red-600 text-sm mt-1">
                    {{ form.errors.message }}
                </div>
            </div>

            <!-- Кнопка -->
            <button
                type="submit"
                class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-blue-500"
                :disabled="form.processing"
            >
                {{ form.processing ? "Отправка..." : "Отправить" }}
            </button>
        </form>
    </div>
</template>

<script setup>
import { useForm } from "@inertiajs/vue3";

const form = useForm({
    message: "",
});

function submit() {
    form.post(route("broadcast"));
}
</script>
