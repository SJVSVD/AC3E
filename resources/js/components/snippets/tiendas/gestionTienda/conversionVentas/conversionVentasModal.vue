<template>
  <transition name="modalcrear">
    <div name="modal">
      <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container-xl">
              <div class="modal-header" style="font-weight: bold; color: #444444;">
                <slot name="header">
                  <label class="col-7 m-0" style="font-size: 17px;">Conversión Ventas:</label>
                </slot>
                <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
              </div>
              <div class="modal-body">
                <slot name="body">
                  <div class="text-uppercase">Filtros:</div>
                  <div class="row">
                      <div class="col-3">
                          <label>Fecha Inicio:
                              <input type="date" class="form-control form-control-sm" placeholder="Fecha Inicial" v-model="fechaInicio">
                          </label>   
                      </div>
                      <div class="col-3">
                          <label>Fecha Término:
                              <input type="date" class="form-control form-control-sm" placeholder="Fecha Termino" v-model="fechaTermino">
                          </label>
                      </div>
                      <div class="col-1 pt-3">
                          <a class="btn btn-search-blue" @click="aplicarFiltro()"><i class="fa-solid fa-magnifying-glass"></i></a>
                      </div>
                  </div>
                  <br>
                  <hr size="4" class="separador">
                  <p class="text-uppercase fw-bold">Detalle Mensual:</p>
                  <div class="table-responsive p-0">
                    <div v-show="mostrarCargaMensual" class="loader-sm"></div>
                    <table v-show="mostrarTablaMensual" class="table align-items-center mb-0" id="tableDesign">
                      <thead>
                        <tr style="color: black">
                          <th class="text-uppercase text-xs font-weight-bolder">Tienda</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Fecha</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Meta Tienda</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Venta</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Cumplimiento</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Tasa Conversión</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Entradas</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Documentos</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Total Artículos</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Art. por Doc.</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Promedio por Doc.</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-if="conversionesVentaMensual == 0">
                          <td colspan="10"> No hay información disponible. </td>
                        </tr>
                        <tr v-else v-for="conversionVentaMensual in conversionesVentaMensual" :key="conversionVentaMensual.id">
                          <td>
                            <p class="text-sm font-weight-bolder mb-0" style="color: black">{{ conversionVentaMensual.tienda.nombre }}</p>
                          </td>
                          <td>
                              <p class="text-sm mb-0">{{ conversionVentaMensual.mes.toString().padStart(2, "0") + "-" + conversionVentaMensual.año }}</p>
                            </td>
                          <td>
                            <p class="text-sm mb-0">{{ formatMoneyCLP(conversionVentaMensual.meta_total) }}</p>
                          </td>
                          <td>
                            <p class="text-sm mb-0">{{ formatMoneyCLP(conversionVentaMensual.meta_total_acumulada_mensual) }}</p>
                          </td>
                          <td>
                            <p v-if= "((conversionVentaMensual.meta_total_acumulada_mensual*100)/conversionVentaMensual.meta_total).toFixed(2) < 100" class="text-sm font-weight-bolder mb-0" style="color: #D21610;">{{ ((conversionVentaMensual.meta_total_acumulada_mensual*100)/conversionVentaMensual.meta_total).toFixed(2) + "%" }}</p>
                              <p v-else class= "text-sm font-weight-bolder mb-0" style="color: #3574EA;;">{{ ((conversionVentaMensual.meta_total_acumulada_mensual*100)/conversionVentaMensual.meta_total).toFixed(2) + "%" }}</p>
                          </td>
                          <td>
                            <p class="text-sm mb-0">{{ ((valorTemporalDoc*100)/conversionVentaMensual.entradas).toFixed(2) + "%" }}</p>
                          </td>
                          <td>
                            <p class="text-sm mb-0">{{ conversionVentaMensual.entradas }}</p>
                          </td>
                          <td>
                            <p class="text-sm mb-0">{{ valorTemporalDoc }}</p>
                          </td>
                          <td>
                            <p class="text-sm mb-0">{{ valorTemporalArticulos }}</p>
                          </td>
                          <td>
                            <p class="text-sm mb-0">{{ valorTemporalArticulos/valorTemporalDoc }}</p>
                          </td>
                          <td>
                            <p class="text-sm mb-0">{{ formatMoneyCLP(conversionVentaMensual.meta_total_acumulada_mensual/valorTemporalDoc) }}</p>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                  <br>
                  <hr size="4" class="separador">
                  <div>
                    <p v-if="verificadoTienda == true" class="text-uppercase fw-bold">Detalle Diario Tienda:</p>
                    <p v-else class="text-uppercase fw-bold">Detalle Diario por Tienda:</p>
                    <div class="table-responsive p-0">
                      <div v-show="mostrarCargaDiaria" class="loader-sm"></div>
                      <table v-show="mostrarTablaDiaria" class="table align-items-center mb-0" id="tableDesign">
                        <thead>
                          <tr style="color: black">
                            <th class="text-uppercase text-xs font-weight-bolder">Tienda</th>
                            <th class="text-uppercase text-xs font-weight-bolder">Meta Dia</th>
                            <th class="text-uppercase text-xs font-weight-bolder">Fecha</th>
                            <th class="text-uppercase text-xs font-weight-bolder">Venta</th>
                            <th class="text-uppercase text-xs font-weight-bolder">Cumplimiento</th>
                            <th class="text-uppercase text-xs font-weight-bolder">Tasa Conversión</th>
                            <th class="text-uppercase text-xs font-weight-bolder">Entradas</th>
                            <th class="text-uppercase text-xs font-weight-bolder">Documentos</th>
                            <th class="text-uppercase text-xs font-weight-bolder">Total Artículos</th>
                            <th class="text-uppercase text-xs font-weight-bolder">Art. por Doc.</th>
                            <th class="text-uppercase text-xs font-weight-bolder">Promedio por Doc.</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-if="conversionesVentaDiaria == 0">
                            <td colspan="11"> No hay información disponible. </td>
                          </tr>
                          <tr v-for="conversionVentaDiaria in conversionesVentaDiaria" :key="conversionVentaDiaria.id">
                            <td>
                              <p class="text-sm font-weight-bolder mb-0" style="color: black">{{ conversionVentaDiaria.tienda.nombre }}</p>
                            </td>
                            <td>
                              <p class="text-sm mb-0">{{ formatMoneyCLP(conversionVentaDiaria.meta_total) }}</p>
                            </td>
                            <td>
                              <p class="text-sm mb-0">{{ conversionVentaDiaria.dia.toString().padStart(2, "0") + "-" + conversionVentaDiaria.mes.toString().padStart(2, "0") + "-" + conversionVentaDiaria.año }}</p>
                            </td>
                            <td>
                              <p class="text-sm mb-0">{{ formatMoneyCLP(conversionVentaDiaria.meta_total_acumulada_diaria) }}</p>
                            </td>
                            <td>
                              <p v-if= "((conversionVentaDiaria.meta_total_acumulada_diaria*100)/conversionVentaDiaria.meta_total).toFixed(2) < 100" class="text-sm font-weight-bolder mb-0" style="color: #D21610;">{{ ((conversionVentaDiaria.meta_total_acumulada_diaria*100)/conversionVentaDiaria.meta_total).toFixed(2) + "%" }}</p>
                              <p v-else class= "text-sm font-weight-bolder mb-0" style="color: #3574EA;;">{{ ((conversionVentaDiaria.meta_total_acumulada_diaria*100)/conversionVentaDiaria.meta_total).toFixed(2) + "%" }}</p>
                            </td>
                            <td>
                              <p class="text-sm mb-0">{{ ((valorTemporalDoc*100)/conversionVentaDiaria.entradas).toFixed(2) + "%" }}</p>
                            </td>
                            <td>
                              <p class="text-sm mb-0">{{ conversionVentaDiaria.entradas }}</p>
                            </td>
                            <td>
                              <p class="text-sm mb-0">{{ valorTemporalDoc }}</p>
                            </td>
                            <td>
                              <p class="text-sm mb-0">{{ valorTemporalArticulos }}</p>
                            </td>
                            <td>
                              <p class="text-sm mb-0">{{ valorTemporalArticulos/valorTemporalDoc  }}</p>
                            </td>
                            <td>
                              <p class="text-sm mb-0">{{ formatMoneyCLP(conversionVentaDiaria.meta_total_acumulada_diaria/valorTemporalDoc) }}</p>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <hr size="4" class="separador">
                  <div v-if="verificadoTienda == true">
                    <p class="text-uppercase fw-bold">Detalle Mensual por Colaborador:</p>
                    <div class="table-responsive p-0">
                      <div v-show="mostrarCargaDiaria" class="loader-sm"></div>
                      <table v-show="mostrarTablaDiaria" class="table align-items-center mb-0" id="tableDesign">
                        <thead>
                          <tr style="color: black">
                            <th class="text-uppercase text-xs font-weight-bolder">Colaborador</th>
                            <th class="text-uppercase text-xs font-weight-bolder">Fecha</th>
                            <th class="text-uppercase text-xs font-weight-bolder">Meta Dia</th>
                            <th class="text-uppercase text-xs font-weight-bolder">Venta</th>
                            <th class="text-uppercase text-xs font-weight-bolder">Cumplimiento</th>
                            <th class="text-uppercase text-xs font-weight-bolder">Tasa Conversión</th>
                            <th class="text-uppercase text-xs font-weight-bolder">Entradas</th>
                            <th class="text-uppercase text-xs font-weight-bolder">Documentos</th>
                            <th class="text-uppercase text-xs font-weight-bolder">Total Artículos</th>
                            <th class="text-uppercase text-xs font-weight-bolder">Art. por Doc.</th>
                            <th class="text-uppercase text-xs font-weight-bolder">Promedio por Doc.</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-if="conversionesVentaColaboradores == 0">
                            <td colspan="11"> No hay información disponible. </td>
                          </tr>
                          <tr v-for="conversionVentaColaboradores in conversionesVentaColaboradores" :key="conversionVentaColaboradores.id">
                            <td>
                              <p class="text-sm font-weight-bolder mb-0" style="color: black">{{ conversionVentaColaboradores.vendedor.nombre1 +" "+ conversionVentaColaboradores.vendedor.nombre2 +" "+ conversionVentaColaboradores.vendedor.apellido1 +" "+ conversionVentaColaboradores.vendedor.apellido2 }}</p>
                            </td>
                            <td>
                              <p class="text-sm mb-0">{{ conversionVentaColaboradores.mes.toString().padStart(2, "0") + "-" + conversionVentaColaboradores.año }}</p>
                            </td>
                            <td>
                              <p class="text-sm mb-0">{{ formatMoneyCLP(conversionVentaColaboradores.meta_total) }}</p>
                            </td>
                            <td>
                              <p class="text-sm mb-0">{{ formatMoneyCLP(conversionVentaColaboradores.meta_total_acumulada_vendedor) }}</p>
                            </td>
                            <td>
                              <p v-if= "((conversionVentaColaboradores.meta_total_acumulada_vendedor*100)/conversionVentaColaboradores.meta_total).toFixed(2) < 100" class="text-sm font-weight-bolder mb-0" style="color: #D21610;">{{ ((conversionVentaColaboradores.meta_total_acumulada_vendedor*100)/conversionVentaColaboradores.meta_total).toFixed(2) + "%" }}</p>
                              <p v-else class= "text-sm font-weight-bolder mb-0" style="color: #3574EA;;">{{ ((conversionVentaColaboradores.meta_total_acumulada_vendedor*100)/conversionVentaColaboradores.meta_total).toFixed(2) + "%" }}</p>
                            </td>
                            <td>
                              <p class="text-sm mb-0">{{ ((valorTemporalDoc*100)/conversionVentaColaboradores.entradas).toFixed(2) + "%" }}</p>
                            </td>
                            <td>
                              <p class="text-sm mb-0">{{ conversionVentaColaboradores.entradas }}</p>
                            </td>
                            <td>
                              <p class="text-sm mb-0">{{ valorTemporalDoc }}</p>
                            </td>
                            <td>
                              <p class="text-sm mb-0">{{ valorTemporalArticulos }}</p>
                            </td>
                            <td>
                              <p class="text-sm mb-0">{{ valorTemporalArticulos/valorTemporalDoc }}</p>
                            </td>
                            <td>
                              <p class="text-sm mb-0">{{ formatMoneyCLP(conversionVentaColaboradores.meta_total_acumulada_vendedor/valorTemporalDoc) }}</p>
                            </td>
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
import moment from "moment/min/moment-with-locales";
moment.locale("es");

export default {
  components: { modalconfirmacion, modalalerta },
  mixins: [mixin],
  data: () => ({
    conversionesVentaMensual: null,
    conversionesVentaDiaria: null,
    conversionesVentaColaboradores: null,
    mostrarCargaMensual: true,
    mostrarTablaMensual: false,
    mostrarCargaDiaria: true,
    mostrarTablaDiaria: false,
    mostrarCargaColaboradores: true,
    mostrarTablaColaboradores: false,
    valorTemporalVenta: 13000000, //conversionVenta.venta, conversionVentaDiaria.venta,  conversionVentaColaboradores.venta
    valorTemporalDoc: 20, //conversionVenta.documentos, conversionVentaDiaria.documentos, conversionVentaColaboradores.documentos
    valorTemporalArticulos: 1000, //conversionVenta.cantidadArticulos, conversionVentaDiaria.cantidadArticulos, conversionVentaColaboradores.articulos
    // Filtros:
    fechaInicio: moment().format("YYYY-MM-DD"),
    fechaTermino: moment().format("YYYY-MM-DD"),
    idTienda: null,
    verificadoTienda: false,
  }),
  created(){
    this.getTiendas();
  },
  methods: {
    getTiendas(){
      axios.get('api/getTiendas').then( response =>{
        this.tiendas = response.data;
        this.tiendas.forEach(tienda => {
          if(tienda.idUsuario == this.userID){
            this.idTienda = tienda.id;
            this.verificadoTienda = true;
          }
        })
      }).catch(e=> console.log(e))
    },
    getConversionMensuales(){
      let datos1 = {
        idTienda: this.idTienda,
        mesInicio: moment(this.fechaInicio).format('MM').toString().padStart(2, "0"),
        añoInicio: moment(this.fechaInicio).format('YYYY'),
        mesTermino: moment(this.fechaTermino).format('MM').toString().padStart(2, "0"),
        añoTermino: moment(this.fechaTermino).format('YYYY'),
      }
      axios.post(`api/metasTienda/getMetasMensualesTiendasRango`, datos1).then( response =>{
        console.log(response.data);
        this.conversionesVentaMensual = response.data;
        this.mostrarCargaMensual = false;
        this.mostrarTablaMensual = true;
      }).catch(e=> console.log(e))
    },
    getConversionDiarias(){
      let datos2 = {
        idTienda: this.idTienda,
        diaInicio: moment(this.fechaInicio).format('DD').toString().padStart(2, "0"),
        mesInicio: moment(this.fechaInicio).format('MM').toString().padStart(2, "0"),
        añoInicio: moment(this.fechaInicio).format('YYYY'),
        diaTermino: moment(this.fechaTermino).format('DD').toString().padStart(2, "0"),
        mesTermino: moment(this.fechaTermino).format('MM').toString().padStart(2, "0"),
        añoTermino: moment(this.fechaTermino).format('YYYY'),
      }
      axios.post(`api/metasDiarias/getMetasDiariasTiendasRango`, datos2).then( response =>{
        console.log(response.data);
        this.conversionesVentaDiaria = response.data;
        this.mostrarCargaDiaria = false;
        this.mostrarTablaDiaria = true;
      }).catch(e=> console.log(e))
    },
    getConversionColaboradores(){
      let datos3 = {
        idTienda: this.idTienda,
        mesInicio: moment(this.fechaInicio).format('MM').toString().padStart(2, "0"),
        añoInicio: moment(this.fechaInicio).format('YYYY'),
        mesTermino: moment(this.fechaTermino).format('MM').toString().padStart(2, "0"),
        añoTermino: moment(this.fechaTermino).format('YYYY'),
      }
      axios.post(`api/metasColaborador/getMetasColaboradoresTiendasRango`, datos3).then( response =>{
        console.log(response.data);
        this.conversionesVentaColaboradores = response.data;
        this.mostrarCargaColaboradores = false;
        this.mostrarTablaColaboradores = true;
      }).catch(e=> console.log(e))
    },
    recargarTabla(){
      this.mostrarCargaMensual = true;
      this.mostrarCargaDiaria = true;
      this.mostrarCargaColaboradores = true;
      this.getConversionMensuales();
      this.getConversionDiarias();
      this.getConversionColaboradores();
    },
    aplicarFiltro(){
      this.recargarTabla();
    },
  }
}
</script>