<template>
  <transition name="modalcrear">
    <div name="modal">
      <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container">
              <div class="modal-header pb-1 fw-bold" style="color: #444444;">
                <slot name="header">
                    New connection 
                </slot>
                <label for="">Progress year: {{ publicPrivate.progressReport }} &nbsp;&nbsp; <a class="btn" @click="showModalProgress = true"><i class="fa-solid fa-pen-to-square"></i></a></label>
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
                        <label for="">AC3E researcher involved:</label>
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
                            <option disabled value="">Select a type</option>
                            <option value="International congress">National</option>
                            <option value="National congress">International</option>
                            </select>
                        </div>
                        <div class="col-md-3 ">
                          <div class="form-check pt-2 ">
                            <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                  v-model="publicPrivate.participationPublicPolicies"> Participation in definition of public policies </label>
                          </div>
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-md-3">
                        <label for="">Start date:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="date" class= "form-control" v-model="publicPrivate.startDate">
                      </div>
                      <div class="col-md-3">
                        <label for="">Ending date:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="date" class= "form-control" v-model="publicPrivate.endingDate">
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
                    <a v-if="draft == false" class="btn btn-continue float-end" @click="createPublicPrivate()" :disabled="buttonDisable">
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
        progressReport: '',
      },
      other: '',
      draft: false,
      showModalProgress: false,
      researchers2: '',
      externalResearcher: '',
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
        'Scientific Training',
        'Installation of Scientist',
        'Participation in Consulting Commitees',
      ],
      errors:[],
      idResearcher: '',
      usuarios:[],
      buttonText:'Save connection',
    }),
    created(){
      this.getProgressReport();
      this.getUsuarios2();
      this.getUsuarios();
    },
    methods: {
      handleFormSubmit1(year) {
        this.publicPrivate.progressReport = year;
      },
      getUsuarios(){
        axios.get('api/usuarios').then( response =>{
            this.usuarios = response.data.sort((a, b) => a.name.localeCompare(b.name));;
        }).catch(e=> console.log(e))
      },
      getUsuarios2(){
        axios.get('api/researchers').then( response =>{
            this.researchers2 = response.data;
        }).catch(e=> console.log(e))
      },
      getProgressReport(){
        axios.get('api/showProgressReport').then( response =>{
            this.publicPrivate.progressReport = response.data;
        }).catch(e=> console.log(e))
      },
      async guardarBorrador(){
        const ok = await this.$refs.confirmation.show({
            title: 'Save draft',
            message: `¿Are you sure you want to save this Public private connection as a draft? this action cannot be undone.`,
            okButton: 'Save',
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
            axios.post("api/publicPrivate", publicPrivate).then((result) => {
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
      cerrarModal(){
        const elem = this.$refs.closeBtn;
        this.$emit('recarga');
        elem.click();
      },
      capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
      },
      async createPublicPrivate() {
        this.errors = [];

        const itemsToCheck = [
          'comments',
          'participationPublicPolicies'
        ];


        for (const item in this.publicPrivate){
          if(this.publicPrivate[item] === "" || this.publicPrivate[item] === 0 || this.publicPrivate[item] == null || this.publicPrivate[item] == []){
            if (itemsToCheck.includes(item)) {
              } else {
                this.errors.push(item);
              }
            }
        }

        var contador = await axios.post('../api/verifyPublicPrivate', this.publicPrivate).then(function(response) {
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
            }else if(item == 'typeOfConnection'){
              mensaje =   mensaje + "The field Type of connection is required" + "\n";
            }else if(item == 'researcherInvolved'){
              mensaje =   mensaje + "The field Researcher involved is required" + "\n";
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
            title: 'Save Public private connection',
            message: `¿Are you sure you want to save this Public private connection?.`,
            okButton: 'Save',
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
              status: 'Finished',
              idUsuario: idUser1,
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
            axios.post("api/publicPrivate", publicPrivate).then((result) => {
              this.toast.success("Public private connection saved successfully!", {
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