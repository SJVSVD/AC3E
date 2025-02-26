<template>
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="row pb-0 p-4">
                    <div class="col-lg-10 col-md-12">
                        <div class="info-box">
                            Includes all visits and stays by AC3E members (students, researchers, postdocs, etc.) abroad and all foreign researchers or students received by AC3E in Chile, executed during the current baseline year (Sep-present).
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-12 d-flex justify-content-lg-end justify-content-center align-items-center">
                        <div class="d-flex">
                            <button v-if="!is('Staff') && !is('Anid') && !is('Titular Researcher')" @click="deleteSelected" class="btn btn-spacing btn-closed"><i class="fa fa-fw fa-trash"></i>  Selected Records</button>
                            <a v-if="!is('Staff') && !is('Anid')" class="btn btn-spacing btn-continue" id="show-modal1" @click="showNewCollaboration = true"><i class="fa-solid fa-add"></i></a>
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
                        <div class="form-check pt-2">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input" v-model="showActiveOnly">
                                Show Active Records Only
                            </label>
                            &nbsp;
                            <a class="btn btn-xs btn-search-blue" @click="recargarTabla('Active')"><i class="fa-solid fa-magnifying-glass"></i></a>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2" style="min-height: 400px">
                    <div class="container">
                        <div class="table-responsive p-0">
                            <div v-show="mostrarCarga" class="loader-sm"></div>
                            <table v-show="mostrarTabla" class="table table-striped align-items-center mb-0" id="myTableCollaborations">
                                <thead class="thead-light">
                                    <tr>
                                        <th style="min-width: 16px;"></th>
                                        <th data-orderable="true" class="text-xs font-weight-bold text-left">ID</th>
                                        <th data-orderable="false" class="text-xs font-weight-bold text-left">Actions</th>
                                        <th data-orderable="false" class="text-xs font-weight-bold text-left">Status</th>
                                        <th data-orderable="true" class="text-xs font-weight-bold text-left">User</th>
                                        <th data-orderable="true" class="text-xs font-weight-bold text-left">Progress Report Year</th>
                                        <th data-orderable="true" class="text-xs font-weight-bold text-left">Activity Name</th>
                                        <th data-orderable="true" class="text-xs font-weight-bold text-left">Ac3e Researchers Involved</th>
                                        <th data-orderable="true" class="text-xs font-weight-bold text-left">Name of External Person</th>
                                        <th data-orderable="true" class="text-xs font-weight-bold text-left">Beginning Date</th>
                                        <th data-orderable="true" class="text-xs font-weight-bold text-left">Ending Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="scCollaboration in filteredCollaborations" :key="scCollaboration.id">
                                        <td></td>
                                        <td>
                                            <p class="text-sm font-weight-bold">{{ scCollaboration.id }}</p>
                                        </td>
                                        <td class="text-left">
                                            <div class="d-flex px-1 py-1 justify-content-start align-items-center">
                                                <a v-if="!is('Staff') && !is('Anid') && (!is('Titular Researcher') || scCollaboration.idUsuario == userID)" class="btn btn-alert btn-xs" title="Edit" @click="editCollaboration(scCollaboration)"><i class="fa fa-fw fa-edit"></i></a>
                                                &nbsp;
                                                <a class="btn btn-success btn-xs" title="Details" @click="verCollaboration(scCollaboration)"><i class="fa-regular fa-eye"></i></a>
                                                &nbsp;
                                                <a v-if="!is('Staff') && !is('Anid') && !is('Titular Researcher')|| scCollaboration.idUsuario == userID"  
                            class="btn btn-closed btn-xs" title="Delete" @click="deleteCollaboration(scCollaboration.id)"><i class="fa fa-fw fa-trash"></i></a>
                                            </div>
                                        </td>
                                        <td class="text-start">
                                            <span v-if="scCollaboration.status == 'Draft'" class="badge bg-alert">Draft</span>
                                            <span v-else-if="scCollaboration.status == 'Finished'" class="badge bg-success">Registered</span>
                                            <span v-else class="badge bg-secondary">No information</span>
                                        </td>
                                        <td class="text-sm text-nowrap" :title="scCollaboration.usuario.name || '---'">
                                            {{ truncateText(scCollaboration.usuario.name || '---', 60) }}
                                        </td>
                                        <td class="text-sm text-nowrap" :title="scCollaboration.progressReport || '---'">
                                            {{ truncateText(scCollaboration.progressReport || '---', 60) }}
                                        </td>
                                        <td class="text-sm text-start text-nowrap" :title="scCollaboration.activityName || '---'">
                                            {{ truncateText(scCollaboration.activityName || '---', 40) }}
                                        </td>
                                        <td class="text-sm text-start text-nowrap" :title="scCollaboration.researcherInvolved || '---'">
                                            {{ truncateText(scCollaboration.researcherInvolved || '---', 60) }}
                                        </td>
                                        <td class="text-sm text-start text-nowrap" :title="scCollaboration.nameOfExternalResearcher || '---'">
                                            {{ truncateText(scCollaboration.nameOfExternalResearcher || '---', 60) }}
                                        </td>
                                        <td class="text-sm text-start text-nowrap" :title="scCollaboration.beginningDate || '---'">
                                            {{ this.thisDate(scCollaboration.beginningDate) || '---' }}
                                        </td>
                                        <td class="text-sm text-start text-nowrap" :title="scCollaboration.endingDate || '---'">
                                            {{ this.thisDate(scCollaboration.endingDate) || '---' }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="row">
                                <div class="col-3">
                                    <label style="font-weight: 500">
                                        These buttons use the elements selected in the table, if none exist, it will select all the records.
                                    </label>
                                </div>
                                <div class="col-auto">
                                    <label title="To select a single record from the table, just do &#013; Click on the box in the first column to select &#013; several consecutive hold SHIFT, to select several &#013; non-consecutive hold CTRL.">
                                        <span class="badge bg-dark-grey fs-10">?</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <modalconfirmacion ref="confirmation"></modalconfirmacion>
            <modalalerta ref="alert"></modalalerta>
            <modalver v-bind:collaboration1="scCollaboration" v-if="showDetailsScCollaboration" @close="showDetailsScCollaboration = false"></modalver>
            <modaleditar v-bind:collaboration1="collaborationEdit" v-if="showEditCollaboration" @close="showEditCollaboration = false" @recarga="recargarTabla('General')"></modaleditar>
            <modalcrear v-if="showNewCollaboration" @close="showNewCollaboration = false" @recarga="recargarTabla('General')"></modalcrear>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import modalver from '../../snippets/sistema/scCollaborations/detailsScCollaborationsModal.vue';
import modalcrear from '../../snippets/sistema/scCollaborations/createScCollaborationModal.vue';
import modaleditar from '../../snippets/sistema/scCollaborations/editScCollaborationModal.vue';
import modalconfirmacion from '../../snippets/sistema/alerts/confirmationModal.vue';
import modalalerta from '../../snippets/sistema/alerts/alertModal.vue';
import { mixin } from '../../../mixins.js';

export default {
    components: { modalver, modalcrear, modaleditar, modalconfirmacion, modalalerta },
    mixins: [mixin],
    data() {
        return {
            scCollaborations: null,
            filteredCollaborations: null,
            uniqueProgressReports: [],
            selectedProgressReport: '',
            showActiveOnly: false,
            showDetailsScCollaboration: false,
            showNewCollaboration: false,
            showEditCollaboration: false,
            collaborationEdit: null,
            mostrarTabla: false,
            mostrarCarga: true,
            table: null,
        };
    },
    created() {
        this.getCollaborations(this.userID);
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
        verCollaboration(scCollaboration){
            this.scCollaboration = scCollaboration;
            this.showDetailsScCollaboration = true;
        },
        async getCollaborations(id) {
            try {
                const response = await axios.get(`api/scCollaborations/${id}`);
                this.scCollaborations = response.data;
                this.filteredCollaborations = [...response.data];

                // Extract unique progressReport values
                this.uniqueProgressReports = [
                    ...new Set(this.scCollaborations.map(pub => pub.progressReport).filter(Boolean)),
                ].sort((a, b) => b - a);

                if (this.table != null) {
                    this.table.destroy();
                }
                this.crearTabla('#myTableCollaborations');
            } catch (error) {
                console.error('Error fetching collaborations:', error);
            }
        },
        getActiveCollaborations(id) {
            axios.get(`api/scCollaborationsActive/${id}`).then(response => {
                this.scCollaborations = response.data;
                this.filteredCollaborations = [...response.data];

                if (this.table != null) {
                    this.table.destroy();
                }
                this.crearTabla('#myTableCollaborations');
            }).catch(error => {
                console.error('Error fetching active collaborations:', error);
            });
        },
        filterByProgressReport() {
            this.mostrarCarga = true;
            if (this.selectedProgressReport === '') {
                this.filteredCollaborations = [...this.scCollaborations];
            } else {
                this.filteredCollaborations = this.scCollaborations.filter(
                    pub => pub.progressReport === this.selectedProgressReport
                );
            }

            if (this.table != null) {
                this.table.destroy();
            }

            setTimeout(() => {
                this.crearTabla('#myTableCollaborations');
                this.mostrarCarga = false;
            }, 500);
        },
        editCollaboration(collaboration){
            this.collaborationEdit = collaboration;
            this.showEditCollaboration= true;
        },
        recargarTabla($tipoRecarga) {
            this.mostrarCarga = true;
            if ($tipoRecarga === 'General') {
                this.showActiveOnly = false;
                this.getCollaborations(this.userID);
            } else if ($tipoRecarga === 'Active') {
                if (this.showActiveOnly) {
                    this.getActiveCollaborations(this.userID);
                } else {
                    this.recargarTabla('General');
                }
            } else {
                this.crearTabla('#myTableCollaborations');
            }
        },
        async deleteCollaboration(id) {
            const ok = await this.$refs.confirmation.show({
                title: 'Delete Sc Collaboration',
                message: `Are you sure you want to delete this Sc Collaboration?`,
                okButton: 'Delete',
                cancelButton: 'Return',
            });
            if (ok) {
                try {
                    // Obtén el ID del usuario actual desde la sesión o contexto
                    const userId = this.userID; // Asegúrate de tener acceso a este dato en tu componente
                    
                    // Envía el ID del proyecto y el ID del usuario
                    await axios.delete(`api/scCollaborations/${id}`, {
                        data: { user_id: userId },
                    });

                    // Mostrar mensaje de éxito
                    this.toast.success('Sc Collaboration successfully removed!', {
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
                    console.error('Error deleting Sc Collaboration:', error);
                }
            }
        },
    },
};
</script>
