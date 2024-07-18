<template>
  <transition name="modalcrear">
    <div name="modal">
      <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container-xl">
              <div class="modal-header pb-1 fw-bold" style="color: #444444;">
                <slot name="header">
                    New Collaborative Project
                </slot>
                <label for="">Progress year: {{ conjointProject.progressReport }} &nbsp;&nbsp; <a class="btn" @click="showModalProgress = true"><i class="fa-solid fa-pen-to-square"></i></a></label>
                <label v-if="is('Administrator')" class="col-5 m-0"> Researcher: <label class="fw-normal" style="font-size: 14px;">
                  <select class="form-select" v-model="idResearcher">
                    <option disabled value="">Select a researcher</option>
                    <option v-for="researcher in researchers2" v-bind:key="researcher.id" v-bind:value="researcher.id">
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
                          v-model="conjointProject.researcherInvolved"
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
                            <label for="">Collaboration Stay:</label>
                            <label for="" style="color: orange;">*</label>
                            <select class="form-select" v-model="conjointProject.collaborationStay">
                              <option disabled value="">Select a type</option>
                              <option value="Short Visit (Up to two weeks)">Short Visit (Up to two weeks)</option>
                              <option value="Long Visit (More than two weeks)">Long Visit (More than two weeks)</option>
                              <option value="Other">Other</option>
                              </select>
                          </div>
                          <div v-if="conjointProject.collaborationStay == 'Other'" class="col-md-3">
                            <label for="">Other Stay:</label>
                            <label for="" style="color: orange;">*</label>
                            <br>
                            <input type="text" class= "form-control" v-model="other2">
                          </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-md-6">
                            <label for="">Activity Name:</label>
                            <label for="" style="color: orange;">*</label>
                            <Multiselect
                                placeholder="Select the options"
                                v-model="conjointProject.activityName"
                                limit=8
                                :searchable="true"
                                :close-on-select="false"
                                :createTag="true"
                                :options="activityNames"
                                mode="tags"
                                label="name"
                                trackBy="name"
                                :object="true"
                            />
                          </div>
                      <div class="col-md-6">
                        <label for="">Institution which the center collaborates:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="conjointProject.institutionCollaborates">
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-md-3">
                        <label v-if="conjointProject.studentOrResearcher == 'Researcher'" for="">Name of external researcher:</label>
                        <label v-else for="">Name of external person:</label>
                        <label for="" style="color: orange;">*</label>
                        <label title="The format for this field should be as follows: 'First Name,Last Name; First Name,Last Name; ...'" style="color: #0A95FF;"><i class="fa-solid fa-circle-info"></i></label>
                        <br>
                        <input type="text" class= "form-control" v-model="conjointProject.nameOfExternalResearcher">
                      </div>
                      <div class="col-md-3">
                            <label for="">Student or Researcher:</label>
                            <label for="" style="color: orange;">*</label>
                            <label title="External Researcher is:" style="color: #0A95FF;"><i class="fa-solid fa-circle-info"></i></label>
                            <br>
                            <select class="form-select" v-model="conjointProject.studentOrResearcher">
                              <option disabled value="">Select a type</option>
                              <option value="Student">Student</option>
                              <option value="Researcher">Researcher</option>
                              </select>
                          </div>
                          <div class="col-md-3">
                        <label for="">Beginning Date:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="date" class= "form-control" v-model="conjointProject.beginningDate">
                      </div>
                      <div class="col-md-3">
                        <label for="">Ending Date:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="date" class= "form-control" v-model="conjointProject.endingDate">
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-md-3">
                        <label for="">Country Origin:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="conjointProject.countryOrigin">
                      </div>
                      <div class="col-md-3">
                        <label for="">City Origin:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="conjointProject.cityOrigin">
                      </div>
                      <div class="col-md-3">
                        <label for="">Country Destination:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="conjointProject.countryDestination">
                      </div>
                      <div class="col-md-3">
                        <label for="">City Destination:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="conjointProject.cityDestination">
                      </div>
                    </div>
                    <br>
                    <div class="row">

                      <div class="col-md-6">
                        <label for="">Comments:</label>
                        <br>
                        <input type="text" class= "form-control" v-model="conjointProject.comments">
                      </div>
                    </div>
                  </slot>
                </div>
                <div class="modal-footer">
                  <slot name="footer">
                    <label class="form-check-label"><input type="checkbox" class="form-check-"
                    v-model="draft"> Save as a draft</label>
                    <a v-if="draft == false" class="btn btn-continue float-end" @click="createCollaboration()" :disabled="buttonDisable">
                      {{ buttonText }}
                    </a>
                    <a v-else class="btn btn-continue float-end" @click="guardarBorrador()" :disabled="buttonDisable">
                      Save draft
                    </a>
                  </slot>
                </div>
                <modalconfirmacion ref="confirmation"></modalconfirmacion>
                <modalalerta ref="alert"></modalalerta>
                <modalProgressYear v-bind:progressYear="conjointProject.progressReport" v-if="showModalProgress" @close="showModalProgress = false" @submit="handleFormSubmit1"></modalProgressYear>
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
      conjointProject:{
        activityName: null,
        researcherInvolved: null,
        institutionCollaborates: '',
        countryOrigin: '',
        cityOrigin: '',
        countryDestination: '',
        cityDestination: '',
        beginningDate: '',
        endingDate: '',
        nameOfExternalResearcher: '',
        studentOrResearcher: '',
        collaborationStay: '',
        comments: '',
        progressReport: '',
      },
      other: '',
      other2: '',
      draft: false,
      showModalProgress: false,
      researchers: '',
      researchers2: '',
      idResearcher: '',
      activityNames: [
        "Participation in R&D Projects directed by other Researcher (external)",
        "Participation in R&D Projects directed by an AC3E Researcher",
        "Other",
      ],
      buttonDisable: false,
      errors:[],
      buttonText:'Save Project',
    }),
    mounted(){
      this.getUsuarios();
      this.getUsuarios2();
      this.getProgressReport();
    },
    methods: {
      // Función para manejar el envío de un formulario con un año
      handleFormSubmit1(year) {
        this.conjointProject.progressReport = year;
      },
      // Función para obtener el año de progreso desde la API
      getProgressReport(){
        axios.get('api/showProgressReport').then( response =>{
            this.conjointProject.progressReport = response.data;
        }).catch(e=> console.log(e))
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
            this.researchers2 = response.data;
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
            title: 'Save draft',
            message: `Are you sure you want to save the report as a draft?
 By saving as a draft, the information will not be reported. However, you can modify the report's status at any time and submit it, as long as all mandatory information is completed.
`,
            okButton: 'Save',
            cancelButton: 'Return'
          })
          if (ok) {

            var peopleInvolved1 = "";
            if (this.conjointProject.researcherInvolved !== null){
              if (this.conjointProject.researcherInvolved.length !== 0) {
                this.conjointProject.researcherInvolved.forEach((researcherInvolved, index) => {
                  peopleInvolved1 += researcherInvolved.name;
                  if (index === this.conjointProject.researcherInvolved.length - 1) {
                    peopleInvolved1 += '.';
                  } else {
                    peopleInvolved1 += ', ';
                  }
                });
              }
            }

            var type2 = '';
            var other2 = 0;

            var activityName1 = "";
            if (this.conjointProject.activityName !== null){
              if (this.conjointProject.activityName.length !== 0) {
                this.conjointProject.activityName.forEach((activityName, index) => {
                  activityName1 += activityName.name;
                  if (index === this.conjointProject.activityName.length - 1) {
                    activityName1 += '.';
                  } else {
                    activityName1 += ', ';
                  }
                });
              }
            }

            if(this.conjointProject.collaborationStay == 'Other'){
              type2 = this.other2;
              other2 = 1;
            }else{
              type2 = this.conjointProject.collaborationStay;
            }

            var idUser1 = ''
            if(this.idResearcher != ''){
              idUser1 = this.idResearcher;
            }else{
              idUser1 = this.userID;
            }

            let conjointProject = {
              idUsuario: idUser1,
              status: 'Draft',
              moduleType: 1,
              institutionCollaborates: this.conjointProject.institutionCollaborates,
              researcherInvolved: peopleInvolved1,
              studentOrResearcher: this.conjointProject.studentOrResearcher,
              activityName: activityName1,
              countryOrigin: this.conjointProject.countryOrigin,
              cityOrigin: this.conjointProject.cityOrigin,
              countryDestination: this.conjointProject.countryDestination,
              cityDestination: this.conjointProject.cityDestination,
              beginningDate: this.conjointProject.beginningDate,
              endingDate: this.conjointProject.endingDate,
              nameOfExternalResearcher: this.conjointProject.nameOfExternalResearcher,
              collaborationStay: type2,
              otherStay: other2,
              comments: this.conjointProject.comments,
              progressReport: this.conjointProject.progressReport,
            };
            axios.post("api/conjointProjects", conjointProject, {headers: { 'Content-Type' : 'multipart/form-data' }} ).then((result) => {
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
      async createCollaboration() {
        this.errors = [];
        const itemsToSkip = [
          'comments'
        ];

        for (const item in this.conjointProject) {
            const skipItem = itemsToSkip.includes(item);
            if (!skipItem && (this.conjointProject[item] === "" || this.conjointProject[item] === 0 || this.conjointProject[item] == null)) {
                this.errors.push(item);
            }
        }


        if(this.conjointProject.collaborationStay == 'Other' && this.other2 == ''){
          this.errors.push('other2');
        }

        let conjointProject1 = {
          status: 'Finished',
          idUsuario: idUser1,
          moduleType: 1,
          institutionCollaborates: this.conjointProject.institutionCollaborates,
          researcherInvolved: peopleInvolved1,
          studentOrResearcher: this.conjointProject.studentOrResearcher,
          countryOrigin: this.conjointProject.countryOrigin,
          cityOrigin: this.conjointProject.cityOrigin,
          countryDestination: this.conjointProject.countryDestination,
          cityDestination: this.conjointProject.cityDestination,
          beginningDate: this.conjointProject.beginningDate,
          endingDate: this.conjointProject.endingDate,
          nameOfExternalResearcher: this.conjointProject.nameOfExternalResearcher,
          collaborationStay: type2,
          otherStay: other2,
          comments: this.conjointProject.comments,
          progressReport: this.conjointProject.progressReport,
        };

        var contador = await axios.post('../api/verifyConjoint', conjointProject1).then(function(response) {
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
            if(item == 'activityName'){
              mensaje =   mensaje + "The field Activity Name is required" + "\n";
            }else if(item == 'researcherInvolved'){
              mensaje =   mensaje + "The field Researcher Involved is required" + "\n";
            }else if(item == 'institutionCollaborates'){
              mensaje =   mensaje + "The field Institution which collaborates is required" + "\n";
            }else if(item == 'countryOrigin'){
              mensaje =   mensaje + "The field Country Origin is required" + "\n";
            }else if(item == 'cityOrigin'){
              mensaje =   mensaje + "The field City Origin is required" + "\n";
            }else if(item == 'countryDestination'){
              mensaje =   mensaje + "The field Country Destination is required" + "\n";
            }else if(item == 'cityDestination'){
              mensaje =   mensaje + "The field City Destination is required" + "\n";
            }else if(item == 'beginningDate'){
              mensaje =   mensaje + "The field Beginning Date is required" + "\n";
            }else if(item == 'endingDate'){
              mensaje =   mensaje + "The field Ending Date is required" + "\n";
            }else if(item == 'progressReport'){
              mensaje =   mensaje + "The field Progress Report line is required" + "\n";
            }else if(item == 'other'){
              mensaje =   mensaje + "The field Other Activity is required" + "\n";
            }else if(item == 'other2'){
              mensaje =   mensaje + "The field Other Stay is required" + "\n";
            }else if(item == 'nameOfExternalResearcher'){
              mensaje =   mensaje + "The field Name of external researcher is required" + "\n";
            }else if(item == 'studentOrResearcher'){
              mensaje =   mensaje + "The field Student or Researcher is required" + "\n";
            }else if(item == 'collaborationStay'){
              mensaje =   mensaje + "The field Collaboration Stay is required" + "\n";
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
            title: 'Save Project',
            message: `¿Are you sure you want to save this Collaborative Project?.`,
            okButton: 'Save',
            cancelButton: 'Return'
          })
          if (ok) {

            var peopleInvolved1 = "";
            if (this.conjointProject.researcherInvolved !== null){
              if (this.conjointProject.researcherInvolved.length !== 0) {
                this.conjointProject.researcherInvolved.forEach((researcherInvolved, index) => {
                  peopleInvolved1 += researcherInvolved.name;
                  if (index === this.conjointProject.researcherInvolved.length - 1) {
                    peopleInvolved1 += '.';
                  } else {
                    peopleInvolved1 += ', ';
                  }
                });
              }
            }


            var type2 = '';
            var other2 = 0;

            var activityName1 = "";
            if (this.conjointProject.activityName !== null){
              if (this.conjointProject.activityName.length !== 0) {
                this.conjointProject.activityName.forEach((activityName, index) => {
                  activityName1 += activityName.name;
                  if (index === this.conjointProject.activityName.length - 1) {
                    activityName1 += '.';
                  } else {
                    activityName1 += ', ';
                  }
                });
              }
            }

            if(this.conjointProject.collaborationStay == 'Other'){
              type2 = this.other2;
              other2 = 1;
            }else{
              type2 = this.conjointProject.collaborationStay;
            }


            var idUser1 = ''
            if(this.idResearcher != ''){
              idUser1 = this.idResearcher;
            }else{
              idUser1 = this.userID;
            }

            let conjointProject = {
              status: 'Finished',
              idUsuario: idUser1,
              moduleType: 1,
              institutionCollaborates: this.conjointProject.institutionCollaborates,
              researcherInvolved: peopleInvolved1,
              studentOrResearcher: this.conjointProject.studentOrResearcher,
              activityName: activityName1,
              countryOrigin: this.conjointProject.countryOrigin,
              cityOrigin: this.conjointProject.cityOrigin,
              countryDestination: this.conjointProject.countryDestination,
              cityDestination: this.conjointProject.cityDestination,
              beginningDate: this.conjointProject.beginningDate,
              endingDate: this.conjointProject.endingDate,
              nameOfExternalResearcher: this.conjointProject.nameOfExternalResearcher,
              collaborationStay: type2,
              otherStay: other2,
              comments: this.conjointProject.comments,
              progressReport: this.conjointProject.progressReport,
            };
            console.log(conjointProject);
            axios.post("api/conjointProjects", conjointProject, {headers: { 'Content-Type' : 'multipart/form-data' }} ).then((result) => {
              this.toast.success("Project saved successfully!", {
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