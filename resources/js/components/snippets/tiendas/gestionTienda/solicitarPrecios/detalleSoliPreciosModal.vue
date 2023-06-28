<template>
  <transition name="modalver">
    <div name="modal">
      <div class="modal-mask">
        <div class="modal-wrapper">
          <div class="modal-container">
            <div class="modal-header fw-bold" style="color: #444444;">
              <slot name="header">
                Ver Solicitud Precios
              </slot>
              <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
            </div>
            <div class="modal-body">
              <slot name="body">
                <h4>Solicitud Precios</h4>
                <br>
                <table id="tableDesign" class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-xs font-weight-bolder">N° Folio</th>
                      <th class="text-uppercase text-xs font-weight-bolder">Fecha</th>
                      <th class="text-uppercase text-xs font-weight-bolder">Usuario</th>
                      <th class="text-uppercase text-xs font-weight-bolder">Cantidad Total</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td><p class="text-sm mb-0">{{ this.solicitudPrecio1.nFolio }}</p></td>
                      <td><p class="text-sm mb-0">{{ thisDate(this.solicitudPrecio1.fecha) }}</p></td>
                      <td><p class="text-sm mb-0">{{ nombreUsuario }}</p></td>
                      <td><p class="text-sm mb-0">{{ this.solicitudPrecio1.cantidadTotal }}</p></td>
                    </tr>
                  </tbody>
                </table>
                <div>
                  <br>
                  <hr size="4" class="separador">
                </div>
                <h4>Detalles Solicitud Precios</h4>
                <br>
                <div class="row">
                  <table id="tableDesign" class="table align-items-center mb-0">
                    <thead>
                      <tr>
                        <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                        <th class="text-uppercase text-xs font-weight-bolder">Tipo Etiqueta</th>
                        <th class="text-uppercase text-xs font-weight-bolder">Monto</th>
                        <th class="text-uppercase text-xs font-weight-bolder">Cantidad</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="detalle in detalles" :key="detalle.id">
                        <td><p class="text-sm mb-0">{{ detalle.id }}</p></td>    
                        <td><p v-if="detalle.tipoEtiqueta == '1'" class="text-sm mb-0"> Normal </p>
                            <p v-if="detalle.tipoEtiqueta == '2'" class="text-sm mb-0"> Especial </p>
                            <p v-if="detalle.tipoEtiqueta == '3'" class="text-sm mb-0"> Liquidación </p></td>    
                        <td><p class="text-sm mb-0">{{ formatMoneyCLP(detalle.precio) }}</p></td>    
                        <td><p class="text-sm mb-0">{{ detalle.cantidad }}</p></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
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
  import modalconfirmacion from '../../../sistema/alerts/confirmationModal.vue'
  import modalalerta from '../../../sistema/alerts/alertModal.vue'
  import {mixin} from '../../../../../mixins.js'


  export default {
    components: { modalconfirmacion, modalalerta },
    mixins: [mixin],
    data: () => ({
      solicitudPrecio1: {
        folioFofi: "",
        fecha: "",
        usuario: "",
        cantidadTotal: "",
        cantidadGastos: "",
        fofiAsignado: "",
        montoTotal: "",
      },
      id: null,
      errors:[],
      detalles: [],
      nombreUsuario:"",
      detalle: null,
      showFotoFofi: false,
    }),
    props:{
      solicitudPrecio: Object,
    },
    created(){
      this.solicitudPrecio1.nFolio = this.solicitudPrecio.nFolio;
      this.solicitudPrecio1.fecha = this.solicitudPrecio.fecha;
      this.solicitudPrecio1.idUsuario = this.solicitudPrecio.usuario;
      this.solicitudPrecio1.cantidadTotal = this.solicitudPrecio.cantidadTotal;
      this.id = this.solicitudPrecio.id;
      this.nombreUsuario = this.solicitudPrecio1.idUsuario.name;
      this.detalles = this.solicitudPrecio.detalles;
    },
  }
</script>