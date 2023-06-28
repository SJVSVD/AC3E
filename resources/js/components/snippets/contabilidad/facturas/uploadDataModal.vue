<template>
  <transition name="modalcrear">
    <div name="modal">
      <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container-xl">
              <div class="modal-header fw-bold" style="color: #444444;">
                <slot v-if="isVisibleUpload" name="header">Subir Archivo:</slot>
                <slot v-if="isVisibleData" name="header">Detalle de Información a Subir:</slot>
                <slot v-if="isVisibleAuth" name="header">Seleccionar Autorizadores:</slot>
                <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
              </div>
              <div class="modal-body">
                <slot v-if="isVisibleUpload" name="body">
                  <div class="text-center" style="position: relative; top: 10%; min-height: 500px;">
                    <label style="font-size: 16px;" for="csv_file">Documento (.CSV) con Facturas:</label><br>
                    <label style="color: #b80f0a; font-size: 11px; font-weight: normal;" for="csv_file">El sistema está diseñado para aceptar<label style="color: #b80f0a;">SOLAMENTE</label> el archivo del SII.</label><br>
                    <select class="form-select-fit" style="top: 15%;" v-model="selectedCompany">
                      <option selected disabled :value="null">Seleccione empresa:</option>
                      <option v-for="empresa in empresas" :key="empresa.id" :value="empresa.id">
                        <label>{{ empresa.razonSocial }}</label>
                      </option>
                    </select> <br><br>
                    <form enctype="multipart/form-data">
                      <input style="width: 500px; margin-bottom: 15px; font-size: 12px;" type="file" name="csv_file" id="csv_file" accept=".csv" class="form-control"><br>
                      <a class="btn btn-danger btn-sm" @click="manipulateCSV(selectedCompany)">Subir archivo</a>
                    </form>
                    <label v-if="subidaExistosa && facturasPorSubir.length != 0 && firstButton == true">Archivo subido:<label style="font-size: 12px; font-weight: normal;">{{ documentCSV }}</label> <br></label>
                  </div>
                </slot>
                <!-- ###################################################################################################################################### -->
                <slot v-if="isVisibleData" name="body">
                  <div class="table-responsive p-0" id="espaciadoTablaUpload" style="max-height: 500px;">
                    <table class="table align-items-center mb-0" id="tableDesign">
                      <thead>
                        <tr style="color: black">
                          <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Rut</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Razón Social</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Folio</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Tipo Documento</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Fecha Documento</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Monto Neto</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Monto IVA Recup.</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Monto IVA no Recup.</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Monto Total</th>
                          <th class="text-uppercase text-xs font-weight-bolder">¿Autorizable?</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Acciones</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-if="facturasUpload != 0">
                          <td colspan="9"></td>
                          <td v-if="checkAllUpload == false"> <label>Marcar Todo:</label></td>
                          <td v-else> <label>Desmarcar Todo:</label></td>
                          <td><div class="form-check"><label class="form-check-label"><input class="form-check-input" @click="checkAll()" type="checkbox"></label></div></td>
                        </tr>
                        <tr v-if="facturasUpload == null">
                          <td colspan="11"> No hay información disponible. </td>
                        </tr>
                        <tr v-else v-for="factura in facturasPorSubir" :key="factura.id">
                          <td>
                            <p class="text-sm font-weight-bolder mb-0" style="color: black">{{ factura['Nro'] }}</p>
                          </td>
                          <td>
                            <p class="text-sm font-weight-bolder mb-0" style="color: black">{{ formatoRut(factura['RUT Proveedor']) }}</p>
                          </td>
                          <td>
                            <p :title="factura['Razon Social']" class="text-sm mb-0" style="width: 200px; word-wrap: break-word; overflow: hidden; text-overflow: ellipsis;">{{ factura['Razon Social'] }}</p>
                          </td>
                          <td>
                            <p class="text-sm font-weight-bolder mb-0" style="color: black">{{ factura['Folio'] }}</p>
                          </td>
                          <td>
                            <p :title="buscarTipoDoc(tiposDocFactura, factura['Tipo Doc'])" class="text-sm mb-0" style="width: 150px; word-wrap: break-word; overflow: hidden; text-overflow: ellipsis;">{{ buscarTipoDoc(tiposDocFactura, factura['Tipo Doc']) }}</p>
                          </td>
                          <td>
                            <p class="text-sm mb-0">{{ factura['Fecha Docto'] }}</p>
                          </td>
                          <td>
                            <p v-if="factura['Monto Neto']!=''" class="text-sm mb-0">{{ formatMoneyCLP(factura['Monto Neto']) }}</p>
                            <p v-else class="text-sm mb-0">{{ formatMoneyCLP(factura['Monto Neto']=0) }}</p>
                          </td>
                          <td>
                            <p v-if="factura['Monto IVA Recuperable']!=''" class="text-sm mb-0">{{ formatMoneyCLP(factura['Monto IVA Recuperable']) }}</p>
                            <p v-else class="text-sm mb-0">{{ formatMoneyCLP(factura['Monto IVA Recuperable']=0) }}</p>
                          </td>
                          <td>
                            <p v-if="factura['Monto Iva No Recuperable']!=''" class="text-sm mb-0">{{ formatMoneyCLP(factura['Monto Iva No Recuperable']) }}</p>
                            <p v-else class="text-sm mb-0">{{ formatMoneyCLP(factura['Monto Iva No Recuperable']=0) }}</p>
                          </td>
                          <td>
                            <p v-if="factura['Monto Total']!=''" class="text-sm mb-0">{{ formatMoneyCLP(factura['Monto Total']) }}</p>
                            <p v-else class="text-sm mb-0">{{ formatMoneyCLP(factura['Monto Total']=0) }}</p>
                          </td>
                          <td>
                            <div class="form-check">
                                <label class="form-check-label">
                                  <input type="checkbox" class="form-check-input" v-model="factura['Autorizable']">
                                </label>
                            </div>
                          </td>
                          <td class="align-middle text-end">
                            <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                <a class="btn btn-success btn-xs" title="Detalle" @click="facturaDetalle(factura, 'open')"><i class="fa-regular fa-eye"></i></a>
                            </div>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </slot>
                <!-- ###################################################################################################################################### -->
                <slot v-if="isVisibleAuth" name="body">
                  <div class="table-responsive p-0" id="espaciadoTablaUpload" style="max-height: 500px;">
                    <div v-show="showNullAutorizables" class="p-7" style="min-height: 500px;">
                      <label class="fs-4">No hay facturas autorizables, puede continuar.</label>
                    </div>
                    <table v-show="showTablaAutorizables" class="table align-items-center mb-0" id="tableDesign">
                      <thead>
                        <tr style="color: black">
                          <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Rut</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Razón Social</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Folio</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Monto Total</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Autorizador 1</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Autorizador 2</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Observaciones</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Acciones</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-if="autorizables == []">
                          <td colspan="8"> No hay información disponible. </td>
                        </tr>
                        <tr v-else v-for="autorizable in autorizables" :key="autorizable.id">
                          <td>
                            <p class="text-sm font-weight-bolder mb-0" style="color: black">{{ autorizable['Nro'] }}</p>
                          </td>
                          <td>
                            <p class="text-sm font-weight-bolder mb-0" style="color: black">{{ formatoRut(autorizable['RUT Proveedor']) }}</p>
                          </td>
                          <td>
                            <p :title="autorizable['Razon Social']" class="text-sm mb-0" style="width: 250px; word-wrap: break-word; overflow: hidden; text-overflow: ellipsis;">{{ autorizable['Razon Social'] }}</p>
                          </td>
                          <td>
                            <p class="text-sm font-weight-bolder mb-0" style="color: black">{{ autorizable['Folio'] }}</p>
                          </td>
                          <td>
                            <p class="text-sm mb-0">{{ formatMoneyCLP(autorizable['Monto Total']) }}</p>
                          </td>
                          <td>
                            <select class="form-select" v-model="autorizable['Autorizador1']">
                              <option selected :value="null">Seleccione autorizador:</option>
                              <option v-for="autorizador in searchAuth(autorizadores, autorizable['Monto Total'])" :key="autorizador.id" v-bind:value="autorizador.id">
                                <label>{{ autorizador.usuario.name }}</label>
                              </option>
                            </select>
                            <div v-if="errors && errors.Autorizador1 && autorizable['Autorizador1'] == null" class="error">
                              {{ errors.Autorizador1[0] }}
                            </div>
                          </td>
                          <td>
                            <select v-show="autorizable['Autorizador1'] != null" class="form-select" v-model="autorizable['Autorizador2']">
                              <option selected :value="null">Seleccione autorizador:</option>
                              <option v-for="autorizador in searchAuth(autorizadores, autorizable['Monto Total'], autorizable['Autorizador1'])" :key="autorizador.id" v-bind:value="autorizador.id">
                                <label>{{ autorizador.usuario.name }}</label>
                              </option>
                            </select>
                          </td>
                          <td>
                            <p class="text-sm mb-0">
                              <textarea type="text" class= "form-control" v-model="autorizable['Observaciones']" style="min-width: 200px; height: 40px; text-align: justify;"></textarea>
                            </p>
                          </td>
                          <td class="align-middle text-end">
                            <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                <a class="btn btn-success btn-xs" title="Detalle" @click="facturaDetalle(autorizable, 'open')"><i class="fa-regular fa-eye"></i></a>
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
                  <div style="font-weight: bold; color: #444444;">Detalles de la Factura '{{ facturaDetalles['Nro'] }}':</div>
                  <div class="text-uppercase">Información Esencial:</div>
                  <label class="col-auto m-0 fs-8"> Rut: <label class="fw-normal" style="font-size: 14px;">{{ facturaDetalles['RUT Proveedor'] }}</label></label>
                  <label class="col-auto m-0 fs-8"> Razón Social: <label class="fw-normal" style="font-size: 14px;">{{ facturaDetalles['Razon Social'] }}</label></label>
                  <label class="col-auto m-0 fs-8"> Folio: <label class="fw-normal" style="font-size: 14px;">{{ facturaDetalles['Folio'] }}</label></label>
                  <label class="col-auto m-0 fs-8"> Empresa: <label class="fw-normal" style="font-size: 14px;">{{ empresaSeleccionada }} </label></label>
                  <label class="col-auto m-0 fs-8"> Tipo Documento: <label class="fw-normal" style="font-size: 14px;">{{ facturaDetalles['Tipo Doc'] }} - {{ buscarTipoDoc(tiposDocFactura, facturaDetalles['Tipo Doc']) }}</label></label>
                  <label class="col-auto m-0 fs-8"> Tipo Compra: <label class="fw-normal" style="font-size: 14px;">{{ facturaDetalles['Tipo Compra'] }}</label></label>
                  <div class="text-uppercase">Montos:</div>
                  <label class="col-auto m-0 fs-8"> Monto IVA Recup.: <label class="fw-normal" style="font-size: 14px;">{{ formatMoneyCLP(facturaDetalles['Monto IVA Recuperable']) }}</label></label>
                  <label class="col-auto m-0 fs-8"> Monto IVA no Recup.: <label class="fw-normal" style="font-size: 14px;">{{ formatMoneyCLP(facturaDetalles['Monto Iva No Recuperable']) }}</label></label>
                  <label class="col-auto m-0 fs-8"> Monto IVA no Retenido: <label class="fw-normal" style="font-size: 14px;">{{ formatMoneyCLP(facturaDetalles['IVA No Retenido']) }}</label></label>
                  <label class="col-auto m-0 fs-8"> Monto Exento: <label class="fw-normal" style="font-size: 14px;">{{ formatMoneyCLP(facturaDetalles['Monto Exento']) }}</label></label>
                  <label class="col-auto m-0 fs-8"> Monto Neto: <label class="fw-normal" style="font-size: 14px;">{{ formatMoneyCLP(facturaDetalles['Monto Neto']) }}</label></label>
                  <label class="col-auto m-0 fs-8"> Monto Total: <label class="fw-normal" style="font-size: 14px;">{{ formatMoneyCLP(facturaDetalles['Monto Total']) }}</label></label>
                  <label class="col-auto m-0 fs-8"> NCE o NDE: <label class="fw-normal" style="font-size: 14px;">{{ formatMoneyCLP(facturaDetalles['NCE o NDE sobre Fact. de Compra']) }}</label></label>
                  <label class="col-auto m-0 fs-8"> Código Otro Imp.: <label class="fw-normal" style="font-size: 14px;">{{ facturaDetalles['Codigo Otro Impuesto'] }}</label></label>
                  <label class="col-auto m-0 fs-8"> Valor Otro Imp.: <label class="fw-normal" style="font-size: 14px;">{{ formatMoneyCLP(facturaDetalles['Valor Otro Impuesto']) }}</label></label>
                  <label class="col-auto m-0 fs-8"> Tasa Otro Imp.: <label class="fw-normal" style="font-size: 14px;">{{ formatMoneyCLP(facturaDetalles['Tasa Otro Impuesto']) }}</label></label>
                  <div class="text-uppercase">Fechas:</div>
                  <label class="col-auto m-0 fs-8"> Fecha Documento: <label class="fw-normal" style="font-size: 14px;">{{ facturaDetalles['Fecha Docto'] }}</label></label>
                  <label class="col-auto m-0 fs-8"> Fecha Recepción: <label class="fw-normal" style="font-size: 14px;">{{ facturaDetalles['Fecha Recepcion'] }}</label></label>
                  <label class="col-auto m-0 fs-8"> Fecha Acuse: <label class="fw-normal" style="font-size: 14px;">{{ facturaDetalles['Fecha Acuse'] }}</label></label>
                </div>
              </div>
              <div class="modal-footer">
                <slot name="footer">
                  <a v-if="isVisibleUpload && firstButton && facturasPorSubir.length != 0" class="btn btn-alert float-end" @click="facturaDetalleIsVisible = false; isVisibleUpload = false; isVisibleData = true; isVisibleAuth = false;">Continuar</a>
                  <a v-if="isVisibleData" class="btn btn-alert float-end" @click="facturaDetalleIsVisible = false; isVisibleUpload = false; isVisibleData = false; isVisibleAuth = true; setAutorizables();">Continuar</a>
                  <button v-if="isVisibleAuth" class="btn btn-alert float-end" @click="facturaDetalleIsVisible = false; saveFacturas();" :disabled="buttonDisable">Guardar</button>
                  <a v-if="isVisibleData" class="btn btn-alert float-start" @click="facturaDetalleIsVisible = false; isVisibleUpload = true; isVisibleData = false; isVisibleAuth = false; firstButton = false;">Volver</a>
                  <a v-if="isVisibleAuth" class="btn btn-alert float-start" @click="facturaDetalleIsVisible = false; isVisibleUpload = false; isVisibleData = true; isVisibleAuth = false;">Volver</a>
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
      facturasUpload: null,
      autorizadores: null,
      empresaSeleccionada: null,
      empresas: null,
      proveedores: null,
      autorizables: [],
      showNullAutorizables: true,
      showTablaAutorizables: false,
      isVisibleUpload: true,
      firstButton: false,
      secondButton: false,
      thirdButton: false,
      isVisibleData: false,
      ingresarEmpresa: false,
      isVisibleAuth: false,
      documentCSV: null,
      subidaExistosa: false,
      facturasPorSubir: [],
      condicional: null,
      errors: [],
      facturaDetalleIsVisible: false,
      facturaDetalles: [],
      checkAllUpload: false,
  }),
  created(){
    this.getAutorizadores();
    this.getEmpresas();
    this.getProveedores();
    this.getFacturas();
    this.getTipoDocFacturas();
  },
  methods: {
    facturaDetalle(factura, accion){
      if(accion == "open"){
        this.facturaDetalleIsVisible = true;
        this.facturaDetalles = factura;
        document.getElementById('espaciadoTablaUpload').style.maxHeight = '300px';
      }
      if(accion == "close"){
        this.facturaDetalleIsVisible = false;
        this.facturaDetalles = [];
        document.getElementById('espaciadoTablaUpload').style.maxHeight = '500px';
      }
    },
    checkAll() {
      if(this.checkAllUpload == false){
        this.facturasPorSubir.forEach(item => {
          item['Autorizable'] = true;
        });
        this.checkAllUpload = true; 
      }else{
        this.facturasPorSubir.forEach(item => {
          item['Autorizable'] = false;
        });
        this.checkAllUpload = false; 
      }
    },
    cerrarModal(){
        const elem = this.$refs.closeBtn;
        this.$emit('recarga');
        elem.click();
    },
    buscarTipoDoc(tiposDocFac,facTipoDoc){    
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
    searchAuth(autorizadores, montoTot, autorizador1){
      var autor = autorizadores;
      if(autorizador1 != null){
        autor.filter((dato)=>dato.id == autorizador1.id);
      }
      if(montoTot >= 100000){
        autor = autor.filter((dato)=>dato.level == true);
      }
      else{
        autor = autor;
      }
      return autor;
    },
    getProveedores(){
      axios.get(`api/proveedores`).then( response =>{
        this.proveedores = response.data;
      }).catch(e=> console.log(e))
    },
    getAutorizadores(){
      axios.get(`api/autorizadores`).then( response =>{
        this.autorizadores = response.data.filter((dato)=>dato.levelName == 'facturas');
      }).catch(e=> console.log(e))
    },
    getEmpresas(){
      axios.get(`api/empresas`).then( response =>{
        this.empresas = response.data;
      }).catch(e=> console.log(e))
    },
    getFacturas(){
      axios.get('api/facturas').then( response =>{
          this.facturasUpload = response.data;
      }).catch(e=> console.log(e))
    },
    getTipoDocFacturas(){
        axios.get('api/tipoDocFactura').then( response =>{
            this.tiposDocFactura = response.data;
        }).catch(e=> console.log(e))
    },
    manipulateCSV(empresa){
      this.subidaExistosa = false;
      if(empresa != null){
        if(document.querySelector('#csv_file').files.item(0) != null){
          this.empresaSeleccionada = empresa;
          this.facturasPorSubir = [];
          var formData = new FormData();
          this.documentCSV = document.getElementById('csv_file').files.item(0).name;
          var csvFile = document.querySelector('#csv_file');
          formData.append("csv_file", csvFile.files[0]);
          axios.post('api/facturas/manipularCSV', formData).then( response => {
            this.temporal = response.data;
            this.temporal.forEach(facturaTemp => {
              this.condicional = false;
              this.facturasUpload.forEach(factura => {
                if (facturaTemp['idFactura'] == factura.idFactura){
                  this.condicional = true;
                }
              });
              if (this.condicional == false){
                  this.facturasPorSubir.push(facturaTemp);
              }
            });
            this.firstButton = true;
            this.subidaExistosa = true;
            if(this.facturasPorSubir.length == 0){
              this.toast('No se han encontrado nuevos registros!', {
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
            else{
              this.toast.success('Se ha subido con éxito!', {
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
          }).catch(e=> console.log(e), this.subidaExistosa = false)
        }
        else{
          this.toast.warning('Seleccione un archivo.', {
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
        this.toast.warning('Seleccione una empresa.', {
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
    },
    setAutorizables(){
      this.buttonText = 'Guardando...';
      this.autorizables = [];
      this.showNullAutorizables = true;
      this.showTablaAutorizables = false;
      this.facturasPorSubir.forEach(factura => {
        factura['tipoDocNombre'] = factura['Tipo Doc']+' - '+this.buscarTipoDoc(this.tiposDocFactura, factura['Tipo Doc']);
        if (factura['Autorizable'] == true) {
          this.autorizables.push(factura);
          this.showNullAutorizables = false;
          this.showTablaAutorizables = true;
        }
        // Verificar si el proveedor posee autorizadores asociados, si los tiene los añade:
        this.proveedores.forEach(proveedor => {
          if(factura['RUT Proveedor'] == proveedor['rut']){
            if(proveedor['idAuthAso1'] != null){
              factura['Autorizador1'] = proveedor['idAuthAso1'];
            }
            if(proveedor['idAuthAso2'] != null){
              factura['Autorizador2'] = proveedor['idAuthAso2'];
            }
          }
        });
      });
    },
    async saveFacturas(){
      const ok = await this.$refs.confirmation.show({
        title: 'Subir datos',
        message: `¿Está seguro/a? Esta acción no puede ser desecha.`,
        okButton: 'Subir',
        cancelButton: 'Volver'
      })
      if(ok) {
        const arrayTemp = [];
        arrayTemp.push(this.facturasPorSubir);
        arrayTemp.push(this.userID);
        arrayTemp.push(this.empresaSeleccionada);
        axios.post('api/facturas', arrayTemp).then( response => {
          this.buttonDisable = true;
          this.buttonText = 'Guardando...';
          this.errors = [];
          this.toast.success("Información subida con éxito!", {
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
          axios.post('api/facturas/sendEmailAdministrador', this.autorizables).then( response => {
            this.toast.success("Correo enviado a administración!", {
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
          this.autorizadores.forEach(autorizador => {
            var $salida = [];
            var $temporal = [];
            this.autorizables.forEach(factura => {
              factura['tipoDocNombre'] = factura['Tipo Doc']+' - '+this.buscarTipoDoc(this.tiposDocFactura, factura['Tipo Doc']);
              if(autorizador.id == factura['Autorizador1']){
                $temporal.push(factura);
              }
            });
            if($temporal.length != 0){
              $salida.push($temporal);
              $salida.push('Autorizador1');
              axios.post('api/facturas/sendEmailAutorizadorCSV', $salida).then( response => {
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
  }
}
</script>