<template>
  <transition name="modalcrear">
    <div name="modal">
      <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container">
              <div class="modal-header" style="font-weight: bold; color: #444444;">
                <slot name="header">
                  <label class="col-7 m-0" style="font-size: 17px;">Detalle de la Caja:</label>
                </slot>
                <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
              </div>
              <div class="modal-body">
                <slot name="body">
                  <div class="form-group">
                    <div class="row">
                      <div class="text-uppercase">Información Básica:</div>
                      <label class="col-4 m-0 fs-8"> Tienda: <label class="fw-normal" style="font-size: 14px;">{{ cierreCaja.tienda.nombre }}</label></label>
                      <label class="col-4 m-0 fs-8"> Dia Cierre: <label class="fw-normal" style="font-size: 14px;">{{ thisDate(cierreCaja.diaCierre) }}</label></label>
                      <label class="col-4 m-0 fs-8"> Imagen Voucher: <label class="fw-normal" style="font-size: 14px;"><p class="text-sm mb-0"><a class="btn btn-dark-grey btn-xs" @click="verFoto(cierreCaja.imagenVoucher)"><i class="fa-solid fa-images"></i></a></p></label></label>
                      <label class="col-12 m-0 fs-8"> Responsable Cierre: <label class="fw-normal" style="font-size: 14px;">{{ cierreCaja.responsableCierre }}</label></label>
                      <label v-if="cierreCaja.observacionCierre != null" class="col-12 m-0 fs-8"> Observación Cierre: <label class="fw-normal" style="font-size: 14px;">{{ cierreCaja.observacionCierre }}</label></label>
                      <label v-else class="col-12 m-0 fs-8"> Observación Cierre: <label class="fw-normal" style="font-size: 14px;">---</label></label>
                      <hr size="4" class="separador">
                      <div class="text-uppercase">Control:</div>
                      <label v-if="cierreCaja.diferenciaDias != null" class="col-6 m-0 fs-8"> Diferencia Días: <label class="fw-normal" style="font-size: 14px;">{{ cierreCaja.diferenciaDias }} Días</label></label>
                      <label v-else class="col-6 m-0 fs-8"> Diferencia Días: <label class="fw-normal" style="font-size: 14px;">---</label></label>
                      <label class="col-6 m-0 fs-8"> Control: <label class="fw-normal" style="font-size: 14px;">{{ formatMoneyCLP(cierreCaja.control) }}</label></label>
                      <hr size="4" class="separador">
                      <div class="text-uppercase">Detalle de Ventas:</div>
                      <label class="col-4 m-0 fs-8"> Tarjeta: <label class="fw-normal" style="font-size: 14px;">{{ formatMoneyCLP(cierreCaja.tarjeta) }}</label></label>
                      <label class="col-4 m-0 fs-8"> Transferencia: <label class="fw-normal" style="font-size: 14px;">{{ formatMoneyCLP(cierreCaja.transferencia) }}</label></label>
                      <label class="col-4 m-0 fs-8"> Efectivo: <label class="fw-normal" style="font-size: 14px;">{{ formatMoneyCLP(cierreCaja.efectivo) }}</label></label>
                      <label class="col-3 m-0 fs-8"> Nota de Crédito: <label class="fw-normal" style="font-size: 14px;">{{ formatMoneyCLP(cierreCaja.notaCredito) }}</label></label>
                      <label class="col-3 m-0 fs-8"> Fondo Fijo: <label class="fw-normal" style="font-size: 14px;">{{ formatMoneyCLP(cierreCaja.fondoFijo) }}</label></label>
                      <label class="col-3 m-0 fs-8"> Cheque: <label class="fw-normal" style="font-size: 14px;">{{ formatMoneyCLP(cierreCaja.cheque) }}</label></label>
                      <label class="col-3 m-0 fs-8"> Cornershop: <label class="fw-normal" style="font-size: 14px;">{{ formatMoneyCLP(cierreCaja.cornershop) }}</label></label>
                      <label class="col-3 m-0 fs-8"> Rappi: <label class="fw-normal" style="font-size: 14px;">{{ formatMoneyCLP(cierreCaja.rappi) }}</label></label>
                      <label class="col-3 m-0 fs-8"> Personal: <label class="fw-normal" style="font-size: 14px;">{{ formatMoneyCLP(cierreCaja.personal) }}</label></label>
                      <label class="col-3 m-0 fs-8"> Marina Online: <label class="fw-normal" style="font-size: 14px;">{{ formatMoneyCLP(cierreCaja.marinaOnline) }}</label></label>
                      <label class="col-3 m-0 fs-8"> Vale: <label class="fw-normal" style="font-size: 14px;">{{ formatMoneyCLP(cierreCaja.vale) }}</label></label>
                      <hr size="4" class="separador">
                      <div class="text-uppercase">Resumen de Ventas:</div>
                      <label class="col-6 m-0 fs-8"> Venta Bruta: <label class="fw-normal" style="font-size: 14px;">{{ formatMoneyCLP(cierreCaja.ventaBruta) }}</label></label>
                      <label class="col-6 m-0 fs-8"> Venta Total: <label class="fw-normal" style="font-size: 14px;">{{ formatMoneyCLP(cierreCaja.ventaTotal) }}</label></label>
                      <hr size="4" class="separador">
                      <div class="text-uppercase">Depósito:</div>
                      <label class="col-4 m-0 fs-8"> Depósito: <label class="fw-normal" style="font-size: 14px;">{{ formatMoneyCLP(cierreCaja.deposito) }}</label></label>
                      <label class="col-4 m-0 fs-8"> Fecha Depósito: <label class="fw-normal" style="font-size: 14px;">{{ thisDate(cierreCaja.fechaDeposito) }}</label></label>
                      <label class="col-4 m-0 fs-8"> Imagen Depósito: <label class="fw-normal" style="font-size: 14px;"><p class="text-sm mb-0"><a class="btn btn-dark-grey btn-xs" @click="verFoto(cierreCaja.imagenDeposito)"><i class="fa-solid fa-images"></i></a></p></label></label>
                      <label v-if="cierreCaja.observacionDeposito != null" class="col-12 m-0 fs-8"> Observación Depósito: <label class="fw-normal" style="font-size: 14px;">{{ cierreCaja.observacionDeposito }}</label></label>
                      <label v-else class="col-12 m-0 fs-8"> Observación Depósito: <label class="fw-normal" style="font-size: 14px;">---</label></label>
                    </div>
                  </div>
                </slot>
              </div>
              <modalconfirmacion ref="confirmation"></modalconfirmacion>
              <modalalerta ref="alert"></modalalerta>
              <modalfoto v-bind:detalle="detalle" v-if="showFotoVoucher" @close="showFotoVoucher = false"></modalfoto>
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
import modalfoto from '../../../fotoModal.vue'
export default {
  components: { modalconfirmacion, modalalerta, modalfoto },
  mixins: [mixin],
  data: () => ({
    idCierreCaja: null,
    detalle: null,
    showFotoVoucher: false,
  }),
  props:{
      cierreCaja: Object,
  },
  created(){
  },
  methods: {
    verFoto(detalle){
      this.detalle = detalle;
      this.showFotoVoucher = true;
    },
  }
}
</script>