<template>
    <transition name="modalcrear">
        <div name="modal">
            <div class="modal-mask">
                <div class="modal-wrapper">
                    <div class="modal-container" >
                        <div class="modal-header" style="font-weight: bold; color: #444444;">
                            <slot name="header">
                                <label class="col-7 m-0" style="font-size: 17px;">Detalle de la Sucursal:</label>
                                <label class="col-3 m-0" style="font-size: 15px; text-align: right;"> Estado:
                                    <label v-if="sucursal.estado == true" class="fs-7 fw-bold" style="color: #28A745">Activo</label>
                                    <label v-if="sucursal.estado == false" class="fs-7 fw-bold" style="color: #B80F0A">Inactivo</label>
                                    <label v-if="sucursal.estado == null" class="fs-7 fw-bold" style="color: black">---</label>
                                </label>
                            </slot>
                            <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
                        </div>
                        <div class="modal-body">
                            <slot name="body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="text-uppercase">Información Esencial:</div>
                                        <label class="col-5 m-0 fs-8"> Nombre: <label class="fw-normal" style="font-size: 14px;">{{ sucursal.nombre }}</label></label>
                                        <label class="col-2 m-0 fs-8"> Código: <label class="fw-normal" style="font-size: 14px;">{{ sucursal.codigo }}</label></label>
                                        <label v-if="sucursal.centroCosto == null" class="col-3 m-0 fs-8"> Centro de Costo: <label class="fw-normal" style="font-size: 14px;">---</label></label>
                                        <label v-else class="col-3 m-0 fs-8"> Centro de Costo: <label class="fw-normal" style="font-size: 14px;">{{ sucursal.centroCosto }}</label></label>
                                        <label v-if="sucursal.bodega == null" class="col-2 m-0 fs-8"> Bodega: <label class="fw-normal" style="font-size: 14px;">---</label></label>
                                        <label v-else class="col-2 m-0 fs-8"> Bodega: <label class="fw-normal" style="font-size: 14px;">{{ sucursal.bodega }}</label></label>
                                        <hr size="4" class="separador">
                                        <div class="text-uppercase">Dirección:</div>
                                        <label class="col-12 m-0 fs-8"> Dirección: <label class="fw-normal" style="font-size: 14px;">{{ sucursal.direccion }}</label></label>
                                        <label class="col-2 m-0 fs-8"> Zona: <label class="fw-normal" style="font-size: 14px;">{{ sucursal.zona.nombre }}</label></label>
                                        <label class="col-2 m-0 fs-8"> País: <label class="fw-normal" style="font-size: 14px;">{{ sucursal.pais.pais }}</label></label>
                                        <label class="col-4 m-0 fs-8"> Región: <label class="fw-normal" style="font-size: 14px;">{{ sucursal.region.region }}</label></label>
                                        <label class="col-4 m-0 fs-8"> Comuna: <label class="fw-normal" style="font-size: 14px;">{{ sucursal.comuna.comuna }}</label></label>
                                        <hr size="4" class="separador">
                                        <div class="text-uppercase">Contacto:</div>
                                        <label v-if="sucursal.telefono == null" class="col-4 m-0 fs-8"> Teléfono: <label class="fw-normal" style="font-size: 14px;">---</label></label>
                                        <label v-else class="col-4 m-0 fs-8"> Teléfono: <label class="fw-normal" style="font-size: 14px;">{{ sucursal.telefono }}</label></label>
                                        <label v-if="sucursal.email == null" class="col-8 m-0 fs-8"> Correo: <label class="fw-normal" style="font-size: 14px;">---</label></label>
                                        <label v-else class="col-8 m-0 fs-8"> Correo: <label class="fw-normal" style="font-size: 14px;">{{ sucursal.email }}</label></label>
                                        <hr size="4" class="separador">
                                        <div class="text-uppercase">Otros Datos:</div>
                                        <label class="col-7 m-0 fs-8"> Empresa: <label class="fw-normal" style="font-size: 14px;">{{ sucursal.empresa_marca.empresa.razonSocial }}</label></label>
                                        <label class="col-5 m-0 fs-8"> Marca: <label class="fw-normal" style="font-size: 14px;">{{ sucursal.empresa_marca.marca.nombre }}</label></label>
                                        <label class="col-12 m-0 fs-8"> Tipo de Sucursal: <label class="fw-normal" style="font-size: 14px;">{{ sucursal.tipo_sucursal.nombre }}</label></label>
                                    </div>
                                </div>
                            </slot>
                            <modalconfirmacion ref="confirmation"></modalconfirmacion>
                            <modalalerta ref="alert"></modalalerta>
                        </div>
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

export default {
  components: { modalconfirmacion, modalalerta },
  mixins: [mixin],
  data: () => ({
  }),
  props:{
    sucursal: Object,
  },
  created(){
  },
}
</script>