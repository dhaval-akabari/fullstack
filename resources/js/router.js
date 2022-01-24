import { createRouter, createWebHistory } from "vue-router";

import HomePage from "./components/pages/HomePage.vue";
import Tag from "./admin/pages/Tag.vue";
import Category from "./admin/pages/Category.vue";
import Blogs from "./admin/pages/Blogs.vue";
import CreateBlog from "./admin/pages/CreateBlog.vue";
import EditBlog from "./admin/pages/EditBlog.vue";
import AdminUser from "./admin/pages/AdminUser.vue";
import Role from "./admin/pages/Role.vue";
import AssignRole from "./admin/pages/AssignRole.vue";
import Login from "./admin/pages/Login.vue";
import NotFound from "./admin/pages/NotFound.vue";

const routes = [
    {
        path: "/dashboard",
        name: "dashboard",
        component: HomePage,
    },
    {
        path: "/tag",
        name: "tag",
        component: Tag,
    },
    {
        path: "/category",
        name: "category",
        component: Category,
    },
    {
        path: "/blogs",
        name: "blogs",
        component: Blogs,
    },
    {
        path: "/create-blog",
        name: "create-blog",
        component: CreateBlog,
    },
    {
        path: "/edit-blog/:id",
        name: "edit-blog",
        component: EditBlog,
    },
    {
        path: "/admin-user",
        name: "admin-user",
        component: AdminUser,
    },
    {
        path: "/role",
        name: "role",
        component: Role,
    },

    {
        path: "/assign-role",
        name: "assign-role",
        component: AssignRole,
    },
    {
        path: "/login",
        name: "login",
        component: Login,
    },
    {
        path: "/:catchAll(.*)",
        name: "NotFound",
        component: NotFound,
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
