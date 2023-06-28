<template> 
  <transition name="modalcrear">
    <div name="modal">
      <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container-xs">
              <div class="modal-header" style="font-weight: bold; color: #444444;">
                <slot name="header">
                    Autorizar Robo
                </slot>
                <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
              </div>
              <div class="modal-body">
                <slot name="body">
                  <label for="">Autorizar: </label>
                  <br>
                  <label>
                    <input style="-webkit-transform: scale(1.2); transform: scale(1.2);" type="radio" value="true" v-model="robo.autorizado">
                    <label style="margin-right:15px;">Si</label>
                    <input style="margin-left:15px; -webkit-transform: scale(1.2); transform: scale(1.2);" type="radio" value="false" v-model="robo.autorizado">
                    <label>No</label>
                    <input selected checked style="margin-left:15px; -webkit-transform: scale(1.2); transform: scale(1.2);" type="radio" value="null" v-model="robo.autorizado">
                    <label>Pendiente</label>
                  </label>
                  <br>
                  <div class="form-group">
                    <label for="">Observación: </label>
                    <textarea type="text" class= "form-control" v-model="robo.observacionAutorizador"> </textarea>
                  </div>
                </slot>
              </div>
              <div class="modal-footer">
                <slot name="footer">
                  <button class="btn btn-alert float-end" @click="saveAuth()" :disabled="buttonDisable">Guardar</button>
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
    buttonText:'Guardar Autorización de Pago',
    buttonDisable: false,
    robosAutorizar: null,
    errors:[],
  }),
  props:{
    robo: Object,
  },
  created(){
  },
  methods: {
    cerrarModal(){
      const elem = this.$refs.closeBtn;
      this.$emit('recarga');
      elem.click();
    },  
    async saveAuth() {
      const ok = await this.$refs.confirmation.show({
          title: 'Autorizar',
          message: `¿Está seguro/a los datos ingresados son correctos? Esta acción no puede ser desecha.`,
          okButton: 'Autorizar',
          cancelButton: 'Volver'
      })
      if (ok) {
        axios.post('api/robos/autorizar', this.robo).then( response => {
          this.buttonDisable = true;
          this.buttonText = 'Guardando...';
          this.toast.success("Descuento de Stock Autorizado!", {
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
          setTimeout(() => {this.cerrarModal();}, 1500);
        })
        .catch((e)=> {
          console.log(e);
          if (e.response.status == 422){
            this.errors = e.response.data.errors;
            this.toast.warning('Existe un valor inválido.', {
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
        });
      }
    },
  }
}
</script>