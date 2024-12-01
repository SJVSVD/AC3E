<template>
    <transition name="modalcrear">
      <div name="modal">
        <div class="modal-mask">
          <div class="modal-wrapper">
            <div class="modal-container-s">
              <div class="modal-header pb-1 fw-bold" style="color: #444444;">
                <slot name="header">Recent Session Log</slot>
                <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
              </div>
              <div class="modal-body">
                <slot name="body">
                  <!-- Mostrar símbolo de carga mientras se obtienen los registros -->
                  <div v-if="loading" class="d-flex justify-content-center align-items-center" style="height: 200px;">
                    <i class="fa fa-spinner fa-spin fa-3x"></i> <!-- Ícono de carga -->
                  </div>
                  <!-- Tabla para mostrar el registro de sesiones recientes -->
                  <div v-else class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                    <table class="table table-striped mb-0" style="font-size: 1rem;">
                      <thead>
                        <tr style="font-weight: bold;">
                          <th>User</th>
                          <th>Event</th>
                          <th>Hour</th>
                          <th>IP</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="session in sessions" :key="session.id" style="height: 3rem;">
                          <td>{{ session.user.name }}</td>
                          <td>{{ capitalizeFirstLetter(session.event_type) }}</td>
                          <td>{{ formatTimestamp(session.timestamp) }}</td>
                          <td>{{ session.ip_address }}</td>
                        </tr>
                      </tbody>
                    </table>
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
  import modalconfirmacion from '../sistema/alerts/confirmationModal.vue';
  import modalalerta from '../sistema/alerts/alertModal.vue';
  import { mixin } from '../../../mixins.js';
  
  export default {
    components: { modalconfirmacion, modalalerta },
    mixins: [mixin],
    data() {
      return {
        sessions: [], // Estado para almacenar las sesiones recientes obtenidas de la API
        loading: true // Estado para manejar la visualización del símbolo de carga
      };
    },
    methods: {
      capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
      },
      // Formatea el timestamp para mostrarlo en formato legible
      formatTimestamp(timestamp) {
        return new Date(timestamp).toLocaleString();
      },
      // Llama a la API para obtener el registro de sesiones recientes
      fetchSessions() {
        this.loading = true; // Inicia el símbolo de carga
        axios
          .get('/api/session-logs') // Ajusta el endpoint según tu configuración
          .then(response => {
            this.sessions = response.data;
          })
          .catch(error => {
            console.error('Error al obtener las sesiones recientes:', error);
          })
          .finally(() => {
            this.loading = false; // Oculta el símbolo de carga
          });
      }
    },
    created() {
      // Llama a la función para obtener las sesiones al montar el modal
      this.fetchSessions();
    }
  };
  </script>
  