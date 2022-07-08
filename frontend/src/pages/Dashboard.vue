<template>
    <div>
        <!--Stats cards-->
        <div>
            <select
                name="sort"
                id=""
                style="-webkit-appearance: auto; margin-bottom: 20px"
                v-model="sort"
            >
                <option value="desc">Latest</option>
                <option value="asc">Earliest</option>
            </select>
        </div>
        <div class="row">
            <div
                class="col-md-6 col-xl-3"
                v-for="post in postData"
                :key="post.id"
            >
                <router-link :to="{ path: 'post/' + post.id }">
                    <stats-card>
                        <div class="" slot="content">
                            <p>{{ post.date }}</p>
                            {{ post.title }}
                        </div>
                        <div class="stats" slot="footer">
                            {{ post.description }}
                        </div>
                    </stats-card>
                </router-link>
            </div>
        </div>
        <pagination
            :totalPages="totalPages"
            :perPage="perPage"
            :currentPage="currentPage"
            @pagechanged="onPageChange"
        />
    </div>
</template>
<script>
import { StatsCard } from "@/components/index";
import Pagination from "../components/Pagination.vue";
import axios from "axios";
import moment from "moment";

export default {
    components: {
        StatsCard,
        Pagination,
    },

    data() {
        return {
            posts: [],
            sort: "desc",
            perPage: 0,
            totalPages: 0,
            currentPage: 0,
        };
    },

    mounted() {
        this.getPosts();
    },

    methods: {
        buildQuery(page = this.currentPage) {
            const order = `order_by=${this.sort}`;
            const per_page = `per_page=${this.perPage}`;
            const pageQuery = `page=${page}`;
            return `${pageQuery}&${per_page}&${order}`;
        },
        getPosts(query) {
            axios
                .get("http://localhost/api/posts?" + query)
                .then((response) => response.data)
                .then((data) => {
                    this.posts = data.data;
                    this.currentPage = data.meta.current_page;
                    this.totalPages = data.meta.last_page;
                    this.perPage = data.meta.per_page;
                });
        },
        sortPosts(type) {
            // let posts = this.posts.map((obj) => {
            //     return { ...obj, sortDate: new Date(obj.publication_date) };
            // });

            // if (type == "desc") {
            //     const sortedDesc = [...posts].sort(
            //         (a, b) => Number(b.sortDate) - Number(a.sortDate)
            //     );

            //     this.posts = sortedDesc;
            // } else {
            //     const sortedAsc = [...posts].sort(
            //         (a, b) => Number(a.sortDate) - Number(b.sortDate)
            //     );

            //     this.posts = sortedAsc;
            // }

            this.getPosts(this.buildQuery());
        },

        onPageChange(page) {
            this.getPosts(this.buildQuery(page));
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
