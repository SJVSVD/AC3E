<template>
    <transition name="modalcrear">
      <div name="modal">
        <div class="modal-mask">
            <div class="modal-wrapper">
              <div class="modal-container-xs">
                <div class="modal-header fw-bold" style="color: #444444;">
                  <slot name="header">
                      Nueva Marcas
                  </slot>
                  <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
                </div>
                <div class="modal-body">
                  <slot name="body">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="nombre">Nombre: </label>
                                <br>
                                <input type="text" class= "form-control" v-model="marca.nombre"> 
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <br>
                                <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked" v-model="marca.estado" >
                                <label class="form-check-label" for="flexCheckChecked">
                                    Estado
                                </label>
                                </div>
                            </div>
                        </div>
                    </div>
                  </slot>
                </div>
                <div class="modal-footer">
                    <slot name="footer">
                        <button class="btn btn-continue" @click="crearMarca()" :disabled="buttonDisable">
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
        marca: {
            nombre: '',
            estado: '',
        },
        buttonText:'Crear Marca',
        buttonDisable: false,
        errors:[],
      }),
      methods: {
        cerrarModal(){
            const elem = this.$refs.closeBtn;
            this.$emit('recarga');
            elem.click();
        },
        async crearMarca() {
            this.errors = []

            for (const item in this.marca){
                if(this.marca[item] === "" || this.marca[item] === 0 || this.marca[item] === null){
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
                    title: 'Crear Marca',
                    message: `¿Está seguro/a que desea crear esta Marca ? Esta acción no puede ser desecha.`,
                    okButton: 'Crear',
                    cancelButton: 'Volver'
                })
                if (ok) {
                    let marca = {
                        nombre: this.marca.nombre,
                        estado: this.marca.estado,
                    };                
                    axios.post("../api/marcas", marca).then((result) => {
                        this.buttonDisable = true;
                        this.buttonText = 'Guardando...';
                        this.toast.success('Marca guardada con éxito!', {
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
  