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
        isAuthenticated: (state) => !!state.user,
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
        async logout({}) {
            localStorage.removeItem("token");
            this.commit("updateUser", {});
        },
        async getPosts({ state }) {
            await new APIService().getPosts().then((posts) => {
                posts.data.forEach((doc) => {
                    if (!state.blogPosts.some((post) => post.id === doc.id)) {
                        const data = {
                            id: doc.id,
                            title: doc.title,
                            description: doc.description,
                            publication_date: doc.publication_date,
                            user_id: doc.user_id,
                        };
                        state.blogPosts.push(data);
                    }
                });
            });
            state.postLoaded = true;
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
        async LogOut({ commit }) {
            let user = null;
            localStorage.removeItem('token');
            commit("logout", user);
        },

        // async updatePost({ commit, dispatch }, payload) {
        //     commit("filterBlogPost", payload);
        //     await dispatch("getPost");
        // },
        // async deletePost({ commit }, payload) {
        //     const getPost = await db.collection("blogPosts").doc(payload);
        //     await getPost.delete();
        //     commit("filterBlogPost", payload);
        // },
        // async updateUserSettings({ commit, state }) {
        //     const dataBase = await db.collection("users").doc(state.profileId);
        //     await dataBase.update({
        //         firstName: state.profileFirstName,
        //         lastName: state.profileLastName,
        //         username: state.profileUsername,
        //     });
        //     commit("setProfileInitials");
        // },
    },
    modules: {},
});
