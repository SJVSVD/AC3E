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
                <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
              </div>
              <div class="modal-body">
                <slot name="body">
                    <div class="row">
                          <div class="col-5">
                              <label for="">Author(s): </label>
                              <br>
                              <input title="First Name and Last Name." type="text" class= "form-control" v-model="isiPublication.authors">
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
                              <input type="text" :disabled="coauthor == false" class= "form-control" v-model="isiPublication.coauthor">
                          </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-4">
                        <label for="">Article Title:</label>
                        <br>
                        <input type="text" class= "form-control" v-model="isiPublication.articleTitle">
                      </div>
                      <div class="col-3">
                        <label for="">Journal Name:</label>
                        <br>
                        <input type="text" class= "form-control" v-model="isiPublication.journalName">
                      </div>
                      <div class="col-4">
                        <label for="">Digital Object Identifier (DOI):</label>
                        <br>
                        <input type="text" class= "form-control" v-model="isiPublication.doi">
                      </div>
                      <div class="col-1 pt-2">
                        <br>
                        <a class="btn btn-search-blue" @click="filtrarFecha()"><i class="fa-solid fa-magnifying-glass"></i></a>
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-3">
                        <label for="">Volume:</label>
                        <br>
                        <input type="text" class= "form-control" v-model="isiPublication.volume">
                      </div>
                      <div class="col-3">
                        <label for="">First Page:</label>
                        <br>
                        <input type="text" class= "form-control" v-model="isiPublication.firstPage">
                      </div>
                      <div class="col-3">
                        <label for="">Last Page:</label>
                        <br>
                        <input type="text" class= "form-control" v-model="isiPublication.lastPage">
                      </div>
                      <div class="col-3">
                        <label for="">Year Published:</label>
                        <br>
                        <input id="yearInput" type="number" v-model="isiPublication.yearPublished" :max="currentYear" @input="onInput" class= "form-control" />
                      </div>
                    </div>
                  </slot>
                </div>
                <div class="modal-footer">
                  <slot name="footer">
                    <a class="btn btn-continue float-end" @click="editarPublicacion()" :disabled="buttonDisable">
                      {{ buttonText }}
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
    components: { modalconfirmacion, modalalerta },
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
      },
      id: null,
      coauthor: false,
      currentYear: new Date().getFullYear(),
      buttonDisable: false,
      errors:[],
      buttonText:'Edit Publication',
    }),
    props:{
      isiPublication1: Object,
    },
    created(){
      this.id = this.isiPublication1.id;
      this.isiPublication.authors = this.isiPublication1.authors;
      if (this.isiPublication1.coauthor != null){
        this.coauthor = true;
        this.isiPublication.coauthor = this.isiPublication1.coauthor;
      }
      this.isiPublication.articleTitle = this.isiPublication1.articleTitle;
      this.isiPublication.journalName = this.isiPublication1.journalName;
      this.isiPublication.doi = this.isiPublication1.doi;
      this.isiPublication.volume = this.isiPublication1.volume;
      this.isiPublication.firstPage = this.isiPublication1.firstPage;
      this.isiPublication.lastPage = this.isiPublication1.lastPage;
      this.isiPublication.yearPublished = this.isiPublication1.yearPublished;
    },
    methods: {
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
      async editarPublicacion() {
        this.errors = [];
        for (const item in this.isiPublication){
          if(this.isiPublication[item] === "" || this.isiPublication[item] === 0){
            if(item == 'coauthor' && this.coauthor == false){
            }else
              this.errors.push(item);
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
            let publication = {
              authors: this.isiPublication.authors,
              coauthor: this.isiPublication.coauthor,
              articleTitle: this.isiPublication.articleTitle,
              journalName: this.isiPublication.journalName,
              doi: this.isiPublication.doi,
              volume: this.isiPublication.volume,
              firstPage: this.isiPublication.firstPage,
              lastPage: this.isiPublication.lastPage,
              yearPublished: this.isiPublication.yearPublished,
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