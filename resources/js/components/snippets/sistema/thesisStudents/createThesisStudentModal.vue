<template>
  <transition name="modalcrear">
    <div name="modal">
      <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container-xl ">
              <div class="modal-header pb-1 fw-bold" style="color: #444444;">
                <slot name="header">
                    New Thesis
                </slot>
                <label for="">Progress year: {{ thesisStudent.progressReport }} &nbsp;&nbsp; <a class="btn" @click="showModalProgress = true"><i class="fa-solid fa-pen-to-square"></i></a></label>
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
                      <div class="col-md-6">
                        <label for="">AC3E researcher involved:</label>
                        <label for="" style="color: orange;">*</label>
                        <label title="You must list all the researchers who are part of the center." style="color: #0A95FF;"><i class="fa-solid fa-circle-info"></i></label>
                        <Multiselect
                          placeholder="Select the participants"
                          v-model="thesisStudent.researcherInvolved"
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
                      <div class="col-md-3">
                        <label for="universitySelect">University that gives the Degree:</label>
                        <label for="universitySelect" style="color: orange;">*</label>
                        <br>
                        <select id="universitySelect" class="form-select" v-model="thesisStudent.university">
                          <option value="">Select University</option>
                          <option v-for="university in universities" :value="university.name">{{ university.name }}</option>
                        </select>
                      </div>
                      <div class="col-md-3">
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
                        <div class="col-md-3">
                            <label for="">Student Name: </label>
                            <label for="" style="color: orange;">*</label>
                            <label title="The format for this field should be as follows: 'First Name,Last Name; First Name,Last Name; ...'" style="color: #0A95FF;"><i class="fa-solid fa-circle-info"></i></label>
                            <br>
                            <input type="text" class= "form-control" v-model="thesisStudent.studentName">
                        </div>
                        <div class="col-md-3">
                            <label for="">Gender: </label>
                            <label for="" style="color: orange;">*</label>
                            <br>
                            <select class="form-select" v-model="thesisStudent.gender">
                              <option disabled value="">Select a Gender</option>
                              <option value="Male">Male</option>
                              <option value="Female">Female</option>
                              <option value="Non binary">Non binary</option>
                              <option value="Other">Other</option>
                              </select>
                        </div>
                        <div class="col-md-3">
                            <label for="">Identification: </label>
                            <label for="" style="color: orange;">*</label>
                            <br>
                            <select class="form-select" @change="thesisStudent.run = ''; thesisStudent.passport = '';" v-model="thesisStudent.identification">
                              <option disabled value="">Select One</option>
                              <option value="Run">Run</option>
                              <option value="Passport">Passport</option>
                            </select>
                        </div>                        
                        <div v-if="thesisStudent.identification == 'Run'" class="col-3">
                            <label for="">Run: </label>
                            <label for="" style="color: orange;">*</label>
                            <br>
                            <input type="text" class= "form-control" v-on:input="validateInput" v-on:keypress="isNumberOrDash" @keyup="checkRut()" v-model="thesisStudent.run">
                        </div>
                        <div v-if="thesisStudent.identification == 'Passport'" class="col-3">
                            <label for="">Passport: </label>
                            <label for="" style="color: orange;">*</label>
                            <br>
                            <input type="text" class= "form-control" v-model="thesisStudent.passport">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-md-3">
                            <label for="">Student Mail: </label>
                            <label for="" style="color: orange;">*</label>
                            <label title="The format must be an email." style="color: #0A95FF;"><i class="fa-solid fa-circle-info"></i></label>
                            <br>
                            <input type="email" class= "form-control" v-model="thesisStudent.studentMail">
                      </div>
                      <div class="col-md-5">
                          <label for="">Academic Degree: </label>
                          <label for="" style="color: orange;">*</label>
                          <br>
                          <select class="form-select" v-model="thesisStudent.academicDegree">
                            <option disabled value="">Select a degree</option>
                            <option value="Undergraduate degree or profesional title">Undergraduate degree or profesional title</option>
                            <option value="Master o equivalent">Master o equivalent</option>
                            <option value="PhD degree">PhD degree</option>
                          </select>
                      </div>
                      <div class="col-md-4">
                          <label for="">Degree Denomination: </label>
                          <label for="" style="color: orange;">*</label>
                          <br>
                          <input type="text" class= "form-control" v-model="thesisStudent.degreeDenomination">
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-md-4">
                        <label for="">Thesis Title: </label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="thesisStudent.thesisTitle">
                      </div>
                      <div class="col-md-3">
                          <label for="">Thesis Status: </label>
                          <label for="" style="color: orange;">*</label>
                          <br>
                          <select class="form-select" v-model="thesisStudent.thesisStatus">
                            <option disabled value="">Select One</option>
                            <option value="1">Finished</option>
                            <option value="2">In progress</option>
                          </select>
                      </div>
                      <div v-if="thesisStudent.thesisStatus == '1'" class="col-4">
                        <div class="form-group">
                          <label for="archivo">Thesis Extract: </label>
                          <label for="" style="color: orange;">*</label>
                          <label title="You must upload a PDF file or image." style="color: #0A95FF;"><i class="fa-solid fa-circle-info"></i></label>
                          <input type="file" ref="fileInput" accept=".pdf" class= "form-control" @change="getFile">
                        </div>
                      </div>
                      <div v-if="thesisStudent.thesisStatus == '1'" class="col-1 pt-2">
                        <br>
                        <a class="btn btn-closed " title="Clear Input" @click="clearFileInput"><i class="fa-solid fa-ban"></i></a>
                      </div>
                    </div>
                    <hr size="3" class="separador">
                    <div class="row">
                      <div class="col-md-4 d-flex justify-content-center">
                        <a v-if="tutors.length != 0" class="btn btn-continue" @click="showModalEditTutor = true">{{ tutors.length }}</a>
                        &nbsp;
                        <a class="btn btn-search-blue" @click="showModalAutor = true"><i class="fa-solid fa-plus"></i> Add Tutor </a>
                      </div>
                      <div class="col-md-4 d-flex justify-content-center">
                        <a v-if="cotutors.length != 0" class="btn btn-continue" @click="showModalEditCotutor = true">{{ cotutors.length }}</a>
                        &nbsp;
                        <a class="btn btn-search-blue" @click="showModalCoautor = true"><i class="fa-solid fa-plus"></i> Add Cotutor </a>
                      </div>
                      <div class="col-md-4 d-flex justify-content-centerd-flex justify-content-center">
                        <a v-if="others.length != 0" class="btn btn-continue" @click="showModalEditOther = true">{{ others.length }}</a>
                        &nbsp;
                        <a class="btn btn-search-blue" @click="showModalOther = true"><i class="fa-solid fa-plus"></i> Add Other </a>
                      </div>
                    </div>
                    <hr size="3" class="separador">
                    <div class="row">

                      <div class="col-md-3">
                          <label for="">Year in which student starts: </label>
                          <label for="" style="color: orange;">*</label>
                          <br>
                          <select class="form-select" id="selectYear" v-model="thesisStudent.yearStart">
                          <option disabled value="">Select a year</option>
                          <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
                          </select>
                      </div>
                      <div class="col-md-3">
                          <label for="selectMonth">Month in which student starts:</label>
                          <label for="selectMonth" style="color: orange;">*</label>
                          <br>
                          <select class="form-select" id="selectMonth" v-model="thesisStudent.monthStart">
                              <option disabled value="">Select a month</option>
                              <option value="January">January</option>
                              <option value="February">February</option>
                              <option value="March">March</option>
                              <option value="April">April</option>
                              <option value="May">May</option>
                              <option value="June">June</option>
                              <option value="July">July</option>
                              <option value="August">August</option>
                              <option value="September">September</option>
                              <option value="October">October</option>
                              <option value="November">November</option>
                              <option value="December">December</option>
                          </select>
                      </div>
                      <div class="col-md-3">
                          <label for="">Year in which the thesis ends: </label>
                          <label v-if="thesisStudent.thesisStatus == 1" for="" style="color: orange;">*</label>
                          <br>
                          <select class="form-select" id="selectYear" v-model="thesisStudent.yearThesisEnd">
                          <option disabled value="">Select a year</option>
                          <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
                          </select>
                      </div>
                      <div class="col-md-3">
                          <label for="selectMonth">Month in which the thesis ends:</label>
                          <label v-if="thesisStudent.thesisStatus == 1" for="selectMonth" style="color: orange;">*</label>
                          <br>
                          <select class="form-select" id="selectMonth" v-model="thesisStudent.monthEnd">
                              <option disabled value="">Select a month</option>
                              <option value="January">January</option>
                              <option value="February">February</option>
                              <option value="March">March</option>
                              <option value="April">April</option>
                              <option value="May">May</option>
                              <option value="June">June</option>
                              <option value="July">July</option>
                              <option value="August">August</option>
                              <option value="September">September</option>
                              <option value="October">October</option>
                              <option value="November">November</option>
                              <option value="December">December</option>
                          </select>
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div v-if="thesisStudent.thesisStatus == 1" class="col-md-3">
                          <label for="">Posterior working area: </label>
                          <label  for="" style="color: orange;">*</label>
                          <br>
                          <select class="form-select" v-model="thesisStudent.posteriorArea">
                            <option disabled value="">Select an area</option>
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
                      <div v-if="thesisStudent.thesisStatus == 1" class="col-md-4">
                          <label for="">Institution of Posterior working area: </label>
                          <label for="" style="color: orange;">*</label>
                          <br>
                          <input type="text" class= "form-control" v-model="thesisStudent.institutionPosteriorArea">
                      </div>
                      <div class="col-md-5">
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
                    v-model="draft"> Save as a draft</label>
                    <a v-if="draft == false" class="btn btn-continue float-end" @click="crearTesis()" :disabled="buttonDisable">
                      {{ buttonText }}
                    </a>
                    <a v-else class="btn btn-continue float-end" @click="guardarBorrador()" :disabled="buttonDisable">
                      Save draft
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
                <modalProgressYear v-bind:progressYear="thesisStudent.progressReport" v-if="showModalProgress" @close="showModalProgress = false" @submit="handleFormSubmit1"></modalProgressYear>
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
import modalProgressYear from '../../sistema/progressYearEdit.vue';

export default {
    components: { modalProgressYear,Multiselect,modalconfirmacion, modalalerta, modalAutor, modalCoautor , modalOther, modalEditarTutor, modalEditarCotutor, modalEditarOther },
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
        monthStart: "",
        yearThesisEnd: "",
        monthEnd: "",
        researcherInvolved: null,
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
      showModalProgress: false,
      tutors:[],
      cotutors:[],
      others:[],
      errors:[],
      universities: [],
      usuarios: [],
      researchers:[],
      idResearcher: '',
      buttonText:'Send Thesis',
      years: [],
    }),
    mounted() {
      this.calculateYears();
      this.getProgressReport();
      this.getUsuarios2();
      this.getUsuarios();
      const currentYear = new Date().getFullYear();
      const startYear = 2000;
      const endYear = currentYear + 1;
      this.years = Array.from({ length: endYear - startYear + 1 }, (_, index) => startYear + index);
      this.selectedYear = currentYear;
      this.years.sort((a, b) => b - a);
      this.fetchData();
    },
    methods: {
      // Función para obtener datos de universidades desde la API
      fetchData() {
        axios.get('/api/universities')
          .then(response => {
            this.universities = response.data;
          })
          .catch(error => {
            console.error('Error fetching universities:', error);
          });
      },
      // Función para obtener usuarios (investigadores) desde la API
      getUsuarios(){
        axios.get('api/researchers').then( response =>{
            this.researchers = response.data;
        }).catch(e=> console.log(e))
      },
      // Función para manejar el envío de un formulario con un año
      handleFormSubmit1(year) {
        this.thesisStudent.progressReport = year;
      },
      // Función para obtener usuarios desde otra ruta de la API
      getUsuarios2(){
        axios.get('api/usuarios').then( response =>{
            this.usuarios = response.data.sort((a, b) => a.name.localeCompare(b.name));;
        }).catch(e=> console.log(e))
      },
      // Función para obtener el año de progreso desde la API
      getProgressReport(){
        axios.get('api/showProgressReport').then( response =>{
            this.thesisStudent.progressReport = response.data;
        }).catch(e=> console.log(e))
      },
      // Función para manejar el envío de un formulario
      handleFormSubmit1(formData) {
        this.tutors.push(formData);
      },
      // Función para manejar el envío de un formulario
      handleFormSubmit2(formData) {
        this.cotutors.push(formData);
      },
      // Función para manejar el envío de un formulario
      handleFormSubmit3(formData) {
        this.others.push(formData);
      },
      // Función para manejar la entrada de un campo de año
      onInput1(event) {
        const input = event.target;
        this.thesisStudent.yearStart = input.value.slice(0, 4);
      },
      // Función para manejar la entrada de un campo de año
      onInput2(event) {
        const input = event.target;
        this.thesisStudent.yearThesisEnd = input.value.slice(0, 4);
      },
      // Función para limpiar la entrada de archivo
      clearFileInput() {
        this.file = null;
        this.$refs.fileInput.value = '';
      },
      // Función para obtener el archivo seleccionado
      async getFile(e){
        this.file = e.target.files[0];
      },
      // Función para calcular los años
      calculateYears() {
        const currentYear = new Date().getFullYear();
        const startYear = currentYear - 20;
        this.years = Array.from({ length: 21 }, (_, index) => startYear + index);
      },
      // Función para verificar y formatear el RUN
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
      // Función para validar la entrada
      validateInput() {
        this.thesisStudent.run = this.thesisStudent.run.replace(/[^0-9Kk\-]/g, '').replace(/(\-)\-+/g, '$1');
      },
      // Función para permitir solo números o guiones
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
      // Función para cerrar el modal y emitir un evento de recarga
      cerrarModal(){
        const elem = this.$refs.closeBtn;
        this.$emit('recarga');
        elem.click();
      },
      // Función para capitalizar la primera letra de una cadena
      capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
      },
      // Función para guardar un borrador 
      async guardarBorrador(){
        const ok = await this.$refs.confirmation.show({
          title: 'Save draft',
            message: `¿Are you sure you want to save this Thesis Student as a draft? this action cannot be undone.`,
            okButton: 'Save',
            cancelButton: 'Return'
        })
        if (ok) {
          var runOrPassport1 = '';
          if(this.thesisStudent.identification == 'Run'){
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
          
          
          var peopleInvolved1 = "";
          if (this.thesisStudent.researcherInvolved !== null){
            if (this.thesisStudent.researcherInvolved.length !== 0) {
              this.thesisStudent.researcherInvolved.forEach((researcherInvolved, index) => {
                peopleInvolved1 += researcherInvolved.name;
                if (index === this.thesisStudent.researcherInvolved.length - 1) {
                  peopleInvolved1 += '.';
                } else {
                  peopleInvolved1 += ', ';
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
          
          let thesisStudent = {
            idUsuario: idUser1,
            status: 'Draft',
            researcherInvolved: peopleInvolved1,
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
            monthStart: this.thesisStudent.monthStart,
            monthEnd: this.thesisStudent.monthEnd,
            yearThesisEnd: this.thesisStudent.yearThesisEnd,
            resourcesCenter: resources1,
            posteriorArea: this.thesisStudent.posteriorArea,
            institutionPosteriorArea: this.thesisStudent.institutionPosteriorArea,
            comments: this.thesisStudent.comments,
            progressReport: this.thesisStudent.progressReport,
            file: this.file,
          };
          axios.post("api/thesisStudents", thesisStudent, {headers: { 'Content-Type' : 'multipart/form-data' }} ).then((result) => {
            this.buttonDisable = true;
            this.buttonText = 'Sending...';
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
      // Función para crear una nueva tesis
      async crearTesis() {
        this.errors = [];

        const fieldsToExclude = ['yearThesisEnd', 'posteriorArea','institutionPosteriorArea','comments', 'monthEnd','run','passport','tutorName', 'tutorInstitution','cotutorName','cotutorInstitution','otherName','otherInstitution']; // Arreglo de campos a excluir

        for (const item in this.thesisStudent) {
          if (!fieldsToExclude.includes(item)) { // Verifica si el campo no está en la lista de campos a excluir
            if (this.thesisStudent[item] === "" || this.thesisStudent[item] === 0 ||this.thesisStudent[item] == null || this.thesisStudent[item] == []){
              this.errors.push(item);
            } 
          }
        }

        if(this.thesisStudent.identification == 'Run' && this.thesisStudent.run == ""){
          this.errors.push('run');
        }else if(this.thesisStudent.identification == 'Passport' && this.thesisStudent.passport == ""){
          this.errors.push('passport');
        }

        if(this.tutors.length == 0 && this.cotutors.length == 0 && this.others.length == 0){
          this.errors.push('noTutors');
        }

        if(this.thesisStudent.identification == 'Run' && this.thesisStudent.run != ""){
          var validacion = this.validarRut(this.thesisStudent.run);
          if(validacion == false){
            this.errors.push('invalidRut');
            }
        }

        if(this.thesisStudent.thesisStatus == 1 && this.thesisStudent.yearThesisEnd == ""){
            this.errors.push('yearThesisEnd');
        }

        if(this.thesisStudent.thesisStatus == 1 && this.thesisStudent.monthEnd == ""){
            this.errors.push('monthEnd');
        }

        if(this.thesisStudent.thesisStatus == 1 && this.thesisStudent.yearThesisEnd == ""){
            this.errors.push('posteriorArea');
        }

        if(this.thesisStudent.thesisStatus == 1 && this.thesisStudent.yearThesisEnd == ""){
            this.errors.push('institutionPosteriorArea');
        }

        var runOrPassport1 = '';
          if(this.thesisStudent.identification == 'Run'){
            runOrPassport1 = this.formatoRut(this.thesisStudent.run);
          }else{
            runOrPassport1 = this.thesisStudent.passport;
          }

        let thesisStudent2 = {
          idUsuario: idUser1,
          runOrPassport: runOrPassport1,
          degreeDenomination: this.thesisStudent.degreeDenomination,
        };

        var contador = await axios.post('../api/verifyThesis', thesisStudent2).then(function(response) {
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
            if(item == 'studentName'){
              mensaje =   mensaje + "The field Student Name is required" + "\n";
            }else if(item == 'researcherInvolved'){
              mensaje =   mensaje + "The field Researcher Involved is required" + "\n";
            }else if(item == 'invalidRut'){
              mensaje =   mensaje + "The entered Run is invalid" + "\n";
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
              mensaje =   mensaje + "The field Year which starts is required" + "\n";
            }else if(item == 'monthStart'){
              mensaje =   mensaje + "The field Month which starts is required" + "\n";
            }else if(item == 'yearThesisEnd'){
              mensaje =   mensaje + "The field Year which thesis ends is required" + "\n";
            }else if(item == 'monthEnd'){
              mensaje =   mensaje + "The field Month which thesis ends is required" + "\n";
            }else if(item == 'resourcesCenter'){
              mensaje =   mensaje + "The field Resources provided is required" + "\n";
            }else if(item == 'posteriorArea'){
              mensaje =   mensaje + "The field Posterior working area is required" + "\n";
            }else if(item == 'institutionPosteriorArea'){
              mensaje =   mensaje + "The field Institution posterior area is required" + "\n";
            }else if(item == 'noTutors'){
              mensaje =   mensaje + "A minimum of one tutor, co-tutor or other must enter" + "\n";
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
            title: 'Send Thesis',
            message: `¿Are you sure you want to send this thesis?.`,
            okButton: 'Send',
            cancelButton: 'Return'
          })
          if (ok) {


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

            var idUser1 = ''
            if(this.idResearcher != ''){
              idUser1 = this.idResearcher;
            }else{
              idUser1 = this.userID;
            }

            var peopleInvolved1 = "";
            if (this.thesisStudent.researcherInvolved !== null){
              if (this.thesisStudent.researcherInvolved.length !== 0) {
                this.thesisStudent.researcherInvolved.forEach((researcherInvolved, index) => {
                  peopleInvolved1 += researcherInvolved.name;
                  if (index === this.thesisStudent.researcherInvolved.length - 1) {
                    peopleInvolved1 += '.';
                  } else {
                    peopleInvolved1 += ', ';
                  }
                });
              }
            }

            let thesisStudent = {
              idUsuario: idUser1,
              status: 'Finished',
              researcherInvolved: peopleInvolved1,
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
              monthStart: this.thesisStudent.monthStart,
              monthEnd: this.thesisStudent.monthEnd,
              yearThesisEnd: this.thesisStudent.yearThesisEnd,
              resourcesCenter: resources1,
              posteriorArea: this.thesisStudent.posteriorArea,
              institutionPosteriorArea: this.thesisStudent.institutionPosteriorArea,
              comments: this.thesisStudent.comments,
              progressReport: this.thesisStudent.progressReport,
              file: this.file,
            };
            axios.post("api/thesisStudents", thesisStudent, {headers: { 'Content-Type' : 'multipart/form-data' }} ).then((result) => {
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
              setTimeout(() => {this.cerrarModal();}, 1500);
            })
            .catch((error) => {
              if (error.response) {
                // Si hay una respuesta del servidor
                if (error.response.status === 422) {
                  // Error de validación
                  this.toast.warning(`Validation error: ${error.response.data.message}`, {
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
                } else if (error.response.status === 404) {
                  // Recurso no encontrado
                  this.toast.error("Resource not found.", {
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
                } else {
                  // Otro tipo de error
                  this.toast.error(`An error occurred: ${error.response.data.message}`, {
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
              } else if (error.request) {
                // Si la solicitud fue hecha pero no se recibió respuesta
                this.toast.error("No response from server.", {
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
              } else {
                // Otro tipo de error
                this.toast.error(`An error occurred: ${error.message}`, {
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