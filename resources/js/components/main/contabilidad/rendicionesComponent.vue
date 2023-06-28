<template>
<div class="row mt-4 mx-4">
    <div class="col-12">
        <div class="card mb-4">
            <div class="row pb-0 p-4">
                <div class="col-8">
                    <div class="row">
                        <div class="col-2">
                            <label>Fecha Inicio Soli.:
                                <input type="date" class="form-control form-control-sm" placeholder="Fecha Inicial" v-model="fechaInicio">
                            </label>   
                        </div>
                        <div class="col-2">
                            <label>Fecha Término Soli.:
                                <input type="date" class="form-control form-control-sm" placeholder="Fecha Termino" v-model="fechaTermino">
                            </label>
                        </div>
                        &nbsp;
                        <div class="col-2">
                            <label>Fecha Inicio Rend.:
                                <input type="date" class="form-control form-control-sm" placeholder="Fecha Inicial" v-model="fechaInicio2">
                            </label>   
                        </div>
                        <div class="col-2">
                            <label>Fecha Término Rend.:
                                <input type="date" class="form-control form-control-sm" placeholder="Fecha Termino" v-model="fechaTermino2">
                            </label>
                        </div>
                        &nbsp;

                        <div class="col-1 pt-3">
                            <a class="btn btn-search-blue" @click="filtrarFecha()"><i class="fa-solid fa-magnifying-glass"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="d-flex justify-content-end">
                        <a v-if="can('Rendiciones_Autorizar')" class="btn btn-closed" id="show-autorizarModal" @click="showAutorizarPago = true">Auth Pago</a>
                        &nbsp;
                        <a v-if="can('Rendiciones_Autorizar')" class="btn btn-closed" id="show-autorizarModal" @click="showAutorizarRendicion = true">Auth Rendis</a>
                        &nbsp;
                        <a v-if="can('Rendiciones_Crear')" class="btn btn-continue" id="show-modal1" @click="showCrearRendicion = true">Nueva Solicitud</a>
                        &nbsp;
                        <a class="btn btn-search-blue" @click="recargarTabla('General')"><i class="fa-solid fa-rotate"></i></a>
                    </div>
                </div>
                <!-- <div class="dropdown col-3">
                    <a class="btn btn-primary" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                        Mostrar/Ocultar Columnas:
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                        <li><a class="dropdown-item toggle-vis" data-column="1" href="#">ID</a></li>
                        <li><a class="dropdown-item toggle-vis" data-column="2" href="#">Folio</a></li>
                        <li><a class="dropdown-item toggle-vis" data-column="3" href="#">Colaborador</a></li>
                        <li><a class="dropdown-item toggle-vis" data-column="4" href="#">Monto Solicitud</a></li>
                        <li><a class="dropdown-item toggle-vis" data-column="5" href="#">Monto Rendición</a></li>
                        <li><a class="dropdown-item toggle-vis" data-column="5" href="#">Tipo Moneda</a></li>
                        <li><a class="dropdown-item toggle-vis" data-column="6" href="#">Usuario Solicitud</a></li>
                        <li><a class="dropdown-item toggle-vis" data-column="7" href="#">Usuario Rendición </a></li>
                        <li><a class="dropdown-item toggle-vis" data-column="8" href="#">Fecha Solicitud</a></li>
                        <li><a class="dropdown-item toggle-vis" data-column="9" href="#">Fecha Rendición</a></li>
                        <li><a class="dropdown-item toggle-vis" data-column="10" href="#">Acciones</a></li>
                    </ul>
                </div> -->
            </div>
            <div class="card-body px-0 pt-0 pb-2" style="min-height: 400px">
                <div class="container">
                    <div class="table-responsive p-0">
                        <div v-show="mostrarCarga" class="loader-sm"></div>
                            <table v-show="mostrarTabla" class="table align-items-center mb-0" id="MyTableRendiciones">
                                <thead>
                                    <tr style="color: black">
                                        <th style="min-width: 16px;"></th>
                                        <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Estado</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Folio</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Colaborador</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Monto Solicitud</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Monto Rendición</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Diferencia</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Tipo Moneda</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Usuario Solicitud</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Usuario Rendición</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Fecha Solicitud</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Fecha Rendición</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="rendi in rendis" :key="rendi.id">
                                        <td></td>
                                        <td>
                                            <p class="text-sm font-weight-bolder mb-0" style="color: black">{{ rendi.id }}</p>
                                        </td>
                                        <td>

                                            <p v-if="rendi.estado == 'PendRen'" class="text-sm font-weight-bolder mb-0" style="color: #8392AB"> Pendiente Rendicion </p>
                                            <p v-else-if="rendi.estado == 'PendAutRen'" class="text-sm font-weight-bolder mb-0" style="color: #FFC107"> Pendiente Auth Rendicion </p>
                                            <p v-else-if="rendi.estado == 'PendAutPag'" class="text-sm font-weight-bolder mb-0" style="color: #FFC107"> Pendiente Auth Pago </p>
                                            <p v-else-if="rendi.estado == 'Rendida'" class="text-sm font-weight-bolder mb-0" style="color: #28A745"> Rendida </p>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0">{{ rendi.folio }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0">{{ rendi.colaborador }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0">{{ formatMoneyCLP(rendi.montoSoli) }}</p>
                                        </td>
                                        <td>
                                            <p v-if="rendi.montoRendi == null" class="text-sm mb-0">---</p>
                                            <p v-else class="text-sm mb-0">{{ formatMoneyCLP(rendi.montoRendi) }}</p>
                                        </td>
                                        <td class="text-sm mb-0">
                                            <p v-if="rendi.montoRendi == null" class="text-sm mb-0">---</p>
                                            <p v-else class="text-sm mb-0">{{ formatMoneyCLP(rendi.montoSoli - rendi.montoRendi) }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0">{{ rendi.moneda.abreviacion }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0">{{ rendi.usuario_soli.name }}</p>
                                        </td>
                                        <td>
                                            <p v-if="rendi.idUsuarioRendi == null" class="text-sm mb-0">---</p>
                                            <p v-else class="text-sm mb-0">{{ rendi.usuario_rendi.name }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0">{{ thisDate(rendi.fechaSoli) }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0">{{ thisDate(rendi.fechaRendi) }}</p>
                                        </td>
                                        <td class="align-middle">
                                            <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                <a v-if="rendi.pendiente == false" class="btn btn-success btn-xs" title="Detalle" @click="verRendi(rendi)"><i class="fa-regular fa-eye"></i></a>
                                                <a v-if="rendi.estado == 'PendRen' && can('Rendiciones_Modificar')" @click="rendir(rendi)" title="Rendir" class="btn btn-purple btn-xs"><i class="fa-solid fa-file-signature"></i></a>
                                                &nbsp;
                                                <a v-if="can('Rendiciones_Modificar')" class="btn btn-closed btn-xs" title="Eliminar" @click="deleteRendi(rendi.id)"><i class="fa fa-fw fa-trash"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="row">
                                <div class="col-3">
                                    <label style="font-weight: 500">Estos botones utilizan los elementos seleccionados en la tabla, en caso de no existir ninguno seleccionará todos los registros. </label>
                                </div>
                                <div class="col-auto">
                                    <label title="Para seleccionar UN solo registro de la tabla basta con hacer &#013; click en el recuadro de la primera columna, para seleccionar &#013; varios consecutivos mantener SHIFT, para seleccionar varios &#013; no consecutivos mantener CTRL."><span class="badge bg-dark-grey fs-10">?</span></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-4 align-left text-center float-end">
                    <!-- -->
                </div>
                <div class="col-4 align-left text-center float-end">
                    <!-- -->
                </div>
                <div class="col-4 align-left text-center float-end">
                    <!-- -->
                </div>
            </div>
            <modalconfirmacion ref="confirmation"></modalconfirmacion>
            <modalalerta ref="alert"></modalalerta>
            <modalrendir v-bind:rendi="rendi" v-if="showRendir" @close="showRendir = false" @recarga="recargarTabla('General')"></modalrendir>
            <modalver v-bind:rendi="rendi" v-if="showDetalleRendicion" @close="showDetalleRendicion = false"></modalver>
            <modalcrear v-if="showCrearRendicion" @close="showCrearRendicion = false" @recarga="recargarTabla('General')"></modalcrear>
            <modalautorizarRendi v-if="showAutorizarRendicion" @close="showAutorizarRendicion = false" @recarga="recargarTabla('General')"></modalautorizarRendi>
            <modalautorizarPago v-if="showAutorizarPago" @close="showAutorizarPago = false" @recarga="recargarTabla('General')"></modalautorizarPago>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import modalver from '../../snippets/contabilidad/rendiciones/detalleRendiModal.vue';
    import modalcrear from '../../snippets/contabilidad/rendiciones/createSoliModal.vue';
    import modalrendir from '../../snippets/contabilidad/rendiciones/rendirModal.vue';
    import modalautorizarRendi from '../../snippets/contabilidad/rendiciones/autorizarRendiModal.vue';
    import modalautorizarPago from '../../snippets/contabilidad/rendiciones/autorizarPagoModal.vue';
    import modalconfirmacion from '../../snippets/sistema/alerts/confirmationModal.vue';
    import modalalerta from '../../snippets/sistema/alerts/alertModal.vue';
    import {mixin} from '../../../mixins.js';

    export default {
        components: { modalcrear, modalver, modalconfirmacion, modalalerta, modalrendir, modalautorizarRendi, modalautorizarPago },
        mixins: [mixin],
        data(){
            return{
                showCrearRendicion: false,
                showDetalleRendicion: false,
                showAutorizarRendicion: false,
                showAutorizarPago: false,
                showRendir: false,
                mostrarTabla: false,
                mostrarCarga: true,
                rendis: null,
                rendisAux: null,
                rendi: null,
                table: null,
                fechaInicio: null,
                fechaTermino: null,
                fechaInicio2: null,
                fechaTermino2: null,
            }
        },
        created(){
            this.getRendis(this.userID);
        },
        methods: {
            filtrarFecha(){
                if (this.fechaInicio == null || this.fechaTermino == null){
                    if (this.fechaInicio2 != null && this.fechaTermino2 != null){
                        let start = new Date(this.fechaInicio2);
                        let end = new Date(this.fechaTermino2);
                        this.rendis = this.rendisAux.filter(item => {
                            let date = new Date(item.fechaRendi);
                            return date >= start && date <= end ;
                        });
                        this.recargarTabla();
                    }else{
                        this.recargarTabla('General');
                    }
                }else{
                    if (this.fechaInicio2 == null || this.fechaTermino2 == null){
                        let start = new Date(this.fechaInicio);
                        let end = new Date(this.fechaTermino);
                        this.rendis = this.rendisAux.filter(item => {
                            let date = new Date(item.fechaSoli);
                            return date >= start && date <= end ;
                        });
                        this.recargarTabla();
                    }else{
                        let start = new Date(this.fechaInicio);
                        let end = new Date(this.fechaTermino);
                        let start2 = new Date(this.fechaInicio2);
                        let end2 = new Date(this.fechaTermino2);
                        this.rendis = this.rendisAux.filter(item => {
                            let date = new Date(item.fechaSoli);
                            let date2 = new Date(item.fechaRendi);
                            return date >= start && date <= end && date2 >= start2 && date2 <= end2;
                        });
                        this.recargarTabla();
                    }
                }
            },
            recargarTabla($tipoRecarga){
                this.mostrarCarga = true;
                if($tipoRecarga == 'General'){
                    this.rendis = null;
                    this.getRendis(this.userID);
                    this.fechaInicio = null;
                    this.fechaTermino = null;
                    this.fechaInicio2 = null;
                    this.fechaTermino2 = null;
                }else{
                    this.table.destroy();
                    this.crearTabla('#MyTableRendiciones');
                }
            },
            // Funcion para obtener rendiciones almacenadas y mostrarlas
            getRendis(id){
                axios.get(`api/rendiciones/${id}`).then( response =>{
                    this.rendis = response.data;
                    this.rendisAux = response.data;
                    if (this.table != null){
                        this.table.clear();
                        this.table.destroy();
                    }
                    this.crearTabla("#MyTableRendiciones");
                }).catch(e=> console.log(e))
            },
            // Funcion para eliminar una rendicion de la base de datos
            async deleteRendi(id) {
                const ok = await this.$refs.confirmation.show({
                    title: 'Borrar Rendición',
                    message: `¿Está seguro/a que desea eliminar la Rendición con la id '${id}'? Esta acción no puede ser desecha.`,
                    okButton: 'Eliminar',
                    cancelButton: 'Volver'
                })
                if (ok) {
                    axios.delete(`api/rendiciones/${id}`).then( response =>{
                        this.toast.success("Rendición eliminada con éxito!", {
                            position: "top-right",
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
                        this.recargarTabla('General');
                    }).catch(e=> console.log(e))
                }
            },
            // Funcion para abrir modal de detalles de rendicion y pasarle la rendicion
            verRendi(rendi){
                this.rendi = rendi;
                this.showDetalleRendicion = true;
            },
            rendir(rendi){
                this.rendi = rendi;
                this.showRendir = true;
            },
        }
    }
</script>
