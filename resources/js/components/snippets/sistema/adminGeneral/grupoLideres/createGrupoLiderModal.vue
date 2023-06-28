<template>
  <transition name="modalcrear">
    <div name="modal">
      <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container-xs">
              <div class="modal-header fw-bold" style="color: #444444;">
                <slot name="header">
                    Nuevo Lider
                </slot>
                <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
              </div>
              <div class="modal-body">
                <slot name="body">
                  <div class="row">
                      <div class="col-8">
                        <div class="form-group">
                            <label for="">Lider: </label>
                            <br>
                            <select class="form-select" v-model="grupoLider.usuario">
                              <option disabled value="">Seleccione un usuario</option>
                              <option v-for="usuario in usuarios" v-bind:key="usuario.id" v-bind:value="usuario">
                              {{ usuario.name }}
                              </option>
                            </select>
                        </div>
                        <div v-if="errors && errors.idUsuario" class="error">
                          {{ errors.idUsuario[0] }}
                        </div>
                      </div>
                      <div class="col-8">
                        <div class="form-group">
                            <label for="">Grupo: </label>
                            <br>
                            <select class="form-select" v-model="grupoLider.grupo">
                              <option disabled value="">Seleccione un grupo</option>
                              <option v-for="grupo in grupos" v-bind:key="grupo.id" v-bind:value="grupo">
                                {{ grupo.nombre }}
                              </option>
                            </select>
                        </div>
                        <div v-if="errors && errors.idGrupo" class="error">
                          {{ errors.idGrupo[0] }}
                        </div>
                      </div>
                    </div>
                </slot>
              </div>
              <div class="modal-footer">
                  <slot name="footer">
                      <button class="btn btn-continue" @click="crearLider()" :disabled="buttonDisable">
                          {{ buttonText }}
                      </button>
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
import modalconfirmacion from '../../alerts/confirmationModal.vue'
import modalalerta from '../../alerts/alertModal.vue'
import {mixin} from '../../../../../mixins.js'

export default {
    components: { modalconfirmacion, modalalerta },
    mixins: [mixin],
    data: () => ({
        buttonText:'Crear Lider',
        buttonDisable: false,
        grupoLider: {
            usuario: null,
            grupo: null,
        },
        errors:[],
        usuarios: [],
        grupos: [],
    }),
    created(){
      this.getUsuarios();
      this.getGrupos();
    },
    methods: {
      cerrarModal(){
        const elem = this.$refs.closeBtn;
        this.$emit('recarga');
        elem.click();
      },
      getUsuarios(){
        axios.get('../api/usuarios').then( response =>{
            this.usuarios = response.data;
        }).catch(e=> console.log(e))
      },
      getGrupos(){
        axios.get('../api/grupos').then( response =>{
            this.grupos = response.data;
        }).catch(e=> console.log(e))
      },
      async crearLider() {
        const ok = await this.$refs.confirmation.show({
            title: 'Crear Lider',
            message: `¿Está seguro/a que desea asignar al usuario '${this.grupoLider.usuario.name}' como lider del grupo '${this.grupoLider.grupo.nombre}'? Esta acción no puede ser desecha.`,            okButton: 'Crear',
            cancelButton: 'Volver'
        })
        if (ok) {
            let grupo = {
              idUsuario: this.grupoLider.usuario.id,
              idGrupo: this.grupoLider.grupo.id,
            };
            axios.post("../api/grupoLideres", grupo).then((result) => {
                this.buttonDisable = true;
                this.buttonText = 'Guardando...';
                this.toast.success('Lider creado con éxito!', {
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
                setTimeout(() => {this.cerrarModal();}, 1500);
            }).catch((error)=> {
              if (error.response.status == 422){
                  this.errors = error.response.data.errors;
                  this.toast.warning('Existe un valor inválido.', {
                      position: "top-right",
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
      }
    }
}
</script>
