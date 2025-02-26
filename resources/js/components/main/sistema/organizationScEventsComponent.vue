<template>
    <div class="row mt-4 mx-4">
      <div class="col-12">
        <div class="card mb-4">
          <div class="row pb-0 p-4">
            <div class="col-lg-10 col-md-12">
              <div class="info-box">
                Includes all scientific events executed during the current baseline year (Sep-present), in which one or more researchers participated as organizers (administrative committee, technical committee, chair, etc). You must attach supporting documents that confirm your role as the event organizer, either in PDF, PNG, or JPEG format. The supporting document can be a confirmation letter, an email, an event program, a screenshot of the event webpage, etc.
              </div>
            </div>
            <div class="col-lg-2 col-md-12 d-flex justify-content-lg-end justify-content-center align-items-center">
              <div class="d-flex">
                <button v-if="!is('Staff') && !is('Anid') && !is('Titular Researcher')" @click="deleteSelected" class="btn btn-spacing btn-closed">
                  <i class="fa fa-fw fa-trash"></i>  Selected Records
                </button>
                <a v-if="!is('Staff') && !is('Anid')" class="btn btn-spacing btn-continue" id="show-modal1" @click="showNewOrganization = true"><i class="fa-solid fa-add"></i></a>
                <a class="btn btn-spacing btn-search-blue" @click="recargarTabla('General')">
                  <i class="fa-solid fa-rotate"></i>
                </a>
              </div>
            </div>
          </div>
  
          <!-- ProgressReport Filter -->
          <div class="row px-4 mb-2">
            <div class="col-lg-2 col-md-6">
              <label for="progressReportFilter" class="form-label">Filter By Progress Report Year:</label>
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
                <table v-show="mostrarTabla" class="table table-striped align-items-center mb-0" id="MyTableOrganization">
                  <thead class="thead-light">
                    <tr>
                      <th style="min-width: 16px;"></th>
                      <th data-orderable="true" class="text-xs font-weight-bold text-left">ID</th>
                      <th data-orderable="false" class="text-xs font-weight-bold text-left">Actions</th>
                      <th data-orderable="false" class="text-xs font-weight-bold text-left">Status</th>
                      <th data-orderable="true" class="text-xs font-weight-bold text-left">User</th>
                      <th data-orderable="true" class="text-xs font-weight-bold text-left">Progress Report Year</th>
                      <th data-orderable="true" class="text-xs font-weight-bold text-left">Event Name</th>
                      <th data-orderable="true" class="text-xs font-weight-bold text-left">Type of Event</th>
                      <th data-orderable="true" class="text-xs font-weight-bold text-left">Start Date</th>
                      <th data-orderable="true" class="text-xs font-weight-bold text-left">Ending Date</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="organizationScEvent in filteredOrganizationScEvents" :key="organizationScEvent.id">
                      <td></td>
                      <td>
                        <p class="text-sm font-weight-bold">
                          {{ organizationScEvent.id }}
                        </p>
                      </td>
                      <td class="text-left">
                        <div class="d-flex px-1 py-1 justify-content-start align-items-center">
                          <a
                            v-if="organizationScEvent.file != null && organizationScEvent.is_link == 0"
                            class="btn btn-search-blue btn-xs"
                            title="Download File"
                            @click="descargarExtracto(organizationScEvent.id, organizationScEvent.usuario.name)"
                          >
                            <i class="fa-solid fa-download"></i>
                          </a>
                          <a
                            v-else-if="organizationScEvent.is_link == 1"
                            :href="organizationScEvent.file"
                            class="btn btn-search-blue btn-xs"
                            title="Redirect"
                            target="_blank"
                          >
                            <i class="fa-solid fa-arrow-up-right-from-square"></i>
                          </a>
                          <a v-else class="btn btn-grey btn-xs"><i class="fa-solid fa-download"></i></a>
                          &nbsp;
                          <a v-if="!is('Staff') && !is('Anid') && (!is('Titular Researcher') || organizationScEvent.idUsuario == userID)" class="btn btn-alert btn-xs" title="Edit" @click="editOrganization(organizationScEvent)">
                            <i class="fa fa-fw fa-edit"></i>
                          </a>
                          &nbsp;
                          <a
                            class="btn btn-success btn-xs"
                            title="Details"
                            @click="verOrganization(organizationScEvent)"
                          >
                            <i class="fa-regular fa-eye"></i>
                          </a>
                          &nbsp;
                          <a v-if="!is('Staff') && !is('Anid') && !is('Titular Researcher')|| organizationScEvent.idUsuario == userID"  
                            class="btn btn-closed btn-xs" title="Delete" @click="deleteOrganization(organizationScEvent.id)">
                            <i class="fa fa-fw fa-trash"></i>
                          </a>
                        </div>
                      </td>
                      <td class="text-start">
                          <span v-if="organizationScEvent.status == 'Draft'" class="badge bg-alert">Draft</span>
                          <span v-else-if="organizationScEvent.status == 'Finished'" class="badge bg-success">Registered</span>
                          <span v-else class="badge bg-secondary">No information</span>
                      </td>
                      <td class="text-sm text-nowrap" :title="organizationScEvent.usuario.name">
                        {{ truncateText(organizationScEvent.usuario.name, 60) }}
                      </td>
                      <td class="text-sm text-nowrap" :title="organizationScEvent.progressReport">
                        {{ truncateText(organizationScEvent.progressReport, 60) }}
                      </td>
                      <td class="text-sm text-start text-nowrap" :title="organizationScEvent.eventName || '---'">
                        {{ truncateText(organizationScEvent.eventName || '---', 60) }}
                      </td>
                      <td class="text-sm text-start text-nowrap" :title="organizationScEvent.typeEvent || '---'">
                        {{ truncateText(organizationScEvent.typeEvent || '---', 60) }}
                      </td>
                      <td class="text-sm text-nowrap">{{ this.thisDate(organizationScEvent.startDate) }}</td>
                      <td class="text-sm text-nowrap">{{ this.thisDate(organizationScEvent.endingDate) }}</td>
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
        v-bind:organization1="organizationScEvent"
        v-if="showDetailsOrganization"
        @close="showDetailsOrganization = false"
      ></modalver>
      <modaleditar
        v-bind:organization1="organizationEdit"
        v-if="showEditOrganization"
        @close="showEditOrganization = false"
        @recarga="recargarTabla('General')"
      ></modaleditar>
      <modalcrear
        v-if="showNewOrganization"
        @close="showNewOrganization = false"
        @recarga="recargarTabla('General')"
      ></modalcrear>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  import modalver from '../../snippets/sistema/organizationScEvents/detailsOrganizationScEventsModal.vue';
  import modalcrear from '../../snippets/sistema/organizationScEvents/createOrganizationScEventModal.vue';
  import modaleditar from '../../snippets/sistema/organizationScEvents/editOrganizationScEventModal.vue';
  import modalconfirmacion from '../../snippets/sistema/alerts/confirmationModal.vue';
  import modalalerta from '../../snippets/sistema/alerts/alertModal.vue';
  import { mixin } from '../../../mixins.js';
  
  export default {
    components: { modalver, modalcrear, modaleditar, modalconfirmacion, modalalerta },
    mixins: [mixin],
    data() {
      return {
        organizationScEvents: [],
        filteredOrganizationScEvents: [],
        uniqueProgressReports: [],
        selectedProgressReport: '',
        showDetailsOrganization: false,
        showNewOrganization: false,
        showEditOrganization: false,
        organizationScEvent: null,
        organizationEdit: null,
        mostrarTabla: false,
        mostrarCarga: true,
        table: null,
      };
    },
    created() {
      this.getOrganizations(this.userID);
    },
    methods: {
      descargarExtracto(id,nombre){
          axios({
              url: `api/organizationDownload/${id}`,
              method: 'GET',
              responseType: 'arraybuffer',
          }).then((response) => {
              let blob = new Blob([response.data], {
                  type: response.headers['content-type']
              });
              let link = document.createElement('a');
              link.href = window.URL.createObjectURL(blob);
              if (blob.type.includes('pdf')) {
                  link.download = `organizationSc-${nombre}.pdf`;
              } else if (blob.type.includes('image')) {
                  link.download = `organizationSc-${nombre}.png`; // Cambia la extensión según el tipo de imagen
              } else {
                  // Si el tipo de archivo no es ni PDF ni imagen, puedes manejarlo de acuerdo a tus requerimientos
                  console.error('Tipo de archivo no compatible');
                  return;
              }
              link.click();
          });
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
          let module = 'organization-sc'; // Cambia este valor dinámicamente según el módulo
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
      async getOrganizations(id) {
        try {
          const response = await axios.get(`api/organizationScEvents/${id}`);
          this.organizationScEvents = response.data;
          this.filteredOrganizationScEvents = [...this.organizationScEvents];
          this.uniqueProgressReports = [
            ...new Set(this.organizationScEvents.map(event => event.progressReport).filter(Boolean)),
          ].sort((a, b) => a - b);
          if (this.table != null) {
            this.table.destroy();
          }
          this.crearTabla('#MyTableOrganization');
        } catch (error) {
          console.error('Error fetching events:', error);
        }
      },
      filterByProgressReport() {
        this.mostrarCarga = true;
        if (this.selectedProgressReport === '') {
          this.filteredOrganizationScEvents = [...this.organizationScEvents];
        } else {
          this.filteredOrganizationScEvents = this.organizationScEvents.filter(
            event => event.progressReport === this.selectedProgressReport
          );
        }
        if (this.table != null) {
          this.table.destroy();
        }
        setTimeout(() => {
          this.crearTabla('#MyTableOrganization');
          this.mostrarCarga = false;
        }, 500);
      },
      recargarTabla(type) {
        this.mostrarCarga = true;
        if (type === 'General') {
          this.getOrganizations(this.userID);
        } else {
          this.crearTabla('#MyTableOrganization');
        }
      },
      editOrganization(organization) {
        this.organizationEdit = organization;
        this.showEditOrganization = true;
      },
      verOrganization(organization){
          this.organizationScEvent = organization;
          this.showDetailsOrganization = true;
      },
      async deleteOrganization(id) {
        const ok = await this.$refs.confirmation.show({
            title: 'Delete Organization Sc',
            message: `Are you sure you want to delete this Organization Sc?`,
            okButton: 'Delete',
            cancelButton: 'Return',
        });
        if (ok) {
            try {
                // Obtén el ID del usuario actual desde la sesión o contexto
                const userId = this.userID; // Asegúrate de tener acceso a este dato en tu componente
                
                // Envía el ID del proyecto y el ID del usuario
                await axios.delete(`api/organizationScEvents/${id}`, {
                    data: { user_id: userId },
                });

                // Mostrar mensaje de éxito
                this.toast.success('Organization Sc successfully removed!', {
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
                console.error('Error deleting Organization Sc:', error);
            }
        }
      },
    },
  };
  </script>
  