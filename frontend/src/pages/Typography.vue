<template>
    <div class="row">
        <div class="col-md-12">
            <card v-if="post">
                <template slot="header">
                    <h4 class="card-title">
                        {{ post.title }}
                    </h4>
                    <p class="card-category">
                        {{ publicationDate }}
                    </p>
                </template>
                <div>
                    <h5>{{ post.description }}</h5>
                </div>
            </card>
        </div>
    </div>
</template>
<script>
import SurveyComponent from "../components/SurveyComponent.vue";
import axios from "axios";
import moment from "moment";

export default {
    components: { SurveyComponent },

    data() {
        return {
            post: null,
        };
    },

    mounted() {
        const id = this.$route.params.id;
        this.getPost(id);
    },

    methods: {
        async getPost(id) {
            await axios
                .get("/api/posts/" + id, {
                    headers: this.$store.getters["headers"],
                })
                .then(response => response.data)
                .then((data) => {
                    console.log(data);
                    this.post = data;
                });
        },
    },

    computed: {
        publicationDate() {
            if (this.post) {
                return moment(this.post.publication_date).format(
                    "Do MMM YY, h:mm:ss a"
                );
            } else {
                return null;
            }
        },
    },
};
</script>
<style>
</style>
