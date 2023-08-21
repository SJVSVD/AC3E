<template>
  <transition name="modalcrear">
    <div name="modal">
      <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container-s">
              <div class="modal-header pb-1 fw-bold" style="color: #444444;">
                <slot name="header">
                    New Award
                </slot>
                <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
              </div>
              <div class="modal-body">
                <slot name="body">
                    <div class="row">
                          <div class="col-4">
                            <label for="">Award Name:</label>
                            <br>
                            <input type="text" class= "form-control" v-model="award.awardName">
                          </div>
                          <div class="col-2">
                            <label for="">Year:</label>
                            <br>
                            <input id="yearInput" type="number" v-model="award.year" :max="currentYear" @input="onInput" class= "form-control" />
                          </div>
                          <div class="col-3">
                            <label for="">Institution:</label>
                            <br>
                            <input type="text" class= "form-control" v-model="award.institution">
                          </div>
                          <div class="col-3">
                            <label for="">Country:</label>
                            <br>
                            <input type="text" class= "form-control" v-model="award.country">
                          </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-6">
                          <label for="">Contribution of the awardee:</label>
                          <br>
                          <textarea class= "form-control" v-model="award.contributionAwardee" style="resize: none;" cols="30" rows="5"></textarea>
                      </div>
                      <div class="col-6">
                        <label for="">Progress Report: </label>
                        <br>
                        <select class="form-select" v-model="award.progressReport">
                          <option disabled value="">Select an option</option>
                          <option value="1">1</option>
                          <option value="2">2</option>
                          <option value="3">3</option>
                          <option value="4">4</option>
                          <option value="5">5</option>
                          <option value="6">6</option>
                          <option value="7">7</option>
                          <option value="8">8</option>
                          <option value="9">9</option>
                          </select>
                      </div>
                    </div>
                    <br>
                  </slot>
                </div>
                <div class="modal-footer">
                  <slot name="footer">
                    <label class="form-check-label"><input type="checkbox" class="form-check-"
                    v-model="draft"> Save as a draft</label>
                    <a v-if="draft == false" class="btn btn-continue float-end" @click="createAward()" :disabled="buttonDisable">
                      {{ buttonText }}
                    </a>
                    <a v-else class="btn btn-continue float-end" @click="guardarBorrador()" :disabled="buttonDisable">
                      Save draft
                    </a>
                  </slot>
                </div>
                <modalfotoperfil v-if="showFotoPerfil" @close="showFotoPerfil = false"></modalfotoperfil>
                <modalBookAutor v-if="showModalBookAutor" @close="showModalBookAutor = false" @submit="handleFormSubmit1" ></modalBookAutor>
                <modalChapterAutor v-if="showModalChapterAutor" @close="showModalChapterAutor = false" @submit="handleFormSubmit2"></modalChapterAutor>
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
import Multiselect from '@vueform/multiselect';

export default {
    components: { Multiselect, modalconfirmacion, modalalerta },
    mixins: [mixin],
    data: () => ({
      award:{
        awardeeName: '',
        awardName: '',
        year: '',
        contributionAwardee: '',
        institution: '',
        country: '',
        progressReport: '',
      },
      currentYear: new Date().getFullYear(),
      coauthor: false,
      draft: false,
      buttonDisable: false,
      errors:[],
      buttonText:'Save Award',
    }),
    methods: {
      async guardarBorrador(){
        const ok = await this.$refs.confirmation.show({
            title: 'Save draft',
            message: `¿Are you sure you want to save this award as a draft? this action cannot be undone.`,
            okButton: 'Save',
            cancelButton: 'Return'
          })
          if (ok) {
            let award = {
              status: 'Draft',
              awardeeName: this.userID,
              awardName: this.award.awardName,
              year: this.award.year,
              contributionAwardee: this.award.contributionAwardee,
              institution: this.award.institution,
              country: this.award.country,
              progressReport: this.award.progressReport,
            };
            axios.post("api/awards", award ).then((result) => {
              this.toast.success("Draft saved successfully!", {
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
            .catch((error)=> {
              if (error.response.status == 422){
                this.errors = error.response.data.errors;
                this.toast.warning('There is an invalid value.', {
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
      onInput(event) {
        const input = event.target;
        // Limitar el año a 4 dígitos
        this.isiPublication.yearPublished = input.value.slice(0, 4);
      },
      cerrarModal(){
        const elem = this.$refs.closeBtn;
        this.$emit('recarga');
        elem.click();
      },
      capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
      },
      async createAward() {
        this.errors = [];
        for (const item in this.award){
          if(this.award[item] === "" || this.award[item] === 0 || this.award[item] == null){
              if(item != 'awardeeName'){
                this.errors.push(item);
              }
            }
        }

        var mensaje = ""
        if (this.errors.length != 0){
          this.errors.forEach(item => {
            if(item == 'awardName'){
              mensaje =   mensaje + "The field Award Name is required" + "\n";
            }else if(item == 'contributionAwardee'){
              mensaje =   mensaje + "The field Contribution Awardee is required" + "\n";
            }else if(item == 'chapterTitle'){
              mensaje =   mensaje + "The field Chapter Title is required" + "\n";
            }else if(item == 'progressReport'){
              mensaje =   mensaje + "The field Progress Report is required" + "\n";
            }else{
              mensaje =   mensaje + "The field " + this.capitalizeFirstLetter(item) + " is required" + "\n" 
            }
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
          const ok = await this.$refs.confirmation.show({
            title: 'Save Award',
            message: `¿Are you sure you want to save this award? This action cannot be undone.`,
            okButton: 'Save',
            cancelButton: 'Return'
          })
          if (ok) {

            let award = {
              status: 'Finished',
              awardeeName: this.userID,
              awardName: this.award.awardName,
              year: this.award.year,
              contributionAwardee: this.award.contributionAwardee,
              institution: this.award.institution,
              country: this.award.country,
              progressReport: this.award.progressReport,
            };
            this.buttonDisable = true;
            this.buttonText = 'Saving...';
            axios.post("api/awards", award ).then((result) => {
              this.toast.success("Award saved successfully!", {
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
            .catch((error)=> {
              if (error.response.status == 422){
                this.errors = error.response.data.errors;
                this.toast.warning('There is an invalid value.', {
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
        }
      },
    },
}
</script>

<style src="@vueform/multiselect/themes/default.css"></style>