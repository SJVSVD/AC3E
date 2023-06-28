<template>
    <transition name="modaleditar">
        <div name="modal">
            <div class="modal-mask">
                <div class="modal-wrapper">
                    <div class="modal-container-xs">
                        <div class="modal-header fw-bold" style="color: #444444;">
                            <slot name="header">
                                Editar Sistemas de salud
                            </slot>
                            <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
                        </div>
                        <div class="modal-body">
                        <slot name="body">
                            <div class="form-group">
                                <label for="nombre">Nombre: </label>
                                <br>
                                <input type="text" class= "form-control" v-model="nombre">
                                <div v-if="errors && errors.nombre" class="error">
                                    {{ errors.nombre[0] }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="porcentaje">Porcentaje: </label>
                                <br>
                                <input type="text" class= "form-control" v-model="porcentaje">
                                <div v-if="errors && errors.porcentaje" class="error">
                                    {{ errors.porcentaje[0] }}
                                </div>
                            </div>
                            <br/>
                            <div class="modal-footer">
                                <slot name="footer">
                                    <a class="btn btn-continue" @click="editarSistema()" :disabled="buttonDisable">
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
        buttonText:'Editar Sistema',
        buttonDisable: false,
        nombre:"",
        porcentaje:"",
        id: "",
        errors:[],
    }),
    props:{
        sistema1: Object,
    },
    created(){
        this.nombre = this.sistema1.nombre;
        this.porcentaje = this.sistema1.porcentaje;

        this.id = this.sistema1.id;
    },
    methods: {
        cerrarModal(){
          const elem = this.$refs.closeBtn;
          this.$emit('recarga');
          elem.click();
        },
        async editarSistema() {
            const ok = await this.$refs.confirmation.show({
                title: 'Editar Sistema',
                message: `¿Está seguro/a que desea editar el sistema de salud '${this.nombre}'? Esta acción no puede ser desecha.`,
                okButton: 'Editar',
                cancelButton: 'Volver'
            })
            if (ok) {
                let sistema2 = {
                    nombre: this.nombre,
                    porcentaje: this.porcentaje,
                }
                var id = this.id;
                axios.put(`../api/sistemasSalud/${id}`, sistema2).then((result) => {
                    this.buttonText = 'Guardando...';
                    this.buttonDisable = true;
                    this.toast.success("Sistema editado con éxito!", {
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

