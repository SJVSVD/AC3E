<template>
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="row pb-0 p-4">
                    <div class="col-9">
                        <div class="row">
                            <div class="col-2">
                                <label>Fecha Inicio:
                                    <input type="date" class="form-control form-control-sm" placeholder="Fecha Inicial" v-model="fechaInicio">
                                </label>   
                            </div>
                            <div class="col-2">
                                <label>Fecha Término:
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
                            <!-- <a v-if="can('Gestión Tienda_NCV_Crear')" class="btn btn-spacing btn-continue" id="show-modal1" @click="showNuevoNVC = true">Nuevo NCV</a> -->
                            <a v-if="can('Gestión Tienda_NCV_Autorizar')" class="btn btn-spacing btn-closed" id="show-autorizarModal" @click="showAutorizarPago = true">Auth Pagos</a>
                            &nbsp;
                            <a v-if="can('Gestión Tienda_NCV_Crear')" class="btn btn-spacing btn-continue" @click="verificarRegistros()"><i class="fa-solid fa-square-plus"></i></a>
                            &nbsp;
                            <a class="btn btn-spacing btn-search-blue" @click="recargarTabla('General')"><i class="fa-solid fa-rotate"></i></a>
                        </div>
                    </div>
                </div>                
                <div class="card-body px-0 pt-0 pb-2" style="min-height: 400px">
                    <div class="container">
                        <div class="table-responsive p-0">
                            <div v-show="mostrarCarga" class="loader-sm"></div>
                            <table v-show="mostrarTabla" class="table align-items-center mb-0" id="MyTableNCVs">
                                <thead>
                                <tr style="color: black">
                                    <th></th>
                                    <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                                    <th class="text-uppercase text-xs font-weight-bolder">Estado</th>
                                    <th class="text-uppercase text-xs font-weight-bolder">Fecha</th>
                                    <th class="text-uppercase text-xs font-weight-bolder">Tienda</th>
                                    <th class="text-uppercase text-xs font-weight-bolder">Nº NCV</th>
                                    <th class="text-uppercase text-xs font-weight-bolder">Nº Boleta</th>
                                    <th class="text-uppercase text-xs font-weight-bolder">Monto NCV</th>
                                    <th class="text-uppercase text-xs font-weight-bolder">Monto Boleta</th>
                                    <th class="text-uppercase text-xs font-weight-bolder">Cantidad Art NCV</th>
                                    <th class="text-uppercase text-xs font-weight-bolder">Cantidad Art Boleta</th>
                                    <th class="text-uppercase text-xs font-weight-bolder">Acciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="ncv in NCVs" :key="ncv.id">
                                    <td></td>
                                    <td>
                                        <p class="text-sm font-weight-bolder mb-0" style="color: black">{{ ncv.id }}</p>
                                    </td>
                                    <td>
                                        <p v-if="ncv.estado == 'Ingresado'" class="text-sm font-weight-bolder mb-0" style="color: #8392AB">{{ ncv.estado }}</p>
                                        <p v-if="ncv.estado == 'Rechazado'" class="text-sm font-weight-bolder mb-0" style="color: #ff071c">{{ ncv.estado }}</p>
                                        <p v-if="ncv.estado == 'Procesado'" class="text-sm font-weight-bolder mb-0" style="color: #FFC107">{{ ncv.estado }}</p>
                                        <p v-if="ncv.estado == 'Pagado'" class="text-sm font-weight-bolder mb-0" style="color: #28A745">{{ ncv.estado }}</p>
                                    </td>
                                    <td>
                                        <p class="text-sm mb-0">{{ ncv.fecha }}</p>
                                    </td>
                                    <td>
                                        <p class="text-sm mb-0">{{ ncv.tienda.nombre }}</p>
                                    </td>
                                    <td>
                                        <p class="text-sm mb-0">{{ ncv.notaCredito }}</p>
                                    </td>
                                    <td>
                                        <p class="text-sm mb-0">{{ ncv.boleta }}</p>
                                    </td>
                                    <td>
                                        <p class="text-sm mb-0">{{ formatMoneyCLP(ncv.montoNotaCredito) }}</p>
                                    </td>
                                    <td>
                                        <p class="text-sm mb-0">{{ formatMoneyCLP(ncv.montoBoleta) }}</p>
                                    </td>
                                    <td>
                                        <p class="text-sm mb-0">{{ ncv.cantidadArtNCV }}</p>
                                    </td>
                                    <td>
                                        <p class="text-sm mb-0">{{ ncv.cantidadArtBoleta }}</p>
                                    </td>
                                    <td class="align-middle text-end">
                                        <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                            <a v-if="ncv.motivo == null" @click="agregarDetalle(ncv)" title="Agregar Detalle" class="btn btn-purple btn-xs"><i class="fa-solid fa-file-signature"></i></a>
                                            <a v-else class="btn btn-success btn-xs" title="Detalle" @click="ncvDetalle(ncv)"><i class="fa-regular fa-eye"></i></a>
                                            &nbsp;
                                            <a v-if="ncv.estado == 'Pagado' && ncv.imagen == null && can('Gestión Tienda_Supervision')" class="btn btn-alert btn-xs" title="Agregar Imagen" @click="agregarImagen(ncv)"><i class="fa-solid fa-image"></i></a>
                                            <a v-if="ncv.estado == 'Pagado' && ncv.imagen != null" class="btn btn-search-blue btn-xs" title="Ver Imagen" @click="verImagen(ncv.imagen)"><i class="fa-solid fa-image"></i></a>
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
            <modalautorizarPago v-if="showAutorizarPago" @close="showAutorizarPago = false" @recarga="recargarTabla('General')"></modalautorizarPago>
            <modalNuevoNCV v-if="showNuevoNVC" @close="showNuevoNVC = false" @recarga="recargarTabla('General')"></modalNuevoNCV>
            <modalDetalleNCV v-bind:ncv="ncvDetalle1" v-if="showDetalleNCV" @close="showDetalleNCV = false"></modalDetalleNCV>
            <modalAgregarImagen v-bind:ncvID1="ncvID" v-if="showAgregarImagen" @close="showAgregarImagen = false"></modalAgregarImagen>
            <modalAgregarDetalle v-bind:ncv="ncv" v-if="showAgregarDetalle" @close="showAgregarDetalle = false" @recarga="recargarTabla('General')"></modalAgregarDetalle>
            <modalfoto v-bind:detalle="ncvFoto" v-if="showFotoNCV" @close="showFotoNCV = false"></modalfoto>       
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import modalconfirmacion from '../../snippets/sistema/alerts/confirmationModal.vue';
import modalalerta from '../../snippets/sistema/alerts/alertModal.vue';
import modalNuevoNCV from '../../snippets/tiendas/gestionTienda/NCV/crearNCVModal.vue';
import modalDetalleNCV from '../../snippets/tiendas/gestionTienda/NCV/detalleNCV.vue';
import modalautorizarPago from '../../snippets/tiendas/gestionTienda/NCV/autorizarPagoModal.vue';
import modalAgregarImagen from '../../snippets/tiendas/gestionTienda/NCV/agregarImagenModal.vue';
import modalAgregarDetalle from '../../snippets/tiendas/gestionTienda/NCV/agregarDetalleNCV.vue';
import modalfoto from '../../snippets/fotoModal.vue'
import {mixin} from '../../../mixins.js'

let user = document.head.querySelector('meta[name="user"]');
export default {
    components: { modalfoto,modalconfirmacion, modalalerta, modalNuevoNCV, modalDetalleNCV, modalAgregarDetalle, modalautorizarPago, modalAgregarImagen },
    mixins: [mixin],
    data(){
        return{
            idUsuario: JSON.parse(user.content).id,
            NCVs: [],
            NCVsAux: [],
            mostrarTabla: false,
            mostrarCarga: true,
            showNuevoNVC: false,
            showAutorizarPago: false,
            ncvDetalle1: null,
            showDetalleNCV: false,
            showAgregarDetalle: false,
            showAgregarImagen: false,
            showFotoNCV: false,
            ncv: null,
            ncvID: null,
            ncvFoto: null,
            table: null,
            fechaInicio: null,
            fechaTermino: null,
        }
    },
    created(){
        this.getNCVs(this.idUsuario);
    },
    methods: {
        verImagen(ncvFoto){
            this.ncvFoto = ncvFoto;
            this.showFotoNCV = true;
        },
        agregarImagen(ncv){
            this.ncvID = ncv.id;
            this.showAgregarImagen = true;
        },
        agregarDetalle(ncv){
            this.ncv = ncv;
            this.showAgregarDetalle = true;
        },
        async verificarRegistros(){
            var contadorRegistros = await axios.get('api/verificarRegistros').then(function(response) {
                return response.data;
            }.bind(this)).catch(function(e) {
                console.log(e);
            });
            if (contadorRegistros > 0){
                this.toast.success('Se han encontrado '+ contadorRegistros + ' Nuevos', {
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
                setTimeout(() => {this.recargarTabla('General')}, 1500);            
            }else{
                this.toast.warning('No se han encontrado registros nuevos', {
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
            }
        },
        getNCVs(id){
            axios.get(`api/ncv/${id}`).then( response =>{
                this.NCVs = response.data;
                this.NCVsAux = response.data;
                if (this.table != null){
                    this.table.clear();
                    this.table.destroy();
                }
                this.crearTabla('#MyTableNCVs');
            }).catch(e=> console.log(e))
        },
        recargarTabla($tipoRecarga){
            this.mostrarCarga = true;
            if($tipoRecarga == 'General'){
                this.NCVs = null;
                this.getNCVs(this.idUsuario);
                this.fechaInicio = null;
                this.fechaTermino = null;
            }else{
                this.table.destroy();
                this.crearTabla('#MyTableNCVs');
            }
        },
        filtrarFecha(){
            if (this.fechaInicio == null || this.fechaTermino == null){
                this.recargarTabla('General');
            }else{
                let start = new Date(this.fechaInicio);
                let end = new Date(this.fechaTermino);
                this.NCVs = this.NCVsAux.filter(item => {
                    let date = new Date(item.fecha);
                    return date >= start && date <= end;
                });
                this.recargarTabla();
            }
        },
        ncvDetalle(NCV){
            this.ncvDetalle1 = NCV;
            this.showDetalleNCV = true;
        },
        async deleteNCV(id) {
            const ok = await this.$refs.confirmation.show({
                title: 'Borrar Nota de Crédito',
                message: `¿Está seguro/a que desea eliminar este Nota de Crédito? Esta acción no puede ser desecha.`,
                okButton: 'Eliminar',
                cancelButton: 'Volver'
            })
            if (ok) {
                axios.delete(`api/ncv/${id}`).then( response =>{
                    this.toast.success("Nota de Crédito eliminado con éxito!", {
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