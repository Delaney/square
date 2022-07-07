<template>
    <div>
        <!--Stats cards-->
        <div style="display: flex; justify-content: space-between">
            <select
                name="sort"
                id=""
                style="-webkit-appearance: auto; margin-bottom: 20px"
                v-model="sort"
            >
                <option value="desc">Latest</option>
                <option value="asc">Earliest</option>
            </select>

            <button class="button" @click="toggleModal">Create Post</button>
        </div>
        <div class="row">
            <div
                class="col-md-6 col-xl-3"
                v-for="post in postData"
                :key="post.title"
            >
                <stats-card>
                    <div class="" slot="content">
                        <p>{{ post.date }}</p>
                        {{ post.title }}
                    </div>
                    <div class="stats" slot="footer">
                        {{ post.description }}
                    </div>
                </stats-card>
            </div>
        </div>

        <modal name="create-post" height="350">
            <div style="padding: 20px 50px; background: #fdc4c4">
                <h4>New Post</h4>
                <fg-input
                    type="text"
                    label="Title"
                    placeholder="Enter post title"
                    v-model="post.title"
                >
                </fg-input>

                <fg-input
                    type="text"
                    label="Description"
                    placeholder="Enter post description"
                    v-model="post.description"
                >
                </fg-input>

                <div class="text-center">
                    <button
                        class="btn-round btn-info btn-lg"
                        @click="createPost()"
                    >
                        Create Post
                    </button>
                </div>
            </div>
        </modal>
    </div>
</template>
<script>
import { PaperTable } from "@/components";
import { StatsCard } from "@/components/index";
import axios from "axios";
import moment from "moment";

const tableColumns = ["Id", "Title", "Description", "Publication_Date"];

export default {
    components: {
        StatsCard,
        PaperTable,
    },
    data() {
        return {
            tableColumns,
            posts: [],
            sort: "desc",
            modalShow: false,
            post: {
                title: "",
                description: "",
            },
        };
    },
    mounted() {
        this.getPosts();
    },
    methods: {
        async getPosts() {
            await axios
                .get("/api/user/posts", {
                    headers: this.$store.getters["headers"],
                })
                .then((response) => response.data)
                .then((data) => (this.posts = data));
        },
        sortPosts(type) {
            let posts = this.posts.map((obj) => {
                return { ...obj, sortDate: new Date(obj.publication_date) };
            });

            if (type == "desc") {
                const sortedDesc = [...posts].sort(
                    (a, b) => Number(b.sortDate) - Number(a.sortDate)
                );

                this.posts = sortedDesc;
            } else {
                const sortedAsc = [...posts].sort(
                    (a, b) => Number(a.sortDate) - Number(b.sortDate)
                );

                this.posts = sortedAsc;
            }
        },
        showModal() {
            this.$modal.show("create-post");
        },
        hideModal() {
            this.$modal.hide("create-post");
        },
        toggleModal() {
            if (this.modalShow) this.hideModal();
            else this.showModal();
        },
        createPost() {
            const { title, description } = this.post;
            if (title && description) {
                axios.post(
                    "/api/post",
                    {
                        title,
                        description,
                    },
                    {
                        headers: this.$store.getters["headers"],
                    }
                )
                .then(response => response.data)
                .then((data) => {
                    this.getPosts();
                    this.post = {
                        title: "",
                        description: ""
                    };
                    this.hideModal();
                });
            }
        },
    },

    computed: {
        postData() {
            return this.posts.map((o) => {
                o.date = moment(o.publication_date).format(
                    "Do MMM YY, h:mm:ss a"
                );
                return o;
            });
        },
    },

    watch: {
        sort(val) {
            this.sortPosts(val);
        },
    },
};
</script>
<style>
</style>
