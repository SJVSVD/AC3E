<template>
    <transition name="modalcrear">
      <div name="modal">
        <div class="modal-mask">
            <div class="modal-wrapper">
              <div class="modal-container-xs">
                <div class="modal-header fw-bold" style="color: #444444;">
                  <slot name="header">
                      New Permission
                  </slot>
                  <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
                </div>
                <div class="modal-body">
                  <slot name="body">
                      <div class="form-group">
                        <label for="name">Name of Permission: </label>
                        <br>
                        <input type="text" class= "form-control" v-model="nombrePermiso">
                      </div>
                      <div v-if="errors && errors.name" class="error">
                        {{ errors.name[0] }}
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
  import modalconfirmacion from '../../sistema/alerts/confirmationModal.vue'
  import modalalerta from '../../sistema/alerts/alertModal.vue'
  import {mixin} from '../../../../mixins.js'
  
  export default {
      components: { modalconfirmacion, modalalerta },
      mixins: [mixin],
      data: () => ({
          buttonText:'Save',
          buttonDisable: false,
          nombrePermiso: "",
          errors:[],
      }),
      created(){
          this.getPermisos();
      },
      methods: {
          // Cierra el modal y emite un evento de recarga.
        cerrarModal(){
          const elem = this.$refs.closeBtn;
          this.$emit('recarga');
          elem.click();
        },
        getPermisos(){
              axios.get('api/permisos').then( response =>{
              this.permisos = response.data;
              }).catch(e=> console.log(e))
          },
        async crearRol() {
            const ok = await this.$refs.confirmation.show({
                title: 'New Permission',
                message: `Are you sure you want to create the permission '${this.nombrePermiso}'? This action cannot be undone.`,
                okButton: 'Save',
                cancelButton: 'Cancel'
            })
            if (ok) {
                let permisos = {
                    name: this.nombrePermiso,
                };
                axios.post("api/permisos", permisos).then((result) => {
                    this.buttonDisable = true;
                    this.buttonText = 'Saving...';
                    this.toast.success("Permission saved successfully!", {
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
                    setTimeout(() => {this.cerrarModal();}, 1500);
                }).catch((error)=> {
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
      }
  }
  </script>
  