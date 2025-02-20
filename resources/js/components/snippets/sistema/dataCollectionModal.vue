<template>
  <transition name="modal-create">
    <div name="modal">
      <div class="modal-mask">
        <div class="modal-wrapper">
          <div class="modal-container-s">
            <!-- Modal Header -->
            <div class="modal-header pb-1 fw-bold" style="color: #444444;">
              <slot name="header">Performance Data Collection Mode</slot>
              <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
              <slot name="body">
                <div v-if="activeMode" class="current-announcement mb-4 p-3 border rounded">
                  <h5>Current Active Mode</h5>
                  <p class="mb-1">
                    <strong>Status:</strong> {{ activeMode.mode.is_active ? 'Active' : 'Inactive' }}
                  </p>
                  <p class="mb-0">
                    <strong>Deadline:</strong> {{ formatDate(activeMode.mode.deadline) }}
                  </p>
                  <button class="btn btn-danger mt-3" @click="disableMode">Disable Mode</button>
                </div>

                <!-- Form to Enable Mode -->
                <form @submit.prevent="submitForm">
                  <!-- End Date and Time -->
                  <div class="form-group mb-3">
                    <label for="end_time" class="form-label">End Date and Time</label>
                    <input
                      type="datetime-local"
                      id="end_time"
                      class="form-control"
                      v-model="mode.deadline"
                      required
                    />
                  </div>

                  <!-- Buttons -->
                  <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-continue me-2">Enable Mode</button>
                  </div>
                </form>
              </slot>
            </div>
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
  data() {
    return {
      activeMode: null,
      mode: {
        deadline: '',
      },
    };
  },
  created(){
    this.fetchActiveMode();
  },
  methods: {
    formatDate(datetime) {
      return new Date(datetime).toLocaleString();
    },
    async fetchActiveMode() {
      const toast = useToast();
      try {
        const { data } = await axios.get('/api/performance-mode');
        if (data.mode.is_active) {
          this.activeMode = data;
          toast.info('Performance Data Collection Mode is active!');
        } else {
          this.activeMode = null;
        }
      } catch (error) {
        console.error('Error fetching the mode status:', error);
        toast.error('Unable to fetch the mode status.');
      }
    },
    async submitForm() {
      const toast = useToast();
      try {
        await axios.post('/api/performance-mode', this.mode);
        toast.success('Performance Data Collection Mode enabled successfully.');
        this.$emit('close'); // Close the modal
        window.location.reload();
      } catch (error) {
        toast.error('There was an issue enabling the mode.');
        console.error('Error enabling the mode:', error.response.data);
      }
    },
    async disableMode() {
      const toast = useToast();
      try {
        await axios.delete('/api/performance-mode');
        toast.success('Performance Data Collection Mode disabled successfully.');
        this.activeMode = null;
        window.location.reload();
      } catch (error) {
        toast.error('Error disabling the mode.');
        console.error('Error disabling the mode:', error.response.data);
      }
    },
  },
};
</script>
