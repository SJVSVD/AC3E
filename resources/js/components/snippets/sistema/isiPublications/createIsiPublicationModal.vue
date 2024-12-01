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
                <label for="">Progress year: {{ isiPublication.progressReport }} &nbsp;&nbsp; <a class="btn" v-if="is('Administrator')"@click="showModalProgress = true"><i class="fa-solid fa-pen-to-square"></i></a></label>
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
                          v-model="isiPublication.researcherInvolved"
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
                      <div class="col-md-6">
                          <label for="">Author(s): </label>
                          <label for="" style="color: orange;">*</label>
                          <label title="The format for this field should be as follows: 'Last Name, Initial of First Name; Last Name, Initial of First Name; ...'" style="color: #0A95FF;"><i class="fa-solid fa-circle-info"></i></label>
                          <br>
                          <input  type="text" class= "form-control" v-model="isiPublication.authors">
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-md-5">
                        <label for="">Digital Object Identifier (DOI):</label>
                        <label for="" style="color: orange;">*</label>
                        <label title="Example of a valid doi: '10.0000/xxxx000000'" style="color: #0A95FF;"><i class="fa-solid fa-circle-info"></i></label>
                        <br>
                        <input type="text" class= "form-control" v-model="isiPublication.doi">
                      </div>
                      <div class="col-md-1 pt-2">
                        <br>
                        <a class="btn btn-search-blue" title="Enter the doi to automatically fill in information." @click="useDOI()"><i class="fa-solid fa-magnifying-glass"></i></a>
                      </div>
                      <div class="col-md-3">
                        <label for="">Year Published:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <select class="form-select" id="selectYear" v-model="isiPublication.yearPublished">
                          <option disabled value="">Select a year</option>
                          <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
                        </select>
                      </div>
                      <div class="col-md-3">
                          <label for="selectMonth">Month:</label>
                          <br>
                          <select class="form-select" id="selectMonth" v-model="isiPublication.month">
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
                        <input type="text" class= "form-control" v-model="isiPublication.articleTitle">
                      </div>
                      <div class="col-md-6">
                        <label for="">Journal Name:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="isiPublication.journalName">
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-md-4">
                        <label for="">Volume:</label>
                        <br>
                        <input type="text" class= "form-control" v-model="isiPublication.volume">
                      </div>
                      <div class="col-md-4">
                        <label for="">First Page:</label>
                        <br>
                        <input type="text" class= "form-control" v-model="isiPublication.firstPage">
                      </div>
                      <div class="col-md-4">
                        <label for="">Last Page:</label>
                        <br>
                        <input type="text" class= "form-control" v-model="isiPublication.lastPage">
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
                        <input type="text" class= "form-control" v-model="isiPublication.comments">
                      </div>
                    </div>
                    <hr size="3" class="separador">
                    <div class="row">
                      <div class="text-uppercase pb-2">Participations:<label for="" style="color: orange;">*</label></div>
                      <div class="col-md-4">
                          <div class="form-check pt-2 ">
                            <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                  v-model="isiPublication.mainResearchers"> Main researchers</label>
                          </div>
                      </div>
                      <div class="col-md-4">
                          <div class="form-check pt-2 ">
                            <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                  v-model="isiPublication.associativeResearchers"> Associative researchers</label>
                          </div>
                      </div>
                      <div class="col-md-4">
                          <div class="form-check pt-2 ">
                            <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                  v-model="isiPublication.postDoc"> Post doc</label>
                          </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-4">
                          <div class="form-check pt-2 ">
                            <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                  v-model="isiPublication.thesisStudents"> Thesis students </label>
                          </div>
                      </div>
                      <div class="col-md-4">
                          <div class="form-check pt-2 ">
                            <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                  v-model="isiPublication.nationalExternalResearchers"> National external researchers </label>
                          </div>
                      </div>
                      <div class="col-md-4">
                          <div class="form-check pt-2 ">
                            <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                  v-model="isiPublication.internationalExternalResearchers"> Inter. external researchers </label>
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
                <modalProgressYear v-bind:progressYear="isiPublication.progressReport" v-if="showModalProgress" @close="showModalProgress = false" @submit="handleFormSubmit1"></modalProgressYear>
                <modalfotoperfil v-if="showFotoPerfil" @close="showFotoPerfil = false"></modalfotoperfil>
                <modalconfirmacion ref="confirmation"></modalconfirmacion>
                <modalalerta ref="alert"></modalalerta>
          </div>
        </div>
      </div>
    </div>
  </transition>
</template>

<script>
import axios from 'axios'
import modalProgressYear from '../../sistema/progressYearEdit.vue';
import modalconfirmacion from '../../sistema/alerts/confirmationModal.vue'
import modalalerta from '../../sistema/alerts/alertModal.vue'
import {mixin} from '../../../../mixins.js'
import Multiselect from '@vueform/multiselect';
let user = document.head.querySelector('meta[name="user"]');
export default {
    components: {modalProgressYear, Multiselect, modalconfirmacion, modalalerta, modalProgressYear },
    mixins: [mixin],
    data: () => ({
      fundingSources: {
        basal: false,
        other: false,
      },
      isiPublication:{
        authors: "",
        articleTitle: "",
        journalName: "",
        doi: "",
        volume: "",
        firstPage: "",
        lastPage: "",
        yearPublished: "",
        month: "",
        keywords: "",
        fundings: null,
        researcherInvolved: null,
        mainResearchers: false,
        associativeResearchers: false,
        postDoc: false,
        thesisStudents: false,
        nationalExternalResearchers: false,
        internationalExternalResearchers: false,
        comments: "",
        progressReport: "",
      },
      showModalProgress: false,
      currentYear: new Date().getFullYear(),
      coauthor: false,
      draft: false,
      buttonDisable: false,
      idResearcher: '',
      researchers: [],
      usuarios:[],
      errors:[],
      years: [],
      buttonText:'Send Publication',
    }),
    mounted(){
      this.getUsuarios();
      this.getUsuarios2();
      const currentYear = new Date().getFullYear();
      const startYear = 2000;
      const endYear = currentYear + 1;
      this.years = Array.from({ length: endYear - startYear + 1 }, (_, index) => startYear + index);
      this.selectedYear = currentYear;
      this.years.sort((a, b) => b - a);
    },
    created(){
      this.getProgressReport();
      var role = JSON.parse(user.content).idRole;
      if(role == '1'){
        this.isiPublication.associativeResearchers = true;
      }else if(role == '2'){
        this.isiPublication.postDoc = true;
      }else if(role == '3'){
        this.isiPublication.mainResearchers = true;
      }
    },
    methods: {
      // Función para manejar el envío de un formulario con un año
      handleFormSubmit1(year) {
        this.isiPublication.progressReport = year;
      },
      // Función para obtener investigadores desde otra ruta de la API
      getUsuarios2(){
        axios.get('api/researchers').then( response =>{
          this.researchers  = response.data.sort((a, b) => {
              if (a.toLowerCase() < b.toLowerCase()) return -1;
              if (a.toLowerCase() > b.toLowerCase()) return 1;
              return 0;
          });
        }).catch(e=> console.log(e))
      },
      // Función para obtener usuarios desde otra ruta de la API
      getUsuarios(){
        axios.get('api/usuarios').then( response =>{
          this.usuarios = response.data.sort((a, b) => a.name.localeCompare(b.name));
        }).catch(e=> console.log(e))
      },
      // Función para obtener el año de progreso desde la API
      getProgressReport(){
        axios.get('api/showProgressReport').then( response =>{
            this.isiPublication.progressReport = response.data;
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


            var idUser1 = ''
            if(this.idResearcher != ''){
              idUser1 = this.idResearcher;
            }else{
              idUser1 = this.userID;
            }

            var peopleInvolved1 = "";
            if (this.isiPublication.researcherInvolved !== null){
              if (this.isiPublication.researcherInvolved.length !== 0) {
                this.isiPublication.researcherInvolved.forEach((researcherInvolved, index) => {
                  peopleInvolved1 += researcherInvolved.name;
                  if (index === this.isiPublication.researcherInvolved.length - 1) {
                    peopleInvolved1 += '.';
                  } else {
                    peopleInvolved1 += ', ';
                  }
                });
              }
            }

            let publication = {
              status: 'Draft',
              idUsuario: idUser1,
              researcherInvolved: peopleInvolved1,
              authors: this.isiPublication.authors,
              articleTitle: this.isiPublication.articleTitle,
              journalName: this.isiPublication.journalName,
              doi: this.isiPublication.doi,
              volume: this.isiPublication.volume,
              firstPage: this.isiPublication.firstPage,
              keywords: this.isiPublication.keywords,
              lastPage: this.isiPublication.lastPage,
              yearPublished: this.isiPublication.yearPublished,
              month: this.isiPublication.month,
              funding: fundingsName1,
              mainResearchers: this.isiPublication.mainResearchers,
              associativeResearchers: this.isiPublication.associativeResearchers,
              postDoc: this.isiPublication.postDoc,
              thesisStudents: this.isiPublication.thesisStudents,
              nationalExternalResearchers: this.isiPublication.nationalExternalResearchers,
              internationalExternalResearchers: this.isiPublication.internationalExternalResearchers,
              progressReport: this.isiPublication.progressReport,
              comments: this.isiPublication.comments,
            };
            axios.post("api/isiPublications", publication)
            .then((result) => {
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
              setTimeout(() => {
                this.cerrarModal();
              }, 1500);
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
      useDOI() {
          const encodedDoi = encodeURIComponent(this.isiPublication.doi);
          axios.post('api/useDoi', { doi: encodedDoi }).then(response => {
              console.log(response.data.Data);
              var register = response.data.Data[0];
              if(response.data.QueryResult.RecordsFound == 0){
                this.toast.warning("No records found with the entered DOI.", {
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
              }else{
                this.toast.success("A record with the entered doi has been found!", {
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
                var authorString = '';
                register.Author.Authors.forEach((author, index) => {
                    if (index !== 0) {
                        authorString += '; ';
                    }
                    authorString += author;
                });
                authorString += '.';
                console.log(register);
                this.isiPublication.authors = authorString;
                this.isiPublication.articleTitle = register.Title.Title[0];
                this.isiPublication.journalName = register.Source.SourceTitle[0];
                this.isiPublication.volume = register.Source.Volume[0];
                if (register.Keyword.Keywords && register.Keyword.Keywords.length > 0) {
                    this.isiPublication.keywords = register.Keyword.Keywords.join(', ');
                } else {
                    this.isiPublication.keywords = '';
                }
                let splitStrings = register.Source.Pages[0].split('-');
                this.isiPublication.firstPage = splitStrings[0];
                this.isiPublication.lastPage = splitStrings[1];
                this.isiPublication.yearPublished = register.Source['Published.BiblioYear'][0];
              }
          }).catch(error => {
              console.error(error);
          });
      },
      onInput(event) {
        const input = event.target;
        // Limitar el año a 4 dígitos
        this.isiPublication.yearPublished = input.value.slice(0, 4);
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
      isAnyCheckboxChecked() {
        return this.isiPublication.mainResearchers || this.isiPublication.associativeResearchers || this.isiPublication.postDoc || this.isiPublication.thesisStudents || this.isiPublication.nationalExternalResearchers || this.isiPublication.internationalExternalResearchers;
      },
      async crearPublicacion() {
        this.errors = [];
        const itemsToSkip = [
        'mainResearchers',
        'associativeResearchers',
        'postDoc',
        'thesisStudents',
        'nationalExternalResearchers',
        'internationalExternalResearchers',
        'sendingDate',
        'acceptanceDate',
        'progressReport',
        'comments',
        'volume',
        'firstPage',
        'lastPage',
        'keywords',
        'month',
        'fundings'
        ];

        for (const item in this.isiPublication) {
            const skipItem = itemsToSkip.includes(item);
            if (!skipItem && (this.isiPublication[item] === "" || this.isiPublication[item] === 0 || this.isiPublication[item] == null)) {
                if (item === 'coauthor' && this.coauthor === false) {
                } else {
                    this.errors.push(item);
                }
            }
        }

        if (!this.fundingSources.basal && !this.fundingSources.other) {
          this.errors.push("fundings");
        }

        var contador = await axios.post('../api/verifyIsi', this.isiPublication).then(function(response) {
          return response.data;
        }.bind(this)).catch(function(e) {
          console.log(e);
        });
        if (contador > 0){
          this.errors.push('duplicated');
        }

        if (!this.isAnyCheckboxChecked()) {
            this.toast.warning( 'At least one checkbox must be checked.', {
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
            return;
        }

        var mensaje = ""
        if (this.errors.length != 0){
          this.errors.forEach(item => {
            if(item == 'articleTitle'){
              mensaje =   mensaje + "The field Article Title is required" + "\n";
            }else if(item == 'researcherInvolved'){
              mensaje =   mensaje + "The field AC3E Researchers Involved is required" + "\n";
            }else if(item == 'journalName'){
              mensaje =   mensaje + "The field Journal Name is required" + "\n";
            }else if(item == 'firstPage'){
              mensaje =   mensaje + "The field First Page is required" + "\n";
            }else if(item == 'lastPage'){
              mensaje =   mensaje + "The field Last Page is required" + "\n";
            }else if(item == 'yearPublished'){
              mensaje =   mensaje + "The field Year Published is required" + "\n";
            }else if(item == 'fundings'){
              mensaje =   mensaje + "At least one funding source must be selected." + "\n";
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
            title: 'Send Publication',
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

            var idUser1 = ''
            if(this.idResearcher != ''){
              idUser1 = this.idResearcher;
            }else{
              idUser1 = this.userID;
            }

            var peopleInvolved1 = "";
            if (this.isiPublication.researcherInvolved !== null){
              if (this.isiPublication.researcherInvolved.length !== 0) {
                this.isiPublication.researcherInvolved.forEach((researcherInvolved, index) => {
                  peopleInvolved1 += researcherInvolved.name;
                  if (index === this.isiPublication.researcherInvolved.length - 1) {
                    peopleInvolved1 += '.';
                  } else {
                    peopleInvolved1 += ', ';
                  }
                });
              }
            }

            let publication = {
              status: 'Finished',
              researcherInvolved: peopleInvolved1,
              idUsuario: idUser1,
              authors: this.isiPublication.authors,
              articleTitle: this.isiPublication.articleTitle,
              journalName: this.isiPublication.journalName,
              doi: this.isiPublication.doi,
              volume: this.isiPublication.volume,
              keywords: this.isiPublication.keywords,
              firstPage: this.isiPublication.firstPage,
              lastPage: this.isiPublication.lastPage,
              yearPublished: this.isiPublication.yearPublished,
              month: this.isiPublication.month,
              funding: fundingsName1,
              mainResearchers: this.isiPublication.mainResearchers,
              associativeResearchers: this.isiPublication.associativeResearchers,
              postDoc: this.isiPublication.postDoc,
              thesisStudents: this.isiPublication.thesisStudents,
              nationalExternalResearchers: this.isiPublication.nationalExternalResearchers,
              internationalExternalResearchers: this.isiPublication.internationalExternalResearchers,
              sendingDate: this.isiPublication.sendingDate,
              acceptanceDate: this.isiPublication.acceptanceDate,
              progressReport: this.isiPublication.progressReport,
              comments: this.isiPublication.comments,
            };
            console.log(publication);
            axios.post("api/isiPublications", publication ).then((result) => {
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