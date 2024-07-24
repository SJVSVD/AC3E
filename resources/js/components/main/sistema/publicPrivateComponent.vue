<template>
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="row pb-0 p-4">
                    <div class="col-12">
                        <div class="d-flex justify-content-end">
                            <a class="btn btn-spacing btn-continue" id="show-modal1" @click="showNewPublicPrivate = true">New Entry</a>
                            &nbsp;
                            <a class="btn btn-spacing btn-search-blue" @click="recargarTabla('General')"><i class="fa-solid fa-rotate"></i></a>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2" style="min-height: 400px">
                    <div class="container">
                        <div class="table-responsive p-0">
                            <div v-show="mostrarCarga" class="loader-sm"></div>
                            <table v-show="mostrarTabla" class="table align-items-center mb-0" id="myTablePublicPrivate">
                                <thead>
                                    <tr style="color: black">
                                        <th style="min-width: 16px;"></th>
                                        <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Actions</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Status</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">User</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Name of Activity</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Name of Organization</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Type of Connection</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Researchers Involved</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="publicPrivate in publicPrivates" :key="publicPrivate.id">
                                        <td></td>
                                        <td>
                                            <p class="text-sm font-weight-bolder mb-0" style="color:black">{{ publicPrivate.id }}</p>
                                        </td>                                          
                                        <td class="align-middle text-end">
                                            <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                <a class="btn btn-alert btn-xs" title="Edit" @click="editPublicPrivate(publicPrivate)"><i class="fa fa-fw fa-edit"></i></a>
                                                &nbsp;
                                                <a class="btn btn-success btn-xs" title="Details" @click="verPublicPrivate(publicPrivate)"><i class="fa-regular fa-eye"></i></a>
                                                &nbsp;
                                                <a class="btn btn-closed btn-xs" title="Delete" @click="deletePublicPrivate(publicPrivate.id)"><i class="fa fa-fw fa-trash"></i></a>
                                            </div>
                                        </td>
                                        <td>
                                            <p v-if="publicPrivate.status == 'Draft'" class="text-sm font-weight-bolder mb-0" style="color:#878686">{{ publicPrivate.status }}</p>
                                            <p v-if="publicPrivate.status == 'Finished'" class="text-sm font-weight-bolder mb-0" style="color:#28A745">Registered</p>
                                        </td>                                          
                                        <td>
                                            <p class="text-sm mb-0">{{ publicPrivate.usuario.name }}</p>
                                        </td>
                                        <td>
                                            <p v-if="publicPrivate.nameOfActivity == null" class="text-sm mb-0">---</p>
                                            <p v-else class="text-sm mb-0 truncate-text">{{ publicPrivate.nameOfActivity }}</p>
                                        </td>
                                        <td>
                                            <p v-if="publicPrivate.nameOfOrganization == null" class="text-sm mb-0">---</p>
                                            <p v-else class="text-sm mb-0">{{ publicPrivate.nameOfOrganization }}</p>
                                        </td>
                                        <td>
                                            <p v-if="publicPrivate.typeOfConnection == null || publicPrivate.typeOfConnection == ''" class="text-sm mb-0">---</p>
                                            <p v-else class="text-sm mb-0">{{ publicPrivate.typeOfConnection }}</p>
                                        </td>
                                        <td>
                                            <p v-if="publicPrivate.researcherInvolved == null" class="text-sm mb-0">---</p>
                                            <p v-else class="text-sm mb-0">{{ publicPrivate.researcherInvolved }}</p>
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
        verPublicPrivate(publicPrivate){
            this.publicPrivate = publicPrivate;
            this.showDetailsPublicPrivate = true;
        },
        getPublicPrivate(id){
            axios.get(`api/publicPrivate/${id}`).then( response =>{
                this.publicPrivates = response.data;
                if (this.table != null){
                    this.table.clear();
                    this.table.destroy();
                }
                this.crearTabla('#myTablePublicPrivate');
            }).catch(e=> console.log(e))
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
                message: `¿Are you sure you want to delete this Public private connection?.`,
                okButton: 'Delete',
                cancelButton: 'Return'
            })
            if (ok) {
                axios.delete(`api/publicPrivate/${id}`).then( response =>{
                    this.toast.success("Public private connection successfully removed!", {
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