<template>
  <transition name="modal-create">
    <div name="modal">
      <div class="modal-mask">
        <div class="modal-wrapper">
          <div class="modal-container-xs">
            <!-- Modal Header -->
            <div class="modal-header pb-1 fw-bold" style="color: #444444;">
              <slot name="header">Notify Data Collection as Complete</slot>
              <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
              <slot name="body">
                <p>Are you sure you want to notify that you have finished uploading your information for the current period?”</p>
              </slot>
            </div>

            <div class="modal-footer d-flex justify-content-end">
              <button class="btn btn-continue" @click="notifyData">Notify</button>
              <button class="btn btn-secondary me-2" @click="$emit('close')">Cancel</button>
            </div>
            <!-- Modal Footer -->
          </div>
        </div>
      </div>
    </div>
  </transition>
</template>


<script>
import axios from 'axios';
import { useToast } from 'vue-toastification';

export default {
  props:{
    iduser: Object,
  },
  data() {
    return {
      activeMode: null,
      mode: {
        deadline: '',
      },
    };
  },
  methods: {
    async notifyData() {
      const toast = useToast();
      try {
        await axios.get(`/api/performance-mode/notify?user_id=${this.iduser}`); 
        toast.success('You have successfully notified your data.');
        this.$emit('close'); // Cierra el modal después de notificar
        window.location.reload();
      } catch (error) {
        toast.error('There was an issue notifying your data.');
        console.error('Error notifying data:', error.response?.data || error);
      }
    },
  },
};
</script>
