<template>
    <div class="login">
        <div class="text-center">
            <h3>REGISTER</h3>
            <form
                @submit.prevent="submit"
                style="width: 500px; margin: 0 auto 200px; border: black"
            >
                <div class="row" style="margin-top: 30px">
                    <label class="col-4" for="name">Name:</label>
                    <input
                        class="col-8"
                        type="text"
                        name="name"
                        v-model="form.name"
                    />
                </div>
                <div class="row" style="margin-top: 30px">
                    <label class="col-4" for="username">Username:</label>
                    <input
                        class="col-8"
                        type="text"
                        name="username"
                        v-model="form.username"
                    />
                </div>
                <div class="row" style="margin-top: 30px">
                    <label class="col-4" for="email">Email:</label>
                    <input
                        class="col-8"
                        type="email"
                        name="email"
                        v-model="form.email"
                    />
                </div>
                <div class="row" style="margin-top: 30px">
                    <label class="col-4" for="password">Password:</label>
                    <input
                        class="col-8"
                        type="password"
                        name="password"
                        v-model="form.password"
                    />
                </div>
                <div class="row">
                    <button
                        style="margin-top: 50px"
                        type="submit"
                        class="btn-info btn-lg btn-round btn-block"
                    >
                        Submit
                    </button>
                </div>
            </form>
            <p v-if="showError" id="error">Username or Password is incorrect</p>
        </div>
    </div>
</template>

<script>
import axios from "axios";
export default {
    name: "Login",
    components: {},
    data() {
        return {
            form: {
                name: "",
                username: "",
                email: "",
                password: "",
            },
            showError: false,
        };
    },
    methods: {
        submit() {
            const user = new FormData();
            user.append("email", this.form.email);
            user.append("password", this.form.password);
            user.append("name", this.form.name);
            user.append("username", this.form.username);
            try {
                axios
                    .post("api/register", user)
                    .then((data) => {
                        if (data) {
                            this.$store.commit("setUser", data.data);
                            localStorage.setItem('token', data.data.access_token);
                            this.$router.push("/dashboard");
                            this.showError = false;
                        }
                    });
            } catch (error) {
                this.showError = true;
            }
        },
    },
};
</script>
