<template>
  <transition name="modalcrear">
    <div name="modal">
      <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container-s">
              <div class="modal-header pb-1 fw-bold" style="color: #444444;">
                <slot name="header">
                    Edit Participation Sc Event
                </slot>
                <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
              </div>
              <div class="modal-body">
                <slot name="body">
                    <div class="row">
                          <div class="col-3">
                            <label for="">Type of Event:</label>
                            <select class="form-select" v-model="participationSc.typeEvent">
                              <option disabled :value="null">Select a type</option>
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
                          <div v-if="participationSc.typeEvent == 'Other'" class="col-3">
                            <label for="">Other:</label>
                            <br>
                            <input type="text" class= "form-control" v-model="other">
                          </div>
                          <div class="col-3">
                            <label for="">Event Name:</label>
                            <br>
                            <input type="text" class= "form-control" v-model="participationSc.eventName">
                          </div>
                          <div class="col-3">
                            <label for="">Country:</label>
                            <br>
                            <input type="text" class= "form-control" v-model="participationSc.country">
                          </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-3">
                            <label for="">City:</label>
                            <br>
                            <input type="text" class= "form-control" v-model="participationSc.city">
                          </div>
                          <div class="col-3">
                            <label for="">Start Date:</label>
                            <br>
                            <input type="date" class= "form-control" v-model="participationSc.startDate">
                          </div>
                          <div class="col-3">
                            <label for="">Ending Date:</label>
                            <br>
                            <input type="date" class= "form-control" v-model="participationSc.endingDate">
                          </div>
                          <div class="col-3">
                            <label for="">Progress Report: </label>
                            <br>
                            <select class="form-select" v-model="participationSc.progressReport">
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
                      <div class="col-6">
                        <label for="">Name of research line:</label>
                        <Multiselect
                          placeholder="Select the options"
                          v-model="participationSc.nameOfResearch"
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
                      <div v-if="participation1.file == null" class="col-5">
                        <div class="form-group">
                        <label for="archivo">File: </label>
                        <input type="file" ref="fileInput" accept=".pdf" class= "form-control" @change="getFile">
                        </div>
                      </div>
                      <div v-if="participation1.file == null" class="col-1 pt-2">
                        <br>
                        <a class="btn btn-closed " title="Clear Input" @click="clearFileInput"><i class="fa-solid fa-ban"></i></a>
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-6">
                        <label for="">Name of participants:</label>
                        <Multiselect
                          placeholder="Select the participants"
                          v-model="participationSc.nameOfParticipants"
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
                    v-model="draft"> Save as a draft</label>
                    <a v-if="draft == false" class="btn btn-continue float-end" @click="createParticipation()" :disabled="buttonDisable">
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
      participationSc:{
        typeEvent: '',
        eventName: '',
        country: '',
        city: '',
        startDate: '',
        endingDate: '',
        nameOfResearch: null,
        nameOfParticipants: null,
        progressReport: '',
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
      draft: false,
      researchers: '',
      id: '',
      buttonDisable: false,
      errors:[],
      buttonText:'Edit Participation',
    }),
    mounted(){
      this.getUsuarios();
    },
    props:{
      participation1: Object,
    },
    created(){
      this.id = this.participation1.id;
      this.participationSc.typeEvent = this.participation1.typeEvent;
      this.participationSc.eventName = this.participation1.eventName;
      this.participationSc.country = this.participation1.country;
      this.participationSc.city = this.participation1.city;
      this.participationSc.startDate = this.participation1.startDate;
      this.participationSc.endingDate = this.participation1.endingDate;
      this.participationSc.progressReport = this.participation1.progressReport;
      this.participationSc.endingDate = this.participation1.endingDate;
      if (this.participation1.nameOfResearch != null) {
          const valoresSeparados1 = this.participation1.nameOfResearch.split(",");
          this.participationSc.nameOfResearch = valoresSeparados1.map((valor, index) => {
              valor = valor.trim();
              if (valor.endsWith('.')) {
                  valor = valor.slice(0, -1);
              }

              return { value: valor, name: valor };
          });
      }

      if (this.participation1.nameOfParticipants != null) {
          const valoresSeparados1 = this.participation1.nameOfParticipants.split(",");
          this.participationSc.nameOfParticipants = valoresSeparados1.map((valor, index) => {
              valor = valor.trim();
              if (valor.endsWith('.')) {
                  valor = valor.slice(0, -1);
              }

              return { value: valor, name: valor };
          });
      }

      if(this.participation1.other == true){
        this.participationSc.typeEvent = 'Other';
        this.other = this.participation1.typeEvent;
      }
    },
    methods: {
      getUsuarios(){
        axios.get('api/researchers').then( response =>{
            this.researchers = response.data;
        }).catch(e=> console.log(e))
      },
      clearFileInput() {
        this.$refs.fileInput.value = '';
      },
      async getFile(e){
        this.participationSc.file = e.target.files[0];
      },
      async guardarBorrador(){
        const ok = await this.$refs.confirmation.show({
            title: 'Save draft',
            message: `¿Are you sure you want to save this Participation as a draft? this action cannot be undone.`,
            okButton: 'Save',
            cancelButton: 'Return'
          })
          if (ok) {
            var nameOfResearchLine1 = "";
            if (this.participationSc.nameOfResearch !== null){
              if (this.participationSc.nameOfResearch.length !== 0) {
                this.participationSc.nameOfResearch.forEach((nameOfResearch, index) => {
                  nameOfResearchLine1 += nameOfResearch.name;
                  if (index === this.participationSc.nameOfResearch.length - 1) {
                    nameOfResearchLine1 += '.';
                  } else {
                    nameOfResearchLine1 += ', ';
                  }
                });
              }
            }

            var nameOfParticipants1 = "";
            if (this.participationSc.nameOfParticipants !== null){
              if (this.participationSc.nameOfParticipants.length !== 0) {
                this.participationSc.nameOfParticipants.forEach((nameOfParticipants, index) => {
                  nameOfParticipants1 += nameOfParticipants.name;
                  if (index === this.participationSc.nameOfParticipants.length - 1) {
                    nameOfParticipants1 += '.';
                  } else {
                    nameOfParticipants1 += ', ';
                  }
                });
              }
            }

            let participationSc = {
              status: 'Draft',
              typeEvent: this.participationSc.typeEvent,
              eventName: this.participationSc.eventName,
              country: this.participationSc.country,
              city: this.participationSc.city,
              startDate: this.participationSc.startDate,
              endingDate: this.participationSc.endingDate,
              progressReport: this.participationSc.progressReport,
              nameOfParticipants: nameOfParticipants1,
              nameOfResearch: nameOfResearchLine1,
            };

            axios.put(`api/participationScEvents/${this.id}`, participationSc ).then((result) => {
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
            if(this.participation1.file == null){
                let salida = {
                  id: this.id,
                  file: this.participationSc.file,
                }
                axios.post('api/participationScEvents/addFile', salida, {
                    headers: { 'Content-Type' : 'multipart/form-data' }
                  }).then( response => {
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
             }else{
              setTimeout(() => {this.cerrarModal();}, 1500);
             }
          }
      },
      cerrarModal(){
        const elem = this.$refs.closeBtn;
        this.$emit('recarga');
        elem.click();
      },
      capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
      },
      async createParticipation() {
        this.errors = [];
        for (const item in this.participationSc){
          if(this.participationSc[item] === "" || this.participationSc[item] === 0 || this.participationSc[item] == null || this.participationSc == []){
              if(item != 'file'){
                this.errors.push(item);
              }
            }
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
            }else if(item == 'nameOfParticipants'){
              mensaje =   mensaje + "The field Name of Participants is required" + "\n";
            }else if(item == 'nameOfResearch'){
              mensaje =   mensaje + "The field Name of Research line is required" + "\n";
            }else if(item == 'progressReport'){
              mensaje =   mensaje + "The field Progress Report is required" + "\n";
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
            title: 'Save Participation',
            message: `¿Are you sure you want to save this Participation of sc event? This action cannot be undone.`,
            okButton: 'Save',
            cancelButton: 'Return'
          })
          if (ok) {
            var nameOfResearchLine1 = "";
            if (this.participationSc.nameOfResearch !== null){
              if (this.participationSc.nameOfResearch.length !== 0) {
                this.participationSc.nameOfResearch.forEach((nameOfResearch, index) => {
                  nameOfResearchLine1 += nameOfResearch.name;
                  if (index === this.participationSc.nameOfResearch.length - 1) {
                    nameOfResearchLine1 += '.';
                  } else {
                    nameOfResearchLine1 += ', ';
                  }
                });
              }
            }

            var nameOfParticipants1 = "";
            if (this.participationSc.nameOfParticipants !== null){
              if (this.participationSc.nameOfParticipants.length !== 0) {
                this.participationSc.nameOfParticipants.forEach((nameOfParticipants, index) => {
                  nameOfParticipants1 += nameOfParticipants.name;
                  if (index === this.participationSc.nameOfParticipants.length - 1) {
                    nameOfParticipants1 += '.';
                  } else {
                    nameOfParticipants1 += ', ';
                  }
                });
              }
            }

            let participationSc = {
              status: 'Finished',
              typeEvent: this.participationSc.typeEvent,
              eventName: this.participationSc.eventName,
              country: this.participationSc.country,
              city: this.participationSc.city,
              startDate: this.participationSc.startDate,
              endingDate: this.participationSc.endingDate,
              progressReport: this.participationSc.progressReport,
              nameOfParticipants: nameOfParticipants1,
              nameOfResearch: nameOfResearchLine1,
            };
            axios.put(`api/participationScEvents/${this.id}`, participationSc ).then((result) => {
              this.toast.success("Participation saved successfully!", {
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
            if(this.participation1.file == null){
                let salida = {
                  id: this.id,
                  file: this.participationSc.file,
                }
                axios.post('api/participationScEvents/addFile', salida, {
                    headers: { 'Content-Type' : 'multipart/form-data' }
                  }).then( response => {
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
             }else{
              setTimeout(() => {this.cerrarModal();}, 1500);
             }
           }
        }
      },
    },
}
</script>

<style src="@vueform/multiselect/themes/default.css"></style>