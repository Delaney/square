<template>
    <div>
        <!--Stats cards-->
        <div>
            <select name="sort" id="" style="-webkit-appearance: auto; margin-bottom: 20px" v-model="sort">
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
                <router-link :to="{ path: 'post/' +  post.id }">
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
    </div>
</template>
<script>
import { StatsCard, ChartCard } from "@/components/index";
import axios from "axios";
import moment from "moment";

const getXindex = new Array(100).fill(0).map((val, index) => {
    if ((index + 1) % 10 === 0) {
        return index + 1;
    } else {
        return "";
    }
});
const getYindex = new Array(100).fill(48);
const getPuppeteer = new Array(100)
    .fill(0)
    .map((val, index) => (index > 58 ? 0 : 47));
const getScrapy = new Array(100)
    .fill(0)
    .map((val, index) => (index > 11 ? 0 : 47));
const getSelenium = new Array(100)
    .fill(0)
    .map((val, index) => (index > 38 ? 0 : 47));

export default {
    components: {
        StatsCard,
        ChartCard,
    },
    /**
     * Chart data used to render stats, charts. Should be replaced with server data
     */
    data() {
        return {
            posts: [],
            statsCards: [
                {
                    type: "success",
                    icon: "ti-medall",
                    title: "Scores",
                    value: "430",
                    footerText: "Updated now",
                    footerIcon: "ti-reload",
                },
                {
                    type: "warning",
                    icon: "ti-cup",
                    title: "Global Ranking",
                    value: "1,345",
                    footerText: "Last day",
                    footerIcon: "ti-calendar",
                },
                {
                    type: "info",
                    icon: "ti-shield",
                    title: "Reported",
                    value: "423",
                    footerText: "In the last hour",
                    footerIcon: "ti-timer",
                },
                {
                    type: "danger",
                    icon: "ti-heart",
                    title: "Be liked",
                    value: "+255",
                    footerText: "Updated now",
                    footerIcon: "ti-reload",
                },
            ],
            sort: "desc"
        };
    },

    mounted() {
        axios.get("http://localhost/api/posts")
            .then(response => response.data)
            .then(data => {
                this.posts = data.data;
            });
    },

    methods: {
        sortPosts(type) {
            let posts = this.posts.map(obj => {
                return {...obj, sortDate: new Date(obj.publication_date)};
            });

            if (type == 'desc') {
                const sortedDesc = [...posts].sort(
                    (a, b) => Number(b.sortDate) - Number(a.sortDate),
                );

                this.posts = sortedDesc;

            } else {
                const sortedAsc = [...posts].sort(
                    (a, b) => Number(a.sortDate) - Number(b.sortDate),
                );

                this.posts = sortedAsc;
            }
        }
    },

    computed: {
        postData() {
            return this.posts.map(o => {
                o.date = moment(o.publication_date).format("Do MMM YY, h:mm:ss a");
                return o;
            });
        }
    },

    watch: {
        sort(val) {
            this.sortPosts(val);
        }
    }
};
</script>
<style>
</style>
