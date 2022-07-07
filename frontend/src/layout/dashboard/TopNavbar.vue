<template>
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">{{ routeName }}</a>

            <button
                class="navbar-toggler navbar-burger"
                type="button"
                @click="toggleSidebar"
                :aria-expanded="$sidebar.showSidebar"
                aria-label="Toggle navigation"
            >
                <span class="navbar-toggler-bar"></span>
                <span class="navbar-toggler-bar"></span>
                <span class="navbar-toggler-bar"></span>
            </button>

            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ml-auto" v-if="isLoggedIn">
                    <li class="nav-item">
                        <a @click="logout" class="nav-link">
                            <p>Logout</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <router-link to="/dashboard" class="nav-link">
                            <p>Dashboard</p>
                        </router-link>
                    </li>
                </ul>

                <ul class="navbar-nav ml-auto" v-else>
                    <li class="nav-item">
                        <router-link to="/register" class="nav-link">
                            <p>Register</p>
                        </router-link>
                    </li>

                    <li class="nav-item">
                        <router-link to="/login" class="nav-link">
                            <p>Login</p>
                        </router-link>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>
<script>
export default {
    computed: {
        routeName() {
            const { name } = this.$route;
            return "";
            // return this.capitalizeFirstLetter(name);
        },
        isLoggedIn() {
            return this.$store.getters.isAuthenticated;
        },
    },
    data() {
        return {
            activeNotifications: false,
        };
    },
    methods: {
        capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        },
        toggleNotificationDropDown() {
            this.activeNotifications = !this.activeNotifications;
        },
        closeDropDown() {
            this.activeNotifications = false;
        },
        toggleSidebar() {
            this.$sidebar.displaySidebar(!this.$sidebar.showSidebar);
        },
        hideSidebar() {
            this.$sidebar.displaySidebar(false);
        },
        async logout() {
            await this.$store.dispatch("LogOut");
            this.$router.push("/login");
        },
    },
};
</script>
<style>
</style>
