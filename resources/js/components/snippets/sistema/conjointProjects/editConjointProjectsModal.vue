<template>
  <transition name="modalcrear">
    <div name="modal">
      <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container-s">
              <div class="modal-header pb-1 fw-bold" style="color: #444444;">
                <slot name="header">
                    Edit conjoint project
                </slot>
                <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
              </div>
              <div class="modal-body">
                <slot name="body">
                    <div class="row">
                          <div class="col-3">
                            <label for="">Activity Type:</label>
                            <select class="form-select" v-model="conjointProject.activityType">
                              <option disabled :value="null">Select a type</option>
                              <option value="International congress">National</option>
                              <option value="National congress">International</option>
                              </select>
                          </div>
                          <div class="col-3">
                            <label for="">Collaboration Type:</label>
                            <select class="form-select" v-model="conjointProject.collaborationType">
                              <option disabled :value="null">Select a type</option>
                              <option value="Short Visit (Up to two weeks)">Short Visit (Up to two weeks)</option>
                              <option value="Long Visit (More than two weeks)">Long Visit (More than two weeks)</option>
                              <option value="Other">Other</option>
                              </select>
                          </div>
                          <div v-if="conjointProject.collaborationType == 'Other'" class="col-3">
                            <label for="">Other:</label>
                            <br>
                            <input type="text" class= "form-control" v-model="other">
                          </div>
                          <div class="col-3">
                            <label for="">Activity Name:</label>
                            <br>
                            <input type="text" class= "form-control" v-model="conjointProject.activityName">
                          </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-6">
                        <label for="">Name of people involved:</label>
                        <Multiselect
                          placeholder="Select the participants"
                          v-model="conjointProject.peopleInvolved"
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
                        <br>
                        <input type="text" class= "form-control" v-model="conjointProject.institutionCollaborates">
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-3">
                        <label for="">Country Origin:</label>
                        <br>
                        <input type="text" class= "form-control" v-model="conjointProject.countryOrigin">
                      </div>
                      <div class="col-3">
                        <label for="">City Origin:</label>
                        <br>
                        <input type="text" class= "form-control" v-model="conjointProject.cityOrigin">
                      </div>
                      <div class="col-3">
                        <label for="">Country Destination:</label>
                        <br>
                        <input type="text" class= "form-control" v-model="conjointProject.countryDestination">
                      </div>
                      <div class="col-3">
                        <label for="">City Destination:</label>
                        <br>
                        <input type="text" class= "form-control" v-model="conjointProject.cityDestination">
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-3">
                        <label for="">Beginning Date:</label>
                        <br>
                        <input type="date" class= "form-control" v-model="conjointProject.beginningDate">
                      </div>
                      <div class="col-3">
                        <label for="">Ending Date:</label>
                        <br>
                        <input type="date" class= "form-control" v-model="conjointProject.endingDate">
                      </div>
                      <div class="col-6">
                        <label for="">Name of research line:</label>
                        <Multiselect
                          placeholder="Select the options"
                          v-model="conjointProject.nameOfResearch"
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
                    </div>
                    <br>
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
        collaborationType: '',
        activityName: '',
        peopleInvolved: null,
        institutionCollaborates: '',
        countryOrigin: '',
        cityOrigin: '',
        countryDestination: '',
        cityDestination: '',
        beginningDate: '',
        endingDate: '',
        nameOfResearch: null,
        progressReport: 9,
      },
      options1: [
        'Biomedical Systems',
        'Control and Automation',
        'Data Analytics and Artificial Intelligence',
        'Electrical Systems',
        'Energy Conversion and Power Systems',
        'Instrumentation',
      ],
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
      collaboration1: Object,
    },
    created(){
      this.id = this.collaboration1.id;

      this.conjointProject.activityType = this.collaboration1.activityType;
      this.conjointProject.activityName = this.collaboration1.activityName;
      this.conjointProject.institutionCollaborates = this.collaboration1.institutionCollaborates;
      this.conjointProject.countryOrigin = this.collaboration1.countryOrigin;
      this.conjointProject.cityOrigin = this.collaboration1.cityOrigin;
      this.conjointProject.countryDestination = this.collaboration1.countryDestination;
      this.conjointProject.cityDestination = this.collaboration1.cityDestination;
      this.conjointProject.beginningDate = this.collaboration1.beginningDate;
      this.conjointProject.endingDate = this.collaboration1.endingDate;

      if (this.collaboration1.nameOfResearch != null) {
          const valoresSeparados1 = this.collaboration1.nameOfResearch.split(",");
          this.conjointProject.nameOfResearch = valoresSeparados1.map((valor, index) => {
              valor = valor.trim();
              if (valor.endsWith('.')) {
                  valor = valor.slice(0, -1);
              }

              return { value: valor, name: valor };
          });
      }

      if (this.collaboration1.peopleInvolved != null) {
          const valoresSeparados1 = this.collaboration1.peopleInvolved.split(",");
          this.conjointProject.peopleInvolved = valoresSeparados1.map((valor, index) => {
              valor = valor.trim();
              if (valor.endsWith('.')) {
                  valor = valor.slice(0, -1);
              }

              return { value: valor, name: valor };
          });
      }

      if(this.collaboration1.otherCollaboration == true){
        this.conjointProject.collaborationType = 'Other';
        this.other = this.collaboration1.collaborationType;
      }else{
        this.conjointProject.collaborationType = this.collaboration1.collaborationType;
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
            message: `¿Are you sure you want to edit this conjoint project as a draft? this action cannot be undone.`,
            okButton: 'Save',
            cancelButton: 'Return'
          })
          if (ok) {
            var nameOfResearchLine1 = "";
            if (this.conjointProject.nameOfResearch !== null){
              if (this.conjointProject.nameOfResearch.length !== 0) {
                this.conjointProject.nameOfResearch.forEach((nameOfResearch, index) => {
                  nameOfResearchLine1 += nameOfResearch.name;
                  if (index === this.conjointProject.nameOfResearch.length - 1) {
                    nameOfResearchLine1 += '.';
                  } else {
                    nameOfResearchLine1 += ', ';
                  }
                });
              }
            }

            var peopleInvolved1 = "";
            if (this.conjointProject.peopleInvolved !== null){
              if (this.conjointProject.peopleInvolved.length !== 0) {
                this.conjointProject.peopleInvolved.forEach((peopleInvolved, index) => {
                  peopleInvolved1 += peopleInvolved.name;
                  if (index === this.conjointProject.peopleInvolved.length - 1) {
                    peopleInvolved1 += '.';
                  } else {
                    peopleInvolved1 += ', ';
                  }
                });
              }
            }

            var type = '';
            var other1 = false;

            if(this.conjointProject.collaborationType == 'Other'){
              type = this.other;
              other1 = true;
            }else{
              type = this.conjointProject.collaborationType;
            }

            let conjointProject = {
              status: 'Draft',
              activityType: this.conjointProject.activityType,
              collaborationType: type,
              otherCollaboration: other1,
              institutionCollaborates: this.conjointProject.institutionCollaborates,
              peopleInvolved: peopleInvolved1,
              activityName: this.conjointProject.activityName,
              countryOrigin: this.conjointProject.countryOrigin,
              cityOrigin: this.conjointProject.cityOrigin,
              countryDestination: this.conjointProject.countryDestination,
              cityDestination: this.conjointProject.cityDestination,
              beginningDate: this.conjointProject.beginningDate,
              endingDate: this.conjointProject.endingDate,
              nameOfResearch: nameOfResearchLine1,
              progressReport: this.conjointProject.progressReport,
            };
            axios.put(`api/conjointProjects/${this.id}`, conjointProject ).then((result) => {
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
        for (const item in this.conjointProject){
          if(this.conjointProject[item] === "" || this.conjointProject[item] === 0 || this.conjointProject[item] == null || this.conjointProject[item] == []){
                this.errors.push(item);
            }
        }

        if(this.conjointProject.collaborationType == 'Other' && this.other == ''){
          this.errors.push('other');
        }

        var mensaje = ""
        if (this.errors.length != 0){
          this.errors.forEach(item => {
            if(item == 'activityType'){
              mensaje =   mensaje + "The field Activity Type is required" + "\n";
            }else if(item == 'collaborationType'){
              mensaje =   mensaje + "The field Collaboration Type is required" + "\n";
            }else if(item == 'activityName'){
              mensaje =   mensaje + "The field Activity Name is required" + "\n";
            }else if(item == 'peopleInvolved'){
              mensaje =   mensaje + "The field People Involved is required" + "\n";
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
            title: 'Edit Project',
            message: `¿Are you sure you want to edit this conjoint project? This action cannot be undone.`,
            okButton: 'Edit',
            cancelButton: 'Return'
          })
          if (ok) {
            var nameOfResearchLine1 = "";
            if (this.conjointProject.nameOfResearch !== null){
              if (this.conjointProject.nameOfResearch.length !== 0) {
                this.conjointProject.nameOfResearch.forEach((nameOfResearch, index) => {
                  nameOfResearchLine1 += nameOfResearch.name;
                  if (index === this.conjointProject.nameOfResearch.length - 1) {
                    nameOfResearchLine1 += '.';
                  } else {
                    nameOfResearchLine1 += ', ';
                  }
                });
              }
            }

            var peopleInvolved1 = "";
            if (this.conjointProject.peopleInvolved !== null){
              if (this.conjointProject.peopleInvolved.length !== 0) {
                this.conjointProject.peopleInvolved.forEach((peopleInvolved, index) => {
                  peopleInvolved1 += peopleInvolved.name;
                  if (index === this.conjointProject.peopleInvolved.length - 1) {
                    peopleInvolved1 += '.';
                  } else {
                    peopleInvolved1 += ', ';
                  }
                });
              }
            }

            var typeCollaboration = '';
            var other1 = false;

            if(this.conjointProject.collaborationType == 'Other'){
              typeCollaboration = this.other;
              other1 = true;
            }else{
              typeCollaboration = this.conjointProject.collaborationType;
            }

            let conjointProject = {
              status: 'Finished',
              idUsuario: this.userID,
              activityType: this.conjointProject.activityType,
              collaborationType: typeCollaboration,
              otherCollaboration: other1,
              institutionCollaborates: this.conjointProject.institutionCollaborates,
              peopleInvolved: peopleInvolved1,
              activityName: this.conjointProject.activityName,
              countryOrigin: this.conjointProject.countryOrigin,
              cityOrigin: this.conjointProject.cityOrigin,
              countryDestination: this.conjointProject.countryDestination,
              cityDestination: this.conjointProject.cityDestination,
              beginningDate: this.conjointProject.beginningDate,
              endingDate: this.conjointProject.endingDate,
              nameOfResearch: nameOfResearchLine1,
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