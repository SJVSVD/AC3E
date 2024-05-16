<template>
    <transition name="modalcrear">
      <div name="modal">
        <div class="modal-mask">
            <div class="modal-wrapper">
              <div class="modal-container-xs">
                <div class="modal-header pb-1 fw-bold" style="color: #444444;">
                  <slot name="header">
                      New Other
                  </slot>
                  <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
                </div>
                <div class="modal-body">
                  <slot name="body">
                      <div class="form-group">
                        <label for="name">Name: </label>
                        <label title="The format for this field should be as follows: 'First Name,Last Name; First Name,Last Name; ...'" style="color: #0A95FF;"><i class="fa-solid fa-circle-info"></i></label>
                        <br>
                        <input type="text" class= "form-control" v-model="other.name">
                      </div>
                      <div class="form-group">
                        <label for="institutionSelect">Institution:</label>
                        <br>
                        <select id="institutionSelect" class="form-select" v-model="other.institution">
                          <option value="">Select Institution</option>
                          <option v-for="institution in universities" :value="institution.name">{{ institution.name }}</option>
                        </select>
                      </div>
                  </slot>
                </div>
                <div class="modal-footer">
                    <slot name="footer">
                        <button class="btn btn-continue float-end" @click="newOther()" :disabled="buttonDisable">
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
          buttonText1:'Crear',
          buttonDisable: false,
          other:{
            name: '',
            institution: '',
          },
          errors:[],
      }),
      methods: {
          // Capitaliza la primera letra de una cadena.
        capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        },
        newOther() {
            this.errors = [];
            for (const item in this.other){
            if(this.other[item] === '' || this.other[item] === 0){
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
                this.$emit("submit", this.other);
                elem.click();
            }
        }
      }
  }
  </script>
  