<template>
  <transition name="modal-create">
    <div name="modal">
      <div class="modal-mask">
        <div class="modal-wrapper">
          <div class="modal-container-s">
            <!-- Modal Header -->
            <div class="modal-header pb-1 fw-bold" style="color: #444444;">
              <slot name="header">Lift Mode</slot>
              <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
              <slot name="body">
                <div v-if="activeAnnouncement1" class="current-announcement mb-4 p-3 border rounded">
                  <h5>Current Announcement</h5>
                  <p class="mb-1">
                    <strong>Message:</strong> {{ activeAnnouncement1.message }}
                  </p>
                  <p class="mb-0">
                    <strong>Active Period:</strong> 
                    {{ formatDate(activeAnnouncement1.start_time) }} - {{ formatDate(activeAnnouncement1.end_time) }}
                  </p>
                  <button class="btn btn-danger mt-3" @click="deleteAnnouncement">Delete Current Announcement</button>
                </div>

                <!-- Form to Create or Update Announcement -->
                <form @submit.prevent="submitForm">
                  <!-- Alert Message Field -->
                  <div class="form-group mb-3">
                    <label for="message" class="form-label">Alert Message</label>
                    <textarea
                      id="message"
                      class="form-control"
                      v-model="announcement.message"
                      placeholder="Write the alert message"
                      rows="3"
                      required
                    ></textarea>
                  </div>

                  <!-- Start Date and Time -->
                  <div class="form-group mb-3">
                    <label for="start_time" class="form-label">Start Date and Time</label>
                    <input
                      type="datetime-local"
                      id="start_time"
                      class="form-control"
                      v-model="announcement.start_time"
                      required
                    />
                  </div>

                  <!-- End Date and Time -->
                  <div class="form-group mb-3">
                    <label for="end_time" class="form-label">End Date and Time</label>
                    <input
                      type="datetime-local"
                      id="end_time"
                      class="form-control"
                      v-model="announcement.end_time"
                      required
                    />
                  </div>

                  <!-- Buttons -->
                  <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-continue me-2">Save</button>
                    <button type="button" class="btn btn-secondary" @click="$emit('close')">Cancel</button>
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
      activeAnnouncement1: null,
      announcement: {
        message: '',
        start_time: '',
        end_time: '',
      },
    };
  },
  created(){
    this.fetchActiveAnnouncement()
  },
  methods: {
    formatDate(datetime) {
      return new Date(datetime).toLocaleString();
    },
    async fetchActiveAnnouncement() {
      const toast = useToast();

      try {
        const { data } = await axios.get('/api/active-announcement');
        if (data.message) {
          this.activeAnnouncement1 = data;
          toast.info('There is an active announcement!');
        } else {
          this.activeAnnouncement1 = null;
        }
      } catch (error) {
        console.error('Error fetching the active announcement:', error);
        toast.error('Unable to fetch the announcement status.');
      }
    },
    validateForm() {
      const errors = [];

      if (!this.announcement.message.trim()) {
        errors.push('The alert message is required.');
      }

      if (!this.announcement.start_time) {
        errors.push('Start date and time are required.');
      }

      if (!this.announcement.end_time) {
        errors.push('End date and time are required.');
      } else if (
        new Date(this.announcement.start_time) >= new Date(this.announcement.end_time)
      ) {
        errors.push('Start date must be earlier than the end date.');
      }

      return errors;
    },

    async submitForm() {
      const toast = useToast();
      const errors = this.validateForm();

      if (errors.length > 0) {
        errors.forEach(error => toast.error(error));
        return;
      }

      try {
        await axios.post('/api/announcements', this.announcement);
        toast.success('Announcement successfully created.');
        this.$emit('close'); // Close the modal
      } catch (error) {
        toast.error('There was an issue saving the announcement.');
        console.error('Error saving the announcement:', error.response.data);
      }
    },
    async deleteAnnouncement() {
      const toast = useToast();
      try {
        // Make a DELETE request to the backend to delete the active announcement
        await axios.delete('/api/announcements/' + this.activeAnnouncement1.id);

        // On success, show a success message
        toast.success('Announcement deleted successfully.');

        // Reset the activeAnnouncement1 to null
        this.activeAnnouncement1 = null;
      } catch (error) {
        // Handle error
        toast.error('Error deleting the announcement.');
        console.error('Error deleting announcement:', error.response.data);
      }
    },
  },
};
</script>
