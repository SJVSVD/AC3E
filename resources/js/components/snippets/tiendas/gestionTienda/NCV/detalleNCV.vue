<template>
  <transition name="modalcrear">
    <div name="modal">
      <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container">
              <div class="modal-header pb-1" style="font-weight: bold; color: #444444;">
                <slot name="header">
                  <label class="col-5 m-0" style="font-size: 17px;">Detalle de la Nota de Crédito:</label>
                  <label class="col-5 m-0 fs-8 pt-2"> Usuario: <label class="fw-normal" style="font-size: 14px;">{{ ncv.usuario.name }}</label></label>
                </slot>
                <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
              </div>
              <div class="modal-body">
                <slot name="body">
                  <div class="form-group">
                    <div class="row">
                      <div class="text-uppercase pb-2">Información General:</div>
                      <label class="col-4 m-0 fs-8"> Tienda: <label class="fw-normal" style="font-size: 14px;"> {{ ncv.tienda.nombre }}</label></label>
                      <label class="col-4 m-0 fs-8"> NºNCV: <label class="fw-normal" style="font-size: 14px;">{{ ncv.notaCredito }}</label></label>
                      <label class="col-4 m-0 fs-8"> NºBoleta: <label class="fw-normal" style="font-size: 14px;">{{ ncv.boleta }}</label></label>
                      <label class="col-4 m-0 fs-8"> Monto NCV: <label class="fw-normal" style="font-size: 14px;">{{ formatMoneyCLP(ncv.montoNotaCredito) }}</label></label>
                      <label class="col-4 m-0 fs-8"> Monto Boleta: <label class="fw-normal" style="font-size: 14px;">{{ formatMoneyCLP(ncv.montoBoleta) }}</label></label>
                      <label class="col-4 m-0 fs-8"> Rut Cliente: <label class="fw-normal" style="font-size: 14px;">{{ ncv.rutCliente }}</label></label>
                      <label class="col-4 m-0 fs-8"> Nombre Cliente: <label class="fw-normal" style="font-size: 14px;">{{ ncv.nomCliente }}</label></label>
                      <label class="col-4 m-0 fs-8 pb-2"> Motivo: <label class="fw-normal" style="font-size: 14px;">{{ ncv.motivo }}</label></label>
                      <label class="col-4 m-0 fs-8 pb-2"> Tipo Pago: <label class="fw-normal" style="font-size: 14px;">{{ ncv.metodoPago }}</label></label>
                      <hr size="4" class="separador">
                      <div class="text-uppercase pb-2">Productos:</div>
                      <div class="row pb-2">
                        <table id="tableDesign" class="table align-items-center mb-0">
                          <thead>
                            <tr>
                              <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                              <th class="text-uppercase text-xs font-weight-bolder">Código</th>
                              <th class="text-uppercase text-xs font-weight-bolder">Nombre</th>
                              <th class="text-uppercase text-xs font-weight-bolder">Cantidad</th>
                              <th class="text-uppercase text-xs font-weight-bolder">Precio</th>
                              <th class="text-uppercase text-xs font-weight-bolder">Numero Linea</th>
                              <th class="text-uppercase text-xs font-weight-bolder">Motivo</th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr v-if="ncv.productos.length == 0">
                              <td colspan="10"> No hay información disponible. </td>
                            </tr>
                            <tr v-else v-for="(producto,index) in ncv.productos" :key="producto">
                              <td><p class="text-sm mb-0"> {{ index + 1 }}  </p></td>
                              <td><p class="text-sm mb-0"> {{ producto.codigo }} </p></td>
                              <td><p class="text-sm mb-0"> {{ producto.descripcion }} </p></td>
                              <td><p class="text-sm mb-0"> {{ producto.cantidad }} </p></td>
                              <td><p class="text-sm mb-0"> {{ formatMoneyCLP(producto.precio)}} </p></td>
                              <td><p class="text-sm mb-0"> {{ producto.nroLinea}} </p></td>
                              <td><p class="text-sm mb-0"> {{ producto.motivo }} </p></td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                      <hr size="4" class="separador">
                      <div class="text-uppercase pb-2">Detalles Pago:</div>
                      <table id="tableDesign" class="table align-items-center mb-0">
                      <thead>
                        <tr>
                          <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Tipo Pago</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Banco</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Tipo Cuenta</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Cuenta</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Rut</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(detalle, index) in ncv.detalles" :key="detalle">
                          <td><p class="text-sm mb-0"> {{ index + 1}}  </p></td>
                          <td><p class="text-sm mb-0"> {{ detalle.tipoPago }} </p></td>
                          <td><p v-if="detalle.banco != null" class="text-sm mb-0"> {{ detalle.banco }} </p>
                              <p v-else class="text-sm mb-0"> --- </p></td>
                          <td><p v-if="detalle.tipoCuenta != null" class="text-sm mb-0"> {{ detalle.tipoCuenta }} </p>
                              <p v-else class="text-sm mb-0"> --- </p></td>
                          <td><p v-if="detalle.cuenta != null" class="text-sm mb-0"> {{ detalle.cuenta }} </p>
                              <p v-else class="text-sm mb-0"> --- </p></td>
                          <td><p v-if="detalle.rut != null" class="text-sm mb-0"> {{ detalle.rut }} </p>
                              <p v-else class="text-sm mb-0"> --- </p></td>
                        </tr>
                      </tbody>
                    </table>
                    </div>
                  </div>
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
import Compressor from 'compressorjs';

export default {
  components: { modalconfirmacion, modalalerta },
  mixins: [mixin],
  data: () => ({
    fotoMiniatura: "",
    foto: "",
  }),
  props:{
      ncv: Object,
  },
  created(){
  },
  methods: {
    guardarImagen(){
      var salida = [];
      console.log(this.foto);
      salida.push(this.foto);
      salida.push(this.userID);
      axios.post('api/ncv/añadirImagen', salida, {
        headers: { 'Content-Type' : 'multipart/form-data' }
      }).then( response => {
      console.log(response.data);
      this.buttonDisable = true;
      this.buttonText = 'Guardando...';
      this.toast.success("Imagen añadida con exito!", {
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
      this.foto = e.target.files[0];
      let compression = [];
      await this.compressImages(this.foto,compression);
      var file = new File([compression[0]], compression[0]['name'], {
      type: compression[0]['type'],});
      this.foto = file;
      this.chargeImage(this.foto);
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