import { defineStore } from 'pinia'

export const useUserStore = defineStore('User', {
    state: () => (
        {
            name: '',
            isAuth: false,

        }), //data
    getters: { // computed
       //
    },
    actions: { // method
        setUser(isAuth, name = ''){
            this.isAuth = isAuth
            this.name = name
        }
    },
    persist: true,
})
