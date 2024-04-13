<template>
  <div class="row mt-4 mx-4">
      <div class="col-12">
          <div class="card mb-4">
            <div class="row pb-0 p-4">
                <div class="col-4"> 
                    <div class="d-flex align-items-center">
                        <select v-model="selectedProgress" class="form-select form-select-sm me-2" style="min-width:100px; max-width:200px;">
                            <option disabled value="">Select a progress report </option>
                            <option v-for="number in progressNumbers" :value="number">{{ number }}</option>
                        </select>
                        <button class="btn btn-spacing btn-search-blue mt-2" @click="searchByProgressReport"><i class="fa-solid fa-magnifying-glass"></i></button>
                    </div>
                </div>
                <div class="col-8">
                    <div class="d-flex justify-content-end">
                        <a class="btn btn-spacing btn-search-blue" @click="recargarTabla('General')"><i class="fa-solid fa-rotate"></i></a>
                    </div>
                </div>
            </div>
              <div class="card-body px-0 pt-0 pb-2" style="min-height: 400px">
                  <div class="container">
                      <div class="table-responsive p-0">
                          <div v-show="mostrarCarga" class="loader-sm"></div>
                          <table v-show="mostrarTabla" class="table align-items-center mb-0" id="myTableIndicators">
                              <thead>
                                  <tr style="color: black">
                                      <th style="min-width: 16px;"></th>
                                      <th class="text-uppercase text-xs font-weight-bolder">Title</th>
                                      <th class="text-uppercase text-xs font-weight-bolder">Count</th>
                                      
                                  </tr>
                              </thead>
                              <tbody>
                                  <tr v-for="indicators in indicators" :key="indicators.name">
                                      <td></td>
                                      <td>
                                          <p class="text-sm font-weight-bolder mb-0">{{ indicators.name }}</p>
                                      </td>
                                      <td>
                                          <p class="text-sm font-weight-bolder mb-0">{{ indicators.value }}</p>
                                      </td>  
                                  </tr>
                              </tbody>
                          </table>
                          <div class="row">
                              <div class="col-3">
                                  <label style="font-weight: 500">These buttons use the elements selected in the table, if none exist, it will select all the records. </label>
                              </div>
                              <div class="col-auto">
                                  <label title="To select a single record from the table, just do &#013; Click on the box in the first column to select &#013; several consecutive hold SHIFT, to select several &#013; non-consecutive hold CTRL."><span class="badge bg-dark-grey fs-10">?</span></label>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
          <modalconfirmacion ref="confirmation"></modalconfirmacion>
          <modalalerta ref="alert"></modalalerta>
      </div>
  </div>
</template>

<script>
import axios from 'axios'
import modalconfirmacion from './snippets/sistema/alerts/confirmationModal.vue'
import modalalerta from './snippets/sistema/alerts/alertModal.vue'
import {mixin} from './../mixins.js'

export default {
  components: {  modalconfirmacion, modalalerta},
  mixins: [mixin],
  data(){
      return{
          indicators: null,
          usuarioeditar: null,
          mostrarTabla: false,
          mostrarCarga: true,
          permisos: null,
          table: null,
          actualProgressReport: '',
          selectedProgress: '',
          progressNumbers: [],
      }
  },
  created(){
      this.getIndicators();
      this.getProgressReport();
  },
  methods: {
    searchByProgressReport(){

        axios.get(`api/getIndicators/${this.selectedProgress}`).then( response =>{
            console.log(response.data);
            this.indicators = response.data;
            this.recargarTabla('Search');
          }).catch(e=> console.log(e))
    },
    getProgressReport(){
        axios.get('api/showProgressReport').then(response => {
            this.actualProgressReport = response.data;
            this.selectedProgress = this.actualProgressReport;
            this.progressNumbers = this.createNumberArray(response.data);
        }).catch(e => console.log(e))
    },
    createNumberArray(progress) {
        return Array.from({length: progress}, (_, index) => index + 1);
    },
      recargarTabla($tipoRecarga){
          this.mostrarCarga = true;
          if($tipoRecarga == 'General'){
              this.indicators = null;
              this.getIndicators();
          }else{
              this.table.destroy();
              this.crearTabla('#myTableIndicators');
          }
      },
      getIndicators(){
          axios.get('api/getIndicators').then( response =>{
              this.indicators = response.data;
              if (this.table != null){
                  this.table.clear();
                  this.table.destroy();
              }
              this.crearTabla("#myTableIndicators");
          }).catch(e=> console.log(e))
      },
  }
}
</script>