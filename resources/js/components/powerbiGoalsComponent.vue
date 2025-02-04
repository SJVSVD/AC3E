<template>
  <div class="row mt-4 mx-4">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-body px-0 pt-4 pb-2" style="min-height: 400px;">
          <div class="container">
            <div class="row">
              
              <!-- FILTROS -->
              <div class="col-3">
                <div class="card shadow">
                  <div class="card-header text-white">
                    <h5 class="card-title text-center">Filters</h5>
                  </div>
                  <div class="card-body p-3">

                    <!-- Selector de Tipo de Filtro -->
                    <label class="form-label">Filter by:</label>
                    <select v-model="selectedFilterType" class="form-select mb-3" @change="resetFilters()">
                      <option value="" disabled>Select a filter type</option>
                      <option value="line">Research Line</option>
                      <option value="researcher">Researcher</option>
                      <option value="researchType">Researcher Type</option>
                    </select>

                    <!-- Selector de Línea, Investigador o Tipo de Investigador -->
                    <div v-if="selectedFilterType === 'line'">
                      <label class="form-label">Select a research line:</label>
                      <select v-model="selectedResearchLine" class="form-select mb-3">
                        <option :value="null" disabled>Select a line</option>
                        <option v-for="line in researchLines" :key="line.id" :value="line.id">
                          {{ line.name }}
                        </option>
                      </select>
                    </div>

                    <div v-if="selectedFilterType === 'researcher'">
                      <label class="form-label">Select a researcher:</label>
                      <select v-model="selectedPerson" class="form-select mb-3">
                        <option :value="null" disabled>Select a researcher</option>
                        <option v-for="person in allResearchers" :key="person.id" :value="person.id">
                          {{ person.name }}
                        </option>
                      </select>
                    </div>

                    <div v-if="selectedFilterType === 'researchType'">
                      <label class="form-label">Select researcher type:</label>
                      <select v-model="selectedResearchType" class="form-select mb-3">
                        <option :value="null" disabled>Select a type</option>
                        <option v-for="type in researcherTypes" :key="type.id" :value="type.id">
                          {{ type.name }}
                        </option>
                      </select>
                    </div>

                    <button class="btn btn-search-blue w-100 mt-3" @click="searchGoals">
                      Load Goals
                    </button>

                  </div>
                </div>
              </div>

              <!-- INPUTS DE METAS -->
              <div class="col-9">
                <div class="card">
                  <div class="card-body">
                    <label class="form-label">Power-BI Goals:</label>
                    <div v-if="actualProgressReport > 0">
                      <div v-for="(input, index) in dynamicInputs" :key="index" class="mb-2">
                        <label :for="`progressReport${input.progressReport}`">Progress report year {{ input.progressReport }}:</label>
                        <input
                          type="number"
                          class="form-control"
                          :id="`progressReport${input.progressReport}`"
                          v-model="input.goal"
                        />
                      </div>
                      <button class="btn btn-continue float-end mt-3" @click="saveProgressReports">
                        Save
                      </button>
                    </div>
                    <div v-else>
                      <p class="text-muted">No goals available.</p>
                    </div>
                  </div>
                </div>
              </div>
              <!-- FIN INPUTS -->

            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</template>

<script>
import axios from "axios";
import { ref, onMounted } from "vue";

export default {
  setup() {
    const selectedFilterType = ref("");
    const selectedResearchLine = ref(null);
    const selectedPerson = ref(null);
    const selectedResearchType = ref(null);

    const researchLines = ref([]);
    const allResearchers = ref([]);
    const researcherTypes = ref([]);

    const actualProgressReport = ref(0);
    const dynamicInputs = ref([]);

    // Obtener año actual del Progress Report
    const getProgressReportYear = async () => {
      try {
        const response = await axios.get("/api/showProgressReport");
        actualProgressReport.value = response.data;
      } catch (error) {
        console.error("Error fetching progress report year:", error);
      }
    };

    const resetFilters = () => {
      selectedResearchLine.value = null;
      selectedPerson.value = null;
      selectedResearchType.value = null;
    };

    const fetchResearchData = async () => {
      try {
        const response = await axios.get("/api/researchLines");
        researchLines.value = response.data.sort((a, b) => a.name.localeCompare(b.name)); // Ordenar por nombre
      } catch (error) {
        console.error("Error fetching research lines:", error);
      }
    };

    const fetchResearcherTypes = async () => {
      try {
        const response = await axios.get("/api/rolesUser");
        researcherTypes.value = response.data.sort((a, b) => a.name.localeCompare(b.name)); // Ordenar por nombre
      } catch (error) {
        console.error("Error fetching researcher types:", error);
      }
    };

    const fetchResearchers = async () => {
      try {
        const response = await axios.get("/api/getResearchers2");
        allResearchers.value = response.data.sort((a, b) => a.name.localeCompare(b.name)); // Ordenar por nombre
      } catch (error) {
        console.error("Error fetching researchers:", error);
      }
    };

    // Función para cargar o inicializar metas
    const searchGoals = async () => {
      let relatedId = null;
      let type = "";

      if (selectedFilterType.value === "line" && selectedResearchLine.value) {
        relatedId = selectedResearchLine.value;
        type = "ResearchLine";
      } else if (selectedFilterType.value === "researcher" && selectedPerson.value) {
        relatedId = selectedPerson.value;
        type = "Individual";
      } else if (selectedFilterType.value === "researchType" && selectedResearchType.value) {
        relatedId = selectedResearchType.value;
        type = "RoleUser";
      } else {
        console.warn("No filter selected.");
        return;
      }

      try {
        const response = await axios.get(`/api/getProgressReport/${relatedId}?type=${type}`);
        let storedGoals = response.data.data?.goals || {};

        if (typeof storedGoals === "string") {
          storedGoals = JSON.parse(storedGoals);
        }

        const sortedGoals = Object.keys(storedGoals)
          .map(year => ({
            progressReport: parseInt(year),
            goal: storedGoals[year],
          }))
          .sort((a, b) => a.progressReport - b.progressReport);

        // Si no hay datos, inicializar los inputs hasta el año actual
        if (sortedGoals.length === 0) {
          dynamicInputs.value = Array.from({ length: actualProgressReport.value }, (_, index) => ({
            progressReport: index + 1,
            goal: null,
          }));
        } else {
          dynamicInputs.value = sortedGoals;
        }
      } catch (error) {
        console.error("Error loading progress reports:", error);
      }
    };

    // Guardar metas
    const saveProgressReports = async () => {
      const payload = {
        type: selectedPerson.value ? "Individual" : selectedResearchLine.value ? "ResearchLine" : "RoleUser",
        related_id: selectedPerson.value || selectedResearchLine.value || selectedResearchType.value,
        goals: dynamicInputs.value,
      };

      console.log(payload);

      try {
        await axios.post("/api/saveProgressReport", payload);
        alert("Goals saved successfully!");
      } catch (error) {
        console.error("Error saving progress reports:", error);
        alert("Error saving goals.");
      }
    };

    onMounted(() => {
      getProgressReportYear();
      fetchResearchData();
      fetchResearchers();
      fetchResearcherTypes();
    });

    return {
      resetFilters,
      selectedFilterType,
      selectedResearchLine,
      selectedPerson,
      selectedResearchType,
      researchLines,
      allResearchers,
      researcherTypes,
      actualProgressReport,
      dynamicInputs,
      searchGoals,
      saveProgressReports,
    };
  },
};
</script>
