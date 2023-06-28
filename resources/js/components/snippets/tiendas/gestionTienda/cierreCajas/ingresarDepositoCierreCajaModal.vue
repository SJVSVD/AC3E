<template>
    <transition name="modalcrear">
      <div name="modal">
        <div class="modal-mask">
            <div class="modal-wrapper">
              <div class="modal-container-xl">
                <div class="modal-header" style="font-weight: bold; color: #444444;">
                  <slot name="header">
                    <label class="col-7 m-0" style="font-size: 17px;">Ingresar Depósito: </label>
                  </slot>
                  <a class="btn btn-closed" @click="$emit('close')">X</a>
                </div>
                <div class="modal-body">
                  <slot name="body">
                    <div class="form-group">
                      <div class="text-uppercase">Detalle del Cierre de Caja:</div>
                      <table id="tableDesign" class="table align-items-center mb-0">
                        <thead>
                          <tr style="color: black">
                            <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                            <th class="text-uppercase text-xs font-weight-bolder">Tienda</th>
                            <th class="text-uppercase text-xs font-weight-bolder">Fecha Día Cierre</th>
                            <th class="text-uppercase text-xs font-weight-bolder">Tarjeta</th>
                            <th class="text-uppercase text-xs font-weight-bolder">Efectivo</th>
                            <th class="text-uppercase text-xs font-weight-bolder">Fondo Fijo</th>
                            <th class="text-uppercase text-xs font-weight-bolder">Nota de Crédito</th>
                            <th class="text-uppercase text-xs font-weight-bolder">Otros Montos</th>
                            <th class="text-uppercase text-xs font-weight-bolder">Venta Total</th>
                            <th class="text-uppercase text-xs font-weight-bolder">Venta Bruta</th>
                            <th class="text-uppercase text-xs font-weight-bolder">Depósito</th>
                            <th class="text-uppercase text-xs font-weight-bolder">Fecha Depósito</th>
                            <th class="text-uppercase text-xs font-weight-bolder">Diferencia Días</th>
                            <th class="text-uppercase text-xs font-weight-bolder">Control</th>
                        </tr>
                        </thead>
                        <tbody>
                          <tr v-if="cantidadDetalles == 0">
                            <td colspan="13"> No hay información disponible. </td>
                          </tr>
                          <tr>
                              <td>
                                  <p class="text-sm font-weight-bolder mb-0" style="color: black">{{ cierreCaja.id }}</p>
                              </td>
                              <td>
                                  <p class="text-sm mb-0">{{ cierreCaja.tienda.nombre }}</p>
                              </td>
                              <td>
                                  <p class="text-sm mb-0">{{ thisDate(cierreCaja.diaCierre) }}</p>
                              </td>
                              <td>
                                  <p class="text-sm mb-0">{{ formatMoneyCLP(cierreCaja.tarjeta) }}</p>
                              </td>
                              <td>
                                  <p class="text-sm mb-0">{{ formatMoneyCLP(cierreCaja.efectivo) }}</p>
                              </td>
                              <td>
                                  <p class="text-sm mb-0">{{ formatMoneyCLP(cierreCaja.fondoFijo) }}</p>
                              </td>
                              <td>
                                  <p class="text-sm mb-0">{{ formatMoneyCLP(cierreCaja.notaCredito) }}</p>
                              </td>
                              <td>
                                  <p class="text-sm mb-0">{{ formatMoneyCLP(cierreCaja.transferencia + cierreCaja.cheque + cierreCaja.cornershop + cierreCaja.rappi + cierreCaja.personal + cierreCaja.marinaOnline + cierreCaja.vale) }}</p>
                              </td>
                              <td>
                                  <p class="text-sm mb-0">{{ formatMoneyCLP(cierreCaja.ventaTotal) }}</p>
                              </td>
                              <td>
                                  <p class="text-sm mb-0">{{ formatMoneyCLP(cierreCaja.ventaBruta) }}</p>
                              </td>
                              <td>
                                  <p class="text-sm mb-0">{{ formatMoneyCLP(cierreCaja.deposito) }}</p>
                              </td>
                              <td>
                                  <p class="text-sm mb-0">{{ thisDate(cierreCaja.fechaDeposito) }}</p>
                              </td>
                              <!-- <td>
                                  <p v-if="cierreCaja.diferenciaDias != null && cierreCaja.diferenciaDias >= 0 && cierreCaja.diferenciaDias <= 3" class="text-sm mb-0" style="color: black">{{ cierreCaja.diferenciaDias }} Días.</p>
                                  <p v-if="cierreCaja.diferenciaDias != null && cierreCaja.diferenciaDias < 8 && cierreCaja.diferenciaDias >= 4" class="text-sm mb-0" style="color: white; background-color: #FFC300;">{{ cierreCaja.diferenciaDias }} Días.</p>
                                  <p v-if="cierreCaja.diferenciaDias != null && cierreCaja.diferenciaDias < 14 && cierreCaja.diferenciaDias >= 8" class="text-sm mb-0" style="color: white; background-color: #FB4C24;">{{ cierreCaja.diferenciaDias }} Días.</p>
                                  <p v-if="cierreCaja.diferenciaDias != null && cierreCaja.diferenciaDias < 0 || cierreCaja.diferenciaDias >= 14" class="text-sm mb-0" style="color: white; background-color: #D21610;">{{ cierreCaja.diferenciaDias }} Días.</p>
                                  <p v-if="cierreCaja.diferenciaDias == null" class="text-sm mb-0">---</p>
                              </td>
                              <td>
                                  <p v-if="cierreCaja.control < 0" class="text-sm mb-0" style="color: white; background-color: #D21610;">{{ formatMoneyCLP(cierreCaja.control) }}</p>
                                  <p v-if="cierreCaja.control == 0" class="text-sm mb-0" style="color: white; background-color: #28a745;">{{ formatMoneyCLP(cierreCaja.control) }}</p>
                                  <p v-if="cierreCaja.control > 0" class="text-sm mb-0" style="color: white; background-color: #3574EA;">{{ formatMoneyCLP(cierreCaja.control) }}</p>
                              </td> -->
                              <td>
                                  <p v-if="cierreCaja.diferenciaDias != null && cierreCaja.diferenciaDias >= 0 && cierreCaja.diferenciaDias <= 3" class="text-sm font-weight-bolder mb-0" style="color: #000000;">{{ cierreCaja.diferenciaDias }} Días.</p>
                                  <p v-if="cierreCaja.diferenciaDias != null && cierreCaja.diferenciaDias < 8 && cierreCaja.diferenciaDias >= 4" class="text-sm font-weight-bolder mb-0" style="color: #FFC300;">{{ cierreCaja.diferenciaDias }} Días.</p>
                                  <p v-if="cierreCaja.diferenciaDias != null && cierreCaja.diferenciaDias < 14 && cierreCaja.diferenciaDias >= 8" class="text-sm font-weight-bolder mb-0" style="color: #FB4C24;">{{ cierreCaja.diferenciaDias }} Días.</p>
                                  <p v-if="cierreCaja.diferenciaDias != null && cierreCaja.diferenciaDias < 0 || cierreCaja.diferenciaDias >= 14" class="text-sm font-weight-bolder mb-0" style="color: #D21610;">{{ cierreCaja.diferenciaDias }} Días.</p>
                                  <p v-if="cierreCaja.diferenciaDias == null" class="text-sm mb-0">---</p>
                              </td>
                              <td>
                                  <p v-if="cierreCaja.control < 0" class="text-sm font-weight-bolder mb-0" style="color: #D21610;">{{ formatMoneyCLP(cierreCaja.control) }}</p>
                                  <p v-if="cierreCaja.control == 0" class="text-sm font-weight-bolder mb-0" style="color: #28a745;">{{ formatMoneyCLP(cierreCaja.control) }}</p>
                                  <p v-if="cierreCaja.control > 0" class="text-sm font-weight-bolder mb-0" style="color: #3574EA;">{{ formatMoneyCLP(cierreCaja.control) }}</p>
                              </td>
                          </tr>
                        </tbody>
                      </table>
                      <hr size="4" class="separador">
                      <br>
                      <div class="row">
                        <div class="text-uppercase">Información del Depósito:</div>
                        <div class="col-3">
                          <div class="form-group">
                            <label for="deposito">Depósito: </label>
                            <input type="number" class= "form-control" v-model="deposito">
                          </div>
                          <div v-if="errors && errors.deposito" class="error">
                            {{ errors.deposito[0] }}
                          </div>
                        </div>
                        <div class="col-3">
                          <div class="form-group">
                            <label for="fechaDeposito">Fecha Depósito: </label>
                            <input type="date" class= "form-control" v-model="fechaDeposito">
                          </div>
                          <div v-if="errors && errors.fechaDeposito" class="error">
                            {{ errors.fechaDeposito[0] }}
                          </div>
                        </div>
                        <div class="col-4">
                          <div class="form-group">
                            <label for="imagenDeposito">Imagen Depósito: </label>
                            <input type="file" accept=".jpg, .jpeg, .png, .heif, .heic" id="imagenDeposito" class= "form-control" @change="getImage">
                          </div>
                          <div v-if="errors && errors.imagenDeposito" class="error">
                            {{ errors.imagenDeposito[0] }}
                          </div>
                        </div>
                        <div v-if="fotoMiniaturaDeposito != '' " class="col-2">
                          <figure>
                            <img width="50" height="50" :src="imagen" alt="Foto Miniatura">
                          </figure>
                        </div>
                        <div class="col-12">
                          <div class="form-group">
                            <label for="observacionDeposito">Observación Depósito: </label>
                            <textarea type="text" class= "form-control" v-model="observacionDeposito" style="min-width: 200px; height: 40px; text-align: justify;"></textarea> 
                          </div>
                        </div>
                      </div>
                    </div>
                  </slot>
                </div>
                <div class="modal-footer">
                <slot name="footer">
                  <button class="btn btn-alert float-end" @click="ingresarDepositoCierreCajas();" :disabled="buttonDisable">Ingresar Depósito</button>
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

export default {
    components: { modalconfirmacion, modalalerta },
    mixins: [mixin],
    data: () => ({
      id: null,
      deposito: null,
      fechaDeposito: null,
      imagenDeposito: null,
      observacionDeposito: null,
      efectivo: null,
      fondoFijo: null,
      fotoMiniaturaDeposito: "",
      buttonText:'Ingresar Cierre de Caja',
      buttonDisable: false,
      errors: [],
    }),
    props:{
      cierreCaja: Object,
    },
    created(){
      this.id = this.cierreCaja.id;
      this.deposito = this.cierreCaja.deposito;
      this.fechaDeposito = this.cierreCaja.fechaDeposito;
      this.observacionDeposito = this.cierreCaja.observacionDeposito;
      this.imagenDeposito = this.cierreCaja.imagenDeposito;
      this.efectivo = this.cierreCaja.efectivo;
      this.fondoFijo = this.cierreCaja.fondoFijo;
    },
    methods: {
      cerrarModal(){
        const elem = this.$refs.closeBtn;
        this.$emit('recarga');
        elem.click();
      },
      async ingresarDepositoCierreCajas() {
        var aDepositar = this.formatMoneyCLP(this.efectivo - this.fondoFijo);
        if (this.deposito > (this.cierreCaja.efectivo - this.cierreCaja.fondoFijo) ) {
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
          if (this.deposito < (this.cierreCaja.efectivo - this.cierreCaja.fondoFijo) ) {
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
            title: 'Ingresar Depósito Cierre Cajas',
            message: `¿Está seguro/a que desea ingresar este depósito? Esta acción no puede ser desecha.`,
            okButton: 'Ingresar',
            cancelButton: 'Volver'
          })
          if (ok) {
            let cierreCaja2 = {
              deposito: this.deposito,
              fechaDeposito: this.fechaDeposito,
              observacionDeposito: this.observacionDeposito,
              imagenDeposito: this.imagenDeposito,
              diferenciaDias: this.distanceFromOther(this.cierreCaja.diaCierre,this.fechaDeposito),
              control: (this.cierreCaja.efectivo - this.cierreCaja.fondoFijo) - this.deposito,
            };
            console.log(cierreCaja2);
            const datos = [];
            datos.push(this.id);
            datos.push(cierreCaja2);
            axios.post("api/cierreCajasDeposito", datos, {headers: { 'Content-Type' : 'multipart/form-data' }}).then((result) => {
              this.buttonDisable = true;
              this.buttonText = 'Guardando...';
              this.toast.success("Depósito ingresado con éxito!", {
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
              setTimeout(() => {location.reload();}, 1000);
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
        this.imagenDeposito = e.target.files[0];
        let compression = [];
        await this.compressImages(this.imagenDeposito,compression);
        var file = new File([compression[0]], compression[0]['name'], {
        type: compression[0]['type'],});
        this.imagenDeposito = file;
        this.chargeImage(this.imagenDeposito);
      },
      // Funcion para cargar la imagen como miniatura
      chargeImage(file){
        let reader = new FileReader();
        reader.onload = (e) => { 
          this.fotoMiniaturaDeposito = e.target.result;
        }
        reader.readAsDataURL(file);
      },
    },
    computed: {
      imagen(){
          return this.fotoMiniaturaDeposito;
      },
    }
}
</script>