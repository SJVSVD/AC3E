<template>
    <transition name="modalcrear">
      <div name="modal">
        <div class="modal-mask">
            <div class="modal-wrapper">
              <div class="modal-container-xs">
                <div class="modal-header fw-bold" style="color: #444444;">
                  <slot name="header">
                      Nuevo Área
                  </slot>
                  <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
                </div>
                <div class="modal-body">
                  <slot name="body">
                    <div class="form-group">
                        <label for="name">Nombre del Área: </label>
                        <br>
                        <input type="text" class= "form-control" v-model="nombreArea">
                        <div  v-if="errors && errors.area" class="error">
                            {{ errors.area[0] }}
                        </div>
                    </div>
                    <div class="form-group">
                    <label for="departamentos">Departamento: </label>
                        <br>
                        <select class="form-select" v-model="idDepartamento">
                            <option disabled value="">Seleccione un departamento</option>
                            <option v-for="departamento in departamentos" v-bind:key="departamento.id" v-bind:value="departamento.id">
                            {{ departamento.departamento }}
                            </option>
                        </select>
                        <div  v-if="errors && errors.idDepartamento" class="error">
                            {{ errors.idDepartamento[0] }}
                        </div>
                    </div>
                </slot>
                </div>
                <div class="modal-footer">
                    <slot name="footer">
                        <button class="btn btn-continue" @click="crearArea()" :disabled="buttonDisable">
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
          buttonText:'Crear Área',
          buttonDisable: false,
          nombreArea: "",
          idDepartamento: "",
          departamentos: null,
          errors:[],
      }),
      created(){
        this.getDepartamentos();
      },
      methods: {
        cerrarModal(){
            const elem = this.$refs.closeBtn;
            this.$emit('recarga');
            elem.click();
        },
        getDepartamentos(){
            axios.get('../api/departamentos').then( response =>{
                this.departamentos = response.data;
            }).catch(e=> console.log(e))
        },
        async crearArea() {
            const ok = await this.$refs.confirmation.show({
                title: 'Crear Área',
                message: `¿Está seguro/a que desea crear el área '${this.nombreArea}'? Esta acción no puede ser desecha.`,
                okButton: 'Crear',
                cancelButton: 'Volver'
            })
            if (ok) {
                let area = {
                    idDepartamento: this.idDepartamento,
                    area: this.nombreArea,
                    idDepArea: this.idDepartamento+this.nombreArea,
                };
                axios.post("../api/areas", area).then((result) => {
                    this.buttonDisable = true;
                    this.buttonText = 'Guardando...';
                    this.toast.success('Área guardada con éxito!', {
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
                        if(this.errors.idDepArea){
                            this.toast.warning(this.errors.idDepArea[0], {
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
  