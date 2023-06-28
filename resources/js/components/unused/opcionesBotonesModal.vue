<template>
    <transition name="modalcrear">
      <div name="modal">
        <div class="modal-mask">
            <div class="modal-wrapper">
              <div class="modal-container-xs">
                <div class="modal-header" style="font-weight: bold; color: #444444;">
                  <slot name="header">
                    <label class="col-7 m-0" style="font-size: 17px;">Cierre de Cajas:</label>
                  </slot>
                  <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
                </div>
                <div class="modal-body">
                  <slot name="body">
                    <div class="m-auto text-center">
                      <a class="btn btn-dark-grey" @click="showIngresarCierreCajas = true" style="width: 140px; height: 140px;">
                          Ingresar Cierre
                          <p></p>
                          <p><i class="fa-solid fa-file-signature fa-3x"></i></p>
                      </a>
                      &nbsp;
                      <a class="btn btn-dark-grey" @click="showConsultarCierreCajas = true" style="width: 140px; height: 140px;">
                          Consultar Historial
                          <p>
                              <i class="fa-solid fa-book fa-3x"></i>
                          </p>
                      </a>
                      &nbsp;
                    </div>
                  </slot>
                </div>
                <modalconfirmacion ref="confirmation"></modalconfirmacion>
                <modalalerta ref="alert"></modalalerta>
                <ingresarCierreCajasModal v-if="showIngresarCierreCajas" @close="showIngresarCierreCajas = false"></ingresarCierreCajasModal>
                <consultarCierreCajasModal v-if="showConsultarCierreCajas" @close="showConsultarCierreCajas = false"></consultarCierreCajasModal>
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
import ingresarCierreCajasModal from './cierreCajas/ingresarCierreCajasModal.vue'
import consultarCierreCajasModal from './snippets/tiendas/gestionTienda/cierreCajas/snippets/tiendas/gestionTienda/cierreCajas/consultarCierreCajasModal.vue'
import {mixin} from '../../../../mixins.js'

export default {
    components: { modalconfirmacion, modalalerta, ingresarCierreCajasModal, consultarCierreCajasModal },
    mixins: [mixin],
    data: () => ({
      showIngresarCierreCajas: null,
      showConsultarCierreCajas: null,
    }),
    props:{
    },
    created(){
    },
    methods: {
    }
}
</script>