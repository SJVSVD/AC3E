<template>
    <transition name="modaleditar">
        <div name="modal">
            <div class="modal-mask">
                <div class="modal-wrapper">
                    <div class="modal-container-xs">
                        <div class="modal-header fw-bold" style="color: #444444;">
                            <slot name="header">
                                Editar Autorizadores
                            </slot>
                            <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
                        </div>
                        <div class="modal-body">
                        <slot name="body">
                            <div class="row">
                                <div class="col-6">
                                    <label>Autorizador 1:</label>
                                    <select class="form-select" v-model="idAutorizador1">
                                    <option disabled :value="null">Seleccione autorizador:</option>
                                    <option v-for="autorizador in searchAuth(autorizadores, factura['montoTotal'])" :key="autorizador.id" v-bind:value="autorizador.id">
                                        <label>{{ autorizador.usuario.name }}</label>
                                    </option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <label>Autorizador 2:</label>
                                    <select class="form-select" v-model="idAutorizador2">
                                    <option selected :value="null">Seleccione autorizador:</option>
                                    <option v-for="autorizador in searchAuth(autorizadores, factura['montoTotal'], factura['idAutorizador2'])" :key="autorizador.id" v-bind:value="autorizador.id">
                                        <label>{{ autorizador.usuario.name }}</label>
                                    </option>
                                    </select>
                                </div>
                            </div>
                        </slot>
                    </div>
                    <div class="modal-footer">
                        <slot name="footer">
                            <label class="font-xs">Este proceso no envía correo, por favor utilizar el botón de insistencia.</label>
                            <a class="btn btn-continue" @click="editarFactura()">
                                {{ buttonText }}
                            </a>
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
        buttonText:'Editar',
        idAutorizador1: null,
        nameAutorizador1: null,
        idAutorizador2: null,
        nameAutorizador2: null,
        autorizadores: null,
        idFactura: null,
        montoTotal: null,
        timer: null,
        id: null,
    }),
    props:{
        factura: Object,
    },
    created(){
        this.id = this.factura.id;
        this.idFactura = this.factura.idFactura;
        this.idAutorizador1 = this.factura.idAutorizador1;
        this.idAutorizador2 = this.factura.idAutorizador2;
        this.montoTotal = this.factura.montoTotal;
        this.getAutorizadores();
        this.getTimers();
    },
    methods: {
        cerrarModal(){
            const elem = this.$refs.closeBtn;
            this.$emit('recarga');
            elem.click();
        },
        getTimers(){
            let data = {
            identificadorEspecifico: this.idFactura,
            timerName: 'insistenciaFactura',
            sistema: 'Facturas',
            userID: this.userID,
            }
            axios.post(`api/usuarios/checkTimer`, data).then( response =>{
            this.timer = response.data;
            }).catch(e=> console.log(e))
        },
        getAutorizadores(){
            axios.get(`api/autorizadores`).then( response =>{
                this.autorizadores = response.data.filter((dato)=>dato.levelName == 'facturas');
            }).catch(e=> console.log(e))
        },
        searchAuth(autorizadores, montoTot, autorizador){
            var autor = autorizadores;
            if(autorizador != null){
                autor.filter((dato)=>dato.id == autorizador.id);
            }
            if(montoTot >= 100000){
                autor = autor.filter((dato)=>dato.level == true);
            }
            else{
                autor = autor;
            }
            return autor;
        },
        async editarFactura() {
            this.nameAutorizador1 = null;
            this.nameAutorizador2 = null;
            this.autorizadores.forEach(autorizador => {
                if(this.idAutorizador1 == autorizador['id']){
                    this.nameAutorizador1 = autorizador.usuario.name;
                }
                if(this.idAutorizador2 == autorizador['id']){
                    this.nameAutorizador2 = autorizador.usuario.name;
                }
            });
            if(this.nameAutorizador2 == null){
                this.nameAutorizador2 = "---";
            }
            const ok = await this.$refs.confirmation.show({
                title: 'Editar Factura',
                message: `¿Está seguro/a que desea editar el Autorizador 1 a '${this.nameAutorizador1}' y el Autorizador 2 a '${this.nameAutorizador2}'? Esta acción no puede ser desecha.`,
                okButton: 'Editar',
                cancelButton: 'Volver'
            })
            if (ok) {
                let factura = {
                    idFactura: this.idFactura,
                    idAutorizador1: this.idAutorizador1,
                    idAutorizador2: this.idAutorizador2,
                }
                var id = this.id;
                axios.put(`api/facturas/${id}`, factura).then((result) => {
                    this.buttonText = 'Guardando...';
                    this.toast.success("Factura editada con éxito!", {
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
                    // REINICIAR TIMER (eliminarlo):
                    if (this.timer != []){
                        var id = this.timer.id;
                        axios.delete(`api/timers/${id}`).then((result) => {
                        }).catch((e)=> {console.log(e)});
                    }
                    this.showInsistencia = true;
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