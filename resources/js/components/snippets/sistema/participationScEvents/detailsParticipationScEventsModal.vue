<template>
  <transition name="modalver">
    <div name="modal">
      <div class="modal-mask">
        <div class="modal-wrapper">
          <div class="modal-container">
            <div class="modal-header pb-1" style="font-weight: bold; color: #444444;">
              <slot name="header">
                <label class="col-5 m-0" style="font-size: 17px;">Participation Details</label>
              </slot>
              <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
            </div>
            <div class="modal-body">
              <slot name="body">
                <div class="form-group">
                  <div class="row">
                    <div class="text-uppercase pb-4">General information:</div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Status: </label>
                      <label v-if="participation1.status != null" class="fw-normal" style="font-size: 14px;">{{ participation1.status }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Presentation Title: </label>
                      <label v-if="participation1.presentationTitle != null" class="fw-normal" style="font-size: 14px;">{{ participation1.presentationTitle }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Type of Participation: </label>
                      <label v-if="participation1.typeOfParticipation != null" class="fw-normal" style="font-size: 14px;">{{ participation1.typeOfParticipation }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Type Event: </label>
                      <label v-if="participation1.typeEvent != null" class="fw-normal" style="font-size: 14px;">{{ participation1.typeEvent }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Event Name: </label>
                      <label v-if="participation1.eventName != null" class="fw-normal" style="font-size: 14px;">{{ participation1.eventName }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Country: </label>
                      <label v-if="participation1.country != null" class="fw-normal" style="font-size: 14px;">{{ participation1.country }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>City: </label>
                      <label v-if="participation1.city != null" class="fw-normal" style="font-size: 14px;">{{ participation1.city }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Start Date: </label>
                      <label v-if="participation1.startDate != null" class="fw-normal" style="font-size: 14px;">{{ participation1.startDate }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Ending Date: </label>
                      <label v-if="participation1.endingDate != null" class="fw-normal" style="font-size: 14px;">{{ participation1.endingDate }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Name of Participants: </label>
                      <label v-if="participation1.nameOfParticipants != null" class="fw-normal" style="font-size: 14px;">{{ participation1.nameOfParticipants }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Progress Report: </label>
                      <label v-if="participation1.progressReport != null" class="fw-normal" style="font-size: 14px;">{{ participation1.progressReport }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Comments: </label>
                      <label v-if="participation1.comments != null" class="fw-normal" style="font-size: 14px;">{{ participation1.comments }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
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
import { mixin } from '../../../../mixins.js'

export default {
  components: { modalconfirmacion, modalalerta },
  mixins: [mixin],
  data: () => ({}),
  props: {
    participation1: Object,
  },
  created() {},
}
</script>
