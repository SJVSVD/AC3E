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

                    <!-- Status -->
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Status: </label>
                      <label class="fw-normal" style="font-size: 14px;">
                        {{ activity1.status || '---' }}
                      </label>
                    </div>

                    <!-- Researchers -->
                    <label class="col-md-10 m-0 fs-8 pb-1">
                      <label>Researchers Involved:</label>
                      <label class="fw-normal" style="font-size: 14px;">
                        {{ activity1.researcherInvolved || '---' }}
                      </label>
                    </label>

                    <!-- Research lines -->
                    <label class="col-md-10 m-0 fs-8 pb-1">
                      <label>Research lines Involved:</label>
                      <label class="fw-normal" style="font-size: 14px;">
                        {{ activity1.researchLinesInvolved || '---' }}
                      </label>
                    </label>

                    <!-- Type -->
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Type of Activity: </label>
                      <label class="fw-normal" style="font-size: 14px;">
                        {{ activity1.activityType || '---' }}
                      </label>
                    </div>

                    <!-- Activity fields (solo si NO es Outreach Material) -->
                    <template v-if="activity1.activityType !== 'Outreach Material'">
                      <div class="col-6 m-0 fs-8 pb-1">
                        <label>Activity Name: </label>
                        <label class="fw-normal" style="font-size: 14px;">
                          {{ activity1.activityName || '---' }}
                        </label>
                      </div>
                      <div class="col-6 m-0 fs-8 pb-1">
                        <label>Activity Description: </label>
                        <label class="fw-normal" style="font-size: 14px;">
                          {{ activity1.activityDescription || '---' }}
                        </label>
                      </div>
                      <div class="col-6 m-0 fs-8 pb-1">
                        <label>Date: </label>
                        <label class="fw-normal" style="font-size: 14px;">
                          {{ activity1.date ? thisDate(activity1.date) : '---' }}
                        </label>
                      </div>
                      <div class="col-6 m-0 fs-8 pb-1">
                        <label>Attendants Amount: </label>
                        <label class="fw-normal" style="font-size: 14px;">
                          {{ activity1.attendantsAmount ?? '---' }}
                        </label>
                      </div>
                      <div class="col-6 m-0 fs-8 pb-1">
                        <label>Duration: </label>
                        <label class="fw-normal" style="font-size: 14px;">
                          {{ activity1.duration || '---' }}
                        </label>
                      </div>
                      <div class="col-6 m-0 fs-8 pb-1">
                        <label>Country: </label>
                        <label class="fw-normal" style="font-size: 14px;">
                          {{ activity1.country || '---' }}
                        </label>
                      </div>
                      <div class="col-6 m-0 fs-8 pb-1">
                        <label>Place Region: </label>
                        <label class="fw-normal" style="font-size: 14px;">
                          {{ activity1.placeRegion || '---' }}
                        </label>
                      </div>
                      <div class="col-6 m-0 fs-8 pb-1">
                        <label>City: </label>
                        <label class="fw-normal" style="font-size: 14px;">
                          {{ activity1.city || '---' }}
                        </label>
                      </div>
                    </template>

                    <!-- Outreach Material fields -->
                    <template v-else>
                      <div class="col-6 m-0 fs-8 pb-1">
                        <label>Type of Material: </label>
                        <label class="fw-normal" style="font-size: 14px;">
                          {{ activity1.materialType || '---' }}
                        </label>
                      </div>
                      <div class="col-6 m-0 fs-8 pb-1">
                        <label>Name of Outreach Material: </label>
                        <label class="fw-normal" style="font-size: 14px;">
                          {{ activity1.materialName || '---' }}
                        </label>
                      </div>
                      <div class="col-6 m-0 fs-8 pb-1">
                        <label>Date of Publication: </label>
                        <label class="fw-normal" style="font-size: 14px;">
                          {{ activity1.publicationDate ? thisDate(activity1.publicationDate) : '---' }}
                        </label>
                      </div>
                      <div class="col-6 m-0 fs-8 pb-1">
                        <label>Mean of Publication: </label>
                        <label class="fw-normal" style="font-size: 14px;">
                          {{ activity1.publicationMean || '---' }}
                        </label>
                      </div>
                      <div v-if="activity1.publicationMean === 'Other'" class="col-6 m-0 fs-8 pb-1">
                        <label>Other Mean: </label>
                        <label class="fw-normal" style="font-size: 14px;">
                          {{ activity1.publicationOther || '---' }}
                        </label>
                      </div>
                      <div class="col-6 m-0 fs-8 pb-1">
                        <label>Downloads: </label>
                        <label class="fw-normal" style="font-size: 14px;">
                          {{ activity1.downloads ?? '---' }}
                        </label>
                      </div>
                      <div class="col-6 m-0 fs-8 pb-1">
                        <label>Twitter Mentions: </label>
                        <label class="fw-normal" style="font-size: 14px;">
                          {{ activity1.twitterMentions ?? '---' }}
                        </label>
                      </div>
                      <div class="col-6 m-0 fs-8 pb-1">
                        <label>Facebook Mentions: </label>
                        <label class="fw-normal" style="font-size: 14px;">
                          {{ activity1.facebookMentions ?? '---' }}
                        </label>
                      </div>
                      <div class="col-6 m-0 fs-8 pb-1">
                        <label>Other Mentions: </label>
                        <label class="fw-normal" style="font-size: 14px;">
                          {{ activity1.otherMentions ?? '---' }}
                        </label>
                      </div>
                    </template>

                    <!-- Target Audiences -->
                    <div class="text-uppercase pt-3">Target Audiences:</div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Undergraduate Students: </label>
                      <label class="fw-normal" style="font-size: 14px;">
                        {{ activity1.undergraduateStudents === 1 ? 'Yes' : (activity1.undergraduateStudents === 0 ? 'No' : '---') }}
                      </label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Primary Education Students: </label>
                      <label class="fw-normal" style="font-size: 14px;">
                        {{ activity1.primaryEducationStudents === 1 ? 'Yes' : (activity1.primaryEducationStudents === 0 ? 'No' : '---') }}
                      </label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Secondary Education Students: </label>
                      <label class="fw-normal" style="font-size: 14px;">
                        {{ activity1.secondaryEducationStudents === 1 ? 'Yes' : (activity1.secondaryEducationStudents === 0 ? 'No' : '---') }}
                      </label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>General Community: </label>
                      <label class="fw-normal" style="font-size: 14px;">
                        {{ activity1.generalCommunity === 1 ? 'Yes' : (activity1.generalCommunity === 0 ? 'No' : '---') }}
                      </label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Companies, Industries, Services: </label>
                      <label class="fw-normal" style="font-size: 14px;">
                        {{ activity1.companiesIndustriesServices === 1 ? 'Yes' : (activity1.companiesIndustriesServices === 0 ? 'No' : '---') }}
                      </label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>School Teachers: </label>
                      <label class="fw-normal" style="font-size: 14px;">
                        {{ activity1.schoolTeachers === 1 ? 'Yes' : (activity1.schoolTeachers === 0 ? 'No' : '---') }}
                      </label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Government Official: </label>
                      <label class="fw-normal" style="font-size: 14px;">
                        {{ activity1.governmentOfficial === 1 ? 'Yes' : (activity1.governmentOfficial === 0 ? 'No' : '---') }}
                      </label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Other: </label>
                      <label class="fw-normal" style="font-size: 14px;">
                        {{ activity1.other === 1 ? 'Yes' : (activity1.other === 0 ? 'No' : '---') }}
                      </label>
                    </div>

                    <!-- Progress Report + Responsibility + Comments -->
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Progress Report Year: </label>
                      <label class="fw-normal" style="font-size: 14px;">
                        {{ activity1.progressReport || '---' }}
                      </label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Responsability: </label>
                      <label class="fw-normal" style="font-size: 14px;">
                        {{ activity1.responsability || '---' }}
                      </label>
                    </div>
                    <div class="col-6 m-0 fs-8 pb-1">
                      <label>Comments: </label>
                      <label class="fw-normal" style="font-size: 14px;">
                        {{ activity1.comments || '---' }}
                      </label>
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
