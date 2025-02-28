<template>
  <transition name="modalver">
    <div name="modal">
      <div class="modal-mask">
        <div class="modal-wrapper">
          <div class="modal-container">
            <div class="modal-header pb-1" style="font-weight: bold; color: #444444;">
              <slot name="header">
                <label class="col-5 m-0" style="font-size: 17px;">Public-Private Connection Details</label>
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
                      <label v-if="publicPrivate1.status != null" class="fw-normal" style="font-size: 14px;">{{ publicPrivate1.status }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <label class="col-md-10 m-0 fs-8 pb-1"> <label for="">Researchers Involved:</label> 
                      <label class="fw-normal" style="font-size: 14px;">{{ publicPrivate1.researcherInvolved || '---' }}</label>
                    </label>
                    <label class="col-md-10 m-0 fs-8 pb-1"> <label for="">Research lines Involved:</label> 
                      <label class="fw-normal" style="font-size: 14px;">{{ publicPrivate1.researchLinesInvolved || '---' }}</label>
                    </label>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Name of Activity: </label>
                      <label v-if="publicPrivate1.nameOfActivity != null" class="fw-normal" style="font-size: 14px;">{{ publicPrivate1.nameOfActivity }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Agent Type: </label>
                      <label v-if="publicPrivate1.agentType != null" class="fw-normal" style="font-size: 14px;">{{ publicPrivate1.agentType }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Type of Connection: </label>
                      <label v-if="publicPrivate1.typeOfConnection != null" class="fw-normal" style="font-size: 14px;">{{ publicPrivate1.typeOfConnection }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Place Where Was Executed: </label>
                      <label v-if="publicPrivate1.placeWhereWasExecuted != null" class="fw-normal" style="font-size: 14px;">{{ publicPrivate1.placeWhereWasExecuted }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>International National: </label>
                      <label v-if="publicPrivate1.internationalNational != null" class="fw-normal" style="font-size: 14px;">{{ publicPrivate1.internationalNational }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Participation Public Policies: </label>
                      <label class="fw-normal" style="font-size: 14px;">
                        {{ publicPrivate1.participationPublicPolicies === 1 ? 'Yes' : (publicPrivate1.participationPublicPolicies === 0 ? 'No' : '---') }}
                      </label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Start Date: </label>
                      <label v-if="publicPrivate1.startDate != null" class="fw-normal" style="font-size: 14px;">{{ this.thisDate(publicPrivate1.startDate) }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Ending Date: </label>
                      <label v-if="publicPrivate1.endingDate != null" class="fw-normal" style="font-size: 14px;">{{ this.thisDate(publicPrivate1.endingDate) }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Results Goals: </label>
                      <label v-if="publicPrivate1.resultsGoals != null" class="fw-normal" style="font-size: 14px;">{{ publicPrivate1.resultsGoals }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Name of Organization: </label>
                      <label v-if="publicPrivate1.nameOfOrganization != null" class="fw-normal" style="font-size: 14px;">{{ publicPrivate1.nameOfOrganization }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Country Origin: </label>
                      <label v-if="publicPrivate1.countryOrigin != null" class="fw-normal" style="font-size: 14px;">{{ publicPrivate1.countryOrigin }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Comments: </label>
                      <label v-if="publicPrivate1.comments != null" class="fw-normal" style="font-size: 14px;">{{ publicPrivate1.comments }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Progress Report Year: </label>
                      <label v-if="publicPrivate1.progressReport != null" class="fw-normal" style="font-size: 14px;">{{ publicPrivate1.progressReport }}</label>
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
    publicPrivate1: Object,
  },
  created() {},
}
</script>
