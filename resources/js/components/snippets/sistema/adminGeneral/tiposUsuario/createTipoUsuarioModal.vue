<template>
  <transition name="modalcrear">
    <div name="modal">
      <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container-xs">
              <div class="modal-header fw-bold" style="color: #444444;">
                <slot name="header">
                    Nuevo Tipo de Usuario
                </slot>
                <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
              </div>
              <div class="modal-body">
                <slot name="body">
                  <div class="form-group">
                    <label for="nombre">Nombre: </label>
                    <br>
                    <input type="text" class= "form-control" v-model="tipoUsuario.nombre">
                    <div v-if="errors && errors.nombre" class="error">
                        {{ errors.nombre[0] }}
                    </div>
                  </div>
                </slot>
              </div>
              <div class="modal-footer">
                  <slot name="footer">
                      <button class="btn btn-continue" @click="crearTipoUsuario()" :disabled="buttonDisable">
                          {{ buttonText }}
                      </button>
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
import modalconfirmacion from '../../alerts/confirmationModal.vue'
import modalalerta from '../../alerts/alertModal.vue'
import {mixin} from '../../../../../mixins.js'

export default {
    components: { modalconfirmacion, modalalerta },
    mixins: [mixin],
    data: () => ({
        buttonText:'Crear Tipo de Usuario',
        buttonDisable: false,
        tipoUsuario: {
            nombre: null,
        },
        errors:[],
    }),
    created(){
    },
    methods: {
      cerrarModal(){
        const elem = this.$refs.closeBtn;
        this.$emit('recarga');
        elem.click();
      },
      async crearTipoUsuario() {
        const ok = await this.$refs.confirmation.show({
            title: 'Crear Tipo de Usuario',
            message: `¿Está seguro/a que desea crear el tipo de usuario '${this.tipoUsuario.nombre}'? Esta acción no puede ser desecha.`,
            okButton: 'Crear',
            cancelButton: 'Volver'
        })
        if (ok) {
            let tipoUsuario = {
              nombre: this.tipoUsuario.nombre,
            };
            axios.post("../api/tiposUsuario", tipoUsuario).then((result) => {
                this.buttonDisable = true;
                this.buttonText = 'Guardando...';
                this.toast.success('Tipo de usuario creado con éxito!', {
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
            }).catch((error)=> {
              if (error.response.status == 422){
                  this.errors = error.response.data.errors;
                  this.toast.warning('Existe un valor inválido.', {
                      position: "top-right",
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
      }
    }
}
</script>
