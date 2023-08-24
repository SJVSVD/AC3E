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

import nonIsiPublicationsComponent from "./components/main/sistema/nonIsiPublicationsComponent.vue";
app.component("non-isi-publications", nonIsiPublicationsComponent);
app.mount("#non-isi-publications");

import booksComponent from "./components/main/sistema/booksComponent.vue";
app.component("books", booksComponent);
app.mount("#books");

import awardsComponent from "./components/main/sistema/awardsComponent.vue";
app.component("awards", awardsComponent);
app.mount("#awards");

import organizationScEventsComponent from "./components/main/sistema/organizationScEventsComponent.vue";
app.component("organization-sc-events", organizationScEventsComponent);
app.mount("#organization-sc-events");

import participationScEventsComponent from "./components/main/sistema/participationScEventsComponent.vue";
app.component("participation-sc-events", participationScEventsComponent);
app.mount("#participation-sc-events");

import thesisStudentsComponent from "./components/main/sistema/thesisStudentsComponent.vue";
app.component("thesis-students", thesisStudentsComponent);
app.mount("#thesis-students");

import adminRolesComponent from "./components/main/sistema/adminRolesComponent.vue";
app.component("admin-roles", adminRolesComponent);
app.mount("#admin-roles");

import userProfileComponent from "./components/userProfileComponent.vue";
app.component("user-profile-component", userProfileComponent);
app.mount("#userp");

import error from "./components/errorComponent.vue";
app.component("error-component", error);
app.mount("#error");
