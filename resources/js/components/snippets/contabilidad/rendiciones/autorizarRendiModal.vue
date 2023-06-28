<template>
  <transition name="modalcrear">
    <div name="modal">
      <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container-xl">
              <div class="modal-header" style="font-weight: bold; color: #444444;">
                <slot name="header">
                    Autorizar Rendición: 
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
                          <th class="text-uppercase text-xs font-weight-bolder">Estado</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Folio</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Colaborador</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Monto Solicitud</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Tipo Moneda</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Usuario Solicitud</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Fecha Solicitud</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Autorizar</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Objeciones</th>
                        </tr>
                      </thead>
                      <tbody>
                        <!-- <tr v-if="rendisAutorizar != 0">
                          <td colspan="7"></td>
                          <td v-if="checkAll1 == false"> <label>Marcar Todo:</label></td>
                          <td v-else> <label>Desmarcar Todo:</label></td>
                          <td><div class="form-check"><label class="form-check-label"><input class="form-check-input" @click="checkAll()" type="checkbox"></label></div></td>
                          <td colspan="1"></td>
                        </tr> -->
                        <tr v-if="rendisAutorizar == 0">
                          <td colspan="10"> No hay información disponible. </td>
                        </tr>
                        <tr v-else v-for="rendi in rendisAutorizar" :key="rendi.id">
                          <td>
                            <p class="text-sm font-weight-bolder mb-0" style="color: black">{{ rendi.id }}</p>
                          </td>
                          <td>
                            <p v-if="rendi.estado == 'PendRen'" class="text-sm font-weight-bolder mb-0" style="color: #FFC107"> Pendiente Rendición </p>
                            <p v-else-if="rendi.estado == 'PendAutRen'" class="text-sm font-weight-bolder mb-0" style="color: #FFC107"> Pendiente Auth Rendición </p>
                            <p v-else-if="rendi.estado == 'PendAutPag'" class="text-sm font-weight-bolder mb-0" style="color: #FFC107"> Pendiente Auth Pago </p>
                            <p v-else-if="rendi.estado == 'Rendida'" class="text-sm font-weight-bolder mb-0" style="color: #28A745"> Rendida </p>
                          </td>
                          <td>
                            <p class="text-sm mb-0">{{ rendi.folio }}</p>
                          </td>
                          <td>
                            <p class="text-sm mb-0">{{ rendi.colaborador }}</p>
                          </td>
                          <td>
                            <p class="text-sm mb-0">{{ formatMoneyCLP(rendi.montoSoli) }}</p>
                          </td>
                          <td>
                            <p class="text-sm mb-0">{{ rendi.moneda.abreviacion }}</p>
                          </td>
                          <td>
                            <p class="text-sm mb-0">{{ rendi.usuario_soli.name }}</p>
                          </td>
                          <td>
                            <p class="text-sm mb-0">{{ thisDate(rendi.fechaSoli) }}</p>
                          </td>
                          <td>
                            <div class="form-check">
                              <input style="-webkit-transform: scale(1.2); transform: scale(1.2);" type="radio" value="true" v-model="rendi.autorizadoRendi">
                              <label style="margin-right:15px;">Si</label>
                              <input style="margin-left:15px; -webkit-transform: scale(1.2); transform: scale(1.2);" type="radio" value="false" v-model="rendi.autorizadoRendi">
                              <label>No</label>
                              <input selected checked style="margin-left:15px; -webkit-transform: scale(1.2); transform: scale(1.2);" type="radio" value="null" v-model="rendi.autorizadoRendi">
                              <label>Pendiente</label>
                            </div>
                          </td>
                          <td>
                            <p class="text-sm mb-0">
                              <textarea class="form-control" v-model="rendi.objecionesRendi" style="min-width: 200px; height: 40px; overflow: hidden; text-align: justify;"> </textarea>
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
      buttonText:'Guardar Autorización de Rendición',
      buttonDisable: false,
      mostrarCargaAutorizar: true,
      mostrarTablaAutorizar: false,
      rendisAutorizar: null,
      errors: [],
      tablaFondoFijoAutorizar: null,
      checkAll1: false,
      usuariosRendiciones: [],
  }),
  created(){
    this.getRendis(this.userID);
  },
  methods: {
    cerrarModal(){
        const elem = this.$refs.closeBtn;
        this.$emit('recarga');
        elem.click();
    },
    getRendis(id){
      axios.get(`api/rendiciones/${id}`).then( response =>{
        this.rendisAutorizar = response.data;
        this.rendisAutorizar = this.rendisAutorizar.filter(rendi => rendi.estado == "PendAutRen");
        this.mostrarCargaAutorizar = false;
        this.mostrarTablaAutorizar = true;
      }).catch(e=> console.log(e))
    },
    async saveAuth() {
      if (this.rendisAutorizar == 0){
        this.toast.warning("No hay nada que autorizar", {
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
      }else{


        const ok = await this.$refs.confirmation.show({
          title: 'Autorizar',
          message: `¿Está seguro/a que desea realizar estas autorizaciones? Esta acción no puede ser desecha.`,
          okButton: 'Autorizar',
          cancelButton: 'Volver'
        })
        if (ok) {
            var $salida = [];
            $salida.push(this.rendisAutorizar);
            $salida.push(this.userID);
            axios.post('api/rendiciones/autorizarRendi', $salida).then( response => {
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
            // this.rendisAutorizar.forEach(rendicion => {
            //   if (!this.usuariosRendiciones.includes(rendicion.idUsuario)) {
            //     this.usuariosRendiciones.push(rendicion.idUsuario);
            //   }
            // });
            // this.usuariosRendiciones.forEach(usuario => {
            //   var $salida = [];
            //   var $temporal = [];
            //   this.rendisAutorizar.forEach(rendicion => {
            //     if(rendicion.idUsuario == usuario && rendicion.autorizado != null){
            //       $temporal.push(rendicion);
            //     }
            //   });
            //   if($temporal.length != 0){
            //     $salida.push($temporal);
            //     $salida.push(usuario);
            //     axios.post('api/fofis/sendEmailFondoFijoAutorizado', $salida).then( response => {
            //       console.log(response);
            //       this.toast.success("Correo informativo enviado!", {
            //         position: "top-right",
            //         timeout: 3000,
            //         closeOnClick: true,
            //         pauseOnFocusLoss: true,
            //         pauseOnHover: true,
            //         draggable: true,
            //         draggablePercent: 0.6,
            //         showCloseButtonOnHover: false,
            //         hideProgressBar: true,
            //         closeButton: "button",
            //         icon: true,
            //         rtl: false
            //       });
            //     }).catch(e=> {console.log(e)});
            //   }
            // });
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
      }
    },
  }
}
</script>