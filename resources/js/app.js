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

import scCollaborationsComponent from "./components/main/sistema/scCollaborationsComponent.vue";
app.component("sc-collaborations", scCollaborationsComponent);
app.mount("#sc-collaborations");

import conjointProjectsComponent from "./components/main/sistema/conjointProjectsComponent.vue";
app.component("conjoint-projects", conjointProjectsComponent);
app.mount("#conjoint-projects");

import thesisStudentsComponent from "./components/main/sistema/thesisStudentsComponent.vue";
app.component("thesis-students", thesisStudentsComponent);
app.mount("#thesis-students");

import outreachActivitiesComponent from "./components/main/sistema/outreachActivitiesComponent.vue";
app.component("outreach-activities", outreachActivitiesComponent);
app.mount("#outreach-activities");

import patentsComponent from "./components/main/sistema/patentsComponent.vue";
app.component("patents", patentsComponent);
app.mount("#patents");

import publicPrivateComponent from "./components/main/sistema/publicPrivateComponent.vue";
app.component("public-private", publicPrivateComponent);
app.mount("#public-private");

import technologyKnowledgeComponent from "./components/main/sistema/technologyKnowledgeComponent.vue";
app.component("technology-knowledge", technologyKnowledgeComponent);
app.mount("#technology-knowledge");

import postDocComponent from "./components/main/sistema/postDocComponent.vue";
app.component("post-doc", postDocComponent);
app.mount("#post-doc");

import fundingSourcesComponent from "./components/main/sistema/fundingSourcesComponent.vue";
app.component("funding-sources", fundingSourcesComponent);
app.mount("#funding-sources");

import adminRolesComponent from "./components/main/sistema/adminRolesComponent.vue";
app.component("admin-roles", adminRolesComponent);
app.mount("#admin-roles");

import userProfileComponent from "./components/userProfileComponent.vue";
app.component("user-profile-component", userProfileComponent);
app.mount("#userp");

import error from "./components/errorComponent.vue";
app.component("error-component", error);
app.mount("#error");
