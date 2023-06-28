<template>
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="row pb-0 p-4">
                    <div class="col-12">
                        <div class="d-flex justify-content-end">
                            <a class="btn btn-spacing btn-closed" @click="obtener_Tabla_SAP('sap_productos')">Obtener Productos SAP</a>
                            &nbsp;
                            <a class="btn btn-spacing btn-closed" @click="obtener_Tabla_SAP('tiendas')">Obtener Tiendas SAP</a>
                            &nbsp;
                            <a class="btn btn-spacing btn-wine" @click="createSuperAdmin()">Super-Administrador</a>
                            &nbsp;
                        </div>
                    </div>
                </div>
                <hr size="4" class="separador">
                <div class="card-body px-0 pt-0 pb-2" style="min-height: 400px">
                    <div class="container">
                        <label class="m-0" style="font-size: 16px;"> Display Table Data:</label>
                        <div>
                            <code>
                                {{ JSON.stringify(tableData) }}
                            </code>
                        </div>
                        <hr size="4" class="separador">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-4 align-left text-left">
                    <a class="btn btn-primary btn-sm" @click="descargarPdf()"><i class="fa fa-fw fa-edit"></i> Generar PDF</a>
                </div>
            </div>
            <modalconfirmacion ref="confirmation"></modalconfirmacion>
            <modalalerta ref="alert"></modalalerta>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import modalconfirmacion from '../../snippets/sistema/alerts/confirmationModal.vue'
import modalalerta from '../../snippets/sistema/alerts/alertModal.vue'
import {mixin} from '../../../mixins.js'

export default {
    components: { modalconfirmacion, modalalerta },
    mixins: [mixin],
    data(){
        return{
            tableData: null,
        }
    },
    created(){
    },
    methods: {
        // NOMBRES TABLAS:
        // sap_productos
        // tiendas
        // mts_metas_diarias
        // sap_vts_detalle
        // metas_diarias
        // contador_personas
        obtener_Tabla_SAP($tabla){
            axios.get(`api/sap/getTabla/${$tabla}`).then( response =>{
                this.tableData = response.data;
            }).catch(e=> console.log(e))
        },
        descargarPdf(){
            axios({
                url: 'api/pdf',
                method: 'GET',
                responseType: 'arraybuffer',
            }).then((response) => {
                let blob = new Blob([response.data], {
                        type: 'application/pdf'
                    })
                    let link = document.createElement('a')
                    link.href = window.URL.createObjectURL(blob)
                    link.download = 'mi-archivo.pdf'
                    link.click()
            });
        },
        saveExcelUser(){
            var $importForm = $('#importForm')
            var data = new FormData(importForm)
            axios.post('api/usersImport', data)
                .then((res) => {
                    alert('Los datos se han subido con exito !')
                })
        },
        createSuperAdmin(){
            axios.post('api/usuarios/superadmin').then( response =>{
                this.toast.success("Super Administrador actualizado con éxito!", {
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
        },
    }
}
</script>