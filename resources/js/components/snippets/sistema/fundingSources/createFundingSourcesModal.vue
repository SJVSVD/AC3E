<template>
  <transition name="modalcrear">
    <div name="modal">
      <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container">
              <div class="modal-header pb-1 fw-bold" style="color: #444444;">
                <slot name="header">
                    New Funding source
                </slot>
                <label for="">Progress year: {{ fundingSource.progressReport }} &nbsp;&nbsp; <a class="btn" @click="showModalProgress = true"><i class="fa-solid fa-pen-to-square"></i></a></label>
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
                      <div class="col-md-3">
                        <label for="">Type of sources: </label>
                        <br>
                        <select class="form-select" v-model="fundingSource.typeSources">
                          <option disabled value="">Select a type</option>
                          <option value="Public funds">Public funds</option>
                          <option value="Productive/Private funds">Productive/Private funds</option>
                          <option value="International funds">International funds</option>
                          </select>
                      </div>
                      <div class="col-md-3">
                        <label for="">Name of institution:</label>
                        <br>
                        <input type="text" class= "form-control" v-model="fundingSource.nameOfInstitution">
                      </div>
                      <div class="col-md-3">
                        <label for="">Program/contest:</label>
                        <br>
                        <input type="text" class= "form-control" v-model="fundingSource.programContest">
                      </div>
                      <div class="col-md-3">
                        <label for="">Project title:</label>
                        <br>
                        <input type="text" class= "form-control" v-model="fundingSource.projectTitle">
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      
                      <div class="col-md-6">
                        <label for="">AC3E Researchers Involved:</label>
                        <Multiselect
                          placeholder="Select the participants"
                          v-model="fundingSource.researcherInvolved"
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
                      <div class="col-md-3">
                        <label for="">Start date:</label>
                        <br>
                        <input type="date" class= "form-control" v-model="fundingSource.startDate">
                      </div>
                      <div class="col-md-3">
                        <label for="">Finish date:</label>
                        <br>
                        <input type="date" class= "form-control" v-model="fundingSource.finishDate">
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-md-6">
                        <label for="">Principal researcher:</label>
                        <br>
                        <input type="text" class= "form-control" v-model="fundingSource.principalResearcher">
                      </div>
                      <div class="col-md-3">
                        <label for="">In cash:</label>
                        <br>
                        <input type="number" class= "form-control" v-model="fundingSource.inCash">
                      </div>
                      <div class="col-md-3">
                          <label for="">Type of collaboration: </label>
                          <br>
                          <select class="form-select" v-model="fundingSource.typeOfCollaboration">
                            <option disabled value="">Select a type</option>
                            <option value="Technical assitance">Technical assitance</option>
                            <option value="Patents">Patents</option>
                            <option value="License">License</option>
                            <option value="Grand/Competitive fund">Grand/Competitive fund</option>
                            <option value="Other (Specify in comments)">Other (Specify in comments)</option>
                            </select>
                      </div>

                    </div>
                    <br>
                    <div class="row">
                      <div class="col-md-6">
                        <label for="">Comments:</label>
                        <br>
                        <input type="text" class= "form-control" v-model="fundingSource.comments">
                      </div>
                    </div>
                  </slot>
                </div>
                <div class="modal-footer">
                  <slot name="footer">
                    <label class="form-check-label"><input type="checkbox" class="form-check-"
                    v-model="draft"> Save as a draft</label>
                    <a v-if="draft == false" class="btn btn-continue float-end" @click="createFunding()" :disabled="buttonDisable">
                      {{ buttonText }}
                    </a>
                    <a v-else class="btn btn-continue float-end" @click="guardarBorrador()" :disabled="buttonDisable">
                      Save draft
                    </a>
                  </slot>
                </div>
                <modalconfirmacion ref="confirmation"></modalconfirmacion>
                <modalalerta ref="alert"></modalalerta>
                <modalProgressYear v-bind:progressYear="fundingSource.progressReport" v-if="showModalProgress" @close="showModalProgress = false" @submit="handleFormSubmit1"></modalProgressYear>
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
      fundingSource:{
        typeSources: '',
        nameOfInstitution: '',
        programContest: '',
        projectTitle: '',
        researcherInvolved: null,
        startDate: '',
        finishDate: '',
        comments: '',
        inCash: '',
        typeOfCollaboration: '',
        progressReport: '',
      },
      draft: false,
      showModalProgress: false,
      researchers: '',
      buttonDisable: false,
      usuarios: [],
      idResearcher: '',
      errors:[],
      buttonText:'Save source',
    }),
    mounted(){
      this.getUsuarios();
      this.getUsuarios2();
      this.getProgressReport();
    },
    methods: {
      // Función para manejar el envío de un formulario con un año
      handleFormSubmit1(year) {
        this.fundingSource.progressReport = year;
      },
      // Función para obtener el año de progreso desde la API
      getProgressReport(){
        axios.get('api/showProgressReport').then( response =>{
            this.fundingSource.progressReport = response.data;
        }).catch(e=> console.log(e))
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
            this.researchers = response.data;
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


            var researcherInvolved1 = "";
            if (this.fundingSource.researcherInvolved !== null){
              if (this.fundingSource.researcherInvolved.length !== 0) {
                this.fundingSource.researcherInvolved.forEach((researcherInvolved, index) => {
                  researcherInvolved1 += researcherInvolved.name;
                  if (index === this.fundingSource.researcherInvolved.length - 1) {
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

            let fundingSources = {
              idUsuario: idUser1,
              status: 'Draft',
              typeSources: this.fundingSource.typeSources,
              nameOfInstitution: this.fundingSource.nameOfInstitution,
              programContest: this.fundingSource.programContest,
              projectTitle: this.fundingSource.projectTitle,
              researcherInvolved: researcherInvolved1,
              principalResearcher: this.fundingSource.principalResearcher,
              startDate: this.fundingSource.startDate,
              finishDate: this.fundingSource.finishDate,
              startYear: this.fundingSource.startYear,
              endingYear: this.fundingSource.endingYear,
              comments: this.fundingSource.comments,
              inCash: this.fundingSource.inCash,
              typeOfCollaboration: this.fundingSource.typeOfCollaboration,
              progressReport: this.fundingSource.progressReport,
            };
            axios.post("api/fundingSources", fundingSources).then((result) => {
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
      async createFunding() {
        this.errors = [];

        for (const item in this.fundingSource) {
          if (item !== "comments") { // Verificar si el item no es "comments"
            if (
              this.fundingSource[item] === "" ||
              this.fundingSource[item] === 0 ||
              this.fundingSource[item] == null ||
              this.fundingSource[item] == []
            ) {
              this.errors.push(item);
            }
          }
        }

        var contador = await axios.post('../api/verifyFunding', this.fundingSource).then(function(response) {
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
            if(item == 'typeSources'){
              mensaje =   mensaje + "The field Type of sources is required" + "\n";
            }else if(item == 'nameOfInstitution'){
              mensaje =   mensaje + "The field Name of institution is required" + "\n";
            }else if(item == 'programContest'){
              mensaje =   mensaje + "The field Program contest is required" + "\n";
            }else if(item == 'projectTitle'){
              mensaje =   mensaje + "The field Project title is required" + "\n";
            }else if(item == 'researcherInvolved'){
              mensaje =   mensaje + "The field Principal researcher is required" + "\n";
            }else if(item == 'startDate'){
              mensaje =   mensaje + "The field Start date is required" + "\n";
            }else if(item == 'finishDate'){
              mensaje =   mensaje + "The field Finish date is required" + "\n";
            }else if(item == 'inCash'){
              mensaje =   mensaje + "The field In cash is required" + "\n";
            }else if(item == 'typeOfCollaboration'){
              mensaje =   mensaje + "The field Type of collaboration is required" + "\n";
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
            title: 'Save Funding source',
            message: `¿Are you sure you want to save this Funding source?.`,
            okButton: 'Save',
            cancelButton: 'Return'
          })
          if (ok) {

            var researcherInvolved1 = "";
            if (this.fundingSource.researcherInvolved !== null){
              if (this.fundingSource.researcherInvolved.length !== 0) {
                this.fundingSource.researcherInvolved.forEach((researcherInvolved, index) => {
                  researcherInvolved1 += researcherInvolved.name;
                  if (index === this.fundingSource.researcherInvolved.length - 1) {
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

            let fundingSources = {
              status: 'Finished',
              idUsuario: idUser1,
              typeSources: this.fundingSource.typeSources,
              nameOfInstitution: this.fundingSource.nameOfInstitution,
              programContest: this.fundingSource.programContest,
              projectTitle: this.fundingSource.projectTitle,
              researcherInvolved: researcherInvolved1,
              principalResearcher: this.fundingSource.principalResearcher,
              startDate: this.fundingSource.startDate,
              finishDate: this.fundingSource.finishDate,
              startYear: this.fundingSource.startYear,
              endingYear: this.fundingSource.endingYear,
              comments: this.fundingSource.comments,
              inCash: this.fundingSource.inCash,
              typeOfCollaboration: this.fundingSource.typeOfCollaboration,
              progressReport: this.fundingSource.progressReport,
            };
            axios.post("api/fundingSources", fundingSources).then((result) => {
              this.toast.success("Funding source saved successfully!", {
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