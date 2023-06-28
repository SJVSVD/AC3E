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

// ################################################ EN DESUSO ################################################
import informaticaComponent from "./components/unused/informaticaComponent.vue";
app.component("informatica-component", informaticaComponent);
app.mount("#informatica");

import gestorComexComponent from "./components/unused/gestorComexComponent.vue";
app.component("gestor-comex", gestorComexComponent);
app.mount("#gestor-comex");

import remuneracionesComponent from "./components/unused/remuneracionesComponent.vue";
app.component("remuneraciones-component", remuneracionesComponent);
app.mount("#remu");

import gestorOCComponent from "./components/unused/gestorOCComponent.vue";
app.component("gestor-oc", gestorOCComponent);
app.mount("#gestor-oc");

import tablesComponent from "./components/unused/tablesComponent.vue";
app.component("tables-component", tablesComponent);
app.mount("#tables");

import billingComponent from "./components/unused/billingComponent.vue";
app.component("billing-component", billingComponent);
app.mount("#billing");

import signInComponent from "./components/unused/signInComponent.vue";
app.component("sign-in-component", signInComponent);
app.mount("#signin");

// ################################################ ACTIVOS ################################################
import manualesComponent from "./components/manualesSistemaComponent.vue";
app.component("manuales-component", manualesComponent);
app.mount("#manuales-sistema");

import facturasComponent from "./components/main/contabilidad/facturasComponent.vue";
app.component("facturas-component", facturasComponent);
app.mount("#facturas");

import dashboardComponent from "./components/dashboardComponent.vue";
app.component("dashboard-component", dashboardComponent);
app.mount("#dashboard");

import menuGestionTiendaComponent from "./components/main/tiendas/gestionTiendaComponent.vue";
app.component("menu-gestion-tienda-component", menuGestionTiendaComponent);
app.mount("#mgt");

import cierreCajasComponent from "./components/main/tiendas/cierreCajasComponent.vue";
app.component("cierre-cajas-component", cierreCajasComponent);
app.mount("#cierre-cajas");

import solicitarPreciosComponent from "./components/main/tiendas/solicitarPreciosComponent.vue";
app.component("solicitar-precios-component", solicitarPreciosComponent);
app.mount("#solicitar-precios");

import userProfileComponent from "./components/userProfileComponent.vue";
app.component("user-profile-component", userProfileComponent);
app.mount("#userp");

import adminGeneralComponent from "./components/main/sistema/adminGeneralComponent.vue";
app.component("admin-general", adminGeneralComponent);
app.mount("#admin-general");

import adminGeneralEmpresaComponent from "./components/main/sistema/adminGeneralEmpresaComponent.vue";
app.component("admin-general-empresa", adminGeneralEmpresaComponent);
app.mount("#admin-general-empresa");

import adminGeneralUbicacionComponent from "./components/main/sistema/adminGeneralUbicacionComponent.vue";
app.component("admin-general-ubicacion", adminGeneralUbicacionComponent);
app.mount("#admin-general-ubicacion");

import adminGeneralTiendasComponent from "./components/main/sistema/adminGeneralTiendasComponent.vue";
app.component("admin-general-tiendas", adminGeneralTiendasComponent);
app.mount("#admin-general-tiendas");

import adminGeneralUsuariosComponent from "./components/main/sistema/adminGeneralUsuariosComponent.vue";
app.component("admin-general-usuarios", adminGeneralUsuariosComponent);
app.mount("#admin-general-usuarios");

import adminGeneralOtrosComponent from "./components/main/sistema/adminGeneralOtrosComponent.vue";
app.component("admin-general-otros", adminGeneralOtrosComponent);
app.mount("#admin-general-otros");

import adminColabComponent from "./components/main/sistema/adminColabComponent.vue";
app.component("admin-colab", adminColabComponent);
app.mount("#admin-colab");

import adminUsuariosComponent from "./components/main/sistema/adminUsuariosComponent.vue";
app.component("admin-usuarios", adminUsuariosComponent);
app.mount("#admin-usuarios");

import adminRolesComponent from "./components/main/sistema/adminRolesComponent.vue";
app.component("admin-roles", adminRolesComponent);
app.mount("#admin-roles");

import queryTestsComponent from "./components/main/sistema/queryTestsComponent.vue";
app.component("query-tests", queryTestsComponent);
app.mount("#query-tests");

import fondosFijosComponent from "./components/main/contabilidad/fondosFijosComponent.vue";
app.component("fofi-component", fondosFijosComponent);
app.mount("#fofi");

import rendiciones from "./components/main/contabilidad/rendicionesComponent.vue";
app.component("rendiciones-component", rendiciones);
app.mount("#rendiciones");

import error from "./components/errorComponent.vue";
app.component("error-component", error);
app.mount("#error");

import gestionRobos from "./components/main/tiendas/gestionRobosComponent.vue";
app.component("gestion-robos", gestionRobos);
app.mount("#gestion-robos");

import gestionInventarioConcesiones from "./components/main/tiendas/gestionInventarioConcesionesComponent.vue";
app.component("gestion-inventario-concesiones", gestionInventarioConcesiones);
app.mount("#gestion-inventario-concesiones");

import ncv from "./components/main/tiendas/ncvComponent.vue";
app.component("ncv-component", ncv);
app.mount("#ncv");

import ventasVendedor from "./components/main/tiendas/ventasVendedorComponent.vue";
app.component("ventas-vendedor-component", ventasVendedor);
app.mount("#ventas-vendedor");
