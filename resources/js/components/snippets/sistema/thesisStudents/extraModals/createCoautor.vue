<template>
  <transition name="modalcrear">
    <div name="modal">
      <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container-xs">
              <div class="modal-header pb-1 fw-bold" style="color: #444444;">
                <slot name="header">
                    New Cotutor
                </slot>
                <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
              </div>
              <div class="modal-body">
                <slot name="body">
                    <div class="form-group">
                      <label for="name">Name: </label>
                      <label title="The format for this field should be as follows: 'First Name,Last Name; First Name,Last Name; ...'" style="color: #0A95FF;"><i class="fa-solid fa-circle-info"></i></label>
                      <br>
                      <input type="text" class= "form-control" v-model="cotutor.name">
                    </div>
                    <div class="form-group">
                      <label for="institutionSelect">Institution:</label>
                      <br>
                      <select id="institutionSelect" class="form-select" v-model="selectedInstitution" @change="checkIfOther">
                        <option value="">Select Institution</option>
                        <option v-for="university in universities" :key="university.name" :value="university.name">{{ university.name }}</option>
                        <option value="other">Other</option>
                      </select>
                      <input v-if="showOtherInstitutionInput" type="text" class="form-control mt-2" v-model="cotutor.institution" placeholder="Enter other university">
                    </div>
                </slot>
              </div>
              <div class="modal-footer">
                  <slot name="footer">
                      <button class="btn btn-continue float-end" @click="newCotutor()" :disabled="buttonDisable">
                          {{ buttonText1 }}
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
import modalconfirmacion from '../../../sistema/alerts/confirmationModal.vue'
import modalalerta from '../../../sistema/alerts/alertModal.vue'
import {mixin} from '../../../../../mixins.js'

export default {
    components: { modalconfirmacion, modalalerta },
    mixins: [mixin],
    data: () => ({
        buttonText1:'New',
        buttonDisable: false,
        cotutor:{
          name: '',
          institution: '',
        },
        selectedInstitution: '',
        showOtherInstitutionInput: false,
        errors:[],
    }),
    methods: {
      checkIfOther() {
          if (this.selectedInstitution === 'other') {
              this.showOtherInstitutionInput = true;
              this.cotutor.institution = '';
          } else {
              this.showOtherInstitutionInput = false;
              this.cotutor.institution = this.selectedInstitution;
          }
      },
      // Capitaliza la primera letra de una cadena.
      capitalizeFirstLetter(string) {
          return string.charAt(0).toUpperCase() + string.slice(1);
      },
      newCotutor() {
          this.errors = [];
          for (const item in this.cotutor){
          if(this.cotutor[item] === '' || this.cotutor[item] === 0){
                  this.errors.push(item);
              }
          }

          var mensaje = ""
          if (this.errors.length != 0){
          this.errors.forEach(item => {
              mensaje =   mensaje + "The field " + this.capitalizeFirstLetter(item) + " is required" + "\n" 
          });
          this.toast.warning( mensaje, {
              position: "top-right",
              timeout: 5000,
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

          if (this.errors.length === 0){
              const elem = this.$refs.closeBtn;
              let universityToSubmit = this.selectedInstitution === 'other' ? this.cotutor.institution : this.selectedInstitution;
              this.cotutor.institution = universityToSubmit 
              this.$emit("submit", this.cotutor);
              elem.click();
          }
      }
    }
}
</script>
