<template>
    <transition name="modalcrear">
      <div name="modal">
        <div class="modal-mask">
            <div class="modal-wrapper">
              <div class="modal-container-xs">
                <div class="modal-header fw-bold" style="color: #444444;">
                  <slot name="header">
                      Nuevo autorizador
                  </slot>
                  <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
                </div>
                <div class="modal-body">
                  <slot name="body">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                              <label for="identificador">Identificador: </label>
                              <br>
                              <input type="text" class= "form-control" v-model="autorizador.identificador">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                              <label for="Autorizador">Autorizador: </label>
                              <br>
                              <select class="form-select" v-model="autorizador.idUsuario">
                              <option disabled value="">Seleccione un autorizador</option>
                              <option v-for="autorizador in autorizadores2" v-bind:key="autorizador.id" v-bind:value="autorizador.id">
                                {{ autorizador.name }}
                              </option>
                              </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                              <label for="levelName">Nombre Nivel: </label>
                              <br>
                              <select class="form-select" v-model="autorizador.levelName">
                              <option disabled value="">Seleccione un nivel</option>
                              <option value="facturas">facturas</option>
                              </select>
                            </div>
                        </div>
                        <div class="col-6">
                            <br>
                            <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked" v-model="autorizador.level" >
                            <label class="form-check-label" for="flexCheckChecked">
                                Alto Nivel
                            </label>
                            </div>
                        </div>
                    </div>
                  </slot>
                </div>
                <div class="modal-footer">
                    <slot name="footer">
                        <button class="btn btn-continue" @click="crearAFP()" :disabled="buttonDisable">
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
        autorizador: {
            identificador: null,
            idUsuario: '',
            levelName: '',
            level: null,
        },
        autorizadores2: [],
        buttonText:'Crear Autorizador',
        buttonDisable: false,
        errors:[],
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
            axios.get("../api/getAutorizadores").then( response =>{
                this.autorizadores2 = response.data;
            }).catch(e=> console.log(e))
        },
        async crearAFP() {
            this.errors = []
            for (const item in this.autorizador){
                if(this.autorizador[item] === "" || this.autorizador[item] === 0 || this.autorizador[item] === null){
                    if (item == 'level'){
                    }else{
                        this.errors.push(item);
                    }
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
                    title: 'Crear Autorizador',
                    message: `¿Está seguro/a que desea crear este autorizador? Esta acción no puede ser desecha.`,
                    okButton: 'Crear',
                    cancelButton: 'Volver'
                })
                if (ok) {
                    let autorizador = {
                        identificador: this.autorizador.identificador,
                        idUsuario: this.autorizador.idUsuario,
                        levelName: this.autorizador.levelName,
                        level: this.autorizador.level,
                    };                
                    axios.post("../api/autorizadores", autorizador).then((result) => {
                        this.buttonDisable = true;
                        this.buttonText = 'Guardando...';
                        this.toast.success('Autorizador guardado con éxito!', {
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
  }
  </script>
  