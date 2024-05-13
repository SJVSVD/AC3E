<template>
    <div class="card shadow-lg mx-4 card-profile-bottom">
        <div class="card-body p-3">
            <div class="row gx-4">
                <div class="col-auto">
                    <div style="position: relative; left: 50%; top: 50%; transform: translate(-50%, -50%); -webkit-transform: translate(-50%, -50%);">
                        <div style="width: 150px; height: 150px; display: flex; justify-content: center; align-items: center;">
                            <img class="img-not-draggable w-100 h-100 border-radius-100 shadow-sm" style="object-fit: cover;" src="https://icon-library.com/images/default-user-icon/default-user-icon-13.jpg">
                        </div>
                    </div>
                </div>
                <div class="col-auto my-auto">
                    <h5 class="mb-1">
                        {{ userName }}
                    </h5>
                    <div class="d-flex">
                        <a @click="mostrarRoles()" class="btn btn-dark">Roles</a>
                    </div>
                </div>
                <div class="col-md-2 me-sm-0 mx-auto mt-3">
                    <div class="form-group">
                        <a @click="editPassword(usuario)" class="btn btn-alert" title="Change Password" style="float: right; font-size: 16px;"><i class="fa fa-fw fa-edit"></i>Change Password</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div v-if="showRoles" class="container-fluid py-3">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <a class="btn btn-closed" style="float: right" @click="mostrarRoles()">X</a>
                        <div class="d-flex align-items-center">
                            <h5>Roles</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div v-if="isEmpty(roles)">
                            <div class="fs-7">No roles.</div>
                        </div>
                        <div v-else class="displayRolesPermisos">
                            <p v-for="rol in roles" :key="rol" class="mb-0 font-weight-bold text-sm">
                                {{ rol.name }} &nbsp;
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid py-3">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <a v-show="!isEditing" @click="editarPerfil()" class="btn btn-alert" title="Edit" style="float: right; font-size: 16px;"><i class="fa fa-fw fa-edit"></i> Edit</a>
                        <div class="d-flex align-items-center">
                            <h5>Personal information</h5>
                        </div>
                        <hr class="horizontal dark">
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Name:</label>
                                    <br>
                                    <input :disabled="isEditing == false" class="form-control" type="text" v-model="editables.name">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Email:</label>
                                    <br>
                                    <input :disabled="isEditing == false" class="form-control" type="text" v-model="editables.email">
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <a v-show="isEditing" @click="guardarPerfil()" class="btn btn-continue" style=" font-size: 16px;"><i class="fa-regular fa-floppy-disk fa-lg"></i> Save</a>
                            &nbsp;
                            <a v-show="isEditing" @click="cancelarEdicion()" class="btn btn-closed" style=" font-size: 16px;"><i class="fa-solid fa-ban"></i> Cancel</a>
                        </div>
                    </div>
                </div>
            </div>
            <modalconfirmacion ref="confirmation"></modalconfirmacion>
            <modalEditPassword v-bind:password1="passwordEdit" v-if="showEditPassword" @close="showEditPassword = false"></modalEditPassword>
            <modalalerta ref="alert"></modalalerta>
        </div>
    </div>
</template>
<script>
import axios from 'axios'
import modalconfirmacion from './snippets/sistema/alerts/confirmationModal.vue'
import modalalerta from './snippets/sistema/alerts/alertModal.vue'
import modalEditPassword from './snippets/sistema/usuarios/editPasswordModal.vue'
import {mixin} from '../mixins.js'

export default {
    components: { modalconfirmacion, modalalerta , modalEditPassword },
    mixins: [mixin],
    data(){
        return{
            showFotoPerfil: false, 
            isEditing: false,
            usuario: null,
            roles: null,
            permisos: null,
            editables:{
                name: null,
                email: null,
            },
            showEditPassword: false,
            passwordEdit: null,
            id: null,
            errors: [],
            showRoles: false,
        }
    },
    created(){
        this.getUsuarios(this.userID);
        this.getRolesUsuario(this.userID);
    },
    methods: {
        // Se abre el modal para editar la contraseña
        editPassword(password){
            this.passwordEdit = password;
            this.showEditPassword = true;
        },
        // Se muestran los roles que tiene el usuario
        mostrarRoles(){
            if(this.showRoles == false){
                this.showRoles = true;
            }
            else{
                this.showRoles = false;
            }
        },
        // Se consiguen los roles del usuario con su id
        getRolesUsuario($id){
            axios.get(`api/roles/${$id}`).then( response =>{
                this.roles = response.data;
            }).catch(e=> console.log(e))
        },
        // Se consiguen los datos del usuario actual
        getUsuarios(id){
            axios.get(`api/usuarios/${id}`).then( response =>{
                this.usuario = response.data;
                this.id = this.usuario.id;
                this.profilePic = this.usuario.profilePic;
                this.editables.name = this.usuario.name;
                this.editables.email = this.usuario.email;
            }).catch(e=> console.log(e))
        },
        // Se activa la edicion del perfil
        editarPerfil(){
            this.isEditing = true;
        },
        cancelarEdicion(){
            this.isEditing = false;
            this.getUsuarios(this.userID);
        },
        // Devuelve un string con la primera letra en mayuscula
          // Capitaliza la primera letra de una cadena.
        capitalizeFirstLetter(string) {
          return string.charAt(0).toUpperCase() + string.slice(1);
        },
        // Guarda los cambios del perfil 
        async guardarPerfil(){
            this.errors = [];
            for (const item in this.editables){
                if(this.editables[item] === "" || this.editables[item] === null){
                    if(item != 'sobreMi'){
                        this.errors.push(item);
                    }
                }
            }
            var mensaje = ""
            if (this.errors.length != 0){
                this.errors.forEach(item => {
                    mensaje =   mensaje + "The field " + this.capitalizeFirstLetter(item) + " is required" + "\n" 
                });
                this.toast.warning( mensaje, {
                position: "top-right",
                timeout: 5000,
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
            if(this.errors.length === 0){
                const ok = await this.$refs.confirmation.show({
                title: 'Edit profile',
                message: `Are you sure you want to edit your profile?.`,
                okButton: 'Edit',
                cancelButton: 'Return'
                })
                if (ok) {
                let usuario = {
                    name: this.editables.name,
                    email: this.editables.email,
                };
                var id = this.id;
                axios.put(`api/usuarios/${id}`, usuario).then((result) => {
                    this.buttonText = 'Editing...';
                    this.buttonDisable = true;
                    if(result.data == 'email existente'){
                        this.toast.warning( 'There is already a user with the Email entered', {
                            position: "top-right",
                            timeout: 5000,
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
                        this.buttonDisable = false;
                        this.buttonText = 'Edit User';
                    }else{
                        this.toast.success("Profile edited successfully!", {
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
                        this.isEditing = false;
                        setTimeout(() => {location.reload();}, 1500);
                    }
                })
                .catch((error)=> {
                    if (error.response.status == 422){
                    this.errors = error.response.data.errors;
                    this.toast.warning('There is an invalid value.', {
                        position: "top-right",
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
                });
                }
            }
        },
    }
}
</script>