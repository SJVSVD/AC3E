<template>
    <transition name="modalcrear">
      <div name="modal">
        <div class="modal-mask">
            <div class="modal-wrapper">
              <div class="modal-container">
                <div class="modal-header fw-bold" style="color: #444444;">
                  <slot name="header">
                      Nueva Sucursal
                  </slot>
                  <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
                </div>
                <div class="modal-body">
                    <slot name="body">
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group">
                                    <label for="nombre">Nombre: </label>
                                    <input type="nombre" class= "form-control" v-model="sucursalCreate.nombre">
                                </div>
                                <div v-if="errors && errors.nombre" class="error">
                                    {{ errors.nombre[0] }}
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-group">
                                    <label for="codigo">Código: </label>
                                    <input type="codigo" class= "form-control" v-model="sucursalCreate.codigo">
                                </div>
                                <div v-if="errors && errors.codigo" class="error">
                                    {{ errors.codigo[0] }}
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-group">
                                    <label for="bodega">Bodega: </label>
                                    <input type="bodega" class= "form-control" v-model="sucursalCreate.bodega">
                                </div>
                                <div v-if="errors && errors.bodega" class="error">
                                    {{ errors.bodega[0] }}
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-group">
                                    <label for="centroCosto">Centro de Costo: </label>
                                    <input type="centroCosto" class= "form-control" v-model="sucursalCreate.centroCosto">
                                </div>
                                <div v-if="errors && errors.centroCosto" class="error">
                                    {{ errors.centroCosto[0] }}
                                </div>
                            </div>
                            <div class="col-2">
                                <div class="form-group">
                                <label for="">Estado: </label>
                                <select class="form-select" v-model="sucursalCreate.estado">
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
                                <select class="form-select" v-model="sucursalCreate.idEmpresaMarca">
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
                                <select class="form-select" v-model="sucursalCreate.idTipoSucursal">
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
                            <input type="direccion" class= "form-control" v-model="sucursalCreate.direccion">
                            </div>
                            <div v-if="errors && errors.direccion" class="error">
                                {{ errors.direccion[0] }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-3">
                                <div class="form-group">
                                <label for="pais">Pais: </label>
                                <select class="form-select" @change="getRegionesSelect($event.target.value)" v-model="sucursalCreate.idPais">
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
                                <select class="form-select" v-model="sucursalCreate.idZona">
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
                                <select class="form-select" @change="getComunasSelect($event.target.value)" v-model="sucursalCreate.idRegion">
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
                                <select class="form-select" v-model="sucursalCreate.idComuna">
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
                                <input type="telefono" class= "form-control" v-model="sucursalCreate.telefono">
                                </div>
                                <div v-if="errors && errors.telefono" class="error">
                                    {{ errors.telefono[0] }}
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                <label for="email">Correo Electrónico: </label>
                                <input type="email" class= "form-control" v-model="sucursalCreate.email">
                                </div>
                                <div v-if="errors && errors.email" class="error">
                                    {{ errors.email[0] }}
                                </div>
                            </div>
                        </div>
                    </slot>
                </div>
                <div class="modal-footer">
                    <slot name="footer">
                        <button class="btn btn-continue" @click="crearSucursal(sucursalCreate.nombre)" :disabled="buttonDisable">
                            {{ buttonText }}
                        </button>
                    </slot>
                </div>
                <modalconfirmacion ref="confirmation"></modalconfirmacion>
                <modalalerta ref="alert"></modalalerta>
            </div>
          </div>
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
        buttonText:'Crear Sucursal',
        buttonDisable: false,
        errors:[],
        sucursales: null,
        usuarios: null,
        sucursalCreate: {
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
        },
        regionesSelect: [],
        comunasSelect: [],
        empresasMarcas: null,
        tiposSucursal: null,
        paises: null,
        zonas: null,
        regiones: null,
        comunas: null,
      }),
      created(){
        this.getPaises();
        this.getZonas();
        this.getRegiones();
        this.getComunas();
        this.getTiposSucursal();
        this.getEmpresasMarcas();
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
            this.regionesSelect = []
            this.regiones.forEach(region => {
            if(region.idPais == id){
                this.regionesSelect.push(region);
            }
            });
        },
        getComunasSelect(id){
            this.comunasSelect = []
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
            }).catch(e=> console.log(e))
        },
        getComunas(){
            axios.get('../api/comunas').then( response =>{
            this.comunas = response.data;
            }).catch(e=> console.log(e))
        },
        async crearSucursal(nombreSucursal) {
            const ok = await this.$refs.confirmation.show({
                title: 'Crear Sucursal',
                message: `¿Está seguro/a que desea crear la sucursal '${nombreSucursal}'? Esta acción no puede ser desecha.`,
                okButton: 'Crear',
                cancelButton: 'Volver'
            })
            if (ok) {
                let sucursal = {
                    codigo: this.sucursalCreate.codigo,
                    centroCosto: this.sucursalCreate.centroCosto,
                    bodega: this.sucursalCreate.bodega,
                    nombre: this.sucursalCreate.nombre,
                    estado: this.sucursalCreate.estado,
                    idEmpresaMarca: this.sucursalCreate.idEmpresaMarca,
                    direccion: this.sucursalCreate.direccion,
                    idZona: this.sucursalCreate.idZona,
                    idPais: this.sucursalCreate.idPais,
                    idRegion: this.sucursalCreate.idRegion,
                    idComuna: this.sucursalCreate.idComuna,
                    idTipoSucursal: this.sucursalCreate.idTipoSucursal,
                    telefono: this.sucursalCreate.telefono,
                    email: this.sucursalCreate.email,
                }
                axios.post("../api/sucursales", sucursal).then((result) => {
                    this.buttonDisable = true;
                    this.buttonText = 'Guardando...';
                    this.toast.success('Sucursal guardada con éxito!', {
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
                }).catch((error)=> {
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
  