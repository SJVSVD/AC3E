<template>
    <transition name="modalcrear">
      <div name="modal">
        <div class="modal-mask">
            <div class="modal-wrapper">
              <div class="modal-container-xs">
                <div class="modal-header fw-bold" style="color: #444444;">
                  <slot name="header">
                      Nuevo Vendedor
                  </slot>
                  <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
                </div>
                <div class="modal-body">
                  <slot name="body">
                      <div class="form-group">
                        <label for="name">Rut: </label>
                        <br>
                        <input type="text" class= "form-control" @keyup="checkRut()" v-on:input="validateInput" v-on:keypress="isNumberOrDash" v-model="vendedor.rut">
                      </div>
                      <div class="form-group">
                        <label for="name">Nombres: </label>
                        <br>
                        <input type="text" class= "form-control" v-model="vendedor.nombre">
                      </div>
                      <div class="form-group">
                        <label for="name">Apellido Paterno: </label>
                        <br>
                        <input type="text" class= "form-control" v-model="vendedor.apellido1">
                      </div>
                      <div class="form-group">
                        <label for="name">Apellido Materno: </label>
                        <br>
                        <input type="text" class= "form-control" v-model="vendedor.apellido2">
                      </div>
                      <div class="form-group">
                          <label for="">Empresa: </label>
                          <br>
                          <select class="form-select" v-model="vendedor.idEmpresa">
                          <option disabled value="">Seleccione una empresa</option>
                          <option v-for="empresa in empresas" v-bind:key="empresa.id" v-bind:value="empresa.id">
                            {{ empresa.razonSocial }}
                          </option>
                          </select>
                      </div>
                      <div class="form-group">
                        <label for="name">Enviar a TPV: </label>
                        <br>
                        <select class="form-select" v-model="vendedor.enviarTPV">
                          <option disabled value="">Seleccione una opcion</option>
                          <option :value="true">Si</option>
                          <option :value="false">No</option>
                        </select>               
                      </div>
                  </slot>
                </div>
                <div class="modal-footer">
                    <slot name="footer">
                        <button class="btn btn-continue float-end" @click="crearVendedor()" :disabled="buttonDisable">
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
          vendedor:{
            rut: "",
            nombre: "",
            apellido1: "",
            apellido2: "",
            idEmpresa: "",
            enviarTPV: "",
          },
          empresas: [],
          buttonText:'Crear Vendedor',
          buttonDisable: false,
          errors:[],
      }),
      created(){
        this.getEmpresas();
      },
      methods: {
        cerrarModal(){
          const elem = this.$refs.closeBtn;
          this.$emit('recarga');
          elem.click();
        },
        validateInput() {
          this.vendedor.rut = this.vendedor.rut.replace(/[^0-9\-]/g, '').replace(/(\-)\-+/g, '$1');
        },
        isNumberOrDash(event) {
          const charCode = event.which ? event.which : event.keyCode;
          if (charCode === 45 && this.vendedor.rut.indexOf('-') !== -1  ) {
            event.preventDefault();
          } else if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode !== 45) {
            event.preventDefault();
          }
        },
        getEmpresas(){
          axios.get('../api/empresas').then( response =>{
              this.empresas = response.data;
          }).catch(e=> console.log(e))
        },
        checkRut() {
            if (this.vendedor.rut != ''){
            this.vendedor.rut.replace('.','');
            // Despejar Puntos
            var valor = this.vendedor.rut.replace('.','');
    
            // Despejar Guión
            valor = valor.replace('-','');
            
            // Aislar Cuerpo y Dígito Verificador
            var cuerpo = valor.slice(0,-1);
            var dv = valor.slice(-1).toUpperCase();
            
            // Formatear RUN
            this.vendedor.rut = cuerpo + '-'+ dv
            }
        },
        capitalizeFirstLetter(string) {
          return string.charAt(0).toUpperCase() + string.slice(1);
        },
        async crearVendedor() {
          this.errors = [];
          for (const item in this.vendedor){
            if(this.vendedor[item] === "" || this.vendedor[item] === 0){
                this.errors.push(item);
              }
          }

          if(this.vendedor.rut != ""){
            var validacion = this.validarRut(this.vendedor.rut);
            if(validacion == false){
              this.errors.push('rut invalido');
              }
          }

          var contador = await axios.post('api/verificarVendedor', this.vendedor).then(function(response) {
            return response.data;
          }.bind(this)).catch(function(e) {
            console.log(e);
          });
          if (contador > 0){
            this.errors.push('rutExistente');
          }

          var mensaje = ""
          if (this.errors.length != 0){
            this.errors.forEach(item => {
              if(item == 'rut invalido'){
                mensaje =   mensaje + "El rut ingresado es invalido" + "\n" 
              }else if(item == 'idEmpresa'){
                mensaje =   mensaje + "El campo Empresa es requerido" + "\n" 
              }else if(item == 'enviarTPV'){
                mensaje =   mensaje + "El campo Enviar a TPV es requerido" + "\n" 
              }else if(item == 'rutExistente'){
                mensaje =   mensaje + "El vendedor ingresado ya existe" + "\n" 
              }else if(item == 'apellido1'){
                mensaje =   mensaje + "El Apellido Paterno ya existe" + "\n" 
              }else if(item == 'apellido2'){
                mensaje =   mensaje + "El Apellido Materno ya existe" + "\n" 
              }else{
                mensaje =   mensaje + "El campo " + this.capitalizeFirstLetter(item) + " es requerido" + "\n" 
              }
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
          if (this.errors.length === 0 ){
            const ok = await this.$refs.confirmation.show({
              title: 'Crear Vendedor',
              message: `¿Está seguro/a que desea crear un vendedor? Esta acción no puede ser desecha.`,
              okButton: 'Crear',
              cancelButton: 'Volver'
            })
              if (ok) {
                  let vendedor = {
                    rut: this.vendedor.rut,
                    nombre: this.vendedor.nombre,
                    apellido1: this.vendedor.apellido1,
                    apellido2: this.vendedor.apellido2,
                    idEmpresa: this.vendedor.idEmpresa,
                    enviarTPV: this.vendedor.enviarTPV,
                  };
                  axios.post("api/vendedor", vendedor).then((result) => {
                      this.buttonDisable = true;
                      this.buttonText = 'Guardando...';
                      this.toast.success("Vendedor Creado con éxito!", {
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
  }
  </script>
  