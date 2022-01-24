<template>
    <div class="menu_srch d-flex">
        <i class="fas fa-search search_btn"></i>
        <!-- SEARCH INPUT -->
        <div class="header_serch dis_none">
            <div class="header_serch_input">
                <input type="text" v-model="search" @keyup.enter="searchBlog" placeholder="Search" />
            </div>
            <div class="header_serch_i">
                <button
                    type="button"
                    @click="searchBlog"
                    class="btn border"
                    title="Search"
                >
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div>
        <!-- SEARCH INPUT -->
    </div>
</template>

<script>
export default {
    data() {
        return {
            search: "",
        };
    },
    methods: {
        searchBlog() {
            window.location = `/search?str=${this.search}`;
        },
    }
};
</script>
