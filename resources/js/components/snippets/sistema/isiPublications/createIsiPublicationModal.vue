<template>
  <transition name="modalcrear">
    <div name="modal">
      <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container-s">
              <div class="modal-header pb-1 fw-bold" style="color: #444444;">
                <slot name="header">
                    New Publication
                </slot>
                <label for="">Progress year: {{ isiPublication.progressReport }}</label>
                <label v-if="is('Administrador')" class="col-4 m-0"> Researcher: <label class="fw-normal" style="font-size: 14px;">
                  <select class="form-select" v-model="idResearcher">
                    <option disabled value="">Select a researcher</option>
                    <option v-for="researcher in researchers" v-bind:key="researcher.id" v-bind:value="researcher.id">
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
                      <div class="col-4">
                        <label for="">Digital Object Identifier (DOI):</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="isiPublication.doi">
                      </div>
                      <div class="col-1 pt-2">
                        <br>
                        <a class="btn btn-search-blue" @click="useDOI()"><i class="fa-solid fa-magnifying-glass"></i></a>
                      </div>
                    </div>
                    <br>
                    <div class="row">
                          <div class="col-5">
                              <label for="">Author(s): </label>
                              <label for="" style="color: orange;">*</label>
                              <br>
                              <input title="First Name and Last Name." type="text" class= "form-control" v-model="isiPublication.authors">
                          </div>
                          <div class="col-3">
                            <br>
                            <div class="form-check pt-2 ">
                              <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                    v-model="coauthor" @change="isiPublication.coauthor = ''">
                                    ¿AC3E co-author? </label>
                            </div>
                          </div>
                          <div class="col-4">
                              <label for="">Coauthor(s):</label>
                              <br>
                              <input type="text" :disabled="coauthor == false" class= "form-control" v-model="isiPublication.coauthor">
                          </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-6">
                        <label for="">Article Title:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="isiPublication.articleTitle">
                      </div>
                      <div class="col-6">
                        <label for="">Journal Name:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="isiPublication.journalName">
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-3">
                        <label for="">Volume:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="isiPublication.volume">
                      </div>
                      <div class="col-3">
                        <label for="">First Page:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="isiPublication.firstPage">
                      </div>
                      <div class="col-3">
                        <label for="">Last Page:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="isiPublication.lastPage">
                      </div>
                      <div class="col-3">
                        <label for="">Year Published:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input id="yearInput" type="number" v-model="isiPublication.yearPublished" :max="currentYear" @input="onInput" class= "form-control" />
                      </div>
                    </div>
                    <br>
                    <div class="row">                        
                      <div class="col-6">
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
                      <div class="col-3">
                        <label for="">Sending Date:</label>
                        <br>
                        <input type="date" class= "form-control" v-model="isiPublication.sendingDate">
                      </div>
                      <div class="col-3">
                        <label for="">Acceptance Date:</label>
                        <br>
                        <input type="date" class= "form-control" v-model="isiPublication.acceptanceDate">
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-6">
                        <label for="">Comments:</label>
                        <br>
                        <input type="text" class= "form-control" v-model="isiPublication.comments">
                      </div>
                    </div>
                    <hr size="3" class="separador">
                    <div class="row">
                      <div class="text-uppercase pb-2">Participations:</div>
                      <div class="col-4">
                          <div class="form-check pt-2 ">
                            <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                  v-model="isiPublication.mainResearchers"> Main researchers</label>
                          </div>
                      </div>
                      <div class="col-4">
                          <div class="form-check pt-2 ">
                            <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                  v-model="isiPublication.associativeResearchers"> Associative researchers</label>
                          </div>
                      </div>
                      <div class="col-4">
                          <div class="form-check pt-2 ">
                            <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                  v-model="isiPublication.postDoc"> Post doc</label>
                          </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-4">
                          <div class="form-check pt-2 ">
                            <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                  v-model="isiPublication.thesisStudents"> Thesis students </label>
                          </div>
                      </div>
                      <div class="col-4">
                          <div class="form-check pt-2 ">
                            <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                  v-model="isiPublication.nationalExternalResearchers"> National external researchers </label>
                          </div>
                      </div>
                      <div class="col-4">
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
import modalconfirmacion from '../../sistema/alerts/confirmationModal.vue'
import modalalerta from '../../sistema/alerts/alertModal.vue'
import {mixin} from '../../../../mixins.js'
import Multiselect from '@vueform/multiselect';

export default {
    components: { Multiselect, modalconfirmacion, modalalerta },
    mixins: [mixin],
    data: () => ({
      isiPublication:{
        authors: "",
        coauthor: "",
        articleTitle: "",
        journalName: "",
        doi: "",
        volume: "",
        firstPage: "",
        lastPage: "",
        yearPublished: "",
        fundings: null,
        mainResearchers: false,
        associativeResearchers: false,
        postDoc: false,
        thesisStudents: false,
        nationalExternalResearchers: false,
        internationalExternalResearchers: false,
        sendingDate: "",
        acceptanceDate: "",
        comments: "",
        progressReport: "",
      },
      options1: [
        'Basal Financing Program Funding',
        'Other sources',
      ],
      currentYear: new Date().getFullYear(),
      coauthor: false,
      draft: false,
      buttonDisable: false,
      idResearcher: '',
      researchers: [],
      errors:[],
      buttonText:'Send Publication',
    }),
    mounted(){
      this.getUsuarios();
    },
    created(){
      this.getProgressReport();
    },
    methods: {
      getUsuarios(){
        axios.get('api/usuarios').then( response =>{
            this.researchers = response.data;
        }).catch(e=> console.log(e))
      },
      getProgressReport(){
        axios.get('api/showProgressReport').then( response =>{
            this.isiPublication.progressReport = response.data;
        }).catch(e=> console.log(e))
      },
      async guardarBorrador(){
        const ok = await this.$refs.confirmation.show({
            title: 'Save draft',
            message: `¿Are you sure you want to save this publication as a draft? this action cannot be undone.`,
            okButton: 'Save',
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
              status: 'Draft',
              idUsuario: idUser1,
              authors: this.isiPublication.authors,
              coauthor: this.isiPublication.coauthor,
              articleTitle: this.isiPublication.articleTitle,
              journalName: this.isiPublication.journalName,
              doi: this.isiPublication.doi,
              volume: this.isiPublication.volume,
              firstPage: this.isiPublication.firstPage,
              lastPage: this.isiPublication.lastPage,
              yearPublished: this.isiPublication.yearPublished,
              funding: fundingsName1,
              mainResearchers: this.isiPublication.mainResearchers,
              associativeResearchers: this.isiPublication.associativeResearchers,
              postDoc: this.isiPublication.postDoc,
              thesisStudents: this.isiPublication.thesisStudents,
              nationalExternalResearchers: this.isiPublication.nationalExternalResearchers,
              internationalExternalResearchers: this.isiPublication.internationalExternalResearchers,
              progressReport: this.isiPublication.progressReport,
              sendingDate: this.isiPublication.sendingDate,
              acceptanceDate: this.isiPublication.acceptanceDate,
              comments: this.isiPublication.comments,
            };
            axios.post("api/isiPublications", publication ).then((result) => {
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
                this.isiPublication.authors = authorString;
                this.isiPublication.articleTitle = register.Title.Title[0];
                this.isiPublication.journalName = register.Source.SourceTitle[0];
                this.isiPublication.volume = register.Source.Volume[0];
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
      cerrarModal(){
        const elem = this.$refs.closeBtn;
        this.$emit('recarga');
        elem.click();
      },
      capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
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
        'comments'
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
            message: `¿Are you sure you want to send this publication? This action cannot be undone.`,
            okButton: 'Send',
            cancelButton: 'Return'
          })
          if (ok) {
            var fundingsName1 = "";
            this.isiPublication.fundings.forEach((fundings, index) => {
              fundingsName1 += fundings.name;
              if (index === this.isiPublication.fundings.length - 1) {
                fundingsName1 += '.';
              } else {
                fundingsName1 += ', ';
              }
            });
            let publication = {
              status: 'Finished',
              idUsuario: this.userID,
              authors: this.isiPublication.authors,
              coauthor: this.isiPublication.coauthor,
              articleTitle: this.isiPublication.articleTitle,
              journalName: this.isiPublication.journalName,
              doi: this.isiPublication.doi,
              volume: this.isiPublication.volume,
              firstPage: this.isiPublication.firstPage,
              lastPage: this.isiPublication.lastPage,
              yearPublished: this.isiPublication.yearPublished,
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
          }
        }
      },
    },
}
</script>

<style src="@vueform/multiselect/themes/default.css"></style>