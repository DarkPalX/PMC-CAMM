import { def } from "@vue/shared";
import { createStore } from "vuex";

const store = createStore({
    state() {
        return {
            expDay: 0,
            firstLoad: true,
        };
    },
    mutations: {
        storeExpDay(state, n) {
            state.expDay = n;
        },
        updateFirstLoad(state,value) {
            state.firstLoad = false;
        },
    },
    actions: {
        storeExpDay({ commit }, n) {
            commit("storeExpDay", n);
        },
        updateFirstLoad({ commit }, value) {
            commit("updateFirstLoad",value);
        },
    },
    getters: {
        ExpDay: (state) => {
            return state.expDay;
        },
        FirstLoad: (state) => {
            return state.firstLoad;
        },
    },
});

export default store;
