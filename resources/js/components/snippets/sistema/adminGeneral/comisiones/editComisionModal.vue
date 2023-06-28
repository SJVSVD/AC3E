<template>
    <transition name="modaleditar">
        <div name="modal">
            <div class="modal-mask">
                <div class="modal-wrapper">
                    <div class="modal-container-s">
                        <div class="modal-header fw-bold" style="color: #444444;">
                            <slot name="header">
                                Editar Comisión
                            </slot>
                            <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
                        </div>
                        <div class="modal-body">
                            <slot name="body">
                                <div class="row">
                                <div class="col-4">
                                    <div class="form-group">
                                    <label for="">Estado: </label>
                                    <br>
                                    <select class="form-select" v-model="estado">
                                    <option disabled value="">Seleccione un estado</option>
                                    <option :value="true">Activo</option>
                                    <option :value="false">Inactivo</option>
                                    </select>
                                    </div>
                                    <div v-if="errors && errors.estado" class="error">
                                        {{ errors.estado[0] }}
                                    </div>
                                </div>
                                <div class="col-8">
                                    <div class="form-group">
                                        <label for="">Usuario Responsable: </label>
                                        <br>
                                        <select class="form-select" v-model="idUsuario">
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
                                <div class="col-3">
                                    <div class="form-group">
                                    <label for="rut">Rut:</label>
                                    <br>
                                    <input type="text" @keyup="checkRut()" class="form-control" v-model="rut">
                                    <div v-if="errors && errors.rut" class="error">
                                        {{ errors.rut[0] }}
                                    </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="form-group">
                                    <label for="correo">Correo Electrónico: </label>
                                    <br>
                                    <input type="email" class= "form-control" v-model="correo">
                                    <div  v-if="errors && errors.correo" class="error">
                                    {{ errors.correo[0] }}
                                    </div>
                                </div>
                                </div>
                                <div class="col-3">
                                    <div class="form-group">
                                    <label for="telefono">Teléfono: </label>
                                    <br>
                                    <input type="text" class= "form-control" v-model="telefono">
                                    <div v-if="errors && errors.telefono" class="error">
                                        {{ errors.telefono[0] }}
                                    </div>
                                    </div>
                                </div>
                                </div>
                            </slot>
                        </div>
                        <div class="modal-footer">
                            <slot name="footer">
                                <button class="btn btn-continue" @click="editarComision()" :disabled="buttonDisable">
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
        buttonText:'Editar Comisión',
        buttonDisable: false,
        id: null,
        estado: null,
        idUsuario: null,
        nombre: null,
        rut: null,
        correo: null,
        telefono: null,
        usuarios: [],
        errors:[],
    }),
    props:{
        comision1: Object,
    },
    created(){
        this.id = this.comision1.id;
        this.estado = this.comision1.estado;
        this.idUsuario = this.comision1.idUsuario;
        this.nombre = this.comision1.nombre;
        this.rut = this.comision1.rut;
        this.correo = this.comision1.correo;
        this.telefono = this.comision1.telefono;
        this.getUsuarios();
    },
    methods: {
        cerrarModal(){
          const elem = this.$refs.closeBtn;
          this.$emit('recarga');
          elem.click();
        },
        checkRut() {
            if (this.rut != ''){
              this.rut.replace('.','');
              // Despejar Puntos
              var valor = this.rut.replace('.','');

              // Despejar Guión
              valor = valor.replace('-','');
              
              // Aislar Cuerpo y Dígito Verificador
              var cuerpo = valor.slice(0,-1);
              var dv = valor.slice(-1).toUpperCase();
              
              // Formatear RUN
              this.rut = cuerpo + '-'+ dv
            }
        },
        getUsuarios(){
            axios.get('../api/usuarios').then( response =>{
                this.usuarios = response.data;
            }).catch(e=> console.log(e))
        },
        async editarComision() {
            const ok = await this.$refs.confirmation.show({
                title: 'Editar Comisión',
                message: `¿Está seguro/a que desea editar la Comisión '${this.comision}'? Esta acción no puede ser desecha.`,
                okButton: 'Editar',
                cancelButton: 'Volver'
            })
            if (ok) {
                let comision2 = {
                    id: this.id,
                    estado: this.estado,
                    idUsuario: this.idUsuario,
                    nombre: this.nombre,
                    rut: this.rut,
                    correo: this.correo,
                    telefono: this.telefono,
                }
                var id = this.id;
                axios.put(`../api/comisiones/${id}`, comision2).then((result) => {
                    this.buttonText = 'Guardando...';
                    this.buttonDisable = true;
                    this.toast.success("Comisión editada con éxito!", {
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

