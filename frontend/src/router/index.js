import Vue from "vue";
import VueRouter from "vue-router";
import routes from "./routes";
import store from "../store/index";
Vue.use(VueRouter);

// configure router
const router = new VueRouter({
    mode: 'history',
    routes, // short for routes: routes
    linkActiveClass: "active",
});

router.beforeEach((to, from, next) => {
    const loggedIn = localStorage.getItem("token");
    if (to.matched.some((record) => record.meta.requiresAuth)) {
        if (loggedIn) {
            return next();
        }
        return next("/login");
    } else {
        next();
    }
});

router.beforeEach((to, from, next) => {
    const loggedIn = localStorage.getItem("token");
    if (to.matched.some((record) => record.meta.guest)) {
        if (loggedIn) {
            return next("/dashboard");
        }
        return next();
    } else {
        next();
    }
});

export default router;
