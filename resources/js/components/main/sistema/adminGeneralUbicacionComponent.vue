<template>
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4" style="flex-direction: inherit;">
                <div class="btn-group-vertical" role="group" style="min-height: 450px; width: max-content;">
                    <div class="btn-group-vertical" role="group" aria-label="First Group" style="width: 160px; margin-left: 10px; margin-right: 10px;">
                        <input type="radio" class="btn-check" name="vertical-radio-btn" id="vertical-radio-btn5" autocomplete="off" @click="botonCheck('Nacionalidades')" checked="">
                        <label class="btn btn-outline-secondary m-auto" for="vertical-radio-btn5">
                            Nacionalidades
                        </label>
                        <input type="radio" class="btn-check" name="vertical-radio-btn" id="vertical-radio-btn6" autocomplete="off" @click="botonCheck('Países')">
                        <label class="btn btn-outline-secondary m-auto" for="vertical-radio-btn6">
                            Países
                        </label>
                        <input type="radio" class="btn-check" name="vertical-radio-btn" id="vertical-radio-btn7" autocomplete="off" @click="botonCheck('Regiones')">
                        <label class="btn btn-outline-secondary m-auto" for="vertical-radio-btn7">
                            Regiones
                        </label>
                        <input type="radio" class="btn-check" name="vertical-radio-btn" id="vertical-radio-btn8" autocomplete="off" @click="botonCheck('Comunas')">
                        <label class="btn btn-outline-secondary m-auto" for="vertical-radio-btn8">
                            Comunas
                        </label>
                        <input type="radio" class="btn-check" name="vertical-radio-btn" id="vertical-radio-btn2" autocomplete="off" @click="botonCheck('Zonas')">
                        <label class="btn btn-outline-secondary m-auto" for="vertical-radio-btn2">
                            Zonas
                        </label>
                    </div>
                </div>
                <!-- ################################################################################################################################# -->
                
                <div style="width: 100%;">
                    <!-- ################################################################################################################################# -->
                    <slot v-if="isVisibleNacionalidades">
                        <div class="row pb-0 p-4">
                            <div class="col-12">
                                <h6>Nacionalidades</h6>
                                <div class="d-flex justify-content-end">
                                    <a class="btn btn-continue" @click="showCreateNacionalidad = true">Nuevo</a>
                                    &nbsp;
                                    <a class="btn btn-search-blue" @click="recargarTabla('nacionalidad')"><i class="fa-solid fa-rotate"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2" style="min-height: 400px;">
                            <div v-show="mostrarCarga6" class="loader-xs"></div>
                            <div class="container">
                                <div class="table-responsive p-0">
                                    <table class="table align-items-center mb-0" id="MyTableNacionalidades">
                                        <thead>
                                            <tr style="color: black">
                                                <th style="min-width: 16px;"></th>
                                                <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Nacionalidad</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">País</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="nacionalidad in nacionalidades" :key="nacionalidad.id">
                                                <td></td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color:black">{{nacionalidad.id}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color:black">{{nacionalidad.nacionalidad}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color:black">{{nacionalidad.pais.pais}}</p>
                                                </td>
                                                <td class="align-middle text-end">
                                                    <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                        <a class="btn btn-alert btn-xs" title="Editar" @click="editNacionalidad(nacionalidad)"><i class="fa fa-fw fa-edit"></i></a>
                                                        &nbsp;
                                                        <a class="btn btn-closed btn-xs" title="Eliminar" @click="deleteNacionalidad(nacionalidad.id,nacionalidad.nacionalidad)"><i class="fa fa-fw fa-trash"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </slot>
                    <!-- ################################################################################################################################# -->
                    <slot v-if="isVisiblePaises">
                        <div class="row pb-0 p-4">
                            <div class="col-12">
                                <h6>Países</h6>
                                <div class="d-flex justify-content-end">
                                    <a class="btn btn-continue" @click="showCreatePais = true">Nuevo</a>
                                    &nbsp;
                                    <a class="btn btn-search-blue" @click="recargarTabla('pais')"><i class="fa-solid fa-rotate"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2" style="min-height: 400px">
                            <div v-show="mostrarCarga7" class="loader-xs"></div>
                            <div class="container">
                                <div class="table-responsive p-0">
                                    <table class="table align-items-center mb-0" id="MyTablePaises">
                                        <thead>
                                            <tr style="color: black">
                                                <th style="min-width: 16px;"></th>
                                                <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Nombre</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Capital</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="pais in paises" :key="pais.id">
                                                <td></td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color:black">{{pais.id}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color:black">{{pais.pais}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color:black">{{pais.capital}}</p>
                                                </td>
                                                <td class="align-middle text-end">
                                                    <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                        <a class="btn btn-alert btn-xs" title="Editar" @click="editPais(pais)"><i class="fa fa-fw fa-edit"></i></a>
                                                        &nbsp;
                                                        <a class="btn btn-closed btn-xs" title="Eliminar" @click="deletePais(pais.id,pais.pais)"><i class="fa fa-fw fa-trash"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </slot>
                    <!-- ################################################################################################################################# -->
                    <slot v-if="isVisibleRegiones">
                        <div class="row pb-0 p-4">
                            <div class="col-12">
                                <h6>Regiones</h6>
                                <div class="d-flex justify-content-end">
                                    <a class="btn btn-continue" @click="showCreateRegion = true">Nuevo</a>
                                    &nbsp;
                                    <a class="btn btn-search-blue" @click="recargarTabla('region')"><i class="fa-solid fa-rotate"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2" style="min-height: 400px">
                            <div v-show="mostrarCarga8" class="loader-xs"></div>
                            <div class="container">
                                <div class="table-responsive p-0">
                                    <table class="table align-items-center mb-0" id="MyTableRegiones">
                                        <thead>
                                            <tr style="color: black">
                                                <th style="min-width: 16px;"></th>
                                                <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Nombre</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Capital</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">País</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="region in regiones" :key="region.id">
                                                <td></td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color:black">{{region.id}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color:black">{{region.region}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color:black">{{region.capital}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color:black">{{region.pais.pais}}</p>
                                                </td>
                                                <td class="align-middle text-end">
                                                    <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                        <a class="btn btn-alert btn-xs" title="Editar" @click="editRegion(region)"><i class="fa fa-fw fa-edit"></i></a>
                                                        &nbsp;
                                                        <a class="btn btn-closed btn-xs" title="Eliminar" @click="deleteRegion(region.id,region.region)"><i class="fa fa-fw fa-trash"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </slot>
                    <!-- ################################################################################################################################# -->
                    <slot v-if="isVisibleComunas">
                        <div class="row pb-0 p-4">
                            <div class="col-12">
                                <h6>Comunas</h6>
                                <div class="d-flex justify-content-end">
                                    <a class="btn btn-continue" @click="showCreateComuna = true">Nuevo</a>
                                    &nbsp;
                                    <a class="btn btn-search-blue" @click="recargarTabla('comuna')"><i class="fa-solid fa-rotate"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2" style="min-height: 400px">
                            <div v-show="mostrarCarga9" class="loader-xs"></div>
                            <div class="container">
                                <div class="table-responsive p-0">
                                    <table class="table align-items-center mb-0" id="MyTableComunas">
                                        <thead>
                                            <tr style="color: black">
                                                <th style="min-width: 16px;"></th>
                                                <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Nombre</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Región</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="comuna in comunas" :key="comuna.id">
                                                <td></td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color:black">{{comuna.id}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color:black">{{comuna.comuna}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color:black">{{comuna.region.region}}</p>
                                                </td>
                                                <td class="align-middle text-end">
                                                    <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                        <a class="btn btn-alert btn-xs" title="Editar" @click="editComuna(comuna)"><i class="fa fa-fw fa-edit"></i></a>
                                                        &nbsp;
                                                        <a class="btn btn-closed btn-xs" title="Eliminar" @click="deleteComuna(comuna.id,comuna.comuna)"><i class="fa fa-fw fa-trash"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </slot>
                    <!-- ################################################################################################################################# -->
                    <slot v-if="isVisibleZonas">
                        <div class="row pb-0 p-4">
                            <div class="col-12">
                                <h6>Zonas</h6>
                                <div class="d-flex justify-content-end">
                                    <a class="btn btn-continue" @click="showCreateZona = true">Nuevo</a>
                                    &nbsp;
                                    <a class="btn btn-search-blue" @click="recargarTabla('zona')"><i class="fa-solid fa-rotate"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2" style="min-height: 400px">
                            <div v-show="mostrarCarga10" class="loader-xs"></div>
                            <div class="container">
                                <div class="table-responsive p-0">
                                    <table class="table align-items-center mb-0" id="MyTableZonas">
                                        <thead>
                                            <tr style="color: black">
                                                <th style="min-width: 16px;"></th>
                                                <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Nombre</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Pais</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="zona in zonas" :key="zona.id">
                                                <td></td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color:black">{{zona.id}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color:black">{{zona.nombre}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color:black">{{zona.pais.pais}}</p>
                                                </td>
                                                <td class="align-middle text-end">
                                                    <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                        <a class="btn btn-alert btn-xs" title="Editar" @click="editZona(zona)"><i class="fa fa-fw fa-edit"></i></a>
                                                        &nbsp;
                                                        <a class="btn btn-closed btn-xs" title="Eliminar" @click="deleteZona(zona.id,zona.nombre)"><i class="fa fa-fw fa-trash"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </slot>                
                </div>
            </div>
            <modalconfirmacion ref="confirmation"></modalconfirmacion>
            <modalalerta ref="alert"></modalalerta>
            <modalEditNacionalidad v-bind:nacionalidad1="nacionalidadEdit" v-if="showEditNacionalidad" @close="showEditNacionalidad = false" @recarga="recargarTabla('Nacionalidades')"></modalEditNacionalidad>
            <modalEditPais v-bind:pais1="paisEdit" v-if="showEditPais" @close="showEditPais = false" @recarga="recargarTabla('Paises')"></modalEditPais>
            <modalEditRegion v-bind:region1="regionEdit" v-if="showEditRegion" @close="showEditRegion = false" @recarga="recargarTabla('Regiones')"></modalEditRegion>
            <modalEditComuna v-bind:comuna1="comunaEdit" v-if="showEditComuna" @close="showEditComuna = false" @recarga="recargarTabla('Comunas')"></modalEditComuna>
            <modalCreateNacionalidad v-if="showCreateNacionalidad" @close="showCreateNacionalidad = false" @recarga="recargarTabla('Nacionalidades')"></modalCreateNacionalidad>
            <modalCreatePais v-if="showCreatePais" @close="showCreatePais = false" @recarga="recargarTabla('Paises')"></modalCreatePais>
            <modalCreateRegion v-if="showCreateRegion" @close="showCreateRegion = false" @recarga="recargarTabla('Regiones')"></modalCreateRegion>
            <modalCreateComuna v-if="showCreateComuna" @close="showCreateComuna = false" @recarga="recargarTabla('Comunas')"></modalCreateComuna>
            <modalCreateZona v-if="showCreateZona" @close="showCreateZona = false" @recarga="recargarTabla('Zonas')"></modalCreateZona>
            <modalEditZona v-bind:zona1="zonaEdit" v-if="showEditZona" @close="showEditZona = false" @recarga="recargarTabla('Zonas')"></modalEditZona>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import {mixin} from '../../../mixins.js'
import modalconfirmacion from '../../snippets/sistema/alerts/confirmationModal.vue'
import modalalerta from '../../snippets/sistema/alerts/alertModal.vue'
import modalEditNacionalidad from '../../snippets/sistema/adminGeneral/nacionalidades/editNacionalidadModal.vue'
import modalEditPais from '../../snippets/sistema/adminGeneral/paises/editPaisesModal.vue'
import modalEditRegion from '../../snippets/sistema/adminGeneral/regiones/editRegionesModal.vue'
import modalEditComuna from '../../snippets/sistema/adminGeneral/comunas/editComunasModal.vue'
import modalCreateNacionalidad from '../../snippets/sistema/adminGeneral/nacionalidades/createNacionalidadModal.vue'
import modalCreatePais from '../../snippets/sistema/adminGeneral/paises/createPaisModal.vue'
import modalCreateRegion from '../../snippets/sistema/adminGeneral/regiones/createRegionModal.vue'
import modalCreateComuna from '../../snippets/sistema/adminGeneral/comunas/createComunaModal.vue'
import modalCreateZona from '../../snippets/sistema/adminGeneral/zonas/createZonaModal.vue'
import modalEditZona from '../../snippets/sistema/adminGeneral/zonas/editZonaModal.vue'

export default {
    components: { modalconfirmacion, modalalerta, modalCreateNacionalidad, modalCreatePais, modalCreateRegion, modalCreateComuna, modalEditNacionalidad, modalEditPais, modalEditRegion, modalEditComuna, modalCreateZona, modalEditZona },
    mixins: [mixin],
    data: () => ({
        // Nacionalidades
        isVisibleNacionalidades: true,
        nacionalidades: null,
        showCreateNacionalidad: false,
        showEditNacionalidad: false,
        nacionalidadEdit: null,
        // Paises
        isVisiblePaises: false,
        paises: null,
        showEditPais: false,
        paisEdit: null,
        showCreatePais: false,
        // Regiones
        isVisibleRegiones: false,
        showCreateRegion: false,
        regiones: null,
        showEditRegion: false,
        regionEdit: null,
        // Comunas
        isVisibleComunas: false,
        showCreateComuna: false,
        comunas: null,
        showEditComuna: false,
        comunaEdit: null,
        // Zonas
        isVisibleZonas: false,
        showCreateZona: false,
        zonas: null,
        showEditZona: false,
        zonaEdit: null,
        // -----------------------
        tableNacionalidades: null,
        mostrarCarga6: true,
        mostrarTabla6: false,
        // -----------------------
        tablePaises: null,
        mostrarCarga7: true,
        mostrarTabla7: false,
        // -----------------------
        tableRegiones: null,
        mostrarCarga8: true,
        mostrarTabla8: false,
        // -----------------------
        tableComunas: null,
        mostrarCarga9: true,
        mostrarTabla9: false,
        // -----------------------
        tableZonas: null,
        mostrarCarga10: true,
        mostrarTabla10: false,
    }),
    created(){
        this.getNacionalidades();
    },
    methods: {
        recargarTabla($tabla){
            if ($tabla == 'Nacionalidades'){
                this.nacionalidades = null;
                this.mostrarCarga6 = true;
                this.getNacionalidades();
            }
            if ($tabla == 'Paises'){
                this.paises = null;
                this.mostrarCarga7 = true;
                this.getPaises();
            }
            if ($tabla == 'Regiones'){
                this.regiones = null;
                this.mostrarCarga8 = true;
                this.getRegiones();
            }
            if ($tabla == 'Comunas'){
                this.comunas = null;
                this.mostrarCarga9 = true;
                this.getComunas();
            }
            if ($tabla == 'Zonas'){
                this.zonas = null;
                this.mostrarCarga10 = true;
                this.getZonas();
            }
        },
        crearTablas($tabla){
            if($tabla == 'nacionalidad'){
                setTimeout(function(){
                    this.tableNacionalidades = $('#MyTableNacionalidades').DataTable({info: false, select:false, "bLengthChange" : false});
                    this.tableNacionalidades.buttons().remove();
                    this.mostrarCarga6 = false;
                    this.mostrarTabla6 = true;   
                }.bind(this),200);
            }
            if($tabla == 'pais'){
                setTimeout(function(){
                    this.tablePaises = $('#MyTablePaises').DataTable({info: false, select:false, "bLengthChange" : false});
                    this.tablePaises.buttons().remove();
                    this.mostrarCarga7 = false;
                    this.mostrarTabla7 = true;   
                }.bind(this),200);
            }
            if($tabla == 'region'){
                setTimeout(function(){
                    this.tableRegiones = $('#MyTableRegiones').DataTable({info: false, select:false, "bLengthChange" : false});
                    this.tableRegiones.buttons().remove();
                    this.mostrarCarga8 = false;
                    this.mostrarTabla8 = true;   
                }.bind(this),200);
            }
            if($tabla == 'comuna'){
                setTimeout(function(){
                    this.tableComunas = $('#MyTableComunas').DataTable({info: false, select:false, "bLengthChange" : false});
                    this.tableComunas.buttons().remove();
                    this.mostrarCarga9 = false;
                    this.mostrarTabla9 = true;   
                }.bind(this),200);
            }
            if($tabla == 'zona'){
                setTimeout(function(){
                    this.tableZonas = $('#MyTableZonas').DataTable({info: false, select:false, "bLengthChange" : false});
                    this.tableZonas.buttons().remove();
                    this.mostrarCarga10 = false;
                    this.mostrarTabla10 = true;   
                }.bind(this),200);
            }
        },
        // TABLAS y MANIPULACIÓN DE VISTAS:
        botonCheck($entrada){
            this.isVisibleNacionalidades = false,
            this.isVisiblePaises = false;
            this.isVisibleRegiones = false;
            this.isVisibleComunas = false;
            this.isVisibleZonas = false;
            if ($entrada == 'Nacionalidades'){
                this.nacionalidades = null;
                this.mostrarCarga6 = true;
                this.getNacionalidades();
                this.isVisibleNacionalidades = true;
            }
            if ($entrada == 'Países'){
                this.paises = null;
                this.mostrarCarga7 = true;
                this.getPaises();
                this.isVisiblePaises = true;
            }
            if ($entrada == 'Regiones'){
                this.regiones = null;
                this.mostrarCarga8 = true;
                this.getRegiones();
                this.isVisibleRegiones = true;
            }
            if ($entrada == 'Comunas'){
                this.comunas = null;
                this.mostrarCarga9 = true;
                this.getComunas();
                this.isVisibleComunas = true;
            }
            if ($entrada == 'Zonas'){
                this.zonas = null;
                this.mostrarCarga10 = true;
                this.getZonas();
                this.isVisibleZonas = true;
            }
        },
        // NACIONALIDADES:
        getNacionalidades(){
            axios.get('../api/nacionalidades').then( response =>{
                this.mostrarCarga6 = true;
                this.nacionalidades = response.data;
                if (this.tableNacionalidades != null){
                    this.tableNacionalidades.clear();
                    this.tableNacionalidades.destroy();
                }
                this.crearTablas('nacionalidad');
            }).catch(e=> console.log(e))
        },
        async deleteNacionalidad(id,nacionalidad){
            const ok = await this.$refs.confirmation.show({
                title: 'Borrar Nacionalidad',
                message: `¿Está seguro/a que desea eliminar la nacionalidad '${nacionalidad}'? Esta acción no puede ser desecha.`,
                okButton: 'Eliminar',
                cancelButton: 'Volver'
            })
            if (ok) {
                axios.delete(`../api/nacionalidades/${id}`).then( response =>{
                    this.toast.success("Nacionalidad eliminada con éxito!", {
                        position: "top-right",
                        timeout: 3000,
                        closeOnClick: true,
                        pauseOnFocusLoss: true,
                        pauseOnHover: true,
                        draggable: true,
                        draggablePercent: 0.6,
                        showCloseButtonOnHover: false,
                        hideProgressBar: true,
                        closeButton: "button",
                        icon: true,
                        rtl: false
                    });
                    this.recargarTabla('Nacionalidades');
                }).catch(e=> console.log(e))
            }
        },
        editNacionalidad(nacionalidad){
            this.nacionalidadEdit = nacionalidad;
            this.showEditNacionalidad = true;
        },
        // PAISES:
        getPaises(){
            axios.get('../api/paises').then( response =>{
                this.mostrarCarga7 = true;
                this.paises = response.data;
                if (this.tablePaises != null){
                    this.tablePaises.clear();
                    this.tablePaises.destroy();
                }
                this.crearTablas('pais');
            }).catch(e=> console.log(e))
        },
        async deletePais(id,pais){
            const ok = await this.$refs.confirmation.show({
                title: 'Borrar País',
                message: `¿Está seguro/a que desea eliminar el país '${pais}'? Esta acción no puede ser desecha.`,
                okButton: 'Eliminar',
                cancelButton: 'Volver'
            })
            if (ok) {
                axios.delete(`../api/paises/${id}`).then( response =>{
                    this.toast.success("País eliminado con éxito!", {
                        position: "top-right",
                        timeout: 3000,
                        closeOnClick: true,
                        pauseOnFocusLoss: true,
                        pauseOnHover: true,
                        draggable: true,
                        draggablePercent: 0.6,
                        showCloseButtonOnHover: false,
                        hideProgressBar: true,
                        closeButton: "button",
                        icon: true,
                        rtl: false
                    });
                    this.recargarTabla('Paises');
                }).catch(e=> console.log(e))
            }
        },
        editPais(pais){
            this.paisEdit = pais;
            this.showEditPais = true;
        },
        // REGIONES:
        getRegiones(){
            axios.get('../api/regiones').then( response =>{
                this.mostrarCarga8 = true;
                this.regiones = response.data;
                if (this.tableRegiones != null){
                    this.tableRegiones.clear();
                    this.tableRegiones.destroy();
                }
                this.crearTablas('region');
            }).catch(e=> console.log(e))
        },
        async deleteRegion(id,region){
            const ok = await this.$refs.confirmation.show({
                title: 'Borrar Región',
                message: `¿Está seguro/a que desea eliminar la región '${region}'? Esta acción no puede ser desecha.`,
                okButton: 'Eliminar',
                cancelButton: 'Volver'
            })
            if (ok) {
                axios.delete(`../api/regiones/${id}`).then( response =>{
                    this.toast.success("Región eliminada con éxito!", {
                        position: "top-right",
                        timeout: 3000,
                        closeOnClick: true,
                        pauseOnFocusLoss: true,
                        pauseOnHover: true,
                        draggable: true,
                        draggablePercent: 0.6,
                        showCloseButtonOnHover: false,
                        hideProgressBar: true,
                        closeButton: "button",
                        icon: true,
                        rtl: false
                    });
                    this.recargarTabla('Regiones');
                }).catch(e=> console.log(e))
            }
        },
        editRegion(region){
            this.regionEdit = region;
            this.showEditRegion = true;
        },
        // COMUNAS:
        getComunas(){
            axios.get('../api/comunas').then( response =>{
                this.mostrarCarga9 = true;
                this.comunas = response.data;
                if (this.tableComunas != null){
                    this.tableComunas.clear();
                    this.tableComunas.destroy();
                }
                this.crearTablas('comuna');
            }).catch(e=> console.log(e))
        },
        async deleteComuna(id,comuna){
            const ok = await this.$refs.confirmation.show({
                title: 'Borrar Comuna',
                message: `¿Está seguro/a que desea eliminar la comuna '${comuna}'? Esta acción no puede ser desecha.`,
                okButton: 'Eliminar',
                cancelButton: 'Volver'
            })
            if (ok) {
                axios.delete(`../api/comunas/${id}`).then( response =>{
                    this.toast.success("Comuna eliminada con éxito!", {
                        position: "top-right",
                        timeout: 3000,
                        closeOnClick: true,
                        pauseOnFocusLoss: true,
                        pauseOnHover: true,
                        draggable: true,
                        draggablePercent: 0.6,
                        showCloseButtonOnHover: false,
                        hideProgressBar: true,
                        closeButton: "button",
                        icon: true,
                        rtl: false
                    });
                    this.recargarTabla('Comunas');
                }).catch(e=> console.log(e))
            }
        },
        editComuna(comuna){
            this.comunaEdit = comuna;
            this.showEditComuna = true;
        },
        // ZONAS:
        getZonas(){
            axios.get('../api/zonas').then( response =>{
                this.mostrarCarga10 = true;
                this.zonas = response.data;
                if (this.tableZonas != null){
                    this.tableZonas.clear();
                    this.tableZonas.destroy();
                }
                this.crearTablas('zona');
            }).catch(e=> console.log(e))
        },
        async deleteZona(id,zona){
            const ok = await this.$refs.confirmation.show({
                title: 'Borrar Zona',
                message: `¿Está seguro/a que desea eliminar la zona '${zona}'? Esta acción no puede ser desecha.`,
                okButton: 'Eliminar',
                cancelButton: 'Volver'
            })
            if (ok) {
                axios.delete(`../api/zonas/${id}`).then( response =>{
                    this.toast.success("Zona eliminada con éxito!", {
                        position: "top-right",
                        timeout: 3000,
                        closeOnClick: true,
                        pauseOnFocusLoss: true,
                        pauseOnHover: true,
                        draggable: true,
                        draggablePercent: 0.6,
                        showCloseButtonOnHover: false,
                        hideProgressBar: true,
                        closeButton: "button",
                        icon: true,
                        rtl: false
                    });
                    this.recargarTabla('Zonas');
                }).catch(e=> console.log(e))
            }
        },
        editZona(zona){
            this.zonaEdit = zona;
            this.showEditZona = true;
        },

    }
}
</script>