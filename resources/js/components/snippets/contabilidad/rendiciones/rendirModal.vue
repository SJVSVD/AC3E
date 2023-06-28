
<template>
  <transition name="modalver">
    <div name="modal">
      <div class="modal-mask">
        <div class="modal-wrapper">
          <div class="modal-container">
            <div class="modal-header fw-bold" style="color: #444444;">
              <slot name="header">
                Rendir Solicitud
              </slot>
              <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
            </div>
            <div class="modal-body">
              <slot name="body">
                <h4>Solicitud</h4>
                <br>
                <table id="tableDesign" class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-xs font-weight-bolder">N° Folio</th>
                      <th class="text-uppercase text-xs font-weight-bolder">Colaborador</th>
                      <th class="text-uppercase text-xs font-weight-bolder">Usuario Solicitud</th>
                      <th class="text-uppercase text-xs font-weight-bolder">Usuario Rendición</th>
                      <th class="text-uppercase text-xs font-weight-bolder">Fecha Solicitud</th>
                      <th class="text-uppercase text-xs font-weight-bolder">Fecha Rendición</th>
                      <th class="text-uppercase text-xs font-weight-bolder">Monto Solicitud</th>
                      <th class="text-uppercase text-xs font-weight-bolder">Monto Rendición</th>
                      <th class="text-uppercase text-xs font-weight-bolder">Tipo Moneda</th>
                    </tr>
                  </thead>
                  <tbody> 
                    <tr>
                      <td><p class="text-sm mb-0"> {{ rendi1.folio }} </p></td>
                      <td><p class="text-sm mb-0"> {{ rendi1.colaborador }} </p></td>
                      <td :title="nombreUsuarioSoli"><p class="text-sm mb-0" style="width: 100px; word-wrap: break-word; overflow: hidden; text-overflow: ellipsis;"> {{ nombreUsuarioSoli  }}</p></td>
                      <td :title="nombreUsuario"><p class="text-sm mb-0" style="width: 100px; word-wrap: break-word; overflow: hidden; text-overflow: ellipsis;"> {{ nombreUsuario }}</p></td>
                      <td><p class="text-sm mb-0"> {{ thisDate(rendi1.fechaSoli) }} </p></td>
                      <td><p class="text-sm mb-0"> {{ rendi1.fechaRendi  }} </p></td>
                      <td><p class="text-sm mb-0"> {{ formatMoneyCLP(rendi1.montoSoli) }} </p></td>
                      <td><p class="text-sm mb-0"> {{ formatMoneyCLP(rendi1.montoRendi) }} </p></td>
                      <td><p class="text-sm mb-0"> {{ abrMoneda }} </p></td>
                    </tr>
                  </tbody>
                </table>
                <hr size="4" class="separador">
                <label> Detalle Solicitud: </label>
                <p>{{ this.rendi1.detalleSoli }}</p>
                <br>
                <label style="vertical-align: top;" for="txtArea">Detalle Rendición:</label>&nbsp;&nbsp;<textarea style="resize: none;" 
                class="form-control" id="txtArea" rows="1" cols="50" v-model="rendi1.detalleRendi"></textarea>
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
                        <input type="date" class= "form-control" v-model="detalleRendicion.fecha" @change="verificarFecha()">
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
                        <input type="text" class= "form-control" v-on:input="validateInput" v-on:keypress="isNumberOrDash" @keyup="checkRut()" v-model="detalleRendicion.rut">
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
                  <div class="justify-content-end">
                    <a v-if="modoeditar == false" @click=agregarDetalle() class="btn btn-dark-grey">Agregar detalle</a>
                    <a v-if="modoeditar == true" @click=guardarEdicion(idEditar) class="btn btn-dark-grey">Editar detalle</a>
                    &nbsp; 
                    <a v-if="modoeditar == true" @click="cancelarEditar()" class="btn btn-grey">Cancelar</a>
                  </div>
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
              </slot>
            </div>
            <div class="modal-footer">
              <slot name="footer">
                <a class="btn btn-dark-grey float-end" @click="crearRendicion()" :disabled="buttonDisable">
                  {{ buttonText }}
                </a>
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

  export default {
    components: { modalconfirmacion, modalalerta },
    mixins: [mixin],
    data: () => ({
      rendi1: {
        folio: "",
        colaborador: "",
        montoSoli: "",
        montoRendi: 0,
        idUsuarioSoli: "",
        idUsuarioRendi: JSON.parse(user.content).id,
        fechaSoli: "",
        fechaRendi: "",
        detalleSoli: "",
        detalleRendi: "",
        idTipoMoneda: "",
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
      id: null,
      fotoMiniatura: "",
      buttonText:'Crear Rendición',
      buttonDisable: false,
      abrMoneda: "",
      tiposGastos:[],
      gastos:[],
      gastosSelect:[],
      tiposDoc:[],
      detalles:[],
      errors:[],
      errorsRendi:[],
      nombreUsuario: "",
      nombreUsuarioSoli:"",
      modoeditar: false,
      cantidadDetalles:0,
      idEditar:"",    
    }),
    props:{
      rendi: Object,
    },
    created(){
      this.rendi1.folio = this.rendi.folio;
      this.rendi1.colaborador = this.rendi.colaborador;
      this.rendi1.montoSoli = this.rendi.montoSoli;
      this.rendi1.idUsuarioSoli = this.rendi.idUsuarioSoli;
      this.rendi1.fechaSoli = this.rendi.fechaSoli;
      this.rendi1.fechaRendi = this.rendi.fechaRendi;
      this.rendi1.detalleSoli = this.rendi.detalleSoli;
      this.rendi1.idTipoMoneda = this.rendi.idTipoMoneda;
      this.abrMoneda = this.rendi.moneda.abreviacion;
      this.id = this.rendi.id;
      this.getDatos(this.userID);
      this.getNomUsuarioSoli(this.rendi.idUsuarioSoli);
      this.getTipoGasto();
      this.getGastos();
      this.getTipoDoc();


    },
    methods: {
      validateInput() {
        this.detalleRendicion.rut = this.detalleRendicion.rut.replace(/[^0-9\-]/g, '').replace(/(\-)\-+/g, '$1');
      },
      isNumberOrDash(event) {
        const charCode = event.which ? event.which : event.keyCode;
        if (charCode === 45 && detalleRendicion.rut.indexOf('-') !== -1 ) {
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
          this.cantidadDetalles = this.cantidadDetalles - 1
          this.rendi1.montoRendi = 0
          this.detalles.forEach(detalleAux => {
            this.rendi1.montoRendi = Number(this.rendi1.montoRendi) + Number(detalleAux.monto);
          });
        }
      },
      // Funcion para salir del modo de edicion
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
      // Funcion para traer los datos de un detalle y iniciar el modo edicion
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
        this.getGastosSelect(detalle.idTipoGasto)
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
      // Funcion para guardar los datos Editados del detalle y salir de modo edicion
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
          /*new Compressor(this.detalleRendicion.foto, {
          quality: 0.6,
          success(result) {
          const myFile = new File([result], result.name,{
          type: result.type
          })
          this.fotoComprimida = myFile;
          },
          error(err) {
          console.log(err.message);
          },
          });*/
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
          this.rendi1.montoRendi = 0

          this.detalles.forEach(detalleAux => {
          this.rendi1.montoRendi = Number(this.rendi1.montoRendi) + Number(detalleAux.monto)
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
      // Funcion para crear la rendicion y sus detalles en la base de datos
      async crearRendicion() {
        this.errorsRendi = []
        if(this.rendi1.detalleRendi == ""){
          this.errorsRendi.push('detalleRendi')
          this.toast.warning('El detalle de la Rendición es requerido', {
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
        if(this.rendi1.tipoImposicion == ""){
          this.errorsRendi.push('tipo imposicion')
          this.toast.warning('El campo tipo imposicion es requerido', {
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
        if (this.cantidadDetalles == 0){
          this.errorsRendi.push('detalles')
          this.toast.warning('No puede crear una Rendición sin detalles', {
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

            if ((this.rendi1.montoSoli - this.rendi1.montoRendi) == 0){
              tipoImposicion1 = 'Ninguno'
            }

            if((this.rendi1.montoSoli - this.rendi1.montoRendi) > 0){
              tipoImposicion1 = 'Sobro'
            }

            if((this.rendi1.montoSoli - this.rendi1.montoRendi) < 0){
              tipoImposicion1 = 'Falto'
            }

            let rendicion = {
              folio: this.rendi1.folio,
              colaborador: this.rendi1.colaborador,
              montoSoli: this.rendi1.montoSoli,
              montoRendi: this.rendi1.montoRendi,
              idUsuarioSoli: this.rendi1.idUsuarioSoli,
              idUsuarioRendi: this.rendi1.idUsuarioRendi,
              fechaSoli: this.rendi1.fechaSoli,
              fechaRendi: this.rendi1.fechaRendi,
              detalleSoli: this.rendi1.detalleSoli,
              detalleRendi: this.rendi1.detalleRendi,
              pendiente: 0,
              tipoImposicion: tipoImposicion1,
            }
            datos.push(rendicion)
            datos.push(this.detalles)

            axios.post("api/storeRendir", datos,{ headers: { 'Content-Type' : 'multipart/form-data' }})
            .then((result) => {
              console.log(result.data);
              this.buttonText = 'Guardando...';
              this.buttonDisable = true;
              this.toast.success("Rendición guardada con éxito!", {
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
              axios.post('api/rendiciones/sendEmailRendir', datos,{ headers: { 'Content-Type' : 'multipart/form-data' }
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
      // Funcion para agregar un objeto de detalle a la lista
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

          this.rendi1.montoRendi = 0;

          this.detalles.forEach(detalleAux => {
            this.rendi1.montoRendi = Number(this.rendi1.montoRendi) + Number(detalleAux.monto);
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
      // Funcion para obtener los tipos de documento de la base de datos
      getTipoDoc(){
        axios.get('api/getTipoDoc').then( response =>{
          this.tiposDoc = response.data;
        }).catch(e=> console.log(e))
      },
      // Funcion para traer la fecha actual y el nombre del usuario logueado
      getDatos(id){
        axios.get(`api/getDataRendi/${id}`).then( response =>{
          this.rendi1.fechaRendi = response.data[1];
          this.nombreUsuario = response.data[2];
        }).catch(e=> console.log(e))
      },
      // Funcion para traer los gastos de la base de datos
      getGastos(){
        axios.get("api/getGastos").then( response =>{
          this.gastos = response.data;
        }).catch(e=> console.log(e))
      },
      // Funcion para rellenar el select de gastos en relacion al tipo de gasto
      getGastosSelect(id){
        this.gastosSelect = []

        this.gastos.forEach(gasto => {
          if(gasto.idTipoGasto == id){
            this.gastosSelect.push(gasto);
          }
        });
      },
      // Funcion para obtener los tipo de gasto de la base de datos
      getTipoGasto(){
        axios.get('api/getTipoGasto').then( response =>{
          this.tiposGastos = response.data;
        }).catch(e=> console.log(e))
      },
      // Funcion para conseguir el nombre del usuario que realizo la solicitud
      getNomUsuarioSoli(id){
        axios.get(`api/getNomUsuarioSoli/${id}`).then( response =>{
          this.nombreUsuarioSoli = response.data;
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