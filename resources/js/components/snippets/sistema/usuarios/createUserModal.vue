<template>
  <transition name="modalcrear">
    <div name="modal">
      <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container-s">
              <div class="modal-header pb-1 fw-bold" style="color: #444444;">
                <slot name="header">
                    Nuevo Usuario
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
                              <input type="password" class= "form-control" v-model="user.password">
                          </div>
                          <div class="col-3">
                              <label for="confirm-password"> Confirmar contraseña: </label>
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
      buttonText:'Crear Usuario',
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
        if (this.errors.length === 0){
          const ok = await this.$refs.confirmation.show({
            title: 'Crear Usuario',
            message: `¿Está seguro/a que desea crear al usuario '${this.user.name}'? Esta acción no puede ser desecha.`,
            okButton: 'Crear',
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
            };
            axios.post("api/usuarios", usuario ).then((result) => {
              this.buttonDisable = true;
              this.buttonText = 'Creando...';
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
                this.buttonText = 'Crear Usuario';
              }else{
                this.toast.success("Usuario creado con éxito!", {
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