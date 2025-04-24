<template>
  <transition name="modalcrear">
    <div name="modal">
      <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container">
              <div class="modal-header pb-1 fw-bold" style="color: #444444;">
                <slot name="header">
                    Edit connection
                </slot>
                <label for="">Progress report year: {{ publicPrivate.progressReport }} &nbsp;&nbsp; <a class="btn" v-if="is('Administrator')"@click="showModalProgress = true"><i class="fa-solid fa-pen-to-square"></i></a></label>
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
                      <div class="col-md-4">
                        <label for="">Name of activity:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="publicPrivate.nameOfActivity">
                      </div>
                      <div class="col-md-4">
                        <label for="">Name of organization:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="publicPrivate.nameOfOrganization">
                      </div>
                      <div class="col-md-4">
                        <label for="">Country of origin:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="publicPrivate.countryOrigin">
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-md-6">
                        <label for="">AC3E Researchers Involved:</label>
                        <label for="" style="color: orange;">*</label>
                        <Multiselect
                          placeholder="Select the researchers"
                          v-model="publicPrivate.researcherInvolved"
                          :searchable="true"
                          :close-on-select="false"
                          :createTag="true"
                          :options="researchers2"
                          mode="tags"
                          label="name"
                          trackBy="id"
                          :object="true"
                        />
                      </div>
                      <div class="col-md-6">
                        <label for="">Type of connection:</label>
                        <label for="" style="color: orange;">*</label>
                        <Multiselect
                          placeholder="Select the types"
                          v-model="publicPrivate.typeOfConnection"
                          :searchable="true"
                          :close-on-select="false"
                          :createTag="true"
                          :options="options2"
                          mode="tags"
                          label="name"
                          trackBy="id"
                          :object="true"
                        />
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-md-5">
                        <label for="">Place where activity was executed:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="publicPrivate.placeWhereWasExecuted">
                      </div>
                      <div class="col-md-3">
                          <label for="">International/National:</label>
                          <label for="" style="color: orange;">*</label>
                          <select class="form-select" v-model="publicPrivate.internationalNational">
                            <option disabled :value="null">Select a type</option>
                            <option value="International congress">National</option>
                            <option value="National congress">International</option>
                            </select>
                        </div>
                        <div class="col-md-3 ">
                          <div class="form-check pt-2 ">
                            <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                  v-model="publicPrivate.participationPublicPolicies"> Participation in definition of public policies </label>
                          </div>
                          <div class="row mt-2" v-if="publicPrivate.participationPublicPolicies">
                              <div class="col-12">
                                <div class="form-group">
                                <label v-if="isLink" for="archivo">Link: </label>
                                  <label v-else for="archivo">File: </label>
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
                                  <label v-if="publicPrivate1.file != null && publicPrivate1.is_link == 0" >Current file: {{ fileName }}</label>
                                  <label v-if="publicPrivate1.file != null && publicPrivate1.is_link == 1" >Current link: {{ publicPrivate1.file }}</label>
                                </div>
                                
                              </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-md-3">
                        <label for="">Start Date:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input 
                          type="date" 
                          class="form-control" 
                          v-model="publicPrivate.startDate" 
                          :max="maxStartDate"
                          @change="validateDates"
                        >
                      </div>
                      <div class="col-md-3">
                        <label for="">Ending Date:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input 
                          type="date" 
                          class="form-control" 
                          v-model="publicPrivate.endingDate" 
                          :min="minEndDate"
                          @change="validateDates"
                        >
                      </div>
                      <div class="col-md-6">
                        <label for="">Agent type:</label>
                        <label for="" style="color: orange;">*</label>
                        <Multiselect
                          placeholder="Select the types"
                          v-model="publicPrivate.agentType"
                          :searchable="true"
                          :close-on-select="false"
                          :createTag="true"
                          :options="options1"
                          mode="tags"
                          label="name"
                          trackBy="id"
                          :object="true"
                        />
                      </div>

                    </div>
                    <br>
                    <div class="row">
                      <div class="col-md-6">
                          <label for="">Results/Goals:</label>
                          <label for="" style="color: orange;">*</label>
                          <br>
                          <textarea class= "form-control" v-model="publicPrivate.resultsGoals" style="resize: none;" cols="30" rows="5"></textarea>
                      </div>
                      <div class="col-md-6">
                        <label for="">Comments:</label>
                        <br>
                        <input type="text" class= "form-control" v-model="publicPrivate.comments">
                      </div>
                    </div>
                  </slot>
                </div>
                <div class="modal-footer">
                  <slot name="footer">
                    <label class="form-check-label"><input type="checkbox" class="form-check-"
                    v-model="draft"> Save as a draft</label>
                    <a v-if="draft == false" class="btn btn-continue float-end" @click="editPublicPrivate()" :disabled="buttonDisable">
                      {{ buttonText }}
                    </a>
                    <a v-else class="btn btn-continue float-end" @click="guardarBorrador()" :disabled="buttonDisable">
                      Save draft
                    </a>
                  </slot>
                </div>
                <modalconfirmacion ref="confirmation"></modalconfirmacion>
                <modalalerta ref="alert"></modalalerta>
                <modalProgressYear v-bind:progressYear="publicPrivate.progressReport" v-if="showModalProgress" @close="showModalProgress = false" @submit="handleFormSubmit1"></modalProgressYear>
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
    components: {modalProgressYear, Multiselect, modalconfirmacion, modalalerta },
    mixins: [mixin],
    data: () => ({
      publicPrivate:{
        nameOfActivity: '',
        agentType: null,
        typeOfConnection: null,
        researcherInvolved: null,
        placeWhereWasExecuted: '',
        internationalNational: '',
        participationPublicPolicies: '',
        startDate: '',
        endingDate: '',
        resultsGoals: '',
        nameOfOrganization: '',
        countryOrigin: '',
        comments: '',
        file: null,
        progressReport: '',
      },
      isLink: 0, // Controla si es un link o un archivo
      link: '',
      other: '',
      draft: false,
      showModalProgress: false,
      researchers2: '',
      buttonDisable: false,
      options1: [
        'Private Industry (product or service)',
        'Public Services',
        'Educational Sector',
        'Social - ONG',
        'Entrepreneurship',
        'Private Education',
        'Education (Schools, Education Ministry)',
      ],
      options2: [
        'Developmental Studies',
        'Project Implementation',
        'Training',
        'Prospective Activity',
        'Installation of Scientist',
        'Temporary Installation (Technician, Professional, Scientific)',
        'International agreements of exchange with foreign institutions',
        'Participations in instances of public policy definition (consulting councils, advisory committees)',
        'International exchange networks',
        'Other (Specify)'
      ],
      errors:[],
      usuarios: [],
      idResearcher: '',
      buttonText:'Edit Record',
      id: '',
    }),
    props:{
      publicPrivate1: Object,
    },

    created(){
      this.getUsuarios2();
      this.getUsuarios();
      this.idResearcher = this.publicPrivate1.idUsuario;
      this.id = this.publicPrivate1.id;
      this.publicPrivate.nameOfActivity = this.publicPrivate1.nameOfActivity;

      this.link = this.publicPrivate1.is_link === 1 ? this.publicPrivate1.file : ''; // Si es un link, se almacena el valor en `link`
      this.isLink = this.publicPrivate1.is_link === 1 ? true : false;


      if (this.publicPrivate1.agentType != null) {
          const valoresSeparados1 = this.publicPrivate1.agentType.split(",");
          this.publicPrivate.agentType = valoresSeparados1.map((valor, index) => {
              valor = valor.trim();
              if (valor.endsWith('.')) {
                  valor = valor.slice(0, -1);
              }

              return { value: valor, name: valor };
          });
      }

      if (this.publicPrivate1.typeOfConnection != null) {
          const valoresSeparados1 = this.publicPrivate1.typeOfConnection.split(",");
          this.publicPrivate.typeOfConnection = valoresSeparados1.map((valor, index) => {
              valor = valor.trim();
              if (valor.endsWith('.')) {
                  valor = valor.slice(0, -1);
              }

              return { value: valor, name: valor };
          });
      }

      if (this.publicPrivate1.researcherInvolved != null) {
          const valoresSeparados1 = this.publicPrivate1.researcherInvolved.split(",");
          this.publicPrivate.researcherInvolved = valoresSeparados1.map((valor, index) => {
              valor = valor.trim();
              if (valor.endsWith('.')) {
                  valor = valor.slice(0, -1);
              }

              return { value: valor, name: valor };
          });
      }

      this.publicPrivate.placeWhereWasExecuted = this.publicPrivate1.placeWhereWasExecuted;
      this.publicPrivate.internationalNational = this.publicPrivate1.internationalNational;
      this.publicPrivate.participationPublicPolicies = this.publicPrivate1.participationPublicPolicies;
      if (this.publicPrivate1.participationPublicPolicies == 1){
        this.publicPrivate.participationPublicPolicies = true;
      }else{
        this.publicPrivate.participationPublicPolicies = false;
      }
      this.publicPrivate.startDate = this.publicPrivate1.startDate;
      this.publicPrivate.endingDate = this.publicPrivate1.endingDate;
      this.publicPrivate.resultsGoals = this.publicPrivate1.resultsGoals;
      this.publicPrivate.nameOfOrganization = this.publicPrivate1.nameOfOrganization;
      this.publicPrivate.countryOrigin = this.publicPrivate1.countryOrigin;
      this.publicPrivate.comments = this.publicPrivate1.comments;
      this.publicPrivate.progressReport = this.publicPrivate1.progressReport;
          
    },
    computed: {
      fileName() {
        // Extraer solo el nombre del archivo
        return this.publicPrivate1.file.split('/').pop();
      },
      // La fecha máxima permitida para el campo de fecha de inicio será la fecha de finalización seleccionada
      maxStartDate() {
        return this.publicPrivate.endingDate ? this.publicPrivate.endingDate : null;
      },
      // La fecha mínima permitida para el campo de fecha de finalización será la fecha de inicio seleccionada
      minEndDate() {
        return this.publicPrivate.startDate ? this.publicPrivate.startDate : null;
      }
    },
    methods: {
      validateDates() {
        // Validar si las fechas son correctas y emitir advertencias o mensajes de error
        const startDate = new Date(this.publicPrivate.startDate);
        const endDate = new Date(this.publicPrivate.endingDate);
        
        if (this.publicPrivate.startDate && this.publicPrivate.endingDate) {
          if (startDate > endDate) {
            this.toast.error(`The start date cannot be later than the end date.`, {
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
        }
      },
      // Función para manejar el envío de un formulario con un año
      handleFormSubmit1(year) {
        this.publicPrivate.progressReport = year;
      },
      // Función para obtener usuarios (investigadores) desde la API
      getUsuarios(){
        axios.get('api/usuarios').then( response =>{
            this.usuarios = response.data.sort((a, b) => a.name.localeCompare(b.name));;
        }).catch(e=> console.log(e))
      },
      // Función para obtener usuarios desde otra ruta de la API
      getUsuarios2(){
        axios.get('api/researchers').then( response =>{
          this.researchers2  = response.data.sort((a, b) => {
                if (a.toLowerCase() < b.toLowerCase()) return -1;
                if (a.toLowerCase() > b.toLowerCase()) return 1;
                return 0;
            });
        }).catch(e=> console.log(e))
      },
      // Función para guardar un borrador 
      async guardarBorrador(){
        const ok = await this.$refs.confirmation.show({
            title: 'Edit draft',
            message: `Are you sure you want to edit the report as a draft?
 By saving as a draft, the information will not be reported. However, you can modify the report's status at any time and submit it, as long as all mandatory information is completed.
`,
            okButton: 'Edit',
            cancelButton: 'Return'
          })
          if (ok) {
            var agentType1 = "";
            if (this.publicPrivate.agentType !== null){
              if (this.publicPrivate.agentType.length !== 0) {
                this.publicPrivate.agentType.forEach((agentType, index) => {
                  agentType1 += agentType.name;
                  if (index === this.publicPrivate.agentType.length - 1) {
                    agentType1 += '.';
                  } else {
                    agentType1 += ', ';
                  }
                });
              }
            }

            var typeOfConnection1 = "";
            if (this.publicPrivate.typeOfConnection !== null){
              if (this.publicPrivate.typeOfConnection.length !== 0) {
                this.publicPrivate.typeOfConnection.forEach((typeOfConnection, index) => {
                  typeOfConnection1 += typeOfConnection.name;
                  if (index === this.publicPrivate.typeOfConnection.length - 1) {
                    typeOfConnection1 += '.';
                  } else {
                    typeOfConnection1 += ', ';
                  }
                });
              }
            }

            var researcherInvolved1 = "";
            if (this.publicPrivate.researcherInvolved !== null){
              if (this.publicPrivate.researcherInvolved.length !== 0) {
                this.publicPrivate.researcherInvolved.forEach((researcherInvolved, index) => {
                  researcherInvolved1 += researcherInvolved.name;
                  if (index === this.publicPrivate.researcherInvolved.length - 1) {
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

            let publicPrivate = {
              idUsuario: idUser1,
              status: 'Draft',
              agentType: agentType1,
              typeOfConnection: typeOfConnection1,
              researcherInvolved: researcherInvolved1,
              placeWhereWasExecuted: this.publicPrivate.placeWhereWasExecuted,
              internationalNational: this.publicPrivate.internationalNational,
              participationPublicPolicies: this.publicPrivate.participationPublicPolicies,
              nameOfActivity: this.publicPrivate.nameOfActivity,
              startDate: this.publicPrivate.startDate,
              endingDate: this.publicPrivate.endingDate,
              resultsGoals: this.publicPrivate.resultsGoals,
              nameOfOrganization: this.publicPrivate.nameOfOrganization,
              countryOrigin: this.publicPrivate.countryOrigin,
              comments: this.publicPrivate.comments,
              progressReport: this.publicPrivate.progressReport,
            };
            axios.put(`api/publicPrivate/${this.id}`, publicPrivate).then((result) => {
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
              if(this.publicPrivate.file != null || this.link != '') {
                const formData = new FormData();
                formData.append('id', this.id);
                console.log(this.publicPrivate.file);

                if(this.publicPrivate.file != null) {
                  // Si el archivo es proporcionado
                  formData.append('file', this.publicPrivate.file);
                  let isLinkFlag = this.isLink ? 1 : 0;
                  formData.append('is_link', isLinkFlag);
                  console.log("aaa");
                  axios.post('api/publicPrivate/addFile', formData, {
                    headers: { 'Content-Type' : 'multipart/form-data' }
                  }).then(response => {
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
                  });
                } else if(this.link != '') {
                  console.log("aaa");
                  // Si solo el link es proporcionado
                  formData.append('file', this.link);
                  let isLinkFlag = this.isLink ? 1 : 0;
                  formData.append('is_link', isLinkFlag);
                  formData.append('participationPublicPolicies', this.publicPrivate.participationPublicPolicies);
                  axios.post('api/publicPrivate/addFile', formData, {
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
                  });
                }
              } else if(this.publicPrivate1.file == null) {
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
        },
        // Cierra el modal y emite un evento de recarga.
        cerrarModal(){
          const elem = this.$refs.closeBtn;
          this.$emit('recarga');
          elem.click();
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
            this.publicPrivate.file = file;
        },
          // Capitaliza la primera letra de una cadena.
        capitalizeFirstLetter(string) {
          return string.charAt(0).toUpperCase() + string.slice(1);
        },
        // Edita el registro despues de validar
        async editPublicPrivate() {
        this.errors = [];

        const itemsToCheck = [
          'comments',
          'participationPublicPolicies',
          'file'
        ];


        for (const item in this.publicPrivate){
          if(this.publicPrivate[item] === "" || this.publicPrivate[item] === 0 || this.publicPrivate[item] == null || this.publicPrivate[item] == []){
            if (itemsToCheck.includes(item)) {
              } else {
                this.errors.push(item);
              }
            }
        }

        let publicPrivate1 = {
          id: this.id,
          agentType: agentType1,
          typeOfConnection: typeOfConnection1,
          researcherInvolved: researcherInvolved1,
          placeWhereWasExecuted: this.publicPrivate.placeWhereWasExecuted,
          internationalNational: this.publicPrivate.internationalNational,
          participationPublicPolicies: this.publicPrivate.participationPublicPolicies,
          nameOfActivity: this.publicPrivate.nameOfActivity,
          startDate: this.publicPrivate.startDate,
          endingDate: this.publicPrivate.endingDate,
          resultsGoals: this.publicPrivate.resultsGoals,
          nameOfOrganization: this.publicPrivate.nameOfOrganization,
          countryOrigin: this.publicPrivate.countryOrigin,
          comments: this.publicPrivate.comments,
          progressReport: this.publicPrivate.progressReport,
        };

        var contador = await axios.post('../api/verifyPublicPrivate', publicPrivate1).then(function(response) {
          return response.data;
        }.bind(this)).catch(function(e) {
          console.log(e);
        });
        if (contador > 0){
          this.errors.push('duplicated');
        }

        if((this.publicPrivate.file == null && this.publicPrivate1.file == null) && this.isLink === false){
            this.errors.push('file');
        }

        if(this.link == '' && this.isLink == true){
            this.errors.push('link');
        }

        var mensaje = ""
        if (this.errors.length != 0){
          this.errors.forEach(item => {
            if(item == 'nameOfActivity'){
              mensaje =   mensaje + "The field Name of activity is required" + "\n";
            }else if(item == 'startDate'){
              mensaje =   mensaje + "The field Start date is required" + "\n";
            }else if(item == 'endingDate'){
              mensaje =   mensaje + "The field Ending date is required" + "\n";
            }else if(item == 'resultsGoals'){
              mensaje =   mensaje + "The field Results/Goals is required" + "\n";
            }else if(item == 'nameOfOrganization'){
              mensaje =   mensaje + "The field Name of organization is required" + "\n";
            }else if(item == 'countryOrigin'){
              mensaje =   mensaje + "The field Country origin is required" + "\n";
            }else if(item == 'invalidLink'){
              mensaje =   mensaje + "The link provided is not valid" + "\n";
            }else if(item == 'typeOfConnection'){
              mensaje =   mensaje + "The field Type of connection is required" + "\n";
            }else if(item == 'researcherInvolved'){
              mensaje =   mensaje + "The field Researchers Involved is required" + "\n";
            }else if(item == 'placeWhereWasExecuted'){
              mensaje =   mensaje + "The field Place where was executed is required" + "\n";
            }else if(item == 'internationalNational'){
              mensaje =   mensaje + "The field International/national is required" + "\n";
            }else if(item == 'agentType'){
              mensaje =   mensaje + "The field Agent type is required" + "\n";
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
            title: 'Edit Public private connection',
            message: `¿Are you sure you want to edit this Public private connection?.`,
            okButton: 'Edit',
            cancelButton: 'Return'
          })
          if (ok) {

            var agentType1 = "";
            if (this.publicPrivate.agentType !== null){
              if (this.publicPrivate.agentType.length !== 0) {
                this.publicPrivate.agentType.forEach((agentType, index) => {
                  agentType1 += agentType.name;
                  if (index === this.publicPrivate.agentType.length - 1) {
                    agentType1 += '.';
                  } else {
                    agentType1 += ', ';
                  }
                });
              }
            }

            var typeOfConnection1 = "";
            if (this.publicPrivate.typeOfConnection !== null){
              if (this.publicPrivate.typeOfConnection.length !== 0) {
                this.publicPrivate.typeOfConnection.forEach((typeOfConnection, index) => {
                  typeOfConnection1 += typeOfConnection.name;
                  if (index === this.publicPrivate.typeOfConnection.length - 1) {
                    typeOfConnection1 += '.';
                  } else {
                    typeOfConnection1 += ', ';
                  }
                });
              }
            }

            var researcherInvolved1 = "";
            if (this.publicPrivate.researcherInvolved !== null){
              if (this.publicPrivate.researcherInvolved.length !== 0) {
                this.publicPrivate.researcherInvolved.forEach((researcherInvolved, index) => {
                  researcherInvolved1 += researcherInvolved.name;
                  if (index === this.publicPrivate.researcherInvolved.length - 1) {
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

            let publicPrivate = {
              idUsuario: idUser1,
              status: 'Finished',
              agentType: agentType1,
              typeOfConnection: typeOfConnection1,
              researcherInvolved: researcherInvolved1,
              placeWhereWasExecuted: this.publicPrivate.placeWhereWasExecuted,
              internationalNational: this.publicPrivate.internationalNational,
              participationPublicPolicies: this.publicPrivate.participationPublicPolicies,
              nameOfActivity: this.publicPrivate.nameOfActivity,
              startDate: this.publicPrivate.startDate,
              endingDate: this.publicPrivate.endingDate,
              resultsGoals: this.publicPrivate.resultsGoals,
              nameOfOrganization: this.publicPrivate.nameOfOrganization,
              countryOrigin: this.publicPrivate.countryOrigin,
              comments: this.publicPrivate.comments,
              progressReport: this.publicPrivate.progressReport,
            };
            axios.put(`api/publicPrivate/${this.id}`, publicPrivate).then((result) => {
              this.toast.success("Public private connection edited successfully!", {
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
              if(this.publicPrivate.file != null || this.link != '') {
                const formData = new FormData();
                formData.append('id', this.id);
                console.log(this.publicPrivate.file);

                if(this.publicPrivate.file != null) {
                  // Si el archivo es proporcionado
                  formData.append('file', this.publicPrivate.file);
                  let isLinkFlag = this.isLink ? 1 : 0;
                  formData.append('is_link', isLinkFlag);
                  console.log("aaa");
                  formData.append('participationPublicPolicies', this.publicPrivate.participationPublicPolicies);
                  axios.post('api/publicPrivate/addFile', formData, {
                    headers: { 'Content-Type' : 'multipart/form-data' }
                  }).then(response => {
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
                  });
                } else if(this.link != '') {
                  console.log("aaa");
                  // Si solo el link es proporcionado
                  formData.append('file', this.link);
                  let isLinkFlag = this.isLink ? 1 : 0;
                  formData.append('is_link', isLinkFlag);
                  axios.post('api/publicPrivate/addFile', formData, {
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
                  });
                }
              } else if(this.publicPrivate1.file == null) {
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