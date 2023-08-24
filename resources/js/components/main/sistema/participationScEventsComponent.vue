<template>
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="row pb-0 p-4">
                    <div class="col-12">
                        <div class="d-flex justify-content-end">
                            <a class="btn btn-spacing btn-continue" id="show-modal1" @click="showNewParticipation = true">New Participation</a>
                            &nbsp;
                            <a class="btn btn-spacing btn-search-blue" @click="recargarTabla('General')"><i class="fa-solid fa-rotate"></i></a>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2" style="min-height: 400px">
                    <div class="container">
                        <div class="table-responsive p-0">
                            <div v-show="mostrarCarga" class="loader-sm"></div>
                            <table v-show="mostrarTabla" class="table align-items-center mb-0" id="myTableParticipations">
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
                                    <tr v-for="participationScEvent in participationScEvents" :key="participationScEvent.id">
                                        <td></td>
                                        <td>
                                            <p class="text-sm font-weight-bolder mb-0" style="color:black">{{ participationScEvent.id }}</p>
                                        </td>                                          
                                        <td>
                                            <p v-if="participationScEvent.status == 'Draft'" class="text-sm font-weight-bolder mb-0" style="color:#878686">{{ participationScEvent.status }}</p>
                                            <p v-if="participationScEvent.status == 'Finished'" class="text-sm font-weight-bolder mb-0" style="color:#28A745">{{ participationScEvent.status }}</p>
                                        </td>                                          
                                        <td>
                                            <p class="text-sm mb-0">{{ participationScEvent.usuario.name }}</p>
                                        </td>
                                        <td class="align-middle text-end">
                                            <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                <a v-if="participationScEvent.file != null" class="btn btn-search-blue btn-xs" title="Download Thesis Extract" @click="descargarExtracto(participationScEvent.id, participationScEvent.usuario.name)"><i class="fa-solid fa-download"></i></a>
                                                <a v-else class="btn btn-grey btn-xs"><i class="fa-solid fa-download"></i></a>
                                                &nbsp;
                                                <a class="btn btn-alert btn-xs" title="Edit" @click="editParticipation(participationScEvent)"><i class="fa fa-fw fa-edit"></i></a>
                                                &nbsp;
                                                <a class="btn btn-closed btn-xs" title="Delete" @click="deleteParticipation(participationScEvent.id)"><i class="fa fa-fw fa-trash"></i></a>
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
            <modaleditar v-bind:participation1="participationEdit" v-if="showEditParticipation" @close="showEditParticipation = false" @recarga="recargarTabla('General')"></modaleditar>
            <modalcrear v-if="showNewParticipation" @close="showNewParticipation = false" @recarga="recargarTabla('General')"></modalcrear>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import modalcrear from '../../snippets/sistema/participationScEvents/createParticipationScEventModal.vue'
import modaleditar from '../../snippets/sistema/participationScEvents/editParticipationScEventModal.vue'
import modalconfirmacion from '../../snippets/sistema/alerts/confirmationModal.vue'
import modalalerta from '../../snippets/sistema/alerts/alertModal.vue'
import {mixin} from '../../../mixins.js'

export default {
    components: { modalcrear, modaleditar, modalconfirmacion, modalalerta },
    mixins: [mixin],
    data(){
        return{
            participationScEvents: null,
            showNewParticipation: false,
            showEditParticipation: false,
            participationEdit: null,
            mostrarTabla: false,
            mostrarCarga: true,
            table: null,
        }
    },
    created(){
        this.getParticipations(this.userID);
    },
    methods: {
        descargarExtracto(id,nombre){
            axios({
                url: `api/participationDownload/${id}`,
                method: 'GET',
                responseType: 'arraybuffer',
            }).then((response) => {
                let blob = new Blob([response.data], {
                        type: 'application/pdf'
                    })
                    let link = document.createElement('a')
                    link.href = window.URL.createObjectURL(blob)
                    link.download = `Participation-${nombre}.pdf`
                    link.click()
            });
        },
        getParticipations(id){
            axios.get(`api/participationScEvents/${id}`).then( response =>{
                this.participationScEvents = response.data;
                if (this.table != null){
                    this.table.clear();
                    this.table.destroy();
                }
                this.crearTabla('#myTableParticipations');
            }).catch(e=> console.log(e))
        },
        recargarTabla($tipoRecarga){
            this.mostrarCarga = true;
            if($tipoRecarga == 'General'){
                this.participationScEvents = null;
                this.getParticipations(this.userID);
            }
            else{
                this.crearTabla("#myTableParticipations");
            }
        },
        editParticipation(participation){
            this.participationEdit = participation;
            this.showEditParticipation= true;
        },
        async deleteParticipation(id) {
            const ok = await this.$refs.confirmation.show({
                title: 'Delete Participation',
                message: `¿Are you sure you want to delete this Participation? This action cannot be undone.`,
                okButton: 'Delete',
                cancelButton: 'Return'
            })
            if (ok) {
                axios.delete(`api/participationScEvents/${id}`).then( response =>{
                    this.toast.success("Participation successfully removed!", {
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