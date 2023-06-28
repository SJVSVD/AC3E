<template>
    <transition name="modalcrear">
      <div name="modal">
        <div class="modal-mask">
            <div class="modal-wrapper">
              <div class="modal-container-xs">
                <div class="modal-header fw-bold" style="color: #444444;">
                  <slot name="header">
                      Nuevo Supervisor
                  </slot>
                  <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
                </div>
                <div class="modal-body">
                    <slot name="body">
                        <div class="col-12">
                            <div class="form-group">
                              <label for="">Estado: </label>
                              <br>
                              <select class="form-select" v-model="supervisorCreate.estado">
                              <option disabled value="">Seleccione un estado</option>
                              <option :value="true">Activo</option>
                              <option :value="false">Inactivo</option>
                              </select>
                            </div>
                            <div v-if="errors && errors.estado" class="error">
                                {{ errors.estado[0] }}
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="">Usuario: </label>
                                <br>
                                <select class="form-select" v-model="supervisorCreate.idUsuario">
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
                              <label for="">Sucursal: </label>
                              <br>
                              <select class="form-select" v-model="supervisorCreate.idSucursal">
                              <option disabled value="">Seleccione una sucursal</option>
                              <option v-for="sucursal in sucursales" v-bind:key="sucursal.id" v-bind:value="sucursal.id">
                                {{ sucursal.nombre }}
                              </option>
                              </select>
                            </div>
                            <div v-if="errors && errors.idSucursal" class="error">
                                {{ errors.idSucursal[0] }}
                            </div>
                        </div>
                  </slot>
                </div>
                <div class="modal-footer">
                    <slot name="footer">
                        <button class="btn btn-continue" @click="crearSupervisor()" :disabled="buttonDisable">
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
  import modalconfirmacion from '../../../sistema/alerts/confirmationModal.vue'
  import modalalerta from '../../../sistema/alerts/alertModal.vue'
  import {mixin} from '../../../../../mixins.js'
  
  export default {
      components: { modalconfirmacion, modalalerta },
      mixins: [mixin],
      data: () => ({
        buttonText:'Crear Supervisor',
        buttonDisable: false,
        errors:[],
        sucursales: null,
        usuarios: null,
        supervisorCreate: {
            estado: null,
            idUsuario: null,
            idSucursal: null,
        },
      }),
      created(){
        this.getUsuarios(3);
        this.getSucursales();
      },
      methods: {
        cerrarModal(){
          const elem = this.$refs.closeBtn;
          this.$emit('recarga');
          elem.click();
        },
        getUsuarios($tipo){
            axios.post(`../api/usuarios/getUsuariosViaTipo/${$tipo}`).then( response =>{
                this.usuarios = response.data;
            }).catch(e=> console.log(e))
        },
        getSucursales(){
            axios.get('../api/sucursales').then( response =>{
                this.sucursales = response.data;
            }).catch(e=> console.log(e))
        },
        async crearSupervisor() {
            const ok = await this.$refs.confirmation.show({
                title: 'Crear Supervisor',
                message: `¿Está seguro/a que desea crear esta relación Supervisor-Sucursal? Esta acción no puede ser desecha.`,
                okButton: 'Crear',
                cancelButton: 'Volver'
            })
            if (ok) {
                let supervisor = {
                    estado: this.supervisorCreate.estado,
                    idUsuario: this.supervisorCreate.idUsuario,
                    idSucursal: this.supervisorCreate.idSucursal,
                };
                axios.post("../api/supervisores", supervisor).then((result) => {
                    this.buttonDisable = true;
                    this.buttonText = 'Guardando...';
                    this.toast.success('Supervisor guardado con éxito!', {
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
  