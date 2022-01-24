import { createStore } from "vuex";

const store = createStore({
    state() {
        return {
            deleteModalObj: {
                deleteUrl: "",
                data: null,
                deleteItemIndex: -1,
                isDeleted: false,
                msg: '',
                success: ''
            },
            user: [],
            permission: null,
        };
    },
    mutations: {
        setDeleteModal(state, payload) {
            const deleteModalObj = {
                deleteUrl: "",
                data: null,
                deleteItemIndex: payload.deleteItemIndex,
                isDeleted: payload.isDeleted,
                msg: '',
                success: ''
            };
            state.deleteModalObj = deleteModalObj;
        },
        setDeleteModalObj(state, payload) {
            state.deleteModalObj = payload;
        },
        setLoggedInUser(state, payload) {
            state.user = payload;
        },
        setLoggedInUserPermission(state, payload) {
            state.permission = payload;
        },
    },
    actions: {
        // increment(context, payload) {
        //   context.commit('increment', payload);
        // },
    },
    getters: {
        getDeleteModalObj(state) {
            return state.deleteModalObj;
        },
        getLoggedInUser(state) {
            return state.user;
        },
        getLoggedInUserPermission(state) {
            return state.permission;
        },
    },
});

export default store;
