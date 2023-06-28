<template>
    <transition name="modalcrear">
      <div name="modal">
        <div class="modal-mask">
            <div class="modal-wrapper">
              <div class="modal-container-xs">
                <div class="modal-header fw-bold" style="color: #444444;">
                  <slot name="header">
                      Nueva Zona
                  </slot>
                  <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
                </div>
                <div class="modal-body">
                  <slot name="body">
                    <div class="form-group">
                        <label for="zona">Zona: </label>
                        <br>
                        <input type="text" class= "form-control" v-model="nombreZona">
                        <div v-if="errors && errors.nombre" class="error">
                            {{ errors.nombre[0] }}
                        </div>
                        <!-- <div v-if="errors && errors.idPaisZona" class="error">
                            {{ errors.idPaisZona[0] }}
                        </div> -->
                    </div>
                    <div class="form-group">
                        <label for="paises">País: </label>
                        <br>
                        <select class="form-select" v-model="idPais">
                        <option disabled value="">Seleccione un País</option>
                        <option v-for="pais in paises" v-bind:key="pais.id" v-bind:value="pais.id">
                            {{ pais.pais }}
                        </option>
                        </select>
                        <div v-if="errors && errors.idPais" class="error">
                            {{ errors.idPais[0] }}
                        </div>
                    </div>
                  </slot>
                </div>
                <div class="modal-footer">
                    <slot name="footer">
                        <button class="btn btn-continue" @click="crearRol()" :disabled="buttonDisable">
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
          buttonText:'Crear Zona',
          buttonDisable: false,
          paises: null,
          nombreZona: "",
          idPais: "",
          errors:[],
      }),
      created(){
        this.getPaises();
      },
      methods: {
        cerrarModal(){
          const elem = this.$refs.closeBtn;
          this.$emit('recarga');
          elem.click();
        },
        getPaises(){
            axios.get('../api/paises').then( response =>{
                this.paises = response.data;
            }).catch(e=> console.log(e))
        },
        async crearRol() {
            const ok = await this.$refs.confirmation.show({
                title: 'Crear Zona',
                message: `¿Está seguro/a que desea crear la zona '${this.nombreZona}'? Esta acción no puede ser desecha.`,
                okButton: 'Crear',
                cancelButton: 'Volver'
            })
            if (ok) {
                let zona = {
                    nombre: this.nombreZona,
                    idPais: this.idPais,
                    idPaisZona: this.idPais+this.nombreZona,
                };
                axios.post("../api/zonas", zona).then((result) => {
                    this.buttonDisable = true;
                    this.buttonText = 'Guardando...';
                    this.toast.success('Zona guardado con éxito!', {
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
                        if(this.errors.idPaisZona){
                            this.toast.warning(this.errors.idPaisZona[0], {
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
                        else{
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
                    }
                });
            }
        }
      }
  }
  </script>
  