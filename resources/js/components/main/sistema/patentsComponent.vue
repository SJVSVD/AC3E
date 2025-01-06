<template>
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="row pb-0 p-4">
                    <div class="col-lg-10 col-md-12">
                        <div class="info-box">
                            Includes all forms of intellectual protection involving AC3E researchers among the authors, including applications and grants, during the current baseline year (Sep-present). If the intellectual property has been awarded, please add the award code/grant number.
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-12 d-flex justify-content-lg-end justify-content-center align-items-center">
                        <div class="d-flex">
                            <button v-if="!is('Staff') && !is('Anid') && !is('Titular Researcher')" @click="deleteSelected" class="btn btn-spacing btn-closed"><i class="fa fa-fw fa-trash"></i> Delete Selected</button>
                            <a v-if="!is('Staff') && !is('Anid')" class="btn btn-spacing btn-continue" id="show-modal1" @click="showNewPatent = true">New Entry</a>
                            <a class="btn btn-spacing btn-search-blue" @click="recargarTabla('General')"><i class="fa-solid fa-rotate"></i></a>
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

                </div>
                <div class="card-body px-0 pt-0 pb-2" style="min-height: 400px">
                    <div class="container">
                        <div class="table-responsive p-0">
                            <div v-show="mostrarCarga" class="loader-sm"></div>
                            <table v-show="mostrarTabla" class="table align-items-center mb-0" id="myTablePatents">
                                <thead>
                                    <tr style="color: black">
                                        <th style="min-width: 16px;"></th>
                                        <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Actions</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Status</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">User</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Authors</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Name of Patent</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Status Application</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Registration Number</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Researchers Involved</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="patent in filteredPatents" :key="patent.id">
                                        <td></td>
                                        <td>
                                            <p class="text-sm font-weight-bolder mb-0" style="color:black">{{ patent.id }}</p>
                                        </td>                                          
                                        <td class="align-middle text-end">
                                            <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                <a v-if="!is('Staff') && !is('Anid') && (!is('Titular Researcher') || patent.idUsuario == userID)" class="btn btn-alert btn-xs" title="Edit" @click="editPatent(patent)"><i class="fa fa-fw fa-edit"></i></a>
                                                &nbsp;
                                                <a class="btn btn-success btn-xs" title="Details" @click="verPatent(patent)"><i class="fa-regular fa-eye"></i></a>
                                                &nbsp;
                                                <a v-if="!is('Staff') && !is('Anid') && !is('Titular Researcher')" class="btn btn-closed btn-xs" title="Delete" @click="deletePatent(patent.id)"><i class="fa fa-fw fa-trash"></i></a>
                                            </div>
                                        </td>
                                        <td>
                                            <p v-if="patent.status == 'Draft'" class="text-sm font-weight-bolder mb-0" style="color:#878686">{{ patent.status }}</p>
                                            <p v-if="patent.status == 'Finished'" class="text-sm font-weight-bolder mb-0" style="color:#28A745">Registered</p>
                                        </td>                                          
                                        <td>
                                            <p class="text-sm mb-0">{{ patent.usuario.name }}</p>
                                        </td>
                                        <td>
                                            <p v-if="patent.authors == null" class="text-sm mb-0">---</p>
                                            <p v-else class="text-sm mb-0">{{ patent.authors }}</p>
                                        </td>
                                        <td>
                                            <p v-if="patent.nameOfPatent == null" class="text-sm mb-0">---</p>
                                            <p v-else class="text-sm mb-0 truncate-text">{{ patent.nameOfPatent }}</p>
                                        </td>
                                        <td>
                                            <p v-if="patent.applicationStatus == null" class="text-sm mb-0">---</p>
                                            <p v-else class="text-sm mb-0">{{ patent.applicationStatus }}</p>
                                        </td>
                                        <td>
                                            <p v-if="patent.registrationNumber == null" class="text-sm mb-0">---</p>
                                            <p v-else class="text-sm mb-0">{{ patent.registrationNumber }}</p>
                                        </td>
                                        <td>
                                            <p v-if="patent.researcherInvolved == null" class="text-sm mb-0">---</p>
                                            <p v-else class="text-sm mb-0">{{ patent.researcherInvolved }}</p>
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
            <modalver v-bind:patent1="patent" v-if="showDetailsPatent" @close="showDetailsPatent = false"></modalver>
            <modaleditar v-bind:patent1="patentEdit" v-if="showEditPatent" @close="showEditPatent = false" @recarga="recargarTabla('General')"></modaleditar>
            <modalcrear v-if="showNewPatent" @close="showNewPatent = false" @recarga="recargarTabla('General')"></modalcrear>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import modalver from '../../snippets/sistema/patents/detailsPatentsModal.vue'
import modalcrear from '../../snippets/sistema/patents/createPatentsModal.vue'
import modaleditar from '../../snippets/sistema/patents/editPatentsModal.vue'
import modalconfirmacion from '../../snippets/sistema/alerts/confirmationModal.vue'
import modalalerta from '../../snippets/sistema/alerts/alertModal.vue'
import {mixin} from '../../../mixins.js'

export default {
    components: { modalver ,modalcrear, modaleditar, modalconfirmacion, modalalerta },
    mixins: [mixin],
    data(){
        return{
            filteredPatents: [], // Publications filtered by progressReport
            uniqueProgressReports: [], // Unique progressReport values for the selector
            selectedProgressReport: '', // Selected progressReport value

            patents: null,
            patent: null,
            showDetailsPatent: false,
            showNewPatent: false,
            showEditPatent: false,
            patentEdit: null,
            mostrarTabla: false,
            mostrarCarga: true,
            table: null,
        }
    },
    created(){
        this.getPatents(this.userID);
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
            let module = 'patents'; // Cambia este valor dinámicamente según el módulo
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
        verPatent(patent){
            this.patent = patent;
            this.showDetailsPatent = true;
        },
        filterByProgressReport() {
            this.mostrarCarga = true;
            if (this.selectedProgressReport === '') {
                this.filteredPatents = [...this.patents];
            } else {
                this.filteredPatents = this.patents.filter(
                    pub => pub.progressReport === this.selectedProgressReport
                );
            }

            if (this.table != null) {
                this.table.destroy();
            }

            setTimeout(() => {
                this.crearTabla('#myTablePatents');
                this.mostrarCarga = false;
            }, 500);
        },  
        async getPatents(id){
            try {
                const response = await axios.get(`api/patents/${id}`);
                // Asegurar que siempre sea un arreglo, incluso si llega como un objeto
                this.patents = Array.isArray(response.data)
                    ? response.data
                    : Object.values(response.data || {}); // Maneja el caso de null o undefined

                // Ahora es seguro usar el spread operator
                this.filteredPatents = [...this.patents];

                // Verificar si hay registros antes de intentar extraer valores únicos
                if (this.patents.length > 0) {
                    this.uniqueProgressReports = [
                        ...new Set(this.patents.map(pub => pub.progressReport).filter(Boolean)),
                    ].sort((a, b) => b - a);
                } else {
                    this.uniqueProgressReports = [];
                }

                if (this.table != null) {
                    this.table.destroy();
                }
                this.crearTabla('#myTablePatents');
            } catch (error) {
                console.error('Error fetching postdoc:', error);
            }
        },
        recargarTabla($tipoRecarga){
            this.mostrarCarga = true;
            if($tipoRecarga == 'General'){
                this.patents = null;
                this.getPatents(this.userID);
            }
            else{
                this.crearTabla("#myTablePatents");
            }
        },
        editPatent(patent){
            this.patentEdit = patent;
            this.showEditPatent= true;
        },
        async deletePatent(id) {
            const ok = await this.$refs.confirmation.show({
                title: 'Delete Patent',
                message: `¿Are you sure you want to delete this Patent?.`,
                okButton: 'Delete',
                cancelButton: 'Return'
            })
            if (ok) {
                axios.delete(`api/patents/${id}`).then( response =>{
                    this.toast.success("Patent successfully removed!", {
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