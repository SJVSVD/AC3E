<template>
  <transition name="modalver">
    <div name="modal">
      <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container">
              <div class="modal-header pb-1" style="font-weight: bold; color: #444444;">
                <slot name="header">
                  <label class="col-5 m-0" style="font-size: 17px;">WoS Publication Details:</label>
                  <label class="col-5 m-0 fs-8"> User: <label class="fw-normal" style="font-size: 14px;"></label>{{ isiPublication1.usuario.name }}</label>
                </slot>
                <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
              </div>
              <div class="modal-body">
                <slot name="body">
                  <div class="form-group">
                    <div class="row">
                      <div class="text-uppercase pb-4">General information:</div>
                      <label class="col-md-6 m-0 fs-8 pb-1"> Status: <label class="fw-normal" style="font-size: 14px;">{{ isiPublication1.status }}</label></label>
                      <label class="col-md-10 m-0 fs-8 pb-1"> AC3E Researchers Involved: <label v-if="isiPublication1.researcherInvolved != null" class="fw-normal" style="font-size: 14px;">{{ isiPublication1.researcherInvolved }}</label><label v-else class="fw-normal" style="font-size: 14px;">---</label></label>
                      <label class="col-md-10 m-0 fs-8 pb-1"> Authors: <label v-if="isiPublication1.authors != null" class="fw-normal" style="font-size: 14px;">{{ isiPublication1.authors }}</label><label v-else class="fw-normal" style="font-size: 14px;">---</label></label>
                      <label class="col-md-10 m-0 fs-8 pb-1"> Article Title: <label v-if="isiPublication1.articleTitle != null" class="fw-normal" style="font-size: 14px;">{{ isiPublication1.articleTitle }}</label><label v-else class="fw-normal" style="font-size: 14px;">---</label></label>
                      <label class="col-md-10 m-0 fs-8 pb-1"> Journal Name: <label v-if="isiPublication1.journalName != null" class="fw-normal" style="font-size: 14px;">{{ isiPublication1.journalName }}</label><label v-else class="fw-normal" style="font-size: 14px;">---</label></label>
                      <label class="col-md-3 m-0 fs-8 pb-1"> Doi: <label v-if="isiPublication1.doi != null" class="fw-normal" style="font-size: 14px;">{{ isiPublication1.doi }}</label><label v-else class="fw-normal" style="font-size: 14px;">---</label></label>
                      <label class="col-md-3 m-0 fs-8 pb-1"> Volume: <label v-if="isiPublication1.volume != null" class="fw-normal" style="font-size: 14px;">{{ isiPublication1.volume }}</label><label v-else class="fw-normal" style="font-size: 14px;">---</label></label>
                      <label class="col-md-3 m-0 fs-8 pb-1"> First Page: <label v-if="isiPublication1.firstPage != null" class="fw-normal" style="font-size: 14px;">{{ isiPublication1.firstPage }}</label><label v-else class="fw-normal" style="font-size: 14px;">---</label></label>
                      <label class="col-md-3 m-0 fs-8 pb-1"> Last Page: <label v-if="isiPublication1.lastPage != null" class="fw-normal" style="font-size: 14px;">{{ isiPublication1.lastPage }}</label><label v-else class="fw-normal" style="font-size: 14px;">---</label></label>
                      <label class="col-md-3 m-0 fs-8 pb-1"> Keywords: <label v-if="isiPublication1.keywords != null" class="fw-normal" style="font-size: 14px;">{{ isiPublication1.keywords }}</label><label v-else class="fw-normal" style="font-size: 14px;">---</label></label>
                      <label class="col-md-3 m-0 fs-8 pb-1"> Year Published: <label v-if="isiPublication1.yearPublished != null" class="fw-normal" style="font-size: 14px;">{{ isiPublication1.yearPublished }}</label><label v-else class="fw-normal" style="font-size: 14px;">---</label></label>
                      <label class="col-md-9 m-0 fs-8 pb-1"> Funding: <label v-if="isiPublication1.funding != null" class="fw-normal" style="font-size: 14px;">{{ isiPublication1.funding }}</label><label v-else class="fw-normal" style="font-size: 14px;">---</label></label>
                      <label class="col-md-6 m-0 fs-8 pb-1"> Comments: <label v-if="isiPublication1.comments != null" class="fw-normal" style="font-size: 14px;">{{ isiPublication1.comments }}</label><label v-else class="fw-normal" style="font-size: 14px;">---</label></label>
                      <label class="col-md-6 m-0 fs-8 pb-1"> Progress Report Year: <label class="fw-normal" style="font-size: 14px;">{{ isiPublication1.progressReport }}</label></label>
                    </div>
                  </div>
                </slot>
              </div>
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
  }),
  props:{
    isiPublication1: Object,
  },
  created(){
  },

}
</script>