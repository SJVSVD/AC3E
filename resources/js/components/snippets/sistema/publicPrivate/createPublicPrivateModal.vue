<template>
  <transition name="modalcrear">
    <div name="modal">
      <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container-s">
              <div class="modal-header pb-1 fw-bold" style="color: #444444;">
                <slot name="header">
                    New connection 
                </slot>
                <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
              </div>
              <div class="modal-body">
                <slot name="body">
                    <div class="row">
                      <div class="col-4">
                        <label for="">Name of activity:</label>
                        <br>
                        <input type="text" class= "form-control" v-model="publicPrivate.nameOfActivity">
                      </div>
                      <div class="col-4">
                        <label for="">Name of organization:</label>
                        <br>
                        <input type="text" class= "form-control" v-model="publicPrivate.nameOfOrganization">
                      </div>
                      <div class="col-4">
                        <label for="">Country of origin:</label>
                        <br>
                        <input type="text" class= "form-control" v-model="publicPrivate.countryOrigin">
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="text-uppercase pb-2">Types of activity:</div>
                      <div class="col-4">
                          <div class="form-check pt-2 ">
                            <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                  v-model="publicPrivate.developmentalStudies"> Developmental studies</label>
                          </div>
                      </div>
                      <div class="col-4">
                          <div class="form-check pt-2 ">
                            <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                  v-model="publicPrivate.projectImplementation"> Project implementation</label>
                          </div>
                      </div>
                      <div class="col-4">
                          <div class="form-check pt-2 ">
                            <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                  v-model="publicPrivate.training"> Training</label>
                          </div>
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-4">
                          <div class="form-check pt-2 ">
                            <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                  v-model="publicPrivate.prospectiveActivity"> Prospective activity</label>
                          </div>
                      </div>
                      <div class="col-4">
                          <div class="form-check pt-2 ">
                            <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                  v-model="publicPrivate.installationOfScientist">  Installation of scientist</label>
                          </div>
                      </div>
                      <div class="col-4">
                          <div class="form-check pt-2 ">
                            <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                  v-model="publicPrivate.temporaryInstallation"> Temporary installation</label>
                          </div>
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-4">
                          <div class="form-check pt-2 ">
                            <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                  v-model="publicPrivate.internationalAgreements"> International agreements of exchange</label>
                          </div>
                      </div>
                      <div class="col-4">
                          <div class="form-check pt-2 ">
                            <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                  v-model="publicPrivate.participationsInInstances"> Participations in instances of public policity</label>
                          </div>
                      </div>
                      <div class="col-4">
                          <div class="form-check pt-2 ">
                            <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                  v-model="publicPrivate.internationalExchange"> International exchange networks</label>
                          </div>
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-3">
                        <label for="">Start date:</label>
                        <br>
                        <input type="date" class= "form-control" v-model="publicPrivate.startDate">
                      </div>
                      <div class="col-3">
                        <label for="">Ending date:</label>
                        <br>
                        <input type="date" class= "form-control" v-model="publicPrivate.endingDate">
                      </div>
                      <div class="col-6">
                          <label for="">Results/Goals:</label>
                          <br>
                          <textarea class= "form-control" v-model="publicPrivate.resultsGoals" style="resize: none;" cols="30" rows="5"></textarea>
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="text-uppercase pb-2">Agent type:</div>
                      <div class="col-3">
                          <div class="form-check pt-2 ">
                            <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                  v-model="publicPrivate.privateSector" @change="checkInputs('privateSector')"> Private sector</label>
                          </div>
                      </div>
                      <div class="col-3">
                          <div class="form-check pt-2 ">
                            <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                  v-model="publicPrivate.academy1" @change="checkInputs('private')"> Academy</label>
                          </div>
                      </div>
                      <div class="col-2">
                          <div class="form-check pt-2 ">
                            <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                  v-model="publicPrivate.business" @change="checkInputs('private')"> Business </label>
                          </div>
                      </div>
                      <div class="col-4">
                          <div class="form-check pt-2 ">
                            <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                  v-model="publicPrivate.ownEntrepreneurship" @change="checkInputs('private')"> Own entrepreneurship </label>
                          </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-3">
                          <div class="form-check pt-2 ">
                            <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                  v-model="publicPrivate.publicSector"  @change="checkInputs('publicSector')"> Public sector</label>
                          </div>
                      </div>
                      <div class="col-2">
                          <div class="form-check pt-2 ">
                            <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                  v-model="publicPrivate.government" @change="checkInputs('public')"> Government</label>
                          </div>
                      </div>
                      <div class="col-2">
                          <div class="form-check pt-2 ">
                            <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                  v-model="publicPrivate.academy2" @change="checkInputs('public')"> Academy </label>
                          </div>
                      </div>
                      <div class="col-2">
                          <div class="form-check pt-2 ">
                            <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                  v-model="publicPrivate.socialOng" @change="checkInputs('public')"> Social-ONG </label>
                          </div>
                      </div>
                      <div class="col-2">
                          <div class="form-check pt-2 ">
                            <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                  v-model="publicPrivate.education" @change="checkInputs('public')"> Education </label>
                          </div>
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-12">
                        <label for="">Comments:</label>
                        <br>
                        <input type="text" class= "form-control" v-model="publicPrivate.comments">
                      </div>
                    </div>
                  </slot>
                </div>
                <div class="modal-footer">
                  <slot name="footer">
                    <label class="form-check-label"><input type="checkbox" class="form-check-"
                    v-model="draft"> Save as a draft</label>
                    <a v-if="draft == false" class="btn btn-continue float-end" @click="createPublicPrivate()" :disabled="buttonDisable">
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
      publicPrivate:{
        nameOfActivity: '',
        developmentalStudies: false,
        projectImplementation: false,
        training: false,
        prospectiveActivity: false,
        installationOfScientist: false,
        temporaryInstallation: false,
        internationalAgreements: false,
        participationsInInstances: false,
        internationalExchange: false,
        startDate: '',
        endingDate: '',
        resultsGoals: '',
        nameOfOrganization: '',
        countryOrigin: '',
        privateSector: false,
        academy1: false,
        business: false,
        ownEntrepreneurship: false,
        publicSector: false,
        government: false,
        academy2: false,
        socialOng: false,
        education: false,
        comments: '',
        progressReport: 9,
      },
      other: '',
      draft: false,
      researchers: '',
      buttonDisable: false,
      errors:[],
      buttonText:'Save connection',
    }),
    methods: {
      checkInputs(input) {
        if (input === 'private') {
          if (this.publicPrivate.academy1 || this.publicPrivate.business || this.publicPrivate.ownEntrepreneurship) {
            this.publicPrivate.privateSector = true;
          } else {
            this.publicPrivate.privateSector = false;
          }
        }

        if (input === 'public') {
          if (this.publicPrivate.government || this.publicPrivate.academy2 || this.publicPrivate.socialOng || this.publicPrivate.education) {
            this.publicPrivate.publicSector = true;
          } else {
            this.publicPrivate.publicSector = false;
          }
        }

        if (input === 'privateSector') {
          if (this.publicPrivate.privateSector) {
            this.publicPrivate.academy1 = true;
            this.publicPrivate.business = true;
            this.publicPrivate.ownEntrepreneurship = true;
          } else {
            this.publicPrivate.academy1 = false;
            this.publicPrivate.business = false;
            this.publicPrivate.ownEntrepreneurship = false;
          }
        }

        if (input === 'publicSector') {
          if (this.publicPrivate.publicSector) {
            this.publicPrivate.government = true;
            this.publicPrivate.academy2 = true;
            this.publicPrivate.socialOng = true;
            this.publicPrivate.education = true;
          } else {
            this.publicPrivate.government = false;
            this.publicPrivate.academy2 = false;
            this.publicPrivate.socialOng = false;
            this.publicPrivate.education = false;
          }
        }
      },
      async guardarBorrador(){
        const ok = await this.$refs.confirmation.show({
            title: 'Save draft',
            message: `¿Are you sure you want to save this Public private connection as a draft? this action cannot be undone.`,
            okButton: 'Save',
            cancelButton: 'Return'
          })
          if (ok) {
            let publicPrivate = {
              status: 'Draft',
              idUsuario: this.userID,
              nameOfActivity: this.publicPrivate.nameOfActivity,
              developmentalStudies: this.publicPrivate.developmentalStudies,
              projectImplementation: this.publicPrivate.projectImplementation,
              training: this.publicPrivate.training,
              prospectiveActivity: this.publicPrivate.prospectiveActivity,
              installationOfScientist: this.publicPrivate.installationOfScientist,
              temporaryInstallation: this.publicPrivate.temporaryInstallation,
              internationalAgreements: this.publicPrivate.internationalAgreements,
              participationsInInstances: this.publicPrivate.participationsInInstances,
              internationalExchange: this.publicPrivate.internationalExchange,
              startDate: this.publicPrivate.startDate,
              endingDate: this.publicPrivate.endingDate,
              resultsGoals: this.publicPrivate.resultsGoals,
              nameOfOrganization: this.publicPrivate.nameOfOrganization,
              countryOrigin: this.publicPrivate.countryOrigin,
              privateSector: this.publicPrivate.privateSector,
              academy1: this.publicPrivate.academy1,
              business: this.publicPrivate.business,
              ownEntrepreneurship: this.publicPrivate.ownEntrepreneurship,
              publicSector: this.publicPrivate.publicSector,
              government: this.publicPrivate.government,
              academy2: this.publicPrivate.academy2,
              socialOng: this.publicPrivate.socialOng,
              education: this.publicPrivate.education,
              comments: this.publicPrivate.comments,
              progressReport: this.publicPrivate.progressReport,
            };
            axios.post("api/publicPrivate", publicPrivate).then((result) => {
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
      async createPublicPrivate() {
        this.errors = [];

        const itemsToCheck = [
          'developmentalStudies',
          'projectImplementation',
          'training',
          'prospectiveActivity',
          'installationOfScientist',
          'temporaryInstallation',
          'internationalAgreements',
          'participationsInInstances',
          'internationalExchange',
          'privateSector',
          'academy1',
          'business',
          'ownEntrepreneurship',
          'publicSector',
          'government',
          'academy2',
          'socialOng',
          'education',
        ];


        for (const item in this.publicPrivate){
          if(this.publicPrivate[item] === "" || this.publicPrivate[item] === 0 || this.publicPrivate[item] == null || this.publicPrivate[item] == []){
            if (itemsToCheck.includes(item)) {
              } else {
                this.errors.push(item);
              }
            }
        }

        var mensaje = ""
        if (this.errors.length != 0){
          this.errors.forEach(item => {
            if(item == 'nameOfActivity'){
              mensaje =   mensaje + "The field Name of activity is required" + "\n";
            }else if(item == 'startDate'){
              mensaje =   mensaje + "The field Start date is required" + "\n";
            }else if(item == 'endingDate'){
              mensaje =   mensaje + "The field Ending date is required" + "\n";
            }else if(item == 'resultsGoals'){
              mensaje =   mensaje + "The field Results/Goals is required" + "\n";
            }else if(item == 'nameOfOrganization'){
              mensaje =   mensaje + "The field Name of organization is required" + "\n";
            }else if(item == 'countryOrigin'){
              mensaje =   mensaje + "The field Country origin is required" + "\n";
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
            title: 'Save Public private connection',
            message: `¿Are you sure you want to save this Public private connection? This action cannot be undone.`,
            okButton: 'Save',
            cancelButton: 'Return'
          })
          if (ok) {

            let publicPrivate = {
              status: 'Finished',
              idUsuario: this.userID,
              nameOfActivity: this.publicPrivate.nameOfActivity,
              developmentalStudies: this.publicPrivate.developmentalStudies,
              projectImplementation: this.publicPrivate.projectImplementation,
              training: this.publicPrivate.training,
              prospectiveActivity: this.publicPrivate.prospectiveActivity,
              installationOfScientist: this.publicPrivate.installationOfScientist,
              temporaryInstallation: this.publicPrivate.temporaryInstallation,
              internationalAgreements: this.publicPrivate.internationalAgreements,
              participationsInInstances: this.publicPrivate.participationsInInstances,
              internationalExchange: this.publicPrivate.internationalExchange,
              startDate: this.publicPrivate.startDate,
              endingDate: this.publicPrivate.endingDate,
              resultsGoals: this.publicPrivate.resultsGoals,
              nameOfOrganization: this.publicPrivate.nameOfOrganization,
              countryOrigin: this.publicPrivate.countryOrigin,
              privateSector: this.publicPrivate.privateSector,
              academy1: this.publicPrivate.academy1,
              business: this.publicPrivate.business,
              ownEntrepreneurship: this.publicPrivate.ownEntrepreneurship,
              publicSector: this.publicPrivate.publicSector,
              government: this.publicPrivate.government,
              academy2: this.publicPrivate.academy2,
              socialOng: this.publicPrivate.socialOng,
              education: this.publicPrivate.education,
              comments: this.publicPrivate.comments,
              progressReport: this.publicPrivate.progressReport,
            };
            axios.post("api/publicPrivate", publicPrivate).then((result) => {
              this.toast.success("Public private connection saved successfully!", {
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