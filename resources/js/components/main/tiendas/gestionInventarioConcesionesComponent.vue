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
                            <a v-if="is('Tiendas_Jefatura') && can('Inventario Concesiones_Modificar')" class="btn btn-spacing btn-closed" id="show-autorizarModal" @click="showAutorizarInvConciones = true"><i class="fa-solid fa-file-signature"></i> Autorizar</a>
                            &nbsp;
                            <a v-if="can('Inventario Concesiones_Crear')" class="btn btn-spacing btn-alert" id="show-uploadDataModal" @click="showUploadDataInvConciones = true"><i class="fa-solid fa-upload"></i> Subir Datos</a>
                            &nbsp;
                            <a class="btn btn-spacing btn-search-blue" @click="recargarTabla('General')"><i class="fa-solid fa-rotate"></i></a>
                        </div>
                    </div>
                    <!-- <div class="dropdown col-3">
                        <a class="btn btn-spacing btn-primary" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                            Mostrar/Ocultar Columnas:
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <li><a class="dropdown-item toggle-vis" data-column="2" href="#">Nombre</a></li>
                            <li><a class="dropdown-item toggle-vis" data-column="3" href="#">Correo Electrónico</a></li>
                            <li><a class="dropdown-item toggle-vis" data-column="4" href="#">Rol</a></li>
                            <li><a class="dropdown-item toggle-vis" data-column="5" href="#">Acciones</a></li>
                        </ul>
                    </div> -->
                </div>
                <div class="card-body px-0 pt-0 pb-2" style="min-height: 400px">
                    <div class="container">
                        <div class="table-responsive p-0">
                            <div v-show="mostrarCarga" class="loader-sm"></div>
                            <table v-show="mostrarTabla" class="table align-items-center mb-0" id="MyTableInventarios">
                                <thead>
                                    <tr style="color: black">
                                        <th style="min-width: 16px;"></th>
                                        <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Estado</th>                                                                                
                                        <th class="text-uppercase text-xs font-weight-bolder">Tipo inventario</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Super familia</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Supervisor</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Sucursal </th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Promotor</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Comisión</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Fecha</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Hora</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Recuento</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Inventario</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Contabilizacion</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Diferencia</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Observación</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Acciones</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="inventario in inventarios" :key="inventario.id">
                                        <td></td>
                                        <td>
                                            <p class="text-sm font-weight-bolder mb-0" style="color:black">{{ inventario.id }}</p>
                                        </td>                                           
                                        <td>
                                            <p v-if="inventario.estado == 'Finalizado'" class="text-sm font-weight-bolder mb-0" style="color: #28A745">{{ inventario.estado }}</p>
                                            <p v-if="inventario.estado == 'Rechazado'" class="text-sm font-weight-bolder mb-0" style="color: #B80F0A">{{ inventario.estado }}</p>
                                            <p v-if="inventario.estado == 'Ingresado'" class="text-sm font-weight-bolder mb-0" style="color: #FFC107">{{ inventario.estado }}</p>
                                            <p v-if="inventario.estado == 'En Proceso'" class="text-sm font-weight-bolder mb-0" style="color: #8392AB">{{ inventario.estado }}</p>
                                            <p v-if="inventario.estado == 'Confirmado'" class="text-sm font-weight-bolder mb-0" style="color: #1520A6">{{ inventario.estado }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0">{{ inventario.tipo_inv.nombre }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0">{{ inventario.superFamilia }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0">{{ inventario.supervisor.usuario.name }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0">{{ inventario.sucursal.codigo }} - {{ inventario.sucursal.nombre }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0">{{ inventario.promotor.usuario.name }}</p>
                                        </td>
                                        <td>
                                            <p v-if="inventario.comision.usuario != null" class="text-sm mb-0">{{ inventario.comision.usuario.name }}</p>
                                            <p v-else class="text-sm mb-0">{{ inventario.comision.nombre }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0">{{ inventario.fecha }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0">{{ inventario.hora }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0">{{ inventario.recuento }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0">{{ inventario.inventario }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0">{{ inventario.contabilizacion }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0">{{ inventario.diferencia }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0">{{ inventario.observacion }}</p>
                                        </td>
                                        <td class="align-middle text-end">
                                            <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                <a class="btn btn-success btn-xs" title="Detalle" @click="invConcDetalle(inventario)"><i class="fa-regular fa-eye"></i></a>
                                                &nbsp;
                                                <a v-if="can('Inventario Concesiones_Modificar') && inventario.autorizadoComision == null"  class="btn btn-alert btn-xs" title="Editar" @click="invConcesionesEditar(inventario)"><i class="fa fa-fw fa-edit"></i></a>
                                                &nbsp;
                                                <a v-if="can('Inventario Concesiones_Modificar') && inventario.autorizadoSupervisora == null" class="btn btn-closed btn-xs" title="Eliminar" @click="deleteInvConc(inventario.id)"><i class="fa fa-fw fa-trash"></i></a>
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
            <modaleditar v-bind:invConc="invConcEditar" v-if="showEditarInvConc" @close="showEditarInvConc = false" @recarga="recargarTabla('General')"></modaleditar>
            <modaldetalle v-bind:invConc="detalles" v-if="showDetallesInvConc" @close="showDetallesInvConc = false" @recarga="recargarTabla('General')"></modaldetalle>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
// import modalautorizar from '../../snippets/tiendas/inventarioConcesiones/autorizarModal.vue'
import modalconfirmacion from '../../snippets/sistema/alerts/confirmationModal.vue'
import modaleditar from '../../snippets/tiendas/inventarioConcesiones/editInvConcModal.vue'
import modaldetalle from '../../snippets/tiendas/inventarioConcesiones/detalleInvConcModal.vue'
import modalalerta from '../../snippets/sistema/alerts/alertModal.vue'
import {mixin} from '../../../mixins.js'

export default {
    components: { modalconfirmacion, modalalerta, modaldetalle, modaleditar },
    mixins: [mixin],
    data(){
        return{
            inventarios: null,
            showEditarInvConc: false,
            showDetallesInvConc: false,
            invConcEditar: null,
            detalles: null,
            mostrarTabla: false,
            mostrarCarga: true,
            table: null,
        }
    },
    created(){
        this.getInventarios();
    },
    methods: {
        getInventarios(){
            axios.get('api/inventario').then( response =>{
                this.inventarios = response.data;
                if (this.table != null){
                    this.table.clear();
                    this.table.destroy();
                }
                this.crearTabla("#MyTableInventarios");
            }).catch(e=> console.log(e))
        },
        recargarTabla($tipoRecarga){
            this.mostrarCarga = true;
            if($tipoRecarga == 'General'){
                this.inventarios = null;
                this.getInventarios();
            }else{
                this.table.destroy();
                this.crearTabla('#MyTableInventarios');
            }
        },
        invConcDetalle(InvConc){
            this.detalles = InvConc;
            this.showDetallesInvConc = true;
        },
        invConcesionesEditar(usuario){
            this.usuarioeditar = usuario;
            this.showEditarInvConc = true;
        },
        async deleteInvConc(id) {
            const ok = await this.$refs.confirmation.show({
                title: 'Borrar Inventario de Concesión',
                message: `¿Está seguro/a que desea eliminar el inventario de concesión? Esta acción no puede ser desecha.`,
                okButton: 'Eliminar',
                cancelButton: 'Volver'
            })
            if (ok) {
                axios.delete(`api/inventario/${id}`).then( response =>{
                    this.toast.success("Inventario de Concesión eliminado con éxito!", {
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