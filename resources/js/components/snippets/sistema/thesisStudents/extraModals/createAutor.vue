<template>
    <transition name="modalcrear">
      <div name="modal">
        <div class="modal-mask">
            <div class="modal-wrapper">
              <div class="modal-container-xs">
                <div class="modal-header pb-1 fw-bold" style="color: #444444;">
                  <slot name="header">
                      New Tutor
                  </slot>
                  <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
                </div>
                <div class="modal-body">
                  <slot name="body">
                      <div class="form-group">
                        <label for="name">Name: </label>
                        <br>
                        <input type="text" class= "form-control" v-model="autor.name">
                      </div>
                      <div class="form-group">
                        <label for="institutionSelect">Institution:</label>
                        <br>
                        <select id="institutionSelect" class="form-select" v-model="autor.institution">
                          <option value="">Select Institution</option>
                          <option v-for="institution in universities" :value="institution.name">{{ institution.name }}</option>
                        </select>
                      </div>
                  </slot>
                </div>
                <div class="modal-footer">
                    <slot name="footer">
                        <button class="btn btn-continue float-end" @click="newAutor()" :disabled="buttonDisable">
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
  import modalconfirmacion from '../../../sistema/alerts/confirmationModal.vue'
  import modalalerta from '../../../sistema/alerts/alertModal.vue'
  import {mixin} from '../../../../../mixins.js'
  
  export default {
      components: { modalconfirmacion, modalalerta },
      mixins: [mixin],
      data: () => ({
          buttonText:'New',
          buttonDisable: false,
          autor:{
            name: '',
            institution: '',
            editing: false,
          },
          errors:[],
      }),
      methods: {
        capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        },
        newAutor() {
            this.errors = [];
            for (const item in this.autor){
            if(this.autor[item] === '' || this.autor[item] === 0){
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
                this.$emit("submit", this.autor);
                elem.click();
            }
        }
      }
  }
  </script>
  