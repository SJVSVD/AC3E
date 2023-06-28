<template>
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="row pb-0 p-4">
                    <div class="col-12">
                        <div class="d-flex justify-content-end">
                            <a class="btn btn-continue" id="show-modal1" @click="showCrearColaborador = true">Nuevo</a>
                            &nbsp;
                            <a class="btn btn-search-blue" @click="recargarTabla('General')"><i class="fa-solid fa-rotate"></i></a>
                        </div>
                    </div>
                    <!-- <div class="dropdown col-3">
                        <a class="btn btn-primary" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
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
                            <table v-show="mostrarTabla" class="table align-items-center mb-0" id="MyTableColaboradores">
                                <thead>
                                    <tr style="color: black">
                                        <th style="min-width: 16px;"></th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Rut</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Estado</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Nombre</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Correo Electrónico Empresa</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Cargo</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Teléfono</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="colaborador in colaboradores" :key="colaborador.id">
                                        <td></td>
                                        <td>
                                            <p class="text-sm font-weight-bolder mb-0" style="color:black">{{ colaborador.identificador}}</p>
                                        </td>
                                        <td>
                                            <p v-if="colaborador.estado == true" class="text-sm font-weight-bolder mb-0" style="color: #28A745">Activo</p>
                                            <p v-else class="text-sm font-weight-bolder mb-0" style="color: #B80F0A">Inactivo</p>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0">{{ colaborador.nombre1 }} {{ colaborador.nombre2 }} {{ colaborador.apellido1 }} {{ colaborador.apellido2 }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0">{{ colaborador.correoEmpresa }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0">{{ colaborador.cargo }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0">{{ colaborador.telefonoEmpresa }}</p>
                                        </td>
                                        <td class="align-middle text-end">
                                            <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                <a class="btn btn-success btn-xs" title="Detalle" @click="colaboradorDetalle(colaborador)"><i class="fa-regular fa-eye"></i></a>
                                                &nbsp;
                                                <a class="btn btn-alert btn-xs" title="Editar" @click="colaboradorEditar(colaborador)"><i class="fa fa-fw fa-edit"></i></a>
                                                &nbsp;
                                                <a class="btn btn-closed btn-xs" title="Eliminar" @click="deleteColaborador(colaborador.id,colaborador.nombre)"><i class="fa fa-fw fa-trash"></i></a>
                                                &nbsp;
                                                <a v-if="colaborador.estado == true" @click="cambiarEstado(colaborador.id)" class="btn btn-continue btn-xs"><i class="fa-solid fa-toggle-on"></i></a>
                                                <a v-else class="btn btn-secondary btn-xs" @click="cambiarEstado(colaborador.id)"><i class="fa-solid fa-toggle-off"></i></a>                                            
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
            <modalconfirmacion ref="confirmation"></modalconfirmacion>
            <modalalerta ref="alert"></modalalerta>
            <modaldetalle v-bind:colaborador="colaboradorDetalle1" v-if="showDetalleColaborador" @recarga="recargarTabla('General')" @close="showDetalleColaborador = false"></modaldetalle>
            <modaleditar v-bind:colaborador1="colaboradorEditar1" v-if="showEditarColaborador" @recarga="recargarTabla('General')" @close="showEditarColaborador = false"></modaleditar>
            <modalcrear v-if="showCrearColaborador" @close="showCrearColaborador = false" @recarga="recargarTabla('General')"></modalcrear>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import modalcrear from '../../snippets/sistema/colaboradores/createColabModal.vue'
import modaleditar from '../../snippets/sistema/colaboradores/editColabModal.vue'
import modaldetalle from '../../snippets/sistema/colaboradores/detalleColabModal.vue'
import modalconfirmacion from '../../snippets/sistema/alerts/confirmationModal.vue'
import modalalerta from '../../snippets/sistema/alerts/alertModal.vue'
import {mixin} from '../../../mixins.js'

export default {
    components: { modalcrear, modaleditar, modalconfirmacion, modalalerta, modaldetalle },
    mixins: [mixin],
    data(){
        return{
            colaboradores: null,
            showCrearColaborador: false,
            showEditarColaborador: false,
            showDetalleColaborador: false,
            colaboradorEditar1: null,
            colaboradorDetalle1: null,
            mostrarTabla: false,
            mostrarCarga: true,
            roles: null,
            table: null,
        }
    },
    created(){
        this.getColaboradores();
    },
    methods: {
        recargarTabla($tipoRecarga){
            this.mostrarCarga = true;
            if($tipoRecarga == 'General'){
                this.colaboradores = null;
                this.getColaboradores();
            }else{
                this.table.destroy();
                this.crearTabla('#MyTableColaboradores');
            }
        },
        cambiarEstado($id){
            axios.get(`api/cambiarEstadoColab/${$id}`).then( response =>{
                this.recargarTabla('General');
            }).catch(e=> console.log(e))
        },
        getColaboradores(){
            axios.get('api/colaboradores').then( response =>{
                this.colaboradores = response.data;
                if (this.table != null){
                    this.table.clear();
                    this.table.destroy();
                }
                this.crearTabla('#MyTableColaboradores');
            }).catch(e=> console.log(e))
        },
        colaboradorDetalle(colaborador){
            this.colaboradorDetalle1 = colaborador;
            this.showDetalleColaborador = true;
        },
        colaboradorEditar(colaborador){
            this.colaboradorEditar1 = colaborador;
            this.showEditarColaborador = true;
        },
        async deleteColaborador(id,nombre) {
            const ok = await this.$refs.confirmation.show({
                title: 'Borrar Colaborador',
                message: `¿Está seguro/a que desea eliminar al colaborador '${nombre}'? Esta acción no puede ser desecha.`,
                okButton: 'Eliminar',
                cancelButton: 'Volver'
            })
            if (ok) {
                axios.delete(`api/colaboradores/${id}`).then( response =>{
                    this.toast.success("Colaborador eliminado con éxito!", {
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