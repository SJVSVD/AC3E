<template>
  <transition name="modalver">
    <div name="modal">
      <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container">
              <div class="modal-header pb-1" style="font-weight: bold; color: #444444;">
                <slot name="header">
                  <label class="col-5 m-0" style="font-size: 17px;">Thesis Details: {{ thesisStudent1.researchLinesInvolved }}</label>
                  <label class="col-5 m-0 fs-8 pt-2"> User: <label class="fw-normal" style="font-size: 14px;"></label>{{ thesisStudent1.usuario.name }}</label>
                </slot>
                <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
              </div>
              <div class="modal-body">
                <slot name="body">
                  <div class="form-group">
                    <div class="row">
                      <div class="text-uppercase pb-2">Personal information:</div>
                      <label class="col-4 m-0 fs-8 pb-1">
                        Student Name:
                        <label class="fw-normal" style="font-size: 14px;">
                          {{ thesisStudent1.studentName || '---' }}
                        </label>
                      </label>
                      <label class="col-4 m-0 fs-8 pb-1">
                        Run or Passport:
                        <label class="fw-normal" style="font-size: 14px;">
                          {{ thesisStudent1.runOrPassport || '---' }}
                        </label>
                      </label>
                      <label class="col-4 m-0 fs-8 pb-1">
                        Gender:
                        <label class="fw-normal" style="font-size: 14px;">
                          {{ thesisStudent1.gender || '---' }}
                        </label>
                      </label>
                      <label class="col-4 m-0 fs-8 pb-2">
                        Student Mail:
                        <label class="fw-normal" style="font-size: 14px;">
                          {{ thesisStudent1.studentMail || '---' }}
                        </label>
                      </label>
                      <hr size="4" class="separador">
                      <div class="text-uppercase pb-2">Thesis information:</div>
                      <label class="col-6 m-0 fs-8 pb-1">
                        Thesis Status:
                        <label class="fw-normal" style="font-size: 14px;">
                          {{ thesisStudent1.thesisStatus || '---' }}
                        </label>
                      </label>
                      <label class="col-6 m-0 fs-8 pb-1">
                        Thesis Title:
                        <label class="fw-normal" style="font-size: 14px;">
                          {{ thesisStudent1.thesisTitle || '---' }}
                        </label>
                      </label>
                      <label class="col-6 m-0 fs-8 pb-1">
                        Academic Degree:
                        <label class="fw-normal" style="font-size: 14px;">
                          {{ thesisStudent1.academicDegree || '---' }}
                        </label>
                      </label>
                      <label class="col-6 m-0 fs-8 pb-2">
                        Degree Denomination:
                        <label class="fw-normal" style="font-size: 14px;">
                          {{ thesisStudent1.degreeDenomination || '---' }}
                        </label>
                      </label>
                      <hr v-if="thesisStudent1.tutorName != null" size="4" class="separador">

                      <div v-if="thesisStudent1.tutorName != null" class="text-uppercase pb-2">Tutor information:</div>
                      <label v-if="thesisStudent1.tutorName != null" class="col-6 m-0 fs-8 pb-1">
                        Tutor Name:
                        <label class="fw-normal" style="font-size: 14px;">
                          {{ thesisStudent1.tutorName || '---' }}
                        </label>
                      </label>
                      <label v-if="thesisStudent1.tutorName != null" class="col-6 m-0 fs-8 pb-2 ">
                        Tutor Institution:
                        <label class="fw-normal" style="font-size: 14px;">
                          {{ thesisStudent1.tutorInstitution || '---' }}
                        </label>
                      </label>
                      <hr v-if="thesisStudent1.cotutorName != null" size="4" class="separador">

                      <div v-if="thesisStudent1.cotutorName != null" class="text-uppercase pb-2">Cotutor information:</div>
                      <label v-if="thesisStudent1.cotutorName != null" class="col-6 m-0 fs-8 pb-1">
                        Cotutor Name:
                        <label class="fw-normal" style="font-size: 14px;">
                          {{ thesisStudent1.cotutorName || '---' }}
                        </label>
                      </label>
                      <label v-if="thesisStudent1.cotutorName != null" class="col-6 m-0 fs-8 pb-2">
                        Cotutor Institution:
                        <label class="fw-normal" style="font-size: 14px;">
                          {{ thesisStudent1.cotutorInstitution || '---' }}
                        </label>
                      </label>
                      <hr v-if="thesisStudent1.otherName != null" size="4" class="separador">

                      <div v-if="thesisStudent1.otherName != null" class="text-uppercase pb-2">Other information:</div>
                      <label v-if="thesisStudent1.otherName != null" class="col-6 m-0 fs-8 pb-1">
                        Other Name:
                        <label class="fw-normal" style="font-size: 14px;">
                          {{ thesisStudent1.otherName || '---' }}
                        </label>
                      </label>
                      <label v-if="thesisStudent1.otherName != null" class="col-6 m-0 fs-8 pb-2">
                        Other Institution:
                        <label class="fw-normal" style="font-size: 14px;">
                          {{ thesisStudent1.otherInstitution || '---' }}
                        </label>
                      </label>
                      <hr size="4" class="separador">

                      <div class="text-uppercase pb-2">Extra information:</div>
                      <label class="col-4 m-0 fs-8 pb-1">
                        University:
                        <label class="fw-normal" style="font-size: 14px;">
                          {{ thesisStudent1.university || '---' }}
                        </label>
                      </label>
                      <label class="col-4 m-0 fs-8 pb-1">
                        Year in which thesis starts:
                        <label class="fw-normal" style="font-size: 14px;">
                          {{ thesisStudent1.yearStart || '---' }}
                        </label>
                      </label>
                      <label class="col-4 m-0 fs-8 pb-1">
                        Year in which the thesis ends:
                        <label class="fw-normal" style="font-size: 14px;">
                          {{ thesisStudent1.yearThesisEnd || '---' }}
                        </label>
                      </label>
                      <label class="col-6 m-0 fs-8 pb-1">
                        Resources provided by the Center:
                        <label class="fw-normal" style="font-size: 14px;">
                          {{ thesisStudent1.resourcesCenter || '---' }}
                        </label>
                      </label>
                      <label class="col-6 m-0 fs-8 pb-1">
                        Posterior working area:
                        <label class="fw-normal" style="font-size: 14px;">
                          {{ thesisStudent1.posteriorArea || '---' }}
                        </label>
                      </label>
                      <label class="col-6 m-0 fs-8 pb-1">
                        Institution of Posterior working area:
                        <label class="fw-normal" style="font-size: 14px;">
                          {{ thesisStudent1.institutionPosteriorArea || '---' }}
                        </label>
                      </label>
                      <label class="col-6 m-0 fs-8 pb-2">
                        Comments:
                        <label class="fw-normal" style="font-size: 14px;">
                          {{ thesisStudent1.comments || '---' }}
                        </label>
                      </label>
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
import {mixin} from '../../../../mixins.js'
export default {
  components: { modalconfirmacion, modalalerta },
  mixins: [mixin],
  data: () => ({
  }),
  props:{
    thesisStudent1: Object,
  },
  created(){
  },

}
</script>