<template>
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="row pb-0 p-4">
                    <div class="col-12">
                        <div class="d-flex justify-content-end">
                            <a class="btn btn-spacing btn-continue" id="show-modal1" @click="showNewOrganization = true">New Organization</a>
                            &nbsp;
                            <a class="btn btn-spacing btn-search-blue" @click="recargarTabla('General')"><i class="fa-solid fa-rotate"></i></a>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2" style="min-height: 400px">
                    <div class="container">
                        <div class="table-responsive p-0">
                            <div v-show="mostrarCarga" class="loader-sm"></div>
                            <table v-show="mostrarTabla" class="table align-items-center mb-0" id="MyTableOrganization">
                                <thead>
                                    <tr style="color: black">
                                        <th style="min-width: 16px;"></th>
                                        <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Actions</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Status</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">User</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Event Name</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Start Date</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Ending Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="organizationScEvent in organizationScEvents" :key="organizationScEvent.id">
                                        <td></td>
                                        <td>
                                            <p class="text-sm font-weight-bolder mb-0" style="color:black">{{ organizationScEvent.id }}</p>
                                        </td>                                          
                                        <td class="align-middle text-end">
                                            <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                <a v-if="organizationScEvent.file != null" class="btn btn-search-blue btn-xs" title="Download File" @click="descargarExtracto(organizationScEvent.id, organizationScEvent.usuario.name)"><i class="fa-solid fa-download"></i></a>
                                                <a v-else class="btn btn-grey btn-xs"><i class="fa-solid fa-download"></i></a>
                                                &nbsp;
                                                <a class="btn btn-alert btn-xs" title="Edit" @click="editOrganization(organizationScEvent)"><i class="fa fa-fw fa-edit"></i></a>
                                                &nbsp;
                                                <a class="btn btn-success btn-xs" title="Details" @click="verOrganization(organizationScEvent)"><i class="fa-regular fa-eye"></i></a>
                                                &nbsp;
                                                <a class="btn btn-closed btn-xs" title="Delete" @click="deleteOrganization(organizationScEvent.id)"><i class="fa fa-fw fa-trash"></i></a>
                                            </div>
                                        </td>
                                        <td>
                                            <p v-if="organizationScEvent.status == 'Draft'" class="text-sm font-weight-bolder mb-0" style="color:#878686">{{ organizationScEvent.status }}</p>
                                            <p v-if="organizationScEvent.status == 'Finished'" class="text-sm font-weight-bolder mb-0" style="color:#28A745">{{ organizationScEvent.status }}</p>
                                        </td>                                          
                                        <td>
                                            <p class="text-sm mb-0">{{ organizationScEvent.usuario.name }}</p>
                                        </td>
                                        <td>
                                            <p v-if="organizationScEvent.eventName == null" class="text-sm mb-0">---</p>
                                            <p v-else class="text-sm mb-0 truncate-text">{{ organizationScEvent.eventName }}</p>
                                        </td>
                                        <td>
                                            <p v-if="organizationScEvent.startDate == null" class="text-sm mb-0">---</p>
                                            <p v-else class="text-sm mb-0">{{ organizationScEvent.startDate }}</p>
                                        </td>
                                        <td>
                                            <p v-if="organizationScEvent.endingDate == null" class="text-sm mb-0">---</p>
                                            <p v-else class="text-sm mb-0">{{ organizationScEvent.endingDate }}</p>
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
            <modalver v-bind:organization1="organizationScEvent" v-if="showDetailsOrganization" @close="showDetailsOrganization = false"></modalver>
            <modaleditar v-bind:organization1="organizationEdit" v-if="showEditOrganization" @close="showEditOrganization = false" @recarga="recargarTabla('General')"></modaleditar>
            <modalcrear v-if="showNewOrganization" @close="showNewOrganization = false" @recarga="recargarTabla('General')"></modalcrear>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import modalver from '../../snippets/sistema/organizationScEvents/detailsOrganizationScEventsModal.vue'
import modalcrear from '../../snippets/sistema/organizationScEvents/createOrganizationScEventModal.vue'
import modaleditar from '../../snippets/sistema/organizationScEvents/editOrganizationScEventModal.vue'
import modalconfirmacion from '../../snippets/sistema/alerts/confirmationModal.vue'
import modalalerta from '../../snippets/sistema/alerts/alertModal.vue'
import {mixin} from '../../../mixins.js'

export default {
    components: { modalver ,modalcrear, modaleditar, modalconfirmacion, modalalerta },
    mixins: [mixin],
    data(){
        return{
            organizationScEvents: null,
            organizationScEvent: null,
            showDetailsOrganization: false,
            showNewOrganization: false,
            showEditOrganization: false,
            organizationEdit: null,
            mostrarTabla: false,
            mostrarCarga: true,
            table: null,
        }
    },
    created(){
        this.getOrganizations(this.userID);
    },
    methods: {
        verOrganization(organizationScEvent){
            this.organizationScEvent = organizationScEvent;
            this.showDetailsOrganization = true;
        },
        descargarExtracto(id,nombre){
            axios({
                url: `api/organizationDownload/${id}`,
                method: 'GET',
                responseType: 'arraybuffer',
            }).then((response) => {
                let blob = new Blob([response.data], {
                    type: response.headers['content-type']
                });
                let link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                if (blob.type.includes('pdf')) {
                    link.download = `Organization-${nombre}.pdf`;
                } else if (blob.type.includes('image')) {
                    link.download = `Organization-${nombre}.png`; // Cambia la extensión según el tipo de imagen
                } else {
                    // Si el tipo de archivo no es ni PDF ni imagen, puedes manejarlo de acuerdo a tus requerimientos
                    console.error('Tipo de archivo no compatible');
                    return;
                }
                link.click();
            });
        },
        getOrganizations(id){
            axios.get(`api/organizationScEvents/${id}`).then( response =>{
                this.organizationScEvents = response.data;
                if (this.table != null){
                    this.table.clear();
                    this.table.destroy();
                }
                this.crearTabla('#MyTableOrganization');
            }).catch(e=> console.log(e))
        },
        recargarTabla($tipoRecarga){
            this.mostrarCarga = true;
            if($tipoRecarga == 'General'){
                this.organizationScEvents = null;
                this.getOrganizations(this.userID);
            }
            else{
                this.crearTabla("#MyTableOrganization");
            }
        },
        editOrganization(organization){
            this.organizationEdit = organization;
            this.showEditOrganization = true;
        },
        async deleteOrganization(id) {
            const ok = await this.$refs.confirmation.show({
                title: 'Delete Organization',
                message: `¿Are you sure you want to delete this Organization?.`,
                okButton: 'Delete',
                cancelButton: 'Return'
            })
            if (ok) {
                axios.delete(`api/organizationScEvents/${id}`).then( response =>{
                    this.toast.success("Organization successfully removed!", {
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