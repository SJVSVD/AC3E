<template>
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="row pb-0 p-4">
                    <div class="col-12">
                        <div class="d-flex justify-content-end">
                            <a class="btn btn-spacing btn-continue" id="show-modal1" @click="showNewIsiPublication = true">Nueva Publicación</a>
                            &nbsp;
                            <a class="btn btn-spacing btn-search-blue" @click="recargarTabla('General')"><i class="fa-solid fa-rotate"></i></a>
                        </div>
                    </div>
                    <!-- <div class="dropdown col-3">
                        <a class="btn btn-spacing btn-primary" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                            Mostrar/Ocultar Columnas:
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <li><a class="dropdown-item toggle-vis" data-column="2" href="#">Nombre</a></li>
                            <li><a class="dropdown-item toggle-vis" data-column="3" href="#">Correo Electrónico</a></li>
                            <li><a class="dropdown-item toggle-vis" data-column="4" href="#">Rol</a></li>
                            <li><a class="dropdown-item toggle-vis" data-column="5" href="#">Acciones</a></li>
                        </ul>
                    </div> -->
                </div>
                <div class="card-body px-0 pt-0 pb-2" style="min-height: 400px">
                    <div class="container">
                        <div class="table-responsive p-0">
                            <div v-show="mostrarCarga" class="loader-sm"></div>
                            <table v-show="mostrarTabla" class="table align-items-center mb-0" id="MyTableIsiPublications">
                                <thead>
                                    <tr style="color: black">
                                        <th style="min-width: 16px;"></th>
                                        <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Usuario</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Author(s)</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Coauthor(s) </th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Article Title</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Journal Name</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Doi</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Volume</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">First Page</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Last Page</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Year Published</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Acciones</th>
                                        
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="isiPublication in isiPublications" :key="isiPublication.id">
                                        <td></td>
                                        <td>
                                            <p class="text-sm font-weight-bolder mb-0" style="color:black">{{ isiPublication.id }}</p>
                                        </td>                                          
                                        <td>
                                            <p class="text-sm mb-0">{{ isiPublication.usuario.name }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0">{{ isiPublication.authors }}</p>
                                        </td>
                                        <td>
                                            <p v-if="isiPublication.coauthor == null" class="text-sm mb-0">---</p>
                                            <p v-else class="text-sm mb-0">{{ isiPublication.coauthor }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0">{{ isiPublication.articleTitle }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0">{{ isiPublication.journalName }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0">{{ isiPublication.doi }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0">{{ isiPublication.volume }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0">{{ isiPublication.firstPage }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0">{{ isiPublication.lastPage }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0">{{ isiPublication.yearPublished }}</p>
                                        </td>
                                        <td class="align-middle text-end">
                                            <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                <a class="btn btn-alert btn-xs" title="Editar" @click="editIsiPublication(isiPublication)"><i class="fa fa-fw fa-edit"></i></a>
                                                &nbsp;
                                                <a class="btn btn-closed btn-xs" title="Eliminar" @click="deletePublication(isiPublication.id,)"><i class="fa fa-fw fa-trash"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="row">
                                <div class="col-3">
                                    <label style="font-weight: 500">Estos botones utilizan los elementos seleccionados en la tabla, en caso de no existir ninguno seleccionará todos los registros. </label>
                                </div>
                                <div class="col-auto">
                                    <label title="Para seleccionar UN solo registro de la tabla basta con hacer &#013; click en el recuadro de la primera columna, para seleccionar &#013; varios consecutivos mantener SHIFT, para seleccionar varios &#013; no consecutivos mantener CTRL."><span class="badge bg-dark-grey fs-10">?</span></label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <modalconfirmacion ref="confirmation"></modalconfirmacion>
            <modalalerta ref="alert"></modalalerta>
            <modaleditar v-bind:isiPublication1="isiPublicationEdit" v-if="showEditIsiPublication" @close="showEditIsiPublication = false" @recarga="recargarTabla('General')"></modaleditar>
            <modalcrear v-if="showNewIsiPublication" @close="showNewIsiPublication = false" @recarga="recargarTabla('General')"></modalcrear>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import modalcrear from '../../snippets/sistema/isiPublications/createIsiPublicationModal.vue'
import modaleditar from '../../snippets/sistema/isiPublications/editIsiPublicationModal.vue'
import modalconfirmacion from '../../snippets/sistema/alerts/confirmationModal.vue'
import modalalerta from '../../snippets/sistema/alerts/alertModal.vue'
import {mixin} from '../../../mixins.js'

export default {
    components: { modalcrear, modaleditar, modalconfirmacion, modalalerta },
    mixins: [mixin],
    data(){
        return{
            isiPublications: null,
            showNewIsiPublication: false,
            showEditIsiPublication: false,
            isiPublicationEdit: null,
            mostrarTabla: false,
            mostrarCarga: true,
            table: null,
        }
    },
    created(){
        this.getIsiPublications(this.userID);
    },
    methods: {
        getIsiPublications(id){
            axios.get(`api/isiPublications/${id}`).then( response =>{
                this.isiPublications = response.data;
                this.isiPublicationsAux = response.data;
                if (this.table != null){
                    this.table.clear();
                    this.table.destroy();
                }
                this.crearTabla('#MyTableIsiPublications');
            }).catch(e=> console.log(e))
        },
        recargarTabla($tipoRecarga){
            this.mostrarCarga = true;
            if($tipoRecarga == 'General'){
                this.isiPublications = null;
                this.getIsiPublications(this.userID);
            }
            else{
                this.crearTabla("#MyTableIsiPublications");
            }
        },
        editIsiPublication(isiPublication){
            this.isiPublicationEdit = isiPublication;
            this.showEditIsiPublication = true;
        },
        async deletePublication(id) {
            const ok = await this.$refs.confirmation.show({
                title: 'Eliminar Publicación',
                message: `¿Está seguro/a que desea eliminar esta publicación? Esta acción no puede ser desecha.`,
                okButton: 'Eliminar',
                cancelButton: 'Volver'
            })
            if (ok) {
                axios.delete(`api/isiPublications/${id}`).then( response =>{
                    this.toast.success("Publicación eliminado con éxito!", {
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