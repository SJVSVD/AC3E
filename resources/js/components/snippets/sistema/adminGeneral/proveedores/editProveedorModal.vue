<template>
    <transition name="modaleditar">
        <div name="modal">
            <div class="modal-mask">
                <div class="modal-wrapper">
                    <div class="modal-container-xs">
                        <div class="modal-header fw-bold" style="color: #444444;">
                            <slot name="header">
                                Editar Proveedor
                            </slot>
                            <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
                        </div>
                        <div class="modal-body">
                            <slot name="body">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="rut">Rut: </label>
                                            <input type="rut" class= "form-control" v-model="rut">
                                        </div>
                                        <div v-if="errors && errors.rut" class="error">
                                            {{ errors.rut[0] }}
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                            <label for="codSocioNegocio">Código Socio de Negocios: </label>
                                            <input type="codSocioNegocio" class= "form-control" v-model="codSocioNegocio">
                                        </div>
                                        <div v-if="errors && errors.codSocioNegocio" class="error">
                                            {{ errors.codSocioNegocio[0] }}
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="razonSocial">Razón Social: </label>
                                            <input type="razonSocial" class= "form-control" v-model="razonSocial">
                                        </div>
                                        <div v-if="errors && errors.razonSocial" class="error">
                                            {{ errors.razonSocial[0] }}
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="">Autorizador 1: </label>
                                            <br>
                                            <select class="form-select" v-model="idAuthAso1">
                                            <option disabled value="">Seleccione un usuario</option>
                                            <option v-for="autorizador in autorizadores" v-bind:key="autorizador.id" v-bind:value="autorizador.id">
                                            {{ autorizador.usuario.name }}
                                            </option>
                                            </select>
                                        </div>
                                        <div v-if="errors && errors.idAuthAso1" class="error">
                                            {{ errors.idAuthAso1[0] }}
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="">Autorizador 2: </label>
                                            <br>
                                            <select class="form-select" v-model="idAuthAso2">
                                            <option disabled value="">Seleccione un usuario</option>
                                            <option v-for="autorizador in autorizadores" v-bind:key="autorizador.id" v-bind:value="autorizador.id">
                                            {{ autorizador.usuario.name }}
                                            </option>
                                            </select>
                                        </div>
                                        <div v-if="errors && errors.idAuthAso2" class="error">
                                            {{ errors.idAuthAso2[0] }}
                                        </div>
                                    </div>
                                </div>
                            </slot>
                            <div class="modal-footer">
                                <slot name="footer">
                                    <a class="btn btn-continue" @click="editarProveedor()" :disabled="buttonDisable">
                                        {{ buttonText }}
                                    </a>
                                </slot>
                            </div>
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
        buttonText:'Editar Proveedor',
        buttonDisable: false,
        rut: null,
        razonSocial: null,
        codSocioNegocio: null,
        idAuthAso1: null,
        idAuthAso2: null,
        errors: null,
        autorizadores: null,
    }),
    props:{
        proveedor1: Object,
    },
    created(){
        this.rut = this.proveedor1.rut;
        this.razonSocial = this.proveedor1.razonSocial;
        this.codSocioNegocio = this.proveedor1.codSocioNegocio;
        this.idAuthAso1 = this.proveedor1.idAuthAso1;
        this.idAuthAso2 = this.proveedor1.idAuthAso2;
        this.getAutorizadores();
    },
    methods: {
        cerrarModal(){
          const elem = this.$refs.closeBtn;
          this.$emit('recarga');
          elem.click();
        },
        getAutorizadores(){
            axios.get("../api/autorizadores").then( response =>{
                this.autorizadores = response.data;
            }).catch(e=> console.log(e))
        },
        async editarProveedor() {
            const ok = await this.$refs.confirmation.show({
                title: 'Editar proveedor',
                message: `¿Está seguro/a que desea editar los datos del proveedor con rut '${this.rut}' con la razón social '${this.razonSocial}'? Esta acción no puede ser desecha.`,
                okButton: 'Editar',
                cancelButton: 'Volver'
            })
            if (ok) {
                let proveedor = {
                    rut : this.rut,
                    razonSocial :this.razonSocial,
                    codSocioNegocio : this.codSocioNegocio,
                    idAuthAso1 : this.idAuthAso1,
                    idAuthAso2 : this.idAuthAso2,
                }
                var id = this.proveedor1.id;
                axios.put(`../api/proveedores/${id}`, proveedor).then((result) => {
                    this.buttonText = 'Guardando...';
                    this.buttonDisable = true;
                    this.toast.success("Datos del proveedor editados con éxito!", {
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

