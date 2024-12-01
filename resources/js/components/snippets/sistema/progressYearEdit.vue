<template>
  <transition name="modalcrear">
    <div name="modal">
      <div class="modal-mask">
        <div class="modal-wrapper">
          <div class="modal-container-xs">
            <div class="modal-header pb-1 fw-bold" style="color: #444444;">
              <slot name="header">Edit Progress Report Year</slot>
              <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
            </div>
            <div class="modal-body">
              <slot name="body">
                <form>
                  <div class="mb-3 d-flex align-items-center">
                    <label class="col-auto m-0 fs-8">Progress Report Year:</label>
                    &nbsp;&nbsp;
                    <input 
                      type="number" 
                      class="form-control" 
                      v-model="numeroConcatenado" 
                      placeholder="Enter amount"
                      @input="validateNumeric"
                    />
                  </div>
                </form>
              </slot>
            </div>
            <div class="modal-footer">
              <slot name="footer">
                <button 
                  class="btn btn-continue float-end" 
                  @click="editProgress"
                  :disabled="!numeroConcatenado"
                >
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
import modalconfirmacion from '../sistema/alerts/confirmationModal.vue'
import modalalerta from '../sistema/alerts/alertModal.vue'
import { mixin } from '../../../mixins.js'

export default {
  components: { modalconfirmacion, modalalerta },
  mixins: [mixin],
  data: () => ({
    buttonText: 'Edit',
    numeroConcatenado: '', // Almacena el valor numérico
  }),
  props: {
    progressYear: String
  },
  created() {
    this.numeroConcatenado = this.progressYear; // Inicializa con el valor actual
  },
  methods: {
    // Permite solo valores numéricos
    validateNumeric() {
      // Remueve cualquier carácter que no sea un número y actualiza `numeroConcatenado`
      this.numeroConcatenado = this.numeroConcatenado.replace(/\D/g, '');
    },

    // Edita y envía el valor numérico
    editProgress() {
      if (!this.numeroConcatenado) {
        this.toast.warning('The field cannot be empty. Please enter a number.', {
          position: "top-right",
          timeout: 5000,
          closeOnClick: true,
          pauseOnFocusLoss: true,
          pauseOnHover: true,
          draggable: true,
          showCloseButtonOnHover: false,
          hideProgressBar: true,
          closeButton: "button",
          icon: true,
          rtl: false
        });
        return;
      }

      // Emite el valor ingresado y cierra el modal
      this.$emit("submit", this.numeroConcatenado);
      this.$refs.closeBtn.click();
    }
  }
}
</script>
