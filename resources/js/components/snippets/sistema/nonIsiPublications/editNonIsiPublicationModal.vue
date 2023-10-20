<template>
  <transition name="modalcrear">
    <div name="modal">
      <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container-s">
              <div class="modal-header pb-1 fw-bold" style="color: #444444;">
                <slot name="header">
                    Edit Publication
                </slot>
                <label for="">Progress year: {{ nonIsiPublication.progressReport }}</label>
                <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
              </div>
              <div class="modal-body">
                <slot name="body">
                    <div class="row">
                          <div class="col-5">
                              <label for="">Author(s): </label>
                              <label for="" style="color: orange;">*</label>
                              <br>
                              <input title="First Name and Last Name." type="text" class= "form-control" v-model="nonIsiPublication.authors">
                          </div>
                          <div class="col-3">
                            <br>
                            <div class="form-check pt-2 ">
                              <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                    v-model="coauthor">
                                    ¿AC3E co-author? </label>
                            </div>
                          </div>
                          <div class="col-4">
                              <label for="">Coauthor(s):</label>
                              <br>
                              <input type="text" :disabled="coauthor == false" class= "form-control" v-model="nonIsiPublication.coauthor">
                          </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-6">
                        <label for="">Article Title:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="nonIsiPublication.articleTitle">
                      </div>
                      <div class="col-6">
                        <label for="">Journal Name:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="nonIsiPublication.journalName">
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-3">
                        <label for="">Volume:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="nonIsiPublication.volume">
                      </div>
                      <div class="col-3">
                        <label for="">First Page:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="nonIsiPublication.firstPage">
                      </div>
                      <div class="col-3">
                        <label for="">Last Page:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="nonIsiPublication.lastPage">
                      </div>
                      <div class="col-3">
                        <label for="">Year Published:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input id="yearInput" type="number" v-model="nonIsiPublication.yearPublished" :max="currentYear" @input="onInput" class= "form-control" />
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
                      <div class="col-6">
                        <label for="">Comments:</label>
                        <br>
                        <input type="text" class= "form-control" v-model="nonIsiPublication.comments">
                      </div>
                    </div>
                    <hr size="3" class="separador">
                    <div class="row">
                      <div class="text-uppercase pb-2">Participations:</div>
                      <div class="col-4">
                          <div class="form-check pt-2 ">
                            <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                  v-model="nonIsiPublication.mainResearchers"> Main researchers</label>
                          </div>
                      </div>
                      <div class="col-4">
                          <div class="form-check pt-2 ">
                            <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                  v-model="nonIsiPublication.associativeResearchers"> Associative researchers</label>
                          </div>
                      </div>
                      <div class="col-4">
                          <div class="form-check pt-2 ">
                            <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                  v-model="nonIsiPublication.postDoc"> Post doc</label>
                          </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-4">
                          <div class="form-check pt-2 ">
                            <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                  v-model="nonIsiPublication.thesisStudents"> Thesis students </label>
                          </div>
                      </div>
                      <div class="col-4">
                          <div class="form-check pt-2 ">
                            <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                  v-model="nonIsiPublication.nationalExternalResearchers"> National external researchers </label>
                          </div>
                      </div>
                      <div class="col-4">
                          <div class="form-check pt-2 ">
                            <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                  v-model="nonIsiPublication.internationalExternalResearchers"> Inter. external researchers </label>
                          </div>
                      </div>
                    </div>
                  </slot>
                </div>
                <div class="modal-footer">
                  <slot name="footer">
                    <label class="form-check-label"><input type="checkbox" class="form-check-"
                    v-model="draft"> Save as a draft</label>
                    <a v-if="draft == false" class="btn btn-continue float-end" @click="editarPublicacion()" :disabled="buttonDisable">
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
    components: { modalconfirmacion, modalalerta, Multiselect },
    mixins: [mixin],
    data: () => ({
      nonIsiPublication:{
        authors: "",
        coauthor: "",
        articleTitle: "",
        journalName: "",
        volume: "",
        firstPage: "",
        lastPage: "",
        fundings: null,
        mainResearchers: false,
        associativeResearchers: false,
        postDoc: false,
        thesisStudents: false,
        nationalExternalResearchers: false,
        internationalExternalResearchers: false,
        yearPublished: "",
        comments: "",
        progressReport: "",
      },
      options1: [
        'Basal Financing Program Funding',
        'Other sources (Specify in comments)',
      ],
      id: null,
      coauthor: false,
      draft: false,
      currentYear: new Date().getFullYear(),
      buttonDisable: false,
      errors:[],
      buttonText:'Edit Publication',
    }),
    props:{
      nonIsiPublication1: Object,
    },
    created(){
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

      this.nonIsiPublication.comments = this.nonIsiPublication1.comments;
      this.nonIsiPublication.mainResearchers = this.nonIsiPublication1.mainResearchers;
      this.nonIsiPublication.associativeResearchers = this.nonIsiPublication1.associativeResearchers;
      this.nonIsiPublication.postDoc = this.nonIsiPublication1.postDoc;
      this.nonIsiPublication.thesisStudents = this.nonIsiPublication1.thesisStudents;
      this.nonIsiPublication.nationalExternalResearchers = this.nonIsiPublication1.nationalExternalResearchers;
      this.nonIsiPublication.internationalExternalResearchers = this.nonIsiPublication1.internationalExternalResearchers;
      this.nonIsiPublication.progressReport = this.nonIsiPublication1.progressReport;    
    },
    methods: {
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
            let publication = {
              status: 'Draft',
              authors: this.nonIsiPublication.authors,
              coauthor: this.nonIsiPublication.coauthor,
              articleTitle: this.nonIsiPublication.articleTitle,
              journalName: this.nonIsiPublication.journalName,
              volume: this.nonIsiPublication.volume,
              firstPage: this.nonIsiPublication.firstPage,
              lastPage: this.nonIsiPublication.lastPage,
              yearPublished: this.nonIsiPublication.yearPublished,
              funding: fundingsName1,
              mainResearchers: this.nonIsiPublication.mainResearchers,
              associativeResearchers: this.nonIsiPublication.associativeResearchers,
              postDoc: this.nonIsiPublication.postDoc,
              thesisStudents: this.nonIsiPublication.thesisStudents,
              nationalExternalResearchers: this.nonIsiPublication.nationalExternalResearchers,
              internationalExternalResearchers: this.nonIsiPublication.internationalExternalResearchers,
              comments: this.nonIsiPublication.comments,
            };
            axios.put(`api/nonIsiPublications/${this.id}`, publication).then((result) => {
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
          'mainResearchers',
          'associativeResearchers',
          'postDoc',
          'thesisStudents',
          'nationalExternalResearchers',
          'internationalExternalResearchers',
          'progressReport',
          'comments'
        ];

        for (const item in this.nonIsiPublication) {
            const skipItem = itemsToSkip.includes(item);
            if (!skipItem && (this.nonIsiPublication[item] === "" || this.nonIsiPublication[item] === 0 || this.nonIsiPublication[item] == null)) {
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
            title: 'Edit Publication',
            message: `¿Are you sure you want to edit this publication? This action cannot be undone.`,
            okButton: 'Send',
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
            let publication = {
              status: 'Finished',
              authors: this.nonIsiPublication.authors,
              coauthor: this.nonIsiPublication.coauthor,
              articleTitle: this.nonIsiPublication.articleTitle,
              journalName: this.nonIsiPublication.journalName,
              volume: this.nonIsiPublication.volume,
              firstPage: this.nonIsiPublication.firstPage,
              lastPage: this.nonIsiPublication.lastPage,
              yearPublished: this.nonIsiPublication.yearPublished,
              funding: fundingsName1,
              mainResearchers: this.nonIsiPublication.mainResearchers,
              associativeResearchers: this.nonIsiPublication.associativeResearchers,
              postDoc: this.nonIsiPublication.postDoc,
              thesisStudents: this.nonIsiPublication.thesisStudents,
              nationalExternalResearchers: this.nonIsiPublication.nationalExternalResearchers,
              internationalExternalResearchers: this.nonIsiPublication.internationalExternalResearchers,
              comments: this.nonIsiPublication.comments,
            };
            axios.put(`api/nonIsiPublications/${this.id}`, publication).then((result) => {
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
