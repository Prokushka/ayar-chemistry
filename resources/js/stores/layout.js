import { defineStore } from 'pinia'
import {computed, onBeforeUnmount, onMounted, ref} from "vue";

export const useLayoutStore = defineStore('layout', () => {


    // Обработчик изменения размера окна
    const handleResize = () => {
        windowWidth.value = window.innerWidth
    }

    const windowWidth = ref(window.innerWidth)
    const isMobile = computed(() => windowWidth.value < 1024)

    onMounted(() => {
        window.addEventListener('resize', handleResize)

    })

    onBeforeUnmount(() => {
        window.removeEventListener('resize', handleResize)
    })

    return { windowWidth, isMobile }
})
