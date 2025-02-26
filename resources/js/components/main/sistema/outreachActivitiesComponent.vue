<template>
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="row pb-0 p-4">
                    <div class="col-lg-10 col-md-12">
                        <div class="info-box">
                            Includes all outreach and dissemination activities that promote and share the work of AC3E and its members, carried out during the current baseline year (Sep–present). Outreach activities engage the general public and non-scientific audiences through initiatives such as school talks, press notes, mass media appearances, guided facility tours, and educational material publication. Dissemination activities focus on sharing research findings with specialized audiences through conferences, workshops, industry collaborations, and technical presentations.
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-12 d-flex justify-content-lg-end justify-content-center align-items-center">
                        <div class="d-flex">
                            <button v-if="!is('Staff') && !is('Anid') && !is('Titular Researcher')" @click="deleteSelected" class="btn btn-spacing btn-closed"><i class="fa fa-fw fa-trash"></i>  Selected Records</button>
                            <a v-if="!is('Staff') && !is('Anid')" class="btn btn-spacing btn-continue" id="show-modal1" @click="showNewOutreach = true"><i class="fa-solid fa-add"></i></a>
                            <a class="btn btn-spacing btn-search-blue" @click="recargarTabla('General')"><i class="fa-solid fa-rotate"></i></a>
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
                </div>
                <div class="card-body px-0 pt-0 pb-2" style="min-height: 400px">
                    <div class="container">
                        <div class="table-responsive p-0">
                            <div v-show="mostrarCarga" class="loader-sm"></div>
                            <table v-show="mostrarTabla" class="table table-striped align-items-center mb-0" id="myTableOutreach">
                                <thead class="thead-light">
                                    <tr>
                                        <th style="width: 16px;"></th>
                                        <th data-orderable="true" class="text-xs font-weight-bold text-left">ID</th>
                                        <th data-orderable="false" class="text-xs font-weight-bold text-left">Actions</th>
                                        <th data-orderable="false" class="text-xs font-weight-bold text-left">Status</th>
                                        <th data-orderable="true" class="text-xs font-weight-bold text-left">User</th>
                                        <th data-orderable="true" class="text-xs font-weight-bold text-left">Progress Report Year</th>
                                        <th data-orderable="true" class="text-xs font-weight-bold text-left">Activity Type</th>
                                        <th data-orderable="true" class="text-xs font-weight-bold text-left">Activity Name</th>
                                        <th data-orderable="true" class="text-xs font-weight-bold text-left">Attendant Amount</th>
                                        <th data-orderable="true" class="text-xs font-weight-bold text-left">Date</th>
                                        <th data-orderable="true" class="text-xs font-weight-bold text-left">Researchers Involved</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="outreachActivity in filteredOutreachs" :key="outreachActivity.id">
                                        <td></td>
                                        <td>
                                            <p class="text-sm font-weight-bold">{{ outreachActivity.id }}</p>
                                        </td>                                          
                                        <td class="text-left">
                                            <div class="d-flex px-1 py-1 justify-content-start align-items-center">
                                                <a v-if="!is('Staff') && !is('Anid') && (!is('Titular Researcher') || outreachActivity.idUsuario == userID)" class="btn btn-alert btn-xs" title="Edit" @click="editActivity(outreachActivity)"><i class="fa fa-fw fa-edit"></i></a>
                                                &nbsp;
                                                <a class="btn btn-success btn-xs" title="Details" @click="verActivity(outreachActivity)"><i class="fa-regular fa-eye"></i></a>
                                                &nbsp;
                                                <a v-if="!is('Staff') && !is('Anid') && !is('Titular Researcher')|| outreachActivity.idUsuario == userID"  
                            class="btn btn-closed btn-xs" title="Delete" @click="deleteActivity(outreachActivity.id)"><i class="fa fa-fw fa-trash"></i></a>
                                            </div>
                                        </td>
                                        <td class="text-start">
                                            <span v-if="outreachActivity.status == 'Draft'" class="badge bg-alert">Draft</span>
                                            <span v-else-if="outreachActivity.status == 'Finished'" class="badge bg-success">Registered</span>
                                            <span v-else class="badge bg-secondary">No information</span>
                                        </td>                                      

                                        <td class="text-sm text-nowrap" :title="outreachActivity.usuario.name">
                                            {{ truncateText(outreachActivity.usuario.name, 60) }}
                                        </td>
                                        <td class="text-sm text-nowrap" :title="outreachActivity.progressReport">
                                            {{ truncateText(outreachActivity.progressReport, 60) }}
                                        </td>
                                        <td class="text-sm text-start text-nowrap" :title="outreachActivity.activityType || '---'">
                                            {{ truncateText(outreachActivity.activityType || '---', 60) }}
                                        </td>
                                        <td class="text-sm text-start text-nowrap" :title="outreachActivity.activityName || '---'">
                                            {{ truncateText(outreachActivity.activityName || '---', 60) }}
                                        </td>
                                        <td class="text-sm text-nowrap" :title="outreachActivity.attendantsAmount || '---'">
                                            {{ truncateText(outreachActivity.attendantsAmount || '---', 60) }}
                                        </td>
                                        <td class="text-sm text-nowrap">{{ this.thisDate(outreachActivity.date) }}</td>
                                        <td class="text-sm text-start text-nowrap" :title="outreachActivity.researcherInvolved || '---'">
                                            {{ truncateText(outreachActivity.researcherInvolved || '---', 60) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="row">
                                <div class="col-3">
                                    <label style="font-weight: 500">
                                    These buttons use the elements selected in the table, if none exist, it will select all the records. </label>
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
            <modalver v-bind:activity1="outreachActivity" v-if="showDetailsActivity" @close="showDetailsActivity = false"></modalver>
            <modaleditar v-bind:activity1="activityEdit" v-if="showEditOutreach" @close="showEditOutreach = false" @recarga="recargarTabla('General')"></modaleditar>
            <modalcrear v-if="showNewOutreach" @close="showNewOutreach = false" @recarga="recargarTabla('General')"></modalcrear>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import modalver from '../../snippets/sistema/outreachActivities/detailsOutreachActivitiesModal.vue'
import modalcrear from '../../snippets/sistema/outreachActivities/createOutreachActivitiesModal.vue'
import modaleditar from '../../snippets/sistema/outreachActivities/editOutreachActivitiesModal.vue'
import modalconfirmacion from '../../snippets/sistema/alerts/confirmationModal.vue'
import modalalerta from '../../snippets/sistema/alerts/alertModal.vue'
import {mixin} from '../../../mixins.js'

export default {
    components: { modalver ,modalcrear, modaleditar, modalconfirmacion, modalalerta },
    mixins: [mixin],
    data(){
        return{
            filteredOutreachs: [], // Publications filtered by progressReport
            uniqueProgressReports: [], // Unique progressReport values for the selector
            selectedProgressReport: '', // Selected progressReport value

            outreachActivities: null,
            outreachActivity: null,
            showDetailsActivity: false,
            showNewOutreach: false,
            showEditOutreach: false,
            activityEdit: null,
            mostrarTabla: false,
            mostrarCarga: true,
            table: null,
        }
    },
    created(){
        this.getOutreachActivities(this.userID);
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
            let module = 'outreach'; // Cambia este valor dinámicamente según el módulo
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
                    this.recargarTabla("General");
                })
                .catch(error => {
                    console.error('Error deleting records:', error);
                    this.toast.error('Failed to delete records.');
                });
            }
        },
        verActivity(outreachActivity){
            this.outreachActivity = outreachActivity;
            this.showDetailsActivity = true;
        },
        filterByProgressReport() {
            this.mostrarCarga = true;
            if (this.selectedProgressReport === '') {
                this.filteredOutreachs = [...this.outreachActivities];
            } else {
                this.filteredOutreachs = this.outreachActivities.filter(
                    pub => pub.progressReport === this.selectedProgressReport
                );
            }

            if (this.table != null) {
                this.table.destroy();
            }

            setTimeout(() => {
                this.crearTabla('#myTableOutreach');
                this.mostrarCarga = false;
            }, 500);
        },
        async getOutreachActivities(id){
            try {
                const response = await axios.get(`api/outreachActivities/${id}`);
                this.outreachActivities = Array.isArray(response.data)
                    ? response.data
                    : Object.values(response.data || {}); // Maneja el caso de null o undefined

                // Ahora es seguro usar el spread operator
                this.filteredOutreachs = [...this.outreachActivities];

                // Verificar si hay registros antes de intentar extraer valores únicos
                if (this.outreachActivities.length > 0) {
                    this.uniqueProgressReports = [
                        ...new Set(this.outreachActivities.map(pub => pub.progressReport).filter(Boolean)),
                    ].sort((a, b) => b - a);
                } else {
                    this.uniqueProgressReports = [];
                }
                
                if (this.table != null) {
                    this.table.destroy();
                }
                this.crearTabla('#myTableOutreach');
            } catch (error) {
                console.error('Error fetching collaborations:', error);
            }
        },
        recargarTabla($tipoRecarga){
            this.mostrarCarga = true;
            if($tipoRecarga == 'General'){
                this.outreachActivities = null;
                this.getOutreachActivities(this.userID);
            }
            else{
                this.crearTabla("#myTableOutreach");
            }
        },
        editActivity(activity){
            this.activityEdit = activity;
            this.showEditOutreach= true;
        },
        async deleteActivity(id) {
            const ok = await this.$refs.confirmation.show({
                title: 'Delete Outreach Activity',
                message: `Are you sure you want to delete this Outreach Activity?`,
                okButton: 'Delete',
                cancelButton: 'Return',
            });
            if (ok) {
                try {
                    // Obtén el ID del usuario actual desde la sesión o contexto
                    const userId = this.userID; // Asegúrate de tener acceso a este dato en tu componente
                    
                    // Envía el ID del proyecto y el ID del usuario
                    await axios.delete(`api/outreachActivities/${id}`, {
                        data: { user_id: userId },
                    });

                    // Mostrar mensaje de éxito
                    this.toast.success('Outreach Activity successfully removed!', {
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
                    console.error('Error deleting Outreach Activity:', error);
                }
            }
        },
    }
}
</script>