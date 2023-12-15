<template>
    <transition name="modalcrear">
      <div name="modal">
        <div class="modal-mask">
            <div class="modal-wrapper">
              <div class="modal-container-xs">
                <div class="modal-header pb-1 fw-bold" style="color: #444444;">
                  <slot name="header">
                      Edit Progress report year
                  </slot>
                  <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
                </div>
                <div class="modal-body">
                  <slot name="body">
                    <form >
                      <div class="mb-3 d-flex align-items-center">
                        <label class="col-auto m-0 fs-8"> Actual Progress report year:</label>
                        &nbsp;&nbsp;
                        <label v-if="numeroConcatenado != ''" class="fw-normal pt-2" style="font-size: 14px;">{{ numeroConcatenado }}</label>
                        <label v-else class="fw-normal pt-2" style="font-size: 14px;">Empty</label>
                        <button type="button" class="btn btn-closed ms-auto" @click="eliminarNumero"><i class="fa-solid fa-minus"></i></button>
                      </div>
                      <div class="mb-3 d-flex align-items-center">
                        <label class="col-auto m-0 fs-8 pb-2"> New Progress report year: </label>
                        &nbsp;&nbsp;
                        <input type="number" class="form-control" id="nuevoNumero" v-model="nuevoNumero">
                        &nbsp;&nbsp;
                        <button type="button" class="btn btn-continue" @click="agregarNumero"><i class="fa-solid fa-plus"></i></button>
                      </div>
                    </form>
                  </slot>
                </div>
                <div class="modal-footer">
                    <slot name="footer">
                        <button class="btn btn-continue float-end" @click="editProgress()" :disabled="buttonDisable">
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
  import modalconfirmacion from '../sistema/alerts/confirmationModal.vue'
  import modalalerta from '../sistema/alerts/alertModal.vue'
  import {mixin} from '../../../mixins.js'
  
  export default {
      components: { modalconfirmacion, modalalerta },
      mixins: [mixin],
      data: () => ({
          buttonText:'Edit',
          numeroConcatenado: '',
          nuevoNumero: '',
          buttonDisable: false,
          other:{
            name: '',
            institution: '',
          },
          errors:[],
      }),
      props:{
        progressYear: Object,
      },
      created(){
        this.numeroConcatenado = this.progressYear.replace(/\./g, "");
      },
      methods: {  
        eliminarNumero() {
          const numerosArray = this.numeroConcatenado.split(',');
          if (numerosArray.length > 0) {
            numerosArray.pop();
            this.numeroConcatenado = numerosArray.join(',');
          }
        },
        agregarNumero() {
          const nuevoNumero = parseInt(this.nuevoNumero);
          if (!isNaN(nuevoNumero)) {
            if (this.numeroConcatenado !== '') {
              this.numeroConcatenado += ','; 
            }
            this.numeroConcatenado += nuevoNumero;
            this.nuevoNumero = '';
          }
        },
        capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        },
        editProgress() {
            this.errors = [];

            if(this.numeroConcatenado == ''){
              this.errors.push('empty')
            }

            if (this.errors.length != 0){
              this.toast.warning( 'The progress report year cannot be empty.', {
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
                const elem = this.$refs.closeBtn;
                if(this.numeroConcatenado.length > 1){
                  this.numeroConcatenado += '.';
                }
                this.$emit("submit", this.numeroConcatenado);
                elem.click();
            }
        }
      }
  }
  </script>
  