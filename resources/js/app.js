import { createApp } from "vue";
import LaravelPermissionToVueJS from "laravel-permission-to-vuejs";
import "vue-sidebar-menu/dist/vue-sidebar-menu.css";
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";

const app = createApp({});
app.use(LaravelPermissionToVueJS);
app.use(Toast, {
    transition: "Vue-Toastification__bounce",
    maxToasts: 20,
    newestOnTop: true,
});


import dashboardComponent from "./components/dashboardComponent.vue";
app.component("dashboard-component", dashboardComponent);
app.mount("#dashboard");

import adminUsuariosComponent from "./components/main/sistema/adminUsuariosComponent.vue";
app.component("admin-usuarios", adminUsuariosComponent);
app.mount("#admin-usuarios");

import isiPublicationsComponent from "./components/main/sistema/isiPublicationsComponent.vue";
app.component("isi-publications", isiPublicationsComponent);
app.mount("#isi-publications");

import thesisStudentsComponent from "./components/main/sistema/thesisStudentsComponent.vue";
app.component("thesis-students", thesisStudentsComponent);
app.mount("#thesis-students");

import adminRolesComponent from "./components/main/sistema/adminRolesComponent.vue";
app.component("admin-roles", adminRolesComponent);
app.mount("#admin-roles");

import error from "./components/errorComponent.vue";
app.component("error-component", error);
app.mount("#error");
