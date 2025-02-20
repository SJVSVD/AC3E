<template>
    <transition name="modaleditar">
        <div name="modal">
            <div class="modal-mask">
                <div class="modal-wrapper">
                    <div class="modal-container">
                        <div class="modal-header fw-bold" style="color: #444444;">
                            <slot name="header">
                                Edit User
                            </slot>
                            <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
                        </div>
                        <div class="modal-body">
                            <slot name="body">
                                <div class="row">
                                    <div class="col-3">
                                        <label for="name">Name: </label>
                                        <br>
                                        <input type="text" class= "form-control" v-model="user.name">
                                    </div>
                                    <div class="col-3">
                                        <label for="email">Email: </label>
                                        <br>
                                        <input type="email" class= "form-control" v-model="user.email">
                                    </div>
                                    <div class="col-3">
                                        <label for="password">Password: </label>
                                        <br>
                                        <input type="password" class= "form-control" v-model="user.password" :disabled="cambiarContraseña == false">
                                    </div>
                                    <div class="col-3">
                                        <label for="confirm-password"> Confirm Password: </label>
                                        <br>
                                        <input type="password" class= "form-control" v-model="user.confirmpassword" :disabled="cambiarContraseña == false">
                                        <br>
                                    </div>
                                    <div class="form-check pt-2">
                                        <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                        v-model="cambiarContraseña" @change="user.password = '', user.confirmpassword = ''">
                                        Update Password </label>
                                    </div>
                                </div>
                                <br>
                                <div class="row">
                                    <div class="col-6">
                                        <label for="">Role User:</label>
                                        <select class="form-select" v-model="user.idRole">
                                        <option disabled value="">Select a role</option>
                                        <option v-for="roleUser in rolesUser" v-bind:key="roleUser.id" v-bind:value="roleUser.id">
                                        {{ roleUser.name }}
                                        </option>
                                        </select>
                                    </div>
                                    <div class="col-6">
                                        <label for="">Research Line:</label>
                                        <select class="form-select" v-model="user.idResearchLine">
                                        <option disabled value="">Select a research line</option>
                                        <option v-for="researchLine in researchLines" v-bind:key="researchLine.id" v-bind:value="researchLine.id">
                                        {{ researchLine.name }}
                                        </option>
                                        </select>
                                    </div>
                                </div>
                                <hr size="3" class="separador">
                                <div class="row">
                                    <div class="col-12">
                                        <label for="">Roles:</label>
                                        <div v-if="marcados == false" class="form-check">
                                        <label class="form-check-label fw-bold"><input type="checkbox" v-bind:value="0" @change="marcarTodos()" class="form-check-input" v-model="selected"> Check all </label>
                                        </div>
                                        <div v-else class="form-check">
                                        <label class="form-check-label fw-bold"><input type="checkbox" v-bind:value="0" @change="marcarTodos()" class="form-check-input" v-model="selected"> Uncheck all </label>
                                        </div>
                                        <div v-for="rol in roles" :key="rol.id" class="form-check">
                                            <label class="form-check-label"><input type="checkbox" class="form-check-input" v-bind:id="rol.id" v-bind:value="rol.id" v-model="selected"  >
                                            {{rol.name}} </label>
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

let roles = document.head.querySelector('meta[name="roles"]');

export default {
    components: { modalconfirmacion, modalalerta,  },
    mixins: [mixin],
    data: () => ({
        buttonText:'Edit Record',
        buttonDisable: false,
        user:{
            name: "",
            email: "",
            password: "",
            confirmpassword: "",
            idRole:"",
            idResearchLine:"",
        },
        selected: [],
        marcados: false,
        selected2: [],
        marcados2: false,
        id: "",
        errors: [],
        roles: '',
        rolesUsuario: null,
        rolesUser: null,
        researchLines: null,
        revision: false,
        revision2: false,
        cambiarContraseña: false,
    }),
    props:{
        usuario: Object,
    },
    created(){
        this.user.name = this.usuario.name;
        this.user.email = this.usuario.email;
        this.id = this.usuario.id;
        this.user.idResearchLine = this.usuario.idResearchLine;
        this.user.idRole = this.usuario.idRole;
        this.getRoles();
        this.getResearchLines();
        this.getUserRoles();
    },
    methods: {
        // Obtiene las líneas de investigación.
        getResearchLines(){
            axios.get('api/researchLines').then( response =>{
                this.researchLines = response.data;
            }).catch(e=> console.log(e))
        },

        // Obtiene los roles de usuario.
        getUserRoles(){
            axios.get('api/rolesUser').then( response =>{
                this.rolesUser = response.data;
            }).catch(e=> console.log(e))
        },

        // Cierra el modal y emite un evento de recarga.
        cerrarModal(){
            const elem = this.$refs.closeBtn;
            this.$emit('recarga');
            elem.click();
        },

        // Obtiene todos los roles y luego los roles del usuario específico.
        async getRoles(){
            await axios.get('api/roles').then( response =>{
                this.roles = response.data;
            }).catch(e=> console.log(e))
            this.getRolesUsuario(this.id);
        },

        // Obtiene los roles específicos de un usuario.
        async getRolesUsuario($id){
            await axios.get(`api/roles/${$id}`).then( response =>{
                this.rolesUsuario = response.data;
            }).catch(e=> console.log(e))
            this.compararRoles();
        },

        // Compara los roles del usuario con los roles disponibles.
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

        // Marca todos los roles.
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

        // Edita el registro despues de validar
          // Edita el registro despues de validar
        async editarUsuario() {
            this.errors = [];
            for (const item in this.user){
                if(this.user[item] === "" || this.user[item] === 0){
                    if((item == 'password' || item == 'confirmpassword') && this.cambiarContraseña == false ){
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
                    mensaje =   mensaje + "The Name field is required" + "\n";
                    }else if(item == 'email'){
                    mensaje =   mensaje + "The Email field is required" + "\n";
                    }else if(item == 'contraseñas diferentes'){
                    mensaje =   mensaje + "Passwords do not match" + "\n";
                    }else if(item == 'password'){
                    mensaje =   mensaje + "The Password field is required" + "\n";
                    }else if(item == 'confirmpassword'){
                    mensaje =   mensaje + "The Confirm password field is required" + "\n";
                    }else if(item == 'idRole'){
                    mensaje =   mensaje + "The Role user field is required" + "\n";
                    }else{
                    mensaje =   mensaje + "The" + this.capitalizeFirstLetter(item) + "field is required" + "\n" 
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
                    title: 'Edit User',
                    message: `¿Are you sure you want to edit the user '${this.user.name}'?.`,
                    okButton: 'Edit',
                    cancelButton: 'Return'
                })
                if (ok) {
                    let usuario = {
                        name: this.user.name,
                        email: this.user.email,
                        password: this.user.password,
                        confirmpassword: this.user.confirmpassword,
                        roles: this.selected,
                        idRole: this.user.idRole,
                        idResearchLine: this.user.idResearchLine,
                    }
                    var id = this.id;
                    axios.post(`api/usuarios/updateUser/${id}`, usuario ).then((result) => {
                        console.log(result);
                        this.buttonText = 'Editando...';
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
                            this.toast.success("User edited successfully!", {
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