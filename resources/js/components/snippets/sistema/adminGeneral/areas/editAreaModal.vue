<template>
    <transition name="modaleditar">
        <div name="modal">
            <div class="modal-mask">
                <div class="modal-wrapper">
                    <div class="modal-container-xs">
                        <div class="modal-header fw-bold" style="color: #444444;">
                            <slot name="header">
                                Editar Area
                            </slot>
                            <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
                        </div>
                        <div class="modal-body">
                        <slot name="body">
                            <div class="form-group">
                                <label for="area">Area: </label>
                                <br>
                                <input type="text" class= "form-control" v-model="area">
                                <div v-if="errors && errors.area" class="error">
                                    {{ errors.area[0] }}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="departamentos">Departamento: </label>
                                <br>
                                <select class="form-select" v-model="idDepartamento">
                                    <option disabled value="">Seleccione un departamento</option>
                                    <option v-for="departamento in departamentos" v-bind:key="departamento.id" v-bind:value="departamento.id">
                                    {{ departamento.departamento }}
                                    </option>
                                </select>
                                <div  v-if="errors && errors.idDepartamento" class="error">
                                    {{ errors.idDepartamento[0] }}
                                </div>
                            </div>
                            <br/>
                            <div class="modal-footer">
                                <slot name="footer">
                                    <button class="btn btn-continue" @click="editarDepto()" :disabled="buttonDisable">
                                        {{ buttonText }}
                                    </button>
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
        buttonText:'Editar Área',
        buttonDisable: false,
        area:"",
        idDepartamento:"",
        id: "",
        departamentos: [],
        errors:[],
    }),
    props:{
        area1: Object,
    },
    created(){
        this.idDepartamento = this.area1.departamento.id;
        this.area = this.area1.area;

        this.id = this.area1.id;
        this.getDepartamentos();
      },
      methods: {
        cerrarModal(){
            const elem = this.$refs.closeBtn;
            this.$emit('recarga');
            elem.click();
        },
        getDepartamentos(){
            axios.get('../api/departamentos').then( response =>{
                this.departamentos = response.data;
            }).catch(e=> console.log(e))
        },
        async editarDepto() {
            const ok = await this.$refs.confirmation.show({
                title: 'Editar Área',
                message: `¿Está seguro/a que desea editar el área '${this.area}'? Esta acción no puede ser desecha.`,
                okButton: 'Editar',
                cancelButton: 'Volver'
            })
            if (ok) {
                let area2 = {
                    area: this.area,
                    idDepartamento: this.idDepartamento,
                }
                var id = this.id;
                axios.put(`../api/areas/${id}`, area2).then((result) => {
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

