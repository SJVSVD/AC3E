<template>
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4" style="flex-direction: inherit;">
                <div class="btn-group-vertical" role="group" style="min-height: 450px; width: max-content;">
                    <!-- <div class="btn-group-vertical" role="group" aria-label="First Group" style="width: 160px; margin-left: 10px; margin-right: 10px;">
                        <input type="radio" class="btn-check" name="vertical-radio-btn" id="vertical-radio-btn5" autocomplete="off" @click="botonCheck('Comisiones')">
                        <label class="btn btn-outline-secondary m-auto" for="vertical-radio-btn5"> AAAAAAAAAAAAAAAAAAA </label>
                    </div> -->
                </div>
                <!-- ################################################################################################################################# -->
                <!-- <div style="width: 100%;">
                    <slot v-if="isVisibleComisiones">
                        <div class="row pb-0 p-4">
                            <div class="col-12">
                                <h6>AAAAAAAAAAAAAAAAAAA</h6>
                                <div class="d-flex justify-content-end">
                                    <a class="btn btn-continue" @click="showCreateAutorizador = true">Nuevo</a>
                                    &nbsp;
                                    <a class="btn btn-search-blue" @click="recargarTabla('Comisiones')"><i class="fa-solid fa-rotate"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2" style="min-height: 400px;">
                            <div v-show="mostrarCarga4" class="loader-xs"></div>
                            <div class="container">
                                <div class="table-responsive p-0">
                                    <table class="table align-items-center mb-0" id="MyTableComisiones">
                                        <thead>
                                            <tr style="color: black">
                                                <th style="min-width: 16px;"></th>
                                                <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Usuario</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Estado</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Rut</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Nombre</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Correo</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Telefono</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="comision in comisiones" :key="comision.id">
                                                <td></td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color:black">{{ comision.id }}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color:black">{{comision.usuario.name}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color:black">{{comision.estado}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color:black">{{comision.rut}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color:black">{{comision.nombre}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color:black">{{comision.correo}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color:black">{{comision.telefono}}</p>
                                                </td>
                                                <td class="align-middle text-end">
                                                    <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                        <a class="btn btn-alert btn-xs" @click="editAutorizador(autorizador)"><i class="fa fa-fw fa-edit"></i></a>
                                                        &nbsp;
                                                        <a class="btn btn-closed btn-xs" @click="deleteAutorizador(autorizador.id,autorizador.autorizador)"><i class="fa fa-fw fa-trash"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </slot>
                </div> -->
            </div>
            <modalconfirmacion ref="confirmation"></modalconfirmacion>
            <modalalerta ref="alert"></modalalerta>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import {mixin} from '../../../mixins.js'
import modalconfirmacion from '../../snippets/sistema/alerts/confirmationModal.vue'
import modalalerta from '../../snippets/sistema/alerts/alertModal.vue'

export default {
    components: { modalconfirmacion, modalalerta },
    mixins: [mixin],
    data: () => ({
        isVisibleTEMA: null,
        // Comisiones
        isVisibleComisiones: false,
        comisiones: null,
        showEditComisiones: false,
        comisionesEdit: null,
        showCreateComisiones: false,
        // -----------------------
        tableComisiones: null,
        mostrarCarga4: true,
        mostrarTabla4: false,
    }),
    created(){
    },
    methods: {
        recargarTabla($tabla){
            if ($tabla == 'Comisiones'){
                this.comisiones = null;
                this.mostrarCarga4 = true;
                this.getComisiones();
            }
        },
        crearTablas($tabla){
            if($tabla == 'Comisiones'){
                setTimeout(function(){
                    this.tableComisiones = $('#MyTableComisiones').DataTable({info: false, select:false, "bLengthChange" : false});
                    this.tableComisiones.buttons().remove();
                    this.mostrarCarga4 = false;
                    this.mostrarTabla4 = true;   
                }.bind(this),200);
            }
        },
        botonCheck($entrada){
            if ($entrada == 'Comisiones'){
                this.comisiones = null;
                this.mostrarCarga4 = true;
                this.getComisiones();
                this.isVisibleComisiones = true;
            }
        },
        // Comisiones:
        getComisiones(){
            axios.get('../api/comisiones').then( response =>{
                this.mostrarCarga4 = true;
                this.comisiones = response.data;
                if (this.tableComisiones != null){
                    this.tableComisiones.clear();
                    this.tableComisiones.destroy();
                }
                this.crearTablas('Comisiones');
            }).catch(e=> console.log(e))
        },
        async deleteComision(id,comision){
            const ok = await this.$refs.confirmation.show({
                title: 'Borrar Comisión',
                message: `¿Está seguro/a que desea eliminar la Comisión '${comision}'? Esta acción no puede ser desecha.`,
                okButton: 'Eliminar',
                cancelButton: 'Volver'
            })
            if (ok) {
                axios.delete(`../api/comisiones/${id}`).then( response =>{
                    this.toast.success("Comisión eliminada con éxito!", {
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
                    this.recargarTabla('Comisiones');
                }).catch(e=> console.log(e))
            }
        },
        editDepto(comision){
            this.comisionEdit = comision;
            this.showEditDepartamento = true;
        },
    }
}
</script>