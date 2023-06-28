<template>
    <transition name="modaleditar">
        <div name="modal">
            <div class="modal-mask">
                <div class="modal-wrapper">
                    <div class="modal-container-xs">
                        <div class="modal-header fw-bold" style="color: #444444;">
                            <slot name="header">
                                Editar Tipo de Usuario
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
                            </slot>
                        </div>
                        <div class="modal-footer">
                            <slot name="footer">
                                <button class="btn btn-continue" @click="editarTipoUsuario()" :disabled="buttonDisable">
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
        buttonText:'Editar Tipo de Usuario',
        buttonDisable: false,
        id: null,
        nombre: null,
        errors:[],
    }),
    props:{
        tipoUsuario: Object,
    },
    created(){
        this.id = this.tipoUsuario.id;
        this.nombre = this.tipoUsuario.nombre;
    },
    methods: {
        cerrarModal(){
          const elem = this.$refs.closeBtn;
          this.$emit('recarga');
          elem.click();
        },
        async editarTipoUsuario() {
            const ok = await this.$refs.confirmation.show({
                title: 'Editar Tipo de Usuario',
                message: `¿Está seguro/a que desea editar al tipo de usuario '${this.nombre}'? Esta acción no puede ser desecha.`,
                okButton: 'Editar',
                cancelButton: 'Volver'
            })
            if (ok) {
                let tipoUsuario2 = {
                    nombre: this.nombre,
                }
                var id = this.id;
                axios.put(`../api/tiposUsuario/${id}`, tipoUsuario2).then((result) => {
                    this.buttonText = 'Guardando...';
                    this.buttonDisable = true;
                    this.toast.success("Tipo de Usuario editado con éxito!", {
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

