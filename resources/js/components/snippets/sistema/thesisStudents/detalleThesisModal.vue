<template>
  <transition name="modalver">
    <div name="modal">
      <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container">
              <div class="modal-header pb-1" style="font-weight: bold; color: #444444;">
                <slot name="header">
                  <label class="col-5 m-0" style="font-size: 17px;">Thesis Details:</label>
                  <label class="col-5 m-0 fs-8 pt-2"> User: <label class="fw-normal" style="font-size: 12px;"></label>{{ thesisStudent1.usuario.name }}</label>
                </slot>
                <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
              </div>
              <div class="modal-body">
                <slot name="body">
                  <div class="form-group">
                    <div class="row">
                      <div class="text-uppercase pb-4">General information:</div>
                      
                      <label class="col-md-10 m-0 fs-8 pb-1">
                        <label for="">Researchers Involved:</label> 
                        <label class="fw-normal" style="font-size: 14px;">{{ thesisStudent1.researcherInvolved || '---' }}</label>
                      </label>
                      
                      <label class="col-md-10 m-0 fs-8 pb-1">
                        <label for="">Research lines Involved:</label> 
                        <label class="fw-normal" style="font-size: 14px;">{{ thesisStudent1.researchLinesInvolved || '---' }}</label>
                      </label>
                      
                      <label class="col-4 m-0 fs-8 pb-1">
                        <label for="">Student Name:</label>
                        <label class="fw-normal" style="font-size: 14px;">{{ thesisStudent1.studentName || '---' }}</label>
                      </label>

                      <label class="col-4 m-0 fs-8 pb-1">
                        <label for="">Run or Passport:</label>
                        <label class="fw-normal" style="font-size: 14px;">{{ thesisStudent1.runOrPassport || '---' }}</label>
                      </label>

                      <label class="col-4 m-0 fs-8 pb-1">
                        <label for="">Gender:</label>
                        <label class="fw-normal" style="font-size: 14px;">{{ thesisStudent1.gender || '---' }}</label>
                      </label>
                      
                      <label class="col-6 m-0 fs-8 pb-1">
                        <label for="">Thesis Status:</label>
                        <label class="fw-normal" style="font-size: 14px;">
                          {{ thesisStudent1.thesisStatus === "1" ? 'Finished' : (thesisStudent1.thesisStatus === "2" ? 'In progress' : '---') }}
                        </label>
                      </label>

                      <label class="col-6 m-0 fs-8 pb-2">
                        <label for="">Student Mail:</label>
                        <label class="fw-normal" style="font-size: 14px;">{{ thesisStudent1.studentMail || '---' }}</label>
                      </label>



                      <label class="col-12 m-0 fs-8 pb-1">
                        <label for="">Thesis Title:</label>
                        <label class="fw-normal" style="font-size: 14px;">{{ thesisStudent1.thesisTitle || '---' }}</label>
                      </label>

                      <label class="col-6 m-0 fs-8 pb-1">
                        <label for="">Academic Degree:</label>
                        <label class="fw-normal" style="font-size: 14px;">{{ thesisStudent1.academicDegree || '---' }}</label>
                      </label>

                      <label class="col-6 m-0 fs-8 pb-2">
                        <label for="">Degree Denomination:</label>
                        <label class="fw-normal" style="font-size: 14px;">{{ thesisStudent1.degreeDenomination || '---' }}</label>
                      </label>
                      
                      <label class="col-6 m-0 fs-8 pb-1">
                        <label for="">Tutor Name:</label>
                        <label class="fw-normal" style="font-size: 14px;">{{ thesisStudent1.tutorName || '---' }}</label>
                      </label>
                      
                      <label class="col-6 m-0 fs-8 pb-2">
                        <label for="">Tutor Institution:</label>
                        <label class="fw-normal" style="font-size: 14px;">{{ thesisStudent1.tutorInstitution || '---' }}</label>
                      </label>

                      <label class="col-6 m-0 fs-8 pb-1">
                        <label for="">Cotutor Name:</label>
                        <label class="fw-normal" style="font-size: 14px;">{{ thesisStudent1.cotutorName || '---' }}</label>
                      </label>
                      
                      <label class="col-6 m-0 fs-8 pb-2">
                        <label for="">Cotutor Institution:</label>
                        <label class="fw-normal" style="font-size: 14px;">{{ thesisStudent1.cotutorInstitution || '---' }}</label>
                      </label>

                      <label class="col-6 m-0 fs-8 pb-1">
                        <label for="">Other Name:</label>
                        <label class="fw-normal" style="font-size: 14px;">{{ thesisStudent1.otherName || '---' }}</label>
                      </label>
                      
                      <label class="col-6 m-0 fs-8 pb-2">
                        <label for="">Other Institution:</label>
                        <label class="fw-normal" style="font-size: 14px;">{{ thesisStudent1.otherInstitution || '---' }}</label>
                      </label>

                      <label class="col-4 m-0 fs-8 pb-1">
                        <label for="">University:</label>
                        <label class="fw-normal" style="font-size: 14px;">{{ thesisStudent1.university || '---' }}</label>
                      </label>
                      
                      <label class="col-4 m-0 fs-8 pb-1">
                        <label for="">Year in which thesis starts:</label>
                        <label class="fw-normal" style="font-size: 14px;">{{ thesisStudent1.yearStart || '---' }}</label>
                      </label>

                      <label class="col-4 m-0 fs-8 pb-1">
                        <label for="">Year in which the thesis ends:</label>
                        <label class="fw-normal" style="font-size: 14px;">{{ thesisStudent1.yearThesisEnd || '---' }}</label>
                      </label>

                      <label class="col-6 m-0 fs-8 pb-1">
                        <label for="">Resources provided by the Center:</label>
                        <label class="fw-normal" style="font-size: 14px;">{{ thesisStudent1.resourcesCenter || '---' }}</label>
                      </label>

                      <label class="col-6 m-0 fs-8 pb-1">
                        <label for="">Posterior working area:</label>
                        <label class="fw-normal" style="font-size: 14px;">{{ thesisStudent1.posteriorArea || '---' }}</label>
                      </label>

                      <label class="col-6 m-0 fs-8 pb-1">
                        <label for="">Institution of Posterior working area:</label>
                        <label class="fw-normal" style="font-size: 14px;">{{ thesisStudent1.institutionPosteriorArea || '---' }}</label>
                      </label>

                      <label class="col-6 m-0 fs-8 pb-2">
                        <label for="">Comments:</label>
                        <label class="fw-normal" style="font-size: 14px;">{{ thesisStudent1.comments || '---' }}</label>
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