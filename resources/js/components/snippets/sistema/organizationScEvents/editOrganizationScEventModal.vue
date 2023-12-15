<template>
  <transition name="modalcrear">
    <div name="modal">
      <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container-s">
              <div class="modal-header pb-1 fw-bold" style="color: #444444;">
                <slot name="header">
                    Edit Organization Sc Event
                </slot>
                <label for="">Progress year: {{ organizationSc.progressReport }} &nbsp;&nbsp; <a class="btn" @click="showModalProgress = true"><i class="fa-solid fa-pen-to-square"></i></a></label>
                <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
              </div>
              <div class="modal-body">
                <slot name="body">
                    <div class="row">
                          <div class="col-3">
                            <label for="">Type of Event:</label>
                            <label for="" style="color: orange;">*</label>
                            <select class="form-select" v-model="organizationSc.typeEvent">
                              <option disabled :value=null>Select a type</option>
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
                            <label for="" style="color: orange;">*</label>
                            <br>
                            <input type="text" class= "form-control" v-model="other">
                          </div>
                          <div class="col-3">
                            <label for="">Event Name:</label>
                            <label for="" style="color: orange;">*</label>
                            <br>
                            <input type="text" class= "form-control" v-model="organizationSc.eventName">
                          </div>
                          <div class="col-3">
                            <label for="">Country:</label>
                            <label for="" style="color: orange;">*</label>
                            <br>
                            <input type="text" class= "form-control" v-model="organizationSc.country">
                          </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-3">
                            <label for="">City:</label>
                            <label for="" style="color: orange;">*</label>
                            <br>
                            <input type="text" class= "form-control" v-model="organizationSc.city">
                          </div>
                          <div class="col-3">
                            <label for="">Start Date:</label>
                            <label for="" style="color: orange;">*</label>
                            <br>
                            <input type="date" class= "form-control" v-model="organizationSc.startDate">
                          </div>
                          <div class="col-3">
                            <label for="">Ending Date:</label>
                            <label for="" style="color: orange;">*</label>
                            <br>
                            <input type="date" class= "form-control" v-model="organizationSc.endingDate">
                          </div>
                          <div class="col-3">
                            <label for="">Number of participants:</label>
                            <label for="" style="color: orange;">*</label>
                            <br>
                            <input type="number" class= "form-control" v-model="organizationSc.numberParticipants">
                          </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-4">
                        <div class="form-group">
                        <label for="archivo">File:</label>
                        <label v-if="organization1.file != null" title="This record already has a file, if you want to change add a new one, otherwise leave this field empty." style="color: #0A95FF;"><i class="fa-solid fa-circle-info"></i></label>
                        <input type="file" ref="fileInput" accept=".pdf" class= "form-control" @change="getFile">
                        </div>
                      </div>
                      <div class="col-2 pt-2">
                        <br>
                        <a class="btn btn-closed " title="Clear Input" @click="clearFileInput"><i class="fa-solid fa-ban"></i></a>
                        &nbsp;
                        <a v-if="organization1.file != null" class="btn btn-search-blue " title="Download" @click="descargarExtracto(id,user)"><i class="fa-solid fa-download"></i></a>
                      </div>
                      <div class="col-6">
                        <label for="">Comments:</label>
                        <input type="text" class= "form-control" v-model="organizationSc.comments">
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-6">
                        <label for="">Researcher involved:</label>
                        <label for="" style="color: orange;">*</label>
                        <Multiselect
                          placeholder="Select the participants"
                          v-model="organizationSc.researcherInvolved"
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
                    <a v-if="draft == false" class="btn btn-continue float-end" @click="editOrganization()" :disabled="buttonDisable">
                      {{ buttonText }}
                    </a>
                    <a v-else class="btn btn-continue float-end" @click="guardarBorrador()" :disabled="buttonDisable">
                      Edit draft
                    </a>
                  </slot>
                </div>
                <modalfotoperfil v-if="showFotoPerfil" @close="showFotoPerfil = false"></modalfotoperfil>
                <modalBookAutor v-if="showModalBookAutor" @close="showModalBookAutor = false" @submit="handleFormSubmit1" ></modalBookAutor>
                <modalChapterAutor v-if="showModalChapterAutor" @close="showModalChapterAutor = false" @submit="handleFormSubmit2"></modalChapterAutor>
                <modalconfirmacion ref="confirmation"></modalconfirmacion>
                <modalalerta ref="alert"></modalalerta>
                <modalProgressYear v-bind:progressYear="organizationSc.progressReport" v-if="showModalProgress" @close="showModalProgress = false" @submit="handleFormSubmit1"></modalProgressYear>
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
    components: { modalProgressYear,Multiselect, modalconfirmacion, modalalerta },
    mixins: [mixin],
    data: () => ({
      organizationSc:{
        typeEvent: '',
        eventName: '',
        country: '',
        city: '',
        startDate: '',
        endingDate: '',
        researcherInvolved: null,
        numberParticipants: '',
        file: '',
        comments: '',
        progressReport: '',
      },
      other: '',
      currentYear: new Date().getFullYear(),
      coauthor: false,
      draft: false,
      showModalProgress: false,
      id: '',
      user: '',
      researchers:[],
      buttonDisable: false,
      errors:[],
      buttonText:'Save Organization',
    }),
    props:{
      organization1: Object,
    },
    created(){
      this.getUsuarios();
      this.id = this.organization1.id;
      this.user = this.organization1.usuario.name;
      this.organizationSc.typeEvent = this.organization1.typeEvent;
      this.organizationSc.eventName = this.organization1.eventName;
      this.organizationSc.country = this.organization1.country;
      this.organizationSc.city = this.organization1.city;
      this.organizationSc.startDate = this.organization1.startDate;
      this.organizationSc.endingDate = this.organization1.endingDate;
      this.organizationSc.numberParticipants = this.organization1.numberParticipants;
      this.organizationSc.endingDate = this.organization1.endingDate;
      this.organizationSc.file = this.organization1.file;
      this.organizationSc.comments = this.organization1.comments;
      this.organizationSc.progressReport = this.organization1.progressReport;
      if(this.organization1.other == true){
        this.organizationSc.typeEvent = 'Other';
        this.other = this.organization1.typeEvent;
      }

      if (this.organization1.researcherInvolved != null) {
          const valoresSeparados1 = this.organization1.researcherInvolved.split(",");
          this.organizationSc.researcherInvolved = valoresSeparados1.map((valor, index) => {
              valor = valor.trim();
              if (valor.endsWith('.')) {
                  valor = valor.slice(0, -1);
              }

              return { value: valor, name: valor };
          });
      }

    },
    methods: {
      getUsuarios(){
        axios.get('api/researchers').then( response =>{
            this.researchers = response.data;
        }).catch(e=> console.log(e))
      },
      handleFormSubmit1(year) {
        this.organizationSc.progressReport = year;
      },
      descargarExtracto(id,nombre){
          axios({
              url: `api/organizationDownload/${id}`,
              method: 'GET',
              responseType: 'arraybuffer',
          }).then((response) => {
              let blob = new Blob([response.data], {
                      type: 'application/pdf'
                  })
                  let link = document.createElement('a')
                  link.href = window.URL.createObjectURL(blob)
                  link.download = `Organization-${nombre}.pdf`
                  link.click()
          });
      },
      clearFileInput() {
        this.$refs.fileInput.value = '';
      },
      async getFile(e){
        this.organizationSc.file = e.target.files[0];
      },
      async guardarBorrador(){
        const ok = await this.$refs.confirmation.show({
            title: 'Edit draft',
            message: `¿Are you sure you want to edit this Organization as a draft? this action cannot be undone.`,
            okButton: 'Edit',
            cancelButton: 'Return'
          })
          if (ok) {

            var typeEvent = '';
            var other = 0;

            if(this.organizationSc.typeEvent == 'Other'){
              typeEvent = this.other;
              other = 1;
            }else{
              typeEvent = this.organizationSc.typeEvent;
            }

            var peopleInvolved1 = "";
            if (this.organizationSc.researcherInvolved !== null){
              if (this.organizationSc.researcherInvolved.length !== 0) {
                this.organizationSc.researcherInvolved.forEach((researcherInvolved, index) => {
                  peopleInvolved1 += researcherInvolved.name;
                  if (index === this.organizationSc.researcherInvolved.length - 1) {
                    peopleInvolved1 += '.';
                  } else {
                    peopleInvolved1 += ', ';
                  }
                });
              }
            }

            let organizationSc = {
              status: 'Draft',
              researcherInvolved: peopleInvolved1,
              typeEvent: typeEvent,
              other: other,
              eventName: this.organizationSc.eventName,
              country: this.organizationSc.country,
              city: this.organizationSc.city,
              startDate: this.organizationSc.startDate,
              endingDate: this.organizationSc.endingDate,
              numberParticipants: this.organizationSc.numberParticipants,
              comments: this.organizationSc.comments,
            };
            await axios.put(`api/organizationScEvents/${this.id}`, organizationSc ).then((result) => {
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
              formData.append('file', this.organizationSc.file);
              axios.post('api/organizationScEvents/addFile', formData, {
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
      cerrarModal(){
        const elem = this.$refs.closeBtn;
        this.$emit('recarga');
        elem.click();
      },
      capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
      },
      async editOrganization() {
        this.errors = [];
        const itemsToSkip = [
          'comments',
          'file'
        ];

        for (const item in this.organizationSc) {
            const skipItem = itemsToSkip.includes(item);
            if (!skipItem && (this.organizationSc[item] === "" || this.organizationSc[item] === 0 || this.organizationSc[item] == null)) {
              this.errors.push(item);
            }
        }

        if(this.organizationSc.typeEvent == 'Other' && this.other == ''){
          this.errors.push('other');
        }

        var idUser1 = ''
        if(this.idResearcher != ''){
          idUser1 = this.idResearcher;
        }else{
          idUser1 = this.userID;
        }

        let organizationSc1 = {
          id: this.id,
          idUsuario: idUser1,
          typeEvent: typeEvent,
          other: other,
          eventName: this.organizationSc.eventName,
          country: this.organizationSc.country,
          city: this.organizationSc.city,
          startDate: this.organizationSc.startDate,
          endingDate: this.organizationSc.endingDate,
          numberParticipants: this.organizationSc.numberParticipants,
          file: this.organizationSc.file,
          comments: this.organizationSc.comments,
          progressReport: this.organizationSc.progressReport,
        };

        var contador = await axios.post('../api/verifyOrganization', organizationSc1).then(function(response) {
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
            title: 'Edit Organization',
            message: `¿Are you sure you want to edit this organization of sc event? This action cannot be undone.`,
            okButton: 'Edit',
            cancelButton: 'Return'
          })
          if (ok) {


            var typeEvent = '';
            var other = 0;

            if(this.organizationSc.typeEvent == 'Other'){
              typeEvent = this.other;
              other = 1;
            }else{
              typeEvent = this.organizationSc.typeEvent;
            }

            var peopleInvolved1 = "";
            if (this.organizationSc.researcherInvolved !== null){
              if (this.organizationSc.researcherInvolved.length !== 0) {
                this.organizationSc.researcherInvolved.forEach((researcherInvolved, index) => {
                  peopleInvolved1 += researcherInvolved.name;
                  if (index === this.organizationSc.researcherInvolved.length - 1) {
                    peopleInvolved1 += '.';
                  } else {
                    peopleInvolved1 += ', ';
                  }
                });
              }
            }

            let organizationSc = {
              status: 'Finished',
              researcherInvolved: peopleInvolved1,
              typeEvent: typeEvent,
              other: other,
              eventName: this.organizationSc.eventName,
              country: this.organizationSc.country,
              city: this.organizationSc.city,
              startDate: this.organizationSc.startDate,
              endingDate: this.organizationSc.endingDate,
              numberParticipants: this.organizationSc.numberParticipants,
              comments: this.organizationSc.comments,
            };

            await axios.put(`api/organizationScEvents/${this.id}`, organizationSc ).then((result) => {
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
              const formData = new FormData();
              formData.append('id', this.id);
              formData.append('file', this.organizationSc.file);
              axios.post('api/organizationScEvents/addFile', formData, {
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