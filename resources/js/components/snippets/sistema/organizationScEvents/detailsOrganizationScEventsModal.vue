<template>
  <transition name="modalver">
    <div name="modal">
      <div class="modal-mask">
        <div class="modal-wrapper">
          <div class="modal-container">
            <div class="modal-header pb-1" style="font-weight: bold; color: #444444;">
              <slot name="header">
                <label class="col-5 m-0" style="font-size: 17px;">Organization Details</label>
              </slot>
              <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
            </div>
            <div class="modal-body">
              <slot name="body">
                <div class="form-group">
                  <div class="row">
                    <div class="text-uppercase pb-4">General information:</div>
                    <div class="col-md-6 m-0 fs-8 pb-1">
                      <label>Status: </label>
                      <label v-if="organization1.status != null" class="fw-normal" style="font-size: 14px;">{{ organization1.status }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <label class="col-md-10 m-0 fs-8 pb-1"> <label for="">Researchers Involved:</label> 
                      <label class="fw-normal" style="font-size: 14px;">{{ organization1.researcherInvolved || '---' }}</label>
                    </label>
                    <label class="col-md-10 m-0 fs-8 pb-1"> <label for="">Research lines Involved:</label> 
                      <label class="fw-normal" style="font-size: 14px;">{{ organization1.researchLinesInvolved || '---' }}</label>
                    </label>
                    <div class="col-md-6 m-0 fs-8 pb-1">
                      <label>Type Event: </label>
                      <label v-if="organization1.typeEvent != null" class="fw-normal" style="font-size: 14px;">{{ organization1.typeEvent }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-md-6 m-0 fs-8 pb-1">
                      <label>Event Name: </label>
                      <label v-if="organization1.eventName != null" class="fw-normal" style="font-size: 14px;">{{ organization1.eventName }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-md-6 m-0 fs-8 pb-1">
                      <label>Country: </label>
                      <label v-if="organization1.country != null" class="fw-normal" style="font-size: 14px;">{{ organization1.country }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-md-6 m-0 fs-8 pb-1">
                      <label>City: </label>
                      <label v-if="organization1.city != null" class="fw-normal" style="font-size: 14px;">{{ organization1.city }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-md-6 m-0 fs-8 pb-1">
                      <label>Start Date: </label>
                      <label v-if="organization1.startDate != null" class="fw-normal" style="font-size: 14px;">{{ this.thisDate(organization1.startDate) }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-md-6 m-0 fs-8 pb-1">
                      <label>Ending Date: </label>
                      <label v-if="organization1.endingDate != null" class="fw-normal" style="font-size: 14px;">{{ this.thisDate(organization1.endingDate) }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-md-6 m-0 fs-8 pb-1">
                      <label>Number Participants: </label>
                      <label v-if="organization1.numberParticipants != null" class="fw-normal" style="font-size: 14px;">{{ organization1.numberParticipants }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-md-6 m-0 fs-8 pb-1">
                      <label>Comments: </label>
                      <label v-if="organization1.comments != null" class="fw-normal" style="font-size: 14px;">{{ organization1.comments }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-md-6 m-0 fs-8 pb-1">
                      <label>Progress Report Year: </label>
                      <label v-if="organization1.progressReport != null" class="fw-normal" style="font-size: 14px;">{{ organization1.progressReport }}</label>
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
    organization1: Object,
  },
  created() {},
}
</script>
