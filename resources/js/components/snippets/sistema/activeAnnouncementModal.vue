<template>
    <transition name="modal-create">
      <div name="modal" v-if="activeAnnouncement1">
        <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container-s">
              <!-- Modal Header -->
              <div class="modal-header pb-1 fw-bold" style="color: #444444;">
                <slot name="header">
                  Important Announcement
                </slot>
                <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
              </div>
  
              <!-- Modal Body -->
              <div class="modal-body">
                <slot name="body">
                  <div>
                    <!-- Show the announcement dynamically -->
                    <p> {{ activeAnnouncement1.message }}</p>
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
  import axios from 'axios';
  import { useToast } from 'vue-toastification';
  
  export default {
    data() {
      return {
        announcement: {
          message: '',
          start_time: '',
          end_time: '',
        },
      };
    },
    props:{
        activeAnnouncement1: Object,
    },
    methods: {
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
    },
  };
  </script>
  