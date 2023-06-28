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
                      <label v-if="invConc.estado == 'Finalizado'" class="fs-7 fw-bold" style="color: #28A745">{{ invConc.estado }}</label>
                      <label v-if="invConc.estado == 'Rechazado'" class="fs-7 fw-bold" style="color: #B80F0A">{{ invConc.estado }}</label>
                      <label v-if="invConc.estado == 'Ingresado'" class="fs-7 fw-bold" style="color: #FFC107">{{ invConc.estado }}</label>
                      <label v-if="invConc.estado == 'En Proceso'" class="fs-7 fw-bold" style="color: #8392AB">{{ invConc.estado }}</label>
                      <label v-if="invConc.estado == 'Confirmado'" class="fs-7 fw-bold" style="color: #1520A6">{{ invConc.estado }}</label>
                    </label>
                </slot>
                <a class="btn btn-closed" @click="$emit('close')">X</a>
              </div>
              <div class="modal-body">
                <slot name="body">
                  <div class="form-group">
                    <div class="row">
                      <div class="text-uppercase">Información Sucursal:</div>
                      <label class="col-6 m-0 fs-8"> Sucursal: <label class="fw-normal" style="font-size: 14px;">{{ invConc.sucursal.codigo }} - {{ invConc.sucursal.nombre }}</label></label>
                      <label class="col-6 m-0 fs-8"> Supervisora: <label class="fw-normal" style="font-size: 14px;">{{ invConc.supervisor.usuario.name }}</label></label>
                      <label class="col-6 m-0 fs-8"> Promotor: <label class="fw-normal" style="font-size: 14px;">{{ invConc.promotor.usuario.name }}</label></label>
                      <hr size="4" class="separador">
                      <div class="text-uppercase">Revisión y Comisión Responsable:</div>
                      <label class="col-6 m-0 fs-8"> Fecha: <label class="fw-normal" style="font-size: 14px;">{{ invConc.fecha }}</label></label>
                      <label class="col-6 m-0 fs-8"> Hora: <label class="fw-normal" style="font-size: 14px;">{{ invConc.hora }}</label></label>
                      <label v-if="invConc.comision.usuario != null" class="col-6 m-0 fs-8"> Comisión: <label class="fw-normal" style="font-size: 14px;">{{ invConc.comision.usuario.name }}</label></label>
                      <label v-else class="col-6 m-0 fs-8"> Comisión: <label class="fw-normal" style="font-size: 14px;">{{ invConc.comision.nombre }}</label></label>
                      <hr size="4" class="separador">
                      <div class="text-uppercase">Detalles de Inventario:</div>
                      <label class="col-6 m-0 fs-8"> Super Familia: <label class="fw-normal" style="font-size: 14px;">{{ invConc.superFamilia }}</label></label>
                      <label class="col-6 m-0 fs-8"> Tipo de Inventario: <label class="fw-normal" style="font-size: 14px;">{{ invConc.tipo_inv.nombre }}</label></label>
                      <label v-if="invConc.recuento == null" class="col-6 m-0 fs-8"> Recuento: <label class="fw-normal" style="font-size: 14px;">---</label></label>
                      <label v-else class="col-6 m-0 fs-8"> Recuento: <label class="fw-normal" style="font-size: 14px;">{{ invConc.recuento }}</label></label>
                      <label v-if="invConc.inventario == null" class="col-6 m-0 fs-8"> Inventario: <label class="fw-normal" style="font-size: 14px;">---</label></label>
                      <label v-else class="col-6 m-0 fs-8"> Inventario: <label class="fw-normal" style="font-size: 14px;">{{ invConc.inventario }}</label></label>
                      <label v-if="invConc.contabilizacion == null" class="col-6 m-0 fs-8"> Contabilización: <label class="fw-normal" style="font-size: 14px;">---</label></label>
                      <label v-else class="col-6 m-0 fs-8"> Contabilización: <label class="fw-normal" style="font-size: 14px;">{{ invConc.contabilizacion }}</label></label>
                      <label v-if="invConc.diferencia == null" class="col-6 m-0 fs-8"> Diferencia: <label class="fw-normal" style="font-size: 14px;">---</label></label>
                      <label v-else class="col-6 m-0 fs-8"> Diferencia: <label class="fw-normal" style="font-size: 14px;">{{ invConc.diferencia }}</label></label>
                      <label v-if="invConc.observacion == null" class="col-6 m-0 fs-8"> Observación: <label class="fw-normal" style="font-size: 14px;">---</label></label>
                      <label v-else class="col-6 m-0 fs-8"> Observación: <label class="fw-normal" style="font-size: 14px;">{{ invConc.observacion }}</label></label>
                      <div class="text-uppercase">Detalles Autorizaciones:</div>
                      <label v-if="invConc.autorizadoSupervisora == true" class="col-6 m-0 fs-8"> ¿Autorizado por Supervisora?: <label class="fw-normal" style="font-size: 14px;">Si</label></label>
                      <label v-if="invConc.autorizadoSupervisora == false" class="col-6 m-0 fs-8"> ¿Autorizado por Supervisora?: <label class="fw-normal" style="font-size: 14px;">No</label></label>
                      <label v-else class="col-6 m-0 fs-8"> ¿Autorizado por Supervisora?: <label class="fw-normal" style="font-size: 14px;">Pendiente</label></label>
                      <label v-if="invConc.fechaAutSup == null" class="col-12 m-0 fs-8"> Fecha Autorización Supervisora: <label class="fw-normal" style="font-size: 14px;">---</label></label>
                      <label v-else class="col-12 m-0 fs-8"> Fecha Autorización Supervisora: <label class="fw-normal" style="font-size: 14px;">{{ invConc.fechaAutSup }}</label></label>
                      <label v-if="invConc.observacionesSupervisora == null" class="col-12 m-0 fs-8"> Observación Autorización Supervisora: <label class="fw-normal" style="font-size: 14px;">---</label></label>
                      <label v-else class="col-12 m-0 fs-8"> Observación Autorización Supervisora: <label class="fw-normal" style="font-size: 14px;">{{ invConc.observacionesSupervisora }}</label></label>
                      <label v-if="invConc.autorizadoComision == true" class="col-6 m-0 fs-8"> ¿Autorizado por Comisión?: <label class="fw-normal" style="font-size: 14px;">Si</label></label>
                      <label v-if="invConc.autorizadoComision == false" class="col-6 m-0 fs-8"> ¿Autorizado por Comisión?: <label class="fw-normal" style="font-size: 14px;">No</label></label>
                      <label v-else class="col-6 m-0 fs-8"> ¿Autorizado por Comisión?: <label class="fw-normal" style="font-size: 14px;">Pendiente</label></label>
                      <label v-if="invConc.fechaAutCom == null" class="col-12 m-0 fs-8"> Fecha Autorización Comisión: <label class="fw-normal" style="font-size: 14px;">---</label></label>
                      <label v-else class="col-12 m-0 fs-8"> Fecha Autorización Comisión: <label class="fw-normal" style="font-size: 14px;">{{ invConc.fechaAutCom }}</label></label>
                      <label v-if="invConc.observacionesComision == null" class="col-12 m-0 fs-8"> Observación Autorización Comisión: <label class="fw-normal" style="font-size: 14px;">---</label></label>
                      <label v-else class="col-12 m-0 fs-8"> Observación Autorización Comisión: <label class="fw-normal" style="font-size: 14px;">{{ invConc.observacionesComision }}</label></label>
                      <hr size="4" class="separador">
                      <label v-if="invConc.fechaFinalizacion == null" class="col-12 m-0 fs-8"> Fecha Finalización: <label class="fw-normal" style="font-size: 14px;">---</label></label>
                      <label v-else class="col-12 m-0 fs-8"> Fecha Finalización: <label class="fw-normal" style="font-size: 14px;">{{ invConc.fechaFinalizacion }}</label></label>
                    </div>
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
import axios from 'axios'
import modalconfirmacion from '../../sistema/alerts/confirmationModal.vue'
import modalalerta from '../../sistema/alerts/alertModal.vue'
import {mixin} from '../../../../mixins.js'

export default {
  components: { modalconfirmacion, modalalerta },
  mixins: [mixin],
  data: () => ({
  }),
  props:{
      invConc: Object,
  },
  created(){
  },
  methods: {
  }
}
</script>