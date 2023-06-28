<template>
  <transition name="modalcrear">
    <div name="modal">
      <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container-xl">
              <div class="modal-header" style="font-weight: bold; color: #444444;">
                <slot name="header">
                    Autorizar Pagos: 
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
                          <th class="text-uppercase text-xs font-weight-bolder">Fecha</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Tienda</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Nº NCV</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Nº Boleta</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Monto NCV</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Monto Boleta</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Cantidad Art NCV</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Cantidad Art Boleta</th>
                          <th class="text-uppercase text-xs font-weight-bolder">Autorizar</th>
                          <!-- <th class="text-uppercase text-xs font-weight-bolder">Acciones</th> -->
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
                        <tr v-if="ncvsAutorizar == 0">
                          <td colspan="14"> No hay información disponible. </td>
                        </tr>
                        <tr v-else v-for="ncv in ncvsAutorizar" :key="ncv.id">
                          <td>
                              <p class="text-sm font-weight-bolder mb-0" style="color: black">{{ ncv.id }}</p>
                          </td>
                          <td>
                              <p v-if="ncv.estado == 'Ingresado'" class="text-sm font-weight-bolder mb-0" style="color: #8392AB">{{ ncv.estado }}</p>
                              <p v-if="ncv.estado == 'Procesado'" class="text-sm font-weight-bolder mb-0" style="color: #FFC107">{{ ncv.estado }}</p>
                              <p v-if="ncv.estado == 'Pagado'" class="text-sm font-weight-bolder mb-0" style="color: #28A745">{{ ncv.estado }}</p>
                          </td>
                          <td>
                              <p class="text-sm mb-0">{{ ncv.fecha }}</p>
                          </td>
                          <td>
                              <p class="text-sm mb-0">{{ ncv.tienda.nombre }}</p>
                          </td>
                          <td>
                              <p class="text-sm mb-0">{{ ncv.notaCredito }}</p>
                          </td>
                          <td>
                              <p class="text-sm mb-0">{{ ncv.boleta }}</p>
                          </td>
                          <td>
                              <p class="text-sm mb-0">{{ formatMoneyCLP(ncv.montoNotaCredito) }}</p>
                          </td>
                          <td>
                              <p class="text-sm mb-0">{{ formatMoneyCLP(ncv.montoBoleta) }}</p>
                          </td>
                          <td>
                              <p class="text-sm mb-0">{{ ncv.cantidadArtNCV }}</p>
                          </td>
                          <td>
                              <p class="text-sm mb-0">{{ ncv.cantidadArtBoleta }}</p>
                          </td>
                          <!-- <td class="align-middle text-end">
                              <div class="d-flex px-3 py-1 justify-content-center align-items-center">
                                  <a v-if="ncv.motivo == null" @click="agregarDetalle(ncv)" title="Agregar Detalle" class="btn btn-purple btn-xs"><i class="fa-solid fa-file-signature"></i></a>
                                  <a v-else class="btn btn-success btn-xs" title="Detalle" @click="ncvDetalle(ncv)"><i class="fa-regular fa-eye"></i></a>
                              </div>
                          </td> -->
                          <td>
                            <div class="form-check">
                              <input style="-webkit-transform: scale(1.2); transform: scale(1.2);" type="radio" value="true" v-model="ncv.autorizadoPago">
                              <label style="margin-right:15px;">Si</label>
                              <input style="margin-left:15px; -webkit-transform: scale(1.2); transform: scale(1.2);" type="radio" value="false" v-model="ncv.autorizadoPago">
                              <label>No</label>
                              <input selected checked style="margin-left:15px; -webkit-transform: scale(1.2); transform: scale(1.2);" type="radio" value="null" v-model="ncv.autorizadoPago">
                              <label>Pendiente</label>
                            </div>
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
import modalconfirmacion from '../../../sistema/alerts/confirmationModal.vue'
import modalalerta from '../../../sistema/alerts/alertModal.vue'
import {mixin} from '../../../../../mixins.js'

export default {
  components: { modalconfirmacion, modalalerta },
  mixins: [mixin],
  data: () => ({
      buttonText:'Guardar Autorización de Pago',
      buttonDisable: false,
      mostrarCargaAutorizar: true,
      mostrarTablaAutorizar: false,
      ncvsAutorizar: null,
      errors: [],
      checkAll1: false,
  }),
  created(){
    this.getNCVs(this.userID);
  },
  methods: {
    getNCVs(id){
      axios.get(`api/ncv/${id}`).then( response =>{
        this.ncvsAutorizar = response.data;
        this.ncvsAutorizar = this.ncvsAutorizar.filter(ncv => ncv.estado == "Procesado");
        this.mostrarCargaAutorizar = false;
        this.mostrarTablaAutorizar = true;
      }).catch(e=> console.log(e))
    },
    cerrarModal(){
        const elem = this.$refs.closeBtn;
        this.$emit('recarga');
        elem.click();
    },
    async saveAuth() {
      if (this.ncvsAutorizar == 0){
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
            $salida.push(this.ncvsAutorizar);
            $salida.push(this.userID);
            axios.post('api/ncv/autorizarPago', $salida).then( response => {
            console.log(response.data);
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