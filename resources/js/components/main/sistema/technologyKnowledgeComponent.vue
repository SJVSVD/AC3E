<template>
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="row pb-0 p-4">
                    <div class="col-lg-10 col-md-12">
                        <div class="info-box">
                            Includes all technology and knowledge transfer projects with the industry, through technology licensing, contract research with companies, and/or technology transfer agreements (contracts or purchase orders), awarded during the current baseline year (Sep-present).
                        </div>
                    </div>
                    <div class="col-lg-2 col-md-12 d-flex justify-content-lg-end justify-content-center align-items-center">
                        <div class="d-flex">
                            <a class="btn btn-spacing btn-continue" id="show-modal1" @click="showNewTechnology = true">New Entry</a>
                            &nbsp;
                            <a class="btn btn-spacing btn-search-blue" @click="recargarTabla('General')"><i class="fa-solid fa-rotate"></i></a>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2" style="min-height: 400px">
                    <div class="container">
                        <div class="table-responsive p-0">
                            <div v-show="mostrarCarga" class="loader-sm"></div>
                            <table v-show="mostrarTabla" class="table align-items-center mb-0" id="myTableTechnology">
                                <thead>
                                    <tr style="color: black">
                                        <th style="min-width: 16px;"></th>
                                        <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Actions</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Status</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">User</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Type of Transfer</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Description</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Institution Involved</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Researchers Involved</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="technology in technologys" :key="technology.id">
                                        <td></td>
                                        <td>
                                            <p class="text-sm font-weight-bolder mb-0" style="color:black">{{ technology.id }}</p>
                                        </td>                                          
                                        <td class="align-middle text-end">
                                            <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                <a class="btn btn-alert btn-xs" title="Edit" @click="editTechnology(technology)"><i class="fa fa-fw fa-edit"></i></a>
                                                &nbsp;
                                                <a class="btn btn-success btn-xs" title="Details" @click="verTechnology(technology)"><i class="fa-regular fa-eye"></i></a>
                                                &nbsp;
                                                <a class="btn btn-closed btn-xs" title="Delete" @click="deleteTechnology(technology.id)"><i class="fa fa-fw fa-trash"></i></a>
                                            </div>
                                        </td>
                                        <td>
                                            <p v-if="technology.status == 'Draft'" class="text-sm font-weight-bolder mb-0" style="color:#878686">{{ technology.status }}</p>
                                            <p v-if="technology.status == 'Finished'" class="text-sm font-weight-bolder mb-0" style="color:#28A745">Registered</p>
                                        </td>                                          
                                        <td>
                                            <p class="text-sm mb-0">{{ technology.usuario.name }}</p>
                                        </td>
                                        <td>
                                            <p v-if="technology.typeOfTransfer == null" class="text-sm mb-0">---</p>
                                            <p v-else class="text-sm mb-0">{{ technology.typeOfTransfer }}</p>
                                        </td>
                                        <td>
                                            <p v-if="technology.description == null" class="text-sm mb-0">---</p>
                                            <p v-else class="text-sm mb-0 truncate-text">{{ technology.description }}</p>
                                        </td>
                                        <td>
                                            <p v-if="technology.nameOfInstitutionInvolved == null" class="text-sm mb-0">---</p>
                                            <p v-else class="text-sm mb-0">{{ technology.nameOfInstitutionInvolved }}</p>
                                        </td>
                                        <td>
                                            <p v-if="technology.researcherInvolved == null" class="text-sm mb-0">---</p>
                                            <p v-else class="text-sm mb-0">{{ technology.researcherInvolved }}</p>
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
            <modalver v-bind:technology1="technology" v-if="showDetailsTechnology" @close="showDetailsTechnology = false"></modalver>
            <modaleditar v-bind:technology1="technologyEdit" v-if="showEditTechnology" @close="showEditTechnology = false" @recarga="recargarTabla('General')"></modaleditar>
            <modalcrear v-if="showNewTechnology" @close="showNewTechnology = false" @recarga="recargarTabla('General')"></modalcrear>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import modalver from '../../snippets/sistema/technologyKnowledge/detailsTechnologyKnowledgeModal.vue'
import modalcrear from '../../snippets/sistema/technologyKnowledge/createTechnologyKnowledgeModal.vue'
import modaleditar from '../../snippets/sistema/technologyKnowledge/editTechnologyKnowledgeModal.vue'
import modalconfirmacion from '../../snippets/sistema/alerts/confirmationModal.vue'
import modalalerta from '../../snippets/sistema/alerts/alertModal.vue'
import {mixin} from '../../../mixins.js'

export default {
    components: { modalver ,modalcrear, modaleditar, modalconfirmacion, modalalerta },
    mixins: [mixin],
    data(){
        return{
            technologys: null,
            technology: null,
            showDetailsTechnology: false,
            showNewTechnology: false,
            showEditTechnology: false,
            technologyEdit: null,
            mostrarTabla: false,
            mostrarCarga: true,
            table: null,
        }
    },
    created(){
        this.getTechnologyKnowledge(this.userID);
    },
    methods: {
        verTechnology(technology){
            this.technology = technology;
            this.showDetailsTechnology = true;
        },
        getTechnologyKnowledge(id){
            axios.get(`api/technologyKnowledge/${id}`).then( response =>{
                this.technologys = response.data;
                if (this.table != null){
                    this.table.clear();
                    this.table.destroy();
                }
                this.crearTabla('#myTableTechnology');
            }).catch(e=> console.log(e))
        },
        recargarTabla($tipoRecarga){
            this.mostrarCarga = true;
            if($tipoRecarga == 'General'){
                this.technologys = null;
                this.getTechnologyKnowledge(this.userID);
            }
            else{
                this.crearTabla("#myTableTechnology");
            }
        },
        editTechnology(technology){
            this.technologyEdit = technology;
            this.showEditTechnology= true;
        },
        async deleteTechnology(id) {
            const ok = await this.$refs.confirmation.show({
                title: 'Delete Technology and knowledge transfer',
                message: `¿Are you sure you want to delete this Technology and knowledge transfer?.`,
                okButton: 'Delete',
                cancelButton: 'Return'
            })
            if (ok) {
                axios.delete(`api/technologyKnowledge/${id}`).then( response =>{
                    this.toast.success("Technology and knowledge transfer successfully removed!", {
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