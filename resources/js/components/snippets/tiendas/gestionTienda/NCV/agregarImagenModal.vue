<template>
    <transition name="modalcrear">
      <div name="modal">
        <div class="modal-mask">
            <div class="modal-wrapper">
              <div class="modal-container-xs ">
                <div class="modal-header fw-bold pb-1" style="color: #444444;">
                  <slot name="header">
                      Agregar Imagen a Nota de Crédito
                  </slot>
                  <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
                </div>
                <div class="modal-body">
                  <slot name="body">
                    <div class="row">
                        <div class="col-8">
                            <div class="form-group">
                                <label for="foto">Imagen: </label>
                                <br>
                                <input type="file" accept=".jpg, .jpeg, .png, .heif, .heic" id="foto" class="form-control"  @change="getImage" v-has-error="errors.foto">
                            </div>
                        </div>
                        <div class="col-4">
                          <figure>
                            <img width="100" height="100" :src="imagen" alt="Foto Miniatura">
                          </figure>
                        </div>
                      </div>
                  </slot>
                </div>
                <div class="modal-footer">
                    <slot name="footer">
                        <button class="btn btn-continue float-end" @click="guardarImagen()" :disabled="buttonDisable">
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
  import Compressor from 'compressorjs';

  export default {
      components: { modalconfirmacion, modalalerta },
      mixins: [mixin],
      data: () => ({
        ncv1: {
          imagen: null,
        },
        buttonText:'Añadir Imagen',
        buttonDisable: false,
        fotoMiniatura: '',
        errors:[],
      }),
      props:{
        ncvID1: Object,
      },
      methods: {
        guardarImagen(){
          if ( this.ncv1.imagen != null){
            var salida = [];
            // console.log(this.ncv1);
            salida.push(this.ncv1.imagen);
            salida.push(this.ncvID1);
            axios.post('api/ncv/añadirImagen', salida, {
              headers: { 'Content-Type' : 'multipart/form-data' }
            }).then( response => {
            this.buttonDisable = true;
            console.log(response.data);
            this.buttonText = 'Guardando...';
            this.toast.success("Imagen añadida con exito!", {
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
            setTimeout(() => {location.reload();}, 1500);
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
          }else{
            this.toast.warning("Debe ingresar una imagen para continuar !", {
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
          this.ncv1.imagen = e.target.files[0];
          let compression = [];
          await this.compressImages(this.ncv1.imagen,compression);
          var file = new File([compression[0]], compression[0]['name'], {
          type: compression[0]['type'],});
          this.ncv1.imagen = file;
          this.chargeImage(this.ncv1.imagen);
        },
        chargeImage(file){
            let reader = new FileReader();
            reader.onload = (e) => { 
                this.fotoMiniatura = e.target.result;
            }
            reader.readAsDataURL(file);
        },  


        cerrarModal(){
            const elem = this.$refs.closeBtn;
            this.$emit('recarga');
            elem.click();
        },
        
      },
    computed: {
      imagen(){
          return this.fotoMiniatura;
      }
    }
  }
  </script>
  