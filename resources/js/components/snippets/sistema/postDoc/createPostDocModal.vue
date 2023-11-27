<template>
  <transition name="modalcrear">
    <div name="modal">
      <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container-s">
              <div class="modal-header pb-1 fw-bold" style="color: #444444;">
                <slot name="header">
                    New Postdoctoral fellow
                </slot>
                <label for="">Progress year: {{ postDoc.progressReport }}</label>
                <label v-if="is('Administrator')" class="col-5 m-0"> Researcher: <label class="fw-normal" style="font-size: 14px;">
                  <select class="form-select" v-model="idResearcher">
                    <option disabled value="">Select a researcher</option>
                    <option v-for="researcher in usuarios" v-bind:key="researcher.id" v-bind:value="researcher.id">
                      {{ researcher.name }}
                    </option>
                    </select>
                  </label>
                </label>
                <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
              </div>
              <div class="modal-body">
                <slot name="body">
                  <div class="row">
                      <div class="col-3">
                        <label for="">Name of postdoc fellow:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="postDoc.nameOfPostdoc">
                      </div>
                      <div class="col-3">
                          <label for="">Gender: </label>
                          <label for="" style="color: orange;">*</label>
                          <br>
                          <select class="form-select" v-model="postDoc.gender">
                            <option disabled value="">Select a Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Non binary">Non binary</option>
                            <option value="Other">Other</option>
                            </select>
                      </div>
                      <div class="col-3">
                          <label for="">Identification: </label>
                          <label for="" style="color: orange;">*</label>
                          <br>
                          <select class="form-select" @change="postDoc.run = ''; postDoc.passport = '';" v-model="postDoc.identification">
                            <option disabled value="">Select One</option>
                            <option value="run">Run</option>
                            <option value="passport">Passport</option>
                          </select>
                      </div>                        
                      <div v-if="postDoc.identification == 'run'" class="col-3">
                          <label for="">Run: </label>
                          <label for="" style="color: orange;">*</label>
                          <br>
                          <input type="text" class= "form-control" v-on:input="validateInput" v-on:keypress="isNumberOrDash" @keyup="checkRut()" v-model="postDoc.run">
                      </div>
                      <div v-if="postDoc.identification == 'passport'" class="col-3">
                          <label for="">Passport: </label>
                          <label for="" style="color: orange;">*</label>
                          <br>
                          <input type="text" class= "form-control" v-model="postDoc.passport">
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-4">
                        <label for="">Research topic:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="postDoc.researchTopic">
                      </div>
                      <div class="col-4">
                          <label for="">Start year: </label>
                          <label for="" style="color: orange;">*</label>
                          <br>
                          <select class="form-select" id="selectYear" v-model="postDoc.startYear">
                          <option disabled value="">Select a year</option>
                          <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
                        </select>
                      </div>
                      <div class="col-4">
                          <label for="">Ending year: </label>
                          <label for="" style="color: orange;">*</label>
                          <br>
                          <select class="form-select" id="selectYear" v-model="postDoc.endingYear">
                          <option disabled value="">Select a year</option>
                          <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
                          </select>
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-6">
                        <label for="">Supervisor name:</label>
                        <label for="" style="color: orange;">*</label>
                        <Multiselect
                          placeholder="Select the options"
                          v-model="postDoc.supervisorName"
                          limit=4
                          :searchable="true"
                          :close-on-select="false"
                          :createTag="true"
                          :options="researchers"
                          mode="tags"
                          label="name"
                          trackBy="name"
                          :object="true"
                        />
                      </div>
                      <div class="col-6">
                        <label for="">Resources provided by the center:</label>
                        <label for="" style="color: orange;">*</label>
                        <Multiselect
                          placeholder="Select the options"
                          v-model="postDoc.resourcesProvided"
                          limit=4
                          :searchable="true"
                          :close-on-select="false"
                          :createTag="true"
                          :options="options2"
                          mode="tags"
                          label="name"
                          trackBy="name"
                          :object="true"
                        />
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-6">
                        <label for="">Funding source:</label>
                        <label for="" style="color: orange;">*</label>
                        <Multiselect
                          placeholder="Select the options"
                          v-model="postDoc.fundingSource"
                          limit=4
                          :searchable="true"
                          :close-on-select="false"
                          :createTag="true"
                          :options="options3"
                          mode="tags"
                          label="name"
                          trackBy="name"
                          :object="true"
                        />
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="text-uppercase pb-2">Postdoctoral inserted:</div>
                      <div class="col-3">
                          <div class="form-check pt-2 ">
                            <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                  v-model="postDoc.privateSector" @change="checkInputs('privateSector')"> Private sector</label>
                          </div>
                      </div>
                      <div class="col-3">
                          <div class="form-check pt-2 ">
                            <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                  v-model="postDoc.academy1" @change="checkInputs('private')"> Academy</label>
                          </div>
                      </div>
                      <div class="col-2">
                          <div class="form-check pt-2 ">
                            <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                  v-model="postDoc.business" @change="checkInputs('private')"> Business </label>
                          </div>
                      </div>
                      <div class="col-4">
                          <div class="form-check pt-2 ">
                            <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                  v-model="postDoc.ownEntrepreneurship" @change="checkInputs('private')"> Own entrepreneurship </label>
                          </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-4">
                          <div class="form-check pt-2 ">
                            <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                  v-model="postDoc.publicSector"  @change="checkInputs('publicSector')"> Public sector</label>
                          </div>
                      </div>
                      <div class="col-4">
                          <div class="form-check pt-2 ">
                            <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                  v-model="postDoc.government" @change="checkInputs('public')"> Government</label>
                          </div>
                      </div>
                      <div class="col-4">
                          <div class="form-check pt-2 ">
                            <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                  v-model="postDoc.academy2" @change="checkInputs('public')"> Academy </label>
                          </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-4">
                          <div class="form-check pt-2 ">
                            <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                  v-model="postDoc.socialOng"> Social-ONG </label>
                          </div>
                      </div>
                      <div class="col-4">
                          <div class="form-check pt-2 ">
                            <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                  v-model="postDoc.inTheCenter"> In the center </label>
                          </div>
                      </div>
                      <div class="col-4">
                          <div class="form-check pt-2 ">
                            <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                  v-model="postDoc.noneOfTheAbove"> None of the above </label>
                          </div>
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-4">
                        <label for="">Personal email:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="postDoc.personalEmail">
                      </div>
                      <div class="col-4">
                        <label for="">Institution where it was inserted:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="postDoc.institutionName">
                      </div>
                      <div class="col-4">
                        <label for="">Comments:</label>
                        <br>
                        <input type="text" class= "form-control" v-model="postDoc.comments">
                      </div>
                    </div>
                    <br>
                  </slot>
                </div>
                <div class="modal-footer">
                  <slot name="footer">
                    <label class="form-check-label"><input type="checkbox" class="form-check-"
                    v-model="draft"> Save as a draft</label>
                    <a v-if="draft == false" class="btn btn-continue float-end" @click="createPostDoc()" :disabled="buttonDisable">
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
      postDoc:{
        nameOfPostdoc: '',
        identification: '',
        passport: "",
        run: "",
        gender: '',
        researchTopic: '',
        personalEmail: '',
        supervisorName: null,
        resourcesProvided: null,
        fundingSource: null,
        startYear: '',
        endingYear: '',
        privateSector: 0,
        academy1: 0,
        business: 0,
        ownEntrepreneurship: 0,
        publicSector: 0,
        government: 0,
        academy2: 0,
        socialOng: 0,
        inTheCenter: 0,
        noneOfTheAbove: 0,
        institutionName: '',
        comments: '',
        progressReport: '',
      },
      options3: [
        'Basal Financing Program Funding',
        'Other sources',
      ],
      options2: [
        'Equipment',
        'Information',
        'Infrastructure',
        'Other',
      ],
      draft: false,
      researchers: '',
      usuarios: [],
      idResearcher: '',
      buttonDisable: false,
      errors:[],
      buttonText:'Save fellow',
    }),
    mounted(){
      this.getUsuarios();
      this.getProgressReport();
      this.getUsuarios2();
      const currentYear = new Date().getFullYear();
      const startYear = 2000;
      const endYear = currentYear + 1;
      this.years = Array.from({ length: endYear - startYear + 1 }, (_, index) => startYear + index);
      this.selectedYear = currentYear;
      this.years.sort((a, b) => b - a);
    },
    methods: {
      getProgressReport(){
        axios.get('api/showProgressReport').then( response =>{
            this.postDoc.progressReport = response.data;
        }).catch(e=> console.log(e))
      },
      checkInputs(input) {
        if (input === 'private') {
          if (this.postDoc.academy1 || this.postDoc.business || this.postDoc.ownEntrepreneurship) {
            this.postDoc.privateSector = 1;
          } else {
            this.postDoc.privateSector = 0;
          }
        }

        if (input === 'public') {
          if (this.postDoc.government || this.postDoc.academy2) {
            this.postDoc.publicSector = 1;
          } else {
            this.postDoc.publicSector = 0;
          }
        }

        if (input === 'privateSector') {
          if (this.postDoc.privateSector) {
            this.postDoc.academy1 = 1;
            this.postDoc.business = 1;
            this.postDoc.ownEntrepreneurship = 1;
          } else {
            this.postDoc.academy1 = 0;
            this.postDoc.business = 0;
            this.postDoc.ownEntrepreneurship = 0;
          }
        }

        if (input === 'publicSector') {
          if (this.postDoc.publicSector) {
            this.postDoc.government = 1;
            this.postDoc.academy2 = 1;
            this.postDoc.education = 1;
          } else {
            this.postDoc.government = 0;
            this.postDoc.academy2 = 0;
          }
        }
      },
      getUsuarios2(){
        axios.get('api/usuarios').then( response =>{
            this.usuarios = response.data;
        }).catch(e=> console.log(e))
      },
      getUsuarios(){
        axios.get('api/researchers').then( response =>{
            this.researchers = response.data;
        }).catch(e=> console.log(e))
      },
      checkRut() {
        if (this.postDoc.run != ''){
          this.postDoc.run.replace('.','');
          var valor = this.postDoc.run.replace('.','');
          valor = valor.replace('-','');
          var cuerpo = valor.slice(0,-1);
          var dv = valor.slice(-1).toUpperCase();

          this.postDoc.run = cuerpo + '-'+ dv;
        }
      },
      validateInput() {
        this.postDoc.run = this.postDoc.run.replace(/[^0-9Kk\-]/g, '').replace(/(\-)\-+/g, '$1');
      },
      isNumberOrDash(event) {
        const charCode = event.which ? event.which : event.keyCode;
        const isK = charCode === 107 || charCode === 75;

        if (charCode === 45 && this.postDoc.run.indexOf('-') !== -1) {
          event.preventDefault();
        } else if (
          (charCode > 31 && charCode < 48) ||
          (charCode > 57 && charCode < 75) ||
          (charCode > 75 && charCode < 107) ||
          (charCode > 107 && charCode !== 109)
        ) {
          event.preventDefault();
        }
      },
      onInput2(event) {
        const input = event.target;
        // Limitar el año a 4 dígitos
        this.postDoc.endingYear = input.value.slice(0, 4);
      },
      onInput1(event) {
        const input = event.target;
        // Limitar el año a 4 dígitos
        this.postDoc.startYear = input.value.slice(0, 4);
      },
      calculateYears() {
        const currentYear = new Date().getFullYear();
        const startYear = currentYear - 20;
        this.years = Array.from({ length: 21 }, (_, index) => startYear + index);
      },
      async guardarBorrador(){
        const ok = await this.$refs.confirmation.show({
            title: 'Save draft',
            message: `¿Are you sure you want to save this Postdoctoral fellow as a draft? this action cannot be undone.`,
            okButton: 'Save',
            cancelButton: 'Return'
          })
          if (ok) {

            var runOrPassport1 = '';
            if(this.postDoc.identification == 'run'){
              runOrPassport1 = this.formatoRut(this.postDoc.run);
            }else{
              runOrPassport1 = this.postDoc.passport;
            }

            var supervisorName1 = "";
            if (this.postDoc.supervisorName !== null){
              if (this.postDoc.supervisorName.length !== 0) {
                this.postDoc.supervisorName.forEach((supervisorName, index) => {
                  supervisorName1 += supervisorName.name;
                  if (index === this.postDoc.supervisorName.length - 1) {
                    supervisorName1 += '.';
                  } else {
                    supervisorName1 += ', ';
                  }
                });
              }
            }

            var resourcesProvided1 = "";
            if (this.postDoc.resourcesProvided !== null){
              if (this.postDoc.resourcesProvided.length !== 0) {
                this.postDoc.resourcesProvided.forEach((resourcesProvided, index) => {
                  resourcesProvided1 += resourcesProvided.name;
                  if (index === this.postDoc.resourcesProvided.length - 1) {
                    resourcesProvided1 += '.';
                  } else {
                    resourcesProvided1 += ', ';
                  }
                });
              }
            }

            var fundingSource1 = "";
            if (this.postDoc.fundingSource !== null){
              if (this.postDoc.fundingSource.length !== 0) {
                this.postDoc.fundingSource.forEach((fundingSource, index) => {
                  fundingSource1 += fundingSource.name;
                  if (index === this.postDoc.fundingSource.length - 1) {
                    fundingSource1 += '.';
                  } else {
                    fundingSource1 += ', ';
                  }
                });
              }
            }
            var idUser1 = ''
            if(this.idResearcher != ''){
              idUser1 = this.idResearcher;
            }else{
              idUser1 = this.userID;
            }
            let postDoc = {
              status: 'Draft',
              idUsuario: idUser1,
              nameOfPostdoc: this.postDoc.nameOfPostdoc,
              identification: this.postDoc.identification,
              runOrPassport: runOrPassport1,
              gender: this.postDoc.gender,
              personalEmail: this.postDoc.personalEmail,
              researchTopic: this.postDoc.researchTopic,
              supervisorName: supervisorName1,
              resourcesProvided: resourcesProvided1,
              fundingSource: fundingSource1,
              startYear: this.postDoc.startYear,
              endingYear: this.postDoc.endingYear,
              privateSector: this.postDoc.privateSector,
              academy1: this.postDoc.academy1,
              business: this.postDoc.business,
              ownEntrepreneurship: this.postDoc.ownEntrepreneurship,
              publicSector: this.postDoc.publicSector,
              government: this.postDoc.government,
              academy2: this.postDoc.academy2,
              socialOng: this.postDoc.socialOng,
              inTheCenter: this.postDoc.inTheCenter,
              noneOfTheAbove: this.postDoc.noneOfTheAbove,
              institutionName: this.postDoc.institutionName,
              comments: this.postDoc.comments,
              progressReport: this.postDoc.progressReport,
            };
            axios.post("api/postDoc", postDoc).then((result) => {
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
      async createPostDoc() {
        this.errors = [];

        const itemsToCheck = [
          'privateSector',
          'academy1',
          'business',
          'ownEntrepreneurship',
          'publicSector',
          'government',
          'academy2',
          'socialOng',
          'inTheCenter',
          'noneOfTheAbove',
          'comments',
          'institutionName',
        ];


        for (const item in this.postDoc){
          if(this.postDoc[item] === "" || this.postDoc[item] === 0 || this.postDoc[item] == null || this.postDoc[item] == []){
              if(this.postDoc.identification == '' && item == 'run' || this.postDoc.identification == '' && item == 'passport'){
              }else if(this.postDoc.identification == 'run' && item == 'passport'){
              }else if(this.postDoc.identification == 'passport' && item == 'run'){
              }else if (itemsToCheck.includes(item)) {
              }else{
                this.errors.push(item);
              }
            }
        }

        if(this.postDoc.identification == 'run' && this.postDoc.run != ""){
          var validacion = this.validarRut(this.postDoc.run);
          if(validacion == false){
            this.errors.push('invalidRut');
            }
        }

        var contador = await axios.post('../api/verifyPostdoc', this.postDoc).then(function(response) {
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
            if(item == 'nameOfPostDoc'){
              mensaje =   mensaje + "The field Name of postdoc fellow is required" + "\n";
            }else if(item == 'researchTopic'){
              mensaje =   mensaje + "The field Research topic is required" + "\n";
            }else if(item == 'supervisorName'){
              mensaje =   mensaje + "The field Supervisor name is required" + "\n";
            }else if(item == 'resourcesProvided'){
              mensaje =   mensaje + "The field Resources provided is required" + "\n";
            }else if(item == 'fundingSource'){
              mensaje =   mensaje + "The field Funding source is required" + "\n";
            }else if(item == 'startYear'){
              mensaje =   mensaje + "The field Start year is required" + "\n";
            }else if(item == 'endingYear'){
              mensaje =   mensaje + "The field Ending year is required" + "\n";
            }else if(item == 'institutionName'){
              mensaje =   mensaje + "The field Institution where it was inserted is required" + "\n";
            }else if(item == 'personalEmail'){
              mensaje =   mensaje + "The field Personal Email is required" + "\n";
            }else if(item == 'nameOfPostdoc'){
              mensaje =   mensaje + "The field Name of post doc is required" + "\n";
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
            title: 'Save Postdoctoral fellow',
            message: `¿Are you sure you want to save this Postdoctoral fellow? This action cannot be undone.`,
            okButton: 'Save',
            cancelButton: 'Return'
          })
          if (ok) {

            var runOrPassport1 = '';
            if(this.postDoc.identification == 'run'){
              runOrPassport1 = this.formatoRut(this.postDoc.run);
            }else{
              runOrPassport1 = this.postDoc.passport;
            }

            var supervisorName1 = "";
            if (this.postDoc.supervisorName !== null){
              if (this.postDoc.supervisorName.length !== 0) {
                this.postDoc.supervisorName.forEach((supervisorName, index) => {
                  supervisorName1 += supervisorName.name;
                  if (index === this.postDoc.supervisorName.length - 1) {
                    supervisorName1 += '.';
                  } else {
                    supervisorName1 += ', ';
                  }
                });
              }
            }

            var resourcesProvided1 = "";
            if (this.postDoc.resourcesProvided !== null){
              if (this.postDoc.resourcesProvided.length !== 0) {
                this.postDoc.resourcesProvided.forEach((resourcesProvided, index) => {
                  resourcesProvided1 += resourcesProvided.name;
                  if (index === this.postDoc.resourcesProvided.length - 1) {
                    resourcesProvided1 += '.';
                  } else {
                    resourcesProvided1 += ', ';
                  }
                });
              }
            }

            var fundingSource1 = "";
            if (this.postDoc.fundingSource !== null){
              if (this.postDoc.fundingSource.length !== 0) {
                this.postDoc.fundingSource.forEach((fundingSource, index) => {
                  fundingSource1 += fundingSource.name;
                  if (index === this.postDoc.fundingSource.length - 1) {
                    fundingSource1 += '.';
                  } else {
                    fundingSource1 += ', ';
                  }
                });
              }
            }

            var idUser1 = ''
            if(this.idResearcher != ''){
              idUser1 = this.idResearcher;
            }else{
              idUser1 = this.userID;
            }

            let postDoc = {
              status: 'Finished',
              idUsuario: idUser1,
              nameOfPostdoc: this.postDoc.nameOfPostdoc,
              identification: this.postDoc.identification,
              runOrPassport: runOrPassport1,
              gender: this.postDoc.gender,
              personalEmail: this.postDoc.personalEmail,
              researchTopic: this.postDoc.researchTopic,
              supervisorName: supervisorName1,
              resourcesProvided: resourcesProvided1,
              fundingSource: fundingSource1,
              startYear: this.postDoc.startYear,
              endingYear: this.postDoc.endingYear,
              privateSector: this.postDoc.privateSector,
              academy1: this.postDoc.academy1,
              business: this.postDoc.business,
              ownEntrepreneurship: this.postDoc.ownEntrepreneurship,
              publicSector: this.postDoc.publicSector,
              government: this.postDoc.government,
              academy2: this.postDoc.academy2,
              socialOng: this.postDoc.socialOng,
              inTheCenter: this.postDoc.inTheCenter,
              noneOfTheAbove: this.postDoc.noneOfTheAbove,
              institutionName: this.postDoc.institutionName,
              comments: this.postDoc.comments,
              progressReport: this.postDoc.progressReport,
            };
            axios.post("api/postDoc", postDoc).then((result) => {
              this.toast.success("Postdoctoral fellow saved successfully!", {
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