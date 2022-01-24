require("./bootstrap");

import { createApp } from "vue";
import router from "./router";
import common from "./common";
import Toaster from "@meforma/vue-toaster";
import SearchPage from './components/SearchPage';
import CommentSection from './components/CommentSection';
import frontend from "./frontend";
import store from './store';

import App from "./App.vue";

// app for backend
const app = createApp(App);

app.mixin(common);
app.use(router);
app.use(store);
app.use(Toaster, {
    position: "top-right",
    duration: 3000,
});

app.mount("#app");

// app2 for frontend
const app2 = createApp({})

app2.mixin(frontend);
app2.component('SearchPage', SearchPage);
app2.component('CommentSection', CommentSection);

app2.mount('#app2')