<template>
  <transition name="modalcrear">
    <div name="modal">
      <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container">
              <div class="modal-header pb-1 fw-bold" style="color: #444444;">
                <slot name="header">
                    New Tec. and know. transfer 
                </slot>
                <label for="">Progress report year: {{ technologyKnowledge.progressReport }} &nbsp;&nbsp; <a class="btn" v-if="is('Administrator')"@click="showModalProgress = true"><i class="fa-solid fa-pen-to-square"></i></a></label>
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
                      <div class="text-uppercase pb-2">Category of transfer:<label for="" style="color: orange;">*</label></div>
                      <div class="col-md-4">
                          <div class="form-check pt-2 ">
                            <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                  v-model="technologyKnowledge.technologyTransfer"> Technology transfer</label>
                          </div>
                      </div>
                      <div class="col-md-4">
                          <div class="form-check pt-2 ">
                            <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                  v-model="technologyKnowledge.knowledgeTransfer"> Knowledge transfer</label>
                          </div>
                      </div>
                      <div class="col-md-4">
                            <label for="">Type of transfer:</label>
                            <label for="" style="color: orange;">*</label>
                            <select class="form-select" v-model="technologyKnowledge.typeOfTransfer">
                              <option disabled value="">Select a type</option>
                              <option value="Spin-off">Spin-off</option>
                              <option value="Licenses">Licenses</option>
                              <option value="Technology Transfer Agreement">Technology transfer agreement</option>
                              <option value="Services(Mandatory for Regional Centers)">Services(mandatory for regional centers)</option>
                              <option value="Sale of technology transfer product">Sale of technology transfer product</option>
                              <option value="Other">Other</option>
                              </select>
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-md-6">
                        <label for="">AC3E Researchers Involved:</label>
                        <label for="" style="color: orange;">*</label>
                        <Multiselect
                          placeholder="Select the options"
                          v-model="technologyKnowledge.researcherInvolved"
                          :searchable="true"
                          :close-on-select="false"
                          :createTag="true"
                          :options="researchers2"
                          mode="tags"
                          label="name"
                          trackBy="name"
                          :object="true"
                        />
                      </div>
                      <div class="col-md-6">
                        <label for="">Name of the institution involved:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="technologyKnowledge.nameOfInstitutionInvolved">
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-md-4">
                        <label for="">Description:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="technologyKnowledge.description">
                      </div>
                      <div class="col-md-2">
                        <label for="actualTrl">Actual TRL:</label>
                        <br>
                        <select id="actualTrl" class="form-select" v-model="technologyKnowledge.actualTrl">
                          <option disabled value="">Select a TRL</option>
                          <option v-for="n in 9" :key="n" :value="n">{{ n }}</option>
                        </select>
                      </div>
                      <div class="col-md-3">
                        <label for="">Beneficiary institution:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="technologyKnowledge.nameOfBeneficiary">
                      </div>
                      <div class="col-md-3">
                        <label for="">Country:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="technologyKnowledge.country">
                      </div>

                    </div>
                    <br>
                    <div class="row">
                      <div class="col-md-3">
                        <label for="">City:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="technologyKnowledge.city">
                      </div>
                      <div class="col-md-3">
                        <label for="">Place/region:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="technologyKnowledge.placeRegion">
                      </div>
                      <div class="col-md-3">
                          <label for="">Year: </label>
                          <label for="" style="color: orange;">*</label>
                          <br>
                          <select class="form-select" id="selectYear" v-model="technologyKnowledge.year">
                          <option disabled value="">Select a year</option>
                          <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
                          </select>
                      </div>
                      <div class="col-md-3">
                          <label for="selectMonth">Month:</label>
                          <br>
                          <select class="form-select" id="selectMonth" v-model="technologyKnowledge.month">
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
                          <label for="">Ending Year: </label>
                          <label for="" style="color: orange;">*</label>
                          <br>
                          <select class="form-select" id="selectYear" v-model="technologyKnowledge.yearEnding">
                          <option disabled value="">Select a year</option>
                          <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
                          </select>
                      </div>
                      <div class="col-md-3">
                          <label for="selectMonth">Ending Month:</label>
                          <br>
                          <select class="form-select" id="selectMonth" v-model="technologyKnowledge.monthEnding">
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
                      <div class="col-md-6">
                        <label for="">Comments:</label>
                        <br>
                        <input type="text" class= "form-control" v-model="technologyKnowledge.comments">
                      </div>
                    </div>
                  </slot>
                </div>
                <div class="modal-footer">
                  <slot name="footer">
                    <label class="form-check-label"><input type="checkbox" class="form-check-"
                    v-model="draft"> Save as a draft</label>
                    <a v-if="draft == false" class="btn btn-continue float-end" @click="createTechnology()" :disabled="buttonDisable">
                      {{ buttonText }}
                    </a>
                    <a v-else class="btn btn-continue float-end" @click="guardarBorrador()" :disabled="buttonDisable">
                      Save draft
                    </a>
                  </slot>
                </div>
                <modalconfirmacion ref="confirmation"></modalconfirmacion>
                <modalalerta ref="alert"></modalalerta>
                <modalProgressYear v-bind:progressYear="technologyKnowledge.progressReport" v-if="showModalProgress" @close="showModalProgress = false" @submit="handleFormSubmit1"></modalProgressYear>
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
      technologyKnowledge:{
        technologyTransfer: false,
        knowledgeTransfer: false,
        description: '',
        nameOfInstitutionInvolved: '',
        researcherInvolved: null,
        typeOfTransfer: '',
        nameOfBeneficiary: '',
        actualTrl: '',
        country: '',
        city: '',
        placeRegion: '',
        year: '',
        month:'',
        yearEnding: '',
        monthEnding:'',
        comments: '',
        progressReport: '',
      },
      draft: false,
      showModalProgress: false,
      buttonDisable: false,
      researchers2: '',
      usuarios: [],
      idResearcher: '',
      errors:[],
      buttonText:'Send New Record',
    }),
    created(){
      this.getUsuarios2();
      this.getProgressReport();
      this.getUsuarios();
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
        this.technologyKnowledge.progressReport = year;
      },
      // Método para obtener el informe de progreso
      getProgressReport(){
        axios.get('api/showProgressReport').then( response =>{
            this.technologyKnowledge.progressReport = response.data;
        }).catch(e=> console.log(e))
      },
      // Método para obtener los usuarios
      getUsuarios(){
        axios.get('api/usuarios').then( response =>{
            this.usuarios = response.data.sort((a, b) => a.name.localeCompare(b.name));;
        }).catch(e=> console.log(e))
      },
      // Método para obtener los usuarios 2
      getUsuarios2(){
        axios.get('api/researchers').then( response =>{
            this.researchers2 = response.data;
            this.researchers2  = response.data.sort((a, b) => {
                if (a.toLowerCase() < b.toLowerCase()) return -1;
                if (a.toLowerCase() > b.toLowerCase()) return 1;
                return 0;
            });
        }).catch(e=> console.log(e))
      },
      // Método para el evento de entrada
      onInput1(event) {
        const input = event.target;
        // Limitar el año a 4 dígitos
        this.technologyKnowledge.year = input.value.slice(0, 4);
      },
      // Método para calcular los años
      calculateYears() {
        const currentYear = new Date().getFullYear();
        const startYear = currentYear - 20;
        this.years = Array.from({ length: 21 }, (_, index) => startYear + index);
      },
      // Método para guardar un borrador
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
            if (this.technologyKnowledge.researcherInvolved !== null){
              if (this.technologyKnowledge.researcherInvolved.length !== 0) {
                this.technologyKnowledge.researcherInvolved.forEach((researcherInvolved, index) => {
                  researcherInvolved1 += researcherInvolved.name;
                  if (index === this.technologyKnowledge.researcherInvolved.length - 1) {
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

            let technologyKnowledge = {
              idUsuario: idUser1,
              status: 'Draft',
              description: this.technologyKnowledge.description,
              nameOfInstitutionInvolved: this.technologyKnowledge.nameOfInstitutionInvolved,
              researcherInvolved: researcherInvolved1,
              technologyTransfer: this.technologyKnowledge.technologyTransfer,
              knowledgeTransfer: this.technologyKnowledge.knowledgeTransfer,
              typeOfTransfer: this.technologyKnowledge.typeOfTransfer,
              nameOfBeneficiary: this.technologyKnowledge.nameOfBeneficiary,
              actualTrl: this.technologyKnowledge.actualTrl,
              country: this.technologyKnowledge.country,
              city: this.technologyKnowledge.city,
              placeRegion: this.technologyKnowledge.placeRegion,
              year: this.technologyKnowledge.year,
              month: this.technologyKnowledge.month,
              yearEnding: this.technologyKnowledge.yearEnding,
              monthEnding: this.technologyKnowledge.monthEnding,
              comments: this.technologyKnowledge.comments,
              progressReport: this.technologyKnowledge.progressReport,
            };
            axios.post("api/technologyKnowledge", technologyKnowledge).then((result) => {
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
      // Método para cerrar el modal
      cerrarModal(){
        const elem = this.$refs.closeBtn;
        this.$emit('recarga');
        elem.click();
      },
      // Método para capitalizar la primera letra de una cadena
      capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
      },
      // Método para crear un registro
      async createTechnology() {
        this.errors = [];
        const fieldsToExclude = ['comments', 'technologyTransfer','knowledgeTransfer','month','monthEnding','actualTrl']; // Arreglo de campos a excluir

        for (const item in this.technologyKnowledge) {
          if (!fieldsToExclude.includes(item)) { // Verifica si el campo no está en la lista de campos a excluir
            if (this.technologyKnowledge[item] === "" || this.technologyKnowledge[item] === 0 ||this.technologyKnowledge[item] == null || this.technologyKnowledge[item] == []){
              this.errors.push(item);
            } 
          }
        }

        if (
          (this.technologyKnowledge['technologyTransfer'] === "" || this.technologyKnowledge['technologyTransfer'] === 0 || this.technologyKnowledge['technologyTransfer'] == null || this.technologyKnowledge['technologyTransfer'] == []) &&
          (this.technologyKnowledge['knowledgeTransfer'] === "" || this.technologyKnowledge['knowledgeTransfer'] === 0 || this.technologyKnowledge['knowledgeTransfer'] == null || this.technologyKnowledge['knowledgeTransfer'] == [])
        ) {
          this.errors.push('categoryTransfer');
        }

        var contador = await axios.post('../api/verifyTechnology', this.technologyKnowledge).then(function(response) {
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
            if(item == 'categoryTransfer'){
              mensaje =   mensaje + "The field Category of transfer is required" + "\n";
            }else if(item == 'typeOfTransfer'){
              mensaje =   mensaje + "The field Type of transfer is required" + "\n";
            }else if(item == 'researcherInvolved'){
              mensaje =   mensaje + "The field Researchers involved is required" + "\n";
            }else if(item == 'nameOfInstitutionInvolved'){
              mensaje =   mensaje + "The field Name of institution involved is required" + "\n";
            }else if(item == 'nameOfBeneficiary'){
              mensaje =   mensaje + "The field Name of beneficiary institution is required" + "\n";
            }else if(item == 'actualTrl'){
              mensaje =   mensaje + "The field Actual TRL is required" + "\n";
            }else if(item == 'placeRegion'){
              mensaje =   mensaje + "The field Place/Region is required" + "\n";
            }else if(item == 'yearEnding'){
              mensaje =   mensaje + "The field Ending Year is required" + "\n";
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
            title: 'Save Technology and knowledge transfer',
            message: `¿Are you sure you want to save this Technology and knowledge transfer?.`,
            okButton: 'Save',
            cancelButton: 'Return'
          })
          if (ok) {

            var researcherInvolved1 = "";
            if (this.technologyKnowledge.researcherInvolved !== null){
              if (this.technologyKnowledge.researcherInvolved.length !== 0) {
                this.technologyKnowledge.researcherInvolved.forEach((researcherInvolved, index) => {
                  researcherInvolved1 += researcherInvolved.name;
                  if (index === this.technologyKnowledge.researcherInvolved.length - 1) {
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

            let technologyKnowledge = {
              status: 'Finished',
              idUsuario: idUser1,
              description: this.technologyKnowledge.description,
              nameOfInstitutionInvolved: this.technologyKnowledge.nameOfInstitutionInvolved,
              researcherInvolved: researcherInvolved1,
              technologyTransfer: this.technologyKnowledge.technologyTransfer,
              knowledgeTransfer: this.technologyKnowledge.knowledgeTransfer,
              typeOfTransfer: this.technologyKnowledge.typeOfTransfer,
              nameOfBeneficiary: this.technologyKnowledge.nameOfBeneficiary,
              actualTrl: this.technologyKnowledge.actualTrl,
              country: this.technologyKnowledge.country,
              city: this.technologyKnowledge.city,
              placeRegion: this.technologyKnowledge.placeRegion,
              year: this.technologyKnowledge.year,
              month: this.technologyKnowledge.month,
              yearEnding: this.technologyKnowledge.yearEnding,
              monthEnding: this.technologyKnowledge.monthEnding,
              comments: this.technologyKnowledge.comments,
              progressReport: this.technologyKnowledge.progressReport,
            };
            axios.post("api/technologyKnowledge", technologyKnowledge).then((result) => {
              this.toast.success("Technology and knowledge transfer saved successfully!", {
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