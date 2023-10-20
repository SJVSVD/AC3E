<template>
  <transition name="modalcrear">
    <div name="modal">
      <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container-s">
              <div class="modal-header pb-1 fw-bold" style="color: #444444;">
                <slot name="header">
                    New Book
                </slot>
                <label for="">Progress year: {{ book.progressReport }}</label>
                <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
              </div>
              <div class="modal-body">
                <slot name="body">
                    <div class="row">
                          <div class="col-4">
                            <label for="">Work Type: </label>
                            <label for="" style="color: orange;">*</label>
                            <br>
                            <select class="form-select" v-model="book.workType">
                              <option disabled value="">Select an option</option>
                              <option value="Book (Whole)">Book (Whole)</option>
                              <option value="Book Chapter">Book Chapter</option>
                              </select>
                          </div>
                          <div class="col-4">
                            <label for="">Book Title:</label>
                            <label for="" style="color: orange;">*</label>
                            <br>
                            <input type="text" class= "form-control" v-model="book.bookTitle">
                          </div>
                          <div class="col-4">
                            <label for="">Chapter Title:</label>
                            <label for="" style="color: orange;">*</label>
                            <br>
                            <input type="text" class= "form-control" v-model="book.chapterTitle">
                          </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-3">
                        <label for="">First Page:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="book.firstPage">
                      </div>
                      <div class="col-3">
                        <label for="">Last Page:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="book.lastPage">
                      </div>
                      <div class="col-3">
                        <label for="">Editorial/City/Country:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="book.editorialCityCountry">
                      </div>
                      <div class="col-3">
                        <label for="">Year:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input id="yearInput" type="number" v-model="book.year" :max="currentYear" @input="onInput" class= "form-control" />
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-6">
                        <div>
                          <label for="">Name of Research Line: </label>
                          <label for="" style="color: orange;">*</label>
                          <br>
                          <Multiselect
                              placeholder="Select the options"
                              v-model="book.nameOfResearchLine"
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
                        <label for="">ISBN:</label>
                        <br>
                        <input type="text" class= "form-control" v-model="book.ISBN">
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-4">
                        <label for="">Editors:</label>
                        <br>
                        <input type="text" class= "form-control" v-model="book.editors">
                      </div>
                      <div class="col-4">
                        <label for="">Book Authors:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="book.bookAuthors">
                      </div>
                      <div class="col-4">
                        <label for="">Chapter Authors:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="book.chapterAuthors">
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-6">
                        <label for="">Comments:</label>
                        <br>
                        <input type="text" class= "form-control" v-model="book.comments">
                      </div>
                    </div>
                  </slot>
                </div>
                <div class="modal-footer">
                  <slot name="footer">
                    <label class="form-check-label"><input type="checkbox" class="form-check-"
                    v-model="draft"> Save as a draft</label>
                    <a v-if="draft == false" class="btn btn-continue float-end" @click="createBook()" :disabled="buttonDisable">
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
      book:{
        workType: '',
        bookAuthors: '',
        chapterAuthors: '',
        bookTitle: '',
        chapterTitle: '',
        firstPage: '',
        lastPage: '',
        editorialCityCountry: '',
        year: '',
        ISBN: '',
        editors: '',
        nameOfResearchLine: null,
        progressReport: '',
        comments: '',
      },
      options1: [
        'Biomedical Systems',
        'Control and Automation',
        'Data Analytics and Artificial Intelligence',
        'Electrical Systems',
        'Energy Conversion and Power Systems',
        'Instrumentation',
      ],
      showModalBookAutor: false,
      showModalChapterAutor: false,
      bookAuthors1:[],
      chapterAuthors1:[],
      currentYear: new Date().getFullYear(),
      coauthor: false,
      draft: false,
      buttonDisable: false,
      errors:[],
      buttonText:'Save Book',
    }),
    created(){
      this.getProgressReport();
    },
    methods: {
      getProgressReport(){
        axios.get('api/showProgressReport').then( response =>{
            this.book.progressReport = response.data;
        }).catch(e=> console.log(e))
      },
      async guardarBorrador(){
        const ok = await this.$refs.confirmation.show({
            title: 'Save draft',
            message: `¿Are you sure you want to save this book as a draft? this action cannot be undone.`,
            okButton: 'Save',
            cancelButton: 'Return'
          })
          if (ok) {
            var nameOfResearchLine1 = "";
            if (this.book.nameOfResearchLine !== null){
              if (this.book.nameOfResearchLine.length !== 0) {
                this.book.nameOfResearchLine.forEach((nameOfResearchLine, index) => {
                  nameOfResearchLine1 += nameOfResearchLine.name;
                  if (index === this.book.nameOfResearchLine.length - 1) {
                    nameOfResearchLine1 += '.';
                  } else {
                    nameOfResearchLine1 += ', ';
                  }
                });
              }
            }

            let book = {
              status: 'Draft',
              workType: this.book.workType,
              centerResearcher: this.userID,
              bookAuthors: this.book.bookAuthors,
              chapterAuthors: this.book.chapterAuthors,
              editors: this.book.editors,
              ISBN: this.book.ISBN,
              bookTitle: this.book.bookTitle,
              chapterTitle: this.book.chapterTitle,
              firstPage: this.book.firstPage,
              lastPage: this.book.lastPage,
              editorialCityCountry: this.book.editorialCityCountry,
              year: this.book.year,
              nameOfResearchLine: nameOfResearchLine1,
              progressReport: this.book.progressReport,
              comments: this.book.comments,
            };
            axios.post("api/books", book ).then((result) => {
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
      onInput(event) {
        const input = event.target;
        // Limitar el año a 4 dígitos
        this.book.year = input.value.slice(0, 4);
      },
      cerrarModal(){
        const elem = this.$refs.closeBtn;
        this.$emit('recarga');
        elem.click();
      },
      capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
      },
      async createBook() {
        this.errors = [];
        var noAutor = false;

        const itemsToSkip = [
          'ISBN',
          'editors',
          'comments'
        ];

        for (const item in this.book) {
            const skipItem = itemsToSkip.includes(item);
            if (!skipItem && (this.book[item] === "" || this.book[item] === 0 || this.book[item] == null)) {
                if (item === 'chapterAuthors' || item === 'bookAuthors') {
                    if (this.book.bookAuthors === '' && this.book.chapterAuthors === '' && noAutor === false) {
                        this.errors.push('noAutor');
                        noAutor = true;
                    }
                } else {
                    this.errors.push(item);
                }
            }
        }

        var mensaje = ""
        if (this.errors.length != 0){
          this.errors.forEach(item => {
            if(item == 'workType'){
              mensaje =   mensaje + "The field Work Type is required" + "\n";
            }else if(item == 'noAutor'){
              mensaje =   mensaje + "Must enter at least one author" + "\n";
            }else if(item == 'bookTitle'){
              mensaje =   mensaje + "The field Book Title is required" + "\n";
            }else if(item == 'chapterTitle'){
              mensaje =   mensaje + "The field Chapter Title is required" + "\n";
            }else if(item == 'editorialCityCountry'){
              mensaje =   mensaje + "The field Editorial/City/Country is required" + "\n";
            }else if(item == 'nameOfResearchLine'){
              mensaje =   mensaje + "The field Name of Research Line is required" + "\n";
            }else if(item == 'progressReport'){
              mensaje =   mensaje + "The field Progress Report is required" + "\n";
            }else if(item == 'firstPage'){
              mensaje =   mensaje + "The field First Page is required" + "\n";
            }else if(item == 'lastPage'){
              mensaje =   mensaje + "The field Last Page is required" + "\n";
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
            title: 'Save Book',
            message: `¿Are you sure you want to save this book? This action cannot be undone.`,
            okButton: 'Save',
            cancelButton: 'Return'
          })
          if (ok) {
            var nameOfResearchLine1 = "";
            if (this.book.nameOfResearchLine !== null){
              if (this.book.nameOfResearchLine.length !== 0) {
                this.book.nameOfResearchLine.forEach((nameOfResearchLine, index) => {
                  nameOfResearchLine1 += nameOfResearchLine.name;
                  if (index === this.book.nameOfResearchLine.length - 1) {
                    nameOfResearchLine1 += '.';
                  } else {
                    nameOfResearchLine1 += ', ';
                  }
                });
              }
            }


            let book = {
              status: 'Finished',
              workType: this.book.workType,
              centerResearcher: this.userID,
              bookAuthors: this.book.bookAuthors,
              chapterAuthors: this.book.chapterAuthors,
              editors: this.book.editors,
              ISBN: this.book.ISBN,
              bookTitle: this.book.bookTitle,
              chapterTitle: this.book.chapterTitle,
              firstPage: this.book.firstPage,
              lastPage: this.book.lastPage,
              editorialCityCountry: this.book.editorialCityCountry,
              year: this.book.year,
              nameOfResearchLine: nameOfResearchLine1,
              progressReport: this.book.progressReport,
              comments: this.book.comments,
            };
            axios.post("api/books", book ).then((result) => {
              this.buttonDisable = true;
              this.buttonText = 'Saving...';
              this.toast.success("Book saved successfully!", {
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