<template>
    <transition name="modalcrear">
      <div name="modal">
        <div class="modal-mask">
            <div class="modal-wrapper">
              <div class="modal-container-s">
                <div class="modal-header fw-bold" style="color: #444444;">
                  <slot name="header">
                      Editar Colaborador
                  </slot>
                  <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
                </div>
                <div class="modal-body">
                  <slot name="body">
                      <div class="row">
                        <div class="col-3">
                          <div class="form-group">
                            <label for="nombre1">Nombre: </label>
                            <br>
                            <input type="nombre1" class= "form-control-small" v-model="colaborador.nombre1">
                          </div>
                        </div>
                        <div class="col-3">
                          <div class="form-group">
                            <label for="nombre2">Segundo nombre: </label>
                            <br>
                            <input type="nombre2" class= "form-control-small" v-model="colaborador.nombre2">
                          </div>
                        </div>
                        <div class="col-3">
                          <div class="form-group">
                            <label for="apellido1">Apellido: </label>
                            <br>
                            <input type="apellido1" class= "form-control-small" v-model="colaborador.apellido1">
                          </div>
                        </div>
                        <div class="col-3">
                          <div class="form-group">
                            <label for="apellido2">Segundo Apellido: </label>
                            <br>
                            <input type="apellido2" class= "form-control-small" v-model="colaborador.apellido2">
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-4">
                          <div class="form-group">
                            <label for="identificador">Identificador: </label>
                            <br>
                            <input type="text" class= "form-control-small" v-model="colaborador.identificador">
                          </div>
                        </div>
                        <div class="col-4">
                          <div class="form-group">
                            <label for="fechaNac">Fecha Nacimiento: </label>
                            <br>
                            <input type="date" class= "form-control-small" v-model="colaborador.fechaNac">
                            <br>
                          </div>
                        </div>
                        <div class="col-4">
                          <div class="form-group">
                            <label for="nacionalidad">Nacionalidad: </label>
                            <br>
                            <select class="form-select" v-model="colaborador.nacionalidad">
                              <option disabled value="">Seleccione una nacionalidad</option>
                              <option v-for="nacionalidad in nacionalidades" v-bind:key="nacionalidad.id" v-bind:value="nacionalidad.id">
                                {{ nacionalidad.nacionalidad }}
                              </option>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-6">
                          <div class="form-group">
                            <label for="direccion">Dirección: </label>
                            <br>
                            <input type="direccion" class= "form-control-small" v-model="colaborador.direccion">
                            <br>
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="form-group">
                            <label for="sector">Sector: </label>
                            <br>
                            <input type="sector" class= "form-control-small" v-model="colaborador.sector">
                            <br>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-4">
                          <div class="form-group">
                            <label for="pais">País: </label>
                            <br>
                            <select class="form-select" @change="getRegionesSelect($event.target.value)" v-model="colaborador.pais">
                              <option disabled value="">Seleccione un país</option>
                              <option v-for="pais in paises" v-bind:key="pais.id" v-bind:value="pais.id">
                                {{ pais.pais }}
                              </option>
                            </select>
                          </div>
                        </div>
                        <div class="col-4">
                          <div class="form-group">
                            <label for="region">Región: </label>
                            <br>
                            <select class="form-select" @change="getComunasSelect($event.target.value)" v-model="colaborador.region">
                              <option disabled value="">Seleccione una Región</option>
                              <option v-for="region in regionesSelect" v-bind:key="region.id" v-bind:value="region.id">
                                {{ region.region }}
                              </option>
                            </select>
                            <br>
                          </div>
                        </div>
                        <div class="col-4">
                          <div class="form-group">
                            <label for="comuna">Comuna: </label>
                            <br>
                            <select class="form-select" v-model="colaborador.comuna">
                              <option disabled value="">Seleccione una comuna</option>
                              <option v-for="comuna in comunasSelect" v-bind:key="comuna.id" v-bind:value="comuna.id">
                                {{ comuna.comuna }}
                              </option>
                            </select>
                            <br>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-6">
                          <div class="form-group">
                            <label for="emailP">Correo Personal: </label>
                            <br>
                            <input type="emailP" class= "form-control-small" v-model="colaborador.emailPersonal">
                            <br>
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="form-group">
                            <label for="direccion">Correo Empresa: </label>
                            <br>
                            <input type="direccion" class= "form-control-small" v-model="colaborador.emailEmpresa">
                            <br>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-4">
                          <div class="form-group">
                            <label for="emailP">Teléfono Empresa: </label>
                            <br>
                            <input type="emailP" class= "form-control-small" v-model="colaborador.telefonoEmpresa">
                            <br>
                          </div>
                        </div>
                        <div class="col-4">
                          <div class="form-group">
                            <label for="telefono">Teléfono Personal: </label>
                            <br>
                            <input type="telefono" class= "form-control-small" v-model="colaborador.telefonoPersonal">
                            <br>
                          </div>
                        </div>
                        <div class="col-4">
                          <div class="form-group">
                            <label for="estadoC">Estado Civil: </label>
                            <br>
                            <select class="form-select" v-model="colaborador.estadoCivil">
                              <option disabled value="">Seleccione un estado</option>
                              <option v-for="estadoCivil in estadosCiviles" v-bind:key="estadoCivil.id" v-bind:value="estadoCivil.id">
                                {{ estadoCivil.nombre }}
                              </option>
                            </select>
                            <br>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-4">
                          <div class="form-group">
                            <label for="departamento">Departamento: </label>
                            <br>
                            <select class="form-select" @change="getAreasSelect($event.target.value)" v-model="colaborador.departamento">
                              <option disabled value="">Seleccione un departamento</option>
                              <option v-for="departamento in departamentos" v-bind:key="departamento.id" v-bind:value="departamento.id">
                                {{ departamento.departamento }}
                              </option>
                            </select>
                            <br>
                          </div>
                        </div>
                        <div class="col-4">
                          <div class="form-group">
                            <label for="area">Área: </label>
                            <br>
                            <select class="form-select" v-model="colaborador.area">
                              <option disabled value="">Seleccione un Área</option>
                              <option v-for="area in areasSelect" v-bind:key="area.id" v-bind:value="area.id">
                                {{ area.area }}
                              </option>
                            </select>
                            <br>
                          </div>
                        </div>
                        <div class="col-4">
                          <div class="form-group">
                            <label for="cargo">Cargo: </label>
                            <br>
                            <input type="cargo" class= "form-control-small" v-model="colaborador.cargo">
                            <br>
                          </div>
                        </div>
                      </div>
                      <div class="row">
                      </div>
                      <div class="row">
                        <div class="col-6">
                          <div class="form-group">
                            <label for="sistemaSalud">Sistema de Salud: </label>
                            <br>
                            <select class="form-select" v-model="colaborador.sistemaSalud">
                              <option disabled value="">Seleccione un tipo</option>
                              <option v-for="sistemaSalud in sistemasSalud" v-bind:key="sistemaSalud.id" v-bind:value="sistemaSalud.id">
                                {{ sistemaSalud.nombre }}
                              </option>
                            </select>
                            <br>
                          </div>
                        </div>
                        <div class="col-6">
                          <div class="form-group">
                            <label for="afpAsociada">AFP asociada: </label>
                            <br>
                            <select class="form-select" v-model="colaborador.afpAsociada">
                              <option disabled value="">Seleccione un tipo</option>
                              <option v-for="afp in afps" v-bind:key="afp.id" v-bind:value="afp.id">
                                {{ afp.nombre }}
                              </option>
                            </select>
                            <br>
                          </div>
                        </div>
                      </div>
                      <!-- <div class="row">
                        <div class="col-6">
                          <div class="form-group">
                            <label for="foto">Foto: </label>
                            <br>
                            <input type="file" accept=".jpg, .jpeg, .png, .heif, .heic" id="foto" class="form-control-small"  @change="getImage" v-has-error="errors.foto">
                          </div>
                        </div>
                        <div class="col-6">
                          <div v-if="fotoMiniatura != '' " class="col-4">
                            <figure>
                              <img width="100" height="100" :src="imagen" alt="Foto Miniatura">
                            </figure>
                          </div>
                        </div>
                      </div> -->
                    </slot>
                  </div>
                  <div class="modal-footer">
                    <slot name="footer">
                      <button class="btn btn-continue" @click="editarColaborador()" :disabled="buttonDisable">
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
  import axios from 'axios';
  import modalconfirmacion from './../../sistema/alerts/confirmationModal.vue';
  import modalalerta from './../../sistema/alerts/alertModal.vue';
  import {mixin} from './../../../../mixins.js';
  import Compressor from 'compressorjs';
  
  export default {
      components: { modalconfirmacion, modalalerta },
      mixins: [mixin],
      data: () => ({
  
          colaborador: {
            identificador: "",
            nombre1: "",
            nombre2: "",
            apellido1: "",
            apellido2: "",
            fechaNac: "",
            direccion: "",
            nacionalidad: "",
            sector: "",
            ciudad: "",
            pais: "",
            region: "",
            comuna: "",
            emailPersonal: "",
            emailEmpresa: "",
            telefonoEmpresa: "",
            telefonoPersonal: "",
            estadoCivil: "",
            departamento: "",
            area: "",
            cargo: "",
            sistemaSalud: "",
            afpAsociada: "",
            // foto:null,
          },

          id: null,
          buttonText:'Editar Colaborador',
          buttonDisable: false,
          errors:[],
          fotoMiniatura: "",
          fotoComprimida: null,
  
          nacionalidades: [],
          paises: [],
          regiones: [],
          regionesSelect: [],
          comunas: [],
          comunasSelect: [],
          departamentos: [],
          estadosCiviles: [],
          areas: [],
          areasSelect: [],
          sistemaSalud: [],
          afps: [],
      }),
      props:{
        colaborador1: Object,
      },
      created(){
        this.getNacionalidades();
        this.getPaises();
        this.getRegiones();
        this.getComunas();
        this.getEstadosC();
        this.getDepartamentos();
        this.getAreas();
        this.getSistemasSalud();
        this.getAfps();
        this.id = this.colaborador1.id;
        this.colaborador.identificador = this.colaborador1.identificador;
        this.colaborador.nombre1 = this.colaborador1.nombre1;
        this.colaborador.nombre2 = this.colaborador1.nombre2;
        this.colaborador.apellido1 = this.colaborador1.apellido1;
        this.colaborador.apellido2 = this.colaborador1.apellido2;
        this.colaborador.fechaNac = this.colaborador1.fechaNac;
        this.colaborador.nacionalidad = this.colaborador1.nacionalidad.id;
        this.colaborador.direccion = this.colaborador1.direccion;
        this.colaborador.sector = this.colaborador1.sector;
        this.colaborador.ciudad = this.colaborador1.ciudad;
        this.colaborador.pais = this.colaborador1.pais.id;
        this.colaborador.region = this.colaborador1.region.id;
        this.colaborador.comuna = this.colaborador1.comuna.id;
        this.colaborador.emailPersonal = this.colaborador1.correoPersonal;
        this.colaborador.emailEmpresa = this.colaborador1.correoEmpresa;
        this.colaborador.telefonoEmpresa = this.colaborador1.telefonoEmpresa;
        this.colaborador.telefonoPersonal = this.colaborador1.telefonoPersonal;
        this.colaborador.estadoCivil = this.colaborador1.estado_civil.id;
        this.colaborador.departamento = this.colaborador1.departamento.id;
        this.colaborador.area = this.colaborador1.area.id;
        this.colaborador.cargo = this.colaborador1.cargo;
        this.colaborador.sistemaSalud = this.colaborador1.sistema_salud.id;
        this.colaborador.afpAsociada = this.colaborador1.afp_asociada.id;
      },
      methods: {
        cerrarModal(){
          const elem = this.$refs.closeBtn;
          this.$emit('recarga');
          elem.click();
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
        getAreasSelect(id){
          this.areasSelect = []
          this.areas.forEach(area => {
            if(area.idDepartamento == id){
              this.areasSelect.push(area);
            }
          });
        },
        getNacionalidades(){
          axios.get('api/nacionalidades').then( response =>{
            this.nacionalidades = response.data;
          }).catch(e=> console.log(e))
        },
        getPaises(){
          axios.get('api/paises').then( response =>{
            this.paises = response.data;
          }).catch(e=> console.log(e))
        },
        getRegiones(){
          axios.get('api/regiones').then( response =>{
            this.regiones = response.data;
            this.getRegionesSelect(this.colaborador.pais);
          }).catch(e=> console.log(e))
        },
        getComunas(){
          axios.get('api/comunas').then( response =>{
            this.comunas = response.data;
            this.getComunasSelect(this.colaborador.region);
          }).catch(e=> console.log(e))
        },
        getDepartamentos(){
          axios.get('api/departamentos').then( response =>{
            this.departamentos = response.data;
          }).catch(e=> console.log(e))
        },
        getAreas(){
          axios.get('api/areas').then( response =>{
            this.areas = response.data;
            this.getAreasSelect(this.colaborador.departamento);
          }).catch(e=> console.log(e))
        },
        getEstadosC(){
          axios.get('api/estadosCiviles').then( response =>{
            this.estadosCiviles = response.data;
          }).catch(e=> console.log(e))
        },
        getSistemasSalud(){
          axios.get('api/sistemasSalud').then( response =>{
            this.sistemasSalud = response.data;
          }).catch(e=> console.log(e))
        },
        getAfps(){
          axios.get('api/afp').then( response =>{
            this.afps = response.data;
          }).catch(e=> console.log(e))
        },
        async editarColaborador() {
          this.errors = [];
          for (const item in this.colaborador){
            if(this.colaborador[item] === "" || this.colaborador[item] === 0){
              this.errors.push(item);
            }
          }
          var mensaje = ""
          if (this.errors.length != 0){
            this.errors.forEach(item => {
                mensaje =   mensaje + "El campo " + item + " es requerido" + "\n" 
            });
            this.toast.warning( mensaje, {
              position: "top-right",
              timeout: 5000,
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
          if(this.errors.length === 0){
            const ok = await this.$refs.confirmation.show({
              title: 'Editar Colaborador',
              message: `¿Está seguro/a que desea editar al colaborador '${this.colaborador.nombre1 + ' ' +this.colaborador.apellido1}'? Esta acción no puede ser desecha.`,
              okButton: 'Crear',
              cancelButton: 'Volver'
            })
            if (ok) {
              let colaborador = {
                nombre1: this.colaborador.nombre1,
                nombre2: this.colaborador.nombre2,
                apellido1: this.colaborador.apellido1,
                apellido2: this.colaborador.apellido2,
                identificador: this.colaborador.identificador,
                fechaNac: this.colaborador.fechaNac,
                nacionalidad: this.colaborador.nacionalidad,
                direccion: this.colaborador.direccion,
                sector: this.colaborador.sector,
                pais: this.colaborador.pais,
                region: this.colaborador.region,
                comuna: this.colaborador.comuna,
                correoPersonal: this.colaborador.emailPersonal, 
                correoEmpresa: this.colaborador.emailEmpresa,
                telefonoPersonal: this.colaborador.telefonoPersonal,
                telefonoEmpresa: this.colaborador.telefonoEmpresa, 
                estadoCivil: this.colaborador.estadoCivil,
                departamento: this.colaborador.departamento,
                area: this.colaborador.area, 
                cargo: this.colaborador.cargo,
                sistemaSalud: this.colaborador.sistemaSalud,
                afpAsociada: this.colaborador.afpAsociada, 
                // foto: this.colaborador.foto,
              };
              var id = this.id;
              axios.put(`api/colaboradores/${id}`, colaborador).then((result) => {
                this.buttonText = 'Guardando...';
                this.buttonDisable = true;
                this.toast.success("Colaborador editado con éxito!", {
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
        },
        async compressImages(fileInput, output) {
          return new Promise(async function (resolve) {
              const file = fileInput;
              await new Promise((resolve) => {
                new Compressor(file, {
                  quality: 0.5,
                  success(result) {
                    output.push(result);
                    resolve();
                  }
                });
              });
              resolve();
          });
        },
        async getImage(e){
            this.colaborador.foto = e.target.files[0];
            let compression = [];
            await this.compressImages(this.colaborador.foto,compression);
            var file = new File([compression[0]], compression[0]['name'], {
            type: compression[0]['type'],});
            this.colaborador.foto = file;
            this.chargeImage(this.colaborador.foto);
        },
        chargeImage(file){
            let reader = new FileReader();
            reader.onload = (e) => { 
                this.fotoMiniatura = e.target.result;
            }
            reader.readAsDataURL(file);
        },  
      },
      computed: {
      imagen(){
          return this.fotoMiniatura;
      }
    }
  }
  </script>