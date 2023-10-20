<template>
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="row pb-0 p-4">
                    <div class="col-12">
                        <div class="d-flex justify-content-end">
                            <a class="btn btn-spacing btn-continue" id="show-modal1" @click="showNewIsiPublication = true">New Publication</a>
                            &nbsp;
                            <a class="btn btn-spacing btn-search-blue" @click="recargarTabla('General')"><i class="fa-solid fa-rotate"></i></a>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2" style="min-height: 400px">
                    <div class="container">
                        <div class="table-responsive p-0">
                            <div v-show="mostrarCarga" class="loader-sm"></div>
                            <table v-show="mostrarTabla" class="table align-items-center mb-0" id="MyTableIsiPublications">
                                <thead>
                                    <tr style="color: black">
                                        <th style="min-width: 16px;"></th>
                                        <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Status</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">User</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Author(s)</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Coauthor(s) </th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Article Title</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Journal Name</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Doi</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Volume</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">First Page</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Last Page</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Year Published</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Actions</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="isiPublication in isiPublications" :key="isiPublication.id">
                                        <td></td>
                                        <td>
                                            <p class="text-sm font-weight-bolder mb-0" style="color:black">{{ isiPublication.id }}</p>
                                        </td>                                          
                                        <td>
                                            <p v-if="isiPublication.status == 'Draft'" class="text-sm font-weight-bolder mb-0" style="color:#878686">{{ isiPublication.status }}</p>
                                            <p v-if="isiPublication.status == 'Finished'" class="text-sm font-weight-bolder mb-0" style="color:#28A745">{{ isiPublication.status }}</p>
                                        </td>                                          
                                        <td>
                                            <p class="text-sm mb-0">{{ isiPublication.usuario.name }}</p>
                                        </td>
                                        <td>
                                            <p v-if="isiPublication.authors == null" class="text-sm mb-0">---</p>
                                            <p v-else class="text-sm mb-0">{{ isiPublication.authors }}</p>
                                        </td>
                                        <td>
                                            <p v-if="isiPublication.coauthor == null" class="text-sm mb-0">---</p>
                                            <p v-else class="text-sm mb-0">{{ isiPublication.coauthor }}</p>
                                        </td>
                                        <td>
                                            <p v-if="isiPublication.articleTitle == null" class="text-sm mb-0">---</p>
                                            <p v-else class="text-sm mb-0">{{ isiPublication.articleTitle }}</p>
                                        </td>
                                        <td>
                                            <p v-if="isiPublication.journalName == null" class="text-sm mb-0">---</p>
                                            <p v-else class="text-sm mb-0">{{ isiPublication.journalName }}</p>
                                        </td>
                                        <td>
                                            <p v-if="isiPublication.doi == null" class="text-sm mb-0">---</p>
                                            <p v-else class="text-sm mb-0">{{ isiPublication.doi }}</p>
                                        </td>
                                        <td>
                                            <p v-if="isiPublication.volume == null" class="text-sm mb-0">---</p>
                                            <p v-else class="text-sm mb-0">{{ isiPublication.volume }}</p>
                                        </td>
                                        <td>
                                            <p v-if="isiPublication.firstPage == null" class="text-sm mb-0">---</p>
                                            <p v-else class="text-sm mb-0">{{ isiPublication.firstPage }}</p>
                                        </td>
                                        <td>
                                            <p v-if="isiPublication.lastPage == null" class="text-sm mb-0">---</p>
                                            <p v-else class="text-sm mb-0">{{ isiPublication.lastPage }}</p>
                                        </td>
                                        <td>
                                            <p v-if="isiPublication.yearPublished == null" class="text-sm mb-0">---</p>
                                            <p v-else class="text-sm mb-0">{{ isiPublication.yearPublished }}</p>
                                        </td>
                                        <td class="align-middle text-end">
                                            <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                <a class="btn btn-alert btn-xs" title="Edit" @click="editIsiPublication(isiPublication)"><i class="fa fa-fw fa-edit"></i></a>
                                                &nbsp;
                                                <a class="btn btn-success btn-xs" title="Details" @click="verIsiPublication(isiPublication)"><i class="fa-regular fa-eye"></i></a>
                                                &nbsp;
                                                <a class="btn btn-closed btn-xs" title="Delete" @click="deletePublication(isiPublication.id,)"><i class="fa fa-fw fa-trash"></i></a>
                                            </div>
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
            <modalver v-bind:isiPublication1="isiPublication" v-if="showDetailsIsi" @close="showDetailsIsi = false"></modalver>
            <modaleditar v-bind:isiPublication1="isiPublicationEdit" v-if="showEditIsiPublication" @close="showEditIsiPublication = false" @recarga="recargarTabla('General')"></modaleditar>
            <modalcrear v-if="showNewIsiPublication" @close="showNewIsiPublication = false" @recarga="recargarTabla('General')"></modalcrear>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import modalver from '../../snippets/sistema/isiPublications/detailsIsiPublicationModal.vue'
import modalcrear from '../../snippets/sistema/isiPublications/createIsiPublicationModal.vue'
import modaleditar from '../../snippets/sistema/isiPublications/editIsiPublicationModal.vue'
import modalconfirmacion from '../../snippets/sistema/alerts/confirmationModal.vue'
import modalalerta from '../../snippets/sistema/alerts/alertModal.vue'
import {mixin} from '../../../mixins.js'

export default {
    components: { modalcrear, modaleditar,modalver, modalconfirmacion, modalalerta },
    mixins: [mixin],
    data(){
        return{
            isiPublications: null,
            isiPublication: null,
            showNewIsiPublication: false,
            showEditIsiPublication: false,
            showDetailsIsi: false,
            isiPublicationEdit: null,
            mostrarTabla: false,
            mostrarCarga: true,
            table: null,
        }
    },
    created(){
        this.getIsiPublications(this.userID);
    },
    methods: {
        verIsiPublication(isiPublication){
            this.isiPublication = isiPublication;
            this.showDetailsIsi = true;
        },
        getIsiPublications(id){
            axios.get(`api/isiPublications/${id}`).then( response =>{
                this.isiPublications = response.data;
                this.isiPublicationsAux = response.data;
                if (this.table != null){
                    this.table.clear();
                    this.table.destroy();
                }
                this.crearTabla('#MyTableIsiPublications');
            }).catch(e=> console.log(e))
        },
        recargarTabla($tipoRecarga){
            this.mostrarCarga = true;
            if($tipoRecarga == 'General'){
                this.isiPublications = null;
                this.getIsiPublications(this.userID);
            }
            else{
                this.crearTabla("#MyTableIsiPublications");
            }
        },
        editIsiPublication(isiPublication){
            this.isiPublicationEdit = isiPublication;
            this.showEditIsiPublication = true;
        },
        async deletePublication(id) {
            const ok = await this.$refs.confirmation.show({
                title: 'Delete Publication',
                message: `¿Are you sure you want to delete this Publication? This action cannot be undone.`,
                okButton: 'Delete',
                cancelButton: 'Return'
            })
            if (ok) {
                axios.delete(`api/isiPublications/${id}`).then( response =>{
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