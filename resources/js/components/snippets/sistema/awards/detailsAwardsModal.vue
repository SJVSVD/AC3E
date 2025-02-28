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
                    <label class="fw-normal" style="font-size: 14px;">{{ award1.awardeeName || '---' }}</label>
                  </div>
                  <div class="col-md-6 m-0 fs-8 pb-1">
                    <label>Award Name: </label>
                    <label class="fw-normal" style="font-size: 14px;">{{ award1.awardName || '---' }}</label>
                  </div>
                  <label class="col-md-10 m-0 fs-8 pb-1"> <label for="">Researchers Involved:</label> 
                    <label class="fw-normal" style="font-size: 14px;">{{ award1.researcherInvolved || '---' }}</label>
                  </label>
                  <label class="col-md-10 m-0 fs-8 pb-1"> <label for="">Research lines Involved:</label> 
                    <label class="fw-normal" style="font-size: 14px;">{{ award1.researchLinesInvolved || '---' }}</label>
                  </label>
                  <div class="col-md-6 m-0 fs-8 pb-1">
                    <label>Year: </label>
                    <label class="fw-normal" style="font-size: 14px;">{{ award1.year || '---' }}</label>
                  </div>
                  <div class="col-md-6 m-0 fs-8 pb-1">
                    <label>Month: </label>
                    <label class="fw-normal" style="font-size: 14px;">{{ award1.month || '---' }}</label>
                  </div>
                  <div class="col-md-6 m-0 fs-8 pb-1">
                    <label>Contribution Awardee: </label>
                    <label class="fw-normal" style="font-size: 14px;">{{ award1.contributionAwardee || '---' }}</label>
                  </div>
                  <div class="col-md-6 m-0 fs-8 pb-1">
                    <label>Institution: </label>
                    <label class="fw-normal" style="font-size: 14px;">{{ award1.institution || '---' }}</label>
                  </div>
                  <div class="col-md-6 m-0 fs-8 pb-1">
                    <label>Country: </label>
                    <label class="fw-normal" style="font-size: 14px;">{{ award1.country || '---' }}</label>
                  </div>
                  <div class="col-md-6 m-0 fs-8 pb-1">
                    <label>Progress Report Year: </label>
                    <label class="fw-normal" style="font-size: 14px;">{{ award1.progressReport || '---' }}</label>
                  </div>
                  <div class="col-md-6 m-0 fs-8 pb-1">
                    <label>Comments: </label>
                    <label class="fw-normal" style="font-size: 14px;">{{ award1.comments || '---' }}</label>
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
