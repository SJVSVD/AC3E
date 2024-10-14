<template>
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="row pb-0 p-4">
                    <div class="col-lg-10 col-md-12">
                        <div class="info-box">
                            Includes all articles presented/developed for conferences. Additionally, you can include publications indexed in Scopus, SciELO, Latindex, or any other type of indexation different from WoS. It is necessary to import the publication in PDF format, with a maximum of 20 MB.
If you have presented the publication at a conference, please ensure it is also reported in the “Participation in Sc events” module.

                        </div>
                    </div>
                    <div class="col-lg-2 col-md-12 d-flex justify-content-lg-end justify-content-center align-items-center">
                        <div class="d-flex">
                            <button @click="deleteSelected" class="btn btn-spacing btn-closed"><i class="fa fa-fw fa-trash"></i> Delete Selected</button>
                            <a class="btn btn-spacing btn-continue" id="show-modal1" @click="showNewNonIsiPublication = true">New Entry</a>
                            <a class="btn btn-spacing btn-search-blue" @click="recargarTabla('General')"><i class="fa-solid fa-rotate"></i></a>
                        </div>
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
                                    <tr v-for="nonIsiPublication in nonIsiPublications" :key="nonIsiPublication.id">
                                        <td></td>
                                        <td>
                                            <p class="text-sm font-weight-bolder mb-0" style="color:black">{{ nonIsiPublication.id }}</p>
                                        </td>       
                                        <td class="align-middle text-end">
                                            <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                <a v-if="nonIsiPublication.file != null" class="btn btn-search-blue btn-xs" title="Download File" @click="descargarExtracto(nonIsiPublication.id, nonIsiPublication.usuario.name)"><i class="fa-solid fa-download"></i></a>
                                                <a v-else class="btn btn-grey btn-xs"><i class="fa-solid fa-download"></i></a>
                                                &nbsp;
                                                <a class="btn btn-alert btn-xs" title="Edit" @click="editIsiPublication(nonIsiPublication)"><i class="fa fa-fw fa-edit"></i></a>
                                                &nbsp;
                                                <a class="btn btn-success btn-xs" title="Details" @click="verIsiPublication(nonIsiPublication)"><i class="fa-regular fa-eye"></i></a>
                                                &nbsp;
                                                <a class="btn btn-closed btn-xs" title="Delete" @click="deletePublication(nonIsiPublication.id,)"><i class="fa fa-fw fa-trash"></i></a>
                                            </div>
                                        </td>
                                        <td>
                                            <p v-if="nonIsiPublication.status == 'Draft'" class="text-sm font-weight-bolder mb-0" style="color:#878686">{{ nonIsiPublication.status }}</p>
                                            <p v-if="nonIsiPublication.status == 'Finished'" class="text-sm font-weight-bolder mb-0" style="color:#28A745">Registered</p>
                                        </td>                                          
                                        <td>
                                            <p class="text-sm mb-0">{{ nonIsiPublication.usuario.name }}</p>
                                        </td>                                   
                                        <td>
                                            <p v-if="nonIsiPublication.authors == null" class="text-sm mb-0">---</p>
                                            <p v-else class="text-sm mb-0 truncate-text">{{ nonIsiPublication.authors }}</p>
                                        </td>
                                        <td>
                                            <p v-if="nonIsiPublication.articleTitle == null" class="text-sm mb-0">---</p>
                                            <p v-else class="text-sm mb-0 truncate-text">{{ nonIsiPublication.articleTitle }}</p>
                                        </td>
                                        <td>
                                            <p v-if="nonIsiPublication.journalName == null" class="text-sm mb-0">---</p>
                                            <p v-else class="text-sm mb-0 truncate-text">{{ nonIsiPublication.journalName }}</p>
                                        </td>
                                        <td>
                                            <p v-if="nonIsiPublication.yearPublished == null" class="text-sm mb-0">---</p>
                                            <p v-else class="text-sm mb-0">{{ nonIsiPublication.yearPublished }}</p>
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
            <modalver v-bind:nonIsiPublication1="nonIsiPublication" v-if="showDetailsIsi" @close="showDetailsIsi = false"></modalver>
            <modaleditar v-bind:nonIsiPublication1="nonIsiPublicationEdit" v-if="showEditNonIsiPublication" @close="showEditNonIsiPublication = false" @recarga="recargarTabla('General')"></modaleditar>
            <modalcrear v-if="showNewNonIsiPublication" @close="showNewNonIsiPublication = false" @recarga="recargarTabla('General')"></modalcrear>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import modalver from '../../snippets/sistema/nonIsiPublications/detailsNonIsiPublicationModal.vue'
import modalcrear from '../../snippets/sistema/nonIsiPublications/createNonIsiPublicationModal.vue'
import modaleditar from '../../snippets/sistema/nonIsiPublications/editNonIsiPublicationModal.vue'
import modalconfirmacion from '../../snippets/sistema/alerts/confirmationModal.vue'
import modalalerta from '../../snippets/sistema/alerts/alertModal.vue'
import {mixin} from '../../../mixins.js'

export default {
    components: { modalver,modalcrear, modaleditar, modalconfirmacion, modalalerta },
    mixins: [mixin],
    data(){
        return{
            nonIsiPublications: null,
            showNewNonIsiPublication: false,
            showEditNonIsiPublication: false,
            showDetailsIsi: false,
            nonIsiPublication: null,
            nonIsiPublicationEdit: null,
            mostrarTabla: false,
            mostrarCarga: true,
            table: null,
        }
    },
    created(){
        this.getNonIsiPublications(this.userID);
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
        descargarExtracto(id, nombre) {
            axios({
                url: `api/nonIsiDownload/${id}`,
                method: 'GET',
                responseType: 'arraybuffer',
            }).then((response) => {
                let blob = new Blob([response.data], {
                    type: response.headers['content-type']
                });
                let link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                if (blob.type.includes('pdf')) {
                    link.download = `NonISI-${nombre}.pdf`;
                } else if (blob.type.includes('image')) {
                    link.download = `NonISI-${nombre}.png`; // Cambia la extensión según el tipo de imagen
                } else {
                    // Si el tipo de archivo no es ni PDF ni imagen, puedes manejarlo de acuerdo a tus requerimientos
                    console.error('Tipo de archivo no compatible');
                    return;
                }
                link.click();
            });
        },
        verIsiPublication(nonIsiPublication){
            this.nonIsiPublication = nonIsiPublication;
            this.showDetailsIsi = true;
        },
        getNonIsiPublications(id){
            axios.get(`api/nonIsiPublications/${id}`).then( response =>{
                this.nonIsiPublications = response.data;
                this.isiPublicationsAux = response.data;
                if (this.table != null){
                    this.table.clear();
                    this.table.destroy();
                }
                this.crearTabla('#MyTableNonIsiPublications');
            }).catch(e=> console.log(e))
        },
        recargarTabla($tipoRecarga){
            this.mostrarCarga = true;
            if($tipoRecarga == 'General'){
                this.nonIsiPublications = null;
                this.getNonIsiPublications(this.userID);
            }
            else{
                this.crearTabla("#MyTableNonIsiPublications");
            }
        },
        editIsiPublication(isiPublication){
            this.nonIsiPublicationEdit = isiPublication;
            this.showEditNonIsiPublication = true;
        },
        async deletePublication(id) {
            const ok = await this.$refs.confirmation.show({
                title: 'Delete Publication',
                message: `¿Are you sure you want to delete this Publication?.`,
                okButton: 'Delete',
                cancelButton: 'Return'
            })
            if (ok) {
                axios.delete(`api/nonIsiPublications/${id}`).then( response =>{
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