<template>
    <transition name="modaleditar">
        <div name="modal">
            <div class="modal-mask">
                <div class="modal-wrapper">
                    <div class="modal-container-s">
                        <div class="modal-header fw-bold" style="color: #444444;">
                            <slot name="header">
                                Editar Usuario
                            </slot>
                            <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
                        </div>
                        <div class="modal-body">
                        <slot name="body">
                            <div class="row">
                                <div class="col-3 d-flex align-items-center">
                                    <div v-if="fotoMiniatura != null && fotoAceptada == true">
                                        <a class="btn" @click="showFotoPerfil = true; fotoAceptada = false;">
                                            <div style="width: 150px; height: 150px; display: flex; justify-content: center; align-items: center;">
                                                <img class="img-not-draggable w-100 h-100 border-radius-100 shadow-sm" style="object-fit: cover;" v-bind:src="imagen" alt="profile_image">
                                            </div>
                                        </a>
                                        <div style="display: flex; justify-content: center;">
                                            <button class="btn btn-closed"  @click="deleteProfilePic();">Eliminar</button>
                                        </div>
                                    </div>
                                    <div v-else-if="fotoMiniatura == null && profilePic == null">
                                        <a class="btn" @click="showFotoPerfil = true; fotoAceptada = false;">
                                            <div style="width: 150px; height: 150px; display: flex; justify-content: center; align-items: center;">
                                                <img class="w-100 h-100 border-radius-100 shadow-sm" style="object-fit: cover;" src="https://icon-library.com/images/default-user-icon/default-user-icon-13.jpg" alt="profile_image">
                                            </div>
                                        </a>
                                        <div style="display: flex; justify-content: center;">
                                            <button class="btn btn-closed"  @click="deleteProfilePic();">Eliminar</button>
                                        </div>
                                    </div>
                                    <div v-else>
                                        <a class="btn" @click="showFotoPerfil = true; fotoAceptada = false;">
                                            <div style="width: 150px; height: 150px; display: flex; justify-content: center; align-items: center;">
                                                <img class="img-not-draggable w-100 h-100 border-radius-100 shadow-sm" style="object-fit: cover;" v-bind:src="'data:image/jpeg;base64,'+profilePic" alt="profile_image">
                                            </div>
                                        </a>
                                        <div style="display: flex; justify-content: center;">
                                            <button class="btn btn-closed"  @click="deleteProfilePic();">Eliminar</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-9">
                                    <div class="row">
                                        <div class="col-12">
                                            <label for="name">Nombre: </label>
                                            <br>
                                            <input type="text" class= "form-control"  v-has-error="errors.name" v-model="name">
                                            <div v-if="errors && errors.name" class="error">
                                                {{ errors.name[0] }}
                                            </div>
                                        </div>
                                        <div class="col-9">
                                            <label for="email">Correo Electrónico (Empresa): </label>
                                            <br>
                                            <input type="email" class= "form-control" v-model="email">
                                            <div  v-if="errors && errors.email" class="error">
                                                {{ errors.email[0] }}
                                            </div>
                                        </div>
                                        <div class="col-3">
                                            <label for="rut">Rut:</label>
                                            <br>
                                            <input type="text" @keyup="checkRut()" class="form-control" v-model="rut">
                                            <div v-if="errors && errors.rut" class="error">
                                                {{ errors.rut[0] }}
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <label for="password">Contraseña: </label>
                                            <br>
                                            <input type="password" class= "form-control" v-model="password" :disabled="cambiarContraseña">
                                            <div v-if="errors && errors.password" class="error">
                                                {{ errors.password[0] }}
                                            </div>
                                        </div>
                                        <div class="col-6">
                                            <label for="confirm-password"> Confirmar contraseña: </label>
                                            <br>
                                            <input type="password" class= "form-control" v-model="confirmpassword" :disabled="cambiarContraseña">
                                            <br>
                                        </div>
                                        <div class="form-check">
                                            <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                            v-model="cambiarContraseña" @change="this.password = '', this.confirmpassword = ''">
                                            Actualizar Contraseña </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr size="4" class="separador">
                            <div class="row">
                                <div class="col-8">
                                    <label for="colaborador"> Colaborador: </label>
                                    <select class="form-select" v-model="colaborador">
                                    <option :value="null">Seleccione un colaborador</option>
                                    <option v-for="colaborador in colaboradoresSelect" v-bind:key="colaborador.id" v-bind:value="colaborador.id">
                                        {{ colaborador.nombre1 }} {{ colaborador.nombre2 }} {{ colaborador.apellido1 }} {{ colaborador.apellido2 }}
                                    </option>
                                    </select>
                                    <div v-if="errors && errors.colaborador" class="error">
                                        {{ errors.colaborador[0] }}
                                    </div>
                                </div>
                                <br>
                                <div class="col-4">
                                    <div class="form-group">
                                    <label for="tipo">Tipo de Usuario: </label>
                                    <br>
                                    <select class="form-select" v-model="idTipoUsuario">
                                        <option :value="null">Seleccione un tipo</option>
                                        <option v-for="tipoUsuario in tiposUsuario" v-bind:key="tipoUsuario.id" v-bind:value="tipoUsuario.id">
                                        {{ tipoUsuario.nombre }}
                                        </option>
                                    </select>
                                    <div v-if="errors && errors.tipo" class="error">
                                        {{ errors.tipo[0] }}
                                    </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div v-if="editarFofi == true" class="form-group" style="margin-bottom: 0px;">
                                        <label class="form-label" for="typeNumber">Fondo fijo asignado: </label>
                                        <input type="number" class="form-control" v-model="fofiAsignado" />
                                    </div>
                                    <div class="form-check">
                                        <label class="form-check-label"><input type="checkbox" class="form-check-input" v-model="editarFofi">Editar fondo fijo </label>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-6">
                                    <label for="">Roles:</label>
                                    <div v-if="marcados == false" class="form-check">
                                    <label class="form-check-label fw-bold"><input type="checkbox" v-bind:value="0" @change="marcarTodos()" class="form-check-input" v-model="selected"> Marcar Todos </label>
                                    </div>
                                    <div v-else class="form-check">
                                    <label class="form-check-label fw-bold"><input type="checkbox" v-bind:value="0" @change="marcarTodos()" class="form-check-input" v-model="selected"> Desmarcar Todos </label>
                                    </div>
                                    <div v-for="rol in roles" :key="rol.id" class="form-check">
                                        <label class="form-check-label"><input type="checkbox" class="form-check-input" v-bind:id="rol.id" v-bind:value="rol.id" v-model="selected"  >
                                        {{rol.name}} </label>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <label for="">Permisos:</label>
                                    <div v-if="marcados2 == false" class="form-check">
                                    <label class="form-check-label fw-bold"><input type="checkbox" v-bind:value="0" @change="marcarTodos2()" class="form-check-input" v-model="selected2"> Marcar Todos </label>
                                    </div>
                                    <div v-else class="form-check">
                                    <label class="form-check-label fw-bold"><input type="checkbox" v-bind:value="0" @change="marcarTodos2()" class="form-check-input" v-model="selected2"> Desmarcar Todos </label>
                                    </div>
                                    <div v-for="permiso in permisos" :key="permiso.id" class="form-check">
                                        <label class="form-check-label"><input type="checkbox" class="form-check-input" v-bind:id="permiso.id" v-bind:value="permiso.id" v-model="selected2"  >
                                        {{permiso.name}} </label>
                                    </div>
                                </div>
                            </div>
                        </slot>
                        </div>
                    <div class="modal-footer">
                        <slot name="footer">
                            <a class="btn btn-continue" @click="editarUsuario()" :disabled="buttonDisable">
                                {{ buttonText }}
                            </a>
                        </slot>
                    </div>
                    </div>
                </div>
                <modalfotoperfil v-if="showFotoPerfil" @close="showFotoPerfil = false"></modalfotoperfil>
                <modalconfirmacion ref="confirmation"></modalconfirmacion>
                <modalalerta ref="alert"></modalalerta>
            </div>
        </div>
    </transition>
</template>

<script>
import axios from 'axios'
import modalconfirmacion from '../../sistema/alerts/confirmationModal.vue'
import modalalerta from '../../sistema/alerts/alertModal.vue'
import modalfotoperfil from './profilePicOnCreate-Edit.vue'
import {mixin} from '../../../../mixins.js'
import Multiselect from '@vueform/multiselect';

let roles = document.head.querySelector('meta[name="roles"]');

export default {
    components: { modalconfirmacion, modalalerta, modalfotoperfil, Multiselect },
    mixins: [mixin],
    data: () => ({
        profilePic: null,
        profilePicChange: null,
        fotoMiniatura: null,
        fotoAceptada: false,
        showFotoPerfil: null,
        buttonText:'Editar Usuario',
        buttonDisable: false,
        name: "",
        email: "",
        password: "",
        confirmpassword: "",
        fofiAsignado: 0,
        idTipoUsuario: null,
        colaborador: null,
        rut: "",
        selected: [],
        marcados: false,
        selected2: [],
        marcados2: false,
        editarFofi: false,
        id: "",
        errors: [],
        roles: '',
        permisos: '',
        colaboradores: null,
        colaboradoresSelect: [],
        rolesUsuario: null,
        permisosUsuario: null,
        revision: false,
        revision2: false,
        cambiarContraseña: true,
        tiposUsuario: null,
    }),
    props:{
        usuario: Object,
    },
    created(){
        this.profilePic = this.usuario.profilePicture;
        this.name = this.usuario.name;
        this.email = this.usuario.email;
        this.id = this.usuario.id;
        this.fofiAsignado = this.usuario.fofiAsignado;
        this.tipo = this.usuario.idTipoUsuario;
        this.rut = this.usuario.rut;
        this.colaborador = this.usuario.idColaborador;
        // this.rolesUsuario = roles.content;
        this.getRoles();
        this.getPermisos();
        this.getTiposUsuario();
        this.getColaboradores();
    },
    methods: {
        async deleteProfilePic() {
            const ok = await this.$refs.confirmation.show({
            title: 'Eliminar Foto de Perfil',
            message: `¿Está seguro/a que desea eliminar la foto de perfil?`,
            okButton: 'Eliminar',
            cancelButton: 'Volver'
            })
            if (ok) {
                axios.post(`api/usuarios/deleteProfilePic/${this.id}`).then((result) => {
                    this.buttonDisable = true;
                    this.buttonText = 'Guardando...';
                    this.toast.success("Foto de perfil eliminada con éxito!", {
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
                        rtl: false,
                    });
                    this.profilePicChange = null;
                    this.profilePic = null;
                    this.imagen = null;
                    this.fotoAceptada = null;
                    this.fotoMiniatura = null;
                    setTimeout(() => {location.reload();}, 1000);
                }).catch((e)=> {
                    console.log(e);
                    if (e.response.status == 422){
                    this.errors = e.response.data.errors;
                    this.toast.warning('Error.', {
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
                });
            }
        },
        cerrarModal(){
          const elem = this.$refs.closeBtn;
          this.$emit('recarga');
          elem.click();
        },
        async getRoles(){
            await axios.get('api/roles').then( response =>{
                this.roles = response.data;
            }).catch(e=> console.log(e))
            this.getRolesUsuario(this.id);
        },
        async getRolesUsuario($id){
            await axios.get(`api/roles/${$id}`).then( response =>{
                this.rolesUsuario = response.data;
            }).catch(e=> console.log(e))
            this.compararRoles();
        },
        compararRoles(){
            if(this.revision == false){
                this.roles.forEach(element => {
                    this.rolesUsuario.forEach(element2 => {
                        if(element.name == element2.name){
                            this.selected.push(element.id);
                        }
                    })
                });
            }
            this.revision = true;
        },
        marcarTodos(){
            if (this.marcados == false){
                this.roles.forEach(rol => {
                    this.selected.push(rol.id);
                });
                this.marcados = true;
            }else{
                this.selected = [];
                this.marcados = false;
            }
        },
        async getPermisos(){
            await axios.get('api/permisos').then( response =>{
                this.permisos = response.data;
            }).catch(e=> console.log(e))
            this.getPermisosUsuario(this.id);
        },
        async getPermisosUsuario($id){
            await axios.get(`api/permisos/${$id}`).then( response =>{
                this.permisosUsuario = response.data;
            }).catch(e=> console.log(e))
            this.compararPermisos();
        },
        compararPermisos(){
            if(this.revision2 == false){
                this.permisos.forEach(element => {
                    this.permisosUsuario.forEach(element2 => {
                        if(element.name == element2.name){
                            this.selected2.push(element.id);
                        }
                    })
                });
            }
            this.revision2 = true;
        },
        marcarTodos2(){
            if (this.marcados2 == false){
                this.permisos.forEach(permiso => {
                    this.selected2.push(permiso.id);
                });
                this.marcados2 = true;
            }else{
                this.selected2 = [];
                this.marcados2 = false;
            }
        },
        getTiposUsuario(){
            axios.get('../api/tiposUsuario').then( response =>{
                this.tiposUsuario = response.data;
            }).catch(e=> console.log(e))
        },
        checkRut() {
            if (this.rut != ''){
            this.rut.replace('.','');
            // Despejar Puntos
            var valor = this.rut.replace('.','');
    
            // Despejar Guión
            valor = valor.replace('-','');
            
            // Aislar Cuerpo y Dígito Verificador
            var cuerpo = valor.slice(0,-1);
            var dv = valor.slice(-1).toUpperCase();
            
            // Formatear RUN
            this.rut = cuerpo + '-'+ dv
            }
        },
        getColaboradores(){
            axios.get('api/colaboradores').then( response =>{
            this.colaboradores = response.data;
            this.colaboradores.forEach(colaborador => {
              if (colaborador.user == null) {
                this.colaboradoresSelect.push(colaborador);
              }
            });
            if (this.usuario.colaborador != null){
                this.colaboradoresSelect.push(this.usuario.colaborador);
            }
            }).catch(e=> console.log(e))
        },
        async editarUsuario() {
            this.errors = [];
            if(this.rut != null & this.rut != '' ){
                var validacion = this.validarRut(this.rut)
                if(validacion == false){
                    this.errors.push('rut')
                    this.toast.warning("El rut ingresado es invalido", {
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
                }
            }
            if(this.errors.length === 0){
                const ok = await this.$refs.confirmation.show({
                    title: 'Editar Usuario',
                    message: `¿Está seguro/a que desea editar al usuario '${this.usuario.name}'? Esta acción no puede ser desecha.`,
                    okButton: 'Editar',
                    cancelButton: 'Volver'
                })
                if (ok) {
                    let usuario = {
                        idColaborador: this.colaborador,
                        name: this.name,
                        email: this.email,
                        password: this.password,
                        confirmpassword: this.confirmpassword,
                        roles: this.selected,
                        permisos: this.selected2,
                        fofiAsignado: this.fofiAsignado, 
                        idTipoUsuario: this.idTipoUsuario,
                        rut: this.formatoRut(this.rut),
                        profilePicChange: this.profilePicChange,
                    }
                    var id = this.id;
                    axios.post(`api/usuarios/updateUser/${id}`, usuario, { headers: { 'Content-Type' : 'multipart/form-data' } } ).then((result) => {
                        console.log(result);
                        this.buttonText = 'Guardando...';
                        this.buttonDisable = true;
                        this.toast.success("Usuario editado con éxito!", {
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
                        setTimeout(() => {this.cerrarModal();}, 1500);
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
                }
            }
        },
      async compressImages(fileInput, output) {
        return new Promise(async function (resolve) {
            const file = fileInput;
            await new Promise((resolve) => {
              new Compressor(file, {
                quality: 0.5,
                success(result) {
                  output.push(result);
                  resolve();
                }
              });
            });
            resolve();
        });
      },
      async getImage(e){
          this.profilePicChange = e.target.files[0];
          let compression = [];
          await this.compressImages(this.profilePicChange,compression);
          var file = new File([compression[0]], compression[0]['name'], {
          type: compression[0]['type'],});
          this.profilePicChange = file;
          this.chargeImage(this.profilePicChange);
      },
      chargeImage(file){
          let reader = new FileReader();
          reader.onload = (e) => { 
              this.fotoMiniatura = e.target.result;
          }
          reader.readAsDataURL(file);
      },
    },
    computed: {
      imagen(){
          return this.fotoMiniatura;
      }
    }
}
</script>