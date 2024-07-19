<template>
  <transition name="modalcrear">
    <div name="modal">
      <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container">
              <div class="modal-header pb-1 fw-bold" style="color: #444444;">
                <slot name="header">
                    Edit Publication
                </slot>
                <label for="">Progress year: {{ isiPublication.progressReport }} &nbsp;&nbsp; <a class="btn" @click="showModalProgress = true"><i class="fa-solid fa-pen-to-square"></i></a></label>
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
                        <label for="">AC3E researcher involved:</label>
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
                          <input title="First Name and Last Name." type="text" class= "form-control" v-model="isiPublication.authors">
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
                          <option disabled :value="null">Select a year</option>
                          <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
                        </select>
                      </div>
                      <div class="col-md-3">
                          <label for="selectMonth">Month:</label>
                          <label for="selectMonth" style="color: orange;">*</label>
                          <br>
                          <select class="form-select" id="selectMonth" v-model="isiPublication.month">
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
                          <div>
                            <Multiselect
                                placeholder="Select the options"
                                v-model="isiPublication.fundings"
                                limit=4
                                :searchable="true"
                                :close-on-select="false"
                                :createTag="true"
                                :options="options1"
                                mode="tags"
                                label="name"
                                trackBy="name"
                                :object="true"
                            />
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
                      <div class="text-uppercase pb-2">Participations:</div>
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
                    v-model="draft"> Edit as a draft</label>
                    <a v-if="draft == false" class="btn btn-continue float-end" @click="editarPublicacion()" :disabled="buttonDisable">
                      {{ buttonText }}
                    </a>
                    <a v-else class="btn btn-continue float-end" @click="guardarBorrador()" :disabled="buttonDisable">
                      Edit draft
                    </a>
                  </slot>
                </div>
                <modalfotoperfil v-if="showFotoPerfil" @close="showFotoPerfil = false"></modalfotoperfil>
                <modalconfirmacion ref="confirmation"></modalconfirmacion>
                <modalalerta ref="alert"></modalalerta>
                <modalProgressYear v-bind:progressYear="isiPublication.progressReport" v-if="showModalProgress" @close="showModalProgress = false" @submit="handleFormSubmit1"></modalProgressYear>
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
    components: { modalProgressYear,modalconfirmacion, modalalerta, Multiselect },
    mixins: [mixin],
    data: () => ({
      isiPublication:{
        authors: "",
        articleTitle: "",
        journalName: "",
        doi: "",
        volume: "",
        firstPage: "",
        lastPage: "",
        keywords: "",
        researcherInvolved: null,
        fundings: null,
        mainResearchers: false,
        associativeResearchers: false,
        postDoc: false,
        thesisStudents: false,
        nationalExternalResearchers: false,
        internationalExternalResearchers: false,
        yearPublished: "",
        month: "",
        comments: "",
        progressReport: "",
      },
      options1: [
        'Basal Financing Program Funding',
        'Other sources',
      ],
      id: null,
      draft: false,
      idResearcher: '',
      showModalProgress: false,
      currentYear: new Date().getFullYear(),
      buttonDisable: false,
      researchers: [],
      usuarios: [],
      errors:[],
      buttonText:'Edit Publication',
    }),
    props:{
      isiPublication1: Object,
    },
    mounted(){
      this.getUsuarios2();
      this.getUsuarios();
      const currentYear = new Date().getFullYear();
      const startYear = 2000;
      const endYear = currentYear + 1;
      this.years = Array.from({ length: endYear - startYear + 1 }, (_, index) => startYear + index);
      this.selectedYear = currentYear;
      this.years.sort((a, b) => b - a);
    },
    created(){
      this.id = this.isiPublication1.id;
      this.idResearcher = this.isiPublication1.idUsuario;
      this.isiPublication.authors = this.isiPublication1.authors;
      this.isiPublication.articleTitle = this.isiPublication1.articleTitle;
      this.isiPublication.journalName = this.isiPublication1.journalName;
      this.isiPublication.doi = this.isiPublication1.doi;
      this.isiPublication.volume = this.isiPublication1.volume;
      this.isiPublication.firstPage = this.isiPublication1.firstPage;
      this.isiPublication.lastPage = this.isiPublication1.lastPage;
      this.isiPublication.yearPublished = this.isiPublication1.yearPublished;
      this.isiPublication.month = this.isiPublication1.month;
      this.isiPublication.keywords = this.isiPublication1.keywords;

      if (this.isiPublication1.funding != null) {
          const valoresSeparados1 = this.isiPublication1.funding.split(",");
          this.isiPublication.fundings = valoresSeparados1.map((valor, index) => {
              valor = valor.trim();
              if (valor.endsWith('.')) {
                  valor = valor.slice(0, -1);
              }

              return { value: valor, name: valor };
          });
      }

      if (this.isiPublication1.researcherInvolved != null) {
          const valoresSeparados1 = this.isiPublication1.researcherInvolved.split(",");
          this.isiPublication.researcherInvolved = valoresSeparados1.map((valor, index) => {
              valor = valor.trim();
              if (valor.endsWith('.')) {
                  valor = valor.slice(0, -1);
              }

              return { value: valor, name: valor };
          });
      }

      this.isiPublication.comments = this.isiPublication1.comments;
      if(this.isiPublication1.mainResearchers == 1){
        this.isiPublication.mainResearchers = true;
      }

      if(this.isiPublication1.associativeResearchers == 1){
        this.isiPublication.associativeResearchers = true;
      }

      if(this.isiPublication1.postDoc == 1){
        this.isiPublication.postDoc = true;
      }

      if(this.isiPublication1.thesisStudents == 1){
        this.isiPublication.thesisStudents = true;
      }

      if(this.isiPublication1.nationalExternalResearchers == 1){
        this.isiPublication.nationalExternalResearchers = true;
      }

      if(this.isiPublication1.internationalExternalResearchers == 1){
        this.isiPublication.internationalExternalResearchers = true;
      }

      this.isiPublication.progressReport = this.isiPublication1.progressReport;
    },
    methods: {
      // Función para obtener usuarios (investigadores) desde la API
      getUsuarios(){
        axios.get('api/usuarios').then( response =>{
            this.usuarios = response.data.sort((a, b) => a.name.localeCompare(b.name));;
        }).catch(e=> console.log(e))
      },
      // Función para obtener usuarios desde otra ruta de la API
      getUsuarios2(){
        axios.get('api/researchers').then( response =>{
            this.researchers = response.data;
        }).catch(e=> console.log(e))
      },
      // Función para manejar el envío de un formulario con un año
      handleFormSubmit1(year) {
        this.isiPublication.progressReport = year;
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
            var fundingsName1 = "";
            if (this.isiPublication.fundings !== null){
              if (this.isiPublication.fundings.length !== 0) {
                this.isiPublication.fundings.forEach((fundings, index) => {
                  fundingsName1 += fundings.name;
                  if (index === this.isiPublication.fundings.length - 1) {
                    fundingsName1 += '.';
                  } else {
                    fundingsName1 += ', ';
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
              comments: this.isiPublication.comments,
            };
            axios.put(`api/isiPublications/${this.id}`, publication ).then((result) => {
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
                var authorString = '';
                register.Author.Authors.forEach((author, index) => {
                    if (index !== 0) {
                        authorString += '; ';
                    }
                    authorString += author;
                });
                authorString += '.';
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
        // Edita el registro despues de validar
        async editarPublicacion() {
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
          'keywords'
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

        let publication1 = {
          id: this.id,
          authors: this.isiPublication.authors,
          articleTitle: this.isiPublication.articleTitle,
          journalName: this.isiPublication.journalName,
          doi: this.isiPublication.doi,
          volume: this.isiPublication.volume,
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
          comments: this.isiPublication.comments,
        };

        var contador = await axios.post('../api/verifyIsi', publication1).then(function(response) {
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
            if(item == 'articleTitle'){
              mensaje =   mensaje + "The field Article Title is required" + "\n";
            }else if(item == 'researcherInvolved'){
              mensaje =   mensaje + "The field AC3E Researcher involved is required" + "\n";
            }else if(item == 'journalName'){
              mensaje =   mensaje + "The field Journal Name is required" + "\n";
            }else if(item == 'firstPage'){
              mensaje =   mensaje + "The field First Page is required" + "\n";
            }else if(item == 'lastPage'){
              mensaje =   mensaje + "The field Last Page is required" + "\n";
            }else if(item == 'yearPublished'){
              mensaje =   mensaje + "The field Year Published is required" + "\n";
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
            title: 'Edit Publication',
            message: `¿Are you sure you want to edit this publication?.`,
            okButton: 'Send',
            cancelButton: 'Return'
          })
          if (ok) {
            var fundingsName1 = "";
            if (this.isiPublication.fundings !== null){
              if (this.isiPublication.fundings.length !== 0) {
                this.isiPublication.fundings.forEach((fundings, index) => {
                  fundingsName1 += fundings.name;
                  if (index === this.isiPublication.fundings.length - 1) {
                    fundingsName1 += '.';
                  } else {
                    fundingsName1 += ', ';
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

            let publication = {
              status: 'Finished',
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
              comments: this.isiPublication.comments,
            };
            axios.put(`api/isiPublications/${this.id}`, publication).then((result) => {
              this.buttonDisable = true;
              this.buttonText = 'Sending...';
              this.toast.success("Publication edited successfully!", {
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
