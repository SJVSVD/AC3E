<template>
  <transition name="modalcrear">
    <div name="modal">
      <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container-s">
              <div class="modal-header pb-1 fw-bold" style="color: #444444;">
                <slot name="header">
                    Edit Technology and knowledge transfer
                </slot>
                <label for="">Progress year: {{ technologyKnowledge.progressReport }}</label>
                <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
              </div>
              <div class="modal-body">
                <slot name="body">
                  <div class="row">
                      <div class="text-uppercase pb-2">Category of transfer:<label for="" style="color: orange;">*</label></div>
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
                            <label for="" style="color: orange;">*</label>
                            <select class="form-select" v-model="technologyKnowledge.typeOfTransfer">
                              <option disabled :value="null">Select a type</option>
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
                      <div class="col-6">
                        <label for="">Description:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="technologyKnowledge.description">
                      </div>
                      <div class="col-6">
                        <label for="">Name of the institution involved:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="technologyKnowledge.nameOfInstitutionInvolved">
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-3">
                        <label for="">Beneficiary institution:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="technologyKnowledge.nameOfBeneficiary">
                      </div>
                      <div class="col-3">
                        <label for="">Country:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="technologyKnowledge.country">
                      </div>
                      <div class="col-3">
                        <label for="">City:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="technologyKnowledge.city">
                      </div>
                      <div class="col-3">
                        <label for="">Place/region:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="technologyKnowledge.placeRegion">
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-2">
                          <label for="">Year: </label>
                          <label for="" style="color: orange;">*</label>
                          <br>
                          <select class="form-select" id="selectYear" v-model="technologyKnowledge.year">
                          <option disabled :value="null">Select a year</option>
                          <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
                          </select>
                      </div>
                      <div class="col-6">
                        <label for="">Researcher involved:</label>
                        <label for="" style="color: orange;">*</label>
                        <Multiselect
                          placeholder="Select the options"
                          v-model="technologyKnowledge.researcherInvolved"
                          limit=4
                          :searchable="true"
                          :close-on-select="false"
                          :createTag="true"
                          :options="researchers2"
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
                    v-model="draft"> Edit as a draft</label>
                    <a v-if="draft == false" class="btn btn-continue float-end" @click="editTechnology()" :disabled="buttonDisable">
                      {{ buttonText }}
                    </a>
                    <a v-else class="btn btn-continue float-end" @click="guardarBorrador()" :disabled="buttonDisable">
                      Edit draft
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
        researcherInvolved: null,
        typeOfTransfer: '',
        nameOfBeneficiary: '',
        country: '',
        city: '',
        placeRegion: '',
        year: '',
        comments: '',
        progressReport: '',
      },
      id: '',
      draft: false,
      buttonDisable: false,
      researchers2: '',
      errors:[],
      buttonText:'Edit transfer',
    }),
    props:{
      technology1: Object,
    },
    created(){
      this.getUsuarios2();
      this.id = this.technology1.id;
      this.technologyKnowledge.technologyTransfer = this.technology1.technologyTransfer;
      this.technologyKnowledge.knowledgeTransfer = this.technology1.knowledgeTransfer;
      this.technologyKnowledge.typeOfTransfer = this.technology1.typeOfTransfer;
      this.technologyKnowledge.nameOfBeneficiary = this.technology1.nameOfBeneficiary;
      this.technologyKnowledge.country = this.technology1.country;
      this.technologyKnowledge.city = this.technology1.city;
      this.technologyKnowledge.placeRegion = this.technology1.placeRegion;
      this.technologyKnowledge.year = this.technology1.year;
      this.technologyKnowledge.comments = this.technology1.comments;
      this.technologyKnowledge.description = this.technology1.description;
      this.technologyKnowledge.nameOfInstitutionInvolved = this.technology1.nameOfInstitutionInvolved;
      this.technologyKnowledge.progressReport = this.technology1.progressReport;

      if (this.technology1.researcherInvolved != null) {
          const valoresSeparados1 = this.technology1.researcherInvolved.split(",");
          this.technologyKnowledge.researcherInvolved = valoresSeparados1.map((valor, index) => {
              valor = valor.trim();
              if (valor.endsWith('.')) {
                  valor = valor.slice(0, -1);
              }

              return { value: valor, name: valor };
          });
      }

      const currentYear = new Date().getFullYear();
      const startYear = 2000;
      const endYear = currentYear + 1;
      this.years = Array.from({ length: endYear - startYear + 1 }, (_, index) => startYear + index);
      this.selectedYear = currentYear;
      this.years.sort((a, b) => b - a);
    },
    methods: {
      getUsuarios2(){
        axios.get('api/researchers').then( response =>{
            this.researchers2 = response.data;
        }).catch(e=> console.log(e))
      },
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
            title: 'Edit draft',
            message: `¿Are you sure you want to edit this Technology knowledge tranfer as a draft? this action cannot be undone.`,
            okButton: 'Edit',
            cancelButton: 'Return'
          })
          if (ok) {

            var researcherInvolved1 = "";
            if (this.technologyKnowledge.researcherInvolved !== null){
              if (this.technologyKnowledge.researcherInvolved.length !== 0) {
                this.technologyKnowledge.researcherInvolved.forEach((researcherInvolved, index) => {
                  researcherInvolved1 += researcherInvolved.name;
                  if (index === this.technologyKnowledge.researcherInvolved.length - 1) {
                    researcherInvolved1 += '.';
                  } else {
                    researcherInvolved1 += ', ';
                  }
                });
              }
            }

            let technologyKnowledge = {
              status: 'Draft',
              description: this.technologyKnowledge.description,
              nameOfInstitutionInvolved: this.technologyKnowledge.nameOfInstitutionInvolved,
              researcherInvolved: researcherInvolved1,
              technologyTransfer: this.technologyKnowledge.technologyTransfer,
              knowledgeTransfer: this.technologyKnowledge.knowledgeTransfer,
              typeOfTransfer: this.technologyKnowledge.typeOfTransfer,
              nameOfBeneficiary: this.technologyKnowledge.nameOfBeneficiary,
              country: this.technologyKnowledge.country,
              city: this.technologyKnowledge.city,
              placeRegion: this.technologyKnowledge.placeRegion,
              year: this.technologyKnowledge.year,
              comments: this.technologyKnowledge.comments,
              progressReport: this.technologyKnowledge.progressReport,
            };
            axios.put(`api/technologyKnowledge/${this.id}`, technologyKnowledge ).then((result) => {
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
      cerrarModal(){
        const elem = this.$refs.closeBtn;
        this.$emit('recarga');
        elem.click();
      },
      capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
      },
      async editTechnology() {
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

        var contador = await axios.post('../api/verifyTechnology', this.technologyKnowledge).then(function(response) {
          return response.data;
        }.bind(this)).catch(function(e) {
          console.log(e);
        });
        if (contador > 0){
          this.errors.push('duplicated');
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
            }else if(item == 'duplicated'){
              mensaje =   mensaje + "There is already a post with the same data, please try again." + "\n";
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
            title: 'Edit Technology and knowledge transfer',
            message: `¿Are you sure you want to edit this Technology and knowledge transfer? This action cannot be undone.`,
            okButton: 'Edit',
            cancelButton: 'Return'
          })
          if (ok) {

            var researcherInvolved1 = "";
            if (this.technologyKnowledge.researcherInvolved !== null){
              if (this.technologyKnowledge.researcherInvolved.length !== 0) {
                this.technologyKnowledge.researcherInvolved.forEach((researcherInvolved, index) => {
                  researcherInvolved1 += researcherInvolved.name;
                  if (index === this.technologyKnowledge.researcherInvolved.length - 1) {
                    researcherInvolved1 += '.';
                  } else {
                    researcherInvolved1 += ', ';
                  }
                });
              }
            }

            let technologyKnowledge = {
              status: 'Finished',
              description: this.technologyKnowledge.description,
              nameOfInstitutionInvolved: this.technologyKnowledge.nameOfInstitutionInvolved,
              researcherInvolved: researcherInvolved1,
              technologyTransfer: this.technologyKnowledge.technologyTransfer,
              knowledgeTransfer: this.technologyKnowledge.knowledgeTransfer,
              typeOfTransfer: this.technologyKnowledge.typeOfTransfer,
              nameOfBeneficiary: this.technologyKnowledge.nameOfBeneficiary,
              country: this.technologyKnowledge.country,
              city: this.technologyKnowledge.city,
              placeRegion: this.technologyKnowledge.placeRegion,
              year: this.technologyKnowledge.year,
              comments: this.technologyKnowledge.comments,
              progressReport: this.technologyKnowledge.progressReport,
            };
            axios.put(`api/technologyKnowledge/${this.id}`, technologyKnowledge ).then((result) => {
              this.toast.success("Technology and knowledge transfer edited successfully!", {
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