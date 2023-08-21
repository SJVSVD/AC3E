<template> 
  <transition name="modalcrear">
    <div name="modal">
      <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container-xs">
              <div class="modal-header pb-2" style="font-weight: bold; color: #444444;">
                <slot name="header">
                    Foto de Perfil
                </slot>
                <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
              </div>
              <div class="modal-body">
                <slot name="body">
                  <div class="row">
                    <div class="col-12">
                      <div class="form-group">
                        <input type="file" accept=".jpg, .jpeg, .png, .heif, .heic, .pdf" id="foto" class="form-control"  @change="getImage">
                      </div>
                    </div>
                    <div v-if="fotoMiniatura != null" class="col-auto">
                      <div class="img-not-draggable" style="position: relative; left: 50%; top: 50%; transform: translate(-50%, -50%); -webkit-transform: translate(-50%, -50%);">
                        <div style="width: 150px; height: 150px; display: flex; justify-content: center; align-items: center;">
                          <img class="img-not-draggable w-100 h-100 border-radius-100 shadow-sm" style="object-fit: cover;" :src="imagen" alt="profile_image">
                        </div>
                      </div>
                    </div>
                    <div v-else class="col-auto">
                      <div class="img-not-draggable" style="position: relative; left: 50%; top: 50%; transform: translate(-50%, -50%); -webkit-transform: translate(-50%, -50%);">
                        <div style="width: 150px; height: 150px; display: flex; justify-content: center; align-items: center;">
                          <img class="w-100 h-100 border-radius-100 shadow-sm" style="object-fit: cover;" src="https://icon-library.com/images/default-user-icon/default-user-icon-13.jpg" alt="profile_image">
                        </div>
                      </div>
                    </div>
                  </div>
                </slot>
              </div>
              <div class="modal-footer">
                <slot name="footer">
                  <button class="btn btn-closed float-start" @click="deleteProfilePic();" :disabled="buttonDisable">Eliminar</button>
                  <button class="btn btn-alert float-end" @click="changeProfilePic()" :disabled="buttonDisable">Guardar</button>
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
import axios from 'axios';
import modalconfirmacion from '../alerts/confirmationModal.vue';
import modalalerta from '../alerts/alertModal.vue';
import {mixin} from '../../../../mixins.js';
import Compressor from 'compressorjs';

export default {
  components: { modalconfirmacion, modalalerta },
  mixins: [mixin],
  data: () => ({
      profilePicChange: null,
      fotoMiniatura: null,
  }),
  created(){
  },  
  methods: {
    async deleteProfilePic() {
        const ok = await this.$refs.confirmation.show({
          title: 'Eliminar Foto de Perfil',
          message: `¿Está seguro/a que desea eliminar su foto de perfil?`,
          okButton: 'Eliminar',
          cancelButton: 'Volver'
        })
        if (ok) {
          axios.post(`api/usuarios/deleteProfilePic/${this.userID}`).then((result) => {
            this.buttonDisable = true;
            this.buttonText = 'Guardando...';
            this.toast.success("Foto de perfil eliminada con éxito!", {
                position: "top-right",
                timeout: 5000,
                closeOnClick: true,
                pauseOnFocusLoss: true,
                pauseOnHover: true,
                draggable: true,
                draggablePercent: 0.6,
                showCloseButtonOnHover: false,
                hideProgressBar: true,
                closeButton: "button",
                icon: true,
                rtl: false,
            });
            setTimeout(() => {location.reload();}, 1000);
          }).catch((e)=> {
            console.log(e);
            if (e.response.status == 422){
              this.errors = e.response.data.errors;
              this.toast.warning('Error.', {
                position: "top-right",
                timeout: 5000,
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
    async changeProfilePic() {
        const ok = await this.$refs.confirmation.show({
          title: 'Actualizar Foto de Perfil',
          message: `¿Está seguro/a que desea actualizar su foto de perfil?`,
          okButton: 'Actualizar',
          cancelButton: 'Volver'
        })
        if (ok) {
          var userData = {
            userID: this.userID,
            profilePicChange: this.profilePicChange,
          }
          axios.post(`api/usuarios/changeProfilePic`, userData, { headers: { 'Content-Type' : 'multipart/form-data' } } ).then((result) => {
            this.buttonDisable = true;
            this.buttonText = 'Guardando...';
            this.toast.success("Foto de perfil actualizada con éxito!", {
                position: "top-right",
                timeout: 5000,
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
            setTimeout(() => {location.reload();}, 1000);
          })
          .catch((e)=> {
            console.log(e);
            if (e.response.status == 422){
              this.errors = e.response.data.errors;
              this.toast.warning('Existe un valor inválido.', {
                position: "top-right",
                timeout: 5000,
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
    async compressImages(fileInput, output) {
      return new Promise(async function (resolve) {
          const file = fileInput;
          await new Promise((resolve) => {
            new Compressor(file, {
              quality: 0.5,
              success(result) {
                output.push(result);
                resolve();
              }
            });
          });
          resolve();
      });
    },
    async getImage(e){
        this.profilePicChange = e.target.files[0];
        let compression = [];
        await this.compressImages(this.profilePicChange,compression);
        var file = new File([compression[0]], compression[0]['name'], {
        type: compression[0]['type'],});
        this.profilePicChange = file;
        this.chargeImage(this.profilePicChange);
    },
    chargeImage(file){
        let reader = new FileReader();
        reader.onload = (e) => { 
            this.fotoMiniatura = e.target.result;
        }
        reader.readAsDataURL(file);
    },  
  },
  computed: {
    imagen(){
        return this.fotoMiniatura;
    }
  }
}
</script>
