<template>
  <transition name="modalver">
    <div name="modal">
      <div class="modal-mask">
        <div class="modal-wrapper">
          <div class="modal-container">
            <div class="modal-header pb-1" style="font-weight: bold; color: #444444;">
              <slot name="header">
                <label class="col-5 m-0" style="font-size: 17px;">Award Details</label>
              </slot>
              <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
            </div>
            <div class="modal-body">
              <slot name="body">
                <div class="form-group">
                  <div class="row">
                    <div class="text-uppercase pb-4">General information:</div>
                    <div class="col-md-6 m-0 fs-8 pb-1">
                      <label>Awardee Name: </label>
                      <label v-if="award1.awardeeName != null" class="fw-normal" style="font-size: 14px;">{{ award1.awardeeName }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-md-6 m-0 fs-8 pb-1">
                      <label>Award Name: </label>
                      <label v-if="award1.awardName != null" class="fw-normal" style="font-size: 14px;">{{ award1.awardName }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-md-6 m-0 fs-8 pb-1">
                      <label>Year: </label>
                      <label v-if="award1.year != null" class="fw-normal" style="font-size: 14px;">{{ award1.year }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-md-6 m-0 fs-8 pb-1">
                      <label>Month: </label>
                      <label v-if="award1.month != null" class="fw-normal" style="font-size: 14px;">{{ award1.month }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-md-6 m-0 fs-8 pb-1">
                      <label>Contribution Awardee: </label>
                      <label v-if="award1.contributionAwardee != null" class="fw-normal" style="font-size: 14px;">{{ award1.contributionAwardee }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-md-6 m-0 fs-8 pb-1">
                      <label>Institution: </label>
                      <label v-if="award1.institution != null" class="fw-normal" style="font-size: 14px;">{{ award1.institution }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-md-6 m-0 fs-8 pb-1">
                      <label>Country: </label>
                      <label v-if="award1.country != null" class="fw-normal" style="font-size: 14px;">{{ award1.country }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-md-6 m-0 fs-8 pb-1">
                      <label>Progress Report Year: </label>
                      <label v-if="award1.progressReport != null" class="fw-normal" style="font-size: 14px;">{{ award1.progressReport }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-md-6 m-0 fs-8 pb-1">
                      <label>Comments: </label>
                      <label v-if="award1.comments != null" class="fw-normal" style="font-size: 14px;">{{ award1.comments }}</label>
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
    award1: Object,
  },
  created() {},
}
</script>
