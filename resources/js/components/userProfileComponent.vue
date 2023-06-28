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
                        <a @click="mostrarPermisos()" class="btn btn-dark">Permisos</a>
                        &nbsp;
                    </div>
                </div>
                <div class="col-md-6 me-sm-0 mx-auto mt-3">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="example-text-input" class="form-control-label">Departamento:</label>
                                <br>
                                <input disabled class="form-control" type="text" name="departamento" id="departamento" v-model="departamento">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="example-text-input" class="form-control-label">Área:</label>
                                <br>
                                <input disabled class="form-control" type="text" name="area" id="area" v-model="area">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="example-text-input" class="form-control-label">Cargo:</label>
                                <br>
                                <input disabled class="form-control" type="text" name="cargo" id="cargo" v-model="cargo">
                            </div>
                        </div>
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
                        <div class="rolesPerfil">
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
                            <h5>Permisos</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="rolesPerfil">
                            <p v-for="permiso in permisos" :key="permiso" class="mb-0 font-weight-bold text-sm">
                                {{ permiso.name }} &nbsp;
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
                        <a v-show="!isEditing" @click="editarPerfil()" class="btn btn-alert" title="Editar" style="float: right; font-size: 16px;"><i class="fa fa-fw fa-edit"></i> Editar</a>
                        <div class="d-flex align-items-center">
                            <h5>Datos personales</h5>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="text-uppercase">Información Personal</div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Sobre mi:</label>
                                    <br>
                                    <textarea disabled class="form-control" type="text" name="sobreMi" id="sobreMi" v-model="editable.sobreMi"></textarea>
                                </div>
                            </div>
                        </div>
                        <hr class="horizontal dark">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Nombre:</label>
                                    <br>
                                    <input disabled class="form-control" type="text" name="nombre" id="nombre" v-model="nombre1">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Segundo Nombre:</label>
                                    <br>
                                    <input disabled class="form-control" type="text" name="nombre" id="nombre" v-model="nombre2">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Apellido Paterno:</label>
                                    <br>
                                    <input disabled class="form-control" type="text" name="nombre" id="nombre" v-model="apellido1">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Apellido Materno:</label>
                                    <br>
                                    <input disabled class="form-control" type="text" name="nombre" id="nombre" v-model="apellido2">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Correo Electrónico (Personal):</label>
                                    <br>
                                    <input disabled class="form-control" type="email" name="emailPersonal" id="emailPersonal" v-model="editable.correoPersonal">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Número de Teléfono (Personal):</label>
                                    <br>
                                    <input disabled class="form-control" type="text" name="telefonoPersonal" id="telefonoPersonal" v-model="editable.telefonoPersonal">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Correo Electrónico (Empresa):</label>
                                    <br>
                                    <input disabled class="form-control" type="email" name="emailEmpresa" id="emailEmpresa" v-model="correoEmpresa">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Teléfono (Empresa):</label>
                                    <br>
                                    <input disabled class="form-control" type="text" name="telefonoEmpresa" id="telefonoEmpresa" v-model="telefonoEmpresa">
                                </div>
                            </div>
                        </div>
                        <hr class="horizontal dark">
                        <div class="text-uppercase">Información de Contacto</div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Calle:</label>
                                    <br>
                                    <input disabled class="form-control" type="text" name="calle" id="calle" v-model="editable.direccion">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Sector:</label>
                                    <br>
                                    <input disabled class="form-control" type="text" name="sector" id="sector" v-model="editable.sector">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="example-text-input" class="form-control-label" >País:</label>
                                <br>
                                <select disabled id="pais" class="form-select" @change="getRegionesSelect($event.target.value)" style="width: 80%" v-model="editable.pais">
                                    <option disabled value="">Seleccione un País</option>
                                    <option v-for="pais in paises" v-bind:key="pais.id" v-bind:value="pais.id">
                                    {{ pais.pais }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label" >Región:</label>
                                    <br>
                                    <select disabled id="region" class="form-select" @change="getComunasSelect($event.target.value)" style="width: 80%" v-model="editable.region">
                                        <option disabled value="">Seleccione un Región</option>
                                        <option v-for="region in regionesSelect" v-bind:key="region.id" v-bind:value="region.id">
                                        {{ region.region }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label" >Comuna:</label>
                                    <br>
                                    <select disabled id="comuna" class="form-select" style="width: 80%" v-model="editable.comuna">
                                        <option disabled value="">Seleccione un comuna</option>
                                        <option v-for="comuna in comunasSelect" v-bind:key="comuna.id" v-bind:value="comuna.id">
                                        {{ comuna.comuna }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <a v-show="isEditing" @click="guardarPerfil()" class="btn btn-continue" style=" font-size: 16px;"><i class="fa-regular fa-floppy-disk fa-lg"></i> Guardar</a>
                            &nbsp;
                            <a v-show="isEditing" @click="cancelarEdicion()" class="btn btn-closed" style=" font-size: 16px;"><i class="fa-solid fa-ban"></i> Cancelar</a>
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
            <modalalerta ref="alert"></modalalerta>
        </div>
    </div>
</template>
<script>
import axios from 'axios'
import modalconfirmacion from './snippets/sistema/alerts/confirmationModal.vue'
import modalalerta from './snippets/sistema/alerts/alertModal.vue'
import modalfotoperfil from './snippets/sistema/usuarios/profilePic.vue'
import {mixin} from '../mixins.js'

export default {
    components: { modalconfirmacion, modalalerta, modalfotoperfil },
    mixins: [mixin],
    data(){
        return{
            showFotoPerfil: false,
            isEditing: false,
            usuario: null,
            profilePic: null,
            nombre1: null,
            nombre2: null,
            apellido1: null,
            apellido2: null,
            correoEmpresa: null,
            telefonoEmpresa: null,
            departamento: null,
            area: null,
            cargo: null,
            roles: null,
            permisos: null,
            editable: {
                sobreMi: null,
                correoPersonal: null,
                telefonoPersonal: null,
                direccion: null,
                sector: null,
                pais: null,
                region: null,
                comuna: null,
            },
            id: null,
            paises: null,
            regiones: null,
            regionesSelect: null,
            comunas: null,
            comunasSelect: null,
            errors: [],
            showRoles: false,
            showPermisos: false,
        }
    },
    created(){
        this.getUsuarios(this.userID);
        this.getFotoUsuario();
        this.getPaises();
        this.getRegiones();
        this.getComunas();
        this.getRolesUsuario(this.userID);
        this.getPermisosUsuario(this.userID);
    },
    methods: {
        getFotoUsuario(){
            axios.post(`api/usuarios/getProfilePic/${this.userID}`).then( response =>{
                this.profilePic = response.data;
            }).catch(e=> console.log(e))
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
            }).catch(e=> console.log(e))
        },
        getRegionesSelect(id){
            this.regionesSelect = []
            this.regiones.forEach(region => {
                if(region.idPais == id){
                    this.regionesSelect.push(region);
                }
            });
            this.comunasSelect = null;
        },
        getComunasSelect(id){
            this.comunasSelect = []
            this.comunas.forEach(comuna => {
                if(comuna.idRegion == id){
                    this.comunasSelect.push(comuna);
                }
            });
        },
        getPaises(){
            axios.get('api/paises').then( response =>{
                this.paises = response.data;
            }).catch(e=> console.log(e))
        },
        getRegiones(){
            axios.get('api/regiones').then( response =>{
                this.regiones = response.data;
                this.getRegionesSelect(this.editable.pais);
            }).catch(e=> console.log(e))
        },
        getComunas(){
            axios.get('api/comunas').then( response =>{
                this.comunas = response.data;
                this.getComunasSelect(this.editable.region);
            }).catch(e=> console.log(e))
        },
        getUsuarios(id){
            axios.get(`api/usuarios/${id}`).then( response =>{
                this.usuario = response.data;
                this.id = this.usuario.colaborador.id;
                this.editable.sobreMi = this.usuario.colaborador.sobreMi;
                this.nombre1 = this.usuario.colaborador.nombre1;
                this.nombre2 = this.usuario.colaborador.nombre2;
                this.apellido1 = this.usuario.colaborador.apellido1;
                this.apellido2 = this.usuario.colaborador.apellido2;
                this.editable.correoPersonal = this.usuario.colaborador.correoPersonal;
                this.editable.telefonoPersonal = this.usuario.colaborador.telefonoPersonal;
                this.correoEmpresa = this.usuario.colaborador.correoEmpresa;
                this.telefonoEmpresa = this.usuario.colaborador.telefonoEmpresa;
                this.editable.direccion = this.usuario.colaborador.direccion;
                this.editable.sector = this.usuario.colaborador.sector;
                this.editable.pais = this.usuario.colaborador.pais.id;
                this.editable.region = this.usuario.colaborador.region.id;
                this.editable.comuna = this.usuario.colaborador.comuna.id;
                this.departamento = this.usuario.colaborador.departamento.departamento;
                this.area = this.usuario.colaborador.area.area;
                this.cargo = this.usuario.colaborador.cargo;
            }).catch(e=> console.log(e))
        },
        editarPerfil(){
            this.isEditing = true;
            document.getElementById("sobreMi").disabled = false;
            document.getElementById("emailPersonal").disabled = false;
            document.getElementById("telefonoPersonal").disabled = false;
            document.getElementById("calle").disabled = false;
            document.getElementById("sector").disabled = false;
            document.getElementById("pais").disabled = false;
            document.getElementById("region").disabled = false;
            document.getElementById("comuna").disabled = false;
        },
        cancelarEdicion(){
            this.isEditing = false;
            document.getElementById("sobreMi").disabled = true;
            document.getElementById("emailPersonal").disabled = true;
            document.getElementById("telefonoPersonal").disabled = true;
            document.getElementById("calle").disabled = true;
            document.getElementById("sector").disabled = true;
            document.getElementById("pais").disabled = true;
            document.getElementById("region").disabled = true;
            document.getElementById("comuna").disabled = true;
            this.getUsuarios(this.userID);
            this.getPaises();
            this.getRegiones();
            this.getComunas();
        },
        async guardarPerfil(){
            this.errors = [];
            for (const item in this.editable){
                if(this.editable[item] === "" || this.editable[item] === null){
                    if(item != 'sobreMi'){
                        this.errors.push(item);
                    }
                }
            }
            var mensaje = ""
            if (this.errors.length != 0){
                this.errors.forEach(item => {
                    mensaje =   mensaje + "El campo " + item + " es requerido" + "\n" 
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
                title: 'Editar Perfil',
                message: `Está seguro/a que desea editar su perfil ? Esta acción no puede ser desecha.`,
                okButton: 'Editar',
                cancelButton: 'Volver'
                })
                if (ok) {
                let perfil = {
                    sobreMi: this.editable.sobreMi,
                    correoPersonal: this.editable.correoPersonal,
                    telefonoPersonal: this.editable.telefonoPersonal,
                    direccion: this.editable.direccion,
                    sector: this.editable.sector,
                    pais: this.editable.pais,
                    region: this.editable.region,
                    comuna: this.editable.comuna,
                };
                var id = this.id;
                axios.put(`api/colaboradores/${id}`, perfil).then((result) => {
                    this.buttonText = 'Guardando...';
                    this.buttonDisable = true;
                    this.toast.success("Perfil editado con éxito!", {
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
                    document.getElementById("sobreMi").disabled = true;
                    document.getElementById("emailPersonal").disabled = true;
                    document.getElementById("telefonoPersonal").disabled = true;
                    document.getElementById("calle").disabled = true;
                    document.getElementById("sector").disabled = true;
                    document.getElementById("pais").disabled = true;
                    document.getElementById("region").disabled = true;
                    document.getElementById("comuna").disabled = true;
                })
                .catch((error)=> {
                    if (error.response.status == 422){
                    this.errors = error.response.data.errors;
                    this.toast.warning('Existe un valor inválido.', {
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
                setTimeout(() => {this.cerrarModal();}, 1500);
                }
            }
        },
    }
}
</script>