<template>
  <transition name="modalcrear">
    <div name="modal">
      <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container-s">
              <div class="modal-header pb-1 fw-bold" style="color: #444444;">
                <slot name="header">
                    New Organization Sc Event
                </slot>
                <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
              </div>
              <div class="modal-body">
                <slot name="body">
                    <div class="row">
                          <div class="col-3">
                            <label for="">Type of Event:</label>
                            <select class="form-select" v-model="organizationSc.typeEvent">
                              <option disabled value="">Select a type</option>
                              <option value="International congress">International congress</option>
                              <option value="National congress">National congress</option>
                              <option value="Workshop">Workshop</option>
                              <option value="Course">Course</option>
                              <option value="Conference">Conference</option>
                              <option value="Seminar">Seminar</option>
                              <option value="Symposium">Symposium</option>
                              <option value="Other">Other</option>
                              </select>
                          </div>
                          <div v-if="organizationSc.typeEvent == 'Other'" class="col-3">
                            <label for="">Other:</label>
                            <br>
                            <input type="text" class= "form-control" v-model="other">
                          </div>
                          <div class="col-3">
                            <label for="">Event Name:</label>
                            <br>
                            <input type="text" class= "form-control" v-model="organizationSc.eventName">
                          </div>
                          <div class="col-3">
                            <label for="">Country:</label>
                            <br>
                            <input type="text" class= "form-control" v-model="organizationSc.country">
                          </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-3">
                            <label for="">City:</label>
                            <br>
                            <input type="text" class= "form-control" v-model="organizationSc.city">
                          </div>
                          <div class="col-3">
                            <label for="">Start Date:</label>
                            <br>
                            <input type="date" class= "form-control" v-model="organizationSc.startDate">
                          </div>
                          <div class="col-3">
                            <label for="">Ending Date:</label>
                            <br>
                            <input type="date" class= "form-control" v-model="organizationSc.endingDate">
                          </div>

                          <div class="col-3">
                            <label for="">Number of participants:</label>
                            <br>
                            <input type="number" class= "form-control" v-model="organizationSc.numberParticipants">
                          </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-6">
                        <label for="">Name of research line:</label>
                        <Multiselect
                          placeholder="Select the options"
                          v-model="organizationSc.nameOfResearch"
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
                      <div class="col-5">
                        <div class="form-group">
                        <label for="archivo">File: </label>
                        <input type="file" ref="fileInput" accept=".pdf" class= "form-control" @change="getFile">
                        </div>
                      </div>
                      <div class="col-1 pt-2">
                        <br>
                        <a class="btn btn-closed " title="Clear Input" @click="clearFileInput"><i class="fa-solid fa-ban"></i></a>
                      </div>
                    </div>
                  </slot>
                </div>
                <div class="modal-footer">
                  <slot name="footer">
                    <label class="form-check-label"><input type="checkbox" class="form-check-"
                    v-model="draft"> Save as a draft</label>
                    <a v-if="draft == false" class="btn btn-continue float-end" @click="createOrganization()" :disabled="buttonDisable">
                      {{ buttonText }}
                    </a>
                    <a v-else class="btn btn-continue float-end" @click="guardarBorrador()" :disabled="buttonDisable">
                      Save draft
                    </a>
                  </slot>
                </div>
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
      organizationSc:{
        typeEvent: '',
        eventName: '',
        country: '',
        city: '',
        startDate: '',
        endingDate: '',
        numberParticipants: '',
        nameOfResearch: null,
        file: '',
      },
      options1: [
        'Biomedical Systems',
        'Control and Automation',
        'Data Analytics and Artificial Intelligence',
        'Electrical Systems',
        'Energy Conversion and Power Systems',
        'Instrumentation',
      ],
      other: '',
      currentYear: new Date().getFullYear(),
      coauthor: false,
      draft: false,
      buttonDisable: false,
      errors:[],
      buttonText:'Save Organization',
    }),
    methods: {
      clearFileInput() {
        this.$refs.fileInput.value = '';
      },
      async getFile(e){
        this.organizationSc.file = e.target.files[0];
      },
      async guardarBorrador(){
        const ok = await this.$refs.confirmation.show({
            title: 'Save draft',
            message: `¿Are you sure you want to save this Organization as a draft? this action cannot be undone.`,
            okButton: 'Save',
            cancelButton: 'Return'
          })
          if (ok) {
            var nameOfResearchLine1 = "";
            if (this.organizationSc.nameOfResearch !== null){
              if (this.organizationSc.nameOfResearch.length !== 0) {
                this.organizationSc.nameOfResearch.forEach((nameOfResearch, index) => {
                  nameOfResearchLine1 += nameOfResearch.name;
                  if (index === this.organizationSc.nameOfResearch.length - 1) {
                    nameOfResearchLine1 += '.';
                  } else {
                    nameOfResearchLine1 += ', ';
                  }
                });
              }
            }

            var typeEvent = '';
            var other = 0;

            if(this.organizationSc.typeEvent == 'Other'){
              typeEvent = this.other;
              other = 1;
            }else{
              typeEvent = this.organizationSc.typeEvent;
            }

            let organizationSc = {
              status: 'Draft',
              idUsuario: this.userID,
              typeEvent: typeEvent,
              other: other,
              eventName: this.organizationSc.eventName,
              country: this.organizationSc.country,
              city: this.organizationSc.city,
              startDate: this.organizationSc.startDate,
              endingDate: this.organizationSc.endingDate,
              numberParticipants: this.organizationSc.numberParticipants,
              nameOfResearch: nameOfResearchLine1,
              file: this.organizationSc.file,
            };
            axios.post("api/organizationScEvents", organizationSc, {headers: { 'Content-Type' : 'multipart/form-data' }} ).then((result) => {
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
      async createOrganization() {
        this.errors = [];
        for (const item in this.organizationSc){
          if(this.organizationSc[item] === "" || this.organizationSc[item] === 0 || this.organizationSc[item] == null){
              this.errors.push(item);
            }
        }

        if(this.organizationSc.typeEvent == 'Other' && this.other == ''){
          this.errors.push('other');
        }

        var mensaje = ""
        if (this.errors.length != 0){
          this.errors.forEach(item => {
            if(item == 'typeEvent'){
              mensaje =   mensaje + "The field Type Event is required" + "\n";
            }else if(item == 'eventName'){
              mensaje =   mensaje + "The field Event Name is required" + "\n";
            }else if(item == 'startDate'){
              mensaje =   mensaje + "The field Start Date is required" + "\n";
            }else if(item == 'endingDate'){
              mensaje =   mensaje + "The field Ending Date is required" + "\n";
            }else if(item == 'numberParticipants'){
              mensaje =   mensaje + "The field Number of Participants is required" + "\n";
            }else if(item == 'nameOfResearch'){
              mensaje =   mensaje + "The field Name of research line is required" + "\n";
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
            title: 'Save Organization',
            message: `¿Are you sure you want to save this organization of sc event? This action cannot be undone.`,
            okButton: 'Save',
            cancelButton: 'Return'
          })
          if (ok) {
            var nameOfResearchLine1 = "";
            if (this.organizationSc.nameOfResearch !== null){
              if (this.organizationSc.nameOfResearch.length !== 0) {
                this.organizationSc.nameOfResearch.forEach((nameOfResearch, index) => {
                  nameOfResearchLine1 += nameOfResearch.name;
                  if (index === this.organizationSc.nameOfResearch.length - 1) {
                    nameOfResearchLine1 += '.';
                  } else {
                    nameOfResearchLine1 += ', ';
                  }
                });
              }
            }
            var typeEvent = '';
            var other = 0;

            if(this.organizationSc.typeEvent == 'Other'){
              typeEvent = this.other;
              other = 1;
            }else{
              typeEvent = this.organizationSc.typeEvent;
            }

            let organizationSc = {
              status: 'Finished',
              idUsuario: this.userID,
              typeEvent: typeEvent,
              other: other,
              eventName: this.organizationSc.eventName,
              country: this.organizationSc.country,
              city: this.organizationSc.city,
              startDate: this.organizationSc.startDate,
              endingDate: this.organizationSc.endingDate,
              numberParticipants: this.organizationSc.numberParticipants,
              nameOfResearch: nameOfResearchLine1,
              file: this.organizationSc.file,
            };
            axios.post("api/organizationScEvents", organizationSc, {headers: { 'Content-Type' : 'multipart/form-data' }} ).then((result) => {
              this.toast.success("Organization saved successfully!", {
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