<template>
    <div class="row mt-4 mx-4">
      <div class="col-12">
        <div class="card mb-4">
          <div class="row pb-0 p-4">
            <div class="col-lg-10 col-md-12">
              <div class="info-box">
                Includes all articles presented/developed for conferences. Additionally, you can include publications indexed in Scopus, SciELO, Latindex, or any other type of indexation different from WoS. It is necessary to <strong>import the publication</strong> in PDF format, with a maximum of 20 MB.
                If you have presented the publication at a conference, please ensure it is also reported in the “Participation in Sc events” module.
              </div>
            </div>
            <div class="col-lg-2 col-md-12 d-flex justify-content-lg-end justify-content-center align-items-center">
              <div class="d-flex">
                <button v-if="!is('Staff') && !is('Anid') && !is('Titular Researcher')" @click="deleteSelected" class="btn btn-spacing btn-closed"><i class="fa fa-fw fa-trash"></i> Delete Selected</button>
                <a v-if="!is('Staff') && !is('Anid')" class="btn btn-spacing btn-continue" id="show-modal1" @click="showNewNonIsiPublication = true">New Entry</a>
                <a class="btn btn-spacing btn-search-blue" @click="recargarTabla('General')"><i class="fa-solid fa-rotate"></i></a>
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
                <table v-show="mostrarTabla" class="table align-items-center mb-0" id="MyTableNonIsiPublications">
                  <thead>
                    <tr style="color: black">
                      <th style="min-width: 16px;"></th>
                      <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                      <th class="text-uppercase text-xs font-weight-bolder">Actions</th>
                      <th class="text-uppercase text-xs font-weight-bolder">Status</th>
                      <th class="text-uppercase text-xs font-weight-bolder">User</th>
                      <th class="text-uppercase text-xs font-weight-bolder">Author(s)</th>
                      <th class="text-uppercase text-xs font-weight-bolder">Article Title</th>
                      <th class="text-uppercase text-xs font-weight-bolder">Journal Name</th>
                      <th class="text-uppercase text-xs font-weight-bolder">Year Published</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="nonIsiPublication in filteredNonIsiPublications" :key="nonIsiPublication.id">
                      <td></td>
                      <td>
                        <p class="text-sm font-weight-bolder mb-0" style="color:black">{{ nonIsiPublication.id }}</p>
                      </td>
                      <td class="align-middle text-end">
                        <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                          <a v-if="nonIsiPublication.file != null && nonIsiPublication.is_link == 0" class="btn btn-search-blue btn-xs" title="Download Thesis Extract" @click="descargarExtracto(nonIsiPublication.id, nonIsiPublication.usuario.name)"><i class="fa-solid fa-download"></i></a>
                          <a v-else-if="nonIsiPublication.is_link == 1" :href="nonIsiPublication.file" class="btn btn-search-blue btn-xs" title="Redirect" target="_blank"><i class="fa-solid fa-arrow-up-right-from-square"></i></a>
                          <a v-else class="btn btn-grey btn-xs"><i class="fa-solid fa-download"></i></a>
                          &nbsp;
                          <a v-if="!is('Staff') && !is('Anid') && (!is('Titular Researcher') || nonIsiPublication.idUsuario == userID)" class="btn btn-alert btn-xs" title="Edit" @click="editNonIsiPublication(nonIsiPublication)"><i class="fa fa-fw fa-edit"></i></a>
                          &nbsp;
                          <a class="btn btn-success btn-xs" title="Details" @click="verNonIsiPublication(nonIsiPublication)"><i class="fa-regular fa-eye"></i></a>
                          &nbsp;
                          <a v-if="!is('Staff') && !is('Anid') && !is('Titular Researcher')" class="btn btn-closed btn-xs" title="Delete" @click="deletePublication(nonIsiPublication.id)"><i class="fa fa-fw fa-trash"></i></a>
                        </div>
                      </td>
                      <td>
                        <p v-if="nonIsiPublication.status == 'Draft'" class="text-sm font-weight-bolder mb-0" style="color:#878686">{{ nonIsiPublication.status }}</p>
                        <p v-if="nonIsiPublication.status == 'Finished'" class="text-sm font-weight-bolder mb-0" style="color:#28A745">Registered</p>
                      </td>
                      <td class="text-sm mb-0 truncate-text" :title="nonIsiPublication.usuario.name">
                        {{ truncateText(nonIsiPublication.usuario.name, 60) }}
                      </td>
                      <td class="text-sm mb-0 truncate-text" :title="nonIsiPublication.authors || '---'">
                        {{ truncateText(nonIsiPublication.authors || '---', 60) }}
                      </td>
                      <td class="text-sm mb-0 truncate-text" :title="nonIsiPublication.articleTitle || '---'">
                        {{ truncateText(nonIsiPublication.articleTitle || '---', 60) }}
                      </td>
                      <td class="text-sm mb-0 truncate-text" :title="nonIsiPublication.journalName || '---'">
                        {{ truncateText(nonIsiPublication.journalName || '---', 60) }}
                      </td>
                      <td class="text-sm mb-0">{{ nonIsiPublication.yearPublished || '---' }}</td>
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
      <modalver v-bind:nonIsiPublication1="nonIsiPublication" v-if="showDetailsNonIsi" @close="showDetailsNonIsi = false"></modalver>
      <modaleditar v-bind:nonIsiPublication1="nonIsiPublicationEdit" v-if="showEditNonIsiPublication" @close="showEditNonIsiPublication = false" @recarga="recargarTabla('General')"></modaleditar>
      <modalcrear v-if="showNewNonIsiPublication" @close="showNewNonIsiPublication = false" @recarga="recargarTabla('General')"></modalcrear>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  import modalver from '../../snippets/sistema/nonIsiPublications/detailsNonIsiPublicationModal.vue';
  import modalcrear from '../../snippets/sistema/nonIsiPublications/createNonIsiPublicationModal.vue';
  import modaleditar from '../../snippets/sistema/nonIsiPublications/editNonIsiPublicationModal.vue';
  import modalconfirmacion from '../../snippets/sistema/alerts/confirmationModal.vue';
  import modalalerta from '../../snippets/sistema/alerts/alertModal.vue';
  import {mixin} from '../../../mixins.js'

  export default {
    components: { modalver, modalcrear, modaleditar, modalconfirmacion, modalalerta },
    mixins: [mixin],
    data() {
      return {
        nonIsiPublications: [],
        filteredNonIsiPublications: [],
        uniqueProgressReports: [],
        selectedProgressReport: '',
        showNewNonIsiPublication: false,
        showEditNonIsiPublication: false,
        showDetailsNonIsi: false,
        nonIsiPublication: null,
        nonIsiPublicationEdit: null,
        mostrarTabla: false,
        mostrarCarga: true,
        table: null,
      };
    },
    created() {
      this.getNonIsiPublications(this.userID);
    },
    methods: {
      verNonIsiPublication(nonIsiPublication){
          this.nonIsiPublication = nonIsiPublication;
          this.showDetailsNonIsi = true;
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
          let module = 'non-wos-publications'; // Cambia este valor dinámicamente según el módulo
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
                ids: selectedIds,
                user_id: this.userID // Asegúrate de que `this.currentUserId` contiene el ID del usuario actual
            })
            .then(response => {
                this.toast.success(`${response.data.deletedCount} records successfully deleted!`);
                this.recargarTabla(module);
            })
            .catch(error => {
                console.error('Error deleting records:', error);
                this.toast.error('Failed to delete records.');
            });
          }
      },
      truncateText(text, maxLength) {
        if (!text) return '---';
        return text.length > maxLength ? `${text.substring(0, maxLength)}...` : text;
      },
      async getNonIsiPublications(id) {
        try {
          const response = await axios.get(`api/nonIsiPublications/${id}`);
          this.nonIsiPublications = response.data;
          this.filteredNonIsiPublications = [...this.nonIsiPublications];
          this.uniqueProgressReports = [
            ...new Set(this.nonIsiPublications.map(pub => pub.progressReport).filter(Boolean)),
          ].sort((a, b) => a - b);
          if (this.table != null) {
            this.table.destroy();
          }
          this.crearTabla('#MyTableNonIsiPublications');
        } catch (error) {
          console.error('Error fetching publications:', error);
        }
      },
      filterByProgressReport() {
        this.mostrarCarga = true;
        if (this.selectedProgressReport === '') {
          this.filteredNonIsiPublications = [...this.nonIsiPublications];
        } else {
          this.filteredNonIsiPublications = this.nonIsiPublications.filter(
            pub => pub.progressReport === this.selectedProgressReport
          );
        }
        if (this.table != null) {
          this.table.destroy();
        }
        setTimeout(() => {
          this.crearTabla('#MyTableNonIsiPublications');
          this.mostrarCarga = false;
        }, 500);
      },
      recargarTabla(type) {
        this.mostrarCarga = true;
        if (type === 'General') {
          this.getNonIsiPublications(this.userID);
        } else {
          this.crearTabla('#MyTableNonIsiPublications');
        }
      },
      async deletePublication(id) {
        const ok = await this.$refs.confirmation.show({
            title: 'Delete Non WoS Publication',
            message: `Are you sure you want to delete this Non WoS Publication?`,
            okButton: 'Delete',
            cancelButton: 'Return',
        });
        if (ok) {
            try {
                // Obtén el ID del usuario actual desde la sesión o contexto
                const userId = this.userID; // Asegúrate de tener acceso a este dato en tu componente
                
                // Envía el ID del proyecto y el ID del usuario
                await axios.delete(`api/nonIsiPublications/${id}`, {
                    data: { user_id: userId },
                });

                // Mostrar mensaje de éxito
                this.toast.success('Non WoS Publication successfully removed!', {
                    position: 'top-right',
                    timeout: 3000,
                    closeOnClick: true,
                    pauseOnFocusLoss: true,
                    pauseOnHover: true,
                    draggable: true,
                    draggablePercent: 0.6,
                    showCloseButtonOnHover: false,
                    hideProgressBar: true,
                    closeButton: 'button',
                    icon: true,
                    rtl: false,
                });

                // Recargar la tabla
                this.recargarTabla('General');
            } catch (error) {
                console.error('Error deleting Non WoS Publication:', error);
            }
        }
      },
      editNonIsiPublication(nonIsiPublication) {
        this.nonIsiPublicationEdit = nonIsiPublication;
        this.showEditNonIsiPublication = true;
        },
    },
  };
  </script>
  