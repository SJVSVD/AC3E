<template>
    <transition name="modaleditar">
        <div name="modal">
            <div class="modal-mask">
                <div class="modal-wrapper">
                    <div class="modal-container-xs">
                        <div class="modal-header fw-bold" style="color: #444444;">
                            <slot name="header">
                                Editar Regiones
                            </slot>
                            <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
                        </div>
                        <div class="modal-body">
                        <slot name="body">
                            <div class="form-group">
                                <label for="region">Región: </label>
                                <br>
                                <input type="text" class= "form-control" v-model="region">
                                <div v-if="errors && errors.region" class="error">
                                    {{ errors.region[0] }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="Abreviatura">Abreviatura: </label>
                                <br>
                                <input type="text" class= "form-control" v-model="abreviatura">
                                <div v-if="errors && errors.abreviatura" class="error">
                                    {{ errors.abreviatura[0] }}
                                </div>
                            </div>
                            <br/>
                            <div class="form-group">
                                <label for="Capital">Capital: </label>
                                <br>
                                <input type="text" class= "form-control" v-model="capital">
                                <div v-if="errors && errors.capital" class="error">
                                    {{ errors.capital[0] }}
                                </div>
                            </div>
                            <br/>
                            <div class="form-group">
                                <label for="paises">País: </label>
                                <br>
                                <select class="form-select" v-model="paisS">
                                <option disabled value="">Seleccione un País</option>
                                <option v-for="pais in paises" v-bind:key="pais.id" v-bind:value="pais.id">
                                    {{ pais.pais }}
                                </option>
                                </select>
                            </div>
                            <br/>
                            <div class="modal-footer">
                                <slot name="footer">
                                    <a class="btn btn-continue" @click="editarRegion()" :disabled="buttonDisable">
                                        {{ buttonText }}
                                    </a>
                                </slot>
                            </div>
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
import modalconfirmacion from '../../../sistema/alerts/confirmationModal.vue'
import modalalerta from '../../../sistema/alerts/alertModal.vue'
import {mixin} from '../../../../../mixins.js'

export default {
    components: { modalconfirmacion, modalalerta },
    mixins: [mixin],
    data: () => ({
        buttonText:'Editar Región',
        buttonDisable: false,
        region:"",
        abreviatura:"",
        capital:"",
        id: "",
        paisS: "",
        paises: [],
        errors:[],
    }),
    props:{
        region1: Object,
    },
    created(){
        this.region = this.region1.region;
        this.abreviatura = this.region1.abreviatura;
        this.capital = this.region1.capital;
        this.paisS = this.region1.pais.id;

        this.id = this.region1.id;
        this.getPaises();
    },
    methods: {
        cerrarModal(){
            const elem = this.$refs.closeBtn;
            this.$emit('recarga');
            elem.click();
        },
        getPaises(){
            axios.get('../api/paises').then( response =>{
                this.paises = response.data;
            }).catch(e=> console.log(e))
        },
        async editarRegion() {
            const ok = await this.$refs.confirmation.show({
                title: 'Editar Región',
                message: `¿Está seguro/a que desea editar la región '${this.region}'? Esta acción no puede ser desecha.`,
                okButton: 'Editar',
                cancelButton: 'Volver'
            })
            if (ok) {
                let region2 = {
                    region: this.region,
                    abreviatura: this.abreviatura,
                    capital: this.capital,
                    idPais: this.paisS,
                }
                var id = this.id;
                axios.put(`../api/regiones/${id}`, region2).then((result) => {
                    this.buttonText = 'Guardando...';
                    this.buttonDisable = true;
                    this.toast.success("Región editada con éxito!", {
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
                    setTimeout(() => {this.cerrarModal();}, 1000);
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

