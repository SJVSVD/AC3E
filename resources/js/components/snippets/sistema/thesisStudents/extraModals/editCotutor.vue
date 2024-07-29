<template>
  <transition name="modalcrear">
    <div name="modal">
      <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container-xs">
              <div class="modal-header pb-1 fw-bold" style="color: #444444;">
                <slot name="header">
                    Edit Tutor
                </slot>
                <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
              </div>
              <div class="modal-body">
                <slot name="body">
                    <div class="form-group">
                      <label for="name">Name: </label>
                      <label title="The format for this field should be as follows: 'First Name,Last Name; First Name,Last Name; ...'" style="color: #0A95FF;"><i class="fa-solid fa-circle-info"></i></label>
                      <br>
                      <input type="text" class= "form-control" v-model="cotutors1.name">
                    </div>
                    <div class="form-group">
                      <label for="institutionSelect">Institution:</label>
                      <br>
                      <select id="institutionSelect" class="form-select" v-model="selectedInstitution2" @change="checkIfOther">
                        <option value="">Select Institution</option>
                        <option v-for="university in universities" :key="university.name" :value="university.name">{{ university.name }}</option>
                        <option value="other">Other</option>
                      </select>
                      <input v-if="showOtherInstitutionInput2" type="text" class="form-control mt-2" v-model="cotutors1.institution" placeholder="Enter other university">
                    </div>
                </slot>
              </div>
              <div class="modal-footer">
                  <slot name="footer">
                      <button class="btn btn-continue float-end" @click="editTutor()" :disabled="buttonDisable">
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
          buttonText1:'Edit',
          buttonDisable: false,
          selectedInstitution2: '',
          showOtherInstitutionInput2: false,
          errors:[],
      }),
      props:{
        cotutors1: Object,
      },
      created(){
        if (!this.universities.some(univ => univ.name === this.cotutors1.institution)) {
              this.selectedInstitution2 = 'other';
              this.cotutors1.institution = this.cotutors1.institution;
              this.showOtherInstitutionInput2 = true;
          } else {
              this.selectedInstitution2 = this.cotutors1.institution;
              this.showOtherInstitutionInput2 = false;
          }
      },
      methods: {
        checkIfOther() {
            if (this.selectedInstitution2 === 'other') {
                this.showOtherInstitutionInput2 = true;
                this.cotutors1.institution = '';
            } else {
                this.showOtherInstitutionInput2 = false;
                this.cotutors1.institution = this.selectedInstitution2;
            }
        },
        // Capitaliza la primera letra de una cadena.
        capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        },
        editTutor() {
          const elem = this.$refs.closeBtn;
          elem.click();
        }
      }
  }
  </script>
  