<template>
  <transition name="modalcrear">
    <div name="modal">
      <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container-s">
              <div class="modal-header pb-1 fw-bold" style="color: #444444;">
                <slot name="header">
                    New Sc Collaboration
                </slot>
                <label for="">Progress year: {{ scCollaboration.progressReport }}</label>
                <label v-if="is('Administrator')" class="col-5 m-0"> Researcher: <label class="fw-normal" style="font-size: 14px;">
                  <select class="form-select" v-model="idResearcher">
                    <option disabled value="">Select a researcher</option>
                    <option v-for="researcher in researchers2" v-bind:key="researcher.id" v-bind:value="researcher.id">
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
                            <label for="">Activity Name:</label>
                            <label for="" style="color: orange;">*</label>
                            <br>
                            <select class="form-select" v-model="scCollaboration.activityName">
                              <option disabled value="">Select a type</option>
                              <option value="Visit in Chile (include students)">Visit in Chile (include students)</option>
                              <option value="Visit abroad (include students)">Visit abroad (include students)</option>
                              <option value="Research Stay (Pasantia de investigacion) (include students)">Research Stay (Pasantia de investigacion) (include students)</option>
                              <option value="Participation in R&D Projects directed by other Researcher (external)">Participation in R&D Projects directed by other Researcher (external)</option>
                              <option value="Participation in R&D Projects directed by an AC3E Researcher">Participation in R&D Projects directed by an AC3E Researcher</option>
                              <option value="Other">Other</option>
                              </select>
                          </div>
                          <div v-if="scCollaboration.activityName == 'Other'" class="col-3">
                            <label for="">Other Activity:</label>
                            <label for="" style="color: orange;">*</label>
                            <br>
                            <input type="text" class= "form-control" v-model="other">
                          </div>
                          <div class="col-3">
                            <label for="">Collaboration Stay:</label>
                            <label for="" style="color: orange;">*</label>
                            <select class="form-select" v-model="scCollaboration.collaborationStay">
                              <option disabled value="">Select a type</option>
                              <option value="Short Visit (Up to two weeks)">Short Visit (Up to two weeks)</option>
                              <option value="Long Visit (More than two weeks)">Long Visit (More than two weeks)</option>
                              <option value="Other">Other</option>
                              </select>
                          </div>
                          <div v-if="scCollaboration.collaborationStay == 'Other'" class="col-3">
                            <label for="">Other Stay:</label>
                            <label for="" style="color: orange;">*</label>
                            <br>
                            <input type="text" class= "form-control" v-model="other2">
                          </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-6">
                        <label for="">Researcher involved:</label>
                        <label for="" style="color: orange;">*</label>
                        <Multiselect
                          placeholder="Select the participants"
                          v-model="scCollaboration.researcherInvolved"
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
                        <label for="">Institution which the center collaborates:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="scCollaboration.institutionCollaborates">
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-5">
                        <label for="">Name of AC3E member:</label>
                        <label for="" style="color: orange;">*</label>
                        <select class="form-select" v-model="scCollaboration.nameOfAC3EMember">
                        <option disabled value="">Select a member</option>
                        <option v-for="researcher in researchers2" v-bind:key="researcher.id" v-bind:value="researcher.id">
                          {{ researcher.name }}
                        </option>
                        </select>
                      </div>
                      <div class="col-4">
                        <label v-if="scCollaboration.studentOrResearcher == 'Researcher'" for="">Name of external researcher:</label>
                        <label v-else for="">Name of external person:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="scCollaboration.nameOfExternalResearcher">
                      </div>
                      <div class="col-3">
                            <label for="">Student or Researcher:</label>
                            <label for="" style="color: orange;">*</label>
                            <br>
                            <select class="form-select" v-model="scCollaboration.studentOrResearcher">
                              <option disabled value="">Select a type</option>
                              <option value="Student">Student</option>
                              <option value="Researcher">Researcher</option>
                              </select>
                          </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-3">
                        <label for="">Country Origin:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="scCollaboration.countryOrigin">
                      </div>
                      <div class="col-3">
                        <label for="">City Origin:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="scCollaboration.cityOrigin">
                      </div>
                      <div class="col-3">
                        <label for="">Country Destination:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="scCollaboration.countryDestination">
                      </div>
                      <div class="col-3">
                        <label for="">City Destination:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="scCollaboration.cityDestination">
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-3">
                        <label for="">Beginning Date:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="date" class= "form-control" v-model="scCollaboration.beginningDate">
                      </div>
                      <div class="col-3">
                        <label for="">Ending Date:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="date" class= "form-control" v-model="scCollaboration.endingDate">
                      </div>
                      <div class="col-3">
                        <label for="">Activity Type:</label>
                        <label for="" style="color: orange;">*</label>
                        <select class="form-select" v-model="scCollaboration.activityType">
                          <option disabled value="">Select a type</option>
                          <option value="International congress">National</option>
                          <option value="National congress">International</option>
                          </select>
                      </div>
                      <div class="col-3">
                        <label for="">Comments:</label>
                        <br>
                        <input type="text" class= "form-control" v-model="scCollaboration.comments">
                      </div>
                    </div>
                  </slot>
                </div>
                <div class="modal-footer">
                  <slot name="footer">
                    <label class="form-check-label"><input type="checkbox" class="form-check-"
                    v-model="draft"> Save as a draft</label>
                    <a v-if="draft == false" class="btn btn-continue float-end" @click="createCollaboration()" :disabled="buttonDisable">
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
      scCollaboration:{
        activityName: '',
        activityType: '',
        researcherInvolved: null,
        institutionCollaborates: '',
        countryOrigin: '',
        cityOrigin: '',
        countryDestination: '',
        cityDestination: '',
        beginningDate: '',
        endingDate: '',
        nameOfAC3EMember: '',
        nameOfExternalResearcher: '',
        studentOrResearcher: '',
        collaborationStay: '',
        comments: '',
        progressReport: '',
      },
      other: '',
      other2: '',
      draft: false,
      researchers: '',
      researchers2: '',
      idResearcher: '',
      buttonDisable: false,
      errors:[],
      buttonText:'Save Collaboration',
    }),
    mounted(){
      this.getUsuarios();
      this.getUsuarios2();
      this.getProgressReport();
    },
    methods: {
      getProgressReport(){
        axios.get('api/showProgressReport').then( response =>{
            this.scCollaboration.progressReport = response.data;
        }).catch(e=> console.log(e))
      },
      getUsuarios(){
        axios.get('api/researchers').then( response =>{
            this.researchers = response.data;
        }).catch(e=> console.log(e))
      },
      getUsuarios2(){
        axios.get('api/usuarios').then( response =>{
            this.researchers2 = response.data;
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
            message: `¿Are you sure you want to save this Sc Collaboration as a draft? this action cannot be undone.`,
            okButton: 'Save',
            cancelButton: 'Return'
          })
          if (ok) {

            var peopleInvolved1 = "";
            if (this.scCollaboration.researcherInvolved !== null){
              if (this.scCollaboration.researcherInvolved.length !== 0) {
                this.scCollaboration.researcherInvolved.forEach((researcherInvolved, index) => {
                  peopleInvolved1 += researcherInvolved.name;
                  if (index === this.scCollaboration.researcherInvolved.length - 1) {
                    peopleInvolved1 += '.';
                  } else {
                    peopleInvolved1 += ', ';
                  }
                });
              }
            }

            var type = '';
            var other1 = 0;
            var type2 = '';
            var other2 = 0;

            if(this.scCollaboration.activityName == 'Other'){
              type = this.other;
              other1 = 1;
            }else{
              type = this.scCollaboration.activityName;
            }

            if(this.scCollaboration.collaborationStay == 'Other'){
              type2 = this.other2;
              other2 = 1;
            }else{
              type2 = this.scCollaboration.collaborationStay;
            }

            var idUser1 = ''
            if(this.idResearcher != ''){
              idUser1 = this.idResearcher;
            }else{
              idUser1 = this.userID;
            }

            let scCollaboration = {
              status: 'Draft',
              idUsuario: idUser1,
              moduleType: 0,
              activityType: this.scCollaboration.activityType,
              institutionCollaborates: this.scCollaboration.institutionCollaborates,
              researcherInvolved: peopleInvolved1,
              studentOrResearcher: this.scCollaboration.studentOrResearcher,
              activityName: type,
              otherActivity: other1,
              countryOrigin: this.scCollaboration.countryOrigin,
              cityOrigin: this.scCollaboration.cityOrigin,
              countryDestination: this.scCollaboration.countryDestination,
              cityDestination: this.scCollaboration.cityDestination,
              beginningDate: this.scCollaboration.beginningDate,
              endingDate: this.scCollaboration.endingDate,
              nameOfAC3EMember: this.scCollaboration.nameOfAC3EMember,
              nameOfExternalResearcher: this.scCollaboration.nameOfExternalResearcher,
              collaborationStay: type2,
              otherStay: other1,
              comments: this.scCollaboration.comments,
              progressReport: this.scCollaboration.progressReport,
            };
            axios.post("api/scCollaborations", scCollaboration, {headers: { 'Content-Type' : 'multipart/form-data' }} ).then((result) => {
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
      async createCollaboration() {
        this.errors = [];
        const itemsToSkip = [
          'comments'
        ];

        for (const item in this.scCollaboration) {
            const skipItem = itemsToSkip.includes(item);
            if (!skipItem && (this.scCollaboration[item] === "" || this.scCollaboration[item] === 0 || this.scCollaboration[item] == null)) {
                this.errors.push(item);
            }
        }

        if(this.scCollaboration.activityName == 'Other' && this.other == ''){
          this.errors.push('other');
        }

        if(this.scCollaboration.collaborationStay == 'Other' && this.other2 == ''){
          this.errors.push('other2');
        }
        
        let scCollaboration1 = {
          status: 'Finished',
          idUsuario: idUser1,
          moduleType: 0,
          activityType: this.scCollaboration.activityType,
          institutionCollaborates: this.scCollaboration.institutionCollaborates,
          researcherInvolved: peopleInvolved1,
          studentOrResearcher: this.scCollaboration.studentOrResearcher,
          activityName: type,
          otherActivity: other1,
          countryOrigin: this.scCollaboration.countryOrigin,
          cityOrigin: this.scCollaboration.cityOrigin,
          countryDestination: this.scCollaboration.countryDestination,
          cityDestination: this.scCollaboration.cityDestination,
          beginningDate: this.scCollaboration.beginningDate,
          endingDate: this.scCollaboration.endingDate,
          nameOfAC3EMember: this.scCollaboration.nameOfAC3EMember,
          nameOfExternalResearcher: this.scCollaboration.nameOfExternalResearcher,
          collaborationStay: type2,
          otherStay: other1,
          comments: this.scCollaboration.comments,
          progressReport: this.scCollaboration.progressReport,
        };

        var contador = await axios.post('../api/verifyCollaboration', scCollaboration1).then(function(response) {
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
            }else if(item == 'researcherInvolved'){
              mensaje =   mensaje + "The field Researcher Involved is required" + "\n";
            }else if(item == 'institutionCollaborates'){
              mensaje =   mensaje + "The field Institution which collaborates is required" + "\n";
            }else if(item == 'countryOrigin'){
              mensaje =   mensaje + "The field Country Origin is required" + "\n";
            }else if(item == 'cityOrigin'){
              mensaje =   mensaje + "The field City Origin is required" + "\n";
            }else if(item == 'countryDestination'){
              mensaje =   mensaje + "The field Country Destination is required" + "\n";
            }else if(item == 'cityDestination'){
              mensaje =   mensaje + "The field City Destination is required" + "\n";
            }else if(item == 'beginningDate'){
              mensaje =   mensaje + "The field Beginning Date is required" + "\n";
            }else if(item == 'endingDate'){
              mensaje =   mensaje + "The field Ending Date is required" + "\n";
            }else if(item == 'progressReport'){
              mensaje =   mensaje + "The field Progress Report line is required" + "\n";
            }else if(item == 'other'){
              mensaje =   mensaje + "The field Other Activity is required" + "\n";
            }else if(item == 'other2'){
              mensaje =   mensaje + "The field Other Stay is required" + "\n";
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
            title: 'Save Collaboration',
            message: `¿Are you sure you want to save this Sc Collaboration? This action cannot be undone.`,
            okButton: 'Save',
            cancelButton: 'Return'
          })
          if (ok) {

            var peopleInvolved1 = "";
            if (this.scCollaboration.researcherInvolved !== null){
              if (this.scCollaboration.researcherInvolved.length !== 0) {
                this.scCollaboration.researcherInvolved.forEach((researcherInvolved, index) => {
                  peopleInvolved1 += researcherInvolved.name;
                  if (index === this.scCollaboration.researcherInvolved.length - 1) {
                    peopleInvolved1 += '.';
                  } else {
                    peopleInvolved1 += ', ';
                  }
                });
              }
            }

            var type = '';
            var other1 = 0;
            var type2 = '';
            var other2 = 0;

            if(this.scCollaboration.activityName == 'Other'){
              type = this.other;
              other1 = 1;
            }else{
              type = this.scCollaboration.activityName;
            }

            if(this.scCollaboration.collaborationStay == 'Other'){
              type2 = this.other2;
              other2 = 1;
            }else{
              type2 = this.scCollaboration.collaborationStay;
            }

            var idUser1 = ''
            if(this.idResearcher != ''){
              idUser1 = this.idResearcher;
            }else{
              idUser1 = this.userID;
            }

            let scCollaboration = {
              status: 'Finished',
              idUsuario: idUser1,
              moduleType: 0,
              activityType: this.scCollaboration.activityType,
              institutionCollaborates: this.scCollaboration.institutionCollaborates,
              researcherInvolved: peopleInvolved1,
              studentOrResearcher: this.scCollaboration.studentOrResearcher,
              activityName: type,
              otherActivity: other1,
              countryOrigin: this.scCollaboration.countryOrigin,
              cityOrigin: this.scCollaboration.cityOrigin,
              countryDestination: this.scCollaboration.countryDestination,
              cityDestination: this.scCollaboration.cityDestination,
              beginningDate: this.scCollaboration.beginningDate,
              endingDate: this.scCollaboration.endingDate,
              nameOfAC3EMember: this.scCollaboration.nameOfAC3EMember,
              nameOfExternalResearcher: this.scCollaboration.nameOfExternalResearcher,
              collaborationStay: type2,
              otherStay: other1,
              comments: this.scCollaboration.comments,
              progressReport: this.scCollaboration.progressReport,
            };
            console.log(scCollaboration);
            axios.post("api/scCollaborations", scCollaboration, {headers: { 'Content-Type' : 'multipart/form-data' }} ).then((result) => {
              this.toast.success("Collaboration saved successfully!", {
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