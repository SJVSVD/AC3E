<template>
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="row pb-0 p-4">
                    <div class="col-9">
                        <div class="row">
                            <div class="col-auto">
                                <label>Año:
                                    <select class="form-control form-control-sm" placeholder="Año" v-model="fechaAño">
                                        <option value="">Seleccione un año:</option>
                                        <option v-for="año in años" v-bind:key="año.value" v-bind:value="año.value">
                                            {{ año.value }}
                                        </option>
                                    </select>
                                </label>   
                            </div>
                            <div class="col-auto">
                                <label>Mes:
                                    <select class="form-control form-control-sm" placeholder="Mes" v-model="fechaMes">
                                        <option value="">Seleccione un mes:</option>
                                        <option v-for="mes in meses" v-bind:key="mes.value" v-bind:value="mes.value">
                                            {{ mes.nombre }}
                                        </option>
                                    </select>
                                </label>
                            </div>
                            <div class="col-auto">
                                <label>Día:
                                    <select class="form-control form-control-sm" placeholder="Día" v-model="fechaDia">
                                        <option value="">Seleccione un día:</option>
                                        <option v-for="dia in dias" v-bind:key="dia.value" v-bind:value="dia.value">
                                            {{ dia.value }}
                                        </option>
                                    </select>
                                </label>   
                            </div>
                            <div v-if="is('Contabilidad')" class="col-auto">
                                <label>Tienda:
                                    <select class="form-control form-control-sm" placeholder="Tienda" v-model="filtroTienda">
                                        <option value="">Seleccione una tienda:</option>
                                        <option v-for="tienda in tiendas" v-bind:key="tienda.id" v-bind:value="tienda.id">
                                            {{ tienda.nombre }}
                                        </option>
                                    </select>
                                </label>    
                            </div>
                            <div class="col-auto pt-3">
                                <a class="btn btn-search-blue" @click="filtrarFecha()"><i class="fa-solid fa-magnifying-glass"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="d-flex justify-content-end">
                            <a v-if="can('Gestión Tienda_Cierre Cajas')" class="btn btn-spacing btn-continue" id="show-modal1" @click="showIngresarCierreCaja = true">Ingresar Cierre Caja</a>
                            &nbsp;
                            <a class="btn btn-spacing btn-search-blue" @click="recargarTabla('General')"><i class="fa-solid fa-rotate"></i></a>
                        </div>
                    </div>
                </div>                
                <div class="card-body px-0 pt-0 pb-2" style="min-height: 400px">
                    <div class="container">
                        <div class="table-responsive p-0">
                            <div v-show="mostrarCarga" class="loader-sm"></div>
                            <table v-show="mostrarTabla" class="table align-items-center mb-0" id="MyTableCierreCajas">
                                <thead>
                                    <tr style="color: black">
                                        <th></th>
                                        <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Tienda</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Fecha Día Cierre</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Tarjeta</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Efectivo</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Fondo Fijo</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Folio Fondo Fijo Referencial</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Nota de Crédito</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Otros Montos</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Venta Total</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Venta Bruta</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Depósito</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Fecha Depósito</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Diferencia Días</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Control</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <tr v-for="cierreCaja in cierresCajas" :key="cierreCaja.id">
                                    <td></td>
                                    <td>
                                        <p class="text-sm font-weight-bolder mb-0" style="color: black">{{ cierreCaja.id }}</p>
                                    </td>
                                    <td>
                                        <p class="text-sm mb-0">{{ cierreCaja.tienda.nombre }}</p>
                                    </td>
                                    <td>
                                        <p class="text-sm mb-0">{{ thisDate(cierreCaja.diaCierre) }}</p>
                                    </td>
                                    <td>
                                        <p class="text-sm mb-0">{{ formatMoneyCLP(cierreCaja.tarjeta) }}</p>
                                    </td>
                                    <td>
                                        <p class="text-sm mb-0">{{ formatMoneyCLP(cierreCaja.efectivo) }}</p>
                                    </td>
                                    <td>
                                        <p class="text-sm mb-0">{{ formatMoneyCLP(cierreCaja.fondoFijo) }}</p>
                                    </td>
                                    <td>
                                        <p v-if="cierreCaja.idRefFondoFijo != null" class="text-sm mb-0">{{ cierreCaja.reffondofijo.folioFofi }}</p>
                                        <p v-else class="text-sm mb-0">---</p>
                                    </td>
                                    <td>
                                        <p class="text-sm mb-0">{{ formatMoneyCLP(cierreCaja.notaCredito) }}</p>
                                    </td>
                                    <td>
                                        <p class="text-sm mb-0">{{ formatMoneyCLP(cierreCaja.transferencia + cierreCaja.cheque + cierreCaja.cornershop + cierreCaja.rappi + cierreCaja.personal + cierreCaja.marinaOnline + cierreCaja.vale) }}</p>
                                    </td>
                                    <td>
                                        <p class="text-sm mb-0">{{ formatMoneyCLP(cierreCaja.ventaTotal) }}</p>
                                    </td>
                                    <td>
                                        <p class="text-sm mb-0">{{ formatMoneyCLP(cierreCaja.ventaBruta) }}</p>
                                    </td>
                                    <td>
                                        <p class="text-sm mb-0">{{ formatMoneyCLP(cierreCaja.deposito) }}</p>
                                    </td>
                                    <td>
                                        <p class="text-sm mb-0">{{ thisDate(cierreCaja.fechaDeposito) }}</p>
                                    </td>
                                    <!-- <td>
                                        <p v-if="cierreCaja.diferenciaDias != null && cierreCaja.diferenciaDias >= 0 && cierreCaja.diferenciaDias <= 3" class="text-sm mb-0" style="color: black">{{ cierreCaja.diferenciaDias }} Días.</p>
                                        <p v-if="cierreCaja.diferenciaDias != null && cierreCaja.diferenciaDias < 8 && cierreCaja.diferenciaDias >= 4" class="text-sm mb-0" style="color: white; background-color: #F7BD00;">{{ cierreCaja.diferenciaDias }} Días.</p>
                                        <p v-if="cierreCaja.diferenciaDias != null && cierreCaja.diferenciaDias < 14 && cierreCaja.diferenciaDias >= 8" class="text-sm mb-0" style="color: white; background-color: #FB4C24;">{{ cierreCaja.diferenciaDias }} Días.</p>
                                        <p v-if="cierreCaja.diferenciaDias != null && cierreCaja.diferenciaDias < 0 || cierreCaja.diferenciaDias >= 14" class="text-sm mb-0" style="color: white; background-color: #D21610;">{{ cierreCaja.diferenciaDias }} Días.</p>
                                        <p v-if="cierreCaja.diferenciaDias == null" class="text-sm mb-0">---</p>
                                    </td>
                                    <td>
                                        <p v-if="cierreCaja.control < 0" class="text-sm mb-0" style="color: white; background-color: #D21610;">{{ formatMoneyCLP(cierreCaja.control) }}</p>
                                        <p v-if="cierreCaja.control == 0" class="text-sm mb-0" style="color: white; background-color: #28a745;">{{ formatMoneyCLP(cierreCaja.control) }}</p>
                                        <p v-if="cierreCaja.control > 0" class="text-sm mb-0" style="color: white; background-color: #3574EA;">{{ formatMoneyCLP(cierreCaja.control) }}</p>
                                    </td> -->
                                    <td>
                                        <p v-if="cierreCaja.diferenciaDias != null && cierreCaja.diferenciaDias >= 0 && cierreCaja.diferenciaDias <= 3" class="text-sm font-weight-bolder mb-0" style="color: #000000;">{{ cierreCaja.diferenciaDias }} Días.</p>
                                        <p v-if="cierreCaja.diferenciaDias != null && cierreCaja.diferenciaDias < 8 && cierreCaja.diferenciaDias >= 4" class="text-sm font-weight-bolder mb-0" style="color: #F7BD00;">{{ cierreCaja.diferenciaDias }} Días.</p>
                                        <p v-if="cierreCaja.diferenciaDias != null && cierreCaja.diferenciaDias < 14 && cierreCaja.diferenciaDias >= 8" class="text-sm font-weight-bolder mb-0" style="color: #FB4C24;">{{ cierreCaja.diferenciaDias }} Días.</p>
                                        <p v-if="cierreCaja.diferenciaDias != null && cierreCaja.diferenciaDias < 0 || cierreCaja.diferenciaDias >= 14" class="text-sm font-weight-bolder mb-0" style="color: #D21610;">{{ cierreCaja.diferenciaDias }} Días.</p>
                                        <p v-if="cierreCaja.diferenciaDias == null" class="text-sm mb-0">---</p>
                                    </td>
                                    <td>
                                        <p v-if="cierreCaja.control < 0" class="text-sm font-weight-bolder mb-0" style="color: #D21610;">{{ formatMoneyCLP(cierreCaja.control) }}</p>
                                        <p v-if="cierreCaja.control == 0" class="text-sm font-weight-bolder mb-0" style="color: #28a745;">{{ formatMoneyCLP(cierreCaja.control) }}</p>
                                        <p v-if="cierreCaja.control > 0" class="text-sm font-weight-bolder mb-0" style="color: #3574EA;">{{ formatMoneyCLP(cierreCaja.control) }}</p>
                                    </td>
                                    <td class="align-middle text-end">
                                        <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                            <a class="btn btn-success btn-xs" title="Detalle" @click="cierreCajaDetalle(cierreCaja)"><i class="fa-regular fa-eye"></i></a>
                                            &nbsp;
                                            <a v-if="(cierreCaja.deposito == 0 || cierreCaja.deposito == null) && cierreCaja.efectivo > 0" class="btn btn-purple btn-xs" title="Ingresar Depósito" id="show-modal2" @click="cierreCajaDeposito(cierreCaja)"><i class="fa-solid fa-file-signature"></i></a>
                                            <a v-else class="btn btn-grey btn-xs"><i class="fa-solid fa-file-signature"></i></a>
                                            &nbsp;
                                            <a v-if="is('Administrador')" class="btn btn-closed btn-xs" title="Eliminar" @click="deleteCierreCaja(cierreCaja.id)"><i class="fa fa-fw fa-trash"></i></a>
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
            <modalingresarcierre v-if="showIngresarCierreCaja" @close="showIngresarCierreCaja = false" @recarga="recargarTabla('General')" ></modalingresarcierre>
            <modalingresardeposito v-bind:cierreCaja="cierreDeposito" v-if="showIngresarDeposito" @close="showIngresarDeposito = false" @recarga="recargarTabla('General')"></modalingresardeposito>
            <modaldetallecierre v-bind:cierreCaja="cierreDetalle" v-if="showDetalleCierreCaja" @close="showDetalleCierreCaja = false"></modaldetallecierre>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import modalconfirmacion from '../../snippets/sistema/alerts/confirmationModal.vue'
import modalalerta from '../../snippets/sistema/alerts/alertModal.vue'
import modalingresarcierre from '../../snippets/tiendas/gestionTienda/cierreCajas/ingresarCierreCajaModal.vue'
import modalingresardeposito from '../../snippets/tiendas/gestionTienda/cierreCajas/ingresarDepositoCierreCajaModal.vue'
import modaldetallecierre from '../../snippets/tiendas/gestionTienda/cierreCajas/detalleCierreCajaModal.vue'

import {mixin} from '../../../mixins.js'

export default {
    components: { modalconfirmacion, modalalerta, modalingresarcierre, modaldetallecierre, modalingresardeposito },
    mixins: [mixin],
    data(){
        return{
            cierresCajas: [],
            mostrarTabla: false,
            mostrarCarga: true,
            showIngresarCierreCaja: false,
            showIngresarDeposito: false,
            showDetalleCierreCaja: false,
            table: null,
            fechaAño: null,
            fechaMes: null,
            fechaDia: null,
            filtroTienda: null,
            tiendas: [],
            años: [{value: 2000}, {value: 2001}, {value: 2002}, {value: 2003}, {value: 2004}, {value: 2005}, {value: 2006}, {value: 2007}, {value: 2008}, {value: 2009}, {value: 2010}, {value: 2011}, {value: 2012}, {value: 2013}, {value: 2014}, {value: 2015}, {value: 2016}, {value: 2017}, {value: 2018}, {value: 2019}, {value: 2020}, {value: 2021}, {value: 2022}, {value: 2023}, {value: 2024}, {value: 2025}, {value: 2026}, {value: 2027}, {value: 2028}, {value: 2029}, {value: 2030}, {value: 2031}, {value: 2032}, {value: 2033}, {value: 2034}, {value: 2035}, {value: 2036}, {value: 2037}, {value: 2038}, {value: 2039}, {value: 2040}, {value: 2041}, {value: 2042}, {value: 2043}, {value: 2044}, {value: 2045}, {value: 2046}, {value: 2047}, {value: 2048}, {value: 2049}, {value: 2050}],
            meses: [{value: 1, nombre: 'Enero'}, {value: 2, nombre: 'Febrero'}, {value: 3, nombre: 'Marzo'}, {value: 4, nombre: 'Abril'}, {value: 5, nombre: 'Mayo'}, {value: 6, nombre: 'Junio'}, {value: 7, nombre: 'Julio'}, {value: 8, nombre: 'Agosto'}, {value: 9, nombre: 'Septiembre'}, {value: 10, nombre: 'Octubre'}, {value: 11, nombre: 'Noviembre'}, {value: 12, nombre: 'Diciembre'}],
            dias: [{value: 1}, {value: 2}, {value: 3}, {value: 4}, {value: 5}, {value: 6}, {value: 7}, {value: 8}, {value: 9}, {value: 10}, {value: 11}, {value: 12}, {value: 13}, {value: 14}, {value: 15}, {value: 16}, {value: 17}, {value: 18}, {value: 19}, {value: 20}, {value: 21}, {value: 22}, {value: 23}, {value: 24}, {value: 25}, {value: 26}, {value: 27}, {value: 28}, {value: 29}, {value: 30}, {value: 31}],
        }
    },
    created(){
        this.getCierresCajas();
        this.getTiendas();
    },
    methods: {
        getTiendas(){
            axios.get('api/getTiendas').then( response =>{
                this.tiendas = response.data;
            }).catch(e=> console.log(e))
        },
        getCierresCajas(){
            axios.get('api/cierreCajas').then( response =>{
                this.cierresCajas = response.data;
                if (this.table != null){
                    this.table.clear();
                    this.table.destroy();
                }
                this.crearTabla('#MyTableCierreCajas');
            }).catch(e=> console.log(e))
        },
        recargarTabla($tipoRecarga){
            this.mostrarCarga = true;
            if($tipoRecarga == 'General'){
                this.cierresCajas = null;
                this.getCierresCajas();
                this.fechaAño = null;
                this.fechaMes = null;
                this.fechaDia = null;
                this.filtroTienda = null;
            }else{
                this.table.destroy();
                this.crearTabla('#MyTableCierreCajas');
            }
        },
        filtrarFecha(){
            // this.fechaAño = null;
            // this.fechaMes = null;
            // this.fechaDia = null;
            // this.filtroTienda = null;
            if (this.fechaInicio == null || this.fechaTermino == null){
                this.recargarTabla('General');
            }else{
                let start = new Date(this.fechaInicio);
                let end = new Date(this.fechaTermino);
                this.robos = this.robos.filter(item => {
                    let date = new Date(item.fecha);
                    return date >= start && date <= end;
                });
                this.recargarTabla();
            }
        },
        cierreCajaDetalle(cierreCaja){
            this.cierreDetalle = cierreCaja;
            this.showDetalleCierreCaja = true;
        },
        cierreCajaDeposito(cierreCaja){
            this.cierreDeposito = cierreCaja;
            this.showIngresarDeposito = true;
        },
        async deleteCierreCaja(id) {
            const ok = await this.$refs.confirmation.show({
                title: 'Borrar Cierre de Caja',
                message: `¿Está seguro/a que desea eliminar este Cierre de Caja? Esta acción no puede ser desecha.`,
                okButton: 'Eliminar',
                cancelButton: 'Volver'
            })
            if (ok) {
                axios.delete(`api/cierreCajas/${id}`).then( response =>{
                    this.toast.success("Cierre de Caja eliminado con éxito!", {
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