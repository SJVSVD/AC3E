<template>
  <transition name="modalcrear">
    <div name="modal">
      <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container">
              <div class="modal-header" style="font-weight: bold; color: #444444;">
                <slot name="header">
                  <label class="col-7 m-0" style="font-size: 17px;">Detalle del Robo:</label>
                    <label class="col-3 m-0" style="font-size: 15px; text-align: right;"> Estado:
                      <label v-if="robo.estado == 'Aprobado'" class="fs-7 fw-bold" style="color: #28A745;">{{ robo.estado }}</label>
                      <label v-if="robo.estado == 'Rechazado'" class="fs-7 fw-bold" style="color: #B80F0A;">{{ robo.estado }}</label>
                      <label v-if="robo.estado == 'Pendiente'" class="fs-7 fw-bold" style="color: #FFC107;">{{ robo.estado }}</label>
                    </label>
                </slot>
                <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
              </div>
              <div class="modal-body">
                <slot name="body">
                  <div class="form-group">
                    <div class="row">
                      <div class="text-uppercase">Información Esencial:</div>
                      <label class="col-6 m-0 fs-8"> Tipo: <label class="fw-normal" style="font-size: 14px;">{{ robo.tipo_robo.nombre }}</label></label>
                      <label class="col-6 m-0 fs-8"> Usuario Informante: <label class="fw-normal" style="font-size: 14px;">{{ robo.usuario.name }}</label></label>
                      <hr size="4" class="separador">
                      <div class="text-uppercase">Lugar:</div>
                      <label class="col-12 m-0 fs-8"> Tienda: <label class="fw-normal" style="font-size: 14px;">{{ robo.tienda.nombre }}</label></label>
                      <label class="col-12 m-0 fs-8"> Dirección: <label class="fw-normal" style="font-size: 14px;">{{ robo.tienda.direccion }}</label></label>
                      <label class="col-3 m-0 fs-8"> País: <label class="fw-normal" style="font-size: 14px;">{{ robo.tienda.pais.pais }}</label></label>
                      <label class="col-3 m-0 fs-8"> Zona: <label class="fw-normal" style="font-size: 14px;">{{ robo.tienda.zona.nombre }}</label></label>
                      <label class="col-3 m-0 fs-8"> Región: <label class="fw-normal" style="font-size: 14px;">{{ robo.tienda.region.region }}</label></label>
                      <label class="col-3 m-0 fs-8"> Comúna: <label class="fw-normal" style="font-size: 14px;">{{ robo.tienda.comuna.comuna }}</label></label>
                      <hr size="4" class="separador">
                      <div class="text-uppercase">Tiempo:</div>
                      <label class="col-6 m-0 fs-8"> Fecha: <label class="fw-normal" style="font-size: 14px;">{{ robo.fecha }}</label></label>
                      <label class="col-6 m-0 fs-8"> Hora: <label class="fw-normal" style="font-size: 14px;">{{ robo.hora }}</label></label>
                      <hr size="4" class="separador">
                      <div class="text-uppercase">Resposables:</div>
                      <label class="col-6 m-0 fs-8"> Supervisor: <label class="fw-normal" style="font-size: 14px;">{{ robo.supervisor.name }}</label></label>
                      <label v-if="robo.autorizado == true" class="col-6 m-0 fs-8"> ¿Autorizado?: <label class="fw-normal" style="font-size: 14px;">Si</label></label>
                      <label v-if="robo.autorizado == false" class="col-6 m-0 fs-8"> ¿Autorizado?: <label class="fw-normal" style="font-size: 14px;">No</label></label>
                      <label v-else class="col-6 m-0 fs-8"> ¿Autorizado?: <label class="fw-normal" style="font-size: 14px;">Pendiente</label></label>
                      <label v-if="robo.observacionAutorizador == null" class="col-12 m-0 fs-8"> Observación Autorización: <label class="fw-normal" style="font-size: 14px;">---</label></label>
                      <label v-else class="col-12 m-0 fs-8"> Observación Autorización: <label class="fw-normal" style="font-size: 14px;">{{ robo.observacionAutorizador }}</label></label>
                      <hr size="4" class="separador">
                      <div class="text-uppercase">Daños:</div>
                      <label class="col-6 m-0 fs-8"> Monto Robado: <label class="fw-normal" style="font-size: 14px;">{{ robo.montoRobado }}</label></label>
                      <label class="col-6 m-0 fs-8"> Observacion: <label class="fw-normal" style="font-size: 14px;">{{ robo.observacion }}</label></label>
                    </div>
                  </div>
                  <!-- ###################################################################################################################################### -->
                  <hr size="4" class="separador">
                  <p style="font-size: 16px; font-weight: bold; color: black; margin: 0;"> Detalles del Robo: </p>
                  <br>
                  <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0" id="tableDesign">
                      <thead>
                        <tr style="color: black">
                          <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Item</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Descripción</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Cantidad</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Monto</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-if="roboDetalles == 0">
                          <td colspan="10"> No hay información disponible. </td>
                        </tr>
                        <tr v-else v-for="detalle in roboDetalles" :key="detalle.id">
                          <td>
                            <p class="text-sm font-weight-bolder mb-0" style="color: black">{{ detalle.id }}</p>
                          </td>
                          <td>
                            <p class="text-sm mb-0">{{ detalle.item }}</p>
                          </td>
                          <td>
                            <p class="text-sm mb-0">
                              <textarea disabled class="form-control" v-model="detalle.descripcion" style="min-width: 200px; height: 40px; overflow: hidden; text-align: justify;"> </textarea>
                            </p>
                          </td>
                          <td>
                            <p class="text-sm mb-0">{{ detalle.cantidad }}</p>
                          </td>
                          <td>
                            <p class="text-sm mb-0">{{ detalle.monto }}</p>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                    <br>
                    <br>
                  </div>
                  <div class="modal-footer">
                    <slot name="footer">
                      <a v-if="is('Tiendas_Jefatura') && robo.estado == 'Pendiente'" class="btn btn-spacing btn-closed" style="float: right;" id="show-autorizarModal" @click="roboAutorizar(robo)"><i class="fa-solid fa-file-signature"></i> Autorizar</a>
                    </slot>
                  </div>
                </slot>
              </div>
              <modalautorizar v-bind:robo="detalles" v-if="showAutorizarRobos" @close="showAutorizarRobos = false"></modalautorizar>
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
import modalautorizar from './autorizarModal.vue'
import modalconfirmacion from '../../sistema/alerts/confirmationModal.vue'
import modalalerta from '../../sistema/alerts/alertModal.vue'
import {mixin} from '../../../../mixins.js'

export default {
  components: { modalconfirmacion, modalalerta, modalautorizar },
  mixins: [mixin],
  data: () => ({
    detalles: null,
    roboDetalles: null,
    showAutorizarRobos: false,
  }),
  props:{
      robo: Object,
  },
  created(){
    this.roboDetalles = this.robo.detalles_robo;
  },
  methods: {
    roboAutorizar(robo){
      this.detalles = robo;
      this.showAutorizarRobos = true;
    },
  }
}
</script>