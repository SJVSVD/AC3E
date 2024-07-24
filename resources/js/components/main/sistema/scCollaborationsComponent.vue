<template>
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="row pb-0 p-4">
                    <div class="col-12">
                        <div class="d-flex justify-content-end">
                            <a class="btn btn-spacing btn-continue" id="show-modal1" @click="showNewCollaboration = true">New Entry</a>
                            &nbsp;
                            <a class="btn btn-spacing btn-search-blue" @click="recargarTabla('General')"><i class="fa-solid fa-rotate"></i></a>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2" style="min-height: 400px">
                    <div class="container">
                        <div class="table-responsive p-0">
                            <div v-show="mostrarCarga" class="loader-sm"></div>
                            <table v-show="mostrarTabla" class="table align-items-center mb-0" id="myTableCollaborations">
                                <thead>
                                    <tr style="color: black">
                                        <th style="min-width: 16px;"></th>
                                        <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Actions</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Status</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">User</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Activity Name</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Ac3e Researchers Involved</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Name of External Person</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Beginning Date</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Ending Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="scCollaboration in scCollaborations" :key="scCollaboration.id">
                                        <td></td>
                                        <td>
                                            <p class="text-sm font-weight-bolder mb-0" style="color:black">{{ scCollaboration.id }}</p>
                                        </td>                                          
                                        <td class="align-middle text-end">
                                            <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                <a class="btn btn-alert btn-xs" title="Edit" @click="editCollaboration(scCollaboration)"><i class="fa fa-fw fa-edit"></i></a>
                                                &nbsp;
                                                <a class="btn btn-success btn-xs" title="Details" @click="verCollaboration(scCollaboration)"><i class="fa-regular fa-eye"></i></a>
                                                &nbsp;
                                                <a class="btn btn-closed btn-xs" title="Delete" @click="deleteCollaboration(scCollaboration.id)"><i class="fa fa-fw fa-trash"></i></a>
                                            </div>
                                        </td>
                                        <td>
                                            <p v-if="scCollaboration.status == 'Draft'" class="text-sm font-weight-bolder mb-0" style="color:#878686">{{ scCollaboration.status }}</p>
                                            <p v-if="scCollaboration.status == 'Finished'" class="text-sm font-weight-bolder mb-0" style="color:#28A745">Registered</p>
                                        </td>                                          
                                        <td>
                                            <p class="text-sm mb-0">{{ scCollaboration.usuario.name }}</p>
                                        </td>
                                        <td>
                                            <p v-if="scCollaboration.activityName == null" class="text-sm mb-0">---</p>
                                            <p v-else class="text-sm mb-0">{{ scCollaboration.activityName }}</p>
                                        </td>
                                        <td>
                                            <p v-if="scCollaboration.researcherInvolved == null" class="text-sm mb-0">---</p>
                                            <p v-else class="text-sm mb-0">{{ scCollaboration.researcherInvolved }}</p>
                                        </td>
                                        <td>
                                            <p v-if="scCollaboration.nameOfExternalResearcher == null" class="text-sm mb-0">---</p>
                                            <p v-else class="text-sm mb-0">{{ scCollaboration.nameOfExternalResearcher }}</p>
                                        </td>
                                        <td>
                                            <p v-if="scCollaboration.beginningDate == null" class="text-sm mb-0">---</p>
                                            <p v-else class="text-sm mb-0">{{ scCollaboration.beginningDate }}</p>
                                        </td>
                                        <td>
                                            <p v-if="scCollaboration.endingDate == null" class="text-sm mb-0">---</p>
                                            <p v-else class="text-sm mb-0">{{ scCollaboration.endingDate }}</p>
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
            <modalver v-bind:collaboration1="scCollaboration" v-if="showDetailsScCollaboration" @close="showDetailsScCollaboration = false"></modalver>
            <modaleditar v-bind:collaboration1="collaborationEdit" v-if="showEditCollaboration" @close="showEditCollaboration = false" @recarga="recargarTabla('General')"></modaleditar>
            <modalcrear v-if="showNewCollaboration" @close="showNewCollaboration = false" @recarga="recargarTabla('General')"></modalcrear>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import modalver from '../../snippets/sistema/scCollaborations/detailsScCollaborationsModal.vue'
import modalcrear from '../../snippets/sistema/scCollaborations/createScCollaborationModal.vue'
import modaleditar from '../../snippets/sistema/scCollaborations/editScCollaborationModal.vue'
import modalconfirmacion from '../../snippets/sistema/alerts/confirmationModal.vue'
import modalalerta from '../../snippets/sistema/alerts/alertModal.vue'
import {mixin} from '../../../mixins.js'

export default {
    components: { modalver ,modalcrear, modaleditar, modalconfirmacion, modalalerta },
    mixins: [mixin],
    data(){
        return{
            scCollaborations: null,
            scCollaboration: null,
            showDetailsScCollaboration: false,
            showNewCollaboration: false,
            showEditCollaboration: false,
            collaborationEdit: null,
            mostrarTabla: false,
            mostrarCarga: true,
            table: null,
        }
    },
    created(){
        this.getCollaborations(this.userID);
    },
    methods: {
        verCollaboration(scCollaboration){
            this.scCollaboration = scCollaboration;
            this.showDetailsScCollaboration = true;
        },
        getCollaborations(id){
            axios.get(`api/scCollaborations/${id}`).then( response =>{
                this.scCollaborations = response.data;
                if (this.table != null){
                    this.table.clear();
                    this.table.destroy();
                }
                this.crearTabla('#myTableCollaborations');
            }).catch(e=> console.log(e))
        },
        recargarTabla($tipoRecarga){
            this.mostrarCarga = true;
            if($tipoRecarga == 'General'){
                this.scCollaborations = null;
                this.getCollaborations(this.userID);
            }
            else{
                this.crearTabla("#myTableCollaborations");
            }
        },
        editCollaboration(collaboration){
            this.collaborationEdit = collaboration;
            this.showEditCollaboration= true;
        },
        async deleteCollaboration(id) {
            const ok = await this.$refs.confirmation.show({
                title: 'Delete Visits And Stays',
                message: `¿Are you sure you want to delete this Publication?.`,
                okButton: 'Delete',
                cancelButton: 'Return'
            })
            if (ok) {
                axios.delete(`api/scCollaborations/${id}`).then( response =>{
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