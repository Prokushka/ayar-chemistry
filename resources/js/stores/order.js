import { defineStore } from 'pinia'

export const useOrderStore = defineStore('Order', {
    state: () => (
        {
            id: 0,
            procent: 0,
            is_active: false,
            sale_price: 0,
            total_price: 0,
            min_value: 1
        }), //data
    getters: { // computed
        saleWithProcent: (state) => state.procent * sale_price,
    },
    actions: { // method
        setOrder(procent, salePrice, totalPrice, minValue){
            this.procent = Number(procent)
            this.sale_price = Number(salePrice)
            this.total_price = Number(totalPrice)
            this.min_value = Number(minValue)
        }
    },
    persist: true,
})
