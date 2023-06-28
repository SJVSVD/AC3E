<template>
    <transition name="modalcrear">
      <div name="modal">
        <div class="modal-mask">
            <div class="modal-wrapper">
              <div class="modal-container-xs">
                <div class="modal-header fw-bold" style="color: #444444;">
                  <slot name="header">
                      Editar Empresa-Marcas
                  </slot>
                  <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
                </div>
                <div class="modal-body">
                  <slot name="body">
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <label for="nombre">Nombre: </label>
                                <br>
                                <input type="text" class= "form-control" v-model="empresaMarca.nombre"> 
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="empresa">Empresa: </label>
                                <br>
                                <select class="form-select" v-model="empresaMarca.idEmpresa">
                                    <option disabled value="">Seleccione una empresa</option>
                                    <option v-for="empresa in empresas" v-bind:key="empresa.id" v-bind:value="empresa.id">
                                        {{ empresa.razonSocial }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="marca">Marca: </label>
                                <br>
                                <select class="form-select" v-model="empresaMarca.idMarca">
                                    <option disabled value="">Seleccione una empresa</option>
                                    <option v-for="marca in marcas" v-bind:key="marca.id" v-bind:value="marca.id">
                                        {{ marca.nombre }}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>
                  </slot>
                </div>
                <div class="modal-footer">
                    <slot name="footer">
                        <button class="btn btn-continue" @click="editarEmpresaMarca()" :disabled="buttonDisable">
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
        empresaMarca: {
            nombre: '',
            idEmpresa: '',
            idMarca: '',
        },
        marcas: [],
        empresas: [],
        id: null,
        buttonText:'Editar Empresa Kam',
        buttonDisable: false,
        errors:[],
      }),
      props:{
        empresaMarca1: Object,
      },
      created(){
        this.getEmpresas();
        this.getMarcas();
        this.empresaMarca.nombre = this.empresaMarca1.nombre;
        this.empresaMarca.idEmpresa = this.empresaMarca1.idEmpresa;
        this.empresaMarca.idMarca = this.empresaMarca1.idMarca;

        this.id = this.empresaMarca1.id;
      },
      methods: {
        cerrarModal(){
            const elem = this.$refs.closeBtn;
            this.$emit('recarga');
            elem.click();
        },
        getMarcas(){
            axios.get("../api/marcas").then( response =>{
                this.marcas = response.data;
            }).catch(e=> console.log(e))
        },
        getEmpresas(){
            axios.get("../api/empresas").then( response =>{
                this.empresas = response.data;
            }).catch(e=> console.log(e))
        },
        async editarEmpresaMarca() {
            const ok = await this.$refs.confirmation.show({
                title: 'Editar Empresa-Marca',
                message: `¿Está seguro/a que desea editar esta relacion Empresa-Marca? Esta acción no puede ser desecha.`,
                okButton: 'Editar',
                cancelButton: 'Volver'
            })
            if (ok) {
                let empresaMarca = {
                    nombre: this.empresaMarca.nombre,
                    idEmpresa: this.empresaMarca.idEmpresa,
                    idMarca: this.empresaMarca.idMarca,
                };       
                var id = this.id;
                axios.put(`../api/empresasMarcas/${id}`, empresaMarca).then((result) => {
                    this.buttonText = 'Guardando...';
                    this.buttonDisable = true;
                    this.toast.success("Empresa-Marca editada con éxito!", {
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
                    setTimeout(() => {this.cerrarModal();}, 1000);
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
            }
        }
      }
  }
  </script>
  