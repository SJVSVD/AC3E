<template>
  <transition name="modalcrear">
    <div name="modal">
      <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container ">
              <div class="modal-header pb-1 fw-bold" style="color: #444444;">
                <slot name="header">
                    Edit Thesis {{ thesisStudent.file }}
                </slot>
                <label for="">Progress year: {{ thesisStudent.progressReport }}</label>
                <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
              </div>
              <div class="modal-body">
                <slot name="body">
                    <div class="row">
                        <div class="col-3">
                            <label for="">Student Name: </label>
                            <label for="" style="color: orange;">*</label>
                            <br>
                            <input type="text" class= "form-control" v-model="thesisStudent.studentName">
                        </div>
                        <div class="col-3">
                            <label for="">Gender: </label>
                            <label for="" style="color: orange;">*</label>
                            <br>
                            <select class="form-select" v-model="thesisStudent.gender">
                              <option disabled :value="null">Select a Gender</option>
                              <option value="Male">Male</option>
                              <option value="Female">Female</option>
                              <option value="Non binary">Non binary</option>
                              </select>
                        </div>
                        <div class="col-3">
                            <label for="">Identification: </label>
                            <label for="" style="color: orange;">*</label>
                            <br>
                            <select class="form-select" @change="thesisStudent.run = ''; thesisStudent.passport = '';" v-model="thesisStudent.identification">
                              <option disabled :value="null">Select One</option>
                              <option value="run">Run</option>
                              <option value="passport">Passport</option>
                            </select>
                        </div>                        
                        <div v-if="thesisStudent.identification == 'run'" class="col-3">
                            <label for="">Run: </label>
                            <label for="" style="color: orange;">*</label>
                            <br>
                            <input type="text" class= "form-control" v-on:input="validateInput" v-on:keypress="isNumberOrDash" @keyup="checkRut()" v-model="thesisStudent.run">
                        </div>
                        <div v-if="thesisStudent.identification == 'passport'" class="col-3">
                            <label for="">Passport: </label>
                            <label for="" style="color: orange;">*</label>
                            <br>
                            <input type="text" class= "form-control" v-model="thesisStudent.passport">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-3">
                            <label for="">Student Mail: </label>
                            <label for="" style="color: orange;">*</label>
                            <br>
                            <input type="email" class= "form-control" v-model="thesisStudent.studentMail">
                      </div>
                      <div class="col-5">
                          <label for="">Academic Degree: </label>
                          <label for="" style="color: orange;">*</label>
                          <br>
                          <select class="form-select" v-model="thesisStudent.academicDegree">
                            <option disabled :value="null">Select a degree</option>
                            <option value="Undergraduate degree or profesional title">Undergraduate degree or profesional title</option>
                            <option value="Master o equivalent">Master o equivalent</option>
                            <option value="PhD degree">PhD degree</option>
                          </select>
                      </div>
                      <div class="col-4">
                          <label for="">Degree Denomination: </label>
                          <label for="" style="color: orange;">*</label>
                          <br>
                          <input type="text" class= "form-control" v-model="thesisStudent.degreeDenomination">
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-4">
                        <label for="">Thesis Title: </label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="thesisStudent.thesisTitle">
                      </div>
                      <div class="col-3">
                          <label for="">Thesis Status: </label>
                          <label for="" style="color: orange;">*</label>
                          <br>
                          <select class="form-select" v-model="thesisStudent.thesisStatus">
                            <option disabled value="">Select One</option>
                            <option value="1">Finished</option>
                            <option value="2">In progress</option>
                          </select>
                      </div>
                      <div class="col-3">
                        <div class="form-group">
                        <label for="archivo">File:</label>
                        <label for="" style="color: orange;">*</label>
                        <label v-if="thesisStudent1.file != null" title="This record already has a file, if you want to change add a new one, otherwise leave this field empty." style="color: #0A95FF;"><i class="fa-solid fa-circle-info"></i></label>
                        <input type="file" ref="fileInput" accept=".pdf" class= "form-control" @change="getFile">
                        </div>
                      </div>
                      <div class="col-2 pt-2">
                        <br>
                        <a class="btn btn-closed " title="Clear Input" @click="clearFileInput"><i class="fa-solid fa-ban"></i></a>
                        &nbsp;
                        <a v-if="thesisStudent1.file != null" class="btn btn-search-blue " title="Download" @click="descargarExtracto(id,user)"><i class="fa-solid fa-download"></i></a>
                      </div>
                    </div>
                    <hr size="3" class="separador">
                    <div class="row">
                      <div class="col-4 d-flex justify-content-center">
                        <a v-if="tutors.length != 0" class="btn btn-continue" @click="showModalEditTutor = true">{{ tutors.length }}</a>
                        &nbsp;
                        <a class="btn btn-search-blue" @click="showModalAutor = true"><i class="fa-solid fa-plus"></i> Add Tutor </a>
                      </div>
                      <div class="col-4 d-flex justify-content-center">
                        <a v-if="cotutors.length != 0" class="btn btn-continue" @click="showModalEditCotutor = true">{{ cotutors.length }}</a>
                        &nbsp;
                        <a class="btn btn-search-blue" @click="showModalCoautor = true"><i class="fa-solid fa-plus"></i> Add Cotutor </a>
                      </div>
                      <div class="col-4 d-flex justify-content-centerd-flex justify-content-center">
                        <a v-if="others.length != 0" class="btn btn-continue" @click="showModalEditOther = true">{{ others.length }}</a>
                        &nbsp;
                        <a class="btn btn-search-blue" @click="showModalOther = true"><i class="fa-solid fa-plus"></i> Add Other </a>
                      </div>
                    </div>
                    <hr size="3" class="separador">
                    <div class="row">
                      <div class="col-3">
                          <label for="">University that gives the Degree: </label>
                          <label for="" style="color: orange;">*</label>
                          <br>
                          <input type="text" class= "form-control" v-model="thesisStudent.university">
                      </div>
                      <div class="col-3">
                          <label for="">Year in which student starts: </label>
                          <label for="" style="color: orange;">*</label>
                          <br>
                          <select class="form-select" id="selectYear" v-model="thesisStudent.yearStart">
                          <option disabled :value="null">Select a year</option>
                          <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
                          </select>
                      </div>
                      <div class="col-3">
                          <label for="">Year in which the thesis ends: </label>
                          <label v-if="thesisStudent.thesisStatus == 1" for="" style="color: orange;">*</label>
                          <br>
                          <select class="form-select" id="selectYear" v-model="thesisStudent.yearThesisEnd">
                          <option disabled :value="null">Select a year</option>
                          <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
                          </select>
                      </div>
                      <div class="col-3">
                          <label for="">Resources provided by the Center: </label>
                          <label for="" style="color: orange;">*</label>
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
                          <label v-if="thesisStudent.thesisStatus == 1" for="" style="color: orange;">*</label>
                          <br>
                          <select class="form-select" v-model="thesisStudent.posteriorArea">
                            <option disabled :value="null">Select an area</option>
                            <option value="Private Education">Private Education</option>
                            <option value="Business">Business</option>
                            <option value="Own entrepreneurship">Own entrepreneurship</option>
                            <option value="Government">Government</option>
                            <option value="Public Education">Public Education</option>
                            <option value="Social-ONG">Social-ONG</option>
                            <option value="In the Center">In the Center</option>
                            <option value="None of the above">None of the above</option>
                          </select>
                      </div>
                      <div class="col-4">
                          <label for="">Institution of Posterior working area: </label>
                          <label v-if="thesisStudent.thesisStatus == 1" for="" style="color: orange;">*</label>
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
                    <label class="form-check-label"><input type="checkbox" class="form-check-"
                    v-model="draft"> Edit as a draft</label>
                    <a v-if="draft == false" class="btn btn-continue float-end" @click="crearTesis()" :disabled="buttonDisable">
                      {{ buttonText }}
                    </a>
                    <a v-else class="btn btn-continue float-end" @click="guardarBorrador()" :disabled="buttonDisable">
                      Edit draft
                    </a>
                  </slot>
                </div>
                <modalOther v-if="showModalOther" @close="showModalOther = false" @submit="handleFormSubmit3"></modalOther>
                <modalCoautor v-if="showModalCoautor" @close="showModalCoautor = false" @submit="handleFormSubmit2"></modalCoautor>
                <modalAutor v-if="showModalAutor" @close="showModalAutor = false" @submit="handleFormSubmit1"></modalAutor>
                <modalEditarTutor v-bind:tutors1="tutors" v-if="showModalEditTutor" @close="showModalEditTutor = false"></modalEditarTutor>
                <modalEditarCotutor v-bind:cotutors1="cotutors" v-if="showModalEditCotutor" @close="showModalEditCotutor = false"></modalEditarCotutor>
                <modalEditarOther v-bind:others1="others" v-if="showModalEditOther" @close="showModalEditOther = false"></modalEditarOther>
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
import modalEditarTutor from './extraModals/editTutor.vue';
import modalEditarCotutor from './extraModals/editCotutor.vue';
import modalEditarOther from './extraModals/editOther.vue';
import {mixin} from '../../../../mixins.js';
import Multiselect from '@vueform/multiselect';

export default {
    components: { Multiselect,modalconfirmacion, modalalerta, modalAutor, modalCoautor , modalOther, modalEditarTutor, modalEditarCotutor, modalEditarOther },
    mixins: [mixin],
    data: () => ({
      thesisStudent:{
        studentName: "",
        identification: "",
        passport: "",
        run: "",
        gender: "",
        studentMail: "",
        thesisStatus: "",
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
        progressReport: "",
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
      showModalEditTutor: false,
      showModalEditCotutor: false,
      showModalEditOther: false,
      buttonDisable: false,
      file: null,
      draft: false,
      tutors:[],
      cotutors:[],
      others:[],
      errors:[],
      id: '',
      user: '',
      buttonText:'Edit Thesis',
      years: [],
    }),
    mounted() {
      this.calculateYears();
    },
    props:{
      thesisStudent1: Object,
    },
    created(){
      this.id = this.thesisStudent1.id;
      this.user = this.thesisStudent1.usuario.name;
      this.thesisStudent.studentName = this.thesisStudent1.studentName;
      this.thesisStudent.progressReport = this.thesisStudent1.progressReport;
      this.thesisStudent.identification = this.thesisStudent1.identification;
      if(this.thesisStudent1.identification == 'run'){
        this.thesisStudent.run = this.thesisStudent1.runOrPassport;
      }else{
        this.thesisStudent.passport = this.thesisStudent1.runOrPassport;
      }

      if(this.thesisStudent1.resourcesCenter != null && this.thesisStudent1.resourcesCenter != []){
        const separatedValues = this.thesisStudent1.resourcesCenter.split(",");
        this.thesisStudent.resourcesCenter = separatedValues.slice(0, -1).map(valor => {
          return { value: valor, name: valor };
        });
      }
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



      this.thesisStudent.thesisStatus = this.thesisStudent1.thesisStatus;
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

      const currentYear = new Date().getFullYear();
      const startYear = 2000;
      const endYear = currentYear + 1;
      this.years = Array.from({ length: endYear - startYear + 1 }, (_, index) => startYear + index);
      this.selectedYear = currentYear;
      this.years.sort((a, b) => b - a);
    },
    methods: {
      descargarExtracto(id,nombre){
          axios({
              url: `api/thesisDownload/${id}`,
              method: 'GET',
              responseType: 'arraybuffer',
          }).then((response) => {
              let blob = new Blob([response.data], {
                      type: 'application/pdf'
                  })
                  let link = document.createElement('a')
                  link.href = window.URL.createObjectURL(blob)
                  link.download = `${nombre}.pdf`
                  link.click()
          });
      },
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
        this.file = null;
        this.$refs.fileInput.value = '';
      },
      async getFile(e){
        this.file = e.target.files[0];
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
      async guardarBorrador(){
        const ok = await this.$refs.confirmation.show({
          title: 'Edit draft',
            message: `¿Are you sure you want to edit this Thesis Student as a draft? this action cannot be undone.`,
            okButton: 'Edit',
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
          if(this.thesisStudent.resourcesCenter != null && this.thesisStudent.resourcesCenter != []){
            this.thesisStudent.resourcesCenter.forEach(resource => {
              resources1 += resource.value + ',';
            }); 
          }
          let thesisStudent = {
            status: 'Draft',
            identification: this.thesisStudent.identification,
            studentName: this.thesisStudent.studentName,
            runOrPassport: runOrPassport1,
            gender: this.thesisStudent.gender,
            studentMail: this.thesisStudent.studentMail,
            thesisStatus: this.thesisStudent.thesisStatus,
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
            progressReport: this.thesisStudent.progressReport,
          };
          await axios.put(`api/thesisStudents/${this.id}`, thesisStudent, {headers: { 'Content-Type' : 'multipart/form-data' }} ).then((result) => {
            this.buttonDisable = true;
            this.buttonText = 'Sending...';
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
            const formData = new FormData();
            formData.append('id', this.id);
            formData.append('file', this.file);
            axios.post('api/thesisStudents/addFile', formData, {
                headers: { 'Content-Type' : 'multipart/form-data' }
              }).then( response => {
                console.log(response.data);
              this.toast.success("File added successfully!", {
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
      async crearTesis() {
        this.errors = [];
        var noTutor = false;
        for (const item in this.thesisStudent){
          if(this.thesisStudent[item] === "" || this.thesisStudent[item] === 0 || this.thesisStudent[item] === null){
              if(item == 'yearThesisEnd' || item == 'posteriorArea' || item == 'institutionPosteriorArea'){
              }else if(this.thesisStudent.identification == '' && item == 'run' || this.thesisStudent.identification == '' && item == 'passport'){
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

        if(this.thesisStudent.thesisStatus == 1 && this.thesisStudent.yearThesisEnd == null){
            this.errors.push('yearThesisEnd');
        }

        if(this.thesisStudent.thesisStatus == 1 && this.thesisStudent.yearThesisEnd == null){
            this.errors.push('posteriorArea');
        }

        if(this.thesisStudent.thesisStatus == 1 && this.thesisStudent.yearThesisEnd == null){
            this.errors.push('institutionPosteriorArea');
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
            title: 'Edit Thesis',
            message: `¿Are you sure you want to edit this thesis? This action cannot be undone.`,
            okButton: 'Edit',
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
              status: 'Finished',
              identification: this.thesisStudent.identification,
              studentName: this.thesisStudent.studentName,
              runOrPassport: runOrPassport1,
              gender: this.thesisStudent.gender,
              studentMail: this.thesisStudent.studentMail,
              thesisStatus: this.thesisStudent.thesisStatus,
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
              progressReport: this.thesisStudent.progressReport,
            };
            await axios.put(`api/thesisStudents/${this.id}`, thesisStudent, {headers: { 'Content-Type' : 'multipart/form-data' }} ).then((result) => {
              this.buttonDisable = true;
              this.buttonText = 'Sending...';
              this.toast.success("Thesis send successfully!", {
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
              const formData = new FormData();
              formData.append('id', this.id);
              formData.append('file', this.file);
              axios.post('api/thesisStudents/addFile', formData, {
                  headers: { 'Content-Type' : 'multipart/form-data' }
                }).then( response => {
                  console.log(response.data);
                this.toast.success("File added successfully!", {
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