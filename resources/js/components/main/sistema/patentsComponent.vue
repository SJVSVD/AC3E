<template>
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="row pb-0 p-4">
                    <div class="col-12">
                        <div class="d-flex justify-content-end">
                            <a class="btn btn-spacing btn-continue" id="show-modal1" @click="showNewPatent = true">New Patent</a>
                            &nbsp;
                            <a class="btn btn-spacing btn-search-blue" @click="recargarTabla('General')"><i class="fa-solid fa-rotate"></i></a>
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
                                        <th class="text-uppercase text-xs font-weight-bolder">Researcher Involved</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="patent in patents" :key="patent.id">
                                        <td></td>
                                        <td>
                                            <p class="text-sm font-weight-bolder mb-0" style="color:black">{{ patent.id }}</p>
                                        </td>                                          
                                        <td class="align-middle text-end">
                                            <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                <a class="btn btn-alert btn-xs" title="Edit" @click="editPatent(patent)"><i class="fa fa-fw fa-edit"></i></a>
                                                &nbsp;
                                                <a class="btn btn-success btn-xs" title="Details" @click="verPatent(patent)"><i class="fa-regular fa-eye"></i></a>
                                                &nbsp;
                                                <a class="btn btn-closed btn-xs" title="Delete" @click="deletePatent(patent.id)"><i class="fa fa-fw fa-trash"></i></a>
                                            </div>
                                        </td>
                                        <td>
                                            <p v-if="patent.status == 'Draft'" class="text-sm font-weight-bolder mb-0" style="color:#878686">{{ patent.status }}</p>
                                            <p v-if="patent.status == 'Finished'" class="text-sm font-weight-bolder mb-0" style="color:#28A745">{{ patent.status }}</p>
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
                                            <p v-if="patent.statusApplication == null" class="text-sm mb-0">---</p>
                                            <p v-else class="text-sm mb-0">{{ patent.statusApplication }}</p>
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
        verPatent(patent){
            this.patent = patent;
            this.showDetailsPatent = true;
        },
        getPatents(id){
            axios.get(`api/patents/${id}`).then( response =>{
                this.patents = response.data;
                if (this.table != null){
                    this.table.clear();
                    this.table.destroy();
                }
                this.crearTabla('#myTablePatents');
            }).catch(e=> console.log(e))
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