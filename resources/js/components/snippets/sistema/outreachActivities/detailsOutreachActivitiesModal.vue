<template>
  <transition name="modalver">
    <div name="modal">
      <div class="modal-mask">
        <div class="modal-wrapper">
          <div class="modal-container">
            <div class="modal-header pb-1" style="font-weight: bold; color: #444444;">
              <slot name="header">
                <label class="col-5 m-0" style="font-size: 17px;">Activity Details</label>
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
                      <label v-if="activity1.status != null" class="fw-normal" style="font-size: 14px;">{{ activity1.status }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Type of Activity: </label>
                      <label v-if="activity1.activityType != null" class="fw-normal" style="font-size: 14px;">{{ activity1.activityType }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Activity Name: </label>
                      <label v-if="activity1.activityName != null" class="fw-normal" style="font-size: 14px;">{{ activity1.activityName }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Activity Description: </label>
                      <label v-if="activity1.activityDescription != null" class="fw-normal" style="font-size: 14px;">{{ activity1.activityDescription }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Date: </label>
                      <label v-if="activity1.date != null" class="fw-normal" style="font-size: 14px;">{{ activity1.date }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Attendants Amount: </label>
                      <label v-if="activity1.attendantsAmount != null" class="fw-normal" style="font-size: 14px;">{{ activity1.attendantsAmount }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Duration: </label>
                      <label v-if="activity1.duration != null" class="fw-normal" style="font-size: 14px;">{{ activity1.duration }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Country: </label>
                      <label v-if="activity1.country != null" class="fw-normal" style="font-size: 14px;">{{ activity1.country }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Place Region: </label>
                      <label v-if="activity1.placeRegion != null" class="fw-normal" style="font-size: 14px;">{{ activity1.placeRegion }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>City: </label>
                      <label v-if="activity1.city != null" class="fw-normal" style="font-size: 14px;">{{ activity1.city }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Undergraduate Students: </label>
                      <label v-if="activity1.undergraduateStudents != null" class="fw-normal" style="font-size: 14px;">{{ activity1.undergraduateStudents }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Primary Education Students: </label>
                      <label v-if="activity1.primaryEducationStudents != null" class="fw-normal" style="font-size: 14px;">{{ activity1.primaryEducationStudents }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Secondary Education Students: </label>
                      <label v-if="activity1.secondaryEducationStudents != null" class="fw-normal" style="font-size: 14px;">{{ activity1.secondaryEducationStudents }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>General Community: </label>
                      <label v-if="activity1.generalCommunity != null" class="fw-normal" style="font-size: 14px;">{{ activity1.generalCommunity }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Companies, Industries, Services: </label>
                      <label v-if="activity1.companiesIndustriesServices != null" class="fw-normal" style="font-size: 14px;">{{ activity1.companiesIndustriesServices }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>School Teachers: </label>
                      <label v-if="activity1.schoolTeachers != null" class="fw-normal" style="font-size: 14px;">{{ activity1.schoolTeachers }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Government Official: </label>
                      <label v-if="activity1.governmentOfficial != null" class="fw-normal" style="font-size: 14px;">{{ activity1.governmentOfficial }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Other: </label>
                      <label v-if="activity1.other != null" class="fw-normal" style="font-size: 14px;">{{ activity1.other }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Name of the Main Responsible: </label>
                      <label v-if="activity1.nameOfTheMainResponsible != null" class="fw-normal" style="font-size: 14px;">{{ activity1.nameOfTheMainResponsible }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Progress Report: </label>
                      <label v-if="activity1.progressReport != null" class="fw-normal" style="font-size: 14px;">{{ activity1.progressReport }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Researcher Involved: </label>
                      <label v-if="activity1.researcherInvolved != null" class="fw-normal" style="font-size: 14px;">{{ activity1.researcherInvolved }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>External Researcher: </label>
                      <label v-if="activity1.externalResearcher != null" class="fw-normal" style="font-size: 14px;">{{ activity1.externalResearcher }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Responsibility: </label>
                      <label v-if="activity1.responsibility != null" class="fw-normal" style="font-size: 14px;">{{ activity1.responsibility }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Comments: </label>
                      <label v-if="activity1.comments != null" class="fw-normal" style="font-size: 14px;">{{ activity1.comments }}</label>
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
    activity1: Object,
  },
  created() {},
}
</script>
