<template>
    <transition name="modalcrear">
      <div name="modal">
        <div class="modal-mask">
            <div class="modal-wrapper">
              <div class="modal-container">
                <div class="modal-header" style="font-weight: bold; color: #444444;">
                  <slot name="header">
                    <label class="col-7 m-0" style="font-size: 17px;">Ingresar Cierre de Caja: </label>
                  </slot>
                  <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
                </div>
                <div class="modal-body">
                  <slot name="body">
                    <div class="form-group">
                      <div class="row">
                        <div class="text-uppercase">Información Básica:</div>
                        <div class="col-4">
                        <div class="form-group">
                            <label for="tienda">Tienda: </label>
                            <select :disabled="verificadoTienda" class="form-select" v-model="cierreCaja.idTienda">
                              <option disabled value="">Seleccione una tienda</option>
                              <option v-for="tienda in tiendas" v-bind:key="tienda.id" v-bind:value="tienda.id">
                                {{ tienda.nombre }}
                              </option>
                            </select>
                            <div v-if="errors && errors.idTienda" class="error">
                              {{ errors.idTienda[0] }}
                            </div>
                          </div>
                        </div>
                        <div class="col-2">
                          <div class="form-group">
                            <label for="diaCierre">Fecha Día Cierre: </label>
                            <input type="date" class= "form-control" v-model="cierreCaja.diaCierre">
                          </div>
                          <div v-if="errors && errors.diaCierre" class="error">
                            {{ errors.diaCierre[0] }}
                          </div>
                        </div>
                        <div class="col-4">
                          <div class="form-group">
                            <label for="imagenVoucher">Imagen Voucher: </label>
                            <input type="file" accept=".jpg, .jpeg, .png, .heif, .heic" id="imagenVoucher" class= "form-control" @change="getImage">
                          </div>
                          <div v-if="errors && errors.imagenVoucher" class="error">
                            {{ errors.imagenVoucher[0] }}
                          </div>
                        </div>
                        <div v-if="fotoMiniaturaVoucher != '' " class="col-2">
                          <figure>
                            <img width="50" height="50" :src="imagen" alt="Foto Miniatura">
                          </figure>
                        </div>
                        <div class="col-6">
                          <div class="form-group">
                            <label for="responsableCierre">Responsable Cierre: </label>
                            <input type="text" class= "form-control" v-model="cierreCaja.responsableCierre">
                          </div>
                          <div v-if="errors && errors.responsableCierre" class="error">
                            {{ errors.responsableCierre[0] }}
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="form-group">
                            <label for="observacionCierre">Observación Cierre: </label>
                            <textarea type="text" class= "form-control" v-model="cierreCaja.observacionCierre" style="min-width: 200px; height: 40px; text-align: justify;"></textarea> 
                          </div>
                        </div>
                        <hr size="4" class="separador">
                        <div class="text-uppercase">Detalle de Ventas:</div>
                        <div class="col-3">
                          <div class="form-group">
                            <label for="tarjeta">Tarjeta: </label>
                            <input type="number" class= "form-control" v-model="cierreCaja.tarjeta">
                          </div>
                        </div>
                        <div class="col-3">
                          <div class="form-group">
                            <label for="transferencia">Transferencia: </label>
                            <input type="number" class= "form-control" v-model="cierreCaja.transferencia">
                          </div>
                        </div>
                        <div class="col-3">
                          <div class="form-group">
                            <label for="efectivo">Efectivo: </label>
                            <input type="number" class= "form-control" v-model="cierreCaja.efectivo">
                          </div>
                        </div>
                        <div class="col-3">
                          <div class="form-group">
                            <label for="notaCredito">Nota de Crédito: </label>
                            <input type="number" class= "form-control" v-model="cierreCaja.notaCredito">
                          </div>
                        </div>
                        <div class="col-2">
                          <div class="form-group">
                            <label for="fondoFijo">Fondo Fijo: </label>
                            <input type="number" class= "form-control" v-model="cierreCaja.fondoFijo">
                          </div>
                        </div>
                        <div v-if="cierreCaja.fondoFijo > 0" class="col-4">
                          <div class="form-group">
                            <label>Referencia de Fondo Fijo: </label>
                            <!-- <Multiselect
                                placeholder="Seleccione una o más opciones:"
                                v-model="referenciasFondosFijos"
                                limit=10
                                :searchable="true"
                                :close-on-select="false"
                                :createTag="true"
                                :options="options2"
                                mode="tags"
                                label='id'
                                trackBy='id'
                                :object="true"
                            />
                            <p> {{ referenciasFondosFijos }} </p>
                            <p> {{ fofis }} </p>
                            <p> {{ fondosFijos }} </p>
                            <p> {{ options2 }} </p> -->
                            <select class="form-select" v-model="cierreCaja.idRefFondoFijo">
                            <option :value="null">Seleccione referencia de Fondo Fijo: </option>
                            <option v-for="fondoFijo1 in fondosFijos" :key="fondoFijo1.id" v-bind:value="fondoFijo1.id">
                            <label>Folio: {{ fondoFijo1.folioFofi }}, Monto Total: {{ fondoFijo1.montoTotal }}</label>
                            </option>
                            </select>
                          </div>
                          <div v-if="errors && errors.idRefFondoFijo" class="error">
                            {{ errors.idRefFondoFijo[0] }}
                          </div>
                        </div>
                        <div class="col-2">
                          <div class="form-group">
                            <label for="cheque">Cheque: </label>
                            <input type="number" class= "form-control" v-model="cierreCaja.cheque">
                          </div>
                        </div>
                        <div class="col-2">
                          <div class="form-group">
                            <label for="cornershop">Cornershop: </label>
                            <input type="number" class= "form-control" v-model="cierreCaja.cornershop">
                          </div>
                        </div>
                        <div class="col-2">
                          <div class="form-group">
                            <label for="rappi">Rappi: </label>
                            <input type="number" class= "form-control" v-model="cierreCaja.rappi">
                          </div>
                        </div>
                        <div class="col-2">
                          <div class="form-group">
                            <label for="personal">Personal: </label>
                            <input type="number" class= "form-control" v-model="cierreCaja.personal">
                          </div>
                        </div>
                        <div class="col-2">
                          <div class="form-group">
                            <label for="marinaOnline">Marina Online: </label>
                            <input type="number" class= "form-control" v-model="cierreCaja.marinaOnline">
                          </div>
                        </div>
                        <div class="col-2">
                          <div class="form-group">
                            <label for="vale">Vale: </label>
                            <input type="number" class= "form-control" v-model="cierreCaja.vale">
                          </div>
                        </div>
                        <hr size="4" class="separador">
                        <div class="text-uppercase">Resumen de Ventas:</div>
                        <div class="col-6">
                          <div class="form-group">
                            <label for="ventaBruta">Venta Bruta: </label>
                            <input disabled type="number" class= "form-control" :value="this.cierreCaja.tarjeta + this.cierreCaja.efectivo + this.cierreCaja.transferencia + this.cierreCaja.notaCredito + this.cierreCaja.cheque + this.cierreCaja.cornershop + this.cierreCaja.rappi + this.cierreCaja.personal + this.cierreCaja.marinaOnline + this.cierreCaja.vale">
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="form-group">
                            <label for="ventaTotal">Venta Total: </label>
                            <input disabled type="number" class= "form-control" :value="this.cierreCaja.tarjeta + this.cierreCaja.efectivo + this.cierreCaja.transferencia - this.cierreCaja.notaCredito + this.cierreCaja.cheque + this.cierreCaja.cornershop + this.cierreCaja.rappi + this.cierreCaja.personal + this.cierreCaja.marinaOnline + this.cierreCaja.vale">
                          </div>
                        </div>
                        <hr size="4" class="separador">
                        <div v-if="cierreCaja.efectivo > 0" class="form-check">
                          <label class="form-check-label"><input type="checkbox" class="form-check-input" v-model="depositoEstado">
                            Agregar Depósito 
                          </label>
                        </div>
                        <div v-if="depositoEstado == true">
                          <hr size="4" class="separador">
                          <div class="row">
                            <div class="text-uppercase">Información del Depósito:</div>
                            <div class="col-4">
                              <div class="form-group">
                                <label for="deposito">Depósito: </label>
                                <input type="number" class= "form-control" v-model="cierreCaja.deposito">
                              </div>
                              <div v-if="errors && errors.deposito" class="error">
                                {{ errors.deposito[0] }}
                              </div>
                            </div>
                            <div class="col-2">
                              <div class="form-group">
                                <label for="fechaDeposito">Fecha Depósito: </label>
                                <input type="date" class= "form-control" v-model="cierreCaja.fechaDeposito">
                              </div>
                              <div v-if="errors && errors.fechaDeposito" class="error">
                                {{ errors.fechaDeposito[0] }}
                              </div>
                            </div>
                            <div class="col-4">
                              <div class="form-group">
                                <label for="imagenDeposito">Imagen Depósito: </label>
                                <input type="file" accept=".jpg, .jpeg, .png, .heif, .heic" id="imagenDeposito" class= "form-control" @change="getImage2">
                              </div>
                              <div v-if="errors && errors.imagenDeposito" class="error">
                                {{ errors.imagenDeposito[0] }}
                              </div>
                            </div>
                            <div v-if="fotoMiniaturaDeposito != '' " class="col-2">
                              <figure>
                                <img width="50" height="50" :src="imagen2" alt="Foto Miniatura">
                              </figure>
                            </div>
                            <div class="col-12">
                              <div class="form-group">
                                <label for="observacionDeposito">Observación Depósito: </label>
                                <textarea type="text" class= "form-control" v-model="cierreCaja.observacionDeposito" style="min-width: 200px; height: 40px; text-align: justify;"></textarea> 
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </slot>
                </div>
                <div class="modal-footer">
                <slot name="footer">
                  <button class="btn btn-alert float-end" @click="ingresarCierreCajas();" :disabled="buttonDisable">Ingresar</button>
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
import Compressor from 'compressorjs';
import Multiselect from '@vueform/multiselect';

export default {
  components: { modalconfirmacion, modalalerta, Multiselect },
  mixins: [mixin],
  data: () => ({
    cierreCaja: {
      idTienda: null,
      diaCierre: null,
      imagenDeposito: null,
      imagenVoucher: null,
      responsableCierre: null,
      observacionCierre: null,
      tarjeta: 0,
      transferencia: 0,
      efectivo: 0,
      notaCredito: 0,
      fondoFijo: 0,
      idRefFondoFijo: null,
      cheque: 0,
      cornershop: 0,
      rappi: 0,
      personal: 0,
      marinaOnline: 0,
      vale: 0,
      deposito: null,
      fechaDeposito: null,
      observacionDeposito: null,
    },
// ######################################
    options2: [
        { id: '1', value: 'captainamerica', name: 'Captain America'},
        { id: '2', value: 'spiderman', name: 'Spiderman'},
        { id: '3', value: 'ironman', name: 'Iron Man'},
        { id: '4', value: 'wanda', name: 'Wanda'},
        { id: '5', value: 'loki', name: 'Loki'},
        { id: '6', value: 'thorodinson', name: 'Thor Odinson'},
        { id: '7', value: 'hawkeye', name: 'Hawkeye'},
        { id: '8', value: 'natasharomanov', name: 'Natasha Romanov'},
    ],
    referenciasFondosFijos: [],
    fofis: [],
// ######################################
    depositoEstado: null,
    tiendas: [],
    fotoMiniaturaDeposito: "",
    fotoMiniaturaVoucher: "",
    buttonText:'Ingresar Cierre de Caja',
    buttonDisable: false,
    fondosFijos: [],
    errors: [],
    verificadoTienda: false,
  }),
  props:{
  },
  created(){
    this.getTiendas();
    this.getCierresCajas();
    this.getFondosFijos();
  },
  methods: {
    cerrarModal(){
      const elem = this.$refs.closeBtn;
      this.$emit('recarga');
      elem.click();
    },
    getCierresCajas(){
      axios.get('api/cierreCajas').then( response =>{
          this.cierresCajas = response.data;
      }).catch(e=> console.log(e))
    },
    getTiendas(){
      axios.get('api/getTiendas').then( response =>{
        this.tiendas = response.data;
        this.tiendas.forEach(tienda => {
          if(tienda.idUsuario == this.userID){
            this.verificadoTienda = true;
            this.cierreCaja.idTienda = tienda.id;
          }
        })
      }).catch(e=> console.log(e))
    },
    getFondosFijos(){
      axios.get('api/fofis').then( response =>{
        var fondosFijosTemp = response.data.filter((dato)=>dato.estado == 'Aprobado');
        if (this.verificadoTienda == true){
          fondosFijosTemp.forEach(fondoFijo => {
            if(fondoFijo.idUsuario == this.userID){
              this.fondosFijos.push(fondoFijo);
            }
          })
        }
        else{
          this.fondosFijos = fondosFijosTemp;
        }
        this.fondosFijos.forEach(fondoFijo => {
          this.fofis.push({id: fondoFijo.id, montoTotal: fondoFijo.montoTotal});
        });
      }).catch(e=> console.log(e))
    },
    async ingresarCierreCajas() {
      var aDepositar = this.formatMoneyCLP(this.cierreCaja.efectivo - this.cierreCaja.fondoFijo);
      if(this.depositoEstado == true){
        if (this.cierreCaja.deposito > (this.cierreCaja.efectivo - this.cierreCaja.fondoFijo) ) {
          this.toast.error(`El monto no puede ser superior al monto esperado (${aDepositar})!`, {
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
        else{
          if (this.cierreCaja.deposito < (this.cierreCaja.efectivo - this.cierreCaja.fondoFijo) ) {
            this.toast.warning(`El monto ingresado es menor al monto que debiese depositar (${aDepositar})!`, {
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
          const ok = await this.$refs.confirmation.show({
            title: 'Ingresar Cierre Cajas',
            message: `¿Está seguro/a que desea ingresar el cierre de caja? Esta acción no puede ser desecha.`,
            okButton: 'Ingresar',
            cancelButton: 'Volver'
          })
          if (ok) {
            var difDiasTemporal = null;
            if (this.cierreCaja.fechaDeposito != null) {
              difDiasTemporal = this.distanceFromOther(this.cierreCaja.diaCierre,this.cierreCaja.fechaDeposito);
            }
            let cierreCaja2 = {
              idTienda: this.cierreCaja.idTienda,
              diaCierre: this.cierreCaja.diaCierre,
              imagenVoucher: this.cierreCaja.imagenVoucher,
              responsableCierre: this.cierreCaja.responsableCierre,
              observacionCierre: this.cierreCaja.observacionCierre,
              tarjeta: this.cierreCaja.tarjeta,
              transferencia: this.cierreCaja.transferencia,
              efectivo: this.cierreCaja.efectivo,
              notaCredito: this.cierreCaja.notaCredito,
              idRefFondoFijo: this.cierreCaja.idRefFondoFijo,
              fondoFijo: this.cierreCaja.fondoFijo,
              cheque: this.cierreCaja.cheque,
              cornershop: this.cierreCaja.cornershop,
              rappi: this.cierreCaja.rappi,
              personal: this.cierreCaja.personal,
              marinaOnline: this.cierreCaja.marinaOnline,
              vale: this.cierreCaja.vale,
              deposito: this.cierreCaja.deposito,
              imagenDeposito: this.cierreCaja.imagenDeposito,
              fechaDeposito: this.cierreCaja.fechaDeposito,
              observacionDeposito: this.cierreCaja.observacionDeposito,
              diferenciaDias: difDiasTemporal,
              control: (this.cierreCaja.efectivo - this.cierreCaja.fondoFijo) - this.cierreCaja.deposito,
              ventaBruta: this.cierreCaja.tarjeta + this.cierreCaja.efectivo + this.cierreCaja.transferencia + this.cierreCaja.notaCredito + this.cierreCaja.cheque + this.cierreCaja.cornershop + this.cierreCaja.rappi + this.cierreCaja.personal + this.cierreCaja.marinaOnline + this.cierreCaja.vale,
              ventaTotal: this.cierreCaja.tarjeta + this.cierreCaja.efectivo + this.cierreCaja.transferencia - this.cierreCaja.notaCredito + this.cierreCaja.cheque + this.cierreCaja.cornershop + this.cierreCaja.rappi + this.cierreCaja.personal + this.cierreCaja.marinaOnline + this.cierreCaja.vale,
              depositoEstado: this.depositoEstado,
            };
            axios.post("api/cierreCajas", cierreCaja2, {headers: { 'Content-Type' : 'multipart/form-data' }}).then((result) => {
              this.buttonDisable = true;
              this.buttonText = 'Guardando...';
              this.toast.success("Cierre de Caja ingresada con éxito!", {
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
              if(this.cierreCaja.idRefFondoFijo){
                let datos = {
                  fondoFijoID: this.cierreCaja.idRefFondoFijo,
                }
                axios.post('api/fofis/sendEmailEstadoActualizado', datos).then( response => {
                  this.toast.success("Correo de actualización de Fondo Fijo enviado!", {
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
                }).catch(e=> {console.log(e)});
              }
              setTimeout(() => {location.reload();}, 1500);
            })
            .catch((error)=> {
              if (error.response.status == 422){
                this.errors = error.response.data.errors;
              }
            });
          }
        }
      }
      else{
        const ok = await this.$refs.confirmation.show({
          title: 'Ingresar Cierre Cajas',
          message: `¿Está seguro/a que desea ingresar el cierre de caja? Esta acción no puede ser desecha.`,
          okButton: 'Ingresar',
          cancelButton: 'Volver'
        })
        if (ok) {
          var difDiasTemporal = null;
          if (this.cierreCaja.fechaDeposito != null) {
            difDiasTemporal = this.distanceFromOther(this.cierreCaja.diaCierre,this.cierreCaja.fechaDeposito);
          }
          let cierreCaja2 = {
            idTienda: this.cierreCaja.idTienda,
            diaCierre: this.cierreCaja.diaCierre,
            imagenVoucher: this.cierreCaja.imagenVoucher,
            responsableCierre: this.cierreCaja.responsableCierre,
            observacionCierre: this.cierreCaja.observacionCierre,
            tarjeta: this.cierreCaja.tarjeta,
            transferencia: this.cierreCaja.transferencia,
            efectivo: this.cierreCaja.efectivo,
            notaCredito: this.cierreCaja.notaCredito,
            idRefFondoFijo: this.cierreCaja.idRefFondoFijo,
            fondoFijo: this.cierreCaja.fondoFijo,
            cheque: this.cierreCaja.cheque,
            cornershop: this.cierreCaja.cornershop,
            rappi: this.cierreCaja.rappi,
            personal: this.cierreCaja.personal,
            marinaOnline: this.cierreCaja.marinaOnline,
            vale: this.cierreCaja.vale,
            deposito: this.cierreCaja.deposito,
            imagenDeposito: this.cierreCaja.imagenDeposito,
            fechaDeposito: this.cierreCaja.fechaDeposito,
            observacionDeposito: this.cierreCaja.observacionDeposito,
            diferenciaDias: difDiasTemporal,
            control: (this.cierreCaja.efectivo - this.cierreCaja.fondoFijo) - this.cierreCaja.deposito,
            ventaBruta: this.cierreCaja.tarjeta + this.cierreCaja.efectivo + this.cierreCaja.transferencia + this.cierreCaja.notaCredito + this.cierreCaja.cheque + this.cierreCaja.cornershop + this.cierreCaja.rappi + this.cierreCaja.personal + this.cierreCaja.marinaOnline + this.cierreCaja.vale,
            ventaTotal: this.cierreCaja.tarjeta + this.cierreCaja.efectivo + this.cierreCaja.transferencia - this.cierreCaja.notaCredito + this.cierreCaja.cheque + this.cierreCaja.cornershop + this.cierreCaja.rappi + this.cierreCaja.personal + this.cierreCaja.marinaOnline + this.cierreCaja.vale,
            depositoEstado: this.depositoEstado,
          };
          axios.post("api/cierreCajas", cierreCaja2, {headers: { 'Content-Type' : 'multipart/form-data' }}).then((result) => {
            this.buttonDisable = true;
            this.buttonText = 'Guardando...';
            this.toast.success("Cierre de Caja ingresada con éxito!", {
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
            if(this.cierreCaja.idRefFondoFijo){
              let datos = {
                fondoFijoID: this.cierreCaja.idRefFondoFijo,
              }
              axios.post('api/fofis/sendEmailEstadoActualizado', datos).then( response => {
                this.toast.success("Correo de actualización de Fondo Fijo enviado!", {
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
              }).catch(e=> {console.log(e)});
            }
            setTimeout(() => {this.cerrarModal();}, 1500);
          })
          .catch((error)=> {
            if (error.response.status == 422){
              this.errors = error.response.data.errors;
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
    // Funcion para extraer la imagen del input de foto
    async getImage(e){
      this.cierreCaja.imagenVoucher = e.target.files[0];
      let compression = [];
      await this.compressImages(this.cierreCaja.imagenVoucher,compression);
      var file = new File([compression[0]], compression[0]['name'], {
      type: compression[0]['type'],});
      this.cierreCaja.imagenVoucher = file;
      this.chargeImage(this.cierreCaja.imagenVoucher);
    },
    async getImage2(e){
      this.cierreCaja.imagenDeposito = e.target.files[0];
      let compression = [];
      await this.compressImages(this.cierreCaja.imagenDeposito,compression);
      var file = new File([compression[0]], compression[0]['name'], {
      type: compression[0]['type'],});
      this.cierreCaja.imagenDeposito = file;
      this.chargeImage2(this.cierreCaja.imagenDeposito);
    },
    // Funcion para cargar la imagen como miniatura
    chargeImage(file){
      let reader = new FileReader();
      reader.onload = (e) => { 
        this.fotoMiniaturaVoucher = e.target.result;
      }
      reader.readAsDataURL(file);
    },
    chargeImage2(file){
      let reader = new FileReader();
      reader.onload = (e) => { 
        this.fotoMiniaturaDeposito = e.target.result;
      }
      reader.readAsDataURL(file);
    },
  },
  computed: {
    imagen(){
        return this.fotoMiniaturaVoucher;
    },
    imagen2(){
        return this.fotoMiniaturaDeposito;
    }
  }
}
</script>