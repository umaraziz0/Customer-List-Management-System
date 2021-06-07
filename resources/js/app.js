/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require("./bootstrap");

window.Vue = require("vue").default;

import Vue from "vue";
import VueRouter from "vue-router";
import routes from "./routes";
import axios from "axios";
import VueAxios from "vue-axios";
import vSelect from "vue-select";
import { BootstrapVue, IconsPlugin } from "bootstrap-vue";
import { library } from "@fortawesome/fontawesome-svg-core";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import { fas } from "@fortawesome/free-solid-svg-icons";
import { fab } from "@fortawesome/free-brands-svg-icons";

Vue.use(VueRouter);
Vue.use(VueAxios, axios);
Vue.use(BootstrapVue);
Vue.use(IconsPlugin);

library.add(fas);
library.add(fab);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

import Admin from "./Admin.vue";
import Agent from "./Agent.vue";
import FollowUpList from "./components/FollowUpList.vue";

Vue.component("font-awesome-icon", FontAwesomeIcon);
Vue.component("v-select", vSelect);

Vue.component(
    "example-component",
    require("./components/ExampleComponent.vue").default
);

const router = new VueRouter({
    mode: "history",
    routes: routes
});

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: "#app",
    components: { Admin, Agent, FollowUpList },
    router: router
});
