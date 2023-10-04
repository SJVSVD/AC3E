<template>
  <transition name="modalcrear">
    <div name="modal">
      <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container-s">
              <div class="modal-header pb-1 fw-bold" style="color: #444444;">
                <slot name="header">
                    New Outreach Activity 
                </slot>
                <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
              </div>
              <div class="modal-body">
                <slot name="body">
                    <div class="row">
                          <div class="col-3">
                            <label for="">Activity Type:</label>
                            <select class="form-select" v-model="outreachActivity.activityType">
                              <option disabled value="">Select a type</option>
                              <option value="Conference">Conference</option>
                              <option value="Seminar">Seminar</option>
                              <option value="Forum">Forum</option>
                              <option value="Exhibition">Exhibition</option>
                              <option value="Workshop">Workshop</option>
                              <option value="Competition">Competition</option>
                              <option value="Course">Course</option>
                              <option value="Outreach Material">Outreach Material</option>
                              <option value="Other">Other</option>
                              </select>
                          </div>
                          <div v-if="outreachActivity.activityType == 'Other'" class="col-3">
                            <label for="">Other:</label>
                            <br>
                            <input type="text" class= "form-control" v-model="other">
                          </div>
                          <div class="col-3">
                            <label for="">Activity Name:</label>
                            <br>
                            <input type="text" class= "form-control" v-model="outreachActivity.activityName">
                          </div>
                          <div class="col-3">
                            <label for="">Activity Description:</label>
                            <br>
                            <input type="text" class= "form-control" v-model="outreachActivity.activityDescription">
                          </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-3">
                        <label for="">Date:</label>
                        <br>
                        <input type="date" class= "form-control" v-model="outreachActivity.date">
                      </div>
                      <div class="col-3">
                        <label for="">Attendants Amount:</label>
                        <br>
                        <input type="number" class= "form-control" v-model="outreachActivity.attendantsAmount">
                      </div>
                      <div class="col-3">
                        <label for="">Duration (Days):</label>
                        <br>
                        <input type="number" class= "form-control" v-model="outreachActivity.duration">
                      </div>
                      <div class="col-3">
                        <label for="">Country:</label>
                        <br>
                        <input type="text" class= "form-control" v-model="outreachActivity.country">
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-3">
                        <label for="">Place/Region:</label>
                        <br>
                        <input type="text" class= "form-control" v-model="outreachActivity.placeRegion">
                      </div>
                      <div class="col-3">
                        <label for="">City:</label>
                        <br>
                        <input type="text" class= "form-control" v-model="outreachActivity.city">
                      </div>
                      <div class="col-6">
                        <label for="">Name of the main responsible:</label>
                        <Multiselect
                          placeholder="Select the researchers"
                          v-model="outreachActivity.nameOfTheMainResponsible"
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
                    <hr size="3" class="separador">
                    <div class="row">
                      <div class="text-uppercase pb-2">Target Audiences:</div>
                      <div class="col-4">
                          <div class="form-check pt-2 ">
                            <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                  v-model="outreachActivity.undergraduateStudents"> Undergraduate students</label>
                          </div>
                      </div>
                      <div class="col-4">
                          <div class="form-check pt-2 ">
                            <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                  v-model="outreachActivity.primaryEducationStudents"> Primary education students</label>
                          </div>
                      </div>
                      <div class="col-4">
                          <div class="form-check pt-2 ">
                            <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                  v-model="outreachActivity.secondaryEducationStudents"> Secondary education students</label>
                          </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-4">
                          <div class="form-check pt-2 ">
                            <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                  v-model="outreachActivity.generalCommunity"> General community</label>
                          </div>
                      </div>
                      <div class="col-4">
                          <div class="form-check pt-2 ">
                            <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                  v-model="outreachActivity.companiesIndustriesServices"> Companies,industries,services </label>
                          </div>
                      </div>
                      <div class="col-4">
                          <div class="form-check pt-2 ">
                            <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                  v-model="outreachActivity.schoolTeachers"> School teachers </label>
                          </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-4">
                          <div class="form-check pt-2 ">
                            <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                  v-model="outreachActivity.governmentOfficial"> Government official </label>
                          </div>
                      </div>
                      <div class="col-4">
                          <div class="form-check pt-2 ">
                            <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                  v-model="outreachActivity.other"> Other </label>
                          </div>
                      </div>
                    </div>
                  </slot>
                </div>
                <div class="modal-footer">
                  <slot name="footer">
                    <label class="form-check-label"><input type="checkbox" class="form-check-"
                    v-model="draft"> Save as a draft</label>
                    <a v-if="draft == false" class="btn btn-continue float-end" @click="createActivity()" :disabled="buttonDisable">
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
      outreachActivity:{
        activityType: '',
        activityName: '',
        activityDescription: '',
        date: '',
        attendantsAmount: '',
        duration: '',
        country: '',
        placeRegion: '',
        city: '',
        undergraduateStudents: 0,
        primaryEducationStudents: 0,
        secondaryEducationStudents: 0,
        generalCommunity: 0,
        companiesIndustriesServices: 0,
        schoolTeachers: 0,
        governmentOfficial: 0,
        other: 0,
        nameOfTheMainResponsible: null,
        progressReport: 9,
      },
      other: '',
      draft: false,
      researchers: '',
      buttonDisable: false,
      errors:[],
      buttonText:'Save Activity',
    }),
    mounted(){
      this.getUsuarios();
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
            message: `¿Are you sure you want to save this Outreach Activity as a draft? this action cannot be undone.`,
            okButton: 'Save',
            cancelButton: 'Return'
          })
          if (ok) {
            var namesResponsibles1 = "";
            if (this.outreachActivity.nameOfTheMainResponsible !== null){
              if (this.outreachActivity.nameOfTheMainResponsible.length !== 0) {
                this.outreachActivity.nameOfTheMainResponsible.forEach((nameOfTheMainResponsible, index) => {
                  namesResponsibles1 += nameOfTheMainResponsible.name;
                  if (index === this.outreachActivity.nameOfTheMainResponsible.length - 1) {
                    namesResponsibles1 += '.';
                  } else {
                    namesResponsibles1 += ', ';
                  }
                });
              }
            }

            var type = '';
            var other1 = false;

            if(this.outreachActivity.activityType == 'Other'){
              type = this.other;
              other1 = true;
            }else{
              type = this.outreachActivity.activityType;
            }

            let outreachActivity = {
              status: 'Draft',
              idUsuario: this.userID,
              activityType: type,
              otherType: other1,
              activityName: this.outreachActivity.activityName,
              activityDescription: this.outreachActivity.activityDescription,
              date: this.outreachActivity.date,
              attendantsAmount: this.outreachActivity.attendantsAmount,
              duration: this.outreachActivity.duration,
              country: this.outreachActivity.country,
              placeRegion: this.outreachActivity.placeRegion,
              city: this.outreachActivity.city,
              undergraduateStudents: this.outreachActivity.undergraduateStudents,
              primaryEducationStudents: this.outreachActivity.primaryEducationStudents,
              secondaryEducationStudents: this.outreachActivity.secondaryEducationStudents,
              generalCommunity: this.outreachActivity.generalCommunity,
              companiesIndustriesServices: this.outreachActivity.companiesIndustriesServices,
              schoolTeachers: this.outreachActivity.schoolTeachers,
              governmentOfficial: this.outreachActivity.governmentOfficial,
              other: this.outreachActivity.other,
              nameOfTheMainResponsible: namesResponsibles1,
              progressReport: this.outreachActivity.progressReport,
            };
            axios.post("api/outreachActivities", outreachActivity, {headers: { 'Content-Type' : 'multipart/form-data' }} ).then((result) => {
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
      cerrarModal(){
        const elem = this.$refs.closeBtn;
        this.$emit('recarga');
        elem.click();
      },
      capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
      },
      async createActivity() {
        this.errors = [];

        const itemsToCheck = [
          'undergraduateStudents',
          'primaryEducationStudents',
          'secondaryEducationStudents',
          'generalCommunity',
          'companiesIndustriesServices',
          'schoolTeachers',
          'governmentOfficial',
          'other',
        ];


        for (const item in this.outreachActivity){
          if(this.outreachActivity[item] === "" || this.outreachActivity[item] === 0 || this.outreachActivity[item] == null || this.outreachActivity[item] == []){
            if (itemsToCheck.includes(item)) {
              } else {
                this.errors.push(item);
              }
            }
        }

        if(this.outreachActivity.activityType == 'Other' && this.other == ''){
          this.errors.push('other');
        }

        var mensaje = ""
        if (this.errors.length != 0){
          this.errors.forEach(item => {
            if(item == 'activityType'){
              mensaje =   mensaje + "The field Activity Type is required" + "\n";
            }else if(item == 'activityName'){
              mensaje =   mensaje + "The field Activity Name is required" + "\n";
            }else if(item == 'activityDescription'){
              mensaje =   mensaje + "The field Activity Description is required" + "\n";
            }else if(item == 'attendantsAmount'){
              mensaje =   mensaje + "The field Attendants Amount is required" + "\n";
            }else if(item == 'placeRegion'){
              mensaje =   mensaje + "The field Place/Region is required" + "\n";
            }else if(item == 'nameOfTheMainResponsible'){
              mensaje =   mensaje + "The field Name of the main responsible is required" + "\n";
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
            title: 'Save Outreach Activity',
            message: `¿Are you sure you want to save this Outreach Activity? This action cannot be undone.`,
            okButton: 'Save',
            cancelButton: 'Return'
          })
          if (ok) {
            var namesResponsibles1 = "";
            if (this.outreachActivity.nameOfTheMainResponsible !== null){
              if (this.outreachActivity.nameOfTheMainResponsible.length !== 0) {
                this.outreachActivity.nameOfTheMainResponsible.forEach((nameOfTheMainResponsible, index) => {
                  namesResponsibles1 += nameOfTheMainResponsible.name;
                  if (index === this.outreachActivity.nameOfTheMainResponsible.length - 1) {
                    namesResponsibles1 += '.';
                  } else {
                    namesResponsibles1 += ', ';
                  }
                });
              }
            }

            var type = '';
            var other1 = 0;

            if(this.outreachActivity.activityType == 'Other'){
              type = this.other;
              other1 = 1;
            }else{
              type = this.outreachActivity.activityType;
            }

            let outreachActivity = {
              status: 'Finished',
              idUsuario: this.userID,
              activityType: type,
              otherType: other1,
              activityName: this.outreachActivity.activityName,
              activityDescription: this.outreachActivity.activityDescription,
              date: this.outreachActivity.date,
              attendantsAmount: this.outreachActivity.attendantsAmount,
              duration: this.outreachActivity.duration,
              country: this.outreachActivity.country,
              placeRegion: this.outreachActivity.placeRegion,
              city: this.outreachActivity.city,
              undergraduateStudents: this.outreachActivity.undergraduateStudents,
              primaryEducationStudents: this.outreachActivity.primaryEducationStudents,
              secondaryEducationStudents: this.outreachActivity.secondaryEducationStudents,
              generalCommunity: this.outreachActivity.generalCommunity,
              companiesIndustriesServices: this.outreachActivity.companiesIndustriesServices,
              schoolTeachers: this.outreachActivity.schoolTeachers,
              governmentOfficial: this.outreachActivity.governmentOfficial,
              other: this.outreachActivity.other,
              nameOfTheMainResponsible: namesResponsibles1,
              progressReport: this.outreachActivity.progressReport,
            };
            axios.post("api/outreachActivities", outreachActivity, {headers: { 'Content-Type' : 'multipart/form-data' }} ).then((result) => {
              this.toast.success("Outreach activity saved successfully!", {
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