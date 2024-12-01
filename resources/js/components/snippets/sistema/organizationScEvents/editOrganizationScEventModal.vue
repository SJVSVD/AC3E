<template>
  <transition name="modalcrear">
    <div name="modal">
      <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container">
              <div class="modal-header pb-1 fw-bold" style="color: #444444;">
                <slot name="header">
                    Edit Organization Sc Event
                </slot>
                <label for="">Progress year: {{ organizationSc.progressReport }} &nbsp;&nbsp; <a class="btn" v-if="is('Administrator')"@click="showModalProgress = true"><i class="fa-solid fa-pen-to-square"></i></a></label>
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
                          v-model="organizationSc.researcherInvolved"
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
                          <div class="col-md-6">
                            <label for="">Type of Event:</label>
                            <label for="" style="color: orange;">*</label>
                            <Multiselect
                                placeholder="Select the options"
                                v-model="organizationSc.typeEvent"
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
                          <div class="col-md-3">
                            <label for="">Event Name:</label>
                            <label for="" style="color: orange;">*</label>
                            <br>
                            <input type="text" class= "form-control" v-model="organizationSc.eventName">
                          </div>
                          <div class="col-md-3">
                            <label for="">Country:</label>
                            <label for="" style="color: orange;">*</label>
                            <br>
                            <input type="text" class= "form-control" v-model="organizationSc.country">
                          </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-md-3">
                            <label for="">City:</label>
                            <label for="" style="color: orange;">*</label>
                            <br>
                            <input type="text" class= "form-control" v-model="organizationSc.city">
                          </div>
                          <div class="col-md-3">
                          <label for="">Start Date:</label>
                          <label for="" style="color: orange;">*</label>
                          <br>
                          <input 
                            type="date" 
                            class="form-control" 
                            v-model="organizationSc.startDate" 
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
                              v-model="organizationSc.endingDate" 
                              :min="minEndDate"
                              @change="validateDates"
                            >
                          </div>
                          <div class="col-md-3">
                            <label for="">Number of participants:</label>
                            <label for="" style="color: orange;">*</label>
                            <label title="It must be a numeric." style="color: #0A95FF;"><i class="fa-solid fa-circle-info"></i></label>
                            <br>
                            <input type="number" class= "form-control" v-model="organizationSc.numberParticipants">
                          </div>
                    </div>
                    <br>
                    <div class="row">
                      <div :class="{'col-md-4': !isLink, 'col-md-6': isLink}">
                        <div class="form-group">
                          <label v-if="isLink" for="archivo">Link: </label>
                          <label v-else for="archivo">File: </label>
                          <label for="" style="color: orange;">*</label>
                          <label title="You must upload a PDF file or image." style="color: #0A95FF;"><i class="fa-solid fa-circle-info"></i></label>
                          <!-- Input para archivo (solo si isLink es false) -->
                          <input v-if="!isLink" type="file" ref="fileInput" accept=".pdf, .png, .jpg, .jpeg" class="form-control" @change="getFile">
    
                          <!-- Input para el link (solo si isLink es true) -->
                          <input v-if="isLink" type="text" v-model="link" placeholder="Enter the link" class="form-control">
                          <!-- Checkbox para alternar entre subir archivo o ingresar un link -->
                          <div class="form-check pt-2">
                            <input type="checkbox" id="isLink" v-model="isLink" class="form-check-input">
                            <label for="isLink" class="form-check-label">Upload Link instead of File</label>
                          </div>
                          <label v-if="organization1.file != null && organization1.is_link == 0" >Current file: {{ fileName }}</label>
                          <label v-if="organization1.file != null && organization1.is_link == 1" >Current link: {{ organization1.file }}</label>
                        </div>
                      </div>
                      <div v-if="organization1.file != null && !isLink" class="col-md-2 pt-2">
                        <br>
                        <a class="btn btn-closed " title="Clear Input" @click="clearFileInput"><i class="fa-solid fa-ban"></i></a>
                        &nbsp;
                        <a  class="btn btn-search-blue " title="Download" @click="descargarExtracto(id,user)"><i class="fa-solid fa-download"></i></a>
                      </div>
                      <div class="col-md-6">
                        <label for="">Comments:</label>
                        <input type="text" class= "form-control" v-model="organizationSc.comments">
                      </div>
                    </div>
                  </slot>
                </div>
                <div class="modal-footer">
                  <slot name="footer">
                    <label class="form-check-label"><input type="checkbox" class="form-check-"
                    v-model="draft"> Edit as a draft</label>
                    <a v-if="draft == false" class="btn btn-continue float-end" @click="editOrganization()" :disabled="buttonDisable">
                      {{ buttonText }}
                    </a>
                    <a v-else class="btn btn-continue float-end" @click="guardarBorrador()" :disabled="buttonDisable">
                      Edit draft
                    </a>
                  </slot>
                </div>
                <modalfotoperfil v-if="showFotoPerfil" @close="showFotoPerfil = false"></modalfotoperfil>
                <modalBookAutor v-if="showModalBookAutor" @close="showModalBookAutor = false" @submit="handleFormSubmit1" ></modalBookAutor>
                <modalChapterAutor v-if="showModalChapterAutor" @close="showModalChapterAutor = false" @submit="handleFormSubmit2"></modalChapterAutor>
                <modalconfirmacion ref="confirmation"></modalconfirmacion>
                <modalalerta ref="alert"></modalalerta>
                <modalProgressYear v-bind:progressYear="organizationSc.progressReport" v-if="showModalProgress" @close="showModalProgress = false" @submit="handleFormSubmit1"></modalProgressYear>
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
      organizationSc:{
        typeEvent: null,
        eventName: '',
        country: '',
        city: '',
        startDate: '',
        endingDate: '',
        researcherInvolved: null,
        numberParticipants: '',
        file: null,
        comments: '',
        progressReport: '',
      },
      isLink: false, // Controla si es un link o un archivo
      link: '',
      currentYear: new Date().getFullYear(),
      coauthor: false,
      draft: false,
      showModalProgress: false,
      id: '',
      user: '',
      researchers:[],
      usuarios: [],
      idResearcher: '',
      buttonDisable: false,
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

      errors:[],
      buttonText:'Save Organization',
    }),
    props:{
      organization1: Object,
    },
    created(){
      this.getUsuarios();
      this.getUsuarios2();
      this.idResearcher = this.organization1.idUsuario;
      this.id = this.organization1.id;
      this.user = this.organization1.usuario.name;
      this.organizationSc.eventName = this.organization1.eventName;
      this.organizationSc.country = this.organization1.country;
      this.organizationSc.city = this.organization1.city;
      this.organizationSc.startDate = this.organization1.startDate;
      this.organizationSc.endingDate = this.organization1.endingDate;
      this.organizationSc.numberParticipants = this.organization1.numberParticipants;
      this.organizationSc.endingDate = this.organization1.endingDate;
      this.organizationSc.file = this.organization1.file;
      this.organizationSc.comments = this.organization1.comments;
      this.organizationSc.progressReport = this.organization1.progressReport;
      this.link = this.organization1.is_link === 1 ? this.organization1.file : null; // Si es un link, se almacena el valor en `link`
      this.isLink = this.organization1.is_link === 1 ? true : false;

      if (this.organization1.researcherInvolved != null) {
          const valoresSeparados1 = this.organization1.researcherInvolved.split(",");
          this.organizationSc.researcherInvolved = valoresSeparados1.map((valor, index) => {
              valor = valor.trim();
              if (valor.endsWith('.')) {
                  valor = valor.slice(0, -1);
              }

              return { value: valor, name: valor };
          });
      }

      if (this.organization1.typeEvent != null) {
          const valoresSeparados1 = this.organization1.typeEvent.split(",");
          this.organizationSc.typeEvent = valoresSeparados1.map((valor, index) => {
              valor = valor.trim();
              if (valor.endsWith('.')) {
                  valor = valor.slice(0, -1);
              }

              return { value: valor, name: valor };
          });
      }
    },
    computed: {
      // La fecha máxima permitida para el campo de fecha de inicio será la fecha de finalización seleccionada
      maxStartDate() {
        return this.organizationSc.endingDate ? this.organizationSc.endingDate : null;
      },
      // La fecha mínima permitida para el campo de fecha de finalización será la fecha de inicio seleccionada
      minEndDate() {
        return this.organizationSc.startDate ? this.organizationSc.startDate : null;
      },
      fileName() {
        // Extraer solo el nombre del archivo
        return this.organization1.file.split('/').pop();
      }
    },
    methods: {
      validateDates() {
        // Validar si las fechas son correctas y emitir advertencias o mensajes de error
        const startDate = new Date(this.organizationSc.startDate);
        const endDate = new Date(this.organizationSc.endingDate);
        
        if (this.organizationSc.startDate && this.organizationSc.endingDate) {
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
      // Función para obtener usuarios desde otra ruta de la API
      getUsuarios2(){
        axios.get('api/usuarios').then( response =>{
            this.usuarios = response.data.sort((a, b) => a.name.localeCompare(b.name));;
        }).catch(e=> console.log(e))
      },
      // Función para obtener usuarios (investigadores) desde la API
      getUsuarios(){
        axios.get('api/researchers').then( response =>{
          this.researchers  = response.data.sort((a, b) => {
              if (a.toLowerCase() < b.toLowerCase()) return -1;
              if (a.toLowerCase() > b.toLowerCase()) return 1;
              return 0;
          });
        }).catch(e=> console.log(e))
      },
      // Función para manejar el envío de un formulario con un año
      handleFormSubmit1(year) {
        this.organizationSc.progressReport = year;
      },
      descargarExtracto(id,nombre){
          axios({
              url: `api/organizationDownload/${id}`,
              method: 'GET',
              responseType: 'arraybuffer',
          }).then((response) => {
            let blob = new Blob([response.data], {
                    type: response.headers['content-type']
                });
                let link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                if (blob.type.includes('pdf')) {
                    link.download = `Organization-${nombre}.pdf`;
                } else if (blob.type.includes('image')) {
                    link.download = `Organization-${nombre}.png`; // Cambia la extensión según el tipo de imagen
                } else {
                    // Si el tipo de archivo no es ni PDF ni imagen, puedes manejarlo de acuerdo a tus requerimientos
                    console.error('Tipo de archivo no compatible');
                    return;
                }
                link.click();
          });
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
          this.organizationSc.file = file;
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

            var peopleInvolved1 = "";
            if (this.organizationSc.researcherInvolved !== null){
              if (this.organizationSc.researcherInvolved.length !== 0) {
                this.organizationSc.researcherInvolved.forEach((researcherInvolved, index) => {
                  peopleInvolved1 += researcherInvolved.name;
                  if (index === this.organizationSc.researcherInvolved.length - 1) {
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

            var typeEvent1 = "";
            if (this.organizationSc.typeEvent !== null){
              if (this.organizationSc.typeEvent.length !== 0) {
                this.organizationSc.typeEvent.forEach((typeEvent, index) => {
                  typeEvent1 += typeEvent.name;
                  if (index === this.organizationSc.typeEvent.length - 1) {
                    typeEvent1 += '.';
                  } else {
                    typeEvent1 += ', ';
                  }
                });
              }
            }

            let organizationSc = {
              idUsuario: idUser1,
              status: 'Draft',
              researcherInvolved: peopleInvolved1,
              typeEvent: typeEvent1,
              eventName: this.organizationSc.eventName,
              country: this.organizationSc.country,
              city: this.organizationSc.city,
              startDate: this.organizationSc.startDate,
              endingDate: this.organizationSc.endingDate,
              numberParticipants: this.organizationSc.numberParticipants,
              comments: this.organizationSc.comments,
            };
            await axios.put(`api/organizationScEvents/${this.id}`, organizationSc ).then((result) => {
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
              if(this.organizationSc.file != null || this.link != '') {
                const formData = new FormData();
                formData.append('id', this.id);
                console.log(this.organizationSc.file);

                if(this.organizationSc.file != null && !this.isLink) {
                  // Si el archivo es proporcionado
                  formData.append('file', this.organizationSc.file);
                  let isLinkFlag = this.isLink ? 1 : 0;
                  formData.append('is_link', isLinkFlag);
                  console.log(this.organizationSc.file);
                  axios.post('api/organizationScEvents/addFile', formData, {
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
                  axios.post('api/organizationScEvents/addFile', formData, {
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
              } else if(this.organization1.file == null) {
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
        // Capitaliza la primera letra de una cadena.
        capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
      },
        // Edita el registro despues de validar
        async editOrganization() {
        this.errors = [];
        const itemsToSkip = [
          'comments',
          'file'
        ];

        for (const item in this.organizationSc) {
            const skipItem = itemsToSkip.includes(item);
            if (!skipItem && (this.organizationSc[item] === "" || this.organizationSc[item] === 0 || this.organizationSc[item] == null)) {
              this.errors.push(item);
            }
        }

        if((this.organizationSc.file == null && this.organization1.file == null) && this.isLink == false){
            this.errors.push('file');
        }

        if(this.link == '' && this.isLink == true){
            this.errors.push('link');
        }

        if(this.link && this.isLink && !this.isValidURL(this.link)){
            this.errors.push('invalidLink');
        }

        var idUser1 = ''
        if(this.idResearcher != ''){
          idUser1 = this.idResearcher;
        }else{
          idUser1 = this.userID;
        }

        let organizationSc1 = {
          idUsuario: idUser1,
          id: this.id,
          eventName: this.organizationSc.eventName,
          country: this.organizationSc.country,
          city: this.organizationSc.city,
          startDate: this.organizationSc.startDate,
          endingDate: this.organizationSc.endingDate,
          numberParticipants: this.organizationSc.numberParticipants,
          comments: this.organizationSc.comments,
          progressReport: this.organizationSc.progressReport,
        };

        var contador = await axios.post('../api/verifyOrganization', organizationSc1).then(function(response) {
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
            if(item == 'typeEvent'){
              mensaje =   mensaje + "The field Type Event is required" + "\n";
            }else if(item == 'researcherInvolved'){
              mensaje =   mensaje + "The field Researchers involved is required" + "\n";
            }else if(item == 'eventName'){
              mensaje =   mensaje + "The field Event Name is required" + "\n";
            }else if(item == 'startDate'){
              mensaje =   mensaje + "The field Start Date is required" + "\n";
            }else if(item == 'endingDate'){
              mensaje =   mensaje + "The field Ending Date is required" + "\n";
            }else if(item == 'invalidLink'){
              mensaje =   mensaje + "The link provided is not valid" + "\n";
            }else if(item == 'numberParticipants'){
              mensaje =   mensaje + "The field Number of Participants is required" + "\n";
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
            title: 'Edit Organization',
            message: `¿Are you sure you want to edit this organization of sc event?.`,
            okButton: 'Edit',
            cancelButton: 'Return'
          })
          if (ok) {


            var peopleInvolved1 = "";
            if (this.organizationSc.researcherInvolved !== null){
              if (this.organizationSc.researcherInvolved.length !== 0) {
                this.organizationSc.researcherInvolved.forEach((researcherInvolved, index) => {
                  peopleInvolved1 += researcherInvolved.name;
                  if (index === this.organizationSc.researcherInvolved.length - 1) {
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

            var typeEvent1 = "";
            if (this.organizationSc.typeEvent !== null){
              if (this.organizationSc.typeEvent.length !== 0) {
                this.organizationSc.typeEvent.forEach((typeEvent, index) => {
                  typeEvent1 += typeEvent.name;
                  if (index === this.organizationSc.typeEvent.length - 1) {
                    typeEvent1 += '.';
                  } else {
                    typeEvent1 += ', ';
                  }
                });
              }
            }

            let organizationSc = {
              idUsuario: idUser1,
              status: 'Finished',
              researcherInvolved: peopleInvolved1,
              typeEvent: typeEvent1,
              eventName: this.organizationSc.eventName,
              country: this.organizationSc.country,
              city: this.organizationSc.city,
              startDate: this.organizationSc.startDate,
              endingDate: this.organizationSc.endingDate,
              numberParticipants: this.organizationSc.numberParticipants,
              comments: this.organizationSc.comments,
            };

            await axios.put(`api/organizationScEvents/${this.id}`, organizationSc ).then((result) => {
              this.toast.success("Organization saved successfully!", {
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
              if(this.organizationSc.file != null || this.link != '') {
                const formData = new FormData();
                formData.append('id', this.id);
                console.log(this.organizationSc.file);

                if(this.organizationSc.file != null && !this.isLink) {
                  // Si el archivo es proporcionado
                  formData.append('file', this.organizationSc.file);
                  let isLinkFlag = this.isLink ? 1 : 0;
                  formData.append('is_link', isLinkFlag);
                  console.log(this.organizationSc.file);
                  axios.post('api/organizationScEvents/addFile', formData, {
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
                  axios.post('api/organizationScEvents/addFile', formData, {
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
              } else if(this.organization1.file == null) {
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