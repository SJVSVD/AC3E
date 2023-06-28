<template>
  <transition name="modalcrear">
    <div name="modal">
      <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container">
              <div class="modal-header pb-1" style="font-weight: bold; color: #444444;">
                <slot name="header">
                  <label class="col-5 m-0" style="font-size: 17px;">Agregar Detalle a Nota de Crédito: </label>
                  <label class="col-5 m-0 fs-8 pt-2"> Usuario: <label class="fw-normal" style="font-size: 14px;">{{ ncv.usuario.name }}</label></label>
                </slot>
                <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
              </div>
              <div class="modal-body">
                <slot name="body">
                  <div class="form-group">
                    <div class="row">
                      <div class="text-uppercase pb-2">Información General:</div>
                      <label class="col-3 m-0 fs-8"> Tienda: <label class="fw-normal" style="font-size: 14px;"> {{ ncv.tienda.nombre }}</label></label>
                      <label class="col-3 m-0 fs-8"> NºNCV: <label class="fw-normal" style="font-size: 14px;">{{ ncv.notaCredito }}</label></label>
                      <label class="col-3 m-0 fs-8"> Monto NCV: <label class="fw-normal" style="font-size: 14px;">{{ formatMoneyCLP(ncv.montoNotaCredito) }}</label></label>
                      <label class="col-3 m-0 fs-8"> NºBoleta: <label class="fw-normal" style="font-size: 14px;">{{ ncv.boleta }}</label></label>
                      <label class="col-3 m-0 fs-8"> Monto Boleta: <label class="fw-normal" style="font-size: 14px;">{{ formatMoneyCLP(ncv.montoBoleta) }}</label></label>
                      <label class="col-3 m-0 fs-8"> Rut Cliente: <label class="fw-normal" style="font-size: 14px;">{{ ncv.rutCliente }}</label></label>
                      <label class="col-5 m-0 fs-8"> Nombre Cliente: <label class="fw-normal" style="font-size: 14px;">{{ ncv.nomCliente }}</label></label>
                      <label class="col-5 m-0 fs-8"> Motivo: <label class="fw-normal" style="font-size: 14px;">
                        <select @change="selectMotivo()" class="form-select" v-model="ncv2.motivo">
                            <option disabled value="">Seleccione un motivo</option>
                            <optgroup label="Decisión del cliente">
                              <option value="Por gusto">Por gusto</option>
                              <option value="Características diferentes">Características diferentes</option>
                              <option value="No cumple las expectativas">No cumple las expectativas</option>
                            </optgroup>
                            <optgroup label="Error">
                              <option value="Emisión de boleta por error">Emisión de boleta por error</option>
                            </optgroup>
                            <optgroup label="Por falla">
                              <option value="Falla de material">Falla de material</option>
                              <option value="Mal manufacturado">Mal manufacturado</option>
                              <option value="Articulo sucio">Articulo sucio</option>
                              <option value="Fruncidos de costuras">Fruncidos de costuras</option>
                              <option value="Daño por plastiflechas o sensores">Daño por plastiflechas o sensores</option>
                              <option value="Presencia de hongos">Presencia de hongos</option>
                              <option value="Mal olor">Mal olor</option>
                              <option value="Perdida de accesorios">Perdida de accesorios</option>
                              <option value="Perdida de accesorios metalizados">Perdida de accesorios metalizados</option>
                            </optgroup>
                            </select></label></label>
                      <label class="col-4 m-0 fs-8"> Tipo Pago: <label class="fw-normal" style="font-size: 14px;">{{ ncv.metodoPago }}</label></label>
                      <hr size="4" class="separador">
                      <div class="text-uppercase pb-2">Productos:</div>
                      <div class="row pb-2">
                        <table id="tableDesign" class="table align-items-center mb-0">
                          <thead>
                            <tr>
                              <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                              <th class="text-uppercase text-xs font-weight-bolder">Código</th>
                              <th class="text-uppercase text-xs font-weight-bolder">Nombre</th>
                              <th class="text-uppercase text-xs font-weight-bolder">Cantidad</th>
                              <th class="text-uppercase text-xs font-weight-bolder">Precio</th>
                              <th class="text-uppercase text-xs font-weight-bolder">Numero Linea</th>
                              <th class="text-uppercase text-xs font-weight-bolder">Motivo</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-if="ncv.productos.length == 0">
                              <td colspan="10"> No hay información disponible. </td>
                            </tr>
                            <tr v-else v-for="(producto,index) in ncv.productos" :key="producto">
                              <td><p class="text-sm mb-0"> {{ index + 1 }}  </p></td>
                              <td><p class="text-sm mb-0"> {{ producto.codigo }} </p></td>
                              <td><p class="text-sm mb-0"> {{ producto.descripcion }} </p></td>
                              <td><p class="text-sm mb-0"> {{ producto.cantidad }} </p></td>
                              <td><p class="text-sm mb-0"> {{ formatMoneyCLP(producto.precio)}} </p></td>
                              <td><p class="text-sm mb-0"> {{ producto.nroLinea}} </p></td>
                              <td><p class="text-sm mb-0"><select class="form-select" v-model="producto.motivo">
                            <option disabled :value="null">Seleccione un motivo</option>
                            <optgroup label="Decisión del cliente">
                              <option value="Por gusto">Por gusto</option>
                              <option value="Características diferentes">Características diferentes</option>
                              <option value="No cumple las expectativas">No cumple las expectativas</option>
                            </optgroup>
                            <optgroup label="Error">
                              <option value="Emisión de boleta por error">Emisión de boleta por error</option>
                            </optgroup>
                            <optgroup label="Por falla">
                              <option value="Falla de material">Falla de material</option>
                              <option value="Mal manufacturado">Mal manufacturado</option>
                              <option value="Articulo sucio">Articulo sucio</option>
                              <option value="Fruncidos de costuras">Fruncidos de costuras</option>
                              <option value="Daño por plastiflechas o sensores">Daño por plastiflechas o sensores</option>
                              <option value="Presencia de hongos">Presencia de hongos</option>
                              <option value="Mal olor">Mal olor</option>
                              <option value="Perdida de accesorios">Perdida de accesorios</option>
                              <option value="Perdida de accesorios metalizados">Perdida de accesorios metalizados</option>
                            </optgroup>
                            </select> </p></td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                      <br>
                      <hr size="4" class="separador">
                      <div class="text-uppercase pb-2">Detalles Pagos:</div>
                      <div v-if="ncv.credito == false">
                      <form enctype="multipart/form-data" class="pb-2">
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
                      <a v-if="modoEditarDetalle == false" @click=agregarDetalle() class="btn btn-dark-grey float-right pb-2">Agregar pago</a>
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
                  </div>
                  <div v-else>
                    <div class="row">
                      <table id="tableDesign" class="table align-items-center mb-0">
                        <thead>
                          <tr>
                            <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                            <th class="text-uppercase text-xs font-weight-bolder">Tipo Pago</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr>
                            <td><p class="text-sm mb-0">1</p></td>
                            <td><p class="text-sm mb-0">Devolución Credito</p></td>
                          </tr>
                        </tbody>
                      </table>
                  </div>
                  </div>
                  </div>
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
import axios from 'axios'
import modalconfirmacion from '../../../sistema/alerts/confirmationModal.vue'
import modalalerta from '../../../sistema/alerts/alertModal.vue'
import {mixin} from '../../../../../mixins.js'

let user = document.head.querySelector('meta[name="user"]');
let roles= document.head.querySelector('meta[name="roles"]');

export default {
  components: { modalconfirmacion, modalalerta },
  mixins: [mixin],
  data: () => ({
        ncv2:{
          motivo: "",
        },
        detallePago:{
          tipoPago: "",
          banco: "",
          tipoCuenta: "",
          cuenta: "",
          rutPago: "",
        },  
        buttonText:'Agregar Detalle',
        buttonDisable: false,
        detalles:[],
        errors:[],
        errorsTipo:[],
        modoEditarDetalle: false,
        idEditar:"",
        bancos: [],
        tiposCuentaBanco: [],
        cantidadDetalles: 0,
    }),
    props:{
      ncv: Object,
    },
    created(){
      this.getBancos();
      this.getTiposCuenta();
    },
    methods: {
      selectMotivo(){
        this.ncv.productos.forEach(producto => {
          producto.motivo = this.ncv2.motivo;
        });
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
      capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
      },
      validateInput() {
          this.detallePago.rutPago = this.detallePago.rutPago.replace(/[^0-9\-]/g, '').replace(/(\-)\-+/g, '$1');
      },
      isNumberOrDash(event) {
        const charCode = event.which ? event.which : event.keyCode;
        if (charCode === 45 && this.detallePago.rutPago.indexOf('-') !== -1  ) {
          event.preventDefault();
        } else if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode !== 45) {
          event.preventDefault();
        }
      },
      checkRut() {
          if (this.detallePago.rutPago != ''){
            this.detallePago.rutPago.replace('.','');
            var valor = this.detallePago.rutPago.replace('.','');
    
            valor = valor.replace('-','');
            
            var cuerpo = valor.slice(0,-1);
            var dv = valor.slice(-1).toUpperCase();
            
            this.detallePago.rutPago = cuerpo + '-'+ dv
          }
      },
      cerrarModal(){
        const elem = this.$refs.closeBtn;
        this.$emit('recarga');
        elem.click();
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

        for (const item in this.ncv2){
          if((this.ncv2[item] === "" || this.ncv[item] === 0)){
            this.errors.push(item);
          }
        }

        var faltante = false;

        this.ncv.productos.forEach(producto => {
          if(producto.motivo == null && faltante == false){
            this.errors.push('motivoProducto');
            faltante = true;
          }
        });

        var mensaje = ""
        if (this.errors.length != 0){
          this.errors.forEach(item => {
            if(item == 'motivoProducto'){
                mensaje =   mensaje + "El campo Motivo en cada producto es requerido" + "\n" 
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

        if (this.cantidadDetalles == 0 && this.ncv.credito == false){
          this.errors.push('detalles')
          this.toast.warning('No puede actualizar una Nota de Crédito sin detalles ', {
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
            title: 'Agregar Detalle a Nota de Crédito',
            message: `¿Está seguro/a que desea agregar detalle a esta Nota de Crédito ? Esta acción no puede ser desecha.`,
            okButton: 'Agregar',
            cancelButton: 'Volver'
          })
          if (ok) {
            const datos = []

            let ncv = {
                estado: 'Procesado',
                motivo: this.ncv2.motivo,
            }

            datos.push(ncv);
            datos.push(this.detalles);
            axios.put(`api/ncv/${this.ncv.id}`, datos).then((result) => {
              this.buttonDisable = true;
              this.buttonText = 'Guardando...';
              this.toast.success("Detalle agregado con éxito!", {
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
              // setTimeout(() => {this.cerrarModal();}, 1500);
            })
            axios.post('api/ncv/editarProductos', this.ncv.productos).then((result) => {
              console.log(result.data);
              this.toast.success("Productos modificados con éxito!", {
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
    },
}
</script>