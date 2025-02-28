<template>
  <transition name="modalver">
    <div name="modal">
      <div class="modal-mask">
        <div class="modal-wrapper">
          <div class="modal-container">
            <div class="modal-header pb-1" style="font-weight: bold; color: #444444;">
              <slot name="header">
                <label class="col-5 m-0" style="font-size: 17px;">Postdoc Details</label>
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
                      <label v-if="postDoc1.status != null" class="fw-normal" style="font-size: 14px;">{{ postDoc1.status }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <label class="col-md-10 m-0 fs-8 pb-1"> <label for="">Researchers Involved:</label> 
                      <label class="fw-normal" style="font-size: 14px;">{{ postDoc1.researcherInvolved || '---' }}</label>
                    </label>
                    <label class="col-md-10 m-0 fs-8 pb-1"> <label for="">Research lines Involved:</label> 
                      <label class="fw-normal" style="font-size: 14px;">{{ postDoc1.researchLinesInvolved || '---' }}</label>
                    </label>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Personal Email: </label>
                      <label v-if="postDoc1.personalEmail != null" class="fw-normal" style="font-size: 14px;">{{ postDoc1.personalEmail }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Name of Postdoc: </label>
                      <label v-if="postDoc1.nameOfPostdoc != null" class="fw-normal" style="font-size: 14px;">{{ postDoc1.nameOfPostdoc }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Identification: </label>
                      <label v-if="postDoc1.identification != null" class="fw-normal" style="font-size: 14px;">{{ postDoc1.identification }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Gender: </label>
                      <label v-if="postDoc1.gender != null" class="fw-normal" style="font-size: 14px;">{{ postDoc1.gender }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Research Topic: </label>
                      <label v-if="postDoc1.researchTopic != null" class="fw-normal" style="font-size: 14px;">{{ postDoc1.researchTopic }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Supervisor Name: </label>
                      <label v-if="postDoc1.supervisorName != null" class="fw-normal" style="font-size: 14px;">{{ postDoc1.supervisorName }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Resources Provided: </label>
                      <label v-if="postDoc1.resourcesProvided != null" class="fw-normal" style="font-size: 14px;">{{ postDoc1.resourcesProvided }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Funding Source: </label>
                      <label v-if="postDoc1.fundingSource != null" class="fw-normal" style="font-size: 14px;">{{ postDoc1.fundingSource }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Start Year: </label>
                      <label v-if="postDoc1.startYear != null" class="fw-normal" style="font-size: 14px;">{{ postDoc1.startYear }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Ending Year: </label>
                      <label v-if="postDoc1.endingYear != null" class="fw-normal" style="font-size: 14px;">{{ postDoc1.endingYear }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Institution Name: </label>
                      <label v-if="postDoc1.institutionName != null" class="fw-normal" style="font-size: 14px;">{{ postDoc1.institutionName }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Comments: </label>
                      <label v-if="postDoc1.comments != null" class="fw-normal" style="font-size: 14px;">{{ postDoc1.comments }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Progress Report Year: </label>
                      <label v-if="postDoc1.progressReport != null" class="fw-normal" style="font-size: 14px;">{{ postDoc1.progressReport }}</label>
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
    postDoc1: Object,
  },
  created() {},
}
</script>
