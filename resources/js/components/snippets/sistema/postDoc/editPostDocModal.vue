<template>
  <transition name="modalcrear">
    <div name="modal">
      <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container">
              <div class="modal-header pb-1 fw-bold" style="color: #444444;">
                <slot name="header">
                    Edit Postdoctoral fellow
                </slot>
                <label for="">Progress year: {{ postDoc.progressReport }} &nbsp;&nbsp; <a class="btn" v-if="is('Administrator')"@click="showModalProgress = true"><i class="fa-solid fa-pen-to-square"></i></a></label>
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
                        <label for="">Personal email:</label>
                        <label for="" style="color: orange;">*</label>
                        <label title="The format must be an email." style="color: #0A95FF;"><i class="fa-solid fa-circle-info"></i></label>
                        <br>
                        <input type="text" class= "form-control" v-model="postDoc.personalEmail">
                      </div>
                      <div class="col-md-6">
                        <label for="">Research topic:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="postDoc.researchTopic">
                      </div>
                  </div>
                  <br>
                  <div class="row">
                      <div class="col-md-3">
                        <label for="">Name of postdoc fellow:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="postDoc.nameOfPostdoc">
                      </div>
                      <div class="col-md-3">
                          <label for="">Gender: </label>
                          <label for="" style="color: orange;">*</label>
                          <br>
                          <select class="form-select" v-model="postDoc.gender">
                            <option disabled :value="null">Select a Gender</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                            <option value="Non binary">Non binary</option>
                            <option value="Other">Other</option>
                            </select>
                      </div>
                      <div class="col-md-3">
                          <label for="">Identification: </label>
                          <label for="" style="color: orange;">*</label>
                          <br>
                          <select class="form-select" @change="postDoc.run = ''; postDoc.passport = '';" v-model="postDoc.identification">
                            <option disabled :value="null">Select One</option>
                            <option value="run">Run</option>
                            <option value="passport">Passport</option>
                          </select>
                      </div>                        
                      <div v-if="postDoc.identification == 'run'" class="col-md-3">
                          <label for="">Run: </label>
                          <label for="" style="color: orange;">*</label>
                          <br>
                          <input type="text" class= "form-control" v-on:input="validateInput" v-on:keypress="isNumberOrDash" @keyup="checkRut()" v-model="postDoc.run">
                      </div>
                      <div v-if="postDoc.identification == 'passport'" class="col-md-3">
                          <label for="">Passport: </label>
                          <label for="" style="color: orange;">*</label>
                          <br>
                          <input type="text" class= "form-control" v-model="postDoc.passport">
                      </div>
                    </div>
                    <br>
                    <div class="row">

                      <div class="col-md-3">
                          <label for="">Start year: </label>
                          <label for="" style="color: orange;">*</label>
                          <br>
                          <select class="form-select" id="selectYear" v-model="postDoc.startYear">
                          <option disabled :value="null">Select a year</option>
                          <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
                          </select>
                      </div>
                      <div class="col-md-3">
                          <label for="selectMonth">Start month:</label>
                          <br>
                          <select class="form-select" id="selectMonth" v-model="postDoc.startMonth">
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
                          <label for="">Ending year: </label>
                          <label for="" style="color: orange;">*</label>
                          <br>
                          <select class="form-select" id="selectYear" v-model="postDoc.endingYear">
                          <option disabled :value="null">Select a year</option>
                          <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
                          </select>
                      </div>
                      <div class="col-md-3">
                          <label for="selectMonth">End month:</label>
                          <br>
                          <select class="form-select" id="selectMonth" v-model="postDoc.endMonth">
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
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-md-6">
                        <label for="">Supervisor name:</label>
                        <label for="" style="color: orange;">*</label>
                        <Multiselect
                          placeholder="Select the options"
                          v-model="postDoc.supervisorName"
                          limit=4
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
                      <div class="col-md-6">
                        <label for="">Resources provided by the center:</label>
                        <label for="" style="color: orange;">*</label>
                        <Multiselect
                          placeholder="Select the options"
                          v-model="postDoc.resourcesProvided"
                          limit=4
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
                    <br>
                    <div class="row">

                      <div class="col-md-6">
                        <label for="">Institution where it was inserted:</label>
                        <br>
                        <input type="text" class= "form-control" v-model="postDoc.institutionName">
                      </div>
                      <div class="col-md-6">
                        <label for="">Funding source:</label>
                        <label for="" style="color: orange;">*</label>
                        <Multiselect
                          placeholder="Select the options"
                          v-model="postDoc.fundingSource"
                          limit=4
                          :searchable="true"
                          :close-on-select="false"
                          :createTag="true"
                          :options="options3"
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
                        <label for="">Comments:</label>
                        <br>
                        <input type="text" class= "form-control" v-model="postDoc.comments">
                      </div>
                    </div>
                  </slot>
                </div>
                <div class="modal-footer">
                  <slot name="footer">
                    <label class="form-check-label"><input type="checkbox" class="form-check-"
                    v-model="draft"> Edit as a draft</label>
                    <a v-if="draft == false" class="btn btn-continue float-end" @click="createPostDoc()" :disabled="buttonDisable">
                      {{ buttonText }}
                    </a>
                    <a v-else class="btn btn-continue float-end" @click="guardarBorrador()" :disabled="buttonDisable">
                      Edit draft
                    </a>
                  </slot>
                </div>
                <modalconfirmacion ref="confirmation"></modalconfirmacion>
                <modalalerta ref="alert"></modalalerta>
                <modalProgressYear v-bind:progressYear="postDoc.progressReport" v-if="showModalProgress" @close="showModalProgress = false" @submit="handleFormSubmit1"></modalProgressYear>
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
      postDoc:{
        nameOfPostdoc: '',
        identification: '',
        passport: "",
        run: "",
        gender: '',
        researchTopic: '',
        personalEmail: '',
        supervisorName: null,
        resourcesProvided: null,
        fundingSource: null,
        startYear: '',
        startMonth: '',
        endMonth: '',
        endingYear: '',
        institutionName: '',
        comments: '',
        progressReport: '',
      },
      options3: [
        'Basal Financing Program Funding',
        'Other sources',
      ],
      options2: [
        'Equipment',
        'Information',
        'Infrastructure',
        'Other',
      ],
      id: '',
      draft: false,
      showModalProgress: false,
      researchers: [],
      usuarios: [],
      idResearcher: '',
      buttonDisable: false,
      errors:[],
      buttonText:'Edit fellow',
    }),
    mounted(){
      this.getUsuarios();
      this.getUsuarios2();
    },
    props:{
      postDoc1: Object,
    },
    created(){
      this.id = this.postDoc1.id;
      this.idResearcher = this.postDoc1.idUsuario;
      this.postDoc.nameOfPostdoc = this.postDoc1.nameOfPostdoc;
      this.postDoc.identification = this.postDoc1.identification;
      if(this.postDoc1.identification == 'run'){
        this.postDoc.run = this.postDoc1.runOrPassport;
      }else{
        this.postDoc.passport = this.postDoc1.runOrPassport;
      }
      this.postDoc.personalEmail = this.postDoc1.personalEmail;
      this.postDoc.gender = this.postDoc1.gender;
      this.postDoc.researchTopic = this.postDoc1.researchTopic;
      this.postDoc.startYear = this.postDoc1.startYear;
      this.postDoc.endingYear = this.postDoc1.endingYear;
      this.postDoc.startMonth = this.postDoc1.startMonth;
      this.postDoc.endMonth = this.postDoc1.endMonth;
      this.postDoc.institutionName = this.postDoc1.institutionName;
      this.postDoc.comments = this.postDoc1.comments;
      this.postDoc.progressReport = this.postDoc1.progressReport;


      if (this.postDoc1.supervisorName != null) {
          const valoresSeparados1 = this.postDoc1.supervisorName.split(",");
          this.postDoc.supervisorName = valoresSeparados1.map((valor, index) => {
              valor = valor.trim();
              if (valor.endsWith('.')) {
                  valor = valor.slice(0, -1);
              }

              return { value: valor, name: valor };
          });
      }

      if (this.postDoc1.fundingSource != null) {
          const valoresSeparados1 = this.postDoc1.fundingSource.split(",");
          this.postDoc.fundingSource = valoresSeparados1.map((valor, index) => {
              valor = valor.trim();
              if (valor.endsWith('.')) {
                  valor = valor.slice(0, -1);
              }

              return { value: valor, name: valor };
          });
      }

      if (this.postDoc1.resourcesProvided != null) {
          const valoresSeparados1 = this.postDoc1.resourcesProvided.split(",");
          this.postDoc.resourcesProvided = valoresSeparados1.map((valor, index) => {
              valor = valor.trim();
              if (valor.endsWith('.')) {
                  valor = valor.slice(0, -1);
              }

              return { value: valor, name: valor };
          });
      }
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
        this.postDoc.progressReport = year;
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
      // Función para obtener usuarios desde otra ruta de la API
      getUsuarios2(){
        axios.get('api/usuarios').then( response =>{
            this.usuarios = response.data.sort((a, b) => a.name.localeCompare(b.name));;
        }).catch(e=> console.log(e))
      },
      checkRut() {
        if (this.postDoc.run != ''){
          this.postDoc.run.replace('.','');
          var valor = this.postDoc.run.replace('.','');
          valor = valor.replace('-','');
          var cuerpo = valor.slice(0,-1);
          var dv = valor.slice(-1).toUpperCase();

          this.postDoc.run = cuerpo + '-'+ dv;
        }
      },
      validateInput() {
        this.postDoc.run = this.postDoc.run.replace(/[^0-9Kk\-]/g, '').replace(/(\-)\-+/g, '$1');
      },
      isNumberOrDash(event) {
        const charCode = event.which ? event.which : event.keyCode;
        const isK = charCode === 107 || charCode === 75;

        if (charCode === 45 && this.postDoc.run.indexOf('-') !== -1) {
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
      onInput2(event) {
        const input = event.target;
        // Limitar el año a 4 dígitos
        this.postDoc.endingYear = input.value.slice(0, 4);
      },
      onInput1(event) {
        const input = event.target;
        // Limitar el año a 4 dígitos
        this.postDoc.startYear = input.value.slice(0, 4);
      },
      calculateYears() {
        const currentYear = new Date().getFullYear();
        const startYear = currentYear - 20;
        this.years = Array.from({ length: 21 }, (_, index) => startYear + index);
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

            var runOrPassport1 = '';
            if(this.postDoc.identification == 'run'){
              runOrPassport1 = this.formatoRut(this.postDoc.run);
            }else{
              runOrPassport1 = this.postDoc.passport;
            }

            var supervisorName1 = "";
            if (this.postDoc.supervisorName !== null){
              if (this.postDoc.supervisorName.length !== 0) {
                this.postDoc.supervisorName.forEach((supervisorName, index) => {
                  supervisorName1 += supervisorName.name;
                  if (index === this.postDoc.supervisorName.length - 1) {
                    supervisorName1 += '.';
                  } else {
                    supervisorName1 += ', ';
                  }
                });
              }
            }

            var resourcesProvided1 = "";
            if (this.postDoc.resourcesProvided !== null){
              if (this.postDoc.resourcesProvided.length !== 0) {
                this.postDoc.resourcesProvided.forEach((resourcesProvided, index) => {
                  resourcesProvided1 += resourcesProvided.name;
                  if (index === this.postDoc.resourcesProvided.length - 1) {
                    resourcesProvided1 += '.';
                  } else {
                    resourcesProvided1 += ', ';
                  }
                });
              }
            }

            var fundingSource1 = "";
            if (this.postDoc.fundingSource !== null){
              if (this.postDoc.fundingSource.length !== 0) {
                this.postDoc.fundingSource.forEach((fundingSource, index) => {
                  fundingSource1 += fundingSource.name;
                  if (index === this.postDoc.fundingSource.length - 1) {
                    fundingSource1 += '.';
                  } else {
                    fundingSource1 += ', ';
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

            let postDoc = {
              idUsuario: idUser1,
              status: 'Draft',
              nameOfPostdoc: this.postDoc.nameOfPostdoc,
              identification: this.postDoc.identification,
              runOrPassport: runOrPassport1,
              gender: this.postDoc.gender,
              researchTopic: this.postDoc.researchTopic,
              personalEmail: this.postDoc.personalEmail,
              supervisorName: supervisorName1,
              resourcesProvided: resourcesProvided1,
              fundingSource: fundingSource1,
              startYear: this.postDoc.startYear,
              endingYear: this.postDoc.endingYear,
              startMonth: this.postDoc.startMonth,
              endMonth: this.postDoc.endMonth,
              institutionName: this.postDoc.institutionName,
              comments: this.postDoc.comments,
              progressReport: this.postDoc.progressReport,
            };
            axios.put(`api/postDoc/${this.id}`, postDoc).then((result) => {
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
      async createPostDoc() {
        this.errors = [];

        const itemsToCheck = [
          'institutionName',
          'comments',
          'startMonth',
          'endMonth'
        ];


        for (const item in this.postDoc){
          if(this.postDoc[item] === "" || this.postDoc[item] === 0 || this.postDoc[item] == null || this.postDoc[item] == []){
              if(this.postDoc.identification == '' && item == 'run' || this.postDoc.identification == '' && item == 'passport'){
              }else if(this.postDoc.identification == 'run' && item == 'passport'){
              }else if(this.postDoc.identification == 'passport' && item == 'run'){
              }else if (itemsToCheck.includes(item)) {
              }else{
                this.errors.push(item);
              }
            }
        }

        if(this.postDoc.identification == 'run' && this.postDoc.run != ""){
          var validacion = this.validarRut(this.postDoc.run);
          if(validacion == false){
            this.errors.push('invalidRut');
            }
        }

        let postDoc1 = {
          id: this.id,
          nameOfPostdoc: this.postDoc.nameOfPostdoc,
          identification: this.postDoc.identification,
          runOrPassport: runOrPassport1,
          gender: this.postDoc.gender,
          personalEmail: this.postDoc.personalEmail,
          researchTopic: this.postDoc.researchTopic,
          supervisorName: supervisorName1,
          resourcesProvided: resourcesProvided1,
          fundingSource: fundingSource1,
          startYear: this.postDoc.startYear,
          endingYear: this.postDoc.endingYear,
          startMonth: this.postDoc.startMonth,
          endMonth: this.postDoc.endMonth,
          institutionName: this.postDoc.institutionName,
          comments: this.postDoc.comments,
          progressReport: this.postDoc.progressReport,
        };

        var contador = await axios.post('../api/verifyPostdoc', postDoc1).then(function(response) {
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
            if(item == 'nameOfPostdoc'){
              mensaje =   mensaje + "The field Name of postdoc is required" + "\n";
            }else if(item == 'researchTopic'){
              mensaje =   mensaje + "The field Research topic is required" + "\n";
            }else if(item == 'supervisorName'){
              mensaje =   mensaje + "The field Supervisor name is required" + "\n";
            }else if(item == 'personalEmail'){
              mensaje =   mensaje + "The field Personal email is required" + "\n";
            }else if(item == 'resourcesProvided'){
              mensaje =   mensaje + "The field Resources provided is required" + "\n";
            }else if(item == 'invalidRut'){
              mensaje =   mensaje + "The entered Run is invalid" + "\n";
            }else if(item == 'fundingSource'){
              mensaje =   mensaje + "The field Funding source is required" + "\n";
            }else if(item == 'startYear'){
              mensaje =   mensaje + "The field Start year is required" + "\n";
            }else if(item == 'startMonth'){
              mensaje =   mensaje + "The field Start month is required" + "\n";
            }else if(item == 'endingYear'){
              mensaje =   mensaje + "The field Ending year is required" + "\n";
            }else if(item == 'endMonth'){
              mensaje =   mensaje + "The field Ending month is required" + "\n";
            }else if(item == 'institutionName'){
              mensaje =   mensaje + "The field Institution where it was inserted is required" + "\n";
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
            title: 'Edit Postdoctoral fellow',
            message: `¿Are you sure you want to edit this Postdoctoral fellow?.`,
            okButton: 'Edit',
            cancelButton: 'Return'
          })
          if (ok) {

            var runOrPassport1 = '';
            if(this.postDoc.identification == 'run'){
              runOrPassport1 = this.formatoRut(this.postDoc.run);
            }else{
              runOrPassport1 = this.postDoc.passport;
            }

            var supervisorName1 = "";
            if (this.postDoc.supervisorName !== null){
              if (this.postDoc.supervisorName.length !== 0) {
                this.postDoc.supervisorName.forEach((supervisorName, index) => {
                  supervisorName1 += supervisorName.name;
                  if (index === this.postDoc.supervisorName.length - 1) {
                    supervisorName1 += '.';
                  } else {
                    supervisorName1 += ', ';
                  }
                });
              }
            }

            var resourcesProvided1 = "";
            if (this.postDoc.resourcesProvided !== null){
              if (this.postDoc.resourcesProvided.length !== 0) {
                this.postDoc.resourcesProvided.forEach((resourcesProvided, index) => {
                  resourcesProvided1 += resourcesProvided.name;
                  if (index === this.postDoc.resourcesProvided.length - 1) {
                    resourcesProvided1 += '.';
                  } else {
                    resourcesProvided1 += ', ';
                  }
                });
              }
            }

            var fundingSource1 = "";
            if (this.postDoc.fundingSource !== null){
              if (this.postDoc.fundingSource.length !== 0) {
                this.postDoc.fundingSource.forEach((fundingSource, index) => {
                  fundingSource1 += fundingSource.name;
                  if (index === this.postDoc.fundingSource.length - 1) {
                    fundingSource1 += '.';
                  } else {
                    fundingSource1 += ', ';
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

            let postDoc = {
              idUsuario: idUser1,
              status: 'Finished',
              nameOfPostdoc: this.postDoc.nameOfPostdoc,
              identification: this.postDoc.identification,
              runOrPassport: runOrPassport1,
              gender: this.postDoc.gender,
              personalEmail: this.postDoc.personalEmail,
              researchTopic: this.postDoc.researchTopic,
              supervisorName: supervisorName1,
              resourcesProvided: resourcesProvided1,
              fundingSource: fundingSource1,
              startYear: this.postDoc.startYear,
              endingYear: this.postDoc.endingYear,
              startMonth: this.postDoc.startMonth,
              endMonth: this.postDoc.endMonth,
              institutionName: this.postDoc.institutionName,
              comments: this.postDoc.comments,
              progressReport: this.postDoc.progressReport,
            };
            axios.put(`api/postDoc/${this.id}`, postDoc).then((result) => {
              this.toast.success("Postdoctoral fellow edited successfully!", {
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