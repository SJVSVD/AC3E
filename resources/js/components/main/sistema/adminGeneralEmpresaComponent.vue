<template>
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4" style="flex-direction: inherit;">
                <div class="btn-group-vertical" role="group" style="min-height: 450px; width: max-content;">
                    <div class="btn-group-vertical" role="group" aria-label="First Group" style="width: 160px; margin-left: 10px; margin-right: 10px;">
                        <input type="radio" class="btn-check" name="vertical-radio-btn" id="vertical-radio-btn1" autocomplete="off" @click="botonCheck('Departamentos')" checked="">
                        <label class="btn btn-outline-secondary m-auto" for="vertical-radio-btn1">
                            Departamentos
                        </label>
                        <input type="radio" class="btn-check" name="vertical-radio-btn" id="vertical-radio-btn9" autocomplete="off" @click="botonCheck('Areas')">
                        <label class="btn btn-outline-secondary m-auto" for="vertical-radio-btn9">
                            Áreas
                        </label>
                        <input type="radio" class="btn-check" name="vertical-radio-btn" id="vertical-radio-btn7" autocomplete="off" @click="botonCheck('Empresas')">
                        <label class="btn btn-outline-secondary m-auto" for="vertical-radio-btn7">
                            Empresas
                        </label>
                        <input type="radio" class="btn-check" name="vertical-radio-btn" id="vertical-radio-btn2" autocomplete="off" @click="botonCheck('Marcas')">
                        <label class="btn btn-outline-secondary m-auto" for="vertical-radio-btn2">
                            Marcas
                        </label>
                        <input type="radio" class="btn-check" name="vertical-radio-btn" id="vertical-radio-btn3" autocomplete="off" @click="botonCheck('EmpresaMarca')">
                        <label class="btn btn-outline-secondary m-auto" for="vertical-radio-btn3">
                            Relación Empresa-Marca
                        </label>
                        <input type="radio" class="btn-check" name="vertical-radio-btn" id="vertical-radio-btn4" autocomplete="off" @click="botonCheck('Autorizadores')">
                        <label class="btn btn-outline-secondary m-auto" for="vertical-radio-btn4">
                            Autorizadores
                        </label>
                        <input type="radio" class="btn-check" name="vertical-radio-btn" id="vertical-radio-btn5" autocomplete="off" @click="botonCheck('EmpresasKam')">
                        <label class="btn btn-outline-secondary m-auto" for="vertical-radio-btn5">
                            Empresas KAM
                        </label>
                        <input type="radio" class="btn-check" name="vertical-radio-btn" id="vertical-radio-btn6" autocomplete="off" @click="botonCheck('Proveedores')">
                        <label class="btn btn-outline-secondary m-auto" for="vertical-radio-btn6">
                            Proveedores
                        </label>
                    </div>
                </div>
                <!-- ################################################################################################################################# -->
                <div style="width: 100%;">
                    <slot v-if="isVisibleDepartamentos">
                        <div class="row pb-0 p-4">
                            <div class="col-12">
                                <h6>Departamentos</h6>
                                <div class="d-flex justify-content-end">
                                    <a class="btn btn-continue" @click="showCreateDepartamento = true">Nuevo</a>
                                    &nbsp;
                                    <a class="btn btn-search-blue" @click="recargarTabla('Departamento')"><i class="fa-solid fa-rotate"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2" style="min-height: 400px;">
                            <div v-show="mostrarCarga1" class="loader-xs"></div>
                            <div class="container">
                                <div class="table-responsive p-0">
                                    <table class="table align-items-center mb-0" id="MyTableDepartamentos">
                                        <thead>
                                            <tr style="color: black">
                                                <th style="min-width: 16px;"></th>
                                                <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Departamento</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="departamento in departamentos" :key="departamento.id">
                                                <td></td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color:black">{{departamento.id}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color:black">{{departamento.departamento}}</p>
                                                </td>
                                                <td class="align-middle text-end">
                                                    <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                        <a class="btn btn-alert btn-xs" title="Editar" @click="editDepto(departamento)"><i class="fa fa-fw fa-edit"></i></a>
                                                        &nbsp;
                                                        <a class="btn btn-closed btn-xs" title="Eliminar" @click="deleteDepartamento(departamento.id,departamento.departamento)"><i class="fa fa-fw fa-trash"></i></a>
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
                <div style="width: 100%;">
                    <slot v-if="isVisibleAreas">
                        <div class="row pb-0 p-4">
                            <div class="col-12">
                                <h6>Áreas</h6>
                                <div class="d-flex justify-content-end">
                                    <a class="btn btn-continue" @click="showCreateArea = true">Nuevo</a>
                                    &nbsp;
                                    <a class="btn btn-search-blue" @click="recargarTabla('Area')"><i class="fa-solid fa-rotate"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2" style="min-height: 400px;">
                            <div v-show="mostrarCarga2" class="loader-xs"></div>
                            <div class="container">
                                <div class="table-responsive p-0">
                                    <table class="table align-items-center mb-0" id="MyTableAreas">
                                        <thead>
                                            <tr style="color: black">
                                                <th style="min-width: 16px;"></th>
                                                <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Area</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Departamento</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="area in areas" :key="area.id">
                                                <td></td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color:black">{{area.id}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color:black">{{area.area}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color:black">{{area.departamento.departamento}}</p>
                                                </td>
                                                <td class="align-middle text-end">
                                                    <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                        <a class="btn btn-alert btn-xs" title="Editar" @click="editArea(area)"><i class="fa fa-fw fa-edit"></i></a>
                                                        &nbsp;
                                                        <a class="btn btn-closed btn-xs" title="Eliminar" @click="deleteArea(area.id,area.area)"><i class="fa fa-fw fa-trash"></i></a>
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
                <!-- ################################################################################################################################# -->
                <div style="width: 100%;">
                    <slot v-if="isVisibleEmpresas">
                        <div class="row pb-0 p-4">
                            <div class="col-12">
                                <h6>Empresas</h6>
                                <div class="d-flex justify-content-end">
                                    <a class="btn btn-continue" @click="showCreateEmpresa = true">Nuevo</a>
                                    &nbsp;
                                    <a class="btn btn-search-blue" @click="recargarTabla('Empresa')"><i class="fa-solid fa-rotate"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2" style="min-height: 400px;">
                            <div v-show="mostrarCarga3" class="loader-xs"></div>
                            <div class="container">
                                <div class="table-responsive p-0">
                                    <table class="table align-items-center mb-0" id="MyTableEmpresas">
                                        <thead>
                                            <tr style="color: black">
                                                <th style="min-width: 16px;"></th>
                                                <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Razón Social</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Rut</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Dirección</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Teléfono</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="empresa in empresas" :key="empresa.id">
                                                <td></td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color:black">{{empresa.id}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color:black">{{empresa.razonSocial}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color:black">{{empresa.rut}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color:black">{{empresa.direccion}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color:black">{{empresa.telefono}}</p>
                                                </td>
                                                <td class="align-middle text-end">
                                                    <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                        <a class="btn btn-alert btn-xs" title="Editar" @click="editEmpresa(empresa)"><i class="fa fa-fw fa-edit"></i></a>
                                                        &nbsp;
                                                        <a class="btn btn-closed btn-xs" title="Eliminar" @click="deleteEmpresa(empresa.id,empresa.empresa)"><i class="fa fa-fw fa-trash"></i></a>
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
                <!-- ################################################################################################################################# -->
                <div style="width: 100%;">
                    <slot v-if="isVisibleMarcas">
                        <div class="row pb-0 p-4">
                            <div class="col-12">
                                <h6>Marcas</h6>
                                <div class="d-flex justify-content-end">
                                    <a class="btn btn-continue" @click="showCreateMarca = true">Nuevo</a>
                                    &nbsp;
                                    <a class="btn btn-search-blue" @click="recargarTabla('Marca')"><i class="fa-solid fa-rotate"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2" style="min-height: 400px;">
                            <div v-show="mostrarCarga4" class="loader-xs"></div>
                            <div class="container">
                                <div class="table-responsive p-0">
                                    <table class="table align-items-center mb-0" id="MyTableMarcas">
                                        <thead>
                                            <tr style="color: black">
                                                <th style="min-width: 16px;"></th>
                                                <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Marca</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Estado</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="marca in marcas" :key="marca.id">
                                                <td></td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color:black">{{marca.id}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color:black">{{marca.nombre}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color:black">{{marca.estado}}</p>
                                                </td>
                                                <td class="align-middle text-end">
                                                    <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                        <a class="btn btn-alert btn-xs" title="Editar" @click="editMarca(marca)"><i class="fa fa-fw fa-edit"></i></a>
                                                        &nbsp;
                                                        <a class="btn btn-closed btn-xs" title="Eliminar" @click="deleteMarca(marca.id,marca.marca)"><i class="fa fa-fw fa-trash"></i></a>
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
                <!-- ################################################################################################################################# -->
                <div style="width: 100%;">
                    <slot v-if="isVisibleEmpresasMarcas">
                        <div class="row pb-0 p-4">
                            <div class="col-12">
                                <h6>Relación Empresas-Marcas</h6>
                                <div class="d-flex justify-content-end">
                                    <a class="btn btn-continue" @click="showCreateEmpresaMarca = true">Nuevo</a>
                                    &nbsp;
                                    <a class="btn btn-search-blue" @click="recargarTabla('EmpresaMarca')"><i class="fa-solid fa-rotate"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2" style="min-height: 400px;">
                            <div v-show="mostrarCarga7" class="loader-xs"></div>
                            <div class="container">
                                <div class="table-responsive p-0">
                                    <table class="table align-items-center mb-0" id="MyTableEmpresasMarcas">
                                        <thead>
                                            <tr style="color: black">
                                                <th style="min-width: 16px;"></th>
                                                <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Nombre</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Empresa</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Marca</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="empresaMarca in empresasMarcas" :key="empresaMarca.id">
                                                <td></td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color:black">{{empresaMarca.id}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color:black">{{empresaMarca.empresa.razonSocial}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color:black">{{empresaMarca.nombre}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color:black">{{empresaMarca.marca.nombre}}</p>
                                                </td>
                                                <td class="align-middle text-end">
                                                    <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                        <a class="btn btn-alert btn-xs" @click="editEmpresaMarca(empresaMarca)"><i class="fa fa-fw fa-edit"></i></a>
                                                        &nbsp;
                                                        <a class="btn btn-closed btn-xs" @click="deleteEmpresaMarca(empresaMarca.id,empresaMarca.nombre)"><i class="fa fa-fw fa-trash"></i></a>
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
                <!-- ################################################################################################################################# -->
                <div style="width: 100%;">
                    <slot v-if="isVisibleAutorizadores">
                        <div class="row pb-0 p-4">
                            <div class="col-12">
                                <h6>Autorizadores</h6>
                                <div class="d-flex justify-content-end">
                                    <a class="btn btn-continue" @click="showCreateAutorizador = true">Nuevo</a>
                                    &nbsp;
                                    <a class="btn btn-search-blue" @click="recargarTabla('Autorizador')"><i class="fa-solid fa-rotate"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2" style="min-height: 400px;">
                            <div v-show="mostrarCarga5" class="loader-xs"></div>
                            <div class="container">
                                <div class="table-responsive p-0">
                                    <table class="table align-items-center mb-0" id="MyTableAutorizadores">
                                        <thead>
                                            <tr style="color: black">
                                                <th style="min-width: 16px;"></th>
                                                <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Identificador</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Autorizador</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Sistema</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">¿Alto Nivel?</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="autorizador in autorizadores" :key="autorizador.id">
                                                <td></td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color:black">{{autorizador.id}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color:black">{{autorizador.identificador}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color:black">{{autorizador.usuario.name}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color:black">{{autorizador.levelName}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color:black">{{autorizador.level}}</p>
                                                </td>
                                                <td class="align-middle text-end">
                                                    <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                        <a class="btn btn-alert btn-xs" title="Editar" @click="editAutorizador(autorizador)"><i class="fa fa-fw fa-edit"></i></a>
                                                        &nbsp;
                                                        <a class="btn btn-closed btn-xs" title="Eliminar" @click="deleteAutorizador(autorizador.id,autorizador.autorizador)"><i class="fa fa-fw fa-trash"></i></a>
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
                <!-- ################################################################################################################################# -->
                <div style="width: 100%;">
                    <slot v-if="isVisibleEmpresasKam">
                        <div class="row pb-0 p-4">
                            <div class="col-12">
                                <h6>Empresas Kam</h6>
                                <div class="d-flex justify-content-end">
                                    <a class="btn btn-continue" @click="showCreateEmpresaKam = true">Nuevo</a>
                                    &nbsp;
                                    <a class="btn btn-search-blue" @click="recargarTabla('EmpresasKam')"><i class="fa-solid fa-rotate"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2" style="min-height: 400px;">
                            <div v-show="mostrarCarga6" class="loader-xs"></div>
                            <div class="container">
                                <div class="table-responsive p-0">
                                    <table class="table align-items-center mb-0" id="MyTableEmpresasKam">
                                        <thead>
                                            <tr style="color: black">
                                                <th style="min-width: 16px;"></th>
                                                <th class="text-uppercase text-xs font-weight-bolder">ID </th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Usuario</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Empresa</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="empresaKam in empresasKam" :key="empresaKam.id">
                                                <td></td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color:black">{{empresaKam.id}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color:black">{{empresaKam.usuario.name}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color:black">{{empresaKam.empresa_kam.razonSocial}}</p>
                                                </td>
                                                <td class="align-middle text-end">
                                                    <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                        <a class="btn btn-alert btn-xs" @click="editEmpresaKam(empresaKam)"><i class="fa fa-fw fa-edit"></i></a>
                                                        &nbsp;
                                                        <a class="btn btn-closed btn-xs" @click="deleteEmpresaKam(empresaKam.id)"><i class="fa fa-fw fa-trash"></i></a>
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
                <!-- ################################################################################################################################# -->
                <div style="width: 100%;">
                    <slot v-if="isVisibleProveedores">
                        <div class="row pb-0 p-4">
                            <div class="col-12">
                                <h6>Proveedores</h6>
                                <div class="d-flex justify-content-end">
                                    <a class="btn btn-continue" @click="showCreateProveedor = true">Nuevo</a>
                                    &nbsp;
                                    <a class="btn btn-search-blue" @click="recargarTabla('Proveedores')"><i class="fa-solid fa-rotate"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2" style="min-height: 400px;">
                            <div v-show="mostrarCarga8" class="loader-xs"></div>
                            <div class="container">
                                <div class="table-responsive p-0">
                                    <table class="table align-items-center mb-0" id="MyTableProveedores">
                                        <thead>
                                            <tr style="color: black">
                                                <th style="min-width: 16px;"></th>
                                                <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Rut</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Razón Social</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Socio de Negocios</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Autorizador 1</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Autorizador 2</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="proveedor in proveedores" :key="proveedor.id">
                                                <td></td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color:black">{{proveedor.id}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color:black">{{formatoRut(proveedor.rut)}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color:black">{{proveedor.razonSocial}}</p>
                                                </td>
                                                <td>
                                                    <p v-if="proveedor.codSocioNegocio != null" class="text-sm font-weight-bolder mb-0"  style="color:black">{{proveedor.codSocioNegocio}}</p>
                                                    <p v-else class="text-sm font-weight-bolder mb-0" style="color:black">---</p>
                                                </td>
                                                <td>
                                                    <p v-if="proveedor.idAuthAso1 != null" class="text-sm font-weight-bolder mb-0"  style="color:black">{{proveedor.autorizador1.usuario.name}}</p>
                                                    <p v-else class="text-sm font-weight-bolder mb-0" style="color:black">---</p>
                                                </td>
                                                <td>
                                                    <p v-if="proveedor.idAuthAso2 != null" class="text-sm font-weight-bolder mb-0" style="color:black">{{proveedor.autorizador2.usuario.name}}</p>
                                                    <p v-else class="text-sm font-weight-bolder mb-0" style="color:black">---</p>
                                                </td>
                                                <td class="align-middle text-end">
                                                    <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                        <a class="btn btn-alert btn-xs" title="Editar" @click="editProveedor(proveedor)"><i class="fa fa-fw fa-edit"></i></a>
                                                        &nbsp;
                                                        <a class="btn btn-closed btn-xs" title="Eliminar" @click="deleteProveedor(proveedor.id,proveedor)"><i class="fa fa-fw fa-trash"></i></a>
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
            </div>
            <modalconfirmacion ref="confirmation"></modalconfirmacion>
            <modalalerta ref="alert"></modalalerta>
            <modalEditDepto v-bind:departamento1="deptoEdit" v-if="showEditDepartamento" @close="showEditDepartamento = false" @recarga="recargarTabla('Departamento')"></modalEditDepto>
            <modalEditArea v-bind:area1="areaEdit" v-if="showEditArea" @close="showEditArea = false" @recarga="recargarTabla('Area')"></modalEditArea>
            <modalEditEmpresa v-bind:empresa1="empresaEdit" v-if="showEditEmpresa" @close="showEditEmpresa = false" @recarga="recargarTabla('Empresa')"></modalEditEmpresa>
            <modalEditEmpresaKam v-bind:kam1="empresasKamEdit" v-if="showEditEmpresasKam" @close="showEditEmpresasKam = false" @recarga="recargarTabla('EmpresasKam')"></modalEditEmpresaKam>
            <modalEditEmpresaMarca v-bind:empresaMarca1="empresaMarcaEdit" v-if="showEditEmpresaMarca" @close="showEditEmpresaMarca = false" @recarga="recargarTabla('EmpresaMarca')"></modalEditEmpresaMarca>   
            <modalEditMarca v-bind:marca1="marcaEdit" v-if="showEditMarca" @close="showEditMarca = false" @recarga="recargarTabla('Marca')"></modalEditMarca>
            <modalEditAutorizador v-bind:autorizador1="autorizadorEdit" v-if="showEditAutorizador" @close="showEditAutorizador = false" @recarga="recargarTabla('Autorizador')"></modalEditAutorizador>
            <modalCreateDepartamento v-if="showCreateDepartamento" @close="showCreateDepartamento = false" @recarga="recargarTabla('Departamento')"></modalCreateDepartamento>
            <modalCreateArea v-if="showCreateArea" @close="showCreateArea = false" @recarga="recargarTabla('Area')"></modalCreateArea>
            <modalCreateEmpresa v-if="showCreateEmpresa" @close="showCreateEmpresa = false" @recarga="recargarTabla('Empresa')"></modalCreateEmpresa>
            <modalCreateEmpresaKam v-if="showCreateEmpresaKam" @close="showCreateEmpresaKam = false" @recarga="recargarTabla('EmpresasKam')"></modalCreateEmpresaKam>
            <modalCreateEmpresaMarca v-if="showCreateEmpresaMarca" @close="showCreateEmpresaMarca = false" @recarga="recargarTabla('EmpresaMarca')"></modalCreateEmpresaMarca>
            <modalCreateMarca v-if="showCreateMarca" @close="showCreateMarca = false" @recarga="recargarTabla('Marca')"></modalCreateMarca>
            <modalCreateAutorizador v-if="showCreateAutorizador" @close="showCreateAutorizador = false" @recarga="recargarTabla('Autorizador')"></modalCreateAutorizador>
            <modalCreateProveedor v-if="showCreateProveedor" @close="showCreateProveedor = false" @recarga="recargarTabla('Proveedores')"></modalCreateProveedor>
            <modalEditProveedor v-bind:proveedor1="proveedorEdit" v-if="showEditProveedor" @close="showEditProveedor = false" @recarga="recargarTabla('Proveedores')"></modalEditProveedor>
        </div>
    </div>
</div>
</template>

<script>
import axios from 'axios'
import {mixin} from '../../../mixins.js'
import modalconfirmacion from '../../snippets/sistema/alerts/confirmationModal.vue'
import modalalerta from '../../snippets/sistema/alerts/alertModal.vue'
import modalEditDepto from '../../snippets/sistema/adminGeneral/departamentos/editDeptoModal.vue'
import modalEditArea from '../../snippets/sistema/adminGeneral/areas/editAreaModal.vue'
import modalEditEmpresaKam from '../../snippets/sistema/adminGeneral/empresasKam/editEmpresasKamModal.vue'
import modalEditEmpresa from '../../snippets/sistema/adminGeneral/empresas/editEmpresasModal.vue'
import modalEditEmpresaMarca from '../../snippets/sistema/adminGeneral/empresasMarcas/editEmpresasMarcasModal.vue'
import modalEditMarca from '../../snippets/sistema/adminGeneral/marcas/editMarcasModal.vue'
import modalEditAutorizador from '../../snippets/sistema/adminGeneral/autorizadores/editAutorizadoresModal.vue'
import modalCreateDepartamento from '../../snippets/sistema/adminGeneral/departamentos/createDepartamentoModal.vue'
import modalCreateArea from '../../snippets/sistema/adminGeneral/areas/createAreaModal.vue'
import modalCreateEmpresaKam from '../../snippets/sistema/adminGeneral/empresasKam/createEmpresasKamModal.vue'
import modalCreateEmpresa from '../../snippets/sistema/adminGeneral/empresas/createEmpresasModal.vue'
import modalCreateEmpresaMarca from '../../snippets/sistema/adminGeneral/empresasMarcas/createEmpresaMarcasModal.vue'
import modalCreateMarca from '../../snippets/sistema/adminGeneral/marcas/createMarcasModal.vue'
import modalCreateAutorizador from '../../snippets/sistema/adminGeneral/autorizadores/createAutorizadoresModal.vue'
import modalCreateProveedor from '../../snippets/sistema/adminGeneral/proveedores/createProveedorModal.vue'
import modalEditProveedor from '../../snippets/sistema/adminGeneral/proveedores/editProveedorModal.vue'

export default {
    components: { modalEditProveedor, modalCreateProveedor, modalconfirmacion, modalalerta, modalCreateDepartamento, modalEditDepto, modalCreateArea, modalEditArea, modalCreateAutorizador, modalEditAutorizador, modalEditEmpresaKam, modalCreateEmpresaKam, modalCreateEmpresa, modalEditEmpresa, modalCreateEmpresaMarca, modalEditEmpresaMarca, modalCreateMarca, modalEditMarca },
    mixins: [mixin],
    data: () => ({
        // Proveedores
        isVisibleProveedores: false,
        proveedores: null,
        showEditProveedor: false,
        proveedorEdit: null,
        showCreateProveedor: false,
        // Departamentos
        isVisibleDepartamentos: true,
        departamentos: null,
        showEditDepartamento: false,
        deptoEdit: null,
        showCreateDepartamento: false,
        // Areas
        isVisibleAreas: false,
        areas: null,
        showEditArea: false,
        areaEdit: null,
        showCreateArea: false,
        // Empresas
        isVisibleEmpresas: false,
        empresas: null,
        showEditEmpresa: false,
        empresaEdit: null,
        showCreateEmpresa: false,
        // Marcas
        isVisibleMarcas: false,
        marcas: null,
        showEditMarca: false,
        marcaEdit: null,
        showCreateMarca: false,
        // Empresas-Marcas
        isVisibleEmpresasMarcas: false,
        empresasMarcas: null,
        showEditEmpresaMarca: false,
        empresaMarcaEdit: null,
        showCreateEmpresaMarca: false,
        // Autorizadores
        isVisibleAutorizadores: false,
        autorizadores: null,
        showEditAutorizador: false,
        autorizadorEdit: null,
        showCreateAutorizador: false,
        // EmpresasKam
        isVisibleEmpresasKam: false,
        empresasKam: null,
        showEditEmpresasKam: false,
        empresasKamEdit: null,
        showCreateEmpresaKam: false,
        // -----------------------
        tableDeptos: null,
        mostrarCarga1: true,
        mostrarTabla: false,
        // -----------------------
        tableAreas: null,
        mostrarCarga2: true,
        mostrarTabla2: false,
        // -----------------------
        tableEmpresas: null,
        mostrarCarga3: true,
        mostrarTabla3: false,
        // -----------------------
        tableMarcas: null,
        mostrarCarga4: true,
        mostrarTabla4: false,
        // -----------------------
        tableAutorizadores: null,
        mostrarCarga5: true,
        mostrarTabla5: false,
        // -----------------------
        tableEmpresasKam: null,
        mostrarCarga6: true,
        mostrarTabla6: false,
        // -----------------------
        tableEmpresasMarcas: null,
        mostrarCarga7: true,
        mostrarTabla7: false,
        // -----------------------
        tableProveedores: null,
        mostrarCarga8: true,
        mostrarTabla8: false,
    }),
    created(){
        this.getDepartamentos();
    },
    methods: {
        recargarTabla($tabla){
            if ($tabla == 'Departamento'){
                this.departamentos = null;
                this.mostrarCarga1 = true;
                this.getDepartamentos();
            }
            if ($tabla == 'Area'){
                this.areas = null;
                this.mostrarCarga2 = true;
                this.getAreas();
            }
            if ($tabla == 'Empresa'){
                this.empresas = null;
                this.mostrarCarga3 = true;
                this.getEmpresas();
            }
            if ($tabla == 'Marca'){
                this.marcas = null;
                this.mostrarCarga4 = true;
                this.getMarcas();
            }
            if ($tabla == 'EmpresaMarca'){
                this.empresasMarcas = null;
                this.mostrarCarga7 = true;
                this.getEmpresasMarcas();
            }
            if ($tabla == 'Autorizador'){
                this.autorizadores = null;
                this.mostrarCarga5 = true;
                this.getAutorizadores();
            }
            if ($tabla == 'EmpresasKam'){
                this.empresasKam = null;
                this.mostrarCarga6 = true;
                this.getEmpresasKam();
            }
            if ($tabla == 'Proveedores'){
                this.proveedores = null;
                this.mostrarCarga8 = true;
                this.getProveedores();
            }
        },
        crearTablas($tabla){
            if($tabla == 'Departamento'){
                setTimeout(function(){
                    this.tableDeptos = $('#MyTableDepartamentos').DataTable({info: false, select:false, "bLengthChange" : false});
                    this.tableDeptos.buttons().remove();
                    this.mostrarCarga1 = false;
                    this.mostrarTabla1 = true;   
                }.bind(this),200);
            }
            if($tabla == 'Area'){
                setTimeout(function(){
                    this.tableAreas = $('#MyTableAreas').DataTable({info: false, select:false, "bLengthChange" : false});
                    this.tableAreas.buttons().remove();
                    this.mostrarCarga2 = false;
                    this.mostrarTabla2 = true;   
                }.bind(this),200);
            }
            if($tabla == 'Empresa'){
                setTimeout(function(){
                    this.tableEmpresas = $('#MyTableEmpresas').DataTable({info: false, select:false, "bLengthChange" : false});
                    this.tableEmpresas.buttons().remove();
                    this.mostrarCarga3 = false;
                    this.mostrarTabla3 = true;   
                }.bind(this),200);
            }
            if($tabla == 'Marca'){
                setTimeout(function(){
                    this.tableMarcas = $('#MyTableMarcas').DataTable({info: false, select:false, "bLengthChange" : false});
                    this.tableMarcas.buttons().remove();
                    this.mostrarCarga4 = false;
                    this.mostrarTabla4 = true;   
                }.bind(this),200);
            }
            if($tabla == 'EmpresaMarca'){
                setTimeout(function(){
                    this.tableEmpresasMarcas = $('#MyTableEmpresasMarcas').DataTable({info: false, select:false, "bLengthChange" : false});
                    this.tableEmpresasMarcas.buttons().remove();
                    this.mostrarCarga7 = false;
                    this.mostrarTabla7 = true;
                }.bind(this),200);
            }
            if($tabla == 'Autorizador'){
                setTimeout(function(){
                    this.tableAutorizadores = $('#MyTableAutorizadores').DataTable({info: false, select:false, "bLengthChange" : false});
                    this.tableAutorizadores.buttons().remove();
                    this.mostrarCarga5 = false;
                    this.mostrarTabla5 = true;   
                }.bind(this),200);
            }
            if($tabla == 'EmpresasKam'){
                setTimeout(function(){
                    this.tableEmpresasKam = $('#MyTableEmpresasKam').DataTable({info: false, select:false, "bLengthChange" : false});
                    this.tableEmpresasKam.buttons().remove();
                    this.mostrarCarga6 = false;
                    this.mostrarTabla6 = true;   
                }.bind(this),200);
            }
            if($tabla == 'Proveedores'){
                setTimeout(function(){
                    this.tableProveedores = $('#MyTableProveedores').DataTable({info: false, select:false, "bLengthChange" : false});
                    this.tableProveedores.buttons().remove();
                    this.mostrarCarga8 = false;
                    this.mostrarTabla8 = true;   
                }.bind(this),200);
            }
        },
        // TABLAS y MANIPULACIÓN DE VISTAS:
        botonCheck($entrada){
            this.isVisibleDepartamentos = false;
            this.isVisibleAreas = false;
            this.isVisibleEmpresas = false;
            this.isVisibleMarcas = false;
            this.isVisibleEmpresasMarcas = false;
            this.isVisibleAutorizadores = false;
            this.isVisibleEmpresasKam = false;
            this.isVisibleProveedores = false;
            if ($entrada == 'Departamentos'){
                this.departamentos = null;
                this.mostrarCarga1 = true;
                this.getDepartamentos();
                this.isVisibleDepartamentos = true;
            }
            if ($entrada == 'Areas'){
                this.areas = null;
                this.mostrarCarga2 = true;
                this.getAreas();
                this.isVisibleAreas = true;
            }
            if ($entrada == 'Empresas'){
                this.empresas = null;
                this.mostrarCarga3 = true;
                this.getEmpresas();
                this.isVisibleEmpresas = true;
            }
            if ($entrada == 'Marcas'){
                this.marcas = null;
                this.mostrarCarga4 = true;
                this.getMarcas();
                this.isVisibleMarcas = true;
            }
            if ($entrada == 'EmpresaMarca'){
                this.empresasMarcas = null;
                this.mostrarCarga7 = true;
                this.getEmpresasMarcas();
                this.isVisibleEmpresasMarcas = true;
            }
            if ($entrada == 'Autorizadores'){
                this.autorizadores = null;
                this.mostrarCarga5 = true;
                this.getAutorizadores();
                this.isVisibleAutorizadores = true;
            }
            if ($entrada == 'EmpresasKam'){
                this.empresasKam = null;
                this.mostrarCarga6 = true;
                this.getEmpresasKam();
                this.isVisibleEmpresasKam = true;
            }
            if ($entrada == 'Proveedores'){
                this.proveedores = null;
                this.mostrarCarga8 = true;
                this.getProveedores();
                this.isVisibleProveedores = true;
            }
        },
        // DEPARTAMENTOS:
        getDepartamentos(){
            axios.get('../api/departamentos').then( response =>{
                this.mostrarCarga1 = true;
                this.departamentos = response.data;
                if (this.tableDeptos != null){
                    this.tableDeptos.clear();
                    this.tableDeptos.destroy();
                }
                this.crearTablas('Departamento');
            }).catch(e=> console.log(e))
        },
        async deleteDepartamento(id,departamento){
            const ok = await this.$refs.confirmation.show({
                title: 'Borrar Departamento',
                message: `¿Está seguro/a que desea eliminar el departamento '${departamento}'? Esta acción no puede ser desecha.`,
                okButton: 'Eliminar',
                cancelButton: 'Volver'
            })
            if (ok) {
                axios.delete(`../api/departamentos/${id}`).then( response =>{
                    this.toast.success("Departamento eliminado con éxito!", {
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
                    this.recargarTabla('Departamento');
                }).catch(e=> console.log(e))
            }
        },
        editDepto(departamento){
            this.deptoEdit = departamento;
            this.showEditDepartamento = true;
        },
        // AREAS:
        getAreas(){
            axios.get('../api/areas').then( response =>{
                this.mostrarCarga2 = true;
                this.areas = response.data;
                if (this.tableAreas != null){
                    this.tableAreas.clear();
                    this.tableAreas.destroy();
                }
                this.crearTablas('Area');
            }).catch(e=> console.log(e))
        },
        async deleteArea(id,area){
            const ok = await this.$refs.confirmation.show({
                title: 'Borrar Área',
                message: `¿Está seguro/a que desea eliminar la área '${area}'? Esta acción no puede ser desecha.`,
                okButton: 'Eliminar',
                cancelButton: 'Volver'
            })
            if (ok) {
                axios.delete(`../api/areas/${id}`).then( response =>{
                    this.toast.success("Área eliminada con éxito!", {
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
                    this.recargarTabla('Area');
                }).catch(e=> console.log(e))
            }
        },
        editArea(area){
            this.areaEdit = area;
            this.showEditArea = true;
        },
        // EMPRESAS:
        getEmpresas(){
            axios.get('../api/empresas').then( response =>{
                this.mostrarCarga3 = true;
                this.empresas = response.data;
                if (this.tableEmpresas != null){
                    this.tableEmpresas.clear();
                    this.tableEmpresas.destroy();
                }
                this.crearTablas('Empresa');
            }).catch(e=> console.log(e))
        },
        async deleteEmpresa(id,empresa){
            const ok = await this.$refs.confirmation.show({
                title: 'Borrar Empresa',
                message: `¿Está seguro/a que desea eliminar la empresa '${empresa}'? Esta acción no puede ser desecha.`,
                okButton: 'Eliminar',
                cancelButton: 'Volver'
            })
            if (ok) {
                axios.delete(`../api/empresas/${id}`).then( response =>{
                    this.toast.success("Empresa eliminada con éxito!", {
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
                    this.recargarTabla('Empresa');
                }).catch(e=> console.log(e))
            }
        },
        editEmpresa(empresa){
            this.empresaEdit = empresa;
            this.showEditEmpresa = true;
        },
        // MARCAS:
        getMarcas(){
            axios.get('../api/marcas').then( response =>{
                this.mostrarCarga4 = true;
                this.marcas = response.data;
                if (this.tableMarcas != null){
                    this.tableMarcas.clear();
                    this.tableMarcas.destroy();
                }
                this.crearTablas('Marca');
            }).catch(e=> console.log(e))
        },
        async deleteMarca(id,marca){
            const ok = await this.$refs.confirmation.show({
                title: 'Borrar Marca',
                message: `¿Está seguro/a que desea eliminar la marca '${marca}'? Esta acción no puede ser desecha.`,
                okButton: 'Eliminar',
                cancelButton: 'Volver'
            })
            if (ok) {
                axios.delete(`../api/marcas/${id}`).then( response =>{
                    this.toast.success("Marca eliminada con éxito!", {
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
                    this.recargarTabla('Marca');
                }).catch(e=> console.log(e))
            }
        },
        editMarca(marca){
            this.marcaEdit = marca;
            this.showEditMarca = true;
        },
        // EMPRESAS-MARCAS:
        getEmpresasMarcas(){
            axios.get('../api/empresasMarcas').then( response =>{
                this.mostrarCarga7 = true;
                this.empresasMarcas = response.data;
                if (this.tableEmpresasMarcas != null){
                    this.tableEmpresasMarcas.clear();
                    this.tableEmpresasMarcas.destroy();
                }
                this.crearTablas('EmpresaMarca');
            }).catch(e=> console.log(e))
        },
        async deleteEmpresaMarca(id,empresaMarca){
            const ok = await this.$refs.confirmation.show({
                title: 'Borrar relación Empresa-Marca',
                message: `¿Está seguro/a que desea eliminar la relación '${empresaMarca}'? Esta acción no puede ser desecha.`,
                okButton: 'Eliminar',
                cancelButton: 'Volver'
            })
            if (ok) {
                axios.delete(`../api/empresasMarcas/${id}`).then( response =>{
                    this.toast.success("Relación Empresa-Marca eliminada con éxito!", {
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
                    this.recargarTabla('EmpresaMarca');
                }).catch(e=> console.log(e))
            }
        },
        editEmpresaMarca(empresaMarca){
            this.empresaMarcaEdit = empresaMarca;
            this.showEditEmpresaMarca = true;
        },
        // AUTORIZADORES:
        getAutorizadores(){
            axios.get('../api/autorizadores').then( response =>{
                this.mostrarCarga5 = true;
                this.autorizadores = response.data;
                if (this.tableAutorizadores != null){
                    this.tableAutorizadores.clear();
                    this.tableAutorizadores.destroy();
                }
                this.crearTablas('Autorizador');
            }).catch(e=> console.log(e))
        },
        async deleteAutorizador(id,autorizador){
            const ok = await this.$refs.confirmation.show({
                title: 'Borrar Autorizador',
                message: `¿Está seguro/a que desea eliminar la autorizador '${autorizador}'? Esta acción no puede ser desecha.`,
                okButton: 'Eliminar',
                cancelButton: 'Volver'
            })
            if (ok) {
                axios.delete(`../api/autorizadores/${id}`).then( response =>{
                    this.toast.success("Autorizador eliminada con éxito!", {
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
                    this.recargarTabla('Autorizador');
                }).catch(e=> console.log(e))
            }
        },
        editAutorizador(autorizador){
            this.autorizadorEdit = autorizador;
            this.showEditAutorizador = true;
        },
        // EmpresasKam:
        getEmpresasKam(){
            axios.get('../api/empresasKam').then( response =>{
                this.mostrarCarga6 = true;
                this.empresasKam = response.data;
                if (this.tableEmpresasKam != null){
                    this.tableEmpresasKam.clear();
                    this.tableEmpresasKam.destroy();
                }
                this.crearTablas('EmpresasKam');
            }).catch(e=> console.log(e))
        },
        async deleteEmpresaKam(id){
            const ok = await this.$refs.confirmation.show({
                title: 'Borrar Autorizador',
                message: `¿Está seguro/a que desea eliminar esta Empresa Kam ? Esta acción no puede ser desecha.`,
                okButton: 'Eliminar',
                cancelButton: 'Volver'
            })
            if (ok) {
                axios.delete(`../api/empresasKam/${id}`).then( response =>{
                    this.toast.success("Empresa Kam eliminada con éxito!", {
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
                    this.recargarTabla('EmpresasKam');
                }).catch(e=> console.log(e))
            }
        },
        editEmpresaKam(empresaKam){
            this.empresasKamEdit = empresaKam;
            this.showEditEmpresasKam = true;
        },
        // Proveedores:
        getProveedores(){
            axios.get('../api/proveedores').then( response =>{
                this.mostrarCarga8 = true;
                this.proveedores = response.data;
                if (this.tableProveedores != null){
                    this.tableProveedores.clear();
                    this.tableProveedores.destroy();
                }
                this.crearTablas('Proveedores');
            }).catch(e=> console.log(e))
        },
        async deleteProveedor(id, proveedor){
            const ok = await this.$refs.confirmation.show({
                title: 'Borrar Proveedor',
                message: `¿Está seguro/a que desea eliminar al proveedor de rut '${proveedor.rut}' y razón social '${proveedor.razonSocial}'? Esta acción no puede ser desecha.`,
                okButton: 'Eliminar',
                cancelButton: 'Volver'
            })
            if (ok) {
                axios.delete(`../api/proveedores/${id}`).then( response =>{
                    this.toast.success("Proveedor eliminado con éxito!", {
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
                    this.recargarTabla('Proveedores');
                }).catch(e=> console.log(e))
            }
        },
        editProveedor(proveedor){
            this.proveedorEdit = proveedor;
            this.showEditProveedor = true;
        },
    }
}
</script>