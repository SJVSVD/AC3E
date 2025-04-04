<template>
  <transition name="modalcrear">
    <div name="modal">
      <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container-xl ">
              <div class="modal-header pb-1 fw-bold" style="color: #444444;">
                <slot name="header">
                    Edit Thesis 
                </slot>
                <label for="">Progress report year: {{ thesisStudent.progressReport }} &nbsp;&nbsp; <a class="btn" v-if="is('Administrator')"@click="showModalProgress = true"><i class="fa-solid fa-pen-to-square"></i></a></label>
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
                        <label for="">AC3E Researchers Involved:</label>
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
                        <select id="universitySelect" class="form-select" v-model="selectedUniversity" @change="checkIfOther">
                          <option value="">Select University</option>
                          <option v-for="university in universities" :key="university.name" :value="university.name">{{ university.name }}</option>
                          <option value="other">Other</option>
                        </select>
                        <input v-if="showOtherUniversityInput" type="text" class="form-control mt-2" v-model="thesisStudent.university" placeholder="Enter other university">
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
                              <option disabled :value="null">Select a Gender</option>
                              <option value="Male">Male</option>
                              <option value="Female">Female</option>
                              <option value="Non binary">Non binary</option>
                              </select>
                        </div>
                        <div class="col-md-3">
                            <label for="">Identification: </label>
                            <label for="" style="color: orange;">*</label>
                            <br>
                            <select class="form-select" @change="thesisStudent.run = ''; thesisStudent.passport = '';" v-model="thesisStudent.identification">
                              <option disabled :value="null">Select One</option>
                              <option value="Run">Run</option>
                              <option value="Passport">Passport</option>
                            </select>
                        </div>                        
                        <div v-if="thesisStudent.identification == 'Run'" class="col-md-3">
                            <label for="">Run: </label>
                            <label for="" style="color: orange;">*</label>
                            <br>
                            <input type="text" class= "form-control" v-on:input="validateInput" v-on:keypress="isNumberOrDash" @keyup="checkRut()" v-model="thesisStudent.run">
                        </div>
                        <div v-if="thesisStudent.identification == 'Passport'" class="col-md-3">
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
                            <option disabled :value="null">Select a degree</option>
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
                      <div v-if="thesisStudent.thesisStatus == 1" :class="{'col-md-3': !isLink, 'col-md-5': isLink}">
                        <div class="form-group">
                          <label for="archivo">Thesis Extract:</label>
                          <label for="" style="color: orange;">*</label>
                          <label v-if="thesisStudent1.file != null" title="This record already has a file, if you want to change add a new one, otherwise leave this field empty." style="color: #0A95FF;">
                            <i class="fa-solid fa-circle-info"></i>
                          </label>



                          <!-- Mostrar campo de archivo si no es link -->
                          <div v-if="!isLink">
                            <input type="file" ref="fileInput" accept=".pdf, .jpg, .jpeg, .png," class="form-control" @change="getFile">
                          </div>

                          <!-- Mostrar campo de link si es link -->
                          <div v-else>
                            <input type="text" v-model="link" class="form-control" placeholder="Enter the link here">
                          </div>


                          <!-- Checkbox para seleccionar entre archivo o link -->
                          <div class="form-check pt-2">
                            <input class="form-check-input" type="checkbox" id="isLink" v-model="isLink">
                            <label class="form-check-label" for="isLink">Use Link Instead of File</label>
                          </div>

                          <label v-if="thesisStudent1.file != null && thesisStudent1.is_link == 0" >Current file: {{ fileName }}</label>
                          <label v-if="thesisStudent1.file != null && thesisStudent1.is_link == 1" >Current link: {{ thesisStudent1.file }}</label>
                        </div>
                      </div>

                      <div v-if="thesisStudent.thesisStatus == 1 && !isLink" class="col-md-2 pt-2">
                        <br>
                        <a class="btn btn-closed" title="Clear Input" @click="clearFileInput"><i class="fa-solid fa-ban"></i></a>
                        &nbsp;
                        <a v-if="thesisStudent1.file != null && !isLink" class="btn btn-search-blue" title="Download" @click="descargarExtracto(id,user)">
                          <i class="fa-solid fa-download"></i>
                        </a>
                      </div>

                    </div>
                    <hr size="3" class="separador">
                    <div class="row">
                      <div class="col-md-4 d-flex justify-content-center">
                        <a v-if="tutor1.name == '' && tutor1.institution == ''" class="btn btn-search-blue" @click="showModalAutor = true"><i class="fa-solid fa-plus"></i> Add Tutor </a>
                        <a v-else class="btn btn-alert" @click="showModalEditTutor = true"><i class="fa fa-fw fa-edit"></i> Edit Tutor</a>
                      </div>
                      <div class="col-md-4 d-flex justify-content-center">
                        <a v-if="cotutor1.name == '' && cotutor1.institution == ''" class="btn btn-search-blue" @click="showModalCoautor = true"><i class="fa-solid fa-plus"></i> Add Cotutor </a>
                        <a v-else class="btn btn-alert" @click="showModalEditCotutor = true"><i class="fa fa-fw fa-edit"></i> Edit Cotutor</a>
                      </div>
                      <div class="col-md-4 d-flex justify-content-centerd-flex justify-content-center">
                        <a v-if="other1.name == '' && other1.institution == ''"class="btn btn-search-blue" @click="showModalOther = true"><i class="fa-solid fa-plus"></i> Add Other </a>
                        <a v-else class="btn btn-alert" @click="showModalEditOther = true"><i class="fa fa-fw fa-edit"></i> Edit Other</a>
                      </div>
                    </div>
                    <hr size="3" class="separador">
                    <div class="row">
                      <div class="col-md-3">
                          <label for="">Year in which thesis starts: </label>
                          <label for="" style="color: orange;">*</label>
                          <br>
                          <select class="form-select" id="selectYear" v-model="thesisStudent.yearStart">
                          <option disabled :value="null">Select a year</option>
                          <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
                          </select>
                      </div>
                      <div class="col-md-3">
                          <label for="selectMonth">Month in which student starts:</label>
                          <br>
                          <select class="form-select" id="selectMonth" v-model="thesisStudent.monthStart">
                              <option disabled :value="null">Select a month</option>
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
                      <div class="col-md-3">
                          <label for="">Posterior working area: </label>
                          <label v-if="thesisStudent.thesisStatus == 1" for="" style="color: orange;">*</label>
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
                      <div class="col-md-4">
                          <label for="">Institution of Posterior working area: </label>
                          <label v-if="thesisStudent.thesisStatus == 1" for="" style="color: orange;">*</label>
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
                    v-model="draft"> Edit as a draft</label>
                    <a v-if="draft == false" class="btn btn-continue float-end" @click="editarTesis()" :disabled="buttonDisable">
                      {{ buttonText }}
                    </a>
                    <a v-else class="btn btn-continue float-end" @click="guardarBorrador()" :disabled="buttonDisable">
                      Edit draft
                    </a>
                  </slot>
                </div>
                <modalOther v-if="showModalOther" @close="showModalOther = false" @submit="handleFormSubmit3"></modalOther>
                <modalCoautor v-if="showModalCoautor" @close="showModalCoautor = false" @submit="handleFormSubmit2"></modalCoautor>
                <modalAutor v-if="showModalAutor" @close="showModalAutor = false" @submit="handleFormSubmit4"></modalAutor>
                <modalEditarTutor v-bind:tutors1="tutor1" v-if="showModalEditTutor" @close="showModalEditTutor = false"></modalEditarTutor>
                <modalEditarCotutor v-bind:cotutors1="cotutor1" v-if="showModalEditCotutor" @close="showModalEditCotutor = false"></modalEditarCotutor>
                <modalEditarOther v-bind:others1="other1" v-if="showModalEditOther" @close="showModalEditOther = false"></modalEditarOther>
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
        monthEnd: "",
        yearThesisEnd: "",
        resourcesCenter: null,
        researcherInvolved: null,
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
      isLink: false, // Controla si es un link o un archivo
      link: '',
      selectedUniversity: '',
      showOtherUniversityInput: false,
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
      tutor1:{
        name: '',
        institution: '',
      },
      cotutor1:{
        name: '',
        institution: '',
      },
      other1:{
        name: '',
        institution: '',
      },
      errors:[],
      universities: [],
      id: '',
      user: '',
      researchers:[],
      usuarios: [],
      idResearcher: '',
      buttonText:'Edit Record',
      years: [],
    }),
    mounted() {
      this.calculateYears();

    },
    props:{
      thesisStudent1: Object,
    },
    created() {
        this.getUsuarios();
        this.getUsuarios2();
        this.id = this.thesisStudent1.id;
        this.idResearcher = this.thesisStudent1.idUsuario;
        this.user = this.thesisStudent1.usuario.name;
        this.thesisStudent.studentName = this.thesisStudent1.studentName;
        this.thesisStudent.progressReport = this.thesisStudent1.progressReport;
        this.thesisStudent.identification = this.thesisStudent1.identification;
        this.thesisStudent.monthStart = this.thesisStudent1.monthStart;
        this.link = this.thesisStudent1.is_link === 1 ? this.thesisStudent1.file : ''; // Si es un link, se almacena el valor en `link`
        this.isLink = this.thesisStudent1.is_link === 1 ? true : false; // Configurar `isLink` como booleano (true si es link, false si es archivo)


        this.thesisStudent.monthEnd = this.thesisStudent1.monthEnd || "";
        this.thesisStudent.yearThesisEnd = this.thesisStudent1.yearThesisEnd || "";
        this.thesisStudent.posteriorArea = this.thesisStudent1.posteriorArea || "";
        this.thesisStudent.institutionPosteriorArea = this.thesisStudent1.institutionPosteriorArea || "";

        if (this.thesisStudent1.identification === 'Run') {
            this.thesisStudent.run = this.thesisStudent1.runOrPassport;
        } else {
            this.thesisStudent.passport = this.thesisStudent1.runOrPassport;
        }

        if (this.thesisStudent1.resourcesCenter != null && this.thesisStudent1.resourcesCenter != []) {
            const separatedValues = this.thesisStudent1.resourcesCenter.split(",");
            this.thesisStudent.resourcesCenter = separatedValues.slice(0, -1).map(valor => {
                return { value: valor, name: valor };
            });
        }

        this.thesisStudent.thesisStatus = this.thesisStudent1.thesisStatus;
        this.thesisStudent.gender = this.thesisStudent1.gender;
        this.thesisStudent.studentMail = this.thesisStudent1.studentMail;
        this.thesisStudent.thesisTitle = this.thesisStudent1.thesisTitle;
        this.thesisStudent.academicDegree = this.thesisStudent1.academicDegree;
        this.thesisStudent.degreeDenomination = this.thesisStudent1.degreeDenomination;

        this.tutor1.name = this.thesisStudent1.tutorName || "";
        this.tutor1.institution = this.thesisStudent1.tutorInstitution || "";
        this.cotutor1.name = this.thesisStudent1.cotutorName || "";
        this.cotutor1.institution = this.thesisStudent1.cotutorInstitution || "";
        this.other1.name = this.thesisStudent1.otherName || "";
        this.other1.institution = this.thesisStudent1.otherInstitution || "";

        this.thesisStudent.tutorName = this.thesisStudent1.tutorName;
        this.thesisStudent.tutorInstitution = this.thesisStudent1.tutorInstitution;
        this.thesisStudent.cotutorName = this.thesisStudent1.cotutorName;
        this.thesisStudent.cotutorInstitution = this.thesisStudent1.cotutorInstitution;
        this.thesisStudent.otherName = this.thesisStudent1.otherName;
        this.thesisStudent.otherInstitution = this.thesisStudent1.otherInstitution;

        this.thesisStudent.university = this.thesisStudent1.university;
        this.thesisStudent.yearStart = this.thesisStudent1.yearStart;
        this.thesisStudent.comments = this.thesisStudent1.comments;

        const currentYear = new Date().getFullYear();
        const startYear = 2000;
        const endYear = currentYear + 1;
        this.years = Array.from({ length: endYear - startYear + 1 }, (_, index) => startYear + index);
        this.selectedYear = currentYear;
        this.years.sort((a, b) => b - a);

        if (this.thesisStudent1.researcherInvolved != null) {
            const valoresSeparados1 = this.thesisStudent1.researcherInvolved.split(",");
            this.thesisStudent.researcherInvolved = valoresSeparados1.map((valor, index) => {
                valor = valor.trim();
                if (valor.endsWith('.')) {
                    valor = valor.slice(0, -1);
                }
                return { value: valor, name: valor };
            });
        }

        // Esperar a que las universidades se carguen antes de realizar la verificación
        this.fetchData().then(() => {
            if (!this.universities.some(univ => univ.name === this.thesisStudent1.university)) {
                this.selectedUniversity = 'other';
                this.thesisStudent.university = this.thesisStudent1.university;
                this.showOtherUniversityInput = true;
            } else {
                this.selectedUniversity = this.thesisStudent1.university;
                this.showOtherUniversityInput = false;
            }
        });
    },
    computed: {
      fileName() {
        // Extraer solo el nombre del archivo
        return this.thesisStudent1.file.split('/').pop();
      }
      
    },
    methods: {
      checkIfOther() {
          if (this.selectedUniversity === 'other') {
              this.showOtherUniversityInput = true;
              this.thesisStudent.university = '';
          } else {
              this.showOtherUniversityInput = false;
              this.thesisStudent.university = this.selectedUniversity;
          }
      },
      fetchData() {
        // Función para obtener datos de universidades desde la API
        return axios.get('/api/universities')
            .then(response => {
                this.universities = response.data;
            })
            .catch(error => {
                console.error('Error fetching universities:', error);
            });
      },
      getUsuarios2(){
        // Función para obtener usuarios desde otra ruta de la API
        axios.get('api/usuarios').then( response =>{
            this.usuarios = response.data.sort((a, b) => a.name.localeCompare(b.name));;
        }).catch(e=> console.log(e))
      },
      getUsuarios(){
        // Función para obtener usuarios (investigadores) desde la API
        axios.get('api/researchers').then( response =>{
          this.researchers  = response.data.sort((a, b) => {
              if (a.toLowerCase() < b.toLowerCase()) return -1;
              if (a.toLowerCase() > b.toLowerCase()) return 1;
              return 0;
          });
        }).catch(e=> console.log(e))
      },
      handleFormSubmit1(year) {
        // Función para manejar el envío de un formulario con un año
        this.thesisStudent.progressReport = year;
      },
      descargarExtracto(id,nombre){
        // Función para descargar un extracto de la tesis
        axios({
            url: `api/thesisDownload/${id}`,
            method: 'GET',
            responseType: 'arraybuffer',
        }).then((response) => {
          let blob = new Blob([response.data], {
                  type: response.headers['content-type']
              });
              let link = document.createElement('a');
              link.href = window.URL.createObjectURL(blob);
              if (blob.type.includes('pdf')) {
                  link.download = `Thesis-${nombre}.pdf`;
              } else if (blob.type.includes('image')) {
                  link.download = `Thesis-${nombre}.png`; // Cambia la extensión según el tipo de imagen
              } else {
                  // Si el tipo de archivo no es ni PDF ni imagen, puedes manejarlo de acuerdo a tus requerimientos
                  console.error('Tipo de archivo no compatible');
                  return;
              }
              link.click();
        });
      },
      // Función para manejar el envío de un formulario
      handleFormSubmit4(formData) {
        this.tutor1.name = formData.name;
        this.tutor1.institution = formData.institution;
      },
      // Función para manejar el envío de un formulario
      handleFormSubmit2(formData) {
        this.cotutor1.name = formData.name;
        this.cotutor1.institution = formData.institution;
      },
      // Función para manejar el envío de un formulario
      handleFormSubmit3(formData) {
        this.other1.name = formData.name;
        this.other1.institution = formData.institution;
      },
      onInput1(event) {
        // Función para manejar la entrada de un campo de año
        const input = event.target;
        // Limitar el año a 4 dígitos
        this.thesisStudent.yearStart = input.value.slice(0, 4);
      },
      onInput2(event) {
        // Función para manejar la entrada de un campo de año
        const input = event.target;
        // Limitar el año a 4 dígitos
        this.thesisStudent.yearThesisEnd = input.value.slice(0, 4);
      },
      clearFileInput() {
        // Función para limpiar la entrada de archivo
        this.file = null;
        this.$refs.fileInput.value = '';
      },
      // Función para obtener el archivo seleccionado
      async getFile(e) {
          this.file = e.target.files[0];

          if (!this.file) return;

          const fileType = this.file.type;
          const allowedTypes = ['application/pdf', 'image/jpeg', 'image/png'];
          const maxSizeMB = 20; // Tamaño máximo permitido en MB
          const maxSizeBytes = maxSizeMB * 1024 * 1024; // Convertir MB a bytes

          // Verificar si el tipo de archivo está en la lista permitida
          if (!allowedTypes.includes(fileType)) {
              // Si el archivo no es PDF ni imagen permitida, mostrar mensaje de error
              this.toast.error("Unsupported file type. Please upload a PDF or an image (JPG, JPEG, PNG).", {
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
              // Limpiar el input de archivo
              e.target.value = '';
              return;
          }

          // Verificar si el tamaño del archivo supera el máximo permitido
          if (this.file.size > maxSizeBytes) {
              // Mostrar mensaje de error si el archivo es demasiado grande
              this.toast.error(`File is too large. Maximum size allowed is ${maxSizeMB} MB.`, {
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
              // Limpiar el input de archivo
              e.target.value = '';
              return;
          }

      },
      calculateYears() {
        // Función para calcular los años
        const currentYear = new Date().getFullYear();
        const startYear = currentYear - 20;
        this.years = Array.from({ length: 21 }, (_, index) => startYear + index);
      },
      checkRut() {
        // Función para verificar y formatear el RUN
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
        // Función para validar la entrada
        this.thesisStudent.run = this.thesisStudent.run.replace(/[^0-9Kk\-]/g, '').replace(/(\-)\-+/g, '$1');
      },
      isNumberOrDash(event) {
        // Función para permitir solo números o guiones
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
        // Función para cerrar el modal y emitir un evento de recarga
        const elem = this.$refs.closeBtn;
        this.$emit('recarga');
        elem.click();
      },
      capitalizeFirstLetter(string) {
        // Función para capitalizar la primera letra de una cadena
        return string.charAt(0).toUpperCase() + string.slice(1);
      },
      async guardarBorrador(){
        // Función para guardar un borrador de la tesis
        const ok = await this.$refs.confirmation.show({
          title: 'Edit draft',
            message: `Are you sure you want to edit the report as a draft?
 By saving as a draft, the information will not be reported. However, you can modify the report's status at any time and submit it, as long as all mandatory information is completed.
`,
            okButton: 'Edit',
            cancelButton: 'Return'
        })
        if (ok) {
          var runOrPassport1 = '';
          if(this.thesisStudent.identification == 'Run'){
            runOrPassport1 = this.formatoRut(this.thesisStudent.run);
          }else{
            runOrPassport1 = this.thesisStudent.passport;
          }

          var resources1 = '';

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

          let universityToSubmit = this.selectedUniversity === 'other' ? this.thesisStudent.university : this.selectedUniversity;

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
            tutorName: this.tutor1.name,
            tutorInstitution: this.tutor1.institution,
            cotutorName: this.cotutor1.name,
            cotutorInstitution: this.cotutor1.institution,
            otherName: this.other1.name,
            otherInstitution: this.other1.institution,
            university: universityToSubmit,
            yearStart: this.thesisStudent.yearStart,
            monthStart: this.thesisStudent.monthStart,
            monthEnd: this.thesisStudent.monthEnd,
            yearThesisEnd: this.thesisStudent.yearThesisEnd,
            resourcesCenter: resources1,
            posteriorArea: this.thesisStudent.posteriorArea,
            institutionPosteriorArea: this.thesisStudent.institutionPosteriorArea,
            comments: this.thesisStudent.comments,
            progressReport: this.thesisStudent.progressReport,
          };
          console.log(thesisStudent);
          axios.put(`api/thesisStudents/${this.id}`, thesisStudent).then((result) => {
            this.buttonDisable = true;
            this.buttonText = 'Sending...';
            console.log(result.data);
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
            if(this.file != null || this.link != '') {
              const formData = new FormData();
              formData.append('id', this.id);

              if(this.file != null) {
                // Si el archivo es proporcionado
                formData.append('file', this.file);
                let isLinkFlag = this.isLink ? 1 : 0;
                formData.append('is_link', isLinkFlag);
                axios.post('api/thesisStudents/addFile', formData, {
                  headers: { 'Content-Type' : 'multipart/form-data' }
                }).then(response => {
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
                }).catch(error => {
                  if (error.response && error.response.status === 400) {
                    this.toast.error(error.response.data.error, {
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
                  } else if (error.response && error.response.status === 422) {
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
                  this.buttonDisable = false;
                  this.buttonText = 'Edit Thesis';
                });
              } else if(this.link != '') {
                // Si solo el link es proporcionado
                formData.append('file', this.link);
                let isLinkFlag = this.isLink ? 1 : 0;
                formData.append('is_link', isLinkFlag);
                axios.post('api/thesisStudents/addFile', formData, {
                  headers: { 'Content-Type' : 'multipart/form-data' }
                }).then(response => {
                  console.log(response.data);
                  this.toast.success("Link added successfully!", {
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
                }).catch(error => {
                  if (error.response && error.response.status === 400) {
                    this.toast.error(error.response.data.error, {
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
                  } else if (error.response && error.response.status === 422) {
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
                  this.buttonDisable = false;
                  this.buttonText = 'Edit Thesis';
                });
              }
            } else {
              // Si ni archivo ni link fueron proporcionados
              this.toast.error('Please upload a file or provide a link.', {
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
              this.buttonDisable = false;
              this.buttonText = 'Edit Thesis';
            }

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
        // Edita el registro despues de validar
        async editarTesis() {
        // Función para editar una tesis
        this.errors = [];
        const fieldsToExclude = ['yearThesisEnd', 'posteriorArea','institutionPosteriorArea','comments', 'monthEnd','run','passport','tutorName', 'tutorInstitution','cotutorName','cotutorInstitution','otherName','otherInstitution','monthStart']; // Arreglo de campos a excluir

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

        // Validar que al menos uno de los conjuntos tenga ambos campos rellenados
        let isValid = false;

        if (this.tutor1.name !== '' && this.tutor1.institution !== '') {
          isValid = true;
        }

        if (this.cotutor1.name !== '' && this.cotutor1.institution !== '') {
          isValid = true;
        }

        if (this.other1.name !== '' && this.other1.institution !== '') {
          isValid = true;
        }

        if (!isValid) {
          this.errors.push('noTutors');
        }

        if(this.thesisStudent.identification == 'Run' && this.thesisStudent.run != ""){
          var validacion = this.validarRut(this.thesisStudent.run);
          if(validacion == false){
            this.errors.push('invalidRut');
            }
        }

        if(this.thesisStudent.thesisStatus == 1 && (this.file == null && this.thesisStudent1.file == null) && this.isLink == false){
            this.errors.push('thesisExtract');
        }

        if(this.thesisStudent.thesisStatus == 1 && this.link == '' && this.isLink == true){
            this.errors.push('link');
        }

        if(this.link && this.isLink && !this.isValidURL(this.link)){
            this.errors.push('invalidLink');
        }

        if(this.thesisStudent.thesisStatus == 1 && this.thesisStudent.yearThesisEnd == ""){
            this.errors.push('yearThesisEnd');
        }

        if(this.thesisStudent.thesisStatus == 1 && this.thesisStudent.posteriorArea == ""){
            this.errors.push('posteriorArea');
        }

        if(this.thesisStudent.thesisStatus == 1 && this.thesisStudent.institutionPosteriorArea == ""){
            this.errors.push('institutionPosteriorArea');
        }

        console.log(this.errors);
        var runOrPassport1 = '';
          if(this.thesisStudent.identification == 'Run'){
            runOrPassport1 = this.formatoRut(this.thesisStudent.run);
          }else{
            runOrPassport1 = this.thesisStudent.passport;
          }
          
        let thesisStudent1 = {
          id: this.id,
          runOrPassport: runOrPassport1,
          degreeDenomination: this.thesisStudent.degreeDenomination,
        };

        var contador = await axios.post('../api/verifyThesis', thesisStudent1).then(function(response) {
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
            }else if(item == 'thesisExtract'){
              mensaje =   mensaje + "The field Thesis Extract is required" + "\n";
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
              mensaje =   mensaje + "The field Year in which student thesis starts is required" + "\n";
            }else if(item == 'monthStart'){
              mensaje =   mensaje + "The field Month in which student thesis starts is required" + "\n";
            }else if(item == 'yearThesisEnd'){
              mensaje =   mensaje + "The field Year which thesis ends is required" + "\n";
            }else if(item == 'monthEnd'){
              mensaje =   mensaje + "The field Month which thesis ends is required" + "\n";
            }else if(item == 'resourcesCenter'){
              mensaje =   mensaje + "The field Resources provided is required" + "\n";
            }else if(item == 'posteriorArea'){
              mensaje =   mensaje + "The field Posterior working area is required" + "\n";
            }else if(item == 'researcherInvolved'){
              mensaje =   mensaje + "The field Researchers involved is required" + "\n";
            }else if(item == 'invalidLink'){
              mensaje =   mensaje + "The link provided is not valid" + "\n";
            }else if(item == 'institutionPosteriorArea'){
              mensaje =   mensaje + "The field Institution posterior working area is required" + "\n";
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
            title: 'Edit Thesis',
            message: `¿Are you sure you want to edit this thesis?.`,
            okButton: 'Edit',
            cancelButton: 'Return'
          })
          if (ok) {
            var resources1 = '';

            this.thesisStudent.resourcesCenter.forEach(resource => {
              resources1 += resource.value + ',';
            });

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
            
            let universityToSubmit = this.selectedUniversity === 'other' ? this.thesisStudent.university : this.selectedUniversity;

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
              tutorName: this.tutor1.name,
              tutorInstitution: this.tutor1.institution,
              cotutorName: this.cotutor1.name,
              cotutorInstitution: this.cotutor1.institution,
              otherName: this.other1.name,
              otherInstitution: this.other1.institution,
              university: universityToSubmit,
              yearStart: this.thesisStudent.yearStart,
              monthStart: this.thesisStudent.monthStart,
              monthEnd: this.thesisStudent.monthEnd,
              yearThesisEnd: this.thesisStudent.yearThesisEnd,
              resourcesCenter: resources1,
              posteriorArea: this.thesisStudent.posteriorArea,
              institutionPosteriorArea: this.thesisStudent.institutionPosteriorArea,
              comments: this.thesisStudent.comments,
              progressReport: this.thesisStudent.progressReport,
            };
            axios.put(`api/thesisStudents/${this.id}`, thesisStudent).then((result) => {
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
              console.log(this.file);
              if(this.file != null || this.link != '') {
                const formData = new FormData();
                formData.append('id', this.id);

                if(this.file != null) {
                  // Si el archivo es proporcionado
                  formData.append('file', this.file);
                  let isLinkFlag = this.isLink ? 1 : 0;
                  formData.append('is_link', isLinkFlag);
                  // 🔍 Verificar que se está enviando correctamente
                  for (let [key, value] of formData.entries()) {
                    console.log(`${key}:`, value);
                  }
                  fetch('api/thesisStudents/addFile', {
                    method: 'POST',
                    body: formData,
                    headers: { 'Content-Type': 'multipart/form-data' }
                  }).then(response => response.json())
                    .then(data => console.log("Respuesta del backend:", data))
                    .catch(error => console.error("Error en fetch:", error));
                  // axios.post('api/thesisStudents/addFile', formData, {
                  //   headers: { 'Content-Type' : 'multipart/form-data' }
                  // }).then(response => {
                  //   console.log(response.data);
                  //   this.toast.success("File added successfully!", {
                  //     position: "top-right",
                  //     timeout: 3000,
                  //     closeOnClick: true,
                  //     pauseOnFocusLoss: true,
                  //     pauseOnHover: true,
                  //     draggable: true,
                  //     draggablePercent: 0.6,
                  //     showCloseButtonOnHover: false,
                  //     hideProgressBar: true,
                  //     closeButton: "button",
                  //     icon: true,
                  //     rtl: false
                  //   });
                  //   setTimeout(() => {this.cerrarModal();}, 1500);
                  // }).catch(error => {
                  //   if (error.response && error.response.status === 400) {
                  //     this.toast.error(error.response.data.error, {
                  //       position: "top-right",
                  //       timeout: 3000,
                  //       closeOnClick: true,
                  //       pauseOnFocusLoss: true,
                  //       pauseOnHover: true,
                  //       draggable: true,
                  //       draggablePercent: 0.6,
                  //       showCloseButtonOnHover: false,
                  //       hideProgressBar: true,
                  //       closeButton: "button",
                  //       icon: true,
                  //       rtl: false
                  //     });
                  //   } else if (error.response && error.response.status === 422) {
                  //     this.errors = error.response.data.errors;
                  //     this.toast.warning('There is an invalid value.', {
                  //       position: "top-right",
                  //       timeout: 3000,
                  //       closeOnClick: true,
                  //       pauseOnFocusLoss: true,
                  //       pauseOnHover: true,
                  //       draggable: true,
                  //       draggablePercent: 0.6,
                  //       showCloseButtonOnHover: false,
                  //       hideProgressBar: true,
                  //       closeButton: "button",
                  //       icon: true,
                  //       rtl: false
                  //     });
                  //   }
                  //   this.buttonDisable = false;
                  //   this.buttonText = 'Edit Thesis';
                  // });
                } else if(this.link != '') {
                  // Si solo el link es proporcionado
                  formData.append('file', this.link);
                  let isLinkFlag = this.isLink ? 1 : 0;
                  formData.append('is_link', isLinkFlag);
                  axios.post('api/thesisStudents/addFile', formData, {
                    headers: { 'Content-Type' : 'multipart/form-data' }
                  }).then(response => {
                    console.log(response.data);
                    this.toast.success("Link added successfully!", {
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
                  }).catch(error => {
                    if (error.response && error.response.status === 400) {
                      this.toast.error(error.response.data.error, {
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
                    } else if (error.response && error.response.status === 422) {
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
                    this.buttonDisable = false;
                    this.buttonText = 'Edit Thesis';
                  });
                }
              } else if(this.thesisStudent1.file == null) {
                // Si ni archivo ni link fueron proporcionados
                this.toast.error('Please upload a file or provide a link.', {
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
                this.buttonDisable = false;
                this.buttonText = 'Edit Thesis';
              }else{
                setTimeout(() => {this.cerrarModal();}, 1500);
              }
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