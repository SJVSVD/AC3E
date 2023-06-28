<template>
    <transition name="modalcrear">
      <div name="modal">
        <div class="modal-mask">
            <div class="modal-wrapper">
              <div class="modal-container-xs">
                <div class="modal-header fw-bold" style="color: #444444;">
                  <slot name="header">
                      Nuevo Proveedor
                  </slot>
                  <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
                </div>
                <div class="modal-body">
                    <slot name="body">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="rut">Rut: </label>
                                    <input type="rut" class= "form-control" v-model="proveedorCreate.rut">
                                </div>
                                <div v-if="errors && errors.rut" class="error">
                                    {{ errors.rut[0] }}
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="codSocioNegocio">Código Socio de Negocios: </label>
                                    <input type="codSocioNegocio" class= "form-control" v-model="proveedorCreate.codSocioNegocio">
                                </div>
                                <div v-if="errors && errors.codSocioNegocio" class="error">
                                    {{ errors.codSocioNegocio[0] }}
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="razonSocial">Razón Social: </label>
                                    <input type="razonSocial" class= "form-control" v-model="proveedorCreate.razonSocial">
                                </div>
                                <div v-if="errors && errors.razonSocial" class="error">
                                    {{ errors.razonSocial[0] }}
                            </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="">Autorizador 1: </label>
                                    <br>
                                    <select class="form-select" v-model="proveedorCreate.idAuthAso1">
                                        <option disabled value="">Seleccione un usuario</option>
                                        <option v-for="autorizador in autorizadores" v-bind:key="autorizador.id" v-bind:value="autorizador.id">
                                            {{ autorizador.usuario.name }}
                                        </option>
                                    </select>
                                </div>
                                <div v-if="errors && errors.idAuthAso1" class="error">
                                    {{ errors.idAuthAso1[0] }}
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label for="">Autorizador 2: </label>
                                    <br>
                                    <select class="form-select" v-model="proveedorCreate.idAuthAso2">
                                        <option disabled value="">Seleccione un usuario</option>
                                        <option v-for="autorizador in autorizadores" v-bind:key="autorizador.id" v-bind:value="autorizador.id">
                                            {{ autorizador.usuario.name }}
                                        </option>
                                    </select>
                                </div>
                                <div v-if="errors && errors.idAuthAso2" class="error">
                                    {{ errors.idAuthAso2[0] }}
                                </div>
                            </div>
                        </div>
                    </slot>
                </div>
                <div class="modal-footer">
                    <slot name="footer">
                        <button class="btn btn-continue" @click="crearProveedor()" :disabled="buttonDisable">
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
        buttonText:'Crear Proveedor',
        buttonDisable: false,
        errors:[],
        autorizadores: null,
        proveedorCreate: {
            rut: null,
            razonSocial: null,
            codSocioNegocio: null,
            idAuthAso1: null,
            idAuthAso2: null,
        },
      }),
      created(){
        this.getAutorizadores();
      },
      methods: {
        cerrarModal(){
          const elem = this.$refs.closeBtn;
          this.$emit('recarga');
          elem.click();
        },
        getAutorizadores(){
            axios.get("../api/autorizadores").then( response =>{
                this.autorizadores = response.data;
            }).catch(e=> console.log(e))
        },
        async crearProveedor() {
            const ok = await this.$refs.confirmation.show({
                title: 'Crear Proveedor',
                message: `¿Está seguro/a que desea agregar a este proveedor a la lista? Esta acción no puede ser desecha.`,
                okButton: 'Crear',
                cancelButton: 'Volver'
            })
            if (ok) {
                let proveedor = {
                    estado: this.proveedorCreate.estado,
                    rut: this.proveedorCreate.rut,
                    razonSocial: this.proveedorCreate.razonSocial,
                    codSocioNegocio: this.proveedorCreate.codSocioNegocio,                    
                    idAuthAso1: this.proveedorCreate.idAuthAso1,
                    idAuthAso2: this.proveedorCreate.idAuthAso2,
                };
                axios.post("../api/proveedores", proveedor).then((result) => {
                    this.buttonDisable = true;
                    this.buttonText = 'Guardando...';
                    this.toast.success('Proveedor guardado con éxito!', {
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
  