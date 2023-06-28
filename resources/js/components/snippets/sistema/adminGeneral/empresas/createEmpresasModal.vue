<template>
    <transition name="modalcrear">
      <div name="modal">
        <div class="modal-mask">
            <div class="modal-wrapper">
              <div class="modal-container-xs">
                <div class="modal-header fw-bold" style="color: #444444;">
                  <slot name="header">
                      Nueva empresa
                  </slot>
                  <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
                </div>
                <div class="modal-body">
                  <slot name="body">
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="rut">Rut: </label>
                                <br>
                                <input type="text" class= "form-control" @keyup="checkRut()" v-model="empresa.rut">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="razonSocial">Razón Social: </label>
                                <br>
                                <input type="text" class= "form-control" v-model="empresa.razonSocial">
                            </div>
                        </div>                        
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="giro">Giro: </label>
                                <br>
                                <input type="text" class= "form-control" v-model="empresa.giro">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="direccion">Dirección: </label>
                                <br>
                                <input type="text" class= "form-control" v-model="empresa.direccion">
                            </div>
                        </div>                        
                    </div>
                    <div class="row">
                      <div class="col-4">
                        <div class="form-group">
                          <label for="pais">País: </label>
                          <br>
                          <select class="form-select" @change="getRegionesSelect($event.target.value)" v-model="empresa.idPais">
                            <option disabled value="">Seleccione un País</option>
                            <option v-for="pais in paises" v-bind:key="pais.id" v-bind:value="pais.id">
                              {{ pais.pais }}
                            </option>
                          </select>
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group">
                          <label for="region">Región: </label>
                          <br>
                          <select class="form-select" @change="getComunasSelect($event.target.value)" v-model="empresa.idRegion">
                            <option disabled value="">Seleccione una Región</option>
                            <option v-for="region in regionesSelect" v-bind:key="region.id" v-bind:value="region.id">
                              {{ region.region }}
                            </option>
                          </select>
                          <br>
                        </div>
                      </div>
                      <div class="col-4">
                        <div class="form-group">
                          <label for="comuna">Comuna: </label>
                          <br>
                          <select class="form-select" v-model="empresa.idComuna">
                            <option disabled value="">Seleccione una comuna</option>
                            <option v-for="comuna in comunasSelect" v-bind:key="comuna.id" v-bind:value="comuna.id">
                              {{ comuna.comuna }}
                            </option>
                          </select>
                          <br>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="telefono">Teléfono: </label>
                                <br>
                                <input type="text" class= "form-control" v-model="empresa.telefono">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="fax">Fax: </label>
                                <br>
                                <input type="text" class= "form-control" v-model="empresa.fax">
                            </div>
                        </div>                       
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <div class="form-group">
                                <label for="codigoPostal">Código Postal: </label>
                                <br>
                                <input type="text" class= "form-control" v-model="empresa.codigoPostal">
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="form-group">
                                <label for="tipo">Tipo: </label>
                                <br>
                                <input type="checkbox"  v-model="empresa.tipo">
                            </div>    
                        </div> 
                    </div>
                  </slot>
                </div>
                <div class="modal-footer">
                    <slot name="footer">
                        <button class="btn btn-continue" @click="crearEmpresa()" :disabled="buttonDisable">
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
        empresa: {
            rut: null,
            razonSocial: null,
            giro: null,
            direccion: null,
            idPais: null,
            idRegion: null,
            idComuna: null,
            telefono: null,
            fax: null,
            codigoPostal: null,
            tipo: null,
        },
        paises: [],
        regiones: [],
        comunas: [],
        regionesSelect: [],
        comunasSelect: [],
        buttonText:'Crear Empresa',
        buttonDisable: false,
        errors:[],
      }),
      created(){
        this.getPaises();
        this.getRegiones();
        this.getComunas();
      },
      methods: {
        checkRut() {
            if (this.empresa.rut != ''){
            this.empresa.rut.replace('.','');
            // Despejar Puntos
            var valor = this.empresa.rut.replace('.','');
    
            // Despejar Guión
            valor = valor.replace('-','');
            
            // Aislar Cuerpo y Dígito Verificador
            var cuerpo = valor.slice(0,-1);
            var dv = valor.slice(-1).toUpperCase();
            
            // Formatear RUN
            this.empresa.rut = cuerpo + '-'+ dv
            }
        },
        cerrarModal(){
            const elem = this.$refs.closeBtn;
            this.$emit('recarga');
            elem.click();
        },
        getPaises(){
            axios.get('../api/paises').then( response =>{
                this.paises = response.data;
            }).catch(e=> console.log(e))
        },
        getRegiones(){
            axios.get('../api/regiones').then( response =>{
                this.regiones = response.data;
            }).catch(e=> console.log(e))
        },
        getComunas(){
            axios.get('../api/comunas').then( response =>{
                this.comunas = response.data;
            }).catch(e=> console.log(e))
        },
        getRegionesSelect(id){
            this.regionesSelect = []
            this.regiones.forEach(region => {
                if(region.idPais == id){
                    this.regionesSelect.push(region);
                }
            });
        },
        getComunasSelect(id){
            this.comunasSelect = []
            this.comunas.forEach(comuna => {
                if(comuna.idRegion == id){
                    this.comunasSelect.push(comuna);
                }
            });
        },
        async crearEmpresa() {
            this.errors = []

            for (const item in this.empresa){
                if(this.empresa[item] === "" || this.empresa[item] === 0 || this.empresa[item] === null){
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
                    title: 'Crear Empresa',
                    message: `¿Está seguro/a que desea crear esta Empresa ? Esta acción no puede ser desecha.`,
                    okButton: 'Crear',
                    cancelButton: 'Volver'
                })
                if (ok) {
                    let empresa = {
                        rut: this.empresa.rut,
                        razonSocial: this.empresa.razonSocial,
                        giro: this.empresa.giro,
                        direccion: this.empresa.direccion,
                        idPais: this.empresa.idPais,
                        idRegion: this.empresa.idRegion,
                        idComuna: this.empresa.idComuna,
                        telefono: this.empresa.telefono,
                        fax: this.empresa.fax,
                        codigoPostal: this.empresa.codigoPostal,
                        tipo: this.empresa.tipo,
                    };                
                    axios.post("../api/empresas", empresa).then((result) => {
                        this.buttonDisable = true;
                        this.buttonText = 'Guardando...';
                        this.toast.success('Empresa guardada con éxito!', {
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
  