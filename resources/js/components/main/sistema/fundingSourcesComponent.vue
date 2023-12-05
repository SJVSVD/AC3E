<template>
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="row pb-0 p-4">
                    <div class="col-12">
                        <div class="d-flex justify-content-end">
                            <a class="btn btn-spacing btn-continue" id="show-modal1" @click="showNewFundingSource = true">New Source</a>
                            &nbsp;
                            <a class="btn btn-spacing btn-search-blue" @click="recargarTabla('General')"><i class="fa-solid fa-rotate"></i></a>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2" style="min-height: 400px">
                    <div class="container">
                        <div class="table-responsive p-0">
                            <div v-show="mostrarCarga" class="loader-sm"></div>
                            <table v-show="mostrarTabla" class="table align-items-center mb-0" id="myTableFunding">
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
                                    <tr v-for="fundingSource in fundingSources" :key="fundingSource.id">
                                        <td></td>
                                        <td>
                                            <p class="text-sm font-weight-bolder mb-0" style="color:black">{{ fundingSource.id }}</p>
                                        </td>                                          
                                        <td>
                                            <p v-if="fundingSource.status == 'Draft'" class="text-sm font-weight-bolder mb-0" style="color:#878686">{{ fundingSource.status }}</p>
                                            <p v-if="fundingSource.status == 'Finished'" class="text-sm font-weight-bolder mb-0" style="color:#28A745">{{ fundingSource.status }}</p>
                                        </td>                                          
                                        <td>
                                            <p class="text-sm mb-0">{{ fundingSource.usuario.name }}</p>
                                        </td>
                                        <td class="align-middle text-end">
                                            <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                <a class="btn btn-alert btn-xs" title="Edit" @click="editFundingSource(fundingSource)"><i class="fa fa-fw fa-edit"></i></a>
                                                &nbsp;
                                                <a class="btn btn-success btn-xs" title="Details" @click="verFunding(fundingSource)"><i class="fa-regular fa-eye"></i></a>
                                                &nbsp;
                                                <a class="btn btn-closed btn-xs" title="Delete" @click="deleteFundingSource(fundingSource.id)"><i class="fa fa-fw fa-trash"></i></a>
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
            <modalver v-bind:fundingSource1="fundingSource" v-if="showDetailsSource" @close="showDetailsSource = false"></modalver>
            <modaleditar v-bind:fundingSource1="fundingSourceEdit" v-if="showFundingSourceEdit" @close="showFundingSourceEdit = false" @recarga="recargarTabla('General')"></modaleditar>
            <modalcrear v-if="showNewFundingSource" @close="showNewFundingSource = false" @recarga="recargarTabla('General')"></modalcrear>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import modalver from '../../snippets/sistema/fundingSources/detailsFundingSourcesModal.vue'
import modalcrear from '../../snippets/sistema/fundingSources/createFundingSourcesModal.vue'
import modaleditar from '../../snippets/sistema/fundingSources/editFundingSourcesModal.vue'
import modalconfirmacion from '../../snippets/sistema/alerts/confirmationModal.vue'
import modalalerta from '../../snippets/sistema/alerts/alertModal.vue'
import {mixin} from '../../../mixins.js'

export default {
    components: { modalver ,modalcrear, modaleditar, modalconfirmacion, modalalerta },
    mixins: [mixin],
    data(){
        return{
            fundingSources: null,
            fundingSource: null,
            showDetailsSource: false,
            showNewFundingSource: false,
            showFundingSourceEdit: false,
            fundingSourceEdit: null,
            mostrarTabla: false,
            mostrarCarga: true,
            table: null,
        }
    },
    created(){
        this.getFundingSources(this.userID);
    },
    methods: {
        verFunding(fundingSource){
            this.fundingSource = fundingSource;
            this.showDetailsSource = true;
        },
        getFundingSources(id){
            axios.get(`api/fundingSources/${id}`).then( response =>{
                this.fundingSources = response.data;
                if (this.table != null){
                    this.table.clear();
                    this.table.destroy();
                }
                this.crearTabla('#myTableFunding');
            }).catch(e=> console.log(e))
        },
        recargarTabla($tipoRecarga){
            this.mostrarCarga = true;
            if($tipoRecarga == 'General'){
                this.fundingSources = null;
                this.getFundingSources(this.userID);
            }
            else{
                this.crearTabla("#myTableFunding");
            }
        },
        editFundingSource(funding){
            this.fundingSourceEdit = funding;
            this.showFundingSourceEdit= true;
        },
        async deleteFundingSource(id) {
            const ok = await this.$refs.confirmation.show({
                title: 'Delete Funding source',
                message: `¿Are you sure you want to delete this Funding source? This action cannot be undone.`,
                okButton: 'Delete',
                cancelButton: 'Return'
            })
            if (ok) {
                axios.delete(`api/fundingSources/${id}`).then( response =>{
                    this.toast.success("Funding source successfully removed!", {
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