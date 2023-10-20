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
                <label for="">Progress year: {{ participationSc.progressReport }}</label>
                <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
              </div>
              <div class="modal-body">
                <slot name="body">
                    <div class="row">
                          <div class="col-3">
                            <label for="">Type of Event:</label>
                            <label for="" style="color: orange;">*</label>
                            <select class="form-select" v-model="participationSc.typeEvent">
                              <option disabled value="">Select a type</option>
                              <option value="International congress">International congress</option>
                              <option value="National congress">National congress</option>
                              <option value="Session chair">Session chair</option>
                              <option value="Keynote">Keynote</option>
                              <option value="Just assistance">Just assistance</option>
                              <option value="Other">Other</option>
                              </select>
                          </div>
                          <div v-if="participationSc.typeEvent == 'Other'" class="col-3">
                            <label for="">Other:</label>
                            <label for="" style="color: orange;">*</label>
                            <br>
                            <input type="text" class= "form-control" v-model="other">
                          </div>
                          <div class="col-3">
                            <label for="">Type of Participation:</label>
                            <label for="" style="color: orange;">*</label>
                            <select class="form-select" v-model="participationSc.typeOfParticipation">
                              <option disabled value="">Select a type</option>
                              <option value="Paper presentation">International congress</option>
                              <option value="Talk">Talk</option>
                              <option value="Workshop">Workshop</option>
                              <option value="Course">Course</option>
                              <option value="Conference">Conference</option>
                              <option value="Seminar">Seminar</option>
                              <option value="Symposium">Symposium</option>
                              <option value="Other">Other</option>
                              </select>
                          </div>
                          <div v-if="participationSc.typeOfParticipation == 'Other'" class="col-3">
                            <label for="">Other:</label>
                            <label for="" style="color: orange;">*</label>
                            <br>
                            <input type="text" class= "form-control" v-model="other2">
                          </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-6">
                          <label for="">Event Name:</label>
                          <label for="" style="color: orange;">*</label>
                          <br>
                          <input type="text" class= "form-control" v-model="participationSc.eventName">
                        </div>
                        <div class="col-6">
                          <label for="">Presentation Title:</label>
                          <label for="" style="color: orange;">*</label>
                          <br>
                          <input type="text" class= "form-control" v-model="participationSc.presentationTitle">
                        </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-3">
                        <label for="">Country:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="participationSc.country">
                      </div>
                      <div class="col-3">
                        <label for="">City:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="participationSc.city">
                      </div>
                      <div class="col-3">
                        <label for="">Start Date:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="date" class= "form-control" v-model="participationSc.startDate">
                      </div>
                      <div class="col-3">
                        <label for="">Ending Date:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="date" class= "form-control" v-model="participationSc.endingDate">
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-6">
                        <label for="">Name of research line:</label>
                        <label for="" style="color: orange;">*</label>
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
                      <div class="col-4">
                        <div class="form-group">
                        <label for="archivo">File:</label>
                        <label v-if="participation1.file != null" title="This record already has a file, if you want to change add a new one, otherwise leave this field empty." style="color: #0A95FF;"><i class="fa-solid fa-circle-info"></i></label>
                        <input type="file" ref="fileInput" accept=".pdf" class= "form-control" @change="getFile">
                        </div>
                      </div>
                      <div class="col-2 pt-2">
                        <br>
                        <a class="btn btn-closed " title="Clear Input" @click="clearFileInput"><i class="fa-solid fa-ban"></i></a>
                        &nbsp;
                        <a v-if="participation1.file != null" class="btn btn-search-blue " title="Download" @click="descargarExtracto(id,user)"><i class="fa-solid fa-download"></i></a>
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-6">
                        <label for="">Name of participants:</label>
                        <label for="" style="color: orange;">*</label>
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
                      <div class="col-6">
                          <label for="">Comments:</label>
                          <br>
                          <input type="text" class= "form-control" v-model="participationSc.comments">
                      </div>
                    </div>
                  </slot>
                </div>
                <div class="modal-footer">
                  <slot name="footer">
                    <label class="form-check-label"><input type="checkbox" class="form-check-"
                    v-model="draft"> Edit as a draft</label>
                    <a v-if="draft == false" class="btn btn-continue float-end" @click="editParticipation()" :disabled="buttonDisable">
                      {{ buttonText }}
                    </a>
                    <a v-else class="btn btn-continue float-end" @click="guardarBorrador()" :disabled="buttonDisable">
                      Edit draft
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
        presentationTitle: '',
        typeOfParticipation: '',
        country: '',
        city: '',
        startDate: '',
        endingDate: '',
        nameOfResearch: null,
        nameOfParticipants: null,
        progressReport: '',
        file: '',
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
      other: '',
      other2: '',
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
      this.participationSc.typeOfParticipation = this.participation1.typeOfParticipation;
      this.participationSc.eventName = this.participation1.eventName;
      this.participationSc.presentationTitle = this.participation1.presentationTitle;
      this.participationSc.country = this.participation1.country;
      this.participationSc.city = this.participation1.city;
      this.participationSc.startDate = this.participation1.startDate;
      this.participationSc.endingDate = this.participation1.endingDate;
      this.participationSc.progressReport = this.participation1.progressReport;
      this.participationSc.endingDate = this.participation1.endingDate;
      this.participationSc.comments = this.participation1.comments;
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

      if(this.participation1.otherParticipation == true){
        this.participationSc.typeOfParticipation = 'Other';
        this.other2 = this.participation1.typeOfParticipation;
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
            title: 'Edit draft',
            message: `¿Are you sure you want to edit this Participation as a draft? this action cannot be undone.`,
            okButton: 'Edit',
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
            var typeEvent = '';
            var other = 0;

            if(this.participationSc.typeEvent == 'Other'){
              typeEvent = this.other;
              other = 1;
            }else{
              typeEvent = this.participationSc.typeEvent;
            }

            var typeOfParticipation = '';
            var other2 = 0;

            if(this.participationSc.typeOfParticipation == 'Other'){
              typeOfParticipation = this.other2;
              other2 = 1;
            }else{
              typeOfParticipation = this.participationSc.typeOfParticipation;
            }

            let participationSc = {
              status: 'Draft',
              typeEvent: typeEvent,
              other: other,
              presentationTitle: this.participationSc.presentationTitle,
              typeOfParticipation: typeOfParticipation,
              otherParticipation: other2,
              eventName: this.participationSc.eventName,
              country: this.participationSc.country,
              city: this.participationSc.city,
              startDate: this.participationSc.startDate,
              endingDate: this.participationSc.endingDate,
              progressReport: this.participationSc.progressReport,
              nameOfParticipants: nameOfParticipants1,
              nameOfResearch: nameOfResearchLine1,
              file: this.participationSc.file,
              comments: this.participationSc.comments,
            };

            axios.put(`api/participationScEvents/${this.id}`, participationSc ).then((result) => {
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
      async editParticipation() {
        this.errors = [];
        const itemsToSkip = [
        'comments',
        'file'
        ];

        for (const item in this.participationSc) {
            const skipItem = itemsToSkip.includes(item);
            if (!skipItem && (this.participationSc[item] === "" || this.participationSc[item] === 0 || this.participationSc[item] == null)) {
              this.errors.push(item);
            }
        }

        if(this.participationSc.typeEvent == 'Other' && this.other == ''){
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
            }else if(item == 'nameOfParticipants'){
              mensaje =   mensaje + "The field Name of Participants is required" + "\n";
            }else if(item == 'nameOfResearch'){
              mensaje =   mensaje + "The field Name of research line is required" + "\n";
            }else if(item == 'progressReport'){
              mensaje =   mensaje + "The field Progress Report line is required" + "\n";
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
            title: 'Edit Participation',
            message: `¿Are you sure you want to edit this Participation of sc event? This action cannot be undone.`,
            okButton: 'Edit',
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

            var typeEvent = '';
            var other = 0;

            if(this.participationSc.typeEvent == 'Other'){
              typeEvent = this.other;
              other = 1;
            }else{
              typeEvent = this.participationSc.typeEvent;
            }

            var typeOfParticipation = '';
            var other2 = 0;

            if(this.participationSc.typeOfParticipation == 'Other'){
              typeOfParticipation = this.other2;
              other2 = 1;
            }else{
              typeOfParticipation = this.participationSc.typeOfParticipation;
            }

            let participationSc = {
              status: 'Finished',
              typeEvent: typeEvent,
              other: other,
              presentationTitle: this.participationSc.presentationTitle,
              typeOfParticipation: typeOfParticipation,
              otherParticipation: other2,
              eventName: this.participationSc.eventName,
              country: this.participationSc.country,
              city: this.participationSc.city,
              startDate: this.participationSc.startDate,
              endingDate: this.participationSc.endingDate,
              progressReport: this.participationSc.progressReport,
              nameOfParticipants: nameOfParticipants1,
              nameOfResearch: nameOfResearchLine1,
              comments: this.participationSc.comments,
            };
            axios.put(`api/participationScEvents/${this.id}`, participationSc ).then((result) => {
              this.toast.success("Participation edited successfully!", {
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