<template>
  <transition name="modalcrear">
    <div name="modal">
      <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container-s">
              <div class="modal-header pb-1 fw-bold" style="color: #444444;">
                <slot name="header">
                    Edit Funding source
                </slot>
                <label for="">Progress year: {{ fundingSource.progressReport }}</label>
                <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
              </div>
              <div class="modal-body">
                <slot name="body">
                  <div class="row">
                      <div class="col-3">
                          <label for="">Type of sources: </label>
                          <br>
                          <select class="form-select" v-model="fundingSource.typeSources">
                            <option disabled :value="null">Select a type</option>
                            <option value="Public funds">Public funds</option>
                            <option value="Productive/Private funds">Productive/Private funds</option>
                            <option value="International funds">International funds</option>
                            </select>
                      </div>
                      <div class="col-3">
                        <label for="">Name of institution:</label>
                        <br>
                        <input type="text" class= "form-control" v-model="fundingSource.nameOfInstitution">
                      </div>
                      <div class="col-3">
                        <label for="">Program/contest:</label>
                        <br>
                        <input type="text" class= "form-control" v-model="fundingSource.programContest">
                      </div>
                      <div class="col-3">
                        <label for="">Project title:</label>
                        <br>
                        <input type="text" class= "form-control" v-model="fundingSource.projectTitle">
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-6">
                        <label for="">Principal researcher:</label>
                        <Multiselect
                          placeholder="Select the options"
                          v-model="fundingSource.principalResearcher"
                          limit=4
                          :searchable="true"
                          :close-on-select="false"
                          :createTag="true"
                          :options="researchers"
                          mode="tags"
                          label="name"
                          trackBy="name"
                          :object="true"
                        />
                      </div>
                      <div class="col-3">
                        <label for="">Start date:</label>
                        <br>
                        <input type="date" class= "form-control" v-model="fundingSource.startDate">
                      </div>
                      <div class="col-3">
                        <label for="">Finish date:</label>
                        <br>
                        <input type="date" class= "form-control" v-model="fundingSource.finishDate">
                      </div>
                    </div>
                    <br>
                    <div class="row">
                      <div class="col-3">
                        <label for="">In cash:</label>
                        <br>
                        <input type="number" class= "form-control" v-model="fundingSource.inCash">
                      </div>
                      <div class="col-3">
                          <label for="">Type of collaboration: </label>
                          <br>
                          <select class="form-select" v-model="fundingSource.typeOfCollaboration">
                            <option disabled :value="null">Select a type</option>
                            <option value="Technical assitance">Technical assitance</option>
                            <option value="Patents">Patents</option>
                            <option value="License">License</option>
                            <option value="Grand/Competitive fund">Grand/Competitive fund</option>
                            <option value="Other (Specify in comments)">Other (Specify in comments)</option>
                            </select>
                      </div>
                      <div class="col-6">
                        <label for="">Comments:</label>
                        <br>
                        <input type="text" class= "form-control" v-model="fundingSource.comments">
                      </div>
                    </div>
                  </slot>
                </div>
                <div class="modal-footer">
                  <slot name="footer">
                    <label class="form-check-label"><input type="checkbox" class="form-check-"
                    v-model="draft"> Edit as a draft</label>
                    <a v-if="draft == false" class="btn btn-continue float-end" @click="editFunding()" :disabled="buttonDisable">
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
      fundingSource:{
        typeSources: '',
        nameOfInstitution: '',
        programContest: '',
        projectTitle: '',
        principalResearcher: null,
        startDate: '',
        finishDate: '',
        comments: '',
        inCash: '',
        typeOfCollaboration: '',
        progressReport: '',
      },
      draft: false,
      researchers: '',
      id: '',
      buttonDisable: false,
      errors:[],
      buttonText:'Edit source',
    }),
    mounted(){
      this.getUsuarios();
    },
    props:{
      fundingSource1: Object,
    },
    created(){
      this.id = this.fundingSource1.id;

      this.fundingSource.typeSources = this.fundingSource1.typeSources;
      this.fundingSource.nameOfInstitution = this.fundingSource1.nameOfInstitution;
      this.fundingSource.programContest = this.fundingSource1.programContest;
      this.fundingSource.projectTitle = this.fundingSource1.projectTitle;
      this.fundingSource.startDate = this.fundingSource1.startDate;
      this.fundingSource.finishDate = this.fundingSource1.finishDate;
      this.fundingSource.comments = this.fundingSource1.comments;
      this.fundingSource.inCash = this.fundingSource1.inCash;
      this.fundingSource.typeOfCollaboration = this.fundingSource1.typeOfCollaboration;
      this.fundingSource.progressReport = this.fundingSource1.progressReport;

      if (this.fundingSource1.principalResearcher != null) {
          const valoresSeparados1 = this.fundingSource1.principalResearcher.split(",");
          this.fundingSource.principalResearcher = valoresSeparados1.map((valor, index) => {
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
      async guardarBorrador(){
        const ok = await this.$refs.confirmation.show({
            title: 'Edit draft',
            message: `¿Are you sure you want to edit this Funding source as a draft? this action cannot be undone.`,
            okButton: 'Edit',
            cancelButton: 'Return'
          })
          if (ok) {


            var principalResearcher1 = "";
            if (this.fundingSource.principalResearcher !== null){
              if (this.fundingSource.principalResearcher.length !== 0) {
                this.fundingSource.principalResearcher.forEach((principalResearcher, index) => {
                  principalResearcher1 += principalResearcher.name;
                  if (index === this.fundingSource.principalResearcher.length - 1) {
                    principalResearcher1 += '.';
                  } else {
                    principalResearcher1 += ', ';
                  }
                });
              }
            }

            var nameOfResearch1 = "";
            if (this.fundingSource.nameOfResearch !== null){
              if (this.fundingSource.nameOfResearch.length !== 0) {
                this.fundingSource.nameOfResearch.forEach((nameOfResearch, index) => {
                  nameOfResearch1 += nameOfResearch.name;
                  if (index === this.fundingSource.nameOfResearch.length - 1) {
                    nameOfResearch1 += '.';
                  } else {
                    nameOfResearch1 += ', ';
                  }
                });
              }
            }

            let fundingSources = {
              status: 'Draft',
              typeSources: this.fundingSource.typeSources,
              nameOfInstitution: this.fundingSource.nameOfInstitution,
              programContest: this.fundingSource.programContest,
              projectTitle: this.fundingSource.projectTitle,
              principalResearcher: principalResearcher1,
              startDate: this.fundingSource.startDate,
              finishDate: this.fundingSource.finishDate,
              startYear: this.fundingSource.startYear,
              endingYear: this.fundingSource.endingYear,
              comments: this.fundingSource.comments,
              inCash: this.fundingSource.inCash,
              typeOfCollaboration: this.fundingSource.typeOfCollaboration,
              progressReport: this.fundingSource.progressReport,
            };
            axios.put(`api/fundingSources/${this.id}`, fundingSources ).then((result) => {
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
      async editFunding() {
        this.errors = [];

        for (const item in this.fundingSource){
          if(this.fundingSource[item] === "" || this.fundingSource[item] === 0 || this.fundingSource[item] == null || this.fundingSource[item] == []){
                this.errors.push(item);
            }
        }

        let fundingSources1 = {
          id: this.id,
          typeSources: this.fundingSource.typeSources,
          nameOfInstitution: this.fundingSource.nameOfInstitution,
          programContest: this.fundingSource.programContest,
          projectTitle: this.fundingSource.projectTitle,
          principalResearcher: principalResearcher1,
          startDate: this.fundingSource.startDate,
          finishDate: this.fundingSource.finishDate,
          startYear: this.fundingSource.startYear,
          endingYear: this.fundingSource.endingYear,
          comments: this.fundingSource.comments,
          inCash: this.fundingSource.inCash,
          typeOfCollaboration: this.fundingSource.typeOfCollaboration,
          progressReport: this.fundingSource.progressReport,
        };

        var contador = await axios.post('../api/verifyFunding', fundingSources1).then(function(response) {
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
            if(item == 'typeSources'){
              mensaje =   mensaje + "The field Type of sources is required" + "\n";
            }else if(item == 'nameOfInstitution'){
              mensaje =   mensaje + "The field Name of institution is required" + "\n";
            }else if(item == 'programContest'){
              mensaje =   mensaje + "The field Program contest is required" + "\n";
            }else if(item == 'projectTitle'){
              mensaje =   mensaje + "The field Project title is required" + "\n";
            }else if(item == 'principalResearcher'){
              mensaje =   mensaje + "The field Principal researcher is required" + "\n";
            }else if(item == 'startDate'){
              mensaje =   mensaje + "The field Start date is required" + "\n";
            }else if(item == 'finishDate'){
              mensaje =   mensaje + "The field Finish date is required" + "\n";
            }else if(item == 'inCash'){
              mensaje =   mensaje + "The field In cash is required" + "\n";
            }else if(item == 'typeOfCollaboration'){
              mensaje =   mensaje + "The field Type of collaboration is required" + "\n";
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
            title: 'Edit Funding source',
            message: `¿Are you sure you want to edit this Funding source? This action cannot be undone.`,
            okButton: 'Edit',
            cancelButton: 'Return'
          })
          if (ok) {

            var principalResearcher1 = "";
            if (this.fundingSource.principalResearcher !== null){
              if (this.fundingSource.principalResearcher.length !== 0) {
                this.fundingSource.principalResearcher.forEach((principalResearcher, index) => {
                  principalResearcher1 += principalResearcher.name;
                  if (index === this.fundingSource.principalResearcher.length - 1) {
                    principalResearcher1 += '.';
                  } else {
                    principalResearcher1 += ', ';
                  }
                });
              }
            }


            let fundingSources = {
              status: 'Finished',
              typeSources: this.fundingSource.typeSources,
              nameOfInstitution: this.fundingSource.nameOfInstitution,
              programContest: this.fundingSource.programContest,
              projectTitle: this.fundingSource.projectTitle,
              principalResearcher: principalResearcher1,
              startDate: this.fundingSource.startDate,
              finishDate: this.fundingSource.finishDate,
              startYear: this.fundingSource.startYear,
              endingYear: this.fundingSource.endingYear,
              comments: this.fundingSource.comments,
              inCash: this.fundingSource.inCash,
              typeOfCollaboration: this.fundingSource.typeOfCollaboration,
              progressReport: this.fundingSource.progressReport,
            };
            axios.put(`api/fundingSources/${this.id}`, fundingSources ).then((result) => {
              this.toast.success("Funding source edited successfully!", {
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