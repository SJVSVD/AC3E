<template>
  <transition name="modalver">
    <div name="modal">
      <div class="modal-mask">
        <div class="modal-wrapper">
          <div class="modal-container">
            <div class="modal-header pb-1" style="font-weight: bold; color: #444444;">
              <slot name="header">
                <label class="col-5 m-0" style="font-size: 17px;">Funding Source Details {{ fundingSource1.researchLinesInvolved }}</label>
              </slot>
              <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
            </div>
            <div class="modal-body">
              <slot name="body">
                <div class="form-group">
                  <div class="row">
                    <div class="text-uppercase pb-4">General Information:</div>
                    <label class="col-6 m-0 fs-8 pb-1">Type of Sources: <label class="fw-normal" style="font-size: 14px;">{{ fundingSource1.typeSources || '---' }}</label></label>
                    <label class="col-6 m-0 fs-8 pb-1">Name of Institution: <label class="fw-normal" style="font-size: 14px;">{{ fundingSource1.nameOfInstitution || '---' }}</label></label>
                    <label class="col-6 m-0 fs-8 pb-1">Program Contest: <label class="fw-normal" style="font-size: 14px;">{{ fundingSource1.programContest || '---' }}</label></label>
                    <label class="col-6 m-0 fs-8 pb-1">Project Title: <label class="fw-normal" style="font-size: 14px;">{{ fundingSource1.projectTitle || '---' }}</label></label>
                    <label class="col-6 m-0 fs-8 pb-1">Principal Researcher: <label class="fw-normal" style="font-size: 14px;">{{ fundingSource1.principalResearcher || '---' }}</label></label>
                    <label class="col-6 m-0 fs-8 pb-1">Start Date: <label class="fw-normal" style="font-size: 14px;">{{ this.thisDate(fundingSource1.startDate) || '---' }}</label></label>
                    <label class="col-6 m-0 fs-8 pb-1">Finish Date: <label class="fw-normal" style="font-size: 14px;">{{ this.thisDate(fundingSource1.finishDate) || '---' }}</label></label>
                    <label class="col-6 m-0 fs-8 pb-1">Comments: <label class="fw-normal" style="font-size: 14px;">{{ fundingSource1.comments || '---' }}</label></label>
                    <label class="col-6 m-0 fs-8 pb-1">In Cash: <label class="fw-normal" style="font-size: 14px;">{{ formatCurrency(fundingSource1.inCash) || '---' }}</label></label>
                    <label class="col-6 m-0 fs-8 pb-1">Type of Collaboration: <label class="fw-normal" style="font-size: 14px;">{{ fundingSource1.typeOfCollaboration || '---' }}</label></label>
                    <label class="col-6 m-0 fs-8 pb-1">Progress Report Year: <label class="fw-normal" style="font-size: 14px;">{{ fundingSource1.progressReport || '---' }}</label></label>
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
    fundingSource1: Object,
  },
  created() {},
  methods: {
    // Formatea el valor con puntos como separadores de miles
    formatCurrency(value) {
      if (!value) return '';
      return parseInt(value).toLocaleString('de-DE'); // Aplica el formato de miles con puntos
    }
  }
}
</script>
