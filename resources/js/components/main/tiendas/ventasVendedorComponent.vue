<template>
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="row pb-0 p-4">
                    <div class="col-9">
                        <div class="row">
                            <div class="col-auto">
                                <label>Año:
                                    <select class="form-control form-control-sm" placeholder="Año" v-model="fechaAño">
                                        <option selected disabled value="">Seleccione un año:</option>
                                        <option v-for="año in años" v-bind:key="año.value" v-bind:value="año.value">
                                            {{ año.value }}
                                        </option>
                                    </select>
                                </label>   
                            </div>
                            <div class="col-auto">
                                <label>Mes:
                                    <select class="form-control form-control-sm" placeholder="Mes" v-model="fechaMes">
                                        <option selected disabled value="">Seleccione un mes:</option>
                                        <option v-for="mes in meses" v-bind:key="mes.value" v-bind:value="mes.value">
                                            {{ mes.nombre }}
                                        </option>
                                    </select>
                                </label>
                            </div>
                            <div class="col-auto">
                                <label>Día:
                                    <select class="form-control form-control-sm" placeholder="Día" v-model="fechaDia">
                                        <option selected disabled value="">Seleccione un día:</option>
                                        <option v-for="dia in dias" v-bind:key="dia.value" v-bind:value="dia.value">
                                            {{ dia.value }}
                                        </option>
                                    </select>
                                </label>   
                            </div>
                            <div class="col-auto">
                                <label>Tienda:
                                    <select class="form-control form-control-sm" placeholder="Tienda" v-model="filtroTienda" :disabled="verificadoTienda">
                                        <option selected disabled value="">Seleccione una tienda:</option>
                                        <option v-for="tienda in tiendas" v-bind:key="tienda.id" v-bind:value="tienda.id">
                                            {{ tienda.nombre }}
                                        </option>
                                    </select>
                                </label>    
                            </div>
                            <div class="col-auto pt-3">
                                <a class="btn btn-search-blue" @click="aplicarFiltro()"><i class="fa-solid fa-magnifying-glass"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-3">
                        <div class="d-flex justify-content-end">
                            <a class="btn btn-spacing btn-search-blue" @click="recargarTabla()"><i class="fa-solid fa-rotate"></i></a>
                        </div>
                    </div>
                </div>
                <hr size="4" class="separador">
                <div class="card-body px-0 pt-0 pb-2" style="min-height: 400px">
                    <div class="container">
                        <label class="col-7 m-0" style="font-size: 17px;">Meta Acumulada en el Mes</label>
                        <div class="table-responsive p-0">
                            <div v-show="mostrarCargaMensual" class="loader-sm"></div>
                            <table v-show="mostrarTablaMensual" class="table align-items-center mb-0" id="tableDesign">
                                <thead>
                                    <tr style="color: black">
                                        <th class="text-uppercase text-xs font-weight-bolder">Meta Mensual</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Ventas Acumuladas</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Ventas Faltantes</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Porcentaje de Cumplimiento</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Días Restantes del Mes</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="metaTiendaMensual == null">
                                        <td colspan="6"> No hay información disponible. </td>
                                    </tr>
                                    <tr v-else>
                                        <td>
                                            <p v-if="metaTiendaMensual.meta_total != null" class="text-sm mb-0">{{ formatMoneyCLP(metaTiendaMensual.meta_total) }}</p>
                                            <p v-else class="text-sm mb-0">{{ formatMoneyCLP(0) }}</p>
                                        </td>
                                        <td>
                                            <p v-if="metaTiendaMensual.meta_total_acumulada_mensual != null" class="text-sm mb-0">{{ formatMoneyCLP(metaTiendaMensual.meta_total_acumulada_mensual) }}</p>
                                            <p v-else class="text-sm mb-0">{{ formatMoneyCLP(0) }}</p>
                                        </td>
                                        <td>
                                            <p v-if="metaTiendaMensual.meta_total != null && metaTiendaMensual.meta_total_acumulada_mensual != null && (meta_total_acumulada < metaTiendaMensual.meta_total) " class="text-sm mb-0">{{ formatMoneyCLP(metaTiendaMensual.meta_total - metaTiendaMensual.meta_total_acumulada_mensual) }}</p>
                                            <p v-else-if="metaTiendaMensual.meta_total != null && metaTiendaMensual.meta_total_acumulada_mensual != null && (meta_total_acumulada > metaTiendaMensual.meta_total)" class="text-sm mb-0">{{ formatMoneyCLP(0) }}</p>
                                            <p v-else class="text-sm mb-0">{{ formatMoneyCLP(0) }}</p>
                                        </td>
                                        <td>
                                            <p v-if="metaTiendaMensual.meta_total != null && metaTiendaMensual.meta_total_acumulada_mensual != null && ((metaTiendaMensual.meta_total_acumulada_mensual*100) / metaTiendaMensual.meta_total).toFixed(3) > 100" class="text-sm font-weight-bolder mb-0" style="color: #3574EA;">{{ ((metaTiendaMensual.meta_total_acumulada_mensual*100) / metaTiendaMensual.meta_total).toFixed(3) + '%'}}</p>
                                            <p v-else-if="metaTiendaMensual.meta_total != null && metaTiendaMensual.meta_total_acumulada_mensual != null && ((metaTiendaMensual.meta_total_acumulada_mensual*100) / metaTiendaMensual.meta_total).toFixed(3) < 100" class="text-sm font-weight-bolder mb-0" style="color: #D21610;">{{ ((metaTiendaMensual.meta_total_acumulada_mensual*100) / metaTiendaMensual.meta_total).toFixed(3) + '%'}}</p>
                                            <p v-else class="text-sm font-weight-bolder mb-0" style="color: #D21610">{{'0%'}}</p>
                                        </td>
                                        <td>
                                            <p v-if="fechaMes != null && fechaDia != null" class="text-sm mb-0">{{ daysInMonth(fechaMes) - fechaDia }}</p>
                                            <p v-else class="text-sm mb-0">-----</p>
                                        </td>
                                        <td class="align-middle text-end">
                                            <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                <a class="btn btn-success btn-xs" title="Detalle" @click="cierreCajaDetalle()"><i class="fa-regular fa-eye"></i></a>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <hr class="separador">
                    <div class="container">
                        <label class="col-7 m-0" style="font-size: 17px;">Metas por Categoría</label>
                        <div class="table-responsive p-0">
                            <div v-show="mostrarCargaMensual" class="loader-sm"></div>
                            <table v-show="mostrarTablaMensual" class="table align-items-center mb-0" id="tableDesign">
                                <thead>
                                    <tr style="color: black">
                                        <th class="text-uppercase text-xs font-weight-bolder">Categoria</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Meta Mensual</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Ventas Acumuladas</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Ventas Faltantes</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Porcentaje de Cumplimiento</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="metaTiendaMensual == null">
                                        <td colspan="5"> No hay información disponible. </td>
                                    </tr>
                                    <tr v-if="metaTiendaMensual != null">
                                        <td>
                                            <p class="text-sm mb-0 fw-bold">Carteras</p>
                                        </td>
                                        <td>
                                            <p v-if="metaTiendaMensual.meta_carteras != null" class="text-sm mb-0">{{ formatMoneyCLP(metaTiendaMensual.meta_carteras) }}</p>
                                            <p v-else class="text-sm mb-0">{{ formatMoneyCLP(0) }}</p>
                                        </td>
                                        <td>
                                            <p v-if="metaTiendaMensual.meta_carteras_acumulada != null" class="text-sm mb-0">{{ formatMoneyCLP(metaTiendaMensual.meta_carteras_acumulada) }}</p>
                                            <p v-else class="text-sm mb-0">{{ formatMoneyCLP(0) }}</p>
                                        </td>
                                        <td>
                                            <p v-if="metaTiendaMensual.meta_carteras != null && metaTiendaMensual.meta_carteras_acumulada != null && (metaTiendaMensual.meta_carteras_acumulada < metaTiendaMensual.meta_carteras)" class="text-sm mb-0">{{ formatMoneyCLP(metaTiendaMensual.meta_carteras - metaTiendaMensual.meta_carteras_acumulada) }}</p>
                                            <p v-else-if="metaTiendaMensual.meta_carteras != null && metaTiendaMensual.meta_carteras_acumulada != null && (metaTiendaMensual.meta_carteras_acumulada > metaTiendaMensual.meta_carteras)" class="text-sm mb-0">{{ formatMoneyCLP(0) }}</p>
                                            <p v-else class="text-sm mb-0">{{ formatMoneyCLP(0) }}</p>
                                        </td>
                                        <td>
                                            <p v-if="metaTiendaMensual.meta_carteras != null && metaTiendaMensual.meta_carteras_acumulada != null && ((metaTiendaMensual.meta_carteras_acumulada*100) / metaTiendaMensual.meta_carteras)<100" class="text-sm font-weight-bolder mb-0" style="color: #D21610">{{ ((metaTiendaMensual.meta_carteras_acumulada*100) / metaTiendaMensual.meta_carteras).toFixed(3) + '%'}}</p>
                                            <p v-else-if="metaTiendaMensual.meta_carteras != null && metaTiendaMensual.meta_carteras_acumulada != null && ((metaTiendaMensual.meta_carteras_acumulada*100) / metaTiendaMensual.meta_carteras)>100" class="text-sm font-weight-bolder mb-0" style="color: #3574EA">{{ ((metaTiendaMensual.meta_carteras_acumulada*100) / metaTiendaMensual.meta_carteras).toFixed(3) + '%'}}</p>
                                            <p v-else class="text-sm font-weight-bolder mb-0" style="color: #D21610">{{'0%'}}</p>
                                        </td>
                                    </tr>
                                    <tr v-if="metaTiendaMensual != null">
                                        <td>
                                            <p class="text-sm mb-0 fw-bold">Marroquineria</p>
                                        </td>
                                        <td>
                                            <p v-if="metaTiendaMensual.meta_marroquineria != null" class="text-sm mb-0">{{ formatMoneyCLP(metaTiendaMensual.meta_marroquineria) }}</p>
                                            <p v-else class="text-sm mb-0">{{ formatMoneyCLP(0) }}</p>
                                        </td>
                                        <td>
                                            <p v-if="metaTiendaMensual.meta_marroquineria_acumulada != null" class="text-sm mb-0">{{ formatMoneyCLP(metaTiendaMensual.meta_marroquineria_acumulada) }}</p>
                                            <p v-else class="text-sm mb-0">{{ formatMoneyCLP(0) }}</p>
                                        </td>
                                        <td>
                                            <p v-if="metaTiendaMensual.meta_marroquineria != null && metaTiendaMensual.meta_marroquineria_acumulada != null && (metaTiendaMensual.meta_marroquineria_acumulada < metaTiendaMensual.meta_marroquineria)" class="text-sm mb-0">{{ formatMoneyCLP(metaTiendaMensual.meta_marroquineria - metaTiendaMensual.meta_marroquineria_acumulada) }}</p>
                                            <p v-else-if="metaTiendaMensual.meta_marroquineria != null && metaTiendaMensual.meta_marroquineria_acumulada != null && (metaTiendaMensual.meta_marroquineria_acumulada > metaTiendaMensual.meta_marroquineria)" class="text-sm mb-0">{{ formatMoneyCLP(0) }}</p>
                                            <p v-else class="text-sm mb-0">{{ formatMoneyCLP(0) }}</p>
                                        </td>
                                        <td>
                                            <p v-if="metaTiendaMensual.meta_marroquineria != null && metaTiendaMensual.meta_marroquineria_acumulada != null  && ((metaTiendaMensual.meta_marroquineria_acumulada*100) / metaTiendaMensual.meta_marroquineria).toFixed(3) < 100" class= "text-sm font-weight-bolder mb-0" style="color: #D21610">{{ ((metaTiendaMensual.meta_marroquineria_acumulada*100) / metaTiendaMensual.meta_marroquineria).toFixed(3) + '%'}}</p>
                                            <p v-else-if="metaTiendaMensual.meta_marroquineria != null && metaTiendaMensual.meta_marroquineria_acumulada != null  && ((metaTiendaMensual.meta_marroquineria_acumulada*100) / metaTiendaMensual.meta_marroquineria).toFixed(3) > 100" class="text-sm font-weight-bolder mb-0" style="color: #3574EA">{{ ((metaTiendaMensual.meta_marroquineria_acumulada*100) / metaTiendaMensual.meta_marroquineria).toFixed(3) + '%'}}</p>
                                            <p v-else class="text-sm font-weight-bolder mb-0" style="color: #D21610">{{'0%'}}</p>
                                        </td>
                                    </tr>
                                    <tr v-if="metaTiendaMensual != null">
                                        <td>
                                            <p class="text-sm mb-0 fw-bold">Calzado</p>
                                        </td>
                                        <td>
                                            <p v-if="metaTiendaMensual.meta_calzado != null" class="text-sm mb-0">{{ formatMoneyCLP(metaTiendaMensual.meta_calzado) }}</p>
                                            <p v-else class="text-sm mb-0">{{ formatMoneyCLP(0) }}</p>
                                        </td>
                                        <td>
                                            <p v-if="metaTiendaMensual.meta_calzado_acumulada != null" class="text-sm mb-0">{{ formatMoneyCLP(metaTiendaMensual.meta_calzado_acumulada) }}</p>
                                            <p v-else class="text-sm mb-0">{{ formatMoneyCLP(0) }}</p>
                                        </td>
                                        <td>
                                            <p v-if="metaTiendaMensual.meta_calzado != null && metaTiendaMensual.meta_calzado_acumulada != null && (metaTiendaMensual.meta_calzado_acumulada < metaTiendaMensual.meta_calzado)" class="text-sm mb-0">{{ formatMoneyCLP(metaTiendaMensual.meta_calzado - metaTiendaMensual.meta_calzado_acumulada) }}</p>
                                            <p v-else-if="metaTiendaMensual.meta_calzado != null && metaTiendaMensual.meta_calzado_acumulada != null && (metaTiendaMensual.meta_calzado_acumulada > metaTiendaMensual.meta_calzado)" class="text-sm mb-0">{{ formatMoneyCLP(0) }}</p>
                                            <p v-else class="text-sm mb-0">{{ formatMoneyCLP(0) }}</p>
                                        </td>
                                        <td>
                                            <p v-if="metaTiendaMensual.meta_calzado != null && metaTiendaMensual.meta_calzado_acumulada != null  && ((metaTiendaMensual.meta_calzado_acumulada*100) / metaTiendaMensual.meta_calzado).toFixed(3) < 100" class= "text-sm font-weight-bolder mb-0" style="color: #D21610">{{ ((metaTiendaMensual.meta_calzado_acumulada*100) / metaTiendaMensual.meta_calzado).toFixed(3) + '%'}}</p>
                                            <p v-else-if="metaTiendaMensual.meta_calzado != null && metaTiendaMensual.meta_calzado_acumulada != null  && ((metaTiendaMensual.meta_calzado_acumulada*100) / metaTiendaMensual.meta_calzado).toFixed(3) > 100" class="text-sm font-weight-bolder mb-0" style="color: #3574EA">{{ ((metaTiendaMensual.meta_calzado_acumulada*100) / metaTiendaMensual.meta_calzado).toFixed(3) + '%'}}</p>
                                            <p v-else class="text-sm font-weight-bolder mb-0" style="color: #D21610">{{'0%'}}</p>
                                        </td>
                                    </tr>
                                    <tr v-if="metaTiendaMensual != null">
                                        <td>
                                            <p class="text-sm mb-0 fw-bold">Perfumes</p>
                                        </td>
                                        <td>
                                            <p v-if="metaTiendaMensual.meta_perfumes != null" class="text-sm mb-0">{{ formatMoneyCLP(metaTiendaMensual.meta_perfumes) }}</p>
                                            <p v-else class="text-sm mb-0">{{ formatMoneyCLP(0) }}</p>
                                        </td>
                                        <td>
                                            <p v-if="metaTiendaMensual.meta_perfumes_acumulada != null" class="text-sm mb-0">{{ formatMoneyCLP(metaTiendaMensual.meta_perfumes_acumulada) }}</p>
                                            <p v-else class="text-sm mb-0">{{ formatMoneyCLP(0) }}</p>
                                        </td>
                                        <td>
                                            <p v-if="metaTiendaMensual.meta_perfumes != null && metaTiendaMensual.meta_perfumes_acumulada != null && (metaTiendaMensual.meta_perfumes_acumulada < metaTiendaMensual.meta_perfumes)" class="text-sm mb-0">{{ formatMoneyCLP(metaTiendaMensual.meta_perfumes - metaTiendaMensual.meta_perfumes_acumulada) }}</p>
                                            <p v-else-if="metaTiendaMensual.meta_perfumes != null && metaTiendaMensual.meta_perfumes_acumulada != null && (metaTiendaMensual.meta_perfumes_acumulada > metaTiendaMensual.meta_perfumes)" class="text-sm mb-0">{{ formatMoneyCLP(0) }}</p>
                                            <p v-else class="text-sm mb-0">{{ formatMoneyCLP(0) }}</p>
                                        </td>
                                        <td>
                                            <p v-if="metaTiendaMensual.meta_perfumes != null && metaTiendaMensual.meta_perfumes_acumulada != null  && ((metaTiendaMensual.meta_perfumes_acumulada*100) / metaTiendaMensual.meta_perfumes).toFixed(3) < 100" class= "text-sm font-weight-bolder mb-0" style="color: #D21610">{{ ((metaTiendaMensual.meta_perfumes_acumulada*100) / metaTiendaMensual.meta_perfumes).toFixed(3) + '%'}}</p>
                                            <p v-else-if="metaTiendaMensual.meta_perfumes != null && metaTiendaMensual.meta_perfumes_acumulada != null  && ((metaTiendaMensual.meta_perfumes_acumulada*100) / metaTiendaMensual.meta_perfumes).toFixed(3) > 100" class="text-sm font-weight-bolder mb-0" style="color: #3574EA">{{ ((metaTiendaMensual.meta_perfumes_acumulada*100) / metaTiendaMensual.meta_perfumes).toFixed(3) + '%'}}</p>
                                            <p v-else class="text-sm font-weight-bolder mb-0" style="color: #D21610">{{'0%'}}</p>
                                        </td>
                                    </tr>
                                    <tr v-if="metaTiendaMensual != null">
                                        <td>
                                            <p class="text-sm mb-0 fw-bold">Vestimenta</p>
                                        </td>
                                        <td>
                                            <p v-if="metaTiendaMensual.meta_vestimenta != null" class="text-sm mb-0">{{ formatMoneyCLP(metaTiendaMensual.meta_vestimenta) }}</p>
                                            <p v-else class="text-sm mb-0">{{ formatMoneyCLP(0) }}</p>
                                        </td>
                                        <td>
                                            <p v-if="metaTiendaMensual.meta_vestimenta_acumulada != null" class="text-sm mb-0">{{ formatMoneyCLP(metaTiendaMensual.meta_vestimenta_acumulada) }}</p>
                                            <p v-else class="text-sm mb-0">{{ formatMoneyCLP(0) }}</p>
                                        </td>
                                        <td>
                                            <p v-if="metaTiendaMensual.meta_vestimenta != null && metaTiendaMensual.meta_vestimenta_acumulada != null && (metaTiendaMensual.meta_vestimenta_acumulada < metaTiendaMensual.meta_vestimenta)" class="text-sm mb-0">{{ formatMoneyCLP(metaTiendaMensual.meta_vestimenta - metaTiendaMensual.meta_vestimenta_acumulada) }}</p>
                                            <p v-else-if="metaTiendaMensual.meta_vestimenta != null && metaTiendaMensual.meta_vestimenta_acumulada != null && (metaTiendaMensual.meta_vestimenta_acumulada > metaTiendaMensual.meta_vestimenta)" class="text-sm mb-0">{{ formatMoneyCLP(0) }}</p>
                                            <p v-else class="text-sm mb-0">{{ formatMoneyCLP(0) }}</p>
                                        </td>
                                        <td>
                                            <p v-if="metaTiendaMensual.meta_vestimenta != null && metaTiendaMensual.meta_vestimenta_acumulada != null  && ((metaTiendaMensual.meta_vestimenta_acumulada*100) / metaTiendaMensual.meta_vestimenta).toFixed(3) < 100" class= "text-sm font-weight-bolder mb-0" style="color: #D21610">{{ ((metaTiendaMensual.meta_vestimenta_acumulada*100) / metaTiendaMensual.meta_vestimenta).toFixed(3) + '%'}}</p>
                                            <p v-else-if="metaTiendaMensual.meta_vestimenta != null && metaTiendaMensual.meta_vestimenta_acumulada != null && ((metaTiendaMensual.meta_vestimenta_acumulada*100) / metaTiendaMensual.meta_vestimenta).toFixed(3) > 100" class="text-sm font-weight-bolder mb-0" style="color: #3574EA">{{ ((metaTiendaMensual.meta_vestimenta_acumulada*100) / metaTiendaMensual.meta_vestimenta).toFixed(3) + '%'}}</p>
                                            <p v-else class="text-sm font-weight-bolder mb-0" style="color: #D21610">{{'0%'}}</p>
                                        </td>
                                    </tr>
                                    <tr v-if="metaTiendaMensual != null">
                                        <td>
                                            <p class="text-sm mb-0 fw-bold">Otros</p>
                                        </td>
                                        <td>
                                            <p class="text-sm mb-0">{{ formatMoneyCLP(0) }}</p>
                                        </td>
                                        <td>
                                            <p v-if="metaTiendaMensual.meta_otro_acumulada != null" class="text-sm mb-0">{{ formatMoneyCLP(metaTiendaMensual.meta_otro_acumulada) }}</p>
                                            <p v-else class="text-sm mb-0">{{ formatMoneyCLP(0) }}</p>
                                        </td>
                                        <td>
                                            <p v-if="metaTiendaMensual.meta_vestimenta != null && metaTiendaMensual.meta_otro_acumulada != null && (metaTiendaMensual.meta_otro_acumulada < metaTiendaMensual.meta_vestimenta)" class="text-sm mb-0">{{ formatMoneyCLP(metaTiendaMensual.meta_vestimenta - metaTiendaMensual.meta_otro_acumulada) }}</p>
                                            <p v-else-if="metaTiendaMensual.meta_vestimenta != null && metaTiendaMensual.meta_otro_acumulada != null && (metaTiendaMensual.meta_otro_acumulada > metaTiendaMensual.meta_vestimenta)" class="text-sm mb-0">{{ formatMoneyCLP(0) }}</p>
                                            <p v-else class="text-sm mb-0">{{ formatMoneyCLP(0) }}</p>
                                        </td>
                                        <td>
                                            <p v-if="metaTiendaMensual.meta_vestimenta != null && metaTiendaMensual.meta_otro_acumulada != null  && ((metaTiendaMensual.meta_otro_acumulada*100) / metaTiendaMensual.meta_vestimenta).toFixed(3) < 100" class= "text-sm font-weight-bolder mb-0" style="color: #D21610">{{ ((metaTiendaMensual.meta_otro_acumulada*100) / metaTiendaMensual.meta_vestimenta).toFixed(3) + '%'}}</p>
                                            <p v-else-if="metaTiendaMensual.meta_vestimenta != null && metaTiendaMensual.meta_otro_acumulada != null && ((metaTiendaMensual.meta_otro_acumulada*100) / metaTiendaMensual.meta_vestimenta).toFixed(3) > 100" class="text-sm font-weight-bolder mb-0" style="color: #3574EA">{{ ((metaTiendaMensual.meta_otro_acumulada*100) / metaTiendaMensual.meta_vestimenta).toFixed(3) + '%'}}</p>
                                            <p v-else class="text-sm font-weight-bolder mb-0" style="color: #D21610">{{'0%'}}</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <hr class="separador">
                    <div class="container">
                        <label class="col-7 m-0" style="font-size: 17px;">Metas Diarias</label>
                        <div class="table-responsive p-0">
                            <div v-show="mostrarCargaDiarias" class="loader-sm"></div>
                            <table v-show="mostrarTablaDiarias" class="table align-items-center mb-0" id="tableDesign">
                                <thead>
                                    <tr style="color: black">
                                        <th class="text-uppercase text-xs font-weight-bolder">Meta Diaria</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Ventas Acumulada Dia</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Ventas Faltantes Dia</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Porcentaje de Cumplimiento</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-if="metaTiendaDiaria == null">
                                        <td colspan="6"> No hay información disponible. </td>
                                    </tr>
                                    <tr v-else>
                                        <td>
                                            <p v-if="metaTiendaDiaria.meta_total != null" class="text-sm mb-0">{{ formatMoneyCLP(metaTiendaDiaria.meta_total) }}</p>
                                            <p v-else class="text-sm mb-0">{{ formatMoneyCLP(0) }}</p>
                                        </td>
                                        <td>
                                            <p v-if="metaTiendaDiaria.meta_total_acumulada_diaria != null" class="text-sm mb-0">{{ formatMoneyCLP(metaTiendaDiaria.meta_total_acumulada_diaria) }}</p>
                                            <p v-else class="text-sm mb-0">{{ formatMoneyCLP(0) }}</p>
                                        </td>
                                        <td>
                                            <p v-if="metaTiendaDiaria.meta_total != null && metaTiendaDiaria.meta_total_acumulada_diaria != null && (metaTiendaDiaria.meta_total_acumulada_diaria < metaTiendaDiaria.meta_total)" class="text-sm mb-0">{{ formatMoneyCLP(metaTiendaDiaria.meta_total - metaTiendaDiaria.meta_total_acumulada_diaria) }}</p>
                                            <p v-else-if="metaTiendaDiaria.meta_total != null && metaTiendaDiaria.meta_total_acumulada_diaria != null && (metaTiendaDiaria.meta_total_acumulada_diaria > metaTiendaDiaria.meta_total)" class="text-sm mb-0">{{ formatMoneyCLP(0) }}</p>
                                            <p v-else class="text-sm mb-0">{{ formatMoneyCLP(0) }}</p>
                                        </td>
                                        <td>
                                            <p v-if="metaTiendaDiaria.meta_total != null && metaTiendaDiaria.meta_total_acumulada_diaria != null && ((metaTiendaDiaria.meta_total_acumulada_diaria*100) / metaTiendaDiaria.meta_total).toFixed(3) < 100" class= "text-sm font-weight-bolder mb-0" style="color: #D21610">{{ ((metaTiendaDiaria.meta_total_acumulada_diaria*100) / metaTiendaDiaria.meta_total).toFixed(3) + '%'}}</p>
                                            <p v-else-if="metaTiendaDiaria.meta_total != null && metaTiendaDiaria.meta_total_acumulada_diaria != null && ((metaTiendaDiaria.meta_total_acumulada_diaria*100) / metaTiendaDiaria.meta_total).toFixed(3) > 100" class="text-sm font-weight-bolder mb-0" style="color: #3574EA">{{ ((metaTiendaDiaria.meta_total_acumulada_diaria*100) / metaTiendaDiaria.meta_total).toFixed(3) + '%'}}</p>
                                            <p v-else class="text-sm font-weight-bolder mb-0" style="color: #D21610">{{'0%'}}</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <hr class="separador">
                    <div class="container">
                        <label class="col-7 m-0" style="font-size: 17px;">Meta Mensual por Colaborador</label>
                        <div class="table-responsive p-0">
                            <div v-show="mostrarCargaColaborador" class="loader-sm"></div>
                            <table v-show="mostrarTablaColaborador" class="table align-items-center mb-0" id="tableDesign">
                                <thead>
                                    <tr style="color: black">
                                        <th class="text-uppercase text-xs font-weight-bolder">Colaborador</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Meta Mensual</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Ventas Acumuladas</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Ventas Faltantes</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Porcentaje de Cumplimiento</th>
                                        <th class="text-uppercase text-xs font-weight-bolder">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <tr v-if="metasColaboradores == 0">
                                    <td colspan="6"> No hay información disponible. </td>
                                </tr>
                                <tr v-else v-for="metaColaborador in metasColaboradores" :key="metaColaborador">
                                    <td>
                                        <p v-if="metaColaborador.meta_total != null" class="text-sm mb-0">{{ metaColaborador.vendedor.nombre1 + " " + metaColaborador.vendedor.nombre2 + " " + metaColaborador.vendedor.apellido1 + " " + metaColaborador.vendedor.apellido2 }}</p>
                                        <p v-else class="text-sm mb-0">-----</p>
                                    </td>
                                    <td>
                                        <p v-if="metaColaborador.meta_total != null" class="text-sm mb-0">{{ formatMoneyCLP(metaColaborador.meta_total) }}</p>
                                        <p v-else class="text-sm mb-0">{{ formatMoneyCLP(0) }}</p>
                                    </td>
                                    <td>
                                        <p v-if="metaColaborador.meta_total_acumulada_vendedor != null" class="text-sm mb-0">{{ metaColaborador.meta_total_acumulada_vendedor }}</p>
                                        <p v-else class="text-sm mb-0">{{ formatMoneyCLP(0) }}</p>
                                    </td>
                                    <td>
                                        <p v-if="metaColaborador.meta_total != null && metaColaborador.meta_total_acumulada_vendedor != null && (metaColaborador.meta_total_acumulada_vendedor < metaColaborador.meta_total)" class="text-sm mb-0">{{ formatMoneyCLP(metaColaborador.meta_total - metaColaborador.meta_total_acumulada_vendedor) }}</p>
                                        <p v-else-if="metaColaborador.meta_total != null && metaColaborador.meta_total_acumulada_vendedor != null && (metaColaborador.meta_total_acumulada_vendedor > metaColaborador.meta_total)" class="text-sm mb-0">{{ formatMoneyCLP(0) }}</p>
                                        <p v-else class="text-sm mb-0">{{ formatMoneyCLP(0) }}</p>
                                    </td>
                                    <td>
                                        <p v-if="metaColaborador.meta_total != null && metaColaborador.meta_total_acumulada_vendedor != null && ((metaColaborador.meta_total_acumulada_vendedor*100) / metaColaborador.meta_total).toFixed(3) < 100" class= "text-sm font-weight-bolder mb-0" style="color: #D21610">{{ ((metaColaborador.meta_total_acumulada_vendedor*100) / metaColaborador.meta_total).toFixed(3) + '%'}}</p>
                                        <p v-else-if="metaColaborador.meta_total != null && metaColaborador.meta_total_acumulada_vendedor != null && ((metaColaborador.meta_total_acumulada_vendedor*100) / metaColaborador.meta_total).toFixed(3) > 100" class="text-sm font-weight-bolder mb-0" style="color: #3574EA">{{ ((metaColaborador.meta_total_acumulada_vendedor*100) / metaColaborador.meta_total).toFixed(3) + '%'}}</p>
                                        <p v-else class="text-sm font-weight-bolder mb-0" style="color: #D21610">{{'0%'}}</p>
                                    </td>
                                    <td class="align-middle text-end">
                                        <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                            <a class="btn btn-success btn-xs" title="Detalle" @click="cierreCajaDetalle()"><i class="fa-regular fa-eye"></i></a>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <modalconfirmacion ref="confirmation"></modalconfirmacion>
            <modalalerta ref="alert"></modalalerta>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import modalconfirmacion from '../../snippets/sistema/alerts/confirmationModal.vue'
import modalalerta from '../../snippets/sistema/alerts/alertModal.vue'
import {mixin} from '../../../mixins.js'
import moment from "moment/min/moment-with-locales";
moment.locale("es");

export default {
    components: { modalconfirmacion, modalalerta },
    mixins: [mixin],
    data(){
        return{
            mostrarTablaMensual: false,
            mostrarTablaDiarias: false,
            mostrarTablaColaborador: false,
            mostrarCargaMensual: true,
            mostrarCargaDiarias: true,
            mostrarCargaColaborador: true,
            metaTiendaMensual: null,
            metaTiendaDiaria: null,
            metasColaboradores: null,
            // Filtros:
            fechaAño: moment().format("YYYY"),
            fechaMes: moment().format("M"),
            fechaDia: moment().format("D"),
            filtroTienda: 1,
            verificadoTienda: false,
            años: [{value: 2000}, {value: 2001}, {value: 2002}, {value: 2003}, {value: 2004}, {value: 2005}, {value: 2006}, {value: 2007}, {value: 2008}, {value: 2009}, {value: 2010}, {value: 2011}, {value: 2012}, {value: 2013}, {value: 2014}, {value: 2015}, {value: 2016}, {value: 2017}, {value: 2018}, {value: 2019}, {value: 2020}, {value: 2021}, {value: 2022}, {value: 2023}, {value: 2024}, {value: 2025}, {value: 2026}, {value: 2027}, {value: 2028}, {value: 2029}, {value: 2030}, {value: 2031}, {value: 2032}, {value: 2033}, {value: 2034}, {value: 2035}, {value: 2036}, {value: 2037}, {value: 2038}, {value: 2039}, {value: 2040}, {value: 2041}, {value: 2042}, {value: 2043}, {value: 2044}, {value: 2045}, {value: 2046}, {value: 2047}, {value: 2048}, {value: 2049}, {value: 2050}],
            meses: [{value: 1, nombre: 'Enero'}, {value: 2, nombre: 'Febrero'}, {value: 3, nombre: 'Marzo'}, {value: 4, nombre: 'Abril'}, {value: 5, nombre: 'Mayo'}, {value: 6, nombre: 'Junio'}, {value: 7, nombre: 'Julio'}, {value: 8, nombre: 'Agosto'}, {value: 9, nombre: 'Septiembre'}, {value: 10, nombre: 'Octubre'}, {value: 11, nombre: 'Noviembre'}, {value: 12, nombre: 'Diciembre'}],
            dias: [{value: 1}, {value: 2}, {value: 3}, {value: 4}, {value: 5}, {value: 6}, {value: 7}, {value: 8}, {value: 9}, {value: 10}, {value: 11}, {value: 12}, {value: 13}, {value: 14}, {value: 15}, {value: 16}, {value: 17}, {value: 18}, {value: 19}, {value: 20}, {value: 21}, {value: 22}, {value: 23}, {value: 24}, {value: 25}, {value: 26}, {value: 27}, {value: 28}, {value: 29}, {value: 30}, {value: 31}],
            tiendas: [],
        }
    },
    created(){
        this.getTiendas();
        this.getMetasTienda();
        this.getMetasDiarias();
        this.getMetasColaborador();
    },
    methods: {
        getTiendas(){
            axios.get('api/getTiendas').then( response =>{
                this.tiendas = response.data;
                this.tiendas.forEach(tienda => {
                    if(tienda.idUsuario == this.userID){
                        this.filtroTienda = tienda.id;
                        this.verificadoTienda = true;
                    }
                })
            }).catch(e=> console.log(e))
        },
        getMetasTienda(){
            let datos1 = {
                idTienda: this.filtroTienda,
                mes: this.fechaMes.toString().padStart(2, "0"),
                año: this.fechaAño,
            }
            axios.post(`api/metasTienda/getMetasMensualesTienda`, datos1).then( response =>{
                this.metaTiendaMensual = response.data[0];
                this.mostrarCargaMensual = false;
                this.mostrarTablaMensual = true;
            }).catch(e=> console.log(e))
        },
        getMetasDiarias(){
            let datos2= {
                idTienda: this.filtroTienda,
                dia: this.fechaDia.toString().padStart(2, "0"),
                mes: this.fechaMes.toString().padStart(2, "0"),
                año: this.fechaAño,
            }
            axios.post(`api/metasDiarias/getMetasDiariasTienda`, datos2).then( response =>{
                this.metaTiendaDiaria = response.data[0];
                this.mostrarCargaDiarias = false;
                this.mostrarTablaDiarias = true;
            }).catch(e=> console.log(e))
        },
        getMetasColaborador(){
            let datos3 = {
                idTienda: this.filtroTienda,
                mes: this.fechaMes.toString().padStart(2, "0"),
                año: this.fechaAño,
            }
            axios.post(`api/metasColaborador/getMetasColaboradoresTienda`, datos3).then( response =>{
                this.metasColaboradores = response.data;
                this.mostrarCargaColaborador = false;
                this.mostrarTablaColaborador = true;
            }).catch(e=> console.log(e))
        },
        recargarTabla(){
            this.mostrarCargaMensual = true;
            this.mostrarCargaDiarias = true;
            this.mostrarCargaColaborador = true;
            this.getMetasTienda();
            this.getMetasDiarias();
            this.getMetasColaborador();
        },
        aplicarFiltro(){
            this.recargarTabla();
        },
    }
}
</script>