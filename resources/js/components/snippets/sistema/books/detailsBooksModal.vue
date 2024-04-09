<template>
  <transition name="modalver">
    <div name="modal">
      <div class="modal-mask">
        <div class="modal-wrapper">
          <div class="modal-container">
            <div class="modal-header pb-1" style="font-weight: bold; color: #444444;">
              <slot name="header">
                <label class="col-5 m-0" style="font-size: 17px;">Book Details</label>
              </slot>
              <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
            </div>
            <div class="modal-body">
              <slot name="body">
                <div class="form-group">
                  <div class="row">
                    <div class="text-uppercase pb-4">General information:</div>
                    <div class="col-md-6 m-0 fs-8 pb-1">
                      <label>Work Type: </label>
                      <label v-if="book1.workType != null" class="fw-normal" style="font-size: 14px;">{{ book1.workType }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-md-6 m-0 fs-8 pb-1">
                      <label>Book Authors: </label>
                      <label v-if="book1.bookAuthors != null" class="fw-normal" style="font-size: 14px;">{{ book1.bookAuthors }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-md-6 m-0 fs-8 pb-1">
                      <label>Chapter Authors: </label>
                      <label v-if="book1.chapterAuthors != null" class="fw-normal" style="font-size: 14px;">{{ book1.chapterAuthors }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-md-6 m-0 fs-8 pb-1">
                      <label>Book Title: </label>
                      <label v-if="book1.bookTitle != null" class="fw-normal" style="font-size: 14px;">{{ book1.bookTitle }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-md-6 m-0 fs-8 pb-1">
                      <label>Chapter Title: </label>
                      <label v-if="book1.chapterTitle != null" class="fw-normal" style="font-size: 14px;">{{ book1.chapterTitle }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-md-6 m-0 fs-8 pb-1">
                      <label>First Page: </label>
                      <label v-if="book1.firstPage != null" class="fw-normal" style="font-size: 14px;">{{ book1.firstPage }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-md-6 m-0 fs-8 pb-1">
                      <label>Last Page: </label>
                      <label v-if="book1.lastPage != null" class="fw-normal" style="font-size: 14px;">{{ book1.lastPage }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-md-6 m-0 fs-8 pb-1">
                      <label>Editorial City/Country: </label>
                      <label v-if="book1.editorialCityCountry != null" class="fw-normal" style="font-size: 14px;">{{ book1.editorialCityCountry }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-md-6 m-0 fs-8 pb-1">
                      <label>Year: </label>
                      <label v-if="book1.year != null" class="fw-normal" style="font-size: 14px;">{{ book1.year }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-md-6 m-0 fs-8 pb-1">
                      <label>Month: </label>
                      <label v-if="book1.month != null" class="fw-normal" style="font-size: 14px;">{{ book1.month }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-md-6 m-0 fs-8 pb-1">
                      <label>Progress Report: </label>
                      <label v-if="book1.progressReport != null" class="fw-normal" style="font-size: 14px;">{{ book1.progressReport }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-md-6 m-0 fs-8 pb-1">
                      <label>Editors: </label>
                      <label v-if="book1.editors != null" class="fw-normal" style="font-size: 14px;">{{ book1.editors }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-md-6 m-0 fs-8 pb-1">
                      <label>ISBN: </label>
                      <label v-if="book1.ISBN != null" class="fw-normal" style="font-size: 14px;">{{ book1.ISBN }}</label>
                      <label v-else class="fw-normal" style="font-size: 14px;">---</label>
                    </div>
                    <div class="col-md-6 m-0 fs-8 pb-1">
                      <label>Comments: </label>
                      <label v-if="book1.comments != null" class="fw-normal" style="font-size: 14px;">{{ book1.comments }}</label>
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
    book1: Object,
  },
  created() {},
}
</script>
