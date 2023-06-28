<template>
  <transition name="modalcrear">
    <div name="modal">
      <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container-2xl">
              <div class="modal-header" style="font-weight: bold; color: #444444;">
                <slot name="header">
                    Autorizar: 
                </slot>
                <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
              </div>
              <div class="modal-body">
                <slot name="body">
                  <div class="table-responsive p-0" id="espaciadoTablaGastos" style="min-height: 300px; max-height: 500px;">
                    <div v-show="mostrarCargaAutorizar" class="loader"></div>
                    <table v-show="mostrarTablaAutorizar" class="table align-items-center mb-0" id="tableDesign">
                      <thead>
                        <tr style="color: black">
                          <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Folio</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Fecha</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Usuario</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Cantidad Gastos</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Fondo Fijo Asignado</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Monto Total</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Observaciones</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Autorizar</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Objeciones</th>
                        </tr>
                      </thead>
                      <tbody>
                        <!-- <tr v-if="fondosFijosAutorizar != 0">
                          <td colspan="7"></td>
                          <td v-if="checkAll1 == false"> <label>Marcar Todo:</label></td>
                          <td v-else> <label>Desmarcar Todo:</label></td>
                          <td><div class="form-check"><label class="form-check-label"><input class="form-check-input" @click="checkAll()" type="checkbox"></label></div></td>
                          <td colspan="1"></td>
                        </tr> -->
                        <tr v-if="fondosFijosAutorizar == 0">
                          <td colspan="10"> No hay información disponible. </td>
                        </tr>
                        <tr v-else v-for="fondoFijo in fondosFijosAutorizar" :key="fondoFijo.id">
                          <td>
                            <p class="text-sm mb-0">{{ fondoFijo.id }}</p>
                          </td>
                          <td>
                            <p class="text-sm mb-0">{{ fondoFijo.folioFofi }}</p>
                          </td>
                          <td>
                            <p class="text-sm mb-0">{{ thisDate(fondoFijo.fecha) }}</p>
                          </td>
                          <td>
                            <p class="text-sm mb-0">{{ fondoFijo.usuario.name }}</p>
                          </td>
                          <td>
                            <p class="text-sm mb-0">{{ fondoFijo.cantidadGastos }}</p>
                          </td>
                          <td>
                            <p class="text-sm mb-0">{{ formatMoneyCLP(fondoFijo.fofiAsignado) }}</p>
                          </td>
                          <td>
                            <p class="text-sm mb-0">{{ formatMoneyCLP(fondoFijo.montoTotal)  }}</p>
                          </td>
                          <td>
                            <textarea readonly class="form-control" style="text-align: justify; min-width: 150px; height: 40px; word-wrap: break-word; overflow: hidden;" v-model="fondoFijo.observaciones"></textarea>
                          </td>
                          <td>
                            <div class="form-check">
                              <input style="-webkit-transform: scale(1.2); transform: scale(1.2);" type="radio" value="true" v-model="fondoFijo.autorizado">
                              <label style="margin-right:15px;">Si</label>
                              <input style="margin-left:15px; -webkit-transform: scale(1.2); transform: scale(1.2);" type="radio" value="false" v-model="fondoFijo.autorizado">
                              <label>No</label>
                              <input selected checked style="margin-left:15px; -webkit-transform: scale(1.2); transform: scale(1.2);" type="radio" value="null" v-model="fondoFijo.autorizado">
                              <label>Pendiente</label>
                            </div>
                          </td>
                          <td>
                            <p class="text-sm mb-0">
                              <textarea class="form-control" v-model="fondoFijo.objeciones" style="min-width: 200px; height: 40px; overflow: hidden; text-align: justify;"> </textarea>
                            </p>
                          </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </slot>
              </div>
              <div class="modal-footer">
                <slot name="footer">
                  <button class="btn btn-alert float-end" @click="saveAuth()" :disabled="buttonDisable">Guardar</button>
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
import modalconfirmacion from '../../sistema/alerts/confirmationModal.vue'
import modalalerta from '../../sistema/alerts/alertModal.vue'
import {mixin} from '../../../../mixins.js'

export default {
  components: { modalconfirmacion, modalalerta },
  mixins: [mixin],
  data: () => ({
      buttonText:'Guardar Autorización de Pago',
      buttonDisable: false,
      mostrarCargaAutorizar: true,
      mostrarTablaAutorizar: false,
      fondosFijosAutorizar: null,
      errors: [],
      tablaFondoFijoAutorizar: null,
      checkAll1: false,
      usuariosFondosFijos: [],
  }),
  created(){
    this.getFondosFijosAutorizables();
  },
  methods: {
    cerrarModal(){
        const elem = this.$refs.closeBtn;
        this.$emit('recarga');
        elem.click();
    },
    getFondosFijosAutorizables(){
      let datosGetAutorizables = {
        userID: this.userID,
      }
      axios.post(`api/fofis/autorizables`, datosGetAutorizables).then( response =>{true
        this.fondosFijosAutorizar = response.data;
        this.mostrarCargaAutorizar = false;
        this.mostrarTablaAutorizar = true;
      }).catch(e=> console.log(e))
    },
    async saveAuth() {
      const ok = await this.$refs.confirmation.show({
        title: 'Autorizar',
        message: `¿Está seguro/a que desea realizar estas autorizaciones? Esta acción no puede ser desecha.`,
        okButton: 'Autorizar',
        cancelButton: 'Volver'
      })
      if (ok) {
          var $salida = [];
          $salida.push(this.fondosFijosAutorizar);
          $salida.push(this.userID);
          axios.post('api/fofis/autorizar', $salida).then( response => {
          this.buttonDisable = true;
          this.buttonText = 'Guardando...';
          this.toast.success("Autorizaciones exitosas!", {
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
          this.fondosFijosAutorizar.forEach(fondoFijo => {
            if (!this.usuariosFondosFijos.includes(fondoFijo.idUsuario)) {
              this.usuariosFondosFijos.push(fondoFijo.idUsuario);
            }
          });
          this.usuariosFondosFijos.forEach(usuario => {
            var $salida = [];
            var $temporal = [];
            this.fondosFijosAutorizar.forEach(fondoFijo => {
              if(fondoFijo.idUsuario == usuario && fondoFijo.autorizado != null){
                $temporal.push(fondoFijo);
              }
            });
            if($temporal.length != 0){
              $salida.push($temporal);
              $salida.push(usuario);
              axios.post('api/fofis/sendEmailFondoFijoAutorizado', $salida).then( response => {
                this.toast.success("Correo informativo enviado!", {
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
              }).catch(e=> {console.log(e)});
            }
          });
          setTimeout(() => {this.cerrarModal();}, 1500);
        })
        .catch((error)=> {
          if (error.response.status == 422){
            this.errors = error.response.data.errors;
            this.toast.warning('Existe un valor inválido.', {
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
          }
        });
      }
    },
  }
}
</script>