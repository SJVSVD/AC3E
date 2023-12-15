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
                <label for="">Progress year: {{ book.progressReport }} &nbsp;&nbsp; <a class="btn" @click="showModalProgress = true"><i class="fa-solid fa-pen-to-square"></i></a></label>
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
                              <option disabled :value="null">Select an option</option>
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
                            <label v-if="book.workType == 'Book Chapter'" for="" style="color: orange;">*</label>
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
                        <select class="form-select" id="selectYear" v-model="book.year">
                          <option disabled :value="null">Select a year</option>
                          <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
                        </select>
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
                        <label v-if="book.workType == 'Book Chapter'" for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="book.chapterAuthors">
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-6">
                        <label for="">ISBN:</label>
                        <br>
                        <input type="text" class= "form-control" v-model="book.ISBN">
                      </div>
                      <div class="col-6">
                        <label for="">Comments:</label>
                        <br>
                        <input type="text" class= "form-control" v-model="book.comments">
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-6">
                        <label for="">Researcher involved:</label>
                        <label for="" style="color: orange;">*</label>
                        <Multiselect
                          placeholder="Select the participants"
                          v-model="book.researcherInvolved"
                          limit=4
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
                  </slot>
                </div>
                <div class="modal-footer">
                  <slot name="footer">
                    <label class="form-check-label"><input type="checkbox" class="form-check-"
                    v-model="draft"> Edit as a draft</label>
                    <a v-if="draft == false" class="btn btn-continue float-end" @click="editBook()" :disabled="buttonDisable">
                      {{ buttonText }}
                    </a>
                    <a v-else class="btn btn-continue float-end" @click="guardarBorrador()" :disabled="buttonDisable">
                      Edit draft
                    </a>
                  </slot>
                </div>
                <modalconfirmacion ref="confirmation"></modalconfirmacion>
                <modalalerta ref="alert"></modalalerta>
                <modalProgressYear v-bind:progressYear="book.progressReport" v-if="showModalProgress" @close="showModalProgress = false" @submit="handleFormSubmit1"></modalProgressYear>
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
    components: { modalProgressYear,modalconfirmacion, modalalerta, Multiselect},
    mixins: [mixin],
    data: () => ({
      book:{
        workType: '',
        bookAuthors: '',
        chapterAuthors: '',
        bookTitle: '',
        chapterTitle: '',
        researcherInvolved: null,
        firstPage: '',
        lastPage: '',
        editorialCityCountry: '',
        year: '',
        ISBN: '',
        editors: '',
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
      researchers:[],
      chapterAuthors1:[],
      currentYear: new Date().getFullYear(),
      coauthor: false,
      draft: false,
      showModalProgress: false,
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
      this.book.ISBN = this.book1.ISBN;
      this.book.editors = this.book1.editors;
      this.book.bookAuthors = this.book1.bookAuthors;
      this.book.chapterAuthors = this.book1.chapterAuthors;
      this.book.comments = this.book1.comments;

      if (this.book1.researcherInvolved != null) {
          const valoresSeparados1 = this.book1.researcherInvolved.split(",");
          this.book.researcherInvolved = valoresSeparados1.map((valor, index) => {
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
      this.getUsuarios();
    },
    methods: {
      getUsuarios(){
        axios.get('api/researchers').then( response =>{
            this.researchers = response.data;
        }).catch(e=> console.log(e))
      },
      handleFormSubmit1(year) {
        this.book.progressReport = year;
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
      async guardarBorrador(){
        const ok = await this.$refs.confirmation.show({
            title: 'Edit draft',
            message: `¿Are you sure you want to edit this book as a draft? this action cannot be undone.`,
            okButton: 'Edit',
            cancelButton: 'Return'
          })
          if (ok) {

            var peopleInvolved1 = "";
            if (this.book.researcherInvolved !== null){
              if (this.book.researcherInvolved.length !== 0) {
                this.book.researcherInvolved.forEach((researcherInvolved, index) => {
                  peopleInvolved1 += researcherInvolved.name;
                  if (index === this.book.researcherInvolved.length - 1) {
                    peopleInvolved1 += '.';
                  } else {
                    peopleInvolved1 += ', ';
                  }
                });
              }
            }

            let book = {
              status: 'Draft',
              researcherInvolved: peopleInvolved1,
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
              progressReport: this.book.progressReport,
              comments: this.book.comments,
            };
            axios.put(`api/books/${this.id}`, book).then((result) => {
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
      async editBook() {
        this.errors = [];

        const itemsToSkip = [
          'ISBN',
          'editors',
          'comments',
          'chapterTitle',
          'chapterAuthors',
          'firstPage',
          'lastPage'
        ];

        for (const item in this.book) {
            const skipItem = itemsToSkip.includes(item);
            if (!skipItem && (this.book[item] === "" || this.book[item] === 0 || this.book[item] == null)) {
              this.errors.push(item);
            }
        }

        if(this.book.workType == 'Book Chapter' && (this.book.chapterTitle == '' || this.book.chapterTitle == null)){
          this.errors.push('chapterTitle');
        }

        if(this.book.workType == 'Book Chapter' && (this.book.chapterAuthors == '' || this.book.chapterAuthors == null)){
          this.errors.push('chapterAuthors');
        }

        let book1 = {
          id: this.id,
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
          progressReport: this.book.progressReport,
          comments: this.book.comments,
        };

        var contador = await axios.post('../api/verifyBook', book1).then(function(response) {
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
            if(item == 'workType'){
              mensaje =   mensaje + "The field Work Type is required" + "\n";
            }else if(item == 'bookAuthors'){
              mensaje =   mensaje + "The field Book Authors is required" + "\n";
            }else if(item == 'bookTitle'){
              mensaje =   mensaje + "The field Book Title is required" + "\n";
            }else if(item == 'chapterTitle'){
              mensaje =   mensaje + "The field Chapter Title is required" + "\n";
            }else if(item == 'chapterAuthors'){
              mensaje =   mensaje + "The field Chapter Authors is required" + "\n";
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
            title: 'Edit Book',
            message: `¿Are you sure you want to edit this book? This action cannot be undone.`,
            okButton: 'Edit',
            cancelButton: 'Return'
          })
          if (ok) {

            var peopleInvolved1 = "";
            if (this.book.researcherInvolved !== null){
              if (this.book.researcherInvolved.length !== 0) {
                this.book.researcherInvolved.forEach((researcherInvolved, index) => {
                  peopleInvolved1 += researcherInvolved.name;
                  if (index === this.book.researcherInvolved.length - 1) {
                    peopleInvolved1 += '.';
                  } else {
                    peopleInvolved1 += ', ';
                  }
                });
              }
            }

            let book = {
              status: 'Finished',
              researcherInvolved: peopleInvolved1,
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
              progressReport: this.book.progressReport,
              comments: this.book.comments,
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
