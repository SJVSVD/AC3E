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
                <label for="">Progress year: {{ nonIsiPublication.progressReport }} &nbsp;&nbsp; <a class="btn" @click="showModalProgress = true"><i class="fa-solid fa-pen-to-square"></i></a></label>
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
                          <label for="">AC3E researcher involved: </label>
                          <label for="" style="color: orange;">*</label>
                          <br>
                          <div>
                            <Multiselect
                                placeholder="Select the options"
                                v-model="nonIsiPublication.researcherInvolved"
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
                      </div>
                    </div>
                    <br>
                    <div class="row">
                          <div class="col-md-6">
                              <label for="">Author(s): </label>
                              <label for="" style="color: orange;">*</label>
                              <label title="The format for this field should be as follows: 'Last Name, Initial of First Name; Last Name, Initial of First Name; ...'" style="color: #0A95FF;"><i class="fa-solid fa-circle-info"></i></label>
                              <br>
                              <input type="text" class= "form-control" v-model="nonIsiPublication.authors">
                          </div>
                          <div class="col-md-3">
                            <label for="">Year Published:</label>
                            <label for="" style="color: orange;">*</label>
                            <br>
                            <select class="form-select" id="selectYear" v-model="nonIsiPublication.yearPublished">
                              <option disabled :value="null">Select a year</option>
                              <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
                            </select>
                          </div>
                          <div class="col-md-3">
                          <label for="selectMonth">Month:</label>
                          <label for="selectMonth" style="color: orange;">*</label>
                          <br>
                          <select class="form-select" id="selectMonth" v-model="nonIsiPublication.month">
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
                        <input type="text" class= "form-control" v-model="nonIsiPublication.articleTitle">
                      </div>
                      <div class="col-md-6">
                        <label for="">Journal Name:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="nonIsiPublication.journalName">
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-md-4">
                        <label for="">Volume:</label>
                        <br>
                        <input type="text" class= "form-control" v-model="nonIsiPublication.volume">
                      </div>
                      <div class="col-md-4">
                        <label for="">First Page:</label>
                        <br>
                        <input type="text" class= "form-control" v-model="nonIsiPublication.firstPage">
                      </div>
                      <div class="col-md-4">
                        <label for="">Last Page:</label>
                        <br>
                        <input type="text" class= "form-control" v-model="nonIsiPublication.lastPage">
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
                                v-model="nonIsiPublication.fundings"
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
                        <input type="text" class= "form-control" v-model="nonIsiPublication.comments">
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-md-6">
                          <label for="">Indexed by: </label>
                          <label for="" style="color: orange;">*</label>
                          <br>
                          <div>
                            <Multiselect
                                placeholder="Select the options"
                                v-model="nonIsiPublication.indexedBy"
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
                      <div class="col-md-4">
                        <div class="form-group">
                        <label for="archivo">File:</label>
                        <label v-if="nonIsiPublication1.file != null" title="This record already has a file, if you want to change add a new one, otherwise leave this field empty." style="color: #0A95FF;"><i class="fa-solid fa-circle-info"></i></label>
                        <input type="file" ref="fileInput" accept=".pdf, .jpg, .jpeg, .png," class= "form-control" @change="getFile">
                        </div>
                      </div>
                      <div class="col-md-2 pt-2">
                        <br>
                        <a class="btn btn-closed " title="Clear Input" @click="clearFileInput"><i class="fa-solid fa-ban"></i></a>
                        &nbsp;
                        <a v-if="nonIsiPublication1.file != null" class="btn btn-search-blue " title="Download" @click="descargarExtracto(id,nonIsiPublication1.usuario.name)"><i class="fa-solid fa-download"></i></a>
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
                <modalProgressYear v-bind:progressYear="nonIsiPublication.progressReport" v-if="showModalProgress" @close="showModalProgress = false" @submit="handleFormSubmit1"></modalProgressYear>
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
      nonIsiPublication:{
        authors: "",
        articleTitle: "",
        indexedBy: null,
        journalName: "",
        volume: "",
        firstPage: "",
        lastPage: "",
        yearPublished: "",
        month: "",
        fundings: null,
        file: "",
        researcherInvolved: null,
        comments: "",
        progressReport: "",
      },
      options1: [
        'Basal Financing Program Funding',
        'Other sources',
      ],
      options2: [
        'Scielo',
        'Scopus',
        'Conference',
        'Other',
      ],
      id: null,
      draft: false,
      idResearcher: '',
      showModalProgress: false,
      currentYear: new Date().getFullYear(),
      buttonDisable: false,
      usuarios: [],
      errors:[],
      buttonText:'Edit Publication',
    }),
    props:{
      nonIsiPublication1: Object,
    },
    created(){
      this.getUsuarios();
      this.getUsuarios2();
      this.idResearcher = this.nonIsiPublication1.idUsuario;
      this.id = this.nonIsiPublication1.id;
      this.nonIsiPublication.authors = this.nonIsiPublication1.authors;
      if (this.nonIsiPublication1.coauthor != null){
        this.coauthor = true;
        this.nonIsiPublication.coauthor = this.nonIsiPublication1.coauthor;
      }
      this.nonIsiPublication.articleTitle = this.nonIsiPublication1.articleTitle;
      this.nonIsiPublication.journalName = this.nonIsiPublication1.journalName;
      this.nonIsiPublication.volume = this.nonIsiPublication1.volume;
      this.nonIsiPublication.firstPage = this.nonIsiPublication1.firstPage;
      this.nonIsiPublication.lastPage = this.nonIsiPublication1.lastPage;
      this.nonIsiPublication.yearPublished = this.nonIsiPublication1.yearPublished;
      this.nonIsiPublication.month = this.nonIsiPublication1.month;
      if (this.nonIsiPublication1.funding != null) {
          const valoresSeparados1 = this.nonIsiPublication1.funding.split(",");
          this.nonIsiPublication.fundings = valoresSeparados1.map((valor, index) => {
              valor = valor.trim();
              if (valor.endsWith('.')) {
                  valor = valor.slice(0, -1);
              }

              return { value: valor, name: valor };
          });
      }

      if (this.nonIsiPublication1.indexedBy != null) {
          const valoresSeparados1 = this.nonIsiPublication1.indexedBy.split(",");
          this.nonIsiPublication.indexedBy = valoresSeparados1.map((valor, index) => {
              valor = valor.trim();
              if (valor.endsWith('.')) {
                  valor = valor.slice(0, -1);
              }

              return { value: valor, name: valor };
          });
      }

      if (this.nonIsiPublication1.researcherInvolved != null) {
          const valoresSeparados1 = this.nonIsiPublication1.researcherInvolved.split(",");
          this.nonIsiPublication.researcherInvolved = valoresSeparados1.map((valor, index) => {
              valor = valor.trim();
              if (valor.endsWith('.')) {
                  valor = valor.slice(0, -1);
              }

              return { value: valor, name: valor };
          });
      }

      this.nonIsiPublication.file = this.nonIsiPublication1.file;
      this.nonIsiPublication.comments = this.nonIsiPublication1.comments;
      this.nonIsiPublication.progressReport = this.nonIsiPublication1.progressReport;    
      const currentYear = new Date().getFullYear();
      const startYear = 2000;
      const endYear = currentYear + 1;
      this.years = Array.from({ length: endYear - startYear + 1 }, (_, index) => startYear + index);
      this.selectedYear = currentYear;
      this.years.sort((a, b) => b - a); 
    },
    methods: {
      handleFormSubmit1(year) {
        this.nonIsiPublication.progressReport = year;
      },
      descargarExtracto(id,nombre){
          axios({
              url: `api/nonIsiDownload/${id}`,
              method: 'GET',
              responseType: 'arraybuffer',
          }).then((response) => {
            let blob = new Blob([response.data], {
                    type: response.headers['content-type']
                });
                let link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                if (blob.type.includes('pdf')) {
                    link.download = `NonISI-${nombre}.pdf`;
                } else if (blob.type.includes('image')) {
                    link.download = `NonISI-${nombre}.png`; // Cambia la extensión según el tipo de imagen
                } else {
                    // Si el tipo de archivo no es ni PDF ni imagen, puedes manejarlo de acuerdo a tus requerimientos
                    console.error('Tipo de archivo no compatible');
                    return;
                }
                link.click();
          });
      },
      clearFileInput() {
        this.$refs.fileInput.value = '';
      },
      async getFile(e){
        this.nonIsiPublication.file = e.target.files[0];
      },
      getUsuarios2(){
        axios.get('api/usuarios').then( response =>{
            this.usuarios = response.data.sort((a, b) => a.name.localeCompare(b.name));;
        }).catch(e=> console.log(e))
      },
      getUsuarios(){
        axios.get('api/researchers').then( response =>{
            this.researchers = response.data;
        }).catch(e=> console.log(e))
      },
      async guardarBorrador(){
        const ok = await this.$refs.confirmation.show({
            title: 'Edit draft',
            message: `¿Are you sure you want to edit this publication as a draft? this action cannot be undone.`,
            okButton: 'Edit',
            cancelButton: 'Return'
          })
          if (ok) {
            var fundingsName1 = "";
            if (this.nonIsiPublication.fundings !== null){
              if (this.nonIsiPublication.fundings.length !== 0) {
                this.nonIsiPublication.fundings.forEach((fundings, index) => {
                  fundingsName1 += fundings.name;
                  if (index === this.nonIsiPublication.fundings.length - 1) {
                    fundingsName1 += '.';
                  } else {
                    fundingsName1 += ', ';
                  }
                });
              }
            }

            var indexedBy1 = "";
            if (this.nonIsiPublication.indexedBy !== null){
              if (this.nonIsiPublication.indexedBy.length !== 0) {
                this.nonIsiPublication.indexedBy.forEach((indexedBy, index) => {
                  indexedBy1 += indexedBy.name;
                  if (index === this.nonIsiPublication.indexedBy.length - 1) {
                    indexedBy1 += '.';
                  } else {
                    indexedBy1 += ', ';
                  }
                });
              }
            }

            var researcherInvolved1 = "";
            if (this.nonIsiPublication.researcherInvolved !== null){
              if (this.nonIsiPublication.researcherInvolved.length !== 0) {
                this.nonIsiPublication.researcherInvolved.forEach((researcherInvolved, index) => {
                  researcherInvolved1 += researcherInvolved.name;
                  if (index === this.nonIsiPublication.researcherInvolved.length - 1) {
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

            let publication = {
              idUsuario: idUser1,
              status: 'Draft',
              authors: this.nonIsiPublication.authors,
              indexedBy: indexedBy1,
              researcherInvolved: researcherInvolved1,
              articleTitle: this.nonIsiPublication.articleTitle,
              journalName: this.nonIsiPublication.journalName,
              volume: this.nonIsiPublication.volume,
              firstPage: this.nonIsiPublication.firstPage,
              lastPage: this.nonIsiPublication.lastPage,
              yearPublished: this.nonIsiPublication.yearPublished,
              month: this.nonIsiPublication.month,
              funding: fundingsName1,
              comments: this.nonIsiPublication.comments,
              progressReport: this.nonIsiPublication.progressReport,
            };
            await axios.put(`api/nonIsiPublications/${this.id}`, publication).then((result) => {
              this.buttonDisable = true;
              this.buttonText = 'Sending...';
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
                const formData = new FormData();
                formData.append('id', this.id);
                formData.append('file', this.nonIsiPublication.file);
                axios.post('api/nonIsi/addFile', formData, {
                    headers: { 'Content-Type' : 'multipart/form-data' }
                  }).then( response => {
                    console.log(response.data);
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
                })
                .catch((error)=> {
                  if (error.response.status == 422){
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
                });
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
      onInput(event) {
        const input = event.target;
        // Limitar el año a 4 dígitos
        this.nonIsiPublication.yearPublished = input.value.slice(0, 4);
      },
      cerrarModal(){
        const elem = this.$refs.closeBtn;
        this.$emit('recarga');
        elem.click();
      },
      capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
      },
      async editarPublicacion() {
        this.errors = [];
        const itemsToSkip = [
        'comments',
        'volume',
        'firstPage',
        'lastPage',
        ];

        for (const item in this.nonIsiPublication) {
            const skipItem = itemsToSkip.includes(item);
            if (!skipItem && (this.nonIsiPublication[item] === "" || this.nonIsiPublication[item] === 0 || this.nonIsiPublication[item] == null)) {
                this.errors.push(item);
            }
        }

        let publication1 = {
            id: this.id,
            authors: this.nonIsiPublication.authors,
            indexedBy: indexedBy1,
            researcherInvolved: researcherInvolved1,
            articleTitle: this.nonIsiPublication.articleTitle,
            journalName: this.nonIsiPublication.journalName,
            volume: this.nonIsiPublication.volume,
            firstPage: this.nonIsiPublication.firstPage,
            lastPage: this.nonIsiPublication.lastPage,
            yearPublished: this.nonIsiPublication.yearPublished,
            funding: fundingsName1,
            comments: this.nonIsiPublication.comments,
            file: this.nonIsiPublication.file,
            progressReport: this.nonIsiPublication.progressReport,
          };

        var contador = await axios.post('../api/verifyNonIsi', publication1).then(function(response) {
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
            }else if(item == 'journalName'){
              mensaje =   mensaje + "The field Journal Name is required" + "\n";
            }else if(item == 'firstPage'){
              mensaje =   mensaje + "The field First Page is required" + "\n";
            }else if(item == 'lastPage'){
              mensaje =   mensaje + "The field Last Page is required" + "\n";
            }else if(item == 'yearPublished'){
              mensaje =   mensaje + "The field Year Published is required" + "\n";
            }else if(item == 'indexedBy'){
              mensaje =   mensaje + "The field Indexed By is required" + "\n";
            }else if(item == 'researcherInvolved'){
              mensaje =   mensaje + "The field Researcher Involved is required" + "\n";
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
            okButton: 'Edit',
            cancelButton: 'Return'
          })
          if (ok) {
            var fundingsName1 = "";
            if (this.nonIsiPublication.fundings !== null){
              if (this.nonIsiPublication.fundings.length !== 0) {
                this.nonIsiPublication.fundings.forEach((fundings, index) => {
                  fundingsName1 += fundings.name;
                  if (index === this.nonIsiPublication.fundings.length - 1) {
                    fundingsName1 += '.';
                  } else {
                    fundingsName1 += ', ';
                  }
                });
              }
            }

            var indexedBy1 = "";
            if (this.nonIsiPublication.indexedBy !== null){
              if (this.nonIsiPublication.indexedBy.length !== 0) {
                this.nonIsiPublication.indexedBy.forEach((indexedBy, index) => {
                  indexedBy1 += indexedBy.name;
                  if (index === this.nonIsiPublication.indexedBy.length - 1) {
                    indexedBy1 += '.';
                  } else {
                    indexedBy1 += ', ';
                  }
                });
              }
            }

            var researcherInvolved1 = "";
            if (this.nonIsiPublication.researcherInvolved !== null){
              if (this.nonIsiPublication.researcherInvolved.length !== 0) {
                this.nonIsiPublication.researcherInvolved.forEach((researcherInvolved, index) => {
                  researcherInvolved1 += researcherInvolved.name;
                  if (index === this.nonIsiPublication.researcherInvolved.length - 1) {
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

            let publication = {
              idUsuario: idUser1,
              status: 'Finished',
              authors: this.nonIsiPublication.authors,
              indexedBy: indexedBy1,
              researcherInvolved: researcherInvolved1,
              articleTitle: this.nonIsiPublication.articleTitle,
              journalName: this.nonIsiPublication.journalName,
              volume: this.nonIsiPublication.volume,
              firstPage: this.nonIsiPublication.firstPage,
              lastPage: this.nonIsiPublication.lastPage,
              yearPublished: this.nonIsiPublication.yearPublished,
              month: this.nonIsiPublication.month,
              funding: fundingsName1,
              comments: this.nonIsiPublication.comments,
              file: this.nonIsiPublication.file,
              progressReport: this.nonIsiPublication.progressReport,
            };
            await axios.put(`api/nonIsiPublications/${this.id}`, publication).then((result) => {
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
              const formData = new FormData();
              formData.append('id', this.id);
              formData.append('file', this.nonIsiPublication.file);
              axios.post('api/nonIsi/addFile', formData, {
                  headers: { 'Content-Type' : 'multipart/form-data' }
                }).then( response => {
                  console.log(response.data);
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
              })
              .catch((error)=> {
                if (error.response.status == 422){
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
              });
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
