<template>
  <transition name="modalver">
    <div name="modal">
      <div class="modal-mask">
        <div class="modal-wrapper">
          <div class="modal-container">
            <div class="modal-header pb-1" style="font-weight: bold; color: #444444;">
              <slot name="header">
                <label class="col-5 m-0" style="font-size: 17px;">Visit And Stay Details</label>
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
                      <label v-if="collaboration1.status != null" class="fw-normal" style="font-size: 14px;">{{ collaboration1.status }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Institution Collaborates: </label>
                      <label v-if="collaboration1.institutionCollaborates != null" class="fw-normal" style="font-size: 14px;">{{ collaboration1.institutionCollaborates }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Researchers Involved: </label>
                      <label v-if="collaboration1.researcherInvolved != null" class="fw-normal" style="font-size: 14px;">{{ collaboration1.researcherInvolved }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Activity Type: </label>
                      <label v-if="collaboration1.activityType != null" class="fw-normal" style="font-size: 14px;">{{ collaboration1.activityType }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Activity Name: </label>
                      <label v-if="collaboration1.activityName != null" class="fw-normal" style="font-size: 14px;">{{ collaboration1.activityName }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Country Origin: </label>
                      <label v-if="collaboration1.countryOrigin != null" class="fw-normal" style="font-size: 14px;">{{ collaboration1.countryOrigin }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>City Origin: </label>
                      <label v-if="collaboration1.cityOrigin != null" class="fw-normal" style="font-size: 14px;">{{ collaboration1.cityOrigin }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Country Destination: </label>
                      <label v-if="collaboration1.countryDestination != null" class="fw-normal" style="font-size: 14px;">{{ collaboration1.countryDestination }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>City Destination: </label>
                      <label v-if="collaboration1.cityDestination != null" class="fw-normal" style="font-size: 14px;">{{ collaboration1.cityDestination }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Beginning Date: </label>
                      <label v-if="collaboration1.beginningDate != null" class="fw-normal" style="font-size: 14px;">{{ collaboration1.beginningDate }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Ending Date: </label>
                      <label v-if="collaboration1.endingDate != null" class="fw-normal" style="font-size: 14px;">{{ collaboration1.endingDate }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Progress Report: </label>
                      <label v-if="collaboration1.progressReport != null" class="fw-normal" style="font-size: 14px;">{{ collaboration1.progressReport }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Name of External Researcher: </label>
                      <label v-if="collaboration1.nameOfExternalResearcher != null" class="fw-normal" style="font-size: 14px;">{{ collaboration1.nameOfExternalResearcher }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Collaboration Stay: </label>
                      <label v-if="collaboration1.collaborationStay != null" class="fw-normal" style="font-size: 14px;">{{ collaboration1.collaborationStay }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Comments: </label>
                      <label v-if="collaboration1.comments != null" class="fw-normal" style="font-size: 14px;">{{ collaboration1.comments }}</label>
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
    collaboration1: Object,
  },
  created() {},
}
</script>
