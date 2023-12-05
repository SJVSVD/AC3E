<template>
  <transition name="modalver">
    <div name="modal">
      <div class="modal-mask">
        <div class="modal-wrapper">
          <div class="modal-container">
            <div class="modal-header pb-1" style="font-weight: bold; color: #444444;">
              <slot name="header">
                <label class="col-5 m-0" style="font-size: 17px;">Technology Details</label>
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
                      <label v-if="technology1.status != null" class="fw-normal" style="font-size: 14px;">{{ technology1.status }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Technology Transfer: </label>
                      <label v-if="technology1.technologyTransfer != null" class="fw-normal" style="font-size: 14px;">{{ technology1.technologyTransfer }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Knowledge Transfer: </label>
                      <label v-if="technology1.knowledgeTransfer != null" class="fw-normal" style="font-size: 14px;">{{ technology1.knowledgeTransfer }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Type of Transfer: </label>
                      <label v-if="technology1.typeOfTransfer != null" class="fw-normal" style="font-size: 14px;">{{ technology1.typeOfTransfer }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Name of Beneficiary: </label>
                      <label v-if="technology1.nameOfBeneficiary != null" class="fw-normal" style="font-size: 14px;">{{ technology1.nameOfBeneficiary }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Country: </label>
                      <label v-if="technology1.country != null" class="fw-normal" style="font-size: 14px;">{{ technology1.country }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>City: </label>
                      <label v-if="technology1.city != null" class="fw-normal" style="font-size: 14px;">{{ technology1.city }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Place/Region: </label>
                      <label v-if="technology1.placeRegion != null" class="fw-normal" style="font-size: 14px;">{{ technology1.placeRegion }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Year: </label>
                      <label v-if="technology1.year != null" class="fw-normal" style="font-size: 14px;">{{ technology1.year }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Comments: </label>
                      <label v-if="technology1.comments != null" class="fw-normal" style="font-size: 14px;">{{ technology1.comments }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Progress Report: </label>
                      <label v-if="technology1.progressReport != null" class="fw-normal" style="font-size: 14px;">{{ technology1.progressReport }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Description: </label>
                      <label v-if="technology1.description != null" class="fw-normal" style="font-size: 14px;">{{ technology1.description }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Name of Institution Involved: </label>
                      <label v-if="technology1.nameOfInstitutionInvolved != null" class="fw-normal" style="font-size: 14px;">{{ technology1.nameOfInstitutionInvolved }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Researcher Involved: </label>
                      <label v-if="technology1.researcherInvolved != null" class="fw-normal" style="font-size: 14px;">{{ technology1.researcherInvolved }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>External Researcher: </label>
                      <label v-if="technology1.externalResearcher != null" class="fw-normal" style="font-size: 14px;">{{ technology1.externalResearcher }}</label>
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
    technology1: Object,
  },
  created() {},
}
</script>
