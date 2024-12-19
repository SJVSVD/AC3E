<template>
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="row pb-0 p-4">
                    <div class="col-lg-10 col-md-12">
                        <div class="info-box">
                            Includes all new postdoctoral researchers working under the supervision of an AC3E researcher, during the current baseline year (Sep-present).
If the project end date is unknown, please enter an approximate date.

                        </div>
                    </div>
                    <div class="col-lg-2 col-md-12 d-flex justify-content-lg-end justify-content-center align-items-center">
                        <div class="d-flex">
                            <button v-if="!is('Staff') && !is('Anid') && !is('Titular Researcher')" @click="deleteSelected" class="btn btn-spacing btn-closed"><i class="fa fa-fw fa-trash"></i> Delete Selected</button>
                            <a v-if="!is('Staff') && !is('Anid') && !is('Titular Researcher')" class="btn btn-spacing btn-continue" id="show-modal1" @click="showNewPostDoc = true">New Entry</a>
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
                            <table v-show="mostrarTabla" class="table align-items-center mb-0" id="myTablePostDoc">
                                <thead>
                                    <tr style="color: black">
                                        <th style="min-width: 16px;"></th>
                                        <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Actions</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Status</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">User</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Name of Postdoc</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Supervisor Name</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Research Topic</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Start Year</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Ending Year</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="postDoc in filteredPostdoc" :key="postDoc.id">
                                        <td></td>
                                        <td>
                                            <p class="text-sm font-weight-bolder mb-0" style="color:black">{{ postDoc.id }}</p>
                                        </td>                                          
                                        <td class="align-middle text-end">
                                            <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                <a v-if="!is('Staff') && !is('Anid') && !is('Titular Researcher')" class="btn btn-alert btn-xs" title="Edit" @click="editPostDoc(postDoc)"><i class="fa fa-fw fa-edit"></i></a>
                                                &nbsp;
                                                <a class="btn btn-success btn-xs" title="Details" @click="verPostDoc(postDoc)"><i class="fa-regular fa-eye"></i></a>
                                                &nbsp;
                                                <a v-if="!is('Staff') && !is('Anid') && !is('Titular Researcher')" class="btn btn-closed btn-xs" title="Delete" @click="deletePostDoc(postDoc.id)"><i class="fa fa-fw fa-trash"></i></a>
                                            </div>
                                        </td>
                                        <td>
                                            <p v-if="postDoc.status == 'Draft'" class="text-sm font-weight-bolder mb-0" style="color:#878686">{{ postDoc.status }}</p>
                                            <p v-if="postDoc.status == 'Finished'" class="text-sm font-weight-bolder mb-0" style="color:#28A745">Registered</p>
                                        </td>                                          
                                        <td>
                                            <p class="text-sm mb-0">{{ postDoc.usuario.name }}</p>
                                        </td>
                                        <td>
                                            <p v-if="postDoc.nameOfPostdoc == null" class="text-sm mb-0">---</p>
                                            <p v-else class="text-sm mb-0 ">{{ postDoc.nameOfPostdoc }}</p>
                                        </td>
                                        <td>
                                            <p v-if="postDoc.supervisorName == null" class="text-sm mb-0">---</p>
                                            <p v-else class="text-sm mb-0">{{ postDoc.supervisorName }}</p>
                                        </td>
                                        <td>
                                            <p v-if="postDoc.researchTopic == null" class="text-sm mb-0">---</p>
                                            <p v-else class="text-sm mb-0 truncate-text">{{ postDoc.researchTopic }}</p>
                                        </td>
                                        <td>
                                            <p v-if="postDoc.startYear == null" class="text-sm mb-0">---</p>
                                            <p v-else class="text-sm mb-0">{{ postDoc.startYear }}</p>
                                        </td>
                                        <td>
                                            <p v-if="postDoc.endingYear == null" class="text-sm mb-0">---</p>
                                            <p v-else class="text-sm mb-0">{{ postDoc.endingYear }}</p>
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
            <modalver v-bind:postDoc1="postDoc" v-if="showDetailsPostDoc" @close="showDetailsPostDoc = false"></modalver>
            <modaleditar v-bind:postDoc1="postDocEdit" v-if="showEditPostDoc" @close="showEditPostDoc = false" @recarga="recargarTabla('General')"></modaleditar>
            <modalcrear v-if="showNewPostDoc" @close="showNewPostDoc = false" @recarga="recargarTabla('General')"></modalcrear>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import modalver from '../../snippets/sistema/postDoc/detailsPostDocModal.vue'
import modalcrear from '../../snippets/sistema/postDoc/createPostDocModal.vue'
import modaleditar from '../../snippets/sistema/postDoc/editPostDocModal.vue'
import modalconfirmacion from '../../snippets/sistema/alerts/confirmationModal.vue'
import modalalerta from '../../snippets/sistema/alerts/alertModal.vue'
import {mixin} from '../../../mixins.js'

export default {
    components: { modalver ,modalcrear, modaleditar, modalconfirmacion, modalalerta },
    mixins: [mixin],
    data(){
        return{

            filteredPostdoc: [], // Publications filtered by progressReport
            uniqueProgressReports: [], // Unique progressReport values for the selector
            selectedProgressReport: '', // Selected progressReport value

            postDocs: null,
            postDoc: null,
            showDetailsPostDoc: false,
            showNewPostDoc: false,
            showEditPostDoc: false,
            postDocEdit: null,
            mostrarTabla: false,
            mostrarCarga: true,
            table: null,
        }
    },
    created(){
        this.getPostDoc(this.userID);
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
            let module = 'postdoc'; // Cambia este valor dinámicamente según el módulo
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
        verPostDoc(postDoc){
            this.postDoc = postDoc;
            this.showDetailsPostDoc = true;
        },
        filterByProgressReport() {
            this.mostrarCarga = true;
            if (this.selectedProgressReport === '') {
                this.filteredPostdoc = [...this.postDocs];
            } else {
                this.filteredPostdoc = this.postDocs.filter(
                    pub => pub.progressReport === this.selectedProgressReport
                );
            }

            if (this.table != null) {
                this.table.destroy();
            }

            setTimeout(() => {
                this.crearTabla('#myTablePostDoc');
                this.mostrarCarga = false;
            }, 500);
        },        
        async getPostDoc(id){
            try {
                const response = await axios.get(`api/postDoc/${id}`);
                // Asegurar que siempre sea un arreglo, incluso si llega como un objeto
                this.postDocs = Array.isArray(response.data)
                    ? response.data
                    : Object.values(response.data || {}); // Maneja el caso de null o undefined

                // Ahora es seguro usar el spread operator
                this.filteredPostdoc = [...this.postDocs];

                // Verificar si hay registros antes de intentar extraer valores únicos
                if (this.postDocs.length > 0) {
                    this.uniqueProgressReports = [
                        ...new Set(this.postDocs.map(pub => pub.progressReport).filter(Boolean)),
                    ].sort((a, b) => b - a);
                } else {
                    this.uniqueProgressReports = [];
                }

                if (this.table != null) {
                    this.table.destroy();
                }
                this.crearTabla('#myTablePostDoc');
            } catch (error) {
                console.error('Error fetching postdoc:', error);
            }
        },
        recargarTabla($tipoRecarga){
            this.mostrarCarga = true;
            if($tipoRecarga == 'General'){
                this.postDocs = null;
                this.getPostDoc(this.userID);
            }
            else{
                this.crearTabla("#myTablePostDoc");
            }
        },
        editPostDoc(postDoc){
            this.postDocEdit = postDoc;
            this.showEditPostDoc= true;
        },
        async deletePostDoc(id) {
            const ok = await this.$refs.confirmation.show({
                title: 'Delete Postdoctoral fellow',
                message: `¿Are you sure you want to delete this Postdoctoral fellow?.`,
                okButton: 'Delete',
                cancelButton: 'Return'
            })
            if (ok) {
                axios.delete(`api/postDoc/${id}`).then( response =>{
                    this.toast.success("Postdoctoral fellow successfully removed!", {
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