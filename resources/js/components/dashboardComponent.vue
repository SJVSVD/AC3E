<template>
    <div class="row">
        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
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
                <div class="card-body p-3 text-white text-shadow-black background-size" style="background-image:linear-gradient(rgba(0,0,0,0.2), rgba(0,0,0,0.2)), url('https://fondosmil.com/fondo/10790.jpg'); min-height: 160px;">
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

        <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
            <div class="card overflow-hidden" style="height: 160px;">
                <div class="card-body p-3 text-white text-shadow-black background-size" style="background-image:linear-gradient(rgba(0,0,0,0.2), rgba(0,0,0,0.2)), url('https://fondosmil.com/fondo/10790.jpg'); min-height: 160px;">
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
    </div>
</template>

<script>
import axios from 'axios'
import modalconfirmacion from './snippets/sistema/alerts/confirmationModal.vue'
import modalalerta from './snippets/sistema/alerts/alertModal.vue'
import {mixin} from '../mixins.js'

export default {
    components: { modalconfirmacion, modalalerta },
    mixins: [mixin],
    data() {
        return {
            CLPRates: null,
            interval: null,
            time: null,
            weather: null,
            coords: null,
            usuariosActivos: [],
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
        this.carouselInterval();
    },
    methods: {
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