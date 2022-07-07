import DashboardLayout from "@/layout/dashboard/DashboardLayout.vue";
// GeneralViews
import NotFound from "@/pages/NotFoundPage.vue";

// Admin pages
import Dashboard from "@/pages/Dashboard.vue";
import Login from "@/pages/Login.vue";
import UserProfile from "@/pages/UserProfile.vue";
import Notifications from "@/pages/Notifications.vue";
import Icons from "@/pages/Icons.vue";
import Maps from "@/pages/Maps.vue";
import Typography from "@/pages/Typography.vue";
import WorkSpace from "@/pages/WorkSpace.vue";

const routes = [
    {
        path: "/",
        component: DashboardLayout,
        children: [
            {
                path: "",
                name: "dashboard",
                component: Dashboard,
            },

            {
                path: "stats",
                name: "stats",
                component: UserProfile,
            },
            {
                path: "notifications",
                name: "notifications",
                component: Notifications,
            },
            {
                path: "icons",
                name: "icons",
                component: Icons,
            },
            {
              path: "maps",
              name: "maps",
              component: Maps
            },
            {
                path: "questionnaire",
                name: "questionnaire",
                component: Typography,
            },
            {
                path: "table-list",
                name: "table-list",
                component: WorkSpace,
            },
        ],
    },
    {
        path: "/register",
        name: "register",
        component: Dashboard,
        meta: { guest: true },
    },
    {
        path: "/login",
        name: "login",
        component: Login,
        meta: { guest: true },
    },
    {
        path: "/posts",
        name: "posts",
        component: DashboardLayout,
        meta: {requiresAuth: true},
        children: [
            {
                path: "",
                name: "dashboard",
                component: WorkSpace,
            },
        ]
    },
    { path: "*", component: NotFound },
];

/**
 * Asynchronously load view (Webpack Lazy loading compatible)
 * The specified component must be inside the Views folder
 * @param  {string} name  the filename (basename) of the view to load.
function view(name) {
   var res= require('../components/Dashboard/Views/' + name + '.vue');
   return res;
};**/

export default routes;
