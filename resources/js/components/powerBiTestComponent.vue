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
          <div class="card mt-3" v-if="filteredUsers.length > 0">
            <div class="card-header text-white">
              <h5 class="card-title text-center">{{ headerTitle }}</h5>
            </div>
            <div class="card-body">
              <div class="row">
                <div 
                  v-for="user in filteredUsers" 
                  :key="user.id" 
                  class="col-md-6 col-lg-4 mb-2"
                >
                  <div class="list-group-item">
                    {{ user.name }} - {{ user.idRole }}
                  </div>
                </div>
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
import { useToast } from "vue-toastification";

export default {
  setup() {
    const selectedFilterType = ref(""); // Tipo de filtro seleccionado
    const headerTitle = ref(""); // Tipo de filtro seleccionado
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

    const updateChartOptions = (labels, data, goals) => {
      const options = {
        title: { text: "Records & Goals by Progress Report Year", left: "center" },
        tooltip: {
          trigger: "axis",
          axisPointer: {
            type: "line", // Cambié el tipo a "line" para que sea más preciso
            lineStyle: {
              color: "#999",  // Color de la línea del puntero
              width: 1,       // Grosor de la línea
              type: "solid"   // Tipo de línea sólida para mayor visibilidad
            },
            // Hacer más preciso el puntero
            snap: true, // Esto asegura que el puntero se alinee exactamente con los puntos de datos
          },
          confine: true, // Para evitar que el tooltip se desborde
          // Mejorar la alineación del tooltip para evitar desplazamientos extraños
          position: function (point, params, dom, rect, size) {
            return [point[0], point[1] + 10]; // Alineamos el tooltip un poco hacia abajo
          },
        },
        xAxis: {
          type: "category",
          data: labels,
          axisPointer: { // Mejorar la interacción en el eje X
            type: "shadow",
          }
        },
        yAxis: {
          type: "value",
          axisPointer: {
            type: "shadow", // Asegura que el puntero también se active en el eje Y
          }
        },
        series: [
          {
            name: "Records",
            type: "line",
            data: data,
            lineStyle: { color: "#ed8d1d" },
            // Esto asegura que el punto se destaque cuando el puntero esté sobre él
            emphasis: {
              focus: "series"
            }
          },
          {
            name: "Goals",
            type: "line",
            data: goals,
            lineStyle: { color: "red", width: 2 },
            itemStyle: { color: "red" },
            emphasis: {
              focus: "series"
            }
          }
        ],
      };

      chartInstance.setOption(options);
    };



    const isEmptyData = ref(false);
    const errorMessage = ref("");
    const hasFiltered = ref(false); // Controla si ya se aplicó algún filtro

    const applyFilters = async () => {
      // Actualizar el encabezado según el filtro seleccionado
      if (selectedFilterType.value === "line") {
        headerTitle.value = "Research Line Members";
      } else if (selectedFilterType.value === "researchType") {
        if (selectedResearchType.value === 1) {
          headerTitle.value = "Associative Researchers";
        } else if (selectedResearchType.value === 2) {
          headerTitle.value = "Postdoctoral Researchers";
        } else if (selectedResearchType.value === 3) {
          headerTitle.value = "Main Researchers";
        }
      } else if (selectedFilterType.value === "researcher") {
        headerTitle.value = "Researcher Details";
      }

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

      // Limpia el gráfico y resetea las variables antes de hacer la llamada
      updateChartOptions([], [], []); // Limpiar gráfico antes de obtener nuevos datos
      filteredUsers.value = [];
      goalsData.value = {};
      isEmptyData.value = false;
      loading.value = true;
      errorMessage.value = "";
      hasFiltered.value = false;

      try {
        const response = await axios.get("/api/getFilteredRecordsByModule", { params });
        const records = response.data.recordsByYear;
        filteredUsers.value = response.data.filteredUsers || [];
        goalsData.value = response.data.goals || {};

        // Si no hay datos, mostrar mensaje y ocultar el gráfico
        if (!records || Object.keys(records).length === 0) {
          isEmptyData.value = true;
          loading.value = false;
          return; // No continuar, ya limpiamos el gráfico
        }

        // Utilizar el currentProgressReport de la API (el año actual según extraTables)
        const currentYear = Number(response.data.currentProgressReport);

        // Crear un arreglo con todos los años desde 0 hasta currentYear (inclusivo)
        const allYears = Array.from({ length: currentYear + 1 }, (_, i) => i.toString());
        const labels = allYears.map(year => `Year ${year}`);
        const values = allYears.map(year => records[year] || 0);
        const goals = allYears.map(year => goalsData.value[year] || null);

        // Actualizar gráfico con los nuevos datos
        updateChartOptions(labels, values, goals);
        isEmptyData.value = false;
        loading.value = false;
      } catch (error) {
        const toast = useToast();

        console.error("Error fetching filtered data:", error);
        toast.warning("Error fetching filtered data: Goals not assigned due to an error.");
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

        // Filtrar las líneas de investigación que sean undefined o null
        researchLines.value = response.data.filter(line => line.name !== "Undefined" && line.name !== null);
      } catch (error) {
        console.error("Error fetching research lines:", error);
      }
    };


    // Cargar datos de roles
    const fetchResearcherTypes = async () => {
      try {
        const response = await axios.get("/api/rolesUser");
        researcherTypes.value = response.data.filter(line => line.name !== "Administrator" && line.name !== "Inactive");
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
      headerTitle,
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