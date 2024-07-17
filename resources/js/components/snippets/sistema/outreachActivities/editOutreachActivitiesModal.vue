<template>
  <transition name="modalcrear">
    <div name="modal">
      <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container">
              <div class="modal-header pb-1 fw-bold" style="color: #444444;">
                <slot name="header">
                    Edit Outreach Activity 
                </slot>
                <label for="">Progress year: {{ outreachActivity.progressReport }} &nbsp;&nbsp; <a class="btn" @click="showModalProgress = true"><i class="fa-solid fa-pen-to-square"></i></a></label>
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
                        <label for="">Researcher Involved:</label>
                        <label for="" style="color: orange;">*</label>
                        <Multiselect
                          placeholder="Select the researchers"
                          v-model="outreachActivity.researcherInvolved"
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

                          <div class="col-md-3">
                            <label for="">Type of Activity:</label>
                            <label for="" style="color: orange;">*</label>
                            <br>
                            <input type="text" class= "form-control" v-model="outreachActivity.activityName">
                          </div>
                          <div class="col-md-3">
                            <label for="">Activity Description:</label>
                            <label for="" style="color: orange;">*</label>
                            <br>
                            <input type="text" class= "form-control" v-model="outreachActivity.activityDescription">
                          </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-md-3">
                            <label for="">Activity Type:</label>
                            <label for="" style="color: orange;">*</label>
                            <select class="form-select" v-model="outreachActivity.activityType">
                              <option disabled value="">Select a type</option>
                              <option value="Conference">Conference</option>
                              <option value="Seminar">Seminar</option>
                              <option value="Forum">Forum</option>
                              <option value="Exhibition">Exhibition</option>
                              <option value="Workshop">Workshop</option>
                              <option value="Competition">Competition</option>
                              <option value="Course">Course</option>
                              <option value="Outreach Material">Outreach Material</option>
                              <option value="Other">Other</option>
                              </select>
                          </div>
                          <div v-if="outreachActivity.activityType == 'Other'" class="col-3">
                            <label for="">Other:</label>
                            <label for="" style="color: orange;">*</label>
                            <br>
                            <input type="text" class= "form-control" v-model="other">
                          </div>
                      <div class="col-md-3">
                        <label for="">Duration (Days):</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="number" class= "form-control" v-model="outreachActivity.duration">
                      </div>
                      <div class="col-md-3">
                        <label for="">Country:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="outreachActivity.country">
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-md-3">
                        <label for="">Date:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="date" class= "form-control" v-model="outreachActivity.date">
                      </div>
                      <div class="col-md-3">
                        <label for="">Attendants Amount:</label>
                        <label for="" style="color: orange;">*</label>
                        <label title="It must be a numeric." style="color: #0A95FF;"><i class="fa-solid fa-circle-info"></i></label>
                        <br>
                        <input type="number" class= "form-control" v-model="outreachActivity.attendantsAmount">
                      </div>
                      <div class="col-md-3">
                        <label for="">Place/Region:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="outreachActivity.placeRegion">
                      </div>
                      <div class="col-md-3">
                        <label for="">City:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="outreachActivity.city">
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-md-6">
                        <label for="">Responsability:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="outreachActivity.responsability">
                      </div>
                      <div class="col-md-6">
                        <label for="">Comments:</label>
                        <br>
                        <input type="text" class= "form-control" v-model="outreachActivity.comments">
                      </div>
                    </div>
                    <br>
                    <hr size="3" class="separador">
                    <div class="row">
                      <div class="text-uppercase pb-2">Target Audiences:<label for="" style="color: orange;">*</label></div>
                      <div class="col-md-4">
                          <div class="form-check pt-2 ">
                            <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                  v-model="outreachActivity.undergraduateStudents"> Undergraduate students</label>
                          </div>
                      </div>
                      <div class="col-md-4">
                          <div class="form-check pt-2 ">
                            <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                  v-model="outreachActivity.primaryEducationStudents"> Primary education students</label>
                          </div>
                      </div>
                      <div class="col-md-4">
                          <div class="form-check pt-2 ">
                            <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                  v-model="outreachActivity.secondaryEducationStudents"> Secondary education students</label>
                          </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-4">
                          <div class="form-check pt-2 ">
                            <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                  v-model="outreachActivity.generalCommunity"> General community</label>
                          </div>
                      </div>
                      <div class="col-md-4">
                          <div class="form-check pt-2 ">
                            <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                  v-model="outreachActivity.companiesIndustriesServices"> Companies,industries,services </label>
                          </div>
                      </div>
                      <div class="col-md-4">
                          <div class="form-check pt-2 ">
                            <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                  v-model="outreachActivity.schoolTeachers"> School teachers </label>
                          </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-4">
                          <div class="form-check pt-2 ">
                            <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                  v-model="outreachActivity.governmentOfficial"> Government official </label>
                          </div>
                      </div>
                      <div class="col-md-4">
                          <div class="form-check pt-2 ">
                            <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                  v-model="outreachActivity.other"> Other </label>
                          </div>
                      </div>
                    </div>
                  </slot>
                </div>
                <div class="modal-footer">
                  <slot name="footer">
                    <label class="form-check-label"><input type="checkbox" class="form-check-"
                    v-model="draft"> Edit as a draft</label>
                    <a v-if="draft == false" class="btn btn-continue float-end" @click="createActivity()" :disabled="buttonDisable">
                      {{ buttonText }}
                    </a>
                    <a v-else class="btn btn-continue float-end" @click="guardarBorrador()" :disabled="buttonDisable">
                      Edit draft
                    </a>
                  </slot>
                </div>
                <modalconfirmacion ref="confirmation"></modalconfirmacion>
                <modalalerta ref="alert"></modalalerta>
                <modalProgressYear v-bind:progressYear="outreachActivity.progressReport" v-if="showModalProgress" @close="showModalProgress = false" @submit="handleFormSubmit1"></modalProgressYear>
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
      outreachActivity:{
        activityType: '',
        activityName: '',
        activityDescription: '',
        date: '',
        attendantsAmount: '',
        duration: '',
        country: '',
        placeRegion: '',
        city: '',
        undergraduateStudents: false,
        primaryEducationStudents: false,
        secondaryEducationStudents: false,
        generalCommunity: false,
        companiesIndustriesServices: false,
        schoolTeachers: false,
        governmentOfficial: false,
        other: false,
        progressReport: '',
      },
      id: '',
      other: '',
      draft: false,
      showModalProgress: false,
      researchers: '',
      usuarios: [],
      idResearcher: '',
      buttonDisable: false,
      errors:[],
      buttonText:'Edit Activity',
    }),
    mounted(){
      this.getUsuarios();
      this.getUsuarios2();
    },
    props:{
      activity1: Object,
    },
    created(){
      this.id = this.activity1.id;
      this.idResearcher = this.activity1.idUsuario;
      this.outreachActivity.activityType = this.activity1.activityType;
      this.outreachActivity.activityName = this.activity1.activityName;
      this.outreachActivity.activityDescription = this.activity1.activityDescription;
      this.outreachActivity.date = this.activity1.date;
      this.outreachActivity.attendantsAmount = this.activity1.attendantsAmount;
      this.outreachActivity.duration = this.activity1.duration;
      this.outreachActivity.country = this.activity1.country;
      this.outreachActivity.placeRegion = this.activity1.placeRegion;
      this.outreachActivity.city = this.activity1.city;
      this.outreachActivity.undergraduateStudents = this.activity1.undergraduateStudents === 1 ? true : false;
      this.outreachActivity.primaryEducationStudents = this.activity1.primaryEducationStudents === 1 ? true : false;
      this.outreachActivity.secondaryEducationStudents = this.activity1.secondaryEducationStudents === 1 ? true : false;
      this.outreachActivity.generalCommunity = this.activity1.generalCommunity === 1 ? true : false;
      this.outreachActivity.companiesIndustriesServices = this.activity1.companiesIndustriesServices === 1 ? true : false;
      this.outreachActivity.schoolTeachers = this.activity1.schoolTeachers === 1 ? true : false;
      this.outreachActivity.governmentOfficial = this.activity1.governmentOfficial === 1 ? true : false;
      this.outreachActivity.other = this.activity1.other === 1 ? true : false;
      this.outreachActivity.progressReport = this.activity1.progressReport;
      this.outreachActivity.responsability = this.activity1.responsability;
      this.outreachActivity.comments = this.activity1.comments;


      if (this.activity1.researcherInvolved != null) {
          const valoresSeparados1 = this.activity1.researcherInvolved.split(",");
          this.outreachActivity.researcherInvolved = valoresSeparados1.map((valor, index) => {
              valor = valor.trim();
              if (valor.endsWith('.')) {
                  valor = valor.slice(0, -1);
              }

              return { value: valor, name: valor };
          });
      }

      if(this.activity1.otherType == true){
        this.outreachActivity.activityType = 'Other';
        this.other = this.activity1.activityType;
      }else{
        this.outreachActivity.activityType = this.activity1.activityType;
      }
    },
    methods: {
      // Función para manejar el envío de un formulario con un año
      handleFormSubmit1(year) {
        this.outreachActivity.progressReport = year;
      },
      // Función para obtener usuarios (investigadores) desde la API
      getUsuarios(){
        axios.get('api/researchers').then( response =>{
            this.researchers = response.data;
        }).catch(e=> console.log(e))
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
      async getFile(e){
        this.participationSc.file = e.target.files[0];
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


            var researcherInvolved1 = "";
            if (this.outreachActivity.researcherInvolved !== null){
              if (this.outreachActivity.researcherInvolved.length !== 0) {
                this.outreachActivity.researcherInvolved.forEach((researcherInvolved, index) => {
                  researcherInvolved1 += researcherInvolved.name;
                  if (index === this.outreachActivity.researcherInvolved.length - 1) {
                    researcherInvolved1 += '.';
                  } else {
                    researcherInvolved1 += ', ';
                  }
                });
              }
            }

            var type = '';
            var other1 = 0;

            if(this.outreachActivity.activityType == 'Other'){
              type = this.other;
              other1 = 1;
            }else{
              type = this.outreachActivity.activityType;
            }

            var idUser1 = ''
            if(this.idResearcher != ''){
              idUser1 = this.idResearcher;
            }else{
              idUser1 = this.userID;
            }

            let outreachActivity = {
              idUsuario: idUser1,
              status: 'Draft',
              activityType: type,
              otherType: other1,
              activityName: this.outreachActivity.activityName,
              activityDescription: this.outreachActivity.activityDescription,
              date: this.outreachActivity.date,
              attendantsAmount: this.outreachActivity.attendantsAmount,
              duration: this.outreachActivity.duration,
              country: this.outreachActivity.country,
              placeRegion: this.outreachActivity.placeRegion,
              city: this.outreachActivity.city,
              undergraduateStudents: this.outreachActivity.undergraduateStudents ? 1 : 0,
              primaryEducationStudents: this.outreachActivity.primaryEducationStudents ? 1 : 0,
              secondaryEducationStudents: this.outreachActivity.secondaryEducationStudents ? 1 : 0,
              generalCommunity: this.outreachActivity.generalCommunity ? 1 : 0,
              companiesIndustriesServices: this.outreachActivity.companiesIndustriesServices ? 1 : 0,
              schoolTeachers: this.outreachActivity.schoolTeachers ? 1 : 0,
              governmentOfficial: this.outreachActivity.governmentOfficial ? 1 : 0,
              other: this.outreachActivity.other ? 1 : 0,
              researcherInvolved: researcherInvolved1,
              responsability: this.outreachActivity.responsability,
              comments: this.outreachActivity.comments,
              progressReport: this.outreachActivity.progressReport,
            };
            axios.put(`api/outreachActivities/${this.id}`, outreachActivity ).then((result) => {
              this.toast.success("Outreach activity edited successfully!", {
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
      // Método para crear un registro
      async createActivity() {
        this.errors = [];

        const itemsToCheck = [
          'undergraduateStudents',
          'primaryEducationStudents',
          'secondaryEducationStudents',
          'generalCommunity',
          'companiesIndustriesServices',
          'schoolTeachers',
          'governmentOfficial',
          'other',
          'comments'
        ];


        for (const item in this.outreachActivity){
          if(this.outreachActivity[item] === "" || this.outreachActivity[item] === 0 || this.outreachActivity[item] == null || this.outreachActivity[item] == []){
            if (itemsToCheck.includes(item)) {
              } else {
                this.errors.push(item);
              }
            }
        }

        const checkboxFields = [
          'undergraduateStudents',
          'primaryEducationStudents',
          'secondaryEducationStudents',
          'generalCommunity',
          'companiesIndustriesServices',
          'schoolTeachers',
          'governmentOfficial',
          'other',
        ];

        const isChecked = checkboxFields.some(field => this.outreachActivity[field]);

        // Si ninguno está marcado, agrega el mensaje al arreglo de errores
        if (!isChecked) {
          this.errors.push('target audiencies');
        }

        if(this.outreachActivity.activityType == 'Other' && this.other == ''){
          this.errors.push('other');
        }

        let outreachActivity1 = {
          id: this.id,
          activityType: type,
          otherType: other1,
          activityName: this.outreachActivity.activityName,
          activityDescription: this.outreachActivity.activityDescription,
          date: this.outreachActivity.date,
          attendantsAmount: this.outreachActivity.attendantsAmount,
          duration: this.outreachActivity.duration,
          country: this.outreachActivity.country,
          placeRegion: this.outreachActivity.placeRegion,
          city: this.outreachActivity.city,
          undergraduateStudents: this.outreachActivity.undergraduateStudents ? 1 : 0,
          primaryEducationStudents: this.outreachActivity.primaryEducationStudents ? 1 : 0,
          secondaryEducationStudents: this.outreachActivity.secondaryEducationStudents ? 1 : 0,
          generalCommunity: this.outreachActivity.generalCommunity ? 1 : 0,
          companiesIndustriesServices: this.outreachActivity.companiesIndustriesServices ? 1 : 0,
          schoolTeachers: this.outreachActivity.schoolTeachers ? 1 : 0,
          governmentOfficial: this.outreachActivity.governmentOfficial ? 1 : 0,
          other: this.outreachActivity.other ? 1 : 0,
          researcherInvolved: researcherInvolved1,
          responsability: this.outreachActivity.responsability,
          comments: this.outreachActivity.comments,
          progressReport: this.outreachActivity.progressReport,
        };

        var contador = await axios.post('../api/verifyOutreach', outreachActivity1).then(function(response) {
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
            if(item == 'activityType'){
              mensaje =   mensaje + "The field Activity Type is required" + "\n";
            }else if(item == 'activityName'){
              mensaje =   mensaje + "The field Activity Name is required" + "\n";
            }else if(item == 'activityDescription'){
              mensaje =   mensaje + "The field Activity Description is required" + "\n";
            }else if(item == 'attendantsAmount'){
              mensaje =   mensaje + "The field Attendants Amount is required" + "\n";
            }else if(item == 'placeRegion'){
              mensaje =   mensaje + "The field Place/Region is required" + "\n";
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
            title: 'Edit Outreach Activity',
            message: `¿Are you sure you want to edit this Outreach Activity?.`,
            okButton: 'Edit',
            cancelButton: 'Return'
          })
          if (ok) {


            var researcherInvolved1 = "";
            if (this.outreachActivity.researcherInvolved !== null){
              if (this.outreachActivity.researcherInvolved.length !== 0) {
                this.outreachActivity.researcherInvolved.forEach((researcherInvolved, index) => {
                  researcherInvolved1 += researcherInvolved.name;
                  if (index === this.outreachActivity.researcherInvolved.length - 1) {
                    researcherInvolved1 += '.';
                  } else {
                    researcherInvolved1 += ', ';
                  }
                });
              }
            }

            var type = '';
            var other1 = 0;

            if(this.outreachActivity.activityType == 'Other'){
              type = this.other;
              other1 = 1;
            }else{
              type = this.outreachActivity.activityType;
            }

            var idUser1 = ''
            if(this.idResearcher != ''){
              idUser1 = this.idResearcher;
            }else{
              idUser1 = this.userID;
            }

            let outreachActivity = {
              idUsuario: idUser1,
              status: 'Finished',
              activityType: type,
              otherType: other1,
              activityName: this.outreachActivity.activityName,
              activityDescription: this.outreachActivity.activityDescription,
              date: this.outreachActivity.date,
              attendantsAmount: this.outreachActivity.attendantsAmount,
              duration: this.outreachActivity.duration,
              country: this.outreachActivity.country,
              placeRegion: this.outreachActivity.placeRegion,
              city: this.outreachActivity.city,
              undergraduateStudents: this.outreachActivity.undergraduateStudents,
              primaryEducationStudents: this.outreachActivity.primaryEducationStudents,
              secondaryEducationStudents: this.outreachActivity.secondaryEducationStudents,
              generalCommunity: this.outreachActivity.generalCommunity,
              companiesIndustriesServices: this.outreachActivity.companiesIndustriesServices,
              schoolTeachers: this.outreachActivity.schoolTeachers,
              governmentOfficial: this.outreachActivity.governmentOfficial,
              other: this.outreachActivity.other,
              researcherInvolved: researcherInvolved1,
              responsability: this.outreachActivity.responsability,
              comments: this.outreachActivity.comments,
              progressReport: this.outreachActivity.progressReport,
            };
            axios.put(`api/outreachActivities/${this.id}`, outreachActivity ).then((result) => {
              this.toast.success("Outreach activity edited successfully!", {
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