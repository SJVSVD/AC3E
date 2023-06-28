<template>
    <transition name="modaleditar">
        <div name="modal">
            <div class="modal-mask">
                <div class="modal-wrapper">
                    <div class="modal-container-xs">
                        <div class="modal-header fw-bold" style="color: #444444;">
                            <slot name="header">
                                Editar Autorizador
                            </slot>
                            <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
                        </div>
                        <div class="modal-body">
                            <slot name="body">
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                        <label for="identificador">Identificador: </label>
                                        <br>
                                        <input type="text" class= "form-control" v-model="autorizador.identificador">
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                        <label for="Autorizador">Autorizador: </label>
                                        <br>
                                        <select class="form-select" v-model="autorizador.idUsuario">
                                        <option disabled value="">Seleccione un autorizador</option>
                                        <option v-for="autorizador in autorizadores2" v-bind:key="autorizador.id" v-bind:value="autorizador.id">
                                            {{ autorizador.name }}
                                        </option>
                                        </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                        <label for="levelName">Level Name: </label>
                                        <br>
                                        <select class="form-select" v-model="autorizador.levelName">
                                        <option disabled value="">Seleccione un nivel</option>
                                        <option value="facturas">facturas</option>
                                        </select>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <br>
                                        <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="" id="flexCheckChecked" v-model="autorizador.level" >
                                        <label class="form-check-label" for="flexCheckChecked">
                                            Level
                                        </label>
                                        </div>
                                    </div>
                                </div>
                            </slot>
                        </div>
                        <div class="modal-footer">
                            <slot name="footer">
                                <button class="btn btn-continue" @click="editarAutorizador()" :disabled="buttonDisable">
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
        autorizador: {
            identificador: null,
            idUsuario: '',
            levelName: '',
            level: null,
        },
        autorizadores2: [],
        buttonText:'Editar Autorizador',
        buttonDisable: false,
        errors:[],
        id: "",
    }),
    props:{
        autorizador1: Object,
    },
    created(){
        this.getAutorizadores();
        this.autorizador.identificador = this.autorizador1.identificador;
        this.autorizador.idUsuario = this.autorizador1.idUsuario;
        this.autorizador.levelName = this.autorizador1.levelName;
        this.autorizador.level = this.autorizador1.level;

        this.id = this.autorizador1.id;
      },
      methods: {
        cerrarModal(){
            const elem = this.$refs.closeBtn;
            this.$emit('recarga');
            elem.click();
        },
        getAutorizadores(){
            axios.get("../api/getAutorizadores").then( response =>{
                this.autorizadores2 = response.data;
            }).catch(e=> console.log(e))
        },
        async editarAutorizador() {
            const ok = await this.$refs.confirmation.show({
                title: 'Editar Autorizador',
                message: `¿Está seguro/a que desea editar este autorizador? Esta acción no puede ser desecha.`,
                okButton: 'Editar',
                cancelButton: 'Volver'
            })
            if (ok) {
                let autorizador = {
                    identificador: this.autorizador.identificador,
                    idUsuario: this.autorizador.idUsuario,
                    levelName: this.autorizador.levelName,
                    level: this.autorizador.level,
                };        
                var id = this.id;
                axios.put(`../api/autorizadores/${id}`, autorizador).then((result) => {
                    this.buttonText = 'Guardando...';
                    this.buttonDisable = true;
                    this.toast.success("Área editada con éxito!", {
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

