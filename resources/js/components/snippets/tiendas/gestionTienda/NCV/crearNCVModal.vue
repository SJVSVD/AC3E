<template> 
  <transition name="modalcrear">
    <div name="modal">
      <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container">
              <div class="modal-header pb-1" style="font-weight: bold; color: #444444;">
                <slot name="header">
                    Nueva Nota de Crédito
                </slot>
                <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
              </div>
              <div class="modal-body">
                <slot name="body">
                  <table id="tableDesign" class="table align-items-center mb-0">
                      <thead>
                        <tr>
                          <th class="text-uppercase text-xs font-weight-bolder">Fecha</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Tienda</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Usuario</th>
                          <th class="text-uppercase text-xs font-weight-bolder">N°NCV</th>
                          <th class="text-uppercase text-xs font-weight-bolder">N°Boleta</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Acciones</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td><p class="text-sm mb-0"> {{ this.ncv.fecha }}</p></td>
                          <td><select :disabled="inputsBloqueados == false" id="selectTienda" v-if="(tienda == false && (confirmarDatos == false || modoeditar == true)) || (tienda == true && administrador == true && (confirmarDatos == false || modoeditar == true))" class="form-select" v-model="ncv.tienda">
                                <option disabled value="">Seleccione una tienda</option>
                                <option v-for="tienda in tiendas" v-bind:key="tienda.id" v-bind:value="tienda.id">
                                  {{ tienda.codigo }} - {{ tienda.nombre }}
                                </option>
                              </select>
                              <p class="text-sm mb-0" v-else>{{ nombreTienda }}</p>
                            </td>
                          <td><p class="text-sm mb-0"> {{ nombreUsuario }}</p></td>
                          <td><input v-if="confirmarDatos == false || modoeditar == true" type="number" class= "form-control-xs" v-model="ncv.numNCV">
                              <p v-else class="text-sm mb-0"> {{ ncv.numNCV }}</p></td>
                          <td><input v-if="confirmarDatos == false || modoeditar == true" :disabled="inputsBloqueados == false" type="number" class= "form-control-xs" v-model="ncv.numBoleta">
                              <p v-else class="text-sm mb-0"> {{ ncv.numBoleta }}</p></td>
                          <td><p class="text-sm mb-0">
                            <a v-if="confirmarDatos == false || modoeditar == true" class="btn btn-search-blue btn-xs" title="Buscar" @click="buscarNCV(ncv.numNCV)"><i class="fa-solid fa-magnifying-glass"></i></a>
                            &nbsp;
                            <a v-if="confirmarDatos == false || modoeditar == true" class="btn btn-success btn-xs" title="Confirmar" @click="confirmarNCV()"><i class="fa-solid fa-check"></i></a>
                            <a v-else class="btn btn-alert btn-xs" title="Editar" @click="editarNCV()"><i class="fa fa-fw fa-edit"></i></a>
                          </p></td>
                        </tr>
                      </tbody>
                    </table>
                    <br>
                    <form enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-2">
                            <div class="form-group" >
                                <label for="">Monto NCV: </label>
                                <br>
                                <input type="number" :disabled="inputsBloqueados == false" class= "form-control-small" v-model="ncv.montoNCV">
                            </div>
                        </div>
                        <div class="col-sm-2" >
                            <div class="form-group">
                                <label for="">Monto Boleta: </label>
                                <br>
                                <input type="number" :disabled="inputsBloqueados == false" class= "form-control-small" v-model="ncv.montoBoleta">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="">Artículos NCV: </label>
                                <br>
                                <input type="number" :disabled="inputsBloqueados == false" class= "form-control-small"  v-model="ncv.cantidadArtNCV">
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label for="">Artículos Boleta: </label>
                                <br>
                                <input type="number" :disabled="inputsBloqueados == false" class= "form-control-small"  v-model="ncv.cantidadArtBoleta">
                            </div>
                        </div>
                        <div class="col-sm-2">
                          <div class="form-group">
                            <label for="">Rut Cliente: </label>
                            <br>
                            <input type="text" :disabled="inputsBloqueados == false" class= "form-control-small" v-on:input="validateInput" v-on:keypress="isNumberOrDash" @keyup="checkRut('Cliente')" v-model="ncv.rutCliente">
                          </div>
                        </div>
                        <div class="col-sm-2">
                          <div class="form-group">
                            <label for="">Nombre Cliente: </label>
                            <br>
                            <input type="text" :disabled="inputsBloqueados == false" class= "form-control-small"  v-model="ncv.nomCliente">
                          </div>
                        </div>
                    </div>
                    <div class="row">
                      <div class="col-3">
                        <div class="form-group">
                          <label for="">Motivo: </label>
                          <br>
                          <select class="form-select" v-model="ncv.motivo">
                          <option disabled value="">Seleccione un motivo</option>
                          <option>No me gusto</option>
                          <option>Error de fabrica</option>
                          <option>Otro</option>
                          </select>
                        </div>
                      </div>
                      <div v-if="ncv.motivo == 'Otro'" class="col-3">
                        <div class="form-group">
                          <label for="">Otro: </label>
                          <br>
                          <input type="text" class= "form-control" v-model="ncv.otro">
                        </div>
                      </div>
                    </div>
                  </form>
                  <div v-if="productos.length > 0">
                    <hr size="4" class="separador">
                    <h6 style="font-weight: bold; color: #444444;">Productos</h6>
                    <div class="row">
                      <table id="tableDesign" class="table align-items-center mb-0">
                        <thead>
                          <tr>
                            <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                            <th class="text-uppercase text-xs font-weight-bolder">Código</th>
                            <th class="text-uppercase text-xs font-weight-bolder">Nombre</th>
                            <th class="text-uppercase text-xs font-weight-bolder">Cantidad</th>
                            <th class="text-uppercase text-xs font-weight-bolder">Precio</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-if="productos.length == 0">
                            <td colspan="10"> No hay información disponible. </td>
                          </tr>
                          <tr v-else v-for="(producto,index) in productos" :key="producto">
                            <td><p class="text-sm mb-0"> {{ index + 1 }}  </p></td>
                            <td><p class="text-sm mb-0"> {{ producto.U_GSP_LIARTI }} </p></td>
                            <td><p class="text-sm mb-0"> {{ producto.U_GSP_LIDES1 }} </p></td>
                            <td><p class="text-sm mb-0"> {{ producto.U_GSP_LIQUAN }} </p></td>
                            <td><p class="text-sm mb-0"> {{ formatMoneyCLP(producto.U_GSP_LIIMPRML)}} </p></td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <hr size="4" class="separador">
                  <form enctype="multipart/form-data">
                    <h6 style="font-weight: bold; color: #444444;">Detalles pagos</h6>
                    <div class="row">
                      <div class="col-3">
                        <div class="form-group">
                          <label for="">Tipo Pago: </label>
                          <br>
                          <select class="form-select" v-model="detallePago.tipoPago">
                          <option disabled value="">Seleccione un tipo</option>
                          <option>Transferencia</option>
                          <option>Devolución Credito</option>
                          </select>
                        </div>
                      </div>
                      <div v-show="detallePago.tipoPago == 'Transferencia'" class="col-3">
                        <div class="form-group">
                          <label for="">Banco: </label>
                          <br>
                          <select class="form-select" v-model="detallePago.banco">
                            <option disabled value="">Seleccione un banco</option>
                            <option v-for="banco in bancos" v-bind:key="banco.id" v-bind:value="banco.nombre">
                              {{ banco.nombre }}
                            </option>
                          </select>
                        </div>
                      </div>
                      <div v-show="detallePago.tipoPago == 'Transferencia'" class="col-2">
                        <div class="form-group">
                          <label for="">Tipo Cuenta: </label>
                          <br>
                          <select class="form-select" v-model="detallePago.tipoCuenta">
                            <option disabled value="">Seleccione uno</option>
                            <option v-for="tipoCuentaBanco in tiposCuentaBanco" v-bind:key="tipoCuentaBanco.id" v-bind:value="tipoCuentaBanco.nombre">
                              {{ tipoCuentaBanco.nombre }}
                            </option>
                          </select>
                        </div>
                      </div>
                      <div v-show="detallePago.tipoPago == 'Transferencia'" class="col-2">
                        <div class="form-group">
                          <label for="">Cuenta: </label>
                          <br>
                          <input type="text" class= "form-control"  v-model="detallePago.cuenta">
                        </div>
                      </div>
                      <div v-show="detallePago.tipoPago == 'Transferencia'" class="col-2">
                        <div class="form-group">
                          <label for="">Rut: </label>
                          <br>
                          <input type="text" class= "form-control" @keyup="checkRut('Pago')" v-on:input="validateInput" v-on:keypress="isNumberOrDash" v-model="detallePago.rutPago">
                        </div>
                      </div>
                    </div>
                    <a v-if="modoEditarDetalle == false" @click=agregarDetalle() class="btn btn-dark-grey float-right">Agregar pago</a>
                    <a v-if="modoEditarDetalle == true" @click=guardarEdicion(idEditar) class="btn btn-dark-grey">Editar pago</a>
                    &nbsp;
                    <a v-if="modoEditarDetalle == true" @click="cancelarEditar()" class="btn btn-grey">Cancelar</a>
                  </form>
                  <hr size="4" class="separador">
                  <div class="row">
                    <table id="tableDesign" class="table align-items-center mb-0">
                      <thead>
                        <tr>
                          <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Tipo Pago</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Banco</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Tipo Cuenta</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Cuenta</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Rut</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Acciones</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-if="cantidadDetalles == 0">
                          <td colspan="10"> No hay información disponible. </td>
                        </tr>
                        <tr v-else v-for="(detalle,index) in detalles" :key="detalle">
                          <td><p class="text-sm mb-0"> {{ index + 1 }}  </p></td>
                          <td><p class="text-sm mb-0"> {{ detalle.tipoPago }} </p></td>
                          <td><p v-if="detalle.banco != ''" class="text-sm mb-0"> {{ detalle.banco }} </p>
                              <p v-else class="text-sm mb-0"> --- </p></td>
                          <td><p v-if="detalle.tipoCuenta != ''" class="text-sm mb-0"> {{ detalle.tipoCuenta }} </p>
                              <p v-else class="text-sm mb-0"> --- </p></td>
                          <td><p v-if="detalle.cuenta != ''" class="text-sm mb-0"> {{ detalle.cuenta }} </p>
                              <p v-else class="text-sm mb-0"> --- </p></td>
                          <td><p v-if="detalle.rut != ''" class="text-sm mb-0"> {{ detalle.rut }} </p>
                              <p v-else class="text-sm mb-0"> --- </p></td>
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
                  <button class="btn btn-dark-grey float-end" @click="crearNCV()" :disabled="buttonDisable">
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
  import modalconfirmacion from '../../../sistema/alerts/confirmationModal.vue';
  import modalalerta from '../../../sistema/alerts/alertModal.vue';
  import {mixin} from '../../../../../mixins.js';

  let user = document.head.querySelector('meta[name="user"]');
  let roles= document.head.querySelector('meta[name="roles"]');

  export default {
    components: { modalconfirmacion, modalalerta },
    mixins: [mixin],
    data: () => ({
        ncv: {
            fecha: "",
            tienda: "",
            usuario: JSON.parse(user.content).id,
            numNCV: "",
            numBoleta: "",
            montoNCV: "",
            montoBoleta: "",
            cantidadArtNCV: "",
            cantidadArtBoleta: "",
            rutCliente: "",
            nomCliente: "",
            motivo: "",
            otro: "",
        },
        detallePago:{
            tipoPago: "",
            banco: "",
            tipoCuenta: "",
            cuenta: "",
            rutPago: "",
        },  
        idUsuario: JSON.parse(user.content).id,
        buttonText:'Crear Solicitud',
        buttonDisable: false,
        inputsBloqueados: false,
        detalles:[],
        productos:[],
        errors:[],
        errorsTipo:[],
        nombreUsuario: "",
        modoeditar: false,
        modoEditarDetalle: false,
        idEditar:"",
        tienda: false,
        administrador: false,
        nombreTienda: "",
        tiendas: [],
        bancos: [],
        tiposCuentaBanco: [],
        dataNCV: "",
        nNCVAux: "",
        cantidadDetalles: 0,
        confirmarDatos: false,
    }),
    created(){
      this.getDatos(this.idUsuario);
      this.getTiendas();
      this.getBancos();
      this.getTiposCuenta();
      this.comprobarRol(JSON.parse(roles.content));
    },
    methods: {
      async buscarNCV(nNCV){
        if (this.ncv.numNCV == this.nNCVAux){
          this.toast.warning("El N° de Nota de Crédito ya fue ingresado ", {
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
        }else if (this.ncv.numNCV != ""){
          try {
            const response = await axios.get(`api/datosNCV/${nNCV}`);
            this.dataNCV = JSON.parse(response.data);
            if (this.dataNCV['value'].length != 0){
              //NUM BOLETA
              this.tiendas.forEach(tiendaAux => {
                if(tiendaAux.codigo == this.dataNCV['value'][0]['Invoices']['CardCode']){
                  this.ncv.tienda = tiendaAux.id;
                  this.nombreTienda = tiendaAux.nombre;
                }
              });
              this.ncv.numBoleta = this.dataNCV['value'][0]['Invoices']['FolioNumber'] ;
              this.ncv.montoBoleta = this.dataNCV['value'][0]['Invoices']['DocTotal'] ;
              this.ncv.montoNCV = this.dataNCV['value'][0]['U_GSP_TPVCAP']['U_GSP_CATOTA'] ;
              this.productos = [];
              this.dataNCV['value'].forEach(product => {
                this.productos.push(product['U_GSP_TPVLIN']);
              });
              this.ncv.rutCliente = this.dataNCV['value'][0]['U_GSP_TPVCAP']['U_GSP_CANIF'] ;
              this.ncv.nomCliente = this.dataNCV['value'][0]['U_GSP_TPVCAP']['U_GSP_CACLNOM'] ;
              this.ncv.cantidadArtBoleta = this.productos.length;
              this.ncv.cantidadArtNCV = this.productos.length;
              this.confirmarDatos = true;
              this.nNCVAux = this.ncv.numNCV;

            }else{
              this.toast.warning("La Nota de Crédito ingresada no existe", {
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
              const ok = await this.$refs.confirmation.show({
                title: 'La Nota de Crédito ingresada no existe',
                message: `¿ Desea ingresar los datos de forma manual ?.`,
                okButton: 'Ingresar Manual',
                cancelButton: 'Cancelar'
              })
              if (ok) {
                this.inputsBloqueados = true; 
              }
            }
          } catch (error) {
            console.error(error);
            this.toast.warning("La Nota de Crédito ingresada no existe", {
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
        }else{
          this.toast.warning("El campo de N° de Nota de Crédito es requerido", {
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
      getTiposCuenta(){
        axios.get('api/tiposCuentaBanco').then( response =>{
          this.tiposCuentaBanco = response.data;
        }).catch(e=> console.log(e))
      },
      getBancos(){
        axios.get('api/bancos').then( response =>{
          this.bancos = response.data;
        }).catch(e=> console.log(e))
      },
      getTiendas(){
        axios.get('api/getTiendas').then( response =>{
          this.tiendas = response.data;
          if (this.tienda == true && this.administrador == false){
            this.tiendas.forEach(tiendaAux => {
              if(tiendaAux.idUsuario == this.idUsuario){
                this.ncv.tienda = tiendaAux.id;
                this.nombreTienda = tiendaAux.nombre;
              }
            })
          }
        }).catch(e=> console.log(e))
      },
      capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
      },
      validateInput(input) {
        if (input == "Cliente"){
          this.ncv.rutCliente = this.ncv.rutCliente.replace(/[^0-9\-]/g, '').replace(/(\-)\-+/g, '$1');
        }else{
          this.detallePago.rutPago = this.detallePago.rutPago.replace(/[^0-9\-]/g, '').replace(/(\-)\-+/g, '$1');
        }
      },
      isNumberOrDash(event) {
        const charCode = event.which ? event.which : event.keyCode;
        if (charCode === 45 && this.ncv.rutCliente.indexOf('-') !== -1 || charCode === 45 && this.detallePago.rutPago.indexOf('-') !== -1  ) {
          event.preventDefault();
        } else if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode !== 45) {
          event.preventDefault();
        }
      },
      checkRut(input) {
        if (input == 'Cliente'){
          if (this.ncv.rutCliente != ''){
            this.ncv.rutCliente.replace('.','');
            var valor = this.ncv.rutCliente.replace('.','');
    
            valor = valor.replace('-','');
            
            var cuerpo = valor.slice(0,-1);
            var dv = valor.slice(-1).toUpperCase();
            
            this.ncv.rutCliente = cuerpo + '-'+ dv
          }
        }else{
          if (this.detallePago.rutPago != ''){
            this.detallePago.rutPago.replace('.','');
            var valor = this.detallePago.rutPago.replace('.','');
    
            valor = valor.replace('-','');
            
            var cuerpo = valor.slice(0,-1);
            var dv = valor.slice(-1).toUpperCase();
            
            this.detallePago.rutPago = cuerpo + '-'+ dv
          }
        }
      },
      confirmarNCV(){
        this.errors = [];

        for (const item in this.ncv){
          if(this.ncv[item] === "" || this.ncv[item] === 0){
            if(item == "numNCV" || item == "numBoleta" || item == "tienda"){
              this.errors.push(item);
            }
          }
        }

        var mensaje = "";
        
        if (this.errors.length != 0){
          this.errors.forEach(item => {
              if (item == "numNCV"){
                mensaje = mensaje + "El campo de N° de Nota de Crédito es requerido" + "\n"
              }else if(item == "numBoleta"){
                mensaje = mensaje + "El campo de N° de Boleta es requerido" + "\n"
              }else if(item == "tienda"){
                mensaje = mensaje + "El campo de Tienda es requerido" + "\n"
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

        if (this.errors.length === 0){
          if (this.tienda == false || this.administrador == true){
            var tienda = document.getElementById('selectTienda');
            this.nombreTienda = tienda.options[tienda.selectedIndex].text;
          }
          this.confirmarDatos = true;
          this.modoeditar = false;
        }
      },
      editarNCV(){
        this.modoeditar = true;
        this.confirmarDatos = false;
      },
      cerrarModal(){
          const elem = this.$refs.closeBtn;
          this.$emit('recarga');
          elem.click();
      },
      comprobarRol(roles){
        roles.forEach(rol => {
          if(rol == 'Administrador'){
            this.administrador = true;
          }
          if(rol == 'Tiendas'){
            this.tienda = true;
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
            this.cantidadDetalles = this.cantidadDetalles - 1;
          }
      },
      cancelarEditar(){
        this.modoEditarDetalle= false;
        this.detallePago.tipoPago = "";
        this.detallePago.banco = "";
        this.detallePago.tipoCuenta = "";
        this.detallePago.cuenta = "";
        this.detallePago.rutPago = "";

        this.idEditar = "";
      },
      editarDetalle(id){
        this.idEditar = "";
        this.idEditar = id;
        this.modoEditarDetalle = true;
        var detalle = this.detalles[id];
        this.detallePago.tipoPago = detalle.tipoPago;
        this.detallePago.banco = detalle.banco;
        this.detallePago.tipoCuenta = detalle.tipoCuenta;
        this.detallePago.cuenta = detalle.cuenta;
        this.detallePago.rutPago = detalle.rut;
      },
      guardarEdicion(id){

        this.errors = [];

        for (const item in this.detallePago){
          if(this.detallePago[item] === "" || this.detallePago[item] === 0){
            if (this.detallePago.tipoPago == "Transferencia"){
              this.errors.push(item);
            }else if(this.detallePago.tipoPago == "" && item == "tipoPago"){
              this.errors.push(item);
            }else{
            }
          }
        }

        if(this.detallePago.rutPago != ""){
          var validacion = this.validarRut(this.detallePago.rutPago)
          if(validacion == false){
            this.errors.push('rut invalido')
          }
        }

        var mensaje = ""
        if (this.errors.length != 0){
          this.errors.forEach(item => {
            if(item == 'rut invalido'){
                mensaje =   mensaje + "El Rut ingresado es invalido" + "\n" 
              }else if(item == 'tipoCuenta'){
                mensaje =   mensaje + "El campo Tipo de Cuenta es requerido" + "\n" 
              }else if(item == 'rutPago'){
                mensaje =   mensaje + "El campo Rut es requerido" + "\n" 
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
            idDetalle: this.cantidadDetalles ,
            tipoPago: this.detallePago.tipoPago,
            banco: this.detallePago.banco,
            tipoCuenta: this.detallePago.tipoCuenta,
            cuenta: this.detallePago.cuenta,
            rut: this.detallePago.rutPago,
          };
          this.detalles[id] = detalle;

          this.detallePago.tipoPago = "";
          this.detallePago.banco = "";
          this.detallePago.tipoCuenta = "";
          this.detallePago.cuenta = "";
          this.detallePago.rutPago = "";

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

          this.modoEditarDetalle = false;
        }
      },
      async crearNCV() {
        this.errors = [];

        for (const item in this.ncv){
          if (this.ncv.motivo == "Otro" && item == "otro"){
            this.errors.push(item);
          }
          if((this.ncv[item] === "" || this.ncv[item] === 0) && (item != "otro")){
            this.errors.push(item);
          }
        }

        if(this.ncv.rutCliente != ""){
          var validacion = this.validarRut(this.ncv.rutCliente)
          if(validacion == false){
            this.errors.push('rut invalido')
          }
        }

        var mensaje = ""
        if (this.errors.length != 0){
          this.errors.forEach(item => {
            if(item == 'rut invalido'){
                mensaje =   mensaje + "El Rut ingresado es invalido" + "\n" 
              }else if(item == 'numNCV'){
                mensaje =   mensaje + "El campo N° de Nota de Crédito es requerido" + "\n" 
              }else if(item == 'numBoleta'){
                mensaje =   mensaje + "El campo N° de Boleta es requerido" + "\n" 
              }else if(item == 'montoNCV'){
                mensaje =   mensaje + "El campo monto de Nota de Crédito es requerido" + "\n" 
              }else if(item == 'montoBoleta'){
                mensaje =   mensaje + "El campo monto de Boleta es requerido" + "\n" 
              }else if(item == 'cantidadArtNCV'){
                mensaje =   mensaje + "El campo cantidad de Artículos NCV es requerido" + "\n" 
              }else if(item == 'cantidadArtBoleta'){
                mensaje =   mensaje + "El campo cantidad de Artículos de Boleta es requerido" + "\n" 
              }else if(item == 'rutCliente'){
                mensaje =   mensaje + "El campo Rut del Cliente es requerido" + "\n" 
              }else if(item == 'nomCliente'){
                mensaje =   mensaje + "El campo Nombre del Cliente es requerido" + "\n" 
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

        if (this.cantidadDetalles == 0){
          this.errors.push('detalles')
          this.toast.warning('No puede crear una solicitud sin detalles', {
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
            title: 'Crear Nota de Crédito',
            message: `¿Está seguro/a que desea crear esta Nota de Crédito ? Esta acción no puede ser desecha.`,
            okButton: 'Crear',
            cancelButton: 'Volver'
          })
          if (ok) {
            const datos = []
            var motivoOtro = "";
            if(this.ncv.motivo == "Otro"){
              motivoOtro = this.ncv.motivo + ":" + this.ncv.otro;
            }else{
              motivoOtro = this.ncv.motivo;
            }
            let ncv = {
                fecha: this.ncv.fecha ,
                idTienda: this.ncv.tienda,
                idUsuario: this.ncv.usuario,
                notaCredito: this.ncv.numNCV,
                boleta: this.ncv.numBoleta,
                montoNotaCredito: this.ncv.montoNCV,
                montoBoleta: this.ncv.montoBoleta,
                cantidadArtNCV: this.ncv.cantidadArtNCV,
                cantidadArtBoleta: this.ncv.cantidadArtBoleta,
                rutCliente: this.ncv.rutCliente,
                nomCliente: this.ncv.nomCliente,
                motivo: motivoOtro,
            }

            datos.push(ncv)
            datos.push(this.detalles)
            axios.post("api/ncv", datos ).then((result) => {
              this.buttonDisable = true;
              this.buttonText = 'Guardando...';
              this.toast.success("Nota de Crédito creada con éxito!", {
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
              axios.post('api/ncv/sendEmail', datos).then( response => {
                  console.log(response.data);
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
            })
            .catch((error)=> {
              if (error.response.status == 422){
                this.errors = error.response.data.errors;
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
      agregarDetalle(){

        this.errors = [];

        for (const item in this.detallePago){
          if(this.detallePago[item] === "" || this.detallePago[item] === 0){
            if (this.detallePago.tipoPago == "Transferencia"){
              this.errors.push(item);
            }else if(this.detallePago.tipoPago == "" && item == "tipoPago"){
              this.errors.push(item);
            }else{
            }
          }
        }

        if(this.detallePago.rutPago != ""){
          var validacion = this.validarRut(this.detallePago.rutPago)
          if(validacion == false){
            this.errors.push('rut invalido')
          }
        }

        var mensaje = ""
        if (this.errors.length != 0){
          this.errors.forEach(item => {
            if(item == 'rut invalido'){
                mensaje =   mensaje + "El Rut ingresado es invalido" + "\n"
              }else if(item == 'tipoPago'){
                mensaje =   mensaje + "El campo Tipo de Pago es requerido" + "\n"  
              }else if(item == 'tipoCuenta'){
                mensaje =   mensaje + "El campo Tipo de Cuenta es requerido" + "\n" 
              }else if(item == 'rutPago'){
                mensaje =   mensaje + "El campo Rut es requerido" + "\n" 
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
            idDetalle: this.cantidadDetalles ,
            tipoPago: this.detallePago.tipoPago,
            banco: this.detallePago.banco,
            tipoCuenta: this.detallePago.tipoCuenta,
            cuenta: this.detallePago.cuenta,
            rut: this.detallePago.rutPago,
          };
          this.cantidadDetalles = this.cantidadDetalles + 1;
          this.detalles.push(detalle);

          this.detallePago.tipoPago = "";
          this.detallePago.banco = "";
          this.detallePago.tipoCuenta = "",
          this.detallePago.cuenta = "";
          this.detallePago.rutPago = "",

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
        axios.get(`api/dataNCV/${id}`).then( response =>{
          this.ncv.fecha = response.data[1];
          this.nombreUsuario = response.data[2];
          }).catch(e=> console.log(e))
      },
    }, 
}

</script>

<style>
.no-padding .form-control {
  padding-left: 0;
  padding-right: 0;
  margin-right: 0px; /* Ajusta la separación entre los input si lo deseas */
  margin-left: 0px;
}
</style>