<template>
  <transition name="modalcrear">
    <div name="modal">
      <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container">
              <div class="modal-header pb-1 fw-bold" style="color: #444444;">
                <slot name="header">
                    New Publication
                </slot>
                <label for="">Progress report year: {{ nonIsiPublication.progressReport }} &nbsp;&nbsp; <a class="btn" v-if="is('Administrator')"@click="showModalProgress = true"><i class="fa-solid fa-pen-to-square"></i></a></label>
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
                            <label for="">AC3E Researchers Involved: </label>
                            <label for="" style="color: orange;">*</label>
                            <label title="You must list all the researchers who are part of the center." style="color: #0A95FF;"><i class="fa-solid fa-circle-info"></i></label>
                            <br>
                            <div>
                              <Multiselect
                                  placeholder="Select the options"
                                  v-model="nonIsiPublication.researcherInvolved"
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
                        </div>
                    </div>
                    <br>
                    <div class="row">
                          <div class="col-md-6">
                              <label for="">Author(s): </label>
                              <label for="" style="color: orange;">*</label>
                              <label title="The format for this field should be as follows: 'Last Name, Initial of First Name; Last Name, Initial of First Name; ...'" style="color: #0A95FF;"><i class="fa-solid fa-circle-info"></i></label>
                              <br>
                              <input  type="text" class= "form-control" v-model="nonIsiPublication.authors">
                          </div>
                          <div class="col-md-3">
                            <label for="">Year Published:</label>
                            <label for="" style="color: orange;">*</label>
                            <br>
                            <select class="form-select" id="selectYear" v-model="nonIsiPublication.yearPublished">
                              <option disabled value="">Select a year</option>
                              <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
                            </select>
                          </div>
                          <div class="col-md-3">
                          <label for="selectMonth">Month:</label>
                          <br>
                          <select class="form-select" id="selectMonth" v-model="nonIsiPublication.month">
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
                      <div class="col-md-6">
                        <label for="">Article Title:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="nonIsiPublication.articleTitle">
                      </div>
                      <div class="col-md-6">
                        <label for="">Journal Name:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="nonIsiPublication.journalName">
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-md-4">
                        <label for="">Volume:</label>
                        <br>
                        <input type="text" class= "form-control" v-model="nonIsiPublication.volume">
                      </div>
                      <div class="col-md-4">
                        <label for="">First Page:</label>
                        <br>
                        <input type="text" class= "form-control" v-model="nonIsiPublication.firstPage">
                      </div>
                      <div class="col-md-4">
                        <label for="">Last Page:</label>
                        <br>
                        <input type="text" class= "form-control" v-model="nonIsiPublication.lastPage">
                      </div>

                    </div>
                    <br>
                    <div class="row">                        
                      <div class="col-md-6">
                        <label for="">Fundings: </label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <div class="form-check pt-2">
                          <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" v-model="fundingSources.basal">
                            Basal Financing Program Funding
                          </label>
                        </div>
                        <div class="form-check pt-2">
                          <label class="form-check-label">
                            <input type="checkbox" class="form-check-input" v-model="fundingSources.other">
                            Other sources
                          </label>
                        </div>
                      </div>
                      <div class="col-md-6">
                        <label for="">Comments:</label>
                        <br>
                        <input type="text" class= "form-control" v-model="nonIsiPublication.comments">
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div :class="{'col-md-5': !isLink, 'col-md-6': isLink}">
                        <div class="form-group">
                          <label for="archivo" v-if="isLink">Link: </label>
                          <label for="archivo" v-else>File: </label>
                          <label for="" style="color: orange;">*</label>
                          <label title="A document verifying event participation and containing relevant information about it must be uploaded. Suitable documents include conference programs, participation certificates, or confirmation emails from the organizers. Any one of these will suffice." style="color: #0A95FF;"><i class="fa-solid fa-circle-info"></i></label>
                          <!-- Input para archivo (solo si isLink es false) -->
                          <input v-if="!isLink" type="file" ref="fileInput" accept=".pdf, .png, .jpg, .jpeg" class="form-control" @change="getFile">
    
                          <!-- Input para el link (solo si isLink es true) -->
                          <input v-if="isLink" type="text" v-model="link" placeholder="Enter the link" class="form-control">
                          <!-- Checkbox para alternar entre subir archivo o ingresar un link -->
                          <div class="form-check pt-2">
                            <input type="checkbox" id="isLink" v-model="isLink" class="form-check-input">
                            <label for="isLink" class="form-check-label">Upload Link instead of File</label>
                          </div>
                        </div>
                        
                      </div>

                      <!-- Clear button for file input -->
                      <div v-if="!isLink" class="col-md-1 pt-2">
                        <br>
                        <a class="btn btn-closed" title="Clear Input" @click="clearFileInput"><i class="fa-solid fa-ban"></i></a>
                      </div>
                      <div class="col-md-6">
                          <label for="">Indexed by: </label>
                          <label for="" style="color: orange;">*</label>
                          <br>
                          <div>
                            <Multiselect
                                placeholder="Select the options"
                                v-model="nonIsiPublication.indexedBy"
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
                    </div>
                    <br>

                    <div class="row">
                      <div class="col-md-6">
                          <div class="form-check pt-2 ">
                            <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                  v-model="participationScToggle"> I participated in a conference with
                                  this post</label>
                          </div>
                      </div>
                    </div>
                    <div v-if="participationScToggle">
                      <hr>

                    <div class="row">
                      <div class="col-md-6">
                          <label for="">Type of Event:</label>
                          <label for="" style="color: orange;">*</label>
                          <Multiselect
                              placeholder="Select the options"
                              v-model="participationSc.typeEvent"
                              limit=8
                              :searchable="true"
                              :close-on-select="false"
                              :createTag="true"
                              :options="optionsTypeEvent"
                              mode="tags"
                              label="name"
                              trackBy="name"
                              :object="true"
                          />
                        </div>
                        <div class="col-md-6">
                          <label for="">Type of Participation:</label>
                          <label for="" style="color: orange;">*</label>
                          <Multiselect
                              placeholder="Select the options"
                              v-model="participationSc.typeOfParticipation"
                              limit=8
                              :searchable="true"
                              :close-on-select="false"
                              :createTag="true"
                              :options="optionsTypeParticipation"
                              mode="tags"
                              label="name"
                              trackBy="name"
                              :object="true"
                          />
                        </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-md-6">
                          <label for="">Event Name:</label>
                          <label for="" style="color: orange;">*</label>
                          <br>
                          <input type="text" class= "form-control" v-model="participationSc.eventName">
                        </div>
                        <div class="col-md-6">
                          <label for="">Presentation Title:</label>
                          <label for="" style="color: orange;">*</label>
                          <br>
                          <input type="text" class= "form-control" v-model="participationSc.presentationTitle">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-md-3">
                        <label for="">Country:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="participationSc.country">
                      </div>
                      <div class="col-md-3">
                        <label for="">City:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="participationSc.city">
                      </div>
                      <div class="col-md-3">
                        <label for="">Start Date:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="date" class= "form-control" v-model="participationSc.startDate">
                      </div>
                      <div class="col-md-3">
                        <label for="">Ending Date:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="date" class= "form-control" v-model="participationSc.endingDate">
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-md-6">
                        <label for="">Name of participants:</label>
                        <label for="" style="color: orange;">*</label>
                        <label title="The format for this field should be as follows: 'First Name,Last Name; First Name,Last Name; ...'" style="color: #0A95FF;"><i class="fa-solid fa-circle-info"></i></label>
                        <input type="text" class= "form-control" v-model="participationSc.nameOfParticipants">
                      </div>
                    </div>
                    </div>
                  </slot>
                </div>
                <div class="modal-footer">
                  <slot name="footer">
                    <label class="form-check-label"><input type="checkbox" class="form-check-"
                    v-model="draft"> Save as a draft</label>
                    <a v-if="draft == false" class="btn btn-continue float-end" @click="crearPublicacion()" :disabled="buttonDisable">
                      {{ buttonText }}
                    </a>
                    <a v-else class="btn btn-continue float-end" @click="guardarBorrador()" :disabled="buttonDisable">
                      Save draft
                    </a>
                  </slot>
                </div>
                <modalfotoperfil v-if="showFotoPerfil" @close="showFotoPerfil = false"></modalfotoperfil>
                <modalconfirmacion ref="confirmation"></modalconfirmacion>
                <modalalerta ref="alert"></modalalerta>
                <modalProgressYear v-bind:progressYear="nonIsiPublication.progressReport" v-if="showModalProgress" @close="showModalProgress = false" @submit="handleFormSubmit1"></modalProgressYear>
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
import modalProgressYear from '../../sistema/progressYearEdit.vue';

export default {
    components: { modalProgressYear,Multiselect, modalconfirmacion, modalalerta },
    mixins: [mixin],
    data: () => ({
      fundingSources: {
        basal: false,
        other: false,
      },
      nonIsiPublication:{
        authors: "",
        articleTitle: "",
        indexedBy: null,
        journalName: "",
        volume: "",
        firstPage: "",
        lastPage: "",
        yearPublished: "",
        month: "",
        fundings: null,
        file: null,
        // link: '',
        researcherInvolved: null,
        comments: "",
        progressReport: "",
      },
      participationSc:{
        typeEvent: null,
        eventName: '',
        presentationTitle: '',
        typeOfParticipation: null,
        country: '',
        city: '',
        startDate: '',
        endingDate: '',
        nameOfParticipants: '',
      },
      options1: [
        'Basal Financing Program Funding',
        'Other sources',
      ],
      options2: [
        'Scielo',
        'Scopus',
        'Conference',
        'Other',
      ],
      optionsTypeEvent: [
        "International congress",
        "National congress",
        "Workshop",
        "Course",
        "Conference",
        "Seminar",
        "Symposium",
        "Other",
      ],
      optionsTypeParticipation: [
        "Paper presentation",
        "Talk",
        "Session chair",
        "Keynote",
        "Just assistance",
        "Other",
      ],
      isLink: 0, // Controla si es un link o un archivo
      link: '',
      participationScToggle: false,
      currentYear: new Date().getFullYear(),
      draft: false,
      buttonDisable: false,
      showModalProgress: false,
      researchers: [],
      usuarios: [],
      errors:[],
      idResearcher: '',
      buttonText:'Send New Record',
    }),
    created(){
      this.getProgressReport();
      this.getUsuarios();
      this.getUsuarios2();
      const currentYear = new Date().getFullYear();
      const startYear = 2000;
      const endYear = currentYear + 1;
      this.years = Array.from({ length: endYear - startYear + 1 }, (_, index) => startYear + index);
      this.selectedYear = currentYear;
      this.years.sort((a, b) => b - a); 
    },
    methods: {
      // Función para manejar el envío de un formulario con un año
      handleFormSubmit1(year) {
        this.nonIsiPublication.progressReport = year;
      },
      // Función para obtener usuarios desde otra ruta de la API
      getUsuarios2(){
        axios.get('api/usuarios').then( response =>{
            this.usuarios = response.data.sort((a, b) => a.name.localeCompare(b.name));;
        }).catch(e=> console.log(e))
      },
      // Función para limpiar la entrada de archivo
      clearFileInput() {
        this.$refs.fileInput.value = '';
      },
      // Función para obtener el archivo seleccionado
      async getFile(e) {
          const file = e.target.files[0];

          if (!file) return;

          const fileType = file.type;
          const allowedTypes = ['application/pdf', 'image/jpeg', 'image/png'];
          const maxSizeMB = 20; // Tamaño máximo permitido en MB
          const maxSizeBytes = maxSizeMB * 1024 * 1024; // Convertir MB a Bytes

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
          if (file.size > maxSizeBytes) {
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

          // Asignar el archivo válido a la propiedad correspondiente
          this.nonIsiPublication.file = file;
      },

      // Función para obtener usuarios (investigadores) desde la API
      getUsuarios(){
        axios.get('api/researchers').then( response =>{
          this.researchers = response.data.sort((a, b) => {
              if (a.toLowerCase() < b.toLowerCase()) return -1;
              if (a.toLowerCase() > b.toLowerCase()) return 1;
              return 0;
          });
        }).catch(e=> console.log(e))
      },
      // Función para obtener el año de progreso desde la API
      getProgressReport(){
        axios.get('api/showProgressReport').then( response =>{
            this.nonIsiPublication.progressReport = response.data;
        }).catch(e=> console.log(e))
      },
      // Función para guardar un borrador 
      async guardarBorrador(){
        const ok = await this.$refs.confirmation.show({
            title: 'Save draft',
            message: `Are you sure you want to save the report as a draft?
 By saving as a draft, the information will not be reported. However, you can modify the report's status at any time and submit it, as long as all mandatory information is completed.
`,
            okButton: 'Save',
            cancelButton: 'Return'
          })
          if (ok) {
            let fundingsName1 = "";

            if (this.fundingSources.basal) {
              fundingsName1 += "Basal Financing Program Funding";
            }

            if (this.fundingSources.other) {
              if (fundingsName1) {
                fundingsName1 += ", "; // Agregar coma si hay más de un valor
              }
              fundingsName1 += "Other sources";
            }

            if (fundingsName1) {
              fundingsName1 += "."; // Agregar punto final al string
            }

            var indexedBy1 = "";
            if (this.nonIsiPublication.indexedBy !== null){
              if (this.nonIsiPublication.indexedBy.length !== 0) {
                this.nonIsiPublication.indexedBy.forEach((indexedBy, index) => {
                  indexedBy1 += indexedBy.name;
                  if (index === this.nonIsiPublication.indexedBy.length - 1) {
                    indexedBy1 += '.';
                  } else {
                    indexedBy1 += ', ';
                  }
                });
              }
            }

            var researcherInvolved1 = "";
            if (this.nonIsiPublication.researcherInvolved !== null){
              if (this.nonIsiPublication.researcherInvolved.length !== 0) {
                this.nonIsiPublication.researcherInvolved.forEach((researcherInvolved, index) => {
                  researcherInvolved1 += researcherInvolved.name;
                  if (index === this.nonIsiPublication.researcherInvolved.length - 1) {
                    researcherInvolved1 += '.';
                  } else {
                    researcherInvolved1 += ', ';
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

            let fileOrLink = this.isLink ? this.link : this.participationSc.file;
            let isLinkFlag = this.isLink ? 1 : 0;

            let publication = {
              status: 'Draft',
              idUsuario: idUser1,
              authors: this.nonIsiPublication.authors,
              indexedBy: indexedBy1,
              researcherInvolved: researcherInvolved1,
              articleTitle: this.nonIsiPublication.articleTitle,
              journalName: this.nonIsiPublication.journalName,
              volume: this.nonIsiPublication.volume,
              firstPage: this.nonIsiPublication.firstPage,
              lastPage: this.nonIsiPublication.lastPage,
              yearPublished: this.nonIsiPublication.yearPublished,
              month: this.nonIsiPublication.month,
              funding: fundingsName1,
              comments: this.nonIsiPublication.comments,
              file: fileOrLink,         // Enviar archivo o link
              isLink: isLinkFlag,      
              progressReport: this.nonIsiPublication.progressReport,
            };
            axios.post("api/nonIsiPublications", publication, {headers: { 'Content-Type' : 'multipart/form-data' }} ).then((result) => {
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

              if(this.participationScToggle){
                var typeEvent1 = "";
                if (this.participationSc.typeEvent !== null){
                  if (this.participationSc.typeEvent.length !== 0) {
                    this.participationSc.typeEvent.forEach((typeEvent, index) => {
                      typeEvent1 += typeEvent.name;
                      if (index === this.participationSc.typeEvent.length - 1) {
                        typeEvent1 += '.';
                      } else {
                        typeEvent1 += ', ';
                      }
                    });
                  }
                }

                var typeOfParticipation1 = "";
                if (this.participationSc.typeOfParticipation !== null){
                  if (this.participationSc.typeOfParticipation.length !== 0) {
                    this.participationSc.typeOfParticipation.forEach((typeOfParticipation, index) => {
                      typeOfParticipation1 += typeOfParticipation.name;
                      if (index === this.participationSc.typeOfParticipation.length - 1) {
                        typeOfParticipation1 += '.';
                      } else {
                        typeOfParticipation1 += ', ';
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

                var peopleInvolved1 = "";
                if (this.nonIsiPublication.researcherInvolved !== null){
                  if (this.nonIsiPublication.researcherInvolved.length !== 0) {
                    this.nonIsiPublication.researcherInvolved.forEach((researcherInvolved, index) => {
                      peopleInvolved1 += researcherInvolved.name;
                      if (index === this.nonIsiPublication.researcherInvolved.length - 1) {
                        peopleInvolved1 += '.';
                      } else {
                        peopleInvolved1 += ', ';
                      }
                    });
                  }
                }

                let participationSc = {
                  status: 'Finished',
                  researcherInvolved: peopleInvolved1,
                  idUsuario: idUser1,
                  typeEvent: typeEvent1,
                  presentationTitle: this.participationSc.presentationTitle,
                  typeOfParticipation: typeOfParticipation1,
                  eventName: this.participationSc.eventName,
                  country: this.participationSc.country,
                  city: this.participationSc.city,
                  startDate: this.participationSc.startDate,
                  endingDate: this.participationSc.endingDate,
                  progressReport: this.nonIsiPublication.progressReport,
                  nameOfParticipants: this.participationSc.nameOfParticipants,
                  file: fileOrLink,         // Enviar archivo o link
                  isLink: isLinkFlag,   
                  comments: this.nonIsiPublication.comments,
                };
                axios.post("api/participationScEvents", participationSc, {headers: { 'Content-Type' : 'multipart/form-data' }} ).then((result) => {
                  this.toast.success("Participation draft saved successfully!", {
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
                    }else if (error.response && error.response.status === 400) {
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
                }else if (error.response && error.response.status === 400) {
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
              this.buttonDisable = false;
              this.buttonText = 'Send New Record';
            });
          }
      },
      onInput(event) {
        const input = event.target;
        // Limitar el año a 4 dígitos
        this.nonIsiPublication.yearPublished = input.value.slice(0, 4);
      },
        // Cierra el modal y emite un evento de recarga.
        cerrarModal(){
        const elem = this.$refs.closeBtn;
        this.$emit('recarga');
        elem.click();
      },
        // Capitaliza la primera letra de una cadena.
        capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
      },
      async crearPublicacion() {
        this.errors = [];
        const itemsToSkip = [
        'comments',
        'volume',
        'firstPage',
        'lastPage',
        'month',
        'fundings',
        'file'
        ];

        for (const item in this.nonIsiPublication) {
            const skipItem = itemsToSkip.includes(item);
            if (!skipItem && (this.nonIsiPublication[item] === "" || this.nonIsiPublication[item] === 0 || this.nonIsiPublication[item] == null)) {
                this.errors.push(item);
            }
        }

        if(this.nonIsiPublication.file == null && this.isLink == false){
            this.errors.push('file');
        }

        if(this.link == '' && this.isLink == true){
            this.errors.push('link');
        }

        if(this.link && this.isLink && !this.isValidURL(this.link)){
            this.errors.push('invalidLink');
        }
      
        if(this.participationScToggle){
          for (const item in this.participationSc) {
              const skipItem = itemsToSkip.includes(item);
              if (!skipItem && (this.participationSc[item] === "" || this.participationSc[item] === 0 || this.participationSc[item] == null)) {
                  this.errors.push(item);
              }
          }
        }

        if (!this.fundingSources.basal && !this.fundingSources.other) {
          this.errors.push("fundings");
        }



        var contador = await axios.post('../api/verifyNonIsi', this.nonIsiPublication).then(function(response) {
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
            
            if(item == 'articleTitle'){
              mensaje =   mensaje + "The field Article Title is required" + "\n";
            }else if(item == 'journalName'){
              mensaje =   mensaje + "The field Journal Name is required" + "\n";
            }else if(item == 'fundings'){
              mensaje =   mensaje + "At least one funding source must be selected." + "\n";
            }else if(item == 'firstPage'){
              mensaje =   mensaje + "The field First Page is required" + "\n";
            }else if(item == 'lastPage'){
              mensaje =   mensaje + "The field Last Page is required" + "\n";
            }else if(item == 'yearPublished'){
              mensaje =   mensaje + "The field Year Published is required" + "\n";
            }else if(item == 'indexedBy'){
              mensaje =   mensaje + "The field Indexed By is required" + "\n";
            }else if(item == 'researcherInvolved'){
              mensaje =   mensaje + "The field Researchers Involved is required" + "\n";
            }else if(item == 'duplicated'){
              mensaje =   mensaje + "There is already a post with the same data, please try again." + "\n";
            }else if(item == 'typeEvent'){
              mensaje =   mensaje + "The field Type Event is required" + "\n";
            }else if(item == 'typeOfParticipation'){
              mensaje =   mensaje + "The field Type of Participation is required" + "\n";
            }else if(item == 'presentationTitle'){
              mensaje =   mensaje + "The field Presentation Title is required" + "\n";
            }else if(item == 'eventName'){
              mensaje =   mensaje + "The field Event Name is required" + "\n";
            }else if(item == 'startDate'){
              mensaje =   mensaje + "The field Start Date is required" + "\n";
            }else if(item == 'endingDate'){
              mensaje =   mensaje + "The field Ending Date is required" + "\n";
            }else if(item == 'invalidLink'){
              mensaje =   mensaje + "The link provided is not valid" + "\n";
            }else if(item == 'nameOfParticipants'){
              mensaje =   mensaje + "The field Name of Participants is required" + "\n";
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
            title: 'Send New Record',
            message: `¿Are you sure you want to send this publication?.`,
            okButton: 'Send',
            cancelButton: 'Return'
          })
          if (ok) {
            let fundingsName1 = "";

            if (this.fundingSources.basal) {
              fundingsName1 += "Basal Financing Program Funding";
            }

            if (this.fundingSources.other) {
              if (fundingsName1) {
                fundingsName1 += ", "; // Agregar coma si hay más de un valor
              }
              fundingsName1 += "Other sources";
            }

            if (fundingsName1) {
              fundingsName1 += "."; // Agregar punto final al string
            }

            var indexedBy1 = "";
            if (this.nonIsiPublication.indexedBy !== null){
              if (this.nonIsiPublication.indexedBy.length !== 0) {
                this.nonIsiPublication.indexedBy.forEach((indexedBy, index) => {
                  indexedBy1 += indexedBy.name;
                  if (index === this.nonIsiPublication.indexedBy.length - 1) {
                    indexedBy1 += '.';
                  } else {
                    indexedBy1 += ', ';
                  }
                });
              }
            }

            var researcherInvolved1 = "";
            if (this.nonIsiPublication.researcherInvolved !== null){
              if (this.nonIsiPublication.researcherInvolved.length !== 0) {
                this.nonIsiPublication.researcherInvolved.forEach((researcherInvolved, index) => {
                  researcherInvolved1 += researcherInvolved.name;
                  if (index === this.nonIsiPublication.researcherInvolved.length - 1) {
                    researcherInvolved1 += '.';
                  } else {
                    researcherInvolved1 += ', ';
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

            let fileOrLink = this.isLink ? this.link : this.nonIsiPublication.file;
            let isLinkFlag = this.isLink ? 1 : 0;

            let publication = {
              status: 'Finished',
              idUsuario: idUser1,
              authors: this.nonIsiPublication.authors,
              indexedBy: indexedBy1,
              researcherInvolved: researcherInvolved1,
              articleTitle: this.nonIsiPublication.articleTitle,
              journalName: this.nonIsiPublication.journalName,
              volume: this.nonIsiPublication.volume,
              firstPage: this.nonIsiPublication.firstPage,
              lastPage: this.nonIsiPublication.lastPage,
              yearPublished: this.nonIsiPublication.yearPublished,
              month: this.nonIsiPublication.month,
              funding: fundingsName1,
              comments: this.nonIsiPublication.comments,
              file: fileOrLink,         // Enviar archivo o link
              isLink: isLinkFlag,   
              progressReport: this.nonIsiPublication.progressReport,
            };
            axios.post("api/nonIsiPublications", publication, {headers: { 'Content-Type' : 'multipart/form-data' }} ).then((result) => {
              this.buttonDisable = true;
              this.buttonText = 'Sending...';
              this.toast.success("Publication send successfully!", {
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
              if(this.participationScToggle){
                var typeEvent1 = "";
                if (this.participationSc.typeEvent !== null){
                  if (this.participationSc.typeEvent.length !== 0) {
                    this.participationSc.typeEvent.forEach((typeEvent, index) => {
                      typeEvent1 += typeEvent.name;
                      if (index === this.participationSc.typeEvent.length - 1) {
                        typeEvent1 += '.';
                      } else {
                        typeEvent1 += ', ';
                      }
                    });
                  }
                }

                var typeOfParticipation1 = "";
                if (this.participationSc.typeOfParticipation !== null){
                  if (this.participationSc.typeOfParticipation.length !== 0) {
                    this.participationSc.typeOfParticipation.forEach((typeOfParticipation, index) => {
                      typeOfParticipation1 += typeOfParticipation.name;
                      if (index === this.participationSc.typeOfParticipation.length - 1) {
                        typeOfParticipation1 += '.';
                      } else {
                        typeOfParticipation1 += ', ';
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

                var peopleInvolved1 = "";
                if (this.nonIsiPublication.researcherInvolved !== null){
                  if (this.nonIsiPublication.researcherInvolved.length !== 0) {
                    this.nonIsiPublication.researcherInvolved.forEach((researcherInvolved, index) => {
                      peopleInvolved1 += researcherInvolved.name;
                      if (index === this.nonIsiPublication.researcherInvolved.length - 1) {
                        peopleInvolved1 += '.';
                      } else {
                        peopleInvolved1 += ', ';
                      }
                    });
                  }
                }

                let participationSc = {
                  status: 'Finished',
                  researcherInvolved: peopleInvolved1,
                  idUsuario: idUser1,
                  typeEvent: typeEvent1,
                  presentationTitle: this.participationSc.presentationTitle,
                  typeOfParticipation: typeOfParticipation1,
                  eventName: this.participationSc.eventName,
                  country: this.participationSc.country,
                  city: this.participationSc.city,
                  startDate: this.participationSc.startDate,
                  endingDate: this.participationSc.endingDate,
                  progressReport: this.nonIsiPublication.progressReport,
                  nameOfParticipants: this.participationSc.nameOfParticipants,
                  file: fileOrLink,         // Enviar archivo o link
                  isLink: isLinkFlag,   
                  comments: this.nonIsiPublication.comments,
                };
                axios.post("api/participationScEvents", participationSc, {headers: { 'Content-Type' : 'multipart/form-data' }} ).then((result) => {
                  this.toast.success("Participation saved successfully!", {
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
                    }else if (error.response && error.response.status === 400) {
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
                }else if (error.response && error.response.status === 400) {
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
              this.buttonDisable = false;
              this.buttonText = 'Send New Record';
            });
          }
        }
      },
    },
}
</script>

<style src="@vueform/multiselect/themes/default.css"></style>