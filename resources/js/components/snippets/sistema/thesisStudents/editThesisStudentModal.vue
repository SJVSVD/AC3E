<template>
  <transition name="modalcrear">
    <div name="modal">
      <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container ">
              <div class="modal-header pb-1 fw-bold" style="color: #444444;">
                <slot name="header">
                    Edit Thesis
                </slot>
                <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
              </div>
              <div class="modal-body">
                <slot name="body">
                    <div class="row">
                        <div class="col-3">
                            <label for="">Student Name: </label>
                            <br>
                            <input type="text" class= "form-control" v-model="thesisStudent.studentName">
                        </div>
                        <div class="col-3">
                            <label for="">Gender(M/F): </label>
                            <br>
                            <select class="form-select" v-model="thesisStudent.gender">
                              <option disabled value="">Select a Gender</option>
                              <option value="M">Male</option>
                              <option value="F">Female</option>
                              </select>
                        </div>
                        <div class="col-3">
                            <label for="">Identification: </label>
                            <br>
                            <select class="form-select" @change="thesisStudent.run = ''; thesisStudent.passport = '';" v-model="thesisStudent.identification">
                              <option disabled value="">Select One</option>
                              <option value="run">Run</option>
                              <option value="passport">Passport</option>
                            </select>
                        </div>                        
                        <div v-if="thesisStudent.identification == 'run'" class="col-3">
                            <label for="">Run: </label>
                            <br>
                            <input type="text" class= "form-control" v-on:input="validateInput" v-on:keypress="isNumberOrDash" @keyup="checkRut()" v-model="thesisStudent.run">
                        </div>
                        <div v-if="thesisStudent.identification == 'passport'" class="col-3">
                            <label for="">Passport: </label>
                            <br>
                            <input type="text" class= "form-control" v-model="thesisStudent.passport">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-3">
                            <label for="">Student Mail: </label>
                            <br>
                            <input type="email" class= "form-control" v-model="thesisStudent.studentMail">
                      </div>
                      <div class="col-5">
                          <label for="">Academic Degree: </label>
                          <br>
                          <select class="form-select" v-model="thesisStudent.academicDegree">
                            <option disabled value="">Select a degree</option>
                            <option value="1">Undergraduate degree or profesional title</option>
                            <option value="2">Master o equivalent</option>
                            <option value="3">PhD degree</option>
                          </select>
                      </div>
                      <div class="col-4">
                          <label for="">Degree Denomination: </label>
                          <br>
                          <input type="text" class= "form-control" v-model="thesisStudent.degreeDenomination">
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-4">
                        <label for="">Thesis Title: </label>
                        <br>
                        <input type="text" class= "form-control" v-model="thesisStudent.thesisTitle">
                      </div>
                    </div>
                    <hr size="3" class="separador">
                    <div class="row">
                      <div class="col-4 d-flex justify-content-center">
                        <a v-if="tutors.length != 0" class="btn btn-continue">{{ tutors.length }}</a>
                        &nbsp;
                        <a class="btn btn-search-blue" @click="showModalAutor = true"><i class="fa-solid fa-plus"></i> Add Tutor </a>
                      </div>
                      <div class="col-4 d-flex justify-content-center">
                        <a v-if="cotutors.length != 0" class="btn btn-continue">{{ cotutors.length }}</a>
                        &nbsp;
                        <a class="btn btn-search-blue" @click="showModalCoautor = true"><i class="fa-solid fa-plus"></i> Add Cotutor </a>
                      </div>
                      <div class="col-4 d-flex justify-content-centerd-flex justify-content-center">
                        <a v-if="others.length != 0" class="btn btn-continue">{{ others.length }}</a>
                        &nbsp;
                        <a class="btn btn-search-blue" @click="showModalOther = true"><i class="fa-solid fa-plus"></i> Add Other </a>
                      </div>
                    </div>
                    <hr size="3" class="separador">
                    <div class="row">
                      <div class="col-3">
                          <label for="">University that gives the Degree: </label>
                          <br>
                          <input type="text" class= "form-control" v-model="thesisStudent.university">
                      </div>
                      <div class="col-3">
                          <label for="">Year in which student starts: </label>
                          <br>
                          <input id="yearInput1" type="number" v-model="thesisStudent.yearStart" :max="currentYear" @input="onInput1" class= "form-control" />
                      </div>
                      <div class="col-3">
                          <label for="">Year in which the thesis ends: </label>
                          <br>
                          <input id="yearInput2" type="number" v-model="thesisStudent.yearThesisEnd" :max="currentYear" @input="onInput2" class= "form-control" />
                      </div>
                      <div class="col-3">
                          <label for="">Resources provided by the Center: </label>
                          <br>
                          <div>
                            <Multiselect
                                placeholder="Select the options"
                                v-model="thesisStudent.resourcesCenter"
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
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-3">
                          <label for="">Posterior working area: </label>
                          <br>
                          <select class="form-select" v-model="thesisStudent.posteriorArea">
                            <option disabled value="">Select an area</option>
                            <option value="1">Private Education</option>
                            <option value="2">Business</option>
                            <option value="3">Own entrepreneurship</option>
                            <option value="4">Government</option>
                            <option value="5">Public Education</option>
                            <option value="6">Social-ONG</option>
                            <option value="7">In the Center</option>
                            <option value="8">None of the above</option>
                          </select>
                      </div>
                      <div class="col-4">
                          <label for="">Institution of Posterior working area: </label>
                          <br>
                          <input type="text" class= "form-control" v-model="thesisStudent.institutionPosteriorArea">
                      </div>
                      <div class="col-5">
                          <label for="">Comments: </label>
                          <br>
                          <input type="text" class= "form-control" v-model="thesisStudent.comments">
                      </div>
                    </div>
                  </slot>
                </div>
                <div class="modal-footer">
                  <slot name="footer">
                    <a class="btn btn-continue float-end" @click="crearTesis()" :disabled="buttonDisable">
                      {{ buttonText }}
                    </a>
                  </slot>
                </div>
                <modalOther v-if="showModalOther" @close="showModalOther = false" @submit="handleFormSubmit3"></modalOther>
                <modalCoautor v-if="showModalCoautor" @close="showModalCoautor = false" @submit="handleFormSubmit2"></modalCoautor>
                <modalAutor v-if="showModalAutor" @close="showModalAutor = false" @submit="handleFormSubmit1"></modalAutor>
                <modalconfirmacion ref="confirmation"></modalconfirmacion>
                <modalalerta ref="alert"></modalalerta>
          </div>
        </div>
      </div>
    </div>
  </transition>
</template>

<script>
import axios from 'axios';
import modalconfirmacion from '../../sistema/alerts/confirmationModal.vue';
import modalalerta from '../../sistema/alerts/alertModal.vue';
import modalAutor from './extraModals/createAutor.vue';
import modalCoautor from './extraModals/createCoautor.vue';
import modalOther from './extraModals/createOther.vue';
import {mixin} from '../../../../mixins.js';
import Multiselect from '@vueform/multiselect';

export default {
    components: { Multiselect,modalconfirmacion, modalalerta, modalAutor, modalCoautor , modalOther },
    mixins: [mixin],
    data: () => ({
      thesisStudent:{
        studentName: "",
        identification: "",
        passport: "",
        run: "",
        gender: "",
        studentMail: "",
        thesisTitle: "",
        academicDegree: "",
        degreeDenomination: "",
        tutorName: "",
        tutorInstitution: "",
        cotutorName: "",
        cotutorInstitution: "",
        otherName: "",
        otherInstitution: "",
        university: "",
        yearStart: "",
        yearThesisEnd: "",
        resourcesCenter: null,
        posteriorArea: "",
        institutionPosteriorArea: "",
        comments: "",
      },
      options1: [
        'Equipment',
        'Information',
        'Infrastructure',
        'Other',
      ],
      formData: null,
      showModalAutor: false,
      showModalCoautor: false,
      showModalOther: false,
      buttonDisable: false,
      tutors:[],
      cotutors:[],
      others:[],
      errors:[],
      id:null,
      buttonText:'Edit Thesis',
      years: [],
    }),
    props:{
        thesisStudent1: Object,
    },
    mounted() {
      this.calculateYears();
    },
    created(){
      this.id = this.thesisStudent1.id;
      this.thesisStudent.studentName = this.thesisStudent1.studentName;
      this.thesisStudent.identification = this.thesisStudent1.identification;
      if(this.thesisStudent1.identification == 'run'){
        this.thesisStudent.run = this.thesisStudent1.runOrPassport;
      }else{
        this.thesisStudent.passport = this.thesisStudent1.runOrPassport;
      }

      const separatedValues = this.thesisStudent1.resourcesCenter.split(",");
      this.thesisStudent.resourcesCenter = separatedValues.slice(0, -1).map(valor => {
        return { value: valor, name: valor };
      });

      if(this.thesisStudent1.tutorName != null){
        const valoresSeparados1 = this.thesisStudent1.tutorName.split(",");
        const nombresSeparados1 = this.thesisStudent1.tutorInstitution.split(",");
        this.tutors = valoresSeparados1.slice(0, -1).map((valor, index) => {
          return { name: valor, institution: nombresSeparados1[index] };
        });
      }

      if(this.thesisStudent1.cotutorName != null){
        const valoresSeparados2 = this.thesisStudent1.cotutorName.split(",");
        const nombresSeparados2 = this.thesisStudent1.cotutorInstitution.split(",");
        this.cotutors = valoresSeparados2.slice(0, -1).map((valor, index) => {
          return { name: valor, institution: nombresSeparados2[index] };
        });
      }
      if(this.thesisStudent1.otherName != null){
        const valoresSeparados3 = this.thesisStudent1.otherName.split(",");
        const nombresSeparados3 = this.thesisStudent1.otherInstitution
        .split(",");
        this.others = valoresSeparados3.slice(0, -1).map((valor, index) => {
          return { name: valor, institution: nombresSeparados3[index] };
        });
      }




      this.thesisStudent.gender = this.thesisStudent1.gender;
      this.thesisStudent.studentMail = this.thesisStudent1.studentMail;
      this.thesisStudent.thesisTitle = this.thesisStudent1.thesisTitle;
      this.thesisStudent.academicDegree = this.thesisStudent1.academicDegree;
      this.thesisStudent.degreeDenomination = this.thesisStudent1.degreeDenomination;
      this.thesisStudent.tutorInstitution = this.thesisStudent1.tutorInstitution;
      this.thesisStudent.cotutorName = this.thesisStudent1.cotutorName;
      this.thesisStudent.cotutorInstitution = this.thesisStudent1.cotutorInstitution;
      this.thesisStudent.otherName = this.thesisStudent1.otherName;
      this.thesisStudent.otherInstitution = this.thesisStudent1.otherInstitution;
      this.thesisStudent.university = this.thesisStudent1.university;
      this.thesisStudent.yearStart = this.thesisStudent1.yearStart;
      this.thesisStudent.yearThesisEnd = this.thesisStudent1.yearThesisEnd;
      this.thesisStudent.posteriorArea = this.thesisStudent1.posteriorArea;
      this.thesisStudent.institutionPosteriorArea = this.thesisStudent1.institutionPosteriorArea;
      this.thesisStudent.comments = this.thesisStudent1.comments;
    },
    methods: {
      handleFormSubmit1(formData) {
        this.tutors.push(formData);
      },
      handleFormSubmit2(formData) {
        this.cotutors.push(formData);
      },
      handleFormSubmit3(formData) {
        this.others.push(formData);
      },
      onInput1(event) {
        const input = event.target;
        // Limitar el año a 4 dígitos
        this.thesisStudent.yearStart = input.value.slice(0, 4);
      },
      onInput2(event) {
        const input = event.target;
        // Limitar el año a 4 dígitos
        this.thesisStudent.yearThesisEnd = input.value.slice(0, 4);
      },
      clearFileInput() {
        this.$refs.fileInput.value = '';
      },
      async getFile(e){
        this.thesisExtract = e.target.files[0];
      },
      calculateYears() {
        const currentYear = new Date().getFullYear();
        const startYear = currentYear - 20;
        this.years = Array.from({ length: 21 }, (_, index) => startYear + index);
      },
      checkRut() {
        if (this.thesisStudent.run != ''){
          this.thesisStudent.run.replace('.','');
          var valor = this.thesisStudent.run.replace('.','');
          valor = valor.replace('-','');
          var cuerpo = valor.slice(0,-1);
          var dv = valor.slice(-1).toUpperCase();

          this.thesisStudent.run = cuerpo + '-'+ dv;
        }
      },
      validateInput() {
        this.thesisStudent.run = this.thesisStudent.run.replace(/[^0-9Kk\-]/g, '').replace(/(\-)\-+/g, '$1');
      },
      isNumberOrDash(event) {
        const charCode = event.which ? event.which : event.keyCode;
        const isK = charCode === 107 || charCode === 75;

        if (charCode === 45 && this.thesisStudent.run.indexOf('-') !== -1) {
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
      cerrarModal(){
        const elem = this.$refs.closeBtn;
        this.$emit('recarga');
        elem.click();
      },
      capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
      },
      async crearTesis() {
        this.errors = [];
        var noTutor = false;
        for (const item in this.thesisStudent){
          if(this.thesisStudent[item] === "" || this.thesisStudent[item] === 0 || this.thesisStudent[item] === null){
              if(this.thesisStudent.identification == '' && item == 'run' || this.thesisStudent.identification == '' && item == 'passport'){
              }else if(this.thesisStudent.identification == 'run' && item == 'passport'){
              }else if(this.thesisStudent.identification == 'passport' && item == 'run'){
              }else if(item == 'tutorName'||item == 'tutorInstitution'||item == 'cotutorName'||item == 'cotutorInstitution'||item == 'otherName'||item == 'otherInstitution'){
                if((this.tutors.length == 0 && this.cotutors.length == 0 && this.others.length == 0) && noTutor == false){
                  this.errors.push('noTutors');
                  noTutor = true
                }
              }else{
                this.errors.push(item);
              }
            }
        }

        if(this.thesisStudent.identification == 'run' && this.thesisStudent.run != ""){
          var validacion = this.validarRut(this.thesisStudent.run);
          if(validacion == false){
            this.errors.push('invalidRut');
            }
        }

        var mensaje = ""
        if (this.errors.length != 0){
          this.errors.forEach(item => {
            if(item == 'studentName'){
              mensaje =   mensaje + "The field Student Name is required" + "\n";
            }else if(item == 'invalidRut'){
              mensaje =   mensaje + "The entered run is invalid" + "\n";
            }else if(item == 'studentMail'){
              mensaje =   mensaje + "The field Student Mail is required" + "\n";
            }else if(item == 'thesisStatus'){
              mensaje =   mensaje + "The field Thesis Status is required" + "\n";
            }else if(item == 'thesisTitle'){
              mensaje =   mensaje + "The field Thesis Title is required" + "\n";
            }else if(item == 'academicDegree'){
              mensaje =   mensaje + "The field Academic Degree is required" + "\n";
            }else if(item == 'degreeDenomination'){
              mensaje =   mensaje + "The field Degree Denomination is required" + "\n";
            }else if(item == 'tutorName'){
              mensaje =   mensaje + "The field Tutor Name is required" + "\n";
            }else if(item == 'tutorInstitution'){
              mensaje =   mensaje + "The field Tutor Institution is required" + "\n";
            }else if(item == 'cotutorName'){
              mensaje =   mensaje + "The field Cotutor Name is required" + "\n";
            }else if(item == 'cotutorInstitution'){
              mensaje =   mensaje + "The field Cotutor Institution is required" + "\n";
            }else if(item == 'otherName'){
              mensaje =   mensaje + "The field Other Name is required" + "\n";
            }else if(item == 'otherInstitution'){
              mensaje =   mensaje + "The field Other Institution is required" + "\n";
            }else if(item == 'academicDegree'){
              mensaje =   mensaje + "The field Academic Degree is required" + "\n";
            }else if(item == 'yearStart'){
              mensaje =   mensaje + "The field Year when starts is required" + "\n";
            }else if(item == 'yearThesisEnd'){
              mensaje =   mensaje + "The field Year when thesis ends is required" + "\n";
            }else if(item == 'resourcesCenter'){
              mensaje =   mensaje + "The field Resources provided is required" + "\n";
            }else if(item == 'posteriorArea'){
              mensaje =   mensaje + "The field Posterior working area is required" + "\n";
            }else if(item == 'institutionPosteriorArea'){
              mensaje =   mensaje + "The field Institution posterior area is required" + "\n";
            }else if(item == 'noTutors'){
              mensaje =   mensaje + "A minimum of one tutor, co-tutor or other must enter" + "\n";
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
            title: 'Send Thesis',
            message: `¿Are you sure you want to send this thesis? This action cannot be undone.`,
            okButton: 'Send',
            cancelButton: 'Return'
          })
          if (ok) {
            var runOrPassport1 = '';
            if(this.thesisStudent.identification == 'run'){
              runOrPassport1 = this.formatoRut(this.thesisStudent.run);
            }else{
              runOrPassport1 = this.thesisStudent.passport;
            }

            var tutorName1 = '';
            var tutorInstitution1 = '';
            var cotutorName1 = '';
            var cotutorInstitution1 = '';
            var otherName1 = '';
            var otherInstitution1 = '';
            var resources1 = '';

            if(this.tutors.length != 0){
              this.tutors.forEach(tutor => {
                tutorName1 += tutor.name + ',';
              });
              this.tutors.forEach(tutor => {
                tutorInstitution1 += tutor.institution + ',';
              });
            }

            if(this.cotutors.length != 0){
              this.cotutors.forEach(cotutor => {
                cotutorName1 += cotutor.name + ',';
              });
              this.cotutors.forEach(cotutor => {
                cotutorInstitution1 += cotutor.institution + ',';
              });
            }

            if(this.others.length != 0){
              this.others.forEach(other => {
                otherName1 += other.name + ',';
              });
              this.others.forEach(other => {
                otherInstitution1 += other.institution + ',';
              });
            }

            this.thesisStudent.resourcesCenter.forEach(resource => {
              resources1 += resource.value + ',';
            });

            let thesisStudent = {
              idUsuario: this.userID,
              identification: this.thesisStudent.identification,
              studentName: this.thesisStudent.studentName,
              runOrPassport: runOrPassport1,
              gender: this.thesisStudent.gender,
              studentMail: this.thesisStudent.studentMail,
              thesisTitle: this.thesisStudent.thesisTitle,
              academicDegree: this.thesisStudent.academicDegree,
              degreeDenomination: this.thesisStudent.degreeDenomination,
              tutorName: tutorName1,
              tutorInstitution: tutorInstitution1,
              cotutorName: cotutorName1,
              cotutorInstitution: cotutorInstitution1,
              otherName: otherName1,
              otherInstitution: otherInstitution1,
              university: this.thesisStudent.university,
              yearStart: this.thesisStudent.yearStart,
              yearThesisEnd: this.thesisStudent.yearThesisEnd,
              resourcesCenter: resources1,
              posteriorArea: this.thesisStudent.posteriorArea,
              institutionPosteriorArea: this.thesisStudent.institutionPosteriorArea,
              comments: this.thesisStudent.comments,
            };
            axios.put(`api/thesisStudents/${this.id}`, thesisStudent ).then((result) => {
              this.buttonDisable = true;
              this.buttonText = 'Editing...';
              this.toast.success("Thesis edited successfully!", {
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