<template>
  <div class="row mt-4 mx-4">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-body px-0 pt-0 pb-2" style="min-height: 400px;">
          <div class="row">
            
            <!-- FILTROS (3 de 12) -->
            <div class="col-3 p-4">
              <div class="card shadow">
                <div class="card-header text-white">
                  <h5 class="card-title text-center">Filters</h5>
                </div>
                <div class="card-body p-3">
                  
                  <!-- Selector de Tipo de Filtro -->
                  <label class="form-label">Filter By:</label>
                  <select v-model="selectedFilterType" class="form-select mb-3" @change="resetFilters">
                    <option value="" disabled>Select a type of filter</option>
                    <option value="line">Research Line</option>
                    <option value="researcher">Researcher</option>
                    <option value="researchType">Type of researcher</option>
                  </select>

                  <!-- Selector Dinámico de Línea, Investigador o Tipo de Investigador -->
                  <div v-if="selectedFilterType === 'line'">
                    <label class="form-label">Research Line:</label>
                    <select v-model="selectedResearchLine" class="form-select mb-3" @change="handleLineSelection">
                      <option :value="null" disabled>Select a research line</option>
                      <option v-for="line in researchLines" :key="line.id" :value="line.id">
                        {{ line.name }}
                      </option>
                    </select>
                  </div>

                  <div v-if="selectedFilterType === 'researcher'">
                    <label class="form-label">Reseacher:</label>
                    <select v-model="selectedPerson" class="form-select mb-3" @change="handlePersonSelection">
                      <option :value="null" disabled>Select a researcher</option>
                      <option v-for="person in allResearchers" :key="person.id" :value="person.id">
                        {{ person.name }}
                      </option>
                    </select>
                  </div>

                  <div v-if="selectedFilterType === 'researchType'">
                    <label class="form-label">Type of researcher:</label>
                    <select v-model="selectedResearchType" class="form-select mb-3">
                      <option :value="null" disabled>Select a type of researcher</option>
                      <option v-for="type in researcherTypes" :key="type" :value="type.id">
                        {{ type.name }}
                      </option>
                    </select>
                  </div>

                  <!-- Selector de Módulos (Opcional) -->
                  <label class="form-label">Filter by KPI:</label>
                  <select v-model="selectedModule" class="form-select mb-3">
                    <option :value="null" >Select a module</option>
                    <option v-for="module in modules" :key="module.name" :value="module.name">
                      {{ module.displayName }}
                    </option>
                  </select>

                  <button class="btn btn-search-blue w-100 mt-3" @click="applyFilters">
                    Apply Filters
                  </button>

                  <div class="card mt-3" v-if="filteredUsers.length > 0">
                    <div class="card-header text-white">
                      <h5 class="card-title text-center">Research Line Members</h5>
                    </div>
                    <ul class="list-group list-group-flush">
                      <li v-for="user in filteredUsers" :key="user.id" class="list-group-item">
                        {{ user.name }} - {{ user.idRole }}
                        
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>

            <!-- GRÁFICO (9 de 12) -->
            <div class="col-9 text-center p-4">
              <div class="chart-container">
                <div ref="chartRef" style="width: 100%; height: 100%;"></div>
              </div>

              <!-- Message if no data is available -->
              <div v-if="!loading && isEmptyData">
                <p class="text-muted">No data available for the selected filters.</p>
              </div>

              <!-- Error message -->
              <div v-if="errorMessage" class="alert alert-danger mt-3">
                <p>{{ errorMessage }}</p>
              </div>

              <!-- Show selected data if available -->
              <div v-if="selectedYearData && !loading">
                <p>{{ selectedYearData }}</p>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>
</template>


<script>
import { ref, onMounted } from "vue";
import * as echarts from "echarts";
import axios from "axios";

export default {
  setup() {
    const selectedFilterType = ref(""); // Tipo de filtro seleccionado
    const selectedResearchLine = ref(null);
    const selectedPerson = ref(null);
    const selectedResearchType = ref(null);
    const selectedModule = ref(null);
    const filteredUsers = ref([]);
    const researchLines = ref([]);
    const allResearchers = ref([]);
    const researcherTypes = ref([]);
    const goalsData = ref([]); // 📌 Almacenar los goals

    const modules = ref([
      { name: 'isiPublications', displayName: 'WoS Publications' },
      { name: 'nonIsiPublications', displayName: 'Non-WoS Publications' },
      { name: 'books', displayName: 'Books' },
      { name: 'awards', displayName: 'Awards' },
      { name: 'organizationsScEvents', displayName: 'Organizations SC Events' },
      { name: 'participationScEvents', displayName: 'Participation SC Events' },
      { name: 'scCollaborations', displayName: 'Scientific Collaborations' },
      { name: 'thesisStudents', displayName: 'Thesis Students' },
      { name: 'postDocs', displayName: 'Postdocs' },
      { name: 'outreachActivities', displayName: 'Outreach Activities' },
      { name: 'patents', displayName: 'Patents' },
      { name: 'publicPrivate', displayName: 'Public-Private Initiatives' },
      { name: 'technologyKnowledge', displayName: 'Technology Knowledge' },
      { name: 'fundingSources', displayName: 'Funding Sources' },
    ]);

    const chartRef = ref(null);
    let chartInstance = null;
    const loading = ref(false);

    // Inicializar el gráfico vacío
    const initChart = () => {
      if (chartRef.value) {
        chartInstance = echarts.init(chartRef.value);
        updateChartOptions([], [], []);
      }
    };

    // 📌 Función para actualizar los datos del gráfico con "goals"
    const updateChartOptions = (labels, data, goals) => {
      const options = {
        title: { text: "Records & Goals by Progress Report Year", left: "center" },
        tooltip: { trigger: "axis" },
        xAxis: { type: "category", data: labels },
        yAxis: { type: "value" },
        series: [
          {
            name: "Records",
            type: "line",
            data: data,
            lineStyle: { color: "#ed8d1d" }
          },
          {
            name: "Goals",
            type: "line",
            data: goals,
            lineStyle: { color: "red", width: 2 },
            itemStyle: { color: "red" }
          }
        ],
      };
      chartInstance.setOption(options);
    };

    const isEmptyData = ref(false);
    const errorMessage = ref("");
    const hasFiltered = ref(false); // Controla si ya se aplicó algún filtro

    // 📌 Modificar `applyFilters` para incluir "goals"
    const applyFilters = async () => {
      let params = {};

      if (selectedFilterType.value === "line" && selectedResearchLine.value) {
        params.line = selectedResearchLine.value;
      } else if (selectedFilterType.value === "researcher" && selectedPerson.value) {
        params.person = selectedPerson.value;
      } else if (selectedFilterType.value === "researchType" && selectedResearchType.value) {
        params.role = selectedResearchType.value;
      } else {
        console.warn("No filters selected.");
        return;
      }

      if (selectedModule.value) {
        params.module = selectedModule.value;
      }

      try {
        loading.value = true;
        errorMessage.value = "";
        isEmptyData.value = false;
        hasFiltered.value = true; // Marca que se aplicó un filtro

        // 📌 Llamar a la API que ahora devuelve también los "goals"
        const response = await axios.get("/api/getFilteredRecordsByModule", { params });
        const records = response.data.recordsByYear;
        filteredUsers.value = response.data.filteredUsers || [];
        goalsData.value = response.data.goals || {}; // 📌 Guardar los goals

        // Si no hay datos, mostrar mensaje y ocultar el gráfico
        if (!records || Object.keys(records).length === 0) {
          isEmptyData.value = true;
          updateChartOptions([], [], []);
          return;
        }

        // Convertir datos en arrays ordenados
        const labels = Object.keys(records).map((key) => `Year ${key}`);
        const values = Object.values(records);
        const goals = labels.map((year) => goalsData.value[year.replace("Year ", "")] || null);

        updateChartOptions(labels, values, goals);
        isEmptyData.value = false;
        loading.value = false;
      } catch (error) {
        console.error("Error fetching filtered data:", error);
        errorMessage.value = "An error occurred while retrieving the data. Please try again.";
        loading.value = false;
        isEmptyData.value = false;
        hasFiltered.value = true;
      }
    };

    // Resetear filtros al cambiar de tipo de filtro
    const resetFilters = () => {
      selectedResearchLine.value = null;
      selectedPerson.value = null;
      selectedResearchType.value = null;
    };

    // Cargar datos de líneas de investigación y miembros
    const fetchResearchLines = async () => {
      try {
        const response = await axios.get("/api/researchLines");
        researchLines.value = response.data;
      } catch (error) {
        console.error("Error fetching research lines:", error);
      }
    };

    // Cargar datos de roles
    const fetchResearcherTypes = async () => {
      try {
        const response = await axios.get("/api/rolesUser");
        researcherTypes.value = response.data;
      } catch (error) {
        console.error("Error fetching researcher types:", error);
      }
    };

    // Cargar datos de usuarios
    const fetchAllResearchers = async () => {
      try {
        const response = await axios.get("/api/getResearchers2");
        allResearchers.value = response.data;
        allResearchers.value.sort((a, b) => {
          if (a.name < b.name) return -1;  // Si 'a' es antes que 'b', retornar -1
          if (a.name > b.name) return 1;   // Si 'a' es después que 'b', retornar 1
          return 0; // Si son iguales
        });
      } catch (error) {
        console.error("Error fetching researchers:", error);
      }
    };

    onMounted(async () => {
      initChart();
      await fetchResearcherTypes();
      await fetchResearchLines();
      await fetchAllResearchers();
    });

    return {
      selectedFilterType,
      selectedResearchLine,
      selectedPerson,
      selectedResearchType,
      selectedModule,
      modules,
      researchLines,
      allResearchers,
      researcherTypes,
      filteredUsers,
      applyFilters,
      chartRef,
      loading,
      resetFilters,
      goalsData, // 📌 Agregar goals a las variables reactivas
    };
  },
};
</script>


<style scoped>
.chart-container {
  height: 400px;
  width: 100%;
}
/* Estilos para la lista */
.module-item {
  transition: all 0.3s ease;
  border: 1px solid #e0e0e0;
  padding: 10px;
  border-radius: 10px;
  cursor: pointer;
}

/* Hover con animación */
.module-item:hover {
  background-color: #dfdfe2;
  transform: translateY(-2px);
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

/* Estado activo */
.active-module {
  background-color: #ed8d1d;
  color: white;
  border-color: #ed8d1d;
  transform: scale(1.02);
  box-shadow: 0 4px 10px rgba(59, 130, 246, 0.3);
}

/* Hover para módulo activo */
.active-module:hover {
  background-color: #fa9017;
}

/* Ícono animado */
.animate__fadeIn {
  animation: fadeIn 0.5s;
}

</style>