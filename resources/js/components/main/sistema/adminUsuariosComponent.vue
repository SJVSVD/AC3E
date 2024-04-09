<template>
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="row pb-0 p-4">
                    <div class="col-12">
                        <div class="d-flex justify-content-end">
                            <a class="btn btn-spacing btn-purple" id="show-modal1" @click="showEditarProgress = true">Update year</a>
                            &nbsp;
                            <a class="btn btn-spacing btn-continue" id="show-modal1" @click="showCrearUsuario = true">New</a>
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
                            <table v-show="mostrarTabla" class="table align-items-center mb-0" id="MyTableUsuarios">
                                <thead>
                                    <tr style="color: black">
                                        <th style="min-width: 16px;"></th>
                                        <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Status</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">User Role</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Research Line</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Name</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Email</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Roles</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Actions</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="usuario in usuarios" :key="usuario.id">
                                        <td></td>
                                        <td>
                                            <p class="text-sm font-weight-bolder mb-0" style="color:black">{{ usuario.id }}</p>
                                        </td>
                                        <td>
                                            <p v-if="usuario.estado == true" class="text-sm font-weight-bolder mb-0" style="color: #28A745">Active</p>
                                            <p v-else class="text-sm font-weight-bolder mb-0" style="color: #B80F0A">Inactive</p>
                                        </td>      
                                        <td>
                                            <p class="text-sm mb-0">{{ usuario.role_user.name }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0">{{ usuario.research_line.name }}</p>
                                        </td>                                      
                                        <td>
                                            <p class="text-sm mb-0">{{ usuario.name }}</p>
                                        </td>
                                        <td>
                                            <p v-if="usuario.email != null" class="text-sm mb-0">{{ usuario.email }}</p>
                                            <p v-else class="text-sm mb-0">---</p>
                                        </td>
                                        <td>
                                            <div v-if="usuario.roles == ''">---</div>
                                            <div v-else>
                                                <p v-for="rol in usuario.roles" :key="rol.id" class="text-sm mb-0">{{ rol.name }}</p>
                                            </div>
                                        </td>
                                        <td class="align-middle text-end">
                                            <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                <a class="btn btn-alert btn-xs" title="Editar" @click="usuarioEditar(usuario)"><i class="fa fa-fw fa-edit"></i></a>
                                                &nbsp;
                                                <a class="btn btn-closed btn-xs" title="Eliminar" @click="deleteUsuario(usuario.id,usuario.name)"><i class="fa fa-fw fa-trash"></i></a>
                                                &nbsp;
                                                <a v-if="usuario.estado == true" @click="cambiarEstado(usuario.id)" class="btn btn-continue btn-xs"><i class="fa-solid fa-toggle-on"></i></a>
                                                <a v-else class="btn btn-secondary btn-xs" @click="cambiarEstado(usuario.id)"><i class="fa-solid fa-toggle-off"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="row">
                                <div class="col-3">
                                    <label style="font-weight: 500">These buttons use the elements selected in the table, if none exist, it will select all the records. </label>
                                </div>
                                <div class="col-auto">
                                    <label title="To select a single record from the table, just do &#013; Click on the box in the first column to select &#013; several consecutive hold SHIFT, to select several &#013; non-consecutive hold CTRL."><span class="badge bg-dark-grey fs-10">?</span></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <modalconfirmacion ref="confirmation"></modalconfirmacion>
            <modalalerta ref="alert"></modalalerta>
            <modaleditar v-bind:usuario="usuarioeditar" v-if="showEditarUsuario" @close="showEditarUsuario = false" @recarga="recargarTabla('General')"></modaleditar>
            <modalcrear v-if="showCrearUsuario" @close="showCrearUsuario = false" @recarga="recargarTabla('General')"></modalcrear>
            <modalprogress v-if="showEditarProgress" @close="showEditarProgress = false"></modalprogress>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import modalcrear from '../../snippets/sistema/usuarios/createUserModal.vue'
import modaleditar from '../../snippets/sistema/usuarios/editUserModal.vue'
import modalconfirmacion from '../../snippets/sistema/alerts/confirmationModal.vue'
import modalalerta from '../../snippets/sistema/alerts/alertModal.vue'
import modalprogress from '../../snippets/sistema/usuarios/editProgressModal.vue'
import {mixin} from '../../../mixins.js'

export default {
    components: { modalcrear, modaleditar, modalconfirmacion, modalalerta,modalprogress },
    mixins: [mixin],
    data(){
        return{
            usuarios: null,
            showCrearUsuario: false,
            showEditarUsuario: false,
            showEditarProgress: false,
            usuarioeditar: null,
            mostrarTabla: false,
            mostrarCarga: true,
            permisos: null,
            table: null,
        }
    },
    created(){
        this.getUsuarios();
    },
    methods: {
        recargarTabla($tipoRecarga){
            this.mostrarCarga = true;
            if($tipoRecarga == 'General'){
                this.usuarios = null;
                this.getUsuarios();
            }else{
                this.table.destroy();
                this.crearTabla('#MyTableUsuarios');
            }
        },
        cambiarEstado($id){
            axios.get(`api/cambiarEstadoUsuario/${$id}`).then( response =>{
                this.toast.success("User has been successfully updated!", {
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
        },
        getUsuarios(){
            axios.get('api/usuarios').then( response =>{
                this.usuarios = response.data;
                if (this.table != null){
                    this.table.clear();
                    this.table.destroy();
                }
                this.crearTabla("#MyTableUsuarios");
            }).catch(e=> console.log(e))
        },
        recargarTabla($tipoRecarga){
            this.mostrarCarga = true;
            if($tipoRecarga == 'General'){
                this.usuarios = null;
                this.getUsuarios();
            }
            else{
                this.crearTabla("#MyTableUsuarios");
            }
        },
        usuarioEditar(usuario){
            this.usuarioeditar = usuario;
            this.showEditarUsuario = true;
        },
        async deleteUsuario(id,Nombre) {
            const ok = await this.$refs.confirmation.show({
                title: 'Delete User',
                message: `¿Are you sure you want to delete the user? '${Nombre}'?.`,
                okButton: 'Delete',
                cancelButton: 'Return'
            })
            if (ok) {
                axios.delete(`api/usuarios/${id}`).then( response =>{
                    this.toast.success("User deleted successfully!", {
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