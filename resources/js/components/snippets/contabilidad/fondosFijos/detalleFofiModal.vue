<template>
  <transition name="modalver">
    <div name="modal">
      <div class="modal-mask">
        <div class="modal-wrapper">
          <div class="modal-container-xl">
            <div class="modal-header fw-bold" style="color: #444444;">
              <slot name="header">
                <label class="col-7 m-0" style="font-size: 17px;">Detalle del Fondo Fijo:</label>
                <label class="col-3 m-0" style="font-size: 15px; text-align: right;"> Estado:
                  <label v-if="fofi.estado == 'Pendiente'" class="fs-7 fw-bold" style="color: #FFC107">{{ fofi.estado }}</label>
                  <label v-if="fofi.estado == 'Rechazado'" class="fs-7 fw-bold" style="color: #B80F0A">{{ fofi.estado }}</label>
                  <label v-if="fofi.estado == 'Aprobado'" class="fs-7 fw-bold" style="color: #28A745">{{ fofi.estado }}</label>
                  <label v-if="fofi.estado == 'Retirado'" class="fs-7 fw-bold" style="color: #8392AB">{{ fofi.estado }}</label>
                </label>
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
                      <th class="text-uppercase text-xs font-weight-bolder">Cantidad Doc.</th>
                      <th class="text-uppercase text-xs font-weight-bolder">Fofi Asignado</th>
                      <th class="text-uppercase text-xs font-weight-bolder">Total Gastos</th>
                      <th class="text-uppercase text-xs font-weight-bolder">Diferencia</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td><p class="text-sm mb-0">{{ this.fofi1.folioFofi }}</p></td>
                      <td><p class="text-sm mb-0">{{ thisDate(this.fofi1.fecha) }}</p></td>
                      <td><p class="text-sm mb-0">{{ nombreUsuario }}</p></td>
                      <td><p class="text-sm mb-0">{{ this.fofi1.cantidadGastos }}</p></td>
                      <td><p class="text-sm mb-0">{{ formatMoneyCLP(this.fofi1.fofiAsignado) }}</p></td>
                      <td><p class="text-sm mb-0">{{ formatMoneyCLP(this.fofi1.montoTotal)  }}</p></td>
                      <td><p class="text-sm mb-0">{{ formatMoneyCLP(this.fofi1.fofiAsignado - this.fofi1.montoTotal) }}</p></td>
                    </tr>
                  </tbody>
                </table>
                <br>
                <div class="row">
                  <div class="col-6">
                    <label class="col-6 m-0 fs-8"> Observación: </label><textarea readonly style="resize:none;" name="textarea" class="form-control" rows="2" cols="20" v-model=this.fofi.observacion></textarea>
                  </div>
                  <div class="col-6">
                    <label class="col-6 m-0 fs-8"> Objeciones: </label><textarea readonly style="resize:none;" name="textarea" class="form-control" rows="2" cols="20" v-model=this.fofi.objeciones></textarea>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12">
                    <label class="col-6 m-0 fs-8"> Autorizador:
                      <label v-if="fofi.autorizador != null" class="fw-normal" style="font-size: 14px;">{{ fofi.autorizador.name }} </label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </label>
                  </div>
                  <br>
                  <hr size="4" class="separador">
                </div>
                <h6>Documentos Asociados al Fondo Fijo</h6>
                <div class="row">
                  <table id="tableDesign" class="table align-items-center mb-0">
                    <thead>
                      <tr>
                        <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                        <th class="text-uppercase text-xs font-weight-bolder">Tipo Doc.</th>
                        <th class="text-uppercase text-xs font-weight-bolder">Fecha</th>
                        <th class="text-uppercase text-xs font-weight-bolder">Fecha Retiro</th>
                        <th class="text-uppercase text-xs font-weight-bolder">N° Documento</th>
                        <th class="text-uppercase text-xs font-weight-bolder">RutDoc</th>
                        <th class="text-uppercase text-xs font-weight-bolder">Tipo Gasto</th>
                        <th class="text-uppercase text-xs font-weight-bolder">Gasto</th>
                        <th class="text-uppercase text-xs font-weight-bolder">Monto</th>
                        <th class="text-uppercase text-xs font-weight-bolder">Detalle</th>
                        <th class="text-uppercase text-xs font-weight-bolder">Foto</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr v-for="detalle in detalles" :key="detalle.nroDocumento">
                        <td><p class="text-sm mb-0">{{ detalle.id }}</p></td>    
                        <td><p class="text-sm mb-0">{{ detalle.tipo_doc.nombre }}</p></td>    
                        <td><p class="text-sm mb-0">{{ thisDate(detalle.fecha) }}</p></td>    
                        <td><p class="text-sm mb-0">{{ thisDate(detalle.fechaRetiro) }}</p></td>
                        <td v-if="detalle.nroDocumento != null"><p class="text-sm mb-0">{{ detalle.nroDocumento }}</p></td>   
                        <td v-else ><p class="text-sm mb-0">---</p></td> 
                        <td v-if="detalle.rut != null"><p class="text-sm mb-0">{{ detalle.rut }}</p></td>
                        <td v-else ><p class="text-sm mb-0">---</p></td>      
                        <td><p :title="detalle.tipo_gasto.nombre" style="width: 200px; word-wrap: break-word; overflow: hidden; text-overflow: ellipsis;" class="text-sm mb-0">{{ detalle.tipo_gasto.nombre }}</p></td>    
                        <td><p :title="detalle.gasto.nombre " class="text-sm mb-0" style="width: 200px; word-wrap: break-word; overflow: hidden; text-overflow: ellipsis;">{{ detalle.gasto.nombre }}</p></td>    
                        <td><p class="text-sm mb-0">{{ formatMoneyCLP(detalle.monto) }}</p></td>    
                        <td><p class="text-sm mb-0"><textarea readonly class="form-control" style="text-align: justify; min-width: 100px; height: 40px; word-wrap: break-word; overflow: hidden;" v-model="detalle.observaciones"></textarea></p></td>    
                        <td><p class="text-sm mb-0"><a class="btn btn-dark-grey btn-xs" @click="verFoto(detalle.foto)"><i class="fa-solid fa-images"></i></a></p></td>    
                      </tr>
                    </tbody>
                  </table>
                  <div>
                    <br>
                    <hr size="3" class="separador">
                  </div>
                </div>
              </slot>
            </div>
            <modalconfirmacion ref="confirmation"></modalconfirmacion>
            <modalalerta ref="alert"></modalalerta>
            <modalfoto v-bind:detalle="detalle" v-if="showFotoFofi" @close="showFotoFofi = false"></modalfoto>
          </div>
        </div>
      </div>
    </div>
  </transition>
</template>
  
<script>
  import modalconfirmacion from '../../sistema/alerts/confirmationModal.vue'
  import modalalerta from '../../sistema/alerts/alertModal.vue'
  import {mixin} from '../../../../mixins.js'
  import modalfoto from '../../fotoModal.vue'


  export default {
    components: { modalconfirmacion, modalalerta, modalfoto  },
    mixins: [mixin],
    data: () => ({
      fofi1: {
        folioFofi: "",
        fecha: "",
        usuario: "",
        observacion: "",
        cantidadGastos: "",
        fofiAsignado: "",
        montoTotal: "",
      },
      id: null,
      errors:[],
      detalles: [],
      nombreUsuario:"",
      detalle: null,
      showFotoFofi: false,
    }),
    props:{
      fofi: Object,
    },
    created(){
      this.fofi1.folioFofi = this.fofi.folioFofi;
      this.fofi1.fecha = this.fofi.fecha;
      this.fofi1.idUsuario = this.fofi.usuario;
      this.fofi1.observacion = this.fofi.observacion;
      this.fofi1.cantidadGastos = this.fofi.cantidadGastos;
      this.fofi1.fofiAsignado = this.fofi.fofiAsignado;
      this.fofi1.montoTotal = this.fofi.montoTotal;
      this.id = this.fofi.id;
      this.nombreUsuario = this.fofi1.idUsuario.name;
      this.detalles = this.fofi.detalles;
    },
    methods: {
      verFoto(detalle){
        this.detalle = detalle;
        this.showFotoFofi = true;
      },
    }
  }
</script>