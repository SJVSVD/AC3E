<template>
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="row pb-0 p-4">
                    <div class="col-12">
                        <div class="d-flex justify-content-end">
                            <a v-if="can('Manuales_Crear')" class="btn btn-spacing btn-continue" id="show-modal1" @click="showCrearManual = true">Nuevo</a>
                            &nbsp;
                            <a class="btn btn-spacing btn-search-blue" @click="recargarTabla('General')"><i class="fa-solid fa-rotate"></i></a>
                        </div>
                    </div>
                    <!-- <div class="dropdown col-12 d-flex justify-content-end">
                        <a class="btn btn-primary" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                            Mostrar/Ocultar Columnas:
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                            <li><a class="dropdown-item toggle-vis" data-column="1" href="#">ID</a></li>
                            <li><a class="dropdown-item toggle-vis" data-column="2" href="#">Folio</a></li>
                            <li><a class="dropdown-item toggle-vis" data-column="3" href="#">Fecha</a></li>
                            <li><a class="dropdown-item toggle-vis" data-column="4" href="#">Usuario</a></li>
                            <li><a class="dropdown-item toggle-vis" data-column="5" href="#">Cantidad Gastos</a></li>
                            <li><a class="dropdown-item toggle-vis" data-column="6" href="#">Fondos Asignados</a></li>
                            <li><a class="dropdown-item toggle-vis" data-column="7" href="#">Monto Total</a></li>
                            <li><a class="dropdown-item toggle-vis" data-column="7" href="#">Acciones</a></li>
                        </ul>
                    </div> -->
                </div>                
                <div class="card-body px-0 pt-0 pb-2" style="min-height: 400px">
                    <div class="container">
                        <div class="table-responsive p-0">
                            <div v-show="mostrarCarga" class="loader-sm"></div>
                            <table v-show="mostrarTabla" class="table align-items-center mb-0" id="MyTableManuales">
                                <thead>
                                    <tr style="color: black">
                                        <th style="min-width: 16px;"></th>
                                        <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Modulo</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Titulo</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Fecha</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="manual in manuales" :key="manual.id">
                                        <td></td>
                                        <td>
                                            <p class="text-sm font-weight-bolder mb-0" style="color:black">{{ manual.id }}</p>
                                        </td>                                           
                                        <td>
                                            <p class="text-sm mb-0">{{ manual.modulo }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0">{{ manual.titulo }}</p>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0">{{ thisDate(manual.fecha) }}</p>
                                        </td>
                                        <td class="align-middle text-end">
                                            <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                <a v-if="manual.url != null" :href=manual.url  target="_blank" class="btn btn-purple btn-xs"><i class="fa-solid fa-play"></i></a>
                                                <a v-else class="btn btn-grey btn-xs"><i class="fa-solid fa-play"></i></a>
                                                &nbsp;
                                                <a v-if="manual.archivo != null" class="btn btn-success btn-xs" title="Descargar" @click="descargarManual(manual.id, manual.titulo)"><i class="fa-solid fa-download"></i></a>
                                                <a v-else class="btn btn-grey btn-xs"><i class="fa-solid fa-download"></i></a>
                                                &nbsp;
                                                <div v-if="can('Manuales_Modificar')">
                                                    <a class="btn btn-closed btn-xs" title="Eliminar" @click="deleteRobo(manual.id)"><i class="fa fa-fw fa-trash"></i></a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
            <modalconfirmacion ref="confirmation"></modalconfirmacion>
            <modalalerta ref="alert"></modalalerta>
            <modaleditar v-bind:usuario="usuarioeditar" v-if="showEditarUsuario" @close="showEditarUsuario = false" @recarga="recargarTabla('General')" ></modaleditar>
            <modalcrear v-if="showCrearManual" @close="showCrearManual = false" @recarga="recargarTabla('General')" ></modalcrear>
            <modaldetalle v-bind:robo="detalles" v-if="showDetallesRobo" @close="showDetallesRobo = false"></modaldetalle>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import modalcrear from './snippets/sistema/manuales/createManualModal.vue'
import modaleditar from './snippets/sistema/usuarios/editUserModal.vue'
import modaldetalle from './snippets/tiendas/gestionRobos/detalleRobosModal.vue'
import modalconfirmacion from './snippets/sistema/alerts/confirmationModal.vue'
import modalalerta from './snippets/sistema/alerts/alertModal.vue'
import {mixin} from '../mixins.js'

export default {
    components: { modalcrear, modaleditar, modalconfirmacion, modaldetalle, modalalerta },
    mixins: [mixin],
    data(){
        return{
            manuales: null,
            showCrearManual: false,
            showEditarUsuario: false,
            showDetallesRobo: false,
            usuarioeditar: null,
            mostrarTabla: false,
            mostrarCarga: true,
            detalles: null,
            roles: null,
            table: null,
            fechaInicio: null,
            fechaTermino: null,
        }
    },
    created(){
        this.getManuales();
    },
    methods: {
        getManuales(){
            axios.get(`api/manuales/${this.userID}`).then( response =>{
                console.log(response);
                this.manuales = response.data;
                if (this.table != null){
                    this.table.clear();
                    this.table.destroy();
                }
                this.crearTabla('#MyTableManuales');
            }).catch(e=> console.log(e))
        },
        filtrarFecha(){
            if (this.fechaInicio == null || this.fechaTermino == null){
                this.recargarTabla('General');
            }else{
                let start = new Date(this.fechaInicio);
                let end = new Date(this.fechaTermino);
                this.robos = this.robos.filter(item => {
                    let date = new Date(item.fecha);
                    return date >= start && date <= end;
                });
                this.recargarTabla();
            }
        },
        descargarManual(id,nombre){
            axios({
                url: `api/manualDownload/${id}`,
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
        recargarTabla($tipoRecarga){
            this.mostrarCarga = true;
            if($tipoRecarga == 'General'){
                this.manuales = null;
                this.getManuales();
            }else{
                this.table.destroy();
                this.crearTabla('#MyTableManuales');
            }
        },
        usuarioEditar(usuario){
            this.usuarioeditar = usuario;
            this.showEditarUsuario = true;
        },
        async deleteRobo(id) {
            const ok = await this.$refs.confirmation.show({
                title: 'Borrar Manual',
                message: `¿Está seguro/a que desea eliminar esta Manual? Esta acción no puede ser desecha.`,
                okButton: 'Eliminar',
                cancelButton: 'Volver'
            })
            if (ok) {
                axios.delete(`api/manuales/${id}`).then( response =>{
                    this.toast.success("Manual eliminado con éxito!", {
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