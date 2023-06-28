<template>
    <transition name="modalcrear">
      <div name="modal">
        <div class="modal-mask">
            <div class="modal-wrapper">
              <div class="modal-container-xs">
                <div class="modal-header fw-bold" style="color: #444444;">
                  <slot name="header">
                      Nueva Empresa-Marcas
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
                        <button class="btn btn-continue" @click="crearEmpresaMarca()" :disabled="buttonDisable">
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
        buttonText:'Crear Empresa Kam',
        buttonDisable: false,
        errors:[],
      }),
      created(){
        this.getEmpresas();
        this.getMarcas();
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
        async crearEmpresaMarca() {
            this.errors = []

            for (const item in this.empresaMarca){
                if(this.empresaMarca[item] === "" || this.empresaMarca[item] === 0 || this.empresaMarca[item] === null){
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
                    title: 'Crear Empresa Marca',
                    message: `¿Está seguro/a que desea crear esta Empresa-Marca ? Esta acción no puede ser desecha.`,
                    okButton: 'Crear',
                    cancelButton: 'Volver'
                })
                if (ok) {
                    let empresaMarca = {
                        nombre: this.empresaMarca.nombre,
                        idEmpresa: this.empresaMarca.idEmpresa,
                        idMarca: this.empresaMarca.idMarca,
                    };                
                    axios.post("../api/empresasMarcas", empresaMarca).then((result) => {
                        this.buttonDisable = true;
                        this.buttonText = 'Guardando...';
                        this.toast.success('Empresa-Marca guardada con éxito!', {
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
  