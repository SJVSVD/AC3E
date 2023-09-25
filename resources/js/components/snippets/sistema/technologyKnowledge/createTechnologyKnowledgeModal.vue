<template>
  <transition name="modalcrear">
    <div name="modal">
      <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container-s">
              <div class="modal-header pb-1 fw-bold" style="color: #444444;">
                <slot name="header">
                    New Technology and knowledge transfer 
                </slot>
                <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
              </div>
              <div class="modal-body">
                <slot name="body">
                  <div class="row">
                      <div class="text-uppercase pb-2">Category of transfer:</div>
                      <div class="col-4">
                          <div class="form-check pt-2 ">
                            <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                  v-model="technologyKnowledge.technologyTransfer"> Technology transfer</label>
                          </div>
                      </div>
                      <div class="col-4">
                          <div class="form-check pt-2 ">
                            <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                  v-model="technologyKnowledge.knowledgeTransfer"> Knowledge transfer</label>
                          </div>
                      </div>
                      <div class="col-4">
                            <label for="">Type of transfer:</label>
                            <select class="form-select" v-model="technologyKnowledge.typeOfTransfer">
                              <option disabled value="">Select a type</option>
                              <option value="Spin-off">Spin-off</option>
                              <option value="Licenses">Licenses</option>
                              <option value="Technology transfer agreement">Technology transfer agreement</option>
                              <option value="Services(mandatory for regional centers)">Services(mandatory for regional centers)</option>
                              <option value="Sale of technology transfer product">Sale of technology transfer product</option>
                              <option value="Public sector">Public sector</option>
                              <option value="Small scale producers">Small scale producers</option>
                              <option value="Community,Social-ONG">Community,Social-ONG</option>
                              <option value="Technical professional sector">Technical professional sector</option>
                              <option value="Other">Other</option>
                              </select>
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-3">
                        <label for="">Beneficiary institution:</label>
                        <br>
                        <input type="text" class= "form-control" v-model="technologyKnowledge.nameOfBeneficiary">
                      </div>
                      <div class="col-3">
                        <label for="">Country:</label>
                        <br>
                        <input type="text" class= "form-control" v-model="technologyKnowledge.country">
                      </div>
                      <div class="col-3">
                        <label for="">City:</label>
                        <br>
                        <input type="text" class= "form-control" v-model="technologyKnowledge.city">
                      </div>
                      <div class="col-3">
                        <label for="">Place/region:</label>
                        <br>
                        <input type="text" class= "form-control" v-model="technologyKnowledge.placeRegion">
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-2">
                          <label for="">Year: </label>
                          <br>
                          <input id="yearInput1" type="number" v-model="technologyKnowledge.year" :max="currentYear" @input="onInput1" class= "form-control" />
                      </div>
                      <div class="col-6">
                        <label for="">Name of research line:</label>
                        <Multiselect
                          placeholder="Select the options"
                          v-model="technologyKnowledge.nameOfResearch"
                          limit=4
                          :searchable="true"
                          :close-on-select="false"
                          :createTag="true"
                          :options="options1"
                          mode="tags"
                          label="name"
                          trackBy="name"
                          :object="true"
                        />
                      </div>
                      <div class="col-4">
                        <label for="">Comments:</label>
                        <br>
                        <input type="text" class= "form-control" v-model="technologyKnowledge.comments">
                      </div>
                    </div>
                  </slot>
                </div>
                <div class="modal-footer">
                  <slot name="footer">
                    <label class="form-check-label"><input type="checkbox" class="form-check-"
                    v-model="draft"> Save as a draft</label>
                    <a v-if="draft == false" class="btn btn-continue float-end" @click="createPatent()" :disabled="buttonDisable">
                      {{ buttonText }}
                    </a>
                    <a v-else class="btn btn-continue float-end" @click="guardarBorrador()" :disabled="buttonDisable">
                      Save draft
                    </a>
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
import Multiselect from '@vueform/multiselect';

export default {
    components: { Multiselect, modalconfirmacion, modalalerta },
    mixins: [mixin],
    data: () => ({
      technologyKnowledge:{
        technologyTransfer: false,
        knowledgeTransfer: false,
        typeOfTransfer: '',
        nameOfBeneficiary: '',
        country: '',
        city: '',
        placeRegion: '',
        year: '',
        nameOfResearch: null,
        comments: '',
        progressReport: 9,
      },
      options1: [
        'Biomedical Systems',
        'Control and Automation',
        'Data Analytics and Artificial Intelligence',
        'Electrical Systems',
        'Energy Conversion and Power Systems',
        'Instrumentation',
      ],
      draft: false,
      buttonDisable: false,
      errors:[],
      buttonText:'Save transfer',
    }),
    methods: {
      onInput1(event) {
        const input = event.target;
        // Limitar el año a 4 dígitos
        this.technologyKnowledge.year = input.value.slice(0, 4);
      },
      calculateYears() {
        const currentYear = new Date().getFullYear();
        const startYear = currentYear - 20;
        this.years = Array.from({ length: 21 }, (_, index) => startYear + index);
      },
      async guardarBorrador(){
        const ok = await this.$refs.confirmation.show({
            title: 'Save draft',
            message: `¿Are you sure you want to save this Technology knowledge tranfer as a draft? this action cannot be undone.`,
            okButton: 'Save',
            cancelButton: 'Return'
          })
          if (ok) {
            var nameOfResearch1 = "";
            if (this.technologyKnowledge.nameOfResearch !== null){
              if (this.technologyKnowledge.nameOfResearch.length !== 0) {
                this.technologyKnowledge.nameOfResearch.forEach((nameOfResearch, index) => {
                  nameOfResearch1 += nameOfResearch.name;
                  if (index === this.technologyKnowledge.nameOfResearch.length - 1) {
                    nameOfResearch1 += '.';
                  } else {
                    nameOfResearch1 += ', ';
                  }
                });
              }
            }

            let technologyKnowledge = {
              status: 'Draft',
              idUsuario: this.userID,
              technologyTransfer: this.technologyKnowledge.technologyTransfer,
              knowledgeTransfer: this.technologyKnowledge.knowledgeTransfer,
              typeOfTransfer: this.technologyKnowledge.typeOfTransfer,
              nameOfBeneficiary: this.technologyKnowledge.nameOfBeneficiary,
              country: this.technologyKnowledge.country,
              city: this.technologyKnowledge.city,
              placeRegion: this.technologyKnowledge.placeRegion,
              year: this.technologyKnowledge.year,
              nameOfResearch: nameOfResearch1,
              comments: this.technologyKnowledge.comments,
              progressReport: this.technologyKnowledge.progressReport,
            };
            axios.post("api/technologyKnowledge", technologyKnowledge).then((result) => {
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
      cerrarModal(){
        const elem = this.$refs.closeBtn;
        this.$emit('recarga');
        elem.click();
      },
      capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
      },
      async createPatent() {
        this.errors = [];

        let categoryTransferErrorAdded = false;
        let transferSelected = false; // Variable para verificar si al menos uno de los dos checkboxes está seleccionado

        for (const item in this.technologyKnowledge) {
          if (this.technologyKnowledge[item] === "" || this.technologyKnowledge[item] === 0 || this.technologyKnowledge[item] == null || this.technologyKnowledge[item] == []) {
            if (item == 'technologyTransfer' || item == 'knowledgeTransfer') {
              if (this.technologyKnowledge.technologyTransfer || this.technologyKnowledge.knowledgeTransfer) {
                transferSelected = true; // Al menos uno de los checkboxes está seleccionado
              }
              if (!categoryTransferErrorAdded && !transferSelected) {
                this.errors.push('categoryTransfer');
                categoryTransferErrorAdded = true;
              }
            } else {
              this.errors.push(item);
            }
          }
        }

        // Ahora, después del bucle, verificamos si al menos uno de los checkboxes está seleccionado y, si no lo está, agregamos 'categoryTransfer'.
        if (!transferSelected) {
          this.errors.push('categoryTransfer');
        }


        var mensaje = ""
        if (this.errors.length != 0){
          this.errors.forEach(item => {
            if(item == 'categoryTransfer'){
              mensaje =   mensaje + "The field Category of transfer is required" + "\n";
            }else if(item == 'typeOfTransfer'){
              mensaje =   mensaje + "The field Type of transfer is required" + "\n";
            }else if(item == 'nameOfBeneficiary'){
              mensaje =   mensaje + "The field Name of beneficiary institution is required" + "\n";
            }else if(item == 'placeRegion'){
              mensaje =   mensaje + "The field Place/Region is required" + "\n";
            }else if(item == 'nameOfResearch'){
              mensaje =   mensaje + "The field Name of research is required" + "\n";
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
            title: 'Save Technology and knowledge transfer',
            message: `¿Are you sure you want to save this Technology and knowledge transfer? This action cannot be undone.`,
            okButton: 'Save',
            cancelButton: 'Return'
          })
          if (ok) {
            var nameOfResearch1 = "";
            if (this.technologyKnowledge.nameOfResearch !== null){
              if (this.technologyKnowledge.nameOfResearch.length !== 0) {
                this.technologyKnowledge.nameOfResearch.forEach((nameOfResearch, index) => {
                  nameOfResearch1 += nameOfResearch.name;
                  if (index === this.technologyKnowledge.nameOfResearch.length - 1) {
                    nameOfResearch1 += '.';
                  } else {
                    nameOfResearch1 += ', ';
                  }
                });
              }
            }

            let technologyKnowledge = {
              status: 'Finished',
              idUsuario: this.userID,
              technologyTransfer: this.technologyKnowledge.technologyTransfer,
              knowledgeTransfer: this.technologyKnowledge.knowledgeTransfer,
              typeOfTransfer: this.technologyKnowledge.typeOfTransfer,
              nameOfBeneficiary: this.technologyKnowledge.nameOfBeneficiary,
              country: this.technologyKnowledge.country,
              city: this.technologyKnowledge.city,
              placeRegion: this.technologyKnowledge.placeRegion,
              year: this.technologyKnowledge.year,
              nameOfResearch: nameOfResearch1,
              comments: this.technologyKnowledge.comments,
              progressReport: this.technologyKnowledge.progressReport,
            };
            axios.post("api/technologyKnowledge", technologyKnowledge).then((result) => {
              this.toast.success("Technology and knowledge transfer saved successfully!", {
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