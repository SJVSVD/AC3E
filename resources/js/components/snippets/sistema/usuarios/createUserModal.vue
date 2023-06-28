<template>
  <transition name="modalcrear">
    <div name="modal">
      <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container-s">
              <div class="modal-header fw-bold" style="color: #444444;">
                <slot name="header">
                    Nuevo Usuario
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
                              <button class="btn btn-closed" @click="profilePicChange = null; profilePic = null; imagen = null; fotoAceptada = null; fotoMiniatura = null;">Eliminar</button>
                            </div>
                        </div>
                        <div v-else>
                            <a class="btn" @click="showFotoPerfil = true; fotoAceptada = false;">
                                <div style="width: 150px; height: 150px; display: flex; justify-content: center; align-items: center;">
                                    <img class="w-100 h-100 border-radius-100 shadow-sm" style="object-fit: cover;" src="https://icon-library.com/images/default-user-icon/default-user-icon-13.jpg" alt="profile_image">
                                </div>
                            </a>
                            <div style="display: flex; justify-content: center;">
                              <button class="btn btn-closed" @click="profilePicChange = null; profilePic = null; imagen = null; fotoAceptada = null; fotoMiniatura = null;">Eliminar</button>
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
                              <input type="password" class= "form-control" v-model="password">
                              <div v-if="errors && errors.password" class="error">
                                {{ errors.password[0] }}
                              </div>
                          </div>
                          <div class="col-6">
                              <label for="confirm-password"> Confirmar contraseña: </label>
                              <br>
                              <input type="password" class= "form-control" v-model="confirmpassword">
                              <br>
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
                          <label for="">Tipo de Usuario: </label>
                          <br>
                          <select class="form-select" v-model="idTipoUsuario">
                            <option :value="null">Seleccione un tipo de usuario</option>
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
                        <div v-if="asignarFofi == true" class="form-group" style="margin-bottom: 0px;">
                            <label class="form-label" for="typeNumber">Fondo fijo asignado: </label>
                            <input type="number" class="form-control-small" v-model="fofiAsignado" />
                        </div>
                        <div class="form-check">
                          <label class="form-check-label"><input type="checkbox" class="form-check-input" v-model="asignarFofi">Asignar fondo fijo </label>
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
                        <!-- <div>
                          <Multiselect
                                placeholder="Seleccione una o más opciones:"
                                v-model="selectedRoles"
                                :searchable="true"
                                :close-on-select="false"
                                :createTag="true"
                                :options="roles"
                                mode="tags"
                                label="name"
                                trackBy="id"
                                :object="true"
                            />
                            <p> {{ selectedRoles }} </p>
                        </div> -->
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
                        <!-- <div>
                          <Multiselect
                                placeholder="Seleccione una o más opciones:"
                                v-model="selectedPermissions"
                                :searchable="true"
                                :close-on-select="false"
                                :createTag="true"
                                :options="permisos"
                                mode="tags"
                                label="name"
                                trackBy="id"
                                :object="true"
                            />
                            <p> {{ selectedPermissions }} </p>
                        </div> -->
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
                    <a class="btn btn-continue" @click="crearUsuario()" :disabled="buttonDisable">
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
import modalfotoperfil from './profilePicOnCreate-Edit.vue'
import {mixin} from '../../../../mixins.js'
import Multiselect from '@vueform/multiselect';

export default {
    components: { modalconfirmacion, modalalerta, modalfotoperfil, Multiselect },
    mixins: [mixin],
    data: () => ({
        profilePic: null,
        profilePicChange: null,
        fotoMiniatura: null,
        fotoAceptada: false,
        showFotoPerfil: null,
        buttonText:'Guardar Usuario',
        selectedRoles: [],
        selectedPermissions: [],
        buttonDisable: false,
        name: "",
        email: "",
        password: "",
        confirmpassword: "",
        fofiAsignado: 0,
        colaborador: null,
        idTipoUsuario: null,
        rut: "",
        selected: [],
        selected2: [],
        marcados: false,
        marcados2: false,
        errors:[],
        roles: null,
        permisos: null,
        colaboradores: null,
        colaboradoresSelect: [],
        asignarFofi: false,
        tiposUsuario: null,
    }),
    created(){
      this.getRoles();
      this.getPermisos();
      this.getTiposUsuario();
      this.getColaboradores();
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
            }).catch(e=> console.log(e))
        },
      async crearUsuario() {
        this.errors = [];
        if(this.rut != ""){
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
        if (this.errors.length === 0){
          const ok = await this.$refs.confirmation.show({
            title: 'Crear Usuario',
            message: `¿Está seguro/a que desea crear al usuario '${this.name}'? Esta acción no puede ser desecha.`,
            okButton: 'Crear',
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
            };
            axios.post("api/usuarios", usuario, { headers: { 'Content-Type' : 'multipart/form-data' } } ).then((result) => {
              this.buttonDisable = true;
              this.buttonText = 'Guardando...';
              this.toast.success("Usuario guardado con éxito!", {
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