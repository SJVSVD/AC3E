<template>
  <transition name="modalcrear">
    <div name="modal">
      <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container-s">
              <div class="modal-header pb-1 fw-bold" style="color: #444444;">
                <slot name="header">
                    Edit Patent 
                </slot>
                <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
              </div>
              <div class="modal-body">
                <slot name="body">
                    <div class="row">
                          <div class="col-6">
                            <label for="">Ip type:</label>
                            <select class="form-select" v-model="patent.ipType">
                              <option disabled :value="null">Select a type</option>
                              <option value="Goods">Goods</option>
                              <option value="Services">Services</option>
                              <option value="Commercial establishments">Commercial establishments</option>
                              <option value="Industrial establishments">Industrial establishments</option>
                              <option value="Slogans">Slogans</option>
                              <option value="Collective trademarks">Collective trademarks</option>
                              <option value="Certification trademarks">Certification trademarks</option>
                              <option value="Invention patent">Invention patent</option>
                              <option value="Utility model">Utility model</option>
                              <option value="Design (Industrial design and industrial drawing)">Design (Industrial design and industrial drawing)</option>
                              <option value="Layout designs (Topographies) of integrated circuits">Layout designs (Topographies) of integrated circuits</option>
                              <option value="Geographical indication (GI)">Geographical indication (GI)</option>
                              <option value="Appelation of origin">Appelation of origin</option>
                              <option value="Copyright">Copyright</option>
                              <option value="Plant varieties">Plant varieties</option>
                              <option value="Traditional knowledge and genetic resources">Traditional knowledge and genetic resources</option>
                              <option value="Others">Others</option>
                              </select>
                          </div>
                          <div class="col-6">
                            <label for="">Authors:</label>
                            <Multiselect
                              placeholder="Select the Authors"
                              v-model="patent.authors"
                              limit=4
                              :searchable="true"
                              :close-on-select="false"
                              :createTag="true"
                              :options="researchers"
                              mode="tags"
                              label="name"
                              trackBy="id"
                              :object="true"
                            />
                          </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-6">
                        <label for="">Name of research line:</label>
                        <Multiselect
                          placeholder="Select the options"
                          v-model="patent.nameOfResearch"
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
                      <div class="col-3">
                        <label for="">Institution owner(s):</label>
                        <br>
                        <input type="text" class= "form-control" v-model="patent.institutionOwner">
                      </div>
                      <div class="col-3">
                        <label for="">Country of registration:</label>
                        <br>
                        <input type="text" class= "form-control" v-model="patent.countryOfRegistration">
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-3">
                        <label for="">Application date:</label>
                        <br>
                        <input type="date" class= "form-control" v-model="patent.applicationDate">
                      </div>
                      <div class="col-3">
                        <label for="">Grant date:</label>
                        <br>
                        <input type="date" class= "form-control" v-model="patent.grantDate">
                      </div>
                      <div class="col-3">
                        <label for="">Application status:</label>
                        <select class="form-select" v-model="patent.applicationStatus">
                          <option disabled :value="null">Select a type</option>
                          <option value="In progress">In progress</option>
                          <option value="Approved">Approved</option>
                          </select>
                      </div>
                      <div class="col-3">
                        <label for="">Registration number:</label>
                        <br>
                        <input type="text" class= "form-control" v-model="patent.registrationNumber">
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-3">
                        <label for="">State:</label>
                        <select class="form-select" v-model="patent.state">
                          <option disabled :value="null">Select a type</option>
                          <option value="Activities initiated during the funding period">Activities initiated during the funding period</option>
                          <option value="Activities were continued as result of the funding">Activities were continued as result of the funding</option>
                          </select>
                      </div>
                      <div class="col-6">
                        <label for="">Researcher involved:</label>
                        <Multiselect
                          placeholder="Select the researchers"
                          v-model="patent.researcherInvolved"
                          limit=4
                          :searchable="true"
                          :close-on-select="false"
                          :createTag="true"
                          :options="researchers"
                          mode="tags"
                          label="name"
                          trackBy="id"
                          :object="true"
                        />
                      </div>
                      <div class="col-3">
                        <label for="">Comments:</label>
                        <br>
                        <input type="text" class= "form-control" v-model="patent.comments">
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
      patent:{
        ipType: '',
        authors: null,
        nameOfResearch: null,
        institutionOwner: '',
        countryOfRegistration: '',
        applicationDate: '',
        grantDate: '',
        applicationStatus: '',
        registrationNumber: '',
        state: '',
        researcherInvolved: null,
        comments: '',
        progressReport: 9,
      },
      other: '',
      draft: false,
      researchers: '',
      id: '',
      buttonDisable: false,
      errors:[],
      buttonText:'Save Activity',
      options1: [
        'Biomedical Systems',
        'Control and Automation',
        'Data Analytics and Artificial Intelligence',
        'Electrical Systems',
        'Energy Conversion and Power Systems',
        'Instrumentation',
      ],
    }),
    mounted(){
      this.getUsuarios();
    },
    props:{
      patent1: Object,
    },
    created(){
      this.id = this.patent1.id;
      this.patent.ipType = this.patent1.ipType;
      this.patent.institutionOwner = this.patent1.institutionOwner;
      this.patent.countryOfRegistration = this.patent1.countryOfRegistration;
      this.patent.applicationDate = this.patent1.applicationDate;
      this.patent.grantDate = this.patent1.grantDate;
      this.patent.applicationStatus = this.patent1.applicationStatus;
      this.patent.registrationNumber = this.patent1.registrationNumber;
      this.patent.state = this.patent1.state;
      this.patent.comments = this.patent1.comments;

      if (this.patent1.authors != null) {
          const valoresSeparados1 = this.patent1.authors.split(",");
          this.patent.authors = valoresSeparados1.map((valor, index) => {
              valor = valor.trim();
              if (valor.endsWith('.')) {
                  valor = valor.slice(0, -1);
              }

              return { value: valor, name: valor };
          });
      }

      if (this.patent1.nameOfResearch != null) {
          const valoresSeparados1 = this.patent1.nameOfResearch.split(",");
          this.patent.nameOfResearch = valoresSeparados1.map((valor, index) => {
              valor = valor.trim();
              if (valor.endsWith('.')) {
                  valor = valor.slice(0, -1);
              }

              return { value: valor, name: valor };
          });
      }

      if (this.patent1.researcherInvolved != null) {
          const valoresSeparados1 = this.patent1.researcherInvolved.split(",");
          this.patent.researcherInvolved = valoresSeparados1.map((valor, index) => {
              valor = valor.trim();
              if (valor.endsWith('.')) {
                  valor = valor.slice(0, -1);
              }

              return { value: valor, name: valor };
          });
      }

    },
    methods: {
      getUsuarios(){
        axios.get('api/researchers').then( response =>{
            this.researchers = response.data;
        }).catch(e=> console.log(e))
      },
      clearFileInput() {
        this.$refs.fileInput.value = '';
      },
      async getFile(e){
        this.participationSc.file = e.target.files[0];
      },
      async guardarBorrador(){
        const ok = await this.$refs.confirmation.show({
            title: 'Edit draft',
            message: `¿Are you sure you want to edit this Patent as a draft? this action cannot be undone.`,
            okButton: 'Edit',
            cancelButton: 'Return'
          })
          if (ok) {
            var nameAuthors = "";
            if (this.patent.authors !== null){
              if (this.patent.authors.length !== 0) {
                this.patent.authors.forEach((authors, index) => {
                  nameAuthors += authors.name;
                  if (index === this.patent.authors.length - 1) {
                    nameAuthors += '.';
                  } else {
                    nameAuthors += ', ';
                  }
                });
              }
            }

            var nameOfResearch1 = "";
            if (this.patent.nameOfResearch !== null){
              if (this.patent.nameOfResearch.length !== 0) {
                this.patent.nameOfResearch.forEach((nameOfResearch, index) => {
                  nameOfResearch1 += nameOfResearch.name;
                  if (index === this.patent.nameOfResearch.length - 1) {
                    nameOfResearch1 += '.';
                  } else {
                    nameOfResearch1 += ', ';
                  }
                });
              }
            }

            var researcherInvolved1 = "";
            if (this.patent.researcherInvolved !== null){
              if (this.patent.researcherInvolved.length !== 0) {
                this.patent.researcherInvolved.forEach((researcherInvolved, index) => {
                  researcherInvolved1 += researcherInvolved.name;
                  if (index === this.patent.researcherInvolved.length - 1) {
                    researcherInvolved1 += '.';
                  } else {
                    researcherInvolved1 += ', ';
                  }
                });
              }
            }

            let patent = {
              status: 'Draft',
              ipType: this.patent.ipType,
              authors: nameAuthors,
              nameOfResearch: nameOfResearch1,
              institutionOwner: this.patent.institutionOwner,
              countryOfRegistration: this.patent.countryOfRegistration,
              applicationDate: this.patent.applicationDate,
              grantDate: this.patent.grantDate,
              applicationStatus: this.patent.applicationStatus,
              registrationNumber: this.patent.registrationNumber,
              state: this.patent.state,
              researcherInvolved: researcherInvolved1,
              comments: this.patent.comments,
              progressReport: this.patent.progressReport,
            };
            axios.put(`api/patents/${this.id}`, patent).then((result) => {
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
      async createPatent() {
        this.errors = [];

        for (const item in this.patent){
          if(this.patent[item] === "" || this.patent[item] === 0 || this.patent[item] == null || this.patent[item] == []){
                this.errors.push(item);
            }
        }

        var mensaje = ""
        if (this.errors.length != 0){
          this.errors.forEach(item => {
            if(item == 'ipType'){
              mensaje =   mensaje + "The field Ip Type is required" + "\n";
            }else if(item == 'nameOfResearch'){
              mensaje =   mensaje + "The field Name of research line is required" + "\n";
            }else if(item == 'institutionOwner'){
              mensaje =   mensaje + "The field Institution owners is required" + "\n";
            }else if(item == 'countryOfRegistration'){
              mensaje =   mensaje + "The field Country of registration is required" + "\n";
            }else if(item == 'applicationDate'){
              mensaje =   mensaje + "The field Application date is required" + "\n";
            }else if(item == 'grantDate'){
              mensaje =   mensaje + "The field Grant date is required" + "\n";
            }else if(item == 'applicationStatus'){
              mensaje =   mensaje + "The field Application status is required" + "\n";
            }else if(item == 'registrationNumber'){
              mensaje =   mensaje + "The field Registration number is required" + "\n";
            }else if(item == 'researcherInvolved'){
              mensaje =   mensaje + "The field Researchers involved is required" + "\n";
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
            title: 'Save Patent',
            message: `¿Are you sure you want to save this Patent? This action cannot be undone.`,
            okButton: 'Save',
            cancelButton: 'Return'
          })
          if (ok) {
            var nameAuthors = "";
            if (this.patent.authors !== null){
              if (this.patent.authors.length !== 0) {
                this.patent.authors.forEach((authors, index) => {
                  nameAuthors += authors.name;
                  if (index === this.patent.authors.length - 1) {
                    nameAuthors += '.';
                  } else {
                    nameAuthors += ', ';
                  }
                });
              }
            }

            var nameOfResearch1 = "";
            if (this.patent.nameOfResearch !== null){
              if (this.patent.nameOfResearch.length !== 0) {
                this.patent.nameOfResearch.forEach((nameOfResearch, index) => {
                  nameOfResearch1 += nameOfResearch.name;
                  if (index === this.patent.nameOfResearch.length - 1) {
                    nameOfResearch1 += '.';
                  } else {
                    nameOfResearch1 += ', ';
                  }
                });
              }
            }

            var researcherInvolved1 = "";
            if (this.patent.researcherInvolved !== null){
              if (this.patent.researcherInvolved.length !== 0) {
                this.patent.researcherInvolved.forEach((researcherInvolved, index) => {
                  researcherInvolved1 += researcherInvolved.name;
                  if (index === this.patent.researcherInvolved.length - 1) {
                    researcherInvolved1 += '.';
                  } else {
                    researcherInvolved1 += ', ';
                  }
                });
              }
            }

            let patent = {
              status: 'Finished',
              ipType: this.patent.ipType,
              authors: nameAuthors,
              nameOfResearch: nameOfResearch1,
              institutionOwner: this.patent.institutionOwner,
              countryOfRegistration: this.patent.countryOfRegistration,
              applicationDate: this.patent.applicationDate,
              grantDate: this.patent.grantDate,
              applicationStatus: this.patent.applicationStatus,
              registrationNumber: this.patent.registrationNumber,
              state: this.patent.state,
              researcherInvolved: researcherInvolved1,
              comments: this.patent.comments,
              progressReport: this.patent.progressReport,
            };
            axios.put(`api/patents/${this.id}`, patent).then((result) => {
              this.toast.success("Patent edited successfully!", {
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