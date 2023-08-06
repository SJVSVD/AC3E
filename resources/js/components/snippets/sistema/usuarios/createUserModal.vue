<template>
  <transition name="modalcrear">
    <div name="modal">
      <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container-s">
              <div class="modal-header pb-1 fw-bold" style="color: #444444;">
                <slot name="header">
                    New User
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
                              <input type="password" class= "form-control" v-model="user.password">
                          </div>
                          <div class="col-3">
                              <label for="confirm-password"> Confirm Password: </label>
                              <br>
                              <input type="password" class= "form-control" v-model="user.confirmpassword">
                              <br>
                          </div>
                    </div>
                    <hr size="3" class="separador">
                    <div class="row">
                      <div class="col-6">
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
                      <div class="col-6">
                        <label for="">Permissions:</label>
                        <div v-if="marcados2 == false" class="form-check">
                          <label class="form-check-label fw-bold"><input type="checkbox" v-bind:value="0" @change="marcarTodos2()" class="form-check-input" v-model="selected2"> Check all </label>
                        </div>
                        <div v-else class="form-check">
                          <label class="form-check-label fw-bold"><input type="checkbox" v-bind:value="0" @change="marcarTodos2()" class="form-check-input" v-model="selected2"> Uncheck all </label>
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
                    <a class="btn btn-continue float-end" @click="crearUsuario()" :disabled="buttonDisable">
                      {{ buttonText }}
                    </a>
                  </slot>
                </div>
                <modalfotoperfil v-if="showFotoPerfil" @close="showFotoPerfil = false"></modalfotoperfil>
                <modalconfirmacion ref="confirmation"></modalconfirmacion>
                <modalalerta ref="alert"></modalalerta>
          </div>
        </div>
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

export default {
    components: { modalconfirmacion, modalalerta, },
    mixins: [mixin],
    data: () => ({
      user:{
        name: "",
        email: "",
        password: "",
        confirmpassword: "",
      },
      selectedRoles: [],
      selectedPermissions: [],
      buttonDisable: false,
      selected: [],
      selected2: [],
      marcados: false,
      marcados2: false,
      errors:[],
      roles: null,
      permisos: null,
      buttonText:'Create User',
    }),
    created(){
      this.getRoles();
      this.getPermisos();
    },
    methods: {
      cerrarModal(){
        const elem = this.$refs.closeBtn;
        this.$emit('recarga');
        elem.click();
      },
      getRoles(){
        axios.get('api/roles').then( response =>{
          this.roles = response.data;
        }).catch(e=> console.log(e))
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
      getPermisos(){
        axios.get('api/permisos').then( response =>{
        this.permisos = response.data;
        }).catch(e=> console.log(e))
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
      capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
      },
      async crearUsuario() {
        this.errors = [];
        for (const item in this.user){
          if(this.user[item] === "" || this.user[item] === 0){
              this.errors.push(item);
            }
        }

        if(this.user.password != this.user.confirmpassword){
          this.errors.push('contraseñas diferentes');
        }

        var mensaje = ""
        if (this.errors.length != 0){
          this.errors.forEach(item => {
            if(item == 'name'){
              mensaje =   mensaje + "The name field is required" + "\n";
            }else if(item == 'email'){
              mensaje =   mensaje + "The email field is required" + "\n";
            }else if(item == 'contraseñas diferentes'){
              mensaje =   mensaje + "Passwords do not match" + "\n";
            }else if(item == 'password'){
              mensaje =   mensaje + "The password field is required" + "\n";
            }else if(item == 'confirmpassword'){
              mensaje =   mensaje + "The confirm password field is required" + "\n";
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
        if (this.errors.length === 0){
          const ok = await this.$refs.confirmation.show({
            title: 'Create User',
            message: `¿Are you sure you want to create the user '${this.user.name}'? This action cannot be undone.`,
            okButton: 'Create',
            cancelButton: 'Return'
          })
          if (ok) {
            let usuario = {
              name: this.user.name,
              email: this.user.email,
              password: this.user.password,
              confirmpassword: this.user.confirmpassword,
              roles: this.selected,
              permisos: this.selected2,
            };
            await axios.post("api/usuarios", usuario ).then((result) => {
              this.buttonDisable = true;
              this.buttonText = 'Creating...';
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
                this.buttonText = 'Create User';
              }else{
                this.toast.success("User created successfully!", {
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
            });
          }
        }
      },
    },
}
</script>