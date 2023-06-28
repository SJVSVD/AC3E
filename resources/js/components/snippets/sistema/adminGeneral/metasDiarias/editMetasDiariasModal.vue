<template>
    <transition name="modaleditar">
        <div name="modal">
            <div class="modal-mask">
                <div class="modal-wrapper">
                    <div class="modal-container-xs">
                        <div class="modal-header fw-bold" style="color: #444444;">
                            <slot name="header">
                                Editar Lider
                            </slot>
                            <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
                        </div>
                        <div class="modal-body">
                            <slot name="body">
                            <div class="row">
                                <div class="col-8">
                                    <div class="form-group">
                                        <label for="">Lider: </label>
                                        <br>
                                        <select class="form-select" v-model="idUsuario" @change="getOneUsuario(idUsuario);">
                                            <option disabled value="">Seleccione un usuario</option>
                                            <option v-for="usuario in usuarios" v-bind:key="usuario.id" v-bind:value="usuario.id">
                                            {{ usuario.name }}
                                            </option>
                                        </select>
                                    </div>
                                    <div v-if="errors && errors.idUsuario" class="error">
                                    {{ errors.idUsuario[0] }}
                                    </div>
                                </div>
                                <div class="col-8">
                                    <div class="form-group">
                                        <label for="">Grupo: </label>
                                        <br>
                                        <select class="form-select" v-model="idGrupo" @change="getOneGrupo(idGrupo);">
                                            <option disabled value="">Seleccione un grupo</option>
                                            <option v-for="grupo in grupos" v-bind:key="grupo.id" v-bind:value="grupo.id">
                                                {{ grupo.nombre }}
                                            </option>
                                        </select>
                                    </div>
                                    <div v-if="errors && errors.idGrupo" class="error">
                                    {{ errors.idGrupo[0] }}
                                    </div>
                                </div>
                                </div>
                            </slot>
                        </div>
                        <div class="modal-footer">
                            <slot name="footer">
                                <button class="btn btn-continue" @click="editarLider()" :disabled="buttonDisable">
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
        buttonText:'Editar Lider',
        buttonDisable: false,
        id: null,
        idUsuario: null,
        idGrupo: null,
        usuario: null,
        grupo: null,
        errors:[],
        usuarios: [],
        grupos: [],
    }),
    props:{
        grupoLider: Object,
    },
    created(){
        this.id = this.grupoLider.id;
        this.idUsuario = this.grupoLider.idUsuario;
        this.idGrupo = this.grupoLider.idGrupo;
        this.getUsuarios();
        this.getGrupos();
        this.getOneUsuario(this.idUsuario);
        this.getOneGrupo(this.idGrupo);
    },
    methods: {
        cerrarModal(){
          const elem = this.$refs.closeBtn;
          this.$emit('recarga');
          elem.click();
        },
        getUsuarios(){
            axios.get('../api/usuarios').then( response =>{
                this.usuarios = response.data;
            }).catch(e=> console.log(e))
        },
        getGrupos(){
            axios.get('../api/grupos').then( response =>{
                this.grupos = response.data;
            }).catch(e=> console.log(e))
        },
        getOneUsuario(id){
            axios.get(`../api/usuarios/${id}`).then( response =>{
                this.usuario = response.data;
            }).catch(e=> console.log(e))
        },
        getOneGrupo(id){
            axios.get(`../api/grupos/${id}`).then( response =>{
                this.grupo = response.data;
            }).catch(e=> console.log(e))
        },
        async editarLider() {
            const ok = await this.$refs.confirmation.show({
                title: 'Editar Lider',
                message: `¿Está seguro/a que desea editar al lider '${this.usuario.name}' del grupo '${this.grupo.nombre}'? Esta acción no puede ser desecha.`,
                okButton: 'Editar',
                cancelButton: 'Volver'
            })
            if (ok) {
                let grupoLider2 = {
                    idUsuario: this.idUsuario,
                    idGrupo: this.idGrupo,
                }
                var id = this.id;
                axios.put(`../api/grupoLideres/${id}`, grupoLider2).then((result) => {
                    this.buttonText = 'Guardando...';
                    this.buttonDisable = true;
                    this.toast.success("Lider editada con éxito!", {
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

