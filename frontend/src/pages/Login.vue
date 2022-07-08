<template>
    <div class="login">
        <div>
            <form @submit.prevent="submit" style="width: 500px; margin: 200px auto; border: black">
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
        submit() {
            const user = new FormData();
            user.append("email", this.form.email);
            user.append("password", this.form.password);
            try {
                this.$store.dispatch("login", user)
                    .then(() => {
                        setTimeout(() => {
                            this.$router.push("/dashboard");
                            this.showError = false;
                        }, 1000);
                    });
            } catch (error) {
                this.showError = true;
            }
        },
    },
};
</script>
