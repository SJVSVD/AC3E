<template>
    <div class="row">
        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
            <div class="card overflow-hidden" style="height: 160px;">
                <!-- <div class="card-body p-3 text-white text-shadow-black background-size" style="background-image:linear-gradient(rgba(0,0,0,0.2), rgba(0,0,0,0.2)), url('https://wallpaperaccess.com/full/5816762.jpg');">
                    <div class="row">
                        <h6 class="fw-bolder text-white">Cuentas Activas Recientes</h6>
                        <div class="text-white fs-7" v-for="usuarioActivo in usuariosActivos" :key="usuarioActivo.id">
                            <span class="dot"></span>
                            {{ usuarioActivo.name }}
                        </div>
                    </div>
                </div> -->
                <div class="card-body p-3 text-white  background-size" style="background-color: #4d4d4d ; min-height: 160px;">
                    <div class="row">
                        <div class="col-12">
                            <div class="numbers" style="min-height: 80px; text-align: center; margin-top: 40px;">
                                <p class="text-sm mb-0 text-uppercase text-white fw-bold">Welcome</p>
                                <h5 style="color: #ffff;" class="fw-bolder">
                                    {{ userName }}
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
            <div class="card overflow-hidden" style="height: 160px;">
                <div class="card-body p-3 text-white  background-size" style="background-color: #4d4d4d; min-height: 160px;">
                    <div class="row">
                        <div class="col-12">
                            <div class="numbers" style="min-height: 80px; text-align: center; margin-top: 40px;">
                                <p class="text-sm mb-0 text-uppercase text-white fw-bold">Current date and time</p>
                                <h5 style="color: orange;" class="fw-bolder">
                                    {{ time }}
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-4 col-sm-6 mb-xl-0 mb-4">
            <div class="card overflow-hidden" style="height: 160px;">
                <div class="card-body p-3 text-white background-size" style="background-image: url('/img/AC3EGif (2).gif'); transform: scale(3); background-size: contain; background-position: center; min-height: 160px;">
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-4">
        <div class="col-lg-8">
            <div class="card z-index-2 p-0" style="min-height: 200px; max-height: 650px;">
                <div class="table-responsive p-4">
                    <div v-show="mostrarCarga" class="loader-sm"></div>
                    <table v-show="mostrarTabla" class="table align-items-center mb-0" id="myTableRegistros">
                        <thead>
                            <tr style="color: black">
                                <th style="min-width: 16px;"></th>
                                <th class="text-uppercase text-xs font-weight-bolder">Creation Date</th>
                                <th class="text-uppercase text-xs font-weight-bolder">Module</th>
                                <th class="text-uppercase text-xs font-weight-bolder">User</th>
                                <th class="text-uppercase text-xs font-weight-bolder">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(registro, index) in registros" :key="index">
                                <td></td>
                                <td>{{ thisDate(registro.created_at,true) }}</td>
                                <td>{{ registro.modulo }}</td>
                                <td>{{ registro.usuario.name }}</td>
                                <td v-if="registro.modulo == 'isiPublication'"><a class="btn btn-success btn-xs" title="Details" @click="verIsiPublication(registro)"><i class="fa-regular fa-eye"></i></a></td>
                                <td v-else>---</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card z-index-2 p-0" style="min-height: 200px; max-height: 650px;">
                <div class="row p-3">
                    <div class="col-6">
                        <a class="btn btn-primary" v-if="is('Administrador')" @click="exportConsolidado()"><i class="fa fa-fw fa-lg fa-solid fa-download"></i> Exportar Consolidado</a>
                    </div>
                    <div class="col-6">
                        <a class="btn btn-primary" @click="exportIndividual()"><i class="fa fa-fw fa-lg fa-solid fa-download"></i> Exportar Planilla Individual</a>
                    </div>
                </div>
            </div>
        </div>
        <modalver v-bind:isiPublication1="isiPublication" v-if="showDetailsIsi" @close="showDetailsIsi = false"></modalver>
    </div>
</template>

<script>
import axios from 'axios'
import modalconfirmacion from './snippets/sistema/alerts/confirmationModal.vue'
import modalver from './snippets/sistema/isiPublications/detailsIsiPublicationModal.vue'
import modalalerta from './snippets/sistema/alerts/alertModal.vue'
import {mixin} from '../mixins.js'

export default {
    components: { modalconfirmacion, modalalerta,modalver },
    mixins: [mixin],
    data() {
        return {
            cantidadRegistros: 10,
            registros: [],
            CLPRates: null,
            interval: null,
            time: null,
            weather: null,
            coords: null,
            usuariosActivos: [],
            mostrarTabla: false,
            mostrarCarga: true,
            isiPublication: '',
            showDetailsIsi: false,
        }
    },
    beforeDestroy() {
        clearInterval(this.interval);
    },
    created() {
        this.getUsuariosActivos();
        this.interval = setInterval(() => {
            options = {
                year: 'numeric', month: 'numeric', day: 'numeric',
                hour: 'numeric', minute: 'numeric', second: 'numeric',
                hour12: false,
            };
            this.time = Intl.DateTimeFormat(navigator.language, options).format()
        }, 1000);
    },
    mounted(){
        this.getRegistros(this.cantidadRegistros);
    },
    methods: {
        verIsiPublication(isiPublication){
            this.isiPublication = isiPublication;
            this.showDetailsIsi = true;
        },
        getRegistros(cantidad){
            axios.get(`api/getRegistros/${cantidad}`).then( response =>{
                this.registros = response.data;
                if (this.table != null){
                    this.table.clear();
                    this.table.destroy();
                }
                this.crearTabla('#myTableRegistros');
                this.table.buttons().remove();
            }).catch(e=> console.log(e))
        },
        exportConsolidado(){
            var data = {
                userID: this.userID,
            };
            axios({
                url: `api/exportConsolidado`, data,
                method: 'POST',
                responseType: 'arraybuffer',
            }).then((response) => {
                let blob = new Blob([response.data], {
                    type: 'application/xlsx'
                })
                let link = document.createElement('a')
                link.href = window.URL.createObjectURL(blob)
                link.download = 'Consolidado.xlsx'
                link.click()
            })
        },
        exportIndividual(){
            var data = {
                userID: this.userID,
            };
            axios({
                url: `api/exportIndividual`, data,
                method: 'POST',
                responseType: 'arraybuffer',
            }).then((response) => {
                let blob = new Blob([response.data], {
                    type: 'application/xlsx'
                })
                let link = document.createElement('a')
                link.href = window.URL.createObjectURL(blob)
                link.download = 'PlanillaIndividual.xlsx'
                link.click()
            })
        },
        carouselInterval(){
            $('.carousel').carousel({
                interval: 3000
            });
        },
        getUsuariosActivos(){
            axios.get('api/usuarios').then( response =>{
                this.usuariosActivos = response.data;
            }).catch(e=> console.log(e))
        },
    }
}
</script>