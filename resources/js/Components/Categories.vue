<template>
    <div class="ml-4 mb-2">
        <div @click.prevent="categoryShow(category.slug)" >
        <div
            class="w-full text-lg flex flex-row justify-between items-center break-words whitespace-normal px-2 py-1 bg-green-700/40 rounded cursor-pointer hover:bg-green-700/60 transition"
            @click="toggle"
        >
            <div class="max-w-[80vw] break-words whitespace-normal text-white break-words whitespace-normal">
                {{ category.title }}
            </div>
            <div v-if="hasChildren">
                <i
                    :class="[
            'ri-arrow-down-s-line text-white transition-transform duration-200',
            expanded ? 'rotate-180' : ''
          ]"
                ></i>
            </div>
        </div>
        <div v-if="hasChildren && expanded" >
            <transition name="fade" mode="out-in">
                <div
                    v-if="hasChildren && expanded"
                    class="ml-2 border-l-2 border-yellow-500  mt-1 space-y-1"
                >
                    <Categories
                        v-for="child in category.children"
                        :key="child.id"
                        :category="child"
                    />
                </div>
            </transition>
        </div>


        </div>

    </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import Categories from "@/Components/Categories.vue";
import {router} from "@inertiajs/vue3";

const props = defineProps({
    category: Object
})

const expanded = ref(false)

const hasChildren = computed(() =>
    props.category.children && props.category.children.length > 0
)
function categoryShow(slug){
    if (!hasChildren.value){
        router.visit(route('product.category.show', slug))
    }

}
const toggle = () => {
    if (hasChildren.value) {
        expanded.value = !expanded.value
    }
}
</script>

<style scoped>
.fade-enter-active, .fade-leave-active {
    transition: all 0.3s ease;
}
.fade-enter-from, .fade-leave-to {
    opacity: 0;
    transform: translateY(-4px);
}
</style>
