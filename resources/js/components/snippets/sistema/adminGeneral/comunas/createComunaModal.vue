<template>
    <transition name="modalcrear">
      <div name="modal">
        <div class="modal-mask">
            <div class="modal-wrapper">
              <div class="modal-container-xs">
                <div class="modal-header fw-bold" style="color: #444444;">
                  <slot name="header">
                      Nueva Comuna
                  </slot>
                  <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn" >X</a>
                </div>
                <div class="modal-body">
                  <slot name="body">
                      <div class="form-group">
                        <label for="name">Nombre de la Comuna: </label>
                        <br>
                        <input type="text" class= "form-control" v-model="nombreComuna">
                        <div  v-if="errors && errors.comuna" class="error">
                          {{ errors.comuna[0] }}
                        </div>
                      </div>
                      <div class="form-group">
                            <label for="regiones">Región: </label>
                            <br>
                            <select class="form-select" v-model="idRegion">
                              <option disabled value="">Seleccione una Región</option>
                              <option v-for="region in regiones" v-bind:key="region.id" v-bind:value="region.id">
                                {{ region.region }}
                              </option>
                            </select>
                            <div  v-if="errors && errors.idRegion" class="error">
                              {{ errors.idRegion[0] }}
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
          buttonText:'Crear Comuna',
          buttonDisable: false,
          nombreComuna: "",
          regiones:[],
          idRegion: "",
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
            axios.get('../api/regiones').then( response =>{
                this.regiones = response.data;
            }).catch(e=> console.log(e))
        },
        async crearRol() {
          const ok = await this.$refs.confirmation.show({
              title: 'Crear Comuna',
              message: `¿Está seguro/a que desea crear la comuna '${this.nombreComuna}'? Esta acción no puede ser desecha.`,
              okButton: 'Crear',
              cancelButton: 'Volver'
          })
          if (ok) {
              let comuna = {
                comuna: this.nombreComuna,
                idRegion: this.idRegion,
                idRegionComuna: this.idRegion+this.nombreComuna,
              };
              axios.post("../api/comunas", comuna).then((result) => {
                  this.buttonDisable = true;
                  this.buttonText = 'Guardando...';
                  this.toast.success('Comuna guardada con éxito!', {
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
                        if(this.errors.idRegionComuna){
                            this.toast.warning(this.errors.idRegionComuna[0], {
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
  