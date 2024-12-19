<template>
  <div class="row mt-4 mx-4">
    <div class="col-12">
      <div class="card mb-4">
        <div class="row pb-0 p-4">
          <div class="col-md-12 col-12 mb-2">
          </div>
        </div>
        <div class="card-body px-0 pt-0 pb-2" style="min-height: 400px;">
          <div class="row">
            <!-- Lista de líneas de investigación -->
            <div class="col-4 p-4">
              <div v-for="line in researchLines" :key="line.name" class="mb-4">
                <div class="card">
                  <div class="card-header d-flex justify-content-between align-items-center">
                    <div class="form-check pt-2">
                      <label class="form-check-label">
                        <input
                          type="checkbox"
                          class="form-check-input"
                          :checked="selectedResearchLine === line.name"
                          @change="handleLineSelection(line.name)"
                        />
                        {{ line.name }}
                      </label>
                    </div>
                    <button
                      class="btn btn-sm btn-link"
                      @click="toggleExpand(line.name)"
                    >
                      <i v-if="expandedLines.includes(line.name)" class="fa-solid fa-chevron-up"></i>
                      <i v-else class="fa-solid fa-chevron-down"></i>
                    </button>
                  </div>
                  <div v-if="expandedLines.includes(line.name)" class="card-body">
                    <ul class="list-group list-group-flush">
                      <li v-for="person in line.members" :key="person.id" class="list-group-item">
                        <div class="form-check pt-2">
                          <label class="form-check-label">
                            <input
                              type="checkbox"
                              class="form-check-input"
                              :id="`person-${person.id}`"
                              :checked="selectedPerson === person.id"
                              @change="handlePersonSelection(person.id)"
                            />
                            {{ person.name }}
                          </label>
                        </div>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
            </div>

            <div class="col-4 p-4">
              <div class="card shadow">
                <div class="card-header text-white">
                  <h5 class="card-title text-center">Filter by Module</h5>
                </div>
                <div class="card-body p-3">
                  <ul class="list-group">
                    <li 
                      v-for="module in modules" 
                      :key="module.name" 
                      class="list-group-item d-flex justify-content-between align-items-center module-item"
                      :class="{ 'active-module': selectedModule === module.name }"
                      @click="selectModule(module.name)"
                    >
                      {{ module.displayName }}
                      <i 
                        v-if="selectedModule === module.name" 
                        class="fa-solid fa-check text-success animate__animated animate__fadeIn"
                      ></i>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            <div class="col-4 text-center p-4">
              <div class="chart-container">
                <div ref="chartRef" style="width: 100%; height: 100%;"></div>
              </div>
              <div v-if="selectedYearData && !loading">
                <p>{{ selectedYearData }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <modalconfirmacion ref="confirmation"></modalconfirmacion>
      <modalalerta ref="alert"></modalalerta>
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

    const selectedModule = ref(null);
    const toast = useToast();

    const showErrorToast = (message) => {
      toast.error(message, {
        position: "top-right",
        timeout: 3000,
      });
    };

    const selectModule = async (moduleName) => {
      if (!selectedResearchLine.value && !selectedPerson.value) {
        showErrorToast("No research line or person selected. Cannot apply module filter.");
        return;
      }
      if (selectedModule.value === moduleName) {
        selectedModule.value = null;
        chartConfig.value.labels = [];
        chartConfig.value.datasets[0].data = [];
        updateChartOptions();
      } else {
        selectedModule.value = moduleName;

        try {
          loading.value = true;

                  // Construir los parámetros para la API
        const params = {
          module: moduleName,
        };

        if (selectedResearchLine.value) {
          params.line = selectedResearchLine.value; // Agregar línea seleccionada si existe
        }

        if (selectedPerson.value) {
          params.person = selectedPerson.value; // Agregar persona seleccionada si existe
        }

        console.log(params);
          const response = await axios.get('/api/getFilteredRecordsByModule', {
            params: params,
          });
          const records = response.data.recordsByYear;

          // Extraer datos
          const labels = Object.keys(records).map((key) => `Year ${key}`);
          const data = Object.values(records);

          // Actualizar gráfico
          updateChartOptions(labels, data);
          loading.value = false;
        } catch (error) {
          console.error('Error fetching records for module:', error);
          loading.value = false;
        }
      }
    };

    const chartRef = ref(null); // Referencia al div del gráfico
    let chartInstance = null;   // Instancia de ECharts
    const loading = ref(false);
    const selectedResearchLine = ref(null);

    // Función para inicializar el gráfico
    const initChart = () => {
      if (chartRef.value) {
        chartInstance = echarts.init(chartRef.value);
        updateChartOptions([], []); // Inicializar con datos vacíos
      }
    };


    // Función para actualizar las opciones del gráfico
    const updateChartOptions = (labels, data) => {
      const options = {
        title: { text: "Records by Progress Report", left: "center" },
        tooltip: {
          trigger: "axis",
          formatter: "{b}: {c} Records", // Mostrar año y cantidad
        },
        xAxis: {
          type: "category",
          data: labels, // Etiquetas de eje X (años)
          nameLocation: "middle",
          nameGap: 25,
          axisLabel: { rotate: 45 },
        },
        yAxis: {
          type: "value",
          name: "Record Count",
          nameLocation: "middle",
          nameGap: 35,
          minInterval: 1,
        },
        series: [
          {
            name: "Records",
            type: "line",
            data: data, // Datos del eje Y
            smooth: true,
            lineStyle: { color: "#ed8d1d" },
            itemStyle: { color: "#666666" },
          },
        ],
      };
      chartInstance.setOption(options);
    };

    // Función para obtener los datos generales
    const fetchGeneralRecords = async () => {
      try {
        loading.value = true;
        const response = await axios.get("/api/getGeneralRecordsByProgressReport");
        const records = response.data.recordsByYear;

        // Extraer datos
        const labels = Object.keys(records).map((key) => `Year ${key}`);
        const data = Object.values(records);

        // Actualizar gráfico
        updateChartOptions(labels, data);
        loading.value = false;
      } catch (error) {
        console.error("Error fetching general records:", error);
        loading.value = false;
      }
    };

    // Función para manejar la selección de una línea de investigación
    const handleLineSelection = async (lineName) => {
      if (selectedResearchLine.value === lineName) {
        // Deseleccionar y limpiar el gráfico
        selectedPerson.value = null;
        selectedResearchLine.value = null;
        updateChartOptions([], []);
      } else {
        selectedResearchLine.value = lineName;

        try {
          loading.value = true;
          const response = await axios.get(`/api/getPublicationsByResearchLine/${lineName}`);
          const data = response.data.recordsByYear;

          // Procesar los datos para el gráfico
          const labels = Object.keys(data).map((key) => `Year ${key}`);
          const values = Object.values(data);
          updateChartOptions(labels, values);
          loading.value = false;
        } catch (error) {
          console.error("Error fetching publications for line:", error);
          loading.value = false;
        }
      }
    };

    const handlePersonSelection = async (personId) => {
      if (selectedPerson.value === personId) {
        // Deseleccionar y limpiar el gráfico
        selectedResearchLine.value = null;
        selectedPerson.value = null;
        updateChartOptions([], []);
      } else {
        selectedPerson.value = personId;

        try {
          loading.value = true;

          // Llamada a la API para obtener los registros de la persona
          const response = await axios.get(`/api/getPersonRecordsByProgressReport/${personId}`);
          const data = response.data.recordsByYear;

          console.log("Fetched data for person:", data);

          // Procesar los datos para el gráfico
          const labels = Object.keys(data).map((key) => `Report ${key}`);
          const values = Object.values(data);

          updateChartOptions(labels, values);
          loading.value = false;
        } catch (error) {
          console.error("Error fetching records for person:", error);
          loading.value = false;
        }
      }
    };

    
    const selectedPerson = ref(null);
    const selectedYearData = ref('');

    const researchLines = ref([]);
    const expandedLines = ref([]);

    const toggleExpand = (lineName) => {
      const index = expandedLines.value.indexOf(lineName);
      if (index === -1) {
        expandedLines.value.push(lineName);
      } else {
        expandedLines.value.splice(index, 1);
      }
    };


    const fetchResearchLinesAndMembers = async () => {
      try {
        const response = await axios.get('/api/getUsersWithResearch');
        const data = Array.isArray(response.data) ? response.data : Object.values(response.data);

        const groupedLines = {};
        data.forEach((user) => {
          const lineName = user.research_line.name;
          if (!groupedLines[lineName]) {
            groupedLines[lineName] = [];
          }
          groupedLines[lineName].push({
            id: user.id,
            name: user.name,
          });
        });

        researchLines.value = Object.keys(groupedLines).map((lineName) => ({
          name: lineName,
          members: groupedLines[lineName].sort((a, b) => a.name.localeCompare(b.name)),
        }));
      } catch (error) {
        console.error('Error fetching research lines and members:', error);
      }
    };

  onMounted(async () => {
    initChart();
    await fetchGeneralRecords();
    fetchResearchLinesAndMembers(); // Cargar datos adicionales
  });



    return {
      chartRef,
      selectedYearData,
      loading,
      researchLines,
      expandedLines,
      selectedResearchLine,
      selectedPerson,
      toggleExpand,
      handleLineSelection,
      modules,
      selectedModule,
      selectModule,
      handlePersonSelection
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