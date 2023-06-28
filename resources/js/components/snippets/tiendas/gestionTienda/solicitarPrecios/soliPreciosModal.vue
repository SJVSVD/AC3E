<template> 
  <transition name="modalcrear">
    <div name="modal">
      <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container">
              <div class="modal-header" style="font-weight: bold; color: #444444;">
                <slot name="header">
                    Solicitar Precios
                </slot>
                <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
              </div>
              <div class="modal-body">
                <slot name="body">
                  <table id="tableDesign" class="table align-items-center mb-0">
                      <thead>
                        <tr>
                          <th class="text-uppercase text-xs font-weight-bolder">N° Folio</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Fecha</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Usuario</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Cantidad Total</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td><p class="text-sm mb-0"> {{ this.fields.nFolio }}</p></td>
                          <td><p class="text-sm mb-0"> {{ this.fields.fecha }}</p></td>
                          <td><p class="text-sm mb-0"> {{ nombreUsuario }}</p></td>
                          <td><p class="text-sm mb-0"> {{ this.fields.cantidadTotal }}</p></td>
                        </tr>
                      </tbody>
                    </table>
                    <br>
                    <form enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-4">
                          <div class="form-group">
                            <label for="">Tipo Etiqueta: </label>
                              <br>
                              <select class="form-select" v-model="detallePrecio.tipoEtiqueta">
                              <option disabled value="">Seleccione un tipo</option>
                              <option value="1">Precio Normal</option>
                              <option value="2">Precio Especial</option>
                              <option value="3">Precio De Liquidación</option>
                              </select>
                          </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="">Precio: </label>
                                <br>
                                <input type="number" class= "form-control" v-model="detallePrecio.precio">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="">Cantidad: </label>
                                <br>
                                <input type="number" class= "form-control"  v-model="detallePrecio.cantidad">
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
                          <th class="text-uppercase text-xs font-weight-bolder">Tipo Etiqueta</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Precio</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Cantidad</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Acciones</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-if="fields.cantidadTotal == 0">
                          <td colspan="10"> No hay información disponible. </td>
                        </tr>
                        <tr v-else v-for="detalle in detalles" :key="detalle">
                          <td><p class="text-sm mb-0"> {{ detalle.idDetalle + 1}}  </p></td>
                          <td>
                            <p v-if="detalle.tipoEtiqueta == '1'" class="text-sm mb-0"> Normal </p>
                            <p v-if="detalle.tipoEtiqueta == '2'" class="text-sm mb-0"> Especial </p>
                            <p v-if="detalle.tipoEtiqueta == '3'" class="text-sm mb-0"> Liquidación </p>
                          </td>
                          <td><p class="text-sm mb-0"> {{ formatMoneyCLP(detalle.precio) }} </p></td>
                          <td><p class="text-sm mb-0"> {{ detalle.cantidad }} </p></td>
                          <td><p class="text-sm mb-0">
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
                  <button class="btn btn-dark-grey" @click="crearSolicitud()" :disabled="buttonDisable">
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
  import modalconfirmacion from '../../../sistema/alerts/confirmationModal.vue';
  import modalalerta from '../../../sistema/alerts/alertModal.vue';
  import {mixin} from '../../../../../mixins.js';

  let user = document.head.querySelector('meta[name="user"]');
  let roles= document.head.querySelector('meta[name="roles"]');

  export default {
    components: { modalconfirmacion, modalalerta },
    mixins: [mixin],
    data: () => ({
        fields: {
            nFolio: "",
            fecha: "",
            usuario: JSON.parse(user.content).id,
            observacion: "",
            cantidadTotal: 0,
        },
        detallePrecio:{
            tipoEtiqueta: "",
            precio: "",
            cantidad: "",
        },  
        idUsuario: JSON.parse(user.content).id,
        buttonText:'Crear Solicitud',
        buttonDisable: false,
        detalles:[],
        errors:[],
        errorsTipo:[],
        nombreUsuario: "",
        modoeditar: false,
        idEditar:"",
        tienda: false,
        cantidadDetalles: 0,
    }),
    created(){
      this.getDatos(this.idUsuario);
      this.comprobarRol(JSON.parse(roles.content))
    },  
    methods: {
      cerrarModal(){
          const elem = this.$refs.closeBtn;
          this.$emit('recarga');
          elem.click();
      },
      checkRut() {
        if (this.detalleFofi.rut != ''){
          this.detalleFofi.rut.replace('.','');
          // Despejar Puntos
          var valor = this.detalleFofi.rut.replace('.','');
  
          // Despejar Guión
          valor = valor.replace('-','');
          
          // Aislar Cuerpo y Dígito Verificador
          var cuerpo = valor.slice(0,-1);
          var dv = valor.slice(-1).toUpperCase();
          
          // Formatear RUN
          this.detalleFofi.rut = cuerpo + '-'+ dv
        }
      },
      comprobarRol(roles){
        roles.forEach(rol => {
          if(rol == 'Tienda'){
            this.tienda = true
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
            this.fields.cantidadTotal = this.fields.cantidadTotal - detalle.cantidad;
            this.cantidadDetalles = this.cantidadDetalles - 1;
          }
      },
      cancelarEditar(){
        this.modoeditar= false;
        this.detallePrecio.tipoEtiqueta = "";
        this.detallePrecio.precio = "";
        this.detallePrecio.cantidad = "";

        this.idEditar = "";
      },
      editarDetalle(id){
        this.idEditar = "";
        this.idEditar = id;
        this.modoeditar= true;
        var detalle = this.detalles[id];
        this.detallePrecio.tipoEtiqueta = detalle.tipoEtiqueta;
        this.detallePrecio.precio = detalle.precio;
        this.detallePrecio.cantidad = detalle.cantidad;
      },
      guardarEdicion(id){

        this.errors = [];

        for (const item in this.detallePrecio){
          if(this.detallePrecio[item] === "" || this.detallePrecio[item] === 0){
            this.errors.push(item);
          }
        }

        var mensaje = "";
        
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
            tipoEtiqueta: this.detallePrecio.tipoEtiqueta,
            precio: this.detallePrecio.precio,
            cantidad: this.detallePrecio.cantidad,
          };
          this.detalles[id] = detalle;

          this.detallePrecio.tipoEtiqueta = "";
          this.detallePrecio.precio = "";
          this.detallePrecio.cantidad = "";

          this.fields.cantidadTotal = 0

          this.detalles.forEach(detalleAux => {
            this.fields.cantidadTotal = Number(this.fields.cantidadTotal) + Number(detalleAux.cantidad)
          });

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
      async crearSolicitud() {
        this.errors = []

        if (this.fields.cantidadTotal == 0){
          this.errors.push('detalles')
          this.toast.warning('No puede crear una solicitud sin detalles', {
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
            title: 'Crear Solicitud De Precios',
            message: `¿Está seguro/a que desea crear esta solicitud ? Esta acción no puede ser desecha.`,
            okButton: 'Crear',
            cancelButton: 'Volver'
          })
          if (ok) {
            const datos = []
            let fields = {
                nFolio: this.fields.nFolio ,
                fecha: this.fields.fecha,
                idUsuario: this.fields.usuario,
                cantidadTotal: this.fields.cantidadTotal,
            }

            datos.push(fields)
            datos.push(this.detalles)
            axios.post("api/solicitarPrecios", datos ).then((result) => {
              this.buttonDisable = true;
              this.buttonText = 'Guardando...';
              this.toast.success("Solicitud creada con éxito!", {
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
              axios.post('api/solicitarPrecios/sendEmail', datos).then( response => {
                  console.log(response.data);
                  this.toast.success("Correo enviado!", {
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
                  setTimeout(() => {this.cerrarModal();}, 1500);
                }).catch(e=> {console.log(e)});
              // setTimeout(() => {location.reload();}, 1000);
            })
            .catch((error)=> {
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
      agregarDetalle(){

        this.errors = [];

        for (const item in this.detallePrecio){
          if(this.detallePrecio[item] === "" || this.detallePrecio[item] === 0){
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
            tipoEtiqueta: this.detallePrecio.tipoEtiqueta,
            precio: this.detallePrecio.precio,
            cantidad: this.detallePrecio.cantidad,
          };
          this.fields.cantidadTotal = this.fields.cantidadTotal + this.detallePrecio.cantidad;
          this.cantidadDetalles = this.cantidadDetalles + 1;
          this.detalles.push(detalle);

          this.detallePrecio.tipoEtiqueta = "";
          this.detallePrecio.precio = "";
          this.detallePrecio.cantidad = "",

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
        axios.get(`api/dataSolicitarPrecio/${id}`).then( response =>{
          this.fields.nFolio = response.data[0];
          this.fields.fecha = response.data[1];
          this.nombreUsuario = response.data[2];
          }).catch(e=> console.log(e))
      },
    },
}
</script>
