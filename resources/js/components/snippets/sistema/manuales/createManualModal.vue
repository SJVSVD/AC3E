<template>
  <transition name="modalcrear">
    <div name="modal">
      <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container-xs">
              <div class="modal-header fw-bold" style="color: #444444;">
                <slot name="header">
                    Nueva Manual
                </slot>
                <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
              </div>
              <div class="modal-body">
                <slot name="body">
                  <div class="row">
                    <div class="col-12">
                      <div class="form-group">
                        <label for="titulo">Titulo del Manual: </label>
                        <input type="text" class= "form-control" v-model="manual.titulo">
                      </div>
                      <div  v-if="errors && errors.titulo" class="error">
                          {{ errors.titulo[0] }}
                      </div>
                    </div>
                    <div class="col-12">
                      <div class="form-group">
                        <label for="" style="display: flex; justify-content: space-between;">Módulo:
                          <label title="Solo las personas con un rol igual al módulo del manual podrán acceder a él.">
                            <span class="badge bg-dark-grey fs-10">?</span>
                          </label>
                        </label>
                        <select class="form-select" v-model="manual.modulo">
                            <option disabled value="">Seleccione el módulo al que pertenece el manual:</option>
                            <option v-for="role in roles" v-bind:key="role.id" v-bind:value="role.name">
                            {{ role.name }}
                            </option>
                        </select>
                      </div>
                      <div  v-if="errors && errors.modulo" class="error">
                        {{ errors.modulo[0] }}
                      </div>
                    </div>
                    <hr size="4" class="separador">
                    <div class="col-12">
                      <div class="form-group">
                        <label for="archivo">Archivo: </label>
                        <input type="file" accept=".pdf" class= "form-control" @change="getFile">
                      </div>
                      <div  v-if="errors && errors.archivo" class="error">
                          {{ errors.archivo[0] }}
                      </div>
                    </div>
                    <div class="col-12">
                      <div class="form-group">
                        <label for="url">URL: </label>
                        <input type="text" class= "form-control" v-model="manual.url">
                      </div>
                      <div  v-if="errors && errors.url" class="error">
                          {{ errors.url[0] }}
                      </div>
                    </div>   
                  </div>
                </slot>
              </div>
              <div class="modal-footer">
                  <slot name="footer">
                      <button class="btn btn-continue" @click="crearManual()" :disabled="buttonDisable">
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
import modalconfirmacion from '../alerts/confirmationModal.vue'
import modalalerta from '../alerts/alertModal.vue'
import {mixin} from '../../../../mixins.js'

export default {
  components: { modalconfirmacion, modalalerta },
  mixins: [mixin],
  data: () => ({
    manual: {
      modulo: null,
      titulo: null,
      fecha: null,
      archivo: null,
      url: null,
    },
    buttonText:'Crear Manual',
    buttonDisable: false,
    errors:[],
  }),
  created(){
    this.manual.fecha = this.printDate();
    this.getRoles();
  },
  methods: {
    getRoles(){
      axios.get('api/roles').then( response =>{
          this.roles = response.data;
      }).catch(e=> console.log(e))
    },
    cerrarModal(){
      const elem = this.$refs.closeBtn;
      this.$emit('recarga');
      elem.click();
    },
    printDate(){
      return new Date().toLocaleDateString();
    },
    async crearManual() {
      const ok = await this.$refs.confirmation.show({
          title: 'Crear Manual',
          message: `¿Está seguro/a que desea crear esta Manual ? Esta acción no puede ser desecha.`,
          okButton: 'Crear',
          cancelButton: 'Volver'
      })
      if (ok) {
        let manual = {
          modulo: this.manual.modulo,
          titulo: this.manual.titulo,
          fecha: this.manual.fecha,
          archivo: this.manual.archivo,
          url: this.manual.url,
        };            
        axios.post("../api/manuales", manual, {headers: { 'Content-Type' : 'multipart/form-data' }} ).then((result) => {
          this.buttonDisable = true;
          this.buttonText = 'Guardando...';
          this.toast.success('Manual guardada con éxito!', {
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
    },
    async getFile(e){
        this.manual.archivo = e.target.files[0];
    },
  }
}
</script>
