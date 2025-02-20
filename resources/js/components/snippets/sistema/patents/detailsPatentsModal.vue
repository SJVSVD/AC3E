<template>
  <transition name="modalver">
    <div name="modal">
      <div class="modal-mask">
        <div class="modal-wrapper">
          <div class="modal-container">
            <div class="modal-header pb-1" style="font-weight: bold; color: #444444;">
              <slot name="header">
                <label class="col-5 m-0" style="font-size: 17px;">Intellectual Property Details</label>
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
                      <label v-if="patent1.status != null" class="fw-normal" style="font-size: 14px;">{{ patent1.status }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>IP Type: </label>
                      <label v-if="patent1.ipType != null" class="fw-normal" style="font-size: 14px;">{{ patent1.ipType }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Authors: </label>
                      <label v-if="patent1.authors != null" class="fw-normal" style="font-size: 14px;">{{ patent1.authors }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Institution Owner: </label>
                      <label v-if="patent1.institutionOwner != null" class="fw-normal" style="font-size: 14px;">{{ patent1.institutionOwner }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Country of Registration: </label>
                      <label v-if="patent1.countryOfRegistration != null" class="fw-normal" style="font-size: 14px;">{{ patent1.countryOfRegistration }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Application Date: </label>
                      <label v-if="patent1.applicationDate != null" class="fw-normal" style="font-size: 14px;">{{ this.thisDate(patent1.applicationDate) }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Grant Date: </label>
                      <label v-if="patent1.grantDate != null" class="fw-normal" style="font-size: 14px;">{{ this.thisDate(patent1.grantDate) }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Application Status: </label>
                      <label v-if="patent1.applicationStatus != null" class="fw-normal" style="font-size: 14px;">{{ patent1.applicationStatus }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Registration Number: </label>
                      <label v-if="patent1.registrationNumber != null" class="fw-normal" style="font-size: 14px;">{{ patent1.registrationNumber }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Application Granted: </label>
                      <label v-if="patent1.applicationGranted != null" class="fw-normal" style="font-size: 14px;">{{ patent1.applicationGranted }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Name of Patent: </label>
                      <label v-if="patent1.nameOfPatent != null" class="fw-normal" style="font-size: 14px;">{{ patent1.nameOfPatent }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Researchers Involved: </label>
                      <label v-if="patent1.researcherInvolved != null" class="fw-normal" style="font-size: 14px;">{{ patent1.researcherInvolved }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Comments: </label>
                      <label v-if="patent1.comments != null" class="fw-normal" style="font-size: 14px;">{{ patent1.comments }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Progress Report Year: </label>
                      <label v-if="patent1.progressReport != null" class="fw-normal" style="font-size: 14px;">{{ patent1.progressReport }}</label>
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
    patent1: Object,
  },
  created() {},
}
</script>
