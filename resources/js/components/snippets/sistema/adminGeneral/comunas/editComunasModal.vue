<template>
    <transition name="modaleditar">
        <div name="modal">
            <div class="modal-mask">
                <div class="modal-wrapper">
                    <div class="modal-container-xs">
                        <div class="modal-header fw-bold" style="color: #444444;">
                            <slot name="header">
                                Editar Comunas
                            </slot>
                            <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
                        </div>
                        <div class="modal-body">
                        <slot name="body">
                            <div class="form-group">
                                <label for="Comuna">Comuna: </label>
                                <br>
                                <input type="text" class= "form-control" v-model="comuna">
                                <div v-if="errors && errors.comuna" class="error">
                                    {{ errors.comuna[0] }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="Regiones">Región: </label>
                                <br>
                                <select class="form-select" v-model="regionS">
                                <option disabled value="">Seleccione una Región</option>
                                <option v-for="region in regiones" v-bind:key="region.id" v-bind:value="region.id">
                                    {{ region.region }}
                                </option>
                                </select>
                            </div>
                            <br/>
                            <div class="modal-footer">
                                <slot name="footer">
                                    <button class="btn btn-continue" @click="editarComuna()" :disabled="buttonDisable">
                                        {{ buttonText }}
                                    </button>
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
        buttonText:'Editar Comuna',
        buttonDisable: false,
        comuna:"",
        regionS:"",
        id: "",
        regiones: [],
        errors:[],
    }),
    props:{
        comuna1: Object,
    },
    created(){
        this.comuna = this.comuna1.comuna;
        this.regionS = this.comuna1.idRegion;

        this.id = this.comuna1.id;
        this.getRegiones();
    },
    methods: {
        cerrarModal(){
            const elem = this.$refs.closeBtn;
            this.$emit('recarga');
            elem.click();
        },
        getRegiones(){
            axios.get('../api/regiones').then( response =>{
                this.regiones = response.data;
            }).catch(e=> console.log(e))
        },
        async editarComuna() {
            const ok = await this.$refs.confirmation.show({
                title: 'Editar Comuna',
                message: `¿Está seguro/a que desea editar la comuna '${this.comuna}'? Esta acción no puede ser desecha.`,
                okButton: 'Editar',
                cancelButton: 'Volver'
            })
            if (ok) {
                let comuna2 = {
                    comuna: this.comuna,
                    idRegion: this.regionS,
                }
                var id = this.id;
                axios.put(`../api/comunas/${id}`, comuna2).then((result) => {
                    this.buttonText = 'Guardando...';
                    this.buttonDisable = true;
                    this.toast.success("Comuna editada con éxito!", {
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

