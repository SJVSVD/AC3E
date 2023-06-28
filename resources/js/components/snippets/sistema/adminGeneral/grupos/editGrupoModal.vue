<template>
    <transition name="modaleditar">
        <div name="modal">
            <div class="modal-mask">
                <div class="modal-wrapper">
                    <div class="modal-container-xs">
                        <div class="modal-header fw-bold" style="color: #444444;">
                            <slot name="header">
                                Editar Grupo
                            </slot>
                            <a class="btn btn-closed" @click="$emit('close')">X</a>
                        </div>
                        <div class="modal-body">
                            <slot name="body">
                                <div class="col-12">
                                <div class="form-group">
                                    <label for="nombre">Nombre: </label>
                                    <br>
                                    <input type="text" class= "form-control" v-model="nombre">
                                    <div v-if="errors && errors.nombre" class="error">
                                        {{ errors.nombre[0] }}
                                    </div>
                                </div>
                                </div>
                            </slot>
                        </div>
                        <div class="modal-footer">
                            <slot name="footer">
                                <button class="btn btn-continue" @click="editarGrupo()" :disabled="buttonDisable">
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
import modalconfirmacion from '../../../sistema/alerts/confirmationModal.vue'
import modalalerta from '../../../sistema/alerts/alertModal.vue'
import {mixin} from '../../../../../mixins.js'

export default {
    components: { modalconfirmacion, modalalerta },
    mixins: [mixin],
    data: () => ({
        buttonText:'Editar Grupo',
        buttonDisable: false,
        nombre: null,
        id: null,
        errors:[],
    }),
    props:{
        grupo: Object,
    },
    created(){
        this.nombre = this.grupo.nombre;
        this.id = this.grupo.id;
    },
    methods: {
        cerrarModal(){
          const elem = this.$refs.closeBtn;
          this.$emit('recarga');
          elem.click();
        },
        async editarGrupo() {
            const ok = await this.$refs.confirmation.show({
                title: 'Editar Grupo',
                message: `¿Está seguro/a que desea editar el grupo '${this.grupo}'? Esta acción no puede ser desecha.`,
                okButton: 'Editar',
                cancelButton: 'Volver'
            })
            if (ok) {
                let grupo2 = {
                    nombre: this.nombre,
                }
                var id = this.id;
                axios.put(`../api/grupos/${id}`, grupo2).then((result) => {
                    this.buttonText = 'Guardando...';
                    this.buttonDisable = true;
                    this.toast.success("Grupo Editado con éxito!", {
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

