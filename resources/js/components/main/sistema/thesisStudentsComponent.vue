<template>
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="row pb-0 p-4">
                    <div class="col-12">
                        <div class="d-flex justify-content-end">
                            <a class="btn btn-spacing btn-continue" id="show-modal1" @click="showNewThesisStudent = true">New Thesis</a>
                            &nbsp;
                            <a class="btn btn-spacing btn-search-blue" @click="recargarTabla('General')"><i class="fa-solid fa-rotate"></i></a>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2" style="min-height: 400px">
                    <div class="container">
                        <div class="table-responsive p-0">
                            <div v-show="mostrarCarga" class="loader-sm"></div>
                            <table v-show="mostrarTabla" class="table align-items-center mb-0" id="MyTableThesisStudents">
                                <thead>
                                    <tr style="color: black">
                                        <th style="min-width: 16px;"></th>
                                        <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Status</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">User</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Thesis Title</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Student Name</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Tutor Name</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Academic Degree</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Actions</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="thesisStudent in thesisStudents" :key="thesisStudent.id">
                                        <td></td>
                                        <td>
                                            <p class="text-sm font-weight-bolder mb-0" style="color:black">{{ thesisStudent.id }}</p>
                                        </td>
                                        <td>
                                            <p v-if="thesisStudent.status == 'Draft'" class="text-sm font-weight-bolder mb-0" style="color:#878686">{{ thesisStudent.status }}</p>
                                            <p v-if="thesisStudent.status == 'Finished'" class="text-sm font-weight-bolder mb-0" style="color:#28A745">{{ thesisStudent.status }}</p>
                                        </td>                                             
                                        <td>
                                            <p class="text-sm mb-0">{{ thesisStudent.usuario.name }}</p>
                                        </td>
                                        <td>
                                            <p v-if="thesisStudent.thesisTitle == null" class="text-sm mb-0">---</p>
                                            <p v-else class="text-sm mb-0">{{ thesisStudent.thesisTitle }}</p>
                                        </td>
                                        <td>
                                            <p v-if="thesisStudent.studentName == null" class="text-sm mb-0">---</p>
                                            <p v-else class="text-sm mb-0">{{ thesisStudent.studentName }}</p>
                                        </td>
                                        <td>
                                            <p v-if="thesisStudent.tutorName == null" class="text-sm mb-0">---</p>
                                            <p v-else class="text-sm mb-0">{{ thesisStudent.tutorName }}</p>
                                        </td>
                                        <td>
                                            <p v-if="thesisStudent.academicDegree == null" class="text-sm mb-0">---</p>
                                            <p v-else class="text-sm mb-0">{{ thesisStudent.academicDegree }}</p>
                                        </td>
                                        <td class="align-middle text-end">
                                            <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                <a v-if="thesisStudent.file != null" class="btn btn-search-blue btn-xs" title="Download Thesis Extract" @click="descargarExtracto(thesisStudent.id, thesisStudent.usuario.name)"><i class="fa-solid fa-download"></i></a>
                                                <a v-else class="btn btn-grey btn-xs"><i class="fa-solid fa-download"></i></a>
                                                &nbsp;
                                                <a class="btn btn-success btn-xs" title="Details" @click="verThesis(thesisStudent)"><i class="fa-regular fa-eye"></i></a>
                                                &nbsp;
                                                <a class="btn btn-alert btn-xs" title="Edit" @click="editThesisStudent(thesisStudent)"><i class="fa fa-fw fa-edit"></i></a>
                                                &nbsp;
                                                <a class="btn btn-closed btn-xs" title="Delete" @click="deleteThesisStudent(thesisStudent.id,)"><i class="fa fa-fw fa-trash"></i></a>
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
            <modalver v-bind:thesisStudent1="thesisStudent" v-if="showDetailsThesis" @close="showDetailsThesis = false"></modalver>
            <modaleditar v-bind:thesisStudent1="thesisStudentEdit" v-if="showEditThesisStudent" @close="showEditThesisStudent = false" @recarga="recargarTabla('General')"></modaleditar>
            <modalcrear v-if="showNewThesisStudent" @close="showNewThesisStudent = false" @recarga="recargarTabla('General')"></modalcrear>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import modalver from '../../snippets/sistema/thesisStudents/detalleThesisModal.vue'
import modalcrear from '../../snippets/sistema/thesisStudents/createThesisStudentModal.vue'
import modaleditar from '../../snippets/sistema/thesisStudents/editThesisStudentModal.vue'
import modalconfirmacion from '../../snippets/sistema/alerts/confirmationModal.vue'
import modalalerta from '../../snippets/sistema/alerts/alertModal.vue'
import {mixin} from '../../../mixins.js'

export default {
    components: { modaleditar,modalcrear, modalver, modalconfirmacion, modalalerta },
    mixins: [mixin],
    data(){
        return{
            thesisStudents: null,
            thesisStudent: null,
            showNewThesisStudent: false,
            showDetailsThesis: false,
            showEditThesisStudent: false,
            thesisStudentEdit: null,
            mostrarTabla: false,
            mostrarCarga: true,
            table: null,
        }
    },
    created(){
        this.getThesisStudents(this.userID);
    },
    methods: {
        descargarExtracto(id,nombre){
            axios({
                url: `api/thesisDownload/${id}`,
                method: 'GET',
                responseType: 'arraybuffer',
            }).then((response) => {
                let blob = new Blob([response.data], {
                        type: 'application/pdf'
                    })
                    let link = document.createElement('a')
                    link.href = window.URL.createObjectURL(blob)
                    link.download = `${nombre}.pdf`
                    link.click()
            });
        },
        verThesis(thesisStudent2){
            this.thesisStudent = thesisStudent2;
            this.showDetailsThesis = true;
        },
        getThesisStudents(id){
            axios.get(`api/thesisStudents/${id}`).then( response =>{
                this.thesisStudents = response.data;
                if (this.table != null){
                    this.table.clear();
                    this.table.destroy();
                }
                this.crearTabla('#MyTableThesisStudents');
            }).catch(e=> console.log(e))
        },
        recargarTabla($tipoRecarga){
            this.mostrarCarga = true;
            if($tipoRecarga == 'General'){
                this.thesisStudents = null;
                this.getThesisStudents(this.userID);
            }
            else{
                this.crearTabla("#MyTableThesisStudents");
            }
        },
        editThesisStudent(thesisStudent){
            this.thesisStudentEdit = thesisStudent;
            this.showEditThesisStudent = true;
        },
        async deleteThesisStudent(id) {
            const ok = await this.$refs.confirmation.show({
                title: 'Delete Thesis',
                message: `¿Are you sure you want to delete this Thesis? This action cannot be undone.`,
                okButton: 'Delete',
                cancelButton: 'Return'
            })
            if (ok) {
                axios.delete(`api/thesisStudents/${id}`).then( response =>{
                    this.toast.success("Thesis successfully removed!", {
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