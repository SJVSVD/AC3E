<template>
<div class="row mt-4 mx-4">
    <div class="col-12">
        <div class="card mb-4">
            <div class="row pb-0 p-4">
                <div class="col-4">
                    <div class="row">
                        <div class="col-5">
                            <label>Fecha Inicio Doc.:
                                <input type="date" class="form-control form-control-sm" placeholder="Fecha Inicial" v-model="fechaInicio">
                            </label>   
                        </div>
                        <div class="col-5">
                            <label>Fecha Término Doc.:
                                <input type="date" class="form-control form-control-sm" placeholder="Fecha Termino" v-model="fechaTermino">
                            </label>
                        </div>
                        <div class="col-auto pt-3">
                            <a class="btn btn-search-blue" @click="filtrarFecha()"><i class="fa-solid fa-magnifying-glass"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-8">
                    <div class="d-flex justify-content-end">
                        <button v-if="is('Contabilidad_Jefatura') && can('Facturas_Crear')" class="btn btn-spacing btn-insist" title="Enviar Recordatorio/Insistencia Masiva" :disabled="buttonDisableInsistenciaGlobal" @click="sendEmailsInsistenciaGlobal()"><i class="fa-solid fa-triangle-exclamation"></i></button>
                        &nbsp;
                        <a v-if="can('Facturas_Pagar')" class="btn btn-spacing btn-dark" id="show-pagarModal" @click="showPagarFacturas = true"><i class="fa-solid fa-file-invoice-dollar"></i> Pagar </a>
                        &nbsp;
                        <a v-if="can('Facturas_Autorizar_Pagos')" class="btn btn-spacing btn-closed" id="show-autorizarPagosModal" @click="showAutorizarPagosFacturas = true"><i class="fa-solid fa-file-signature"></i> Autorizar Pagos</a>
                        &nbsp;
                        <a v-if="can('Facturas_Autorizar_Gastos')" class="btn btn-spacing btn-closed" id="show-autorizarGastosModal" @click="showAutorizarGastosFacturas = true"><i class="fa-solid fa-file-signature"></i> Autorizar Gastos</a>
                        &nbsp;
                        <a v-if="is('Contabilidad_Jefatura') && can('Facturas_Crear')" class="btn btn-spacing btn-alert" id="show-uploadDataModal" @click="showUploadDataFacturas = true"><i class="fa-solid fa-upload"></i> Subir Datos</a>
                        &nbsp;
                        <a v-if="is('Contabilidad_Jefatura') && can('Facturas_Crear')" class="btn btn-spacing btn-alert" id="show-uploadDataModal" @click="showUploadReferencesFacturas = true"><i class="fa-solid fa-file-import"></i> Subir Referencias</a>
                        &nbsp;
                        <a class="btn btn-spacing btn-search-blue" @click="recargarTabla('General')"><i class="fa-solid fa-rotate"></i></a>
                    </div>
                </div>
                <!-- <div class="dropdown col-3">
                    <a class="btn btn-primary" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                        Mostrar/Ocultar Columnas:
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                        <li><a class="dropdown-item toggle-vis" data-column="3" href="#">Identificador</a></li>
                        <li><a class="dropdown-item toggle-vis" data-column="5" href="#">Razón Social</a></li>
                        <li><a class="dropdown-item toggle-vis" data-column="7" href="#">Tipo Documento</a></li>
                        <li><a class="dropdown-item toggle-vis" data-column="8" href="#">Fecha Documento</a></li>
                        <li><a class="dropdown-item toggle-vis" data-column="9" href="#">Monto Neto</a></li>
                        <li><a class="dropdown-item toggle-vis" data-column="10" href="#">Monto IVA Recup.</a></li>
                        <li><a class="dropdown-item toggle-vis" data-column="11" href="#">Monto IVA no Recup.</a></li>
                        <li><a class="dropdown-item toggle-vis" data-column="12" href="#">Monto Total</a></li>
                        <li><a class="dropdown-item toggle-vis" data-column="13" href="#">Fecha Autorización</a></li>
                    </ul>
                </div> -->
            </div>
            <div class="card-body px-0 pt-0 pb-2" style="min-height: 400px">
                <div class="container">
                    <div class="table-responsive p-0">
                        <div v-show="mostrarCarga" class="loader-sm"></div>
                        <table v-show="mostrarTabla" class="table align-items-center mb-0" id="MyTableFacturas">
                            <thead>
                                <tr style="color: black">
                                    <th></th>
                                    <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                                    <th class="text-uppercase text-xs font-weight-bolder">Estado</th>
                                    <th class="text-uppercase text-xs font-weight-bolder">Empresa</th>
                                    <th class="text-uppercase text-xs font-weight-bolder">Identificador</th>
                                    <th class="text-uppercase text-xs font-weight-bolder">Rut</th>
                                    <th class="text-uppercase text-xs font-weight-bolder">Razón Social</th>
                                    <th class="text-uppercase text-xs font-weight-bolder">Folio</th>
                                    <th class="text-uppercase text-xs font-weight-bolder">Tipo Documento</th>
                                    <th class="text-uppercase text-xs font-weight-bolder">Fecha Documento</th>
                                    <th class="text-uppercase text-xs font-weight-bolder">Monto Neto</th>
                                    <th class="text-uppercase text-xs font-weight-bolder">Monto IVA Recup.</th>
                                    <th class="text-uppercase text-xs font-weight-bolder">Monto IVA no Recup.</th>
                                    <th class="text-uppercase text-xs font-weight-bolder">Monto Total</th>
                                    <th class="text-uppercase text-xs font-weight-bolder">Fecha Autorización Gasto</th>
                                    <th class="text-uppercase text-xs font-weight-bolder">Fecha Autorización Pago</th>
                                    <!-- <th class="text-uppercase text-xs font-weight-bolder">Objeciones</th> -->
                                    <th class="text-uppercase text-xs font-weight-bolder">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="factura in facturas" :key="factura.id">
                                    <td></td>
                                    <td>
                                        <p class="text-sm font-weight-bolder mb-0" style="color: black">{{ factura.id }}</p>
                                    </td>
                                    <td>
                                        <p v-if="factura.estado == 'No Autorizable'" class="text-sm font-weight-bolder mb-0" style="color: #1520A6;">{{ factura.estado }}</p>
                                        <p v-if="factura.estado == 'Pendiente'" class="text-sm font-weight-bolder mb-0" style="color: #FFC107">{{ factura.estado }}</p>
                                        <p v-if="factura.estado == 'Gasto Rechazado'" class="text-sm font-weight-bolder mb-0" style="color: #B80F0A">{{ factura.estado }}</p>
                                        <p v-if="factura.estado == 'Pago Rechazado'" class="text-sm font-weight-bolder mb-0" style="color: #B80F0A">{{ factura.estado }}</p>
                                        <p v-if="factura.estado == 'Gasto Autorizado'" class="text-sm font-weight-bolder mb-0" style="color: #28A745">{{ factura.estado }}</p>
                                        <p v-if="factura.estado == 'Pago Autorizado'" class="text-sm font-weight-bolder mb-0" style="color: #28A745">{{ factura.estado }}</p>
                                        <p v-if="factura.estado == 'Pagado'" class="text-sm font-weight-bolder mb-0" style="color: #8392AB">{{ factura.estado }}</p>
                                    </td>
                                    <td>
                                        <p class="text-sm mb-0">{{ factura.empresa.razonSocial }}</p>
                                    </td>
                                    <td>
                                        <p class="text-sm mb-0">{{ factura.identificador }}</p>
                                    </td>
                                    <td>
                                        <p class="text-sm font-weight-bolder mb-0" style="color: black">{{ formatoRut(factura.rutProveedor) }}</p>
                                    </td>
                                    <td>
                                        <p :title="factura.razonSocial" class="text-sm mb-0" style="width: 250px; word-wrap: break-word; overflow: hidden; text-overflow: ellipsis;">{{ factura.razonSocial }}</p>
                                    </td>
                                    <td>
                                        <p class="text-sm font-weight-bolder mb-0" style="color: black">{{ factura.folio }}</p>
                                    </td>
                                    <td>
                                        <p :title="buscarTipoDoc(tiposDocFactura, factura.tipoDocumento)" class="text-sm mb-0" style="width: 200px; word-wrap: break-word; overflow: hidden; text-overflow: ellipsis;">{{factura.tipoDocumento}} - {{ buscarTipoDoc(tiposDocFactura, factura.tipoDocumento) }}</p>
                                    </td>
                                    <td>
                                        <p class="text-sm mb-0">{{ thisDate(factura.fechaDcto) }}</p>
                                    </td>
                                    <td>
                                        <p class="text-sm mb-0">{{ formatMoneyCLP(factura.montoNeto) }}</p>
                                    </td>
                                    <td>
                                        <p class="text-sm mb-0">{{ formatMoneyCLP(factura.montoIVARec) }}</p>
                                    </td>
                                    <td>
                                        <p class="text-sm mb-0">{{ formatMoneyCLP(factura.montoIVANoRec) }}</p>
                                    </td>
                                    <td>
                                        <p class="text-sm mb-0">{{ formatMoneyCLP(factura.montoTotal) }}</p>
                                    </td>
                                    <td>
                                        <p class="text-sm mb-0">{{ thisDate(factura.fechaAuthGasto) }}</p>
                                    </td>
                                    <td>
                                        <p class="text-sm mb-0">{{ thisDate(factura.fechaAuthPago) }}</p>
                                    </td>
                                    <!-- <td>
                                        <textarea readonly class="form-control" style="text-align: justify; min-width: 270px; height: 40px word-wrap: break-word; overflow: hidden;" v-model="factura.objeciones"></textarea>
                                    </td> -->
                                    <td class="align-middle text-end">
                                        <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                            <a class="btn btn-success btn-xs" title="Detalle" @click="facturaDetalle(factura)"><i class="fa-regular fa-eye"></i></a>
                                            &nbsp;
                                            <a v-if="factura.url != null" :href=factura.url  target="_blank" class="btn btn-purple btn-xs"><i class="fa-solid fa-file-invoice"></i></a>
                                            <a v-else class="btn btn-grey btn-xs"><i class="fa-solid fa-file-invoice"></i></a>
                                            &nbsp;
                                            <a v-if="is('Contabilidad_Jefatura') && can('Facturas_Modificar')" class="btn btn-closed btn-xs" title="Eliminar" @click="deleteFactura(factura)"><i class="fa fa-fw fa-trash"></i></a>
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
            <div v-if="is('Contabilidad_Jefatura')" class="col-4 align-left text-center float-end">
                <a class="btn btn-primary" @click="handleAccionTipoEmpresa('facturasExportPropuesta')"><i class="fa fa-fw fa-lg fa-solid fa-download"></i> Exportar Propuesta</a>
            </div>
            <div v-if="is('Contabilidad_Jefatura')" class="col-4 align-left text-center float-end">
                <a class="btn btn-primary" @click="handleAccionTipoEmpresa('facturasExportGastoAutorizado')"><i class="fa fa-fw fa-lg fa-solid fa-download"></i> Exportar con Autorización de Pago Pendiente</a>
            </div>
            <div v-if="is('Contabilidad_Jefatura')" class="col-4 align-left text-center float-end">
                <a class="btn btn-primary" @click="handleAccionTipoEmpresa('facturasExport')"><i class="fa fa-fw fa-lg fa-solid fa-download"></i> Exportar TODO</a>
            </div>
        </div>
        <modalconfirmacion ref="confirmation"></modalconfirmacion>
        <modalalerta ref="alert"></modalalerta>
        <modalselectcompany v-bind:accion="accionTipoEmpresa" v-if="showSelectCompany" @close="showSelectCompany = false"></modalselectcompany>
        <modaluploaddata v-if="showUploadDataFacturas" @close="showUploadDataFacturas = false" @recarga="recargarTabla('General')"></modaluploaddata>
        <modaluploadreferences v-if="showUploadReferencesFacturas" @close="showUploadReferencesFacturas = false" @recarga="recargarTabla('General')"></modaluploadreferences>
        <modalpagar v-if="showPagarFacturas" @close="showPagarFacturas = false" @recarga="recargarTabla('General')"></modalpagar>
        <modalautorizargastos v-if="showAutorizarGastosFacturas" @close="showAutorizarGastosFacturas = false" @recarga="recargarTabla('General')"></modalautorizargastos>
        <modalautorizarpagos v-if="showAutorizarPagosFacturas" @close="showAutorizarPagosFacturas = false" @recarga="recargarTabla('General')"></modalautorizarpagos>
        <modaldetalle v-bind:factura="facturaPagos" v-if="showDetallesFactura" @close="showDetallesFactura = false"></modaldetalle>
    </div>
</div>
</template>

<script>
import axios from 'axios'
import modalautorizargastos from '../../snippets/contabilidad/facturas/autorizarGastosModal.vue'
import modalautorizarpagos from '../../snippets/contabilidad/facturas/autorizarPagosModal.vue'
import modaldetalle from '../../snippets/contabilidad/facturas/detalleFacturasModal.vue'
import modaluploaddata from '../../snippets/contabilidad/facturas/uploadDataModal.vue'
import modaluploadreferences from '../../snippets/contabilidad/facturas/uploadReferencesModal.vue'
import modalselectcompany from '../../snippets/selectCompanyModal.vue'
import modalpagar from '../../snippets/contabilidad/facturas/pagarModal.vue'
import modalconfirmacion from '../../snippets/sistema/alerts/confirmationModal.vue'
import modalalerta from '../../snippets/sistema/alerts/alertModal.vue'
import {mixin} from '../../../mixins.js'

export default {
    components: { modalpagar, modalconfirmacion, modalalerta, modalselectcompany, modalautorizargastos, modalautorizarpagos, modaldetalle, modaluploaddata, modaluploadreferences },
    mixins: [mixin],
    data(){
        return{
            facturas: null,
            facturasAux: null,
            tiposDocFactura: null,
            estadoTipoDoc: null,
            showSelectCompany: false,
            showAutorizarGastosFacturas: false,
            showAutorizarPagosFacturas: false,
            showDetallesFactura: false,
            showUploadDataFacturas: false,
            showUploadReferencesFacturas: false,
            showPagarFacturas: false,
            showCrearFactura: false,
            showEditarFactura: false,
            facturasEditar: null,
            facturaPagos: null,
            mostrarTabla: false,
            table: null,
            mostrarCarga: true,
            fechaInicio: null,
            accionTipoEmpresa: null,
            fechaTermino: null,
            buttonDisableInsistenciaGlobal: false,
            timer: null,
            tiempo_actual: null,
        }
    },
    created(){
        this.getTipoDocFacturas();
        this.getFacturas(this.userID);
        this.getTimers();
    },
    methods: {
        handleAccionTipoEmpresa(accion){
            this.accionTipoEmpresa = accion;
            this.showSelectCompany = true;
        },
        getTimers(){
            let data = {
                identificadorEspecifico: null,
                timerName: 'insistenciaFacturaGlobal',
                sistema: 'Facturas',
                userID: this.userID,
            }
            axios.post(`api/usuarios/checkTimer`, data).then( response =>{
                this.timer = response.data;
            }).catch(e=> console.log(e))
        },
        async sendEmailsInsistenciaGlobal(){
            this.tiempo_actual = new Date();
            if (this.timer != []){
                if (new Date(this.agregarMinutos(new Date(this.timer.updated_at), this.timer.timeToWait)).getTime() < new Date(this.tiempo_actual).getTime()){
                    const ok = await this.$refs.confirmation.show({
                        title: 'Enviar Correos',
                        message: `¿Está seguro/a que desea enviar un correo a cada uno de los autorizadores con un resumen de sus respectivas facturas pendientes de autorización? Esta acción no puede ser desecha.`,
                        okButton: 'Enviar',
                        cancelButton: 'Volver'
                    })
                    if (ok) {
                        this.buttonDisableInsistenciaGlobal = true;
                        axios.post('api/facturas/sendEmailsInsistenciaMasivo', this.facturas).then( response => {
                            this.toast.success("Correos de insistencia enviados!", {
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
                        }).catch(e=> {console.log(e)});
                        let cronometrito = {
                            browser: navigator.userAgent,
                        }
                        var id = this.timer.id;
                        axios.put(`api/timers/${id}`, cronometrito).then((result) => {
                            this.buttonText = 'Guardando...';
                        }).catch((e)=> {console.log(e)});
                    }
                }
                else{
                    var tiempoFaltante = new Date(this.agregarMinutos(new Date(this.timer.updated_at), this.timer.timeToWait)).getTime() - new Date(this.tiempo_actual).getTime();
                    tiempoFaltante = new Date(tiempoFaltante).getHours() + ' horas y ' + new Date(tiempoFaltante).getMinutes() + ' minutos';
                    this.toast.warning(`No puede enviar la insistencia masiva hasta dentro de ${tiempoFaltante} más.`, {
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
            }
            else{
                const ok = await this.$refs.confirmation.show({
                    title: 'Enviar Correos',
                    message: `¿Está seguro/a que desea enviar un correo a cada uno de los autorizadores con un resumen de sus respectivas facturas pendientes de autorización? Esta acción no puede ser desecha.`,
                    okButton: 'Enviar',
                    cancelButton: 'Volver'
                })
                if (ok) {
                    this.buttonDisableInsistenciaGlobal = true;
                    axios.post('api/facturas/sendEmailsInsistenciaMasivo', this.facturas).then( response => {
                        this.toast.success("Correos de insistencia enviados!", {
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
                    }).catch(e=> {console.log(e)});
                    let cronometrito = {
                        sistema: 'Facturas',
                        identificadorEspecifico: null,
                        idUsuario: this.userID,
                        timerName: 'insistenciaFacturaGlobal',
                        timeToWait: 1620,
                        browser: navigator.userAgent,
                    }
                    axios.post("api/timers", cronometrito).then((result) => {
                        this.buttonText = 'Guardando...';
                    }).catch((e)=> {console.log(e)})
                }
            }
        },
        filtrarFecha(){
            if (this.fechaInicio == null || this.fechaTermino == null){
                this.recargarTabla('General');
            }else{
                let start = new Date(this.fechaInicio);
                let end = new Date(this.fechaTermino);
                this.facturas = this.facturasAux.filter(item => {
                    let date = new Date(item.fechaDcto);
                    return date >= start && date <= end;
                });
                this.recargarTabla();
            }
        },
        recargarTabla($tipoRecarga){
            this.mostrarCarga = true;
            if($tipoRecarga == 'General'){
                this.facturas = null;
                this.getFacturas(this.userID);
                this.fechaInicio = null;
                this.fechaTermino = null;
            }else{
                this.table.destroy();
                this.crearTabla('#MyTableFacturas');
            }
        },
        buscarTipoDoc(tiposDocFac, facTipoDoc){    
            var nombre = null;
            tiposDocFac.forEach(tipoDocFac => {
                if(facTipoDoc == tipoDocFac.id){
                    nombre = tipoDocFac.name;
                }
            });
            if (nombre == null){
                nombre = "No encontrado.";
            }
            return nombre;
        },
        getTipoDocFacturas(){
            axios.get('api/tipoDocFactura').then( response =>{
                this.tiposDocFactura = response.data;
            }).catch(e=> console.log(e))
        },
        getFacturas(userID){
            axios.get(`api/facturas/${userID}`).then( response =>{
                this.facturas = response.data;
                this.facturasAux = response.data;
                if (this.table != null){
                        this.table.clear();
                        this.table.destroy();
                    }
                this.crearTabla('#MyTableFacturas');
            }).catch(e=> console.log(e))
        },
        facturaDetalle(factura){
            this.facturaPagos = factura;
            this.showDetallesFactura = true;
        },
        checkTipoDocumento($tipoDoc){
            this.tiposDocFactura.forEach(tipoDocFactura => {
                if ($tipoDoc == tipoDocFactura.id){
                    this.estadoTipoDoc = tipoDocFactura.name;
                }
            });
        },
        async deleteFactura(factura) {
            const ok = await this.$refs.confirmation.show({
                title: 'Borrar Factura',
                message: `¿Está seguro/a que desea eliminar la factura con Rut '${factura.rutProveedor}' y Folio '${factura.folio}'? Esta acción no puede ser desecha.`,
                okButton: 'Eliminar',
                cancelButton: 'Volver'
            })
            if (ok) {
                axios.delete(`api/facturas/${factura.id}`).then( response =>{
                    this.toast.success("Factura eliminada con éxito!", {
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