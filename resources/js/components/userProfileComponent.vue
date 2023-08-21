<template>
    <div class="card shadow-lg mx-4 card-profile-bottom">
        <div class="card-body p-3">
            <div class="row gx-4">
                <div class="col-auto">
                    <div v-if="profilePic != null" style="position: relative; left: 50%; top: 50%; transform: translate(-50%, -50%); -webkit-transform: translate(-50%, -50%);">
                        <a class="btn" @click="showFotoPerfil = true">
                            <div style="width: 150px; height: 150px; display: flex; justify-content: center; align-items: center;">
                                <img class="img-not-draggable w-100 h-100 border-radius-100 shadow-sm" style="object-fit: cover;" v-bind:src="'data:image/jpeg;base64,'+profilePic">
                            </div>
                        </a>
                    </div>
                    <div v-else style="position: relative; left: 50%; top: 50%; transform: translate(-50%, -50%); -webkit-transform: translate(-50%, -50%);">
                        <a class="btn" @click="showFotoPerfil = true">
                            <div style="width: 150px; height: 150px; display: flex; justify-content: center; align-items: center;">
                                <img class="img-not-draggable w-100 h-100 border-radius-100 shadow-sm" style="object-fit: cover;" src="https://icon-library.com/images/default-user-icon/default-user-icon-13.jpg">
                            </div>
                        </a>
                    </div>
                </div>
                <div class="col-auto my-auto">
                    <h5 class="mb-1">
                        {{ userName }}
                    </h5>
                    <div class="d-flex">
                        <a @click="mostrarRoles()" class="btn btn-dark">Roles</a>
                        &nbsp;
                        <a @click="mostrarPermisos()" class="btn btn-dark">Permissions</a>
                        &nbsp;
                    </div>
                </div>
                <div class="col-md-2 me-sm-0 mx-auto mt-3">
                    <div class="form-group">
                        <a @click="editPassword(usuario)" class="btn btn-alert" title="Change Password" style="float: right; font-size: 16px;"><i class="fa fa-fw fa-edit"></i>Change Password</a>
                    </div>
                    <!-- <div class="nav-wrapper position-relative end-0">
                        <ul class="nav nav-pills nav-fill p-1" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1 active d-flex align-items-center justify-content-center "
                                    data-bs-toggle="tab" href="javascript:;" role="tab" aria-selected="true">
                                    <i class="ni ni-app"></i>
                                    <span class="ms-2">App</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1 d-flex align-items-center justify-content-center "
                                    data-bs-toggle="tab" href="javascript:;" role="tab" aria-selected="false">
                                    <i class="ni ni-email-83"></i>
                                    <span class="ms-2">Messages</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link mb-0 px-0 py-1 d-flex align-items-center justify-content-center "
                                    data-bs-toggle="tab" href="javascript:;" role="tab" aria-selected="false">
                                    <i class="ni ni-settings-gear-65"></i>
                                    <span class="ms-2">Settings</span>
                                </a>
                            </li>
                        </ul>
                    </div> -->
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
    <div v-if="showPermisos" class="container-fluid py-3">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header pb-0">
                        <a class="btn btn-closed" style="float: right" @click="mostrarPermisos()">X</a>
                        <div class="d-flex align-items-center">
                            <h5>Permissions</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div v-if="isEmpty(permisos)">
                            <div class="fs-7">No permissions.</div>
                        </div>
                        <div v-else v-for="(items, group) in groupedPermissionsProfile" :key="group">
                            <div v-if="group != 'null'">
                                <div class="d-flex align-items-center">
                                    <h6>{{group}}</h6>
                                </div>
                                <div class="card-body">
                                    <div class="displayRolesPermisos">
                                        <p v-for="item in items" :key="item.name" class="mb-0 font-weight-bold text-sm">
                                            {{ item.name }} &nbsp;
                                        </p>
                                    </div>
                                </div>
                                <hr size="4" class="separador">
                            </div>
                            <div v-else>
                                <div class="d-flex align-items-center">
                                    <h6>Otros:</h6>
                                </div>
                                <div class="card-body">
                                    <div class="displayRolesPermisos">
                                        <p v-for="item in items" :key="item.name" class="mb-0 font-weight-bold text-sm">
                                            {{ item.name }} &nbsp;
                                        </p>
                                    </div>
                                </div>
                            </div>
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
            <!-- <div class="col-md-4 mt-4">
                <div class="card card-profile" style="min-height: 400px">
                    ################################################
                    <div style="text-align: center; vertical-align: center; margin: center;">
                        <label style="font-size: 24px;"><i class="fa-solid fa-triangle-exclamation fa-lg" style="margin-right: 30px; margin-top: 45%;"></i>EN MANTENCIÓN<i class="fa-solid fa-person-digging fa-2x" style="margin-left: 30px;"></i></label>
                    </div>
                    ################################################
                    <img src="/img/bg-profile.jpg" alt="Image placeholder" class="card-img-top">
                    <div class="row justify-content-center">
                        <div class="col-4 col-lg-4 order-lg-2">
                            <div class="mt-n4 mt-lg-n6 mb-4 mb-lg-0">
                                <a href="javascript:;">
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-header text-center border-0 pt-0 pt-lg-2 pb-4 pb-lg-3">
                        <div class="d-flex justify-content-between">
                            <a href="javascript:;" class="btn btn-sm btn-info mb-0 d-none d-lg-block">Connect</a>
                            <a href="javascript:;" class="btn btn-sm btn-info mb-0 d-block d-lg-none"><i
                                    class="ni ni-collection"></i></a>
                            <a href="javascript:;"
                                class="btn btn-sm btn-dark float-right mb-0 d-none d-lg-block">Message</a>
                            <a href="javascript:;" class="btn btn-sm btn-dark float-right mb-0 d-block d-lg-none"><i
                                    class="ni ni-email-83"></i></a>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="row">
                            <div class="col">
                                <div class="d-flex justify-content-center">
                                    <div class="d-grid text-center">
                                        <span class="text-lg font-weight-bolder">22</span>
                                        <span class="text-sm opacity-8">Friends</span>
                                    </div>
                                    <div class="d-grid text-center mx-4">
                                        <span class="text-lg font-weight-bolder">10</span>
                                        <span class="text-sm opacity-8">Photos</span>
                                    </div>
                                    <div class="d-grid text-center">
                                        <span class="text-lg font-weight-bolder">89</span>
                                        <span class="text-sm opacity-8">Comments</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-center mt-4">
                            <h5>
                                Mark Davis<span class="font-weight-light">, 35</span>
                            </h5>
                            <div class="h6 font-weight-300">
                                <i class="ni location_pin mr-2"></i>Bucharest, Romania
                            </div>
                            <div class="h6 mt-4">
                                <i class="ni business_briefcase-24 mr-2"></i>Solution Manager - Creative Tim Officer
                            </div>
                            <div>
                                <i class="ni education_hat mr-2"></i>University of Computer Science
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->
            <modalfotoperfil v-if="showFotoPerfil" @close="showFotoPerfil = false"></modalfotoperfil>
            <modalconfirmacion ref="confirmation"></modalconfirmacion>
            <modalEditPassword v-bind:password1="passwordEdit" v-if="showEditPassword" @close="showEditPassword = false" @recarga="recargarTabla('Vendedores')"></modalEditPassword>
            <modalalerta ref="alert"></modalalerta>
        </div>
    </div>
</template>
<script>
import axios from 'axios'
import modalconfirmacion from './snippets/sistema/alerts/confirmationModal.vue'
import modalalerta from './snippets/sistema/alerts/alertModal.vue'
import modalfotoperfil from './snippets/sistema/usuarios/profilePic.vue'
import modalEditPassword from './snippets/sistema/usuarios/editPasswordModal.vue'
import {mixin} from '../mixins.js'

export default {
    components: { modalconfirmacion, modalalerta, modalfotoperfil , modalEditPassword },
    mixins: [mixin],
    data(){
        return{
            showFotoPerfil: false,
            isEditing: false,
            usuario: null,
            profilePic: null,
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
            showPermisos: false,
            groupedPermissionsProfile: {},
        }
    },
    created(){
        this.getUsuarios(this.userID);
        this.getRolesUsuario(this.userID);
        this.getPermisosUsuario(this.userID);
    },
    methods: {
        editPassword(password){
            this.passwordEdit = password;
            this.showEditPassword = true;
        },
        mostrarRoles(){
            if(this.showRoles == false){
                this.showRoles = true;
            }
            else{
                this.showRoles = false;
            }
        },
        mostrarPermisos(){
            if(this.showPermisos == false){
                this.showPermisos = true;
            }
            else{
                this.showPermisos = false;
            }
        },
        getRolesUsuario($id){
            axios.get(`api/roles/${$id}`).then( response =>{
                this.roles = response.data;
            }).catch(e=> console.log(e))
        },
        getPermisosUsuario($id){
            axios.get(`api/permisos/${$id}`).then( response =>{
                this.permisos = response.data;
                this.permisos.forEach(permiso => {
                var grupoActual = permiso.group;
                if (!this.groupedPermissionsProfile[grupoActual]) {
                    this.groupedPermissionsProfile[grupoActual] = [];
                }
                this.groupedPermissionsProfile[grupoActual].push(permiso);
            });
            }).catch(e=> console.log(e))
        },
        getUsuarios(id){
            axios.get(`api/usuarios/${id}`).then( response =>{
                this.usuario = response.data;
                this.id = this.usuario.id;
                this.profilePic = this.usuario.profilePic;
                this.editables.name = this.usuario.name;
                this.editables.email = this.usuario.email;
            }).catch(e=> console.log(e))
        },
        editarPerfil(){
            this.isEditing = true;
        },
        cancelarEdicion(){
            this.isEditing = false;
            this.getUsuarios(this.userID);
        },
        capitalizeFirstLetter(string) {
          return string.charAt(0).toUpperCase() + string.slice(1);
        },
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
                message: `Are you sure you want to edit your profile? This action cannot be undone.`,
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