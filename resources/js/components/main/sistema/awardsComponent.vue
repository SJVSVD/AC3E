<template>
    <div class="row mt-4 mx-4">
      <div class="col-12">
        <div class="card mb-4">
          <div class="row pb-0 p-4">
            <div class="col-lg-10 col-md-12">
              <div class="info-box">
                Includes all awards or recognitions <strong>received by AC3E researchers</strong>, during the current baseline year (Sep-present).
              </div>
            </div>
            <div class="col-lg-2 col-md-12 d-flex justify-content-lg-end justify-content-center align-items-center">
              <div class="d-flex">
                <button v-if="!is('Staff') && !is('Anid') && !is('Titular Researcher')" @click="deleteSelected" class="btn btn-spacing btn-closed">
                  <i class="fa fa-fw fa-trash"></i> Delete Selected
                </button>
                <a v-if="!is('Staff') && !is('Anid') && !is('Titular Researcher')" class="btn btn-spacing btn-continue" id="show-modal1" @click="showNewAward = true">New Entry</a>
                <a class="btn btn-spacing btn-search-blue" @click="recargarTabla('General')">
                  <i class="fa-solid fa-rotate"></i>
                </a>
              </div>
            </div>
          </div>
  
          <!-- ProgressReport Filter -->
          <div class="row px-4 mb-2">
            <div class="col-lg-2 col-md-6">
              <label for="progressReportFilter" class="form-label">Filter by Progress Report:</label>
              <select
                id="progressReportFilter"
                class="form-select"
                v-model="selectedProgressReport"
                @change="filterByProgressReport"
              >
                <option value="">All</option>
                <option v-for="progress in uniqueProgressReports" :key="progress" :value="progress">
                  {{ progress }}
                </option>
              </select>
            </div>
          </div>
  
          <div class="card-body px-0 pt-0 pb-2" style="min-height: 400px">
            <div class="container">
              <div class="table-responsive p-0">
                <div v-show="mostrarCarga" class="loader-sm"></div>
                <table v-show="mostrarTabla" class="table align-items-center mb-0" id="MyTableAward">
                  <thead>
                    <tr style="color: black">
                      <th style="min-width: 16px;"></th>
                      <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                      <th class="text-uppercase text-xs font-weight-bolder">Actions</th>
                      <th class="text-uppercase text-xs font-weight-bolder">Status</th>
                      <th class="text-uppercase text-xs font-weight-bolder">Awardee Name</th>
                      <th class="text-uppercase text-xs font-weight-bolder">Award Name</th>
                      <th class="text-uppercase text-xs font-weight-bolder">Year</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="award in filteredAwards" :key="award.id">
                      <td></td>
                      <td>
                        <p class="text-sm font-weight-bolder mb-0" style="color:black">{{ award.id }}</p>
                      </td>
                      <td class="align-middle text-end">
                        <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                          <a v-if="!is('Staff') && !is('Anid') && !is('Titular Researcher')" class="btn btn-alert btn-xs" title="Edit" @click="editAward(award)">
                            <i class="fa fa-fw fa-edit"></i>
                          </a>
                          &nbsp;
                          <a class="btn btn-success btn-xs" title="Details" @click="verAward(award)">
                            <i class="fa-regular fa-eye"></i>
                          </a>
                          &nbsp;
                          <a v-if="!is('Staff') && !is('Anid') && !is('Titular Researcher')" class="btn btn-closed btn-xs" title="Delete" @click="deleteAward(award.id)">
                            <i class="fa fa-fw fa-trash"></i>
                          </a>
                        </div>
                      </td>
                      <td>
                        <p v-if="award.status == 'Draft'" class="text-sm font-weight-bolder mb-0" style="color:#878686">
                          {{ award.status }}
                        </p>
                        <p v-if="award.status == 'Finished'" class="text-sm font-weight-bolder mb-0" style="color:#28A745">
                          Registered
                        </p>
                      </td>
                      <td class="text-sm mb-0 truncate-text" :title="award.usuario.name">
                        {{ truncateText(award.usuario.name, 60) }}
                      </td>
                      <td class="text-sm mb-0 truncate-text" :title="award.awardName || '---'">
                        {{ truncateText(award.awardName || '---', 60) }}
                      </td>
                      <td class="text-sm mb-0">{{ award.year || '---' }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
      <modalconfirmacion ref="confirmation"></modalconfirmacion>
      <modalalerta ref="alert"></modalalerta>
      <modalver v-bind:award1="award" v-if="showDetailsAward" @close="showDetailsAward = false"></modalver>
      <modaleditar v-bind:award1="awardEdit" v-if="showEditAward" @close="showEditAward = false" @recarga="recargarTabla('General')"></modaleditar>
      <modalcrear v-if="showNewAward" @close="showNewAward = false" @recarga="recargarTabla('General')"></modalcrear>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  import modalver from '../../snippets/sistema/awards/detailsAwardsModal.vue';
  import modalcrear from '../../snippets/sistema/awards/createAwardModal.vue';
  import modaleditar from '../../snippets/sistema/awards/editAwardModal.vue';
  import modalconfirmacion from '../../snippets/sistema/alerts/confirmationModal.vue';
  import modalalerta from '../../snippets/sistema/alerts/alertModal.vue';
  import {mixin} from '../../../mixins.js'

  export default {
    components: { modalver, modalcrear, modaleditar, modalconfirmacion, modalalerta },
    mixins: [mixin],
    data() {
      return {
        awards: [],
        filteredAwards: [],
        uniqueProgressReports: [],
        selectedProgressReport: '',
        showDetailsAward: false,
        showNewAward: false,
        showEditAward: false,
        award: null,
        awardEdit: null,
        mostrarTabla: false,
        mostrarCarga: true,
        table: null,
      };
    },
    created() {
      this.getAwards(this.userID);
    },
    methods: {
      verAward(award){
          this.award = award;
          this.showDetailsAward = true;
      },
      truncateText(text, maxLength) {
        if (!text) return '---';
        return text.length > maxLength ? `${text.substring(0, maxLength)}...` : text;
      },
      async deleteSelected() {
          // Obtener las filas seleccionadas
          let selectedData = this.table.rows({ selected: true }).data().toArray();
          let selectedIds = selectedData.map(row => {
              // Supongamos que el ID está en la segunda columna (índice 1)
              const tempDiv = document.createElement("div");
              tempDiv.innerHTML = row[1]; // Cambia el índice según dónde esté el ID
              return tempDiv.textContent.trim(); // Obtener el contenido de texto que es el ID
          });
          let module = 'awards'; // Cambia este valor dinámicamente según el módulo
          if (selectedIds.length === 0) {
              this.toast.error("No records selected.");
              return;
          }
          const ok = await this.$refs.confirmation.show({
              title: 'Delete Multiple',
              message: `¿Are you sure you want to delete `+selectedIds.length+` records?.`,
              okButton: 'Delete',
              cancelButton: 'Return'
          })
          if (ok) { 
              axios.post('api/delete-records', {
                  module: module,
                  ids: selectedIds
              })
              .then(response => {
                  this.toast.success(response.data.success);
                  this.recargarTabla('General');
              })
              .catch(error => {
                  this.toast.error("An error occurred.");
              });
          }
      },
      async getAwards(id) {
        try {
          const response = await axios.get(`api/awards/${id}`);
          this.awards = response.data;
          this.filteredAwards = [...this.awards];
          this.uniqueProgressReports = [
            ...new Set(this.awards.map(award => award.progressReport).filter(Boolean)),
          ].sort((a, b) => a - b);
          if (this.table != null) {
            this.table.destroy();
          }
          this.crearTabla('#MyTableAward');
        } catch (error) {
          console.error('Error fetching awards:', error);
        }
      },
      filterByProgressReport() {
        this.mostrarCarga = true;
        if (this.selectedProgressReport === '') {
          this.filteredAwards = [...this.awards];
        } else {
          this.filteredAwards = this.awards.filter(
            award => award.progressReport === this.selectedProgressReport
          );
        }
        if (this.table != null) {
          this.table.destroy();
        }
        setTimeout(() => {
          this.crearTabla('#MyTableAward');
          this.mostrarCarga = false;
        }, 500);
      },
      recargarTabla(type) {
        this.mostrarCarga = true;
        if (type === 'General') {
          this.getAwards(this.userID);
        } else {
          this.crearTabla('#MyTableAward');
        }
      },
      editAward(award) {
        this.awardEdit = award;
        this.showEditAward = true;
      },
      async deleteAward(id) {
        const ok = await this.$refs.confirmation.show({
          title: 'Delete Award',
          message: `Are you sure you want to delete this award?`,
          okButton: 'Delete',
          cancelButton: 'Cancel',
        });
        if (ok) {
          try {
            await axios.delete(`api/awards/${id}`);
            this.toast.success('Award successfully deleted.');
            this.recargarTabla('General');
          } catch (error) {
            console.error('Error deleting award:', error);
          }
        }
      },
    },
  };
  </script>
  