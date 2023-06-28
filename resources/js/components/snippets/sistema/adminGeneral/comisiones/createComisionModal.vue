<template>
    <transition name="modalcrear">
      <div name="modal">
        <div class="modal-mask">
            <div class="modal-wrapper">
              <div class="modal-container-s">
                <div class="modal-header fw-bold" style="color: #444444;">
                  <slot name="header">
                      Nueva Comisión
                  </slot>
                  <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
                </div>
                <div class="modal-body">
                  <slot name="body">
                    <div class="row">
                      <div class="col-4">
                          <div class="form-group">
                          <label for="">Estado: </label>
                          <br>
                          <select class="form-select" v-model="comision.estado">
                          <option disabled value="">Seleccione un estado</option>
                          <option :value="true">Activo</option>
                          <option :value="false">Inactivo</option>
                          </select>
                          </div>
                          <div v-if="errors && errors.estado" class="error">
                              {{ errors.estado[0] }}
                          </div>
                      </div>
                      <div class="col-8">
                        <div class="form-group">
                            <label for="">Usuario Responsable: </label>
                            <br>
                            <select class="form-select" v-model="comision.idUsuario">
                              <option disabled value="">Seleccione un usuario</option>
                              <option v-for="usuario in usuarios" v-bind:key="usuario.id" v-bind:value="usuario.id">
                              {{ usuario.name }}
                              </option>
                            </select>
                        </div>
                        <div v-if="errors && errors.idUsuario" class="error">
                          {{ errors.idUsuario[0] }}
                        </div>
                      </div>
                      <div class="col-12">
                        <div class="form-group">
                          <label for="nombre">Nombre: </label>
                          <br>
                          <input type="text" class= "form-control" v-model="comision.nombre">
                          <div v-if="errors && errors.nombre" class="error">
                              {{ errors.nombre[0] }}
                          </div>
                        </div>
                      </div>
                      <div class="col-3">
                        <div class="form-group">
                          <label for="rut">Rut:</label>
                          <br>
                          <input type="text" @keyup="checkRut()" class="form-control" v-model="comision.rut">
                          <div v-if="errors && errors.rut" class="error">
                              {{ errors.rut[0] }}
                          </div>
                        </div>
                      </div>
                      <div class="col-6">
                        <div class="form-group">
                        <label for="correo">Correo Electrónico: </label>
                        <br>
                        <input type="email" class= "form-control" v-model="comision.correo">
                        <div  v-if="errors && errors.correo" class="error">
                          {{ errors.correo[0] }}
                        </div>
                      </div>
                      </div>
                      <div class="col-3">
                        <div class="form-group">
                          <label for="telefono">Teléfono: </label>
                          <br>
                          <input type="text" class= "form-control" v-model="comision.telefono">
                          <div v-if="errors && errors.telefono" class="error">
                              {{ errors.telefono[0] }}
                          </div>
                        </div>
                      </div>
                    </div>
                  </slot>
                </div>
                <div class="modal-footer">
                    <slot name="footer">
                        <button class="btn btn-continue" @click="crearComision()" :disabled="buttonDisable">
                            {{ buttonText }}
                        </button>
                    </slot>
                </div>
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
  import modalconfirmacion from '../../alerts/confirmationModal.vue'
  import modalalerta from '../../alerts/alertModal.vue'
  import {mixin} from '../../../../../mixins.js'
  
  export default {
      components: { modalconfirmacion, modalalerta },
      mixins: [mixin],
      data: () => ({
          comision: {
              idUsuario: '',
              estado: null,
              nombre: null,
              rut: null,
              correo: null,
              telefono: null,
          },
          buttonText:'Crear Comisión',
          buttonDisable: false,
          usuarios:[],
          errors:[],
      }),
      created(){
        this.getUsuarios();
      },
      methods: {
        cerrarModal(){
          const elem = this.$refs.closeBtn;
          this.$emit('recarga');
          elem.click();
        },
        checkRut() {
            if (this.comision.rut != ''){
              this.comision.rut.replace('.','');
              // Despejar Puntos
              var valor = this.comision.rut.replace('.','');

              // Despejar Guión
              valor = valor.replace('-','');
              
              // Aislar Cuerpo y Dígito Verificador
              var cuerpo = valor.slice(0,-1);
              var dv = valor.slice(-1).toUpperCase();
              
              // Formatear RUN
              this.comision.rut = cuerpo + '-'+ dv
            }
        },
        getUsuarios(){
            axios.get('../api/usuarios').then( response =>{
                this.usuarios = response.data;
            }).catch(e=> console.log(e))
        },
        async crearComision() {
          const ok = await this.$refs.confirmation.show({
              title: 'Crear Comisión',
              message: `¿Está seguro/a que desea crear la Comisión '${this.comision.nombre}'? Esta acción no puede ser desecha.`,
              okButton: 'Crear',
              cancelButton: 'Volver'
          })
          if (ok) {
              let comision = {
                idUsuario: this.comision.idUsuario,
                rut: this.comision.rut,
                estado: this.comision.estado,
                nombre: this.comision.nombre,
                correo: this.comision.correo,
                telefono: this.comision.telefono,
              };
              axios.post("../api/comisiones", comision).then((result) => {
                  this.buttonDisable = true;
                  this.buttonText = 'Guardando...';
                  this.toast.success('Comisión guardada con éxito!', {
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
              }).catch((error)=> {
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
      }
  }
  </script>
  