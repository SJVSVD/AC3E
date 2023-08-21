<template>
  <transition name="modalcrear">
    <div name="modal">
      <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container-s">
              <div class="modal-header pb-1 fw-bold" style="color: #444444;">
                <slot name="header">
                    Edit Book 
                </slot>
                <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
              </div>
              <div class="modal-body">
                <slot name="body">
                    <div class="row">
                          <div class="col-4">
                            <label for="">Work Type: </label>
                            <br>
                            <select class="form-select" v-model="book.workType">
                              <option disabled :value="null">Select an option</option>
                              <option value="Book (Whole)">Book (Whole)</option>
                              <option value="Book Chapter">Book Chapter</option>
                              </select>
                          </div>
                          <div class="col-4">
                            <label for="">Book Title:</label>
                            <br>
                            <input type="text" class= "form-control" v-model="book.bookTitle">
                          </div>
                          <div class="col-4">
                            <label for="">Chapter Title:</label>
                            <br>
                            <input type="text" class= "form-control" v-model="book.chapterTitle">
                          </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-3">
                        <label for="">First Page:</label>
                        <br>
                        <input type="text" class= "form-control" v-model="book.firstPage">
                      </div>
                      <div class="col-3">
                        <label for="">Last Page:</label>
                        <br>
                        <input type="text" class= "form-control" v-model="book.lastPage">
                      </div>
                      <div class="col-3">
                        <label for="">Editorial/City/Country:</label>
                        <br>
                        <input type="text" class= "form-control" v-model="book.editorialCityCountry">
                      </div>
                      <div class="col-3">
                        <label for="">Year:</label>
                        <br>
                        <input id="yearInput" type="number" v-model="book.year" :max="currentYear" @input="onInput" class= "form-control" />
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-6">
                        <div>
                          <label for="">Name of Research Line: </label>
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
                        <label for="">Progress Report: </label>
                        <br>
                        <select class="form-select" v-model="book.progressReport">
                          <option disabled :value="null">Select an option</option>
                          <option value="1">1</option>
                          <option value="2">2</option>
                          <option value="3">3</option>
                          <option value="4">4</option>
                          <option value="5">5</option>
                          <option value="6">6</option>
                          <option value="7">7</option>
                          <option value="8">8</option>
                          <option value="9">9</option>
                          </select>
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-6 d-flex justify-content-center">
                        <a v-if="bookAuthors1.length > 0" class="btn btn-continue">{{ bookAuthors1.length }}</a>
                        &nbsp;
                        <a class="btn btn-search-blue" @click="showModalBookAutor = true"><i class="fa-solid fa-plus"></i> Add Book Author </a>
                      </div>
                      <div class="col-6 d-flex justify-content-center">
                        <a v-if="chapterAuthors1.length > 0" class="btn btn-continue">{{ chapterAuthors1.length }}</a>
                        &nbsp;
                        <a class="btn btn-search-blue" @click="showModalChapterAutor = true"><i class="fa-solid fa-plus"></i> Add Chapter Author </a>
                      </div>
                    </div>
                    <br>

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
                <modalBookAutor v-if="showModalBookAutor" @close="showModalBookAutor = false" @submit="handleFormSubmit1" ></modalBookAutor>
                <modalChapterAutor v-if="showModalChapterAutor" @close="showModalChapterAutor = false" @submit="handleFormSubmit2"></modalChapterAutor>
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
import modalBookAutor from './extraModals/createBookAutor.vue';
import modalChapterAutor from './extraModals/createChapterAutor.vue';
import {mixin} from '../../../../mixins.js'
import Multiselect from '@vueform/multiselect';

export default {
    components: { modalconfirmacion, modalalerta, Multiselect, modalBookAutor, modalChapterAutor },
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
        nameOfResearchLine: null,
        progressReport: '',
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
      id:'',
      errors:[],
      buttonText:'Edit Book',
    }),
    props:{
      book1: Object,
    },
    created(){
      this.id = this.book1.id;
      this.book.workType = this.book1.workType;
      this.book.bookAuthors = this.book1.bookAuthors;
      this.book.chapterAuthors = this.book1.chapterAuthors;
      this.book.bookTitle = this.book1.bookTitle;
      this.book.chapterTitle = this.book1.chapterTitle;
      this.book.firstPage = this.book1.firstPage;
      this.book.lastPage = this.book1.lastPage;
      this.book.editorialCityCountry = this.book1.editorialCityCountry;
      this.book.year = this.book1.year;
      this.book.progressReport = this.book1.progressReport;

      if (this.book1.nameOfResearchLine != null) {
          const valoresSeparados1 = this.book1.nameOfResearchLine.split(",");
          this.book.nameOfResearchLine = valoresSeparados1.map((valor, index) => {
              valor = valor.trim();
              if (valor.endsWith('.')) {
                  valor = valor.slice(0, -1);
              }

              return { value: valor, name: valor };
          });
      }

      if(this.book1.bookAuthors != null){
        const valoresSeparados1 = this.book1.bookAuthors.split(",");
        this.bookAuthors1 = valoresSeparados1.slice(0, -1).map((valor, index) => {
          return { name: valor };
        });
      }

      if(this.book1.chapterAuthors != null){
        const valoresSeparados1 = this.book1.chapterAuthors.split(",");
        this.chapterAuthors1 = valoresSeparados1.slice(0, -1).map((valor, index) => {
          return { name: valor };
        });
      }
    },
    methods: {
      handleFormSubmit2(formData) {
        this.chapterAuthors1.push(formData);
      },
      handleFormSubmit1(formData) {
        this.bookAuthors1.push(formData);
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

            var bookAuthors = '';
            var chapterAuthors = '';

            if(this.bookAuthors1.length != 0){
              this.bookAuthors1.forEach((autor, index) => {
                bookAuthors += autor.name;
                  if (index === this.bookAuthors1.length - 1) {
                    bookAuthors += '.';
                  } else {
                    bookAuthors += ', ';
                  }
              });
            }

            if(this.chapterAuthors1.length != 0){
              this.bookAuthors1.forEach((autor, index) => {
                chapterAuthors += autor.name;
                  if (index === this.chapterAuthors1.length - 1) {
                    chapterAuthors += '.';
                  } else {
                    chapterAuthors += ', ';
                  }
              });
            }

            let book = {
              status: 'Draft',
              workType: this.book.workType,
              centerResearcher: this.userID,
              bookAuthors: bookAuthors,
              chapterAuthors: chapterAuthors,
              bookTitle: this.book.bookTitle,
              chapterTitle: this.book.chapterTitle,
              firstPage: this.book.firstPage,
              lastPage: this.book.lastPage,
              editorialCityCountry: this.book.editorialCityCountry,
              year: this.book.year,
              nameOfResearchLine: nameOfResearchLine1,
              progressReport: this.book.progressReport,
            };
            axios.put(`api/books/${this.id}`, book).then((result) => {
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
      async createBook() {
        this.errors = [];
        var noAutor = false;
        for (const item in this.book){
          if(this.book[item] === "" || this.book[item] === 0 || this.book[item] == null){
            if(item == 'chapterAuthors'||item == 'bookAuthors'){
                if((this.bookAuthors1.length == 0 && this.chapterAuthors1.length == 0) && noAutor == false){
                  this.errors.push('noAutor');
                  noAutor = true
                }
            }else
              this.errors.push(item);
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

            var bookAuthors = '';
            var chapterAuthors = '';

            if(this.bookAuthors1.length != 0){
              this.bookAuthors1.forEach((autor, index) => {
                bookAuthors += autor.name;
                  if (index === this.bookAuthors1.length - 1) {
                    bookAuthors += '.';
                  } else {
                    bookAuthors += ', ';
                  }
              });
            }

            if(this.chapterAuthors1.length != 0){
              this.bookAuthors1.forEach((autor, index) => {
                chapterAuthors += autor.name;
                  if (index === this.chapterAuthors1.length - 1) {
                    chapterAuthors += '.';
                  } else {
                    chapterAuthors += ', ';
                  }
              });
            }

            let book = {
              status: 'Finished',
              workType: this.book.workType,
              centerResearcher: this.userID,
              bookAuthors: bookAuthors,
              chapterAuthors: chapterAuthors,
              bookTitle: this.book.bookTitle,
              chapterTitle: this.book.chapterTitle,
              firstPage: this.book.firstPage,
              lastPage: this.book.lastPage,
              editorialCityCountry: this.book.editorialCityCountry,
              year: this.book.year,
              nameOfResearchLine: nameOfResearchLine1,
              progressReport: this.book.progressReport,
            };
            this.buttonDisable = true;
            this.buttonText = 'Editing...';
            axios.put(`api/books/${this.id}`, book).then((result) => {
              this.toast.success("Book edited successfully!", {
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
