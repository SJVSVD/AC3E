<template>
    <transition name="modaleditar">
        <div name="modal">
            <div class="modal-mask">
                <div class="modal-wrapper">
                    <div class="modal-container-s">
                        <div class="modal-header fw-bold" style="color: #444444;">
                            <slot name="header">
                                Editar Meta Mensual
                            </slot>
                            <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
                        </div>
                        <div class="modal-body">
                            <slot name="body">
                                <div class="row">
                                    <div class="col-5">
                                    <div class="form-group">
                                        <label for="">Tienda: </label>
                                        <br>
                                        <select class="form-select" v-model="idTienda">
                                        <option disabled value="">Seleccione una Tienda</option>
                                        <option v-for="tienda in tiendas" v-bind:key="tienda.id" v-bind:value="tienda.id">
                                            {{ tienda.nombre }}
                                        </option>
                                        </select>
                                    </div>
                                    <div v-if="errors && errors.idTienda" class="error">
                                        {{ errors.idTienda[0] }}
                                    </div>
                                    </div>
                                    <div class="col-3">
                                    <div class="form-group">
                                        <label for="">Mes: </label>
                                        <br>
                                        <select class="form-select" v-model="mes">
                                        <option disabled value="">Seleccione un mes</option>
                                        <option v-for="mes in meses" v-bind:key="mes.value" v-bind:value="mes.value">
                                            {{ mes.nombre }}
                                        </option>
                                        </select>
                                    </div>
                                    <div v-if="errors && errors.mes" class="error">
                                        {{ errors.mes[0] }}
                                    </div>
                                    </div>
                                    <div class="col-2">
                                    <div class="form-group">
                                        <label for="">Año: </label>
                                        <br>
                                        <select class="form-select" v-model="año">
                                        <option disabled value="">Seleccione un año</option>
                                        <option v-for="año in años" v-bind:key="año.value" v-bind:value="año.value">
                                            {{ año.value }}
                                        </option>
                                        </select>
                                    </div>
                                    <div v-if="errors && errors.año" class="error">
                                        {{ errors.año[0] }}
                                    </div>
                                    </div>
                                    <div class="col-2">
                                    <div class="form-group">
                                        <label for="nombre">Meta Mensual: </label>
                                        <br>
                                        <input type="text" class= "form-control" v-model="meta_total"> 
                                    </div>
                                    <div v-if="errors && errors.meta_total" class="error">
                                        {{ errors.meta_total[0] }}
                                    </div>
                                    </div>
                                    <hr size="4" class="separador">
                                    <div class="col-4">
                                    <div class="form-group">
                                        <label for="nombre">Metas Carteras: </label>
                                        <br>
                                        <input type="text" class= "form-control" v-model="meta_carteras"> 
                                    </div>
                                    <div v-if="errors && errors.meta_carteras" class="error">
                                        {{ errors.meta_carteras[0] }}
                                    </div>
                                    </div>
                                    <div class="col-4">
                                    <div class="form-group">
                                        <label for="nombre">Metas Marroquinería: </label>
                                        <br>
                                        <input type="text" class= "form-control" v-model="meta_marroquineria"> 
                                    </div>
                                    <div v-if="errors && errors.meta_marroquineria" class="error">
                                        {{ errors.meta_marroquineria[0] }}
                                    </div>
                                    </div>
                                    <div class="col-4">
                                    <div class="form-group">
                                        <label for="nombre">Metas Calzado: </label>
                                        <br>
                                        <input type="text" class= "form-control" v-model="meta_calzado"> 
                                    </div>
                                    <div v-if="errors && errors.meta_calzado" class="error">
                                        {{ errors.meta_calzado[0] }}
                                    </div>
                                    </div>
                                    <div class="col-4">
                                    <div class="form-group">
                                        <label for="nombre">Metas Perfume: </label>
                                        <br>
                                        <input type="text" class= "form-control" v-model="meta_perfume"> 
                                    </div>
                                    <div v-if="errors && errors.meta_perfume" class="error">
                                        {{ errors.meta_perfume[0] }}
                                    </div>
                                    </div>
                                    <div class="col-4">
                                    <div class="form-group">
                                        <label for="nombre">Metas Vestimenta: </label>
                                        <br>
                                        <input type="text" class= "form-control" v-model="meta_vestimenta"> 
                                    </div>
                                    <div v-if="errors && errors.meta_vestimenta" class="error">
                                        {{ errors.meta_vestimenta[0] }}
                                    </div>
                                    </div>
                                </div>
                                </slot>
                        </div>
                        <div class="modal-footer">
                            <slot name="footer">
                                <button class="btn btn-continue" @click="editarMetaMensual()" :disabled="buttonDisable">
                                    {{ buttonText }}
                                </button>
                            </slot>
                        </div>
                    </div>
                </div>
                <modalconfirmacion ref="confirmation"></modalconfirmacion>
                <modalalerta ref="alert"></modalalerta>
            </div>
        </div>
    </transition>
</template>

<script>
import axios from 'axios'
import modalconfirmacion from '../../alerts/confirmationModal.vue'
import modalalerta from '../../alerts/alertModal.vue'
import {mixin} from '../../../../../mixins.js'

export default {
    components: { modalconfirmacion, modalalerta },
    mixins: [mixin],
    data: () => ({
        buttonText:'Editar Meta Mensual',
        buttonDisable: false,
        id: null,
        idTienda: null,
        meta_total: null,
        meta_carteras: null,
        meta_marroquineria: null,
        meta_calzado: null,
        meta_perfume: null,
        meta_vestimenta: null,
        año: null,
        mes: null,
        errors:[],
        usuarios: [],
        grupos: [],
        tiendas: [],
        años: [{value: 2000}, {value: 2001}, {value: 2002}, {value: 2003}, {value: 2004}, {value: 2005}, {value: 2006}, {value: 2007}, {value: 2008}, {value: 2009}, {value: 2010}, {value: 2011}, {value: 2012}, {value: 2013}, {value: 2014}, {value: 2015}, {value: 2016}, {value: 2017}, {value: 2018}, {value: 2019}, {value: 2020}, {value: 2021}, {value: 2022}, {value: 2023}, {value: 2024}, {value: 2025}, {value: 2026}, {value: 2027}, {value: 2028}, {value: 2029}, {value: 2030}, {value: 2031}, {value: 2032}, {value: 2033}, {value: 2034}, {value: 2035}, {value: 2036}, {value: 2037}, {value: 2038}, {value: 2039}, {value: 2040}, {value: 2041}, {value: 2042}, {value: 2043}, {value: 2044}, {value: 2045}, {value: 2046}, {value: 2047}, {value: 2048}, {value: 2049}, {value: 2050}],
        meses: [{value: 1, nombre: 'Enero'}, {value: 2, nombre: 'Febrero'}, {value: 3, nombre: 'Marzo'}, {value: 4, nombre: 'Abril'}, {value: 5, nombre: 'Mayo'}, {value: 6, nombre: 'Junio'}, {value: 7, nombre: 'Julio'}, {value: 8, nombre: 'Agosto'}, {value: 9, nombre: 'Septiembre'}, {value: 10, nombre: 'Octubre'}, {value: 11, nombre: 'Noviembre'}, {value: 12, nombre: 'Diciembre'}],
    }),
    props:{
        metaMensual1: Object,
    },
    created(){
        this.id = this.metaMensual1.id;
        this.idTienda = this.metaMensual1.idTienda;
        this.meta_total = this.metaMensual1.meta_total;
        this.meta_carteras = this.metaMensual1.meta_carteras;
        this.meta_marroquineria = this.metaMensual1.meta_marroquineria;
        this.meta_calzado = this.metaMensual1.meta_calzado;
        this.meta_perfume = this.metaMensual1.meta_perfume;
        this.meta_vestimenta = this.metaMensual1.meta_vestimenta;
        this.mes = this.metaMensual1.mes;
        this.año = this.metaMensual1.año;
        this.getTiendas();
    },
    methods: {
        cerrarModal(){
          const elem = this.$refs.closeBtn;
          this.$emit('recarga');
          elem.click();
        },
        getTiendas(){
            axios.get('../api/getTiendas').then( response =>{
                this.tiendas = response.data;
            }).catch(e=> console.log(e))
        },
        async editarMetaMensual() {
            const ok = await this.$refs.confirmation.show({
                title: 'Editar Meta Mensual',
                message: `¿Está seguro/a que desea editar esta meta mensual? Esta acción no puede ser desecha.`,
                okButton: 'Editar',
                cancelButton: 'Volver'
            })
            if (ok) {
                let metaMensual2 = {
                    idTienda: this.idTienda,
                    meta_total: this.meta_total,
                    meta_carteras: this.meta_carteras,
                    meta_marroquineria: this.meta_marroquineria,
                    meta_calzado: this.meta_calzado,
                    meta_perfume: this.meta_perfume,
                    meta_vestimenta: this.meta_vestimenta,
                    mes: this.mes,
                    año: this.año,
                }
                var id = this.id;
                axios.put(`../api/metasTienda/${id}`, metaMensual2).then((result) => {
                    this.buttonText = 'Guardando...';
                    this.buttonDisable = true;
                    this.toast.success("Meta mensual editada con éxito!", {
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
                    setTimeout(() => {this.cerrarModal();}, 1500);
                })
                .catch((error)=> {
                    if (error.response.status == 422){
                        this.errors = error.response.data.errors;
                        this.toast.warning('Existe un valor inválido.', {
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
                    }
                });
                
            }
        }
    }
}
</script>

