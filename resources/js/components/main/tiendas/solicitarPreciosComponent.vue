<template>
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="row pb-0 p-4">
                    <div class="col-9">
                        <div class="row">
                            <div class="col-2">
                                <label>Fecha Inicio Cierre:
                                    <input type="date" class="form-control form-control-sm" placeholder="Fecha Inicial" v-model="fechaInicio">
                                </label>   
                            </div>
                            <div class="col-2">
                                <label>Fecha Término Cierre:
                                    <input type="date" class="form-control form-control-sm" placeholder="Fecha Termino" v-model="fechaTermino">
                                </label>
                            </div>
                            <div class="col-auto pt-3">
                                <a class="btn btn-search-blue" @click="filtrarFecha()"><i class="fa-solid fa-magnifying-glass"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="d-flex justify-content-end">
                            <a v-if="can('Gestión Tienda_Solicitar Precios')" class="btn btn-spacing btn-continue" id="show-modal1" @click="showSolicitarPrecios = true">Solicitar Precios</a>
                            &nbsp;
                            <a class="btn btn-spacing btn-search-blue" @click="recargarTabla('General')"><i class="fa-solid fa-rotate"></i></a>
                        </div>
                    </div>
                </div>                
                <div class="card-body px-0 pt-0 pb-2" style="min-height: 400px">
                    <div class="container">
                        <div class="table-responsive p-0">
                            <div v-show="mostrarCarga" class="loader-sm"></div>
                            <table v-show="mostrarTabla" class="table align-items-center mb-0" id="MyTableSolicitudes">
                                <thead>
                                <tr style="color: black">
                                    <th></th>
                                    <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                                    <th class="text-uppercase text-xs font-weight-bolder">Fecha</th>
                                    <th class="text-uppercase text-xs font-weight-bolder">Usuario</th>
                                    <th class="text-uppercase text-xs font-weight-bolder">Cantidad Total</th>
                                    <th class="text-uppercase text-xs font-weight-bolder">Acciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="solicitudPrecio in solicitudesPrecios" :key="solicitudPrecio.id">
                                    <td></td>
                                    <td>
                                        <p class="text-sm font-weight-bolder mb-0" style="color: black">{{ solicitudPrecio.id }}</p>
                                    </td>
                                    <td>
                                        <p class="text-sm mb-0">{{ thisDate(solicitudPrecio.fecha) }}</p>
                                    </td>
                                    <td>
                                        <p class="text-sm mb-0">{{ solicitudPrecio.usuario.name }}</p>
                                    </td>
                                    <td>
                                        <p class="text-sm mb-0">{{ solicitudPrecio.cantidadTotal }}</p>
                                    </td>
                                    <td class="align-middle text-end">
                                        <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                            <a class="btn btn-success btn-xs" title="Detalle" @click="solicitudPrecioDetalle(solicitudPrecio)"><i class="fa-regular fa-eye"></i></a>
                                            &nbsp;
                                            <a v-if="can('Gestión Tienda_Solicitar Precios Borrar')" class="btn btn-closed btn-xs" title="Eliminar" @click="deleteSolicitudPrecio(solicitudPrecio.id)"><i class="fa fa-fw fa-trash"></i></a>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <modalconfirmacion ref="confirmation"></modalconfirmacion>
            <modalalerta ref="alert"></modalalerta>
            <modalSolicitarPrecios v-if="showSolicitarPrecios" @close="showSolicitarPrecios = false" @recarga="recargarTabla('General')"></modalSolicitarPrecios>
            <modalDetallePrecios v-bind:solicitudPrecio="soliDetalle" v-if="showDetalleSoliPrecio" @close="showDetalleSoliPrecio = false"></modalDetallePrecios>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import modalconfirmacion from '../../snippets/sistema/alerts/confirmationModal.vue'
import modalalerta from '../../snippets/sistema/alerts/alertModal.vue'
import modalSolicitarPrecios from '../../snippets/tiendas/gestionTienda/solicitarPrecios/soliPreciosModal.vue'
import modalDetallePrecios from '../../snippets/tiendas/gestionTienda/solicitarPrecios/detalleSoliPreciosModal.vue'

import {mixin} from '../../../mixins.js'

export default {
    components: { modalconfirmacion, modalalerta, modalSolicitarPrecios, modalDetallePrecios },
    mixins: [mixin],
    data(){
        return{
            solicitudesPrecios: [],
            solicitudesPreciosAux: [],
            mostrarTabla: false,
            mostrarCarga: true,
            showSolicitarPrecios: false,
            showIngresarDeposito: false,
            showDetalleSoliPrecio: false,
            table: null,
            fechaInicio: null,
            fechaTermino: null,
        }
    },
    created(){
        this.getSolicitudesPrecios();
    },
    methods: {
        getSolicitudesPrecios(){
            axios.get('api/solicitarPrecios').then( response =>{
                this.solicitudesPrecios = response.data;
                this.solicitudesPreciosAux = response.data;
                if (this.table != null){
                    this.table.clear();
                    this.table.destroy();
                }
                this.crearTabla('#MyTableSolicitudes');
            }).catch(e=> console.log(e))
        },
        recargarTabla($tipoRecarga){
            this.mostrarCarga = true;
            if($tipoRecarga == 'General'){
                this.solicitudesPrecios = null;
                this.getSolicitudesPrecios();
                this.fechaInicio = null;
                this.fechaTermino = null;
            }else{
                this.table.destroy();
                this.crearTabla('#MyTableSolicitudes');
            }
        },
        filtrarFecha(){
            if (this.fechaInicio == null || this.fechaTermino == null){
                this.recargarTabla('General');
            }else{
                let start = new Date(this.fechaInicio);
                let end = new Date(this.fechaTermino);
                this.solicitudesPrecios = this.solicitudesPreciosAux.filter(item => {
                    let date = new Date(item.fecha);
                    return date >= start && date <= end;
                });
                this.recargarTabla();
            }
        },
        solicitudPrecioDetalle(solicitudPrecio){
            this.soliDetalle = solicitudPrecio;
            this.showDetalleSoliPrecio = true;
        },
        cierreCajaDeposito(cierreCaja){
            this.cierreDeposito = cierreCaja;
            this.showIngresarDeposito = true;
        },
        async deleteSolicitudPrecio(id) {
            const ok = await this.$refs.confirmation.show({
                title: 'Borrar Solicitud de Precios',
                message: `¿Está seguro/a que desea eliminar este Solicitud de Precios? Esta acción no puede ser desecha.`,
                okButton: 'Eliminar',
                cancelButton: 'Volver'
            })
            if (ok) {
                axios.delete(`api/solicitarPrecios/${id}`).then( response =>{
                    this.toast.success("Solicitud de Precios eliminado con éxito!", {
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
                    this.recargarTabla('General');
                }).catch(e=> console.log(e))
            }
        },
    }
}
</script>