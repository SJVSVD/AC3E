<template>
    <div class="row mt-4 mx-4">
        <div class="col-12">
            <div class="card mb-4">
                <div class="row">
                    <div class="col-12" style="text-align:center">
                        .
                        <br>
                        <!-- ################################################ -->
                        <div class="text-center align-middle m-center">
                            <label style="font-size: 32px;"><i class="fa-solid fa-triangle-exclamation fa-2x" style="margin-right: 30px;"></i>EN MANTENCIÓN<i class="fa-solid fa-person-digging fa-2x" style="margin-left: 30px;"></i></label>
                        </div>
                        <!-- ################################################ -->
                        <br>
                        .
                        <div v-if=true>
                            <h1>Todos</h1> Puede ver este DIV.
                        </div>
                        <div v-if="can('ver-rol')">
                            <h1>Ver Rol</h1> Puede ver este DIV.
                        </div>
                        <div v-if="can('crear-rol')">
                            <h1>Crear Rol</h1> Puede ver este DIV.
                        </div>
                        <div v-if="can('editar-rol')">
                            <h1>Editar Rol</h1> Puede ver este DIV.
                        </div>
                        <div v-if="can('borrar-rol')">
                            <h1>Borrar Rol</h1> Puede ver este DIV.
                        </div>
                        <div v-if="is('Administrador')">
                            <h1>Es Admin</h1> Puede ver este DIV.
                        </div>
                        .
                        <br>
                        <div class="col-6">
                            <!-- SELECT SIMPLE, DATOS EN LISTA -->
                            <Multiselect
                                placeholder="Seleccione una opción:"
                                limit=4
                                v-model="value1"
                                :searchable="true"
                                :options="options1"
                            />
                            <p> {{ value1 }} </p>
                            <br>
                            <!-- SELECT MULTIPLE, DATOS EN LISTA DE OBJETOS -->
                            <Multiselect
                                placeholder="Seleccione una o más opciones:"
                                v-model="value2"
                                limit=4
                                :searchable="true"
                                :close-on-select="false"
                                :createTag="true"
                                :options="options2"
                                mode="tags"
                                label="name"
                                trackBy="name"
                                :object="true"
                            />
                            <p> {{ value2 }} </p>
                            <!-- SELECT SIMPLE, DATOS EN LISTA DE OBJETOS -->
                            <Multiselect
                                placeholder="Seleccione una opción:"
                                limit=4
                                v-model="value3"
                                :searchable="true"
                                :options="options3"
                                label="name"
                                trackBy="name"
                                :object="true"
                            />
                            <p> {{ value3 }} </p>
                            <br>
                            <!-- SELECT MULTIPLE, DATOS EN LISTA DE OBJETOS (DESDE AXIOS) -->
                            <Multiselect
                                placeholder="Seleccione una o más opciones:"
                                v-model="selectedRoles"
                                :searchable="true"
                                :close-on-select="false"
                                :createTag="true"
                                :options="roles"
                                mode="tags"
                                label="name"
                                trackBy="id"
                                :object="true"
                                v-if="!loading"
                            />
                            <p> {{ selectedRoles }} </p>
                        </div>
                        <br>
                        <br>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
import Multiselect from '@vueform/multiselect';

export default {
    components: { Multiselect },
    data() {
        return {
            loading: true,
            selectedRoles: [],
            roles: [],
            rolesdefinitivos: [],
            value1: null,
            options1: [
                'Batman',
                'Robin',
                'Joker',
                'Harley Queen',
                'Poison Ivy',
                'Scarecrow',
                'Arrow',
            ],
            value2: [],
            options2: [
                { id: '1', value: 'captainamerica', name: 'Captain America'},
                { id: '2', value: 'spiderman', name: 'Spiderman'},
                { id: '3', value: 'ironman', name: 'Iron Man'},
                { id: '4', value: 'wanda', name: 'Wanda'},
                { id: '5', value: 'loki', name: 'Loki'},
                { id: '6', value: 'thorodinson', name: 'Thor Odinson'},
                { id: '7', value: 'hawkeye', name: 'Hawkeye'},
                { id: '8', value: 'natasharomanov', name: 'Natasha Romanov'},
            ],
            value3: [],
            options3: [
                { id: '1', value: 'captainamerica', name: 'Captain America'},
                { id: '2', value: 'spiderman', name: 'Spiderman'},
                { id: '3', value: 'ironman', name: 'Iron Man'},
                { id: '4', value: 'wanda', name: 'Wanda'},
                { id: '5', value: 'loki', name: 'Loki'},
                { id: '6', value: 'thorodinson', name: 'Thor Odinson'},
                { id: '7', value: 'hawkeye', name: 'Hawkeye'},
                { id: '8', value: 'natasharomanov', name: 'Natasha Romanov'},
            ],
        }
    },
    created(){
      this.getRoles();
    },
    methods: {
        getRoles(){
            axios.get('api/roles').then(response => {
                this.roles = response.data.map(role => ({ id: role.id.toString(), name: role.name }));
                console.log("OPTIONS", this.options2);
                console.log("ROLES", this.roles);
                this.loading = false;
            }).catch(e => {console.log(e); this.loading = false;});
        },
    }
}
</script>

<style src="@vueform/multiselect/themes/default.css"></style>