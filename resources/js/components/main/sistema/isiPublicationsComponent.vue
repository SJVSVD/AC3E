<template>
    <div class="row mt-4 mx-4">
      <div class="col-12">
        <div class="card mb-4">
          <div class="row pb-0 p-4">
            <div class="col-lg-10 col-md-12">
              <div class="info-box">
                Includes all articles <strong>published</strong> in scientific journals indexed in the Web of Science, during the current baseline year (Sep-present). By entering the DOI, most of the fields will be filled automatically; if not, please manually enter the required information.
                In the table below, you have the option to search for a publication to check if it has already been reported. It is recommended to filter by year of publication for the review.
                Articles published in periods prior to the current baseline year <strong>will NOT be considered</strong> in the report and individual results evaluation.
              </div>
            </div>
            <div class="col-lg-2 col-md-12 d-flex justify-content-lg-end justify-content-center align-items-center">
              <div class="d-flex">
                <button v-if="!is('Staff') && !is('Anid') && !is('Titular Researcher')" @click="deleteSelected" class="btn btn-spacing btn-closed"><i class="fa fa-fw fa-trash"></i> Delete Selected</button>
                <a v-if="!is('Staff') && !is('Anid')" class="btn btn-spacing btn-continue" id="show-modal1" @click="showNewIsiPublication = true">New Entry</a>
                <a class="btn btn-spacing btn-search-blue ml-2" @click="recargarTabla('General')"><i class="fa-solid fa-rotate"></i></a>
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
                <option
                  v-for="progress in uniqueProgressReports"
                  :key="progress"
                  :value="progress"
                >
                  {{ progress }}
                </option>
              </select>
            </div>
          </div>
  
          <div class="card-body px-0 pt-0 pb-2" style="min-height: 400px">
            <div class="container">
              <div class="table-responsive p-0">
                <div v-show="mostrarCarga" class="loader-sm"></div>
                <table v-show="mostrarTabla" class="table align-items-center mb-0" id="MyTableIsiPublications">
                  <thead>
                    <tr style="color: black">
                      <th style="min-width: 16px;"></th>
                      <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                      <th class="text-uppercase text-xs font-weight-bolder">Actions</th>
                      <th class="text-uppercase text-xs font-weight-bolder">Status</th>
                      <th class="text-uppercase text-xs font-weight-bolder">User</th>
                      <th class="text-uppercase text-xs font-weight-bolder">Author(s)</th>
                      <th class="text-uppercase text-xs font-weight-bolder">Doi</th>
                      <th class="text-uppercase text-xs font-weight-bolder">Article Title</th>
                      <th class="text-uppercase text-xs font-weight-bolder">Journal Name</th>
                      <th class="text-uppercase text-xs font-weight-bolder">Year Published</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="isiPublication in filteredIsiPublications" :key="isiPublication.id">
                      <td></td>
                      <td>
                        <p class="text-sm font-weight-bolder mb-0" style="color:black">{{ isiPublication.id }}</p>
                      </td>
                      <td class="align-middle text-end">
                        <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                          <a v-if="!is('Staff') && !is('Anid') && (!is('Titular Researcher') || isiPublication.idUsuario == userID)"  class="btn btn-alert btn-xs" title="Edit" @click="editIsiPublication(isiPublication)"><i class="fa fa-fw fa-edit"></i></a>
                          &nbsp;
                          <a class="btn btn-success btn-xs" title="Details" @click="verIsiPublication(isiPublication)"><i class="fa-regular fa-eye"></i></a>
                          &nbsp;
                          <a v-if="!is('Staff') && !is('Anid') && !is('Titular Researcher')" class="btn btn-closed btn-xs" title="Delete" @click="deletePublication(isiPublication.id,)"><i class="fa fa-fw fa-trash"></i></a>
                        </div>
                      </td>
                      <td>
                        <p v-if="isiPublication.status == 'Draft'" class="text-sm font-weight-bolder mb-0" style="color:#878686">{{ isiPublication.status }}</p>
                        <p v-if="isiPublication.status == 'Finished'" class="text-sm font-weight-bolder mb-0" style="color:#28A745">Registered</p>
                      </td>
                      <td class="text-sm mb-0 truncate-text" :title="isiPublication.usuario.name">
                    {{ truncateText(isiPublication.usuario.name, 60) }}
                    </td>
                    <td class="text-sm mb-0 truncate-text" :title="isiPublication.authors || '---'">
                    {{ truncateText(isiPublication.authors || '---', 60) }}
                    </td>
                    <td class="text-sm mb-0 truncate-text" :title="isiPublication.doi || '---'">
                    {{ truncateText(isiPublication.doi || '---', 60) }}
                    </td>
                    <td class="text-sm mb-0 truncate-text" :title="isiPublication.articleTitle || '---'">
                    {{ truncateText(isiPublication.articleTitle || '---', 60) }}
                    </td>
                    <td class="text-sm mb-0 truncate-text" :title="isiPublication.journalName || '---'">
                    {{ truncateText(isiPublication.journalName || '---', 60) }}
                    </td>
                    <td class="text-sm mb-0 truncate-text" :title="isiPublication.yearPublished || '---'">
                    {{ truncateText(isiPublication.yearPublished || '---', 60) }}
                    </td>
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
      <modalver v-bind:isiPublication1="isiPublication" v-if="showDetailsIsi" @close="showDetailsIsi = false"></modalver>
      <modaleditar v-bind:isiPublication1="isiPublicationEdit" v-if="showEditIsiPublication" @close="showEditIsiPublication = false" @recarga="recargarTabla('General')"></modaleditar>
      <modalcrear v-if="showNewIsiPublication" @close="showNewIsiPublication = false" @recarga="recargarTabla('General')"></modalcrear>
    </div>
  </template>

<script>
import axios from 'axios'
import modalver from '../../snippets/sistema/isiPublications/detailsIsiPublicationModal.vue'
import modalcrear from '../../snippets/sistema/isiPublications/createIsiPublicationModal.vue'
import modaleditar from '../../snippets/sistema/isiPublications/editIsiPublicationModal.vue'
import modalconfirmacion from '../../snippets/sistema/alerts/confirmationModal.vue'
import modalalerta from '../../snippets/sistema/alerts/alertModal.vue'
import {mixin} from '../../../mixins.js'

export default {
    components: { modalcrear, modaleditar,modalver, modalconfirmacion, modalalerta },
    mixins: [mixin],
    data(){
        return{
            isiPublications: [], // All publications
            filteredIsiPublications: [], // Publications filtered by progressReport
            uniqueProgressReports: [], // Unique progressReport values for the selector
            selectedProgressReport: '', // Selected progressReport value
            isiPublication: null,
            showNewIsiPublication: false,
            showEditIsiPublication: false,
            showDetailsIsi: false,
            isiPublicationEdit: null,
            mostrarTabla: false,
            mostrarCarga: true,
            table: null,
        }
    },
    created(){
        this.getIsiPublications(this.userID);
    },
    methods: {
        verIsiPublication(isiPublication){
            this.isiPublication = isiPublication;
            this.showDetailsIsi = true;
        },
        async getIsiPublications(id) {
            try {
                const response = await axios.get(`api/isiPublications/${id}`);
                this.isiPublications = response.data;

                // Default filtered publications
                this.filteredIsiPublications = [...this.isiPublications];

                // Extract unique progressReport values (excluding null or empty values)
                this.uniqueProgressReports = [
                ...new Set(this.isiPublications.map(pub => pub.progressReport).filter(Boolean))
                ].sort((a, b) => a - b);

                // Handle table reinitialization
                if (this.table != null) {
                this.table.destroy(); // Destroy the table instance
                }
                this.crearTabla('#MyTableIsiPublications'); // Recreate the table with new data
            } catch (error) {
                console.error('Error fetching publications:', error);
            }
        },
        filterByProgressReport() {
            this.mostrarCarga = true; // Activar indicador de carga

            // Restaurar datos originales si se selecciona "All"
            if (this.selectedProgressReport == "") {
                this.filteredIsiPublications = [...this.isiPublications];

                // Verificar y destruir la tabla existente
                if (this.table != null) {

                this.table.destroy();
                this.table = null; // Limpiar referencia de la tabla
                }

                // Reconstruir la tabla de manera segura
                setTimeout(() => {
                if (document.querySelector("#MyTableIsiPublications")) {
                    this.crearTabla("#MyTableIsiPublications");
                }
                this.mostrarCarga = false; // Desactivar indicador de carga
                }, 500);
            } else {
                // Filtrar por progressReport
                this.filteredIsiPublications = this.isiPublications.filter(
                publication => publication.progressReport === this.selectedProgressReport
                );
                // Verificar y destruir la tabla existente
                if (this.table != null) {
                    this.table.destroy();
                    this.table = null; // Limpiar referencia de la tabla
                }
                
                // Reconstruir la tabla de manera segura
                setTimeout(() => {
                    console.log(this.filteredIsiPublications);
                if (document.querySelector("#MyTableIsiPublications")) {
                    this.crearTabla("#MyTableIsiPublications");
                }
                this.mostrarCarga = false; // Desactivar indicador de carga
                }, 500);
            }
            },


        recargarTabla($tipoRecarga){
            this.mostrarCarga = true;
            if($tipoRecarga == 'General'){
                this.isiPublications = null;
                this.getIsiPublications(this.userID);
            }
            else{
                this.crearTabla("#MyTableIsiPublications");
            }
        },
        editIsiPublication(isiPublication){
            this.isiPublicationEdit = isiPublication;
            this.showEditIsiPublication = true;
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
            let module = 'wos-publications'; // Cambia este valor dinámicamente según el módulo
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

        async deletePublication(id) {
            const ok = await this.$refs.confirmation.show({
                title: 'Delete Publication',
                message: `¿Are you sure you want to delete this Publication?.`,
                okButton: 'Delete',
                cancelButton: 'Return'
            })
            if (ok) {
                axios.delete(`api/isiPublications/${id}`).then( response =>{
                    this.toast.success("Publication successfully removed!", {
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
                    this.recargarTabla('General');
                }).catch(e=> console.log(e))
            }
        },
    }
}
</script>