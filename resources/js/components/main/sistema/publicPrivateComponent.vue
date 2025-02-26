<template>
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="row pb-0 p-4">
                    <div class="col-lg-10 col-md-12">
                        <div class="info-box">
                            Includes all activities that link AC3E members with external organizations or institutions, both public and private, executed during the current baseline year (Sep-present). R&D service contracts are NOT included; these should be included in the Technology and Knowledge Transfer module.
Includes participation in public policy design, formalization of MOUs/NDAs, participation in project prospecting activities with the Technology Transfer unit, and participation as editors in scientific journals.

                        </div>
                    </div>
                    <div class="col-lg-2 col-md-12 d-flex justify-content-lg-end justify-content-center align-items-center">
                        <div class="d-flex">
                            <button v-if="!is('Staff') && !is('Anid') && !is('Titular Researcher')" @click="deleteSelected" class="btn btn-spacing btn-closed"><i class="fa fa-fw fa-trash"></i>  Selected Records</button>
                            <a v-if="!is('Staff') && !is('Anid')" class="btn btn-spacing btn-continue" id="show-modal1" @click="showNewPublicPrivate = true"><i class="fa-solid fa-add"></i></a>
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
                            <table v-show="mostrarTabla" class="table table-striped align-items-center mb-0" id="myTablePublicPrivate">
                                <thead class="thead-light">
                                    <tr>
                                        <th style="width: 16px;"></th>
                                        <th data-orderable="true" class="text-xs font-weight-bold text-left">ID</th>
                                        <th data-orderable="false" class="text-xs font-weight-bold text-left">Actions</th>
                                        <th data-orderable="false" class="text-xs font-weight-bold text-left">Status</th>
                                        <th data-orderable="true" class="text-xs font-weight-bold text-left">User</th>
                                        <th data-orderable="true" class="text-xs font-weight-bold text-left">Progress Report Year</th>
                                        <th data-orderable="true" class="text-xs font-weight-bold text-left">Name of Activity</th>
                                        <th data-orderable="true" class="text-xs font-weight-bold text-left">Name of Organization</th>
                                        <th data-orderable="true" class="text-xs font-weight-bold text-left">Agent Type</th>
                                        <th data-orderable="true" class="text-xs font-weight-bold text-left">Type of Connection</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="publicPrivate in filteredPublicprivate" :key="publicPrivate.id">
                                        <td></td>
                                        <td>
                                            <p class="text-sm font-weight-bold">{{ publicPrivate.id }}</p>
                                        </td>                                          
                                        <td class="text-left">
                                            <div class="d-flex px-1 py-1 justify-content-start align-items-center">
                                                <a
                                                    v-if="publicPrivate.file != null && publicPrivate.is_link == 0"
                                                    class="btn btn-search-blue btn-xs"
                                                    title="Download File"
                                                    @click="descargarExtracto(publicPrivate.id, publicPrivate.usuario.name)"
                                                >
                                                    <i class="fa-solid fa-download"></i>
                                                </a>
                                                &nbsp;
                                                <a v-if="!is('Staff') && !is('Anid') && (!is('Titular Researcher') || publicPrivate.idUsuario == userID)" class="btn btn-alert btn-xs" title="Edit" @click="editPublicPrivate(publicPrivate)"><i class="fa fa-fw fa-edit"></i></a>
                                                &nbsp;
                                                <a class="btn btn-success btn-xs" title="Details" @click="verPublicPrivate(publicPrivate)"><i class="fa-regular fa-eye"></i></a>
                                                &nbsp;
                                                <a v-if="!is('Staff') && !is('Anid') && !is('Titular Researcher')|| publicPrivate.idUsuario == userID"  
                            class="btn btn-closed btn-xs" title="Delete" @click="deletePublicPrivate(publicPrivate.id)"><i class="fa fa-fw fa-trash"></i></a>
                                            </div>
                                        </td>
                                        <td class="text-start">
                                            <span v-if="publicPrivate.status == 'Draft'" class="badge bg-alert">Draft</span>
                                            <span v-else-if="publicPrivate.status == 'Finished'" class="badge bg-success">Registered</span>
                                            <span v-else class="badge bg-secondary">No information</span>
                                        </td>                                           

                                        <td class="text-sm text-nowrap" :title="publicPrivate.usuario.name || '---'">
                                            {{ truncateText(publicPrivate.usuario.name || '---', 60) }}
                                        </td>
                                        <td class="text-sm text-nowrap" :title="publicPrivate.progressReport || '---'">
                                            {{ truncateText(publicPrivate.progressReport || '---', 60) }}
                                        </td>
                                        <td class="text-sm text-start text-nowrap" :title="publicPrivate.nameOfActivity || '---'">
                                            {{ truncateText(publicPrivate.nameOfActivity || '---', 40) }}
                                        </td>
                                        <td class="text-sm text-start text-nowrap" :title="publicPrivate.nameOfOrganization || '---'">
                                            {{ truncateText(publicPrivate.nameOfOrganization || '---', 40) }}
                                        </td>
                                        <td class="text-sm text-start text-nowrap" :title="publicPrivate.agentType || '---'">
                                            {{ truncateText(publicPrivate.agentType || '---', 40) }}
                                        </td>
                                        <td class="text-sm text-start text-nowrap" :title="publicPrivate.typeOfConnection || '---'">
                                            {{ truncateText(publicPrivate.typeOfConnection || '---', 60) }}
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
            <modalver v-bind:publicPrivate1="publicPrivate" v-if="showDetailsPublicPrivate" @close="showDetailsPublicPrivate = false"></modalver>
            <modaleditar v-bind:publicPrivate1="publicPrivateEdit" v-if="showPublicPrivateEdit" @close="showPublicPrivateEdit = false" @recarga="recargarTabla('General')"></modaleditar>
            <modalcrear v-if="showNewPublicPrivate" @close="showNewPublicPrivate = false" @recarga="recargarTabla('General')"></modalcrear>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import modalver from '../../snippets/sistema/publicPrivate/detailsPublicPrivateModal.vue'
import modalcrear from '../../snippets/sistema/publicPrivate/createPublicPrivateModal.vue'
import modaleditar from '../../snippets/sistema/publicPrivate/editPublicPrivateModal.vue'
import modalconfirmacion from '../../snippets/sistema/alerts/confirmationModal.vue'
import modalalerta from '../../snippets/sistema/alerts/alertModal.vue'
import {mixin} from '../../../mixins.js'

export default {
    components: { modalver ,modalcrear, modaleditar, modalconfirmacion, modalalerta },
    mixins: [mixin],
    data(){
        return{

            filteredPublicprivate: [], // Publications filtered by progressReport
            uniqueProgressReports: [], // Unique progressReport values for the selector
            selectedProgressReport: '', // Selected progressReport value
            publicPrivates: null,
            publicPrivate: null,
            showDetailsPublicPrivate: false,
            showNewPublicPrivate: false,
            showPublicPrivateEdit: false,
            publicPrivateEdit: null,
            mostrarTabla: false,
            mostrarCarga: true,
            table: null,
        }
    },
    created(){
        this.getPublicPrivate(this.userID);
    },
    methods: {
        descargarExtracto(id,nombre){
            axios({
                url: `api/publicPrivateDownload/${id}`,
                method: 'GET',
                responseType: 'arraybuffer',
            }).then((response) => {
                let blob = new Blob([response.data], {
                    type: response.headers['content-type']
                });
                let link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                if (blob.type.includes('pdf')) {
                    link.download = `publicPrivate-${nombre}.pdf`;
                } else if (blob.type.includes('image')) {
                    link.download = `publicPrivate-${nombre}.png`; // Cambia la extensión según el tipo de imagen
                } else {
                    // Si el tipo de archivo no es ni PDF ni imagen, puedes manejarlo de acuerdo a tus requerimientos
                    console.error('Tipo de archivo no compatible');
                    return;
                }
                link.click();
            });
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
            let module = 'public-private'; // Cambia este valor dinámicamente según el módulo
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
        verPublicPrivate(publicPrivate){
            this.publicPrivate = publicPrivate;
            this.showDetailsPublicPrivate = true;
        },
        filterByProgressReport() {
            this.mostrarCarga = true;
            if (this.selectedProgressReport === '') {
                this.filteredPublicprivate = [...this.publicPrivates];
            } else {
                this.filteredPublicprivate = this.publicPrivates.filter(
                    pub => pub.progressReport === this.selectedProgressReport
                );
            }

            if (this.table != null) {
                this.table.destroy();
            }

            setTimeout(() => {
                this.crearTabla('#myTablePublicPrivate');
                this.mostrarCarga = false;
            }, 500);
        },        
        async getPublicPrivate(id){
            try {
                const response = await axios.get(`api/publicPrivate/${id}`);
                // Asegurar que siempre sea un arreglo, incluso si llega como un objeto
                this.publicPrivates = Array.isArray(response.data)
                    ? response.data
                    : Object.values(response.data || {}); // Maneja el caso de null o undefined

                // Ahora es seguro usar el spread operator
                this.filteredPublicprivate = [...this.publicPrivates];

                // Verificar si hay registros antes de intentar extraer valores únicos
                if (this.publicPrivates.length > 0) {
                    this.uniqueProgressReports = [
                        ...new Set(this.publicPrivates.map(pub => pub.progressReport).filter(Boolean)),
                    ].sort((a, b) => b - a);
                } else {
                    this.uniqueProgressReports = [];
                }

                if (this.table != null) {
                    this.table.destroy();
                }
                this.crearTabla('#myTablePublicPrivate');
            } catch (error) {
                console.error('Error fetching postdoc:', error);
            }
        },
        recargarTabla($tipoRecarga){
            this.mostrarCarga = true;
            if($tipoRecarga == 'General'){
                this.publicPrivates = null;
                this.getPublicPrivate(this.userID);
            }
            else{
                this.crearTabla("#myTablePublicPrivate");
            }
        },
        editPublicPrivate(publicPrivate){
            this.publicPrivateEdit = publicPrivate;
            this.showPublicPrivateEdit= true;
        },
        async deletePublicPrivate(id) {
            const ok = await this.$refs.confirmation.show({
                title: 'Delete Public private connection',
                message: `Are you sure you want to delete this Public private connection?`,
                okButton: 'Delete',
                cancelButton: 'Return',
            });
            if (ok) {
                try {
                    // Obtén el ID del usuario actual desde la sesión o contexto
                    const userId = this.userID; // Asegúrate de tener acceso a este dato en tu componente
                    
                    // Envía el ID del proyecto y el ID del usuario
                    await axios.delete(`api/publicPrivate/${id}`, {
                        data: { user_id: userId },
                    });

                    // Mostrar mensaje de éxito
                    this.toast.success('Public private connection successfully removed!', {
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
                    console.error('Error deleting Public privates:', error);
                }
            }
        },
    }
}
</script>