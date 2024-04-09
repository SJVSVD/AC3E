<template>
    <transition name="modalcrear">
      <div name="modal">
        <div class="modal-mask">
            <div class="modal-wrapper">
              <div class="modal-container-s">
                <div class="modal-header pb-1 fw-bold" style="color: #444444;">
                  <slot name="header">
                      Edit cotutors
                  </slot>
                  <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
                </div>
                <div class="modal-body">
                  <slot name="body">
                    <div class="row">
                      <table id="tableDesign" class="table align-items-center mb-0">
                        <thead>
                          <tr>
                            <th class="text-uppercase text-xs font-weight-bolder">ID</th>
                            <th class="text-uppercase text-xs font-weight-bolder">Name</th>
                            <th class="text-uppercase text-xs font-weight-bolder">Institution</th>
                            <th class="text-uppercase text-xs font-weight-bolder">Actions</th>
                          </tr>
                        </thead>
                        <tbody>
                          <tr v-if="cotutors1.length == 0">
                            <td colspan="10"> No information available. </td>
                          </tr>
                          <tr v-else v-for="(tutor, index) in cotutors1" :key="index">
                            <td><p class="text-sm mb-0"> {{ index + 1 }}  </p></td>
                            <td v-if="tutor.editing"><p class="text-sm mb-0"><input type="text" class= "form-control" v-model="tutor.name"></p></td>
                            <td v-else ><p class="text-sm mb-0"> {{ tutor.name }}  </p></td>
                            <td v-if="tutor.editing">
                              <p class="text-sm mb-0">
                                <select class="form-select" v-model="tutor.institution">
                                  <option value="">Select Institution</option>
                                  <option v-for="institution in universities" :value="institution.name">{{ institution.name }}</option>
                                </select>
                              </p>
                            </td>
                            <td v-else ><p class="text-sm mb-0"> {{ tutor.institution }}  </p></td>
                            <td><p class="text-sm mb-0">
                            <a v-if="!tutor.editing" class="btn btn-alert btn-xs" title="Edit" @click="editarDetalle(index)"><i class="fa fa-fw fa-edit"></i></a>
                            <a v-else class="btn btn-search-blue btn-xs" title="Confirm" @click="confirmarDetalle(index)"><i class="fa-solid fa-check"></i></a>
                            &nbsp;
                            <a class="btn btn-closed btn-xs" title="Delete" @click="eliminarDetalle(index)"><i class="fa fa-fw fa-trash"></i></a>
                            </p></td>
                          </tr>
                        </tbody>
                      </table>
                    </div>
                  </slot>
                </div>
                <div class="modal-footer">
                    <slot name="footer">
                        <button class="btn btn-continue float-end" @click="editCotutor()" :disabled="buttonDisable">
                            {{ buttonText }}
                        </button>
                    </slot>
                </div>
                <modalconfirmacion ref="confirmation"></modalconfirmacion>
                <modalalerta ref="alert"></modalalerta>
            </div>
          </div>
        </div>
      </div>
    </transition>
  </template>
  
  <script>
  import axios from 'axios'
  import modalconfirmacion from '../../../sistema/alerts/confirmationModal.vue'
  import modalalerta from '../../../sistema/alerts/alertModal.vue'
  import {mixin} from '../../../../../mixins.js'
  
  export default {
      components: { modalconfirmacion, modalalerta },
      mixins: [mixin],
      data: () => ({
          buttonText:'Edit',
          buttonDisable: false,
          errors:[],
      }),
      props:{
        cotutors1: Object,
      },
      methods: {
        eliminarDetalle(id){
          this.cotutors1.splice(id, 1);
        },
        confirmarDetalle(id){
          this.cotutors1[id].editing = false;
        },
        editarDetalle(id){
          this.cotutors1[id].editing = true;
        },
        capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        },
        editCotutor() {
          this.cotutors1.forEach(item => {
            item.editing = false;
          });
          const elem = this.$refs.closeBtn;
          elem.click();
        }
      }
  }
  </script>
  