<template>
    <transition name="modaleditar">
        <div name="modal">
            <div class="modal-mask">
                <div class="modal-wrapper">
                    <div class="modal-container">
                        <div class="modal-header fw-bold" style="color: #444444;">
                            <slot name="header">
                                Editar Sucursal
                            </slot>
                            <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
                        </div>
                        <div class="modal-body">
                            <slot name="body">
                                <div class="row">
                                    <div class="col-4">
                                        <div class="form-group">
                                            <label for="nombre">Nombre: </label>
                                            <br>
                                            <input type="nombre" class= "form-control" v-model="nombre">
                                        </div>
                                        <div v-if="errors && errors.nombre" class="error">
                                            {{ errors.nombre[0] }}
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="form-group">
                                            <label for="codigo">Código: </label>
                                            <br>
                                            <input type="codigo" class= "form-control" v-model="codigo">
                                            <br>
                                        </div>
                                        <div v-if="errors && errors.codigo" class="error">
                                            {{ errors.codigo[0] }}
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="form-group">
                                            <label for="bodega">Bodega: </label>
                                            <br>
                                            <input type="bodega" class= "form-control" v-model="bodega">
                                        </div>
                                        <div v-if="errors && errors.bodega" class="error">
                                            {{ errors.bodega[0] }}
                                        </div>
                                    </div>
                                    <div class="col-2">
                                        <div class="form-group">
                                            <label for="centroCosto">Centro de Costo: </label>
                                            <input type="centroCosto" class= "form-control" v-model="centroCosto">
                                        </div>
                                        <div v-if="errors && errors.centroCosto" class="error">
                                            {{ errors.centroCosto[0] }}
                                        </div>
                                    </div>
                                    <div class="col-2">
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
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                        <label for="empresaMarca">Empresa-Marca: </label>
                                        <br>
                                        <select class="form-select" v-model="idEmpresaMarca">
                                            <option disabled value="">Seleccione una empresa-marca</option>
                                            <option v-for="empresaMarca in empresasMarcas" v-bind:key="empresaMarca.id" v-bind:value="empresaMarca.id">
                                            {{ empresaMarca.nombre }}
                                            </option>
                                        </select>
                                        </div>
                                        <div v-if="errors && errors.idEmpresaMarca" class="error">
                                            {{ errors.idEmpresaMarca[0] }}
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                        <label for="tipoSucursal">Tipo Sucursal: </label>
                                        <br>
                                        <select class="form-select" v-model="idTipoSucursal">
                                            <option disabled value="">Seleccione un tipo de sucursal</option>
                                            <option v-for="tipoSucursal in tiposSucursal" v-bind:key="tipoSucursal.id" v-bind:value="tipoSucursal.id">
                                            {{ tipoSucursal.nombre }}
                                            </option>
                                        </select>
                                        </div>
                                        <div v-if="errors && errors.idTipoSucursal" class="error">
                                            {{ errors.idTipoSucursal[0] }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-group">
                                    <label for="direccion">Dirección: </label>
                                    <br>
                                    <input type="direccion" class= "form-control" v-model="direccion">
                                    <br>
                                    </div>
                                    <div v-if="errors && errors.direccion" class="error">
                                        {{ errors.direccion[0] }}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-3">
                                        <div class="form-group">
                                        <label for="pais">País: </label>
                                        <br>
                                        <select class="form-select" @change="getRegionesSelect($event.target.value)" v-model="idPais">
                                            <option disabled value="">Seleccione un País</option>
                                            <option v-for="pais in paises" v-bind:key="pais.id" v-bind:value="pais.id">
                                            {{ pais.pais }}
                                            </option>
                                        </select>
                                        </div>
                                        <div v-if="errors && errors.idPais" class="error">
                                            {{ errors.idPais[0] }}
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                        <label for="zona">Zona: </label>
                                        <br>
                                        <select class="form-select" v-model="idZona">
                                            <option disabled value="">Seleccione una zona</option>
                                            <option v-for="zona in zonas" v-bind:key="zona.id" v-bind:value="zona.id">
                                            {{ zona.nombre }}
                                            </option>
                                        </select>
                                        </div>
                                        <div v-if="errors && errors.idZona" class="error">
                                            {{ errors.idZona[0] }}
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                        <label for="region">Región: </label>
                                        <br>
                                        <select class="form-select" @change="getComunasSelect($event.target.value)" v-model="idRegion">
                                            <option disabled value="">Seleccione una Región</option>
                                            <option v-for="region in regionesSelect" v-bind:key="region.id" v-bind:value="region.id">
                                            {{ region.region }}
                                            </option>
                                        </select>
                                        </div>
                                        <div v-if="errors && errors.idRegion" class="error">
                                            {{ errors.idRegion[0] }}
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group">
                                        <label for="comuna">Comuna: </label>
                                        <br>
                                        <select class="form-select" v-model="idComuna">
                                            <option disabled value="">Seleccione una comuna</option>
                                            <option v-for="comuna in comunasSelect" v-bind:key="comuna.id" v-bind:value="comuna.id">
                                            {{ comuna.comuna }}
                                            </option>
                                        </select>
                                        </div>
                                        <div v-if="errors && errors.idComuna" class="error">
                                            {{ errors.idComuna[0] }}
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group">
                                        <label for="telefono">Teléfono: </label>
                                        <br>
                                        <input type="telefono" class= "form-control" v-model="telefono">
                                        </div>
                                        <div v-if="errors && errors.telefono" class="error">
                                            {{ errors.telefono[0] }}
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group">
                                        <label for="email">Correo Electrónico: </label>
                                        <br>
                                        <input type="email" class= "form-control" v-model="email">
                                        </div>
                                        <div v-if="errors && errors.email" class="error">
                                            {{ errors.email[0] }}
                                        </div>
                                    </div>
                                </div>
                            </slot>
                            <div class="modal-footer">
                                <slot name="footer">
                                    <a class="btn btn-continue" @click="editarSucursal()" :disabled="buttonDisable">
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
import modalconfirmacion from '../../../sistema/alerts/confirmationModal.vue'
import modalalerta from '../../../sistema/alerts/alertModal.vue'
import {mixin} from '../../../../../mixins.js'

export default {
    components: { modalconfirmacion, modalalerta },
    mixins: [mixin],
    data: () => ({
        buttonText:'Editar Sucursal',
        buttonDisable: false,
        idSucursal: null,
        codigo: null,
        centroCosto: null,
        bodega: null,
        nombre: null,
        estado: null,
        idEmpresaMarca: null,
        direccion: null,
        idZona: null,
        idPais: null,
        idRegion: null,
        idComuna: null,
        idTipoSucursal: null,
        telefono: null,
        email: null,
        errors:[],
        regionesSelect: [],
        comunasSelect: [],
        empresasMarcas: null,
        tiposSucursal: null,
        paises: null,
        zonas: null,
        regiones: null,
        comunas: null,
    }),
    props:{
        sucursal1: Object,
    },
    created(){
        this.getPaises();
        this.getZonas();
        this.getRegiones();
        this.getComunas();
        this.getTiposSucursal();
        this.getEmpresasMarcas();
        this.idPais = this.sucursal1.idPais;
        this.idSucursal = this.sucursal1.id;
        this.codigo = this.sucursal1.codigo;
        this.centroCosto = this.sucursal1.centroCosto;
        this.bodega = this.sucursal1.bodega;
        this.nombre = this.sucursal1.nombre;
        this.idRegion = this.sucursal1.idRegion;
        this.estado = this.sucursal1.estado;
        this.idEmpresaMarca = this.sucursal1.idEmpresaMarca;
        this.direccion = this.sucursal1.direccion;
        this.idZona = this.sucursal1.idZona;
        this.idComuna = this.sucursal1.idComuna;
        this.idTipoSucursal = this.sucursal1.idTipoSucursal;
        this.telefono = this.sucursal1.telefono;
        this.email = this.sucursal1.email;
    },
    methods: {
        cerrarModal(){
          const elem = this.$refs.closeBtn;
          this.$emit('recarga');
          elem.click();
        },
        getEmpresasMarcas(){
            axios.get('../api/empresasMarcas').then( response =>{
                this.empresasMarcas = response.data;
            }).catch(e=> console.log(e))
        },
        getTiposSucursal(){
            axios.get('../api/tiposSucursal').then( response =>{
                this.tiposSucursal = response.data;
            }).catch(e=> console.log(e))
        },
        getRegionesSelect(id){
            this.regiones.forEach(region => {
            if(region.idPais == id){
                this.regionesSelect.push(region);
            }
            });
        },
        getComunasSelect(id){
            this.comunas.forEach(comuna => {
            if(comuna.idRegion == id){
                this.comunasSelect.push(comuna);
            }
            });
        },
        getPaises(){
            axios.get('../api/paises').then( response =>{
            this.paises = response.data;
            }).catch(e=> console.log(e))
        },
        getZonas(){
            axios.get('../api/zonas').then( response =>{
            this.zonas = response.data;
            }).catch(e=> console.log(e))
        },
        getRegiones(){
            axios.get('../api/regiones').then( response =>{
            this.regiones = response.data;
            this.getRegionesSelect(this.idPais);
            }).catch(e=> console.log(e))
        },
        getComunas(){
            axios.get('../api/comunas').then( response =>{
            this.comunas = response.data;
            this.getComunasSelect(this.idRegion);
            }).catch(e=> console.log(e))
        },
        async editarSucursal() {
            const ok = await this.$refs.confirmation.show({
                title: 'Editar Sucursal',
                message: `¿Está seguro/a que desea editar la sucursal '${this.nombre}'? Esta acción no puede ser desecha.`,
                okButton: 'Editar',
                cancelButton: 'Volver'
            })
            if (ok) {
                let sucursal = {
                    codigo: this.codigo,
                    centroCosto: this.centroCosto,
                    bodega: this.bodega,
                    nombre: this.nombre,
                    estado: this.estado,
                    idEmpresaMarca: this.idEmpresaMarca,
                    direccion: this.direccion,
                    idZona: this.idZona,
                    idPais: this.idPais,
                    idRegion: this.idRegion,
                    idComuna: this.idComuna,
                    idTipoSucursal: this.idTipoSucursal,
                    telefono: this.telefono,
                    email: this.email,
                }
                var id = this.idSucursal;
                axios.put(`../api/sucursales/${id}`, sucursal).then((result) => {
                    this.buttonText = 'Guardando...';
                    this.buttonDisable = true;
                    this.toast.success("Sucursal editada con éxito!", {
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

