<template>
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="row pb-0 p-4">
                    <div class="col-12">
                        <div class="d-flex justify-content-end">
                            <a class="btn btn-spacing btn-continue" id="show-modal1" @click="showNewAward = true">New Award</a>
                            &nbsp;
                            <a class="btn btn-spacing btn-search-blue" @click="recargarTabla('General')"><i class="fa-solid fa-rotate"></i></a>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2" style="min-height: 400px">
                    <div class="container">
                        <div class="table-responsive p-0">
                            <div v-show="mostrarCarga" class="loader-sm"></div>
                            <table v-show="mostrarTabla" class="table align-items-center mb-0" id="MyTableAward">
                                <thead>
                                    <tr style="color: black">
                                        <th style="min-width: 16px;"></th>
                                        <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Status</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Awardee Name</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Award Name</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Year</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="award in awards" :key="award.id">
                                        <td></td>
                                        <td>
                                            <p class="text-sm font-weight-bolder mb-0" style="color:black">{{ award.id }}</p>
                                        </td>                                          
                                        <td>
                                            <p v-if="award.status == 'Draft'" class="text-sm font-weight-bolder mb-0" style="color:#878686">{{ award.status }}</p>
                                            <p v-if="award.status == 'Finished'" class="text-sm font-weight-bolder mb-0" style="color:#28A745">{{ award.status }}</p>
                                        </td>                                          
                                        <td>
                                            <p class="text-sm mb-0">{{ award.usuario.name }}</p>
                                        </td>
                                        <td>
                                            <p v-if="award.awardName == null" class="text-sm mb-0">---</p>
                                            <p v-else class="text-sm mb-0">{{ award.awardName }}</p>
                                        </td>
                                        <td>
                                            <p v-if="award.year == null" class="text-sm mb-0">---</p>
                                            <p v-else class="text-sm mb-0">{{ award.year }}</p>
                                        </td>
                                        <td class="align-middle text-end">
                                            <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                <a class="btn btn-alert btn-xs" title="Edit" @click="editAward(award)"><i class="fa fa-fw fa-edit"></i></a>
                                                &nbsp;
                                                <a class="btn btn-success btn-xs" title="Details" @click="verAward(award)"><i class="fa-regular fa-eye"></i></a>
                                                &nbsp;
                                                <a class="btn btn-closed btn-xs" title="Delete" @click="deleteAward(award.id)"><i class="fa fa-fw fa-trash"></i></a>
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
            <modalver v-bind:award1="award" v-if="showDetailsAward" @close="showDetailsAward = false"></modalver>
            <modaleditar v-bind:award1="awardEdit" v-if="showEditAward" @close="showEditAward = false" @recarga="recargarTabla('General')"></modaleditar>
            <modalcrear v-if="showNewAward" @close="showNewAward = false" @recarga="recargarTabla('General')"></modalcrear>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import modalver from '../../snippets/sistema/awards/detailsAwardsModal.vue'
import modalcrear from '../../snippets/sistema/awards/createAwardModal.vue'
import modaleditar from '../../snippets/sistema/awards/editAwardModal.vue'
import modalconfirmacion from '../../snippets/sistema/alerts/confirmationModal.vue'
import modalalerta from '../../snippets/sistema/alerts/alertModal.vue'
import {mixin} from '../../../mixins.js'

export default {
    components: { modalver ,modalcrear, modaleditar, modalconfirmacion, modalalerta },
    mixins: [mixin],
    data(){
        return{
            awards: null,
            award: null,
            showDetailsAward: false,
            showNewAward: false,
            showEditAward: false,
            awardEdit: null,
            mostrarTabla: false,
            mostrarCarga: true,
            table: null,
        }
    },
    created(){
        this.getAwards(this.userID);
    },
    methods: {
        verAward(award){
            this.award = award;
            this.showDetailsAward = true;
        },
        getAwards(id){
            axios.get(`api/awards/${id}`).then( response =>{
                this.awards = response.data;
                if (this.table != null){
                    this.table.clear();
                    this.table.destroy();
                }
                this.crearTabla('#MyTableAward');
            }).catch(e=> console.log(e))
        },
        recargarTabla($tipoRecarga){
            this.mostrarCarga = true;
            if($tipoRecarga == 'General'){
                this.awards = null;
                this.getAwards(this.userID);
            }
            else{
                this.crearTabla("#MyTableAward");
            }
        },
        editAward(award){
            this.awardEdit = award;
            this.showEditAward = true;
        },
        async deleteAward(id) {
            const ok = await this.$refs.confirmation.show({
                title: 'Delete Award',
                message: `¿Are you sure you want to delete this Award? This action cannot be undone.`,
                okButton: 'Delete',
                cancelButton: 'Return'
            })
            if (ok) {
                axios.delete(`api/awards/${id}`).then( response =>{
                    this.toast.success("Award successfully removed!", {
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