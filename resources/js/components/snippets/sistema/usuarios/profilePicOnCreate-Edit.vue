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
                <a class="btn btn-closed" @click="$emit('close'); fotoMiniatura = null; profilePicChange = null;" ref="closeBtn">X</a>
              </div>
              <div class="modal-body">
                <slot name="body">
                  <div class="row">
                    <div class="col-12">
                      <div class="form-group">
                        <input type="file" accept=".jpg, .jpeg, .png, .heif, .heic" id="foto" class="form-control"  @change="getImage">
                      </div>
                    </div>
                    <div v-if="fotoMiniatura != null" class="col-auto">
                      <div class="img-not-draggable" style="position: relative; left: 50%; top: 50%; transform: translate(-50%, -50%); -webkit-transform: translate(-50%, -50%);">
                        <div style="width: 150px; height: 150px; display: flex; justify-content: center; align-items: center;">
                          <img class="img-not-draggable w-100 h-100 border-radius-100 shadow-sm" style="object-fit: cover;" :src="imagen" alt="profile_image">
                        </div>
                      </div>
                    </div>
                    <div v-else>
                      <div class="img-not-draggable" style="position: relative; left: 50%; top: 50%; transform: translate(-50%, -50%); -webkit-transform: translate(-50%, -50%);">
                        <div style="width: 150px; height: 150px; display: flex; justify-content: center; align-items: center;">
                          <img class="img-not-draggable w-100 h-100 border-radius-100 shadow-sm" style="object-fit: cover;" src="https://icon-library.com/images/default-user-icon/default-user-icon-13.jpg" alt="profile_image">
                        </div>
                      </div>
                    </div>
                  </div>
                </slot>
              </div>
              <div class="modal-footer">
                <slot name="footer">
                  <button class="btn btn-alert float-end" @click="$emit('close'); fotoAceptada = true;" :disabled="buttonDisable">Aceptar</button>
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
    fotoAceptada: null,
    profilePicChange: null,
    fotoMiniatura: null,
  }),
  methods: {
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
