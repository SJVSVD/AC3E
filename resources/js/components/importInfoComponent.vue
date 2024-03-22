<template>
  <div class="container mt-4">
    <div class="row justify-content-center">
      <div class="col-lg-12">
        <div class="card">
          <div class="card-body p-4" style="min-height: 400px;">
            <div class="row" > 
              <div class="col-3">
                <a class="btn btn-search-blue" @click="showIsiPublication = true" :disabled="buttonDisable" style="min-height: 120px; display: flex; justify-content: center; align-items: center;">
                  Isi Publications
                </a>
              </div>
              <div class="col-3">
                <a class="btn btn-search-blue" @click="showNonIsiPublication = true" :disabled="buttonDisable" style="min-height: 120px; display: flex; justify-content: center; align-items: center;">
                  Non Isi Publications
                </a>
              </div>
              <div class="col-3">
                <a class="btn btn-search-blue" @click="showBooks = true" :disabled="buttonDisable" style="min-height: 120px; display: flex; justify-content: center; align-items: center;">
                  Books
                </a>
              </div>
              <div class="col-3">
                <a class="btn btn-search-blue" @click="showAwards = true" :disabled="buttonDisable" style="min-height: 120px; display: flex; justify-content: center; align-items: center;">
                  Awards
                </a>
              </div>
            </div>
            <br>
            <div class="row" > 
              <div class="col-3">
                <a class="btn btn-search-blue" @click="showOrganizationSc = true" :disabled="buttonDisable" style="min-height: 120px; display: flex; justify-content: center; align-items: center;">
                  Organization of Sc Events
                </a>
              </div>
              <div class="col-3">
                <a class="btn btn-search-blue" @click="showParticipationSc = true" :disabled="buttonDisable" style="min-height: 120px; display: flex; justify-content: center; align-items: center;">
                  Participation of Sc Events
                </a>
              </div>
              <div class="col-3">
                <a class="btn btn-search-blue" @click="showScCollaboration = true" :disabled="buttonDisable" style="min-height: 120px; display: flex; justify-content: center; align-items: center;">
                  Sc Collaboration
                </a>
              </div>
              <div class="col-3">
                <a class="btn btn-search-blue" @click="showThesisStudent = true" :disabled="buttonDisable" style="min-height: 120px; display: flex; justify-content: center; align-items: center;">
                  Thesis Student
                </a>
              </div>
            </div>
            <br>
            <div class="row" > 
              <div class="col-4">
                <a class="btn btn-search-blue" @click="showPostDoc = true" :disabled="buttonDisable" style="min-height: 120px; display: flex; justify-content: center; align-items: center;">
                  PostDoc
                </a>
              </div>
              <div class="col-4">
                <a class="btn btn-search-blue" @click="showOutreach = true" :disabled="buttonDisable" style="min-height: 120px; display: flex; justify-content: center; align-items: center;">
                  Outreach
                </a>
              </div>
              <div class="col-4">
                <a class="btn btn-search-blue" @click="showPatents = true" :disabled="buttonDisable" style="min-height: 120px; display: flex; justify-content: center; align-items: center;">
                  Patents
                </a>
              </div>
            </div>
            <br>
            <div class="row" > 
              <div class="col-4">
                <a class="btn btn-search-blue" @click="showPublicPrivate = true" :disabled="buttonDisable" style="min-height: 120px; display: flex; justify-content: center; align-items: center;">
                  Public-Private Connection
                </a>
              </div>
              <div class="col-4">
                <a class="btn btn-search-blue" @click="showTecKnowTransfer = true" :disabled="buttonDisable" style="min-height: 120px; display: flex; justify-content: center; align-items: center;">
                  Tec and know. transfer
                </a>
              </div>
              <div class="col-4">
                <a class="btn btn-search-blue" @click="showFundingSource = true" :disabled="buttonDisable" style="min-height: 120px; display: flex; justify-content: center; align-items: center;">
                  Funding Source
                </a>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
  <modalIsiPublication v-if="showIsiPublication" @close="showIsiPublication = false" ></modalIsiPublication>
  <modalNonIsiPublication v-if="showNonIsiPublication" @close="showNonIsiPublication = false" ></modalNonIsiPublication>
  <modalBook v-if="showBooks" @close="showBooks = false" ></modalBook>
  <modalAward v-if="showAwards" @close="showAwards = false" ></modalAward>
  <modalScOrganization v-if="showOrganizationSc" @close="showOrganizationSc = false" ></modalScOrganization>
  <modalScParticipation v-if="showParticipationSc" @close="showParticipationSc = false" ></modalScParticipation>
  <modalScCollaboration v-if="showScCollaboration" @close="showScCollaboration = false" ></modalScCollaboration>

  <modalThesisStudent v-if="showThesisStudent" @close="showThesisStudent = false" ></modalThesisStudent>
  <modalPostDoc v-if="showPostDoc" @close="showPostDoc = false" ></modalPostDoc>
  <modalOutreach v-if="showOutreach" @close="showOutreach = false" ></modalOutreach>
  <modalPatents v-if="showPatents" @close="showPatents = false" ></modalPatents>
  <modalPublicPrivate v-if="showPublicPrivate" @close="showPublicPrivate = false" ></modalPublicPrivate>
  <modalTecAndKnow v-if="showTecKnowTransfer" @close="showTecKnowTransfer = false" ></modalTecAndKnow>
  <modalFundingSource v-if="showFundingSource" @close="showFundingSource = false" ></modalFundingSource>
</template>

<script>

import {mixin} from '../mixins.js'
import modalIsiPublication from './snippets/sistema/importsData/isiPublicationModal.vue'
import modalNonIsiPublication from './snippets/sistema/importsData/nonIsiPublicationModal.vue'
import modalBook from './snippets/sistema/importsData/bookModal.vue'
import modalAward from './snippets/sistema/importsData/awardModal.vue'
import modalScOrganization from './snippets/sistema/importsData/scOrganizationModal.vue'
import modalScParticipation from './snippets/sistema/importsData/scParticipationModal.vue'
import modalScCollaboration from './snippets/sistema/importsData/scCollaborationModal.vue'

import modalThesisStudent from './snippets/sistema/importsData/thesisStudentModal.vue'
import modalPostDoc from './snippets/sistema/importsData/postDocModal.vue'
import modalOutreach from './snippets/sistema/importsData/outreachModal.vue'
import modalPatents from './snippets/sistema/importsData/patentsModal.vue'
import modalPublicPrivate from './snippets/sistema/importsData/publicPrivateModal.vue'
import modalTecAndKnow from './snippets/sistema/importsData/tecAndKnowModal.vue'
import modalFundingSource from './snippets/sistema/importsData/fundingSourceModal.vue'

export default {
  components: { modalIsiPublication, modalNonIsiPublication, modalBook, modalAward, modalScOrganization, modalScParticipation, modalScCollaboration, modalThesisStudent, modalPostDoc, modalOutreach, modalPatents, modalPublicPrivate, modalTecAndKnow, modalFundingSource},
  mixins: [mixin],
  data() {
    return {
      showIsiPublication: false,
      showNonIsiPublication: false,
      showBooks: false,
      showAwards: false,
      showOrganizationSc: false,
      showParticipationSc: false,
      showScCollaboration: false,
      showThesisStudent: false,
      showPostDoc: false,
      showOutreach: false,
      showPatents: false,
      showPublicPrivate: false,
      showTecKnowTransfer: false,
      showFundingSource: false,
    };
  },
  methods: {

  }
};
</script>