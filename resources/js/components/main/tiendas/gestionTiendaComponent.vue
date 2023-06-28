<template>
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4" style="padding-top: 20px;">
                <div class="card-body px-0 pt-0 pb-2" style="text-align: center; margin: auto; align-items: center; min-height: 400px">
                    <div class="m-auto text-center">
                        <h4>Menu Gestion Tienda</h4>
                        <div class="row mb-3">
                            <div v-if="can('Gestión Tienda_Solicitar Precios')" class="col-auto">
                                <a class="btn btn-dark-grey" href="/solicitar-precios" style=" width: 140px; height: 140px;">
                                    Solicitar Precios
                                    <p><i class="fa-solid fa-hand-holding-dollar fa-3x"></i></p>
                                </a>
                            </div>
                            <div v-if="can('Gestión Tienda_Conversion Ventas')" class="col-auto">
                                <a class="btn btn-dark-grey" @click="showConversionVentas = true" style="width: 140px; height: 140px;">
                                    Conversión de Ventas
                                    <p><i class="fa-solid fa-person-arrow-up-from-line fa-3x"></i></p>
                                </a>
                            </div>
                            <div v-if="can('Gestión Tienda_Cierre Cajas')" class="col-auto">
                                <a class="btn btn-dark-grey" href="/cierre-cajas" style="width: 140px; height: 140px;">
                                    Cierre de Cajas
                                    <p></p>
                                    <p><i class="fa-solid fa-store-slash fa-3x"></i></p>
                                </a>
                            </div>
                            <div v-if="can('Gestión Tienda_NCV')" class="col-auto">
                                <a class="btn btn-dark-grey" href="/ncv" style="width: 140px; height: 140px;">
                                    NCV
                                    <p></p>
                                    <p><i class="fa-regular fa-clipboard fa-3x"></i></p>
                                </a>
                            </div>
                            <div v-if="can('Gestión Tienda_Ventas por Vendedor')" class="col-auto">
                                <a class="btn btn-dark-grey" href="/ventasVendedor" style="width: 140px; height: 140px;">
                                    Ventas por Vendedor
                                    <p></p>
                                    <p><i class="fa-sharp fa-solid fa-user fa-2x"></i></p>
                                </a>
                            </div>
                        </div>
                        <div v-if="is('Administrador')" class="row mt-3">
                            <h4>Administrador</h4>
                            <div class="row mb-3">
                                <div v-if="can('Gestión Tienda_Crear Vendedor')"  class="col-auto" style="text-align: center; margin: auto; align-items: center;">
                                    <a class="btn btn-insist" @click="showCrearVendedor = true" style="width: 140px; height: 140px;">
                                        Crear Vendedor
                                        <p><i class="fa-solid fa-user-plus fa-3x"></i></p>
                                    </a>
                                </div>
                                <div v-if="can('Gestión Tienda_Subir Metas')"  class="col-auto" style="text-align: center; margin: auto; align-items: center;">
                                    <a class="btn btn-insist" @click="showSubirMetas = true" style="width: 140px; height: 140px;">
                                        Subir Metas
                                        <p><i class="fa-solid fa-flag-checkered fa-3x"></i></p>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <modalCrearVendedor v-if="showCrearVendedor" @close="showCrearVendedor = false"></modalCrearVendedor>
            <modalsubirmetas v-if="showSubirMetas" @close="showSubirMetas = false"></modalsubirmetas>
            <conversionVentasModal v-if="showConversionVentas" @close="showConversionVentas = false"></conversionVentasModal>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import modalCrearVendedor from '../../snippets/tiendas/gestionTienda/crearVendedor/crearVendedorModal.vue'
import modalsubirmetas from '../../snippets/tiendas/gestionTienda/metas/uploadMetasModal.vue'
import conversionVentasModal from '../../snippets/tiendas/gestionTienda/conversionVentas/conversionVentasModal.vue'
import {mixin} from '../../../mixins.js'

export default {
    components: { modalCrearVendedor, conversionVentasModal, modalsubirmetas },
    mixins: [mixin],
    data: () => ({
        showConversionVentas: null,
        showCrearVendedor: null,
        showSubirMetas: null,
    }),
    created(){
    },
    methods: {
    }
}
</script>