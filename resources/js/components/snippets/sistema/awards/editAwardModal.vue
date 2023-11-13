<template>
  <transition name="modalcrear">
    <div name="modal">
      <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container-s">
              <div class="modal-header pb-1 fw-bold" style="color: #444444;">
                <slot name="header">
                    Edit Award
                </slot>
                <label for="">Progress year: {{ award.progressReport }}</label>
                <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
              </div>
              <div class="modal-body">
                <slot name="body">
                    <div class="row">
                          <div class="col-4">
                            <label for="">Award Name:</label>
                            <label for="" style="color: orange;">*</label>
                            <br>
                            <input type="text" class= "form-control" v-model="award.awardName">
                          </div>
                          <div class="col-2">
                            <label for="">Year:</label>
                            <label for="" style="color: orange;">*</label>
                            <br>
                            <select class="form-select" id="selectYear" v-model="award.year">
                            <option disabled :value="null">Select a year</option>
                            <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
                            </select>
                          </div>
                          <div class="col-3">
                            <label for="">Institution:</label>
                            <label for="" style="color: orange;">*</label>
                            <br>
                            <input type="text" class= "form-control" v-model="award.institution">
                          </div>
                          <div class="col-3">
                            <label for="">Country:</label>
                            <label for="" style="color: orange;">*</label>
                            <br>
                            <input type="text" class= "form-control" v-model="award.country">
                          </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-6">
                          <label for="">Contribution of the awardee:</label>
                          <label for="" style="color: orange;">*</label>
                          <br>
                          <textarea class= "form-control" v-model="award.contributionAwardee" style="resize: none;" cols="30" rows="5"></textarea>
                      </div>
                      <div class="col-6">
                          <label for="">Comments:</label>
                          <br>
                          <input type="text" class= "form-control" v-model="award.comments">
                      </div>
                    </div>
                    <br>
                  </slot>
                </div>
                <div class="modal-footer">
                  <slot name="footer">
                    <label class="form-check-label"><input type="checkbox" class="form-check-"
                    v-model="draft"> Edit as a draft</label>
                    <a v-if="draft == false" class="btn btn-continue float-end" @click="editAward()" :disabled="buttonDisable">
                      {{ buttonText }}
                    </a>
                    <a v-else class="btn btn-continue float-end" @click="guardarBorrador()" :disabled="buttonDisable">
                      Edit draft
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
      id: '',
      buttonDisable: false,
      errors:[],
      buttonText:'Edit Award',
    }),
    props:{
      award1: Object,
    },
    created(){
      this.id = this.award1.id;
      this.award.awardName = this.award1.awardName;
      this.award.year = this.award1.year;
      this.award.contributionAwardee = this.award1.contributionAwardee;
      this.award.institution = this.award1.institution;
      this.award.country = this.award1.country;
      this.award.progressReport = this.award1.progressReport;
      this.award.comments = this.award1.comments;
      const currentYear = new Date().getFullYear();
      const startYear = 2000;
      const endYear = currentYear + 1;
      this.years = Array.from({ length: endYear - startYear + 1 }, (_, index) => startYear + index);
      this.selectedYear = currentYear;
      this.years.sort((a, b) => b - a); 
    },
    methods: {
      async guardarBorrador(){
        const ok = await this.$refs.confirmation.show({
            title: 'Edit draft',
            message: `¿Are you sure you want to edit this award as a draft? this action cannot be undone.`,
            okButton: 'Edit',
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
              comments: this.award.comments,
            };
            axios.put(`api/awards/${this.id}`, award).then((result) => {
              this.toast.success("Draft edited successfully!", {
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
        this.award.year = input.value.slice(0, 4);
      },
      cerrarModal(){
        const elem = this.$refs.closeBtn;
        this.$emit('recarga');
        elem.click();
      },
      capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
      },
      async editAward() {
        this.errors = [];
        const itemsToSkip = [
          'awardeeName',
          'comments'
        ];
        for (const item in this.award) {
            const skipItem = itemsToSkip.includes(item);
            if (!skipItem && (this.award[item] === "" || this.award[item] === 0 || this.award[item] == null)) {
                this.errors.push(item);
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
            title: 'Edit Award',
            message: `¿Are you sure you want to Edit this award? This action cannot be undone.`,
            okButton: 'Edit',
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
              comments: this.award.comments
            };
            this.buttonDisable = true;
            this.buttonText = 'Editing...';
            axios.put(`api/awards/${this.id}`, award).then((result) => {
              this.toast.success("Award edited successfully!", {
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