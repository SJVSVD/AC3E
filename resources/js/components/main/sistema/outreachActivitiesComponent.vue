<template>
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="row pb-0 p-4">
                    <div class="col-lg-10 col-md-12">
                        <div class="info-box">
                            Includes all non-scientific activities that promote and disseminate the work of AC3E and its members, executed during the current baseline year (Sep-present). Examples of outreach activities include: school talks, press notes, appearances in mass media, guided tours of facilities, publication of educational materials, etc.
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-12 d-flex justify-content-lg-end justify-content-center align-items-center">
                        <div class="d-flex">
                            <a class="btn btn-spacing btn-continue" id="show-modal1" @click="showNewOutreach = true">New Entry</a>
                            &nbsp;
                            <a class="btn btn-spacing btn-search-blue" @click="recargarTabla('General')"><i class="fa-solid fa-rotate"></i></a>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2" style="min-height: 400px">
                    <div class="container">
                        <div class="table-responsive p-0">
                            <div v-show="mostrarCarga" class="loader-sm"></div>
                            <table v-show="mostrarTabla" class="table align-items-center mb-0" id="myTableOutreach">
                                <thead>
                                    <tr style="color: black">
                                        <th style="min-width: 16px;"></th>
                                        <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Actions</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Status</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">User</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Activity Type</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Activity Name</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Date</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Researchers Involved</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="outreachActivity in outreachActivities" :key="outreachActivity.id">
                                        <td></td>
                                        <td>
                                            <p class="text-sm font-weight-bolder mb-0" style="color:black">{{ outreachActivity.id }}</p>
                                        </td>                                          
                                        <td class="align-middle text-end">
                                            <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                <a class="btn btn-alert btn-xs" title="Edit" @click="editActivity(outreachActivity)"><i class="fa fa-fw fa-edit"></i></a>
                                                &nbsp;
                                                <a class="btn btn-success btn-xs" title="Details" @click="verActivity(outreachActivity)"><i class="fa-regular fa-eye"></i></a>
                                                &nbsp;
                                                <a class="btn btn-closed btn-xs" title="Delete" @click="deleteActivity(outreachActivity.id)"><i class="fa fa-fw fa-trash"></i></a>
                                            </div>
                                        </td>
                                        <td>
                                            <p v-if="outreachActivity.status == 'Draft'" class="text-sm font-weight-bolder mb-0" style="color:#878686">{{ outreachActivity.status }}</p>
                                            <p v-if="outreachActivity.status == 'Finished'" class="text-sm font-weight-bolder mb-0" style="color:#28A745">Registered</p>
                                        </td>                                          
                                        <td>
                                            <p class="text-sm mb-0">{{ outreachActivity.usuario.name }}</p>
                                        </td>
                                        <td>
                                            <p v-if="outreachActivity.activityType == null || outreachActivity.activityType == '' " class="text-sm mb-0">---</p>
                                            <p v-else class="text-sm mb-0">{{ outreachActivity.activityType }}</p>
                                        </td>
                                        <td>
                                            <p v-if="outreachActivity.activityName == null" class="text-sm mb-0">---</p>
                                            <p v-else class="text-sm mb-0 truncate-text">{{ outreachActivity.activityName }}</p>
                                        </td>
                                        <td>
                                            <p v-if="outreachActivity.date == null" class="text-sm mb-0">---</p>
                                            <p v-else class="text-sm mb-0">{{ outreachActivity.date }}</p>
                                        </td>
                                        <td>
                                            <p v-if="outreachActivity.researcherInvolved == null" class="text-sm mb-0">---</p>
                                            <p v-else class="text-sm mb-0">{{ outreachActivity.researcherInvolved }}</p>
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
        verActivity(outreachActivity){
            this.outreachActivity = outreachActivity;
            this.showDetailsActivity = true;
        },
        getOutreachActivities(id){
            axios.get(`api/outreachActivities/${id}`).then( response =>{
                this.outreachActivities = response.data;
                if (this.table != null){
                    this.table.clear();
                    this.table.destroy();
                }
                this.crearTabla('#myTableOutreach');
            }).catch(e=> console.log(e))
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
                message: `¿Are you sure you want to delete this Outreach Activity?.`,
                okButton: 'Delete',
                cancelButton: 'Return'
            })
            if (ok) {
                axios.delete(`api/outreachActivities/${id}`).then( response =>{
                    this.toast.success("Outreach Activity successfully removed!", {
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