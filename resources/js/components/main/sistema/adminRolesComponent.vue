<template>
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="row pb-0 p-4">
                    <div class="col-12">
                        <div class="d-flex justify-content-end">
                            <a class="btn btn-continue" id="show-modal1" @click="showCrearRol = true">Nuevo Rol</a>
                            &nbsp;
                            <a class="btn btn-continue" id="show-modal1" @click="showCrearPermiso = true">Nuevo Permiso</a>
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
                            <li><a class="dropdown-item toggle-vis" data-column="3" href="#">Acciones</a></li>
                        </ul>
                    </div> -->
                </div>
                <div class="card-body px-0 pt-0 pb-2" style="min-height: 400px">
                    <div class="container">
                        <div class="table-responsive p-0">
                            <div v-show="mostrarCarga" class="loader-sm"></div>
                            <table v-show="mostrarTabla" class="table align-items-center mb-0" id="MyTableRoles">
                                <thead>
                                    <tr style="color: black">
                                        <th style="min-width: 16px;"></th>
                                        <th class="text-uppercase text-xs font-weight-bolder">ID-Rol</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Nombre</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="rol in roles" :key="rol.id">
                                        <td></td>
                                        <td>
                                            <p class="text-sm font-weight-bolder mb-0" style="color:black">{{ rol.id }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0">{{ rol.name }}</p>
                                        </td>
                                        <td class="align-middle text-end">
                                            <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                <a class="btn btn-alert btn-xs" title="Editar" @click="rolEditar(rol)"><i class="fa fa-fw fa-edit"></i></a>
                                                &nbsp;
                                                <a class="btn btn-closed btn-xs" title="Eliminar" @click="deleteRole(rol.id,rol.name)"><i class="fa fa-fw fa-trash"></i></a>
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
            <modaleditar2 v-bind:rol="roleditar" v-if="showEditarRol" @close="showEditarRol = false" @recarga="recargarTabla('General')"></modaleditar2>
            <modalcrear2   v-if="showCrearRol" @close="showCrearRol = false" @recarga="recargarTabla('General')"></modalcrear2>
            <modalpermisos v-if="showCrearPermiso" @close="showCrearPermiso = false" @recarga="recargarTabla('General')"></modalpermisos>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import modalcrear2 from '../../snippets/sistema/roles&permisos/createRolModal.vue'
import modaleditar2 from '../../snippets/sistema/roles&permisos/editRolModal.vue'
import modalpermisos from '../../snippets/sistema/roles&permisos/createPermisos.vue'
import modalconfirmacion from '../../snippets/sistema/alerts/confirmationModal.vue'
import modalalerta from '../../snippets/sistema/alerts/alertModal.vue'
import {mixin} from '../../../mixins.js'

export default {
    components: { modalcrear2, modaleditar2, modalconfirmacion, modalalerta, modalpermisos },
    mixins: [mixin],
    data(){
        return{
            roles: null,
            showCrearRol: false,
            showEditarRol: false,
            showCrearPermiso: false,
            roleditar: null,
            mostrarTabla: false,
            mostrarCarga: true,
            permisos: null,
            table: null,
        }
    },
    created(){
        this.getRoles();
    },
    methods: {
        recargarTabla($tipoRecarga){
            this.mostrarCarga = true;
            if($tipoRecarga == 'General'){
                this.roles = null;
                this.getRoles();
            }else{
                this.table.destroy();
                this.crearTabla('#MyTableRoles');
            }
        },
        getRoles(){
            axios.get('api/roles').then( response =>{
                this.roles = response.data;
                if (this.table != null){
                    this.table.clear();
                    this.table.destroy();
                }
                this.crearTabla("#MyTableRoles");
            }).catch(e=> console.log(e))
        },
        rolEditar(rol){
            this.roleditar = rol;
            this.showEditarRol = true;

        },
        async deleteRole(id,Nombre) {
            const ok = await this.$refs.confirmation.show({
                title: 'Borrar Rol',
                message: `¿Está seguro/a que desea eliminar al rol '${Nombre}'? Esta acción no puede ser desecha.`,
                okButton: 'Eliminar',
                cancelButton: 'Volver'
            })
            if (ok) {
                axios.delete(`api/roles/${id}`).then( response =>{
                    this.toast.success("Rol eliminado con éxito!", {
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
        }
    }
}
</script>

