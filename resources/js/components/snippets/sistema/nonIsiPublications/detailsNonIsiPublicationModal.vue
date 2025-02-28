<template>
  <transition name="modalver">
    <div name="modal">
      <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container">
              <div class="modal-header pb-1" style="font-weight: bold; color: #444444;">
                <slot name="header">
                  <label class="col-5 m-0" style="font-size: 17px;">Non WoS Publication Details: </label>
                  <label class="col-5 m-0 fs-8"> User: <label class="fw-normal" style="font-size: 14px;"></label>{{ nonIsiPublication1.usuario.name }}</label>
                </slot>
                <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
              </div>
              <div class="modal-body">
                <slot name="body">
                  <div class="form-group">
                    <div class="row">
                      <div class="text-uppercase pb-4">General information:</div>
                      <label class="col-md-4 m-0 fs-8 pb-1"> Status: <label class="fw-normal" style="font-size: 14px;">{{ nonIsiPublication1.status }}</label></label>
                      <label class="col-md-8 m-0 fs-8 pb-1"> Authors: <label v-if="nonIsiPublication1.authors != null" class="fw-normal" style="font-size: 14px;">{{ nonIsiPublication1.authors }}</label><label v-else class="fw-normal" style="font-size: 14px;">---</label></label>
                      <label class="col-md-10 m-0 fs-8 pb-1"> Researchers Involved: <label v-if="nonIsiPublication1.researcherInvolved != null" class="fw-normal" style="font-size: 14px;">{{ nonIsiPublication1.researcherInvolved }}</label><label v-else class="fw-normal" style="font-size: 14px;">---</label></label>
                      <label class="col-md-10 m-0 fs-8 pb-1"> Research lines Involved: <label v-if="nonIsiPublication1.researchLinesInvolved != null" class="fw-normal" style="font-size: 14px;">{{ nonIsiPublication1.researchLinesInvolved }}</label><label v-else class="fw-normal" style="font-size: 14px;">---</label></label>
                      <label class="col-md-10 m-0 fs-8 pb-1"> Article Title: <label v-if="nonIsiPublication1.articleTitle != null" class="fw-normal" style="font-size: 14px;">{{ nonIsiPublication1.articleTitle }}</label><label v-else class="fw-normal" style="font-size: 14px;">---</label></label>
                      <label class="col-md-10 m-0 fs-8 pb-1"> Journal Name: <label v-if="nonIsiPublication1.journalName != null" class="fw-normal" style="font-size: 14px;">{{ nonIsiPublication1.journalName }}</label><label v-else class="fw-normal" style="font-size: 14px;">---</label></label>
                      <label class="col-md-3 m-0 fs-8 pb-1"> Volume: <label v-if="nonIsiPublication1.volume != null" class="fw-normal" style="font-size: 14px;">{{ nonIsiPublication1.volume }}</label><label v-else class="fw-normal" style="font-size: 14px;">---</label></label>
                      <label class="col-md-3 m-0 fs-8 pb-1"> First Page: <label v-if="nonIsiPublication1.firstPage != null" class="fw-normal" style="font-size: 14px;">{{ nonIsiPublication1.firstPage }}</label><label v-else class="fw-normal" style="font-size: 14px;">---</label></label>
                      <label class="col-md-3 m-0 fs-8 pb-1"> Last Page: <label v-if="nonIsiPublication1.lastPage != null" class="fw-normal" style="font-size: 14px;">{{ nonIsiPublication1.lastPage }}</label><label v-else class="fw-normal" style="font-size: 14px;">---</label></label>
                      <label class="col-md-3 m-0 fs-8 pb-1"> Year Published: <label v-if="nonIsiPublication1.yearPublished != null" class="fw-normal" style="font-size: 14px;">{{ nonIsiPublication1.yearPublished }}</label><label v-else class="fw-normal" style="font-size: 14px;">---</label></label>
                      <label class="col-md-3 m-0 fs-8 pb-1"> Month: <label v-if="nonIsiPublication1.month != null" class="fw-normal" style="font-size: 14px;">{{ nonIsiPublication1.month }}</label><label v-else class="fw-normal" style="font-size: 14px;">---</label></label>
                      <label class="col-md-9 m-0 fs-8 pb-1"> Funding: <label v-if="nonIsiPublication1.funding != null" class="fw-normal" style="font-size: 14px;">{{ nonIsiPublication1.funding }}</label><label v-else class="fw-normal" style="font-size: 14px;">---</label></label>
                      <label class="col-md-6 m-0 fs-8 pb-1"> Indexed by: <label v-if="nonIsiPublication1.indexedBy != null" class="fw-normal" style="font-size: 14px;">{{ nonIsiPublication1.indexedBy }}</label><label v-else class="fw-normal" style="font-size: 14px;">---</label></label>
                      <label class="col-md-6 m-0 fs-8 pb-1"> Acceptance Date: <label v-if="nonIsiPublication1.acceptanceDate != null" class="fw-normal" style="font-size: 14px;">{{ nonIsiPublication1.acceptanceDate }}</label><label v-else class="fw-normal" style="font-size: 14px;">---</label></label>
                      <label class="col-md-6 m-0 fs-8 pb-1"> Comments: <label v-if="nonIsiPublication1.comments != null" class="fw-normal" style="font-size: 14px;">{{ nonIsiPublication1.comments }}</label><label v-else class="fw-normal" style="font-size: 14px;">---</label></label>
                      <label class="col-md-6 m-0 fs-8 pb-1"> Progress Report Year: <label class="fw-normal" style="font-size: 14px;">{{ nonIsiPublication1.progressReport }}</label></label>
                      <!-- <label class="col-4 m-0 fs-8 pb-1"> Student Name: <label class="fw-normal" style="font-size: 14px;"></label>{{ thesisStudent1.studentName }}</label>
                      <label class="col-4 m-0 fs-8 pb-1"> Run or Passport: <label class="fw-normal" style="font-size: 14px;"></label>{{ thesisStudent1.runOrPassport }}</label>
                      <label class="col-4 m-0 fs-8 pb-1"> Gender: <label class="fw-normal" style="font-size: 14px;"></label>{{ thesisStudent1.gender }}</label>
                      <label class="col-4 m-0 fs-8 pb-2"> Student Mail: <label class="fw-normal" style="font-size: 14px;"></label>{{ thesisStudent1.studentMail }}</label>
                      <hr size="4" class="separador">
                      <div class="text-uppercase pb-2">Thesis information:</div>
                      <label class="col-6 m-0 fs-8 pb-1"> Thesis Status: <label class="fw-normal" style="font-size: 14px;"></label>{{ thesisStudent1.thesisStatus }}</label>
                      <label class="col-6 m-0 fs-8 pb-1"> Thesis Title: <label class="fw-normal" style="font-size: 14px;"></label>{{ thesisStudent1.thesisTitle }}</label>
                      <label class="col-6 m-0 fs-8 pb-1"> Academic Degree: <label class="fw-normal" style="font-size: 14px;"></label>{{ thesisStudent1.academicDegree }}</label>
                      <label class="col-6 m-0 fs-8 pb-2"> Degree Denomination: <label class="fw-normal" style="font-size: 14px;"></label>{{ thesisStudent1.degreeDenomination }}</label>
                      <hr v-if="thesisStudent1.tutorName != null" size="4" class="separador">
                      <div v-if="thesisStudent1.tutorName != null" class="text-uppercase pb-2">Tutor information:</div>
                      <label v-if="thesisStudent1.tutorName != null" class="col-6 m-0 fs-8 pb-1"> Tutor Name: <label class="fw-normal" style="font-size: 14px;"></label>{{ thesisStudent1.tutorName }}</label>
                      <label v-if="thesisStudent1.tutorName != null" class="col-6 m-0 fs-8 pb-2 "> Tutor Institution: <label class="fw-normal" style="font-size: 14px;"></label>{{ thesisStudent1.tutorInstitution }}</label>
                      <hr v-if="thesisStudent1.cotutorName != null" size="4" class="separador">
                      <div v-if="thesisStudent1.cotutorName != null" class="text-uppercase pb-2">Cotutor information:</div>
                      <label v-if="thesisStudent1.cotutorName != null" class="col-6 m-0 fs-8 pb-1"> Cotutor Name: <label class="fw-normal" style="font-size: 14px;"></label>{{ thesisStudent1.cotutorName }}</label>
                      <label v-if="thesisStudent1.cotutorName != null" class="col-6 m-0 fs-8 pb-2"> Cotutor Institution: <label class="fw-normal" style="font-size: 14px;"></label>{{ thesisStudent1.cotutorInstitution }}</label>
                      <hr v-if="thesisStudent1.otherName != null" size="4" class="separador">
                      <div v-if="thesisStudent1.otherName != null" class="text-uppercase pb-2">Other information:</div>
                      <label v-if="thesisStudent1.otherName != null" class="col-6 m-0 fs-8 pb-1"> Other Name: <label class="fw-normal" style="font-size: 14px;"></label>{{ thesisStudent1.otherName }}</label>
                      <label v-if="thesisStudent1.otherName != null" class="col-6 m-0 fs-8 pb-2"> Other Institution: <label class="fw-normal" style="font-size: 14px;"></label>{{ thesisStudent1.otherInstitution }}</label>
                      <hr size="4" class="separador">
                      <div class="text-uppercase pb-2">Extra information:</div>
                      <label class="col-4 m-0 fs-8 pb-1"> University: <label class="fw-normal" style="font-size: 14px;"></label>{{ thesisStudent1.university }}</label>
                      <label class="col-4 m-0 fs-8 pb-1"> Year in which student starts program: <label class="fw-normal" style="font-size: 14px;"></label>{{ thesisStudent1.yearStart }}</label>
                      <label class="col-4 m-0 fs-8 pb-1"> Year in which the thesis ends: <label class="fw-normal" style="font-size: 14px;"></label>{{ thesisStudent1.yearThesisEnd }}</label>
                      <label class="col-6 m-0 fs-8 pb-1"> Resources provided by the Center: <label class="fw-normal" style="font-size: 14px;"></label>{{ thesisStudent1.resourcesCenter }}</label>
                      <label class="col-6 m-0 fs-8 pb-1"> Posterior working area: <label class="fw-normal" style="font-size: 14px;"></label>{{ thesisStudent1.posteriorArea }}</label>
                      <label class="col-6 m-0 fs-8 pb-1"> Institution of Posterior working area: <label class="fw-normal" style="font-size: 14px;"></label>{{ thesisStudent1.institutionPosteriorArea }}</label>
                      <label class="col-6 m-0 fs-8 pb-2"> Comments: <label class="fw-normal" style="font-size: 14px;"></label>{{ thesisStudent1.comments }}</label> -->
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
    nonIsiPublication1: Object,
  },
  created(){
  },

}
</script>