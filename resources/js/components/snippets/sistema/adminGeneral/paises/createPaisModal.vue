<template>
    <transition name="modalcrear">
      <div name="modal">
        <div class="modal-mask">
            <div class="modal-wrapper">
              <div class="modal-container-xs">
                <div class="modal-header fw-bold" style="color: #444444;">
                  <slot name="header">
                      Nuevo País
                  </slot>
                  <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
                </div>
                <div class="modal-body">
                  <slot name="body">
                      <div class="form-group">
                        <label for="name">Nombre del País: </label>
                        <br>
                        <input type="text" class= "form-control" v-model="nombrePais">
                        <div  v-if="errors && errors.pais" class="error">
                            {{ errors.pais[0] }}
                        </div>
                      </div>
                      <div class="form-group">
                        <label for="name">Nombre de la Capital: </label>
                        <br>
                        <input type="text" class= "form-control" v-model="capitalPais">
                        <div  v-if="errors && errors.capital" class="error">
                            {{ errors.capital[0] }}
                        </div>
                      </div>
                  </slot>
                </div>
                <div class="modal-footer">
                    <slot name="footer">
                        <button class="btn btn-continue" @click="crearPais()" :disabled="buttonDisable">
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
          buttonText:'Crear País',
          buttonDisable: false,
          nombrePais: "",
          capitalPais: "",
          errors:[],
      }),
      methods: {
        cerrarModal(){
            const elem = this.$refs.closeBtn;
            this.$emit('recarga');
            elem.click();
        },
        async crearPais() {
            const ok = await this.$refs.confirmation.show({
                title: 'Crear País',
                message: `¿Está seguro/a que desea crear el país '${this.nombrePais}'? Esta acción no puede ser desecha.`,
                okButton: 'Crear',
                cancelButton: 'Volver'
            })
            if (ok) {
                let pais = {
                    pais: this.nombrePais,
                    capital: this.capitalPais,
                };
                axios.post("../api/paises", pais).then((result) => {
                    this.buttonDisable = true;
                    this.buttonText = 'Guardando...';
                    this.toast.success('País guardado con éxito!', {
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
  </script>
  