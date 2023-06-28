<template>
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4" style="flex-direction: inherit;">
                <div class="btn-group-vertical" role="group" style="min-height: 450px; width: max-content;">
                    <div class="btn-group-vertical" role="group" aria-label="First Group" style="width: 160px; margin-left: 10px; margin-right: 10px;">
                        <input type="radio" class="btn-check" name="vertical-radio-btn" id="vertical-radio-btn2" autocomplete="off" @click="botonCheck('Sistemas de Salud')" checked="">
                        <label class="btn btn-outline-secondary m-auto" for="vertical-radio-btn2">
                            Sistemas de Salud
                        </label>
                        <input type="radio" class="btn-check" name="vertical-radio-btn" id="vertical-radio-btn3" autocomplete="off" @click="botonCheck('AFPs')">
                        <label class="btn btn-outline-secondary m-auto" for="vertical-radio-btn3">
                            AFPs
                        </label>
                        <input type="radio" class="btn-check" name="vertical-radio-btn" id="vertical-radio-btn4" autocomplete="off" @click="botonCheck('Estados Civiles')">
                        <label class="btn btn-outline-secondary m-auto" for="vertical-radio-btn4">
                            Estados Civiles
                        </label>
                        <input type="radio" class="btn-check" name="vertical-radio-btn" id="vertical-radio-btn7" autocomplete="off" @click="botonCheck('Tipos Usuario')">
                        <label class="btn btn-outline-secondary m-auto" for="vertical-radio-btn7">
                            Tipos de Usuario
                        </label>
                        <input type="radio" class="btn-check" name="vertical-radio-btn" id="vertical-radio-btn1" autocomplete="off" @click="botonCheck('Grupos')">
                        <label class="btn btn-outline-secondary m-auto" for="vertical-radio-btn1">
                            Grupos
                        </label>
                        <input type="radio" class="btn-check" name="vertical-radio-btn" id="vertical-radio-btn5" autocomplete="off" @click="botonCheck('Grupo Lideres')">
                        <label class="btn btn-outline-secondary m-auto" for="vertical-radio-btn5">
                            Líderes de Grupos
                        </label>
                        <input type="radio" class="btn-check" name="vertical-radio-btn" id="vertical-radio-btn6" autocomplete="off" @click="botonCheck('Grupo Miembros')">
                        <label class="btn btn-outline-secondary m-auto" for="vertical-radio-btn6">
                            Miembros de Grupos
                        </label>
                    </div>
                </div>
                <div style="width: 100%;">
                    <!-- ################################################################################################################################# -->
                    <slot v-if="isVisibleSistemasSalud">
                        <div class="row pb-0 p-4">
                            <div class="col-12">
                                <h6>Sistemas de Salud</h6>
                                <div class="d-flex justify-content-end">
                                    <a class="btn btn-continue" @click="showCreateSistemaSalud = true">Nuevo</a>
                                    &nbsp;
                                    <a class="btn btn-search-blue" @click="recargarTabla('Sistemas de Salud')"><i class="fa-solid fa-rotate"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2" style="min-height: 400px">
                            <div v-show="mostrarCarga3" class="loader-xs"></div>
                            <div class="container">
                                <div class="table-responsive p-0">
                                    <table class="table align-items-center mb-0" id="MyTableSistemasSalud">
                                        <thead>
                                            <tr style="color: black">
                                                <th style="min-width: 16px;"></th>
                                                <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Nombre</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Porcentaje</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="sistemaSalud in sistemasSalud" :key="sistemaSalud.id">
                                                <td></td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color:black">{{sistemaSalud.id}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color:black">{{sistemaSalud.nombre}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color:black">{{sistemaSalud.porcentaje}}</p>
                                                </td>
                                                <td class="align-middle text-end">
                                                    <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                        <a class="btn btn-alert btn-xs" title="Editar" @click="editSistema(sistemaSalud)"><i class="fa fa-fw fa-edit"></i></a>
                                                        &nbsp;
                                                        <a class="btn btn-closed btn-xs" title="Eliminar" @click="deleteSistemaSalud(sistemaSalud.id,sistemaSalud.nombre)"><i class="fa fa-fw fa-trash"></i></a>
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
                    <slot v-if="isVisibleAFPs">
                        <div class="row pb-0 p-4">
                            <div class="col-12">
                                <h6>AFPs</h6>
                                <div class="d-flex justify-content-end">
                                    <a class="btn btn-continue" @click="showCreateAFP = true">Nuevo</a>
                                    &nbsp;
                                    <a class="btn btn-search-blue" @click="recargarTabla('AFP')"><i class="fa-solid fa-rotate"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2" style="min-height: 400px;">
                            <div v-show="mostrarCarga4" class="loader-xs"></div>
                            <div class="container">
                                <div class="table-responsive p-0">
                                    <table class="table align-items-center mb-0" id="MyTableAFP">
                                        <thead>
                                            <tr style="color: black">
                                                <th style="min-width: 16px;"></th>
                                                <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Nombre</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="afp in afps" :key="afp.id">
                                                <td></td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color:black">{{afp.id}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color:black">{{afp.nombre}}</p>
                                                </td>
                                                <td class="align-middle text-end">
                                                    <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                        <a class="btn btn-alert btn-xs" title="Editar" @click="editAfp(afp)"><i class="fa fa-fw fa-edit"></i></a>
                                                        &nbsp;
                                                        <a class="btn btn-closed btn-xs" title="Eliminar" @click="deleteAFP(afp.id,afp.nombre)"><i class="fa fa-fw fa-trash"></i></a>
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
                    <slot v-if="isVisibleEstadosCiviles">
                        <div class="row pb-0 p-4">
                            <div class="col-12">
                                <h6>Estados Civiles</h6>
                                <div class="d-flex justify-content-end">
                                    <a class="btn btn-continue" @click="showCreateEstadoCivil = true">Nuevo</a>
                                    &nbsp;
                                    <a class="btn btn-search-blue" @click="recargarTabla('Estados Civiles')"><i class="fa-solid fa-rotate"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2" style="min-height: 400px">
                            <div v-show="mostrarCarga5" class="loader-xs"></div>
                            <div class="container">
                                <div class="table-responsive p-0">
                                    <table class="table align-items-center mb-0" id="MyTableEstadosCiviles">
                                        <thead>
                                            <tr style="color: black">
                                                <th style="min-width: 16px;"></th>
                                                <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Nombre</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="estadoCivil in estadosCiviles" :key="estadoCivil.id">
                                                <td></td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color:black">{{estadoCivil.id}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color:black">{{estadoCivil.nombre}}</p>
                                                </td>
                                                <td class="align-middle text-end">
                                                    <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                        <a class="btn btn-alert btn-xs" title="Editar" @click="editEstado(estadoCivil)"><i class="fa fa-fw fa-edit"></i></a>
                                                        &nbsp;
                                                        <a class="btn btn-closed btn-xs" title="Eliminar" @click="deleteEstadoCivil(estadoCivil.id,estadoCivil.nombre)"><i class="fa fa-fw fa-trash"></i></a>
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
                    <slot v-if="isVisibleTipoUsuarios">
                        <div class="row pb-0 p-4">
                            <div class="col-12">
                                <h6>Tipos de Usuario</h6>
                                <div class="d-flex justify-content-end">
                                    <a class="btn btn-continue" @click="showCreateTipoUsuario = true">Nuevo</a>
                                    &nbsp;
                                    <a class="btn btn-search-blue" @click="recargarTabla('Tipos de Usuario')"><i class="fa-solid fa-rotate"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2" style="min-height: 400px">
                            <div v-show="mostrarCarga7" class="loader-xs"></div>
                            <div class="container">
                                <div class="table-responsive p-0">
                                    <table class="table align-items-center mb-0" id="MyTableTiposUsuario">
                                        <thead>
                                            <tr style="color: black">
                                                <th style="min-width: 16px;"></th>
                                                <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Nombre</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="tipoUsuario in tiposUsuarios" :key="tipoUsuario.id">
                                                <td></td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color:black">{{tipoUsuario.id}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color:black">{{tipoUsuario.nombre}}</p>
                                                </td>
                                                <td class="align-middle text-end">
                                                    <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                        <a class="btn btn-alert btn-xs" title="Editar" @click="editTipoUsuario(tipoUsuario)"><i class="fa fa-fw fa-edit"></i></a>
                                                        &nbsp;
                                                        <a class="btn btn-closed btn-xs" title="Eliminar" @click="deleteTipoUsuario(tipoUsuario.id,tipoUsuario.nombre)"><i class="fa fa-fw fa-trash"></i></a>
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
                    <slot v-if="isVisibleGrupos">
                        <div class="row pb-0 p-4">
                            <div class="col-12">
                                <h6>Grupos</h6>
                                <div class="d-flex justify-content-end">
                                    <a class="btn btn-continue" @click="showCreateGrupo = true">Nuevo</a>
                                    &nbsp;
                                    <a class="btn btn-search-blue" @click="recargarTabla('Grupos')"><i class="fa-solid fa-rotate"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2" style="min-height: 400px">
                            <div v-show="mostrarCarga7" class="loader-xs"></div>
                            <div class="container">
                                <div class="table-responsive p-0">
                                    <table class="table align-items-center mb-0" id="MyTableGrupos">
                                        <thead>
                                            <tr style="color: black">
                                                <th style="min-width: 16px;"></th>
                                                <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Grupo</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="grupo in grupos" :key="grupo.id">
                                                <td></td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color:black">{{grupo.id}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color:black">{{grupo.nombre}}</p>
                                                </td>
                                                <td class="align-middle text-end">
                                                    <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                        <a class="btn btn-alert btn-xs" @click="editGrupo(grupo)"><i class="fa fa-fw fa-edit"></i></a>
                                                        &nbsp;
                                                        <a class="btn btn-closed btn-xs" @click="deleteGrupo(grupo.id,grupo.nombre)"><i class="fa fa-fw fa-trash"></i></a>
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
                    <slot v-if="isVisibleGrupoLideres">
                        <div class="row pb-0 p-4">
                            <div class="col-12">
                                <h6>Líderes de Grupos</h6>
                                <div class="d-flex justify-content-end">
                                    <a class="btn btn-continue" @click="showCreateGrupoLider = true">Nuevo</a>
                                    &nbsp;
                                    <a class="btn btn-search-blue" @click="recargarTabla('Grupo Lideres')"><i class="fa-solid fa-rotate"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2" style="min-height: 400px">
                            <div v-show="mostrarCarga8" class="loader-xs"></div>
                            <div class="container">
                                <div class="table-responsive p-0">
                                    <table class="table align-items-center mb-0" id="MyTableGrupoLideres">
                                        <thead>
                                            <tr style="color: black">
                                                <th style="min-width: 16px;"></th>
                                                <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Lider</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Grupo</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="grupoLider in grupoLideres" :key="grupoLider.id">
                                                <td></td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color:black">{{grupoLider.id}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color:black">{{grupoLider.usuario.name}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color:black">{{grupoLider.grupo.nombre}}</p>
                                                </td>
                                                <td class="align-middle text-end">
                                                    <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                        <a class="btn btn-alert btn-xs" @click="editGrupoLider(grupoLider)"><i class="fa fa-fw fa-edit"></i></a>
                                                        &nbsp;
                                                        <a class="btn btn-closed btn-xs" @click="deleteGrupoLider(grupoLider.id,grupoLider.usuario.name,grupoLider.grupo.nombre)"><i class="fa fa-fw fa-trash"></i></a>
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
                    <slot v-if="isVisibleGrupoMiembros">
                        <div class="row pb-0 p-4">
                            <div class="col-12">
                                <h6>Miembros de Grupos</h6>
                                <div class="d-flex justify-content-end">
                                    <a class="btn btn-continue" @click="showCreateGrupoMiembro = true">Nuevo</a>
                                    &nbsp;
                                    <a class="btn btn-search-blue" @click="recargarTabla('Grupo Miembros')"><i class="fa-solid fa-rotate"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body px-0 pt-0 pb-2" style="min-height: 400px">
                            <div v-show="mostrarCarga9" class="loader-xs"></div>
                            <div class="container">
                                <div class="table-responsive p-0">
                                    <table class="table align-items-center mb-0" id="MyTableGrupoMiembros">
                                        <thead>
                                            <tr style="color: black">
                                                <th style="min-width: 16px;"></th>
                                                <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Miembro</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Grupo</th>
                                                <th class="text-uppercase text-xs font-weight-bolder">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="grupoMiembro in grupoMiembros" :key="grupoMiembro.id">
                                                <td></td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color:black">{{grupoMiembro.id}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color:black">{{grupoMiembro.usuario.name}}</p>
                                                </td>
                                                <td>
                                                    <p class="text-sm font-weight-bolder mb-0" style="color:black">{{grupoMiembro.grupo.nombre}}</p>
                                                </td>
                                                <td class="align-middle text-end">
                                                    <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                                        <a class="btn btn-alert btn-xs" @click="editGrupoMiembro(grupoMiembro)"><i class="fa fa-fw fa-edit"></i></a>
                                                        &nbsp;
                                                        <a class="btn btn-closed btn-xs" @click="deleteGrupoMiembro(grupoMiembro.id,grupoMiembro.usuario.name,grupoMiembro.grupo.nombre)"><i class="fa fa-fw fa-trash"></i></a>
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
            <modalEditSistema v-bind:sistema1="sistemaEdit" v-if="showEditSistemaSalud" @close="showEditSistemaSalud = false" @recarga="recargarTabla('Sistemas de Salud')"></modalEditSistema>
            <modalEditAFP v-bind:afp1="afpEdit" v-if="showEditAFP" @close="showEditAFP = false" @recarga="recargarTabla('Afp')"></modalEditAFP>
            <modalEditEstado v-bind:estado1="estadoEdit" v-if="showEditEstadoCivil" @close="showEditEstadoCivil = false" @recarga="recargarTabla('Estados Civiles')"></modalEditEstado>
            <modalCreateSistemaSalud v-if="showCreateSistemaSalud" @close="showCreateSistemaSalud = false" @recarga="recargarTabla('Sistemas de Salud')"></modalCreateSistemaSalud>
            <modalCreateAFP v-if="showCreateAFP" @close="showCreateAFP = false" @recarga="recargarTabla('Afp')"></modalCreateAFP>
            <modalCreateEstadoCivil v-if="showCreateEstadoCivil" @close="showCreateEstadoCivil = false" @recarga="recargarTabla('Estados Civiles')"></modalCreateEstadoCivil>
            <modalCreateGrupo v-if="showCreateGrupo" @close="showCreateGrupo = false" @recarga="recargarTabla('Grupos')"></modalCreateGrupo>
            <modalEditGrupo v-bind:grupo="grupoEdit" v-if="showEditGrupo" @close="showEditGrupo = false" @recarga="recargarTabla('Grupos')"></modalEditGrupo>
            <modalCreateGrupoLider v-if="showCreateGrupoLider" @close="showCreateGrupoLider = false" @recarga="recargarTabla('Grupo Lideres')"></modalCreateGrupoLider>
            <modalEditGrupoLider v-bind:grupoLider="grupoLiderEdit" v-if="showEditGrupoLider" @close="showEditGrupoLider = false" @recarga="recargarTabla('Grupo Lideres')"></modalEditGrupoLider>
            <modalCreateGrupoMiembro v-if="showCreateGrupoMiembro" @close="showCreateGrupoMiembro = false" @recarga="recargarTabla('Grupo Miembros')"></modalCreateGrupoMiembro>
            <modalEditGrupoMiembro v-bind:grupoMiembro="grupoMiembroEdit" v-if="showEditGrupoMiembro" @close="showEditGrupoMiembro = false" @recarga="recargarTabla('Grupo Miembros')"></modalEditGrupoMiembro>
            <!--  -->
            <modalCreateTipoUsuario v-if="showCreateTipoUsuario" @close="showCreateTipoUsuario = false" @recarga="recargarTabla('Tipos de usuario')"></modalCreateTipoUsuario>
            <modalEditTipoUsuario v-bind:tipoUsuario="tipoUsuarioEdit" v-if="showEditTipoUsuario" @close="showEditTipoUsuario = false" @recarga="recargarTabla('Tipos de usuario')"></modalEditTipoUsuario>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import {mixin} from '../../../mixins.js'
import modalconfirmacion from '../../snippets/sistema/alerts/confirmationModal.vue'
import modalalerta from '../../snippets/sistema/alerts/alertModal.vue'
import modalCreateSistemaSalud from '../../snippets/sistema/adminGeneral/sistemasSalud/createSistemaSaludModal.vue'
import modalEditSistema from '../../snippets/sistema/adminGeneral/sistemasSalud/editSistemaModal.vue'
import modalCreateEstadoCivil from '../../snippets/sistema/adminGeneral/estadosCiviles/createEstadoCivilModal.vue'
import modalEditEstado from '../../snippets/sistema/adminGeneral/estadosCiviles/editEstadoModal.vue'
import modalCreateAFP from '../../snippets/sistema/adminGeneral/AFP/createAFPModal.vue'
import modalEditAFP from '../../snippets/sistema/adminGeneral/AFP/editAfpModal.vue'
import modalCreateGrupo from '../../snippets/sistema/adminGeneral/grupos/createGrupoModal.vue'
import modalEditGrupo from '../../snippets/sistema/adminGeneral/grupos/editGrupoModal.vue'
import modalCreateGrupoLider from '../../snippets/sistema/adminGeneral/grupoLideres/createGrupoLiderModal.vue'
import modalEditGrupoLider from '../../snippets/sistema/adminGeneral/grupoLideres/editGrupoLiderModal.vue'
import modalCreateGrupoMiembro from '../../snippets/sistema/adminGeneral/grupoMiembros/createGrupoMiembroModal.vue'
import modalEditGrupoMiembro from '../../snippets/sistema/adminGeneral/grupoMiembros/editGrupoMiembroModal.vue'
import modalCreateTipoUsuario from '../../snippets/sistema/adminGeneral/tiposUsuario/createTipoUsuarioModal.vue'
import modalEditTipoUsuario from '../../snippets/sistema/adminGeneral/tiposUsuario/editTipoUsuarioModal.vue'

export default {
    components: { modalconfirmacion, modalalerta, modalCreateSistemaSalud, modalCreateAFP, modalCreateEstadoCivil, modalEditSistema, modalEditAFP, modalEditEstado, modalCreateGrupo, modalEditGrupo, modalCreateGrupoLider, modalEditGrupoLider, modalCreateGrupoMiembro, modalEditGrupoMiembro, modalCreateTipoUsuario, modalEditTipoUsuario },
    mixins: [mixin],
    data: () => ({
        // Sistemas de Salud
        isVisibleSistemasSalud: true,
        sistemasSalud: null,
        showEditSistemaSalud: false,
        sistemaEdit: null,
        showCreateSistemaSalud: false,
        // AFPs
        isVisibleAFPs: false,
        showCreateAFP: false,
        afps: null,
        showEditAFP: false,
        afpEdit: null,
        // Estados Civiles
        isVisibleEstadosCiviles: false,
        showEditEstadoCivil: false,
        estadoEdit: null,
        estadosCiviles: null,
        showCreateEstadoCivil: false,
        // Tipos de Usuario
        isVisibleTipoUsuarios: false,
        showEditTipoUsuario: false,
        tipoUsuarioEdit: null,
        tiposUsuarios: null,
        showCreateTipoUsuario: false,
        // Grupos
        isVisibleGrupos: false,
        showEditGrupo: false,
        grupoEdit: null,
        grupos: null,
        showCreateGrupo: false,
        // Grupo Lideres
        isVisibleGrupoLideres: false,
        showEditGrupoLider: false,
        grupoLiderEdit: null,
        grupoLideres: null,
        showCreateGrupoLider: false,
        // Grupo Miembros
        isVisibleGrupoMiembros: false,
        showEditGrupoMiembro: false,
        grupoMiembroEdit: null,
        grupoMiembros: null,
        showCreateGrupoMiembro: false,
        // -----------------------
        tableSisSalud: null,
        mostrarCarga3: true,
        mostrarTabla3: false,
        // -----------------------
        tableAfp: null,
        mostrarCarga4: true,
        mostrarTabla4: false,
        // -----------------------
        tableEstadoCivil: null,
        mostrarCarga5: true,
        mostrarTabla5: false,
        // -----------------------
        tableTipoUsuario: null,
        mostrarCarga6: true,
        mostrarTabla6: false,
        // -----------------------
        tableGrupos: null,
        mostrarCarga7: true,
        mostrarTabla7: false,
        // -----------------------
        tableGrupoLideres: null,
        mostrarCarga8: true,
        mostrarTabla8: false,
        // -----------------------
        tableGrupoMiembros: null,
        mostrarCarga9: true,
        mostrarTabla9: false,
    }),
    created(){
        this.getSistemasSalud();
    },
    methods: {
        recargarTabla($tabla){
            if ($tabla == 'Sistemas de Salud'){
                this.sistemasSalud = null;
                this.mostrarCarga3 = true;
                this.getSistemasSalud();
            }
            if ($tabla == 'AFP'){
                this.afps = null;
                this.mostrarCarga4 = true;
                this.getAFPs();
            }
            if ($tabla == 'Estados Civiles'){
                this.estadosCiviles = null;
                this.mostrarCarga5 = true;
                this.getEstadosCiviles();
            }
            if ($tabla == 'Tipos de Usuario'){
                this.tiposUsuarios = null;
                this.mostrarCarga7 = true;
                this.getTiposUsuario();
            }
            if ($tabla == 'Grupos'){
                this.grupos = null;
                this.mostrarCarga7 = true;
                this.getGrupos();
            }
            if ($tabla == 'Grupo Lideres'){
                this.grupoLideres = null;
                this.mostrarCarga8 = true;
                this.getGrupoLideres();
            }
            if ($tabla == 'Grupo Miembros'){
                this.grupoMiembros = null;
                this.mostrarCarga9 = true;
                this.getGrupoMiembros();
            }
        },
        crearTablas($tabla){
            if($tabla == 'Sistemas de Salud'){
                setTimeout(function(){
                    this.tableSisSalud = $('#MyTableSistemasSalud').DataTable({info: false, select:false, "bLengthChange" : false});
                    this.tableSisSalud.buttons().remove();
                    this.mostrarCarga3 = false;
                    this.mostrarTabla3 = true;   
                }.bind(this),200);
            }
            if($tabla == 'AFP'){
                setTimeout(function(){
                    this.tableAfp = $('#MyTableAFP').DataTable({info: false, select:false, "bLengthChange" : false});
                    this.tableAfp.buttons().remove();
                    this.mostrarCarga4 = false;
                    this.mostrarTabla4 = true;   
                }.bind(this),200);
            }
            if($tabla == 'Estados Civiles'){
                setTimeout(function(){
                    this.tableEstadoCivil = $('#MyTableEstadosCiviles').DataTable({info: false, select:false, "bLengthChange" : false});
                    this.tableEstadoCivil.buttons().remove();
                    this.mostrarCarga5 = false;
                    this.mostrarTabla5 = true;   
                }.bind(this),200);
            }
            if($tabla == 'Tipos de Usuario'){
                setTimeout(function(){
                    this.tableTipoUsuario = $('#MyTableTiposUsuario').DataTable({info: false, select:false, "bLengthChange" : false});
                    this.tableTipoUsuario.buttons().remove();
                    this.mostrarCarga7 = false;
                    this.mostrarTabla6 = true;   
                }.bind(this),200);
            }
            if ($tabla == 'Grupos'){
                setTimeout(function(){
                    this.tableGrupos = $('#MyTableGrupos').DataTable({info: false, select:false, "bLengthChange" : false});
                    this.tableGrupos.buttons().remove();
                    this.mostrarCarga7 = false;
                    this.mostrarTabla7 = true;   
                }.bind(this),200);
            }
            if ($tabla == 'Grupo Lideres'){
                setTimeout(function(){
                    this.tableGrupoLideres = $('#MyTableGrupoLideres').DataTable({info: false, select:false, "bLengthChange" : false});
                    this.tableGrupoLideres.buttons().remove();
                    this.mostrarCarga8 = false;
                    this.mostrarTabla8 = true;   
                }.bind(this),200);
            }
            if ($tabla == 'Grupo Miembros'){
                setTimeout(function(){
                    this.tableGrupoMiembros = $('#MyTableGrupoMiembros').DataTable({info: false, select:false, "bLengthChange" : false});
                    this.tableGrupoMiembros.buttons().remove();
                    this.mostrarCarga9 = false;
                    this.mostrarTabla9 = true;   
                }.bind(this),200);
            }
        },
        // TABLAS y MANIPULACIÓN DE VISTAS:
        botonCheck($entrada){
            this.isVisibleSistemasSalud = false;
            this.isVisibleAFPs = false;
            this.isVisibleEstadosCiviles = false;
            this.isVisibleTipoUsuarios = false;
            this.isVisibleGrupos = false;
            this.isVisibleGrupoLideres = false;
            this.isVisibleGrupoMiembros = false;
            if ($entrada == 'Sistemas de Salud'){
                this.sistemasSalud = null;
                this.mostrarCarga3 = true;
                this.getSistemasSalud();
                this.isVisibleSistemasSalud = true;
            }
            if ($entrada == 'AFPs'){
                this.afps = null;
                this.mostrarCarga4 = true;
                this.getAFPs();
                this.isVisibleAFPs = true;
            }
            if ($entrada == 'Estados Civiles'){
                this.estadosCiviles = null;
                this.mostrarCarga5 = true;
                this.getEstadosCiviles();
                this.isVisibleEstadosCiviles = true;
            }
            if ($entrada == 'Tipos Usuario'){
                this.tiposUsuarios = null;
                this.mostrarCarga7 = true;
                this.getTiposUsuario();
                this.isVisibleTipoUsuarios = true;
            }
            if ($entrada == 'Grupos'){
                this.grupos = null;
                this.mostrarCarga7 = true;
                this.getGrupos();
                this.isVisibleGrupos = true;
            }
            if ($entrada == 'Grupo Lideres'){
                this.grupoLideres = null;
                this.mostrarCarga8 = true;
                this.getGrupoLideres();
                this.isVisibleGrupoLideres = true;
            }
            if ($entrada == 'Grupo Miembros'){
                this.grupoMiembros = null;
                this.mostrarCarga9 = true;
                this.getGrupoMiembros();
                this.isVisibleGrupoMiembros = true;
            }
        },
        // SISTEMAS DE SALUD:
        getSistemasSalud(){
            axios.get('../api/sistemasSalud').then( response =>{
                this.mostrarCarga3 = true;
                this.sistemasSalud = response.data;
                if (this.tableSisSalud != null){
                    this.tableSisSalud.clear();
                    this.tableSisSalud.destroy();
                }
                this.crearTablas('Sistemas de Salud');
            }).catch(e=> console.log(e))
        },
        async deleteSistemaSalud(id,sistemaSalud){
            const ok = await this.$refs.confirmation.show({
                title: 'Borrar Sistema de Salud',
                message: `¿Está seguro/a que desea eliminar el sistema de salud '${sistemaSalud}'? Esta acción no puede ser desecha.`,
                okButton: 'Eliminar',
                cancelButton: 'Volver'
            })
            if (ok) {
                axios.delete(`../api/sistemasSalud/${id}`).then( response =>{
                    this.toast.success("Sistema de Salud eliminado con éxito!", {
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
                    this.recargarTabla('Sistemas de Salud');
                }).catch(e=> console.log(e))
            }
        },
        editSistema(sistema){
            this.sistemaEdit = sistema;
            this.showEditSistemaSalud = true;
        },
        // AFPs:
        getAFPs(){
            axios.get('../api/afp').then( response =>{
                this.mostrarCarga4 = true;
                this.afps = response.data;
                if (this.tableAfp != null){
                    this.tableAfp.clear();
                    this.tableAfp.destroy();
                }
                this.crearTablas('AFP');
            }).catch(e=> console.log(e))
        },
        async deleteAFP(id,afp){
            const ok = await this.$refs.confirmation.show({
                title: 'Borrar AFP',
                message: `¿Está seguro/a que desea eliminar la AFP '${afp}'? Esta acción no puede ser desecha.`,
                okButton: 'Eliminar',
                cancelButton: 'Volver'
            })
            if (ok) {
                axios.delete(`../api/afp/${id}`).then( response =>{
                    this.toast.success("AFP eliminada con éxito!", {
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
                    this.recargarTabla('AFP');
                }).catch(e=> console.log(e))
            }
        },
        editAfp(afp){
            this.afpEdit = afp;
            this.showEditAFP = true;
        },
        // ESTADOS CIVILES:
        getEstadosCiviles(){
            axios.get('../api/estadosCiviles').then( response =>{
                this.estadosCiviles = response.data;

                this.mostrarCarga5 = true;
                this.estadosCiviles = response.data;
                if (this.tableEstadoCivil != null){
                    this.tableEstadoCivil.clear();
                    this.tableEstadoCivil.destroy();
                }
                this.crearTablas('Estados Civiles');
            }).catch(e=> console.log(e))
        },
        editEstado(estado){
            this.estadoEdit = estado;
            this.showEditEstadoCivil = true;
        },
        async deleteEstadoCivil(id,estadoCivil){
            const ok = await this.$refs.confirmation.show({
                title: 'Borrar Estado Civil',
                message: `¿Está seguro/a que desea eliminar el estado civil '${estadoCivil}'? Esta acción no puede ser desecha.`,
                okButton: 'Eliminar',
                cancelButton: 'Volver'
            })
            if (ok) {
                axios.delete(`../api/estadosCiviles/${id}`).then( response =>{
                    this.toast.success("Estado Civil eliminado con éxito!", {
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
                    this.recargarTabla('Estados Civiles');
                }).catch(e=> console.log(e))
            }
        },
        // TIPOS DE USUARIO:
        getTiposUsuario(){
            axios.get('../api/tiposUsuario').then( response =>{
                this.tiposUsuarios = response.data;
                this.mostrarCarga7 = true;
                if (this.tableTipoUsuario != null){
                    this.tableTipoUsuario.clear();
                    this.tableTipoUsuario.destroy();
                }
                this.crearTablas('Tipos de Usuario');
            }).catch(e=> console.log(e))
        },
        editTipoUsuario(tipoUsuario){
            this.tipoUsuarioEdit = tipoUsuario;
            this.showEditTipoUsuario = true;
        },
        async deleteTipoUsuario(id,tipoUsuario){
            const ok = await this.$refs.confirmation.show({
                title: 'Borrar Tipo de Usuario',
                message: `¿Está seguro/a que desea eliminar el tipo de usuario '${tipoUsuario}'? Esta acción no puede ser desecha.`,
                okButton: 'Eliminar',
                cancelButton: 'Volver'
            })
            if (ok) {
                axios.delete(`../api/tiposUsuario/${id}`).then( response =>{
                    this.toast.success("Tipo de Usuario eliminado con éxito!", {
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
                    this.recargarTabla('Tipos de Usuario');
                }).catch(e=> console.log(e))
            }
        },
        // GRUPOS:
        getGrupos(){
            axios.get('../api/grupos').then( response =>{
                this.grupos = response.data;
                this.mostrarCarga7 = true;
                if (this.tableGrupos != null){
                    this.tableGrupos.clear();
                    this.tableGrupos.destroy();
                }
                this.crearTablas('Grupos');
            }).catch(e=> console.log(e))
        },
        editGrupo(grupo){
            this.grupoEdit = grupo;
            this.showEditGrupo = true;
        },
        async deleteGrupo(id,grupo){
            const ok = await this.$refs.confirmation.show({
                title: 'Borrar Grupo',
                message: `¿Está seguro/a que desea eliminar el grupo '${grupo}'? Esta acción no puede ser desecha.`,
                okButton: 'Eliminar',
                cancelButton: 'Volver'
            })
            if (ok) {
                axios.delete(`../api/grupos/${id}`).then( response =>{
                    this.toast.success("Grupo eliminado con éxito!", {
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
                    this.recargarTabla('Grupos');
                }).catch(e=> console.log(e))
            }
        },
        // GRUPO LIDERES:
        getGrupoLideres(){
            axios.get('../api/grupoLideres').then( response =>{
                this.mostrarCarga8 = true;
                this.grupoLideres = response.data;
                if (this.tableGrupoLideres != null){
                    this.tableGrupoLideres.clear();
                    this.tableGrupoLideres.destroy();
                }
                this.crearTablas('Grupo Lideres');
            }).catch(e=> console.log(e))
        },
        editGrupoLider(grupoLider){
            this.grupoLiderEdit = grupoLider;
            this.showEditGrupoLider = true;
        },
        async deleteGrupoLider(id,lider,grupo){
            const ok = await this.$refs.confirmation.show({
                title: 'Borrar Lider',
                message: `¿Está seguro/a que desea eliminar el lider '${lider}' del grupo '${grupo}'? Esta acción no puede ser desecha.`,
                okButton: 'Eliminar',
                cancelButton: 'Volver'
            })
            if (ok) {
                axios.delete(`../api/grupoLideres/${id}`).then( response =>{
                    this.toast.success("Lider eliminado con éxito!", {
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
                    this.recargarTabla('Grupo Lideres');
                }).catch(e=> console.log(e))
            }
        },
        // GRUPO MIEMBROS:
        getGrupoMiembros(){
            axios.get('../api/grupoMiembros').then( response =>{
                this.mostrarCarga8 = true;
                this.grupoMiembros = response.data;
                if (this.tableGrupoMiembros != null){
                    this.tableGrupoMiembros.clear();
                    this.tableGrupoMiembros.destroy();
                }
                this.crearTablas('Grupo Miembros');
            }).catch(e=> console.log(e))
        },
        editGrupoMiembro(grupoMiembro){
            this.grupoMiembroEdit = grupoMiembro;
            this.showEditGrupoMiembro = true;
        },
        async deleteGrupoMiembro(id,miembro,grupo){
            const ok = await this.$refs.confirmation.show({
                title: 'Borrar Miembro',
                message: `¿Está seguro/a que desea eliminar el miembro '${miembro}' del grupo '${grupo}'? Esta acción no puede ser desecha.`,
                okButton: 'Eliminar',
                cancelButton: 'Volver'
            })
            if (ok) {
                axios.delete(`../api/grupoMiembros/${id}`).then( response =>{
                    this.toast.success("Miembro eliminado con éxito!", {
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
                    this.recargarTabla('Grupo Miembros');
                }).catch(e=> console.log(e))
            }
        },
    }
}
</script>