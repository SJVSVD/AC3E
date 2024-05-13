<template>
    <transition name="modaleditar">
        <div name="modal">
            <div class="modal-mask">
                <div class="modal-wrapper">
                    <div class="modal-container-xs">
                        <div class="modal-header fw-bold" style="color: #444444;">
                            <slot name="header">
                                Editar Rol
                            </slot>
                            <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
                        </div>
                        <div class="modal-body">
                        <slot name="body">
                            <div class="form-group">
                                <label for="name">Nombre: </label>
                                <br>
                                <input type="text" class= "form-control" v-model="name">
                                <div v-if="errors && errors.name" class="error">
                                    {{ errors.name[0] }}
                                </div>
                            </div>
                            <label for="">Permisos para este Rol: </label>
                            <br/>
                            <div v-for="permiso in permisos" :key="permiso.id" class="form-check">
                                <label class="form-check-label">
                                    <input type="checkbox" class="form-check-input" v-bind:id="permiso.id" v-bind:value="permiso.id" v-model="permissions">
                                        &nbsp;{{ permiso.name }}
                                </label>
                            </div>
                            <br/>
                            <div class="modal-footer">
                                <slot name="footer">
                                    <a class="btn btn-continue" @click="editarRol()" :disabled="buttonDisable">
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
import modalconfirmacion from '../../sistema/alerts/confirmationModal.vue'
import modalalerta from '../../sistema/alerts/alertModal.vue'
import {mixin} from '../../../../mixins.js'

export default {
    components: { modalconfirmacion, modalalerta },
    mixins: [mixin],
    data: () => ({
        buttonText:'Editar Rol',
        buttonDisable: false,
        name: "",
        id: "",
        errors:[],
        permisos: [],
        permissions:[],
        permisosRol: [],
        revision: false,
    }),
    props:{
        rol: Object,
    },
    created(){
        this.name = this.rol.name;
        this.id = this.rol.id;
        this.getPermisos();
        this.getPermisosRol(this.id);
    },
    methods: {
          // Cierra el modal y emite un evento de recarga.
        cerrarModal(){
          const elem = this.$refs.closeBtn;
          this.$emit('recarga');
          elem.click();
        },
        compararPermisos(){

            if(this.revision == false){
                this.permisos.forEach(element => {
                    this.permisosRol.forEach(element2 => {
                        if(element.name == element2){
                            this.permissions.push(element.id);
                        }
                    })
                });
            }

            this.revision = true;
        },
        getPermisosRol($id){
            axios.get(`api/getPermisosRol/${$id}`).then( response =>{
            this.permisosRol = response.data;
            this.compararPermisos()
            }).catch(e=> console.log(e))
        },
        getPermisos(){
            axios.get('api/permisos').then( response =>{
            this.permisos = response.data;
            }).catch(e=> console.log(e))
        },
          // Edita el registro despues de validar
        async editarRol() {
            const ok = await this.$refs.confirmation.show({
                title: 'Editar Rol',
                message: `¿Está seguro/a que desea editar al rol '${this.rol.name}'? Esta acción no puede ser desecha.`,
                okButton: 'Editar',
                cancelButton: 'Volver'
            })
            if (ok) {
                let rol = {
                    name: this.name,
                    permission: this.permissions
                }
                var id = this.id;
                axios.put(`api/roles/${id}`, rol).then((result) => {
                    this.buttonText = 'Guardando...';
                    this.buttonDisable = true;
                    this.toast.success("Rol editado con éxito!", {
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

