<template>
    <transition name="modalcrear">
      <div name="modal">
        <div class="modal-mask">
            <div class="modal-wrapper">
              <div class="modal-container-xs">
                <div class="modal-header fw-bold" style="color: #444444;">
                  <slot name="header">
                      Nueva empresa kam
                  </slot>
                  <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
                </div>
                <div class="modal-body">
                  <slot name="body">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                              <label for="usuario">Usuario: </label>
                              <br>
                              <select class="form-select" v-model="kam.idUsuario">
                                <option disabled value="">Seleccione un usuario</option>
                                <option v-for="usuario in usuarios" v-bind:key="usuario.id" v-bind:value="usuario.id">
                                    {{ usuario.name }}
                                </option>
                              </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="empresa">Empresa: </label>
                                <br>
                                <select class="form-select" v-model="kam.idEmpresa">
                                    <option disabled value="">Seleccione una empresa</option>
                                    <option v-for="empresa in empresas" v-bind:key="empresa.id" v-bind:value="empresa.id">
                                        {{ empresa.razonSocial }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                  </slot>
                </div>
                <div class="modal-footer">
                    <slot name="footer">
                        <button class="btn btn-continue" @click="crearEmpresaKam()" :disabled="buttonDisable">
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
        kam: {
            idUsuario: '',
            idEmpresa: '',
        },
        usuarios: [],
        empresas: [],
        buttonText:'Crear Empresa Kam',
        buttonDisable: false,
        errors:[],
      }),
      created(){
        this.getUsuarios();
        this.getEmpresas();
      },
      methods: {
        cerrarModal(){
            const elem = this.$refs.closeBtn;
            this.$emit('recarga');
            elem.click();
        },
        getUsuarios(){
            axios.get("../api/usuarios").then( response =>{
                this.usuarios = response.data;
            }).catch(e=> console.log(e))
        },
        getEmpresas(){
            axios.get("../api/empresas").then( response =>{
                this.empresas = response.data;
            }).catch(e=> console.log(e))
        },
        async crearEmpresaKam() {
            this.errors = []

            for (const item in this.kam){
                if(this.kam[item] === "" || this.kam[item] === 0 || this.kam[item] === null){
                    this.errors.push(item);
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
                    title: 'Crear Empresa Kam',
                    message: `¿Está seguro/a que desea crear esta Empresa Kam ? Esta acción no puede ser desecha.`,
                    okButton: 'Crear',
                    cancelButton: 'Volver'
                })
                if (ok) {
                    let kam = {
                        idUsuario: this.kam.idUsuario,
                        idEmpresa: this.kam.idEmpresa,
                    };                
                    axios.post("../api/empresasKam", kam).then((result) => {
                        this.buttonDisable = true;
                        this.buttonText = 'Guardando...';
                        this.toast.success('Empresa kam guardada con éxito!', {
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
                        setTimeout(() => {this.cerrarModal()}, 1500);
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
  }
  </script>
  