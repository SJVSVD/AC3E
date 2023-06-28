
<template>
  <transition name="modalcrear">
    <div name="modal">
      <div class="modal-mask">
        <div class="modal-wrapper">
          <div class="modal-container">
            <div class="modal-header fw-bold" style="color: #444444;">
              <slot name="header">
                Nueva Solicitud Rendición
              </slot>
              <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
            </div>
            <div class="modal-body">
              <slot name="body">
                <table id="tableDesign" class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-xs font-weight-bolder">Folio</th>
                      <th class="text-uppercase text-xs font-weight-bolder">Fecha Solicitud</th>
                      <th class="text-uppercase text-xs font-weight-bolder">Usuario Solicitud</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td><p class="text-sm mb-0"> {{ solicitud.folio }} </p></td>
                      <td><p class="text-sm mb-0"> {{ solicitud.fecha }} </p></td>
                      <td><p class="text-sm mb-0"> {{ nombreUsuario }} </p></td>
                    </tr>
                  </tbody>
                </table>
                <br>
                <form> 
                  <div class="row">
                    <div class="col-3">
                      <div class="form-group">
                        <label for="colaborador">Colaborador: </label>
                        <br>
                        <select v-bind:disabled="selectDisabled" class="form-select" v-model="solicitud.colaborador">
                          <option disabled value="">Seleccione un colaborador</option>
                          <option v-for="usuario in usuarios" v-bind:key="usuario.id" v-bind:value="usuario.name">
                            {{ usuario.name }}
                          </option>
                        </select>
                      </div>
                    </div>
                    <div class="col-3">
                      <div class="form-group">
                        <label for="detalleSoli">Detalle Solicitud: </label>
                        <br>
                        <input type="text" class= "form-control" v-model="solicitud.detallesoli">
                      </div>
                    </div>
                    <div class="col-3">
                      <div class="form-group">
                        <label for="monto">Monto: </label>
                        <br>
                        <input type="number" class= "form-control" v-model="solicitud.monto">
                      </div>
                    </div>
                    <div class="col-3">
                      <div class="form-group">
                        <label for="monedas">Monedas: </label>
                        <br>
                        <select class="form-select" v-model="solicitud.moneda">
                          <option disabled value="">Seleccione una divisa</option>
                          <option v-for="moneda in monedas" v-bind:key="moneda.id" v-bind:value="moneda.id">
                            {{ moneda.abreviacion }}
                          </option>
                        </select>
                      </div>
                    </div>
                  </div>
                </form>
                <div v-if="administrador == true" class="form-check">
                  <label class="form-check-label"><input type="checkbox" class="form-check-input" v-model="rendicionEstado">
                    Agregar Rendición 
                  </label>
                </div>
                <hr size="4" class="separador">
                <div v-if="rendicionEstado == true">
                  <h5>Rendición</h5>
                    <table id="tableDesign" class="table align-items-center mb-0">
                      <thead>
                        <tr>
                          <th class="text-uppercase text-xs font-weight-bolder">Folio</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Fecha Rendición</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Usuario Rendición</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Monto Rendición</th>
                        </tr>
                      </thead>
                      <tbody> 
                        <tr>
                          <td><p class="text-sm mb-0"> {{ solicitud.folio }} </p></td>
                          <td><p class="text-sm mb-0"> {{ solicitud.fecha }} </p></td>
                          <td><p class="text-sm mb-0"> {{ nombreUsuario }} </p></td>
                          <td><p class="text-sm mb-0"> {{ formatMoneyCLP(rendicion.monto) }} </p></td>
                        </tr>
                      </tbody>  
                    </table>
                    <br>
                    <label  for="txtArea">Detalle Rendición:</label>&nbsp;&nbsp;
                    <textarea style="resize: none;  vertical-align: middle;" 
                    class="form-control" id="txtArea" rows="1" cols="50" v-model="rendicion.detalleRendi"></textarea>
                    <hr size="4" class="separador">
                    <form enctype="multipart/form-data">
                      <h5>Detalles Rendición</h5>
                      <div class="row">
                        <div class="col-3">
                          <div class="form-group">
                            <label for="idTipoDocumento">Tipo Documento: </label>
                            <br>
                            <select class="form-select" v-model="detalleRendicion.idTipoDocumento">
                              <option disabled value="">Seleccione un tipo</option>
                              <option v-for="idTipoDocumento in tiposDoc" v-bind:key="idTipoDocumento.id" v-bind:value="idTipoDocumento.id">
                                {{ idTipoDocumento.nombre }}
                              </option>
                            </select>
                          </div>
                        </div>
                        <div class="col-2">
                          <div class="form-group">
                            <label for="fecha">Fecha: </label>
                            <br>
                            <input type="date" class= "form-control" @change="verificarFecha()" v-model="detalleRendicion.fecha">
                          </div>
                        </div>
                        <div class="col-2">
                          <div class="form-group">
                            <label for="nroDocumento">N° Documento: </label>
                            <br>
                            <input type="number" class= "form-control"  v-model="detalleRendicion.nroDocumento">
                          </div>
                        </div>
                        <div class="col-2">
                          <div class="form-group">
                            <label for="rut">Rut Documento: </label>
                            <br>
                            <input type="text" class= "form-control" @keyup="checkRut()" v-on:input="validateInput" v-on:keypress="isNumberOrDash" v-model="detalleRendicion.rut">
                          </div>
                        </div>
                        <div class="col-3">
                          <div class="form-group">
                            <label for="observaciones">Detalle: </label>
                            <br>
                            <input type="text" class= "form-control"  v-model="detalleRendicion.observaciones">
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-3">
                          <div class="form-group">
                            <label for="tipoGasto">Tipo Gasto: </label>
                            <br>
                            <select class="form-select" @change="getGastosSelect($event.target.value)" v-model="detalleRendicion.tipoGasto">
                              <option disabled value="">Seleccione un tipo</option>
                              <option v-for="tipoGasto in tiposGastos" v-bind:key="tipoGasto.id" v-bind:value="tipoGasto.id">
                                {{ tipoGasto.nombre }}
                              </option>
                            </select>
                          </div>
                        </div>
                        <div class="col-3">
                          <div class="form-group">
                            <label for="gasto">Gasto: </label>
                            <br>
                            <select class="form-select" v-model="detalleRendicion.gasto">
                              <option disabled value="">Seleccione un gasto</option>
                              <option v-for="gastoS in gastosSelect" v-bind:key="gastoS.id" v-bind:value="gastoS.id">
                                {{ gastoS.nombre }}
                              </option>
                            </select>
                          </div>
                        </div>
                        <div class="col-3">
                          <div class="form-group">
                            <label for="monto">Monto: </label>
                            <br>
                            <input type="number" class= "form-control"  v-model="detalleRendicion.monto">
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-4">
                          <div class="form-group">
                            <label for="foto">Foto: </label>
                            <br>
                            <input type="file" accept=".jpg, .jpeg, .png, .heif, .heic" id="foto" class= "form-control"  @change="getImage" v-has-error="errors.foto">
                          </div>
                        </div>
                        <div v-if="fotoMiniatura != '' " class="col-4">
                          <figure>
                            <img width="100" height="100" :src="imagen" alt="Foto Miniatura">
                          </figure>
                        </div>
                      </div>
                      <a v-if="modoeditar == false" @click=agregarDetalle() class="btn btn-dark-grey">Agregar detalle</a>
                      <a v-if="modoeditar == true" @click=guardarEdicion(idEditar) class="btn btn-dark-grey">Editar detalle</a>
                      &nbsp; 
                      <a v-if="modoeditar == true" @click="cancelarEditar()" class="btn btn-grey">Cancelar</a>
                    </form>
                    <hr size="4" class="separador">
                      <div class="row">
                        <table id="tableDesign" class="table align-items-center mb-0">
                          <thead>
                            <tr>
                              <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                              <th class="text-uppercase text-xs font-weight-bolder">Tipo Doc.</th>
                              <th class="text-uppercase text-xs font-weight-bolder">Fecha</th>
                              <th class="text-uppercase text-xs font-weight-bolder">N° Documento</th>
                              <th class="text-uppercase text-xs font-weight-bolder">Rut Documento</th>
                              <th class="text-uppercase text-xs font-weight-bolder">TipoGasto</th>
                              <th class="text-uppercase text-xs font-weight-bolder">Gasto</th>
                              <th class="text-uppercase text-xs font-weight-bolder">Monto</th>
                              <th class="text-uppercase text-xs font-weight-bolder">Acciones</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-if="cantidadDetalles == 0">
                              <td colspan="10"> No hay información disponible. </td>
                            </tr>
                            <tr v-else v-for="detalle in detalles" :key="detalle">
                              <td><p class="text-sm mb-0"> {{ detalle.idDetalle + 1}}  </p></td>
                              <td>
                                <div v-for="tipoDoc in tiposDoc" :key="tipoDoc.id">
                                  <p v-if="detalle.idTipoDocumento == tipoDoc.id" class="text-sm mb-0"> {{ tipoDoc.nombre }} </p>
                                </div>
                              </td>
                              <td><p class="text-sm mb-0"> {{ detalle.fecha }} </p></td>
                              <td v-if="detalle.nroDocumento != ''"><p class="text-sm mb-0"> {{ detalle.nroDocumento }} </p></td>
                              <td v-else>---</td>
                              <td v-if="detalle.rut != ''"><p class="text-sm mb-0"> {{ detalle.rut }} </p></td>
                              <td v-else>---</td>
                              <td>
                                <div v-for="tipoGasto in tiposGastos" :key="tipoGasto.id">
                                  <p v-if="detalle.idTipoGasto == tipoGasto.id" class="text-sm mb-0"> {{ tipoGasto.nombre }} </p>
                                </div>
                              </td>
                              <td>
                                <div v-for="gasto in gastos" :key="gasto.id">
                                  <p v-if="detalle.idGasto == gasto.id" class="text-sm mb-0"> {{ gasto.nombre }} </p>
                                </div>
                              </td>
                              <td><p class="text-sm mb-0"> {{ formatMoneyCLP(detalle.monto) }} </p></td>
                              <td>
                                <p class="text-sm mb-0">
                                  <a class="btn btn-alert btn-xs" title="Editar" @click="editarDetalle(detalle.idDetalle)"><i class="fa fa-fw fa-edit"></i></a>
                                  &nbsp;
                                  <a class="btn btn-closed btn-xs" title="Eliminar" @click="eliminarDetalle(detalle)"><i class="fa fa-fw fa-trash"></i></a>
                                </p>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </slot>
                </div>
                <div class="modal-footer">
                  <slot name="footer">
                    <button v-if="rendicionEstado==true" class="btn btn-dark-grey float-end" @click="crearRendicion()" :disabled="buttonDisable1">
                      {{ buttonText }}
                    </button>
                    <button v-if="rendicionEstado==false" class="btn btn-dark-grey float-end" @click="crearSolicitud()" :disabled="buttonDisable2">Crear Solicitud</button>
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
  import axios from 'axios';
  import modalconfirmacion from '../../sistema/alerts/confirmationModal.vue';
  import modalalerta from '../../sistema/alerts/alertModal.vue';
  import {mixin} from '../../../../mixins.js';
  import Compressor from 'compressorjs';

  let user = document.head.querySelector('meta[name="user"]');
  let roles= document.head.querySelector('meta[name="roles"]');

  export default {
    components: { modalconfirmacion, modalalerta },
    mixins: [mixin],
    data: () => ({
    solicitud: {
      folio: "",
      fecha: "",
      usuario: JSON.parse(user.content).id,
      monto: "",
      colaborador: "",
      detallesoli: "",
      moneda: "",
    },
    rendicion: {
      monto: 0,
      detalleRendi: "",
    },
    detalleRendicion:{
      idTipoDocumento: "",
      fecha: "",
      nroDocumento: "",
      rut: "",
      tipoGasto: "",
      gasto: "",
      monto: "",
      foto: null,
      observaciones: "",
    },  
    fotoMiniatura: "",
    buttonText:'Crear Rendicion',
    buttonDisable1: false,
    buttonDisable2: false,
    tiposGastos:[],
    monedas:[],
    gastos:[],
    gastosSelect:[],
    tiposDoc:[],
    detalles:[],
    errors:[],
    errorsRendi:[],
    errorsSoli:[],
    cantidadDetalles:0,
    nombreUsuario: "",
    modoeditar: false,
    administrador: false,
    selectDisabled: false,
    usuarios: [],
    idEditar:"",
    rendicionEstado:false,
    }),
  created(){
    this.getDatos(this.solicitud.usuario);
    this.getUsuarios();
    this.comprobarRol(JSON.parse(roles.content),JSON.parse(user.content).name);
    this.getTipoGasto();
    this.getGastos();
    this.getTipoDoc();
    this.getMonedas();
    
  },  
  methods: {
    validateInput() {
      this.detalleRendicion.rut = this.detalleRendicion.rut.replace(/[^0-9\-]/g, '').replace(/(\-)\-+/g, '$1');
    },
    isNumberOrDash(event) {
      const charCode = event.which ? event.which : event.keyCode;
      if (charCode === 45 && this.detalleRendicion.rut.indexOf('-') !== -1  ) {
        event.preventDefault();
      } else if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode !== 45) {
        event.preventDefault();
      }
    },
    verificarFecha() {
      const fechaActual = new Date().toISOString().slice(0, 10);
      if (this.detalleRendicion.fecha > fechaActual) {
        this.detalleRendicion.fecha = "";
        this.toast.warning( "La fecha ingresada es mayor a la fecha actual.", {
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
    },
    getUsuarios(){
      axios.get('api/usuarios').then( response =>{
          this.usuarios = response.data;
      }).catch(e=> console.log(e))
    },
    comprobarRol(roles,nombre){
      roles.forEach(rol => {
        if(rol == 'Administrador'){
          this.administrador = true;
        }
      });
      if(this.administrador == false){
        this.selectDisabled = true;
        this.solicitud.colaborador = nombre;
      }
    },
    capitalizeFirstLetter(string) {
      return string.charAt(0).toUpperCase() + string.slice(1);
    },
    cerrarModal(){
      const elem = this.$refs.closeBtn;
      this.$emit('recarga');
      elem.click();
    },
    checkRut() {
        if (this.detalleRendicion.rut != ''){
          this.detalleRendicion.rut.replace('.','');
          // Despejar Puntos
          var valor = this.detalleRendicion.rut.replace('.','');

          // Despejar Guión
          valor = valor.replace('-','');
          
          // Aislar Cuerpo y Dígito Verificador
          var cuerpo = valor.slice(0,-1);
          var dv = valor.slice(-1).toUpperCase();
          
          // Formatear RUN
          this.detalleRendicion.rut = cuerpo + '-'+ dv
        }
    },
    // Funcion para eliminar un detalle de la rendicion
    async eliminarDetalle(detalle){
      const ok = await this.$refs.confirmation.show({
        title: 'Eliminar Detalle ',
        message: `¿Está seguro/a que desea crear este detalle ? Esta acción no puede ser desecha.`,
        okButton: 'Eliminar',
        cancelButton: 'Volver'
      })
      if (ok) {
        this.detalles = this.detalles.filter((item) => item !== detalle)
        this.toast.success("Detalle Eliminado correctamente!", {
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
        this.rendicion.cantidadDetalles = this.rendicion.cantidadDetalles - 1
        this.rendicion.monto = 0
        this.detalles.forEach(detalleAux => {
          this.rendicion.monto = Number(this.rendicion.monto) + Number(detalleAux.monto)
        });
      }
    },
    // Funcion para cancelar el modo editar
    cancelarEditar(){
      this.modoeditar= false
      this.detalleRendicion.idTipoDocumento = "";
      this.detalleRendicion.fecha = "";
      this.detalleRendicion.rut = "";
      this.detalleRendicion.nroDocumento = "";
      this.detalleRendicion.tipoGasto = "";
      this.detalleRendicion.gasto = "";
      this.detalleRendicion.monto = "";
      this.detalleRendicion.foto = null;
      this.detalleRendicion.observaciones = "";
      this.fotoMiniatura = "";
      this.clearInput('foto');

      this.idEditar = "";
    },
    // Funcion para editar el detalle de una rendicion
    editarDetalle(id){
      this.idEditar = "";
      this.idEditar = id;
      this.modoeditar= true
      var detalle = this.detalles[id]
      this.detalleRendicion.idTipoDocumento = detalle.idTipoDocumento;
      this.detalleRendicion.fecha = detalle.fecha;
      this.detalleRendicion.rut = detalle.rut;
      this.detalleRendicion.nroDocumento = detalle.nroDocumento;
      this.detalleRendicion.tipoGasto = detalle.idTipoGasto;
      this.getGastosSelect(detalle.idTipoGasto);
      this.detalleRendicion.gasto = detalle.idGasto;
      this.detalleRendicion.monto = detalle.monto;
      this.detalleRendicion.foto = detalle.foto;

      var input = document.querySelector("#foto");

      const dataTransfer = new DataTransfer();
      dataTransfer.items.add(detalle.foto);

      input.files = dataTransfer.files;

      this.detalleRendicion.observaciones = detalle.observaciones;
      this.chargeImage(detalle.foto);
    },
    // Funcion para guardar la edicion de un detalle
    async guardarEdicion(id){

      this.errors = []
      var foto = false

      for (const item in this.detalleRendicion){
        if(this.detalleRendicion[item] === "" || this.detalleRendicion[item] === 0){
          if(item == 'rut' || item == 'nroDocumento'){
            if((this.detalleRendicion.idTipoDocumento == 1 && item == 'rut') || (this.detalleRendicion.idTipoDocumento == 1 && item == 'nroDocumento' )|| (this.detalleRendicion.idTipoDocumento == 2 && item == 'rut')){
            }else{
              this.errors.push(item)
            }
          }else{
            this.errors.push(item)
          }
        }
        if(this.fotoMiniatura == "" && foto == false){
          this.errors.push('foto')
          foto = true
        }
      }
      if(this.detalleRendicion.rut != ""){
        var validacion = this.validarRut(this.detalleRendicion.rut)
        if(validacion == false){
          this.errors.push('rut invalido')
        }
      }
      if(this.detalleRendicion.idTipoDocumento == 3 || this.detalleRendicion.idTipoDocumento == 4 || this.detalleRendicion.idTipoDocumento == 5){
          let datosAux = [];
          datosAux[0] = this.detalleRendicion.nroDocumento;
          datosAux[1] = this.formatoRut(this.detalleRendicion.rut);
          var contador = await axios.post('api/verificarDetalleRendi', datosAux).then(function(response) {
            return response.data;
          }.bind(this)).catch(function(e) {
            console.log(e);
          });
          if (contador > 0){
            this.errors.push('detalleExistente');
          }else{
            if (this.cantidadDetalles > 0){
              var existe = false;
              this.detalles.forEach(detalle => {
                if (datosAux[0] == detalle.nroDocumento && datosAux[1] == detalle.rut && existe == false){
                  this.errors.push('detalleExistente');
                  existe = true;
                }
              })
            }
          }
        }

        var mensaje = ""
        if (this.errors.length != 0){
          this.errors.forEach(item => {
            if(item == 'rut invalido'){
              mensaje =   mensaje + "El rut ingresado es invalido" + "\n" 
            }else if(item == 'idTipoDocumento'){
              mensaje =   mensaje + "El campo Tipo Documento es requerido" + "\n" 
            }else if(item == 'fechaRetiro'){
              mensaje =   mensaje + "El campo Fecha Retiro es requerido" + "\n" 
            }else if(item == 'nroDocumento'){
              mensaje =   mensaje + "El campo Nº Documento es requerido" + "\n" 
            }else if(item == 'tipoGasto'){
              mensaje =   mensaje + "El campo Tipo Gasto es requerido" + "\n" 
            }else if(item == 'detalleExistente'){
              mensaje =   mensaje + "El documento ingresado ya existe" + "\n" 
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
      if(this.errors.length === 0){
        let detalle = {
          idDetalle: this.idEditar ,
          idTipoDocumento: this.detalleRendicion.idTipoDocumento,
          fecha: this.detalleRendicion.fecha,
          nroDocumento: this.detalleRendicion.nroDocumento,
          rut: this.formatoRut(this.detalleRendicion.rut),
          idTipoGasto: this.detalleRendicion.tipoGasto,
          idGasto: this.detalleRendicion.gasto,
          monto: this.detalleRendicion.monto,
          foto: this.detalleRendicion.foto,
          observaciones: this.detalleRendicion.observaciones,
        };
        this.detalles[id] = detalle;
        this.rendicion.monto = 0

        this.detalles.forEach(detalleAux => {
        this.rendicion.monto = Number(this.rendicion.monto) + Number(detalleAux.monto)
      });

      this.detalleRendicion.idTipoDocumento = "";
      this.detalleRendicion.fecha = "";
      this.detalleRendicion.rut = "";
      this.detalleRendicion.nroDocumento = "";
      this.detalleRendicion.tipoGasto = "";
      this.detalleRendicion.gasto = "";
      this.detalleRendicion.monto = "";
      this.detalleRendicion.foto = null;
      this.detalleRendicion.observaciones = "";
      this.fotoMiniatura = "";
      this.clearInput('foto')

      this.toast.success("Detalle Editado correctamente!", {
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

      this.modoeditar = false;
      }
    },
    // Funcion para crear una rendicion con sus detalles
    async crearRendicion() {
      this.errorsRendi = []

      for (const item in this.solicitud){
        if(this.solicitud[item] === "" || this.solicitud[item] === 0){
          this.errorsRendi.push(item)
        }
      }
      for (const item in this.rendicion){
        if(this.rendicion[item] === "" || this.rendicion[item] === 0){
          this.errorsRendi.push(item)
        }
      }
      if (this.cantidadDetalles == 0){
        this.errorsRendi.push('detalles')
        this.toast.warning('No puede crear una Rendición sin detalles', {
          position: "top-right",
          timeout: 4000,
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
      var mensaje = ""
      if (this.errorsRendi.length != 0){
        this.errorsRendi.forEach(item => {
          if(item != 'detalles'){
            if(item == 'rut invalido'){
              mensaje =   mensaje + "El rut ingresado es invalido" + "\n" 
            }else if(item == 'detallesoli'){
              mensaje =   mensaje + "El campo Detalle Solicitud es requerido" + "\n" 
            }else if(item == 'detalleRendi'){
              mensaje =   mensaje + "El campo Detalle Rendición es requerido" + "\n" 
            }else{
              mensaje =   mensaje + "El campo " + this.capitalizeFirstLetter(item) + " es requerido" + "\n" 
            }
          }
        });
        this.toast.warning( mensaje, {
          position: "top-right",
          timeout: 4000,
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
      if(this.errorsRendi.length === 0){
        const ok = await this.$refs.confirmation.show({
          title: 'Crear Rendición',
          message: `¿Está seguro/a que desea crear esta Rendición ? Esta acción no puede ser desecha.`,
          okButton: 'Crear',
          cancelButton: 'Volver'
        })
      if (ok) {
        const datos = []
        var tipoImposicion1 = ""

        if ((this.solicitud.monto - this.rendicion.monto) == 0){
          tipoImposicion1 = 'Ninguno'
        }

        if((this.solicitud.monto - this.rendicion.monto) > 0){
          tipoImposicion1 = 'Sobro'
        }

        if((this.solicitud.monto - this.rendicion.monto) < 0){
          tipoImposicion1 = 'Falto'
        }

        let rendicion = {
          folio: this.solicitud.folio,
          fechaSoli: this.solicitud.fecha,
          fechaRendi: this.solicitud.fecha,
          idUsuarioSoli: this.solicitud.usuario,
          idUsuarioRendi: this.solicitud.usuario,
          montoSoli: this.solicitud.monto,
          montoRendi: this.rendicion.monto,
          colaborador: this.solicitud.colaborador,
          detalleSoli: this.solicitud.detallesoli,
          detalleRendi: this.rendicion.detalleRendi,
          idTipoMoneda: this.solicitud.moneda,
          pendiente: 0,
          tipoImposicion: tipoImposicion1,
        }
        datos.push(rendicion)
        datos.push(this.detalles)

        axios.post("api/storeRendiciones", datos, { headers: { 'Content-Type' : 'multipart/form-data' }
        }).then((result) => {
          this.buttonText = 'Guardando...';
          this.buttonDisable1 = true;
          this.toast.success("Rendición guardada con éxito!", {
            position: "top-right",
            timeout: 4000,
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
          axios.post('api/rendiciones/sendEmailRendir', datos,{ headers: { 'Content-Type' : 'multipart/form-data' }}).then( response => {
              this.toast.success("Correo enviado!", {
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
              setTimeout(() => {this.cerrarModal();}, 1500);
            }).catch(e=> { console.log(e)});
          }).catch((error)=> {
            if (error.response.status == 422){
              this.errors = error.response.data.errors;
              this.toast.warning('Existe un valor inválido.', {
                position: "top-right",
                timeout: 4000,
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
    },
    // Funcion para obtener los tipos de documento existentes
    getTipoDoc(){
      axios.get('api/getTipoDoc').then( response =>{
        this.tiposDoc = response.data;
      }).catch(e=> console.log(e))
    },
    // Funcion para obtener los gastos desde la base de datos
    getGastos(){
      axios.get("api/getGastos").then( response =>{
        this.gastos = response.data;
      }).catch(e=> console.log(e))
    },
    // Funcion para rellenar select de gastos segun el tipo de gasto seleccionado
    getGastosSelect(id){
      this.gastosSelect = [];
      this.detalleRendicion.gasto = "";
      this.gastos.forEach(gasto => {
        if(gasto.idTipoGasto == id){
          this.gastosSelect.push(gasto);
        }
      });
    },
    // Funcion para obtener los tipos de gastos existentes
    getTipoGasto(){
      axios.get('api/getTipoGasto').then( response =>{
        this.tiposGastos = response.data;
      }).catch(e=> console.log(e))
    },
    // Funcion para obtener los tipos de monedas en la base de datos
    getMonedas(){
      axios.get('api/getMonedas').then( response =>{
        this.monedas = response.data;
      }).catch(e=> console.log(e))
    },
    // Funcion para crear solo la solicitud de una rendicion
    async crearSolicitud(){
      this.errorsSoli = []
      for (const item in this.solicitud){
        if(this.solicitud[item] === "" || this.solicitud[item] === 0){
          this.errorsSoli.push(item)
        }
      }
      var mensaje = ""
      if (this.errorsSoli.length != 0){
        this.errorsSoli.forEach(item => {
            if(item == 'detallesoli'){
              mensaje =   mensaje + "El campo Detalle Solicitud es requerido" + "\n" 
            }else if(item == 'detalleRendi'){
              mensaje =   mensaje + "El campo Detalle Rendición es requerido" + "\n" 
            }else{
              mensaje =   mensaje + "El campo " + this.capitalizeFirstLetter(item) + " es requerido" + "\n" 
            }
        });
        this.toast.warning( mensaje, {
          position: "top-right",
          timeout: 4000,
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
      if(this.errorsSoli.length === 0){
        const ok = await this.$refs.confirmation.show({
          title: 'Crear Solicitud',
          message: `¿Está seguro/a que desea crear esta solicitud ? Esta acción no puede ser desecha.`,
          okButton: 'Crear',
          cancelButton: 'Volver'
        })
        if (ok) {
          let solicitud = {
            folio: this.solicitud.folio,
            fechaSoli: this.solicitud.fecha,
            idUsuarioSoli: this.solicitud.usuario,
            montoSoli: this.solicitud.monto,
            colaborador: this.solicitud.colaborador,
            detalleSoli: this.solicitud.detallesoli,
            idTipoMoneda: this.solicitud.moneda,
            pendiente: 1,
          }
          axios.post("api/rendiciones", solicitud).then((result) => {
            this.buttonText = 'Guardando...';
            this.buttonDisable2 = true;
            this.toast.success("Solicitud guardada con éxito!", {
              position: "top-right",
              timeout: 4000,
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
            axios.post('api/rendiciones/sendEmail', solicitud,{ headers: { 'Content-Type' : 'multipart/form-data' }
            }).then( response => {
              this.toast.success("Correo enviado!", {
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
              setTimeout(() => {this.cerrarModal();}, 1500);
            }).catch(e=> { console.log(e)});
          }).catch((error)=> {
            if (error.response.status == 422){
              this.errors = error.response.data.errors;
              this.toast.warning('Existe un valor inválido.', {
                position: "top-right",
                timeout: 4000,
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
    //Funcion para agregar un objeto de detalle a la lista de detalles
    async agregarDetalle(){

      this.errors = []
      var foto = false

      for (const item in this.detalleRendicion){
        if(this.detalleRendicion[item] === "" || this.detalleRendicion[item] === 0){
          if(item == 'rut' || item == 'nroDocumento'){
            if((this.detalleRendicion.idTipoDocumento == 1 && item == 'rut') || (this.detalleRendicion.idTipoDocumento == 1 && item == 'nroDocumento' )|| (this.detalleRendicion.idTipoDocumento == 2 && item == 'rut')){
            }else{
              this.errors.push(item)
            }
          }else{
            this.errors.push(item)
          }
        }

        if(this.fotoMiniatura == "" && foto == false){
          this.errors.push('foto')
          foto = true
        }
      }

      if(this.detalleRendicion.rut != ""){
        var validacion = this.validarRut(this.detalleRendicion.rut)
        if(validacion == false){
          this.errors.push('rut invalido')
        }
      }

      if(this.detalleRendicion.idTipoDocumento == 3 || this.detalleRendicion.idTipoDocumento == 4 || this.detalleRendicion.idTipoDocumento == 5){
        let datosAux = [];
        datosAux[0] = this.detalleRendicion.nroDocumento;
        datosAux[1] = this.formatoRut(this.detalleRendicion.rut);
        var contador = await axios.post('api/verificarDetalleRendi', datosAux).then(function(response) {
          return response.data;
        }.bind(this)).catch(function(e) {
          console.log(e);
        });
        if (contador > 0){
          this.errors.push('detalleExistente');
        }else{
          if (this.cantidadDetalles > 0){
            var existe = false;
            this.detalles.forEach(detalle => {
              if (datosAux[0] == detalle.nroDocumento && datosAux[1] == detalle.rut && existe == false){
                this.errors.push('detalleExistente');
                existe = true;
              }
            })
          }
        }
      }
      
      var mensaje = ""
      if (this.errors.length != 0){
        this.errors.forEach(item => {
          if(item == 'rut invalido'){
            mensaje =   mensaje + "El rut ingresado es invalido" + "\n" 
          }else if(item == 'idTipoDocumento'){
            mensaje =   mensaje + "El campo Tipo Documento es requerido" + "\n" 
          }else if(item == 'fechaRetiro'){
            mensaje =   mensaje + "El campo Fecha Retiro es requerido" + "\n" 
          }else if(item == 'nroDocumento'){
            mensaje =   mensaje + "El campo Nº Documento es requerido" + "\n" 
          }else if(item == 'tipoGasto'){
            mensaje =   mensaje + "El campo Tipo Gasto es requerido" + "\n" 
          }else if(item == 'detalleExistente'){
              mensaje =   mensaje + "El documento ingresado ya existe" + "\n" 
          }else{
            mensaje =   mensaje + "El campo " + this.capitalizeFirstLetter(item) + " es requerido" + "\n" 
          }
        });
        this.toast.warning( mensaje, {
          position: "top-right",
          timeout: 4000,
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
        let detalle = {
          idDetalle: this.cantidadDetalles,
          idTipoDocumento: this.detalleRendicion.idTipoDocumento,
          fecha: this.detalleRendicion.fecha,
          nroDocumento: this.detalleRendicion.nroDocumento,
          rut: this.formatoRut(this.detalleRendicion.rut),
          idTipoGasto: this.detalleRendicion.tipoGasto,
          idGasto: this.detalleRendicion.gasto,
          monto: this.detalleRendicion.monto,
          foto: this.detalleRendicion.foto,
          observaciones: this.detalleRendicion.observaciones,
        };

        this.cantidadDetalles = this.cantidadDetalles + 1;
        this.detalles.push(detalle);

        this.rendicion.monto = 0

        this.detalles.forEach(detalleAux => {
          this.rendicion.monto = Number(this.rendicion.monto) + Number(detalleAux.monto)
        });

        this.detalleRendicion.idTipoDocumento = "";
        this.detalleRendicion.fecha = "";
        this.detalleRendicion.rut = "";
        this.detalleRendicion.nroDocumento = "";
        this.detalleRendicion.tipoGasto = "";
        this.detalleRendicion.gasto = "";
        this.detalleRendicion.monto = "";
        this.detalleRendicion.foto = null;
        this.detalleRendicion.observaciones = "";
        this.fotoMiniatura = "";
        this.clearInput('foto')

        this.toast.success("Detalle agregado correctamente!", {
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
    },
    // Funcion para obtener datos variados para la rendicion
    getDatos(id){
      axios.get(`api/getDataRendi/${id}`).then( response =>{
        this.solicitud.folio = response.data[0];
        this.solicitud.fecha = response.data[1];
        this.nombreUsuario = response.data[2];
      }).catch(e=> console.log(e))
    },
    // Funcion para extraer la imagen del input de foto
    async getImage(e){
      this.detalleRendicion.foto = e.target.files[0];
      let compression = [];
      await this.compressImages(this.detalleRendicion.foto,compression);
      var file = new File([compression[0]], compression[0]['name'], {
      type: compression[0]['type'],});
      this.detalleRendicion.foto = file;
      this.chargeImage(this.detalleRendicion.foto);
    },
    // Funcion para cargar la imagen como miniatura
    chargeImage(file){
      let reader = new FileReader();
      reader.onload = (e) => { 
        this.fotoMiniatura = e.target.result;
      }
      reader.readAsDataURL(file);
    },
  },
  computed: {
    imagen(){
      return this.fotoMiniatura;
    }
  }
}
</script>