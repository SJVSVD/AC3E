import { createApp, defineAsyncComponent } from 'vue';
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

const dashboardComponent = defineAsyncComponent(() => import('@/components/dashboardComponent.vue'));
const adminUsuariosComponent = defineAsyncComponent(() => import('@/components/main/sistema/adminUsuariosComponent.vue'));
const isiPublicationsComponent = defineAsyncComponent(() => import('@/components/main/sistema/isiPublicationsComponent.vue'));
const nonIsiPublicationsComponent = defineAsyncComponent(() => import('@/components/main/sistema/nonIsiPublicationsComponent.vue'));
const booksComponent = defineAsyncComponent(() => import('@/components/main/sistema/booksComponent.vue'));
const awardsComponent = defineAsyncComponent(() => import('@/components/main/sistema/awardsComponent.vue'));
const organizationScEventsComponent = defineAsyncComponent(() => import('@/components/main/sistema/organizationScEventsComponent.vue'));
const participationScEventsComponent = defineAsyncComponent(() => import('@/components/main/sistema/participationScEventsComponent.vue'));
const scCollaborationsComponent = defineAsyncComponent(() => import('@/components/main/sistema/scCollaborationsComponent.vue'));
const conjointProjectsComponent = defineAsyncComponent(() => import('@/components/main/sistema/conjointProjectsComponent.vue'));
const thesisStudentsComponent = defineAsyncComponent(() => import('@/components/main/sistema/thesisStudentsComponent.vue'));
const outreachActivitiesComponent = defineAsyncComponent(() => import('@/components/main/sistema/outreachActivitiesComponent.vue'));
const patentsComponent = defineAsyncComponent(() => import('@/components/main/sistema/patentsComponent.vue'));
const publicPrivateComponent = defineAsyncComponent(() => import('@/components/main/sistema/publicPrivateComponent.vue'));
const technologyKnowledgeComponent = defineAsyncComponent(() => import('@/components/main/sistema/technologyKnowledgeComponent.vue'));
const postDocComponent = defineAsyncComponent(() => import('@/components/main/sistema/postDocComponent.vue'));
const fundingSourcesComponent = defineAsyncComponent(() => import('@/components/main/sistema/fundingSourcesComponent.vue'));
const adminRolesComponent = defineAsyncComponent(() => import('@/components/main/sistema/adminRolesComponent.vue'));
const userProfileComponent = defineAsyncComponent(() => import('@/components/userProfileComponent.vue'));
const powerBiComponent = defineAsyncComponent(() => import('@/components/powerBiComponent.vue'));
const importInfoComponent = defineAsyncComponent(() => import('@/components/importInfoComponent.vue'));
const recycleBinComponent = defineAsyncComponent(() => import('@/components/recycleBinComponent.vue'));
const error = defineAsyncComponent(() => import('@/components/errorComponent.vue'));
const indicatorsComponent = defineAsyncComponent(() => import('@/components/indicatorsComponent.vue'));

app.component("dashboard-component", dashboardComponent);
app.mount("#dashboard");

app.component("admin-usuarios", adminUsuariosComponent);
app.mount("#admin-usuarios");

app.component("isi-publications", isiPublicationsComponent);
app.mount("#isi-publications");

app.component("non-isi-publications", nonIsiPublicationsComponent);
app.mount("#non-isi-publications");

app.component("books", booksComponent);
app.mount("#books");

app.component("awards", awardsComponent);
app.mount("#awards");

app.component("organization-sc-events", organizationScEventsComponent);
app.mount("#organization-sc-events");

app.component("participation-sc-events", participationScEventsComponent);
app.mount("#participation-sc-events");

app.component("sc-collaborations", scCollaborationsComponent);
app.mount("#sc-collaborations");

app.component("conjoint-projects", conjointProjectsComponent);
app.mount("#conjoint-projects");

app.component("thesis-students", thesisStudentsComponent);
app.mount("#thesis-students");

app.component("outreach-activities", outreachActivitiesComponent);
app.mount("#outreach-activities");

app.component("patents", patentsComponent);
app.mount("#patents");

app.component("public-private", publicPrivateComponent);
app.mount("#public-private");

app.component("technology-knowledge", technologyKnowledgeComponent);
app.mount("#technology-knowledge");

app.component("post-doc", postDocComponent);
app.mount("#post-doc");

app.component("funding-sources", fundingSourcesComponent);
app.mount("#funding-sources");

app.component("admin-roles", adminRolesComponent);
app.mount("#admin-roles");

app.component("user-profile-component", userProfileComponent);
app.mount("#userp");

app.component("powerbi-component", powerBiComponent);
app.mount("#powerbi-component");

app.component("import-info", importInfoComponent);
app.mount("#import-info");

app.component("recycle-bin", recycleBinComponent);
app.mount("#recycle-bin");

app.component("indicators-component", indicatorsComponent);
app.mount("#indicators-component");

app.component("error-component", error);
app.mount("#error");
