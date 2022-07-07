<template>
    <div class="login">
        <div>
            <form @submit.prevent="submit">
                <div>
                    <label for="email">email:</label>
                    <input type="email" name="email" v-model="form.email" />
                </div>
                <div>
                    <label for="password">Password:</label>
                    <input
                        type="password"
                        name="password"
                        v-model="form.password"
                    />
                </div>
                <button type="submit">Submit</button>
            </form>
            <p v-if="showError" id="error">Username or Password is incorrect</p>
        </div>
    </div>
</template>

<script>
export default {
    name: "Login",
    components: {},
    data() {
        return {
            form: {
                email: "",
                password: "",
            },
            showError: false,
        };
    },
    methods: {
        async submit() {
            const user = new FormData();
            user.append("email", this.form.email);
            user.append("password", this.form.password);
            try {
                await this.$store.dispatch("login", user);
                this.$router.push("/posts");
                this.showError = false;
            } catch (error) {
                this.showError = true;
            }
        },
    },
};
</script>
