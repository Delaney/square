import axios from "axios";
import Vue from "vue";
import Vuex from "vuex";

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        blogPosts: [],
        user: null,
    },
    getters: {
        blogPostsFeed(state) {
            return state.blogPosts.slice(0, 2);
        },
        blogPostsCards(state) {
            return state.blogPosts.slice(2, 6);
        },
        isAuthenticated: (state) => !!localStorage.getItem("token"),
        headers: () => {
            const token = localStorage.getItem("token");
            return {"Authorization" : `Bearer ${token}`};
        }
    },
    mutations: {
        setUser(state, payload) {
            state.user = payload;
        },
    },
    actions: {
        async getCurrentUser({ commit }) {
            const token = localStorage.getItem("token");

            if (token) {
                axios
                    .get("/api/user")
                    .then((response) => response.data)
                    .then((data) => {
                        console.log(data);
                        commit("updateUser", data);
                    });
            }
        },
        async register({ commit }, User) {
            await axios
                .post("api/register", User)
                .then((response) => response.data)
                .then((data) => {
                    commit("setUser", data);
                    localStorage.setItem('token', data.access_token);
                    return data;
                })
                .catch((error) => error);
        },
        async login({ commit }, User) {
            await axios
                .post("api/login", User)
                .then((response) => response.data)
                .then((data) => {
                    commit("setUser", data);
                    localStorage.setItem('token', data.access_token);
                    return data;
                })
                .catch((error) => error);
        },
        async logout({ commit }) {
            let user = null;
            localStorage.removeItem('token');
            commit("setUser", user);
        },
    },
    modules: {},
});
