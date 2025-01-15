<template>
  <transition name="modalcrear">
    <div name="modal">
      <div class="modal-mask">
        <div class="modal-wrapper">
          <div class="modal-container-xl">
            <div class="modal-header pb-1 fw-bold" style="color: #444444;">
              <slot name="header">Recent Session Log</slot>
              <a class="btn btn-closed" @click="$emit('close')" ref="closeBtn">X</a>
            </div>
            <div class="modal-body">
              <slot name="body">
                <!-- Filtro de fechas -->
                <div class="d-flex mb-3">
                  <label for="startDate" class="me-2">Start Date:</label>
                  <input
                    type="date"
                    id="startDate"
                    v-model="startDate"
                    class="form-control me-3"
                  />
                  <label for="endDate" class="me-2">End Date:</label>
                  <input
                    type="date"
                    id="endDate"
                    v-model="endDate"
                    class="form-control me-3"
                  />
                  <button class="btn btn-search-blue" @click="applyFilter">Filter</button>
                </div>

                <!-- Mostrar símbolo de carga mientras se obtienen los registros -->
                <div
                  v-if="loading"
                  class="d-flex justify-content-center align-items-center"
                  style="height: 200px;"
                >
                  <i class="fa fa-spinner fa-spin fa-3x"></i>
                </div>

                <!-- Tabla para mostrar el registro de sesiones recientes -->
                <div
                  v-else
                  class="table-responsive"
                  style="max-height: 600px; overflow-y: auto;"
                >
                  <table class="table table-striped mb-0" style="font-size: 1rem;">
                    <thead>
                      <tr style="font-weight: bold;">
                        <th>User</th>
                        <th>Event</th>
                        <th>Hour</th>
                        <th>Description</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr
                        v-for="session in sessions"
                        :key="session.id"
                        style="height: 3rem;"
                      >
                        <td>{{ session.user.name }}</td>
                        <td>{{ capitalizeFirstLetter(session.event_type) }}</td>
                        <td>{{ formatTimestamp(session.timestamp) }}</td>
                        <td>{{ session.description }}</td>
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
import axios from "axios";
import modalconfirmacion from "../sistema/alerts/confirmationModal.vue";
import modalalerta from "../sistema/alerts/alertModal.vue";
import { mixin } from "../../../mixins.js";

export default {
  components: { modalconfirmacion, modalalerta },
  mixins: [mixin],
  data() {
    return {
      sessions: [], // Estado para almacenar las sesiones recientes obtenidas de la API
      loading: true, // Estado para manejar la visualización del símbolo de carga
      startDate: "", // Fecha inicial predeterminada (5 días atrás)
      endDate: "", // Fecha final predeterminada (hoy)
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
    fetchSessions(startDate, endDate) {
      this.loading = true; // Inicia el símbolo de carga
      axios
        .get("/api/session-logs", {
          params: {
            startDate,
            endDate,
          },
        })
        .then((response) => {
          this.sessions = response.data;
        })
        .catch((error) => {
          console.error("Error al obtener las sesiones recientes:", error);
        })
        .finally(() => {
          this.loading = false; // Oculta el símbolo de carga
        });
    },
    applyFilter() {
      // Valida que las fechas no estén vacías y aplica el filtro
      if (!this.startDate || !this.endDate) {
        alert("Please select both start and end dates.");
        return;
      }

      if (this.startDate > this.endDate) {
        alert("Start date cannot be after end date.");
        return;
      }

      this.fetchSessions(this.startDate, this.endDate);
    },
  },
  created() {
    // Llama a la función para obtener las sesiones al montar el modal
    this.fetchSessions(this.startDate, this.endDate);
  },
};
</script>
