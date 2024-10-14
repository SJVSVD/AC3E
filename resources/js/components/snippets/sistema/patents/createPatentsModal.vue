<template>
  <transition name="modalcrear">
    <div name="modal">
      <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container">
              <div class="modal-header pb-1 fw-bold" style="color: #444444;">
                <slot name="header">
                    New Patent 
                </slot>
                <label for="">Progress year: {{ patent.progressReport }} &nbsp;&nbsp; <a class="btn" v-if="is('Administrator')"@click="showModalProgress = true"><i class="fa-solid fa-pen-to-square"></i></a></label>
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
                          <Multiselect
                            placeholder="Select the researchers"
                            v-model="patent.researcherInvolved"

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
                            <label for="">Ip type:</label>
                            <label for="" style="color: orange;">*</label>
                            <select class="form-select" v-model="patent.ipType">
                              <option disabled value="">Select a type</option>
                              <option value="Invention patent">Invention patent</option>
                              <option value="Utility model">Utility model</option>
                              <option value="Design (Industrial design and industrial drawing)">Design (Industrial design and industrial drawing)</option>
                              <option value="Appelation of origin">Appelation of origin</option>
                              <option value="Copyright">Copyright</option>
                              <option value="Others">Others</option>
                              </select>
                          </div>
                          <div class="col-md-6">
                            <label for="">Authors:</label>
                            <label for="" style="color: orange;">*</label>
                            <label title="The format for this field should be as follows: 'First Name,Last Name; First Name,Last Name; ...'" style="color: #0A95FF;"><i class="fa-solid fa-circle-info"></i></label>
                            <br>
                            <input type="text" class= "form-control" v-model="patent.authors">
                          </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-md-3">
                        <label for="">Institution owner(s):</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="patent.institutionOwner">
                      </div>
                      <div class="col-md-3">
                        <label for="">Country of registration:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="patent.countryOfRegistration">
                      </div>
                      <div class="col-md-3">
                        <label for="">Application date:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="date" class= "form-control" v-model="patent.applicationDate">
                      </div>
                      <div class="col-md-3">
                        <label for="">Grant date:</label>
                        <label v-if="patent.applicationStatus == 'Approved'" for="" style="color: orange;">*</label>
                        <br>
                        <input type="date" class= "form-control" v-model="patent.grantDate">
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-md-4">
                        <label for="">Application status:</label>
                        <label for="" style="color: orange;">*</label>
                        <select class="form-select" v-model="patent.applicationStatus">
                          <option disabled value="">Select a type</option>
                          <option value="In progress">In progress</option>
                          <option value="Approved">Approved</option>
                          </select>
                      </div>
                      <div class="col-md-4">
                        <label for="">Application granted n.º:</label>
                        <label v-if="patent.applicationStatus == 'Approved'" for="" style="color: orange;">*</label>
                        <label title="You must enter if approved." style="color: #0A95FF;"><i class="fa-solid fa-circle-info"></i></label>
                        <br>
                        <input type="text" class= "form-control" v-model="patent.applicationGranted">
                      </div>
                      <div class="col-md-4">
                        <label for="">Registration number:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="patent.registrationNumber">
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-md-3">
                        <label for="">Name of patent:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="patent.nameOfPatent">
                      </div>

                      <div class="col-md-6">
                        <label for="">Comments:</label>
                        <br>
                        <input type="text" class= "form-control" v-model="patent.comments">
                      </div>
                    </div>
                  </slot>
                </div>
                <div class="modal-footer">
                  <slot name="footer">
                    <label class="form-check-label"><input type="checkbox" class="form-check-"
                    v-model="draft"> Save as a draft</label>
                    <a v-if="draft == false" class="btn btn-continue float-end" @click="createPatent()" :disabled="buttonDisable">
                      {{ buttonText }}
                    </a>
                    <a v-else class="btn btn-continue float-end" @click="guardarBorrador()" :disabled="buttonDisable">
                      Save draft
                    </a>
                  </slot>
                </div>
                <modalconfirmacion ref="confirmation"></modalconfirmacion>
                <modalalerta ref="alert"></modalalerta>
                <modalProgressYear v-bind:progressYear="patent.progressReport" v-if="showModalProgress" @close="showModalProgress = false" @submit="handleFormSubmit1"></modalProgressYear>
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
      patent:{
        ipType: '',
        nameOfPatent: '',
        authors: '',
        institutionOwner: '',
        countryOfRegistration: '',
        applicationDate: '',
        grantDate: '',
        applicationStatus: '',
        registrationNumber: '',
        applicationGranted:'',
        researcherInvolved: null,
        comments: '',
        progressReport: '',
      },
      other: '',
      draft: false,
      showModalProgress: false,
      researchers: '',
      buttonDisable: false,
      usuarios: [],
      idResearcher: '',
      errors:[],
      buttonText:'Save Activity',
    }),
    mounted(){
      this.getUsuarios();
      this.getUsuarios2();
      this.getProgressReport();
    },
    methods: {
      // Función para manejar el envío de un formulario con un año
      handleFormSubmit1(year) {
        this.patent.progressReport = year;
      },
      // Función para obtener el año de progreso desde la API
      getProgressReport(){
        axios.get('api/showProgressReport').then( response =>{
            this.patent.progressReport = response.data;
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
      // Función para limpiar la entrada de archivo
      clearFileInput() {
        this.$refs.fileInput.value = '';
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
            if (this.patent.researcherInvolved !== null){
              if (this.patent.researcherInvolved.length !== 0) {
                this.patent.researcherInvolved.forEach((researcherInvolved, index) => {
                  researcherInvolved1 += researcherInvolved.name;
                  if (index === this.patent.researcherInvolved.length - 1) {
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

            let patent = {
              idUsuario: idUser1,
              status: 'Draft',
              ipType: this.patent.ipType,
              nameOfPatent: this.patent.nameOfPatent,
              authors: this.patent.authors,
              institutionOwner: this.patent.institutionOwner,
              countryOfRegistration: this.patent.countryOfRegistration,
              applicationDate: this.patent.applicationDate,
              grantDate: this.patent.grantDate,
              applicationStatus: this.patent.applicationStatus,
              registrationNumber: this.patent.registrationNumber,
              applicationGranted: this.patent.applicationGranted,
              researcherInvolved: researcherInvolved1,
              comments: this.patent.comments,
              progressReport: this.patent.progressReport,
            };
            axios.post("api/patents", patent).then((result) => {
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
      async createPatent() {
        this.errors = [];

        const itemsToCheck = [
          'grantDate',
          'applicationGranted',
          'comments'
        ];


        for (const item in this.patent){
          if(this.patent[item] === "" || this.patent[item] === 0 || this.patent[item] == null || this.patent[item] == []){
            if (itemsToCheck.includes(item)) {
              } else {
                this.errors.push(item);
              }
            }
        }

        if(this.patent.applicationGranted == '' && this.patent.applicationStatus == 'Approved'){
          this.errors.push('application granted n.º')
        }

        if(this.patent.grantDate == '' && this.patent.applicationStatus == 'Approved'){
          this.errors.push('grantDate')
        }

        var contador = await axios.post('../api/verifyPatent', this.patent).then(function(response) {
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
            if(item == 'ipType'){
              mensaje =   mensaje + "The field Ip type is required" + "\n";
            }else if(item == 'institutionOwner'){
              mensaje =   mensaje + "The field Institution owners is required" + "\n";
            }else if(item == 'countryOfRegistration'){
              mensaje =   mensaje + "The field Country of registration is required" + "\n";
            }else if(item == 'applicationDate'){
              mensaje =   mensaje + "The field Application date is required" + "\n";
            }else if(item == 'grantDate'){
              mensaje =   mensaje + "The field Grant date is required" + "\n";
            }else if(item == 'applicationStatus'){
              mensaje =   mensaje + "The field Application status is required" + "\n";
            }else if(item == 'registrationNumber'){
              mensaje =   mensaje + "The field Registration number is required" + "\n";
            }else if(item == 'researcherInvolved'){
              mensaje =   mensaje + "The field Researchers involved is required" + "\n";
            }else if(item == 'nameOfPatent'){
              mensaje =   mensaje + "The field Name of patent is required" + "\n";
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
            title: 'Save Patent',
            message: `¿Are you sure you want to save this Patent?.`,
            okButton: 'Save',
            cancelButton: 'Return'
          })
          if (ok) {

            var researcherInvolved1 = "";
            if (this.patent.researcherInvolved !== null){
              if (this.patent.researcherInvolved.length !== 0) {
                this.patent.researcherInvolved.forEach((researcherInvolved, index) => {
                  researcherInvolved1 += researcherInvolved.name;
                  if (index === this.patent.researcherInvolved.length - 1) {
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

            let patent = {
              status: 'Finished',
              idUsuario: idUser1,
              ipType: this.patent.ipType,
              nameOfPatent: this.patent.nameOfPatent,
              authors: this.patent.authors,
              institutionOwner: this.patent.institutionOwner,
              countryOfRegistration: this.patent.countryOfRegistration,
              applicationDate: this.patent.applicationDate,
              grantDate: this.patent.grantDate,
              applicationStatus: this.patent.applicationStatus,
              registrationNumber: this.patent.registrationNumber,
              applicationGranted: this.patent.applicationGranted,
              researcherInvolved: researcherInvolved1,
              comments: this.patent.comments,
              progressReport: this.patent.progressReport,
            };
            axios.post("api/patents", patent).then((result) => {
              this.toast.success("Patent saved successfully!", {
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