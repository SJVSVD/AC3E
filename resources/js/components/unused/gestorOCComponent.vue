<template>
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="row">
                    <div class="col-9">
                        <div class="card-header pb-0">
                            <h6>Pokemons</h6>
                        </div>
                    </div>
                    <div class="dropdown col-auto">
                        <div class="card-header pb-0">
                            <a class="btn btn-primary" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                Mostrar/Ocultar Columnas:
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <li><a class="dropdown-item toggle-vis" data-column="2" href="#">PokedexID</a></li>
                                <li><a class="dropdown-item toggle-vis" data-column="3" href="#">Nombre</a></li>
                                <li><a class="dropdown-item toggle-vis" data-column="4" href="#">Tipo 1</a></li>
                                <li><a class="dropdown-item toggle-vis" data-column="5" href="#">Tipo 2</a></li>
                                <li><a class="dropdown-item toggle-vis" data-column="6" href="#">Estadísticas</a></li>
                                <li><a class="dropdown-item toggle-vis" data-column="7" href="#">Vida</a></li>
                                <li><a class="dropdown-item toggle-vis" data-column="8" href="#">Ataque</a></li>
                                <li><a class="dropdown-item toggle-vis" data-column="9" href="#">Defensa</a></li>
                                <li><a class="dropdown-item toggle-vis" data-column="10" href="#">Ataque Especial</a></li>
                                <li><a class="dropdown-item toggle-vis" data-column="11" href="#">Defensa Especial</a></li>
                                <li><a class="dropdown-item toggle-vis" data-column="12" href="#">Velocidad</a></li>
                                <li><a class="dropdown-item toggle-vis" data-column="13" href="#">Generación</a></li>
                                <li><a class="dropdown-item toggle-vis" data-column="14" href="#">¿Legendario?</a></li>
                            </ul>
                        </div>
                        <p></p>
                    </div>
                    <div class="col-auto" style="margin-left: auto; margin-right: 60px;">
                        <div>
                            <a class="btn btn-continue" id="show-modal1" @click="showModal1 = true">Nuevo</a>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2" style="min-height: 400px">
                    <div class="table-responsive p-0">
                        <div v-show="mostrarCarga" class="loader-sm"></div>
                        <table v-show="mostrarTabla" class="table align-items-center mb-0" id="MyTableGestorOC">
                            <thead>
                                <tr style="color: black">
                                    <th style="min-width: 16px;"></th>
                                    <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                                    <th class="text-uppercase text-xs font-weight-bolder">PokedexID</th>
                                    <th class="text-uppercase text-xs font-weight-bolder">Nombre</th>
                                    <th class="text-uppercase text-xs font-weight-bolder">Tipo 1</th>
                                    <th class="text-uppercase text-xs font-weight-bolder">Tipo 2</th>
                                    <th class="text-uppercase text-xs font-weight-bolder">Estadísticas</th>
                                    <th class="text-uppercase text-xs font-weight-bolder">Vida</th>
                                    <th class="text-uppercase text-xs font-weight-bolder">Ataque</th>
                                    <th class="text-uppercase text-xs font-weight-bolder">Defensa</th>
                                    <th class="text-uppercase text-xs font-weight-bolder">Ataque Especial</th>
                                    <th class="text-uppercase text-xs font-weight-bolder">Defensa Especial</th>
                                    <th class="text-uppercase text-xs font-weight-bolder">Velocidad</th>
                                    <th class="text-uppercase text-xs font-weight-bolder">Generación</th>
                                    <th class="text-uppercase text-xs font-weight-bolder">¿Legendario?</th>
                                    <th class="text-uppercase text-xs font-weight-bolder">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="pokemon in pokemons" :key="pokemon.id">
                                    <td></td>
                                    <td>
                                        <p class="text-sm font-weight-bolder mb-0" style="color:black">{{ pokemon.id }}</p>
                                    </td>
                                    <td>
                                        <p class="text-sm mb-0">{{ pokemon.pokedexID }}</p>
                                    </td>
                                    <td>
                                        <p class="text-sm mb-0">{{ pokemon.name }}</p>
                                    </td>
                                    <td>
                                        <p class="text-sm mb-0">{{ pokemon.type1 }}</p>
                                    </td>
                                    <td>
                                        <p class="text-sm mb-0">{{ pokemon.type2 }}</p>
                                    </td>
                                    <td>
                                        <p class="text-sm mb-0">{{ pokemon.total_stats }}</p>
                                    </td>
                                    <td>
                                        <p class="text-sm mb-0">{{ pokemon.health }}</p>
                                    </td>
                                    <td>
                                        <p class="text-sm mb-0">{{ pokemon.attack }}</p>
                                    </td>
                                    <td>
                                        <p class="text-sm mb-0">{{ pokemon.defense }}</p>
                                    </td>
                                    <td>
                                        <p class="text-sm mb-0">{{ pokemon.sp_attack }}</p>
                                    </td>
                                    <td>
                                        <p class="text-sm mb-0">{{ pokemon.sp_defense }}</p>
                                    </td>
                                    <td>
                                        <p class="text-sm mb-0">{{ pokemon.speed }}</p>
                                    </td>
                                    <td>
                                        <p class="text-sm mb-0">{{ pokemon.generation }}</p>
                                    </td>
                                    <td>
                                        <p class="text-sm mb-0">{{ pokemon.legendary }}</p>
                                    </td>
                                    <td class="align-middle text-end">
                                        <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                            <a class="btn btn-success btn-sm" @click="pokemonEditar(pokemon)"><i class="fa fa-fw fa-edit"></i> Editar</a>
                                            &nbsp;
                                            <a class="btn btn-closed btn-sm" @click="deletePokemon(pokemon.id,pokemon.name)"><i class="fa fa-fw fa-trash"></i>Eliminar</a>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="col-3">
                                <label style="font-weight: 500">Estos botones utilizan los elementos seleccionados en la tabla, en caso de no existir ninguno seleccionará todos los registros. </label>
                            </div>
                            <div class="col-auto">
                                <label title="Para seleccionar UN solo registro de la tabla basta con hacer &#013; click en el recuadro de la primera columna, para seleccionar &#013; varios consecutivos mantener SHIFT, para seleccionar varios &#013; no consecutivos mantener CTRL."><span class="badge bg-dark-grey fs-10">?</span></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-4 align-left text-center float-end">
                    <!-- -->
                </div>
                <div class="col-4 align-left text-center float-end">
                    <!-- -->
                </div>
                <div class="col-4 align-left text-center float-end">
                    <!-- -->
                </div>
            </div>
            <modalconfirmacion ref="confirmation"></modalconfirmacion>
            <modalalerta ref="alert"></modalalerta>
            <modaleditar v-bind:pokemon="pokemoneditar" v-if="showModal2" @close="showModal2 = false"></modaleditar>
            <modalcrear v-if="showModal1" @close="showModal1 = false"></modalcrear>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import modalcrear from '../snippets/sistema/usuarios/createUserModal.vue'
import modaleditar from '../snippets/sistema/usuarios/editUserModal.vue'
import modalconfirmacion from '../snippets/sistema/alerts/confirmationModal.vue'
import modalalerta from '../snippets/sistema/alerts/alertModal.vue'
import {mixin} from '../../mixins.js'

export default {
    components: { modalcrear, modaleditar, modalconfirmacion, modalalerta },
    mixins: [mixin],
    data(){
        return{
            pokemons: null,
            showModal1: false,
            showModal2: false,
            pokemoneditar:null,
            mostrarTabla: false,
            mostrarCarga: true
        }
    },
    created(){
        this.getPokemons();
    },
    methods: {
        getPokemons(){
            axios.get('api/pokemons').then( response =>{
                this.pokemons = response.data;
                setTimeout(function(){
                    var table = $('#MyTableGestorOC').DataTable({});
                    this.mostrarCarga = false;
                    this.mostrarTabla = true;
                    $('a.toggle-vis').on('click', function (e) {
                        e.preventDefault();
                        var column = table.column($(this).attr('data-column'));
                        column.visible(!column.visible());
                    });
                    table.on("click", "th.select-checkbox", function() {
                        if ($("th.select-checkbox").hasClass("selected")) {
                            table.rows().deselect();
                            $("th.select-checkbox").removeClass("selected");
                        } else {
                            table.rows().select();
                            $("th.select-checkbox").addClass("selected");
                        }
                    }).on("select deselect", function() {
                        ("Some selection or deselection going on")
                        if (table.rows({
                                selected: true
                            }).count() !== table.rows().count()) {
                            $("th.select-checkbox").removeClass("selected");
                        } else {
                            $("th.select-checkbox").addClass("selected");
                        }
                    });
                }.bind(this),100);
            }).catch(e=> console.log(e))
        },
        pokemonEditar(pokemon){
            this.pokemoneditar = pokemon;
            this.showModal2 = true;

        },
        async deletePokemon(id,Nombre) {
            const ok = await this.$refs.confirmation.show({
                title: 'Borrar Pokemon',
                message: `¿Está seguro/a que desea eliminar al pokemon '${Nombre}'? Esta acción no puede ser desecha.`,
                okButton: 'Eliminar',
                cancelButton: 'Volver'
            })
            if (ok) {
                axios.delete(`api/pokemons/${id}`).then( response =>{
                    this.toast.success("Pokemon eliminado con éxito!", {
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
                    this.recargarTabla('General');
                }).catch(e=> console.log(e))
            }
        },
    }
}
</script>

