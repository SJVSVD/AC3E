<template>
    <transition name="modalcrear">
        <div name="modal">
            <div class="modal-mask">
                <div class="modal-wrapper">
                    <div class="modal-container" >
                        <div class="modal-header" style="font-weight: bold; color: #444444;">
                            <slot name="header">
                                <label class="col-7 m-0" style="font-size: 17px;">Detalle del Colaborador:</label>
                            </slot>
                            <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
                        </div>
                        <div class="modal-body">
                            <slot name="body">
                                <div class="form-group">
                                    <div class="row">
                                        <div class="text-uppercase">Información Personal:</div>
                                        <label class="col-3 m-0 fs-8"> Nombre: <label class="fw-normal" style="font-size: 14px;">{{ colaborador.nombre1 }}</label></label>
                                        <label class="col-3 m-0 fs-8"> 2do Nombre: <label class="fw-normal" style="font-size: 14px;">{{ colaborador.nombre2 }}</label></label>
                                        <label class="col-3 m-0 fs-8"> Apellido Paterno: <label class="fw-normal" style="font-size: 14px;">{{ colaborador.apellido1 }}</label></label>
                                        <label class="col-3 m-0 fs-8"> Apellido Materno: <label class="fw-normal" style="font-size: 14px;">{{ colaborador.apellido2 }}</label></label>
                                        <label class="col-4 m-0 fs-8"> Identificador: <label class="fw-normal" style="font-size: 14px;">{{ formatoRut(colaborador.identificador) }}</label></label>
                                        <label class="col-4 m-0 fs-8"> Estado Civil: <label class="fw-normal" style="font-size: 14px;">{{ colaborador.estado_civil.nombre }}</label></label>
                                        <label class="col-4 m-0 fs-8"> Fecha Nacimiento: <label class="fw-normal" style="font-size: 14px;">{{ colaborador.fechaNac }}</label></label>
                                        <label class="col-4 m-0 fs-8"> Nacionalidad: <label class="fw-normal" style="font-size: 14px;">{{ colaborador.nacionalidad.nacionalidad }}</label></label>
                                        <hr size="4" class="separador">
                                        <div class="text-uppercase">Información de Trabajo:</div>
                                        <label class="col-4 m-0 fs-8"> Departamento: <label class="fw-normal" style="font-size: 14px;">{{ colaborador.departamento.departamento }}</label></label>
                                        <label class="col-4 m-0 fs-8"> Área: <label class="fw-normal" style="font-size: 14px;">{{ colaborador.area.area }}</label></label>
                                        <label class="col-4 m-0 fs-8"> Cargo: <label class="fw-normal" style="font-size: 14px;">{{ colaborador.cargo }}</label></label>
                                        <hr size="4" class="separador">
                                        <div class="text-uppercase">Información de Contacto:</div>
                                        <label class="col-6 m-0 fs-8"> Correo Empresa: <label class="fw-normal" style="font-size: 14px;">{{ colaborador.correoEmpresa }}</label></label>
                                        <label class="col-6 m-0 fs-8"> Teléfono Empresa: <label class="fw-normal" style="font-size: 14px;">{{ colaborador.telefonoEmpresa }}</label></label>
                                        <label class="col-6 m-0 fs-8"> Correo Personal: <label class="fw-normal" style="font-size: 14px;">{{ colaborador.correoPersonal }}</label></label>
                                        <label class="col-6 m-0 fs-8"> Teléfono Personal: <label class="fw-normal" style="font-size: 14px;">{{ colaborador.telefonoPersonal }}</label></label>
                                        <hr size="4" class="separador">
                                        <div class="text-uppercase">Dirección:</div>
                                        <label class="col-4 m-0 fs-8"> Dirección: <label class="fw-normal" style="font-size: 14px;">{{ colaborador.direccion }}</label></label>
                                        <label class="col-4 m-0 fs-8"> Sector: <label class="fw-normal" style="font-size: 14px;">{{ colaborador.sector }}</label></label>
                                        <label class="col-4 m-0 fs-8"> Comuna: <label class="fw-normal" style="font-size: 14px;">{{ colaborador.comuna.comuna }}</label></label>
                                        <label class="col-4 m-0 fs-8"> Región: <label class="fw-normal" style="font-size: 14px;">{{ colaborador.region.region }}</label></label>
                                        <label class="col-4 m-0 fs-8"> País: <label class="fw-normal" style="font-size: 14px;">{{ colaborador.pais.pais }}</label></label>
                                        <hr size="4" class="separador">
                                        <div class="text-uppercase">Salud:</div>
                                        <label class="col-6 m-0 fs-8"> Sistema Salud: <label class="fw-normal" style="font-size: 14px;">{{ colaborador.sistema_salud.nombre }}</label></label>
                                        <label class="col-6 m-0 fs-8"> AFP: <label class="fw-normal" style="font-size: 14px;">{{ colaborador.afp_asociada.nombre }}</label></label>
                                        <hr size="4" class="separador">
                                        <div class="text-uppercase">Otros:</div>
                                        <div class="col-6">
                                            <label class="m-0 fs-8"> Foto:</label>
                                            <figure v-if="foto != null">
                                                <img width="100" height="100" :v-bind:src="'data:image/jpeg;base64,'+foto" />
                                            </figure>
                                            <label v-else class="fs-8 fw-normal">---</label>
                                        </div>
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
import modalconfirmacion from './../../sistema/alerts/confirmationModal.vue'
import modalalerta from './../../sistema/alerts/alertModal.vue'
import {mixin} from './../../../../mixins.js'

export default {
  components: { modalconfirmacion, modalalerta },
  mixins: [mixin],
  data: () => ({
  }),
  props:{
    colaborador: Object,
  },
  created(){
  },
}
</script>