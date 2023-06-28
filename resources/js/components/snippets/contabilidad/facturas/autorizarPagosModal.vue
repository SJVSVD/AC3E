<template>
  <transition name="modalcrear">
    <div name="modal">
      <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container-2xl">
              <div class="modal-header" style="font-weight: bold; color: #444444;">
                <slot name="header">
                    Autorizar Pagos:
                    <div style="margin-left: auto;">
                      <label> Filtro por Empresa: </label>
                      <select class="form-select-fit" @change="manipularFacturas()" style="margin: 10px" v-model="selectedCompany">
                        <option selected disabled :value="null">Seleccione la empresa:</option>
                        <option v-for="empresa in empresas" :key="empresa.id" :value="empresa.id">
                          <label>{{ empresa.razonSocial }}</label>
                        </option>
                        <option value="Todas">Todas.</option>
                      </select>
                    </div>
                </slot>
                <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
              </div>
              <div class="modal-body">
                <slot name="body">
                  <div class="table-responsive p-0" id="espaciadoTablaPagos" style="min-height: 300px; max-height: 500px;">
                    <div v-show="mostrarCargaAutorizar" class="loader"></div>
                    <table v-show="mostrarTablaAutorizar" class="table align-items-center mb-0" id="tableDesign">
                      <thead>
                        <tr style="color: black">
                          <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Autorizador 1</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Autorizador 2</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Rut</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Razón Social</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Folio</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Tipo Documento</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Monto Total</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Observaciones</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Autorizar Si/No</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Objeciones</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Acciones</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-if="facturasAutorizar != 0">
                          <td colspan="9"></td>
                          <td v-if="checkAll1 == false"> <label>Marcar Todo:</label></td>
                          <td v-else> <label>Desmarcar Todo:</label></td>
                          <td><div class="form-check"><label class="form-check-label"><input class="form-check-input" @click="checkAll()" type="checkbox"></label></div></td>
                          <td colspan="2"></td>
                        </tr>
                        <tr v-if="facturasAutorizar == 0">
                          <td colspan="13"> No hay información disponible. </td>
                        </tr>
                        <tr v-else v-for="factura in facturasAutorizar" :key="factura.id">
                          <td>
                            <p class="text-sm font-weight-bolder mb-0" style="color: black">{{ factura.id }}</p>
                          </td>
                          <td>
                            <p v-if="factura.autorizador1 != null" :title="factura.autorizador1.usuario.name" class="text-sm mb-0" style="width: 100px; word-wrap: break-word; overflow: hidden; text-overflow: ellipsis;">{{ factura.autorizador1.usuario.name }}</p>
                            <p v-else class="text-sm mb-0">-----</p>
                          </td>
                          <td>
                            <p v-if="factura.autorizador2 != null" :title="factura.autorizador2.usuario.name" class="text-sm mb-0" style="width: 100px; word-wrap: break-word; overflow: hidden; text-overflow: ellipsis;">{{ factura.autorizador2.usuario.name }}</p>
                            <p v-else class="text-sm mb-0">-----</p>
                          </td>
                          <td>
                            <p class="text-sm font-weight-bolder mb-0" style="color: black">{{ formatoRut(factura.rutProveedor) }}</p>
                          </td>
                          <td>
                            <p :title="factura.razonSocial" class="text-sm mb-0" style="width: 130px; word-wrap: break-word; overflow: hidden; text-overflow: ellipsis;">{{ factura.razonSocial }}</p>
                          </td>
                          <td>
                            <p class="text-sm font-weight-bolder mb-0" style="color: black">{{ factura.folio }}</p>
                          </td>
                          <td>
                              <p :title="buscarTipoDoc(tiposDocFactura, factura.tipoDocumento)" class="text-sm mb-0" style="width: 150px; word-wrap: break-word; overflow: hidden; text-overflow: ellipsis;">{{factura.tipoDocumento}} - {{ buscarTipoDoc(tiposDocFactura, factura.tipoDocumento) }}</p>
                          </td>
                          <td>
                            <p class="text-sm mb-0">{{ formatMoneyCLP(factura.montoTotal) }}</p>
                          </td>
                          <td>
                            <textarea readonly class="form-control" style="text-align: justify; min-width: 150px; height: 40px; word-wrap: break-word; overflow: hidden;" v-model="factura.observaciones"></textarea>
                          </td>
                          <td>
                            <div class="form-check">
                              <label v-if="factura.autorizador1.usuario.id == this.userID || is('Contabilidad_Jefatura')" class="form-check-label">
                                <input class="form-check-input" :name="'autorizador'+factura.id" type="checkbox" @change="factura.autorizadoAuth1 = nullifyCheckbox(factura.autorizadoAuth1)" v-model="factura.autorizadoAuth1">
                                <span v-if="factura.autorizadoAuth1 == true">
                                  Aprobado
                                </span>
                                <span v-if="factura.autorizadoAuth1 == null">
                                  Pendiente
                                </span>
                                <!-- <input class="form-check-input" style="-webkit-transform: scale(1.2); transform: scale(1.2);" :name="'autorizador'+factura.id" type="radio" value="true" v-model="factura.autorizadoAuth1">
                                <label style="margin-right:15px;">Si</label>
                                <input style="margin-left:15px; -webkit-transform: scale(1.2); transform: scale(1.2);" :name="'autorizador'+factura.id" type="radio" value="false" v-model="factura.autorizadoAuth1">
                                <label>No</label>
                                <input selected checked style="margin-left:15px; -webkit-transform: scale(1.2); transform: scale(1.2);" :name="'autorizador'+factura.id" type="radio" value="null" v-model="factura.autorizadoAuth1">
                                <label>Pendiente</label> -->
                              </label>
                              <label v-else class="form-check-label">
                                <input class="form-check-input" :name="'autorizador'+factura.id" type="checkbox" @change="factura.autorizadoAuth2 = nullifyCheckbox(factura.autorizadoAuth2)" v-model="factura.autorizadoAuth2">
                                <span v-if="factura.autorizadoAuth2 == true">
                                  Aprobado
                                </span>
                                <span v-if="factura.autorizadoAuth2 == null">
                                  Pendiente
                                </span>
                                <!-- <input class="form-check-input" style="-webkit-transform: scale(1.2); transform: scale(1.2);" :name="'autorizador'+factura.id" type="radio" value="true" v-model="factura.autorizadoAuth2">
                                <label style="margin-right:15px;">Si</label>
                                <input style="margin-left:15px; -webkit-transform: scale(1.2); transform: scale(1.2);" :name="'autorizador'+factura.id" type="radio" value="false" v-model="factura.autorizadoAuth2">
                                <label>No</label>
                                <input selected checked style="margin-left:15px; -webkit-transform: scale(1.2); transform: scale(1.2);" :name="'autorizador'+factura.id" type="radio" value="null" v-model="factura.autorizadoAuth2">
                                <label>Pendiente</label> -->
                              </label>
                            </div>
                          </td>
                          <td>
                            <p class="text-sm mb-0">
                              <textarea class="form-control" v-model="factura.objeciones" style="min-width: 200px; height: 40px; overflow: hidden; text-align: justify;"> </textarea>
                            </p>
                          </td>
                          <td class="align-middle text-end">
                            <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                <a class="btn btn-success btn-xs" title="Detalle" @click="facturaDetalle(factura, 'open');"><i class="fa-regular fa-eye"></i></a>
                                <a v-if="facturaDetalles.url != null" :href=facturaDetalles.url  target="_blank" class="btn btn-purple btn-xs m-1"><i class="fa-solid fa-file-invoice"></i></a>
                                <a v-else class="btn btn-grey btn-xs m-1"><i class="fa-solid fa-file-invoice"></i></a>
                            </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </slot>
              </div>
              <div v-if="facturaDetalleIsVisible == true">
                <hr size="4" class="separador">
                <a class="btn btn-closed btn-xs" style="float: right;" @click="facturaDetalle(facturaDetalles, 'close')" ref="closeBtn">X</a>
                <div class="row">
                  <div style="font-weight: bold; color: #444444;">
                    Detalles de la Factura '{{ facturaDetalles.id }}':
                  </div>
                  <div class="text-uppercase">Información Esencial:</div>
                  <label class="col-auto m-0 fs-8"> Rut: <label class="fw-normal" style="font-size: 14px;">{{ formatoRut(facturaDetalles.rutProveedor) }}</label></label>
                  <label class="col-auto m-0 fs-8"> Razón Social: <label class="fw-normal" style="font-size: 14px;">{{ facturaDetalles.razonSocial }}</label></label>
                  <label class="col-auto m-0 fs-8"> Folio: <label class="fw-normal" style="font-size: 14px;">{{ facturaDetalles.folio }}</label></label>
                  <label class="col-auto m-0 fs-8"> Empresa: <label class="fw-normal" style="font-size: 14px;">{{ facturaDetalles.empresa.razonSocial }} </label></label>
                  <label class="col-auto m-0 fs-8"> Usuario Solicitante: <label class="fw-normal" style="font-size: 14px;">{{ facturaDetalles.user.name }} </label></label>
                  <label class="col-auto m-0 fs-8"> Tipo Documento: <label class="fw-normal" style="font-size: 14px;">{{ facturaDetalles.tipoDocumento }} - {{ buscarTipoDoc(tiposDocFactura, facturaDetalles.tipoDocumento) }}</label></label>
                  <label class="col-auto m-0 fs-8"> Tipo Compra: <label class="fw-normal" style="font-size: 14px;">{{ facturaDetalles.tipoCompra }}</label></label>
                  <div class="text-uppercase">Montos:</div>
                  <label class="col-auto m-0 fs-8"> Monto IVA Recup.: <label class="fw-normal" style="font-size: 14px;">{{ formatMoneyCLP(facturaDetalles.montoIVARec) }}</label></label>
                  <label class="col-auto m-0 fs-8"> Monto IVA no Recup.: <label class="fw-normal" style="font-size: 14px;">{{ formatMoneyCLP(facturaDetalles.montoIVANoRec) }}</label></label>
                  <label class="col-auto m-0 fs-8"> Monto IVA no Retenido: <label class="fw-normal" style="font-size: 14px;">{{ formatMoneyCLP(facturaDetalles.IVANoRetenido) }}</label></label>
                  <label class="col-auto m-0 fs-8"> Monto Exento: <label class="fw-normal" style="font-size: 14px;">{{ formatMoneyCLP(facturaDetalles.montoExento) }}</label></label>
                  <label class="col-auto m-0 fs-8"> Monto Neto: <label class="fw-normal" style="font-size: 14px;">{{ formatMoneyCLP(facturaDetalles.montoNeto) }}</label></label>
                  <label class="col-auto m-0 fs-8"> Monto Total: <label class="fw-normal" style="font-size: 14px;">{{ formatMoneyCLP(facturaDetalles.montoTotal) }}</label></label>
                  <label class="col-auto m-0 fs-8"> NCE o NDE: <label class="fw-normal" style="font-size: 14px;">{{ formatMoneyCLP(facturaDetalles.NCEoNDE) }}</label></label>
                  <label class="col-auto m-0 fs-8"> Código Otro Imp.: <label class="fw-normal" style="font-size: 14px;">{{ facturaDetalles.otroImpuesto }}</label></label>
                  <label class="col-auto m-0 fs-8"> Valor Otro Imp.: <label class="fw-normal" style="font-size: 14px;">{{ formatMoneyCLP(facturaDetalles.valorOtroImpuesto) }}</label></label>
                  <label class="col-auto m-0 fs-8"> Tasa Otro Imp.: <label class="fw-normal" style="font-size: 14px;">{{ formatMoneyCLP(facturaDetalles.tasaOtroImpuesto) }}</label></label>
                  <div class="text-uppercase">Fechas:</div>
                  <label class="col-auto m-0 fs-8"> Fecha Documento: <label class="fw-normal" style="font-size: 14px;">{{ thisDate(facturaDetalles.fechaDcto) }}</label></label>
                  <label class="col-auto m-0 fs-8"> Fecha Recepción: <label class="fw-normal" style="font-size: 14px;">{{ thisDate(facturaDetalles.fechaRecepcion) }}</label></label>
                  <label class="col-auto m-0 fs-8"> Fecha Acuse: <label class="fw-normal" style="font-size: 14px;">{{ thisDate(facturaDetalles.fechaAcuse) }}</label></label>
                  <label class="col-auto m-0 fs-8"> Fecha Requerimiento: <label class="fw-normal" style="font-size: 14px;">{{ thisDate(facturaDetalles.fechaReq) }}</label></label>
                  <label class="col-auto m-0 fs-8"> Fecha Autorización Gasto: <label class="fw-normal" style="font-size: 14px;">{{ thisDate(facturaDetalles.fechaAuthGasto) }}</label></label>
                  <label class="col-auto m-0 fs-8"> Fecha Autorización Pago: <label class="fw-normal" style="font-size: 14px;">{{ thisDate(facturaDetalles.fechaAuthPago) }}</label></label>
                </div>
              </div>
              <div class="modal-footer">
                <slot name="footer">
                  <button class="btn btn-alert float-end" @click="saveAuth()" :disabled="buttonDisable">Guardar</button>
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
import modalconfirmacion from '../../sistema/alerts/confirmationModal.vue'
import modalalerta from '../../sistema/alerts/alertModal.vue'
import {mixin} from '../../../../mixins.js'

export default {
  components: { modalconfirmacion, modalalerta },
  mixins: [mixin],
  data: () => ({
      buttonText:'Guardar Autorización de Pago',
      buttonDisable: false,
      mostrarCargaAutorizar: true,
      mostrarTablaAutorizar: false,
      facturasPagos: null,
      facturasAutorizar: null,
      autorizadores: null,
      errors: [],
      tablaFacturaAutorizar: null,
      facturaDetalleIsVisible: false,
      facturaDetalles: [],
      checkAll1: false,
      empresas: null,
      selectedCompany: null,
  }),
  created(){
    this.getEmpresas();
    this.getTipoDocFacturas();
    this.getAutorizadores();
    this.getFacturasAutorizables(this.userID);
  },
  methods: {
    manipularFacturas(){
      if(this.selectedCompany == "Todas"){
        this.facturasAutorizar = this.facturasPagos;
      }
      else{
        this.facturasAutorizar = this.facturasPagos.filter((dato)=>dato.idEmpresa == this.selectedCompany);
      }
    },
    getEmpresas(){
      axios.get('../api/empresas').then( response =>{
          this.empresas = response.data;
      }).catch(e=> console.log(e))
    },
    facturaDetalle(factura, accion){
      if(accion == "open"){
        this.facturaDetalleIsVisible = true;
        this.facturaDetalles = factura;
        document.getElementById('espaciadoTablaPagos').style.maxHeight = '300px';
      }
      if(accion == "close"){
        this.facturaDetalleIsVisible = false;
        this.facturaDetalles = [];
        document.getElementById('espaciadoTablaPagos').style.maxHeight = '500px';
      }
    },
    checkAll() {
      if(this.checkAll1 == false){
        this.facturasAutorizar.forEach(item => {
          if(item.autorizadoAuth1 == true){
            item.autorizadoAuth2 = true;
          }
          if(item.autorizadoAuth1 == null){
            item.autorizadoAuth1 = true;
          }
        });
        this.checkAll1 = true; 
      }else{
        this.facturasAutorizar.forEach(item => {
          if(item.autorizadoAuth1 == true && item.autorizadoAuth2 == true){
            item.autorizadoAuth2 = null;
          }
          if(item.autorizadoAuth1 == true && item.autorizadoAuth2 == null){
            item.autorizadoAuth1 = null;
          }
        });
        this.checkAll1 = false; 
      }
    },
    nullifyCheckbox(value) {
      return value ? value : null;
    },
    cerrarModal(){
        const elem = this.$refs.closeBtn;
        this.$emit('recarga');
        elem.click();
    },
    getAutorizadores(){
      axios.get(`api/autorizadores`).then( response =>{
        this.autorizadores = response.data.filter((dato)=>dato.levelName == 'facturas');
      }).catch(e=> console.log(e))
    },
    getFacturasAutorizables(userID){
      let datosGetAutorizables = {
        userID: userID,
        tipoAuth: "PAGOS",
      }
      axios.post(`api/facturas/autorizables`, datosGetAutorizables).then( response =>{true
          this.facturasPagos = response.data;
          this.facturasAutorizar = response.data;
          this.mostrarCargaAutorizar = false;
          this.mostrarTablaAutorizar = true;
      }).catch(e=> console.log(e))
    },
    async saveAuth() {
      const ok = await this.$refs.confirmation.show({
          title: 'Autorizar',
          message: `¿Está seguro/a que desea realizar estas autorizaciones? Esta acción no puede ser desecha.`,
          okButton: 'Autorizar',
          cancelButton: 'Volver'
      })
      if (ok) {
        axios.post('api/facturas/autorizar', this.facturasAutorizar).then( response => {
          console.log(response);
          this.buttonDisable = true;
          this.buttonText = 'Guardando...';
          this.toast.success("Autorizaciones exitosas!", {
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
          this.autorizadores.forEach(autorizador => {
            var $salida = [];
            var $temporal = [];
            this.facturasAutorizar.forEach(factura => {
              factura['tipoDocNombre'] = factura['tipoDocumento']+' - '+this.buscarTipoDoc(this.tiposDocFactura, factura.tipoDocumento);
              if(autorizador.id == factura['idAutorizador2'] && factura['autorizadoAuth1'] === "true"){
                $temporal.push(factura);
              }
            });
            if($temporal.length != 0){
              $salida.push($temporal);
              $salida.push('idAutorizador2');
              axios.post('api/facturas/sendEmailAutorizador2', $salida).then( response => {
                this.toast.success("Correo enviado a autorizador!", {
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
            }
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
    },
    getTipoDocFacturas(){
        axios.get('api/tipoDocFactura').then( response =>{
            this.tiposDocFactura = response.data;
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
        return nombre;
    },
  }
}
</script>