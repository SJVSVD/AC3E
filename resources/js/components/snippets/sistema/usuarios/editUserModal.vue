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
                                    <div class="col-3">
                                        <label for="name">Nombre: </label>
                                        <br>
                                        <input type="text" class= "form-control" v-model="user.name">
                                    </div>
                                    <div class="col-3">
                                        <label for="email">Correo Electrónico: </label>
                                        <br>
                                        <input type="email" class= "form-control" v-model="user.email">
                                    </div>
                                    <div class="col-3">
                                        <label for="password">Contraseña: </label>
                                        <br>
                                        <input type="password" class= "form-control" v-model="user.password" :disabled="cambiarContraseña">
                                    </div>
                                    <div class="col-3">
                                        <label for="confirm-password"> Confirmar contraseña: </label>
                                        <br>
                                        <input type="password" class= "form-control" v-model="user.confirmpassword" :disabled="cambiarContraseña">
                                        <br>
                                    </div>
                                    <div class="form-check pt-2">
                                        <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                        v-model="cambiarContraseña" @change="user.password = '', user.confirmpassword = ''">
                                        Actualizar Contraseña </label>
                                    </div>
                                </div>
                                <hr size="3" class="separador">
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
                            <a class="btn btn-continue float-end" @click="editarUsuario()" :disabled="buttonDisable">
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
import {mixin} from '../../../../mixins.js'
import Multiselect from '@vueform/multiselect';

let roles = document.head.querySelector('meta[name="roles"]');

export default {
    components: { modalconfirmacion, modalalerta,  },
    mixins: [mixin],
    data: () => ({
        buttonText:'Editar Usuario',
        buttonDisable: false,
        user:{
            name: "",
            email: "",
            password: "",
            confirmpassword: "",
        },
        selected: [],
        marcados: false,
        selected2: [],
        marcados2: false,
        id: "",
        errors: [],
        roles: '',
        permisos: '',
        rolesUsuario: null,
        permisosUsuario: null,
        revision: false,
        revision2: false,
        cambiarContraseña: true,
    }),
    props:{
        usuario: Object,
    },
    created(){
        this.user.name = this.usuario.name;
        this.user.email = this.usuario.email;
        this.id = this.usuario.id;
        this.getRoles();
        this.getPermisos();
    },
    methods: {
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
        async editarUsuario() {
            this.errors = [];
            for (const item in this.user){
                if(this.user[item] === "" || this.user[item] === 0){
                    if((item == 'password' || item == 'confirmpassword') && this.cambiarContraseña == true ){
                    }else{
                        this.errors.push(item);
                    }
                }
            }

            if(this.user.password != this.user.confirmpassword){
                this.errors.push('contraseñas diferentes');
            }

            var mensaje = ""
            if (this.errors.length != 0){
                this.errors.forEach(item => {
                    if(item == 'name'){
                    mensaje =   mensaje + "El campo de Nombre es requerido" + "\n";
                    }else if(item == 'email'){
                    mensaje =   mensaje + "El campo de Correo Electrónico es requerido" + "\n";
                    }else if(item == 'contraseñas diferentes'){
                    mensaje =   mensaje + "Las Contraseñas ingresadas no coinciden" + "\n";
                    }else if(item == 'password'){
                    mensaje =   mensaje + "El campo Contraseña es requerido" + "\n";
                    }else if(item == 'confirmpassword'){
                    mensaje =   mensaje + "El campo Confirmar Contraseña es requerido" + "\n";
                    }else{
                    mensaje =   mensaje + "El campo " + this.capitalizeFirstLetter(item) + " es requerido" + "\n" 
                    }
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
                    title: 'Editar Usuario',
                    message: `¿Está seguro/a que desea editar al usuario '${this.user.name}'? Esta acción no puede ser desecha.`,
                    okButton: 'Editar',
                    cancelButton: 'Volver'
                })
                if (ok) {
                    let usuario = {
                        name: this.user.name,
                        email: this.user.email,
                        password: this.user.password,
                        confirmpassword: this.user.confirmpassword,
                        roles: this.selected,
                        permisos: this.selected2,
                    }
                    var id = this.id;
                    axios.post(`api/usuarios/updateUser/${id}`, usuario ).then((result) => {
                        console.log(result);
                        this.buttonText = 'Editando...';
                        this.buttonDisable = true;
                        if(result.data == 'email existente'){
                            this.toast.warning( 'Ya existe un usuario con el Correo Electrónico ingresado', {
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
                            this.buttonText = 'Editar Usuario';
                        }else{
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
                        }

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
    },
}
</script>