<template>
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="row pb-0 p-4">
                    <div class="col-12">
                        <div class="d-flex justify-content-end">
                            <a class="btn btn-spacing btn-continue" id="show-modal1" @click="showNewOutreach = true">New Activity</a>
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
                                        <th class="text-uppercase text-xs font-weight-bolder">Status</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">User</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="outreachActivity in outreachActivities" :key="outreachActivity.id">
                                        <td></td>
                                        <td>
                                            <p class="text-sm font-weight-bolder mb-0" style="color:black">{{ outreachActivity.id }}</p>
                                        </td>                                          
                                        <td>
                                            <p v-if="outreachActivity.status == 'Draft'" class="text-sm font-weight-bolder mb-0" style="color:#878686">{{ outreachActivity.status }}</p>
                                            <p v-if="outreachActivity.status == 'Finished'" class="text-sm font-weight-bolder mb-0" style="color:#28A745">{{ outreachActivity.status }}</p>
                                        </td>                                          
                                        <td>
                                            <p class="text-sm mb-0">{{ outreachActivity.usuario.name }}</p>
                                        </td>
                                        <td class="align-middle text-end">
                                            <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                <a class="btn btn-alert btn-xs" title="Edit" @click="editActivity(outreachActivity)"><i class="fa fa-fw fa-edit"></i></a>
                                                &nbsp;
                                                <a class="btn btn-closed btn-xs" title="Delete" @click="deleteActivity(outreachActivity.id)"><i class="fa fa-fw fa-trash"></i></a>
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
            <modaleditar v-bind:activity1="activityEdit" v-if="showEditOutreach" @close="showEditOutreach = false" @recarga="recargarTabla('General')"></modaleditar>
            <modalcrear v-if="showNewOutreach" @close="showNewOutreach = false" @recarga="recargarTabla('General')"></modalcrear>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import modalcrear from '../../snippets/sistema/outreachActivities/createOutreachActivitiesModal.vue'
import modaleditar from '../../snippets/sistema/outreachActivities/editOutreachActivitiesModal.vue'
import modalconfirmacion from '../../snippets/sistema/alerts/confirmationModal.vue'
import modalalerta from '../../snippets/sistema/alerts/alertModal.vue'
import {mixin} from '../../../mixins.js'

export default {
    components: { modalcrear, modaleditar, modalconfirmacion, modalalerta },
    mixins: [mixin],
    data(){
        return{
            outreachActivities: null,
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
                message: `¿Are you sure you want to delete this Outreach Activity? This action cannot be undone.`,
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