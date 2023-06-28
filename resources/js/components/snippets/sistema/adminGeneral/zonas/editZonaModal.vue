<template>
    <transition name="modaleditar">
        <div name="modal">
            <div class="modal-mask">
                <div class="modal-wrapper">
                    <div class="modal-container-xs">
                        <div class="modal-header fw-bold" style="color: #444444;">
                            <slot name="header">
                                Editar Zona
                            </slot>
                            <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
                        </div>
                        <div class="modal-body">
                        <slot name="body">
                            <div class="form-group">
                                <label for="zona">Zona: </label>
                                <br>
                                <input type="text" class= "form-control" v-model="nombreZona">
                                <div v-if="errors && errors.nombre" class="error">
                                    {{ errors.nombre[0] }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="paises">País: </label>
                                <br>
                                <select class="form-select" v-model="idPais">
                                <option disabled value="">Seleccione un País</option>
                                <option v-for="pais in paises" v-bind:key="pais.id" v-bind:value="pais.id">
                                    {{ pais.pais }}
                                </option>
                                </select>
                                <div v-if="errors && errors.idPais" class="error">
                                    {{ errors.idPais[0] }}
                                </div>
                            </div>
                            <div class="modal-footer">
                                <slot name="footer">
                                    <a class="btn btn-continue" @click="editarZona()" :disabled="buttonDisable">
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
import modalconfirmacion from '../../alerts/confirmationModal.vue'
import modalalerta from '../../alerts/alertModal.vue'
import {mixin} from '../../../../../mixins.js'

export default {
    components: { modalconfirmacion, modalalerta },
    mixins: [mixin],
    data: () => ({
        buttonText:'Editar zona',
        buttonDisable: false,
        id: "",
        nombreZona:"",
        idPais: "",
        paises: [],
        errors:[],
    }),
    props:{
        zona1: Object,
    },
    created(){
        this.id = this.zona1.id;
        this.nombreZona = this.zona1.nombre;
        this.idPais = this.zona1.idPais;
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
        async editarZona() {
            const ok = await this.$refs.confirmation.show({
                title: 'Editar Zona',
                message: `¿Está seguro/a que desea editar la zona '${this.nombreZona}'? Esta acción no puede ser desecha.`,
                okButton: 'Editar',
                cancelButton: 'Volver'
            })
            if (ok) {
                let zona2 = {
                    nombre: this.nombreZona,
                    idPais: this.idPais,
                    idPaisZona: this.idPais+this.nombreZona,
                }
                var id = this.id;
                axios.put(`../api/zonas/${id}`, zona2).then((result) => {
                    this.buttonText = 'Guardando...';
                    this.buttonDisable = true;
                    this.toast.success("Zona editada con éxito!", {
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

