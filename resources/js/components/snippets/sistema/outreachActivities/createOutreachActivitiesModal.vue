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
                <label for="">Progress year: {{ outreachActivity.progressReport }}</label>
                <label v-if="is('Administrator')" class="col-5 m-0"> Researcher: <label class="fw-normal" style="font-size: 14px;">
                  <select class="form-select" v-model="idResearcher">
                    <option disabled value="">Select a researcher</option>
                    <option v-for="researcher in usuarios" v-bind:key="researcher.id" v-bind:value="researcher.id">
                      {{ researcher.name }}
                    </option>
                    </select>
                  </label>
                </label>
                <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
              </div>
              <div class="modal-body">
                <slot name="body">
                    <div class="row">
                          <div class="col-3">
                            <label for="">Activity Type:</label>
                            <label for="" style="color: orange;">*</label>
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
                            <label for="" style="color: orange;">*</label>
                            <br>
                            <input type="text" class= "form-control" v-model="other">
                          </div>
                          <div class="col-3">
                            <label for="">Activity Name:</label>
                            <label for="" style="color: orange;">*</label>
                            <br>
                            <input type="text" class= "form-control" v-model="outreachActivity.activityName">
                          </div>
                          <div class="col-3">
                            <label for="">Activity Description:</label>
                            <label for="" style="color: orange;">*</label>
                            <br>
                            <input type="text" class= "form-control" v-model="outreachActivity.activityDescription">
                          </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-3">
                        <label for="">Date:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="date" class= "form-control" v-model="outreachActivity.date">
                      </div>
                      <div class="col-3">
                        <label for="">Attendants Amount:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="number" class= "form-control" v-model="outreachActivity.attendantsAmount">
                      </div>
                      <div class="col-3">
                        <label for="">Duration (Days):</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="number" class= "form-control" v-model="outreachActivity.duration">
                      </div>
                      <div class="col-3">
                        <label for="">Country:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="outreachActivity.country">
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-3">
                        <label for="">Place/Region:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="outreachActivity.placeRegion">
                      </div>
                      <div class="col-3">
                        <label for="">City:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="outreachActivity.city">
                      </div>
                      <div class="col-6">
                        <label for="">Name of the main responsible:</label>
                        <label for="" style="color: orange;">*</label>
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
                    <br>
                    <div class="row">
                      <div class="col-6">
                        <label for="">Researcher Involved:</label>
                        <label for="" style="color: orange;">*</label>
                        <Multiselect
                          placeholder="Select the researchers"
                          v-model="outreachActivity.researcherInvolved"
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
                        <label for="">Responsability:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="outreachActivity.responsability">
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-6">
                        <label for="">Comments:</label>
                        <br>
                        <input type="text" class= "form-control" v-model="outreachActivity.comments">
                      </div>
                    </div>
                    <hr size="3" class="separador">
                    <div class="row">
                      <div class="text-uppercase pb-2">Target Audiences:<label for="" style="color: orange;">*</label></div>
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
        researcherInvolved: null,
        responsability: '',
        comments: '',
        progressReport: '',
      },
      other: '',
      draft: false,
      researchers: '',
      buttonDisable: false,
      usuarios:[],
      idResearcher: '',
      errors:[],
      buttonText:'Save Activity',
    }),
    mounted(){
      this.getUsuarios();
      this.getUsuarios2();
      this.getProgressReport();
    },
    methods: {
      getProgressReport(){
        axios.get('api/showProgressReport').then( response =>{
            this.outreachActivity.progressReport = response.data;
        }).catch(e=> console.log(e))
      },
      getUsuarios2(){
        axios.get('api/usuarios').then( response =>{
            this.usuarios = response.data;
        }).catch(e=> console.log(e))
      },
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

            var researcherInvolved1 = "";
            if (this.outreachActivity.researcherInvolved !== null){
              if (this.outreachActivity.researcherInvolved.length !== 0) {
                this.outreachActivity.researcherInvolved.forEach((researcherInvolved, index) => {
                  researcherInvolved1 += researcherInvolved.name;
                  if (index === this.outreachActivity.researcherInvolved.length - 1) {
                    researcherInvolved1 += '.';
                  } else {
                    researcherInvolved1 += ', ';
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

            var idUser1 = ''
            if(this.idResearcher != ''){
              idUser1 = this.idResearcher;
            }else{
              idUser1 = this.userID;
            }

            let outreachActivity = {
              status: 'Draft',
              idUsuario: idUser1,
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
              researcherInvolved: researcherInvolved1,
              responsability: this.outreachActivity.responsability,
              comments: this.outreachActivity.comments,
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
          'comments'
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

        const checkboxFields = [
          'undergraduateStudents',
          'primaryEducationStudents',
          'secondaryEducationStudents',
          'generalCommunity',
          'companiesIndustriesServices',
          'schoolTeachers',
          'governmentOfficial',
          'other',
        ];

        const isChecked = checkboxFields.some(field => this.outreachActivity[field]);

        if (!isChecked) {
          this.errors.push('target audiencies');
        }

        var contador = await axios.post('../api/verifyOutreach', this.outreachActivity).then(function(response) {
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
            }else if(item == 'researcherInvolved'){
              mensaje =   mensaje + "The field Researcher Involved is required" + "\n";
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

            var researcherInvolved1 = "";
            if (this.outreachActivity.researcherInvolved !== null){
              if (this.outreachActivity.researcherInvolved.length !== 0) {
                this.outreachActivity.researcherInvolved.forEach((researcherInvolved, index) => {
                  researcherInvolved1 += researcherInvolved.name;
                  if (index === this.outreachActivity.researcherInvolved.length - 1) {
                    researcherInvolved1 += '.';
                  } else {
                    researcherInvolved1 += ', ';
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

            var idUser1 = ''
            if(this.idResearcher != ''){
              idUser1 = this.idResearcher;
            }else{
              idUser1 = this.userID;
            }

            let outreachActivity = {
              status: 'Finished',
              idUsuario: idUser1,
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
              researcherInvolved: researcherInvolved1,
              responsability: this.outreachActivity.responsability,
              comments: this.outreachActivity.comments,
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