<template>
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="row pb-0 p-4">
                    <div class="col-12">
                        <div class="d-flex justify-content-end">
                            <a class="btn btn-spacing btn-continue" id="show-modal1" @click="showCrearUsuario = true">Nuevo</a>
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
                                        <th class="text-uppercase text-xs font-weight-bolder">Estado</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Nombre</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Correo Electrónico </th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Roles</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Permisos</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Acciones</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="usuario in usuarios" :key="usuario.id">
                                        <td></td>
                                        <td>
                                            <p class="text-sm font-weight-bolder mb-0" style="color:black">{{ usuario.id }}</p>
                                        </td>
                                        <td>
                                            <p v-if="usuario.estado == true" class="text-sm font-weight-bolder mb-0" style="color: #28A745">Activo</p>
                                            <p v-else class="text-sm font-weight-bolder mb-0" style="color: #B80F0A">Inactivo</p>
                                        </td>                                            
                                        <td>
                                            <p class="text-sm mb-0">{{ usuario.name }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0">{{ usuario.email }}</p>
                                        </td>
                                        <td>
                                            <div v-if="usuario.roles == ''">---</div>
                                            <div v-else>
                                                <p v-for="rol in usuario.roles" :key="rol.id" class="text-sm mb-0">{{ rol.name }}</p>
                                            </div>
                                        </td>
                                        <td>
                                            <div v-if="usuario.permissions == ''">---</div>
                                            <div v-else>
                                                <p v-for="permiso in usuario.permissions" :key="permiso.id" class="text-sm mb-0">{{ permiso.name }}</p>
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
            <modalcrear v-if="showCrearUsuario" @close="showCrearUsuario = false" @recarga="recargarTabla('General')"></modalcrear>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import modalcrear from '../../snippets/sistema/usuarios/createUserModal.vue'
import modaleditar from '../../snippets/sistema/usuarios/editUserModal.vue'
import modalconfirmacion from '../../snippets/sistema/alerts/confirmationModal.vue'
import modalalerta from '../../snippets/sistema/alerts/alertModal.vue'
import {mixin} from '../../../mixins.js'

export default {
    components: { modalcrear, modaleditar, modalconfirmacion, modalalerta },
    mixins: [mixin],
    data(){
        return{
            usuarios: null,
            showCrearUsuario: false,
            showEditarUsuario: false,
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
        createSuperAdmin(){
            axios.post('api/usuarios/superadmin').then( response =>{
                this.toast.success("Super Administrador actualizado con éxito!", {
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
                this.toast.success("Usuario se ha actualizado con éxito!", {
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
                title: 'Borrar Usuario',
                message: `¿Está seguro/a que desea eliminar al usuario '${Nombre}'? Esta acción no puede ser desecha.`,
                okButton: 'Eliminar',
                cancelButton: 'Volver'
            })
            if (ok) {
                axios.delete(`api/usuarios/${id}`).then( response =>{
                    this.toast.success("Usuario eliminado con éxito!", {
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