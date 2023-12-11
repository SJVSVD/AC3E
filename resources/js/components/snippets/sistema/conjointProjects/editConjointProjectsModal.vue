<template>
  <transition name="modalcrear">
    <div name="modal">
      <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container-s">
              <div class="modal-header pb-1 fw-bold" style="color: #444444;">
                <slot name="header">
                    Edit Conjoint Project
                </slot>
                <label for="">Progress year: {{ conjointProject.progressReport }}</label>
                <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
              </div>
              <div class="modal-body">
                <slot name="body">
                    <div class="row">
                          <div class="col-3">
                            <label for="">Activity Name:</label>
                            <label for="" style="color: orange;">*</label>
                            <br>
                            <select class="form-select" v-model="conjointProject.activityName">
                              <option disabled :value="null">Select a type</option>
                              <option value="Visit in Chile (include students)">Visit in Chile (include students)</option>
                              <option value="Visit abroad (include students)">Visit abroad (include students)</option>
                              <option value="Research Stay (Pasantia de investigacion) (include students)">Research Stay (Pasantia de investigacion) (include students)</option>
                              <option value="Participation in R&D Projects directed by other Researcher (external)">Participation in R&D Projects directed by other Researcher (external)</option>
                              <option value="Participation in R&D Projects directed by an AC3E Researcher">Participation in R&D Projects directed by an AC3E Researcher</option>
                              <option value="Other">Other</option>
                              </select>
                          </div>
                          <div v-if="conjointProject.activityName == 'Other'" class="col-3">
                            <label for="">Other Activity:</label>
                            <label for="" style="color: orange;">*</label>
                            <br>
                            <input type="text" class= "form-control" v-model="other">
                          </div>
                          <div class="col-3">
                            <label for="">Collaboration Stay:</label>
                            <label for="" style="color: orange;">*</label>
                            <select class="form-select" v-model="conjointProject.collaborationStay">
                              <option disabled :value="null">Select a type</option>
                              <option value="Short Visit (Up to two weeks)">Short Visit (Up to two weeks)</option>
                              <option value="Long Visit (More than two weeks)">Long Visit (More than two weeks)</option>
                              <option value="Other">Other</option>
                              </select>
                          </div>
                          <div v-if="conjointProject.collaborationStay == 'Other'" class="col-3">
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
                          v-model="conjointProject.researcherInvolved"
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
                        <input type="text" class= "form-control" v-model="conjointProject.institutionCollaborates">
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-5">
                        <label for="">Name of AC3E member:</label>
                        <label for="" style="color: orange;">*</label>
                        <select class="form-select" v-model="conjointProject.nameOfAC3EMember">
                        <option disabled :value="null">Select a member</option>
                        <option v-for="researcher in researchers2" v-bind:key="researcher.id" v-bind:value="researcher.id">
                          {{ researcher.name }}
                        </option>
                        </select>
                      </div>
                      <div class="col-4">
                        <label v-if="conjointProject.studentOrResearcher == 'Researcher'" for="">Name of external researcher:</label>
                        <label v-else for="">Name of external person:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="conjointProject.nameOfExternalResearcher">
                      </div>
                      <div class="col-3">
                            <label for="">Student or Researcher:</label>
                            <label for="" style="color: orange;">*</label>
                            <br>
                            <select class="form-select" v-model="conjointProject.studentOrResearcher">
                              <option disabled :value="null">Select a type</option>
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
                        <input type="text" class= "form-control" v-model="conjointProject.countryOrigin">
                      </div>
                      <div class="col-3">
                        <label for="">City Origin:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="conjointProject.cityOrigin">
                      </div>
                      <div class="col-3">
                        <label for="">Country Destination:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="conjointProject.countryDestination">
                      </div>
                      <div class="col-3">
                        <label for="">City Destination:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="text" class= "form-control" v-model="conjointProject.cityDestination">
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-3">
                        <label for="">Beginning Date:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="date" class= "form-control" v-model="conjointProject.beginningDate">
                      </div>
                      <div class="col-3">
                        <label for="">Ending Date:</label>
                        <label for="" style="color: orange;">*</label>
                        <br>
                        <input type="date" class= "form-control" v-model="conjointProject.endingDate">
                      </div>
                      <div class="col-3">
                        <label for="">Activity Type:</label>
                        <label for="" style="color: orange;">*</label>
                        <select class="form-select" v-model="conjointProject.activityType">
                          <option disabled :value="null">Select a type</option>
                          <option value="International congress">National</option>
                          <option value="National congress">International</option>
                          </select>
                      </div>
                      <div class="col-3">
                        <label for="">Comments:</label>
                        <br>
                        <input type="text" class= "form-control" v-model="conjointProject.comments">
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
      conjointProject:{
        activityType: '',
        activityName: '',
        researcherInvolved: null,
        institutionCollaborates: '',
        countryOrigin: '',
        cityOrigin: '',
        countryDestination: '',
        cityDestination: '',
        beginningDate: '',
        endingDate: '',
        progressReport: '',
      },

      id: '',
      other: '',
      draft: false,
      researchers: '',
      buttonDisable: false,
      errors:[],
      buttonText:'Edit Project',
    }),
    mounted(){
      this.getUsuarios();
    },
    props:{
      project1: Object,
    },
    created(){
      this.id = this.project1.id;

      this.conjointProject.activityType = this.project1.activityType;
      this.conjointProject.activityName = this.project1.activityName;
      this.conjointProject.institutionCollaborates = this.project1.institutionCollaborates;
      this.conjointProject.countryOrigin = this.project1.countryOrigin;
      this.conjointProject.cityOrigin = this.project1.cityOrigin;
      this.conjointProject.countryDestination = this.project1.countryDestination;
      this.conjointProject.cityDestination = this.project1.cityDestination;
      this.conjointProject.beginningDate = this.project1.beginningDate;
      this.conjointProject.endingDate = this.project1.endingDate;
      this.conjointProject.nameOfAC3EMember = this.project1.nameOfAC3EMember;
      this.conjointProject.nameOfExternalResearcher = this.project1.nameOfExternalResearcher;
      this.conjointProject.comments = this.project1.comments;
      this.conjointProject.progressReport = this.project1.progressReport;
      this.conjointProject.studentOrResearcher = this.project1.studentOrResearcher;

      if (this.project1.researcherInvolved != null) {
          const valoresSeparados1 = this.project1.researcherInvolved.split(",");
          this.conjointProject.researcherInvolved = valoresSeparados1.map((valor, index) => {
              valor = valor.trim();
              if (valor.endsWith('.')) {
                  valor = valor.slice(0, -1);
              }

              return { value: valor, name: valor };
          });
      }

      if(this.project1.otherActivity == true){
        this.conjointProject.activityName = 'Other';
        this.other = this.project1.activityName;
      }else{
        this.conjointProject.activityName = this.project1.activityName;
      }

      if(this.project1.otherStay == true){
        this.conjointProject.collaborationStay = 'Other';
        this.other2 = this.project1.collaborationStay;
      }else{
        this.conjointProject.collaborationStay = this.project1.collaborationStay;
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
      async guardarBorrador(){
        const ok = await this.$refs.confirmation.show({
            title: 'Edit draft',
            message: `¿Are you sure you want to edit this Conjoint Project as a draft? this action cannot be undone.`,
            okButton: 'Edit',
            cancelButton: 'Return'
          })
          if (ok) {
            var peopleInvolved1 = "";
            if (this.conjointProject.researcherInvolved !== null){
              if (this.conjointProject.researcherInvolved.length !== 0) {
                this.conjointProject.researcherInvolved.forEach((researcherInvolved, index) => {
                  peopleInvolved1 += researcherInvolved.name;
                  if (index === this.conjointProject.researcherInvolved.length - 1) {
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

            if(this.conjointProject.activityName == 'Other'){
              type = this.other;
              other1 = 1;
            }else{
              type = this.conjointProject.activityName;
            }

            if(this.conjointProject.collaborationStay == 'Other'){
              type2 = this.other2;
              other2 = 1;
            }else{
              type2 = this.conjointProject.collaborationStay;
            }

            let conjointProject = {
              status: 'Draft',
              moduleType: 1,
              activityType: this.conjointProject.activityType,
              institutionCollaborates: this.conjointProject.institutionCollaborates,
              researcherInvolved: peopleInvolved1,
              studentOrResearcher: this.conjointProject.studentOrResearcher,
              activityName: type,
              otherActivity: other1,
              countryOrigin: this.conjointProject.countryOrigin,
              cityOrigin: this.conjointProject.cityOrigin,
              countryDestination: this.conjointProject.countryDestination,
              cityDestination: this.conjointProject.cityDestination,
              beginningDate: this.conjointProject.beginningDate,
              endingDate: this.conjointProject.endingDate,
              nameOfAC3EMember: this.conjointProject.nameOfAC3EMember,
              nameOfExternalResearcher: this.conjointProject.nameOfExternalResearcher,
              collaborationStay: type2,
              otherStay: other1,
              comments: this.conjointProject.comments,
              progressReport: this.conjointProject.progressReport,
            };
            axios.put(`api/conjointProjects/${this.id}`, conjointProject ).then((result) => {
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

        for (const item in this.conjointProject) {
            const skipItem = itemsToSkip.includes(item);
            if (!skipItem && (this.conjointProject[item] === "" || this.conjointProject[item] === 0 || this.conjointProject[item] == null)) {
                this.errors.push(item);
            }
        }

        if(this.conjointProject.activityName == 'Other' && this.other == ''){
          this.errors.push('other');
        }

        if(this.conjointProject.collaborationStay == 'Other' && this.other2 == ''){
          this.errors.push('other2');
        }

        let conjointProject1 = {
          id: this.id,
          status: 'Finished',
          idUsuario: idUser1,
          moduleType: 1,
          activityType: this.conjointProject.activityType,
          institutionCollaborates: this.conjointProject.institutionCollaborates,
          researcherInvolved: peopleInvolved1,
          studentOrResearcher: this.conjointProject.studentOrResearcher,
          activityName: type,
          otherActivity: other1,
          countryOrigin: this.conjointProject.countryOrigin,
          cityOrigin: this.conjointProject.cityOrigin,
          countryDestination: this.conjointProject.countryDestination,
          cityDestination: this.conjointProject.cityDestination,
          beginningDate: this.conjointProject.beginningDate,
          endingDate: this.conjointProject.endingDate,
          nameOfAC3EMember: this.conjointProject.nameOfAC3EMember,
          nameOfExternalResearcher: this.conjointProject.nameOfExternalResearcher,
          collaborationStay: type2,
          otherStay: other1,
          comments: this.conjointProject.comments,
          progressReport: this.conjointProject.progressReport,
        };

        var contador = await axios.post('../api/verifyConjoint', conjointProject1).then(function(response) {
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
            }else if(item == 'nameOfAc3eMember'){
              mensaje =   mensaje + "The field Name of ac3e member is required" + "\n";
            }else if(item == 'nameOfExternalResearcher'){
              mensaje =   mensaje + "The field Name of external researcher is required" + "\n";
            }else if(item == 'studentOrResearcher'){
              mensaje =   mensaje + "The field Student or Researcher is required" + "\n";
            }else if(item == 'collaborationStay'){
              mensaje =   mensaje + "The field Collaboration Stay is required" + "\n";
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
            title: 'Edit Project',
            message: `¿Are you sure you want to edit this Conjoint Project? This action cannot be undone.`,
            okButton: 'Edit',
            cancelButton: 'Return'
          })
          if (ok) {

            var peopleInvolved1 = "";
            if (this.conjointProject.researcherInvolved !== null){
              if (this.conjointProject.researcherInvolved.length !== 0) {
                this.conjointProject.researcherInvolved.forEach((researcherInvolved, index) => {
                  peopleInvolved1 += researcherInvolved.name;
                  if (index === this.conjointProject.researcherInvolved.length - 1) {
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

            if(this.conjointProject.activityName == 'Other'){
              type = this.other;
              other1 = 1;
            }else{
              type = this.conjointProject.activityName;
            }

            if(this.conjointProject.collaborationStay == 'Other'){
              type2 = this.other2;
              other2 = 1;
            }else{
              type2 = this.conjointProject.collaborationStay;
            }

            let conjointProject = {
              status: 'Finished',
              moduleType: 1,
              activityType: this.conjointProject.activityType,
              institutionCollaborates: this.conjointProject.institutionCollaborates,
              researcherInvolved: peopleInvolved1,
              studentOrResearcher: this.conjointProject.studentOrResearcher,
              activityName: type,
              otherActivity: other1,
              countryOrigin: this.conjointProject.countryOrigin,
              cityOrigin: this.conjointProject.cityOrigin,
              countryDestination: this.conjointProject.countryDestination,
              cityDestination: this.conjointProject.cityDestination,
              beginningDate: this.conjointProject.beginningDate,
              endingDate: this.conjointProject.endingDate,
              nameOfAC3EMember: this.conjointProject.nameOfAC3EMember,
              nameOfExternalResearcher: this.conjointProject.nameOfExternalResearcher,
              collaborationStay: type2,
              otherStay: other1,
              comments: this.conjointProject.comments,
              progressReport: this.conjointProject.progressReport,
            };
            console.log(conjointProject);
            axios.put(`api/conjointProjects/${this.id}`, conjointProject ).then((result) => {
              this.toast.success("Project edited successfully!", {
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