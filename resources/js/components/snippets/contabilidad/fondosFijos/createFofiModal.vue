<template> 
  <transition name="modalcrear">
    <div name="modal">
      <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container">
              <div class="modal-header pb-2" style="font-weight: bold; color: #444444;">
                <slot name="header">
                    Nuevo Fondo Fijo
                </slot>
                <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
              </div>
              <div class="modal-body">
                <slot name="body">
                  <table id="tableDesign" class="table align-items-center mb-0">
                      <thead>
                        <tr>
                          <th class="text-uppercase text-xs font-weight-bolder">N° Folio</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Fecha</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Usuario</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Cantidad Doc.</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Fofi Asignado</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Total Gastos</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Diferencia</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td><p class="text-sm mb-0"> {{ this.fields.folioFofi }}</p></td>
                          <td><p class="text-sm mb-0"> {{ this.fields.fecha }}</p></td>
                          <td><p class="text-sm mb-0"> {{ nombreUsuario }}</p></td>
                          <td><p class="text-sm mb-0"> {{ this.fields.cantidadGastos }}</p></td>
                          <td><p class="text-sm mb-0"> {{ formatMoneyCLP(this.fields.fofiAsignado) }}</p></td>
                          <td><p class="text-sm mb-0"> {{ formatMoneyCLP(this.fields.montoTotal)  }}</p></td>
                          <td><p class="text-sm mb-0"> {{ formatMoneyCLP(this.fields.fofiAsignado - this.fields.montoTotal) }}</p></td>
                        </tr>
                      </tbody>
                    </table>
                    <br>
                    <label style="vertical-align: top;" for="txtArea">Observación:</label>&nbsp;&nbsp;<textarea style="resize: none;" class="form-control" id="txtArea" rows="1" cols="50" v-model="fields.observacion"></textarea>
                    <hr size="4" class="separador">
                    <form enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-3">
                          <div class="form-group">
                            <label for="idTipoDocumento">Tipo Documento: </label>
                              <br>
                              <select class="form-select" v-model="detalleFofi.idTipoDocumento">
                              <option disabled value="">Seleccione un tipo</option>
                              <option v-for="tipoDoc in tiposDoc" v-bind:key="tipoDoc.id" v-bind:value="tipoDoc.id">
                                {{ tipoDoc.nombre }}
                              </option>
                              </select>
                          </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group">
                                <label for="fecha">Fecha: </label>
                                <br>
                                <input type="date" class= "form-control" v-model="detalleFofi.fecha" @change="verificarFecha()">
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group">
                                <label for="nroDocumento">N° Documento: </label>
                                <br>
                                <input type="number" class= "form-control"  v-model="detalleFofi.nroDocumento">
                            </div>
                        </div>
                        <div class="col-2">
                            <div class="form-group">
                                <label for="rut">Rut Documento: </label>
                                <br>
                                <input type="text" class= "form-control" v-on:input="validateInput" v-on:keypress="isNumberOrDash" @keyup="checkRut()" v-model="detalleFofi.rut">
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="form-group">
                                <label for="observaciones">Detalle: </label>
                                <br>
                                <input type="text" class= "form-control"  v-model="detalleFofi.observaciones">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                      <div class="col-3">
                          <div class="form-group">
                            <label for="tipoGasto">Tipo Gasto: </label>
                            <br>
                            <select class="form-select" @change="getGastosSelect($event.target.value)" v-model="detalleFofi.tipoGasto">
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
                            <select class="form-select" v-model="detalleFofi.gasto">
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
                                <input type="number" class= "form-control"  v-model="detalleFofi.monto">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <label for="foto">Foto: </label>
                                <br>
                                <input type="file" accept=".jpg, .jpeg, .png, .heif, .heic" id="foto" class="form-control"  @change="getImage" v-has-error="errors.foto">
                            </div>
                        </div>
                        <div v-if="fotoMiniatura != '' " class="col-4">
                          <figure>
                            <img width="100" height="100" :src="imagen" alt="Foto Miniatura">
                          </figure>
                        </div>
                      </div>
                      <a v-if="modoeditar == false" @click=agregarDetalle() class="btn btn-dark-grey float-right">Agregar detalle</a>
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
                          <th class="text-uppercase text-xs font-weight-bolder">Tipo Gasto</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Gasto</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Monto</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Acciones</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-if="fields.cantidadGastos == 0">
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
                          <td><p class="text-sm mb-0">
                          <a class="btn btn-alert btn-xs" title="Editar" @click="editarDetalle(detalle.idDetalle)"><i class="fa fa-fw fa-edit"></i></a>
                          &nbsp;
                          <a class="btn btn-closed btn-xs" title="Eliminar" @click="eliminarDetalle(detalle)"><i class="fa fa-fw fa-trash"></i></a>
                          </p></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </slot>
              </div>
              <div class="modal-footer">
                <slot name="footer">
                  <button class="btn btn-dark-grey float-end" @click="crearFofi()" :disabled="buttonDisable">
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
        fields: {
          folioFofi: "",
          fecha: "",
          usuario: JSON.parse(user.content).id,
          observacion: "",
          cantidadGastos: 0,
          fofiAsignado: JSON.parse(user.content).fofiAsignado,
          montoTotal: 0,
        },
        detalleFofi:{
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
        idUsuario: JSON.parse(user.content).id,
        fotoMiniatura: "",
        fondoFijoPendiente: 0,
        buttonText:'Crear Fondo Fijo',
        buttonDisable: false,
        tiposGastos:[],
        gastos:[],
        gastosSelect:[],
        tiposDoc:[],
        detalles:[],
        errors:[],
        nombreUsuario: "",
        montoAnterior: "",
        modoeditar: false,
        idEditar:"",
        tienda: false,
    }),
    created(){
      this.getDatos(this.idUsuario);
      this.comprobarRol(JSON.parse(roles.content));
      this.getTipoGasto();
      this.getGastos();
      this.getTipoDoc();
    },  
    methods: {
      validateInput() {
        this.detalleFofi.rut = this.detalleFofi.rut.replace(/[^0-9\-]/g, '').replace(/(\-)\-+/g, '$1');
      },
      isNumberOrDash(event) {
        const charCode = event.which ? event.which : event.keyCode;
        if (charCode === 45 && this.detalleFofi.rut.indexOf('-') !== -1) {
          event.preventDefault();
        } else if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode !== 45) {
          event.preventDefault();
        }
      },
      verificarFecha() {
        const fechaActual = new Date().toISOString().slice(0, 10);
        if (this.detalleFofi.fecha > fechaActual) {
          this.detalleFofi.fecha = "";
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
        if (this.detalleFofi.rut != ''){
          this.detalleFofi.rut.replace('.','');
          var valor = this.detalleFofi.rut.replace('.','');
          valor = valor.replace('-','');
          var cuerpo = valor.slice(0,-1);
          var dv = valor.slice(-1).toUpperCase();

          this.detalleFofi.rut = cuerpo + '-'+ dv
        }
      },
      comprobarRol(roles){
        roles.forEach(rol => {
          if(rol == 'Tiendas'){
            this.tienda = true
          }
        });
      },
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
            this.fields.cantidadGastos = this.fields.cantidadGastos - 1
            this.fields.montoTotal = 0
            this.detalles.forEach(detalleAux => {
              this.fields.montoTotal = Number(this.fields.montoTotal) + Number(detalleAux.monto)
            });
          }
      },
      cancelarEditar(){
        this.modoeditar= false;
        this.detalleFofi.idTipoDocumento = "";
        this.detalleFofi.fecha = "";
        this.detalleFofi.rut = "";
        this.detalleFofi.nroDocumento = "";
        this.detalleFofi.tipoGasto = "";
        this.detalleFofi.gasto = "";
        this.detalleFofi.monto = "";
        this.detalleFofi.foto = null;
        this.detalleFofi.observaciones = "";
        this.fotoMiniatura = "";
        this.clearInput('foto');

        this.idEditar = "";
      },
      editarDetalle(id){
        this.idEditar = "";
        this.idEditar = id;
        this.modoeditar= true
        var detalle = this.detalles[id]
        this.montoAnterior = detalle.monto;
        this.detalleFofi.idTipoDocumento = detalle.idTipoDocumento;
        this.detalleFofi.fecha = detalle.fecha;
        this.detalleFofi.rut = detalle.rut;
        this.detalleFofi.nroDocumento = detalle.nroDocumento;
        this.detalleFofi.tipoGasto = detalle.idTipoGasto;
        this.getGastosSelect(detalle.idTipoGasto);
        this.detalleFofi.gasto = detalle.idGasto;
        this.detalleFofi.monto = detalle.monto;
        this.detalleFofi.foto = detalle.foto;

        var input = document.querySelector("#foto");

        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(detalle.foto);

        input.files = dataTransfer.files;

        this.detalleFofi.observaciones = detalle.observaciones;
        this.chargeImage(detalle.foto);
      },
      async guardarEdicion(id){

        this.errors = []
        var foto = false

        for (const item in this.detalleFofi){
          if(this.detalleFofi[item] === "" || this.detalleFofi[item] === 0){
            if(item == 'rut' || item == 'fechaRetiro' || item == 'nroDocumento'){
              if((this.detalleFofi.idTipoDocumento == 1 && item == 'rut') || (this.detalleFofi.idTipoDocumento == 1 && item == 'nroDocumento' ) ||  (this.detalleFofi.idTipoDocumento == 2 && item == 'rut' )){
              }else{
                this.errors.push(item);
              }
            }else{
              this.errors.push(item);
            }

            if(this.fotoMiniatura == "" && foto == false){
              this.errors.push('foto');
              foto = true;
            }
          }
        }
        if(this.detalleFofi.rut != ""){
          var validacion = this.validarRut(this.detalleFofi.rut);
          if(validacion == false){
            this.errors.push('rut invalido');
            }
        }

        if(this.detalleFofi.idTipoDocumento == 3 || this.detalleFofi.idTipoDocumento == 4 || this.detalleFofi.idTipoDocumento == 5){
          let datosAux = [];
          datosAux[0] = this.detalleFofi.nroDocumento;
          datosAux[1] = this.formatoRut(this.detalleFofi.rut);
          var contador = await axios.post('api/verificarDetalle', datosAux).then(function(response) {
            return response.data;
          }.bind(this)).catch(function(e) {
            console.log(e);
          });
          if (contador > 0){
            this.errors.push('detalleExistente');
          }else{
            if (this.fields.cantidadGastos > 0){
              var existe = false;
              var contador2 = 0;
              this.detalles.forEach(detalle => {
                if (datosAux[0] == detalle.nroDocumento && datosAux[1] == detalle.rut && existe == false && contador2 != id){
                  this.errors.push('detalleExistente');
                  existe = true;
                }
                contador2 = contador2 + 1;
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

        if((Number(this.fields.montoTotal) - Number(this.montoAnterior)) + Number(this.detalleFofi.monto)   > Number(this.fields.fofiAsignado) ){
          this.errors.push('MontoSuperado');
          this.toast.error( 'El monto total excede el fofi asignado', {
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
            idTipoDocumento: this.detalleFofi.idTipoDocumento,
            fecha: this.detalleFofi.fecha,
            nroDocumento: this.detalleFofi.nroDocumento,
            rut: this.formatoRut(this.detalleFofi.rut),
            idTipoGasto: this.detalleFofi.tipoGasto,
            idGasto: this.detalleFofi.gasto,
            monto: this.detalleFofi.monto,
            foto: this.detalleFofi.foto,
            observaciones: this.detalleFofi.observaciones,
          };
          this.detalles[id] = detalle;
          this.fields.montoTotal = 0

          this.detalles.forEach(detalleAux => {
            this.fields.montoTotal = Number(this.fields.montoTotal) + Number(detalleAux.monto)
          });

          this.detalleFofi.idTipoDocumento = "";
          this.detalleFofi.fecha = "";
          this.detalleFofi.rut = "";
          this.detalleFofi.nroDocumento = "";
          this.detalleFofi.tipoGasto = "";
          this.detalleFofi.gasto = "";
          this.detalleFofi.monto = "";
          this.detalleFofi.foto = null;
          this.detalleFofi.observaciones = "";
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
      async crearFofi() {
        this.errors = []

        if (this.fields.cantidadGastos == 0){
          this.errors.push('detalles')
          this.toast.warning('No puede crear un fondo fijo sin detalles', {
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
            title: 'Crear Fondo Fijo',
            message: `¿Está seguro/a que desea crear este fondo fijo ? Esta acción no puede ser desecha.`,
            okButton: 'Crear',
            cancelButton: 'Volver'
          })
          if (ok) {
            const datos = []
            let fields = {
                folioFofi: this.fields.folioFofi,
                estado: "Pendiente",
                fecha: this.fields.fecha,
                idUsuario: this.fields.usuario,
                observacion: this.fields.observacion,
                cantidadGastos: this.fields.cantidadGastos,
                fofiAsignado: this.fields.fofiAsignado,
                montoTotal: this.fields.montoTotal,
            }
            datos.push(fields);
            datos.push(this.detalles);
            axios.post("api/fofis", datos, {
              headers: { 'Content-Type' : 'multipart/form-data' }
            } ).then((result) => {
              this.buttonDisable = true;
              this.buttonText = 'Guardando...';
              this.toast.success("Fondo Fijo creado con éxito!", {
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
              if(this.tienda == true){
                axios.post('api/fofis/sendEmailTienda', datos,{ headers: { 'Content-Type' : 'multipart/form-data' } }).then( response => {
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
                }).catch(e=> {console.log(e)});
              }else{
                axios.post('api/fofis/sendEmail', datos,{headers: { 'Content-Type' : 'multipart/form-data' }}).then( response => {
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
                }).catch(e=> {console.log(e)});
              }
            })
            .catch((e)=> {
              console.log(e);
              if (e.response.status == 422){
                this.errors = e.response.data.errors;
                this.toast.warning('Existe un valor inválido.', {
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
            });
          }
        }
      },
      getTipoDoc(){
        axios.get('api/getTipoDoc').then( response =>{
          this.tiposDoc = response.data;
          }).catch(e=> console.log(e))
      },
      getGastos(){
        axios.get("api/getGastos").then( response =>{
          this.gastos = response.data;
          }).catch(e=> console.log(e))
      },
      getGastosSelect(id){
        this.gastosSelect = [];
        this.detalleFofi.gasto = "";
        this.gastos.forEach(gasto => {
          if(gasto.idTipoGasto == id){
            this.gastosSelect.push(gasto);
          }
        });
      },
      getTipoGasto(){
        axios.get('api/getTipoGasto').then( response =>{
          this.tiposGastos = response.data;
          }).catch(e=> console.log(e))
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
      async agregarDetalle(){
        this.errors = [];
        var foto = false;
        for (const item in this.detalleFofi){
          if(this.detalleFofi[item] === "" || this.detalleFofi[item] === 0){
            if(item == 'rut' || item == 'fechaRetiro' || item == 'nroDocumento'){
              if((this.detalleFofi.idTipoDocumento == 1 && item == 'rut' ) || (this.detalleFofi.idTipoDocumento == 1 && item == 'nroDocumento' ) ||(this.detalleFofi.idTipoDocumento == 2 && item == 'rut')){
              }else{
                this.errors.push(item);
              }
            }else{
              this.errors.push(item);
            }

            if(this.fotoMiniatura == "" && foto == false){
              this.errors.push('foto');
              foto = true;
            }
          }
        }

        if(this.detalleFofi.rut != ""){
          var validacion = this.validarRut(this.detalleFofi.rut);
          if(validacion == false){
            this.errors.push('rut invalido');
          }
        }

        if(this.detalleFofi.idTipoDocumento == 3 || this.detalleFofi.idTipoDocumento == 4 || this.detalleFofi.idTipoDocumento == 5){
          let datosAux = [];
          datosAux[0] = this.detalleFofi.nroDocumento;
          datosAux[1] = this.formatoRut(this.detalleFofi.rut);
          var contador = await axios.post('api/verificarDetalle', datosAux).then(function(response) {
            return response.data;
          }.bind(this)).catch(function(e) {
            console.log(e);
          });
          if (contador > 0){
            this.errors.push('detalleExistente');
          }else{
            if (this.fields.cantidadGastos > 0){
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

        if(Number(this.fields.montoTotal) + Number(this.detalleFofi.monto) > Number(this.fields.fofiAsignado) ){
          this.errors.push('MontoSuperado');
          this.toast.error( 'El monto total excede el fofi asignado', {
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
            idDetalle: this.fields.cantidadGastos ,
            idTipoDocumento: this.detalleFofi.idTipoDocumento,
            fecha: this.detalleFofi.fecha,
            nroDocumento: this.detalleFofi.nroDocumento,
            rut: this.formatoRut(this.detalleFofi.rut),
            idTipoGasto: this.detalleFofi.tipoGasto,
            idGasto: this.detalleFofi.gasto,
            monto: this.detalleFofi.monto,
            foto: this.detalleFofi.foto,
            observaciones: this.detalleFofi.observaciones,
          };
          this.fields.cantidadGastos = this.fields.cantidadGastos + 1;
          this.detalles.push(detalle);
          this.fields.montoTotal = 0
          this.detalles.forEach(detalleAux => {
            this.fields.montoTotal = Number(this.fields.montoTotal) + Number(detalleAux.monto)
          });

          this.detalleFofi.idTipoDocumento = "";
          this.detalleFofi.fecha = "";
          this.detalleFofi.rut = "";
          this.detalleFofi.nroDocumento = "";
          this.detalleFofi.tipoGasto = "";
          this.detalleFofi.gasto = "";
          this.detalleFofi.monto = "";
          this.detalleFofi.foto = null;
          this.detalleFofi.observaciones = "";
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
      getDatos(id){
        axios.get(`api/dataFofi/${id}`).then( response =>{
          this.fields.folioFofi = response.data[0];
          this.fields.fecha = response.data[1];
          this.nombreUsuario = response.data[2];
          this.fondoFijoPendiente = response.data[3];
          this.fields.fofiAsignado = this.fields.fofiAsignado - this.fondoFijoPendiente;
          }).catch(e=> console.log(e))
      },
      async getImage(e){
          this.detalleFofi.foto = e.target.files[0];
          let compression = [];
          await this.compressImages(this.detalleFofi.foto,compression);
          var file = new File([compression[0]], compression[0]['name'], {
          type: compression[0]['type'],});
          this.detalleFofi.foto = file;
          this.chargeImage(this.detalleFofi.foto);
      },
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
