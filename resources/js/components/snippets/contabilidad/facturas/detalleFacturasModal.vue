<template>
  <transition name="modalcrear">
    <div name="modal">
      <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container">
              <div class="modal-header" style="font-weight: bold; color: #444444;">
                <slot name="header">
                  <label class="col-7 m-0" style="font-size: 17px;">Detalle de la Factura:</label>
                    <label class="col-3 m-0" style="font-size: 15px; text-align: right;"> Estado:
                      <label v-if="factura.estado == 'Gasto Autorizado'" class="fs-7 fw-bold" style="color: #28A745;">{{ factura.estado }}</label>
                      <label v-if="factura.estado == 'Pago Autorizado'" class="fs-7 fw-bold" style="color: #28A745;">{{ factura.estado }}</label>
                      <label v-if="factura.estado == 'Gasto Rechazado'" class="fs-7 fw-bold" style="color: #B80F0A;">{{ factura.estado }}</label>
                      <label v-if="factura.estado == 'Pago Rechazado'" class="fs-7 fw-bold" style="color: #B80F0A;">{{ factura.estado }}</label>
                      <label v-if="factura.estado == 'Pendiente'" class="fs-7 fw-bold" style="color: #FFC107;">{{ factura.estado }}</label>
                      <label v-if="factura.estado == 'No Autorizable'" class="fs-7 fw-bold" style="color: #1520A6;">{{ factura.estado }}</label>
                      <label v-if="factura.estado == 'Pagado'" class="fs-7 fw-bold" style="color: #8392AB;">{{ factura.estado }}</label>
                    </label>
                </slot>
                <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
              </div>
              <div class="modal-body">
                <slot name="body">
                  <div class="form-group">
                    <div class="row">
                      <div class="text-uppercase">Información Esencial:</div>
                      <label class="col-5 m-0 fs-8"> Rut: <label class="fw-normal" style="font-size: 14px;">{{ formatoRut(factura.rutProveedor) }}</label></label>
                      <label class="col-7 m-0 fs-8"> Razón Social: <label class="fw-normal" style="font-size: 14px;">{{ factura.razonSocial }}</label></label>
                      <label class="col-5 m-0 fs-8"> Folio: <label class="fw-normal" style="font-size: 14px;">{{ factura.folio }}</label></label>
                      <label class="col-7 m-0 fs-8"> Empresa: <label class="fw-normal" style="font-size: 14px;">{{ factura.empresa.razonSocial }} </label></label>
                      <hr size="4" class="separador">
                      <div class="text-uppercase">Documento y Compra:</div>
                      <label class="col-8 m-0 fs-8"> Tipo Documento: <label class="fw-normal" style="font-size: 14px;">{{ factura.tipoDocumento }} - {{ nombre }}</label></label>
                      <label class="col-4 m-0 fs-8"> Tipo Compra: <label class="fw-normal" style="font-size: 14px;">{{ factura.tipoCompra }}</label></label>
                      <hr size="4" class="separador">
                      <div class="text-uppercase">Montos:</div>
                      <label class="col-4 m-0 fs-8"> Monto IVA Recup.: <label class="fw-normal" style="font-size: 14px;">{{ formatMoneyCLP(factura.montoIVARec) }}</label></label>
                      <label class="col-4 m-0 fs-8"> Monto IVA no Recup.: <label class="fw-normal" style="font-size: 14px;">{{ formatMoneyCLP(factura.montoIVANoRec) }}</label></label>
                      <label class="col-4 m-0 fs-8"> Monto IVA no Retenido: <label class="fw-normal" style="font-size: 14px;">{{ formatMoneyCLP(factura.IVANoRetenido) }}</label></label>
                      <label class="col-4 m-0 fs-8"> Monto Exento: <label class="fw-normal" style="font-size: 14px;">{{ formatMoneyCLP(factura.montoExento) }}</label></label>
                      <label class="col-4 m-0 fs-8"> Monto Neto: <label class="fw-normal" style="font-size: 14px;">{{ formatMoneyCLP(factura.montoNeto) }}</label></label>
                      <label class="col-4 m-0 fs-8"> Monto Total: <label class="fw-normal" style="font-size: 14px;">{{ formatMoneyCLP(factura.montoTotal) }}</label></label>
                      <hr size="4" class="separador">
                      <div class="text-uppercase">Fechas:</div>
                      <label class="col-4 m-0 fs-8"> Fecha Documento: <label class="fw-normal" style="font-size: 14px;">{{ thisDate(factura.fechaDcto) }}</label></label>
                      <label class="col-4 m-0 fs-8"> Fecha Recepción: <label class="fw-normal" style="font-size: 14px;">{{ thisDate(factura.fechaRecepcion) }}</label></label>
                      <label class="col-4 m-0 fs-8"> Fecha Acuse: <label class="fw-normal" style="font-size: 14px;">{{ thisDate(factura.fechaAcuse) }}</label></label>
                      <label class="col-6 m-0 fs-8"> Fecha Requerimiento: <label class="fw-normal" style="font-size: 14px;">{{ thisDate(factura.fechaReq) }}</label></label>
                      <label class="col-6 m-0 fs-8"> Fecha Autorización Gasto: <label class="fw-normal" style="font-size: 14px;">{{ thisDate(factura.fechaAuthGasto) }}</label></label>
                      <label class="col-6 m-0 fs-8"> Fecha Autorización Pago: <label class="fw-normal" style="font-size: 14px;">{{ thisDate(factura.fechaAuthPago) }}</label></label>
                      <hr size="4" class="separador">
                      <div class="text-uppercase">Otros Montos:</div>
                      <label class="col-2 m-0 fs-8"> NCE o NDE: <label class="fw-normal" style="font-size: 14px;">{{ formatMoneyCLP(factura.NCEoNDE) }}</label></label>
                      <label class="col-4 m-0 fs-8"> Código Otro Imp.: <label class="fw-normal" style="font-size: 14px;">{{ factura.otroImpuesto }}</label></label>
                      <label class="col-3 m-0 fs-8"> Valor Otro Imp.: <label class="fw-normal" style="font-size: 14px;">{{ formatMoneyCLP(factura.valorOtroImpuesto) }}</label></label>
                      <label class="col-3 m-0 fs-8"> Tasa Otro Imp.: <label class="fw-normal" style="font-size: 14px;">{{ formatMoneyCLP(factura.tasaOtroImpuesto) }}</label></label>
                      <hr size="4" class="separador">
                      <div class="text-uppercase">Información Solicitante:</div>
                      <label class="col-12 m-0 fs-8"> Usuario Solicitante: <label class="fw-normal" style="font-size: 14px;">{{ factura.user.name }} </label></label>
                      <label class="col-12 m-0 fs-8"> Observación:
                        <label v-if="factura.observaciones != null" class="fw-normal" style="font-size: 14px;">{{ factura.observaciones }} </label>
                        <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                      </label>
                      <hr size="4" class="separador">
                      <div class="text-uppercase">Información Autorización:</div>
                      <label class="col-6 m-0 fs-8"> Autorizable:
                        <label v-if="factura.autorizable == true" class="fw-normal" style="font-size: 14px;">Si</label>
                        <label v-else-if="factura.autorizable == false" class="fw-normal" style="font-size: 14px;">No</label>
                      </label>
                      <label class="col-6 m-0 fs-8"> Identificador: <label class="fw-normal" style="font-size: 14px;">{{ factura.identificador }}</label></label>
                      <div v-if="factura.autorizable == true">
                        <label class="col-6 m-0 fs-8"> Autorizador/es:
                          <label v-if="factura.autorizador1 != null" class="fw-normal" style="font-size: 14px;">{{ factura.autorizador1.usuario.name }} </label>
                          <label v-if="factura.autorizador2 != null" class="fw-normal" style="font-size: 14px;">- {{ factura.autorizador2.usuario.name }} </label>
                          <label v-if="factura.autorizable == false" class="fw-normal" style="font-size: 14px;">---</label>
                        </label>
                        <label class="col-6 m-0 fs-8"> Objeción:
                          <label v-if="factura.objeciones != null" class="fw-normal" style="font-size: 14px;">{{ factura.objeciones }} </label>
                          <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                        </label>
                      </div>
                      <br>
                      <br>
                      <button v-if="is('Contabilidad_Jefatura') && can('Facturas_Crear') && factura.estado == 'Pendiente' && factura.autorizable == true" class="col-auto btn btn-insist fw-semibold" :disabled="buttonDisableInsistencia" @click="sendEmailInsistencia()" style="margin: 0 auto;"><i class="fa fa-fw fa-triangle-exclamation"></i>Insistencia</button>
                      <a v-if="is('Contabilidad_Jefatura') && can('Facturas_Crear') && factura.estado == 'Pendiente' && factura.autorizable == true" class="col-auto btn btn-alert fw-semibold" title="Editar" @click="editAutorizadorFactura(factura)" style="margin: 0 auto;"><i class="fa fa-fw fa-edit"></i>Editar Autorizadores</a>
                    </div>  
                  </div>
                  <!-- ###################################################################################################################################### -->
                  <!-- <br>
                  <hr size="4" class="separador">
                  <p style="font-size: 16px; font-weight: bold; color: black;"> Pagos Realizados: </p>
                  <div class="table-responsive p-0">
                    <table class="table align-items-center mb-0" id="tableDesign">
                      <thead>
                        <tr style="color: black">
                          <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Fecha Pago</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Monto Pagado</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Realizador del Pago</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-if="pagos == 0">
                          <td colspan="4"> No hay información disponible. </td>
                        </tr>
                        <tr v-for="pago in pagos" :key="pago.id">
                          <td>
                            <p class="text-sm font-weight-bolder mb-0" style="color: black">{{ pago.id }}</p>
                          </td>
                          <td>
                            <p class="text-sm mb-0">{{ thisDate(pago.fechaPago) }}</p>
                          </td>
                          <td>
                            <p class="text-sm mb-0">{{ formatMoneyCLP(pago.montoPagado) }}</p>
                          </td>
                          <td>
                            <p class="text-sm mb-0">{{ pago.usuarioPago }}</p>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                    <br>
                    <br>
                  </div> -->
                </slot>
              </div>
              <modalconfirmacion ref="confirmation"></modalconfirmacion>
              <modalalerta ref="alert"></modalalerta>
              <modaleditar v-bind:factura="facturaeditar" v-if="showEditarFactura" @close="showEditarFactura = false"></modaleditar>
          </div>
        </div>
      </div>
    </div>
  </transition>
</template>

<script>
import axios from 'axios'
import modalconfirmacion from '../../sistema/alerts/confirmationModal.vue'
import modalalerta from '../../sistema/alerts/alertModal.vue'
import modaleditar from './editFacturaModal.vue'
import {mixin} from '../../../../mixins.js'

export default {
  components: { modalconfirmacion, modalalerta, modaleditar },
  mixins: [mixin],
  data: () => ({
      pagos: [],
      autorizadores: null,
      idFactura: null,
      rutProveedor: null,
      showEditarFactura: false,
      buttonDisableInsistencia: false,
      facturaeditar: null,
      timer: null,
      tiempo_actual: null,
      folio: null,
      nombre: null,
      tiposDocFactura: null,
  }),
  props:{
      factura: Object,
  },
  created(){
    this.getTipoDocFacturas();
    this.idFactura = this.factura.idFactura;
    this.rutProveedor = this.factura.rutProveedor;
    this.folio = this.factura.folio;
    this.getTimers();
    this.getPagos();
  },
  methods: {
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
    getPagos(){
      this.factura.pagos.forEach(pago => {
          this.pagos.push(pago);
      });
    },
    sendEmailInsistencia(){
      this.tiempo_actual = new Date();
      var $salida = [];
      $salida.push(this.factura);
      if (this.factura.autorizadoAuth1 !== "true"){
        $salida.push('idAutorizador1');
      }
      else{
        $salida.push('idAutorizador2');
      }
      $salida.push(this.factura.tipoDocumento+' - '+this.nombre);
      if (this.timer != []){
        if (new Date(this.agregarMinutos(new Date(this.timer.updated_at), this.timer.timeToWait)).getTime() < new Date(this.tiempo_actual).getTime()){
          axios.post('api/facturas/sendEmailInsistencia', $salida).then( response => {
            this.buttonDisableInsistencia = true;
            this.toast.success("Correo de insistencia enviado!", {
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
          let cronometrito = {
            browser: navigator.userAgent,
          }
          var id = this.timer.id;
          axios.put(`api/timers/${id}`, cronometrito).then((result) => {
              this.buttonText = 'Guardando...';
          }).catch((e)=> {console.log(e)});
        }
        else{
          var tiempoFaltante = new Date(this.agregarMinutos(new Date(this.timer.updated_at), this.timer.timeToWait)).getTime() - new Date(this.tiempo_actual).getTime();
          tiempoFaltante = new Date(tiempoFaltante).getMinutes();
          this.toast.warning(`No puede enviar otro correo hasta dentro de '${tiempoFaltante}' minutos.`, {
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
      }
      else{
        axios.post('api/facturas/sendEmailInsistencia', $salida).then( response => {
          this.buttonDisableInsistencia = true;
          this.toast.success("Correo de insistencia enviado!", {
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
        let cronometrito = {
          sistema: 'Facturas',
          identificadorEspecifico: this.idFactura,
          idUsuario: this.userID,
          timerName: 'insistenciaFactura',
          timeToWait: 60,
          browser: navigator.userAgent,
        }
        axios.post("api/timers", cronometrito).then((result) => {
            this.buttonText = 'Guardando...';
        }).catch((e)=> {console.log(e)})
      }
    },
    getTipoDocFacturas(){
        axios.get('api/tipoDocFactura').then( response =>{
            this.tiposDocFactura = response.data;
            this.buscarTipoDoc(this.tiposDocFactura,this.factura.tipoDocumento)
        }).catch(e=> console.log(e))
    },
    buscarTipoDoc(tiposDocFac, facTipoDoc){    
        var nombre = null;
        tiposDocFac.forEach(tipoDocFac => {
            if(facTipoDoc == tipoDocFac.id){
                nombre = tipoDocFac.name;
            }
        });
        if (nombre == null){
            nombre = "No encontrado.";
        }
        this.nombre = nombre;
    },
    editAutorizadorFactura(factura){
      this.facturaeditar = factura;
      this.showEditarFactura = true;
    },
  }
}
</script>