<template>
    <transition name="modaleditar">
        <div name="modal">
            <div class="modal-mask">
                <div class="modal-wrapper">
                    <div class="modal-container-xs">
                        <div class="modal-header fw-bold" style="color: #444444;">
                            <slot name="header">
                                Editar Nacionalidad
                            </slot>
                            <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
                        </div>
                        <div class="modal-body">
                        <slot name="body">
                            <div class="form-group">
                                <label for="name">Nacionalidad: </label>
                                <br>
                                <input type="text" class= "form-control" v-model="nacionalidad">
                                <div v-if="errors && errors.nacionalidad" class="error">
                                    {{ errors.nacionalidad[0] }}
                                </div>
                            </div>
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
                                    <a class="btn btn-continue" @click="editarNacionalidad()" :disabled="buttonDisable">
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
        buttonText:'Editar nacionalidad',
        buttonDisable: false,
        id: "",
        nacionalidad: "",
        paisS: "",
        paises: [],
        errors:[],
    }),
    props:{
        nacionalidad1: Object,
    },
    created(){
        this.nacionalidad = this.nacionalidad1.nacionalidad;
        this.id = this.nacionalidad1.id;
        this.paisS = this.nacionalidad1.pais.id;
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
        async editarNacionalidad() {
            const ok = await this.$refs.confirmation.show({
                title: 'Editar Nacionalidad',
                message: `¿Está seguro/a que desea editar la nacionalidad '${this.nacionalidad}'? Esta acción no puede ser desecha.`,
                okButton: 'Editar',
                cancelButton: 'Volver'
            })
            if (ok) {
                let nacionali = {
                    nacionalidad: this.nacionalidad,
                    idPais: this.paisS
                }
                var id = this.id;
                axios.put(`../api/nacionalidades/${id}`, nacionali).then((result) => {
                    this.buttonText = 'Guardando...';
                    this.buttonDisable = true;
                    this.toast.success("Nacionalidad editada con éxito!", {
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

