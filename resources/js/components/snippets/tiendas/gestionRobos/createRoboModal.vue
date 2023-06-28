<template> 
  <transition name="modalcrear">
    <div name="modal">
      <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container">
              <div class="modal-header" style="font-weight: bold; color: #444444;">
                <slot name="header">
                    Nuevo Robo 
                </slot>
                <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
              </div>
              <div class="modal-body">
                <slot name="body">
                    <table id="tableDesign" class="table align-items-center mb-0">
                      <thead>
                        <tr>
                          <th class="text-uppercase text-xs font-weight-bolder">Usuario</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Monto Total Robado</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td><p class="text-sm mb-0"> {{ nombreUsuario }}</p></td>
                          <td><p class="text-sm mb-0"> {{ formatMoneyCLP(fields.montoRobado) }}</p></td>
                        </tr>
                      </tbody>
                    </table>
                    <br>
                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                <label for="idTienda">Tienda: </label>
                                <br>
                                <select class="form-select" :disabled="tienda == true & administrador == false" v-model="fields.idTienda" @change="getSupervisoresSelect($event.target.value)">
                                <option disabled value="">Seleccione una tienda</option>
                                <option v-for="tienda in tiendas" v-bind:key="tienda.id" v-bind:value="tienda.id">
                                {{ tienda.codigo }} - {{ tienda.nombre }}
                                </option>
                              </select>
                            </div>
                        </div>
                        <div class="col-4">
                          <div class="form-group">
                            <label for="supervisor">Supervisor:</label>
                              <br>
                              <select class="form-select" v-model="fields.idSupervisor">
                              <option disabled value="">Seleccione un supervisor</option>
                              <option v-for="supervisor in supervisorSelect" v-bind:key="supervisor.idUsuario" v-bind:value="supervisor.idUsuario">
                                {{ supervisor.usuario.name }}
                              </option>
                              </select>
                          </div>
                        </div>
                        <div class="col-4">
                          <div class="form-group">
                            <label for="colaborador">Colaborador:</label>
                              <br>
                              <select class="form-select" v-model="fields.idColaborador">
                              <option disabled value="">Seleccione un colaborador</option>
                              <option v-for="colaborador in colaboradores" v-bind:key="colaborador.idUsuario" v-bind:value="colaborador.idUsuario">
                                {{ colaborador.identificador }} - {{ colaborador.nombre1 }} {{ colaborador.nombre2 }} {{ colaborador.apellido1 }} {{ colaborador.apellido2 }}
                              </option>
                              </select>
                          </div>
                        </div>
                          <div class="col-3">
                            <div class="form-group">
                              <label for="tipoRobo">Tipo de Robo: </label>
                                <br>
                                <select class="form-select" v-model="fields.idTipoRobo">
                                <option disabled value="">Seleccione un tipo</option>
                                <option v-for="tipoRobo in tiposRobos" v-bind:key="tipoRobo.id" v-bind:value="tipoRobo.id">
                                  {{ tipoRobo.nombre }}
                                </option>
                                </select>
                            </div>
                          </div>
                        <div class="col-2">
                          <div class="form-group">
                            <label for="fecha">Fecha: </label>
                            <br>
                            <input type="date" class= "form-control" v-model="fields.fecha" @change="verificarFecha()">
                          </div>
                        </div>
                        <div class="col-2">
                          <div class="form-group">
                            <label for="hora">Hora: </label>
                            <br>
                            <input type="time" class= "form-control" v-model="fields.hora">
                          </div>
                        </div>
                        <div class="col-auto">
                          <div class="form-group">
                            <label for="observacion">Observacion: </label>
                            <br>
                            <textarea cols="52" rows="1" type="text" class= "form-control"  v-model="fields.observacion">
                            </textarea>
                          </div>
                        </div>
                    </div>
                    <hr size="4" class="separador">
                    <form enctype="multipart/form-data">
                    <div class="row">
                        <div v-if="fields.idTipoRobo != null" class="col-6">
                          <div v-if="fields.idTipoRobo == 1" class="form-group">
                            <div class="form-group">
                                <label for="">Producto: </label>
                                <br>
                                <input type="text" class="form-control" v-model="detalleRobo.item" >
                            </div>
                          </div>
                          <div v-if="fields.idTipoRobo == 2" class="form-group">
                            <label for="insumo">Insumo: </label>
                            <br>
                            <input type="text" class="form-control" v-model="detalleRobo.item" >
                          </div>
                          <div v-if="fields.idTipoRobo == 3" class="form-group">
                            <label for="roboColaborador">Robo Colaborador: </label>
                            <br>
                            <input type="text" class="form-control" v-model="detalleRobo.item" >
                          </div>
                          <div v-if="fields.idTipoRobo == 4" class="form-group">
                            <label for="roboCliente">Robo Cliente: </label>
                            <br>
                            <input type="text" class="form-control" v-model="detalleRobo.item" >
                          </div>
                          <div v-if="fields.idTipoRobo == 5" class="form-group">
                            <label for="dinero">Dinero: </label>
                            <br>
                            <select class="form-select" v-model="detalleRobo.item">
                              <option disabled value="">Seleccione a que corresponde</option>
                              <option value="Caja">Caja</option>
                            </select>
                          </div>
                        </div>
                        <div class="col-2">
                          <div v-if="fields.idTipoRobo == 3 || fields.idTipoRobo == 4" class="form-group">
                            <label for="name">Rut: </label>
                            <br>
                            <input type="text" class= "form-control" @keyup="checkRut()">
                          </div>
                          <div v-if="fields.idTipoRobo == 1 || fields.idTipoRobo == 2" class="form-group">
                            <label for="name">Número de Guía: </label>
                            <br>
                            <input type="text" class= "form-control" @keyup="checkRut()">
                          </div>
                        </div>
                        <div class="col-2">
                          <div class="form-group">
                              <label for="monto">Monto: </label>
                              <br>
                              <input type="number" class="form-control" v-model="detalleRobo.monto" >
                            </div>
                          </div>
                          <div class="col-2">
                            <div class="form-group">
                              <label for="cantidad">Cantidad: </label>
                              <br>
                              <input type="number" class="form-control" v-model="detalleRobo.cantidad" >
                            </div>
                          </div>
                          <div class="col-12">
                              <div class="form-group">
                                  <label for="descripcion">Descripción: </label>
                                  <br>
                                  <textarea class= "form-control" cols="30" rows="2" v-model="detalleRobo.descripcion"></textarea>
                              </div>
                          </div>
                    </div>
                    <a v-if="modoeditar == false" @click=agregarDetalle() class="btn btn-dark-grey float-right">Agregar detalle</a>
                    <a v-if="modoeditar == true" @click=guardarEdicion(idEditar) class="btn btn-dark-grey">Editar detalle</a>
                    &nbsp;
                    <a v-if="modoeditar == true" @click="cancelarEditar()" class="btn btn-grey">Cancelar</a>
                  </form>
                  <hr size="4" class="separador">
                  <div class="row">
                    <table id="tableDesign" class="table align-items-center mb-0">
                      <thead>
                        <tr>
                          <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Item</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Descripción</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Cantidad</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Monto de Unidad</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Monto Detalle</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Acciones</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-if="cantidadDetalles == 0">
                          <td colspan="10"> No hay información disponible. </td>
                        </tr>
                        <tr v-else v-for="detalle in detalles" :key="detalle">
                          <td>
                            <p class="text-sm mb-0"> {{ detalle.idDetalle + 1}}  </p>
                          </td>
                          <td>
                            <p class="text-sm mb-0"> {{ detalle.item }} </p>
                          </td>
                          <td>
                            <p class="text-sm mb-0">
                              <textarea class="form-control" v-model="detalle.descripcion" style="min-width: 200px; height: 40px; overflow: hidden; text-align: justify;"></textarea>
                            </p>
                          </td>
                          <td>
                            <p class="text-sm mb-0"> {{ detalle.cantidad }} </p>
                          </td>
                          <td>
                            <p class="text-sm mb-0"> {{ formatMoneyCLP(detalle.monto) }} </p>
                          </td>
                          <td>
                            <p class="text-sm mb-0"> {{ formatMoneyCLP(detalle.monto * detalle.cantidad) }} </p>
                          </td>
                          <td>
                            <p class="text-sm mb
                            -0">
                          <a class="btn btn-alert btn-xs" title="Editar" @click="editarDetalle(detalle.idDetalle)"><i class="fa fa-fw fa-edit"></i></a>
                          &nbsp;
                          <a class="btn btn-closed btn-xs" title="Eliminar" @click="eliminarDetalle(detalle)"><i class="fa fa-fw fa-trash"></i></a>
                          </p></td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </slot>
              </div>
              <div class="modal-footer">
                <slot name="footer">
                  <button class="btn btn-dark-grey float-end" @click="crearRobo()" :disabled="buttonDisable">
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
  import Multiselect from '@vueform/multiselect';

  let user = document.head.querySelector('meta[name="user"]');
  let roles= document.head.querySelector('meta[name="roles"]');

  export default {
    components: { Multiselect, modalconfirmacion, modalalerta },
    mixins: [mixin],
    data: () => ({
        fields: {
          idTipoRobo: null,
          idTienda: null,
          idUsuario: JSON.parse(user.content).id,
          fecha: null,
          hora: null,
          observacion: null,
          idSupervisor: '',
          montoRobado: 0,
        },
        detalleRobo:{
          item: null,
          descripcion: null,
          cantidad: null,
          monto: null,
        },  
        fotoMiniatura: "",
        buttonText:'Crear Robo',
        buttonDisable: false,
        tiposRobos:[],
        supervisores:[],
        supervisorSelect: '',
        detalles:[],
        cantidadDetalles: 0,
        errors:[],
        errorsRobo:[],
        nombreUsuario: "",
        modoeditar: false,
        idEditar:"",
        tiendas: null,
        tienda: false,
        productos: null,
        colaboradores: null,
    }),
    created(){
      this.getDatos(this.fields.idUsuario);
      this.getTipoRobo();
      this.getProductos();
      this.getTiendas();
      this.getSupervisores();
      this.getColaboradores();
      this.comprobarRol(JSON.parse(roles.content))
    },
    methods: {
      getColaboradores(){
        axios.get('api/colaboradores').then( response =>{
            this.colaboradores = response.data;
        }).catch(e=> console.log(e))
      },
      verificarFecha() {
        const fechaActual = new Date().toISOString().slice(0, 10);
        if (this.fields.fecha > fechaActual) {
          this.fields.fecha = "";
          this.toast.warning( "La fecha ingresada es mayor a la fecha actual.", {
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
      },
      cerrarModal(){
        const elem = this.$refs.closeBtn;
        this.$emit('recarga');
        elem.click();
      },
      getProductos(){
        axios.get('api/sap/productos').then( response =>{
          this.productos = response.data;
        }).catch(e=> console.log(e))
      },
      comprobarRol(roles){
        roles.forEach(rol => {
          if(rol == 'Administrador'){
            this.administrador = true;
          }
          if(rol == 'Tiendas'){
            this.tienda = true
          }
        });
      },
      getTiendas(){
        axios.get('api/getTiendas').then( response =>{
          this.tiendas = response.data;
          if (this.tienda == true){
            this.tiendas.forEach(tiendaAux => {
              if(tiendaAux.idUsuario == this.fields.idUsuario){
                this.fields.idTienda = tiendaAux.id;
                this.getSupervisoresSelect(tiendaAux.id);
              }
            })
          }
        }).catch(e=> console.log(e))
      },
      getSupervisoresSelect(id){
        this.supervisorSelect = []
        this.supervisores.forEach(supervisor => {
          if(supervisor.idSucursal == id){
            this.supervisorSelect.push(supervisor);
          }
        });
      },
      async eliminarDetalle(detalle){
        const ok = await this.$refs.confirmation.show({
            title: 'Eliminar Detalle ',
            message: `¿Está seguro/a que desea crear este detalle ? Esta acción no puede ser desecha.`,
            okButton: 'Eliminar',
            cancelButton: 'Volver'
          })
          if (ok) {
            this.detalles = this.detalles.filter((item) => item !== detalle)
            this.toast.success("Detalle Eliminado correctamente!", {
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
            this.cantidadDetalles = this.cantidadDetalles - 1
            this.fields.montoRobado = 0
            this.detalles.forEach(detalleAux => {
              this.fields.montoRobado = Number(this.fields.montoRobado) + (Number(detalleAux.monto) * Number(detalleAux.cantidad))
            });
          }
      },
      cancelarEditar(){
        this.detalleRobo.item = null;
        this.detalleRobo.descripcion = null;
        this.detalleRobo.cantidad = null,
        this.detalleRobo.monto = null;
        this.modoeditar= false;
        this.idEditar = "";
      },
      editarDetalle(id){
        this.idEditar = "";
        this.idEditar = id;
        this.modoeditar= true;
        var detalle = this.detalles[id];
        this.detalleRobo.item = detalle.item;
        this.detalleRobo.descripcion = detalle.descripcion;
        this.detalleRobo.cantidad = detalle.cantidad,
        this.detalleRobo.monto = detalle.monto;
      },
      guardarEdicion(id){
        this.errors = []
        for (const item in this.detalleRobo){
          if(this.detalleRobo[item] === "" || this.detalleRobo[item] === 0 || this.detalleRobo[item] === null){
              this.errors.push(item)
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
          let detalle = {
            idDetalle: this.idEditar ,
            item: this.detalleRobo.item,
            descripcion: this.detalleRobo.descripcion,
            cantidad: this.detalleRobo.cantidad,
            monto: this.detalleRobo.monto,
          };
          this.detalles[id] = detalle;
          this.fields.montoRobado = 0;
          this.detalles.forEach(detalleAux => {
            this.fields.montoRobado = Number(this.fields.montoRobado) + (Number(detalleAux.monto) * Number(detalleAux.cantidad));
          });
          this.detalleRobo.item = null;
          this.detalleRobo.descripcion = null;
          this.detalleRobo.cantidad = null,
          this.detalleRobo.monto = null;
          this.toast.success("Detalle Editado correctamente!", {
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

          this.modoeditar = false;
        }
      },
      async crearRobo() {
        this.errorsRobo = []
        if (this.cantidadDetalles == 0){
          this.errorsRobo.push('detalles')
          this.toast.warning('No puedes registrar un robo sin detalles', {
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
        for (const item in this.fields){
          if(this.fields[item] === "" || this.fields[item] === 0 || this.fields[item] === null){
              if (item == 'montoRobado' || item == 'observacion'){
              }else{
                this.errorsRobo.push(item);
              }
          }
        }
        var mensaje = ""
        if (this.errorsRobo.length != 0){
          this.errorsRobo.forEach(item => {
              if(item == 'detalles'){
              }else if(item == 'idTipoRobo'){
                mensaje = mensaje + "El campo tipo robo es requerido" + "\n"
              }else if(item == 'idSupervisor'){
                mensaje = mensaje + "El campo supervisor es requerido" + "\n"
              }else if(item == 'idTienda'){
                mensaje = mensaje + "El campo tienda es requerido" + "\n"
              }else{
                mensaje =   mensaje + "El campo " + item + " es requerido" + "\n" 
              }
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
        if(this.errorsRobo.length === 0){
          const ok = await this.$refs.confirmation.show({
            title: 'Crear Registro Robo',
            message: `¿Está seguro/a que desea registrar este robo ? Esta acción no puede ser desecha.`,
            okButton: 'Crear',
            cancelButton: 'Volver'
          })
          if (ok) {
            const datos = []
            let fields = {
              idTipoRobo: this.fields.idTipoRobo,
              idTienda: this.fields.idTienda,
              idUsuario: this.fields.idUsuario,
              fecha: this.fields.fecha,
              hora: this.fields.hora,
              observacion: this.fields.observacion,
              idSupervisor: this.fields.idSupervisor,
              montoRobado: this.fields.montoRobado,
              estado: 'Pendiente',
              autorizado: null,
            }
            datos.push(fields)
            datos.push(this.detalles)
            axios.post("api/robos", datos ).then((result) => {
              this.buttonDisable = true;
              this.buttonText = 'Guardando...';
              this.toast.success("Robo registrado creado con éxito!", {
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
              axios.post('api/robos/sendEmailSupervisor', datos).then( response => {
                this.toast.success("Correo enviado al supervisor!", {
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
              }).catch(e=> {console.log(e)});
              setTimeout(() => {this.cerrarModal();}, 1500);
            }).catch((error)=> {
              if (error.response.status == 422){
                this.errors = error.response.data.errors;
                this.toast.warning('Existe un valor inválido.', {
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
            });
          }
        }
      },
      getSupervisores(){
        axios.get("api/supervisores").then( response =>{
          this.supervisores = response.data;
          }).catch(e=> console.log(e))
      },
      getTipoRobo(){
        axios.get('api/getTipoRobo').then( response =>{
          this.tiposRobos = response.data;
          }).catch(e=> console.log(e))
      },
      agregarDetalle(){
        this.errors = [];
        for (const item in this.detalleRobo){
          if(this.detalleRobo[item] === "" || this.detalleRobo[item] === 0 || this.detalleRobo[item] === null){
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
          let detalle = {
            idDetalle: this.cantidadDetalles ,
            item: this.detalleRobo.item,
            descripcion: this.detalleRobo.descripcion,
            cantidad: this.detalleRobo.cantidad,
            monto: this.detalleRobo.monto,
          };
          this.cantidadDetalles = this.cantidadDetalles + 1;
          this.detalles.push(detalle);
          this.fields.montoRobado = 0;
          this.detalles.forEach(detalleAux => {
            this.fields.montoRobado = Number(this.fields.montoRobado) + (Number(detalleAux.monto) * Number(detalleAux.cantidad));
          });
          this.detalleRobo.item = "";
          this.detalleRobo.descripcion = "";
          this.detalleRobo.cantidad = "",
          this.detalleRobo.monto = "";
          this.toast.success("Detalle agregado correctamente!", {
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
      },
      getDatos(id){
        axios.get(`api/dataFofi/${id}`).then( response =>{
          this.nombreUsuario = response.data[2];
          }).catch(e=> console.log(e))
      },
    },
}
</script>

<style src="@vueform/multiselect/themes/default.css"></style>