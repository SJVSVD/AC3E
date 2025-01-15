<template>
    <div class="row mt-4 mx-4">
      <div class="col-12">
        <div class="card mb-4">
          <div class="row pb-0 p-4">
            <div class="col-lg-10 col-md-12">
              <div class="info-box">
                Includes all scientific events executed during the current baseline year (Sep-present), in which one or more researchers participated as speakers, attendees, or others. You must attach supporting documents that confirm your participation in the event, either in PDF, PNG, or JPEG format. The supporting document can be a confirmation letter, an email, an event program, a screenshot of the event webpage, etc.
                If you participated by presenting a paper, ensure it is also reported in the “Non WoS Publications” module.
              </div>
            </div>
            <div class="col-lg-2 col-md-12 d-flex justify-content-lg-end justify-content-center align-items-center">
              <div class="d-flex">
                <button v-if="!is('Staff') && !is('Anid') && !is('Titular Researcher')" @click="deleteSelected" class="btn btn-spacing btn-closed"><i class="fa fa-fw fa-trash"></i> Delete Selected</button>
                <a v-if="!is('Staff') && !is('Anid')" class="btn btn-spacing btn-continue" id="show-modal1" @click="showNewParticipation = true">New Entry</a>
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
                <table v-show="mostrarTabla" class="table align-items-center mb-0" id="myTableParticipations">
                  <thead>
                    <tr style="color: black">
                      <th style="min-width: 16px;"></th>
                      <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                      <th class="text-uppercase text-xs font-weight-bolder">Actions</th>
                      <th class="text-uppercase text-xs font-weight-bolder">Status</th>
                      <th class="text-uppercase text-xs font-weight-bolder">User</th>
                      <th class="text-uppercase text-xs font-weight-bolder">Event Name</th>
                      <th class="text-uppercase text-xs font-weight-bolder">Start Date</th>
                      <th class="text-uppercase text-xs font-weight-bolder">Ending Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="participationScEvent in filteredParticipationScEvents" :key="participationScEvent.id">
                      <td></td>
                      <td>
                        <p class="text-sm font-weight-bolder mb-0" style="color:black">
                          {{ participationScEvent.id }}
                        </p>
                      </td>
                      <td class="align-middle text-end">
                        <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                          <a
                            v-if="participationScEvent.file != null && participationScEvent.is_link == 0"
                            class="btn btn-search-blue btn-xs"
                            title="Download File"
                            @click="descargarExtracto(participationScEvent.id, participationScEvent.usuario.name)"
                          >
                            <i class="fa-solid fa-download"></i>
                          </a>
                          <a
                            v-else-if="participationScEvent.is_link == 1"
                            :href="participationScEvent.file"
                            class="btn btn-search-blue btn-xs"
                            title="Redirect"
                            target="_blank"
                          >
                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                          </a>
                          <a v-else class="btn btn-grey btn-xs"><i class="fa-solid fa-download"></i></a>
                          &nbsp;
                          <a v-if="!is('Staff') && !is('Anid') && (!is('Titular Researcher') || participationScEvent.idUsuario == userID)" class="btn btn-alert btn-xs" title="Edit" @click="editParticipation(participationScEvent)">
                            <i class="fa fa-fw fa-edit"></i>
                          </a>
                          &nbsp;
                          <a class="btn btn-success btn-xs" title="Details" @click="verParticipation(participationScEvent)">
                            <i class="fa-regular fa-eye"></i>
                          </a>
                          &nbsp;
                          <a v-if="!is('Staff') && !is('Anid') && !is('Titular Researcher')" class="btn btn-closed btn-xs" title="Delete" @click="deleteParticipation(participationScEvent.id)">
                            <i class="fa fa-fw fa-trash"></i>
                          </a>
                        </div>
                      </td>
                      <td>
                        <p
                          v-if="participationScEvent.status == 'Draft'"
                          class="text-sm font-weight-bolder mb-0"
                          style="color:#878686"
                        >
                          {{ participationScEvent.status }}
                        </p>
                        <p
                          v-if="participationScEvent.status == 'Finished'"
                          class="text-sm font-weight-bolder mb-0"
                          style="color:#28A745"
                        >
                          Registered
                        </p>
                      </td>
                      <td class="text-sm mb-0 truncate-text" :title="participationScEvent.usuario.name">
                        {{ truncateText(participationScEvent.usuario.name, 60) }}
                      </td>
                      <td class="text-sm mb-0 truncate-text" :title="participationScEvent.eventName || '---'">
                        {{ truncateText(participationScEvent.eventName || '---', 60) }}
                      </td>
                      <td class="text-sm mb-0">{{ this.thisDate(participationScEvent.startDate) }}</td>
                      <td class="text-sm mb-0">{{ this.thisDate(participationScEvent.endingDate) }}</td>
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
      <modalver
        v-bind:participation1="participationScEvent"
        v-if="showDetailsParticipation"
        @close="showDetailsParticipation = false"
      ></modalver>
      <modaleditar
        v-bind:participation1="participationEdit"
        v-if="showEditParticipation"
        @close="showEditParticipation = false"
        @recarga="recargarTabla('General')"
      ></modaleditar>
      <modalcrear
        v-if="showNewParticipation"
        @close="showNewParticipation = false"
        @recarga="recargarTabla('General')"
      ></modalcrear>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  import modalver from '../../snippets/sistema/participationScEvents/detailsParticipationScEventsModal.vue';
  import modalcrear from '../../snippets/sistema/participationScEvents/createParticipationScEventModal.vue';
  import modaleditar from '../../snippets/sistema/participationScEvents/editParticipationScEventModal.vue';
  import modalconfirmacion from '../../snippets/sistema/alerts/confirmationModal.vue';
  import modalalerta from '../../snippets/sistema/alerts/alertModal.vue';
  import { mixin } from '../../../mixins.js';
  
  export default {
    components: { modalver, modalcrear, modaleditar, modalconfirmacion, modalalerta },
    mixins: [mixin],
    data() {
      return {
        participationScEvents: [],
        filteredParticipationScEvents: [],
        uniqueProgressReports: [],
        selectedProgressReport: '',
        showDetailsParticipation: false,
        showNewParticipation: false,
        showEditParticipation: false,
        participationScEvent: null,
        participationEdit: null,
        mostrarTabla: false,
        mostrarCarga: true,
        table: null,
      };
    },
    created() {
      this.getParticipations(this.userID);
    },
    methods: {
      async deleteSelected() {
          // Obtener las filas seleccionadas
          let selectedData = this.table.rows({ selected: true }).data().toArray();
          let selectedIds = selectedData.map(row => {
              // Supongamos que el ID está en la segunda columna (índice 1)
              const tempDiv = document.createElement("div");
              tempDiv.innerHTML = row[1]; // Cambia el índice según dónde esté el ID
              return tempDiv.textContent.trim(); // Obtener el contenido de texto que es el ID
          });
          let module = 'participation-sc'; // Cambia este valor dinámicamente según el módulo
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
      async getParticipations(id) {
        try {
          const response = await axios.get(`api/participationScEvents/${id}`);
          this.participationScEvents = response.data;
          this.filteredParticipationScEvents = [...this.participationScEvents];
          this.uniqueProgressReports = [
            ...new Set(this.participationScEvents.map(event => event.progressReport).filter(Boolean)),
          ].sort((a, b) => a - b);
          if (this.table != null) {
            this.table.destroy();
          }
          this.crearTabla('#myTableParticipations');
        } catch (error) {
          console.error('Error fetching participations:', error);
        }
      },
      filterByProgressReport() {
        this.mostrarCarga = true;
        if (this.selectedProgressReport === '') {
          this.filteredParticipationScEvents = [...this.participationScEvents];
        } else {
          this.filteredParticipationScEvents = this.participationScEvents.filter(
            event => event.progressReport === this.selectedProgressReport
          );
        }
        if (this.table != null) {
          this.table.destroy();
        }
        setTimeout(() => {
          this.crearTabla('#myTableParticipations');
          this.mostrarCarga = false;
        }, 500);
      },
      recargarTabla(type) {
        this.mostrarCarga = true;
        if (type === 'General') {
          this.getParticipations(this.userID);
        } else {
          this.crearTabla('#myTableParticipations');
        }
      },
      editParticipation(participation) {
        this.participationEdit = participation;
        this.showEditParticipation = true;
      },
      async deleteParticipation(id) {
        const ok = await this.$refs.confirmation.show({
            title: 'Delete Participation Sc Event',
            message: `Are you sure you want to delete this Participation Sc Event?`,
            okButton: 'Delete',
            cancelButton: 'Return',
        });
        if (ok) {
            try {
                // Obtén el ID del usuario actual desde la sesión o contexto
                const userId = this.userID; // Asegúrate de tener acceso a este dato en tu componente
                
                // Envía el ID del proyecto y el ID del usuario
                await axios.delete(`api/participationScEvents/${id}`, {
                    data: { user_id: userId },
                });

                // Mostrar mensaje de éxito
                this.toast.success('Participation Sc Event successfully removed!', {
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
                console.error('Error deleting Participation Sc Events:', error);
            }
        }
      },
    },
  };
  </script>
  