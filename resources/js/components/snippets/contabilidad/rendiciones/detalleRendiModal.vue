
<template>
  <transition name="modalver">
    <div name="modal">
      <div class="modal-mask">
        <div class="modal-wrapper">
          <div class="modal-container-xl">
            <div class="modal-header fw-bold" style="color: #444444;">
              <slot name="header">
                Ver Rendición 
              </slot>
              <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
            </div>
            <div class="modal-body">
              <slot name="body">
                <h5>Rendición</h5>
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
                      <th class="text-uppercase text-xs font-weight-bolder">Diferencia</th>
                    </tr>
                  </thead>
                  <tbody> 
                    <tr>
                      <td><p class="text-sm mb-0"> {{ this.rendi1.folio }}</p></td>
                      <td><p class="text-sm mb-0"> {{ this.rendi1.colaborador }}</p></td>
                      <td :title="nombreUsuarioSoli"><p class="text-sm mb-0" style="width: 200px; word-wrap: break-word; overflow: hidden; text-overflow: ellipsis;"> {{ nombreUsuarioSoli  }}</p></td>
                      <td :title="nombreUsuarioRendi"><p class="text-sm mb-0" style="width: 200px; word-wrap: break-word; overflow: hidden; text-overflow: ellipsis;"> {{ nombreUsuarioRendi }}</p></td>
                      <td><p class="text-sm mb-0"> {{ thisDate(this.rendi1.fechaSoli) }}</p></td>
                      <td><p class="text-sm mb-0"> {{ thisDate(this.rendi1.fechaRendi) }}</p></td>
                      <td><p class="text-sm mb-0"> {{ formatMoneyCLP(this.rendi1.montoSoli) }}</p></td>
                      <td><p class="text-sm mb-0"> {{ formatMoneyCLP(this.rendi1.montoRendi) }}</p></td>
                      <td><p class="text-sm mb-0"> {{ abrMoneda }}</p></td>
                      <td><p class="text-sm mb-0"> {{ formatMoneyCLP(this.rendi1.montoSoli - this.rendi1.montoRendi) }}</p></td>
                    </tr>
                  </tbody>
                </table>
                <hr size="4" class="separador">
                <div class="row">
                  <div class="col-9">
                    <label >Detalle Solicitud: <p> {{ this.rendi1.detalleSoli }}</p></label>
                    <br>
                  </div>
                  <div class="col-3">
                    <label >Tipo Imposicion: 
                      <p v-if="this.rendi1.tipoImposicion == 'Falto'">Devolución de dinero</p>
                      <p v-else-if="this.rendi1.tipoImposicion == 'Sobro'">Entrega de dinero</p>
                      <p v-else>Ninguna</p></label>
                    <br>
                  </div>
                </div>
                <label >Detalle Rendición: <p> {{ this.rendi1.detalleRendi }}</p></label>
                <br>
                <hr size="4" class="separador">
                <h5>Detalles Rendición</h5>
                <div class="row">
                  <table id="tableDesign" class="table align-items-center mb-0">
                    <thead>
                      <tr>
                        <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                        <th class="text-uppercase text-xs font-weight-bolder">Tipo Doc.</th>
                        <th class="text-uppercase text-xs font-weight-bolder">Fecha</th>
                        <th class="text-uppercase text-xs font-weight-bolder">N° Documento</th>
                        <th class="text-uppercase text-xs font-weight-bolder">Rut</th>
                        <th class="text-uppercase text-xs font-weight-bolder">Tipo Gasto</th>
                        <th class="text-uppercase text-xs font-weight-bolder">Gasto</th>
                        <th class="text-uppercase text-xs font-weight-bolder">Monto</th>
                        <th class="text-uppercase text-xs font-weight-bolder">Detalle</th>
                        <th class="text-uppercase text-xs font-weight-bolder">Foto</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="detalle in detalles" :key="detalle.nroDocumento">
                        <td><p class="text-sm mb-0"> {{ detalle.id }}</p></td>
                        <td><p class="text-sm mb-0"> {{ detalle.tipo_doc.nombre }}</p></td>
                        <td><p class="text-sm mb-0"> {{ thisDate(detalle.fecha) }}</p></td>
                        <td v-if="detalle.nroDocumento != null"><p class="text-sm mb-0"> {{ detalle.nroDocumento }}</p></td>
                        <td v-else ><p class="text-sm mb-0">---</p></td> 
                        <td v-if="detalle.rut != null"><p class="text-sm mb-0">{{ detalle.rut }}</p></td>
                        <td v-else ><p class="text-sm mb-0">---</p></td>   
                        <td><p class="text-sm mb-0"> {{ detalle.tipo_gasto.nombre }}</p></td>
                        <td><p class="text-sm mb-0"> {{ detalle.gasto.nombre }}</p></td>
                        <td><p class="text-sm mb-0"> {{ formatMoneyCLP(detalle.monto) }}</p></td>
                        <td><p class="text-sm mb-0"> <textarea readonly class="form-control" style="text-align: justify; min-width: 200px; height: 40px; word-wrap: break-word; overflow: hidden;" v-model="detalle.observaciones"></textarea></p></td>
                        <td><p class="text-sm mb-0"> <a class="btn btn-dark-grey btn-xs" @click="verFoto(detalle.foto)"><i class="fa-solid fa-images"></i></a></p></td>
                      </tr>
                    </tbody>  
                  </table>
                  <br>
                  <hr size="4" class="separador">
                </div>
              </slot>
            </div>
            <modalconfirmacion ref="confirmation"></modalconfirmacion>
            <modalalerta ref="alert"></modalalerta>
            <modalfoto v-bind:detalle="detalle" v-if="showFotoRendicion" @close="showFotoRendicion = false"></modalfoto>
          </div>
        </div>
      </div>
    </div>
  </transition>
</template>
  
<script>
import modalconfirmacion from '../../sistema/alerts/confirmationModal.vue'
import modalalerta from '../../sistema/alerts/alertModal.vue'
import modalfoto from '../../fotoModal.vue'
import {mixin} from '../../../../mixins.js'

export default {
  components: { modalconfirmacion, modalalerta, modalfoto },
  mixins: [mixin],
  data: () => ({
    rendi1: {
      folio: "",
      colaborador: "",
      montoSoli: "",
      montoRendi: "",
      idUsuarioSoli: "",
      idUsuarioRendi: "",
      fechaSoli: "",
      fechaRendi: "",
      detalleSoli: "",
      detalleRendi: "",
      idTipoMoneda: "",
      tipoImposicion: "",
    },
    id: null,
    errors:[],
    detalles: [],
    detalle: null,
    showFotoRendicion: false,
    abrMoneda: "",
    nombreUsuarioSoli: "",
    nombreUsuarioRendi: "",
  }),
  props:{
    rendi: Object,
  },
  created(){
    this.rendi1.folio = this.rendi.folio;
    this.rendi1.colaborador = this.rendi.colaborador;
    this.rendi1.montoSoli = this.rendi.montoSoli;
    this.rendi1.montoRendi = this.rendi.montoRendi;
    this.rendi1.idUsuarioSoli = this.rendi.idUsuarioSoli;
    this.rendi1.idUsuarioRendi = this.rendi.idUsuarioRendi;
    this.rendi1.fechaSoli = this.rendi.fechaSoli;
    this.rendi1.fechaRendi = this.rendi.fechaRendi;
    this.rendi1.detalleSoli = this.rendi.detalleSoli;
    this.rendi1.detalleRendi = this.rendi.detalleRendi;
    this.rendi1.idTipoMoneda = this.rendi.idTipoMoneda;
    this.rendi1.tipoImposicion = this.rendi.tipoImposicion;
    this.nombreUsuarioSoli = this.rendi.usuario_soli.name;
    this.nombreUsuarioRendi = this.rendi.usuario_rendi.name;
    this.abrMoneda = this.rendi.moneda.abreviacion;
    this.id = this.rendi.id;
    this.detalles = this.rendi.detalles;
  },
  methods: {
    // Funcion para abrir modal con foto del detalle
    verFoto(detalle){
      this.detalle = detalle;
      this.showFotoRendicion = true;
    },
  }
}
</script>
