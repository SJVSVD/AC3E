<template>
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="row pb-0 p-4">
                    <div class="col-4">
                        <div class="row">
                            <div class="col-5">
                                <label>Fecha Inicio:
                                    <input type="date" class="form-control form-control-sm" placeholder="Fecha Inicial" v-model="fechaInicio">
                                </label>   
                            </div>
                            <div class="col-5">
                                <label>Fecha Término:
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
                            <a v-if="can('Robos_Crear')" class="btn btn-spacing btn-continue" id="show-modal1" @click="showCrearRobo = true">Ingresar Robo</a>
                            &nbsp;
                            <a class="btn btn-spacing btn-search-blue" @click="recargarTabla('General')"><i class="fa-solid fa-rotate"></i></a>
                        </div>
                    </div>
                    <!-- <div class="dropdown col-12 d-flex justify-content-end">
                        <a class="btn btn-primary" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                            Mostrar/Ocultar Columnas:
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <li><a class="dropdown-item toggle-vis" data-column="1" href="#">ID</a></li>
                            <li><a class="dropdown-item toggle-vis" data-column="2" href="#">Folio</a></li>
                            <li><a class="dropdown-item toggle-vis" data-column="3" href="#">Fecha</a></li>
                            <li><a class="dropdown-item toggle-vis" data-column="4" href="#">Usuario</a></li>
                            <li><a class="dropdown-item toggle-vis" data-column="5" href="#">Cantidad Gastos</a></li>
                            <li><a class="dropdown-item toggle-vis" data-column="6" href="#">Fondos Asignados</a></li>
                            <li><a class="dropdown-item toggle-vis" data-column="7" href="#">Monto Total</a></li>
                            <li><a class="dropdown-item toggle-vis" data-column="7" href="#">Acciones</a></li>
                        </ul>
                    </div> -->
                </div>                
                <div class="card-body px-0 pt-0 pb-2" style="min-height: 400px">
                    <div class="container">
                        <div class="table-responsive p-0">
                            <div v-show="mostrarCarga" class="loader-sm"></div>
                            <table v-show="mostrarTabla" class="table align-items-center mb-0" id="MyTableRobos">
                                <thead>
                                    <tr style="color: black">
                                        <th style="min-width: 16px;"></th>
                                        <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Estado</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Tipo</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Tienda</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Fecha</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Supervisor</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Monto Robado</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="robo in robos" :key="robo.id">
                                        <td></td>
                                        <td>
                                            <p class="text-sm font-weight-bolder mb-0" style="color:black">{{ robo.id }}</p>
                                        </td>                                           
                                        <td>
                                            <p v-if="robo.estado == 'Ingresado'" class="text-sm font-weight-bolder mb-0" style="color: #28A745">{{ robo.estado }}</p>
                                            <p v-if="robo.estado == 'Cerrado'" class="text-sm font-weight-bolder mb-0" style="color: #B80F0A">{{ robo.estado }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0">{{ robo.tipo_robo.nombre }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0">{{ robo.tienda.codigo }} - {{ robo.tienda.nombre }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0">{{ robo.fecha }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0">{{ robo.supervisor.name }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0">{{ formatMoneyCLP(robo.montoRobado) }}</p>
                                        </td>
                                        <td class="align-middle text-end">
                                            <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                <a class="btn btn-success btn-xs" title="Detalle" @click="roboDetalle(robo)"><i class="fa-regular fa-eye"></i></a>
                                                &nbsp;
                                                <a v-if="can('Robos_Modificar')" class="btn btn-closed btn-xs" title="Eliminar" @click="deleteRobo(robo.id)"><i class="fa fa-fw fa-trash"></i></a>
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
            <modaleditar v-bind:usuario="usuarioeditar" v-if="showEditarUsuario" @close="showEditarUsuario = false" @recarga="recargarTabla('General')"></modaleditar>
            <modalcrear v-if="showCrearRobo" @close="showCrearRobo = false" @recarga="recargarTabla('General')"></modalcrear>
            <modaldetalle v-bind:robo="detalles" v-if="showDetallesRobo" @close="showDetallesRobo = false"></modaldetalle>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import modalcrear from '../../snippets/tiendas/gestionRobos/createRoboModal.vue'
import modaleditar from '../../snippets/sistema/usuarios/editUserModal.vue'
import modaldetalle from '../../snippets/tiendas/gestionRobos/detalleRobosModal.vue'
import modalconfirmacion from './../../snippets/sistema/alerts/confirmationModal.vue'
import modalalerta from '../../snippets/sistema/alerts/alertModal.vue'
import {mixin} from '../../../mixins.js'

export default {
    components: { modalcrear, modaleditar, modalconfirmacion, modaldetalle, modalalerta },
    mixins: [mixin],
    data(){
        return{
            robos: null,
            robosAux: null,
            showCrearRobo: false,
            showEditarUsuario: false,
            showDetallesRobo: false,
            usuarioeditar: null,
            mostrarTabla: false,
            mostrarCarga: true,
            detalles: null,
            roles: null,
            table: null,
            
            fechaInicio: null,
            fechaTermino: null,
        }
    },
    created(){
        this.getRobos();
    },
    methods: {
        filtrarFecha(){
            if (this.fechaInicio == null || this.fechaTermino == null){
                this.recargarTabla('General');
            }else{
                let start = new Date(this.fechaInicio);
                let end = new Date(this.fechaTermino);
                this.robos = this.robosAux.filter(item => {
                    let date = new Date(item.fecha);
                    return date >= start && date <= end;
                });
                this.recargarTabla();
            }
        },
        roboDetalle(robo){
            this.detalles = robo;
            this.showDetallesRobo = true;
        },
        getRobos(){
            axios.get('api/robos').then( response =>{
                this.robos = response.data;
                this.robosAux = response.data;
                if (this.table != null){
                    this.table.clear();
                    this.table.destroy();
                }
                this.crearTabla("#MyTableRobos");
            }).catch(e=> console.log(e))
        },
        recargarTabla($tipoRecarga){
            this.mostrarCarga = true;
            if($tipoRecarga == 'General'){
                this.robos = null;
                this.getRobos();
                this.fechaInicio = null;
                this.fechaTermino = null;
            }else{
                this.table.destroy();
                this.crearTabla('#MyTableRobos');
            }
        },
        usuarioEditar(usuario){
            this.usuarioeditar = usuario;
            this.showEditarUsuario = true;
        },
        async deleteRobo(id) {
            const ok = await this.$refs.confirmation.show({
                title: 'Borrar Registro de robo',
                message: `¿Está seguro/a que desea eliminar este registro de robo? Esta acción no puede ser desecha.`,
                okButton: 'Eliminar',
                cancelButton: 'Volver'
            })
            if (ok) {
                axios.delete(`api/robos/${id}`).then( response =>{
                    this.toast.success("Registro de robo eliminado con éxito!", {
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