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
                      <label>Private Sector: </label>
                      <label v-if="postDoc1.privateSector != null" class="fw-normal" style="font-size: 14px;">{{ postDoc1.privateSector }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Academy1: </label>
                      <label v-if="postDoc1.academy1 != null" class="fw-normal" style="font-size: 14px;">{{ postDoc1.academy1 }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Business: </label>
                      <label v-if="postDoc1.business != null" class="fw-normal" style="font-size: 14px;">{{ postDoc1.business }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Own Entrepreneurship: </label>
                      <label v-if="postDoc1.ownEntrepreneurship != null" class="fw-normal" style="font-size: 14px;">{{ postDoc1.ownEntrepreneurship }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Public Sector: </label>
                      <label v-if="postDoc1.publicSector != null" class="fw-normal" style="font-size: 14px;">{{ postDoc1.publicSector }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Government: </label>
                      <label v-if="postDoc1.government != null" class="fw-normal" style="font-size: 14px;">{{ postDoc1.government }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Academy2: </label>
                      <label v-if="postDoc1.academy2 != null" class="fw-normal" style="font-size: 14px;">{{ postDoc1.academy2 }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Social ONG: </label>
                      <label v-if="postDoc1.socialOng != null" class="fw-normal" style="font-size: 14px;">{{ postDoc1.socialOng }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>In the Center: </label>
                      <label v-if="postDoc1.inTheCenter != null" class="fw-normal" style="font-size: 14px;">{{ postDoc1.inTheCenter }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>None of the Above: </label>
                      <label v-if="postDoc1.noneOfTheAbove != null" class="fw-normal" style="font-size: 14px;">{{ postDoc1.noneOfTheAbove }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Institution Name: </label>
                      <label v-if="postDoc1.institutionName != null" class="fw-normal" style="font-size: 14px;">{{ postDoc1.institutionName }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Name of Research: </label>
                      <label v-if="postDoc1.nameOfResearch != null" class="fw-normal" style="font-size: 14px;">{{ postDoc1.nameOfResearch }}</label>
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
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Posterior Working Area: </label>
                      <label v-if="postDoc1.posteriorWorkingArea != null" class="fw-normal" style="font-size: 14px;">{{ postDoc1.posteriorWorkingArea }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Institution Working Area: </label>
                      <label v-if="postDoc1.institutionWorkingArea != null" class="fw-normal" style="font-size: 14px;">{{ postDoc1.institutionWorkingArea }}</label>
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
