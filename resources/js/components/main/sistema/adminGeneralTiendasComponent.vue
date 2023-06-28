<template>
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4" style="flex-direction: inherit;">
                <div class="btn-group-vertical" role="group" style="min-height: 450px; width: max-content;">
                    <div class="btn-group-vertical" role="group" aria-label="First Group" style="width: 160px; margin-left: 10px; margin-right: 10px;">
                        <input type="radio" class="btn-check" name="vertical-radio-btn" id="vertical-radio-btn1" autocomplete="off" @click="botonCheck('Sucursales')" checked="">
                        <label class="btn btn-outline-secondary m-auto" for="vertical-radio-btn1">
                            Sucursales
                        </label>
                        <input type="radio" class="btn-check" name="vertical-radio-btn" id="vertical-radio-btn4" autocomplete="off" @click="botonCheck('Promotores')">
                        <label class="btn btn-outline-secondary m-auto" for="vertical-radio-btn4">
                            Promotores-Sucursal
                        </label>
                        <input type="radio" class="btn-check" name="vertical-radio-btn" id="vertical-radio-btn7" autocomplete="off" @click="botonCheck('Supervisores')">
                        <label class="btn btn-outline-secondary m-auto" for="vertical-radio-btn7">
                            Supervisores-Sucursal
                        </label>
                        <input type="radio" class="btn-check" name="vertical-radio-btn" id="vertical-radio-btn3" autocomplete="off" @click="botonCheck('Vendedores')">
                        <label class="btn btn-outline-secondary m-auto" for="vertical-radio-btn3">
                            Vendedor-Sucursal
                        </label>
                        <input type="radio" class="btn-check" name="vertical-radio-btn" id="vertical-radio-btn5" autocomplete="off" @click="botonCheck('Comisiones')">
                        <label class="btn btn-outline-secondary m-auto" for="vertical-radio-btn5">
                            Comisiones
                        </label>
                        <input type="radio" class="btn-check" name="vertical-radio-btn" id="vertical-radio-btn6" autocomplete="off" @click="botonCheck('MetasMensuales')">
                        <label class="btn btn-outline-secondary m-auto" for="vertical-radio-btn6">
                            Metas Mensuales
                        </label>
                        <input type="radio" class="btn-check" name="vertical-radio-btn" id="vertical-radio-btn8" autocomplete="off" @click="botonCheck('MetasDiarias')">
                        <label class="btn btn-outline-secondary m-auto" for="vertical-radio-btn8">
                            Metas Diarias
                        </label>
                        <input type="radio" class="btn-check" name="vertical-radio-btn" id="vertical-radio-btn9" autocomplete="off" @click="botonCheck('MetasColaboradores')">
                        <label class="btn btn-outline-secondary m-auto" for="vertical-radio-btn9">
                            Metas Colaboradores
                        </label>
                        <input type="radio" class="btn-check" name="vertical-radio-btn" id="vertical-radio-btn2" autocomplete="off" @click="botonCheck('ContadoresPersonas')">
                        <label class="btn btn-outline-secondary m-auto" for="vertical-radio-btn2">
                            Contador de Personas
                        </label>
                        <input type="radio" class="btn-check" name="vertical-radio-btn" id="vertical-radio-btn10" autocomplete="off" @click="botonCheck('Bancos')">
                        <label class="btn btn-outline-secondary m-auto" for="vertical-radio-btn10">
                            Bancos
                        </label>
                    </div>
                </div>
                <!-- ################################################################################################################################# -->
                <div style="width: 100%;">
                    <slot v-if="isVisibleSucursales">
                        <div class="row pb-0 p-4">
                            <div class="col-12">
                                <h6>Sucursales</h6>
                                <div class="d-flex justify-content-end">
                                    <a class="btn btn-continue" @click="showCreateSucursal = true">Nuevo</a>
                                    &nbsp;
                                    <a class="btn btn-search-blue" @click="recargarTabla('Sucursales')"><i class="fa-solid fa-rotate"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2" style="min-height: 400px;">
                            <div v-show="mostrarCarga1" class="loader-xs"></div>
                            <div class="container">
                                <div class="table-responsive p-0">
                                    <table class="table align-items-center mb-0" id="MyTableSucursales">
                                        <thead>
                                            <tr style="color: black">
                                                <th style="min-width: 16px;"></th>
                                                <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Estado</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Nombre</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Código</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Bodega</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Centro de Costo</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Tipo</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Empresa</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Marca</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Dirección</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Teléfono</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Correo Electrónico</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="sucursal in sucursales" :key="sucursal.id">
                                                <td></td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{sucursal.id}}</p>
                                                </td>
                                                <td>
                                                    <p v-if="sucursal.estado == true" class="text-sm font-weight-bolder mb-0" style="color: #28A745">Activo</p>
                                                    <p v-if="sucursal.estado == false" class="text-sm font-weight-bolder mb-0" style="color: #B80F0A">Inactivo</p>
                                                    <p v-if="sucursal.estado == null" class="text-sm font-weight-bolder mb-0" style="color: black">---</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{sucursal.nombre}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{sucursal.codigo}}</p>
                                                </td>
                                                <td>
                                                    <p v-if="sucursal.bodega != null" class="text-sm font-weight-bolder mb-0" style="color: black">{{sucursal.bodega}}</p>
                                                    <p v-else class="text-sm font-weight-bolder mb-0" style="color: black">---</p>
                                                </td>
                                                <td>
                                                    <p v-if="sucursal.centroCosto != null" class="text-sm font-weight-bolder mb-0" style="color: black">{{sucursal.centroCosto}}</p>
                                                    <p v-else class="text-sm font-weight-bolder mb-0" style="color: black">---</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{sucursal.tipo_sucursal.nombre}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{sucursal.empresa_marca.empresa.razonSocial}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{sucursal.empresa_marca.marca.nombre}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{sucursal.direccion}}</p>
                                                </td>
                                                <td>
                                                    <p v-if="sucursal.telefono != null" class="text-sm font-weight-bolder mb-0" style="color: black">{{sucursal.telefono}}</p>
                                                    <p v-else class="text-sm font-weight-bolder mb-0" style="color: black">---</p>
                                                </td>
                                                <td>
                                                    <p v-if="sucursal.email != null" class="text-sm font-weight-bolder mb-0" style="color: black">{{sucursal.email}}</p>
                                                    <p v-else class="text-sm font-weight-bolder mb-0" style="color: black">---</p>
                                                </td>
                                                <td class="align-middle text-end">
                                                    <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                        <a class="btn btn-success btn-xs" @click="sucursalDetalle(sucursal)"><i class="fa-regular fa-eye"></i></a>
                                                        &nbsp;
                                                        <a class="btn btn-alert btn-xs" @click="editSucursal(sucursal)"><i class="fa fa-fw fa-edit"></i></a>
                                                        &nbsp;
                                                        <a class="btn btn-closed btn-xs" @click="deleteSucursal(sucursal.id,sucursal.sucursal)"><i class="fa fa-fw fa-trash"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </slot>
                    <!-- ################################################################################################################################# -->
                    <slot v-if="isVisiblePromotores">
                        <div class="row pb-0 p-4">
                            <div class="col-12">
                                <h6>Relación Promotores-Sucursal</h6>
                                <div class="d-flex justify-content-end">
                                    <a class="btn btn-continue" @click="showCreatePromotor = true">Nuevo</a>
                                    &nbsp;
                                    <a class="btn btn-search-blue" @click="recargarTabla('Promotores')"><i class="fa-solid fa-rotate"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2" style="min-height: 400px;">
                            <div v-show="mostrarCarga2" class="loader-xs"></div>
                            <div class="container">
                                <div class="table-responsive p-0">
                                    <table class="table align-items-center mb-0" id="MyTablePromotores">
                                        <thead>
                                            <tr style="color: black">
                                                <th style="min-width: 16px;"></th>
                                                <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Estado</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Nombre</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Sucursal</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="promotor in promotores" :key="promotor.id">
                                                <td></td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{promotor.id}}</p>
                                                </td>
                                                <td>
                                                    <p v-if="promotor.estado == true" class="text-sm font-weight-bolder mb-0" style="color: #28A745">Activo</p>
                                                    <p v-if="promotor.estado == false" class="text-sm font-weight-bolder mb-0" style="color: #B80F0A">Inactivo</p>
                                                    <p v-if="promotor.estado == null" class="text-sm font-weight-bolder mb-0" style="color: black">---</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{promotor.usuario.name}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{promotor.sucursal.nombre}}</p>
                                                    <!-- <p class="text-sm font-weight-bolder mb-0" style="color: black; padding-bottom: 5px;"><span class="badge bg-primary">Interandina de Comercio Limitada - Amphora <span class="badge bg-secondary" style="font-size: 10px">5</span></span></p>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black; padding-bottom: 5px;"><span class="badge bg-primary">Ripley - Ziol <span class="badge bg-secondary" style="font-size: 10px">1</span></span></p>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black; padding-bottom: 5px;"><span class="badge bg-primary">Falabella - Amphora <span class="badge bg-secondary" style="font-size: 10px">3</span></span></p> -->
                                                </td>
                                                <td class="align-middle text-end">
                                                    <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                        <a class="btn btn-alert btn-xs" @click="editPromotor(promotor)"><i class="fa fa-fw fa-edit"></i></a>
                                                        &nbsp;
                                                        <a class="btn btn-closed btn-xs" @click="deletePromotor(promotor.id,promotor.promotor,promotor.sucursal.nombre)"><i class="fa fa-fw fa-trash"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <label style="font-weight: 500">Si desea añadir nuevos promotores de sucursales debe crear un nuevo usuario o editar uno existente y seleccionar "Promotor Tienda" en el 'tipo de usuario'.</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </slot>
                    <!-- ################################################################################################################################# -->
                    <slot v-if="isVisibleSupervisores">
                        <div class="row pb-0 p-4">
                            <div class="col-12">
                                <h6>Relación Supervisores-Sucursal</h6>
                                <div class="d-flex justify-content-end">
                                    <a class="btn btn-continue" @click="showCreateSupervisor = true">Nuevo</a>
                                    &nbsp;
                                    <a class="btn btn-search-blue" @click="recargarTabla('Supervisores')"><i class="fa-solid fa-rotate"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2" style="min-height: 400px;">
                            <div v-show="mostrarCarga3" class="loader-xs"></div>
                            <div class="container">
                                <div class="table-responsive p-0">
                                    <table class="table align-items-center mb-0" id="MyTableSupervisores">
                                        <thead>
                                            <tr style="color: black">
                                                <th style="min-width: 16px;"></th>
                                                <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Estado</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Nombre</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Sucursal</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="supervisor in supervisores" :key="supervisor.id">
                                                <td></td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{supervisor.id}}</p>
                                                </td>
                                                <td>
                                                    <p v-if="supervisor.estado == true" class="text-sm font-weight-bolder mb-0" style="color: #28A745">Activo</p>
                                                    <p v-if="supervisor.estado == false" class="text-sm font-weight-bolder mb-0" style="color: #B80F0A">Inactivo</p>
                                                    <p v-if="supervisor.estado == null" class="text-sm font-weight-bolder mb-0" style="color: black">---</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{supervisor.usuario.name}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{supervisor.sucursal.nombre}}</p>
                                                    <!-- <p class="text-sm font-weight-bolder mb-0" style="color: black; padding-bottom: 5px;"><span class="badge bg-primary">Interandina de Comercio Limitada - Amphora <span class="badge bg-secondary" style="font-size: 10px">5</span></span></p>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black; padding-bottom: 5px;"><span class="badge bg-primary">Ripley - Ziol <span class="badge bg-secondary" style="font-size: 10px">1</span></span></p>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black; padding-bottom: 5px;"><span class="badge bg-primary">Falabella - Amphora <span class="badge bg-secondary" style="font-size: 10px">3</span></span></p> -->
                                                </td>
                                                <td class="align-middle text-end">
                                                    <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                        <a class="btn btn-alert btn-xs" @click="editSupervisor(supervisor)"><i class="fa fa-fw fa-edit"></i></a>
                                                        &nbsp;
                                                        <a class="btn btn-closed btn-xs" @click="deleteSupervisor(supervisor.id,supervisor.supervisor,supervisor.sucursal.nombre)"><i class="fa fa-fw fa-trash"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <label style="font-weight: 500">Si desea añadir nuevos supervisores de sucursales debe crear un nuevo usuario o editar uno existente y seleccionar "Supervisor Tienda" en el 'tipo de usuario'.</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </slot>
                    <!-- ################################################################################################################################# -->
                    <slot v-if="isVisibleVendedores">
                        <div class="row pb-0 p-4">
                            <div class="col-12">
                                <h6>Relación Vendedores-Sucursal</h6>
                                <div class="d-flex justify-content-end">
                                    <a class="btn btn-continue" @click="showCreateVendedor = true">Nuevo</a>
                                    &nbsp;
                                    <a class="btn btn-search-blue" @click="recargarTabla('Vendedores')"><i class="fa-solid fa-rotate"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2" style="min-height: 400px;">
                            <div v-show="mostrarCarga10" class="loader-xs"></div>
                            <div class="container">
                                <div class="table-responsive p-0">
                                    <table class="table align-items-center mb-0" id="MyTableVendedores">
                                        <thead>
                                            <tr style="color: black">
                                                <th style="min-width: 16px;"></th>
                                                <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Nombre</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Rut</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Codigo Vendedor</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Empresa</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="vendedor in vendedores" :key="vendedor.id">
                                                <td></td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{vendedor.id}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{vendedor.nombre1 +" "+ vendedor.nombre2 +" "+ vendedor.apellido1 +" "+ vendedor.apellido2}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{vendedor.rut}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{vendedor.codigoVendedor}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{vendedor.idEmpresa}}</p>
                                                </td>
                                                <td class="align-middle text-end">
                                                    <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                        <a class="btn btn-alert btn-xs" @click="editVendedor(vendedor)"><i class="fa fa-fw fa-edit"></i></a>
                                                        &nbsp;
                                                        <a class="btn btn-closed btn-xs" @click="deleteVendedor(vendedor.id)"><i class="fa fa-fw fa-trash"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="row">
                                    <div class="col-6">
                                        <label style="font-weight: 500">Si desea añadir nuevos vendedores de sucursales debe crear un nuevo usuario o editar uno existente y seleccionar "Vendedor Tienda" en el 'tipo de usuario'.</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </slot>
                    <!-- ################################################################################################################################# -->
                    <slot v-if="isVisibleComisiones">
                        <div class="row pb-0 p-4">
                            <div class="col-12">
                                <h6>Comisiones</h6>
                                <div class="d-flex justify-content-end">
                                    <a class="btn btn-continue" @click="showCreateComision = true">Nuevo</a>
                                    &nbsp;
                                    <a class="btn btn-search-blue" @click="recargarTabla('Comisiones')"><i class="fa-solid fa-rotate"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2" style="min-height: 400px;">
                            <div v-show="mostrarCarga4" class="loader-xs"></div>
                            <div class="container">
                                <div class="table-responsive p-0">
                                    <table class="table align-items-center mb-0" id="MyTableComisiones">
                                        <thead>
                                            <tr style="color: black">
                                                <th style="min-width: 16px;"></th>
                                                <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Estado</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Rut</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Nombre</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Responsable</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Correo</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Telefono</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="comision in comisiones" :key="comision.id">
                                                <td></td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{ comision.id }}</p>
                                                </td>
                                                <td>
                                                    <p v-if="comision.estado == true" class="text-sm font-weight-bolder mb-0" style="color: #28A745">Activo</p>
                                                    <p v-if="comision.estado == false" class="text-sm font-weight-bolder mb-0" style="color: #B80F0A">Inactivo</p>
                                                    <p v-if="comision.estado == null" class="text-sm font-weight-bolder mb-0" style="color: black">---</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{comision.rut}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{comision.nombre}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{comision.usuario.name}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{comision.correo}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{comision.telefono}}</p>
                                                </td>
                                                <td class="align-middle text-end">
                                                    <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                        <a class="btn btn-alert btn-xs" title="Editar" @click="editComision(comision)"><i class="fa fa-fw fa-edit"></i></a>
                                                        &nbsp;
                                                        <a class="btn btn-closed btn-xs" title="Eliminar" @click="deleteComision(comision.id,comision.nombre)"><i class="fa fa-fw fa-trash"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </slot>
                    <!-- ################################################################################################################################# -->
                    <slot v-if="isVisibleMetasColaboradores">
                        <div class="row pb-0 p-4">
                            <div class="col-12">
                                <h6>Metas Colaboradores</h6>
                                <div class="d-flex justify-content-end">
                                    <a class="btn btn-continue" @click="showCreateMetaColaborador = true">Nuevo</a>
                                    &nbsp;
                                    <a class="btn btn-search-blue" @click="recargarTabla('MetasColaboradores')"><i class="fa-solid fa-rotate"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2" style="min-height: 400px;">
                            <div v-show="mostrarCarga8" class="loader-xs"></div>
                            <div class="container">
                                <div class="table-responsive p-0">
                                    <table class="table align-items-center mb-0" id="MyTableMetasColaboradores">
                                        <thead>
                                            <tr style="color: black">
                                                <th style="min-width: 16px;"></th>
                                                <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Tienda</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Colaborador</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Meta Total</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Meta Carteras</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Meta Marroquinería</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Meta Calzado</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Meta Perfume</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Meta Vestimenta</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Año</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Mes</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="metaColaborador in metasColaboradores" :key="metaColaborador.id">
                                                <td></td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{metaColaborador.id}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{metaColaborador.tienda.nombre}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{metaColaborador.vendedor.nombre1 + " " + metaColaborador.vendedor.nombre2 + " " + metaColaborador.vendedor.apellido1 + " " + metaColaborador.vendedor.apellido2 }}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{formatMoneyCLP(metaColaborador.meta_total)}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{formatMoneyCLP(metaColaborador.meta_carteras)}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{formatMoneyCLP(metaColaborador.meta_marroquineria)}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{formatMoneyCLP(metaColaborador.meta_calzado)}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{formatMoneyCLP(metaColaborador.meta_perfume)}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{formatMoneyCLP(metaColaborador.meta_vestimenta)}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{metaColaborador.año}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{metaColaborador.mes.toString().padStart(2, "0")}}</p>
                                                </td>
                                                <td class="align-middle text-end">
                                                    <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                        <a class="btn btn-alert btn-xs" title="Editar" @click="editMetaColaborador(metaColaborador)"><i class="fa fa-fw fa-edit"></i></a>
                                                        &nbsp;
                                                        <a class="btn btn-closed btn-xs" title="Eliminar" @click="deleteMetaColaborador(metaColaborador.id,metaColaborador.nombre)"><i class="fa fa-fw fa-trash"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </slot>
                    <!-- ################################################################################################################################# -->
                    <slot v-if="isVisibleMetasDiarias">
                        <div class="row pb-0 p-4">
                            <div class="col-12">
                                <h6>Metas Diarias</h6>
                                <div class="d-flex justify-content-end">
                                    <a class="btn btn-continue" @click="showCreateMetaDiaria = true">Nuevo</a>
                                    &nbsp;
                                    <a class="btn btn-search-blue" @click="recargarTabla('MetasDiarias')"><i class="fa-solid fa-rotate"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2" style="min-height: 400px;">
                            <div v-show="mostrarCarga7" class="loader-xs"></div>
                            <div class="container">
                                <div class="table-responsive p-0">
                                    <table class="table align-items-center mb-0" id="MyTableMetasDiarias">
                                        <thead>
                                            <tr style="color: black">
                                                <th style="min-width: 16px;"></th>
                                                <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Tienda</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Meta Total</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Meta Carteras</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Meta Marroquinería</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Meta Calzado</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Meta Perfume</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Meta Vestimenta</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Año</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Mes</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Día</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="metaDiaria in metasDiarias" :key="metaDiaria.id">
                                                <td></td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{metaDiaria.id}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{metaDiaria.tienda.nombre}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{formatMoneyCLP(metaDiaria.meta_total)}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{formatMoneyCLP(metaDiaria.meta_carteras)}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{formatMoneyCLP(metaDiaria.meta_marroquineria)}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{formatMoneyCLP(metaDiaria.meta_calzado)}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{formatMoneyCLP(metaDiaria.meta_perfume)}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{formatMoneyCLP(metaDiaria.meta_vestimenta)}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{metaDiaria.año}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{metaDiaria.mes.toString().padStart(2, "0")}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{metaDiaria.dia.toString().padStart(2, "0")}}</p>
                                                </td>
                                                <td class="align-middle text-end">
                                                    <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                        <a class="btn btn-alert btn-xs" title="Editar" @click="editMetaDiaria(metaDiaria)"><i class="fa fa-fw fa-edit"></i></a>
                                                        &nbsp;
                                                        <a class="btn btn-closed btn-xs" title="Eliminar" @click="deleteMetaDiaria(metaDiaria.id,metaDiaria.nombre)"><i class="fa fa-fw fa-trash"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </slot>
                    <!-- ################################################################################################################################# -->
                    <slot v-if="isVisibleMetasMensuales">
                        <div class="row pb-0 p-4">
                            <div class="col-12">
                                <h6>Metas Mensuales</h6>
                                <div class="d-flex justify-content-end">
                                    <a class="btn btn-continue" @click="showCreateMetaMensual = true">Nuevo</a>
                                    &nbsp;
                                    <a class="btn btn-search-blue" @click="recargarTabla('MetasMensuales')"><i class="fa-solid fa-rotate"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2" style="min-height: 400px;">
                            <div v-show="mostrarCarga6" class="loader-xs"></div>
                            <div class="container">
                                <div class="table-responsive p-0">
                                    <table class="table align-items-center mb-0" id="MyTableMetasMensuales">
                                        <thead>
                                            <tr style="color: black">
                                                <th style="min-width: 16px;"></th>
                                                <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Tienda</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Meta Total</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Meta Carteras</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Meta Marroquinería</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Meta Calzado</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Meta Perfume</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Meta Vestimenta</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Año</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Mes</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="metaMensual in metasMensuales" :key="metaMensual.id">
                                                <td></td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{metaMensual.id}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{metaMensual.tienda.nombre}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{formatMoneyCLP(metaMensual.meta_total)}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{formatMoneyCLP(metaMensual.meta_carteras)}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{formatMoneyCLP(metaMensual.meta_marroquineria)}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{formatMoneyCLP(metaMensual.meta_calzado)}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{formatMoneyCLP(metaMensual.meta_perfume)}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{formatMoneyCLP(metaMensual.meta_vestimenta)}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{metaMensual.año}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{metaMensual.mes.toString().padStart(2, "0")}}</p>
                                                </td>
                                                <td class="align-middle text-end">
                                                    <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                        <a class="btn btn-alert btn-xs" title="Editar" @click="editMetaMensual(metaMensual)"><i class="fa fa-fw fa-edit"></i></a>
                                                        &nbsp;
                                                        <a class="btn btn-closed btn-xs" title="Eliminar" @click="deleteMetaMensual(metaMensual.id,metaMensual.nombre)"><i class="fa fa-fw fa-trash"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </slot>
                    <!-- ################################################################################################################################# -->
                    <slot v-if="isVisibleContadoresPersonas">
                        <div class="row pb-0 p-4">
                            <div class="col-12">
                                <h6>Contadores de Personas</h6>
                                <div class="d-flex justify-content-end">
                                    <a class="btn btn-continue" @click="showCreateContadorPersonas = true">Nuevo</a>
                                    &nbsp;
                                    <a class="btn btn-search-blue" @click="recargarTabla('ContadoresPersonas')"><i class="fa-solid fa-rotate"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2" style="min-height: 400px;">
                            <div v-show="mostrarCarga9" class="loader-xs"></div>
                            <div class="container">
                                <div class="table-responsive p-0">
                                    <table class="table align-items-center mb-0" id="MyTableContadoresPersonas">
                                        <thead>
                                            <tr style="color: black">
                                                <th style="min-width: 16px;"></th>
                                                <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Tienda</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Tipo Entrada</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">H1</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">H2</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">H3</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">H4</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">H5</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">H6</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">H7</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">H8</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">H9</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">H10</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">H11</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">H12</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">H13</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">H14</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">H15</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">H16</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">H17</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">H18</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">H19</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">H20</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">H21</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">H22</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">H23</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">H24</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="contadorPersonas in contadoresPersonas" :key="contadorPersonas.id">
                                                <td></td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{contadorPersonas.id}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{contadorPersonas.tienda.nombre}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{contadorPersonas.tipoEntrada}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{contadorPersonas.h1}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{contadorPersonas.h2}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{contadorPersonas.h3}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{contadorPersonas.h4}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{contadorPersonas.h5}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{contadorPersonas.h6}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{contadorPersonas.h7}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{contadorPersonas.h8}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{contadorPersonas.h9}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{contadorPersonas.h10}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{contadorPersonas.h11}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{contadorPersonas.h12}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{contadorPersonas.h13}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{contadorPersonas.h14}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{contadorPersonas.h15}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{contadorPersonas.h16}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{contadorPersonas.h17}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{contadorPersonas.h18}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{contadorPersonas.h19}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{contadorPersonas.h20}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{contadorPersonas.h21}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{contadorPersonas.h22}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{contadorPersonas.h23}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{contadorPersonas.h24}}</p>
                                                </td>
                                                <td class="align-middle text-end">
                                                    <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                        <a class="btn btn-alert btn-xs" title="Editar" @click="editContadorPersonas(contadorPersonas)"><i class="fa fa-fw fa-edit"></i></a>
                                                        &nbsp;
                                                        <a class="btn btn-closed btn-xs" title="Eliminar" @click="deleteContadorPersonas(contadorPersonas.id,contadorPersonas.nombre)"><i class="fa fa-fw fa-trash"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </slot>
                    <!-- ################################################################################################################################# -->
                    <slot v-if="isVisibleBancos">
                        <div class="row pb-0 p-4">
                            <div class="col-12">
                                <h6>Bancos</h6>
                                <div class="d-flex justify-content-end">
                                    <a class="btn btn-continue" @click="showCreateBanco = true">Nuevo</a>
                                    &nbsp;
                                    <a class="btn btn-search-blue" @click="recargarTabla('Bancos')"><i class="fa-solid fa-rotate"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2" style="min-height: 400px;">
                            <div v-show="mostrarCarga5" class="loader-xs"></div>
                            <div class="container">
                                <div class="table-responsive p-0">
                                    <table class="table align-items-center mb-0" id="myTableBancos">
                                        <thead>
                                            <tr style="color: black">
                                                <th style="min-width: 16px;"></th>
                                                <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Nombre</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="banco in bancos" :key="banco.id">
                                                <td></td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{ banco.id }}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color: black">{{banco.nombre}}</p>
                                                </td>
                                                <td class="align-middle text-end">
                                                    <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                        <a class="btn btn-alert btn-xs" title="Editar" @click="editBanco(banco)"><i class="fa fa-fw fa-edit"></i></a>
                                                        &nbsp;
                                                        <a class="btn btn-closed btn-xs" title="Eliminar" @click="deleteBanco(banco.id,banco.nombre)"><i class="fa fa-fw fa-trash"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </slot>
                </div>
                <modalEditPromotor v-bind:promotor="promotorEdit" v-if="showEditPromotor" @close="showEditPromotor = false" @recarga="recargarTabla('Promotores')"></modalEditPromotor>
                <modalCreatePromotor v-if="showCreatePromotor" @close="showCreatePromotor = false" @recarga="recargarTabla('Promotores')"></modalCreatePromotor>
                <modalEditBanco v-bind:banco="bancoEdit" v-if="showEditBanco" @close="showEditBanco = false" @recarga="recargarTabla('Bancos')"></modalEditBanco>
                <modalCreateBanco v-if="showCreateBanco" @close="showCreateBanco = false" @recarga="recargarTabla('Bancos')"></modalCreateBanco>
                <modalEditSupervisor v-bind:supervisor="supervisorEdit" v-if="showEditSupervisor" @close="showEditSupervisor = false" @recarga="recargarTabla('Supervisores')"></modalEditSupervisor>
                <modalCreateSupervisor v-if="showCreateSupervisor" @close="showCreateSupervisor = false" @recarga="recargarTabla('Supervisores')"></modalCreateSupervisor>
                <modalEditSucursal v-bind:sucursal1="sucursalEdit" v-if="showEditSucursal" @close="showEditSucursal = false" @recarga="recargarTabla('Sucursales')"></modalEditSucursal>
                <modalCreateSucursal v-if="showCreateSucursal" @close="showCreateSucursal = false" @recarga="recargarTabla('Sucursales')"></modalCreateSucursal>
                <modalDetalleSucursal v-bind:sucursal="detalleSucursal" v-if="showDetallesSucursal" @close="showDetallesSucursal = false" ></modalDetalleSucursal>
                <modalEditComision v-bind:comision1="comisionEdit" v-if="showEditComision" @close="showEditComision = false" @recarga="recargarTabla('Comisiones')"></modalEditComision>
                <modalCreateComision v-if="showCreateComision" @close="showCreateComision = false" @recarga="recargarTabla('Comisiones')"></modalCreateComision>
                <modalCreateMetaMensual v-if="showCreateMetaMensual" @close="showCreateMetaMensual = false" @recarga="recargarTabla('MetasMensuales')"></modalCreateMetaMensual>
                <modalEditMetaMensual v-bind:metaMensual1="metaMensualEdit" v-if="showEditMetaMensual" @close="showEditMetaMensual = false" @recarga="recargarTabla('MetasMensuales')"></modalEditMetaMensual>
                <modalCreateMetaDiaria v-if="showCreateMetaDiaria" @close="showCreateMetaDiaria = false" @recarga="recargarTabla('MetasDiarias')"></modalCreateMetaDiaria>
                <modalEditMetaDiaria v-bind:metaDiaria1="metaDiariaEdit" v-if="showEditMetaDiaria" @close="showEditMetaDiaria = false" @recarga="recargarTabla('MetasDiarias')"></modalEditMetaDiaria>
                <modalCreateMetaColaborador v-if="showCreateMetaColaborador" @close="showCreateMetaColaborador = false" @recarga="recargarTabla('MetasColaboradores')"></modalCreateMetaColaborador>
                <modalEditMetaColaborador v-bind:metaColaborador1="metaColaboradorEdit" v-if="showEditMetaColaborador" @close="showEditMetaColaborador = false" @recarga="recargarTabla('MetasColaboradores')"></modalEditMetaColaborador>
                <modalCreateContadorPersonas v-if="showCreateContadorPersonas" @close="showCreateContadorPersonas = false" @recarga="recargarTabla('ContadoresPersonas')"></modalCreateContadorPersonas>
                <modalEditContadorPersonas v-bind:contadorPersonas1="contadorPersonaEdit" v-if="showEditContadorPersonas" @close="showEditContadorPersonas = false" @recarga="recargarTabla('ContadoresPersonas')"></modalEditContadorPersonas>
                <modalCreateVendedor v-if="showCreateVendedor" @close="showCreateVendedor = false" @recarga="recargarTabla('Vendedores')"></modalCreateVendedor>
                <modalEditVendedor v-bind:vendedor1="vendedorEdit" v-if="showEditVendedor" @close="showEditVendedor = false" @recarga="recargarTabla('Vendedores')"></modalEditVendedor>
                <modalconfirmacion ref="confirmation"></modalconfirmacion>
                <modalalerta ref="alert"></modalalerta>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';
import {mixin} from '../../../mixins.js';
import modalconfirmacion from '../../snippets/sistema/alerts/confirmationModal.vue';
import modalalerta from '../../snippets/sistema/alerts/alertModal.vue';
import modalCreatePromotor from '../../snippets/sistema/adminGeneral/promotores/createPromotorModal.vue';
import modalEditPromotor from '../../snippets/sistema/adminGeneral/promotores/editPromotorModal.vue';
import modalCreateSupervisor from '../../snippets/sistema/adminGeneral/supervisores/createSupervisorModal.vue';
import modalEditSupervisor from '../../snippets/sistema/adminGeneral/supervisores/editSupervisorModal.vue';
import modalCreateSucursal from '../../snippets/sistema/adminGeneral/sucursales/createSucursalModal.vue';
import modalEditSucursal from '../../snippets/sistema/adminGeneral/sucursales/editSucursalModal.vue';
import modalCreateBanco from '../../snippets/sistema/adminGeneral/bancos/createBancoModal.vue';
import modalEditBanco from '../../snippets/sistema/adminGeneral/bancos/editBancoModal.vue';
import modalDetalleSucursal from '../../snippets/sistema/adminGeneral/sucursales/detalleSucursalModal.vue';
import modalCreateComision from '../../snippets/sistema/adminGeneral/comisiones/createComisionModal.vue';
import modalEditComision from '../../snippets/sistema/adminGeneral/comisiones/editComisionModal.vue';
import modalCreateMetaMensual from '../../snippets/sistema/adminGeneral/metasMensuales/createMetasMensualesModal.vue';
import modalEditMetaMensual from '../../snippets/sistema/adminGeneral/metasMensuales/editMetasMensualesModal.vue';
import modalCreateMetaDiaria from '../../snippets/sistema/adminGeneral/metasDiarias/createMetasDiariasModal.vue';
import modalEditMetaDiaria from '../../snippets/sistema/adminGeneral/metasDiarias/editMetasDiariasModal.vue';
import modalCreateMetaColaborador from '../../snippets/sistema/adminGeneral/metasColaboradores/createMetasColaboradoresModal.vue';
import modalEditMetaColaborador from '../../snippets/sistema/adminGeneral/metasColaboradores/editMetasColaboradoresModal.vue';
import modalCreateContadorPersonas from '../../snippets/sistema/adminGeneral/contadorPersonas/createContadorPersonasModal.vue';
import modalEditContadorPersonas from '../../snippets/sistema/adminGeneral/contadorPersonas/editContadorPersonasModal.vue';
import modalCreateVendedor from '../../snippets/sistema/adminGeneral/vendedores/createVendedorModal.vue';
import modalEditVendedor from '../../snippets/sistema/adminGeneral/vendedores/editVendedorModal.vue';

export default {
    components: { modalconfirmacion, modalalerta, modalCreateVendedor, modalEditVendedor, modalCreateMetaDiaria, modalEditMetaDiaria, modalCreateMetaColaborador, modalEditMetaColaborador, modalCreateContadorPersonas, modalEditContadorPersonas, modalCreateMetaMensual, modalEditMetaMensual, modalCreateSupervisor, modalEditSupervisor, modalCreatePromotor, modalEditPromotor, modalEditSucursal, modalCreateSucursal, modalDetalleSucursal, modalCreateComision, modalEditComision, modalCreateBanco, modalEditBanco },
    mixins: [mixin],
    data: () => ({
        // Comisiones
        isVisibleComisiones: false,
        comisiones: null,
        showEditComision: false,
        comisionEdit: null,
        showCreateComision: false,
        // Sucursales
        isVisibleSucursales: true,
        sucursales: null,
        showEditSucursal: false,
        sucursalEdit: null,
        showCreateSucursal: false,
        showDetallesSucursal: false,
        detalleSucursal: null,
        // Promotores
        isVisiblePromotores: false,
        promotores: null,
        showEditPromotor: false,
        promotorEdit: null,
        showCreatePromotor: false,
        // Supervisores
        isVisibleSupervisores: false,
        supervisores: null,
        showEditSupervisor: false,
        supervisorEdit: null,
        showCreateSupervisor: false,
        // Vendedores
        isVisibleVendedores: false,
        vendedores: null,
        showEditVendedor: false,
        vendedorEdit: null,
        showCreateVendedor: false,
        // Metas Mensuales
        isVisibleMetasMensuales: false,
        metasMensuales: null,
        showEditMetaMensual: false,
        metaMensualEdit: null,
        showCreateMetaMensual: false,
        // Metas Diarias
        isVisibleMetasDiarias: false,
        metasDiarias: null,
        showEditMetaDiaria: false,
        metaDiariaEdit: null,
        showCreateMetaDiaria: false,
        // Metas Colaboradores
        isVisibleMetasColaboradores: false,
        metasColaboradores: null,
        showEditMetaColaborador: false,
        metaColaboradorEdit: null,
        showCreateMetaColaborador: false,
        // Contador de Personas
        isVisibleContadoresPersonas: false,
        contadoresPersonas: null,
        showEditContadorPersonas: false,
        contadorPersonaEdit: null,
        showCreateContadorPersonas: false,
        // Bancos
        isVisibleBancos: false,
        bancos: null,
        showEditBanco: false,
        bancoEdit: null,
        showCreateBanco: false,
        // -----------------------
        tableSucursales: null,
        mostrarCarga1: true,
        mostrarTabla1: false,
        // -----------------------
        tablePromotores: null,
        mostrarCarga2: true,
        mostrarTabla2: false,
        // -----------------------
        tableSupervisores: null,
        mostrarCarga3: true,
        mostrarTabla3: false,
        // -----------------------
        tableComisiones: null,
        mostrarCarga4: true,
        mostrarTabla4: false,
        // -----------------------
        tableBancos: null,
        mostrarCarga5: true,
        mostrarTabla5: false,
        // -----------------------
        tableMetasMensuales: null,
        mostrarCarga6: true,
        mostrarTabla6: false,
        // -----------------------
        tableMetasDiarias: null,
        mostrarCarga7: true,
        mostrarTabla7: false,
        // -----------------------
        tableMetasColaborador: null,
        mostrarCarga8: true,
        mostrarTabla8: false,
        // -----------------------
        tableContadoresPersonas: null,
        mostrarCarga9: true,
        mostrarTabla9: false,
        // -----------------------
        tableVendedores: null,
        mostrarCarga10: true,
        mostrarTabla10: false,
    }),
    created(){
        this.getSucursales();
    },
    methods: {
        sucursalDetalle(sucursal){
            this.detalleSucursal = sucursal;
            this.showDetallesSucursal = true;
        },
        recargarTabla($tabla){
            if ($tabla == 'Comisiones'){
                this.comisiones = null;
                this.mostrarCarga4 = true;
                this.getComisiones();
            }
            if ($tabla == 'Sucursales'){
                this.sucursales = null;
                this.mostrarCarga1 = true;
                this.getSucursales();
            }
            if ($tabla == 'Promotores'){
                this.promotores = null;
                this.mostrarCarga2 = true;
                this.getPromotores();
            }
            if ($tabla == 'Supervisores'){
                this.supervisores = null;
                this.mostrarCarga3 = true;
                this.getSupervisores();
            }
            if ($tabla == 'Bancos'){
                this.bancos = null;
                this.mostrarCarga5 = true;
                this.getBancos();
            }
            if ($tabla == 'MetasMensuales'){
                this.metasMensuales = null;
                this.mostrarCarga6 = true;
                this.getMetasMensuales();
            }
            if ($tabla == 'MetasDiarias'){
                this.metasDiarias = null;
                this.mostrarCarga7 = true;
                this.getMetasDiarias();
            }
            if ($tabla == 'MetasColaboradores'){
                this.metasColaboradores = null;
                this.mostrarCarga8 = true;
                this.getMetasColaboradores();
            }
            if ($tabla == 'ContadoresPersonas'){
                this.contadoresPersonas = null;
                this.mostrarCarga9 = true;
                this.getContadoresPersonas();
            }
            if ($tabla == 'Vendedores'){
                this.vendedores = null;
                this.mostrarCarga10 = true;
                this.getVendedores();
            }
        },
        crearTablas($tabla){
            if($tabla == 'Comisiones'){
                setTimeout(function(){
                    this.tableComisiones = $('#MyTableComisiones').DataTable({info: false, select:false, "bLengthChange" : false});
                    this.tableComisiones.buttons().remove();
                    this.mostrarCarga4 = false;
                    this.mostrarTabla4 = true;   
                }.bind(this),200);
            }
            if($tabla == 'Sucursales'){
                setTimeout(function(){
                    this.tableSucursales = $('#MyTableSucursales').DataTable({info: false, select:false, "bLengthChange" : false});
                    this.tableSucursales.buttons().remove();
                    this.mostrarCarga1 = false;
                    this.mostrarTabla1 = true;   
                }.bind(this),200);
            }
            if($tabla == 'Promotores'){
                setTimeout(function(){
                    this.tablePromotores = $('#MyTablePromotores').DataTable({info: false, select:false, "bLengthChange" : false});
                    this.tablePromotores.buttons().remove();
                    this.mostrarCarga2 = false;
                    this.mostrarTabla2 = true;   
                }.bind(this),200);
            }
            if($tabla == 'Supervisores'){
                setTimeout(function(){
                    this.tableSupervisores = $('#MyTableSupervisores').DataTable({info: false, select:false, "bLengthChange" : false});
                    this.tableSupervisores.buttons().remove();
                    this.mostrarCarga3 = false;
                    this.mostrarTabla3 = true;   
                }.bind(this),200);
            }
            if($tabla == 'Bancos'){
                setTimeout(function(){
                    this.tableBancos = $('#MyTableBancos').DataTable({info: false, select:false, "bLengthChange" : false});
                    this.tableBancos.buttons().remove();
                    this.mostrarCarga5 = false;
                    this.mostrarTabla5 = true;   
                }.bind(this),200);
            }
            if($tabla == 'MetasMensuales'){
                setTimeout(function(){
                    this.tableMetasMensuales = $('#MyTableMetasMensuales').DataTable({info: false, select:false, "bLengthChange" : false});
                    this.tableMetasMensuales.buttons().remove();
                    this.mostrarCarga6 = false;
                    this.mostrarTabla6 = true;   
                }.bind(this),200);
            }
            if($tabla == 'MetasDiarias'){
                setTimeout(function(){
                    this.tableMetasDiarias = $('#MyTableMetasDiarias').DataTable({info: false, select:false, "bLengthChange" : false});
                    this.tableMetasDiarias.buttons().remove();
                    this.mostrarCarga7 = false;
                    this.mostrarTabla7 = true;   
                }.bind(this),200);
            }
            if($tabla == 'MetasColaboradores'){
                setTimeout(function(){
                    this.tableMetasColaboradores = $('#MyTableMetasColaboradores').DataTable({info: false, select:false, "bLengthChange" : false});
                    this.tableMetasColaboradores.buttons().remove();
                    this.mostrarCarga8 = false;
                    this.mostrarTabla8 = true;   
                }.bind(this),200);
            }
            if($tabla == 'ContadoresPersonas'){
                setTimeout(function(){
                    this.tableContadoresPersonas = $('#MyTableContadoresPersonas').DataTable({info: false, select:false, "bLengthChange" : false});
                    this.tableContadoresPersonas.buttons().remove();
                    this.mostrarCarga9 = false;
                    this.mostrarTabla9 = true;   
                }.bind(this),200);
            }
            if($tabla == 'Vendedores'){
                setTimeout(function(){
                    this.tableVendedores = $('#MyTableVendedores').DataTable({info: false, select:false, "bLengthChange" : false});
                    this.tableVendedores.buttons().remove();
                    this.mostrarCarga10 = false;
                    this.mostrarTabla10 = true;   
                }.bind(this),200);
            }
        },
        botonCheck($entrada){
            this.isVisibleComisiones = false;
            this.isVisibleSucursales = false;
            this.isVisiblePromotores = false;
            this.isVisibleSupervisores = false;
            this.isVisibleBancos = false;
            this.isVisibleMetasMensuales = false;
            this.isVisibleMetasDiarias = false;
            this.isVisibleMetasColaboradores = false;
            this.isVisibleContadoresPersonas = false;
            this.isVisibleVendedores = false;
            if ($entrada == 'Comisiones'){
                this.comisiones = null;
                this.mostrarCarga4 = true;
                this.getComisiones();
                this.isVisibleComisiones = true;
            }
            if ($entrada == 'Sucursales'){
                this.sucursales = null;
                this.mostrarCarga1 = true;
                this.getSucursales();
                this.isVisibleSucursales = true;
            }
            if ($entrada == 'Promotores'){
                this.promotores = null;
                this.mostrarCarga2 = true;
                this.getPromotores();
                this.isVisiblePromotores = true;
            }
            if ($entrada == 'Supervisores'){
                this.supervisores = null;
                this.mostrarCarga3 = true;
                this.getSupervisores();
                this.isVisibleSupervisores = true;
            }
            if ($entrada == 'Bancos'){
                this.bancos = null;
                this.mostrarCarga5 = true;
                this.getBancos();
                this.isVisibleBancos = true;
            }
            if ($entrada == 'MetasMensuales'){
                this.metasMensuales = null;
                this.mostrarCarga6 = true;
                this.getMetasMensuales();
                this.isVisibleMetasMensuales = true;
            }
            if ($entrada == 'MetasDiarias'){
                this.metasDiarias = null;
                this.mostrarCarga7 = true;
                this.getMetasDiarias();
                this.isVisibleMetasDiarias = true;
            }
            if ($entrada == 'MetasColaboradores'){
                this.metasColaboradores = null;
                this.mostrarCarga8 = true;
                this.getMetasColaboradores();
                this.isVisibleMetasColaboradores = true;
            }
            if ($entrada == 'ContadoresPersonas'){
                this.contadoresPersonas = null;
                this.mostrarCarga9 = true;
                this.getContadoresPersonas();
                this.isVisibleContadoresPersonas = true;
            }
            if ($entrada == 'Vendedores'){
                this.vendedores = null;
                this.mostrarCarga10 = true;
                this.getVendedores();
                this.isVisibleVendedores = true;
            }
        },
        // COMISIONES:
        getComisiones(){
            axios.get('../api/comisiones').then( response =>{
                this.mostrarCarga4 = true;
                this.comisiones = response.data;
                if (this.tableComisiones != null){
                    this.tableComisiones.clear();
                    this.tableComisiones.destroy();
                }
                this.crearTablas('Comisiones');
            }).catch(e=> console.log(e))
        },
        async deleteComision(id,comision){
            const ok = await this.$refs.confirmation.show({
                title: 'Borrar Comisión',
                message: `¿Está seguro/a que desea eliminar la Comisión '${comision}'? Esta acción no puede ser desecha.`,
                okButton: 'Eliminar',
                cancelButton: 'Volver'
            })
            if (ok) {
                axios.delete(`../api/comisiones/${id}`).then( response =>{
                    this.toast.success("Comisión eliminada con éxito!", {
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
                    this.recargarTabla('Comisiones');
                }).catch(e=> console.log(e))
            }
        },
        editComision(comision){
            this.comisionEdit = comision;
            this.showEditComision = true;
        },
        // SUCURSALES:
        getSucursales(){
            axios.get('../api/sucursales').then( response =>{
                this.mostrarCarga1 = true;
                this.sucursales = response.data;
                if (this.tableSucursales != null){
                    this.tableSucursales.clear();
                    this.tableSucursales.destroy();
                }
                this.crearTablas('Sucursales');
            }).catch(e=> console.log(e))
        },
        async deleteSucursal(id,sucursal){
            const ok = await this.$refs.confirmation.show({
                title: 'Borrar Sucursal',
                message: `¿Está seguro/a que desea eliminar la sucursal '${sucursal}'? Esta acción no puede ser desecha.`,
                okButton: 'Eliminar',
                cancelButton: 'Volver'
            })
            if (ok) {
                axios.delete(`../api/sucursales/${id}`).then( response =>{
                    this.toast.success("Sucursal eliminada con éxito!", {
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
                    this.recargarTabla('Sucursales');
                }).catch(e=> console.log(e))
            }
        },
        editSucursal(sucursal){
            this.sucursalEdit = sucursal;
            this.showEditSucursal = true;
        },
        // PROMOTORES:
        getPromotores(){
            axios.get('../api/promotores').then( response =>{
                this.mostrarCarga2 = true;
                this.promotores = response.data;
                if (this.tablePromotores != null){
                    this.tablePromotores.clear();
                    this.tablePromotores.destroy();
                }
                this.crearTablas('Promotores');
            }).catch(e=> console.log(e))
        },
        async deletePromotor(id,promotor,tienda){
            const ok = await this.$refs.confirmation.show({
                title: 'Borrar Promotor',
                message: `¿Está seguro/a que desea eliminar la relación del promotor/a '${promotor}' con la tienda '${tienda}'? Esta acción no puede ser desecha.`,
                okButton: 'Eliminar',
                cancelButton: 'Volver'
            })
            if (ok) {
                axios.delete(`../api/promotores/${id}`).then( response =>{
                    this.toast.success("Promotor/a eliminado con éxito!", {
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
                    this.recargarTabla('Promotores');
                }).catch(e=> console.log(e))
            }
        },
        editPromotor(promotor){
            this.promotorEdit = promotor;
            this.showEditPromotor = true;
        },
        // SUPERVISORES:
        getSupervisores(){
            axios.get('../api/supervisores').then( response =>{
                this.mostrarCarga3 = true;
                this.supervisores = response.data;
                if (this.tableSupervisores != null){
                    this.tableSupervisores.clear();
                    this.tableSupervisores.destroy();
                }
                this.crearTablas('Supervisores');
            }).catch(e=> console.log(e))
        },
        async deleteSupervisor(id,supervisor,tienda){
            const ok = await this.$refs.confirmation.show({
                title: 'Borrar Supervisor',
                message: `¿Está seguro/a que desea eliminar la relación del supervisor/a '${supervisor}' con la tienda '${tienda}'? Esta acción no puede ser desecha.`,
                okButton: 'Eliminar',
                cancelButton: 'Volver'
            })
            if (ok) {
                axios.delete(`../api/supervisores/${id}`).then( response =>{
                    this.toast.success("Supervisor/a eliminado con éxito!", {
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
                    this.recargarTabla('Supervisores');
                }).catch(e=> console.log(e))
            }
        },
        editSupervisor(supervisor){
            this.supervisorEdit = supervisor;
            this.showEditSupervisor = true;
        },
        // BANCOS:
        getBancos(){
            axios.get('../api/bancos').then( response =>{
                this.mostrarCarga5 = true;
                this.bancos = response.data;
                if (this.tableBancos != null){
                    this.tableBancos.clear();
                    this.tableBancos.destroy();
                }
                this.crearTablas('Bancos');
            }).catch(e=> console.log(e))
        },
        async deleteBanco(id,banco){
            const ok = await this.$refs.confirmation.show({
                title: 'Borrar Banco',
                message: `¿Está seguro/a que desea eliminar el banco '${banco}' ? Esta acción no puede ser desecha.`,
                okButton: 'Eliminar',
                cancelButton: 'Volver'
            })
            if (ok) {
                axios.delete(`../api/bancos/${id}`).then( response =>{
                    this.toast.success("Banco eliminado con éxito!", {
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
                    this.recargarTabla('Bancos');
                }).catch(e=> console.log(e))
            }
        },
        editBanco(banco){
            this.bancoEdit = banco;
            this.showEditBanco = true;
        },
        // METAS MENSUALES:
        getMetasMensuales(){
            axios.get('../api/metasTienda').then( response =>{
                this.mostrarCarga6 = true;
                this.metasMensuales = response.data;
                if (this.tableMetasMensuales != null){
                    this.tableMetasMensuales.clear();
                    this.tableMetasMensuales.destroy();
                }
                this.crearTablas('MetasMensuales');
            }).catch(e=> console.log(e))
        },
        async deleteMetaMensual(id){
            const ok = await this.$refs.confirmation.show({
                title: 'Borrar Meta Mensual',
                message: `¿Está seguro/a que desea eliminar la meta mensual? Esta acción no puede ser desecha.`,
                okButton: 'Eliminar',
                cancelButton: 'Volver'
            })
            if (ok) {
                axios.delete(`../api/metasTienda/${id}`).then( response =>{
                    this.toast.success("Meta Mensual eliminada con éxito!", {
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
                    this.recargarTabla('MetasMensuales');
                }).catch(e=> console.log(e))
            }
        },
        editMetaMensual(metaMensual){
            this.metaMensualEdit = metaMensual;
            this.showEditMetaMensual = true;
        },
        // METAS DIARIAS:
        getMetasDiarias(){
            axios.get('../api/metasDiarias').then( response =>{
                this.mostrarCarga7 = true;
                this.metasDiarias = response.data;
                if (this.tableMetasDiarias != null){
                    this.tableMetasDiarias.clear();
                    this.tableMetasDiarias.destroy();
                }
                this.crearTablas('MetasDiarias');
            }).catch(e=> console.log(e))
        },
        async deleteMetaDiaria(id){
            const ok = await this.$refs.confirmation.show({
                title: 'Borrar Meta Diaria',
                message: `¿Está seguro/a que desea eliminar la meta diaria? Esta acción no puede ser desecha.`,
                okButton: 'Eliminar',
                cancelButton: 'Volver'
            })
            if (ok) {
                axios.delete(`../api/metasDiarias/${id}`).then( response =>{
                    this.toast.success("Meta Diaria eliminada con éxito!", {
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
                    this.recargarTabla('MetasDiarias');
                }).catch(e=> console.log(e))
            }
        },
        editMetaDiaria(metaDiaria){
            this.metaDiariaEdit = metaDiaria;
            this.showEditMetaDiaria = true;
        },
        // METAS COLABORADORES:
        getMetasColaboradores(){
            axios.get('../api/metasColaborador').then( response =>{
                this.mostrarCarga8 = true;
                this.metasColaboradores = response.data;
                if (this.tableMetasColaboradores != null){
                    this.tableMetasColaboradores.clear();
                    this.tableMetasColaboradores.destroy();
                }
                this.crearTablas('MetasColaboradores');
            }).catch(e=> console.log(e))
        },
        async deleteMetaColaborador(id){
            const ok = await this.$refs.confirmation.show({
                title: 'Borrar Meta Colaborador',
                message: `¿Está seguro/a que desea eliminar la meta del colaborador? Esta acción no puede ser desecha.`,
                okButton: 'Eliminar',
                cancelButton: 'Volver'
            })
            if (ok) {
                axios.delete(`../api/metasColaborador/${id}`).then( response =>{
                    this.toast.success("Meta Colaborador eliminada con éxito!", {
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
                    this.recargarTabla('MetasColaboradores');
                }).catch(e=> console.log(e))
            }
        },
        editMetaColaborador(metaColaborador){
            this.metaColaboradorEdit = metaColaborador;
            this.showEditMetaColaborador = true;
        },
        // CONTADOR DE PERSONAS:
        getContadoresPersonas(){
            axios.get('../api/contadoresPersonas').then( response =>{
                this.mostrarCarga9 = true;
                this.contadoresPersonas = response.data;
                if (this.tableContadoresPersonas != null){
                    this.tableContadoresPersonas.clear();
                    this.tableContadoresPersonas.destroy();
                }
                this.crearTablas('ContadoresPersonas');
            }).catch(e=> console.log(e))
        },
        async deleteContadorPersonas(id){
            const ok = await this.$refs.confirmation.show({
                title: 'Borrar Contador de Personas',
                message: `¿Está seguro/a que desea eliminar el contador de personas? Esta acción no puede ser desecha.`,
                okButton: 'Eliminar',
                cancelButton: 'Volver'
            })
            if (ok) {
                axios.delete(`../api/contadoresPersonas/${id}`).then( response =>{
                    this.toast.success("Contador de Personas eliminado con éxito!", {
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
                    this.recargarTabla('ContadoresPersonas');
                }).catch(e=> console.log(e))
            }
        },
        editContadorPersonas(contadorPersona){
            this.contadorPersonaEdit = contadorPersona;
            this.showEditContadorPersonas = true;
        },
        // VENDEDORES:
        getVendedores(){
            axios.get('../api/vendedores').then( response =>{
                this.mostrarCarga10 = true;
                this.vendedores = response.data;
                if (this.tableVendedores != null){
                    this.tableVendedores.clear();
                    this.tableVendedores.destroy();
                }
                this.crearTablas('Vendedores');
            }).catch(e=> console.log(e))
        },
        async deleteVendedor(id){
            const ok = await this.$refs.confirmation.show({
                title: 'Borrar Vendedor',
                message: `¿Está seguro/a que desea eliminar el vendedor? Esta acción no puede ser desecha.`,
                okButton: 'Eliminar',
                cancelButton: 'Volver'
            })
            if (ok) {
                axios.delete(`../api/vendedores/${id}`).then( response =>{
                    this.toast.success("Vendedor eliminado con éxito!", {
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
                    this.recargarTabla('Vendedores');
                }).catch(e=> console.log(e))
            }
        },
        editVendedor(vendedor){
            this.vendedorEdit = vendedor;
            this.showEditVendedor = true;
        },
    }
}
</script>