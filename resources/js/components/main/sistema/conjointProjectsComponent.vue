<template>
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="row pb-0 p-4">
                    <div class="col-12">
                        <div class="d-flex justify-content-end">
                            <a class="btn btn-spacing btn-continue" id="show-modal1" @click="showNewProject = true">New Project</a>
                            &nbsp;
                            <a class="btn btn-spacing btn-search-blue" @click="recargarTabla('General')"><i class="fa-solid fa-rotate"></i></a>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2" style="min-height: 400px">
                    <div class="container">
                        <div class="table-responsive p-0">
                            <div v-show="mostrarCarga" class="loader-sm"></div>
                            <table v-show="mostrarTabla" class="table align-items-center mb-0" id="myTableProjects">
                                <thead>
                                    <tr style="color: black">
                                        <th style="min-width: 16px;"></th>
                                        <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Actions</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Status</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">User</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Activity Name</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Name of AC3E Member</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Name of External Person</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Beggining Date</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Ending Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="conjointProject in conjointProjects" :key="conjointProject.id">
                                        <td></td>
                                        <td>
                                            <p class="text-sm font-weight-bolder mb-0" style="color:black">{{ conjointProject.id }}</p>
                                        </td>                                          
                                        <td class="align-middle text-end">
                                            <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                <a class="btn btn-alert btn-xs" title="Edit" @click="editProject(conjointProject)"><i class="fa fa-fw fa-edit"></i></a>
                                                &nbsp;
                                                <a class="btn btn-success btn-xs" title="Details" @click="verProject(conjointProject)"><i class="fa-regular fa-eye"></i></a>
                                                &nbsp;
                                                <a class="btn btn-closed btn-xs" title="Delete" @click="deleteProject(conjointProject.id)"><i class="fa fa-fw fa-trash"></i></a>
                                            </div>
                                        </td>
                                        <td>
                                            <p v-if="conjointProject.status == 'Draft'" class="text-sm font-weight-bolder mb-0" style="color:#878686">{{ conjointProject.status }}</p>
                                            <p v-if="conjointProject.status == 'Finished'" class="text-sm font-weight-bolder mb-0" style="color:#28A745">{{ conjointProject.status }}</p>
                                        </td>                                          
                                        <td>
                                            <p class="text-sm mb-0">{{ conjointProject.usuario.name }}</p>
                                        </td>
                                        <td>
                                            <p v-if="conjointProject.activityName == null" class="text-sm mb-0">---</p>
                                            <p v-else class="text-sm mb-0">{{ conjointProject.activityName }}</p>
                                        </td>
                                        <td>
                                            <p v-if="conjointProject.nameOfAC3EMember == null" class="text-sm mb-0">---</p>
                                            <p v-else class="text-sm mb-0">{{ conjointProject.nameOfAC3EMember }}</p>
                                        </td>
                                        <td>
                                            <p v-if="conjointProject.nameOfExternalResearcher == null" class="text-sm mb-0">---</p>
                                            <p v-else class="text-sm mb-0">{{ conjointProject.nameOfExternalResearcher }}</p>
                                        </td>
                                        <td>
                                            <p v-if="conjointProject.begginingDate == null" class="text-sm mb-0">---</p>
                                            <p v-else class="text-sm mb-0">{{ conjointProject.begginingDate }}</p>
                                        </td>
                                        <td>
                                            <p v-if="conjointProject.endingDate == null" class="text-sm mb-0">---</p>
                                            <p v-else class="text-sm mb-0">{{ conjointProject.endingDate }}</p>
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
            <modalver v-bind:project1="conjointProject" v-if="showDetailsProject" @close="showDetailsProject = false"></modalver>
            <modaleditar v-bind:project1="projectEdit" v-if="showEditProject" @close="showEditProject = false" @recarga="recargarTabla('General')"></modaleditar>
            <modalcrear v-if="showNewProject" @close="showNewProject = false" @recarga="recargarTabla('General')"></modalcrear>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import modalver from '../../snippets/sistema/conjointProjects/detailsConjointProjectsModal.vue'
import modalcrear from '../../snippets/sistema/conjointProjects/createConjointProjectsModal.vue'
import modaleditar from '../../snippets/sistema/conjointProjects/editConjointProjectsModal.vue'
import modalconfirmacion from '../../snippets/sistema/alerts/confirmationModal.vue'
import modalalerta from '../../snippets/sistema/alerts/alertModal.vue'
import {mixin} from '../../../mixins.js'

export default {
    components: { modalver ,modalcrear, modaleditar, modalconfirmacion, modalalerta },
    mixins: [mixin],
    data(){
        return{
            conjointProjects: null,
            conjointProject: null,
            showNewProject: false,
            showDetailsProject: false,
            showEditProject: false,
            projectEdit: null,
            mostrarTabla: false,
            mostrarCarga: true,
            table: null,
        }
    },
    created(){
        this.getConjointProjects(this.userID);
    },
    methods: {
        verProject(conjointProject){
            this.conjointProject = conjointProject;
            this.showDetailsProject = true;
        },
        getConjointProjects(id){
            axios.get(`api/conjointProjects/${id}`).then( response =>{
                this.conjointProjects = response.data;
                if (this.table != null){
                    this.table.clear();
                    this.table.destroy();
                }
                this.crearTabla('#myTableProjects');
            }).catch(e=> console.log(e))
        },
        recargarTabla($tipoRecarga){
            this.mostrarCarga = true;
            if($tipoRecarga == 'General'){
                this.conjointProjects = null;
                this.getConjointProjects(this.userID);
            }
            else{
                this.crearTabla("#myTableProjects");
            }
        },
        editProject(collaboration){
            this.projectEdit = collaboration;
            this.showEditProject= true;
        },
        async deleteProject(id) {
            const ok = await this.$refs.confirmation.show({
                title: 'Delete Conjoint project',
                message: `¿Are you sure you want to delete this Conjoint project?.`,
                okButton: 'Delete',
                cancelButton: 'Return'
            })
            if (ok) {
                axios.delete(`api/conjointProjects/${id}`).then( response =>{
                    this.toast.success("Conjoint project successfully removed!", {
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