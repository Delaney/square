/*!

 =========================================================
 * Vue Paper Dashboard - v2.0.0
 =========================================================

 * Product Page: http://www.creative-tim.com/product/paper-dashboard
 * Copyright 2019 Creative Tim (http://www.creative-tim.com)
 * Licensed under MIT (https://github.com/creativetimofficial/paper-dashboard/blob/master/LICENSE.md)

 =========================================================

 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

 */
import Vue from "vue";
import App from "./App";
import router from "./router/index";
import vuetify from "@/plugins/vuetify"; // path to vuetify export
import PaperDashboard from "./plugins/paperDashboard";
import "vue-notifyjs/themes/default.css";
import store from "./store/index";
import axios from "axios";
import VModal from 'vue-js-modal'

Vue.use(PaperDashboard);
Vue.use(VModal);

axios.defaults.baseURL = "http://localhost";
axios.interceptors.response.use(undefined, function (error) {
    if (error) {
        const originalRequest = error.config;
        if (error.response.status === 401 && !originalRequest._retry) {
            originalRequest._retry = true;
            store.dispatch("logout");
            if (window.location.pathname != "/login") {
                return router.push("/login");
            }
        }
    }
});

/* eslint-disable no-new */
new Vue({
    router,
    vuetify,
    store,
    render: (h) => h(App),
}).$mount("#app");
