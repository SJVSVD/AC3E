<template>
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="row pb-0 p-4">
                    <div class="col-lg-10 col-md-12">
                        <div class="info-box">
                            Includes all scientific collaborations with external researchers, in the context of R&D projects, executed during the current baseline year (Sep-present). 
The destination country and city correspond to the location of the project leader, while the origin corresponds to the location of the researcher supporting the project's execution.

                        </div>
                    </div>
                    <div class="col-lg-2 col-md-12 d-flex justify-content-lg-end justify-content-center align-items-center">
                        <div class="d-flex">
                            <button v-if="!is('Staff') && !is('Anid') && !is('Titular Researcher')" @click="deleteSelected" class="btn btn-spacing btn-closed"><i class="fa fa-fw fa-trash"></i>  Selected Records</button>
                            <a v-if="!is('Staff') && !is('Anid')" class="btn btn-spacing btn-continue" id="show-modal1" @click="showNewProject = true"><i class="fa-solid fa-add"></i></a>
                            <a class="btn btn-spacing btn-search-blue" @click="recargarTabla('General')"><i class="fa-solid fa-rotate"></i></a>
                        </div>
                    </div>
                    <div class="col-md-2">
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
                    <div class="col-md-4">
                        <div class="form-check pt-2 ">
                        <label class="form-check-label"><input type="checkbox" class="form-check-input"
                                v-model="showActiveOnly"> Show Active Records Only</label>
                                &nbsp;
                                <a class="btn btn-xs btn-search-blue" @click="recargarTabla('Active')"><i class="fa-solid fa-magnifying-glass"></i></a>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2" style="min-height: 400px">
                    <div class="container">
                        <div class="table-responsive p-0">
                            <div v-show="mostrarCarga" class="loader-sm"></div>
                            <table v-show="mostrarTabla" class="table align-items-center mb-0" id="myTableProjects">
                                <thead>
                                    <tr style="color: black">
                                        <th style="min-width: 16px;"></th>
                                        <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Actions</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Status</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">User</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Activity Name</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Name of AC3E Member</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Name of External Person</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Beggining Date</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Ending Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="conjointProject in filteredProjects" :key="conjointProject.id">
                                        <td></td>
                                        <td>
                                            <p class="text-sm font-weight-bolder mb-0" style="color:black">{{ conjointProject.id }}</p>
                                        </td>                                          
                                        <td class="align-middle text-end">
                                            <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                <a v-if="!is('Staff') && !is('Anid') && (!is('Titular Researcher') || conjointProject.idUsuario == userID)" class="btn btn-alert btn-xs" title="Edit" @click="editProject(conjointProject)"><i class="fa fa-fw fa-edit"></i></a>
                                                &nbsp;
                                                <a class="btn btn-success btn-xs" title="Details" @click="verProject(conjointProject)"><i class="fa-regular fa-eye"></i></a>
                                                &nbsp;
                                                <a v-if="!is('Staff') && !is('Anid') && !is('Titular Researcher')" class="btn btn-closed btn-xs" title="Delete" @click="deleteProject(conjointProject.id)"><i class="fa fa-fw fa-trash"></i></a>
                                            </div>
                                        </td>
                                        <td>
                                            <p v-if="conjointProject.status == 'Draft'" class="text-sm font-weight-bolder mb-0" style="color:#878686">{{ conjointProject.status }}</p>
                                            <p v-if="conjointProject.status == 'Finished'" class="text-sm font-weight-bolder mb-0" style="color:#28A745">Registered</p>
                                        </td>                                          
                                        <td>
                                            <p class="text-sm mb-0">{{ conjointProject.usuario.name }}</p>
                                        </td>
                                        <td>
                                            <p v-if="conjointProject.activityName == null" class="text-sm mb-0">---</p>
                                            <p v-else class="text-sm mb-0">{{ conjointProject.activityName }}</p>
                                        </td>
                                        <td>
                                            <p v-if="conjointProject.researcherInvolved == null" class="text-sm mb-0">---</p>
                                            <p v-else class="text-sm mb-0">{{ conjointProject.researcherInvolved }}</p>
                                        </td>
                                        <td>
                                            <p v-if="conjointProject.nameOfExternalResearcher == null" class="text-sm mb-0">---</p>
                                            <p v-else class="text-sm mb-0">{{ conjointProject.nameOfExternalResearcher }}</p>
                                        </td>
                                        <td>
                                            <p v-if="conjointProject.beginningDate == null" class="text-sm mb-0">---</p>
                                            <p v-else class="text-sm mb-0">{{ this.thisDate(conjointProject.beginningDate) }}</p>
                                        </td>
                                        <td>
                                            <p v-if="conjointProject.endingDate == null" class="text-sm mb-0">---</p>
                                            <p v-else class="text-sm mb-0">{{ this.thisDate(conjointProject.endingDate) }}</p>
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
            <modalver v-bind:project1="conjointProject" v-if="showDetailsProject" @close="showDetailsProject = false"></modalver>
            <modaleditar v-bind:project1="projectEdit" v-if="showEditProject" @close="showEditProject = false" @recarga="recargarTabla('General')"></modaleditar>
            <modalcrear v-if="showNewProject" @close="showNewProject = false" @recarga="recargarTabla('General')"></modalcrear>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import modalver from '../../snippets/sistema/conjointProjects/detailsConjointProjectsModal.vue'
import modalcrear from '../../snippets/sistema/conjointProjects/createConjointProjectsModal.vue'
import modaleditar from '../../snippets/sistema/conjointProjects/editConjointProjectsModal.vue'
import modalconfirmacion from '../../snippets/sistema/alerts/confirmationModal.vue'
import modalalerta from '../../snippets/sistema/alerts/alertModal.vue'
import {mixin} from '../../../mixins.js'

export default {
    components: { modalver ,modalcrear, modaleditar, modalconfirmacion, modalalerta },
    mixins: [mixin],
    data(){
        return{
            conjointProjects: null,
            filteredProjects: null,
            uniqueProgressReports: [],
            selectedProgressReport: '',
            conjointProject: null,
            showNewProject: false,
            showActiveOnly: false,
            showDetailsProject: false,
            showEditProject: false,
            projectEdit: null,
            mostrarTabla: false,
            mostrarCarga: true,
            table: null,
        }
    },
    created(){
        this.getConjointProjects(this.userID);
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
            let module = 'conjoint'; // Cambia este valor dinámicamente según el módulo
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
        verProject(conjointProject){
            this.conjointProject = conjointProject;
            this.showDetailsProject = true;
        },
        async getConjointProjects(id){
            try {
                const response = await axios.get(`api/conjointProjects/${id}`);
                this.conjointProjects = response.data;
                this.filteredProjects = [...response.data];

                // Extract unique progressReport values
                this.uniqueProgressReports = [
                    ...new Set(this.conjointProjects.map(pub => pub.progressReport).filter(Boolean)),
                ].sort((a, b) => b - a);

                if (this.table != null) {
                    this.table.destroy();
                }
                this.crearTabla('#myTableProjects');
            } catch (error) {
                console.error('Error fetching collaborations:', error);
            }
        },
        getActiveProjects(id){
            axios.get(`api/conjointProjectsActive/${id}`).then( response =>{
                this.conjointProjects = response.data;
                this.filteredProjects = [...response.data];
                if (this.table != null){
                    this.table.destroy();
                }
                this.crearTabla('#myTableProjects');
            }).catch(e=> console.log(e))
        },
        filterByProgressReport() {
            this.mostrarCarga = true;
            if (this.selectedProgressReport === '') {
                this.filteredProjects = [...this.conjointProjects];
            } else {
                this.filteredProjects = this.conjointProjects.filter(
                    pub => pub.progressReport === this.selectedProgressReport
                );
            }

            if (this.table != null) {
                this.table.destroy();
            }

            setTimeout(() => {
                this.crearTabla('#myTableProjects');
                this.mostrarCarga = false;
            }, 500);
        },
        recargarTabla($tipoRecarga){
            this.mostrarCarga = true;
            if($tipoRecarga == 'General'){
                this.showActiveOnly = false;
                this.conjointProjects = null;
                this.getConjointProjects(this.userID);
            }else if($tipoRecarga == 'Active'){
                if (this.showActiveOnly) {
                    this.conjointProjects = null;
                    this.getActiveProjects(this.userID);
                }else{
                    this.recargarTabla('General');
                }
            }else{
                this.crearTabla("#myTableProjects");
            }
        },
        editProject(collaboration){
            this.projectEdit = collaboration;
            this.showEditProject= true;
        },
        async deleteProject(id) {
            const ok = await this.$refs.confirmation.show({
                title: 'Delete Collaborative Project',
                message: `Are you sure you want to delete this Collaborative Project?`,
                okButton: 'Delete',
                cancelButton: 'Return',
            });
            if (ok) {
                try {
                    // Obtén el ID del usuario actual desde la sesión o contexto
                    const userId = this.userID; // Asegúrate de tener acceso a este dato en tu componente
                    
                    // Envía el ID del proyecto y el ID del usuario
                    await axios.delete(`api/conjointProjects/${id}`, {
                        data: { user_id: userId },
                    });

                    // Mostrar mensaje de éxito
                    this.toast.success('Collaborative Project successfully removed!', {
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
                    console.error('Error deleting Collaborative Project:', error);
                }
            }
        },

    }
}
</script>